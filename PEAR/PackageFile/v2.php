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
require_once 'PEAR/ErrorStack.php';
require_once 'PEAR/Validate.php';
require_once 'PEAR/Dependency2.php';
require_once 'PEAR/PackageFile/Generator/v2.php';
require_once 'PEAR/PackageFile/v2/Validator.php';
/**
 * @author Greg Beaver <cellog@php.net>
 * @package PEAR
 */
class PEAR_PackageFile_v2
{

    /**
     * Parsed package information
     * @var array
     * @access private
     */
    var $_packageInfo = array();

    /**
     * path to package .tgz or false if this is a local/extracted package.xml
     * @var string|false
     * @access private
     */
    var $_archiveFile;

    /**
     * path to package .xml or false if this is an abstract parsed-from-string xml
     * @var string|false
     * @access private
     */
    var $_packageFile;

    /**
     * This is used by file analysis routines to log progress information
     * @var PEAR_Common
     * @access protected
     */
    var $_logger;

    /**
     * This is set to the highest validation level that has been validated
     *
     * If the package.xml is invalid or unknown, this is set to 0.  If
     * normal validation has occurred, this is set to PEAR_VALIDATE_NORMAL.  If
     * downloading/installation validation has occurred it is set to PEAR_VALIDATE_DOWNLOADING
     * or INSTALLING, and so on up to PEAR_VALIDATE_PACKAGING.  This allows validation
     * "caching" to occur, which is particularly important for package validation, so
     * that PHP files are not validated twice
     * @var int
     * @access private
     */
    var $_isValid = 0;

    /**
     * True if the filelist has been validated
     * @param bool
     */
    var $_filesValid = false;

    /**
     * @var PEAR_Registry
     * @access protected
     */
    var $_registry;

    /**
     * Optional Dependency group requested for installation
     * @var string
     * @access private
     */
    var $_requestedGroup = false;

    /**
     * @var PEAR_ErrorStack
     * @access protected
     */
    var $_stack;

    /**
     * Namespace prefix used for tasks in this package.xml - use tasks: whenever possible
     */
    var $_tasksNs;

    /**
     * The constructor merely sets up the private error stack
     */
    function PEAR_PackageFile_v2()
    {
        $this->_stack = new PEAR_ErrorStack('PEAR_PackageFile_v2', false, null);
        $this->_isValid = false;
    }

    /**
     * To make unit-testing easier
     * @param PEAR_Frontend_*
     * @param array options
     * @param PEAR_Config
     * @return PEAR_Downloader
     * @access protected
     */
    function &getPEARDownloader(&$i, $o, &$c)
    {
        $z = &new PEAR_Downloader($i, $o, $c);
        return $z;
    }

    /**
     * To make unit-testing easier
     * @param PEAR_Config
     * @param array options
     * @param array package name as returned from {@link PEAR_Registry::parsePackageName()}
     * @param int PEAR_VALIDATE_* constant
     * @return PEAR_Dependency2
     * @access protected
     */
    function &getPEARDependency2(&$c, $o, $p, $s = PEAR_VALIDATE_INSTALLING)
    {
        $z = &new PEAR_Dependency2($c, $o, $p, $s);
        return $z;
    }

    /**
     * Installation of source package has failed, attempt to download and install the
     * binary version of this package.
     * @param PEAR_Installer
     */
    function installBinary(&$installer)
    {
        if ($this->getPackageType() == 'extsrc') {
            if (!is_array($installer->getInstallPackages())) {
                return false;
            }
            foreach ($installer->getInstallPackages() as $p) {
                if ($p->isExtension($this->_packageInfo['providesextension'])) {
                    if ($p->getPackageType() != 'extsrc') {
                        return false; // the user probably downloaded it separately
                    }
                }
            }
            if (isset($this->_packageInfo['extsrcrelease']['binarypackage'])) {
                $installer->log(0, 'Attempting to download binary version of extension "' .
                    $this->_packageInfo['providesextension'] . '"');
                $params = $this->_packageInfo['extsrcrelease']['binarypackage'];
                if (!is_array($params) || !isset($params[0])) {
                    $params = array($params);
                }
                if (isset($this->_packageInfo['channel'])) {
                    foreach ($params as $i => $param) {
                        $params[$i] = array('channel' => $this->_packageInfo['channel'],
                            'package' => $param);
                    }
                }
                $dl = &$this->getPEARDownloader($installer->ui, $installer->getOptions(),
                    $installer->config);
                $verbose = $dl->config->get('verbose');
                $dl->config->set('verbose', -1);
                foreach ($params as $param) {
                    PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                    $ret = $dl->download(array($param));
                    PEAR::popErrorHandling();
                    if (is_array($ret) && count($ret)) {
                        break;
                    }
                }
                $dl->config->set('verbose', $verbose);
                if (is_array($ret)) {
                    if (count($ret) == 1) {
                        $pf = $ret[0]->getPackageFile();
                        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                        $ret = $installer->install($ret[0]);
                        PEAR::popErrorHandling();
                        if (is_array($ret)) {
                            $installer->log(0, 'Download and install of binary extension "' .
                                $this->_registry->parsedPackageNameToString(
                                    array('channel' => $pf->getChannel(),
                                          'package' => $pf->getPackage()), true) . '" successful');
                            return $ret;
                        }
                        $installer->log(0, 'Download and install of binary extension "' .
                            $this->_registry->parsedPackageNameToString(
                                    array('channel' => $pf->getChannel(),
                                          'package' => $pf->getPackage()), true) . '" failed');
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param string Extension name
     * @return bool success of operation
     */
    function setProvidesExtension($extension)
    {
        if (in_array($this->getPackageType(), array('extsrc', 'extbin'))) {
            if (!isset($this->_packageInfo['providesextension'])) {
                // ensure that the channel tag is set up in the right location
                $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                    array('srcpackage', 'srcuri', 'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog'), $extension, 'providesextension');
            }
            $this->_packageInfo['providesextension'] = $extension;
            return true;
        }
        return false;
    }

    /**
     * @return string|false Extension name
     */
    function getProvidesExtension()
    {
        if (in_array($this->getPackageType(), array('extsrc', 'extbin'))) {
            if (isset($this->_packageInfo['providesextension'])) {
                return $this->_packageInfo['providesextension'];
            }
        }
        return false;
    }

    /**
     * @param string Extension name
     * @return bool
     */
    function isExtension($extension)
    {
        if (in_array($this->getPackageType(), array('extsrc', 'extbin'))) {
            return $this->_packageInfo['providesextension'] == $extension;
        }
        return false;
    }

    /**
     * Tests whether every part of the package.xml 1.0 is represented in
     * this package.xml 2.0
     * @param PEAR_PackageFile_v1
     * @return bool
     */
    function isEquivalent($pf1)
    {
        if (!$pf1) {
            return true;
        }
        if ($this->getPackageType() == 'bundle') {
            return false;
        }
        $this->_stack->getErrors(true);
        if (!$pf1->validate(PEAR_VALIDATE_NORMAL)) {
            return false;
        }
        $pass = true;
        if ($pf1->getPackage() != $this->getPackage()) {
            $this->_differentPackage($pf1->getPackage());
            $pass = false;
        }
        if ($pf1->getVersion() != $this->getVersion()) {
            $this->_differentVersion($pf1->getVersion());
            $pass = false;
        }
        if ($pf1->getState() != $this->getState()) {
            $this->_differentState($pf1->getState());
            $pass = false;
        }
        $filelist = $this->getFilelist();
        foreach ($pf1->getFilelist() as $file => $atts) {
            if (!isset($filelist[$file])) {
                $this->_missingFile($file);
                $pass = false;
            }
        }
        return $pass;
    }

    function _differentPackage($package)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('package' => $package,
            'self' => $this->getPackage()),
            'package.xml 1.0 package "%package%" does not match "%self%"');
    }

    function _differentVersion($version)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('version' => $version,
            'self' => $this->getVersion()),
            'package.xml 1.0 version "%version%" does not match "%self%"');
    }

