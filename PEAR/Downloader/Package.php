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
     * @param PEAR_Config
     */
    function PEAR_Downloader_Package(&$downloader)
    {
        $this->_downloader = &$downloader;
        $this->_config = &$this->_downloader->config;
        $this->_registry = &$this->_config->getRegistry();
        $this->_valid = false;
    }

    function initialize($param)
    {
        $origErr = $this->_fromFile($param);
        if (!$this->_valid) {
            $err = $this->_fromUrl($param);
            if (PEAR::isError($err) || !$this->_valid) {
                $err = $this->_fromString($param);
                if (PEAR::isError($err) || !$this->_valid) {
                    if (PEAR::isError($origErr)) {
                        if (PEAR::isError($err)) {
                            $this->_downloader->log(0, $err->getMessage());
                        }
                        if (is_array($origErr->getUserInfo())) {
                            foreach ($origErr->getUserInfo() as $err) {
                                if (is_array($err)) {
                                    $err = $err['message'];
                                }
                                $this->_downloader->log(0, $err);
                            }
                        }
                        return PEAR::raiseError($origErr);
                    } else {
                        return PEAR::raiseError($err);
                    }
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
        return $this->_packagefile;
    }

    function &getPackageFile()
    {
        return $this->_packagefile;
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
        $pname = $this->getParsedPackage();
        if (!$pname) {
            return;
        }
        $deps = $this->getDeps();
        if (isset($deps['required']) || isset($deps['optional'])
              || isset($deps['group'])) { // package.xml 2.0
            // get required dependency group
            if (isset($deps['required']['package'])) {
                if (!isset($deps['required']['package']['attribs'])) {
                    foreach ($deps['required']['package'] as $dep) {
                        $this->_downloadDeps[] =
                            $this->_downloader->_getDepPackageDownloadUrl($dep, $pname);
                    }
                } else {
                    $this->_downloadDeps[] =
                        $this->_downloader->_getDepPackageDownloadUrl($dep, $pname);
                }
            }
            // get optional dependency group, if any
            $groupname = $this->getGroup();
            if ($groupname) {
                if (isset($deps['group'])) {
                    if (isset($deps['group']['attribs'])) {
                        if (isset($deps['group']['package'])) {
                            if (isset($deps['group']['package']['attribs'])) {
                                $this->_downloadDeps[] =
                                    $this->_downloader->_getDepPackageDownloadUrl(
                                        $deps['group']['package'], $pname);
                            } else {
                                foreach ($deps['group']['package'] as $dep) {
                                    $this->_downloader->_getDepPackageDownloadUrl(
                                        $dep, $pname);
                                }
                            }
                        }
                    } else {
                    }
                }
            }
        } else { // package.xml 1.0
            foreach ($deps as $dep) {
                if (isset($dep['optional'])) {
                    if (!isset($this->_downloader->_options['alldeps'])) {
                        if ($dep['optional'] == 'yes' && $dep['type'] == 'pkg') {
                            $this->_downloader->log(0, 'Notice: package "pear.php.net/' .
                                $this->getPackage() . '" optional dependency ' .
                                '"pear.php.net/' . $dep['name'] . '" will not be downloaded, ' .
                                'use --alldeps to automatically download required ' .
                                'and optional dependencies');
                            continue;
                        }
                    }
                }
                if ($dep['type'] == 'pkg') {
                    $dep['channel'] = 'pear.php.net';
                    $dep['package'] = $dep['name'];
                    switch ($dep['rel']) {
                        case 'ge' :
                        case 'eq' :
                        case 'gt' :
                            if (PEAR_Downloader_Package::willDownload($dep, $params)) {
                                continue;
                            }
                    }
                    // check to see if a dep is already installed
                    if ($this->isInstalled(
                            array(
                             'info' => array(
                                'package' => $dep['name'],
                                'channel' => $dep['channel'],
                             ),
                            'version' => $dep['version']
                            ), $dep['rel'])) {
                        continue;
                    }
                    if (!isset($this->_downloader->_options['onlyreqdeps']) &&
                          !isset($this->_downloader->_options['alldeps'])) {
                        $this->_downloader->log(0, 'Warning: package "pear.php.net/' .
                            $this->getPackage() . '" required dependency "pear.php.net/'.
                            $dep['name'] . '" will not be downloaded, use --onlyreqdeps' .
                            ' to automatically download required dependencies');
                        continue;
                    }
                    $this->_downloadDeps[] =
                        $this->_downloader->_getDepPackageDownloadUrl($dep, $pname);
                    continue;
                }
            }
        }
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
        if ($this->_registry->packageExists($dep['info']['package'], $dep['info']['channel'])) {
            if (version_compare($this->_registry->packageInfo($dep['info']['package'], 'version',
                  $dep['info']['channel']), $dep['version'], $oper)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array all packages to be installed
     * @static
     */
    function analyzeDependencies($params)
    {
        foreach ($params as $param) {
            $deps = $param->getDeps();
            if (count($deps)) {
                $depchecker = &new PEAR_Dependency2($param->_config,
                    $param->_downloader->_options, PEAR_VALIDATE_DOWNLOADING,
                    $param->getParsedPackage());
                PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
                $failed = false;
                if (isset($deps['required'])) {
                    foreach ($deps['required'] as $type => $dep) {
                        if (!isset($dep[0])) {
                            if (PEAR::isError($e =
                                  $depchecker->{"validate{$type}Dependency"}($dep,
                                  true, $params))) {
                                $failed = true;
                                $param->_downloader->log(0, $e->getMessage());
                            } elseif (is_array($e)) {
                                $param->_downloader->log(0, $e[0]);
                            }
                        } else {
                            foreach ($dep as $d) {
                                if (PEAR::isError($e =
                                      $depchecker->{"validate{$type}Dependency"}($d,
                                      true, $params))) {
                                    $failed = true;
                                    $param->_downloader->log(0, $e->getMessage());
                                } elseif (is_array($e)) {
                                    $param->_downloader->log(0, $e[0]);
                                }
                            }
                        }
                    }
                    if (isset($deps['optional'])) {
                        foreach ($deps['optional'] as $type => $dep) {
                            if (!isset($dep[0])) {
                                if (PEAR::isError($e =
                                      $depchecker->{"validate{$type}Dependency"}($dep,
                                      false, $params))) {
                                    $failed = true;
                                    $param->_downloader->log(0, $e->getMessage());
                                } elseif (is_array($e)) {
                                    $param->_downloader->log(0, $e[0]);
                                }
                            } else {
                                foreach ($dep as $d) {
                                    if (PEAR::isError($e =
                                          $depchecker->{"validate{$type}Dependency"}($d,
                                          false, $params))) {
                                        $failed = true;
                                        $param->_downloader->log(0, $e->getMessage());
                                    } elseif (is_array($e)) {
                                        $param->_downloader->log(0, $e[0]);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($deps as $dep) {
                        if (PEAR::isError($e = $depchecker->validateDependency1($dep, $params))) {
                            $failed = true;
                            $param->_downloader->log(0, $e->getMessage());
                        } elseif (is_array($e)) {
                            $param->_downloader->log(0, $e[0]);
                        }
                    }
                }
                PEAR::staticPopErrorHandling();
                if ($failed) {
                    return PEAR::raiseError("Cannot install, dependencies failed");
                }
            }
        }
    }

    /**
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
        for ($i = 0, $count = count($param); $i < $count; $i++) {
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
            }
            // whew, download worked!
            $pkg = new PEAR_PackageFile($this->_config, $this->_downloader->debug,
                $this->_downloader->getDownloadDir());
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $pf = &$pkg->fromAnyFile($file, PEAR_VALIDATE_INSTALLING);
            PEAR::popErrorHandling();
            if (PEAR::isError($pf)) {
                foreach ($pf->getUserInfo as $err) {
                    $this->log(0, "Validation Error: $err");
                }
                $this->log(0, $pf->getMessage());
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
                    $this->_downloader->log(0, 'Channel "' . $parsed['channel'] .
                        '" is not initialized, use ' .
                        'pear discover ' . $parsed['channel'] . ' to use');
                }
            }
            $this->_downloader->log(0, $pname->getMessage());
            $err = PEAR::raiseError('invalid package name/package file "' .
                $param . '"');
            $this->_valid = false;
            return $err;
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
                $param['package'] . '" exist' . $saveparam);
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