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
// | Authors:   Tomas V.V.Cox <cox@idecnet.com>                           |
// |            Greg Beaver <cellog@php.net>                              |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'PEAR.php';
require_once 'PEAR/Registry.php';
require_once 'PEAR/Config.php';

class PEAR_DependencyDB
{
    // {{{ properties

    var $_config;
    var $_registry;
    var $_depdb = false;
    var $lockfile = false;
    var $lock_fp = false;
    var $_version = '1.0';
    var $_cache;

    // }}}
    // {{{ & singleton()

    /**
     * @param string
     * @param PEAR_Config
     * @return PEAR_DependencyDB
     * @static
     */
    function &singleton(&$config, $depdb = false)
    {
        if (!isset($GLOBALS['_PEAR_DEPENDENCYDB_INSTANCE'])) {
            $GLOBALS['_PEAR_DEPENDENCYDB_INSTANCE'] = new PEAR_DependencyDB;
            $GLOBALS['_PEAR_DEPENDENCYDB_INSTANCE']->setConfig($config, $depdb);
            $GLOBALS['_PEAR_DEPENDENCYDB_INSTANCE']->assertDepsDB();
        }
        return $GLOBALS['_PEAR_DEPENDENCYDB_INSTANCE'];
    }

    function setConfig(&$config, $depdb)
    {
        if (!$config) {
            $this->_config = &PEAR_Config::singleton();
        } else {
            $this->_config = &$config;
        }
        $this->_registry = &$config->getRegistry();
        $this->lockfile = $this->_registry->lockfile;
        if (!$depdb) {
            $this->_depdb = $config->get('php_dir', null, 'pear.php.net') .
                DIRECTORY_SEPARATOR . '.depdb';
        } else {
            $this->_depdb = $depdb;
        }
    }
    // }}}
    // {{{ assertDepsDB()

    function assertDepsDB()
    {
        if (!is_file($this->_depdb)) {
            $this->rebuildDB();
        } else {
            $depdb = $this->_getDepDB();
            // Datatype format has been changed, rebuild the Deps DB
            if ($depdb['_version'] != $this->_version) {
                $this->rebuildDB();
            }
        }
    }

    /**
     * Get a list of installed packages that depend on this package
     * @param PEAR_PackageFile|array
     * @return array|false
     */
    function getDependentPackages(&$pkg)
    {
        $data = $this->_getDepDB();
        if (is_object($pkg)) {
            $channel = strtolower($pkg->getChannel());
            $package = strtolower($pkg->getPackage());
        } else {
            $channel = strtolower($pkg['channel']);
            $package = strtolower($pkg['package']);
        }
        if (isset($data['packages'][$channel][$package])) {
            return $data['packages'][$channel][$package];
        }
        return false;
    }

    /**
     * Get a list of the actual dependencies of installed packages that depend on
     * a package.
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2|array
     * @return array
     */
    function getDependentPackageDependencies(&$pkg)
    {
        $data = $this->_getDepDB();
        if (is_object($pkg)) {
            $channel = strtolower($pkg->getChannel());
            $package = strtolower($pkg->getPackage());
        } else {
            $channel = strtolower($pkg['channel']);
            $package = strtolower($pkg['package']);
        }
        $depend = $this->getDependentPackages($pkg);
        if (!$depend) {
            return false;
        }
        $dependencies = array();
        foreach ($depend as $info) {
            $temp = $this->getDependencies($info);
            foreach ($temp as $dep) {
                if ($dep['dep']['channel'] == $channel && $dep['dep']['name'] == $package) {
                    $dependencies[$info['channel']][$info['package']] = $dep;
                }
            }
        }
        return $dependencies;
    }

    /**
     * Get a list of dependencies of this installed package
     */
    function getDependencies(&$pkg)
    {
        if (is_object($pkg)) {
            $channel = strtolower($pkg->getChannel());
            $package = strtolower($pkg->getPackage());
        } else {
            $channel = strtolower($pkg['channel']);
            $package = strtolower($pkg['package']);
        }
        $data = $this->_getDepDB();
        if (isset($data['dependencies'][$channel][$package])) {
            return $data['dependencies'][$channel][$package];
        }
        return false;
    }

    /**
     * Determine whether $parent depends on $child, near or deep
     */
    function dependsOn($parent, $child)
    {
        $c = array();
        $this->_getDepDB();
        return $this->_dependsOn($parent, $child, $c);
    }
    
