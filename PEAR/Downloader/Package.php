<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Gregory Beaver <cellog@php.net>                             |
// +----------------------------------------------------------------------+
//
// $Id$
require_once 'Archive/Tar.php';
require_once 'PEAR/Dependency2.php';
/**
 * Coordinates download parameters and manages their dependencies
 * prior to downloading them.
 *
 * Input can come from three sources:
 *
 * - local files (archives or package.xml)
 * - remote files (downloadable urls)
 * - abstract package names
 *
 * The first two elements are handled cleanly by PEAR_PackageFile, but the third requires
 * accessing pearweb's xml-rpc interface to determine necessary dependencies, and the
 * format returned of dependencies is slightly different from that used in package.xml.
 *
 * This class hides the differences between these elements, and makes automatic
 * dependency resolution a piece of cake.  It also manages conflicts when
 * two classes depend on incompatible dependencies, or differing versions of the same
 * package dependency.  In addition, download will not be attempted if the php version is
 * not supported, PEAR installer version is not supported, or non-PECL extensions are not
 * installed.
 * @todo implement all that automatic resolution and the actual downloading.
 * @package PEAR
 */
class PEAR_Downloader_Package
{
    /**
     * @var PEAR_Downloader
     */
    var $_downloader;
    /**
     * @var PEAR_Config
     */
    var $_config;
    /**
     * @var PEAR_Registry
     */
    var $_registry;
    /**
     * @var PEAR_PackageFile_v1|PEAR_PackageFile|v2
     */
    var $_packagefile;
    /**
     * @var array
     */
    var $_parsedname;
    /**
     * @var array
     */
    var $_downloadURL;
    /**
     * @var array
     */
    var $_downloadDeps = array();
    /**
     * @var boolean
     */
    var $_valid = false;
    /**
     * @var boolean
     */
    var $_analyzed = false;
    /**
     * Package type local|url|xmlrpc
     * @var string
     */
    var $_type;

    /**
     * @param PEAR_Config
     */
    function PEAR_Downloader_Package(&$downloader)
    {
        $this->_downloader = &$downloader;
        $this->_config = &$this->_downloader->config;
        $this->_registry = &$this->_config->getRegistry();
        $this->_valid = $this->_analyzed = false;
    }

    function initialize($param)
    {
        $origErr = $this->_fromFile($param);
        if (!$this->_valid) {
            $options = $this->_downloader->getOptions();
            if (isset($options['offline'])) {
                if (PEAR::isError($origErr)) {
                    $this->log(0, $origErr->getMessage());
                }
                return PEAR::raiseError('Cannot download non-local package "' . $param . '"');
            }
            $err = $this->_fromUrl($param);
            if (PEAR::isError($err) || !$this->_valid) {
                if ($this->_type == 'url') {
                    if (PEAR::isError($err)) {
                        $this->_downloader->log(0, $err->getMessage());
                    }
                    return PEAR::raiseError("Invalid package file");
                }
                $err = $this->_fromString($param);
                if (PEAR::isError($err) || !$this->_valid) {
                    if (PEAR::isError($err)) {
                        $this->_downloader->log(0, $err->getMessage());
                    }
                    if (PEAR::isError($origErr)) {
                        if (is_array($origErr->getUserInfo())) {
                            foreach ($origErr->getUserInfo() as $err) {
                                if (is_array($err)) {
                                    $err = $err['message'];
                                }
                                $this->_downloader->log(0, $err);
                            }
                        }
                        $this->_downloader->log(0, $origErr->getMessage());
                    }
                    return PEAR::raiseError(
                        "Cannot initialize '$param', invalid or missing package file");
                }
            }
        }
    }

    function &download()
    {
        if (isset($this->_packagefile)) {
            return $this->_packagefile;
        }
        if (isset($this->_downloadURL['url'])) {
            $this->_isvalid = false;
            $err = $this->_fromUrl($this->_downloadURL['url'],
                $this->_registry->parsedPackageNameToString($this->_parsedname));
            if (PEAR::isError($err) || !$this->_valid) {
                return $err;
            }
        }
        $this->_type = 'local';
        return $this->_packagefile;
    }

