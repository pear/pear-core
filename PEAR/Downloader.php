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
// | Authors: Greg Beaver <cellog@php.net>                                |
// |          Stig Bakken <ssb@php.net>                                   |
// |          Tomas V.V.Cox <cox@idecnet.com>                             |
// |          Martin Jansen <mj@php.net>                                  |
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'PEAR/Common.php';
require_once 'PEAR/Registry.php';
require_once 'PEAR/Dependency.php';
require_once 'PEAR/Remote.php';
require_once 'PEAR/PackageFile.php';
require_once 'PEAR/Downloader/Package.php';
require_once 'System.php';


define('PEAR_INSTALLER_OK',       1);
define('PEAR_INSTALLER_FAILED',   0);
define('PEAR_INSTALLER_SKIPPED', -1);
define('PEAR_INSTALLER_ERROR_NO_PREF_STATE', 2);

/**
 * Administration class used to download PEAR packages and maintain the
 * installed package database.
 *
 * @since PEAR 1.4
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Downloader extends PEAR_Common
{
    /**
     * @var PEAR_Registry
     * @access private
     */
    var $_registry;

    /**
     * @var PEAR_Remote
     * @access private
     */
    var $_remote;

    /**
     * Preferred Installation State (snapshot, devel, alpha, beta, stable)
     * @var string|null
     * @access private
     */
    var $_preferredState;

    /**
     * Options from command-line passed to Install.
     *
     * Recognized options:<br />
     *  - onlyreqdeps   : install all required dependencies as well
     *  - alldeps       : install all dependencies, including optional
     *  - installroot   : base relative path to install files in
     *  - force         : force a download even if warnings would prevent it
     * @see PEAR_Command_Install
     * @access private
     * @var array
     */
    var $_options;

    /**
     * Downloaded Packages after a call to download().
     *
     * Format of each entry:
     *
     * <code>
     * array('pkg' => 'package_name', 'file' => '/path/to/local/file',
     *    'info' => array() // parsed package.xml
     * );
     * </code>
     * @access private
     * @var array
     */
    var $_downloadedPackages = array();

    /**
     * Packages slated for download.
     *
     * This is used to prevent downloading a package more than once should it be a dependency
     * for two packages to be installed.
     * Format of each entry:
     *
     * <pre>
     * array('package_name1' => parsed package.xml, 'package_name2' => parsed package.xml,
     * );
     * </pre>
     * @access private
     * @var array
     */
    var $_toDownload = array();

    /**
     * Array of every package installed, with names lower-cased.
     *
     * Format:
     * <code>
     * array('package1' => 0, 'package2' => 1, );
     * </code>
     * @var array
     */
    var $_installed = array();

    /**
     * @var array
     * @access private
     */
    var $_errorStack = array();
    
    /**
     * @var boolean
     * @access private
     */
    var $_internalDownload = false;

    /**
     * Temporary variable used in sorting packages by dependency in {@link sortPkgDeps()}
     * @var array
     * @access private
     */
    var $_packageSortTree;

    /**
     * Temporary directory, or configuration value where downloads will occur
     * @var string
     */
    var $_downloadDir;
    // {{{ PEAR_Downloader()

    function PEAR_Downloader(&$ui, $options, &$config)
    {
        $this->_options = $options;
        $this->config = &$config;
        $this->_preferredState = $this->config->get('preferred_state');
        $this->ui = &$ui;
        if (!$this->_preferredState) {
            // don't inadvertantly use a non-set preferred_state
            $this->_preferredState = null;
        }

        $php_dir = $this->config->get('php_dir');
        if (isset($this->_options['installroot'])) {
            if (substr($this->_options['installroot'], -1) == DIRECTORY_SEPARATOR) {
                $this->_options['installroot'] = substr($this->_options['installroot'], 0, -1);
            }
            $php_dir = $this->_prependPath($php_dir, $this->_options['installroot']);
        }
        $this->_registry = &new PEAR_Registry($php_dir);
        $this->_remote = &new PEAR_Remote($config);
        $this->_remote->setRegistry($this->_registry);

        if (isset($this->_options['alldeps']) || isset($this->_options['onlyreqdeps'])) {
            $this->_installed = $this->_registry->listAllPackages();
            foreach ($this->_installed as $key => $unused) {
                if (!count($unused)) {
                    continue;
                }
                @array_walk($this->_installed[$key], 'strtolower');
            }
        }
        parent::PEAR_Common();
    }

    function discover($channel)
    {
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $a = $this->downloadHttp($channel . '/channel.xml', $this->ui, System::mktemp());
        PEAR::popErrorHandling();
        if (PEAR::isError($a)) {
            return false;
        }
        include_once 'PEAR/ChannelFile.php';
        $b = new PEAR_ChannelFile;
        if ($b->fromXmlFile($a)) {
            @unlink($a);
            return true;
        }
        @unlink($a);
        return false;
    }

    function _download($params)
    {
        if (!isset($this->_registry)) {
            $this->_registry = &$this->config->getRegistry();
        }
        if (!isset($this->_remote)) {
            $this->_remote = &$this->config->getRemote();
        }
        // convert all parameters into PEAR_Downloader_Package objects
        foreach ($params as $i => $param) {
            $params[$i] = &new PEAR_Downloader_Package($this);
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $err = $params[$i]->initialize($param);
            PEAR::popErrorHandling();
            if (PEAR::isError($err)) {
                $this->log(0, $err->getMessage());
                $params[$i] = false;
            }
        }
        PEAR_Downloader_Package::removeDuplicates($params);
        foreach ($params as $i => $param) {
            $params[$i]->detectDependencies();
        }
        do {
            $err = PEAR_Downloader_Package::mergeDependencies($params);
        } while ($err && !PEAR::isError($err));
        $err = PEAR_Downloader_Package::analyzeDependencies($params);
        if (PEAR::isError($err)) {
            return $err;
        }
        $ret = array();
        foreach ($params as $package) {
            $pf = &$package->download();
            $ret[] = array('file' => $pf->getArchiveFile(),
                                   'info' => &$pf,
                                   'pkg' => $pf->getPackage());
        }
        return $ret;
    }

    /**
     * Retrieve the directory that downloads will happen in
     * @access private
     * @return string
     */
    function getDownloadDir()
    {
        if (isset($this->_downloadDir)) {
            return $this->_downloadDir;
        }
        $downloaddir = $this->config->get('download_dir');
        if (empty($downloaddir)) {
            if (PEAR::isError($downloaddir = System::mktemp('-d'))) {
                return $downloaddir;
            }
            $this->log(3, '+ tmp dir created at ' . $downloaddir);
        }
        return $this->_downloadDir = $downloaddir;
    }

    // }}}
    // {{{ configSet()
    function configSet($key, $value, $layer = 'user', $channel = false)
    {
        $this->config->set($key, $value, $layer, $channel);
        $this->_preferredState = $this->config->get('preferred_state', null, $channel);
        if (!$this->_preferredState) {
            // don't inadvertantly use a non-set preferred_state
            $this->_preferredState = null;
        }
    }

    // }}}
    // {{{ setOptions()
    function setOptions($options)
    {
        $this->_options = $options;
    }

    // }}}
    // {{{ _downloadFile()
    /**
     * @param string filename to download
     * @param string version/state
     * @param string original value passed to command-line
     * @param string|null preferred state (snapshot/devel/alpha/beta/stable)
     *                    Defaults to configuration preferred state
     * @return null|PEAR_Error|string
     * @access private
     */
    function _downloadFile($pkgfile, $version, $origpkgfile, $state = null)
    {
        if (is_null($state)) {
            $state = $this->_preferredState;
        }
        // {{{ check the package filename, and whether it's already installed
        $need_download = false;
        if (preg_match('#^(http|ftp)://#', $pkgfile)) {
            $need_download = true;
            $chan = false;
        } elseif (!@is_file($pkgfile)) {
            $package = $pkgfile;
            $channel = $this->config->get('default_channel');
            if (strpos($pkgfile, '::')); {
                list($channel, $package) = explode('::', $pkgfile);
            }
            if (!$this->_registry->channelExists($channel)) {
                return $this->raiseError("unknown channel '$channel' in '$pkgfile'");
            }
            $chan = $this->_registry->getChannel($channel);
            if (!$chan) {
                return $this->raiseError("Exception: corrupt registry, could not retrieve channel $channel information");
            }
            if ($chan->validPackageName($package)) {
                if ($this->_registry->packageExists($package, $channel)) {
                    if (empty($this->_options['upgrade']) && empty($this->_options['force'])) {
                        $errors[] = "$pkgfile already installed";
                        return;
                    }
                }
                $pkgfile = $this->_getPackageDownloadUrl($package, $channel, $version);
                $pkgfile = $pkgfile['url'];
                $need_download = true;
            } else {
                if (strlen($pkgfile)) {
                    $errors[] = "Could not open the package file: $pkgfile";
                } else {
                    $errors[] = "No package file given";
                }
                return;
            }
        }
        // }}}

        // {{{ Download package -----------------------------------------------
        if ($need_download) {
            $downloaddir = $this->config->get('download_dir');
            if (empty($downloaddir)) {
                if (PEAR::isError($downloaddir = System::mktemp('-d'))) {
                    return $downloaddir;
                }
                $this->log(3, '+ tmp dir created at ' . $downloaddir);
            }
            $callback = $this->ui ? array(&$this, '_downloadCallback') : null;
            $this->pushErrorHandling(PEAR_ERROR_RETURN);
            $file = $this->downloadHttp($pkgfile, $this->ui, $downloaddir, $callback);
            $this->popErrorHandling();
            if (PEAR::isError($file)) {
                $currentchannel = $this->config->get('default_channel');
                if ($chan && $chan->validPackageName($package)) {
                    $this->configSet('default_channel', $channel);
                    $info = $this->_remote->call('package.info', $origpkgfile);
                    $this->configSet('default_channel', $currentchannel);
                    if (!PEAR::isError($info)) {
                        if (!count($info['releases'])) {
                            return $this->raiseError('Package ' . $origpkgfile .
                            ' has no releases');
                        } else {
                            return $this->raiseError('No releases of preferred state "'
                            . $state . '" exist for package ' . $origpkgfile .
                            '.  Use ' . $origpkgfile . '-state to install another' .
                            ' state (like ' . $origpkgfile .'-beta)',
                            PEAR_INSTALLER_ERROR_NO_PREF_STATE);
                        }
                    } else {
                        return $pkgfile;
                    }
                } else {
                    return $this->raiseError($file);
                }
            }
            $pkgfile = $file;
        }
        // }}}
        return $pkgfile;
    }
    // }}}
    // {{{ getPackageDownloadUrl()

    /**
     * @param array output of {@link parsePackageName()}
     * @access private
     */
    function _getPackageDownloadUrl($parr)
    {
        $curchannel = $this->config->get('default_channel');
        $this->configSet('default_channel', $parr['channel']);
        // getDownloadURL returns an array.  On error, it only contains information
        // on the latest release as array(version, info).  On success it contains
        // array(version, info, download url string)
        $url = $this->_remote->call('package.getDownloadURL', $parr,
            $this->config->get('preferred_state'));
        if ($parr['channel'] != $curchannel) {
            $this->configSet('default_channel', $curchannel);
        }
        if (isset($url['__PEAR_ERROR_CLASS__'])) {
            return PEAR::raiseError($url['message']);
        }
        if (!extension_loaded("zlib")) {
            $ext = '.tar';
        } else {
            $ext = '.tgz';
        }
        if (is_array($url)) {
            if (!isset($url['multiple'])) { // multiple urls returned for a bundle
                if (count($url) == 3) {
                    $url['url'] .= $ext;
                }
            } else {
                foreach ($url as $i => $u) {
                    if (count($u) == 3) {
                        $url[$i]['url'] .= $ext;
                    }
                }
            }
        }
        return $url;
    }
    // }}}
    // {{{ getDepPackageDownloadUrl()

    /**
     * @param array dependency array
     * @access private
     */
    function _getDepPackageDownloadUrl($dep, $parr)
    {
        $xsdversion = isset($dep['rel']) ? '1.0' : '2.0';
        $curchannel = $this->config->get('default_channel');
        $this->configSet('default_channel', $parr['channel']);
        $url = $this->_remote->call('package.getDepDownloadURL', $xsdversion, $dep,
            $parr, $this->config->get('preferred_state'));
        if ($parr['channel'] != $curchannel) {
            $this->configSet('default_channel', $curchannel);
        }
        if (is_array($url)) {
            if (!extension_loaded("zlib")) {
                $ext = '.tar';
            } else {
                $ext = '.tgz';
            }
            if (!isset($url['multiple'])) { // multiple urls returned for a bundle
                if (count($url) == 3) {
                    $url['url'] .= $ext;
                }
            } else {
                foreach ($url as $i => $u) {
                    if (count($u) == 3) {
                        $url[$i]['url'] .= $ext;
                    }
                }
            }
        }
        return $url;
    }
    // }}}
    // {{{ getPackageDownloadUrl()

    /**
     * @deprecated in favor of _getPackageDownloadUrl
     */
    function getPackageDownloadUrl($package, $version = null, $channel = false)
    {
        if ($version) {
            $package .= "-$version";
        }
        if ($this === null || $this->_registry === null) {
            $package = "http://pear.php.net/get/$package";
        } else {
            $chan = $this->_registry->getChannel();
            $package = "http://" . $chan->getServer() . "/get/$package";
        }
        if (!extension_loaded("zlib")) {
            $package .= '?uncompress=yes';
        }
        return $package;
    }

    // }}}
    // {{{ extractDownloadFileName($pkgfile, &$version)

    function extractDownloadFileName($pkgfile, &$version)
    {
        if (!isset($this->_registry)) {
            $this->_registry = &new PEAR_Registry($this->config->get('php_dir'));
        }
        if (@is_file($pkgfile)) {
            return $pkgfile;
        }
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $parsed = $this->_registry->parsePackageName($pkgfile);
        PEAR::popErrorHandling();
        if (!$parsed) {
            // this is a url
            return $pkgfile;
        }
        $package = $parsed['package'];
        
        $chan = $this->_registry->getChannel($channel);
        if (!$chan) {
            // regexes defined in Common.php
            if (preg_match(PEAR_COMMON_CHANNEL_DOWNLOAD_PREG, $pkgfile, $m)) {
                $version = (isset($m[4])) ? $m[4] : null;
                return array('channel' => $m[1], 'package' => $m[2]);
            }
            if (preg_match(PEAR_COMMON_PACKAGE_DOWNLOAD_PREG, $pkgfile, $m)) {
                $version = (isset($m[3])) ? $m[3] : null;
                return $m[1];
            }
        } else {
            if (preg_match('/^' . $chan->getChannelPackageDownloadRegex() . '$/', $pkgfile, $m)) {
                $version = (isset($m[4])) ? $m[4] : null;
                return array('channel' => $m[1], 'package' => $m[2]);
            }
            if (preg_match('/^' . $chan->getPackageDownloadRegex() . '$/', $pkgfile, $m)) {
                $version = (isset($m[3])) ? $m[3] : null;
                return $m[1];
            }
        }
        $version = null;
        return $pkgfile;
    }

    // }}}

    // }}}
    // {{{ getDownloadedPackages()

    /**
     * Retrieve a list of downloaded packages after a call to {@link download()}.
     *
     * Also resets the list of downloaded packages.
     * @return array
     */
    function getDownloadedPackages()
    {
        $ret = $this->_downloadedPackages;
        $this->_downloadedPackages = array();
        $this->_toDownload = array();
        return $ret;
    }

    // }}}
    // {{{ download()

    /**
     * Download any files and their dependencies, if necessary
     *
     * BC-compatible method name
     * @param array a mixed list of package names, local files, or package.xml
     */
    function download($packages)
    {
        return $this->doDownload($packages);
    }

    // }}}
    // {{{ doDownload()

    /**
     * Download any files and their dependencies, if necessary
     *
     * @param array a mixed list of package names, local files, or package.xml
     */
    function doDownload($packages)
    {
        return $this->_downloadedPackages = $this->_download($packages);
        if (!isset($this->_registry)) {
            $this->_registry = &new PEAR_Registry($this->config->get('php_dir', null, 'pear'));
        }
        if (!isset($this->_remote)) {
            $this->_remote = &new PEAR_Remote($this->config);
        }
        $mywillinstall = array();
        $state = $this->_preferredState;

        // {{{ download files in this list if necessary
        foreach($packages as $pkgfile) {
            $savepkgfile = $pkgfile;
            $need_download = false;
            if (!is_file($pkgfile)) {
                if (preg_match('#^(http|ftp)://#', $pkgfile)) {
                    $need_download = true;
                }
                $dlfilename = $this->extractDownloadFileName($pkgfile, $version);
                $pkgfile = $this->_downloadNonFile($pkgfile);
                // channel explicitly specified?
                if (is_array($dlfilename)) {
                    $channel = $dlfilename['channel'];
                } else {
                    if (!$this->_internalDownload) {
                        $channel = $this->config->get('default_channel');
                    } else {
                        $channel = 'pear';
                    }
                }
                if (PEAR::isError($pkgfile)) {
                    $this->_internalDownload = false;
                    return $pkgfile;
                }
                if ($pkgfile === false) {
                    continue;
                }
            } // end is_file()

            $pkg = new PEAR_PackageFile($this->_registry, $this->debug);
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $test = $pkg->fromAnyFile($pkgfile, PEAR_VALIDATE_INSTALLING);
            PEAR::popErrorHandling();
            if (PEAR::isError($test)) {
                foreach ($test->getUserInfo as $err) {
                    $this->log(0, "Validation Error: $err");
                }
                return $this->raiseError("Invalid package.xml file");
            }
            $pkg = $test;
            if ($need_download) {
                // package was a url, no channel enforcement needed
                $channel = $pkg->getChannel();
                $this->_toDownload[] = $channel . '/' . $pkg->getPackage();
            } else {
                $pkgchannel = $pkg->getChannel();
                $explicit = version_compare($pkg->getPackagexmlVersion(), '2.0', 'ge');
                if (!is_file($savepkgfile) && (strtolower($pkgchannel) != strtolower($channel))) {
                    if ($explicit) {
                        $msg = "Downloaded package $pkgchannel/" .
                            $pkg->getPackage() . " from channel $channel, implicitly a pear package";
                    } else {
                        $msg = "Downloaded package $pkgchannel/" .
                            $pkg->getPackage() . " from channel $channel";
                    }
                    if (isset($this->_options['force'])) {
                        $this->log(0, "Warning: $msg - channel mismatch");
                    } else {
                        return $this->raiseError("Error: $msg - channel mismatch, use option force to install anyways");
                    }
                }
            }
            if (isset($this->_options['alldeps']) || isset($this->_options['onlyreqdeps'])) {
                $channel = $pkg->getChannel();
                $mywillinstall[strtolower($channel . '/' . $pkg->getPackage())] = $pkg->getDeps();
            }
            $this->_downloadedPackages[] = array('pkg' => $pkg->getPackage(),
                                       'file' => $pkgfile, 'info' => $pkg);
        } // end foreach($packages)
        // }}}

        // {{{ extract dependencies from downloaded files and then download
        // them if necessary
        if (isset($this->_options['alldeps']) || isset($this->_options['onlyreqdeps'])) {
            $deppackages = array();
            foreach ($mywillinstall as $package => $alldeps) {
                if (!is_array($alldeps)) {
                    // there are no dependencies
                    continue;
                }
                foreach($alldeps as $info) {
                    if ($info['type'] != 'pkg') {
                        continue;
                    }
                    $ret = $this->_processDependency($package, $info, $mywillinstall);
                    if ($ret === false) {
                        continue;
                    }
                    if (PEAR::isError($ret)) {
                        return $ret;
                    }
                    $deppackages[] = $ret;
                } // foreach($alldeps
            }

            if (count($deppackages)) {
                $dl = $this->_internalDownload;
                $this->_internalDownload = true;
                $this->doDownload($deppackages);
                $this->_internalDownload = $dl;
            }
        } // }}} if --alldeps or --onlyreqdeps
    }

    // }}}
    // {{{ _downloadNonFile($pkgfile)

    /**
     * @return false|PEAR_Error|string false if loop should be broken out of,
     *                                 string if the file was downloaded,
     *                                 PEAR_Error on exception
     * @access private
     */
    function _downloadNonFile($pkgfile)
    {
        if (preg_match('#^(http|ftp)://#', $pkgfile)) {
            return $this->_downloadFile($pkgfile, null, $pkgfile);
        }
        $origpkgfile = $pkgfile;
        $state = null;
        $pkgfile = $this->extractDownloadFileName($pkgfile, $version);
        // channel explicitly specified?
        if (is_array($pkgfile)) {
            $channel = $pkgfile['channel'];
            $package = $pkgfile['package'];
            $pkgfile = $pkgfile['channel'] . '/' . $pkgfile['package'];
            $usefulpkgfile = true;
        } else {
            if (!$this->_internalDownload) {
                $channel = $this->config->get('default_channel');
            } else {
                $channel = 'pear';
            }
            $package = $pkgfile;
        }
        $chan = $this->_registry->getChannel($channel);
        if (!$chan->validPackageName($package)) {
            return $this->raiseError("Package name '$package' not valid for channel '$channel'");
        }
        // ignore packages that are installed unless we are upgrading
        if ($this->_registry->packageExists($package, $channel)
              && empty($this->_options['upgrade']) && empty($this->_options['force'])) {
            $this->log(0, "Package '$channel/$package' already installed, skipping");
            return false;
        }
        $curinfo = $this->_registry->packageInfo($package, null, $channel);
        if (in_array($pkgfile, $this->_toDownload)) {
            return false;
        }
        $parsedPname = $this->_registry->parsePackageName("channel://$channel/$package-$version");
        $url = $this->_getPackageDownloadUrl($parsedPname);
        if (!$url) {
            return $this->raiseError("No releases found for package '$channel/$package'");
        }
        if (is_array($url) && count($url) == 2) {
            // nothing found, but there is a latest release
            if ($version !== null) {
                // Passed Foo-1.2
                if ($this->validPackageVersion($version)) {
                    $msg = "No release of '$channel/$package' " .
                          "with version '$version' found, latest release is version " .
                          "'$url[0]', stability '" . $url['info']['state'] . "'";
                } elseif (in_array($version, $this->getReleaseStates())) {
                    $msg = "No release of '$channel/$package' " .
                          "with state '$version' found, latest release is version " .
                          "'$url[0]', stability '" . $url['info']['state'] . "'";
                } else {
                    // invalid suffix passed
                    return $this->raiseError("Invalid suffix '-$version', be sure to pass a valid PEAR ".
                                             "version number or release state");
                }
            } else {
                $ug = !isset($this->_options['force']) ? ' is' : ',';
                $msg = "No release of '$channel/$package'" .
                       " within preferred_state of '" . $this->config->get('preferred_state') .
                       "' found, latest release$ug version '" . $url['version'] . "', stability '" .
                       $url['info']['state'] . "'";
            }
            if (!isset($this->_options['force'])) {
                return $this->raiseError($msg . ', use --force to install');
            } else {
                $this->log(0, 'Warning: ' . $msg . ' will be downloaded');
                // default to downloading the latest package on --force
                $version = $url['version'];
                $pname = $this->_registry->parsePackageName("channel://$channel/$package-" . $url['version']);
                $pkgfile = $this->_getPackageDownloadUrl($pname);
                $this->_toDownload[] = $channel . '/' . $pkgfile['url'];
                return $this->_downloadFile($pkgfile['url'], $version, $origpkgfile, $state);
            }
        }
        // Check if we haven't already the version
        if (empty($this->_options['force']) && !is_null($curinfo)) {
            $version = isset($url['version']) ? $url['version'] : $version;
            if ($curinfo['version'] == $version) {
                $this->log(0, "Package '$channel/{$curinfo['package']}', version '{$curinfo['version']}' already installed, skipping");
                return false;
            } elseif (version_compare("$version", "{$curinfo['version']}") < 0) {
                $this->log(0, "Package '$channel/{$curinfo['package']}' version '{$curinfo['version']}' " .
                              " is installed and {$curinfo['version']} is > requested '$version', skipping");
                return false;
            }
        }
        $this->_toDownload[] = $channel . '/' . $package;
            
        return $this->_downloadFile($url['url'], $version, $origpkgfile, $state);
    }

    // }}}
    // {{{ _processDependency($package, $info, $mywillinstall)

    /**
     * Process a dependency, download if necessary
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param array dependency information from PEAR_Remote call
     * @param array packages that will be installed in this iteration
     * @return false|string|PEAR_Error
     * @access private
     * @todo Add test for relation 'lt'/'le' -> make sure that the dependency requested is
     *       in fact lower than the required value.  This will be very important for BC dependencies
     */
    function _processDependency($pkg, $info, $mywillinstall)
    {
        $channel = $pkg->getChannel();
        $package = $pkg->getPackage();
        $state = $this->_preferredState;
        $depchannel = isset($info['channel']) ? $info['channel'] : 'pear';
        if (!isset($this->_options['alldeps']) && isset($info['optional']) &&
              $info['optional'] == 'yes') {
            // skip optional deps
            $this->log(0, "skipping Package '$channel/$package' optional dependency" .
                " '$depchannel/$info[name]'");
            return false;
        }
        // {{{ get releases
        $curchannel = $this->config->get('default_channel');
        if ($channel != $curchannel) {
            $this->configSet('default_channel', $depchannel);
        }
        $downloadURL = $this->_remote->call('package.getDepDownloadURL',
            $pkg->getPackagexmlVersion(), $info,
            $this->config->get('preferred_state'));
        $releases = $this->_remote->call('package.info', $info['name'],
            'releases', true);
        $this->configSet('default_channel', $curchannel);
        if (PEAR::isError($releases)) {
            return $releases;
        }
        if (!count($releases)) {
            if (!isset($this->_installed[strtolower($depchannel)]
                  [strtolower($info['name'])])) {
                $this->pushError("Package '$channel/$package' dependency '" .
                    "$depchannel::$info[name]' has no releases");
            }
            return false;
        }
        $found = false;
        $save = $releases;
        while(count($releases) && !$found) {
            if (!empty($state) && $state != 'any') {
                list($release_version, $release) = each($releases);
                if ($state != $release['state'] &&
                    !in_array($release['state'], $this->betterStates($state)))
                {
                    // drop this release - it ain't stable enough
                    array_shift($releases);
                } else {
                    $found = true;
                }
            } else {
                $found = true;
            }
        }
        if (!count($releases) && !$found) {
            $get = array();
            foreach($save as $release) {
                $get = array_merge($get,
                    $this->betterStates($release['state'], true));
            }
            $savestate = array_shift($get);
            $this->pushError( "Release for '$channel/$package' dependency '$depchannel/$info[name]' " .
                "has state '$savestate', requires '$state'");
            return false;
        }
        if (in_array(strtolower($depchannel . '/' . $info['name']), $this->_toDownload) ||
              isset($mywillinstall[strtolower($depchannel . '/' . $info['name'])])) {
            // skip upgrade check for packages we will install
            return false;
        }
        if (!isset($this->_installed[$depchannel][strtolower($info['name'])])) {
            // check to see if we can install the specific version required
            if ($info['rel'] == 'eq') {
                return $depchannel . '/' . $info['name'] . '-' . $info['version'];
            }
            // skip upgrade check for packages we don't have installed
            return $depchannel . '/' . $info['name'];
        }
        // }}}

        // {{{ see if a dependency must be upgraded
        $inst_version = $this->_registry->packageInfo($info['name'], 'version', $depchannel);
        if (!isset($info['version'])) {
            // this is a rel='has' dependency, check against latest
            if (version_compare($release_version, $inst_version, 'le')) {
                return false;
            } else {
                return $depchannel . '/' . $info['name'];
            }
        }
        if (version_compare($info['version'], $inst_version, 'le')) {
            // installed version is up-to-date
            return false;
        }
        return $depchannel . '/' . $info['name'];
    }

    // }}}
    // {{{ _downloadCallback()

    function _downloadCallback($msg, $params = null)
    {
        switch ($msg) {
            case 'saveas':
                $this->log(1, "downloading $params ...");
                break;
            case 'done':
                $this->log(1, '...done: ' . number_format($params, 0, '', ',') . ' bytes');
                break;
            case 'bytesread':
                static $bytes;
                if (empty($bytes)) {
                    $bytes = 0;
                }
                if (!($bytes % 10240)) {
                    $this->log(1, '.', false);
                }
                $bytes += $params;
                break;
            case 'start':
                $this->log(1, "Starting to download {$params[0]} (".number_format($params[1], 0, '', ',')." bytes)");
                break;
        }
        if (method_exists($this->ui, '_downloadCallback'))
            $this->ui->_downloadCallback($msg, $params);
    }

    // }}}
    // {{{ _prependPath($path, $prepend)

    function _prependPath($path, $prepend)
    {
        if (strlen($prepend) > 0) {
            if (OS_WINDOWS && preg_match('/^[a-z]:/i', $path)) {
                $path = $prepend . substr($path, 2);
            } else {
                $path = $prepend . $path;
            }
        }
        return $path;
    }
    // }}}
    // {{{ pushError($errmsg, $code)

    /**
     * @param string
     * @param integer
     */
    function pushError($errmsg, $code = -1)
    {
        array_push($this->_errorStack, array($errmsg, $code));
    }

    // }}}
    // {{{ getErrorMsgs()

    function getErrorMsgs()
    {
        $msgs = array();
        $errs = $this->_errorStack;
        foreach ($errs as $err) {
            $msgs[] = $err[0];
        }
        $this->_errorStack = array();
        return $msgs;
    }

    // }}}
    // {{{ sortPkgDeps()

    /**
     * Sort a list of arrays of array(downloaded packagefilename) by dependency.
     *
     * It also removes duplicate dependencies
     * @param array
     * @param boolean Sort packages in reverse order if true
     * @return array array of array(packagefilename, package.xml contents)
     */
    function sortPkgDeps(&$packages, $uninstall = false)
    {
        $ret = array();
        if ($uninstall) {
            foreach($packages as $packageinfo) {
                $ret[] = array('info' => $packageinfo);
            }
        } else {
            foreach($packages as $packagefile) {
                if (!is_array($packagefile)) {
                    $a = new PEAR_PackageFile($this->_registry, $this->_debug);
                    $a = &$a->fromAnyFile($packagefile, PEAR_VALIDATE_INSTALLING);
                    $ret[] = array('file' => $packagefile,
                                   'info' => $a,
                                   'pkg' => $a->getPackage());
                } else {
                    $ret[] = $packagefile;
                }
            }
        }
        $checkdupes = array();
        $newret = array();
        foreach($ret as $i => $p) {
            $channel = $p['info']->getChannel();
            if (!isset($checkdupes[$channel . '/' . $p['info']->getPackage()])) {
                $checkdupes[$channel . '/' . $p['info']->getPackage()][] = $i;
                $newret[] = $p;
            }
        }
        $this->_packageSortTree = $this->_getPkgDepTree($newret);

        $func = $uninstall ? '_sortPkgDepsRev' : '_sortPkgDeps';
        usort($newret, array(&$this, $func));
        $this->_packageSortTree = null;
        $packages = $newret;
    }

    // }}}
    // {{{ _sortPkgDeps()

    /**
     * Compare two package's package.xml, and sort
     * so that dependencies are installed first
     *
     * This is a crude compare, real dependency checking is done on install.
     * The only purpose this serves is to make the command-line
     * order-independent (you can list a dependent package first, and
     * installation occurs in the order required)
     * @access private
     */
    function _sortPkgDeps($p1, $p2)
    {
        $c1 = $p1['info']->getChannel();
        $c2 = $p2['info']->getChannel();
        $p1name = $c1 . '/' . $p1['info']->getPackage();
        $p2name = $c2 . '/' . $p2['info']->getPackage();
        $p1deps = $this->_getPkgDeps($p1);
        $p2deps = $this->_getPkgDeps($p2);
        if (!count($p1deps) && !count($p2deps)) {
            return 0; // order makes no difference
        }
        if (!count($p1deps)) {
            return -1; // package 2 has dependencies, package 1 doesn't
        }
        if (!count($p2deps)) {
            return 1; // package 1 has dependencies, package 2 doesn't
        }
        // both have dependencies
        if (in_array($p1name, $p2deps)) {
            return -1; // put package 1 first: package 2 depends on package 1
        }
        if (in_array($p2name, $p1deps)) {
            return 1; // put package 2 first: package 1 depends on package 2
        }
        if ($this->_removedDependency($p1name, $p2name)) {
            return -1; // put package 1 first: package 2 depends on packages that depend on package 1
        }
        if ($this->_removedDependency($p2name, $p1name)) {
            return 1; // put package 2 first: package 1 depends on packages that depend on package 2
        }
        // doesn't really matter if neither depends on the other
        return 0;
    }

    // }}}
    // {{{ _sortPkgDepsRev()

    /**
     * Compare two package's package.xml, and sort
     * so that dependencies are uninstalled last
     *
     * This is a crude compare, real dependency checking is done on uninstall.
     * The only purpose this serves is to make the command-line
     * order-independent (you can list a dependency first, and
     * uninstallation occurs in the order required)
     * @access private
     */
    function _sortPkgDepsRev($p1, $p2)
    {
        $c1 = $p1['info']->getChannel();
        $c2 = $p2['info']->getChannel();
        $p1name = $c1 . '/' . $p1['info']->getPackage();
        $p2name = $c2 . '/' . $p2['info']->getPackage();
        $p1deps = $this->_getRevPkgDeps($p1);
        $p2deps = $this->_getRevPkgDeps($p2);
        if (!count($p1deps) && !count($p2deps)) {
            return 0; // order makes no difference
        }
        if (!count($p1deps)) {
            return 1; // package 2 has dependencies, package 1 doesn't
        }
        if (!count($p2deps)) {
            return -1; // package 2 has dependencies, package 1 doesn't
        }
        // both have dependencies
        if (in_array($p1name, $p2deps)) {
            return 1; // put package 1 last
        }
        if (in_array($p2name, $p1deps)) {
            return -1; // put package 2 last
        }
        if ($this->_removedDependency($p1name, $p2name)) {
            return 1; // put package 1 last: package 2 depends on packages that depend on package 1
        }
        if ($this->_removedDependency($p2name, $p1name)) {
            return -1; // put package 2 last: package 1 depends on packages that depend on package 2
        }
        // doesn't really matter if neither depends on the other
        return 0;
    }

    // }}}
    // {{{ _getPkgDeps()

    /**
     * get an array of package dependency names
     * @param array
     * @return array
     * @access private
     */
    function _getPkgDeps($p)
    {
        if (!is_array($p['info'])) {
            return $this->_getRevPkgDeps($p);
        }
        $rel = array_shift($p['info']['releases']);
        if (!isset($rel['deps'])) {
            return array();
        }
        $ret = array();
        foreach($rel['deps'] as $dep) {
            if ($dep['type'] == 'pkg') {
                $channel = isset($dep['channel']) ? $dep['channel'] : 'pear';
                $ret[] = $channel . '/' . $dep['name'];
            }
        }
        return $ret;
    }

    // }}}
    // {{{ _getPkgDeps()

    /**
     * get an array representation of the package dependency tree
     * @return array
     * @access private
     */
    function _getPkgDepTree($packages)
    {
        $tree = array();
        foreach ($packages as $p) {
            $channel = $p['info']->getChannel();
            $package = $channel . '/' . $p['info']->getPackage();
            $deps = $this->_getPkgDeps($p);
            $tree[$package] = $deps;
        }
        return $tree;
    }

    // }}}
    // {{{ _removedDependency($p1, $p2)

    /**
     * get an array of package dependency names for uninstall
     * @param string package 1 name
     * @param string package 2 name
     * @return bool
     * @access private
     */
    function _removedDependency($p1, $p2)
    {
        if (empty($this->_packageSortTree[$p2])) {
            return false;
        }
        if (!in_array($p1, $this->_packageSortTree[$p2])) {
            foreach ($this->_packageSortTree[$p2] as $potential) {
                if ($this->_removedDependency($p1, $potential)) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    // }}}
    // {{{ _getRevPkgDeps()

    /**
     * get an array of package dependency names for uninstall
     * @param array
     * @return array
     * @access private
     */
    function _getRevPkgDeps($p)
    {
        if (!$deps = $p['info']->getDeps()) {
            return array();
        }
        $ret = array();
        foreach($deps as $dep) {
            if ($dep['type'] == 'pkg') {
                $channel = isset($dep['channel']) ? $dep['channel'] : 'pear';
                $ret[] = $channel . '/' . $dep['name'];
            }
        }
        return $ret;
    }

    // }}}

    // {{{ downloadHttp()

    /**
     * Download a file through HTTP.  Considers suggested file name in
     * Content-disposition: header and can run a callback function for
     * different events.  The callback will be called with two
     * parameters: the callback type, and parameters.  The implemented
     * callback types are:
     *
     *  'setup'       called at the very beginning, parameter is a UI object
     *                that should be used for all output
     *  'message'     the parameter is a string with an informational message
     *  'saveas'      may be used to save with a different file name, the
     *                parameter is the filename that is about to be used.
     *                If a 'saveas' callback returns a non-empty string,
     *                that file name will be used as the filename instead.
     *                Note that $save_dir will not be affected by this, only
     *                the basename of the file.
     *  'start'       download is starting, parameter is number of bytes
     *                that are expected, or -1 if unknown
     *  'bytesread'   parameter is the number of bytes read so far
     *  'done'        download is complete, parameter is the total number
     *                of bytes read
     *  'connfailed'  if the TCP connection fails, this callback is called
     *                with array(host,port,errno,errmsg)
     *  'writefailed' if writing to disk fails, this callback is called
     *                with array(destfile,errmsg)
     *
     * If an HTTP proxy has been configured (http_proxy PEAR_Config
     * setting), the proxy will be used.
     *
     * @param string  $url       the URL to download
     * @param object  $ui        PEAR_Frontend_* instance
     * @param object  $config    PEAR_Config instance
     * @param string  $save_dir  (optional) directory to save file in
     * @param mixed   $callback  (optional) function/method to call for status
     *                           updates
     *
     * @return string  Returns the full path of the downloaded file or a PEAR
     *                 error on failure.  If the error is caused by
     *                 socket-related errors, the error object will
     *                 have the fsockopen error code available through
     *                 getCode().
     *
     * @access public
     */
    function downloadHttp($url, &$ui, $save_dir = '.', $callback = null)
    {
        if ($callback) {
            call_user_func($callback, 'setup', array(&$ui));
        }
        if (preg_match('!^http://([^/:?#]*)(:(\d+))?(/.*)!', $url, $matches)) {
            list(,$host,,$port,$path) = $matches;
        }
        if (isset($this)) {
            $config = &$this->config;
        } else {
            $config = &PEAR_Config::singleton();
        }
        $proxy_host = $proxy_port = $proxy_user = $proxy_pass = '';
        if ($proxy = parse_url($config->get('http_proxy'))) {
            $proxy_host = @$proxy['host'];
            $proxy_port = @$proxy['port'];
            $proxy_user = @$proxy['user'];
            $proxy_pass = @$proxy['pass'];

            if ($proxy_port == '') {
                $proxy_port = 8080;
            }
            if ($callback) {
                call_user_func($callback, 'message', "Using HTTP proxy $host:$port");
            }
        }
        if (empty($port)) {
            $port = 80;
        }
        if ($proxy_host != '') {
            $fp = @fsockopen($proxy_host, $proxy_port, $errno, $errstr);
            if (!$fp) {
                if ($callback) {
                    call_user_func($callback, 'connfailed', array($proxy_host, $proxy_port,
                                                                  $errno, $errstr));
                }
                return PEAR::raiseError("Connection to `$proxy_host:$proxy_port' failed: $errstr", $errno);
            }
            $request = "GET $url HTTP/1.0\r\n";
        } else {
            $fp = @fsockopen($host, $port, $errno, $errstr);
            if (!$fp) {
                if ($callback) {
                    call_user_func($callback, 'connfailed', array($host, $port,
                                                                  $errno, $errstr));
                }
                return PEAR::raiseError("Connection to `$host:$port' failed: $errstr", $errno);
            }
            $request = "GET $path HTTP/1.0\r\n";
        }
        $request .= "Host: $host:$port\r\n".
            "User-Agent: PHP/".PHP_VERSION."\r\n";
        if ($proxy_host != '' && $proxy_user != '') {
            $request .= 'Proxy-Authorization: Basic ' .
                base64_encode($proxy_user . ':' . $proxy_pass) . "\r\n";
        }
        $request .= "\r\n";
        fwrite($fp, $request);
        $headers = array();
        while (trim($line = fgets($fp, 1024))) {
            if (preg_match('/^([^:]+):\s+(.*)\s*$/', $line, $matches)) {
                $headers[strtolower($matches[1])] = trim($matches[2]);
            } elseif (preg_match('|^HTTP/1.[01] ([0-9]{3}) |', $line, $matches)) {
                if ($matches[1] != 200) {
                    return PEAR::raiseError("File http://$host:$port$path not valid (received: $line)");
                }
            }
        }
        if (isset($headers['content-disposition']) &&
            preg_match('/\sfilename=\"([^;]*\S)\"\s*(;|$)/', $headers['content-disposition'], $matches)) {
            $save_as = basename($matches[1]);
        } else {
            $save_as = basename($url);
        }
        if ($callback) {
            $tmp = call_user_func($callback, 'saveas', $save_as);
            if ($tmp) {
                $save_as = $tmp;
            }
        }
        $dest_file = $save_dir . DIRECTORY_SEPARATOR . $save_as;
        if (!$wp = @fopen($dest_file, 'wb')) {
            fclose($fp);
            if ($callback) {
                call_user_func($callback, 'writefailed', array($dest_file, $php_errormsg));
            }
            return PEAR::raiseError("could not open $dest_file for writing");
        }
        if (isset($headers['content-length'])) {
            $length = $headers['content-length'];
        } else {
            $length = -1;
        }
        $bytes = 0;
        if ($callback) {
            call_user_func($callback, 'start', array(basename($dest_file), $length));
        }
        while ($data = @fread($fp, 1024)) {
            $bytes += strlen($data);
            if ($callback) {
                call_user_func($callback, 'bytesread', $bytes);
            }
            if (!@fwrite($wp, $data)) {
                fclose($fp);
                if ($callback) {
                    call_user_func($callback, 'writefailed', array($dest_file, $php_errormsg));
                }
                return PEAR::raiseError("$dest_file: write failed ($php_errormsg)");
            }
        }
        fclose($fp);
        fclose($wp);
        if ($callback) {
            call_user_func($callback, 'done', $bytes);
        }
        return $dest_file;
    }

    // }}}
}
// }}}

?>
