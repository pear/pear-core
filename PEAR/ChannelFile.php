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

require_once 'PEAR/Common.php';

/**
 * Error code if the channel.xml <channel> tag does not contain a valid version
 */
define('PEAR_CHANNELFILE_ERROR_NO_VERSION', 1);
/**
 * Error code if the channel.xml <channel> tag version is not supported (version 1.0 is the only supported version,
 * currently
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_VERSION', 2);

/**
 * Error code if parsing is attempted with no xml extension
 */
define('PEAR_CHANNELFILE_ERROR_NO_XML_EXT', 3);

/**
 * Error code if creating the xml parser resource fails
 */
define('PEAR_CHANNELFILE_ERROR_CANT_MAKE_PARSER', 4);

/**
 * Error code used for all sax xml parsing errors
 */
define('PEAR_CHANNELFILE_ERROR_PARSER_ERROR', 5);

/**#@+
 * Validation errors
 */
/**
 * Error code when channel name is missing
 */
define('PEAR_CHANNELFILE_ERROR_NO_NAME', 6);
/**
 * Error code when channel name is invalid
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_NAME', 7);
/**
 * Error code when channel summary is missing
 */
define('PEAR_CHANNELFILE_ERROR_NO_SUMMARY', 8);
/**
 * Error code when channel summary is multi-line
 */
define('PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY', 9);
/**
 * Error code when channel server is missing for xmlrpc or soap protocol
 */
define('PEAR_CHANNELFILE_ERROR_NO_HOST', 10);
/**
 * Error code when channel server is invalid for xmlrpc or soap protocol
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_HOST', 11);
/**
 * Error code when a dependency has no type="" attribute
 */
define('PEAR_CHANNELFILE_ERROR_NO_DEPTYPE', 12);
/**
 * Error code when a dependency has an invalid type
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_DEPTYPE', 13);
/**
 * Error code when a dependency has no rel="" attribute
 */
define('PEAR_CHANNELFILE_ERROR_NO_DEPREL', 14);
/**
 * Error code when a dependency has an invalid rel
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_DEPREL', 15);
/**
 * Error code when a dependency has no version
 */
define('PEAR_CHANNELFILE_ERROR_NO_DEPVERSION', 16);
/**
 * Error code when a dependency has both version tag and rel="has"
 */
define('PEAR_CHANNELFILE_ERROR_DEPVERSION_IGNORED', 17);
/**
 * Error code when a php dependency has a name
 */
define('PEAR_CHANNELFILE_ERROR_PHPNAME_IGNORED', 18);
/**
 * Error code when a non-php dependency has no name
 */
define('PEAR_CHANNELFILE_ERROR_NO_DEPNAME', 19);
/**
 * Error code when a subchannel has a mirror - only the main channel may have mirrors
 */
define('PEAR_CHANNELFILE_ERROR_SUBCH_MIRROR', 20);
/**
 * Error code when a mirror name is invalid
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_MIRROR', 21);
/**
 * Error code when a mirror type is invalid
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_MIRRORTYPE', 22);
/**
 * Error code when an attempt is made to generate xml, but the parsed content is invalid
 */
define('PEAR_CHANNELFILE_ERROR_INVALID', 23);
/**
 * Error code when an empty package name validate regex is passed in
 */
define('PEAR_CHANNELFILE_ERROR_EMPTY_REGEX', 24);
/**
 * Error code when a <function> tag has no version
 */
define('PEAR_CHANNELFILE_ERROR_NO_FUNCTIONVERSION', 25);
/**
 * Error code when a <function> tag has no name
 */
define('PEAR_CHANNELFILE_ERROR_NO_FUNCTIONNAME', 26);
/**
 * Error code when a <validatepackage> tag has no name
 */
define('PEAR_CHANNELFILE_ERROR_NOVALIDATE_NAME', 27);
/**
 * Error code when a <validatepackage> tag has no version attribute
 */
define('PEAR_CHANNELFILE_ERROR_NOVALIDATE_VERSION', 28);
/**
 * Error code when no <xmlrpc> tag exists in the channel.xml
 */
define('PEAR_CHANNELFILE_ERROR_NO_XMLRPC', 29);
/**
 * Error code when a <subchannel> has no name attribute
 */
define('PEAR_CHANNELFILE_ERROR_NO_SUBNAME', 30);
/**
 * Error code when a <subchannel> has no summmary tag
 */
define('PEAR_CHANNELFILE_ERROR_NO_SUBSUMMARY', 31);
/**
 * Error code when a mirror does not exist but is called for in one of the set*
 * methods.
 */
define('PEAR_CHANNELFILE_ERROR_MIRROR_NOT_FOUND', 32);
/**@#-*/


// {{{ constants and globals

/**
 * Mirror types allowed.  Currently only internet servers are recognized.
 */
$GLOBALS['_PEAR_CHANNELS_MIRROR_TYPES'] =  array('server');


// }}}

/**
 * Class providing Channel support
 * @todo implement protocol type validation (xml-rpc and get)
 */
class PEAR_ChannelFile {
    // {{{ properties
    /**
     * @access private
     * @var PEAR_ErrorStack
     * @access private
     */
    var $_stack;
    
    /**
     * Supported channel.xml versions, for parsing
     * @var array
     * @access private
     */
    var $_supportedVersions = array('1.0');

    /**
     * Parsed channel information
     * @var array
     * @access private
     */
    var $_channelInfo;

    /**
     * index into the subchannels array, used for parsing xml
     * @var int
     * @access private
     */
    var $_subchannelIndex;

    /**
     * index into the mirrors array, used for parsing xml
     * @var int
     * @access private
     */
    var $_mirrorIndex;
    
    /**
     * Flag used to determine the validity of parsed content
     * @var boolean
     * @access private
     */
    var $_isValid = false;
    // }}}
    // {{{ constructor
    
    /**
     * @param bool determines whether to return a PEAR_Error object, or use the PEAR_ErrorStack
     * @param string Name of Error Stack class to use.  This allows inheritance with stacks that have the
     *        same constructor as the parent PEAR_ErrorStack class
     */
    function PEAR_ChannelFile($compatibility = false, $stackclass = 'PEAR_ErrorStack')
    {
        if (!class_exists('PEAR_ErrorStack')) {
            include_once 'PEAR/ErrorStack.php';
        }
        $this->_stack = &PEAR_ErrorStack::singleton('PEAR_PackageFile', false,
            false, $compatibility, 'Exception', $stackclass);
        $this->_stack->setErrorMessageTemplate($this->_getErrorMessage());
        $this->_isValid = false;
        $this->_compatibility = $compatibility;
    }

