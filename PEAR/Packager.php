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
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'PEAR/Common.php';
require_once 'PEAR/PackageFile.php';
require_once 'System.php';

/**
 * Administration class used to make a PEAR release tarball.
 *
 * TODO:
 *  - add an extra param the dir where to place the created package
 *
 * @since PHP 4.0.2
 * @author Stig Bakken <ssb@php.net>
 */
class PEAR_Packager extends PEAR_Common
{
    /**
     * @var PEAR_Registry
     */
    var $_registry;
    // {{{ constructor

    function PEAR_Packager()
    {
        parent::PEAR_Common();
    }

    // }}}
    function setRegistry(&$reg)
    {
        $this->_registry = $reg;
    }
    // {{{ destructor

    function _PEAR_Packager()
    {
        parent::_PEAR_Common();
    }

    // }}}

    // {{{ package()

    function package($pkgfile = null, $compress = true)
    {
        // {{{ validate supplied package.xml file
        if (empty($pkgfile)) {
            $pkgfile = 'package.xml';
        }
        if (!isset($this->_registry)) {
            return $this->raiseError("Cannot package without registry, use PEAR_Packager::setRegistry() first");
        }
        $pkg = new PEAR_PackageFile($this->_registry, $this->debug);
        if (PEAR::isError($pf = &$pkg->fromPackageFile($pkgfile, PEAR_VALIDATE_NORMAL))) {
            foreach ($pf->getUserInfo() as $error) {
                $this->log(0, 'Error: ' . $error['message']);
            }
            $this->log(0, $pf->getMessage());
            return $this->raiseError("Cannot package, errors in package file");
        } else {
            foreach ($pf->getValidationWarnings() as $warning) {
                $this->log(1, 'Warning: ' . $warning);
            }
        }

        // }}}
        $pf->setLogger($this);
        if (!$pf->validate(PEAR_VALIDATE_PACKAGING)) {
            foreach ($pf->getValidationWarnings() as $warning) {
                $this->log(0, 'Error: ' . $warning);
            }
            return $this->raiseError("Cannot package, errors in package");
        } else {
            foreach ($pf->getValidationWarnings() as $warning) {
                $this->log(1, 'Warning: ' . $warning);
            }
        }

        $gen = &$pf->getDefaultGenerator();
        $tgzfile = $gen->toTgz($this, $compress);
        $dest_package = basename($tgzfile);
        $pkgdir = dirname($pkgfile);

        // {{{ TAR the Package -------------------------------------------
        $this->log(1, "Package $dest_package done");
        if (file_exists("$pkgdir/CVS/Root")) {
            $cvsversion = preg_replace('/[^a-z0-9]/i', '_', $pf->getVersion());
            $cvstag = "RELEASE_$cvsversion";
            $this->log(1, "Tag the released code with `pear cvstag $pkgfile'");
            $this->log(1, "(or set the CVS tag $cvstag by hand)");
        }
        // }}}

        return $dest_package;
    }

    // }}}
}

// {{{ md5_file() utility function
if (!function_exists('md5_file')) {
    function md5_file($file) {
        if (!$fd = @fopen($file, 'r')) {
            return false;
        }
        $md5 = md5(fread($fd, filesize($file)));
        fclose($fd);
        return $md5;
    }
}
// }}}

?>
