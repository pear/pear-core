<?php
/**
 * Parser for package.xml version 2.0
 */
class PEAR_PackageFile_Parser_v2
{
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

    /**
     * convert an old package.xml into the new 2.0 format
     *
     * This method assumes the channel desired is the pear channel
     * so set it differently if you desire
     * @param PEAR_PackageFile_v1
     */
    function fromV1($packagefile)
    {
    }

    function setRegistry($r)
    {
        $this->_registry = $r;
    }

    function parse($data, $state, $file, $archive = false)
    {
        $this->_valStack = array();
        $this->_dataStack = array();
        $this->_depth = 0;

        $xp = @xml_parser_create();
        xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, 0);
        xml_set_object($xp, $this);
        xml_set_element_handler($xp, 'startHandler', 'endHandler');
        xml_set_character_data_handler($xp, 'cdataHandler');
        xml_parse($xp, $data);
        xml_parser_free($xp);
        include_once 'PEAR/PackageFile/v2.php';
        $ret = new PEAR_PackageFile_v2;
        $ret->setRegistry($this->_registry);
        $ret->fromArray($this->_unserializedData);
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