    function isAnalyzed()
    {
        return $this->_analyzed;
    }

    function setAnalyzed()
    {
        return $this->_analyzed;
    }

    function &getPackageFile()
    {
        return $this->_packagefile;
    }

    function getType() 
    {
        return $this->_type;
    }

    function fromDepURL($dep)
    {
        $this->_downloadURL = $dep;
        $this->_parsedname =
            array(
                'package' => $dep['info']['package'],
                'channel' => $dep['info']['channel'],
                'version' => $dep['version']
            );
        if (isset($dep['group'])) {
            $this->_parsedname['group'] = $dep['group'];
        }
    }

    function detectDependencies($params)
    {
        $options = $this->_downloader->getOptions();
        if (isset($options['offline'])) {
            $this->_downloader->log(3, 'Skipping dependency download check, --offline specified');
            return;
        }
        $pname = $this->getParsedPackage();
        if (!$pname) {
            return;
        }
        $deps = $this->getDeps();
        if (isset($deps['required'])) { // package.xml 2.0
            return $this->_detect2($deps, $pname, $options, $params);
        } else {
            return $this->_detect1($deps, $pname, $options, $params);
        }
    }

    function removeInstalled(&$params)
    {
        foreach ($params as $i => $param) {
            $options = $param->_downloader->getOptions();
            // remove self if already installed with this version
            if ($param->_registry->packageExists($param->getPackage(), $param->getChannel())) {
                if (version_compare($param->_registry->packageInfo($param->getPackage(), 'version',
                      $param->getChannel()), $param->getVersion(), '==')) {
                    if (!isset($options['force'])) {
                        $info = $param->getParsedPackage();
                        unset($info['version']);
                        unset($info['state']);
                        $param->_downloader->log(1, 'Skipping package "' .
                            $param->_registry->parsedPackageNameToString($info) .
                            '", already installed as version ' . $param->getVersion());
                        $params[$i] = false;
                    }
                }
            }
        }
        PEAR_Downloader_Package::removeDuplicates($params);
    }