    function _dependsOn($parent, $child, &$checked)
    {
        if (is_object($parent)) {
            $channel = strtolower($parent->getChannel());
            $package = strtolower($parent->getPackage());
        } else {
            $channel = strtolower($parent['channel']);
            $package = strtolower($parent['package']);
        }
        if (isset($checked[$channel][$package])) {
            return false; // avoid endless recursion
        }
        $checked[$channel][$package] = true;
        if (is_object($child)) {
            $depchannel = strtolower($child->getChannel());
            $deppackage = strtolower($child->getPackage());
        } else {
            $depchannel = strtolower($child['channel']);
            $deppackage = strtolower($child['package']);
        }
        if (!isset($this->_cache['dependencies'][$channel][$package])) {
            return false;
        }
        foreach ($this->_cache['dependencies'][$channel][$package] as $info) {
            if ($info['dep']['channel'] == $depchannel &&
                  $info['dep']['name'] == $deppackage) {
                return true;
            }
        }
        foreach ($this->_cache['dependencies'][$channel][$package] as $info) {
            if ($this->_dependsOn(array(
                    'channel' => $info['dep']['channel'],
                    'package' => $info['dep']['name']), $child, $checked)) {
                return true;
            }
        }
        return false;
    }

    function installPackage(&$package)
    {
        $data = $this->_getDepDB();
        unset($this->_cache);
        $this->_setPackageDeps($data, $package);
        $this->_writeDepDB($data);
    }

    function uninstallPackage(&$pkg)
    {
        $data = $this->_getDepDB();
        unset($this->_cache);
        if (is_object($pkg)) {
            $channel = strtolower($pkg->getChannel());
            $package = strtolower($pkg->getPackage());
        } else {
            $channel = strtolower($pkg['channel']);
            $package = strtolower($pkg['package']);
        }
        if (!isset($data['dependencies'][$channel][$package])) {
            return true;
        }
        foreach ($data['dependencies'][$channel][$package] as $dep) {
            $found = false;
            foreach ($data['packages'][strtolower($dep['dep']['channel'])]
                  [strtolower($dep['dep']['name'])] as $i => $info) {
                if ($info['channel'] == $channel &&
                      $info['package'] == $package) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                unset($data['packages'][strtolower($dep['dep']['channel'])]
                    [strtolower($dep['dep']['name'])][$i]);
                if (!count($data['packages'][strtolower($dep['dep']['channel'])]
                      [strtolower($dep['dep']['name'])])) {
                    unset($data['packages'][strtolower($dep['dep']['channel'])]
                        [strtolower($dep['dep']['name'])]);
                    if (!count($data['packages'][strtolower($dep['dep']['channel'])])) {
                        unset($data['packages'][strtolower($dep['dep']['channel'])]);
                    }
                } else {
                    $data['packages'][strtolower($dep['dep']['channel'])]
                        [strtolower($dep['dep']['name'])] =
                        array_values($data['packages'][strtolower($dep['dep']['channel'])]
                            [strtolower($dep['dep']['name'])]);
                }
            }
        }
        unset($data['dependencies'][$channel][$package]);
        if (!count($data['dependencies'][$channel])) {
            unset($data['dependencies'][$channel]);
        }
        $this->_writeDepDB($data);
    }

    function rebuildDB()
    {
        $depdb = array('_version' => $this->_version);
        $packages = $this->_registry->listAllPackages();
        foreach ($packages as $channel => $ps) {
            foreach ($ps as $package) {
                $package = $this->_registry->getPackage($package, $channel);
                $this->_setPackageDeps($depdb, $package);
            }
        }
        $error = $this->_writeDepDB($depdb);
        if (PEAR::isError($error)) {
            return $error;
        }
        $this->_cache = $depdb;
        return true;
    }

    function _lock($mode = LOCK_EX)
    {
        if (!eregi('Windows 9', php_uname())) {
            if ($mode != LOCK_UN && is_resource($this->lock_fp)) {
                // XXX does not check type of lock (LOCK_SH/LOCK_EX)
                return true;
            }
            $open_mode = 'w';
            // XXX People reported problems with LOCK_SH and 'w'
            if ($mode === LOCK_SH) {
                if (@!is_file($this->lockfile)) {
                    touch($this->lockfile);
                }
                $open_mode = 'r';
            }

            $this->lock_fp = @fopen($this->lockfile, $open_mode);

            if (!is_resource($this->lock_fp)) {
                return PEAR::raiseError("could not create lock file" .
                                         (isset($php_errormsg) ? ": " . $php_errormsg : ""));
            }
            if (!(int)flock($this->lock_fp, $mode)) {
                switch ($mode) {
                    case LOCK_SH: $str = 'shared';    break;
                    case LOCK_EX: $str = 'exclusive'; break;
                    case LOCK_UN: $str = 'unlock';    break;
                    default:      $str = 'unknown';   break;
                }
                return PEAR::raiseError("could not acquire $str lock ($this->lockfile)");
            }
        }
        return true;
    }

    function _unlock()
    {
        $ret = $this->_lock(LOCK_UN);
        $this->lock_fp = null;
        return $ret;
    }

    function _getDepDB()
    {
        if (isset($this->_cache)) {
            return $this->_cache;
        }
        if (!$fp = fopen($this->_depdb, 'r')) {
            $err = PEAR::raiseError("Could not open dependencies file `".$this->_depdb."'");
            return $err;
        }
        $rt = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        clearstatcache();
        $data = unserialize(fread($fp, filesize($this->_depdb)));
        set_magic_quotes_runtime($rt);
        fclose($fp);
        $this->_cache = $data;
        return $data;
    }

