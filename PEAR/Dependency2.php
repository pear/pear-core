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
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'OS/Guess.php';
require_once 'PEAR/Validate.php';

/**
 * Dependency check for PEAR packages
 *
 * This class handles both version 1.0 and 2.0 dependencies
 * WARNING: *any* changes to this class must be duplicated in the
 * test_PEAR_Dependency2 class found in tests/PEAR_Dependency2/setup.php.inc,
 * or unit tests will not actually validate the changes
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Dependency2
{
    var $_state;
    var $_options;
    /**
     * @var OS_Guess
     */
    var $_os;
    var $_registry;
    /**
     * Output of PEAR_Registry::parsedPackageName()
     * @var array
     */
    var $_currentPackage;
    /**
     * @param PEAR_Config|PEAR_Registry
     * @param array installation options
     * @param array format of PEAR_Registry::parsedPackageName()
     * @param int installation state (one of PEAR_VALIDATE_*)
     */
    function PEAR_Dependency2($configOrRegistry, $installoptions, $package,
                              $state = PEAR_VALIDATE_INSTALLING)
    {
        if (is_a($configOrRegistry, 'PEAR_Config')) {
            $configOrRegistry = $configOrRegistry->getRegistry();
        }
        $this->_registry = $configOrRegistry;
        $this->_options = $installoptions;
        $this->_state = $state;
        $this->_os = new OS_Guess;
        $this->_currentPackage = $package;
    }

    function _getExtraString($dep)
    {
        $extra = ' (';
        if (isset($dep['recommended'])) {
            $extra .= 'recommended version ' . $dep['recommended'];
        } else {
            if (isset($dep['min'])) {
                $extra .= 'version >= ' . $dep['min'];
            }
            if (isset($dep['max'])) {
                if ($extra != ' (') {
                    $extra .= ', ';
                }
                $extra .= 'version <= ' . $dep['max'];
            }
            if (isset($dep['exclude'])) {
                if (!is_array($dep['exclude'])) {
                    $dep['exclude'] = array($dep['exclude']);
                }
                if ($extra != ' (') {
                    $extra .= ', ';
                }
                $extra .= 'excluded versions: ';
                foreach ($dep['exclude'] as $i => $exclude) {
                    if ($i) {
                        $extra .= ', ';
                    }
                    $extra .= $exclude;
                }
            }
        }
        $extra .= ')';
        if ($extra == ' ()') {
            $extra = '';
        }
        return $extra;
    }

    /**
     * Specify a dependency on an OS.  Use arch for detailed os/processor information
     *
     * There are two generic OS dependencies that will be the most common, unix and windows.
     * Other options are linux, freebsd, darwin (OS X), sunos, irix, hpux, aix
     */
    function validateOsDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            $not = true;
        } else {
            $not = false;
        }
        switch (strtolower($dep['name'])) {
            case 'windows' :
                if ($not) {
                    if (substr(PHP_OS, 0, 3) == 'WIN') {
                        if (!isset($this->_options['nodeps']) &&
                              !isset($this->_options['force'])) {
                            return $this->raiseError("Cannot install %s on Windows");
                        } else {
                            return $this->warning("warning: Cannot install %s on Windows");
                        }
                    }
                } else {
                    if (substr(PHP_OS, 0, 3) != 'WIN') {
                        if (!isset($this->_options['nodeps']) &&
                              !isset($this->_options['force'])) {
                            return $this->raiseError("Can only install %s on Windows");
                        } else {
                            return $this->warning("warning: Can only install %s on Windows");
                        }
                    }
                }
            break;
            case 'unix' :
                $unices = array('linux', 'freebsd', 'darwin', 'sunos', 'irix', 'hpux', 'aix');
                if ($not) {
                    if (in_array($this->_os->getSysname(), $unices)) {
                        if (!isset($this->_options['nodeps']) &&
                              !isset($this->_options['force'])) {
                            return $this->raiseError("Cannot install %s on any Unix system");
                        } else {
                            return $this->warning(
                                "warning: Cannot install %s on any Unix system");
                        }
                    }
                } else {
                    if (!in_array($this->_os->getSysname(), $unices)) {
                        if (!isset($this->_options['nodeps']) &&
                              !isset($this->_options['force'])) {
                            return $this->raiseError("Can only install %s on a Unix system");
                        } else {
                            return $this->warning(
                                "warning: Can only install %s on a Unix system");
                        }
                    }
                }
            break;
            default :
                if ($not) {
                    if ($dep['name'] == $this->_os->getSysname()) {
                        if (!isset($this->_options['nodeps']) &&
                              !isset($this->_options['force'])) {
                            return $this->raiseError('Cannot install %s on ' . $dep['name'] .
                                ' operating system');
                        } else {
                            return $this->warning('warning: Cannot install %s on ' .
                                $dep['name'] . ' operating system');
                        }
                    }
                } else {
                    if ($dep['name'] != $this->_os->getSysname()) {
                        if (!isset($this->_options['nodeps']) &&
                              !isset($this->_options['force'])) {
                            return $this->raiseError('Cannot install %s on ' .
                                $this->_os->getSysname() .
                                ' operating system, can only install on ' . $dep['name']);
                        } else {
                            return $this->warning('warning: Cannot install %s on ' .
                                $this->_os->getSysname() .
                                ' operating system, can only install on ' . $dep['name']);
                        }
                    }
                }
        }
        return true;
    }

    /**
     * Specify a complex dependency on an OS/processor/kernel version,
     * Use OS for simple operating system dependency.
     *
     * This is the only dependency that accepts an eregable pattern.  The pattern
     * will be matched against the php_uname() output parsed by OS_Guess
     */
    function validateArchDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING) {
            return true;
        }
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            $not = true;
        } else {
            $not = false;
        }
        if (!$this->_os->matchSignature($dep['pattern'])) {
            if (!$not) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s Architecture dependency failed, does not ' .
                        'match "' . $dep['pattern'] . '"');
                } else {
                    return $this->warning('warning: %s Architecture dependency failed, does ' .
                        'not match "' . $dep['pattern'] . '"');
                }
            }
            return true;
        } else {
            if ($not) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s Architecture dependency failed, required "' .
                        $dep['pattern'] . '"');
                } else {
                    return $this->warning('warning: %s Architecture dependency failed, ' .
                        'required "' . $dep['pattern'] . '"');
                }
            }
            return true;
        }
    }

    function validateExtensionDependency($dep, $required = true)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        $loaded = extension_loaded($dep['name']);
        $extra = $this->_getExtraString($dep);
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
        }
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            if ($loaded) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s conflicts with PHP extension "' .
                        $dep['name'] . '"' . $extra);
                } else {
                    return $this->warning('warning: %s conflicts with PHP extension "' .
                        $dep['name'] . '"' . $extra);
                }
            } else {
                return true;
            }
        }
        if (!isset($dep['min']) && !isset($dep['max']) &&
              !isset($dep['recommended'])) {
            if ($loaded) {
                return true;
            } else {
                if ($required) {
                    if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['name'] . '"' . $extra);
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['name'] . '"' . $extra);
                    }
                } else {
                    return $this->warning('%s can optionally use PHP extension "' .
                        $dep['name'] . '"' . $extra);
                }
            }
        }
        if (!$loaded) {
            if (!$required) {
                return $this->warning('%s can optionally use PHP extension "' .
                    $dep['name'] . '"' . $extra);
            } else {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires PHP extension "' . $dep['name'] .
                        '"' . $extra);
                }
                    return $this->warning('warning: %s requires PHP extension "' . $dep['name'] .
                        '"' . $extra);
            }
        }
        $version = (string) phpversion($dep['name']);
        if (empty($version)) {
            $version = '0';
        }
        if (isset($dep['min'])) {
            if (!version_compare($version, $dep['min'], '>=')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires PHP extension "' . $dep['name'] .
                        '"' . $extra . ', installed version is ' . $version);
                } else {
                    return $this->warning('warning: %s requires PHP extension "' . $dep['name'] .
                        '"' . $extra . ', installed version is ' . $version);
                }
            }
        }
        if (isset($dep['max'])) {
            if (!version_compare($version, $dep['max'], '<=')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires PHP extension "' . $dep['name'] .
                        '"' . $extra . ', installed version is ' . $version);
                } else {
                    return $this->warning('warning: %s requires PHP extension "' . $dep['name'] .
                        '"' . $extra . ', installed version is ' . $version);
                }
            }
        }
        if (isset($dep['exclude'])) {
            foreach ($dep['exclude'] as $exclude) {
                if (version_compare($version, $exclude, '==')) {
                    if (!isset($this->_options['nodeps']) &&
                          !isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PHP extension "' .
                            $dep['name'] . '" version ' .
                            $exclude);
                    } else {
                        return $this->warning('warning: %s is not compatible with PHP extension "' .
                            $dep['name'] . '" version ' .
                            $exclude);
                    }
                }
            }
        }
        if (isset($dep['recommended'])) {
            if (version_compare($version, $dep['recommended'], '==')) {
                return true;
            } else {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s dependency: PHP extension ' . $dep['name'] .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['recommended'] .
                        '", but may be compatible, use --force to install');
                } else {
                    return $this->warning('warning: %s dependency: PHP extension ' .
                        $dep['name'] . ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['recommended'].'"');
                }
            }
        }
        return true;
    }

    function validatePhpDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        $version = phpversion();
        $extra = $this->_getExtraString($dep);
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
        }
        if (isset($dep['min'])) {
            if (!version_compare($version, $dep['min'], '>=')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires PHP' .
                        $extra . ', installed version is ' . $version);
                } else {
                    return $this->warning('warning: %s requires PHP' .
                        $extra . ', installed version is ' . $version);
                }
            }
        }
        if (isset($dep['max'])) {
            if (!version_compare($version, $dep['max'], '<=')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires PHP' .
                        $extra . ', installed version is ' . $version);
                } else {
                    return $this->warning('warning: %s requires PHP' .
                        $extra . ', installed version is ' . $version);
                }
            }
        }
        if (isset($dep['exclude'])) {
            foreach ($dep['exclude'] as $exclude) {
                if (version_compare($version, $exclude, '==')) {
                    if (!isset($this->_options['nodeps']) &&
                          !isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PHP version ' .
                            $exclude);
                    } else {
                        return $this->warning(
                            'warning: %s is not compatible with PHP version ' .
                            $exclude);
                    }
                }
            }
        }
        return true;
    }

    function validatePearinstallerDependency($dep)
    {
        $pearversion = '@PEAR-VER@';
        $extra = $this->_getExtraString($dep);
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
        }
        if (version_compare($pearversion, $dep['min'], '<')) {
            if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                return $this->raiseError('%s requires PEAR Installer' . $extra .
                    ', installed version is ' . $pearversion);
            } else {
                return $this->warning('warning: %s requires PEAR Installer' . $extra .
                    ', installed version is ' . $pearversion);
            }
        }
        if (isset($dep['max'])) {
            if (version_compare($pearversion, $dep['max'], '>')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires PEAR Installer' . $extra .
                        ', installed version is ' . $pearversion);
                } else {
                    return $this->warning('warning: %s requires PEAR Installer' . $extra .
                        ', installed version is ' . $pearversion);
                }
            }
        }
        if (isset($dep['exclude'])) {
            if (!isset($dep['exclude'][0])) {
                $dep['exclude'] = array($dep['exclude']);
            }
            foreach ($dep['exclude'] as $exclude) {
                if (version_compare($exclude, $pearversion, '==')) {
                    if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PEAR Installer ' .
                            'version ' . $exclude);
                    } else {
                        return $this->warning('warning: %s is not compatible with PEAR ' .
                            'Installer version ' . $exclude);
                    }
                }
            }
        }
        return true;
    }

    function validateSubpackageDependency($dep, $required, $params)
    {
        return $this->validatePackageDependency($dep, $required, $params);
    }

    /**
     * @param array dependency information (2.0 format)
     * @param boolean whether this is a required dependency
     * @param array a list of downloaded packages to be installed, if any
     */
    function validatePackageDependency($dep, $required, $params)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        if (isset($dep['providesextension'])) {
            if (extension_loaded($dep['providesextension'])) {
                $save = $dep;
                $subdep = $dep;
                $subdep['name'] = $subdep['providesextension'];
                PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                $ret = $this->validateExtensionDependency($subdep, $required);
                PEAR::popErrorHandling();
                if (!PEAR::isError($ret)) {
                    return true;
                }
            }
        }
        if ($this->_state == PEAR_VALIDATE_INSTALLING) {
            return $this->_validatePackageInstall($dep, $required);
        }
        if ($this->_state == PEAR_VALIDATE_DOWNLOADING) {
            return $this->_validatePackageDownload($dep, $required, $params);
        }
    }

    function _validatePackageDownload($dep, $required, $params)
    {
        $found = false;
        foreach ($params as $param) {
            if ($param->isEqual(
                  array('package' => $dep['name'],
                        'channel' => $dep['channel']))) {
                $found = true;
                break;
            }
        }
        if (!$found && isset($dep['providesextension'])) {
            foreach ($params as $param) {
                if ($param->isExtension($dep['providesextension'])) {
                    $found = true;
                    break;
                }
            }
        }
        if ($found) {
            $version = $param->getVersion();
            $installed = true;
            $downloaded = true;
        } else {
            if ($this->_registry->packageExists($dep['name'],
                  $dep['channel'])) {
                $installed = true;
                $downloaded = false;
                $version = $this->_registry->packageinfo($dep['name'], 'version',
                    $dep['channel']);
            } else {
                $version = 'not installed or downloaded';
                $installed = false;
                $downloaded = false;
            }
        }
        $extra = $this->_getExtraString($dep);
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
        }
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            if ($installed) {
                if ($required) {
                    if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                        return $this->raiseError('%s conflicts with package "' . $dep['name'] . '"');
                    } else {
                        return $this->warning('warning: %s conflicts with package "' . $dep['name'] . '"');
                    }
                } else {
                    return $this->warning('%s conflicts with package "' . $dep['name'] . '"');
                }
            } else {
                return true;
            }
        }
        if (!isset($dep['min']) && !isset($dep['max']) &&
              !isset($dep['recommended'])) {
            if ($installed || $downloaded) {
                return true;
            } else {
                if ($required) {
                    if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                        return $this->raiseError('%s requires package "' . $dep['name'] . '"' .
                            $extra);
                    } else {
                        return $this->warning('warning: %s requires package "' . $dep['name'] . '"' .
                            $extra);
                    }
                } else {
                    return $this->warning('%s can optionally use package "' . $dep['name'] . '"' .
                        $extra);
                }
            }
        }
        if (!$installed && !$downloaded) {
            if ($required) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires package "' . $dep['name'] . '"' .
                        $extra);
                } else {
                    return $this->warning('warning: %s requires package "' . $dep['name'] . '"' .
                        $extra);
                }
            } else {
                return $this->warning('%s can optionally use package "' . $dep['name'] . '"' .
                    $extra);
            }
        }
        if (isset($dep['min'])) {
            if (version_compare($version, $dep['min'], '<')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires package "' . $dep['name'] . '"' .
                        $extra . ', installed version is ' . $version);
                } else {
                    return $this->warning('warning: %s requires package "' . $dep['name'] . '"' .
                        $extra . ', installed version is ' . $version);
                }
            }
        }
        if (isset($dep['max'])) {
            if (version_compare($version, $dep['max'], '>')) {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s requires package "' . $dep['name'] . '"' .
                        $extra . ', installed version is ' . $version);
                } else {
                    return $this->warning('warning: %s requires package "' . $dep['name'] . '"' .
                        $extra . ', installed version is ' . $version);
                }
            }
        }
        if (isset($dep['exclude'])) {
            foreach ($dep['exclude'] as $exclude) {
                if (version_compare($version, $exclude, '==')) {
                    if (!isset($this->_options['nodeps']) &&
                          !isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with package "' .
                            $name . '" version ' .
                            $exclude);
                    } else {
                        return $this->warning('warning: %s is not compatible with package "' .
                            $name . '" version ' .
                            $exclude);
                    }
                }
            }
        }
        if (isset($dep['recommended'])) {
            if (version_compare($version, $dep['recommended'], '==')) {
                return true;
            } else {
                if (!isset($this->_options['nodeps']) && !isset($this->_options['force'])) {
                    return $this->raiseError('%s dependency package ' . $name .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['recommended'] .
                        '", but may be compatible, use --force to install');
                } else {
                    return $this->warning('warning: %s dependency package ' . $name .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['recommended'].'"');
                }
            }
        }
        return true;
    }

    function _validatePackageInstall($dep, $required)
    {
        return $this->_validatePackageDownload($dep, $required, array());
    }

    /**
     * @todo use an error stack to catch all errors
     */
    function _validatePackageUninstall($testdep, $required)
    {
        $channels = $this->registry->listAllPackages();
        $name = $this->_registry->parsedPackageNameToString(
                            array('package' => $testdep['name'],
                                  'channel' => $testdep['channel']));
        foreach ($channels as $channelname => $packages) {
            foreach ($packages as $pkg) {
                if ($pkg == $package && $channel == $channelname) {
                    continue;
                }
                $deps = $this->registry->packageInfo($pkg, 'release_deps', $channel);
                if (empty($deps)) {
                    continue;
                }
                foreach ($deps as $dep) {
                    $depchannel = isset($dep['channel']) ? $dep['channel'] : 'pear.php.net';
                    if ($dep['type'] == 'pkg' && (strcasecmp($dep['name'],
                          $testdep['package']) == 0) &&
                          ($depchannel == $testdep['channel'])) {
                        if ($dep['rel'] == 'ne') {
                            continue;
                        }
                        $depname == $this->_registry->parsedPackageNameToString(
                            array('package' => $dep['name'], 'channel' => $depchannel));
                        if (isset($dep['optional']) && $dep['optional'] == 'yes') {
                            return $this->warning('warning: package "' . $depname .
                                '" optionally depends on "' . $name . '"');
                        } else {
                            if (!isset($this->_options['nodeps']) &&
                                  !isset($this->_options['force'])) {
                                return $this->raiseError('error: package "' . $depname . '" ' .
                                    'depends on "' . $name . '"');
                            } else {
                                return $this->warning('warning: package "' . $depname . '" ' .
                                    'depends on "' . $name . '"');
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * validate a package.xml 1.0 dependency
     */
    function validateDependency1($dep, $params = array())
    {
        if (!isset($dep['optional'])) {
            $dep['optional'] = 'no';
        }
        list($newdep, $type) = $this->normalizeDep($dep);
        if (!$newdep) {
            return $this->raiseError("Invalid Dependency");
        }
        if (method_exists($this, "validate{$type}Dependency")) {
            return $this->{"validate{$type}Dependency"}($newdep, $dep['optional'] == 'no', $params);
        }
    }

    /**
     * Convert a 1.0 dep into a 2.0 dep
     */
    function normalizeDep($dep)
    {
        $types = array(
            'pkg' => 'Package',
            'ext' => 'Extension',
            'os' => 'Os',
            'php' => 'Php'
        );
        if (isset($types[$dep['type']])) {
            $type = $types[$dep['type']];
        } else {
            return array(false, false);
        }
        $newdep = array();
        switch ($type) {
            case 'Package' :
                $newdep['channel'] = 'pear.php.net';
            case 'Extension' :
            case 'Os' :
                $newdep['name'] = $dep['name'];
            break;
        }
        $dep['rel'] = $this->signOperator($dep['rel']);
        switch ($dep['rel']) {
            case 'has' :
                return array($newdep, $type);
            break;
            case 'not' :
                $newdep['conflicts'] = 'yes';
            break;
            case '>=' :
            case '>' :
                $newdep['min'] = $dep['version'];
                if ($dep['rel'] == '>') {
                    $newdep['exclude'] = $dep['version'];
                }
            break;
            case '<=' :
            case '<' :
                $newdep['min'] = $dep['version'];
                if ($dep['rel'] == '<') {
                    $newdep['exclude'] = $dep['version'];
                }
            break;
            case '!=' :
                $newdep['min'] = '0';
                $newdep['max'] = '100000';
                $newdep['exclude'] = $dep['version'];
            break;
            case '==' :
                $newdep['min'] = $dep['version'];
                $newdep['max'] = $dep['version'];
            break;
        }
        if ($type == 'Php') {
            if (!isset($newdep['min'])) {
                $newdep['min'] = '4.2.0';
            }
            if (!isset($newdep['max'])) {
                $newdep['max'] = '6.0.0';
            }
        }
        return array($newdep, $type);
    }

    /**
     * Converts text comparing operators to them sign equivalents
     *
     * Example: 'ge' to '>='
     *
     * @access public
     * @param  string Operator
     * @return string Sign equivalent
     */
    function signOperator($operator)
    {
        switch($operator) {
            case 'lt': return '<';
            case 'le': return '<=';
            case 'gt': return '>';
            case 'ge': return '>=';
            case 'eq': return '==';
            case 'ne': return '!=';
            default:
                return $operator;
        }
    }

    function raiseError($msg)
    {
        return PEAR::raiseError(sprintf($msg, $this->_registry->parsedPackageNameToString($this->_currentPackage)));
    }

    function warning($msg)
    {
        return array(sprintf($msg, $this->_registry->parsedPackageNameToString($this->_currentPackage)));
    }
}
?>