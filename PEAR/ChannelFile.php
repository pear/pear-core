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
 * Error code when channel server is missing
 */
define('PEAR_CHANNELFILE_ERROR_NO_SERVER', 10);
/**
 * Error code when channel server is invalid
 */
define('PEAR_CHANNELFILE_ERROR_INVALID_SERVER', 11);
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
 * Error code when a <provides> <protocol> tag has no type attribute
 */
define('PEAR_CHANNELFILE_ERROR_NO_PROTOCOLTYPE', 25);
/**
 * Error code when a <provides> <protocol> tag has no version attribute
 */
define('PEAR_CHANNELFILE_ERROR_NO_PROTOCOLVERSION', 26);
/**
 * Error code when a <validatepackage> tag has no name
 */
define('PEAR_CHANNELFILE_ERROR_NOVALIDATE_NAME', 27);
/**
 * Error code when a <validatepackage> tag has no version attribute
 */
define('PEAR_CHANNELFILE_ERROR_NOVALIDATE_VERSION', 28);
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
                PEAR_CHANNELFILE_ERROR_NO_SERVER =>
                    'Missing channel server',
                PEAR_CHANNELFILE_ERROR_INVALID_SERVER =>
                    'Server name "%server%" is invalid',
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
                PEAR_CHANNELFILE_ERROR_NO_PROTOCOLTYPE =>
                    'provides protocol has no type',
                PEAR_CHANNELFILE_ERROR_NO_PROTOCOLVERSION =>
                    'provides protocol has no version',
                PEAR_CHANNELFILE_ERROR_NOVALIDATE_NAME =>
                    'Validation package has no name in <validatepackage> tag',
                PEAR_CHANNELFILE_ERROR_NOVALIDATE_VERSION =>
                    'Validation package "%package%" has no version',
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
        $this->_providesIndex =
        $this->_mirrorIndex =
        $this->d_i = 0;
        $this->cdata = '';
        $this->_validChannelFile = false;

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
        switch ($name) {
            case 'channel' :
                $this->_channelInfo['version'] = $attribs['version'];
                break;
            case 'deps':
                $this->d_i = 0;
                if (!$this->_subchannelIndex) {
                    $this->_channelInfo['deps'] = array();
                } else {
                    $this->_channelInfo['subchannels'][$this->_subchannelIndex]['deps'] = array();
                }
                break;
            case 'subchannels':
                $this->_channelInfo['subchannels'] = array();
                $this->_subchannelIndex = 0;
                break;
            case 'mirrors':
                $this->_channelInfo['mirrors'] = array();
                $this->_mirrorIndex = 0;
                break;
            case 'provides' :
                if (isset($this->_channelInfo['mirrors'][$this->_mirrorIndex])) {
                    $this->_channelInfo['mirrors'][$this->_mirrorIndex]['provides'] = array();
                } else {
                    $this->_channelInfo['provides'] = array();
                }
                $this->_providesIndex = 0;
                break;
            case 'dep':
                // dependencies array index
                $this->d_i++;
                if (empty($attribs['type'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPTYPE,
                        array('index' => $this->d_i));
                } elseif (!in_array($attribs['type'], PEAR_Common::getDependencyTypes())) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_DEPTYPE,
                        array('index' => $this->d_i, 'type' => $attribs['type'],
                            'deps' => PEAR_Common::getDependencyTypes()));
                }
                if (empty($attribs['rel'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPREL,
                        array('index' => $this->d_i));
                } elseif (!in_array($attribs['rel'], PEAR_Common::getDependencyRelations())) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_DEPREL,
                        array('index' => $this->d_i, 'rel' => $attribs['rel'],
                            'rels' => PEAR_Common::getDependencyRelations()));
                }
                if ($attribs['rel'] != 'has' && empty($attribs['version'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPVERSION,
                        array('index' => $this->d_i));
                } elseif ($attribs['rel'] == 'has' && !empty($attribs['version'])) {
                    $this->_validateWarning(PEAR_CHANNELFILE_ERROR_DEPVERSION_IGNORED,
                        array('index' => $this->d_i, 'version' => $attribs['version']));
                }
                if (!$this->_subchannelIndex) {
                    $this->_channelInfo['deps'][$this->d_i] = $attribs;
                } else {
                    $this->_channelInfo['subchannels'][$this->_subchannelIndex]['deps'][$this->d_i] = $attribs;
                }
                break;
            case 'protocol':
                if (!isset($attribs['type'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_PROTOCOLTYPE);
                }
                if (!isset($attribs['version'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_PROTOCOLVERSION);
                }
                $this->_providesIndex++;
                if ($this->_mirrorIndex) {
                    $this->_channelInfo['mirrors'][$this->_mirrorIndex]['provides'][$this->_providesIndex] = $attribs;
                } else {
                    $this->_channelInfo['provides'][$this->_providesIndex] = $attribs;
                }
                break;
            case 'subchannel':
                $this->_subchannelIndex++;
                if (isset($attribs['name'])) {
                    $attribs['name'] = strtolower($attribs['name']);
                }
                $this->_channelInfo['subchannels'][$this->_subchannelIndex] = $attribs;
                break;
            case 'mirror':
                $this->_mirrorIndex++;
                $this->_channelInfo['mirrors'][$this->_mirrorIndex] = $attribs;
                break;
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

    function _handleProvidesOpen1_0($attribs)
    {
        if (isset($this->_channelInfo['mirrors'][$this->_mirrorIndex])) {
            $this->_channelInfo['mirrors'][$this->_mirrorIndex]['provides'] = array();
        } else {
            $this->_channelInfo['provides'] = array();
        }
        $this->_providesIndex = 0;
    }

    function _handleDepOpen1_0($attribs)
    {
        // dependencies array index
        $this->d_i++;
        if (empty($attribs['type'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPTYPE,
                array('index' => $this->d_i));
        } elseif (!in_array($attribs['type'], PEAR_Common::getDependencyTypes())) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_DEPTYPE,
                array('index' => $this->d_i, 'type' => $attribs['type'],
                    'deps' => PEAR_Common::getDependencyTypes()));
        }
        if (empty($attribs['rel'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPREL,
                array('index' => $this->d_i));
        } elseif (!in_array($attribs['rel'], PEAR_Common::getDependencyRelations())) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_DEPREL,
                array('index' => $this->d_i, 'rel' => $attribs['rel'],
                    'rels' => PEAR_Common::getDependencyRelations()));
        }
        if ($attribs['rel'] != 'has' && empty($attribs['version'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPVERSION,
                array('index' => $this->d_i));
        } elseif ($attribs['rel'] == 'has' && !empty($attribs['version'])) {
            $this->_validateWarning(PEAR_CHANNELFILE_ERROR_DEPVERSION_IGNORED,
                array('index' => $this->d_i, 'version' => $attribs['version']));
        }
        if (!$this->_subchannelIndex) {
            $this->_channelInfo['deps'][$this->d_i] = $attribs;
        } else {
            $this->_channelInfo['subchannels'][$this->_subchannelIndex]['deps'][$this->d_i] = $attribs;
        }
    }

    function _handleProtocolOpen1_0($attribs)
    {
        if (!isset($attribs['type'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_PROTOCOLTYPE);
        }
        if (!isset($attribs['version'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_PROTOCOLVERSION);
        }
        $this->_providesIndex++;
        if ($this->_mirrorIndex) {
            $this->_channelInfo['mirrors'][$this->_mirrorIndex]['provides'][$this->_providesIndex] = $attribs;
        } else {
            $this->_channelInfo['provides'][$this->_providesIndex] = $attribs;
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
            return $this->$method($data);
        }
        switch ($name) {
            case 'name' :
                if ($data == '') {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_NAME);
                } elseif (!$this->validChannelName($data)) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_NAME, array('name' => $data));
                }
                $this->_channelInfo['name'] = strtolower($data);
                break;
            case 'summary' :
                if ($data == '') {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SUMMARY);
                } elseif (strpos($data, "\n") !== false) {
                    $this->_validateWarning(PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY, array('summary' => $data));
                }
                if (!$this->_subchannelIndex) {
                    $this->_channelInfo['summary'] = $data;
                } else {
                    $this->_channelInfo['subchannels'][$this->_subchannelIndex]['summary'] = $data;
                }
                break;
            case 'protocol' :
                if ($data != '') {
                    if ($this->_mirrorIndex) {
                        $this->_channelInfo['mirrors'][$this->_mirrorIndex]['provides'][$this->_providesIndex]['name'] = $data;
                    } else {
                        $this->_channelInfo['provides'][$this->_providesIndex]['name'] = $data;
                    }
                }
                break;
            case 'validatepackage' :
                $this->_channelInfo['validatepackage'] = $data;
                break;
            case 'mirror' :
                if (!$this->_subchannelIndex) {
                    $mirror = $this->_channelInfo['mirrors'][$this->_mirrorIndex];
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
                        break;
                    }
                } else {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_SUBCH_MIRROR, array('mirror' => $data,
                        'subchannel' => $this->_subchannelIndex));
                }
                break;
            case 'server' :
                if (empty($data)) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SERVER);
                } elseif (!$this->validChannelServer($data)) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_SERVER,
                        array('server' => $data));
                }
                if (!$this->_subchannelIndex) {
                    $this->_channelInfo[$name] = $data;
                } else {
                    $this->_channelInfo['subchannels'][$this->_subchannelIndex][$name] = $data;
                }
                break;
            case 'dep' :
                if ($data) {
                    if (!$this->_subchannelIndex) {
                        $this->_channelInfo['deps'][$this->d_i]['name'] = $data;
                    } else {
                        $this->_channelInfo['subchannels'][$this->_subchannelIndex]
                                          ['deps'][$this->d_i]['name'] = $data;
                    }
                }
                $d = $this->_subchannelIndex ?
                        $this->_channelInfo['subchannels'][$this->_subchannelIndex]
                                           ['deps'][$this->d_i] :
                        $this->_channelInfo['deps'][$this->d_i];
                if ($d['type'] == 'php' && !empty($d['name'])) {
                    $this->_validateWarning(PEAR_CHANNELFILE_ERROR_PHPNAME_IGNORED,
                        array('index' => $this->d_i, 'name' => $d['name']));
                } elseif ($d['type'] != 'php' && empty($d['name'])) {
                    $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPNAME,
                        array('index' => $this->d_i));
                }
                break;
            case 'subchannels' :
                $this->_subchannelIndex = 0;
                break;
            case 'deps' :
                $this->d_i = 0;
                // BC code
                $this->_channelInfo['release_deps'] = $this->_channelInfo['deps'];
                break;
        }
        array_pop($this->element_stack);
        $spos = sizeof($this->element_stack) - 1;
        $this->current_element = ($spos > 0) ? $this->element_stack[$spos] : '';
        $this->cdata = '';
    }

    // }}}

    function _handleNameClose1_0($data)
    {
        if ($data == '') {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_NAME);
        } elseif (!$this->validChannelName($data)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_NAME, array('name' => $data));
        }
        $this->_channelInfo['name'] = strtolower($data);
    }

    function _handleSummaryClose1_0($data)
    {
        if ($data == '') {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SUMMARY);
        } elseif (strpos($data, "\n") !== false) {
            $this->_validateWarning(PEAR_CHANNELFILE_ERROR_MULTILINE_SUMMARY, array('summary' => $data));
        }
        if (!$this->_subchannelIndex) {
            $this->_channelInfo['summary'] = $data;
        } else {
            $this->_channelInfo['subchannels'][$this->_subchannelIndex]['summary'] = $data;
        }
    }

    function _handleProtocolClose1_0($data)
    {
        if ($data != '') {
            if ($this->_mirrorIndex) {
                $this->_channelInfo['mirrors'][$this->_mirrorIndex]['provides'][$this->_providesIndex]['name'] = $data;
            } else {
                $this->_channelInfo['provides'][$this->_providesIndex]['name'] = $data;
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
                break;
            }
        } else {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_SUBCH_MIRROR, array('mirror' => $data,
                'subchannel' => $this->_subchannelIndex));
        }
    }

    function _handleServerClose1_0($data)
    {
        if (empty($data)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SERVER);
        } elseif (!$this->validChannelServer($data)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_SERVER,
                array('server' => $data));
        }
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
        $d = $this->_subchannelIndex ?
                $this->_channelInfo['subchannels'][$this->_subchannelIndex]
                                   ['deps'][$this->d_i] :
                $this->_channelInfo['deps'][$this->d_i];
        if ($d['type'] == 'php' && !empty($d['name'])) {
            $this->_validateWarning(PEAR_CHANNELFILE_ERROR_PHPNAME_IGNORED,
                array('index' => $this->d_i, 'name' => $d['name']));
        } elseif ($d['type'] != 'php' && empty($d['name'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_DEPNAME,
                array('index' => $this->d_i));
        }
    }

    function _handleSubpackagesClose1_0($data)
    {
        $this->_subchannelIndex = 0;
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
        $ret .= "<!DOCTYPE package SYSTEM \"http://pear.php.net/dtd/channel-1.0\">\n";
        $ret .= "<channel version=\"1.0\">
 <name>$channelInfo[name]</name>
 <summary>" . htmlspecialchars($channelInfo['summary'])."</summary>
 <server>" . htmlspecialchars($channelInfo['server']) . "</server>
";
        if (isset($channelInfo['provides'])) {
            $ret .= $this->_makeProvidesXml($channelInfo['provides'], ' ');
        }
        if (isset($channelInfo['validatepackage'])) {
            $ret .= ' <validatepackage version="' . $channelInfo['validatepackage']['version']. '">' . htmlspecialchars($channelInfo['validatepackage']['name']) .
                "</validatepackage>\n";
        }
        if (isset($channelInfo['mirrors'])) {
            $ret .= $this->_makeMirrorsXml($channelInfo);
        }
        $ret .= $this->_makeXml($channelInfo);
        $ret .= "</channel>\n";
        return str_replace("\r", "\n", str_replace("\r\n", "\n", $ret));
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
            $ret .= '" name="' . $mirror['name'] . '"';
            if (isset($mirror['provides'])) {
                $ret .= ">\n" . $this->_makeProvidesXml($mirror['provides'], '   ');
                $ret .= "  </mirror>\n";
            } else {
                $ret .= "/>\n";
            }
        }
        $ret .= " </mirrors>\n";
        return $ret;
    }

    // }}}
    // {{{ _makeProvidesXml()
    /**
     * Generate the <provides> tag
     * @access private
     */
    function _makeProvidesXml($provides, $indent)
    {
        $ret = "$indent<provides>\n";
        foreach ($provides as $protocol) {
            $ret .= "$indent <protocol type=\"$protocol[type]\" version=\"$protocol[version]\"";
            if (isset($protocol['name'])) {
                $ret .= ">$protocol[name]</protocol>\n";
            } else {
                $ret .= "/>\n";
            }
        }
        $ret .= "$indent</provides>\n";
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
                $ret .= "  <subchannel name=\"$subchannel[name]\"";
                if (isset($subchannel['server'])) {
                    $ret .= " server=\"$subchannel[server]\">\n";
                }
                $ret .= "   <summary>$subchannel[summary]</summary>\n";
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
     * Validate XML channel definition file.
     *
     * @param  string $info Filename of the package archive or of the
     *                package definition file
     * @param  array $errors Array that will contain the errors
     * @param  array $warnings Array that will contain the warnings
     * @param  string $dir_prefix (optional) directory where source files
     *                may be found, or empty if they are not available
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
        if (empty($info['server'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SERVER);
        } elseif (!$this->validChannelServer($info['server'])) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_SERVER, array('server' => $info['server']));
        }
        if (isset($info['validatepackage'])) {
            if (!isset($info['validatepackage']['name'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NOVALIDATE_NAME);
            }
            if (!isset($info['validatepackage']['version'])) {
                $this->_validateError(PEAR_CHANNELFILE_ERROR_NOVALIDATE_VERSION, array('package' => @$info['validatepackage']['name']));
            }
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
                    break;
                }
            }
        }
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
        return $this->_isValid;
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
    function getServer()
    {
        if (isset($this->_channelInfo['server'])) {
            return $this->_channelInfo['server'];
        } else {
            return false;
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
     * @param string Mirror name
     * @return array|false
     */
    function getProtocols($mirror = false)
    {
        if ($mirror) {
            if (isset($this->_channelInfo['mirrors'])) {
                foreach ($this->_channelInfo['mirrors'] as $mirror) {
                    if ($mirror['name'] == $mirror) {
                        if (isset($mirror['provides'])) {
                            return $mirror['provides'];
                        }
                    }
                }
                return false;
            } else {
                return false;
            }
        } else {
            if (isset($this->_channelInfo['provides'])) {
                return $this->_channelInfo['provides'];
            } else {
                return false;
            }
        }
    }

    /**
     * @param string Protocol type
     * @param string Protocol name (null to return the
     *               first protocol of the type requested)
     * @param string Mirror name, if any
     * @return array
     */
     function getProtocol($type, $name = null, $mirror = false)
     {
        $protocols = $this->getProtocols($mirror);
        if (!$protocols) {
            return false;
        }
        foreach ($protocols as $protocol) {
            if ($protocol['type'] != $type) {
                continue;
            }
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
        $protocols = $this->getProtocols($mirror);
        if (!$protocols) {
            return false;
        }
        foreach ($protocols as $protocol) {
            if ($protocol['type'] != $type) {
                continue;
            }
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
    function resetProtocols($mirror = false)
    {
        if ($mirror) {
            if (isset($this->_channelInfo['mirrors'])) {
                foreach ($this->_channelInfo['mirrors'] as $i => $mirror) {
                    if ($mirror['name'] == $mirror) {
                        $this->_channelInfo['mirrors'][$i]['provides'] = array();
                        return true;
                    }
                }
                return false;
            } else {
                return false;
            }
        } else {
            $this->_channelInfo['provides'] = array();
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
                $this->resetProtocols($mirror);
                $this->addProtocol('xml-rpc', '1.0', 'logintest', $mirror);
                $this->addProtocol('xml-rpc', '1.0', 'package.listLatestReleases', $mirror);
                $this->addProtocol('xml-rpc', '1.0', 'package.listAll', $mirror);
                $this->addProtocol('xml-rpc', '1.0', 'package.info', $mirror);
                $this->addProtocol('xml-rpc', '1.0', 'package.getDownloadURL', $mirror);
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
                if (isset($mirror['provides'])) {
                    $provides = $mirror['provides'];
                } else {
                    $provides = array();
                }
                $ret[$mirror['type']][] = array('name' => $mirror['name'], 'provides' => $provides);
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
    function setServer($server)
    {
        if (empty($server)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_NO_SERVER);
            return false;
        } elseif (!$this->validChannelServer($server)) {
            $this->_validateError(PEAR_CHANNELFILE_ERROR_INVALID_SERVER, array('server' => $server));
            return false;
        }
        $this->_channelInfo['server'] = $server;
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
    function addProtocol($type, $version, $name = '', $mirror = false)
    {
        if ($mirror) {
            return $this->addMirrorProtocol($mirror, $type, $version, $name);
        }
        $set = array('type' => $type, 'version' => $version);
        if ($name) {
            $set['name'] = $name;
        }
        if (!isset($this->_channelInfo['provides'])) {
            $this->_channelInfo['provides'] = array(1 => $set);
            return;
        }
        $this->_channelInfo['provides'][] = $set;
    }
    /**
     * Add a protocol to a mirror's provides section
     * @param string mirror name (server)
     * @param string protocol type
     * @param string protocol version
     * @param string protocol name, if any
     */
    function addMirrorProtocol($mirror, $type, $version, $name = '')
    {
        foreach ($this->_channelInfo['mirrors'] as $i => $mir) {
            if ($mirror == $mir['name']) {
                break;
            }
        }
        $set = array('type' => $type, 'version' => $version);
        if ($name) {
            $set['name'] = $name;
        }
        if (!isset($this->_channelInfo['mirrors'][$i]['provides'])) {
            $this->_channelInfo['mirrors'][$i]['provides'] = array(1 => $set);
            return;
        }
        $this->_channelInfo['mirrors'][$i]['provides'][] = $set;
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
     * Retrieve the object that can be used for custom validation
     * @return PEAR_Validate|false false is returned if the validation package
     *         cannot be located
     */
    function &getValidationObject()
    {
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
}
?>