    function _writeDepDB(&$deps)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        if (!$fp = fopen($this->_depdb, 'wb')) {
            $this->_unlock();
            return PEAR::raiseError("Could not open dependencies file `".$this->_depdb."' for writing");
        }
        $rt = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        fwrite($fp, serialize($deps));
        set_magic_quotes_runtime($rt);
        fclose($fp);
        $this->_unlock();
        $this->_cache = $deps;
        return true;
    }

    /**
     * @param array
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     */
    function _setPackageDeps(&$data, &$pkg)
    {
        $pkg->setConfig($this->_config);
        if ($pkg->getPackagexmlVersion() == '1.0') {
            $gen = &$pkg->getDefaultGenerator();
            $v2 = &$gen->toV2();
            $deps = $v2->getDeps(true);
        } else {
            $deps = $pkg->getDeps(true);
        }
        if (!$deps) {
            return;
        }
        $data['dependencies'][strtolower($pkg->getChannel())][strtolower($pkg->getPackage())]
            = array();
        if (isset($deps['required']['package'])) {
            if (!isset($deps['required']['package'][0])) {
                $deps['required']['package'] = array($deps['required']['package']);
            }
            foreach ($deps['required']['package'] as $dep) {
                $this->_registerDep($data, $pkg, $dep, 'required');
            }
        }
        if (isset($deps['optional']['package'])) {
            if (!isset($deps['optional']['package'][0])) {
                $deps['optional']['package'] = array($deps['optional']['package']);
            }
            foreach ($deps['optional']['package'] as $dep) {
                $this->_registerDep($data, $pkg, $dep, 'optional');
            }
        }
        if (isset($deps['required']['subpackage'])) {
            if (!isset($deps['required']['subpackage'][0])) {
                $deps['required']['subpackage'] = array($deps['required']['subpackage']);
            }
            foreach ($deps['required']['subpackage'] as $dep) {
                $this->_registerDep($data, $pkg, $dep, 'required');
            }
        }
        if (isset($deps['optional']['subpackage'])) {
            if (!isset($deps['optional']['subpackage'][0])) {
                $deps['optional']['subpackage'] = array($deps['optional']['subpackage']);
            }
            foreach ($deps['optional']['subpackage'] as $dep) {
                $this->_registerDep($data, $pkg, $dep, 'optional');
            }
        }
        if (isset($deps['group'])) {
            if (!isset($deps['group'][0])) {
                $deps['group'] = array($deps['group']);
            }
            foreach ($deps['group'] as $group) {
                if (isset($group['package'])) {
                    if (!isset($group['package'][0])) {
                        $group['package'] = array($group['package']);
                    }
                    foreach ($group['package'] as $dep) {
                        $this->_registerDep($data, $pkg, $dep, 'optional',
                            $group['attribs']['name']);
                    }
                }
                if (isset($group['subpackage'])) {
                    if (!isset($group['subpackage'][0])) {
                        $group['subpackage'] = array($group['subpackage']);
                    }
                    foreach ($group['subpackage'] as $dep) {
                        $this->_registerDep($data, $pkg, $dep, 'optional',
                            $group['attribs']['name']);
                    }
                }
            }
        }
        if ($data['dependencies'][strtolower($pkg->getChannel())]
              [strtolower($pkg->getPackage())] == array()) {
            unset($data['dependencies'][strtolower($pkg->getChannel())]
              [strtolower($pkg->getPackage())]);
            if (!count($data['dependencies'][strtolower($pkg->getChannel())])) {
                unset($data['dependencies'][strtolower($pkg->getChannel())]);
            }
        }
    }

    function _registerDep(&$data, $pkg, $dep, $type, $group = false)
    {
        $info = array(
            'dep' => $dep,
            'type' => $type,
            'group' => $group);

        if (isset($dep['channel'])) {
            $depchannel = $dep['channel'];
        } else {
            $depchannel = '__private';
        }
        $data['dependencies'][strtolower($pkg->getChannel())][strtolower($pkg->getPackage())][]
            = $info;
        if (isset($data['packages'][strtolower($depchannel)][strtolower($dep['name'])])) {
            $found = false;
            foreach ($data['packages'][strtolower($depchannel)][strtolower($dep['name'])]
                  as $i => $p) {
                if ($p['channel'] == strtolower($pkg->getChannel()) &&
                      $p['package'] == strtolower($pkg->getPackage())) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $data['packages'][strtolower($depchannel)][strtolower($dep['name'])][]
                    = array('channel' => strtolower($pkg->getChannel()),
                            'package' => strtolower($pkg->getPackage()));
            }
        } else {
            $data['packages'][strtolower($depchannel)][strtolower($dep['name'])][]
                = array('channel' => strtolower($pkg->getChannel()),
                        'package' => strtolower($pkg->getPackage()));
        }
    }
}
?>