    function _detect2($deps, $pname, $options, $params)
    {
        foreach (array('package', 'subpackage') as $packagetype) {
            // get required dependency group
            if (isset($deps['required'][$packagetype])) {
                if (isset($deps['required'][$packagetype][0])) {
                    foreach ($deps['required'][$packagetype] as $dep) {
                        if (isset($dep['conflicts'])) {
                            // skip any package that this package conflicts with
                            continue;
                        }
                        $ret = $this->_detect2Dep($dep, $pname, 'required', $params);
                        if (is_array($ret)) {
                            $this->_downloadDeps[] = $ret;
                        }
                    }
                } else {
                    $dep = $deps['required'][$packagetype];
                    if (!isset($dep['conflicts'])) {
                        // skip any package that this package conflicts with
                        $ret = $this->_detect2Dep($dep, $pname, 'required', $params);
                        if (is_array($ret)) {
                            $this->_downloadDeps[] = $ret;
                        }
                    }
                }
            }
            // get optional dependency group, if any
            if (isset($deps['optional'][$packagetype])) {
                $skipnames = array();
                if (!isset($deps['optional'][$packagetype][0])) {
                    $deps['optional'][$packagetype] = array($deps['optional'][$packagetype]);
                }
                foreach ($deps['optional'][$packagetype] as $dep) {
                    $skip = false;
                    if (!isset($options['alldeps'])) {
                        $dep['package'] = $dep['name'];
                        $this->_downloader->log(3, 'Notice: package "pear.php.net/' .
                            $this->getPackage() . '" optional dependency ' .
                            '"pear.php.net/' . $dep['name'] .
                            '" will not be automatically downloaded');
                        $skipnames[] = $this->_registry->parsedPackageNameToString($dep);
                        $skip = true;
                        unset($dep['package']);
                    }
                    if (!($ret = $this->_detect2Dep($dep, $pname, 'optional', $params))) {
                        $dep['package'] = $dep['name'];
                        if (@$skipnames[count($skipnames) - 1] ==
                              $this->_registry->parsedPackageNameToString($dep)) {
                            array_pop($skipnames);
                        }
                    }
                    if (!$skip && is_array($ret)) {
                        $this->_downloadDeps[] = $ret;
                    }
                }
                if (count($skipnames)) {
                    $this->_downloader->log(1, 'Did not download optional dependencies: ' .
                        implode(', ', $skipnames) .
                        ', use --alldeps to download automatically');
                }
            }
            // get requested dependency group, if any
            $groupname = $this->getGroup();
            if (!$groupname) {
                $groupname = 'default'; // try the default dependency group
                $explicit = false;
            } else {
                $explicit = true;
            }
            if (isset($deps['group'])) {
                if (isset($deps['group']['attribs'])) {
                    if (strtolower($deps['group']['attribs']['name']) == strtolower($groupname)) {
                        $group = $deps['group'];
                    } elseif ($explicit) {
                        $this->_downloader->log(0, 'Warning: package "' .
                            $this->_registry->parsedPackageNameToString($pname) .
                            '" has no dependency ' . 'group named "' . $groupname . '"');
                        return;
                    }
                } else {
                    $found = false;
                    foreach ($deps['group'] as $group) {
                        if (strtolower($group['attribs']['name']) == strtolower($groupname)) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        if ($explicit) {
                            $this->_downloader->log(0, 'Warning: package "' .
                                $this->_registry->parsedPackageNameToString($pname) .
                                '" has no dependency ' . 'group named "' . $groupname . '"');
                        }
                        return;
                    }
                }
            }
            if (isset($group)) {
                if (isset($group[$packagetype])) {
                    if (isset($group[$packagetype][0])) {
                        foreach ($group[$packagetype] as $dep) {
                            $ret = $this->_detect2Dep($dep, $pname, 'dependency group "' .
                                $group['attribs']['name'] . '"', $params);
                            if (is_array($ret)) {
                                $this->_downloadDeps[] = $ret;
                            }
                        }
                    } else {
                        $ret = $this->_detect2Dep($group[$packagetype], $pname,
                            'dependency group "' .
                            $group['attribs']['name'] . '"', $params);
                        if (is_array($ret)) {
                            $this->_downloadDeps[] = $ret;
                        }
                    }
                }
            }
        }
    }

    function _detect2Dep($dep, $pname, $group, $params)
    {
        $testdep = $dep;
        $testdep['package'] = $dep['name'];
        if (PEAR_Downloader_Package::willDownload($testdep, $params)) {
            $dep['package'] = $dep['name'];
            $this->_downloader->log(2, 'Skipping ' . $group . ' dependency "' .
                $this->_registry->parsedPackageNameToString($dep) .
                '", will be installed');
            return false;
        }
        $options = $this->_downloader->getOptions();
        $url =
            $this->_downloader->_getDepPackageDownloadUrl($dep, $pname);
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        if (PEAR::isError($url)) {
            PEAR::popErrorHandling();
            return $url;
        }
        $dep['package'] = $dep['name'];
        $ret = $this->_analyzeDownloadURL($url, 'dependency', $dep, $params);
        PEAR::popErrorHandling();
        if (PEAR::isError($ret)) {
            $this->_downloader->log(0, $ret->getMessage());
            return false;
        } else {
            // check to see if a dep is already installed and is the same or newer
            if (!isset($ret['min']) && !isset($ret['max']) && !isset($ret['recommended'])) {
                $oper = 'has';
            } else {
                $oper = '>';
            }
            if (!isset($options['force']) && $this->isInstalled($ret, $oper)) {
                $version = $this->_registry->packageInfo($dep['name'], 'version',
                    $dep['channel']);
                $dep['package'] = $dep['name'];
                $this->_downloader->log(3, 'Skipping ' . $group . ' dependency "' .
                $this->_registry->parsedPackageNameToString($dep) .
                    '" version ' . $url['version'] . ', already installed as version ' .
                    $version);
                return false;
            }
        }
        return $ret;
    }

