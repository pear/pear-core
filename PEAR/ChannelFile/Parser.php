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
require_once 'PEAR/ChannelFile.php';
/**
 * Parser for package.xml version 2.0
 * @author Greg Beaver <cellog@php.net>
 * @author Stephan Schmidt (original XML_Unserializer)
 * @package PEAR
 */
class PEAR_ChannelFile_Parser extends PEAR_XMLParser
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

    function parse($data, $file)
    {
        if (PEAR::isError($err = parent::parse($data, $file))) {
            return $err;
        }
        $ret = new PEAR_ChannelFile;
        $ret->setConfig($this->_config);
        if (isset($this->_logger)) {
            $ret->setLogger($this->_logger);
        }
        $ret->fromArray($this->_unserializedData);
        // make sure the filelist is in the easy to read format needed
        $ret->flattenFilelist();
        $ret->setPackagefile($file, $archive);
        return $ret;
    }
}
?>