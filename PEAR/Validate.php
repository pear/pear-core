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
class PEAR_Validate
{
    var $packageregex = _PEAR_COMMON_PACKAGE_NAME_PREG;
    /**
     * @param PEAR_PackageFile
     * @param string portion of the package file to validate.  Legal values are:
     *
     *        - name Validate the package name
     *        - version Validate the package version
     *        - state Validate the package state
     * @return bool
     */
    function validatePackage($package, $section = false)
    {
        switch ($section) {
            case 'name' :
                return $this->validPackageName($package->getPackage());
            break;
        }
        return true;
    }

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
}
?>