    function _differentState($state)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('state' => $state,
            'self' => $this->getState()),
            'package.xml 1.0 state "%state%" does not match "%self%"');
    }

    function _missingFile($file)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('file' => $file),
            'package.xml 1.0 file "%file%" is not present in <contents>');
    }

    function setRequestedGroup($group)
    {
        $this->_requestedGroup = $group;
    }

    function getRequestedGroup()
    {
        if (isset($this->_requestedGroup)) {
            return $this->_requestedGroup;
        }
        return false;
    }

    /**
     * Convert a recursive set of <dir> and <file> tags into a single <dir> tag with
     * <file> tags.
     */
    function flattenFilelist()
    {
        if (isset($this->_packageInfo['bundle'])) {
            return;
        }
        $filelist = array();
        if (isset($this->_packageInfo['contents']['dir']['dir'])) {
            $this->_getFlattenedFilelist($filelist, $this->_packageInfo['contents']['dir']);
            if (!isset($filelist[1])) {
                $filelist = $filelist[0];
            }
            $this->_packageInfo['contents']['dir']['file'] = $filelist;
            unset($this->_packageInfo['contents']['dir']['dir']);
        }   // else already flattened
    }

    /**
     * @param array the final flattened file list
     * @param array the current directory being processed
     * @param string|false any recursively inherited baeinstalldir attribute
     * @param string private recursion variable
     * @return array
     * @access protected
     */
    function _getFlattenedFilelist(&$files, $dir, $baseinstall = false, $path = '')
    {
        if (isset($dir['attribs']) && isset($dir['attribs']['baseinstalldir'])) {
            $baseinstall = $dir['attribs']['baseinstalldir'];
        }
        if (isset($dir['dir'])) {
            if (!isset($dir['dir'][0])) {
                $dir['dir'] = array($dir['dir']);
            }
            foreach ($dir['dir'] as $subdir) {
                if (!isset($subdir['attribs']) || !isset($subdir['attribs']['name'])) {
                    $name = '*unknown*';
                } else {
                    $name = $subdir['attribs']['name'];
                }
                $newpath = empty($path) ? $name :
                    $path . '/' . $name;
                $this->_getFlattenedFilelist($files, $subdir,
                    $baseinstall, $newpath);
            }
        }
        if (isset($dir['file'])) {
            if (!isset($dir['file'][0])) {
                $dir['file'] = array($dir['file']);
            }
            foreach ($dir['file'] as $file) {
                $attrs = $file['attribs'];
                $name = $attrs['name'];
                if ($baseinstall) {
                    $attrs['baseinstalldir'] = $baseinstall;
                }
                $attrs['name'] = empty($path) ? $name : $path . '/' . $name;
                $attrs['name'] = preg_replace(array('!\\\\+!', '!/+!'), array('/', '/'),
                    $attrs['name']);
                $file['attribs'] = $attrs;
                $files[] = $file;
            }
        }
    }

    function setConfig(&$config)
    {
        $this->_config = &$config;
        $this->_registry = &$config->getRegistry();
    }

    function setLogger(&$logger)
    {
        if ($logger && (!is_object($logger) || !method_exists($logger, 'log'))) {
            return PEAR::raiseError('Logger must be compatible with PEAR_Common::log');
        }
        $this->_logger = &$logger;
    }

    function setPackagefile($file, $archive = false)
    {
        $this->_packageFile = $file;
        $this->_archiveFile = $archive ? $archive : $file;
    }

    /**
     * Wrapper to {@link PEAR_ErrorStack::getErrors()}
     * @param boolean determines whether to purge the error stack after retrieving
     * @return array
     */
    function getValidationWarnings($purge = true)
    {
        return $this->_stack->getErrors($purge);
    }

    function getPackageFile()
    {
        return $this->_packageFile;
    }

    function getArchiveFile()
    {
        return $this->_archiveFile;
    }


    /**
     * Directly set the array that defines this packagefile
     *
     * WARNING: no validation.  This should only be performed by internal methods
     * inside PEAR or by inputting an array saved from an existing PEAR_PackageFile_v2
     * @param array
     */
    function fromArray($pinfo)
    {
        unset($pinfo['old']);
        unset($pinfo['xsdversion']);
        $this->_packageInfo = $pinfo;
    }

    /**
     * @return array
     */
    function toArray($forreg = false)
    {
        if (!$this->validate(PEAR_VALIDATE_NORMAL)) {
            return false;
        }
        return $this->getArray($forreg);
    }

    function getArray($forReg = false)
    {
        if ($forReg) {
            $arr = $this->_packageInfo;
            $arr['old'] = array();
            $arr['old']['version'] = $this->getVersion();
            $arr['old']['release_date'] = $this->getDate();
            $arr['old']['release_state'] = $this->getState();
            $arr['old']['release_license'] = $this->getLicense();
            $arr['old']['release_notes'] = $this->getNotes();
            $arr['old']['release_deps'] = $this->getDeps();
            $arr['old']['maintainers'] = $this->getMaintainers();
            $arr['xsdversion'] = '2.0';
            return $arr;
        } else {
            $info = $this->_packageInfo;
            unset($info['dirtree']);
            return $info;
        }
    }

    function packageInfo($field)
    {
        static $arr = false;
        if (!$arr) {
            $arr = $this->getArray(true);
        }
        if (isset($arr['old'][$field])) {
            if (!is_string($arr['old'][$field])) {
                return null;
            }
            return $arr['old'][$field];
        }
        if (isset($arr[$field])) {
            if (!is_string($arr[$field])) {
                return null;
            }
            return $arr[$field];
        }
        return null;
    }

    function getName()
    {
        return $this->getPackage();
    }

    function getPackage()
    {
        if (isset($this->_packageInfo['name'])) {
            return $this->_packageInfo['name'];
        }
        return false;
    }

    function setPackage($package)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['attribs'])) {
            $this->_packageInfo = array_merge(array('attribs' => array(
                                 'version' => '2.0',
                                 'xmlns' => 'http://pear.php.net/dtd/package-2.0',
                                 'xmlns:tasks' => 'http://pear.php.net/dtd/tasks-1.0',
                                 'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                                 'xsi:schemaLocation' => 'http://pear.php.net/dtd/tasks-1.0
    http://pear.php.net/dtd/tasks-1.0.xsd
    http://pear.php.net/dtd/package-2.0
    http://pear.php.net/dtd/package-2.0.xsd',
                             )), $this->_packageInfo);
        }
        if (!isset($this->_packageInfo['name'])) {
            return $this->_packageInfo = array_merge(array('name' => $package),
                $this->_packageInfo);
        }
        $this->_packageInfo['name'] = $package;
    }

    function getChannel()
    {
        if (isset($this->_packageInfo['uri'])) {
            return '__uri';
        }
        if (isset($this->_packageInfo['channel'])) {
            return strtolower($this->_packageInfo['channel']);
        }
        return false;
    }

    function getUri()
    {
        if (isset($this->_packageInfo['uri'])) {
            return $this->_packageInfo['uri'];
        }
        return false;
    }

    function setUri($uri)
    {
        unset($this->_packageInfo['channel']);
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['uri'])) {
            // ensure that the uri tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo, 
                array('extends', 'summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), $uri, 'uri');
        }
        $this->_packageInfo['uri'] = $uri;
    }

    function setChannel($channel)
    {
        unset($this->_packageInfo['uri']);
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['channel'])) {
            // ensure that the channel tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('extends', 'summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), $channel, 'channel');
        }
        $this->_packageInfo['channel'] = $channel;
    }

    function getExtends()
    {
        if (isset($this->_packageInfo['extends'])) {
            return $this->_packageInfo['extends'];
        }
        return false;
    }

    function setExtends($extends)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['extends'])) {
            // ensure that the extends tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), $extends, 'extends');
        }
        $this->_packageInfo['extends'] = $extends;
    }

    function getSummary()
    {
        if (isset($this->_packageInfo['summary'])) {
            return $this->_packageInfo['summary'];
        }
        return false;
    }

    function setSummary($summary)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['summary'])) {
            // ensure that the summary tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), $summary, 'summary');
        }
        $this->_packageInfo['summary'] = $summary;
    }

    function getDescription()
    {
        if (isset($this->_packageInfo['description'])) {
            return $this->_packageInfo['description'];
        }
        return false;
    }

    function setDescription($desc)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['description'])) {
            // ensure that the description tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), $desc, 'description');
        }
        $this->_packageInfo['description'] = $desc;
    }

    /**
     * Adds a new maintainer - no checking of duplicates is performed, use
     * updatemaintainer for that purpose.
     */
    function addMaintainer($role, $handle, $name, $email, $active = 'yes')
    {
        if (!in_array($role, array('lead', 'developer', 'contributor', 'helper'))) {
            return false;
        }
        if (isset($this->_packageInfo[$role])) {
            if (!isset($this->_packageInfo[$role][0])) {
                $this->_packageInfo[$role] = array($this->_packageInfo[$role]);
            }
            $this->_packageInfo[$role][] =
                array(
                    'name' => $name,
                    'user' => $handle,
                    'email' => $email,
                    'active' => $active,
                );
        } else {
            $testarr = array('lead',
                    'developer', 'contributor', 'helper', 'date', 'time', 'version',
                    'stability', 'license', 'notes', 'contents', 'compatible',
                    'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog');
            foreach (array('lead', 'developer', 'contributor', 'helper') as $testrole) {
                array_shift($testarr);
                if ($role == $testrole) {
                    break;
                }
            }
            if (!isset($this->_packageInfo[$role])) {
                // ensure that the extends tag is set up in the right location
                $this->_packageInfo = $this->_insertBefore($this->_packageInfo, $testarr,
                    array(), $role);
            }
            $this->_packageInfo[$role] =
                array(
                    'name' => $name,
                    'user' => $handle,
                    'email' => $email,
                    'active' => $active,
                );
        }
        $this->_isValid = 0;
    }

    function updateMaintainer($newrole, $handle, $name, $email, $active = 'yes')
    {
        $found = false;
        foreach (array('lead', 'developer', 'contributor', 'helper') as $role) {
            if (!isset($this->_packageInfo[$role])) {
                continue;
            }
            $info = $this->_packageInfo[$role];
            if (!isset($info[0])) {
                if ($info['user'] == $handle) {
                    $found = true;
                    break;
                }
            }
            foreach ($info as $i => $maintainer) {
                if ($maintainer['user'] == $handle) {
                    $found = $i;
                    break 2;
                }
            }
        }
        if ($found === false) {
            return $this->addMaintainer($newrole, $handle, $name, $email, $active);
        }
        if ($found !== false) {
            if ($found === true) {
                unset($this->_packageInfo[$role]);
            } else {
                unset($this->_packageInfo[$role][$found]);
                $this->_packageInfo[$role] = array_values($this->_packageInfo[$role]);
            }
        }
        $this->addMaintainer($newrole, $handle, $name, $email);
        $this->_isValid = 0;
    }

    function deleteMaintainer($handle)
    {
        $found = false;
        foreach (array('lead', 'developer', 'contributor', 'helper') as $role) {
            if (!isset($this->_packageInfo[$role])) {
                continue;
            }
            if (!isset($this->_packageInfo[$role][0])) {
                $this->_packageInfo[$role] = array($this->_packageInfo[$role]);
            }
            foreach ($this->_packageInfo[$role] as $i => $maintainer) {
                if ($maintainer['user'] == $handle) {
                    $found = $i;
                    break;
                }
            }
            if ($found !== false) {
                unset($this->_packageInfo[$role][$found]);
                if (!count($this->_packageInfo[$role]) && $role == 'lead') {
                    $this->_isValid = 0;
                }
                if (!count($this->_packageInfo[$role])) {
                    unset($this->_packageInfo[$role]);
                    return true;
                }
                $this->_packageInfo[$role] =
                    array_values($this->_packageInfo[$role]);
                if (count($this->_packageInfo[$role]) == 1) {
                    $this->_packageInfo[$role] = $this->_packageInfo[$role][0];
                }
                return true;
            }
            if (count($this->_packageInfo[$role]) == 1) {
                $this->_packageInfo[$role] = $this->_packageInfo[$role][0];
            }
        }
        return false;
    }

    function getMaintainers($raw = false)
    {
        if (!$this->_isValid && !$this->validate()) {
            return false;
        }
        if ($raw) {
            $ret = array('lead' => $this->_packageInfo['lead']);
            (isset($this->_packageInfo['developer'])) ?
                $ret['developer'] = $this->_packageInfo['developer'] :null;
            (isset($this->_packageInfo['contributor'])) ?
                $ret['contributor'] = $this->_packageInfo['contributor'] :null;
            (isset($this->_packageInfo['helper'])) ?
                $ret['helper'] = $this->_packageInfo['helper'] :null;
            return $ret;
        } else {
            $ret = array();
            $leads = isset($this->_packageInfo['lead'][0]) ? $this->_packageInfo['lead'] :
                array($this->_packageInfo['lead']);
            foreach ($leads as $lead) {
                $s = $lead;
                $s['handle'] = $s['user'];
                unset($s['user']);
                $s['role'] = 'lead';
                $ret[] = $s;
            }
            if (isset($this->_packageInfo['developer'])) {
                $leads = isset($this->_packageInfo['developer'][0]) ?
                    $this->_packageInfo['developer'] :
                    array($this->_packageInfo['developer']);
                foreach ($leads as $maintainer) {
                    $s = $maintainer;
                    $s['handle'] = $s['user'];
                    unset($s['user']);
                    $s['role'] = 'developer';
                    $ret[] = $s;
                }
            }
            if (isset($this->_packageInfo['contributor'])) {
                $leads = isset($this->_packageInfo['contributor'][0]) ?
                    $this->_packageInfo['contributor'] :
                    array($this->_packageInfo['contributor']);
                foreach ($leads as $maintainer) {
                    $s = $maintainer;
                    $s['handle'] = $s['user'];
                    unset($s['user']);
                    $s['role'] = 'contributor';
                    $ret[] = $s;
                }
            }
            if (isset($this->_packageInfo['helper'])) {
                $leads = isset($this->_packageInfo['helper'][0]) ?
                    $this->_packageInfo['helper'] :
                    array($this->_packageInfo['helper']);
                foreach ($leads as $maintainer) {
                    $s = $maintainer;
                    $s['handle'] = $s['user'];
                    unset($s['user']);
                    $s['role'] = 'helper';
                    $ret[] = $s;
                }
            }
            return $ret;
        }
        return false;
    }

    function getLeads()
    {
        if (isset($this->_packageInfo['lead'])) {
            return $this->_packageInfo['lead'];
        }
        return false;
    }

    function getDevelopers()
    {
        if (isset($this->_packageInfo['developer'])) {
            return $this->_packageInfo['developer'];
        }
        return false;
    }

    function getContributors()
    {
        if (isset($this->_packageInfo['contributor'])) {
            return $this->_packageInfo['contributor'];
        }
        return false;
    }

    function getHelpers()
    {
        if (isset($this->_packageInfo['helper'])) {
            return $this->_packageInfo['helper'];
        }
        return false;
    }

    function getDate()
    {
        if (isset($this->_packageInfo['date'])) {
            return $this->_packageInfo['date'];
        }
        return false;
    }

    function setDate($date)
    {
        if (!isset($this->_packageInfo['date'])) {
            // ensure that the extends tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('time', 'version',
                    'stability', 'license', 'notes', 'contents', 'compatible',
                    'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog'), array(), 'date');
        }
        $this->_packageInfo['date'] = $date;
        $this->_isValid = 0;
    }

    function getTime()
    {
        if (isset($this->_packageInfo['time'])) {
            return $this->_packageInfo['time'];
        }
        return false;
    }

    function setTime($time)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['time'])) {
            // ensure that the time tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                    array('version',
                    'stability', 'license', 'notes', 'contents', 'compatible',
                    'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog'), $time, 'time');
        }
        $this->_packageInfo['time'] = $time;
    }

    /**
     * @param package|api version category to return
     */
    function getVersion($key = 'release')
    {
        if (isset($this->_packageInfo['version'][$key])) {
            return $this->_packageInfo['version'][$key];
        }
        return false;
    }

    function setReleaseVersion($version)
    {
        if (isset($this->_packageInfo['version']) &&
              isset($this->_packageInfo['version']['release'])) {
            unset($this->_packageInfo['version']['release']);
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $version, array(
            'version' => array('stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'),
            'release' => array('api')));
        $this->_isValid = 0;
    }

    function setAPIVersion($version)
    {
        if (isset($this->_packageInfo['version']) &&
              isset($this->_packageInfo['version']['api'])) {
            unset($this->_packageInfo['version']['api']);
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $version, array(
            'version' => array('stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'),
            'api' => array()));
        $this->_isValid = 0;
    }

    function getStability()
    {
        if (isset($this->_packageInfo['stability'])) {
            return $this->_packageInfo['stability'];
        }
        return false;
    }

    function getState($key = 'release')
    {
        if (isset($this->_packageInfo['stability'][$key])) {
            return $this->_packageInfo['stability'][$key];
        }
        return false;
    }

    /**
     * snapshot|devel|alpha|beta|stable
     */
    function setReleaseStability($state)
    {
        if (isset($this->_packageInfo['stability']) &&
              isset($this->_packageInfo['stability']['release'])) {
            unset($this->_packageInfo['stability']['release']);
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $state, array(
            'stability' => array('license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'),
            'release' => array('api')));
        $this->_isValid = 0;
    }

    /**
     * @param devel|alpha|beta|stable
     */
    function setAPIStability($state)
    {
        if (isset($this->_packageInfo['stability']) &&
              isset($this->_packageInfo['stability']['api'])) {
            unset($this->_packageInfo['stability']['api']);
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $state, array(
            'stability' => array('license', 'notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'),
            'api' => array()));
        $this->_isValid = 0;
    }

    function getLicense()
    {
        if (isset($this->_packageInfo['license'])) {
            if (is_array($this->_packageInfo['license'])) {
                return $this->_packageInfo['license']['_content'];
            } else {
                return $this->_packageInfo['license'];
            }
        }
        return false;
    }

    function getLicenseLocation()
    {
        if (!isset($this->_packageInfo['license']) || !is_array($this->_packageInfo['license'])) {
            return false;
        }
        return $this->_packageInfo['license']['attribs'];
    }

    function setLicense($license, $uri = false, $filesource = false)
    {
        if (!isset($this->_packageInfo['license'])) {
            // ensure that the license tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('notes', 'contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), 0, 'license');
        }
        if ($uri || $filesource) {
            $attribs = array();
            if ($uri) {
                $attribs['uri'] = $uri;
            }
            $uri = true; // for test below
            if ($filesource) {
                $attribs['filesource'] = $filesource;
            }
        }
        $license = $uri ? array('attribs' => $attribs, '_content' => $license) : $license;
        $this->_packageInfo['license'] = $license;
        $this->_isValid = 0;
    }

    function getNotes()
    {
        if (isset($this->_packageInfo['notes'])) {
            return $this->_packageInfo['notes'];
        }
        return false;
    }

    function setNotes($notes)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['notes'])) {
            // ensure that the notes tag is set up in the right location
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('contents', 'compatible',
                'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), $notes, 'notes');
        }
        $this->_packageInfo['notes'] = $notes;
    }

    /**
     * This should only be used to retrieve filenames and install attributes
     */
    function getFilelist($preserve = false)
    {
        if (isset($this->_packageInfo['filelist']) && !$preserve) {
            return $this->_packageInfo['filelist'];
        }
        $this->flattenFilelist();
        if ($contents = $this->getContents()) {
            $ret = array();
            if (!isset($contents['dir']['file'][0])) {
                $contents['dir']['file'] = array($contents['dir']['file']);
            }
            foreach ($contents['dir']['file'] as $file) {
                $name = $file['attribs']['name'];
                if (!$preserve) {
                    $file = $file['attribs'];
                }
                $ret[$name] = $file;
            }
            if (!$preserve) {
                $this->_packageInfo['filelist'] = $ret;
            }
            return $ret;
        }
        return false;
    }

    /**
     * This is only used at install-time, after all serialization
     * is over.
     */
    function resetFilelist()
    {
        $this->_packageInfo['filelist'] = array();
    }

    /**
     * Retrieve a list of files that should be installed on this computer
     * @return array
     */
    function getInstallationFilelist()
    {
        $contents = $this->getFilelist(true);
        if (isset($contents['dir']['attribs']['baseinstalldir'])) {
            $base = $contents['dir']['attribs']['baseinstalldir'];
        }
        if (isset($this->_packageInfo['bundle'])) {
            return PEAR::raiseError(
                'Exception: bundles should be handled in download code only');
        }
        $release = $this->getReleases();
        if ($release) {
            if (!isset($release[0])) {
                if (!isset($release['installconditions']) && !isset($release['filelist'])) {
                    return $contents;
                }
                $release = array($release);
            }
            $depchecker = &$this->getPEARDependency2($this->_config, array(),
                array('channel' => $this->getChannel(), 'package' => $this->getPackage()),
                PEAR_VALIDATE_INSTALLING);
            foreach ($release as $instance) {
                if (isset($instance['installconditions'])) {
                    $installconditions = $instance['installconditions'];
                    if (is_array($installconditions)) {
                        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                        foreach ($installconditions as $type => $conditions) {
                            if (!isset($conditions[0])) {
                                $conditions = array($conditions);
                            }
                            foreach ($conditions as $condition) {
                                $ret = $depchecker->{"validate{$type}Dependency"}($condition);
                                if (PEAR::isError($ret)) {
                                    PEAR::popErrorHandling();
                                    continue 3; // skip this release
                                }
                            }
                        }
                        PEAR::popErrorHandling();
                    }
                }
                // this is the release to use
                if (isset($instance['filelist'])) {
                    // ignore files
                    if (isset($instance['filelist']['ignore'])) {
                        $ignore = isset($instance['filelist']['ignore'][0]) ?
                            $instance['filelist']['ignore'] :
                            array($instance['filelist']['ignore']);
                        foreach ($ignore as $ig) {
                            unset ($contents[$ig['attribs']['name']]);
                        }
                    }
                    // install files as this name
                    if (isset($instance['filelist']['install'])) {
                        $installas = isset($instance['filelist']['install'][0]) ?
                            $instance['filelist']['install'] :
                            array($instance['filelist']['install']);
                        foreach ($installas as $as) {
                            $contents[$as['attribs']['name']]['attribs']['install-as'] =
                                $as['attribs']['as'];
                        }
                    }
                }
                return $contents;
            }
        } else { // simple release - no installconditions or install-as
            return $contents;
        }
        // no releases matched
        return PEAR::raiseError('No releases in package.xml matched the existing operating ' .
            'system, extensions installed, or architecture, cannot install');
    }

    /**
     * This is only used at install-time, after all serialization
     * is over.
     * @param string file name
     * @param string installed path
     */
    function setInstalledAs($file, $path)
    {
        if ($path) {
            return $this->_packageInfo['filelist'][$file]['installed_as'] = $path;
        }
        unset($this->_packageInfo['filelist'][$file]['installed_as']);
    }

    function getInstalledLocation($file)
    {
        if (isset($this->_packageInfo['filelist'][$file]['installed_as'])) {
            return $this->_packageInfo['filelist'][$file]['installed_as'];
        }
        return false;
    }

    /**
     * This is only used at install-time, after all serialization
     * is over.
     */
    function installedFile($file, $atts)
    {
        if (isset($this->_packageInfo['filelist'][$file])) {
            $this->_packageInfo['filelist'][$file] =
                array_merge($this->_packageInfo['filelist'][$file], $atts['attribs']);
        } else {
            $this->_packageInfo['filelist'][$file] = $atts['attribs'];
        }
    }

    /**
     * Retrieve the contents tag
     */
    function getContents()
    {
        if (isset($this->_packageInfo['contents'])) {
            return $this->_packageInfo['contents'];
        }
        return false;
    }

    /**
     * Reset the listing of package contents
     * @param string base installation dir for the whole package, if any
     */
    function clearContents($baseinstall = false)
    {
        $this->_filesValid = false;
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['contents'])) {
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('compatible',
                    'dependencies', 'providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog'), array(), 'contents');
        }
        if ($this->getPackageType() != 'bundle') {
            $this->_packageInfo['contents'] = 
                array('dir' => array('attribs' => array('name' => '/')));
            if ($baseinstall) {
                $this->_packageInfo['contents']['dir']['attribs']['baseinstalldir'] = $baseinstall;
            }
        }
    }

    /**
     * @param string relative path of the bundled package.
     */
    function addBundledPackage($path)
    {
        if ($this->getPackageType() != 'bundle') {
            return false;
        }
        $this->_filesValid = false;
        $this->_isValid = 0;
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $path, array(
                'contents' => array('compatible', 'dependencies', 'providesextension',
                'srcpackage', 'srcuri', 'phprelease', 'extsrcrelease', 'extbinrelease',
                'bundle', 'changelog'),
                'bundledpackage' => array()));
    }

    /**
     * @param string path to the file
     * @param string filename
     * @param array extra attributes
     */
    function addFile($dir, $file, $attrs)
    {
        if ($this->getPackageType() == 'bundle') {
            return false;
        }
        $this->_filesValid = false;
        $this->_isValid = 0;
        $dir = preg_replace(array('!\\\\+!', '!/+!'), array('/', '/'), $dir);
        if ($dir == '/' || $dir == '') {
            $dir = '';
        } else {
            $dir .= '/';
        }
        $attrs['name'] = $dir . $file;
        if (!isset($this->_packageInfo['contents'])) {
            // ensure that the contents tag is set up
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo,
                array('compatible', 'dependencies', 'providesextension', 'srcpackage', 
                'srcuri', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'contents');
        }
        if (isset($this->_packageInfo['contents']['dir']['file'])) {
            if (!isset($this->_packageInfo['contents']['dir']['file'][0])) {
                $this->_packageInfo['contents']['dir']['file'] =
                    array($this->_packageInfo['contents']['dir']['file']);
            }
            $this->_packageInfo['contents']['dir']['file'][]['attribs'] = $attrs;
        } else {
            $this->_packageInfo['contents']['dir']['file']['attribs'] = $attrs;
        }
    }

    /**
     * @param string full path to file
     * @param string attribute name
     * @param string attribute value
     * @param int risky but fast - use this to choose a file based on its position in the list
     *            of files.  Index is zero-based like PHP arrays.
     * @return bool success of operation
     */
    function setFileAttribute($filename, $attr, $value, $index = false)
    {
        $this->_isValid = 0;
        if (in_array($attr, array('role', 'name', 'baseinstalldir'))) {
            $this->_filesValid = false;
        }
        if ($index !== false &&
              isset($this->_packageInfo['contents']['dir']['file'][$index]['attribs'])) {
            $this->_packageInfo['contents']['dir']['file'][$index]['attribs'][$attr] = $value;
            return true;
        }
        if (!isset($this->_packageInfo['contents']['dir']['file'])) {
            return false;
        }
        $files = $this->_packageInfo['contents']['dir']['file'];
        if (!isset($files[0])) {
            $files = array($files);
            $ind = false;
        } else {
            $ind = true;
        }
        foreach ($files as $i => $file) {
            if (isset($file['attribs'])) {
                if ($file['attribs']['name'] == $filename) {
                    if ($ind) {
                        $this->_packageInfo['contents']['dir']['file'][$i]['attribs'][$attr] = $value;
                    } else {
                        $this->_packageInfo['contents']['dir']['file']['attribs'][$attr] = $value;
                    }
                    return true;
                }
            }
        }
        return false;
    }

    function setDirtree($path)
    {
        $this->_packageInfo['dirtree'][$path] = true;
    }

    function getDirtree()
    {
        if (isset($this->_packageInfo['dirtree']) && count($this->_packageInfo['dirtree'])) {
            return $this->_packageInfo['dirtree'];
        }
        return false;
    }

    function resetDirtree()
    {
        unset($this->_packageInfo['dirtree']);
    }

    /**
     * Determines whether this package claims it is compatible with the version of
     * the package that has a recommended version dependency
     * @param PEAR_PackageFile_v2|PEAR_PackageFile_v1|PEAR_Downloader_Package
     * @return boolean
     */
    function isCompatible($pf)
    {
        if (!isset($this->_packageInfo['compatible'])) {
            return false;
        }
        if (!isset($this->_packageInfo['channel'])) {
            return false;
        }
        $me = $pf->getVersion();
        $compatible = $this->_packageInfo['compatible'];
        if (!isset($compatible[0])) {
            $compatible = array($compatible);
        }
        $found = false;
        foreach ($compatible as $info) {
            if (strtolower($info['name']) == strtolower($pf->getPackage())) {
                if (strtolower($info['channel']) == strtolower($pf->getChannel())) {
                    $found = true;
                    break;
                }
            }
        }
        if (!$found) {
            return false;
        }
        if (isset($info['exclude'])) {
            if (!isset($info['exclude'][0])) {
                $info['exclude'] = array($info['exclude']);
            }
            foreach ($info['exclude'] as $exclude) {
                if (version_compare($me, $exclude, '==')) {
                    return false;
                }
            }
        }
        if (version_compare($me, $info['min'], '>=') && version_compare($me, $info['max'], '<=')) {
            return true;
        }
        return false;
    }

    /**
     * @param string Dependent package name
     * @param string Dependent package's channel name
     * @param string minimum version of specified package that this release is guaranteed to be
     *               compatible with
     * @param string maximum version of specified package that this release is guaranteed to be
     *               compatible with
     * @param string versions of specified package that this release is not compatible with
     */
    function addCompatiblePackage($name, $channel, $min, $max, $exclude = false)
    {
        $this->_isValid = 0;
        $set = array(
            'name' => $name,
            'channel' => $channel,
            'min' => $min,
            'max' => $max,
        );
        if ($exclude) {
            $set['exclude'] = $exclude;
        }
        $this->_isValid = 0;
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $set, array(
                'compatible' => array('dependencies', 'providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease', 'bundle', 'changelog')
            ));
    }

    /**
     * Remove all compatible tags
     */
    function clearCompatible()
    {
        unset($this->_packageInfo['compatible']);
    }

    /**
     * @return array|false
     */
    function getCompatible()
    {
        if (isset($this->_packageInfo['compatible'])) {
            return $this->_packageInfo['compatible'];
        }
        return false;
    }

    function getDependencies()
    {
        if (isset($this->_packageInfo['dependencies'])) {
            return $this->_packageInfo['dependencies'];
        }
        return false;
    }

    function isSubpackageOf($p)
    {
        return $p->isSubpackage($this);
    }

    /**
     * Determines whether the passed in package is a subpackage of this package.
     *
     * No version checking is done, only name verification.
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @return bool
     */
    function isSubpackage($p)
    {
        $sub = array();
        if (isset($this->_packageInfo['dependencies']['required']['subpackage'])) {
            $sub = $this->_packageInfo['dependencies']['required']['subpackage'];
            if (!isset($sub[0])) {
                $sub = array($sub);
            }
        }
        if (isset($this->_packageInfo['dependencies']['optional']['subpackage'])) {
            $sub1 = $this->_packageInfo['dependencies']['optional']['subpackage'];
            if (!isset($sub1[0])) {
                $sub1 = array($sub1);
            }
            $sub = array_merge($sub, $sub1);
        }
        if (isset($this->_packageInfo['dependencies']['group'])) {
            $group = $this->_packageInfo['dependencies']['group'];
            if (!isset($group[0])) {
                $group = array($group);
            }
            foreach ($group as $deps) {
                if (isset($deps['subpackage'])) {
                    $sub2 = $deps['subpackage'];
                    if (!isset($sub2[0])) {
                        $sub2 = array($sub2);
                    }
                    $sub = array_merge($sub, $sub2);
                }
            }
        }
        foreach ($sub as $dep) {
            if (strtolower($dep['name']) == strtolower($p->getPackage())) {
                if (isset($dep['channel'])) {
                    if (strtolower($dep['channel']) == strtolower($p->getChannel())) {
                        return true;
                    }
                } else {
                    if ($dep['uri'] == $p->getURI()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function dependsOn($package, $channel)
    {
        if (!($deps = $this->getDependencies())) {
            return false;
        }
        foreach (array('package', 'subpackage') as $type) {
            foreach (array('required', 'optional') as $needed) {
                if (isset($deps[$needed][$type])) {
                    if (!isset($deps[$needed][$type][0])) {
                        $deps[$needed][$type] = array($deps[$needed][$type]);
                    }
                    foreach ($deps[$needed][$type] as $dep) {
                        $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                        if (strtolower($dep['name']) == strtolower($package) &&
                              $depchannel == $channel) {
                            return true;
                        }  
                    }
                }
            }
            if (isset($deps['group'])) {
                if (!isset($deps['group'][0])) {
                    $dep['group'] = array($deps['group']);
                }
                foreach ($deps['group'] as $group) {
                    if (isset($group[$type])) {
                        if (!is_array($group[$type])) {
                            $group[$type] = array($group[$type]);
                        }
                        foreach ($group[$type] as $dep) {
                            $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                            if (strtolower($dep['name']) == strtolower($package) &&
                                  $depchannel == $channel) {
                                return true;
                            }  
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get the contents of a dependency group
     * @param string
     * @return array|false
     */
    function getDependencyGroup($name)
    {
        $name = strtolower($name);
        if (!isset($this->_packageInfo['dependencies']['group'])) {
            return false;
        }
        $groups = $this->_packageInfo['dependencies']['group'];
        if (!isset($groups[0])) {
            $groups = array($groups);
        }
        foreach ($groups as $group) {
            if (strtolower($group['attribs']['name']) == $name) {
                return $group;
            }
        }
        return false;
    }

    /**
     * Retrieve a partial package.xml 1.0 representation of dependencies
     *
     * a very limited representation of dependencies is returned by this method.
     * The <exclude> tag for excluding certain versions of a dependency is
     * completely ignored.  In addition, dependency groups are ignored, with the
     * assumption that all dependencies in dependency groups are also listed in
     * the optional group that work with all dependency groups
     * @param boolean return package.xml 2.0 <dependencies> tag
     * @return array|false
     */
    function getDeps($raw = false)
    {
        if (isset($this->_packageInfo['dependencies'])) {
            if ($raw) {
                return $this->_packageInfo['dependencies'];
            }
            $ret = array();
            $map = array(
                'php' => 'php',
                'package' => 'pkg',
                'subpackage' => 'pkg',
                'extension' => 'ext',
                'os' => 'os',
                'pearinstaller' => 'pkg',
                );
            foreach (array('required', 'optional') as $type) {
                $optional = ($type == 'optional') ? 'yes' : 'no';
                if (!isset($this->_packageInfo['dependencies'][$type])) {
                    continue;
                }
                foreach ($this->_packageInfo['dependencies'][$type] as $dtype => $deps) {
                    if (!isset($deps[0])) {
                        $deps = array($deps);
                    }
                    foreach ($deps as $dep) {
                        if (!isset($map[$dtype])) {
                            // no support for arch type
                            continue;
                        }
                        if ($dtype == 'pearinstaller') {
                            $dep['name'] = 'PEAR';
                            $dep['channel'] = 'pear.php.net';
                        }
                        $s = array('type' => $map[$dtype]);
                        if (isset($dep['channel'])) {
                            $s['channel'] = $dep['channel'];
                        }
                        if (isset($dep['uri'])) {
                            $s['uri'] = $dep['uri'];
                        }
                        if (isset($dep['name'])) {
                            $s['name'] = $dep['name'];
                        }
                        if (isset($dep['conflicts'])) {
                            $s['rel'] = 'not';
                        } else {
                            if (!isset($dep['min']) &&
                                  !isset($dep['max'])) {
                                $s['rel'] = 'has';
                            } elseif (isset($dep['min']) &&
                                  isset($dep['max'])) {
                                $s['rel'] = 'ge';
                                $s1 = $s;
                                $s1['rel'] = 'le';
                                $s['version'] = $dep['min'];
                                $s1['version'] = $dep['max'];
                                if (isset($dep['channel'])) {
                                    $s1['channel'] = $dep['channel'];
                                }
                                if ($dtype != 'php') {
                                    $s['name'] = $dep['name'];
                                    $s1['name'] = $dep['name'];
                                }
                                $s['optional'] = $optional;
                                $s1['optional'] = $optional;
                                $ret[] = $s1;
                            } elseif (isset($dep['min'])) {
                                $s['rel'] = 'ge';
                                $s['version'] = $dep['min'];
                                $s['optional'] = $optional;
                                if ($dtype != 'php') {
                                    $s['name'] = $dep['name'];
                                }
                            } elseif (isset($dep['max'])) {
                                $s['rel'] = 'le';
                                $s['version'] = $dep['max'];
                                $s['optional'] = $optional;
                                if ($dtype != 'php') {
                                    $s['name'] = $dep['name'];
                                }
                            }
                        }
                        $ret[] = $s;
                    }
                }
            }
            if (count($ret)) {
                return $ret;
            }
        }
        return false;
    }

    /**
     * Reset dependencies prior to adding new ones
     */
    function clearDeps()
    {
        if (!isset($this->_packageInfo['dependencies'])) {
            $this->_packageInfo = $this->_mergeTag($this->_packageInfo, array(),
                array(
                    'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                        'phprelease', 'extsrcrelease', 'extbinrelease',
                        'bundle', 'changelog')));
        }
        $this->_packageInfo['dependencies'] = array();
    }

    /**
     * @param string minimum PHP version allowed
     * @param string maximum PHP version allowed
     * @param array $exclude incompatible PHP versions
     */
    function setPhpDep($min, $max, $exclude = false)
    {
        $this->_isValid = 0;
        $dep =
            array(
                'min' => $min,
                'max' => $max
            );
        if ($exclude) {
            if (count($exclude) == 1) {
                $exclude = $exclude[0];
            }
            $dep['exclude'] = $exclude;
        }
        if (isset($this->_packageInfo['dependencies']['required']['php'])) {
            $this->_stack->push(__FUNCTION__, 'warning', array('dep' =>
            $this->_packageInfo['dependencies']['required']['php']),
                'warning: PHP dependency already exists, overwriting');
            unset($this->_packageInfo['dependencies']['required']['php']);
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'required' => array('optional', 'group'),
                'php' => array('pearinstaller', 'package', 'subpackage', 'extension', 'os', 'arch')
            ));
        return true;
    }

    /**
     * @param string minimum allowed PEAR installer version
     * @param string maximum allowed PEAR installer version
     * @param string recommended PEAR installer version
     * @param array incompatible version of the PEAR installer
     */
    function setPearinstallerDep($min, $max = false, $recommended = false, $exclude = false)
    {
        $this->_isValid = 0;
        $dep =
            array(
                'min' => $min,
            );
        if ($max) {
            $dep['max'] = $max;
        }
        if ($recommended) {
            $dep['recommended'] = $recommended;
        }
        if ($exclude) {
            if (count($exclude) == 1) {
                $exclude = $exclude[0];
            }
            $dep['exclude'] = $exclude;
        }
        if (isset($this->_packageInfo['dependencies']['required']['pearinstaller'])) {
            $this->_stack->push(__FUNCTION__, 'warning', array('dep' =>
            $this->_packageInfo['dependencies']['required']['pearinstaller']),
                'warning: PEAR Installer dependency already exists, overwriting');
            unset($this->_packageInfo['dependencies']['required']['pearinstaller']);
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'required' => array('optional', 'group'),
                'pearinstaller' => array('package', 'subpackage', 'extension', 'os', 'arch')
            ));
    }

    /**
     * Mark a package as conflicting with this package
     * @param string package name
     * @param string package channel
     * @param string extension this package provides, if any
     */
    function addConflictingPackageDepWithChannel($name, $channel, $providesextension = false)
    {
        $this->_isValid = 0;
        $dep =
            array(
                'name' => $name,
                'channel' => $channel,
                'conflicts' => '',
            );
        if ($providesextension) {
            $dep['providesextension'] = $providesextension;
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'required' => array('optional', 'group'),
                'package' => array('subpackage', 'extension', 'os', 'arch')
            ));
    }

    /**
     * Mark a package as conflicting with this package
     * @param string package name
     * @param string package channel
     * @param string extension this package provides, if any
     */
    function addConflictingPackageDepWithUri($name, $uri, $providesextension = false)
    {
        $this->_isValid = 0;
        $dep =
            array(
                'name' => $name,
                'uri' => $uri,
                'conflicts' => '',
            );
        if ($providesextension) {
            $dep['providesextension'] = $providesextension;
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'required' => array('optional', 'group'),
                'package' => array('subpackage', 'extension', 'os', 'arch')
            ));
    }

    function addDependencyGroup($name, $hint)
    {
        $this->_isValid = 0;
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo,
            array('attribs' => array('name' => $name, 'hint' => $hint)),
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'group' => array(),
            ));
    }

    /**
     * @param string package name
     * @param string|false channel name, false if this is a uri
     * @param string|false uri name, false if this is a channel
     * @param string|false minimum version required
     * @param string|false maximum version allowed
     * @param string|false recommended installation version
     * @param array|false versions to exclude from installation
     * @param string extension this package provides, if any
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     * @return array
     * @access private
     */
    function _constructDep($name, $channel, $uri, $min, $max, $recommended, $exclude,
                           $providesextension = false, $nodefault = false)
    {
        $dep =
            array(
                'name' => $name,
            );
        if ($channel) {
            $dep['channel'] = $channel;
        } elseif ($uri) {
            $dep['uri'] = $uri;
        }
        if ($min) {
            $dep['min'] = $min;
        }
        if ($max) {
            $dep['max'] = $max;
        }
        if ($recommended) {
            $dep['recommended'] = $recommended;
        }
        if ($exclude) {
            if (is_array($exclude) && count($exclude) == 1) {
                $exclude = $exclude[0];
            }
            $dep['exclude'] = $exclude;
        }
        if ($nodefault) {
            $dep['nodefault'] = '';
        }
        if ($providesextension) {
            $dep['providesextension'] = $providesextension;
        }
        return $dep;
    }

    /**
     * @param package|subpackage
     * @param string group name
     * @param string package name
     * @param string package channel
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param array|false optional excluded versions
     * @param string extension this package provides, if any
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     * @return bool false if the dependency group has not been initialized with
     *              {@link addDependencyGroup()}, or a subpackage is added with
     *              a providesextension
     */
    function addGroupPackageDepWithChannel($type, $groupname, $name, $channel, $min = false,
                                      $max = false, $recommended = false, $exclude = false,
                                      $providesextension = false, $nodefault = false)
    {
        if ($type == 'subpackage' && $providesextension) {
            return false; // subpackages must be php packages
        }
        $dep = $this->_constructDep($name, $channel, false, $min, $max, $recommended, $exclude,
            $providesextension, $nodefault);
        return $this->_addGroupDependency($type, $dep, $groupname);
    }

    /**
     * @param package|subpackage
     * @param string group name
     * @param string package name
     * @param string package uri
     * @param string extension this package provides, if any
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     * @return bool false if the dependency group has not been initialized with
     *              {@link addDependencyGroup()}
     */
    function addGroupPackageDepWithURI($type, $groupname, $name, $uri, $providesextension = false,
                                       $nodefault = false)
    {
        if ($type == 'subpackage' && $providesextension) {
            return false; // subpackages must be php packages
        }
        $dep = $this->_constructDep($name, false, $uri, false, false, false, false,
            $providesextension, $nodefault);
        return $this->_addGroupDependency($type, $dep, $groupname);
    }

    /**
     * @param string group name (must be pre-existing)
     * @param string extension name
     * @param string minimum version allowed
     * @param string maximum version allowed
     * @param string recommended version
     * @param array incompatible versions
     */
    function addGroupExtensionDep($groupname, $name, $min = false, $max = false,
                                         $recommended = false, $exclude = false)
    {
        $this->_isValid = 0;
        $dep = $this->_constructDep($name, false, false, $min, $max, $recommended, $exclude);
        return $this->_addGroupDependency('extension', $dep, $groupname);
    }

    /**
     * @param package|subpackage|extension
     * @param array dependency contents
     * @param string name of the dependency group to add this to
     * @return boolean
     * @access private
     */
    function _addGroupDependency($type, $dep, $groupname)
    {
        $arr = array('subpackage', 'extension');
        if ($type != 'package') {
            array_shift($arr);
        }
        if ($type == 'extension') {
            array_shift($arr);
        }
        if (!isset($this->_packageInfo['dependencies']['group'])) {
            return false;
        } else {
            if (!isset($this->_packageInfo['dependencies']['group'][0])) {
                if ($this->_packageInfo['dependencies']['group']['attribs']['name'] == $groupname) {
                    $this->_packageInfo['dependencies']['group'] = $this->_mergeTag(
                        $this->_packageInfo['dependencies']['group'], $dep,
                        array(
                            $type => $arr
                        ));
                    $this->_isValid = 0;
                    return true;
                } else {
                    return false;
                }
            } else {
                foreach ($this->_packageInfo['dependencies']['group'] as $i => $group) {
                    if ($group['attribs']['name'] == $groupname) {
                    $this->_packageInfo['dependencies']['group'][$i] = $this->_mergeTag(
                        $this->_packageInfo['dependencies']['group'][$i], $dep,
                        array(
                            $type => $arr
                        ));
                        $this->_isValid = 0;
                        return true;
                    }
                }
                return false;
            }
        }
    }

    /**
     * @param optional|required
     * @param string package name
     * @param string package channel
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param string extension this package provides, if any
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     * @param array|false optional excluded versions
     */
    function addPackageDepWithChannel($type, $name, $channel, $min = false, $max = false,
                                      $recommended = false, $exclude = false,
                                      $providesextension = false, $nodefault = false)
    {
        $this->_isValid = 0;
        $arr = array('optional', 'group');
        if ($type != 'required') {
            array_shift($arr);
        }
        $dep = $this->_constructDep($name, $channel, false, $min, $max, $recommended, $exclude,
            $providesextension, $nodefault);
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                $type => $arr,
                'package' => array('subpackage', 'extension', 'os', 'arch')
            ));
    }

    /**
     * @param optional|required
     * @param string name of the package
     * @param string uri of the package
     * @param string extension this package provides, if any
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     */
    function addPackageDepWithUri($type, $name, $uri, $providesextension = false,
                                  $nodefault = false)
    {
        $this->_isValid = 0;
        $arr = array('optional', 'group');
        if ($type != 'required') {
            array_shift($arr);
        }
        $dep = $this->_constructDep($name, false, $uri, false, false, false, false,
            $providesextension, $nodefault);
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                $type => $arr,
                'package' => array('subpackage', 'extension', 'os', 'arch')
            ));
    }

    /**
     * @param optional|required optional, required
     * @param string package name
     * @param string package channel
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param array incompatible versions
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     */
    function addSubpackageDepWithChannel($type, $name, $channel, $min = false, $max = false,
                                         $recommended = false, $exclude = false,
                                         $nodefault = false)
    {
        $this->_isValid = 0;
        $arr = array('optional', 'group');
        if ($type != 'required') {
            array_shift($arr);
        }
        $dep = $this->_constructDep($name, $channel, false, $min, $max, $recommended, $exclude,
            $nodefault);
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                $type => $arr,
                'subpackage' => array('extension', 'os', 'arch')
            ));
    }

    /**
     * @param optional|required optional, required
     * @param string package name
     * @param string package uri for download
     * @param bool if true, tells the installer to ignore the default optional dependency group
     *             when installing this package
     */
    function addSubpackageDepWithUri($type, $name, $uri, $nodefault = false)
    {
        $this->_isValid = 0;
        $arr = array('optional', 'group');
        if ($type != 'required') {
            array_shift($arr);
        }
        $dep = $this->_constructDep($name, false, $uri, false, false, false, false, $nodefault);
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                $type => $arr,
                'subpackage' => array('extension', 'os', 'arch')
            ));
    }

    /**
     * @param optional|required optional, required
     * @param string extension name
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param array incompatible versions
     */
    function addExtensionDep($type, $name, $min = false, $max = false, $recommended = false,
                             $exclude = false)
    {
        $this->_isValid = 0;
        $arr = array('optional', 'group');
        if ($type != 'required') {
            array_shift($arr);
        }
        $dep = $this->_constructDep($name, false, false, $min, $max, $recommended, $exclude);
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                $type => $arr,
                'extension' => array('os', 'arch')
            ));
    }

    /**
     * @param string Operating system name
     * @param boolean true if this package cannot be installed on this OS
     */
    function addOsDep($name, $conflicts = false)
    {
        $this->_isValid = 0;
        $dep = array('name' => $name);
        if ($conflicts) {
            $dep['conflicts'] = '';
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'required' => array('optional', 'group'),
                'os' => array('arch')
            ));
    }

    /**
     * @param string Architecture matching pattern
     * @param boolean true if this package cannot be installed on this architecture
     */
    function addArchDep($pattern, $conflicts = false)
    {
        $this->_isValid = 0;
        $dep = array('pattern' => $pattern);
        if ($conflicts) {
            $dep['conflicts'] = '';
        }
        $this->_packageInfo = $this->_mergeTag($this->_packageInfo, $dep,
            array(
                'dependencies' => array('providesextension', 'srcpackage', 'srcuri',
                    'phprelease', 'extsrcrelease', 'extbinrelease',
                    'bundle', 'changelog'),
                'required' => array('optional', 'group'),
                'arch' => array()
            ));
    }

    /**
     * @return php|extsrc|extbin|bundle|false
     */
    function getPackageType()
    {
        if (isset($this->_packageInfo['phprelease'])) {
            return 'php';
        }
        if (isset($this->_packageInfo['extsrcrelease'])) {
            return 'extsrc';
        }
        if (isset($this->_packageInfo['extbinrelease'])) {
            return 'extbin';
        }
        if (isset($this->_packageInfo['bundle'])) {
            return 'bundle';
        }
        return false;
    }

    /**
     * Set the kind of package, and erase all release tags
     *
     * - a php package is a PEAR-style package
     * - an extbin package is a PECL-style extension binary
     * - an extsrc package is a PECL-style source for a binary
     * - a bundle package is a collection of other pre-packaged packages
     * @param php|extbin|extsrc|bundle
     * @return bool success
     */
    function setPackageType($type)
    {
        $this->_isValid = 0;
        if (!in_array($type, array('php', 'extbin', 'extsrc', 'bundle'))) {
            return false;
        }
        if ($type != 'bundle') {
            $type .= 'release';
        }
        foreach (array('phprelease', 'extbinrelease', 'extsrcrelease', 'bundle') as $test) {
            unset($this->_packageInfo[$test]);
        }
        if (!isset($this->_packageInfo[$type])) {
            // ensure that the release tag is set up
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo, array('changelog'),
                array(), $type);
        }
        $this->_packageInfo[$type] = array();
        return true;
    }

    /**
     * @return bool true if package type is set up
     */
    function addRelease()
    {
        if ($type = $this->getPackageType()) {
            if ($type != 'bundle') {
                $type .= 'release';
            }
            $this->_packageInfo = $this->_mergeTag($this->_packageInfo, array(),
                array($type => array('changelog')));
            return true;
        }
        return false;
    }

    /**
     * @return array|false
     */
    function getReleases()
    {
        $type = $this->getPackageType();
        if ($type != 'bundle') {
            $type .= 'release';
        }
        if ($this->getPackageType() && isset($this->_packageInfo[$type])) {
            return $this->_packageInfo[$type];
        }
        return false;
    }

    /**
     * Get the current release tag in order to add to it
     * @param bool returns only releases that have installcondition if true
     * @return array|null
     */
    function &_getCurrentRelease($strict = true)
    {
        if ($p = $this->getPackageType()) {
            if ($strict) {
                if ($p == 'extsrc') {
                    $a = null;
                    return $a;
                }
            }
            if ($p != 'bundle') {
                $p .= 'release';
            }
            if (isset($this->_packageInfo[$p][0])) {
                return $this->_packageInfo[$p][count($this->_packageInfo[$p]) - 1];
            } else {
                return $this->_packageInfo[$p];
            }
        } else {
            $a = null;
            return $a;
        }
    }

    /**
     * Add a file to the current release that should be installed under a different name
     * @param string <contents> path to file
     * @param string name the file should be installed as
     */
    function addInstallAs($path, $as)
    {
        $r = &$this->_getCurrentRelease();
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        $r = $this->_mergeTag($r, array('attribs' => array('name' => $path, 'as' => $as)),
            array(
                'filelist' => array(),
                'install' => array('ignore')
            ));
    }

    /**
     * Add a file to the current release that should be ignored
     * @param string <contents> path to file
     * @return bool success of operation
     */
    function addIgnore($path)
    {
        $r = &$this->_getCurrentRelease();
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        $r = $this->_mergeTag($r, array('attribs' => array('name' => $path)),
            array(
                'filelist' => array(),
                'ignore' => array()
            ));
    }

    /**
     * Add an extension binary package for this extension source code release
     *
     * Note that the package must be from the same channel as the extension source package
     * @param string
     */
    function addBinarypackage($package)
    {
        if ($this->getPackageType() != 'extsrc') {
            return false;
        }
        $r = &$this->_getCurrentRelease(false);
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        $r = $this->_mergeTag($r, $package,
            array(
                'binarypackage' => array(),
            ));
    }

    /**
     * Add a configureoption to an extension source package
     * @param string
     * @param string
     * @param string
     */
    function addConfigureOption($name, $prompt, $default = null)
    {
        if ($this->getPackageType() != 'extsrc') {
            return false;
        }
        $r = &$this->_getCurrentRelease(false);
        if ($r === null) {
            return false;
        }
        $opt = array('attribs' => array('name' => $name, 'prompt' => $prompt));
        if ($default !== null) {
            $opt['default'] = $default;
        }
        $this->_isValid = 0;
        $r = $this->_mergeTag($r, $opt,
            array(
                'configureoption' => array('binarypackage'),
            ));
    }

    /**
     * Set an installation condition based on php version for the current release set
     * @param string minimum version
     * @param string maximum version
     * @param false|array incompatible versions of PHP
     */
    function setPhpInstallCondition($min, $max, $exclude = false)
    {
        $r = &$this->_getCurrentRelease();
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        if (isset($r['installconditions']['php'])) {
            unset($r['installconditions']['php']);
        }
        $dep = array('min' => $min, 'max' => $max);
        if ($exclude) {
            if (is_array($exclude) && count($exclude) == 1) {
                $exclude = $exclude[0];
            }
            $dep['exclude'] = $exclude;
        }
        $r = $this->_mergeTag($r, $dep,
            array(
                'installconditions' => array('filelist'),
                'php' => array('extension', 'os', 'arch')
            ));
    }

    /**
     * @param optional|required optional, required
     * @param string extension name
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param array incompatible versions
     */
    function addExtensionInstallCondition($name, $min = false, $max = false, $recommended = false,
                                          $exclude = false)
    {
        $r = &$this->_getCurrentRelease();
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        $dep = $this->_constructDep($name, false, false, $min, $max, $recommended, $exclude);
        $r = $this->_mergeTag($r, $dep,
            array(
                'installconditions' => array('filelist'),
                'extension' => array('os', 'arch')
            ));
    }

    /**
     * Set an installation condition based on operating system for the current release set
     * @param string OS name
     * @param bool whether this OS is incompatible with the current release
     */
    function setOsInstallCondition($name, $conflicts = false)
    {
        $r = &$this->_getCurrentRelease();
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        if (isset($r['installconditions']['os'])) {
            unset($r['installconditions']['os']);
        }
        $dep = array('name' => $name);
        if ($conflicts) {
            $dep['conflicts'] = '';
        }
        $r = $this->_mergeTag($r, $dep,
            array(
                'installconditions' => array('filelist'),
                'os' => array('arch')
            ));
    }

    /**
     * Set an installation condition based on architecture for the current release set
     * @param string architecture pattern
     * @param bool whether this arch is incompatible with the current release
     */
    function setArchInstallCondition($pattern, $conflicts = false)
    {
        $r = &$this->_getCurrentRelease();
        if ($r === null) {
            return false;
        }
        $this->_isValid = 0;
        if (isset($r['installconditions']['arch'])) {
            unset($r['installconditions']['arch']);
        }
        $dep = array('pattern' => $pattern);
        if ($conflicts) {
            $dep['conflicts'] = '';
        }
        $r = $this->_mergeTag($r, $dep,
            array(
                'installconditions' => array('filelist'),
                'arch' => array()
            ));
    }

    function hasDeps()
    {
        return isset($this->_packageInfo['dependencies']);
    }

    function getPackagexmlVersion()
    {
        return '2.0';
    }

    /**
     * For extension binary releases, this is used to specify either the
     * static URI to a source package, or the package name and channel of the extsrc
     * package it is based on.
     * @param string Package name, or full URI to source package (extsrc type)
     * @param string|false channel name
     */
    function setSourcePackage($packageOrUri, $channel = false)
    {
        $this->_isValid = 0;
        if ($channel) {
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo, array('phprelease',
                'extsrcrelease', 'extbinrelease', 'bundle', 'changelog'),
                $packageOrUri, 'srcpackage');
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo, array('phprelease',
                'extsrcrelease', 'extbinrelease', 'bundle', 'changelog'), $channel, 'srcchannel');
        } else {
            $this->_packageInfo = $this->_insertBefore($this->_packageInfo, array('phprelease',
                'extsrcrelease', 'extbinrelease', 'bundle', 'changelog'), $packageOrUri, 'srcuri');
        }
    }

    /**
     * @return array|false
     */
    function getSourcePackage()
    {
        if (isset($this->_packageInfo['extbinrelease'])) {
            return array('channel' => $this->_packageInfo['srcchannel'],
                         'package' => $this->_packageInfo['srcpackage']);
        }
        return false;
    }

    function getBundledPackages()
    {
        if (isset($this->_packageInfo['bundle'])) {
            return $this->_packageInfo['contents']['bundledpackage'];
        }
        return false;
    }

    function getLastModified()
    {
        if (isset($this->_packageInfo['_lastmodified'])) {
            return $this->_packageInfo['_lastmodified'];
        }
        return false;
    }

    /**
     * Get the contents of a file listed within the package.xml
     * @param string
     * @return string
     */
    function getFileContents($file)
    {
        if ($this->_archiveFile == $this->_packageFile) { // unpacked
            $dir = dirname($this->_packageFile);
            $file = $dir . DIRECTORY_SEPARATOR . $file;
            $file = str_replace(array('/', '\\'),
                array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR), $file);
            if (file_exists($file) && is_readable($file)) {
                return implode('', file($file));
            }
        } else { // tgz
            $tar = &new Archive_Tar($this->_archiveFile);
            $tar->pushErrorHandling(PEAR_ERROR_RETURN);
            if ($file != 'package.xml' && $file != 'package2.xml') {
                $file = $this->getPackage() . '-' . $this->getVersion() . '/' . $file;
            }
            $file = $tar->extractInString($file);
            $tar->popErrorHandling();
            if (PEAR::isError($file)) {
                return PEAR::raiseError("Cannot locate file '$file' in archive");
            }
            return $file;
        }
    }

    function &getDefaultGenerator()
    {
        $a = &new PEAR_PackageFile_Generator_v2($this);
        return $a;
    }

    /**
     * Key-friendly array_splice
     * @param tagname to splice a value in before
     * @param mixed the value to splice in
     * @param string the new tag name
     */
    function _ksplice($array, $key, $value, $newkey)
    {
        $offset = array_search($key, array_keys($array));
        $after = array_slice($array, $offset);
        $before = array_slice($array, 0, $offset);
        $before[$newkey] = $value;
        return array_merge($before, $after);
    }

    /**
     * @param array a list of possible keys, in the order they may occur
     * @param mixed contents of the new package.xml tag
     * @param string tag name
     * @access private
     */
    function _insertBefore($array, $keys, $contents, $newkey)
    {
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                return $array = $this->_ksplice($array, $key, $contents, $newkey);
            }
        }
        $array[$newkey] = $contents;
        return $array;
    }

    /**
     * @param subsection of {@link $_packageInfo}
     * @param array|string tag contents
     * @param array format:
     * <pre>
     * array(
     *   tagname => array(list of tag names that follow this one),
     *   childtagname => array(list of child tag names that follow this one),
     * )
     * </pre>
     *
     * This allows construction of nested tags
     * @access private
     */
    function _mergeTag($manip, $contents, $order)
    {
        if (count($order)) {
            foreach ($order as $tag => $curorder) {
                if (!isset($manip[$tag])) {
                    // ensure that the tag is set up
                    $manip = $this->_insertBefore($manip, $curorder, array(), $tag);
                }
                if (count($order) > 1) {
                    $manip[$tag] = $this->_mergeTag($manip[$tag], $contents, array_slice($order, 1));
                    return $manip;
                }
            }
        } else {
            return $manip;
        }
        if (is_array($manip[$tag]) && !empty($manip[$tag]) && isset($manip[$tag][0])) {
            $manip[$tag][] = $contents;
        } else {
            if (!count($manip[$tag])) {
                $manip[$tag] = $contents;
            } else {
                $manip[$tag] = array($manip[$tag]);
                $manip[$tag][] = $contents;
            }
        }
        return $manip;
    }

    function analyzeSourceCode($file, $string = false)
    {
        if (!isset($this->_v2Validator)) {
            $this->_v2Validator = new PEAR_PackageFile_v2_Validator;
        }
        return $this->_v2Validator->analyzeSourceCode($file, $string);
    }

    function validate($state = PEAR_VALIDATE_NORMAL)
    {
        if (!isset($this->_packageInfo) || !is_array($this->_packageInfo)) {
            return false;
        }
        if (!isset($this->_v2Validator)) {
            $this->_v2Validator = new PEAR_PackageFile_v2_Validator;
        }
        if (isset($this->_packageInfo['xsdversion'])) {
            unset($this->_packageInfo['xsdversion']);
        }
        return $this->_v2Validator->validate($this, $state);
    }

    function getTasksNs()
    {
        return $this->_tasksNs;
    }

    /**
     * Determine whether a task name is a valid task.  Custom tasks may be defined
     * using subdirectories by putting a "-" in the name, as in <tasks:mycustom-task>
     *
     * Note that this method will auto-load the task class file and test for the existence
     * of the name with "-" replaced by "_" as in PEAR/Task/mycustom/task.php makes class
     * PEAR_Task_mycustom_task
     * @param string
     * @return boolean
     */
    function getTask($task)
    {
        if (!isset($this->_tasksNs)) {
            if (isset($this->_packageInfo['attribs'])) {
                foreach ($this->_packageInfo['attribs'] as $name => $value) {
                    if ($value == 'http://pear.php.net/dtd/tasks-1.0') {
                        $this->_tasksNs = str_replace('xmlns:', '', $name);
                        break;
                    }
                }
            }
        }
        // transform all '-' to '/' and 'tasks:' to '' so tasks:replace becomes replace
        $task = str_replace(array($this->_tasksNs . ':', '-'), array('', ' '), $task);
        $task = str_replace(' ', '/', ucwords($task));
        $ps = (strtolower(substr(PHP_OS, 0, 3)) == 'win') ? ';' : ':';
        foreach (explode($ps, ini_get('include_path')) as $path) {
            if (file_exists($path . "/PEAR/Task/$task.php")) {
                include_once "PEAR/Task/$task.php";
                $task = str_replace('/', '_', $task);
                if (class_exists("PEAR_Task_$task")) {
                    return "PEAR_Task_$task";
                }
            }
        }
        return false;
    }
}
?>