    function _detect1($deps, $pname, $options, $params)
    {
        $skipnames = array();
        foreach ($deps as $dep) {
            $nodownload = false;
            if ($dep['type'] == 'pkg') {
                $dep['channel'] = 'pear.php.net';
                $dep['package'] = $dep['name'];
                switch ($dep['rel']) {
                    case 'ge' :
                    case 'eq' :
                    case 'gt' :
                        if (PEAR_Downloader_Package::willDownload($dep, $params)) {
                            $group = (!isset($dep['optional']) || $dep['optional'] == 'no') ?
                                'required' :
                                'optional';
                            $this->_downloader->log(2, 'Skipping ' . $group . ' dependency "' .
                                $this->_registry->parsedPackageNameToString($dep) .
                                '", will be installed');
                            continue;
                        }
                }
                if (!isset($options['alldeps'])) {
                    if (isset($dep['optional']) && $dep['optional'] == 'yes') {
                        $this->_downloader->log(3, 'Notice: package "pear.php.net/' .
                            $this->getPackage() . '" optional dependency ' .
                            '"channel://pear.php.net/' . $dep['name'] .
                            '" will not be automatically downloaded');
                        $skipnames[] = 'channel://pear.php.net/' . $dep['name'];
                        $nodownload = true;
                    }
                }
                if (!isset($options['alldeps']) && !isset($options['onlyreqdeps'])) {
                    if (!isset($dep['optional']) || $dep['optional'] == 'no') {
                        $this->_downloader->log(3, 'Notice: package "pear.php.net/' .
                            $this->getPackage() . '" required dependency ' .
                            '"channel://pear.php.net/' . $dep['name'] .
                            '" will not be automatically downloaded');
                        $skipnames[] = 'channel://pear.php.net/' . $dep['name'];
                        $nodownload = true;
                    }
                }
                $url =
                    $this->_downloader->_getDepPackageDownloadUrl($dep, $pname);
                // check to see if a dep is already installed
                if (!isset($options['force']) && $this->isInstalled(
                        array(
                         'info' => array(
                            'package' => $dep['name'],
                            'channel' => $dep['channel'],
                         ),
                        'version' => $url['version']
                        ), $dep['rel'])) {
                    $group = (!isset($dep['optional']) || $dep['optional'] == 'no') ?
                        'required' :
                        'optional';
                    $dep['package'] = $dep['name'];
                    $version = $this->_registry->packageInfo($dep['name'], 'version');
                    $dep['version'] = $url['version'];
                    $this->_downloader->log(3, 'Skipping ' . $group . ' dependency "' .
                        $this->_registry->parsedPackageNameToString($dep) .
                        '", already installed as version ' . $version);
                    if (@$skipnames[count($skipnames) - 1] ==
                          'channel://pear.php.net/' . $dep['name']) {
                        array_pop($skipnames);
                    }
                    continue;
                }
                if ($nodownload) {
                    continue;
                }
                PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                $dep['package'] = $dep['name'];
                $ret = $this->_analyzeDownloadURL($url, 'dependency', $dep, $params);
                PEAR::popErrorHandling();
                if (PEAR::isError($ret)) {
                    $this->_downloader->log(0, $ret->getMessage());
                    continue;
                }
                $this->_downloadDeps[] = $ret;
            }
        }
        if (count($skipnames)) {
            $this->_downloader->log(1, 'Did not download dependencies: ' .
                implode(', ', $skipnames) .
                ', use --alldeps or --onlyreqdeps to download automatically');
        }
    }

    function setDownloadURL($pkg)
    {
        $this->_downloadURL = $pkg;
    }

    function setPackageFile(&$pkg)
    {
        $this->_packagefile = &$pkg;
    }

    function getParsedPackage()
    {   
        if (isset($this->_packagefile) || isset($this->_parsedname)) {
            return array('channel' => $this->getChannel(),
                'package' => $this->getPackage(),
                'version' => $this->getVersion());
        }
        return false;
    }

    function getDownloadURL()
    {
        return $this->_downloadURL;
    }