    // }}}
    
    /**
     * @return array
     * @access protected
     */
    function _getErrorMessage()
    {
        return
            array(
                PEAR_CHANNELFILE_ERROR_INVALID_VERSION =>
                    'While parsing channel.xml, an invalid version number "%version% was passed in, expecting one of %versions%',
                PEAR_CHANNELFILE_ERROR_NO_VERSION =>
                    'No version number found in <channel> tag',
                PEAR_CHANNELFILE_ERROR_NO_XML_EXT =>
                    '%error%',
                PEAR_CHANNELFILE_ERROR_CANT_MAKE_PARSER =>
                    'Unable to create XML parser',
                PEAR_CHANNELFILE_ERROR_PARSER_ERROR =>
                    '%error%',
                PEAR_CHANNELFILE_ERROR_NO_NAME =>
                    'Missing channel name',
                PEAR_CHANNELFILE_ERROR_INVALID_NAME =>
                    'Invalid channel name "%name%"',
                PEAR_CHANNELFILE_ERROR_NO_SUMMARY =>
                    'Missing channel summary',
                PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY =>
                    'Channel summary should be on one line, but is multi-line',
                PEAR_CHANNELFILE_ERROR_NO_HOST =>
                    'Missing channel server for %protocol% protocol',
                PEAR_CHANNELFILE_ERROR_INVALID_HOST =>
                    'Server name "%server%" is invalid for protocol %protocol%',
                PEAR_CHANNELFILE_ERROR_NO_DEPTYPE =>
                    'Dependency number %index% has no type attribute',
                PEAR_CHANNELFILE_ERROR_INVALID_DEPTYPE =>
                    'Dependency number %index% has invalid type "%type%," should be one of "%deps%"',
                PEAR_CHANNELFILE_ERROR_NO_DEPREL =>
                    'Dependency number %index% has no rel attribute',
                PEAR_CHANNELFILE_ERROR_INVALID_DEPREL =>
                    'Dependency number %index% has invalid rel "%rel%," should be one of "%rels%"',
                PEAR_CHANNELFILE_ERROR_NO_DEPVERSION =>
                    'Dependency number %index% has no version attribute',
                PEAR_CHANNELFILE_ERROR_DEPVERSION_IGNORED =>
                    'Dependency number %index% version attribute "%version%" will be ignored because of rel="has"',
                PEAR_CHANNELFILE_ERROR_PHPNAME_IGNORED =>
                    'Dependency number %index% is a php dependency, name "%name%" will be ignored',
                PEAR_CHANNELFILE_ERROR_NO_DEPNAME =>
                    'Dependency number %index% has no name',
                PEAR_CHANNELFILE_ERROR_SUBCH_MIRROR =>
                    'Sub-channel "%subchannel%" has mirror "%mirror%", but only the main channel may have mirrors',
                PEAR_CHANNELFILE_ERROR_INVALID_MIRROR =>
                    'Invalid mirror name "%name%", mirror type %type%',
                PEAR_CHANNELFILE_ERROR_INVALID_MIRRORTYPE =>
                    'Invalid mirror type "%type%"',
                PEAR_CHANNELFILE_ERROR_INVALID =>
                    'Cannot generate xml, contents are invalid',
                PEAR_CHANNELFILE_ERROR_EMPTY_REGEX =>
                    'packagenameregex cannot be empty',
                PEAR_CHANNELFILE_ERROR_NO_FUNCTIONVERSION =>
                    '%parent% %protocol% function has no version',
                PEAR_CHANNELFILE_ERROR_NO_FUNCTIONNAME =>
                    '%parent% %protocol% function has no name',
                PEAR_CHANNELFILE_ERROR_NOVALIDATE_NAME =>
                    'Validation package has no name in <validatepackage> tag',
                PEAR_CHANNELFILE_ERROR_NOVALIDATE_VERSION =>
                    'Validation package "%package%" has no version',
                PEAR_CHANNELFILE_ERROR_NO_XMLRPC =>
                    'No <xmlrpc> tag is defined',
                PEAR_CHANNELFILE_ERROR_NO_SUBNAME =>
                    'No name attribute for subchannel',
                PEAR_CHANNELFILE_ERROR_NO_SUBSUMMARY =>
                    'No summary for subchannel "%name%"',
                PEAR_CHANNELFILE_ERROR_MIRROR_NOT_FOUND =>
                    'Mirror "%mirror%" does not exist',
            );
    }
    
    /**
     * Determine whether a mirror type is valid
     * @param string
     * @return boolean
     */
    function validMirrorType($type)
    {
        return in_array($type, $GLOBALS['_PEAR_CHANNELS_MIRROR_TYPES']);
    }

    /**
     * @param string contents of package.xml file
     * @return bool success of parsing
     */
    function fromXmlString($data)
    {
        if (preg_match('/<channel\s+version="([0-9]+\.[0-9]+)"/', $data, $channelversion)) {
            if (!in_array($channelversion[1], $this->_supportedVersions)) {
                $this->_stack->push(PEAR_CHANNELFILE_ERROR_INVALID_VERSION, 'error', array('version' => $channelversion[1]));
            }
            $channelversion = str_replace('.', '_', $channelversion[1]);
            $method = "_parse$channelversion";
            return $this->$method($data);
        } else {
            $this->_stack->push(PEAR_CHANNELFILE_ERROR_NO_VERSION, 'error', array('xml' => $data));
            return false;
        }
    }
    
    /**
     * @return array
     */
    function toArray()
    {
        if (!$this->_isValid && !$this->validate()) {
            return false;
        }
        return $this->_channelInfo;
    }
    
    /**
     * @param array
     * @static
     * @return PEAR_ChannelFile|false false if invalid
     */
    function fromArray($data, $compatibility = false, $stackClass = 'PEAR_ErrorStack')
    {
        $a = new PEAR_ChannelFile($compatibility, $stackClass);
        $a->_fromArray($data);
        if (!$a->validate()) {
            return false;
        }
        return $a;
    }
    
    /**
     * @param array
     * @access private
     */
    function _fromArray($data)
    {
        $this->_channelInfo = $data;
    }
    
