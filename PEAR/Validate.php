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
define('PEAR_VALIDATE_NORMAL', 1);
define('PEAR_VALIDATE_PACKAGING', 2);
define('PEAR_VALIDATE_INSTALLING', 3);
class PEAR_Validate
{
    var $packageregex = _PEAR_COMMON_PACKAGE_NAME_PREG;
    /**
     * @var PEAR_PackageFile
     */
    var $_packagexml;
    /**
     * @var int one of the PEAR_VALIDATE_* constants
     */
    var $_state = PEAR_VALIDATE_NORMAL;

    /**
     * Format: ('error' => array('field' => name, 'reason' => reason), 'warning' => same)
     * @var array
     * @access private
     */
    var $_failures = array('error' => array(), 'warning' => array());

    function getChannelPackageDownloadRegex()
    {
        return '(' . _PEAR_CHANNELS_NAME_PREG . ')::' . $this->getPackageDownloadRegex();
    }

    function getPackageDownloadRegex()
    {
        return '(' . $this->packageregex . ')(-([.0-9a-zA-Z]+))?';
    }

    function validPackageName($name)
    {
        return (bool)preg_match('/^' . $this->packageregex . '$/', $name);
    }

    function setPackageFile(&$pf)
    {
        $this->_packagexml = &$pf;
    }

    function _addFailure($field, $reason)
    {
        $this->_failures['error'] = array('field' => $field, 'reason' => $reason);
    }

    function _addWarning($field, $reason)
    {
        $this->_failures['warning'] = array('field' => $field, 'reason' => $reason);
    }

    function validate()
    {
        if (!isset($this->_packagexml)) {
            return false;
        }
    }

    function validateName()
    {
        return $this->validPackageName($this->_packagexml->getPackage());
    }

    function validateVersion()
    {
        if ($this->_state != PEAR_VALIDATE_PACKAGING) {
            return true;
        }
        $version = $this->_packagexml->getVersion();
        $versioncomponents = explode('.', $version);
        if (count($versioncomponents) != 3) {
            $this->addFailure('version', 'Must have 3 decimals (x.y.z) in a version number');
            return false;
        }
        $name = $this->_packagexml->getPackage();
        // version must be based upon state
        switch ($this->_packagexml->getState()) {
            case 'snapshot' :
            case 'devel' :
                if ($versioncomponents[0] == '0') {
                    return true;
                }
                return false;
            break;
            case 'alpha' :
            case 'beta' :
            // check for Package2 version 2.0.0
            if ($name{strlen($name) - 1} != $versioncomponents[0]) {
                if ($versioncomponents[0] == '1') {
                    if ($versioncomponents[1] == '0' && $versioncomponents[2]{0} == '0') {
                        if (strlen($versioncomponents[2]) > 1) {
                            return true;
                        } else {
                            $this->_addFailure('version', 'version 1.0.0 cannot be alpha or beta');
                            return false;
                        }
                    } else {
                        $this->_addFailure('version', 'versions greater than 1.0.0 cannot be alpha or beta');
                        return false;
                    }
                } else {
                    $this->_addFailure('version', 'major versions greater than 1 are not allowed for packages ' .
                        'not containing an identical postfix');
                }
            } else {
                if ($versioncomponents[1] == '0'&& $versioncomponents[2]{0} == '0') {
                    if (strlen($versioncomponents[2]) > 1) {
                        return true;
                    } else {
                        $this->_addFailure('version', 'version ' . $versioncomponents[0] . '.0.0 cannot be alpha or beta');
                        return false;
                    }
                } else {
                    $this->_addFailure('version', 'versions greater than ' . $versioncomponents[0] .
                        '.0.0 cannot be alpha or beta');
                    return false;
                }
            }
            case 'stable' :
                return true;
            break;
            default :
                return false;
            break;
        }
    }
}
?>