    function getPackage()
    {
        if (isset($this->_packagefile)) {
            return $this->_packagefile->getPackage();
        } elseif (isset($this->_downloadURL)) {
            return $this->_downloadURL['info']['package'];
        } else {
            return false;
        }
    }

    function getPackageXmlVersion()
    {
        if (isset($this->_packagefile)) {
            return $this->_packagefile->getPackagexmlVersion();
        } elseif (isset($this->_downloadURL['info']['packagexmlversion'])) {
            return $this->_downloadURL['info']['packagexmlversion'];
        } else {
            return '1.0';
        }
    }

    function getChannel()
    {
        if (isset($this->_packagefile)) {
            return $this->_packagefile->getChannel();
        } elseif (isset($this->_downloadURL)) {
            return $this->_downloadURL['info']['channel'];
        } else {
            return false;
        }
    }

    function getVersion()
    {
        if (isset($this->_packagefile)) {
            return $this->_packagefile->getVersion();
        } elseif (isset($this->_downloadURL)) {
            return $this->_downloadURL['version'];
        } else {
            return false;
        }
    }

    function getGroup()
    {
        if (isset($this->_parsedname['group'])) {
            return $this->_parsedname['group'];
        } else {
            return '';
        }
    }

    function isExtension($name)
    {
        if (isset($this->_packagefile)) {
            return $this->_packagefile->isExtension($name);
        } elseif (isset($this->_downloadURL)) {
            return @$this->_downloadURL['providesextension'] == $name;
        } else {
            return false;
        }
    }

    function getDeps()
    {
        if (isset($this->_packagefile)) {
            if ($this->_packagefile->getPackagexmlVersion() == '2.0') {
                return $this->_packagefile->getDeps(true);
            } else {
                return $this->_packagefile->getDeps();
            }
        } elseif (isset($this->_downloadURL)) {
            if (isset($this->_downloadURL['info']['deps'])) {
                return $this->_downloadURL['info']['deps'];
            }
            return array();
        } else {
            return array();
        }
    }

    /**
     * @param array Parsed array from {@link PEAR_Registry::parsePackageName()} or a dependency
     *                     returned from getDepDownloadURL()
     */
    function isEqual($param)
    {
        $package = isset($param['package']) ? $param['package'] : $param['info']['package'];
        $channel = isset($param['channel']) ? $param['channel'] : $param['info']['channel'];
        if (isset($param['version'])) {
            return ($package == $this->getPackage() &&
                $channel == $this->getChannel() &&
                $param['version'] == $this->getVersion());
        } else {
            return $package == $this->getPackage() &&
                $channel == $this->getChannel();
        }
    }