    /**
     * Wrapper to {@link PEAR_ErrorStack::getErrors()}
     * @param boolean determines whether to purge the error stack after retrieving
     * @return array
     */
    function getErrors($purge = false)
    {
        return $this->_stack->getErrors($purge);
    }

    // {{{ _unIndent()

    /**
     * Unindent given string (?)
     *
     * @param string $str The string that has to be unindented.
     * @return string
     * @access private
     */
    function _unIndent($str)
    {
        // remove leading newlines
        $str = preg_replace('/^[\r\n]+/', '', $str);
        // find whitespace at the beginning of the first line
        $indent_len = strspn($str, " \t");
        $indent = substr($str, 0, $indent_len);
        $data = '';
        // remove the same amount of whitespace from following lines
        foreach (explode("\n", $str) as $line) {
            if (substr($line, 0, $indent_len) == $indent) {
                $data .= substr($line, $indent_len) . "\n";
            }
        }
        return $data;
    }

    // }}}
    /**
     * @param string contents of package.xml file, version 1.0
     * @return bool success of parsing
     */
    function _parse1_0($data)
    {
        require_once('PEAR/Dependency.php');
        if (PEAR_Dependency::checkExtension($error, 'xml')) {
            $this->_stack->push(PEAR_CHANNELFILE_ERROR_NO_XML_EXT, 'exception', array('error' => $error));
            return false;
        }
        $xp = @xml_parser_create();
        if (!$xp) {
            $this->_stack->push(PEAR_CHANNELFILE_ERROR_CANT_MAKE_PARSER, 'exception');
            return false;
        }
        xml_set_object($xp, $this);
        xml_set_element_handler($xp, '_channelElementStart_1_0', '_channelElementEnd_1_0');
        xml_set_character_data_handler($xp, '_channelInfoCdata_1_0');
        xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, false);

        $this->element_stack = array();
        $this->_channelInfo = array();
        $this->_isValid = true;
        $this->current_element = false;
        $this->_subchannelIndex =
        $this->_functionsIndex =
        $this->_mirrorIndex =
        $this->d_i = 0;
        $this->cdata = '';

        if (!xml_parse($xp, $data, 1)) {
            $code = xml_get_error_code($xp);
            $msg = sprintf("XML error: %s at line %d",
                           $str = xml_error_string($code),
                           $line = xml_get_current_line_number($xp));
            xml_parser_free($xp);
            $this->_channelInfo = array();
            $this->_validateError(PEAR_CHANNELFILE_ERROR_PARSER_ERROR,
                array('error' => $msg, 'code' => $code, 'message' => $str, 'line' => $line));
            return false;
        }

