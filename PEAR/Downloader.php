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
// | Authors: Stig Bakken <ssb@php.net>                                   |
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
            if (strpos($pkgfile, '::')) {
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
                $pkgfile = $pkgfile[2];
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
     * @param string package name
     * @param string channel name
     * @param string|array version or state to download
     * @access private
     */
    function _getPackageDownloadUrl($package, $channel = false, $version = null)
    {
        $channel = $channel ? $channel : 'pear';
        $curchannel = $this->config->get('default_channel');
        $this->configSet('default_channel', $channel);
        if ($version === null) {
            // tell the thing to retrieve any download in the preferred_state range
            $version = $this->betterStates($this->config->get('preferred_state'), true);
        }
        // getDownloadURL returns an array.  On error, it only contains information
        // on the latest release as array(version, info).  On success it contains
        // array(version, info, download url string)
        $url = $this->_remote->call('package.getDownloadURL', $channel, $package, $version);
        if ($channel != $curchannel) {
            $this->configSet('default_channel', $curchannel);
        }
        if (is_array($url) && count($url) == 3) {
            if (!extension_loaded("zlib")) {
                $url[2] .= '.tar';
            } else {
                $url[2] .= '.tgz';
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
        if (strpos($pkgfile, '::')) {
            $channel = array_shift(explode('::', $pkgfile));
        } else {
            $channel = ($this->_internalDownload) ? 'pear' : $this->config->get('default_channel');
        }
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

            $pkg = new PEAR_PackageFile;
            $pkg->setup($this->ui, $this->debug);
            $pkg->setRegistry($this->_registry);
            $test = $pkg->fromAny($pkgfile);
            if (!$test) {
                foreach ($pkg->getErrors(true) as $err) {
                    $this->log(0, "$err[level]: $err[message]");
                }
                return $this->raiseError("Invalid package.xml file");
            }
            $tempinfo = $pkg->toArray();
            if ($need_download) {
                // package was a url, no channel enforcement needed
                $channel = isset($tempinfo['channel']) ? $tempinfo['channel'] : 'pear';
                $this->_toDownload[] = $channel . '::' . $tempinfo['package'];
            } else {
                $pkgchannel = isset($tempinfo['channel']) ? $tempinfo['channel'] : 'pear';
                $explicit = isset($tempinfo['channel']);
                if (!is_file($savepkgfile) && (strtolower($pkgchannel) != strtolower($channel))) {
                    if ($explicit) {
                        $msg = "Downloaded package $pkgchannel::$tempinfo[package] from $channel, implicitly a pear package";
                    } else {
                        $msg = "Downloaded package $pkgchannel::$tempinfo[package] from $channel";
                    }
                    if (isset($this->_options['force'])) {
                        $this->log(0, "Warning: $msg - channel mismatch");
                    } else {
                        return $this->raiseError("Error: $msg - channel mismatch, use option force to install anyways");
                    }
                }
            }
            if (isset($this->_options['alldeps']) || isset($this->_options['onlyreqdeps'])) {
                $channel = isset($tempinfo['channel']) ? $tempinfo['channel'] : 'pear';
                $mywillinstall[strtolower($channel . '::' . $tempinfo['package'])] = @$tempinfo['release_deps'];
            }
            $this->_downloadedPackages[] = array('pkg' => $tempinfo['package'],
                                       'file' => $pkgfile, 'info' => $tempinfo);
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
        $this->_internalDownload = false;
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
            $pkgfile = $pkgfile['channel'] . '::' . $pkgfile['package'];
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
            $this->log(0, "Package '$channel::$package' already installed, skipping");
            return false;
        }
        $curinfo = $this->_registry->packageInfo($package, null, $channel);
        if (in_array($pkgfile, $this->_toDownload)) {
            return false;
        }
        $url = $this->_getPackageDownloadUrl($package, $channel, $version);
        if (!$url) {
            return $this->raiseError("No releases found for package '$channel::$package'");
        }
        if (is_array($url) && count($url) == 2) {
            // nothing found, but there is a latest release
            if ($version !== null) {
                // Passed Foo-1.2
                if ($this->validPackageVersion($version)) {
                    $msg = "No release of '$channel::$package' " .
                          "with version '$version' found, latest release is version " .
                          "'$url[0]', stability '" . $url[1]['state'] . "'";
                } elseif (in_array($version, $this->getReleaseStates())) {
                    $msg = "No release of '$channel::$package' " .
                          "with state '$version' found, latest release is version " .
                          "'$url[0]', stability '" . $url[1]['state'] . "'";
                } else {
                    // invalid suffix passed
                    return $this->raiseError("Invalid suffix '-$version', be sure to pass a valid PEAR ".
                                             "version number or release state");
                }
            } else {
                $ug = !isset($this->_options['force']) ? ' is' : ',';
                $msg = "No release of '$channel::$package'" .
                       " within preferred_state of '" . $this->config->get('preferred_state') .
                       "' found, latest release$ug version '" . $url[0] . "', stability '" .
                       $url[1]['state'] . "'";
            }
            if (!isset($this->_options['force'])) {
                return $this->raiseError($msg . ', use --force to install');
            } else {
                $this->log(0, 'Warning: ' . $msg . ' will be downloaded');
                // default to downloading the latest package on --force
                $version = $url[0];
                $pkgfile = $this->_getPackageDownloadUrl($package, $channel, $url[0]);
                $this->_toDownload[] = $channel . '::' . $pkgfile[2];
                return $this->_downloadFile($pkgfile[2], $version, $origpkgfile, $state);
            }
        }
        // Check if we haven't already the version
        if (empty($this->_options['force']) && !is_null($curinfo)) {
            $version = isset($url[0]) ? $url[0] : $version;
            if ($curinfo['version'] == $version) {
                $this->log(0, "Package '$channel::{$curinfo['package']}', version '{$curinfo['version']}' already installed, skipping");
                return false;
            } elseif (version_compare("$version", "{$curinfo['version']}") < 0) {
                $this->log(0, "Package '$channel::{$curinfo['package']}' version '{$curinfo['version']}' " .
                              " is installed and {$curinfo['version']} is > requested '$version', skipping");
                return false;
            }
        }
        $this->_toDownload[] = $channel . '::' . $package;
            
        return $this->_downloadFile($url[2], $version, $origpkgfile, $state);
    }

    // }}}
    // {{{ _processDependency($package, $info, $mywillinstall)

    /**
     * Process a dependency, download if necessary
     * @param package name
     * @param array dependency information from PEAR_Remote call
     * @param array packages that will be installed in this iteration
     * @return false|string|PEAR_Error
     * @access private
     * @todo Add test for relation 'lt'/'le' -> make sure that the dependency requested is
     *       in fact lower than the required value.  This will be very important for BC dependencies
     */
    function _processDependency($package, $info, $mywillinstall)
    {
        $channel = explode('::', $package);
        $package = $channel[1];
        $channel = $channel[0];
        $state = $this->_preferredState;
        $depchannel = isset($info['channel']) ? $info['channel'] : 'pear';
        if (!isset($this->_options['alldeps']) && isset($info['optional']) &&
              $info['optional'] == 'yes') {
            // skip optional deps
            $this->log(0, "skipping Package '$channel::$package' optional dependency '$depchannel::$info[name]'");
            return false;
        }
        // {{{ get releases
        $curchannel = $this->config->get('default_channel');
        if ($channel != $curchannel) {
            $this->configSet('default_channel', $depchannel);
        }
        $releases = $this->_remote->call('package.info', $info['name'], 'releases', true);
        $this->configSet('default_channel', $curchannel);
        if (PEAR::isError($releases)) {
            return $releases;
        }
        if (!count($releases)) {
            if (!isset($this->_installed[strtolower($depchannel)][strtolower($info['name'])])) {
                $this->pushError("Package '$channel::$package' dependency '$depchannel::$info[name]' ".
                            "has no releases");
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
            $this->pushError( "Release for '$channel::$package' dependency '$depchannel::$info[name]' " .
                "has state '$savestate', requires '$state'");
            return false;
        }
        if (in_array(strtolower($depchannel . '::' . $info['name']), $this->_toDownload) ||
              isset($mywillinstall[strtolower($depchannel . '::' . $info['name'])])) {
            // skip upgrade check for packages we will install
            return false;
        }
        if (!isset($this->_installed[$depchannel][strtolower($info['name'])])) {
            // check to see if we can install the specific version required
            if ($info['rel'] == 'eq') {
                return $depchannel . '::' . $info['name'] . '-' . $info['version'];
            }
            // skip upgrade check for packages we don't have installed
            return $depchannel . '::' . $info['name'];
        }
        // }}}

        // {{{ see if a dependency must be upgraded
        $inst_version = $this->_registry->packageInfo($info['name'], 'version', $depchannel);
        if (!isset($info['version'])) {
            // this is a rel='has' dependency, check against latest
            if (version_compare($release_version, $inst_version, 'le')) {
                return false;
            } else {
                return $depchannel . '::' . $info['name'];
            }
        }
        if (version_compare($info['version'], $inst_version, 'le')) {
            // installed version is up-to-date
            return false;
        }
        return $depchannel . '::' . $info['name'];
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
}
// }}}

?>
