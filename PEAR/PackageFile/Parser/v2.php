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
// | Author: Greg Beaver <cellog@php.net>                                 |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$
require_once 'PEAR/XMLParser.php';
require_once 'PEAR/PackageFile/v2.php';
/**
 * Parser for package.xml version 2.0
 * @author Greg Beaver <cellog@php.net>
 * @package PEAR
 */
class PEAR_PackageFile_Parser_v2 extends PEAR_XMLParser
{
    var $_config;
    var $_logger;
    var $_registry;

    function setConfig(&$c)
    {
        $this->_config = &$c;
        $this->_registry = &$c->getRegistry();
    }

    function setLogger(&$l)
    {
        $this->_logger = &$l;
    }

    /**
     * @param string
     * @param string file name of the package.xml
     * @param string|false name of the archive this package.xml came from, if any
     * @param string class name to instantiate and return.  This must be PEAR_PackageFile_v2 or
     *               a subclass
     * @return PEAR_PackageFile_v2
     */
    function parse($data, $file, $archive = false, $class = 'PEAR_PackageFile_v2')
    {
        if (PEAR::isError($err = parent::parse($data, $file))) {
            return $err;
        }
        $ret = new $class;
        $ret->setConfig($this->_config);
        if (isset($this->_logger)) {
            $ret->setLogger($this->_logger);
        }
        $ret->fromArray($this->_unserializedData);
        $ret->setPackagefile($file, $archive);
        return $ret;
    }
}
?>