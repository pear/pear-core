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
// |         Stephan Schmidt (original XML_Unserializer code)             |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * Parser for package.xml version 2.0
 * @author Greg Beaver <cellog@php.net>
 * @author Stephan Schmidt (original XML_Unserializer)
 * @package PEAR
 */
class PEAR_PackageFile_Parser_v2
{
    var $_config;
    var $_logger;
    var $_registry;
    /**
     * unserilialized data
     * @var string $_serializedData
     */
    var $_unserializedData = null;

    /**
     * name of the root tag
     * @var string $_root
     */
    var $_root = null;

    /**
     * stack for all data that is found
     * @var array    $_dataStack
     */
    var $_dataStack  =   array();

    /**
     * stack for all values that are generated
     * @var array    $_valStack
     */
    var $_valStack  =   array();

    /**
     * current tag depth
     * @var int    $_depth
     */
    var $_depth = 0;

    function setConfig(&$c)
    {
        $this->_config = &$c;
        $this->_registry = &$c->getRegistry();
    }

    function setLogger(&$l)
    {
        $this->_logger = &$l;
    }

    function parse($data, $file, $archive = false)
    {
        $this->_valStack = array();
        $this->_dataStack = array();
        $this->_depth = 0;

        $xp = @xml_parser_create();
        xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, 0);
        xml_set_object($xp, $this);
        xml_set_element_handler($xp, 'startHandler', 'endHandler');
        xml_set_character_data_handler($xp, 'cdataHandler');
        if (!xml_parse($xp, $data)) {
            $msg = xml_error_string(xml_get_error_code($xp));
            $line = xml_get_current_line_number($xp);
            xml_parser_free($xp);
            return PEAR::raiseError("XML Error: '$msg' on line '$line'", 2);
        }
        xml_parser_free($xp);
        include_once 'PEAR/PackageFile/v2.php';
        $ret = new PEAR_PackageFile_v2;
        $ret->setConfig($this->_config);
        if (isset($this->_logger)) {
            $pf->setLogger($this->_logger);
        }
        $ret->fromArray($this->_unserializedData);
        // make sure the filelist is in the easy to read format needed
        $ret->flattenFilelist();
        $ret->setPackagefile($file, $archive);
        return $ret;
    }

    /**
     * Start element handler for XML parser
     *
     * @access private
     * @param  object $parser  XML parser object
     * @param  string $element XML element
     * @param  array  $attribs attributes of XML tag
     * @return void
     */
    function startHandler($parser, $element, $attribs)
    {
        $type = 'string';

        $this->_depth++;
        $this->_dataStack[$this->_depth] = null;

        $val = array(
                     'name'         => $element,
                     'value'        => null,
                     'type'         => $type,
                     'childrenKeys' => array(),
                     'aggregKeys'   => array()
                    );

        if (count($attribs) > 0) {
            $val['children'] = array();
            $val['type'] = 'array';

            $val['children']['attribs'] = $attribs;

        }

        array_push($this->_valStack, $val);
    }

    /**
     * End element handler for XML parser
     *
     * @access private
     * @param  object XML parser object
     * @param  string
     * @return void
     */
    function endHandler($parser, $element)
    {
        $value = array_pop($this->_valStack);
        $data  = trim($this->_dataStack[$this->_depth]);

        // adjust type of the value
        switch(strtolower($value['type'])) {

            /*
             * unserialize an array
             */
            case 'array':
                if ($data !== '') {
                    $value['children']['_content'] = $data;
                }
                if (isset($value['children'])) {
                    $value['value'] = $value['children'];
                } else {
                    $value['value'] = array();
                }
                break;

            /*
             * unserialize a null value
             */
            case 'null':
                $data = null;
                break;

            /*
             * unserialize any scalar value
             */
            default:
                settype($data, $value['type']);
                $value['value'] = $data;
                break;
        }
        $parent = array_pop($this->_valStack);
        if ($parent === null) {
            $this->_unserializedData = &$value['value'];
            $this->_root = &$value['name'];
            return true;
        } else {
            // parent has to be an array
            if (!isset($parent['children']) || !is_array($parent['children'])) {
                $parent['children'] = array();
                if ($parent['type'] != 'array') {
                    $parent['type'] = 'array';
                }
            }

            if (!empty($value['name'])) {
                // there already has been a tag with this name
                if (in_array($value['name'], $parent['childrenKeys'])) {
                    // no aggregate has been created for this tag
                    if (!in_array($value['name'], $parent['aggregKeys'])) {
                        if (isset($parent['children'][$value['name']])) {
                            $parent['children'][$value['name']] = array($parent['children'][$value['name']]);
                        } else {
                            $parent['children'][$value['name']] = array();
                        }
                        array_push($parent['aggregKeys'], $value['name']);
                    }
                    array_push($parent['children'][$value['name']], $value['value']);
                } else {
                    $parent['children'][$value['name']] = &$value['value'];
                    array_push($parent['childrenKeys'], $value['name']);
                }
            } else {
                array_push($parent['children'],$value['value']);
            }
            array_push($this->_valStack, $parent);
        }

        $this->_depth--;
    }

    /**
     * Handler for character data
     *
     * @access private
     * @param  object XML parser object
     * @param  string CDATA
     * @return void
     */
    function cdataHandler($parser, $cdata)
    {
        $this->_dataStack[$this->_depth] .= $cdata;
    }
}
?>