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

define('PEAR_DEPENDENCY2_OS', 1);
/**
 * Dependency check for PEAR packages
 *
 * This class handles both version 1.0 and 2.0 dependencies
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
    var $_config;
    var $_registry;
    /**
     * Output of PEAR_Registry::parsedPackageName()
     * @var array
     */
    var $_currentPackage;
    function PEAR_Dependency2(&$config, $installoptions, $state = PEAR_VALIDATE_INSTALLING,
                              $package)
    {
        $this->_config = &$config;
        $this->_registry = &$config->getRegistry();
        $this->_options = $installoptions;
        $this->_state = $state;
        $this->_os = new OS_Guess;
        $this->_currentPackage = $package;
    }

    /**
     * Specify a dependency on an OS.  Use arch for detailed os/processor information
     *
     * There are two generic OS dependencies that will be the most common, unix and windows.
     * Other options are linux, freebsd, darwin (OS X), sunos, irix, hpux, aix
     */
    function validateOsDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING) {
            return true;
        }
        if (isset($dep['not']) && $dep['not'] == 'yes') {
            $not = true;
        }
        switch (strtolower($dep['attribs']['name'])) {
            case 'windows' :
                if ($not) {
                    if (substr(PHP_OS, 0, 3) == 'WIN') {
                        return $this->raiseError("Cannot install %s on Windows");
                    }
                } else {
                    if (substr(PHP_OS, 0, 3) != 'WIN') {
                        return $this->raiseError("Can only install %s on Windows");
                    }
                }
            break;
            case 'unix' :
                $unices = array('linux', 'freebsd', 'darwin', 'sunos', 'irix', 'hpux', 'aix');
                if ($not) {
                    if (in_array($this->_os->getSysname(), $unices)) {
                        return $this->raiseError("Cannot install %s on any Unix system");
                    }
                } else {
                    if (!in_array($this->_os->getSysname(), $unices)) {
                        return $this->raiseError("Can only install %s on a Unix system");
                    }
                }
            break;
            default :
                if ($not) {
                    if ($dep['attribs']['name'] == $this->_os->getSysname()) {
                        return $this->raiseError('Cannot install %s on ' . $dep['attribs']['name']);
                    }
                } else {
                    if ($dep['attribs']['name'] != $this->_os->getSysname()) {
                        return $this->raiseError('Cannot install %s on ' . $this->_os->getSysname() .
                            ', can only install on ' . $dep['attribs']['name']);
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
        if (isset($dep['not']) && $dep['not'] == 'yes') {
            $not = true;
        }
        if (!$this->_os->matchSignature($dep['attribs']['pattern'])) {
            if (!$not) {
                return $this->raiseError('%s Architecture dependency failed, cannot match "' .
                    $dep['attribs']['pattern'] . "'");
            }
            return true;
        } else {
            if ($not) {
                return $this->raiseError('%s Architecture dependency failed, required "' .
                    $dep['attribs']['pattern'] . "'");
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
        $loaded = extension_loaded($dep['attribs']['name']);
        if (isset($dep['attribs']['not']) && $dep['attribs']['not'] == 'yes') {
            if ($loaded) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s conflicts with PHP extension "' .
                            $dep['attribs']['name'] . '"');
                    } else {
                        return $this->warning('warning: %s conflicts with PHP extension "' .
                            $dep['attribs']['name'] . '"');
                    }
                } else {
                    return $this->warning('warning: %s conflicts with PHP extension "' .
                        $dep['attribs']['name'] . '"');
                }
            } else {
                return true;
            }
        }
        if (!isset($dep['attribs']['min']) && !isset($dep['attribs']['max']) &&
              !isset($dep['attribs']['recommended'])) {
            if ($loaded) {
                return true;
            } else {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['attribs']['name'] . '"');
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['attribs']['name'] . '"');
                    }
                } else {
                    return $this->warning('%s can optionally use PHP extension "' .
                        $dep['attribs']['name'] . '"');
                }
            }
        }
        $version = phpversion($dep['attribs']['name']);
        if (isset($dep['attribs']['min'])) {
            if (!version_compare($version, $dep['attribs']['min'], '>=')) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['attribs']['name'] . '" version ' . $dep['attribs']['min'] .
                            ' or greater');
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['attribs']['name'] . '" version ' . $dep['attribs']['min'] .
                            ' or greater');
                    }
                } else {
                    return $this->warning('warning: %s optionally requires PHP extension "' .
                        $dep['attribs']['name'] . '" version ' . $dep['attribs']['min'] .
                        ' or greater');
                }
            }
        }
        if (isset($dep['attribs']['max'])) {
            if (!version_compare($version, $dep['attribs']['max'], '<=')) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['attribs']['name'] . '" version ' . $dep['attribs']['max'] .
                            ' or less');
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['attribs']['name'] . '" version ' . $dep['attribs']['max'] .
                            ' or less');
                    }
                } else {
                    return $this->warning('warning: %s requires PHP extension "' .
                        $dep['attribs']['name'] . '" version ' . $dep['attribs']['max'] .
                        ' or less');
                }
            }
        }
        if (isset($dep['exclude'])) {
            if (isset($dep['exclude']['attribs'])) {
                if (version_compare($version, $dep['exclude']['attribs']['version'], '==')) {
                    if (!isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PHP extension "' .
                            $dep['attribs']['name'] . '" version ' .
                            $dep['exclude']['attribs']['version']);
                    } else {
                        return $this->warning('warning: %s is not compatible with PHP extension "' .
                            $dep['attribs']['name'] . '" version ' .
                            $dep['exclude']['attribs']['version']);
                    }
                }
            } else {
                foreach ($dep['exclude'] as $exclude) {
                    if (version_compare($version, $exclude['attribs']['version'], '==')) {
                        if (!isset($this->_options['force'])) {
                            return $this->raiseError('%s is not compatible with PHP extension "' .
                                $dep['attribs']['name'] . '" version ' .
                                $exclude['attribs']['version']);
                        } else {
                            return $this->warning('warning: %s is not compatible with PHP extension "' .
                                $dep['attribs']['name'] . '" version ' .
                                $exclude['attribs']['version']);
                        }
                    }
                }
            }
        }
        if (isset($dep['attribs']['recommended'])) {
            if (version_compare($version, $dep['attribs']['recommended'], '==')) {
                return true;
            } else {
                if (!isset($this->_options['force'])) {
                    return $this->warning('%s dependency: PHP extension ' . $dep['attribs']['name'] .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['attribs']['recommended'].'"');
                } else {
                    return $this->raiseError('%s dependency: PHP extension ' . $dep['attribs']['name'] .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['attribs']['recommended'] .
                        '", but may be compatible, use --force to install');
                }
            }
        }
    }

    function validatePhpDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        $version = phpversion();
        if (!version_compare($version, $dep['attribs']['min'], '>=')) {
            if ($required) {
                if (!isset($this->_options['nodeps'])) {
                    return $this->raiseError('%s requires PHP version '. $dep['attribs']['min'] .
                        ' or greater');
                } else {
                    return $this->warning('warning: %s requires PHP version ' .
                        $dep['attribs']['min'] . ' or greater');
                }
            } else {
                return $this->warning('warning: %s requires PHP version ' . $dep['attribs']['min'] .
                    ' or greater');
            }
        }
        if (!version_compare($version, $dep['attribs']['max'], '<=')) {
            if ($required) {
                if (!isset($this->_options['nodeps'])) {
                    return $this->raiseError('%s requires PHP version ' .
                        $dep['attribs']['max'] . ' or less');
                } else {
                    return $this->warning('%s requires PHP version ' . $dep['attribs']['max'] .
                        ' or less');
                }
            } else {
                return $this->warning('%s requires PHP version ' . $dep['attribs']['max'] .
                    ' or less');
            }
        }
        if (isset($dep['exclude'])) {
            if (isset($dep['exclude']['attribs'])) {
                if (version_compare($version, $dep['exclude']['attribs']['version'], '==')) {
                    if (!isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PHP version "' .
                            $dep['attribs']['name'] . '" version ' .
                            $dep['exclude']['attribs']['version']);
                    } else {
                        return $this->warning('warning: %s is not compatible with PHP version "' .
                            $dep['attribs']['name'] . '" version ' .
                            $dep['exclude']['attribs']['version']);
                    }
                }
            } else {
                foreach ($dep['exclude'] as $exclude) {
                    if (version_compare($version, $exclude['attribs']['version'], '==')) {
                        if (!isset($this->_options['force'])) {
                            return $this->raiseError('%s is not compatible with PHP version "' .
                                $dep['attribs']['name'] . '"');
                        } else {
                            return $this->warning('warning: %s is not compatible with PHP version "' .
                                $dep['attribs']['name'] . '"');
                        }
                    }
                }
            }
        }
    }

    function validatePackageDependency($dep, $required, $params)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
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
                  array('package' => $dep['attribs']['name'],
                        'channel' => $dep['attribs']['channel']))) {
                $found = true;
                break;
            }
        }
        $name = $this->_registry->parsedPackageNameToString(
                            array('package' => $dep['attribs']['name'],
                                  'channel' => $dep['attribs']['channel']));
        if ($found) {
            $version = $param->getVersion();
            $installed = true;
            $downloaded = true;
        } else {
            if ($this->_registry->packageExists($dep['attribs']['name'],
                  $dep['attribs']['channel'])) {
                $installed = true;
                $downloaded = false;
                $version = $this->_registry->packageinfo($dep['attribs']['name'], 'version',
                    $dep['attribs']['channel']);
            } else {
                $installed = false;
                $downloaded = false;
                $version = 'not installed or downloaded';
            }
        }
        if (isset($dep['attribs']['not']) && $dep['attribs']['not'] == 'yes') {
            if ($installed) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s conflicts with package "' . $name . '"');
                    } else {
                        return $this->warning('warning: %s conflicts with package "' . $name . '"');
                    }
                } else {
                    return $this->warning('%s conflicts with package "' . $name . '"');
                }
            } else {
                return true;
            }
        }
        if (!isset($dep['attribs']['min']) && !isset($dep['attribs']['max']) &&
              !isset($dep['attribs']['recommended'])) {
            if ($installed || $downloaded) {
                return true;
            } else {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires package "' . $name . '"');
                    } else {
                        return $this->warning('warning: %s requires package "' . $name . '"');
                    }
                } else {
                    return $this->warning('%s can optionally use package "' . $name . '"');
                }
            }
        }
        if (isset($dep['attribs']['min'])) {
            if (!($installed || $downloaded) ||
                  !version_compare($version, $dep['attribs']['min'], '>=')) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires package "' .
                            $name . '" version ' . $dep['attribs']['min'] .
                            ' or greater');
                    } else {
                        return $this->warning('warning: %s requires package "' .
                            $name . '" version ' . $dep['attribs']['min'] .
                            ' or greater');
                    }
                } else {
                    return $this->warning('warning: %s requires package "' .
                        $name . '" version ' . $dep['attribs']['min'] .
                        ' or greater');
                }
            }
        }
        if (isset($dep['attribs']['max'])) {
            if (!($installed || $downloaded) ||
                  !version_compare($version, $dep['attribs']['max'], '>=')) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires package "' .
                            $name . '" version ' . $dep['attribs']['max'] .
                            ' or less');
                    } else {
                        return $this->warning('%s requires package "' .
                            $name . '" version ' . $dep['attribs']['max'] .
                            ' or less');
                    }
                } else {
                    return $this->warning('warning: %s requires package "' .
                        $name . '" version ' . $dep['attribs']['max'] .
                        ' or less');
                }
            }
        }
        if (!$installed && !$downloaded) {
            if ($required) {
                return $this->raiseError('%s dependency package ' . $name . ' is not installed, and was ' .
                    'not downloaded');
            }
        }
        if (isset($dep['exclude'])) {
            if (isset($dep['exclude']['attribs'])) {
                if (version_compare($version, $dep['exclude']['attribs']['version'], '==')) {
                    if (!isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with package "' .
                            $name . '" version ' .
                            $dep['exclude']['attribs']['version']);
                    } else {
                        return $this->warning('warning: %s is not compatible with package "' .
                            $name . '" version ' .
                            $dep['exclude']['attribs']['version']);
                    }
                }
            } else {
                foreach ($dep['exclude'] as $exclude) {
                    if (version_compare($version, $exclude['attribs']['version'], '==')) {
                        if (!isset($this->_options['force'])) {
                            return $this->raiseError('%s is not compatible with package "' .
                                $name . '" version ' .
                                $exclude['attribs']['version']);
                        } else {
                            return $this->warning('warning: %s is not compatible with package "' .
                                $name . '" version ' .
                                $exclude['attribs']['version']);
                        }
                    }
                }
            }
        }
        if (isset($dep['attribs']['recommended'])) {
            if (version_compare($version, $dep['attribs']['recommended'], '==')) {
                return true;
            } else {
                if (!isset($this->_options['force'])) {
                    return array('%s dependency package ' . $name .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['attribs']['recommended'].'"');
                } else {
                    return $this->raiseError('%s dependency package ' . $name .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['attribs']['recommended'] .
                        '", but may be compatible, use --force to install');
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
                            array('package' => $testdep['attribs']['name'],
                                  'channel' => $testdep['attribs']['channel']));
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
                          $testdep['attribs']['package']) == 0) &&
                          ($depchannel == $testdep['attribs']['channel'])) {
                        if ($dep['rel'] == 'ne') {
                            continue;
                        }
                        $depname == $this->_registry->parsedPackageNameToString(
                            array('package' => $dep['name'], 'channel' => $depchannel));
                        if (isset($dep['optional']) && $dep['optional'] == 'yes') {
                            return array('warning: package "' . $depname . '" optionally ' .
                                'depends on "' . $name . '"');
                        } else {
                            if (isset($this->_options['force'])) {
                                return array('warning: package "' . $depname . '" ' .
                                    'depends on "' . $name . '"');
                            } else {
                                return $this->raiseError('error: package "' . $depname . '" ' .
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
                $newdep['attribs']['channel'] = 'pear.php.net';
            case 'Extension' :
            case 'Os' :
                $newdep['attribs']['name'] = $dep['name'];
            break;
        }
        $dep['rel'] = $this->signOperator($dep['rel']);
        switch ($dep['rel']) {
            case 'has' :
                return array($newdep, $type);
            break;
            case 'not' :
                $newdep['attribs']['not'] = 'yes';
            break;
            case '>=' :
            case '>' :
                $newdep['attribs']['min'] = $dep['version'];
                if ($dep['rel'] == '>') {
                    $newdep['exclude']['attribs']['version'] = $dep['version'];
                }
            break;
            case '<=' :
            case '<' :
                $newdep['attribs']['min'] = $dep['version'];
                if ($dep['rel'] == '<') {
                    $newdep['exclude']['attribs']['version'] = $dep['version'];
                }
            break;
            case '!=' :
                $newdep['attribs']['min'] = '0';
                $newdep['attribs']['max'] = '100000';
                $newdep['exclude']['attribs']['version'] = $dep['version'];
            break;
            case '==' :
                $newdep['attribs']['min'] = $dep['version'];
                $newdep['attribs']['max'] = $dep['version'];
            break;
        }
        if ($type == 'Php') {
            if (!isset($newdep['attribs']['min'])) {
                $newdep['attribs']['min'] = '4.2.0';
            }
            if (!isset($newdep['attribs']['max'])) {
                $newdep['attribs']['max'] = '6.0.0';
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