    function isInstalled($dep, $oper = '==')
    {
        if ($oper != 'ge' && $oper != 'gt' && $oper != 'has') {
            return false;
        }
        $options = $this->_downloader->getOptions();
        if ($this->_registry->packageExists($dep['info']['package'], $dep['info']['channel'])) {
            if (isset($options['upgrade'])) {
                if ($oper == 'has') {
                    if (version_compare($this->_registry->packageInfo(
                          $dep['info']['package'], 'version', $dep['info']['channel']),
                          $dep['version'], '>=')) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (version_compare($this->_registry->packageInfo(
                          $dep['info']['package'], 'version', $dep['info']['channel']),
                          $dep['version'], '>=')) {
                        return true;
                    }
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    function &getDependency2Object($c, $i, $p, $s)
    {
        $z = &new PEAR_Dependency2($c, $i, $p, $s);
        return $z;
    }

    /**
     * @param array
     * @static
     */
    function removeDuplicates(&$params)
    {
        $pnames = array();
        foreach ($params as $i => $param) {
            if (!$param) {
                continue;
            }
            if ($param->getPackage()) {
                $pnames[$i] = $param->getChannel() . '/' .
                    $param->getPackage() . '-' . $param->getVersion() . '#' . $param->getGroup();
            }
        }
        $pnames = array_unique($pnames);
        for ($i = 0, $count = count($params); $i < $count; $i++) {
            if (!isset($pnames[$i])) {
                unset($params[$i]);
            }
        }
        $ret = array();
        foreach ($params as $i => $param) {
            $ret[] = &$params[$i];
        }
        $params = array();
        foreach ($ret as $i => $param) {
            $params[] = &$ret[$i];
        }
    }

    /**
     * @static
     */
    function mergeDependencies(&$params)
    {
        $newparams = array();
        foreach ($params as $i => $param) {
            $newdeps = array();
            foreach ($param->_downloadDeps as $dep) {
                if (!PEAR_Downloader_Package::willDownload($dep,
                      array_merge($params, $newparams)) && !$param->isInstalled($dep)) {
                    $newdeps[] = $dep;
                } else {
                    // detect versioning conflicts here
                }
            }
            // convert the dependencies into PEAR_Downloader_Package objects for the next time
            // around
            $params[$i]->_downloadDeps = array();
            foreach ($newdeps as $dep) {
                $obj = &new PEAR_Downloader_Package($params[$i]->_downloader);
                $obj->fromDepURL($dep);
                $obj->detectDependencies($params);
                $j = &$obj;
                $newparams[] = &$j;
            }
        }
        if (count($newparams)) {
            foreach ($newparams as $i => $unused) {
                $params[] = &$newparams[$i];
            }
            return true;
        } else {
            return false;
        }
    }


    /**
     * @static
     */
    function willDownload($param, $params)
    {
        if (!is_array($params)) {
            return false;
        }
        foreach ($params as $obj) {
            if ($obj->isEqual($param)) {
                return true;
            }
        }
        return false;
    }

    function _fromFile($param)
    {
        if (@is_file($param)) {
            $this->_type = 'local';
            $pkg = new PEAR_PackageFile($this->_config, $this->_downloader->_debug,
                $this->_downloader->getDownloadDir());
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $pf = &$pkg->fromAnyFile($param, PEAR_VALIDATE_INSTALLING);
            PEAR::popErrorHandling();
            if (PEAR::isError($pf)) {
                $this->_valid = false;
                return $pf;
            }
            $this->_packagefile = &$pf;
            return $this->_valid = true;
        }
        return $this->_valid = false;
    }

    function _fromUrl($param, $saveparam = '')
    {
        if (!is_array($param) &&
              (preg_match('#^(http|ftp)://#', $param))) {
            $this->_type = 'url';
            $callback = $this->_downloader->ui ?
                array(&$this->_downloader, '_downloadCallback') : null;
            $this->_downloader->pushErrorHandling(PEAR_ERROR_RETURN);
            $file = $this->_downloader->downloadHttp($param, $this->_downloader->ui,
                $this->_downloader->getDownloadDir(), $callback);
            $this->_downloader->popErrorHandling();
            if (PEAR::isError($file)) {
                if (!empty($saveparam)) {
                    $saveparam = ", cannot download \"$saveparam\"";
                }
                $err = PEAR::raiseError('Could not download from "' . $param .
                    '"' . $saveparam);
                    return $err;
            }
            // whew, download worked!
            $pkg = new PEAR_PackageFile($this->_config, $this->_downloader->debug,
                $this->_downloader->getDownloadDir());
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $pf = &$pkg->fromAnyFile($file, PEAR_VALIDATE_INSTALLING);
            PEAR::popErrorHandling();
            if (PEAR::isError($pf)) {
                if (is_array($pf->getUserInfo())) {
                    foreach ($pf->getUserInfo() as $err) {
                        if (is_array($err)) {
                            $err = $err['message'];
                        }
                        $this->_downloader->log(0, "Validation Error: $err");
                    }
                }
                $this->_downloader->log(0, $pf->getMessage());
                $err = PEAR::raiseError('Download of "' . ($saveparam ? $saveparam :
                    $param) . '" succeeded, but it is not a valid package archive');
                $this->_valid = false;
                return $err;
            }
            $this->_packagefile = &$pf;
            return $this->_valid = true;
        }
        return $this->_valid = false;
    }

    /**
     *
     * @param string|array pass in an array of format
     *                     array(
     *                      'package' => 'pname',
     *                     ['channel' => 'channame',]
     *                     ['version' => 'version',]
     *                     ['state' => 'state',])
     *                     or a string of format [channame/]pname[-version|-state]
     */
    function _fromString($param)
    {
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $pname = $this->_registry->parsePackageName($param,
            $this->_config->get('default_channel'));
        PEAR::popErrorHandling();
        if (PEAR::isError($pname)) {
            if ($pname->getCode() == 'invalid') {
                $this->_valid = false;
                return false;
            }
            if ($pname->getCode() == 'channel') {
                $parsed = $pname->getUserInfo();
                if ($this->_downloader->discover($parsed['channel'])) {
                    if ($this->_config->get('auto_discover')) {
                        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                        $pname = $this->_registry->parsePackageName($param,
                            $this->_config->get('default_channel'));
                        PEAR::popErrorHandling();
                    } else {
                        $this->_downloader->log(0, 'Channel "' . $parsed['channel'] .
                            '" is not initialized, use ' .
                            '"pear discover ' . $parsed['channel'] . '" to initialize');
                    }
                }
                if (PEAR::isError($pname)) {
                    $this->_downloader->log(0, $pname->getMessage());
                    $err = PEAR::raiseError('invalid package name/package file "' .
                        $param . '"');
                    $this->_valid = false;
                    return $err;
                }
            } else {
                $this->_downloader->log(0, $pname->getMessage());
                $err = PEAR::raiseError('invalid package name/package file "' .
                    $param . '"');
                $this->_valid = false;
                return $err;
            }
        }
        if (!isset($this->_type)) {
            $this->_type = 'xmlrpc';
        }
        $this->_parsedname = $pname;
        $info = $this->_downloader->_getPackageDownloadUrl($pname);
        $ret = $this->_analyzeDownloadURL($info, $param, $pname);
        if ($ret) {
            $this->_downloadURL = $ret;
            return $this->_valid = (bool) $ret;
        }
    }

    function _analyzeDownloadURL($info, $param, $pname, $params = null)
    {
        if (PEAR_Downloader_Package::willDownload($param, $params)) {
            return false;
        }
        if (!$info) {
            if (!is_array($param)) {
                $saveparam = ", cannot download \"$param\"";
            } else {
                $saveparam = '';
            }
            // no releases exist
            $ret = PEAR::raiseError('No releases for package "' .
                $this->_registry->parsedPackageNameToString($pname) . '" exist' . $saveparam);
            return $ret;
        }
        if (!isset($info['url'])) {
            // releases exist, but we failed to get any
            if (isset($this->_downloader->_options['force'])) {
                if (isset($pname['version'])) {
                    $vs = ', version "' . $pname['version'] . '"';
                } elseif (isset($pname['state'])) {
                    $vs = ', stability "' . $pname['state'] . '"';
                } else {
                    $vs = ' within preferred state ' . $this->_config->get(
                        'preferred_state') . '"';
                }
                $this->_downloader->log(1, 'WARNING: failed to download ' . $pname['channel'] .
                    '/' . $pname['package'] . $vs .
                    ', will instead download version ' . $info['version'] .
                    ', stability "' . $info['info']['state'] . '"');
                // download the latest release
                $info = $this->_downloader->_getPackageDownloadUrl(
                    array('package' => $pname['package'],
                          'channel' => $pname['channel'],
                          'version' => $info['version']));
                return $info;
            } else {
                // construct helpful error message
                if (isset($pname['version'])) {
                    $vs = ', version "' . $pname['version'] . '"';
                } elseif (isset($pname['state'])) {
                    $vs = ', stability "' . $pname['state'] . '"';
                } else {
                    $vs = ' within preferred state ' . $this->_downloader->config->get(
                        'preferred_state') . '"';
                }
                $err = PEAR::raiseError(
                    'Failed to download ' . $pname['channel'] .
                    '::' . $pname['package'] . $vs .
                    ', latest release is version ' . $info['version'] .
                    ', stability "' . $info['info']['state'] . '", use "' .
                    $pname['channel'] . '/' . $pname['package'] . '-' .
                    $info['version'] . '" to install');
                return $err;
            }
        }
        return $info;
    }
}
?>