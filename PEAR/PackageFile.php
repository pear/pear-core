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
 * Error code if the package.xml <package> tag does not contain a valid version
 */
define('PEAR_PACKAGEFILE_ERROR_NO_PACKAGEVERSION', 1);
/**
 * Error code if the package.xml <package> tag version is not supported (version 1.0 and 1.1 are the only supported versions,
 * currently
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_PACKAGEVERSION', 2);

/**
 * Error code if parsing is attempted with no xml extension
 */
define('PEAR_PACKAGEFILE_ERROR_NO_XML_EXT', 3);

/**
 * Error code if creating the xml parser resource fails
 */
define('PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER', 4);

/**
 * Error code used for all sax xml parsing errors
 */
define('PEAR_PACKAGEFILE_ERROR_PARSER_ERROR', 5);

/**
 * Error code used when there is no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_NAME', 6);

/**
 * Error code when a package name is not valid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_NAME', 7);

/**
 * Error code used when no summary is parsed
 */
define('PEAR_PACKAGEFILE_ERROR_NO_SUMMARY', 8);

/**
 * Error code for summaries that are more than 1 line
 */
define('PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY', 9);

/**
 * Error code used when no description is present
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION', 10);

/**
 * Error code used when no license is present
 */
define('PEAR_PACKAGEFILE_ERROR_NO_LICENSE', 11);

/**
 * Error code used when a <version> version number is not present
 */
define('PEAR_PACKAGEFILE_ERROR_NO_VERSION', 12);

/**
 * Error code used when a <version> version number is invalid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_VERSION', 13);

/**
 * Error code when release state is missing
 */
define('PEAR_PACKAGEFILE_ERROR_NO_STATE', 14);

/**
 * Error code when release state is invalid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_STATE', 15);

/**
 * Error code when release state is missing
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DATE', 16);

/**
 * Error code when release state is invalid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DATE', 17);

/**
 * Error code when no release notes are found
 */
define('PEAR_PACKAGEFILE_ERROR_NO_NOTES', 18);

/**
 * Error code when no maintainers are found
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTAINERS', 19);

/**
 * Error code when a maintainer has no handle
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE', 20);

/**
 * Error code when a maintainer has no handle
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE', 21);

/**
 * Error code when a maintainer has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME', 22);

/**
 * Error code when a maintainer has no email
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL', 23);

/**
 * Error code when a maintainer has no handle
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE', 24);

/**
 * Error code when a dependency is not a PHP dependency, but has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPNAME', 25);

/**
 * Error code when a dependency has no type (pkg, php, etc.)
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE', 26);

/**
 * Error code when a dependency has no relation (lt, ge, has, etc.)
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPREL', 27);

/**
 * Error code when a dependency is not a 'has' relation, but has no version
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION', 28);

/**
 * Error code when a dependency has an invalid relation
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL', 29);

/**
 * Error code when a dependency has an invalid type
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE', 30);

/**
 * Error code when a dependency has an invalid optional option
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL', 31);

/**
 * Error code when a dependency is a pkg dependency, and has an invalid package name
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME', 32);

/**
 * Error code when a dependency has a channel="foo" attribute, and foo is not a registered channel
 */
define('PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL', 33);

/**
 * Error code when rel="has" and version attribute is present.
 */
define('PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED', 34);

/**
 * Error code when type="php" and dependency name is present
 */
define('PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED', 35);

/**
 * Error code when a configure option has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_CONFNAME', 36);

/**
 * Error code when a configure option has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT', 37);

/**
 * Error code when a file in the filelist has an invalid role
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE', 38);

/**
 * Error code when a file in the filelist has no role
 */
define('PEAR_PACKAGEFILE_ERROR_NO_FILEROLE', 39);

/**
 * Error code when analyzing a php source file that has parse errors
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE', 40);

/**
 * Error code when analyzing a php source file reveals a source element
 * without a package name prefix
 */
define('PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX', 41);

/**
 * Error code when an unknown channel is specified
 */
define('PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL', 42);

/**
 * Error code when no files are found in the filelist
 */
define('PEAR_PACKAGEFILE_ERROR_NO_FILES', 43);

/**
 * Abstraction for the package.xml package description file
 *
 * @author Gregory Beaver <cellog@php.net>
 * @version $Revision$
 * @stability devel
 * @package PEAR
 */
class PEAR_PackageFile
{
    /**
     * @access private
     * @var PEAR_ErrorStack
     * @access private
     */
    var $_stack;
    
    /**
     * Supported package.xml versions, for parsing
     * @var array
     * @access private
     */
    var $_supportedVersions = array('1.0', '1.1');
    
    /**
     * Parsed package information
     * @var array
     * @access private
     */
    var $_packageInfo;
    
    /**
     * A registry object, used to access the package name validation regex for non-standard channels
     * @var PEAR_Registry
     * @access private
     */
    var $_registry;
    
    /**
     * Debug level, for PEAR installer
     */
    var $debug = 1;
    
    var $ui = false;
    
    /**
     * @param bool determines whether to return a PEAR_Error object, or use the PEAR_ErrorStack
     * @param string Name of Error Stack class to use.  This allows inheritance with stacks that have the
     *        same constructor as the parent PEAR_ErrorStack class
     */
    function PEAR_PackageFile($compatibility = false, $stackclass = 'PEAR_ErrorStack')
    {
        if (!class_exists('PEAR_ErrorStack')) {
            include_once 'PEAR/ErrorStack.php';
        }
        $this->_stack = &new $stackclass('PEAR_PackageFile', false,
            false, $compatibility, 'Exception');
        $this->_stack->setErrorMessageTemplate($this->_getErrorMessage());
        $this->_isValid = false;
        $this->_compatibility = $compatibility;
    }
    
    /**
     * @param PEAR_Registry
     */
    function setRegistry(&$reg)
    {
        $this->_registry = &$reg;
    }
    