        xml_parser_free($xp);
        if (!$this->_isValid) {
            $this->_channelinfo = array();
            return false;
        }
        return $this->_channelInfo;
    }

    // Support for channel DTD v1.0:
    // {{{ _element_start_1_0()

    /**
     * XML parser callback for ending elements.  Used for version 1.0
     * channels.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _channelElementStart_1_0($xp, $name, $attribs)
    {
        array_push($this->element_stack, $name);
        $this->current_element = $name;
        $spos = sizeof($this->element_stack) - 2;
        $this->prev_element = ($spos >= 0) ? $this->element_stack[$spos] : '';
        $this->current_attributes = $attribs;
        $this->cdata = '';
        $method = "_handle${name}Open1_0";
        if (method_exists($this, $method)) {
            return $this->$method($attribs, $name);
        }
    }
    
    /**
     * @param array
     */
    function _handleChannelOpen1_0($attribs)
    {
        $this->_channelInfo['version'] = $attribs['version'];
    }

    function _handleDepsOpen1_0($attribs)
    {
        $this->d_i = 0;
        if (!$this->_subchannelIndex) {
            $this->_channelInfo['deps'] = array();
        } else {
            $this->_channelInfo['subchannels'][$this->_subchannelIndex]['deps'] = array();
        }
    }

    function _handleSubchannelsOpen1_0($attribs)
    {
        $this->_channelInfo['subchannels'] = array();
        $this->_subchannelIndex = 0;
    }

    function _handleMirrorsOpen1_0($attribs)
    {
        $this->_channelInfo['mirrors'] = array();
        $this->_subchannelIndex = 0;
    }

    function _handleDepOpen1_0($attribs)
    {
        // dependencies array index
        $this->d_i++;
        if (!$this->_subchannelIndex) {
            $this->_channelInfo['deps'][$this->d_i] = $attribs;
        } else {
            $this->_channelInfo['subchannels'][$this->_subchannelIndex]['deps'][$this->d_i] = $attribs;
        }
    }

    function _handleXmlrpcOpen1_0($attribs)
    {
        $this->_protocol = 'xmlrpc';
        if ($this->_mirrorIndex) {
            $this->_functionsIndex = 0;
            $this->_channelInfo['mirrors'][$this->_mirrorIndex]['protocols'][$this->_protocol]
                = $attribs;
        } else {
            $this->_functionsIndex = 0;
            $this->_channelInfo['protocols'][$this->_protocol] = $attribs;
        }
    }

    function _handleSoapOpen1_0($attribs)
    {
        $this->_protocol = 'soap';
        if ($this->_mirrorIndex) {
            $this->_functionsIndex = 0;
            $this->_channelInfo['mirrors'][$this->_mirrorIndex]['protocols'][$this->_protocol]
                = $attribs;
        } else {
            $this->_functionsIndex = 0;
            $this->_channelInfo['protocols'][$this->_protocol] = $attribs;
        }
    }

    function _handleFunctionOpen1_0($attribs)
    {
        $this->_functionsIndex++;
        if ($this->_mirrorIndex) {
            $this->_channelInfo['mirrors'][$this->_mirrorIndex]['protocols'][$this->_protocol]['functions'][$this->_functionsIndex] = $attribs;
        } else {
            $this->_channelInfo['protocols'][$this->_protocol]['functions'][$this->_functionsIndex] = $attribs;
        }
    }

    function _handleSubchannelOpen1_0($attribs)
    {
        $this->_subchannelIndex++;
        if (isset($attribs['name'])) {
            $attribs['name'] = strtolower($attribs['name']);
        }
        $this->_channelInfo['subchannels'][$this->_subchannelIndex] = $attribs;
    }

    function _handleMirrorOpen1_0($attribs)
    {
        $this->_mirrorIndex++;
        $this->_channelInfo['mirrors'][$this->_mirrorIndex] = $attribs;
    }

    // }}}
    // {{{ _element_end_1_0()

    /**
     * XML parser callback for ending elements.  Used for version 1.0
     * channels.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _channelElementEnd_1_0($xp, $name)
    {
        $data = trim($this->cdata);
        $method = "_handle${name}Close1_0";
        if (method_exists($this, $method)) {
            $this->$method($data);
        }
        array_pop($this->element_stack);
        $spos = sizeof($this->element_stack) - 1;
        $this->current_element = ($spos > 0) ? $this->element_stack[$spos] : '';
        $this->cdata = '';
    }

    // }}}

    function _handleNameClose1_0($data)
    {
        $this->_channelInfo['name'] = strtolower($data);
    }

    function _handleSummaryClose1_0($data)
    {
        if (!$this->_subchannelIndex) {
            $this->_channelInfo['summary'] = $data;
        } else {
            $this->_channelInfo['subchannels'][$this->_subchannelIndex]['summary'] = $data;
        }
    }

    function _handleFunctionClose1_0($data)
    {
        if ($data != '') {
            if ($this->_mirrorIndex) {
                $this->_channelInfo['mirrors'][$this->_mirrorIndex]['protocols'][$this->_protocol]['functions'][$this->_functionsIndex]['name'] = $data;
            } else {
                $this->_channelInfo['protocols'][$this->_protocol]['functions'][$this->_functionsIndex]['name'] = $data;
            }
        }
    }

    function _handleValidatepackageOpen1_0($attribs)
    {
        $this->_channelInfo['validatepackage'] = $attribs;
    }

    function _handleValidatepackageClose1_0($data)
    {
        $this->_channelInfo['validatepackage']['name'] = $data;
    }

    function _handleMirrorClose1_0($data)
    {
        if (!$this->_subchannelIndex) {
            $mirror = $this->_channelInfo['mirrors'][$this->_mirrorIndex];
        } else {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_SUBCH_MIRROR, array('mirror' => $data,
                'subchannel' => $this->_subchannelIndex));
        }
    }

    function _handleServerClose1_0($data)
    {
        if (!$this->_subchannelIndex) {
            $this->_channelInfo['server'] = $data;
        } else {
            $this->_channelInfo['subchannels'][$this->_subchannelIndex]['server'] = $data;
        }
    }

    function _handleDepClose1_0($data)
    {
        if ($data) {
            if (!$this->_subchannelIndex) {
                $this->_channelInfo['deps'][$this->d_i]['name'] = $data;
            } else {
                $this->_channelInfo['subchannels'][$this->_subchannelIndex]
                                  ['deps'][$this->d_i]['name'] = $data;
            }
        }
    }

    function _handleSubpackagesClose1_0($data)
    {
        $this->_subchannelIndex = 0;
    }

    function _handleFunctionsClose1_0($data)
    {
        $this->_functionsIndex = 0;
    }

    function _handleXmlrpcClose1_0($data)
    {
        unset($this->_protocol);
    }

    function _handleSoapClose1_0($data)
    {
        unset($this->_protocol);
    }

    function _handleDepsClose1_0($data)
    {
        $this->d_i = 0;
        // BC code
        $this->_channelInfo['release_deps'] = @$this->_channelInfo['deps'];
    }

    // {{{ _channelInfoCdata_1_0()

    /**
     * XML parser callback for character data.  Used for version 1.0
     * channels.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  character data
     *
     * @return void
     *
     * @access private
     */
    function _channelInfoCdata_1_0($xp, $data)
    {
        if (isset($this->cdata)) {
            $this->cdata .= $data;
        }
    }

    // }}}
    // {{{ fromXmlFile()

    /**
     * Parse a channel.xml file.  Expects the name of
     * a channel xml file as input.
     *
     * @param string  $descfile  name of channel xml file
     *
     * @return array  array with package information
     *
     * @access public
     *
     */
    function fromXmlFile($descfile)
    {
        if (!@is_file($descfile) || !is_readable($descfile) ||
             (!$fp = @fopen($descfile, 'r'))) {
            return $this->raiseError("Unable to open $descfile");
        }

        // read the whole thing so we only get one cdata callback
        // for each block of cdata
        $data = fread($fp, filesize($descfile));
        return $this->fromXmlString($data);
    }

    // }}}
    // {{{ fromAny()

    /**
     * Returns channel information from different sources
     *
     * This method is able to extract information about a package
     * from a .tgz archive or from a XML package definition file.
     *
     * @access public
     * @param  string Filename of the source ('channel.xml', '<package>.tgz')
     * @return string
     */
    function fromAny($info)
    {
        if (is_string($info) && file_exists($info)) {
            $tmp = substr($info, -4);
            if ($tmp == '.xml') {
                $info = $this->fromXmlFile($info);
            } else {
                $fp = fopen($info, "r");
                $test = fread($fp, 5);
                fclose($fp);
                if ($test == "<?xml") {
                    $info = $this->fromXmlFile($info);
                }
            }
            if (PEAR::isError($info)) {
                return $this->raiseError($info);
            }
        }
        if (is_string($info)) {
            $info = $this->fromXmlString($info);
        }
        return $info;
    }

    // }}}
    // {{{ toXml()

    /**
     * Return an XML document based on previous parsing and modifications
     *
     * @return string XML data
     *
     * @access public
     */
    function toXml()
    {
        if (!$this->_isValid && !$this->validate()) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID);
            return false;
        }
        $channelInfo = $this->_channelInfo;
        $ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
        $ret .= "<!DOCTYPE channel SYSTEM \"http://pear.php.net/dtd/channel-$channelInfo[version].dtd\">\n";
        $ret .= "<channel version=\"$channelInfo[version]\">
 <name>$channelInfo[name]</name>
 <summary>" . htmlspecialchars($channelInfo['summary'])."</summary>