    function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @param string contents of package.xml file
     * @return bool success of parsing
     */
    function fromXmlString($data)
    {
        if (preg_match('/<package\s+version="([0-9]+\.[0-9]+)"/', $data, $packageversion)) {
            if (!in_array($packageversion[1], $this->_supportedVersions)) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_PACKAGEVERSION,
                    array('version' => $packageversion[1], 'versions' => $this->_supportedVersions));
            }
            $packageversion = str_replace('.', '_', $packageversion[1]);
            $method = "_parse$packageversion";
            return $this->$method($data);
        } else {
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_NO_PACKAGEVERSION, 'warning', array('xml' => $data));
            return $this->_parse1_0($data);
        }
    }
    
    // {{{ addTempFile()

    /**
     * Register a temporary file or directory.  When the destructor is
     * executed, all registered temporary files and directories are
     * removed.
     *
     * @param string  $file  name of file or directory
     */
    function addTempFile($file)
    {
        $GLOBALS['_PEAR_Common_tempfiles'][] = $file;
    }
    
    function fromTgzFile($file)
    {
        $tar = new Archive_Tar($file);
        if ($this->debug <= 1) {
            $tar->pushErrorHandling(PEAR_ERROR_RETURN);
        }
        $content = $tar->listContent();
        if ($this->debug <= 1) {
            $tar->popErrorHandling();
        }
        if (!is_array($content)) {
            if (!@is_file($file)) {
                return PEAR::raiseError("could not open file \"$file\"");
            }
            $file = realpath($file);
            return PEAR::raiseError("Could not get contents of package \"$file\"".
                                     '. Invalid tgz file.');
        } else {
            if (!count($content) && !@is_file($file)) {
                return PEAR::raiseError("could not open file \"$file\"");
            }
        }
        $xml = null;
        foreach ($content as $file) {
            $name = $file['filename'];
            if ($name == 'package.xml') {
                $xml = $name;
                break;
            } elseif (ereg('package.xml$', $name, $match)) {
                $xml = $match[0];
                break;
            }
        }
        $tmpdir = System::mkTemp(array('-d', 'pear'));
        $this->addTempFile($tmpdir);
        if (!$xml || !$tar->extractList(array($xml), $tmpdir)) {
            return PEAR::raiseError('could not extract the package.xml file');
        }
        return $this->fromPackageFile("$tmpdir/$xml");
    }
    
    /**
     * Returns information about a package file.  Expects the name of
     * a package xml file as input.
     *
     * @param string  $descfile  name of package xml file
     *
     * @return array  array with package information
     *
     * @access public
     *
     */
    function fromPackageFile($descfile)
    {
        if (!@is_file($descfile) || !is_readable($descfile) ||
             (!$fp = @fopen($descfile, 'r'))) {
            return PEAR::raiseError("Unable to open $descfile");
        }

        // read the whole thing so we only get one cdata callback
        // for each block of cdata
        $data = fread($fp, filesize($descfile));
        return $this->fromXmlString($data);
    }


    /**
     * Returns package information from different sources
     *
     * This method is able to extract information about a package
     * from a .tgz archive or from a XML package definition file.
     *
     * @access public
     * @return string
     */
    function fromAny($info)
    {
        $fp = false;
        if (is_string($info) && (file_exists($info) || ($fp = @fopen($info, 'r')))) {
            if ($fp) {
                fclose($fp);
            }
            $tmp = substr($info, -4);
            if ($tmp == '.xml') {
                $info = $this->fromPackageFile($info);
            } elseif ($tmp == '.tar' || $tmp == '.tgz') {
                $info = $this->fromTgzFile($info);
            } else {
                $fp = fopen($info, "r");
                $test = fread($fp, 5);
                fclose($fp);
                if ($test == "<?xml") {
                    $info = $this->fromPackageFile($info);
                } else {
                    $info = $this->fromTgzFile($info);
                }
            }
            if (PEAR::isError($info)) {
                return PEAR::raiseError($info);
            }
        } else {
            return false;
        }
        return $info;
    }
    
    function setup(&$ui, $debug)
    {
        $this->ui = &$ui;
        $this->debug = $debug;
    }

    /**
     * @return array
     */
    function toArray()
    {
        if (!$this->_isValid && !$this->validate()) {
            return false;
        }
        $pinfo = $this->_packageInfo;
        unset($pinfo['_packagexml_version']);
        return $pinfo;
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
    
    function getChannel()
    {
        if (isset($this->_packageInfo['channel'])) {
            return $this->_packageInfo['channel'];
        }
        return 'pear';
    }

    function getPackage()
    {
        if (isset($this->_packageInfo['package'])) {
            return $this->_packageInfo['package'];
        }
        return false;
    }

    function getVersion()
    {
        if (isset($this->_packageInfo['version'])) {
            return $this->_packageInfo['version'];
        }
        return false;
    }

    function getMaintainers()
    {
        if (isset($this->_packageInfo['maintainers'])) {
            return $this->_packageInfo['maintainers'];
        }
        return false;
    }

    function getState()
    {
        if (isset($this->_packageInfo['release_state'])) {
            return $this->_packageInfo['release_state'];
        }
        return false;
    }

    function getDate()
    {
        if (isset($this->_packageInfo['release_date'])) {
            return $this->_packageInfo['release_date'];
        }
        return false;
    }

    function getLicense()
    {
        if (isset($this->_packageInfo['release_license'])) {
            return $this->_packageInfo['release_license'];
        }
        return false;
    }

    function getSummary()
    {
        if (isset($this->_packageInfo['summary'])) {
            return $this->_packageInfo['summary'];
        }
        return false;
    }

    function getDescription()
    {
        if (isset($this->_packageInfo['description'])) {
            return $this->_packageInfo['description'];
        }
        return false;
    }

    function getNotes()
    {
        if (isset($this->_packageInfo['release_notes'])) {
            return $this->_packageInfo['release_notes'];
        }
        return false;
    }

    function getDeps()
    {
        if (isset($this->_packageInfo['release_deps'])) {
            return $this->_packageInfo['release_deps'];
        }
        return false;
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
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_NO_XML_EXT, 'exception', array('error' => $error));
            return $this->_isValid = false;
        }
        $xp = @xml_parser_create();
        if (!$xp) {
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER, 'exception');
            return $this->_isValid = false;
        }
        xml_set_object($xp, $this);
        xml_set_element_handler($xp, '_element_start_1_0', '_element_end_1_0');
        xml_set_character_data_handler($xp, '_pkginfo_cdata_1_0');
        xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, false);

        $this->element_stack = array();
        $this->_packageInfo = array('provides' => array());
        $this->current_element = false;
        unset($this->dir_install);
        $this->_packageInfo['filelist'] = array();
        $this->filelist =& $this->_packageInfo['filelist'];
        $this->dir_names = array();
        $this->in_changelog = false;
        $this->d_i = 0;
        $this->cdata = '';
        $this->_isValid = true;

        if (!xml_parse($xp, $data, 1)) {
            $code = xml_get_error_code($xp);
            $msg = sprintf("XML error: %s at line %d",
                           $str = xml_error_string($code),
                           $line = xml_get_current_line_number($xp));
            xml_parser_free($xp);
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_PARSER_ERROR, 'error',
                array('error' => $msg, 'code' => $code, 'message' => $str, 'line' => $line));
            return false;
        }

        xml_parser_free($xp);

        foreach ($this->_packageInfo as $k => $v) {
            if (!is_array($v)) {
                $this->_packageInfo[$k] = trim($v);
            }
        }
        if (empty($this->_packageInfo['maintainers'])) {
            $this->_validateError(PEAR_PACKAGEFILE_NO_MAINTAINERS);
        } else {
            $i = 1;
            foreach ($this->_packageInfo['maintainers'] as $m) {
                if (empty($m['handle'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE,
                        array('index' => $i));
                }
                if (empty($m['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE,
                        array('index' => $i, 'roles' => PEAR_Common::getUserRoles()));
                } elseif (!in_array($m['role'], PEAR_Common::getUserRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE,
                        array('index' => $i, 'role' => $m['role'], 'roles' =>
                            PEAR_Common::getUserRoles()));
                }
                if (empty($m['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME,
                        array('index' => $i));
                }
                if (empty($m['email'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (!empty($this->_packageInfo['deps'])) {
            $i = 1;
            foreach ($this->_packageInfo['deps'] as $d) {
                if (empty($d['type'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes()));
                } elseif (!in_array($d['type'], PEAR_Common::getDependencyTypes())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes(),
                              'type' => $d['type']));
                }
                if (empty($d['rel'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPREL,
                        array('index' => $i, 'rels' => PEAR_Common::getDependencyRelations()));
                } elseif (!in_array($d['rel'], PEAR_Common::getDependencyRelations())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyRelations(),
                              'type' => $d['rel']));
                }
                if (!empty($d['optional'])) {
                    if (!in_array($d['optional'], array('yes', 'no'))) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL,
                            array('index' => $i, 'opt' => $d['optional']));
                    }
                }
                if ($d['rel'] != 'has' && empty($d['version'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION,
                        array('index' => $i));
                } elseif ($d['rel'] == 'has' && !empty($d['version'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED,
                        array('index' => $i, 'version' => $d['version']));
                }
                if ($d['type'] == 'php' && !empty($d['name'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED,
                        array('index' => $i, 'name' => $d['name']));
                } elseif ($d['type'] != 'php' && empty($d['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPNAME,
                        array('index' => $i));
                }
                if (isset($this->_registry)) {
                    if (isset($d['channel'])) {
                        if (!$this->_registry->channelExists($d['channel'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL,
                                array('index' => $i, 'channel' => $d['channel']));
                        } else {
                            if ($d['type'] == 'pkg' && !empty($d['name'])) {
                                $channel = $this->_registry->getChannel($d['channel']);
                                if ($channel) {
                                    if (!$channel->validPackageName($d['name'])) {
                                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                            array('index' => $i, 'name' => $d['name']));
                                    }
                                }
                            }
                        }
                    } else {
                        if ($d['type'] == 'pkg' && !empty($d['name'])) {
                            if (!PEAR_Common::validPackageName($d['name'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                    array('index' => $i, 'name' => $d['name']));
                            }
                        }
                    }
                } else {
                    if ($d['type'] == 'pkg' && !empty($d['name'])) {
                        if (!PEAR_Common::validPackageName($d['name'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                array('index' => $i, 'name' => $d['name']));
                        }
                    }
                }
                $i++;
            }
        }
        if (!empty($this->_packageInfo['configure_options'])) {
            $i = 1;
            foreach ($this->_packageInfo['configure_options'] as $c) {
                if (empty($c['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFNAME,
                        array('index' => $i));
                }
                if (empty($c['prompt'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (empty($this->_packageInfo['filelist'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILES);
        } else {
            foreach ($this->_packageInfo['filelist'] as $file => $fa) {
                if (empty($fa['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILEROLE,
                        array('file' => $file));
                    continue;
                } elseif (!in_array($fa['role'], PEAR_Common::getFileRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE,
                        array('file' => $file, 'role' => $fa['role'], 'roles' => PEAR_Common::getFileRoles()));
                }
            }
        }
        return $this->_isValid;
    }

    // Support for package DTD v1.0:
    // {{{ _element_start_1_0()

    /**
     * XML parser callback for ending elements.  Used for version 1.0
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _element_start_1_0($xp, $name, $attribs)
    {
        array_push($this->element_stack, $name);
        $this->current_element = $name;
        $spos = sizeof($this->element_stack) - 2;
        $this->prev_element = ($spos >= 0) ? $this->element_stack[$spos] : '';
        $this->current_attributes = $attribs;
        $this->cdata = '';
        switch ($name) {
            case 'dir':
                if ($this->in_changelog) {
                    break;
                }
                if ($attribs['name'] != '/') {
                    $this->dir_names[] = $attribs['name'];
                }
                if (isset($attribs['baseinstalldir'])) {
                    $this->dir_install = $attribs['baseinstalldir'];
                }
                if (isset($attribs['role'])) {
                    $this->dir_role = $attribs['role'];
                }
                break;
            case 'file':
                if ($this->in_changelog) {
                    break;
                }
                if (isset($attribs['name'])) {
                    $path = '';
                    if (count($this->dir_names)) {
                        foreach ($this->dir_names as $dir) {
                            $path .= $dir . DIRECTORY_SEPARATOR;
                        }
                    }
                    $path .= $attribs['name'];
                    unset($attribs['name']);
                    $this->current_path = $path;
                    $this->filelist[$path] = $attribs;
                    // Set the baseinstalldir only if the file don't have this attrib
                    if (!isset($this->filelist[$path]['baseinstalldir']) &&
                        isset($this->dir_install))
                    {
                        $this->filelist[$path]['baseinstalldir'] = $this->dir_install;
                    }
                    // Set the Role
                    if (!isset($this->filelist[$path]['role']) && isset($this->dir_role)) {
                        $this->filelist[$path]['role'] = $this->dir_role;
                    }
                }
                break;
            case 'replace':
                if (!$this->in_changelog) {
                    $this->filelist[$this->current_path]['replacements'][] = $attribs;
                }
                break;
            case 'maintainers':
                $this->_packageInfo['maintainers'] = array();
                $this->m_i = 0; // maintainers array index
                break;
            case 'maintainer':
                // compatibility check
                if (!isset($this->_packageInfo['maintainers'])) {
                    $this->_packageInfo['maintainers'] = array();
                    $this->m_i = 0;
                }
                $this->_packageInfo['maintainers'][$this->m_i] = array();
                $this->current_maintainer =& $this->_packageInfo['maintainers'][$this->m_i];
                break;
            case 'changelog':
                $this->_packageInfo['changelog'] = array();
                $this->c_i = 0; // changelog array index
                $this->in_changelog = true;
                break;
            case 'release':
                if ($this->in_changelog) {
                    $this->_packageInfo['changelog'][$this->c_i] = array();
                    $this->current_release = &$this->_packageInfo['changelog'][$this->c_i];
                } else {
                    $this->current_release = &$this->_packageInfo;
                }
                break;
            case 'deps':
                if (!$this->in_changelog) {
                    $this->_packageInfo['release_deps'] = array();
                }
                break;
            case 'dep':
                // dependencies array index
                if (!$this->in_changelog) {
                    $this->d_i++;
                    isset($attribs['type']) ? ($attribs['type'] = strtolower($attribs['type'])) : false;
                    $this->_packageInfo['release_deps'][$this->d_i] = $attribs;
                }
                break;
            case 'configureoptions':
                if (!$this->in_changelog) {
                    $this->_packageInfo['configure_options'] = array();
                }
                break;
            case 'configureoption':
                if (!$this->in_changelog) {
                    $this->_packageInfo['configure_options'][] = $attribs;
                }
                break;
            case 'provides':
                if (empty($attribs['type']) || empty($attribs['name'])) {
                    break;
                }
                $attribs['explicit'] = true;
                $this->_packageInfo['provides']["$attribs[type];$attribs[name]"] = $attribs;
                break;
            case 'package' :
                if (isset($attribs['version'])) {
                    $this->_packageInfo['_packagexml_version'] = $attribs['version'];
                } else {
                    $this->_packageInfo['_packagexml_version'] = '1.0';
                }
                break;
        }
    }

    // }}}
    // {{{ _element_end_1_0()

    /**
     * XML parser callback for ending elements.  Used for version 1.0
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _element_end_1_0($xp, $name)
    {
        $data = trim($this->cdata);
        switch ($name) {
            case 'name':
                switch ($this->prev_element) {
                    case 'package':
                        $this->_packageInfo['package'] = $data;
                        if ($this->_packageInfo['_packagexml_version'] == '1.0') {
                            if (!isset($this->_packageInfo['package'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
//                            } elseif (!PEAR_Common::validPackageName($this->_packageInfo['package'])) {
//                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
//                                    array('name' => $this->_packageInfo['package']));
                            }
                        }
                        break;
                    case 'maintainer':
                        $this->current_maintainer['name'] = $data;
                        break;
                }
                break;
            case 'summary':
                if (empty($data)) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_SUMMARY);
                } elseif (strpos($data, "\n") !== false) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY,
                        array('summary' => $data));
                }
                $this->_packageInfo['summary'] = $data;
                break;
            case 'description':
                $data = $this->_unIndent($this->cdata);
                if (empty($data)) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION);
                }
                $this->_packageInfo['description'] = $data;
                break;
            case 'user':
                $this->current_maintainer['handle'] = $data;
                break;
            case 'email':
                $this->current_maintainer['email'] = $data;
                break;
            case 'role':
                $this->current_maintainer['role'] = $data;
                break;
            case 'version':
                //$data = ereg_replace ('[^a-zA-Z0-9._\-]', '_', $data);
                if ($this->in_changelog) {
                    $this->current_release['version'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_VERSION);
                    } elseif (!PEAR_Common::validPackageVersion($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_VERSION,
                            array('version' => $data));
                    }
                    $this->_packageInfo['version'] = $data;
                }
                break;
            case 'date':
                if ($this->in_changelog) {
                    $this->current_release['release_date'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DATE);
                    } elseif (!preg_match('/^\d{4}-\d\d-\d\d$/', $data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DATE,
                            array('date' => $data));
                    }
                    $this->_packageInfo['release_date'] = $data;
                }
                break;
            case 'notes':
                // try to "de-indent" release notes in case someone
                // has been over-indenting their xml ;-)
                $data = $this->_unIndent($this->cdata);
                if ($this->in_changelog) {
                    $this->current_release['release_notes'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NOTES);
                    }
                    $this->_packageInfo['release_notes'] = $data;
                }
                break;
            case 'warnings':
                if ($this->in_changelog) {
                    $this->current_release['release_warnings'] = $data;
                } else {
                    $this->_packageInfo['release_warnings'] = $data;
                }
                break;
            case 'state':
                if ($this->in_changelog) {
                    $this->current_release['release_state'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_STATE);
                    } elseif (!in_array($data, PEAR_Common::getReleaseStates())) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_STATE,
                            array('state' => $data,
                                  'states' => PEAR_Common::getReleaseStates()));
                    }
                    $this->_packageInfo['release_state'] = $data;
                }
                break;
            case 'license':
                if ($this->in_changelog) {
                    $this->current_release['release_license'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_LICENSE);
                    }
                    $this->_packageInfo['release_license'] = $data;
                }
                break;
            case 'dep':
                if ($data && !$this->in_changelog) {
                    $this->_packageInfo['release_deps'][$this->d_i]['name'] = $data;
                }
                break;
            case 'dir':
                if ($this->in_changelog) {
                    break;
                }
                array_pop($this->dir_names);
                break;
            case 'file':
                if ($this->in_changelog) {
                    break;
                }
                if ($data) {
                    $path = '';
                    if (count($this->dir_names)) {
                        foreach ($this->dir_names as $dir) {
                            $path .= $dir . DIRECTORY_SEPARATOR;
                        }
                    }
                    $path .= $data;
                    $this->filelist[$path] = $this->current_attributes;
                    // Set the baseinstalldir only if the file don't have this attrib
                    if (!isset($this->filelist[$path]['baseinstalldir']) &&
                        isset($this->dir_install))
                    {
                        $this->filelist[$path]['baseinstalldir'] = $this->dir_install;
                    }
                    // Set the Role
                    if (!isset($this->filelist[$path]['role']) && isset($this->dir_role)) {
                        $this->filelist[$path]['role'] = $this->dir_role;
                    }
                }
                break;
            case 'maintainer':
                if (empty($this->_packageInfo['maintainers'][$this->m_i]['role'])) {
                    $this->_packageInfo['maintainers'][$this->m_i]['role'] = 'lead';
                }
                $this->m_i++;
                break;
            case 'release':
                if ($this->in_changelog) {
                    $this->c_i++;
                }
                break;
            case 'changelog':
                $this->in_changelog = false;
                break;
        }
        array_pop($this->element_stack);
        $spos = sizeof($this->element_stack) - 1;
        $this->current_element = ($spos > 0) ? $this->element_stack[$spos] : '';
        $this->cdata = '';
    }

    // }}}
    // {{{ _pkginfo_cdata_1_0()

    /**
     * XML parser callback for character data.  Used for version 1.0
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  character data
     *
     * @return void
     *
     * @access private
     */
    function _pkginfo_cdata_1_0($xp, $data)
    {
        if (isset($this->cdata)) {
            $this->cdata .= $data;
        }
    }

    // }}}

    /**
     * @param string contents of package.xml file, version 1.1
     * @return bool success of parsing
     */
    function _parse1_1($data)
    {
        require_once('PEAR/Dependency.php');
        if (PEAR_Dependency::checkExtension($error, 'xml')) {
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_NO_XML_EXT, 'exception', array('error' => $error));
            return $this->_isValid = false;
        }
        $xp = @xml_parser_create();
        if (!$xp) {
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER, 'exception');
            return $this->_isValid = false;
        }
        xml_set_object($xp, $this);
        xml_set_element_handler($xp, '_elementStart1_1', '_elementEnd1_1');
        xml_set_character_data_handler($xp, '_pkginfoCdata1_1');
        xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, false);

        $this->element_stack = array();
        $this->_packageInfo = array('provides' => array());
        $this->current_element = false;
        unset($this->dir_install);
        $this->_packageInfo['filelist'] = array();
        $this->filelist =& $this->_packageInfo['filelist'];
        $this->dir_names = array();
        $this->in_changelog = false;
        $this->d_i = 0;
        $this->cdata = '';
        $this->_isValid = true;

        if (!xml_parse($xp, $data, 1)) {
            $code = xml_get_error_code($xp);
            $msg = sprintf("XML error: %s at line %d",
                           $str = xml_error_string($code),
                           $line = xml_get_current_line_number($xp));
            xml_parser_free($xp);
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_PARSER_ERROR, 'error',
                array('error' => $msg, 'code' => $code, 'message' => $str, 'line' => $line));
            return false;
        }

        xml_parser_free($xp);

        if (empty($this->_packageInfo['maintainers'])) {
            $this->_validateError(PEAR_PACKAGEFILE_NO_MAINTAINERS);
        } else {
            $i = 1;
            foreach ($this->_packageInfo['maintainers'] as $m) {
                if (empty($m['handle'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE,
                        array('index' => $i));
                }
                if (empty($m['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE,
                        array('index' => $i, 'roles' => PEAR_Common::getUserRoles()));
                } elseif (!in_array($m['role'], PEAR_Common::getUserRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE,
                        array('index' => $i, 'role' => $m['role'], 'roles' =>
                            PEAR_Common::getUserRoles()));
                }
                if (empty($m['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME,
                        array('index' => $i));
                }
                if (empty($m['email'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (!empty($this->_packageInfo['deps'])) {
            $i = 1;
            foreach ($this->_packageInfo['deps'] as $d) {
                if (empty($d['type'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes()));
                } elseif (!in_array($d['type'], PEAR_Common::getDependencyTypes())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes(),
                              'type' => $d['type']));
                }
                if (empty($d['rel'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPREL,
                        array('index' => $i, 'rels' => PEAR_Common::getDependencyRelations()));
                } elseif (!in_array($d['rel'], PEAR_Common::getDependencyRelations())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyRelations(),
                              'type' => $d['rel']));
                }
                if (!empty($d['optional'])) {
                    if (!in_array($d['optional'], array('yes', 'no'))) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL,
                            array('index' => $i, 'opt' => $d['optional']));
                    }
                }
                if ($d['rel'] != 'has' && empty($d['version'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION,
                        array('index' => $i));
                } elseif ($d['rel'] == 'has' && !empty($d['version'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED,
                        array('index' => $i, 'version' => $d['version']));
                }
                if ($d['type'] == 'php' && !empty($d['name'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED,
                        array('index' => $i, 'name' => $d['name']));
                } elseif ($d['type'] != 'php' && empty($d['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPNAME,
                        array('index' => $i));
                }
                if (isset($this->_registry)) {
                    if (isset($d['channel'])) {
                        if (!$this->_registry->channelExists($d['channel'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL,
                                array('index' => $i, 'channel' => $d['channel']));
                        } else {
                            if ($d['type'] == 'pkg' && !empty($d['name'])) {
                                $channel = $this->_registry->getChannel($d['channel']);
                                if ($channel) {
                                    if (!$channel->validPackageName($d['name'])) {
                                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                            array('index' => $i, 'name' => $d['name']));
                                    }
                                }
                            }
                        }
                    } else {
                        if ($d['type'] == 'pkg' && !empty($d['name'])) {
                            if (!PEAR_Common::validPackageName($d['name'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                    array('index' => $i, 'name' => $d['name']));
                            }
                        }
                    }
                } else {
                    if ($d['type'] == 'pkg' && !empty($d['name'])) {
                        if (!PEAR_Common::validPackageName($d['name'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                array('index' => $i, 'name' => $d['name']));
                        }
                    }
                }
                $i++;
            }
        }
        if (!empty($this->_packageInfo['configure_options'])) {
            $i = 1;
            foreach ($this->_packageInfo['configure_options'] as $c) {
                if (empty($c['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFNAME,
                        array('index' => $i));
                }
                if (empty($c['prompt'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (empty($this->_packageInfo['filelist'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILES);
        } else {
            foreach ($this->_packageInfo['filelist'] as $file => $fa) {
                if (empty($fa['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILEROLE,
                        array('file' => $file));
                    continue;
                } elseif (!in_array($fa['role'], PEAR_Common::getFileRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE,
                        array('file' => $file, 'role' => $fa['role'], 'roles' => PEAR_Common::getFileRoles()));
                }
            }
        }
        return $this->_isValid;
    }

    // Support for package DTD v1.1:
    // {{{ _elementStart1_1()

    /**
     * XML parser callback for ending elements.  Used for version 1.1
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _elementStart1_1($xp, $name, $attribs)
    {
        switch ($name) {
            case 'dep' :
                if (!isset($attribs['channel'])) {
                    $attribs['channel'] = 'pear';
                }
        }
        $this->_element_start_1_0($xp, $name, $attribs);
        switch ($name) {
            case 'name' :
                if ($this->prev_element == 'package') {
                    if (isset($attribs['channel'])) {
                        $this->_packageInfo['channel'] = strtolower(trim($attribs['channel']));
                        if (isset($this->_registry)) {
                            if (!$this->_registry->channelExists($this->_packageInfo['channel'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL,
                                    array('channel' => $this->_packageInfo['channel']));
                            }
                        }
                    } else {
                        $this->_packageInfo['channel'] = 'pear';
                    }
                }
        }
    }

    // }}}
    // {{{ _elementEnd1_1()

    /**
     * XML parser callback for ending elements.  Used for version 1.1
     * package.xml.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _elementEnd1_1($xp, $name)
    {
        $data = trim($this->cdata);
        switch ($name) {
            case 'name':
                switch ($this->prev_element) {
                    case 'package':
                        $channel = isset($this->_packageInfo['channel']) ? $this->_packageInfo['channel'] : 'pear';
                        $this->_packageInfo['package'] = $data;
                        $validate = isset($this->_registry) ? $this->_registry->getChannelValidator($channel) : false;
                        if (!$validate) {
                            if (empty($this->_packageInfo['package'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
                            } elseif (!PEAR_Common::validPackageName($this->_packageInfo['package'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                                    array('name' => $this->_packageInfo['package']));
                            }
                        } else {
                            $validate->setPackageFile($this);
                            if (isset($this->_packageInfo['package'])) {
                                if (!$validate->validatePackageName($this->_packageInfo['package'])) {
                                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                                        array('name' => $this->_packageInfo['package']));
                                }
                            } else {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
                            }
                        }
                        break;
                    case 'maintainer':
                        $this->current_maintainer['name'] = $data;
                        break;
                }
                break;
            default :
                return $this->_element_end_1_0($xp, $name);
                break;
        }
    }

    // }}}
    // {{{ _pkginfoCdata1_1()

    /**
     * XML parser callback for character data.  Used for version 1.1
     * package.xml.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  character data
     *
     * @return void
     *
     * @access private
     */
    function _pkginfoCdata1_1($xp, $data)
    {
        if (isset($this->cdata)) {
            $this->cdata .= $data;
        }
    }

    /**
     * Validation error.  Also marks the object contents as invalid
     * @param error code
     * @param array error information
     * @access private
     */
    function _validateError($code, $params = array())
    {
        $this->_stack->push($code, 'error', $params, false, false, debug_backtrace());
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
        $this->_stack->push($code, 'warning', $params, false, false, debug_backtrace());
    }

    /**
     * @param integer error code
     * @access protected
     */
    function _getErrorMessage()
    {
        return array(
                PEAR_PACKAGEFILE_ERROR_INVALID_PACKAGEVERSION =>
                    'While parsing package.xml, an invalid <package> version number "%version% was passed in, expecting one of %versions%',
                PEAR_PACKAGEFILE_ERROR_NO_PACKAGEVERSION =>
                    'No version number found in <package> tag',
                PEAR_PACKAGEFILE_ERROR_NO_XML_EXT =>
                    '%error%',
                PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER =>
                    'Unable to create XML parser',
                PEAR_PACKAGEFILE_ERROR_PARSER_ERROR =>
                    '%error%',
                PEAR_PACKAGEFILE_ERROR_NO_NAME =>
                    'Missing Package Name',
                PEAR_PACKAGEFILE_ERROR_INVALID_NAME =>
                    'Invalid Package Name "%name%"',
                PEAR_PACKAGEFILE_ERROR_NO_SUMMARY =>
                    'No summary found',
                PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL =>
                    'Unknown channel, "%channel%"',
                PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY =>
                    'Summary should be on one line',
                PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION =>
                    'Missing description',
                PEAR_PACKAGEFILE_ERROR_NO_LICENSE =>
                    'Missing license',
                PEAR_PACKAGEFILE_ERROR_INVALID_VERSION =>
                    'Invalid <version> version "%version%"',
                PEAR_PACKAGEFILE_ERROR_NO_VERSION =>
                    'No <version> version found',
                PEAR_PACKAGEFILE_ERROR_NO_STATE =>
                    'No state found',
                PEAR_PACKAGEFILE_ERROR_INVALID_STATE =>
                    'Invalid state "%state%", expecting one of "%states%"',
                PEAR_PACKAGEFILE_ERROR_NO_DATE =>
                    'No release date found',
                PEAR_PACKAGEFILE_ERROR_INVALID_DATE =>
                    'Invalid release date "%date%", format is YYYY-MM-DD',
                PEAR_PACKAGEFILE_ERROR_NO_NOTES =>
                    'No release notes found',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTAINERS =>
                    'No maintainers found, at least one must be defined',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE =>
                    'Maintainer %index% has no handle (user ID at channel server)',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE =>
                    'Maintainer %index% has no role, must be one of %roles%',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME =>
                    'Maintainer %index% has no name',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL =>
                    'Maintainer %index% has no email',
                PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE =>
                    'Maintainer %index% has invalid role "%role%", must be one of %roles%',
                PEAR_PACKAGEFILE_ERROR_NO_DEPNAME =>
                    'Dependency %index% is not a php dependency, and has no name',
                PEAR_PACKAGEFILE_ERROR_NO_DEPREL =>
                    'Dependency %index% has no relation, expecting one of %rels%',
                PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE =>
                    'Dependency %index% has no type, expecting one of %types%',
                PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION =>
                    'Dependency %index% is not a rel="has" dependency, and has no version',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL =>
                    'Dependency %index% has invalid relation "%rel%", expecting one of %rels%',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE =>
                    'Dependency %index% has invalid type "%type%", expecting one of %types%',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME =>
                    'Dependency %index% has a package dependency with invalid package name "%name%"',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL =>
                    'Dependency %index% has invalid optional value "%opt%", should be yes or no',
                PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL =>
                    'Dependency %index% requires unknown channel "%channel%"',
                PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED =>
                    'Dependency %index% is type="php", name "%name%" will be ignored',
                PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED =>
                    'Dependency %index% is rel="has", version "%version%" will be ignored',
                PEAR_PACKAGEFILE_ERROR_NO_CONFNAME =>
                    'Configure Option %index% has no name',
                PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT =>
                    'Configure Option %index% has no prompt',
                PEAR_PACKAGEFILE_ERROR_NO_FILES =>
                    'No files in <filelist> section of package.xml',
                PEAR_PACKAGEFILE_ERROR_NO_FILEROLE =>
                    'File "%file%" has no role, expecting one of "%roles%"',
                PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE =>
                    'File "%file%" has invalid role "%role%", expecting one of "%roles%"',
                PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE =>
                    'Parser error: Invalid PHP file "%file%"',
                PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX =>
                    'in %file%: %type% "%name%" not prefixed with package name "%package%"',
            );
    }

    /**
     * Validate XML package definition file.
     *
     * @access public
     * @return boolean
     */
    function validate()
    {
        $this->_isValid = true;
        $info = $this->_packageInfo;
        $errors = array();
        $warnings = array();
        $channel = isset($info['channel']) ? $info['channel'] : 'pear';
        $chan = isset($this->_registry) ? $this->_registry->getChannel($channel) : false;
        if (!$chan) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL,
                array('channel' => $channel));
            if (!isset($info['package'])) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
            } elseif (!PEAR_Common::validPackageName($info['package'])) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                    array('name' => $info['package']));
            }
        } else {
            if (!empty($info['package'])) {
                if (!$chan->validPackageName($info['package'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                        array('name' => $info['package']));
                }
            } else {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
            }
        }
        $this->_packageName = $pn = $info['package'];

        if (empty($info['summary'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_SUMMARY);
        } elseif (strpos(trim($info['summary']), "\n") !== false) {
            $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY,
                array('summary' => $info['summary']));
        }
        if (empty($info['description'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION);
        }
        if (empty($info['release_license'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_LICENSE);
        }
        if (empty($info['version'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_VERSION);
        } elseif (!PEAR_Common::validPackageVersion($info['version'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_VERSION,
                array('version' => $info['version']));
        }
        if (empty($info['release_state'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_STATE);
        } elseif (!in_array($info['release_state'], PEAR_Common::getReleaseStates())) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_STATE,
                array('state' => $info['release_state'],
                      'states' => PEAR_Common::getReleaseStates()));
        }
        if (empty($info['release_date'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DATE);
        } elseif (!preg_match('/^\d{4}-\d\d-\d\d$/', $info['release_date'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DATE,
                array('date' => $info['release_date']));
        }
        if (empty($info['release_notes'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NOTES);
        }
        if (empty($info['maintainers'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTAINERS);
        } else {
            $i = 1;
            foreach ($info['maintainers'] as $m) {
                if (empty($m['handle'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE,
                        array('index' => $i));
                }
                if (empty($m['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE,
                        array('index' => $i, 'roles' => PEAR_Common::getUserRoles()));
                } elseif (!in_array($m['role'], PEAR_Common::getUserRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE,
                        array('index' => $i, 'role' => $m['role'], 'roles' =>
                            PEAR_Common::getUserRoles()));
                }
                if (empty($m['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME,
                        array('index' => $i));
                }
                if (empty($m['email'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (!empty($info['deps'])) {
            $i = 1;
            foreach ($info['deps'] as $d) {
                if (empty($d['type'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes()));
                } elseif (!in_array($d['type'], PEAR_Common::getDependencyTypes())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes(),
                              'type' => $d['type']));
                }
                if (empty($d['rel'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPREL,
                        array('index' => $i, 'rels' => PEAR_Common::getDependencyRelations()));
                } elseif (!in_array($d['rel'], PEAR_Common::getDependencyRelations())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyRelations(),
                              'type' => $d['rel']));
                }
                if (!empty($d['optional'])) {
                    if (!in_array($d['optional'], array('yes', 'no'))) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL,
                            array('index' => $i, 'opt' => $d['optional']));
                    }
                }
                if ($d['rel'] != 'has' && empty($d['version'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION,
                        array('index' => $i));
                } elseif ($d['rel'] == 'has' && !empty($d['version'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED,
                        array('index' => $i, 'version' => $d['version']));
                }
                if ($d['type'] == 'php' && !empty($d['name'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED,
                        array('index' => $i, 'name' => $d['name']));
                } elseif ($d['type'] != 'php' && empty($d['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPNAME,
                        array('index' => $i));
                }
                if (isset($this->_registry)) {
                    if (isset($d['channel'])) {
                        if (!$this->_registry->channelExists($d['channel'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL,
                                array('index' => $i, 'channel' => $d['channel']));
                        } else {
                            if ($d['type'] == 'pkg' && !empty($d['name'])) {
                                $channel = $this->_registry->getChannel($d['channel']);
                                if ($channel) {
                                    if (!$channel->validPackageName($d['name'])) {
                                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                            array('index' => $i, 'name' => $d['name']));
                                    }
                                }
                            }
                        }
                    } else {
                        if ($d['type'] == 'pkg' && !empty($d['name'])) {
                            if (!PEAR_Common::validPackageName($d['name'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                    array('index' => $i, 'name' => $d['name']));
                            }
                        }
                    }
                } else {
                    if ($d['type'] == 'pkg' && !empty($d['name'])) {
                        if (!PEAR_Common::validPackageName($d['name'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                array('index' => $i, 'name' => $d['name']));
                        }
                    }
                }
                $i++;
            }
        }
        if (!empty($info['configure_options'])) {
            $i = 1;
            foreach ($info['configure_options'] as $c) {
                if (empty($c['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFNAME,
                        array('index' => $i));
                }
                if (empty($c['prompt'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (empty($info['filelist'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILES);
            $errors[] = 'no files';
        } else {
            foreach ($info['filelist'] as $file => $fa) {
                if (empty($fa['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILEROLE,
                        array('file' => $file));
                    continue;
                } elseif (!in_array($fa['role'], PEAR_Common::getFileRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE,
                        array('file' => $file, 'role' => $fa['role'], 'roles' => PEAR_Common::getFileRoles()));
                }
            }
        }
        return $this->_isValid;
    }

    function analyzePhpFiles($dir_prefix)
    {
        if (!$this->_isValid) {
            return false;
        }
        $info = $this->_packageInfo;
        foreach ($info['filelist'] as $file => $fa) {
            if ($fa['role'] == 'php' && $dir_prefix) {
                PEAR_Common::log(1, "Analyzing $file");
                $srcinfo = $this->_analyzeSourceCode($dir_prefix . DIRECTORY_SEPARATOR . $file);
                if ($srcinfo) {
                    $this->_buildProvidesArray($srcinfo);
                }
            }
        }
        $this->_packageName = $pn = $info['package'];
        $pnl = strlen($pn);
        foreach ((array)$this->_packageInfo['provides'] as $key => $what) {
            if (isset($what['explicit'])) {
                // skip conformance checks if the provides entry is
                // specified in the package.xml file
                continue;
            }
            extract($what);
            if ($type == 'class') {
                if (!strncasecmp($name, $pn, $pnl)) {
                    continue;
                }
                $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX,
                    array('file' => $file, 'type' => $type, 'name' => $name, 'package' => $pn));
                $warnings[] = "in $file: class \"$name\" not prefixed with package name \"$pn\"";
            } elseif ($type == 'function') {
                if (strstr($name, '::') || !strncasecmp($name, $pn, $pnl)) {
                    continue;
                }
                $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX,
                    array('file' => $file, 'type' => $type, 'name' => $name, 'package' => $pn));
                $warnings[] = "in $file: function \"$name\" not prefixed with package name \"$pn\"";
            }
        }
    }

    /**
     * Build a "provides" array from data returned by
     * analyzeSourceCode().  The format of the built array is like
     * this:
     *
     *  array(
     *    'class;MyClass' => 'array('type' => 'class', 'name' => 'MyClass'),
     *    ...
     *  )
     *
     *
     * @param array $srcinfo array with information about a source file
     * as returned by the analyzeSourceCode() method.
     *
     * @return void
     *
     * @access private
     *
     */
    function _buildProvidesArray($srcinfo)
    {
        if (!$this->_isValid) {
            return false;
        }
        $file = basename($srcinfo['source_file']);
        $pn = $this->_packageInfo['package'];
        $pnl = strlen($pn);
        foreach ($srcinfo['declared_classes'] as $class) {
            $key = "class;$class";
            if (isset($this->_packageInfo['provides'][$key])) {
                continue;
            }
            $this->_packageInfo['provides'][$key] =
                array('file'=> $file, 'type' => 'class', 'name' => $class);
            if (isset($srcinfo['inheritance'][$class])) {
                $this->_packageInfo['provides'][$key]['extends'] =
                    $srcinfo['inheritance'][$class];
            }
        }
        foreach ($srcinfo['declared_methods'] as $class => $methods) {
            foreach ($methods as $method) {
                $function = "$class::$method";
                $key = "function;$function";
                if ($method{0} == '_' || !strcasecmp($method, $class) ||
                    isset($this->_packageInfo['provides'][$key])) {
                    continue;
                }
                $this->_packageInfo['provides'][$key] =
                    array('file'=> $file, 'type' => 'function', 'name' => $function);
            }
        }

        foreach ($srcinfo['declared_functions'] as $function) {
            $key = "function;$function";
            if ($function{0} == '_' || isset($this->_packageInfo['provides'][$key])) {
                continue;
            }
            if (!strstr($function, '::') && strncasecmp($function, $pn, $pnl)) {
                $warnings[] = "in1 " . $file . ": function \"$function\" not prefixed with package name \"$pn\"";
            }
            $this->_packageInfo['provides'][$key] =
                array('file'=> $file, 'type' => 'function', 'name' => $function);
        }
    }

    // }}}
    // {{{ analyzeSourceCode()

    /**
     * Analyze the source code of the given PHP file
     *
     * @param  string Filename of the PHP file
     * @return mixed
     * @access private
     */
    function _analyzeSourceCode($file)
    {
        if (!function_exists("token_get_all")) {
            return false;
        }
        if (!$fp = @fopen($file, "r")) {
            return false;
        }
        $contents = @fread($fp, filesize($file));
        if (!$contents) {
            return false;
        }
        $tokens = token_get_all($contents);
/*
        for ($i = 0; $i < sizeof($tokens); $i++) {
            @list($token, $data) = $tokens[$i];
            if (is_string($token)) {
                var_dump($token);
            } else {
                print token_name($token) . ' ';
                var_dump(rtrim($data));
            }
        }
*/
        $look_for = 0;
        $paren_level = 0;
        $bracket_level = 0;
        $brace_level = 0;
        $lastphpdoc = '';
        $current_class = '';
        $current_class_level = -1;
        $current_function = '';
        $current_function_level = -1;
        $declared_classes = array();
        $declared_functions = array();
        $declared_methods = array();
        $used_classes = array();
        $used_functions = array();
        $extends = array();
        $nodeps = array();
        $inquote = false;
        for ($i = 0; $i < sizeof($tokens); $i++) {
            if (is_array($tokens[$i])) {
                list($token, $data) = $tokens[$i];
            } else {
                $token = $tokens[$i];
                $data = '';
            }
            if ($inquote) {
                if ($token != '"') {
                    continue;
                } else {
                    $inquote = false;
                }
            }
            switch ($token) {
                case '"':
                    $inquote = true;
                    break;
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case '{': $brace_level++; continue 2;
                case '}':
                    $brace_level--;
                    if ($current_class_level == $brace_level) {
                        $current_class = '';
                        $current_class_level = -1;
                    }
                    if ($current_function_level == $brace_level) {
                        $current_function = '';
                        $current_function_level = -1;
                    }
                    continue 2;
                case '[': $bracket_level++; continue 2;
                case ']': $bracket_level--; continue 2;
                case '(': $paren_level++;   continue 2;
                case ')': $paren_level--;   continue 2;
                case T_CLASS:
                    if (($current_class_level != -1) || ($current_function_level != -1)) {
                        $this>_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE,
                            array('file' => $file));
                        return false;
                    }
                case T_FUNCTION:
                case T_NEW:
                case T_EXTENDS:
                    $look_for = $token;
                    continue 2;
                case T_STRING:
                    if ($look_for == T_CLASS) {
                        $current_class = $data;
                        $current_class_level = $brace_level;
                        $declared_classes[] = $current_class;
                    } elseif ($look_for == T_EXTENDS) {
                        $extends[$current_class] = $data;
                    } elseif ($look_for == T_FUNCTION) {
                        if ($current_class) {
                            $current_function = "$current_class::$data";
                            $declared_methods[$current_class][] = $data;
                        } else {
                            $current_function = $data;
                            $declared_functions[] = $current_function;
                        }
                        $current_function_level = $brace_level;
                        $m = array();
                    } elseif ($look_for == T_NEW) {
                        $used_classes[$data] = true;
                    }
                    $look_for = 0;
                    continue 2;
                case T_VARIABLE:
                    $look_for = 0;
                    continue 2;
                case T_COMMENT:
                    if (preg_match('!^/\*\*\s!', $data)) {
                        $lastphpdoc = $data;
                        if (preg_match_all('/@nodep\s+(\S+)/', $lastphpdoc, $m)) {
                            $nodeps = array_merge($nodeps, $m[1]);
                        }
                    }
                    continue 2;
                case T_DOUBLE_COLON:
                    if ($tokens[$i - 1][0] != T_STRING) {
                        $this>_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE,
                            array('file' => $file));
                        return false;
                    }
                    $class = $tokens[$i - 1][1];
                    if (strtolower($class) != 'parent') {
                        $used_classes[$class] = true;
                    }
                    continue 2;
            }
        }
        return array(
            "source_file" => $file,
            "declared_classes" => $declared_classes,
            "declared_methods" => $declared_methods,
            "declared_functions" => $declared_functions,
            "used_classes" => array_diff(array_keys($used_classes), $nodeps),
            "inheritance" => $extends,
            );
    }
    
    /**
     * Explicitly set the package.xml version.
     *
     * This can be used to upgrade an existing package.xml to version 1.1, for example
     * @return boolean success
     * @param string
     */
    function setPackagexmlVersion($version)
    {
        if (!$this->_isValid || $this->validate()) {
            return false;
        }
        if (!in_array($version, $this->_supportedVersions)) {
            return false;
        }
        $this->packageInfo['_packagexml_version'] = $version;
        return true;
    }

    /**
     * Return an XML document based on the package info (as returned
     * by the PEAR_Common::infoFrom* methods).
     *
     * @return string XML data
     */
    function toXml()
    {
        if (!$this->_isValid || !$this->validate()) {
            return false;
        }
        $pkginfo = $this->_packageInfo;
        static $maint_map = array(
            "handle" => "user",
            "name" => "name",
            "email" => "email",
            "role" => "role",
            );
        $version = isset($pkginfo['_packagexml_version']) ? $pkginfo['_packagexml_version'] : '1.0';
        if ($version == '1.1') {
            $channel = isset($pkginfo['channel']) ? $pkginfo['channel'] : 'pear';
            $channel = " channel=\"$channel\"";
        } else {
            $channel = '';
        }
        $ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
        $ret .= "<!DOCTYPE package SYSTEM \"http://pear.php.net/dtd/package-$version\">\n";
        $ret .= "<package version=\"$version\" packagerversion=\"@PEAR-VER@\">
  <name$channel>$pkginfo[package]</name>
  <summary>".htmlspecialchars($pkginfo['summary'])."</summary>
  <description>".htmlspecialchars($pkginfo['description'])."</description>
  <maintainers>
";
        foreach ($pkginfo['maintainers'] as $maint) {
            $ret .= "    <maintainer>\n";
            foreach ($maint_map as $idx => $elm) {
                $ret .= "      <$elm>";
                $ret .= htmlspecialchars($maint[$idx]);
                $ret .= "</$elm>\n";
            }
            $ret .= "    </maintainer>\n";
        }
        $ret .= "  </maintainers>\n";
        $ret .= $this->_makeReleaseXml($pkginfo);
        if (@sizeof($pkginfo['changelog']) > 0) {
            $ret .= "  <changelog>\n";
            foreach ($pkginfo['changelog'] as $oldrelease) {
                $ret .= $this->_makeReleaseXml($oldrelease, true);
            }
            $ret .= "  </changelog>\n";
        }
        $ret .= "</package>\n";
        return $ret;
    }

    // }}}
    // {{{ _makeReleaseXml()

    /**
     * Generate part of an XML description with release information.
     *
     * @param array  $pkginfo    array with release information
     * @param bool   $changelog  whether the result will be in a changelog element
     *
     * @return string XML data
     *
     * @access private
     */
    function _makeReleaseXml($pkginfo, $changelog = false)
    {
        // XXX QUOTE ENTITIES IN PCDATA, OR EMBED IN CDATA BLOCKS!!
        $indent = $changelog ? "  " : "";
        $ret = "$indent  <release>\n";
        if (!empty($pkginfo['version'])) {
            $ret .= "$indent    <version>$pkginfo[version]</version>\n";
        }
        if (!empty($pkginfo['release_date'])) {
            $ret .= "$indent    <date>$pkginfo[release_date]</date>\n";
        }
        if (!empty($pkginfo['release_license'])) {
            $ret .= "$indent    <license>$pkginfo[release_license]</license>\n";
        }
        if (!empty($pkginfo['release_state'])) {
            $ret .= "$indent    <state>$pkginfo[release_state]</state>\n";
        }
        if (!empty($pkginfo['release_notes'])) {
            $ret .= "$indent    <notes>".htmlspecialchars($pkginfo['release_notes'])."</notes>\n";
        }
        if (!empty($pkginfo['release_warnings'])) {
            $ret .= "$indent    <warnings>".htmlspecialchars($pkginfo['release_warnings'])."</warnings>\n";
        }
        if (isset($pkginfo['release_deps']) && sizeof($pkginfo['release_deps']) > 0) {
            $ret .= "$indent    <deps>\n";
            foreach ($pkginfo['release_deps'] as $dep) {
                $ret .= "$indent      <dep type=\"$dep[type]\" rel=\"$dep[rel]\"";
                if (isset($dep['version'])) {
                    $ret .= " version=\"$dep[version]\"";
                }
                if (isset($dep['channel'])) {
                    $ret .= " channel=\"$dep[channel]\"";
                }
                if (isset($dep['optional'])) {
                    $ret .= " optional=\"$dep[optional]\"";
                }
                if (isset($dep['name'])) {
                    $ret .= ">$dep[name]</dep>\n";
                } else {
                    $ret .= "/>\n";
                }
            }
            $ret .= "$indent    </deps>\n";
        }
        if (isset($pkginfo['configure_options'])) {
            $ret .= "$indent    <configureoptions>\n";
            foreach ($pkginfo['configure_options'] as $c) {
                $ret .= "$indent      <configureoption name=\"".
                    htmlspecialchars($c['name']) . "\"";
                if (isset($c['default'])) {
                    $ret .= " default=\"" . htmlspecialchars($c['default']) . "\"";
                }
                $ret .= " prompt=\"" . htmlspecialchars($c['prompt']) . "\"";
                $ret .= "/>\n";
            }
            $ret .= "$indent    </configureoptions>\n";
        }
        if (isset($pkginfo['provides'])) {
            foreach ($pkginfo['provides'] as $key => $what) {
                $ret .= "$indent    <provides type=\"$what[type]\" ";
                $ret .= "name=\"$what[name]\" ";
                if (isset($what['extends'])) {
                    $ret .= "extends=\"$what[extends]\" ";
                }
                $ret .= "/>\n";
            }
        }
        if (isset($pkginfo['filelist'])) {
            $ret .= "$indent    <filelist>\n";
            foreach ($pkginfo['filelist'] as $file => $fa) {
                @$ret .= "$indent      <file role=\"$fa[role]\"";
                if (isset($fa['baseinstalldir'])) {
                    $ret .= ' baseinstalldir="' .
                        htmlspecialchars($fa['baseinstalldir']) . '"';
                }
                if (isset($fa['md5sum'])) {
                    $ret .= " md5sum=\"$fa[md5sum]\"";
                }
                if (isset($fa['platform'])) {
                    $ret .= " platform=\"$fa[platform]\"";
                }
                if (!empty($fa['install-as'])) {
                    $ret .= ' install-as="' .
                        htmlspecialchars($fa['install-as']) . '"';
                }
                $ret .= ' name="' . htmlspecialchars($file) . '"';
                if (empty($fa['replacements'])) {
                    $ret .= "/>\n";
                } else {
                    $ret .= ">\n";
                    foreach ($fa['replacements'] as $r) {
                        $ret .= "$indent        <replace";
                        foreach ($r as $k => $v) {
                            $ret .= " $k=\"" . htmlspecialchars($v) .'"';
                        }
                        $ret .= "/>\n";
                    }
                    @$ret .= "$indent      </file>\n";
                }
            }
            $ret .= "$indent    </filelist>\n";
        }
        $ret .= "$indent  </release>\n";
        return $ret;
    }
}