";
        if (isset($channelInfo['validatepackage'])) {
            $ret .= ' <validatepackage version="' . $channelInfo['validatepackage']['version']. '">' . htmlspecialchars($channelInfo['validatepackage']['name']) .
                "</validatepackage>\n";
        }
        $ret .= " <protocols>\n";
        $ret .= $this->_makeXmlrpcXml($channelInfo['protocols']['xmlrpc'], '  ');
        if (isset($channelInfo['protocols']['soap'])) {
            $ret .= $this->_makeSoapXml($channelInfo['protocols']['soap'], '  ');
        }
        $ret .= " </protocols>\n";
        if (isset($channelInfo['mirrors'])) {
            $ret .= $this->_makeMirrorsXml($channelInfo);
        }
        $ret .= $this->_makeXml($channelInfo);
        $ret .= "</channel>\n";
        return str_replace("\r", "\n", str_replace("\r\n", "\n", $ret));
    }

    // }}}
    // {{{ _makeXmlrpcXml()
    /**
     * Generate the <xmlrpc> tag
     * @access private
     */
    function _makeXmlrpcXml($info, $indent)
    {
        $ret = $indent . "<xmlrpc host=\"$info[host]\"";
        if (isset($info['path'])) {
            $ret .= ' path="' . htmlspecialchars($info['path']) . '"';
        }
        if (isset($info['filename'])) {
            $ret .= ' path="' . htmlspecialchars($info['filename']) . '"';
        }
        $ret .= ">\n";
        $ret .= $this->_makeFunctionsXml($info['functions'], "$indent ");
        $ret .= $indent . "</xmlrpc>\n";
        return $ret;
    }

    // }}}
    // {{{ _makeSoapXml()
    /**
     * Generate the <soap> tag
     * @access private
     */
    function _makeSoapXml($info, $indent)
    {
        $ret = $indent . "<soap host=\"$info[host]\"";
        if (isset($info['path'])) {
            $ret .= ' path="' . htmlspecialchars($info['path']) . '"';
        }
        if (isset($info['filename'])) {
            $ret .= ' path="' . htmlspecialchars($info['filename']) . '"';
        }
        $ret .= ">\n";
        $ret .= $this->_makeFunctionsXml($info['functions'], "$indent ");
        $ret .= $indent . "</soap>\n";
        return $ret;
    }

    // }}}
    // {{{ _makeMirrorsXml()
    /**
     * Generate the <mirrors> tag
     * @access private
     */
    function _makeMirrorsXml($channelInfo)
    {
        $ret = " <mirrors>\n";
        foreach ($channelInfo['mirrors'] as $mirror) {
            $ret .= '  <mirror type="' . $mirror['type'];
            $ret .= '" name="' . $mirror['name'] . "\">\n";
            if (isset($mirror['protocols']['xmlrpc']) || isset($mirror['protocols']['soap'])) {
                $ret .= "   <protocols>\n";
                if (isset($mirror['protocols']['xmlrpc'])) {
                    $ret .= $this->_makeXmlrpcXml($mirror['protocols']['xmlrpc'], '    ');
                }
                if (isset($mirror['protocols']['soap'])) {
                    $ret .= $this->_makeSoapXml($mirror['protocols']['soap'], '    ');
                }
                $ret .= "   </protocols>\n";
                $ret .= "  </mirror>\n";
            } else {
                $ret .= "/>\n";
            }
        }
        $ret .= " </mirrors>\n";
        return $ret;
    }

    // }}}
    // {{{ _makeFunctionsXml()
    /**
     * Generate the <functions> tag
     * @access private
     */
    function _makeFunctionsXml($functions, $indent)
    {
        $ret = '';
        foreach ($functions as $function) {
            $ret .= "$indent<function version=\"$function[version]\"";
            $ret .= ">$function[name]</function>\n";
        }
        return $ret;
    }

    // }}}
    // {{{ _makeXml()

    /**
     * Generate part of an XML description with dependency/subchannel information.
     *
     * @param array  $channelInfo    array with dependency/subchannel information
     * @param bool   $subchannel  whether the result will be in a subchannel element
     *
     * @return string XML data
     *
     * @access private
     */
    function _makeXml($channelInfo, $subchannel = false)
    {
        $indent = $subchannel ? ' ' : '';
        $ret = '';
        if (!$subchannel && !empty($channelInfo['subchannels'])) {
            $ret .= " <subchannels>\n";
            foreach ($channelInfo['subchannels'] as $subchannel) {
                $ret .= "  <subchannel name=\"$subchannel[name]\">\n";
                $ret .= "   <summary>$subchannel[summary]</summary>\n";
                $ret .= "   <protocols>\n";
                if (isset($subchannel['protocols']['xmlrpc'])) {
                    $ret .= $this->_makeXmlrpcXml($subchannel['protocols']['xmlrpc'], '    ');
                }
                if (isset($subchannel['protocols']['soap'])) {
                    $ret .= $this->_makeSoapXml($subchannel['protocols']['soap'], '    ');
                }
                $ret .= "   </protocols>\n";
                if (isset($subchannel['deps'])) {
                    $ret .= $this->_makeXml($subchannel, true);
                }
                $ret .= "  </subchannel>\n";
            }
            $ret .= " </subchannels>\n";
        }
        if (isset($channelInfo['deps']) && sizeof($channelInfo['deps']) > 0) {
            if ($indent) {
                $indent .= ' ';
            }
            $ret .= "$indent <deps>\n";
            foreach ($channelInfo['deps'] as $dep) {
                $ret .= "$indent  <dep type=\"$dep[type]\" rel=\"$dep[rel]\"";
                if (isset($dep['version'])) {
                    $ret .= " version=\"$dep[version]\"";
                }
                if (isset($dep['name'])) {
                    $ret .= ">$dep[name]</dep>\n";
                } else {
                    $ret .= "/>\n";
                }
            }
            $ret .= "$indent </deps>\n";
        }
        return $ret;
    }

    // }}}
    /**
     * Validation error.  Also marks the object contents as invalid
     * @param error code
     * @param array error information
     * @access private
     */
    function _validateError($code, $params = array())
    {
        $this->_stack->push($code, 'error', $params);
        $this->_isValid = false;
    }

    /**
     * Validation warning.  Does not mark the object contents invalid.
     * @param error code
     * @param array error information
     * @access private
     */
    function _validateWarning($code, $params = array())
    {
        $this->_stack->push($code, 'warning', $params);
    }
    // {{{ validate()

    /**
     * Validate parsed file.
     *
     * @access public
     * @return boolean
     */
    function validate()
    {
        $this->_isValid = true;
        $info = $this->_channelInfo;
        if (empty($info['name'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_NAME);
        } elseif (!$this->validChannelName($info['name'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_NAME, array('name' => $info['name']));
        }
        if (empty($info['summary'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SUMMARY);
        } elseif (strpos(trim($info['summary']), "\n") !== false) {
            $this->_validateWarning(PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY, array('summary' => $info['summary']));
        }
        if (isset($info['validatepackage'])) {
            if (!isset($info['validatepackage']['name'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NOVALIDATE_NAME);
            }
            if (!isset($info['validatepackage']['version'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NOVALIDATE_VERSION, array('package' => @$info['validatepackage']['name']));
            }
        }
        if (!isset($info['protocols']['xmlrpc']) || !isset($info['protocols']['xmlrpc']['functions'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_XMLRPC);
        } else {
            if (empty($info['protocols']['xmlrpc']['host'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_HOST, array('protocol' => 'xmlrpc'));
            } elseif (!$this->validChannelServer($info['protocols']['xmlrpc']['host'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_HOST,
                    array('server' => $info['protocols']['xmlrpc']['host'], 'protocol' => 'xmlrpc'));
            }
            $this->validateFunctions('xmlrpc', $info['protocols']['xmlrpc']['functions']);
        }
        if (isset($info['protocols']['soap'])) {
            if (empty($info['protocols']['soap']['host'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_HOST, array('protocol' => 'soap'));
            } elseif (!$this->validChannelServer($info['protocols']['soap']['host'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_HOST,
                    array('server' => $info['protocols']['soap']['host'], 'protocol' => 'soap'));
            }
            $this->validateFunctions('soap', $info['protocols']['soap']['functions']);
        }
        if (isset($info['mirrors'])) {
            $i = 1;
            foreach ($info['mirrors'] as $mirror) {
                if (!$this->validMirrorType($mirror['type'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_MIRRORTYPE,
                        array('type' => $mirror['type']));
                }
                switch ($mirror['type']) {
                    case 'server' :
                        if (!$this->validChannelServer($mirror['name'])) {
                            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_MIRROR,
                                array('type' => 'server', 'name' => $mirror['name']));
                        }
                        if (isset($mirror['protocols']['xmlrpc'])) {
                            $this->validateFunctions('xmlrpc', $mirror['protocols']['xmlrpc']['functions'], $mirror['name']);
                        }
                        if (isset($mirror['protocols']['soap'])) {
                            $this->validateFunctions('soap', $mirror['protocols']['soap']['functions'], $mirror['name']);
                        }
                    break;
                }
            }
        }
        $this->validateDeps($info);
        if (isset($info['subchannels'])) {
            foreach ($info['subchannels'] as $subchannel) {
                if (!isset($subchannel['name']) || empty($subchannel['name'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SUBNAME);
                }
                if (!isset($subchannel['summary']) || empty($subchannel['summary'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SUBSUMMARY,
                        array('name' => @$subchannel['name']));
                }
                $this->validateDeps($subchannel);
                if (isset($subchannel['protocols']['xmlrpc'])) {
                    if (empty($subchannel['protocols']['xmlrpc']['host'])) {
                        $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_HOST, 'xmlrpc');
                    } elseif (!$this->validChannelServer($subchannel['protocols']['xmlrpc']['host'])) {
                        $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_HOST,
                            array('server' => $subchannel['protocols']['xmlrpc']['host'], 'protocol' => 'xmlrpc'));
                    }
                    $this->validateFunctions('xmlrpc', $subchannel['protocols']['xmlrpc']['functions']);
                }
                if (isset($subchannel['protocols']['soap'])) {
                    if (empty($subchannel['protocols']['soap']['host'])) {
                        $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_HOST, 'soap');
                    } elseif (!$this->validChannelServer($subchannel['protocols']['soap']['host'])) {
                        $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_HOST,
                            array('server' => $subchannel['protocols']['soap']['host'], 'protocol' => 'soap'));
                    }
                    $this->validateFunctions('soap', $subchannel['protocols']['soap']['functions']);
                }
            }
        }
        return $this->_isValid;
    }

    // }}}
    // {{{ validateDeps()

    /**
     * @param array the information containing deps
     * @param string the name of the parent element (mirror name, for instance)
     */
    function validateDeps($info)
    {
        if (isset($info['deps'])) {
            $i = 1;
            foreach ($info['deps'] as $d) {
                if (empty($d['type'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPTYPE,
                        array('index' => $i));
                } elseif (!in_array($d['type'], PEAR_Common::getDependencyTypes())) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_DEPTYPE,
                        array('index' => $i, 'type' => $d['type'], 'deps' => PEAR_Common::getDependencyTypes()));
                }
                if (empty($d['rel'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPREL,
                        array('index' => $i));
                } elseif (!in_array($d['rel'], PEAR_Common::getDependencyRelations())) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_DEPREL,
                        array('index' => $i, 'rel' => $d['rel'], 'rels' => PEAR_Common::getDependencyRelations()));
                }
                if ($d['rel'] != 'has' && empty($d['version'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPVERSION,
                        array('index' => $i));
                } elseif ($d['rel'] == 'has' && !empty($d['version'])) {
                    $this->_validateWarning(PEAR_CHANNELFILE_ERROR_DEPVERSION_IGNORED,
                        array('index' => $i, 'version' => $d['version']));
                }
                if ($d['type'] == 'php' && !empty($d['name'])) {
                    $this->_validateWarning(PEAR_CHANNELFILE_ERROR_PHPNAME_IGNORED,
                        array('index' => $i, 'name' => $d['name']));
                } elseif ($d['type'] != 'php' && empty($d['name'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPNAME,
                        array('index' => $i));
                }
                $i++;
            }
        }
    }

    // }}}
    // {{{ validateFunctions()

    /**
     * @param string xmlrpc or soap - protocol name this function applies to
     * @param array the functions
     * @param string the name of the parent element (mirror name, for instance)
     */
    function validateFunctions($protocol, $functions, $parent = '')
    {
        foreach ($functions as $function) {
            if (!isset($function['name']) || empty($function['name'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_FUNCTIONNAME,
                    array('parent' => $parent, 'protocol' => $protocol));
            }
            if (!isset($function['version']) || empty($function['version'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_FUNCTIONVERSION,
                    array('parent' => $parent, 'protocol' => $protocol));
            }
        }
    }

    // }}}
    // {{{ validChannelName()

    /**
     * Test whether a string contains a valid channel name.
     *
     * @param string $name the channel name to test
     *
     * @return bool
     *
     * @access public
     * @static
     */
    function validChannelName($name)
    {
        return (bool)preg_match(PEAR_CHANNELS_NAME_PREG, $name);
    }

    // }}}
    // {{{ validPackageVersion()

    /**
     * Test whether a string contains a valid package version.
     *
     * @param string $ver the package version to test
     *
     * @return bool
     *
     * @access public
     */
    function validChannelServer($server)
    {
        return (bool)preg_match(PEAR_CHANNELS_SERVER_PREG, $server);
    }
    // }}}

    /**
     * @return string|false
     */
    function getName()
    {
        if (isset($this->_channelInfo['name'])) {
            return $this->_channelInfo['name'];
        } else {
            return false;
        }
    }

    /**
     * @return string|false
     */
    function getServer($protocol)
    {
        if (isset($this->_channelInfo['protocols'][$protocol]['host'])) {
            return $this->_channelInfo['protocols'][$protocol]['host'];
        } else {
            return false;
        }
    }

    /**
     * @return string|false
     */
    function getPath($protocol)
    {
        if (isset($this->_channelInfo['protocols'][$protocol]['path'])) {
            return $this->_channelInfo['protocols'][$protocol]['path'];
        } else {
            return '/';
        }
    }

    /**
     * @return string|false
     */
    function getFilename($protocol)
    {
        if (isset($this->_channelInfo['protocols'][$protocol]['filename'])) {
            return $this->_channelInfo['protocols'][$protocol]['filename'];
        } else {
            return $protocol . '.php';
        }
    }

    /**
     * @return string|false
     */
    function getSummary()
    {
        if (isset($this->_channelInfo['summary'])) {
            return $this->_channelInfo['summary'];
        } else {
            return false;
        }
    }

    /**
     * @param string protocol type (xmlrpc, soap)
     * @param string Mirror name
     * @return array|false
     */
    function getFunctions($protocol, $mirror = false)
    {
        if ($mirror) {
            if (isset($this->_channelInfo['mirrors'])) {
                foreach ($this->_channelInfo['mirrors'] as $mirror) {
                    if ($mirror['name'] == $mirror) {
                        if (isset($mirror['protocols'][$protocol]['functions'])) {
                            return $mirror['protocols'][$protocol]['functions'];
                        }
                    }
                }
                return false;
            } else {
                return false;
            }
        } else {
            if (isset($this->_channelInfo['protocols'][$protocol]['functions'])) {
                return $this->_channelInfo['protocols'][$protocol]['functions'];
            } else {
                return false;
            }
        }
    }

    /**
     * @param string Protocol type
     * @param string Function name (null to return the
     *               first protocol of the type requested)
     * @param string Mirror name, if any
     * @return array
     */
     function getFunction($type, $name = null, $mirror = false)
     {
        $protocols = $this->getFunctions($type, $mirror);
        if (!$protocols) {
            return false;
        }
        foreach ($protocols as $protocol) {
            if ($name === null) {
                return $protocol;
            }
            if ($protocol['name'] != $name) {
                continue;
            }
            return $protocol;
        }
        return false;
     }

    /**
     * @param string protocol type
     * @param string protocol name
     * @param string version
     * @param string mirror name
     * @return boolean
     */
    function supports($type, $name = null, $version = '1.0', $mirror = false)
    {
        $protocols = $this->getFunctions($type, $mirror);
        if (!$protocols) {
            return false;
        }
        foreach ($protocols as $protocol) {
            if ($protocol['version'] != $version) {
                continue;
            }
            if ($name === null) {
                return true;
            }
            if ($protocol['name'] != $name) {
                continue;
            }
            return true;
        }
        return false;
    }
    
    /**
     * Empty all protocol definitions
     */
    function resetFunctions($type, $mirror = false)
    {
        if ($mirror) {
            if (isset($this->_channelInfo['mirrors'])) {
                foreach ($this->_channelInfo['mirrors'] as $i => $mirror) {
                    if ($mirror['name'] == $mirror) {
                        $this->_channelInfo['mirrors'][$i]['protocols'][$type]['functions'] = array();
                        return true;
                    }
                }
                return false;
            } else {
                return false;
            }
        } else {
            $this->_channelInfo['protocols'][$type]['functions'] = array();
            return true;
        }
    }

    /**
     * Set a channel's protocols to the protocols supported by pearweb
     */
    function setDefaultPEARProtocols($version = '1.0', $mirror = false)
    {
        switch ($version) {
            case '1.0' :
                unset($this->_channelInfo['protocols']['soap']);
                $this->resetFunctions('xmlrpc', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'logintest', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'package.listLatestReleases', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'package.listAll', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'package.info', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'package.getDownloadURL', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'channel.update', $mirror);
                $this->addFunction('xmlrpc', '1.0', 'channel.listAll', $mirror);
                return true;
            break;
            default :
                return false;
            break;
        }
    }
    
    /**
     * @return array|false
     */
    function getMirrors()
    {
        if (isset($this->_channelInfo['mirrors'])) {
            $ret = array();
            foreach ($this->_channelInfo['mirrors'] as $mirror) {
                $ret[$mirror['type']][] = $mirror;
            }
            return $ret;
        } else {
            return false;
        }
    }
    
    /**
     * @param string
     * @return string|false
     * @error PEAR_CHANNELFILE_ERROR_NO_NAME
     * @error PEAR_CHANNELFILE_ERROR_INVALID_NAME
     */
    function setName($name)
    {
        if (empty($name)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_NAME);
            return false;
        } elseif (!$this->validChannelName($name)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_NAME, array('name' => $name));
            return false;
        }
        $this->_channelInfo['name'] = $name;
        return true;
    }

    /**
     * @param string
     * @return string|false
     * @error PEAR_CHANNELFILE_ERROR_NO_SERVER
     * @error PEAR_CHANNELFILE_ERROR_INVALID_SERVER
     */
    function setServer($server, $protocol, $mirror = false)
    {
        if (empty($server)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SERVER);
            return false;
        } elseif (!$this->validChannelServer($server)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_SERVER, array('server' => $server));
            return false;
        }
        if ($mirror) {
            $found = false;
            foreach ($this->_channelInfo['mirrors'] as $i => $mir) {
                if ($mirror == $mir['name']) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_MIRROR_NOT_FOUND,
                    array('mirror' => $mirror));
                return false;
            }
            $this->_channelInfo['mirrors'][$i]['protocols'][$protocol]['host'] = $server;
            return true;
        }
        $this->_channelInfo['protocols'][$protocol]['host'] = $server;
        return true;
    }

    /**
     * @param string
     * @return boolean success
     * @error PEAR_CHANNELFILE_ERROR_NO_SUMMARY
     * @warning PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY
     */
    function setSummary($summary)
    {
        if (empty($summary)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SUMMARY);
            return false;
        } elseif (strpos(trim($summary), "\n") !== false) {
            $this->_validateWarning(PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY, array('summary' => $summary));
        }
        $this->_channelInfo['summary'] = $summary;
        return true;
    }
    
    /**
     * Set the package validation object if it differs from PEAR's default
     * The class must be includeable via changing _ in the classname to path separator,
     * but no checking of this is made.
     * @param string|false pass in false to reset to the default packagename regex
     * @return boolean success
     */
    function setValidationPackage($validateclass, $version)
    {
        if (empty($validateclass)) {
            unset($this->_channelInfo['validatepackage']);
        }
        $this->_channelInfo['validatepackage'] = array('version' => $version, 'name' => $validateclass);
    }

    /**
     * Get the regular expression needed to validate "channel::package[-version/state]"
     * @deprecated will be switching all of this over to the validation object soon
     * @return string
     */
    function getChannelPackageDownloadRegex()
    {
        $val = &$this->getValidationObject();
        if ($val) {
            return $val->getChannelPackageDownloadRegex();
        } else {
            return _PEAR_COMMON_CHANNEL_DOWNLOAD_PREG;
        }
    }

    /**
     * Get the regular expression needed to validate "package[-version/state]"
     * @deprecated will be switching all of this over to the validation object soon
     * @return string
     */
    function getPackageDownloadRegex()
    {
        $val = &$this->getValidationObject();
        if ($val) {
            return $val->getPackageDownloadRegex();
        } else {
            return _PEAR_COMMON_PACKAGE_DOWNLOAD_PREG;
        }
    }

    /**
     * validate a package name for this channel
     * @deprecated will be switching all of this over to the validation object soon
     * @return bool
     */
    function validPackageName($name)
    {
        $val = &$this->getValidationObject();
        if ($val) {
            return $val->validPackageName($name);
        } else {
            return (bool)preg_match(PEAR_COMMON_PACKAGE_NAME_PREG, $name);
        }
    }

    /**
     * Add a protocol to the provides section
     * @param string protocol type
     * @param string protocol version
     * @param string protocol name, if any
     * @param string mirror name, if this is a mirror's protocol
     */
    function addFunction($type, $version, $name = '', $mirror = false)
    {
        if ($mirror) {
            return $this->addMirrorFunction($mirror, $type, $version, $name);
        }
        $set = array('version' => $version, 'name' => $name, );
        if (!isset($this->_channelInfo['protocols'][$type]['functions'])) {
            $this->_channelInfo['protocols'][$type]['functions'] = array(1 => $set);
            $this->_isValid = false;
            return;
        }
        $this->_channelInfo['protocols'][$type]['functions'][] = $set;
    }
    /**
     * Add a protocol to a mirror's provides section
     * @param string mirror name (server)
     * @param string protocol type
     * @param string protocol version
     * @param string protocol name, if any
     */
    function addMirrorFunction($mirror, $type, $version, $name = '')
    {
        $found = false;
        foreach ($this->_channelInfo['mirrors'] as $i => $mir) {
            if ($mirror == $mir['name']) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_MIRROR_NOT_FOUND,
                array('mirror' => $mirror));
            return false;
        }
        $set = array('version' => $version, 'name' => $name, );
        if (!isset($this->_channelInfo['mirrors'][$i]['protocols'][$type]['functions'])) {
            $this->_channelInfo['mirrors'][$i]['protocols'][$type]['functions'] = array(1 => $set);
            $this->_isValid = false;
            return;
        }
        $this->_channelInfo['mirrors'][$i]['protocols'][$type]['functions'][] = $set;
    }
    
    /**
     * @param string mirror type
     * @param string mirror name
     */
    function addMirror($type, $name)
    {
        $set = array('type' => $type, 'name' => $name);
        if (!isset($this->_channelInfo['mirrors'])) {
            $this->_channelInfo['mirrors'] = array(1 => $set);
            return;
        }
        $this->_channelInfo['mirrors'][] = $set;
    }

    /**
     * Retrieve the name of the validation package for this channel
     * @return string|false
     */
    function getValidationPackage()
    {
        if (!$this->_isValid || !$this->validate()) {
            return false;
        }
        if (!isset($this->_channelInfo['validatepackage'])) {
            return array('version' => 'default', 'name' => 'PEAR_Validate');
        }
        return $this->_channelInfo['validatepackage'];
    }

    /**
     * Retrieve the object that can be used for custom validation
     * @return PEAR_Validate|false false is returned if the validation package
     *         cannot be located
     */
    function &getValidationObject()
    {
        if (!$this->_isValid || !$this->validate()) {
            $a = false;
            return $a;
        }
        if (isset($this->_channelInfo['validatepackage'])) {
            if (!class_exists($this->_channelInfo['validatepackage']['name'])) {
                if ($this->isIncludeable(str_replace('_', '/', $this->_channelInfo['validatepackage']['name']) . '.php')) {
                    include_once str_replace('_', '/', $this->_channelInfo['validatepackage']['name']) . '.php';
                    $val = &new $this->_channelInfo['validatepackage']['name'];
                } else {
                    return false;
                }
            } else {
                $val = &new $this->_channelInfo['validatepackage']['name'];
            }
        } else {
            include_once 'PEAR/Validate.php';
            $val = &new PEAR_Validate;
        }
        return $val;
    }

    function isIncludeable($path)
    {
        $possibilities = explode(PATH_SEPARATOR, get_include_path());
        foreach ($possibilities as $dir) {
            if (file_exists($dir . DIRECTORY_SEPARATOR . $path)
                  && is_readable($dir . DIRECTORY_SEPARATOR . $path)) {
                return true;
            }
        }
        return false;
    }

    /**
     * This function is used by the channel updater and retrieves a value set by
     * the registry, or the current time if it has not been set
     * @return string
     */
    function lastModified()
    {
        if (isset($this->_channelInfo['_lastmodified'])) {
            return $this->_channelInfo['_lastmodified'];
        }
        return time();
    }
}
?>