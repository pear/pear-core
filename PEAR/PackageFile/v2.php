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
require_once 'PEAR/PackageFile/Generator/v2.php';
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
    var $_packageInfo;

    /**
     * path to package .tgz or false if this is a local/extracted package.xml
     * @var string
     * @access private
     */
    var $_archiveFile;

    var $_packageFile;
    
    var $_logger;
    
    var $_prettyFilelists = false;
    
    var $_isValid = false;

    var $_registry;

    /**
     * Optional Dependency group requested for installation
     * @var string
     * @access private
     */
    var $_requestedGroup = false;

    /**
     * @var PEAR_ErrorStack
     */
    var $_stack;

    /**
     * Namespace prefix used for tasks in this package.xml - use tasks: whenever possible
     */
    var $_tasksNs;
    function PEAR_PackageFile_v2()
    {
        $this->_stack = new PEAR_ErrorStack('PEAR_PackageFile_v2', false, null);
        $this->_isValid = false;
    }

    function &getPEARDownloader(&$i, $o, &$c)
    {
        $z = &new PEAR_Downloader($i, $o, $c);
        return $z;
    }

    /**
     * Installation of source package has failed, attempt to download and install the
     * binary version of this package
     * @param PEAR_Installer
     */
    function installBinary(&$installer)
    {
        if ($this->getPackageType() == 'extsrc') {
            foreach ($installer->getInstallPackages() as $p) {
                if ($p->isExtension($this->_packageInfo['extsrcrelease']['providesextension'])) {
                    if ($p->getPackage() != $this->getPackage() &&
                          $p->getChannel() != $this->getChannel()) {
                        return false; // the user probably downloaded it separately
                    }
                }
            }
            if (isset($this->_packageInfo['extsrcrelease']['binarypackage'])) {
                $installer->log(0, 'Attempting to download binary version of extension "' .
                    $this->_packageInfo['extsrcrelease']['providesextension'] . '"');
                $params = $this->_packageInfo['extsrcrelease']['binarypackage'];
                if (!isset($params[0])) {
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
                    if (is_array($ret)) {
                        break;
                    }
                }
                $dl->config->set('verbose', $verbose);
                if (is_array($ret)) {
                    if (count($ret) == 1) {
                        $pf = $ret[0]->getPackageFile();
                        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                        $ret = $installer->install($pf);
                        PEAR::popErrorHandling();
                        if (is_array($ret)) {
                            $installer->log(0, 'Download and install of binary extension "' .
                                $this->_registry->parsedPackageNameToString(
                                    array('channel' => $pf->getChannel(),
                                          'package' => $pf->getPackage())) . '" successful');
                            return true;
                        }
                        $installer->log(0, 'Download and install of binary extension "' .
                            $this->_registry->parsedPackageNameToString(
                                    array('channel' => $pf->getChannel(),
                                          'package' => $pf->getPackage())) . '" failed');
                    }
                }
            }
        }
        return false;
    }

    function isExtension($name)
    {
        if (in_array($this->getPackageType(), array('extsrc', 'extbin'))) {
            return $this->_packageInfo[$this->getPackageType() . 'release']
                ['providesextension'] == $name;
        }
        return false;
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

    function flattenFilelist()
    {
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

    function _getFlattenedFilelist(&$files, $dir, $baseinstall = false, $path = '')
    {
        if (isset($dir['attribs']['baseinstalldir'])) {
            $baseinstall = $dir['attribs']['baseinstalldir'];
        }
        if (isset($dir['dir'])) {
            if (isset($dir['dir']['attribs'])) {
                $newpath = empty($path) ? $dir['dir']['attribs']['name'] :
                    $path . '/' . $dir['dir']['attribs']['name'];
                $this->_getFlattenedFilelist($files, $dir['dir'],
                    $baseinstall, $newpath);
            } else {
                foreach ($dir['dir'] as $subdir) {
                    $newpath = empty($path) ? $subdir['attribs']['name'] :
                        $path . '/' . $subdir['attribs']['name'];
                    $this->_getFlattenedFilelist($files, $subdir,
                        $baseinstall, $newpath);
                }
            }
        }
        if (isset($dir['file'])) {
            if (isset($dir['file']['attribs'])) {
                $attrs = $dir['file']['attribs'];
                $name = $attrs['name'];
                if ($baseinstall) {
                    $attrs['baseinstalldir'] = $baseinstall;
                }
                $attrs['name'] = empty($path) ? $name : $path . '/' . $name;
                $file['attribs'] = $attrs;
                $files[] = $file;
            } else {
                foreach ($dir['file'] as $file) {
                    $attrs = $file['attribs'];
                    $name = $attrs['name'];
                    if ($baseinstall) {
                        $attrs['baseinstalldir'] = $baseinstall;
                    }
                    $attrs['name'] = empty($path) ? $name : $path . '/' . $name;
                    $file['attribs'] = $attrs;
                    $files[] = $file;
                }
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
        if (!is_object($logger) || !method_exists($logger, 'log')) {
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
            $this->_packageInfo['old'] = array();
            $this->_packageInfo['old']['version'] = $this->getVersion();
            $this->_packageInfo['old']['release_date'] = $this->getDate();
            $this->_packageInfo['old']['release_state'] = $this->getState();
            $this->_packageInfo['old']['release_license'] = $this->getLicense();
            $this->_packageInfo['old']['release_notes'] = $this->getNotes();
            $this->_packageInfo['old']['release_deps'] = $this->getDeps();
            $this->_packageInfo['old']['maintainers'] = $this->getMaintainers();
            $this->_packageInfo['xsdversion'] = '2.0';
            return $this->_packageInfo;
        } else {
            $info = $this->_packageInfo;
            unset($info['dirtree']);
            return $info;
        }
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
        if (!isset($this->_packageInfo['name'])) {
            return $this->_packageInfo = array_merge(array('name' => array($package)),
                $this->_packageInfo);
        }
        $this->_packageInfo['name'] = $package;
        if (!isset($this->_packageInfo['attribs'])) {
            return $this->_packageInfo = array_merge(array('attribs' => array(
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
            return $this->_insertBefore($this->_packageInfo, 
                array('extends', 'summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
            return $this->_insertBefore($this->_packageInfo,
                array('extends', 'summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
            return $this->_insertBefore($this->_packageInfo,
                array('summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
            return $this->_insertBefore($this->_packageInfo,
                array('summary', 'description', 'lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
        if (!isset($this->_packageInfo['summary'])) {
            // ensure that the description tag is set up in the right location
            return $this->_insertBefore($this->_packageInfo,
                array('lead',
                'developer', 'contributor', 'helper', 'date', 'time', 'version',
                'stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
                    'dependencies', 'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog');
            foreach (array('lead', 'developer', 'contributor', 'helper') as $testrole) {
                array_shift($testarr);
                if ($role == $testrole) {
                    break;
                }
            }
            if (!isset($this->_packageInfo[$role])) {
                // ensure that the extends tag is set up in the right location
                $this->_insertBefore($this->_packageInfo, $testarr, array(), $role);
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
            $this->_insertBefore($this->_packageInfo,
                array('time', 'version',
                    'stability', 'license', 'notes', 'contents', 'compatible',
                    'dependencies', 'phprelease', 'extsrcrelease',
                    'extbinrelease', 'bundle', 'changelog'), array(), 'stability');
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
            return $this->_insertBefore($this->_packageInfo,
                    array('version',
                    'stability', 'license', 'notes', 'contents', 'compatible',
                    'dependencies', 'phprelease', 'extsrcrelease',
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
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['version'])) {
            // ensure that the version tag is set up in the right location
            $this->_insertBefore($this->_packageInfo,
                array('stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'version');
        }
        $this->_packageInfo['version']['release'] = $version;
    }

    function setAPIVersion($version)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['version'])) {
            // ensure that the version tag is set up in the right location
            $this->_insertBefore($this->_packageInfo,
                array('stability', 'license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'version');
        }
        $this->_packageInfo['version']['api'] = $version;
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

    function setReleaseStability($state)
    {
        if (!isset($this->_packageInfo['stability'])) {
            // ensure that the stability tag is set up in the right location
            $this->_insertBefore($this->_packageInfo,
                array('license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'stability');
        }
        $this->_packageInfo['stability']['release'] = $state;
        $this->_isValid = 0;
    }

    function setAPIStability($state)
    {
        if (!isset($this->_packageInfo['stability'])) {
            // ensure that the stability tag is set up in the right location
            $this->_insertBefore($this->_packageInfo,
                array('license', 'notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'stability');
        }
        $this->_packageInfo['stability']['api'] = $state;
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
            $this->_insertBefore($this->_packageInfo,
                array('notes', 'contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
        if (!isset($this->_packageInfo['license'])) {
            // ensure that the notes tag is set up in the right location
            return $this->_insertBefore($this->_packageInfo,
                array('contents', 'compatible',
                'dependencies', 'phprelease', 'extsrcrelease',
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
     */
    function getInstallationFilelist()
    {
        $contents = $this->getFilelist(true);
        if (isset($contents['dir']['attribs']['baseinstalldir'])) {
            $base = $contents['dir']['attribs']['baseinstalldir'];
        }
        if (isset($this->_packageInfo['phprelease'])) {
            $release = $this->_packageInfo['phprelease'];
        } elseif (isset($this->_packageInfo['extsrcrelease'])) {
            $release = $this->_packageInfo['extsrcrelease'];
        } elseif (isset($this->_packageInfo['extbinrelease'])) {
            $release = $this->_packageInfo['extbinrelease'];
        } // bundles should never reach this point
        if (isset($this->_packageInfo['bundle'])) {
            return PEAR::raiseError(
                'Exception: bundles should be handled in download code only');
        }
        if ($release) {
            if (!isset($release[0])) {
                if (!isset($release['installconditions']) && !isset($release['filelist'])) {
                    return $contents;
                }
                $release = array($release);
            }
            include_once 'PEAR/Dependency2.php';
            $depchecker = &new PEAR_Dependency2($this->_config, array(),
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

    function addFile($dir, $file, $attrs)
    {
        $this->_isValid = 0;
        if ($dir == '/') {
            $dir = '';
        } else {
            $dir .= '/';
        }
        $attrs['name'] = $dir . $file;
        if (!isset($this->_packageInfo['contents'])) {
            // ensure that the contents tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('compatible', 'dependencies', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'contents');
        }
        $this->_packageInfo['contents']['dir']['attribs']['name'] = '/';
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

    function setFileAttribute($file, $attr, $value, $index)
    {
        $this->_isValid = 0;
        if (isset($this->_packageInfo['contents']['dir']['file']['attribs'])) {
            if ($this->_packageInfo['contents']['dir']['file']['attribs']['name'] == $file) {
                $this->_packageInfo['contents']['dir']['file']['attribs'][$attr] = $value;
                return;
            }
        }
        if (isset($this->_packageInfo['contents']['dir']['file'][$index]['attribs'])) {
            $this->_packageInfo['contents']['dir']['file'][$index]['attribs'][$attr] = $value;
        }
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

    function addCompatiblePackage($name, $channel, $min, $max, $exclude = false)
    {
        if (!isset($this->_packageInfo['compatible'])) {
            // ensure that the compatible tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('dependencies', 'phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'contents');
        }
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
        if (isset($this->_packageInfo['compatible'])) {
            if (!isset($this->_packageInfo['compatible'][0])) {
                $this->_packageInfo['compatible'] = array($this->_packageInfo['compatible']);
            }
            $this->_packageInfo['compatible'][] = $set;
        } else {
            $this->_packageInfo['compatible'] = $set;
        }
    }

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
     * Determines whether the passed in package is a subpackage of this package
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     */
    function isSubpackage($p)
    {
        if (isset($this->_packageInfo['dependencies']['required']['subpackage'])) {
            $sub = $this->_packageInfo['dependencies']['required']['subpackage'];
            if (!isset($sub[0])) {
                $sub = array($sub);
            }
            foreach ($sub as $dep) {
                if ($dep['name'] == $p->getPackage()) {
                    if (isset($dep['channel'])) {
                        if ($dep['channel'] == $p->getChannel()) {
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
            }
        }
        if (isset($this->_packageInfo['dependencies']['optional']['subpackage'])) {
            $sub = $this->_packageInfo['dependencies']['optional']['subpackage'];
            if (!isset($sub[0])) {
                $sub = array($sub);
            }
            foreach ($sub as $dep) {
                if ($dep['name'] == $p->getPackage()) {
                    if (isset($dep['channel'])) {
                        if ($dep['channel'] == $p->getChannel()) {
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
            }
        }
        if (isset($this->_packageInfo['dependencies']['group'])) {
            $group = $this->_packageInfo['dependencies']['group'];
            if (!isset($group[0])) {
                $group = array($group);
            }
            foreach ($group as $deps) {
                if (isset($deps['subpackage'])) {
                    $sub = $deps['subpackage'];
                    if (!isset($sub[0])) {
                        $sub = array($sub);
                    }
                    foreach ($sub as $dep) {
                        if ($dep['name'] == $p->getPackage()) {
                            if (isset($dep['channel'])) {
                                if ($dep['channel'] == $p->getChannel()) {
                                    return true;
                                }
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    function dependsOn($package, $channel)
    {
        if (!($deps = $this->getDeps(true))) {
            return false;
        }
        if (isset($deps['required']['package'])) {
            if (!isset($deps['required']['package'][0])) {
                $deps['required']['package'] = array($deps['required']['package']);
            }
            foreach ($deps['required']['package'] as $dep) {
                $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                if (strtolower($dep['name']) == strtolower($package) &&
                      $depchannel == $channel) {
                    return true;
                }  
            }
        }
        if (isset($deps['required']['subpackage'])) {
            if (!isset($deps['required']['subpackage'][0])) {
                $deps['required']['subpackage'] = array($deps['required']['subpackage']);
            }
            foreach ($deps['required']['subpackage'] as $dep) {
                $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                if (strtolower($dep['name']) == strtolower($package) &&
                      $depchannel == $channel) {
                    return true;
                }  
            }
        }
        if (isset($deps['optional']['package'])) {
            if (!isset($deps['optional']['package'][0])) {
                $deps['optional']['package'] = array($deps['optional']['package']);
            }
            foreach ($deps['optional']['package'] as $dep) {
                $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                if (strtolower($dep['name']) == strtolower($package) &&
                      $depchannel == $channel) {
                    return true;
                }  
            }
        }
        if (isset($deps['optional']['subpackage'])) {
            if (!isset($deps['optional']['subpackage'][0])) {
                $deps['optional']['subpackage'] = array($deps['optional']['subpackage']);
            }
            foreach ($deps['optional']['subpackage'] as $dep) {
                $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                if (strtolower($dep['name']) == strtolower($package) &&
                      $depchannel == $channel) {
                    return true;
                }  
            }
        }
        if (isset($deps['group'])) {
            if (!isset($deps['group'][0])) {
                $dep['group'] = array($deps['group']);
            }
            foreach ($deps['group'] as $group) {
                if (isset($group['package'])) {
                    if (!is_array($group['package'])) {
                        $group['package'] = array($group['package']);
                    }
                    foreach ($group['package'] as $dep) {
                        $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                        if (strtolower($dep['name']) == strtolower($package) &&
                              $depchannel == $channel) {
                            return true;
                        }  
                    }
                }
                if (isset($group['subpackage'])) {
                    if (!is_array($group['subpackage'])) {
                        $group['subpackage'] = array($group['subpackage']);
                    }
                    foreach ($group['subpackage'] as $dep) {
                        $depchannel = isset($dep['channel']) ? $dep['channel'] : '__uri';
                        if (strtolower($dep['name']) == strtolower($package) &&
                              $depchannel == $channel) {
                            return true;
                        }  
                    }
                }
            }
        }
        return false;
    }

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
     * @todo handle <exclude>
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
            foreach ($this->_packageInfo['dependencies']['required']
                  as $dtype => $deps) {
                if (!isset($deps[0])) {
                    $deps = array($deps);
                }
                foreach ($deps as $dep) {
                    if (!isset($map[$dtype])) {
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
                        $s['optional'] = 'no';
                        $s1['optional'] = 'no';
                        $ret[] = $s1;
                    } elseif (isset($dep['min'])) {
                        $s['rel'] = 'ge';
                        $s['version'] = $dep['min'];
                        $s['optional'] = 'no';
                        if ($dtype != 'php') {
                            $s['name'] = $dep['name'];
                        }
                    } elseif (isset($dep['max'])) {
                        $s['rel'] = 'le';
                        $s['version'] = $dep['min'];
                        $s['optional'] = 'no';
                        if ($dtype != 'php') {
                            $s['name'] = $dep['name'];
                        }
                    }
                    $ret[] = $s;
                }
            }
            if (isset($this->_packageInfo['dependencies']['optional'])) {
                foreach ($this->_packageInfo['dependencies']['optional']
                      as $dtype => $deps) {
                    if (!isset($deps[0])) {
                        $deps = array($deps);
                    }
                    foreach ($deps as $dep) {
                        if (!isset($map[$dtype])) {
                            continue;
                        }
                        $s = array('type' => $map[$dtype]);
                        if (!isset($dep['min']) &&
                              !isset($dep['max'])) {
                            $s['rel'] = 'has';
                        } elseif (isset($dep['min']) &&
                              isset($dep['max'])) {
                            $s['rel'] = 'ge';
                            $s1 = $s;
                            $s['version'] = $dep['min'];
                            $s1['version'] = $dep['max'];
                            if ($dtype != 'php') {
                                $s['name'] = $dep['name'];
                                $s1['name'] = $dep['name'];
                            }
                            $s['optional'] = 'yes';
                            $s1['optional'] = 'yes';
                            $ret[] = $s1;
                        } elseif (isset($dep['min'])) {
                            $s['rel'] = 'ge';
                            $s['version'] = $dep['min'];
                            $s['optional'] = 'yes';
                            if ($dtype != 'php') {
                                $s['name'] = $dep['name'];
                            }
                        } elseif (isset($dep['max'])) {
                            $s['rel'] = 'le';
                            $s['version'] = $dep['min'];
                            $s['optional'] = 'yes';
                            if ($dtype != 'php') {
                                $s['name'] = $dep['name'];
                            }
                        }
                        $ret[] = $s;
                    }
                }
            }
            if (isset($this->_packageInfo['dependencies']['group'])) {
                foreach ($this->_packageInfo['dependencies']['group']
                      as $dtype => $deps) {
                    if (!isset($deps[0])) {
                        $deps = array($deps);
                    }
                    foreach ($deps as $dep) {
                        if (!isset($map[$dtype])) {
                            continue;
                        }
                        $s = array('type' => $map[$dtype],
                            'channel' => $t = $dep['channel']);
                        if (!isset($dep['min']) &&
                              !isset($dep['max'])) {
                            $s['rel'] = 'has';
                        } elseif (isset($dep['min']) &&
                              isset($dep['max'])) {
                            $s['rel'] = 'ge';
                            $s1 = $s;
                            $s['version'] = $dep['min'];
                            $s1['version'] = $dep['max'];
                            if ($dtype != 'php') {
                                $s['name'] = $dep['name'];
                                $s1['name'] = $dep['name'];
                            }
                            $s['optional'] = 'yes';
                            $s1['optional'] = 'yes';
                            $ret[] = $s1;
                        } elseif (isset($dep['min'])) {
                            $s['rel'] = 'ge';
                            $s['version'] = $dep['min'];
                            $s['optional'] = 'yes';
                            if ($dtype != 'php') {
                                $s['name'] = $dep['name'];
                            }
                        } elseif (isset($dep['max'])) {
                            $s['rel'] = 'le';
                            $s['version'] = $dep['min'];
                            $s['optional'] = 'yes';
                            if ($dtype != 'php') {
                                $s['name'] = $dep['name'];
                            }
                        }
                        $ret[] = $s;
                    }
                }
            }
            return $ret;
        }
        return false;
    }

    /**
     * Reset dependencies prior to adding new ones
     */
    function clearDeps()
    {
        $this->_packageInfo['dependencies'] = array();
    }

    /**
     * @param string $min
     * @param string $max
     * @param string $exclude... optional excluded versions
     */
    function addPhpDep($min, $max)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies']['required'])) {
            $this->_insertBefore($this->_packageInfo['dependencies'],
                array('optional', 'group'), array(), 'required');
        }
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        if (count($args)) {
            $exclude = $args;
            if (count($exclude) == 1) {
                $exclude = $exclude[0];
            }
        }
        $dep =
            array(
                'min' => $min,
                'max' => $max
            );
        if (isset($exclude)) {
            $dep['exclude'] = $exclude;
        }
        if (!isset($this->_packageInfo['dependencies']['required']['php'])) {
            $this->_insertBefore($this->_packageInfo['dependencies']['required'],
                array('pearinstaller', 'package', 'subpackage',
                'extension', 'os', 'arch'), $dep, 'php');
            $this->_packageInfo['dependencies']['required']['php'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['required']['php'][] = $dep;
        }
    }

    /**
     * @param string $min
     * @param string $max
     * @param string $recommended
     * @param string $exclude... optional excluded versions
     */
    function addPearinstallerDep($min, $max = false, $recommended = false)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies']['required'])) {
            $this->_insertBefore($this->_packageInfo['dependencies'],
                array('optional', 'group'), array(), 'required');
        }
        $args = func_get_args();
        if (count($args) > 3) {
            $exclude = array_slice($args, 3);
            if (count($exclude) == 1) {
                $exclude = $exclude[0];
            }
        }
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
        if (isset($exclude)) {
            $dep['exclude'] = $exclude;
        }
        if (!isset($this->_packageInfo['dependencies']['required']['pearinstaller'])) {
            $this->_packageInfo['dependencies']['required']['pearinstaller'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['required']['pearinstaller'][] = $dep;
        }
    }

    /**
     * Mark a package as conflicting with this package
     * @param string package name
     * @param string package channel
     */
    function addConflictingPackageDepWithChannel($name, $channel)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies']['required'])) {
            $this->_insertBefore($this->_packageInfo['dependencies'],
                array('optional', 'group'), array(), 'required');
        }
        $dep =
            array(
                'name' => $name,
                'channel' => $channel,
                'conflicts' => 'yes',
            );
        if (!isset($this->_packageInfo['dependencies']['required']['package'])) {
            $this->_packageInfo['dependencies']['required']['package'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['required']['package'][] = $dep;
        }
    }

    /**
     * Mark a package as conflicting with this package
     * @param string package name
     * @param string package channel
     */
    function addConflictingPackageDepWithUri($name, $uri)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies']['required'])) {
            $this->_insertBefore($this->_packageInfo['dependencies'],
                array('optional', 'group'), array(), 'required');
        }
        $dep =
            array(
                'name' => $name,
                'uri' => $uri,
                'conflicts' => 'yes',
            );
        if (!isset($this->_packageInfo['dependencies']['required']['package'])) {
            $this->_packageInfo['dependencies']['required']['package'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['required']['package'][] = $dep;
        }
    }

    /**
     * @param optional|required|string optional, required, or group name
     * @param string package name
     * @param string package channel
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param string $exclude... optional excluded versions
     */
    function addPackageDepWithChannel($type, $name, $channel, $min = false, $max = false,
                                      $recommended = false, $group = null)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies'][$type])) {
            $save = $this->_packageInfo['dependencies'];
            $new = array();
            foreach (array('required', 'optional', 'group') as $possible) {
                if ($type == $possible) {
                    $new[$type] = array();
                } elseif (isset($save[$type])) {
                    $new[$type] = $save[$type];
                }
            }
            $this->_packageInfo['dependencies'] = $new;
        }
        $args = func_get_args();
        if (count($args) > 6) {
            $exclude = array_slice($args, 6);
            if (count($exclude) == 1) {
                $exclude = $exclude[0];
            }
        }
        $dep =
            array(
                'name' => $name,
                'channel' => $channel,
            );
        if ($min) {
            $dep['min'] = $min;
        }
        if ($max) {
            $dep['max'] = $max;
        }
        if ($recommended) {
            $dep['recommended'] = $recommended;
        }
        if (isset($exclude)) {
            $dep['exclude'] = $exclude;
        }
        if (!isset($this->_packageInfo['dependencies'][$type]['package'])) {
            $this->_packageInfo['dependencies'][$type]['package'] = $dep;
        } else {
            if (!isset($this->_packageInfo['dependencies'][$type]['package'][0])) {
                $this->_packageInfo['dependencies'][$type]['package'] =
                    array($this->_packageInfo['dependencies'][$type]['package']);
            }
            $this->_packageInfo['dependencies'][$type]['package'][] = $dep;
        }
    }

    function addPackageDepWithUri($type, $name, $uri, $group = null)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies'][$type])) {
            $save = $this->_packageInfo['dependencies'];
            $new = array();
            foreach (array('required', 'optional', 'group') as $possible) {
                if ($type == $possible) {
                    $new[$type] = array();
                } elseif (isset($save[$type])) {
                    $new[$type] = $save[$type];
                }
            }
            $this->_packageInfo['dependencies'] = $new;
        }
        $dep = array('name' => $name, 'uri' => $uri);
        if (!isset($this->_packageInfo['dependencies'][$type]['package'])) {
            $this->_packageInfo['dependencies'][$type]['package'] = $dep;
        } else {
            $this->_packageInfo['dependencies'][$type]['package'][] = $dep;
        }
    }

    /**
     * @param optional|required|string optional, required, or group name
     * @param string package name
     * @param string package channel
     * @param string minimum version
     * @param string maximum version
     * @param string recommended version
     * @param string $exclude... optional excluded versions
     */
    function addSubpackageDepWithChannel($type, $name, $channel, $min = false, $max = false,
                                      $recommended = false, $group = null)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies'][$type])) {
            $save = $this->_packageInfo['dependencies'];
            $new = array();
            foreach (array('required', 'optional', 'group') as $possible) {
                if ($type == $possible) {
                    $new[$type] = array();
                } elseif (isset($save[$type])) {
                    $new[$type] = $save[$type];
                }
            }
            $this->_packageInfo['dependencies'] = $new;
        }
        $args = func_get_args();
        if (count($args) > 6) {
            $exclude = array_slice($args, 6);
            if (count($exclude) == 1) {
                $exclude = $exclude[0];
            }
        }
        $dep =
            array(
                'name' => $name,
                'channel' => $channel,
            );
        if ($min) {
            $dep['min'] = $min;
        }
        if ($max) {
            $dep['max'] = $max;
        }
        if ($recommended) {
            $dep['recommended'] = $recommended;
        }
        if (isset($exclude)) {
            $dep['exclude'] = $exclude;
        }
        if (!isset($this->_packageInfo['dependencies'][$type]['subpackage'])) {
            $this->_packageInfo['dependencies'][$type]['subpackage'] = $dep;
        } else {
            $this->_packageInfo['dependencies'][$type]['subpackage'][] = $dep;
        }
    }

    function addSubpackageDepWithUri($type, $name, $uri, $group = null)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies'][$type])) {
            $save = $this->_packageInfo['dependencies'];
            $new = array();
            foreach (array('required', 'optional', 'group') as $possible) {
                if ($type == $possible) {
                    $new[$type] = array();
                } elseif (isset($save[$type])) {
                    $new[$type] = $save[$type];
                }
            }
            $this->_packageInfo['dependencies'] = $new;
        }
        $dep = array('name' => $name, 'uri' => $uri);
        if (!isset($this->_packageInfo['dependencies'][$type]['subpackage'])) {
            $this->_packageInfo['dependencies'][$type]['subpackage'] = $dep;
        } else {
            $this->_packageInfo['dependencies'][$type]['subpackage'][] = $dep;
        }
    }

    function addExtensionDep($type, $name, $version, $rel, $optional = 'no', $group = null)
    {
        $this->_isValid = 0;
        if (!isset($this->_packageInfo['dependencies'])) {
            // ensure that the dependencies tag is set up
            $this->_insertBefore($this->_packageInfo,
                array('phprelease', 'extsrcrelease',
                'extbinrelease', 'bundle', 'changelog'), array(), 'dependencies');
        } elseif (!isset($this->_packageInfo['dependencies'][$type])) {
            $save = $this->_packageInfo['dependencies'];
            $new = array();
            foreach (array('required', 'optional', 'group') as $possible) {
                if ($type == $possible) {
                    $new[$type] = array();
                } elseif (isset($save[$type])) {
                    $new[$type] = $save[$type];
                }
            }
            $this->_packageInfo['dependencies'] = $new;
        }
    }

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

    function setPackageType($type)
    {
        $this->_isValid = 0;
        $type .= 'release';
        if (!isset($this->_packageInfo[$type])) {
            // ensure that the compatible tag is set up
            $this->_insertBefore($this->_packageInfo, array('changelog'), array(), $type);
        }
        $this->_packageInfo[$type] = array();
    }

    function hasDeps()
    {
        return isset($this->_packageInfo['dependencies']);
    }

    function getPackagexmlVersion()
    {
        return '2.0';
    }

    function getSourcePackage()
    {
        if (isset($this->_packageInfo['extbinrelease'])) {
            return array('channel' => $this->_packageInfo['extbinrelease']['srcchannel'],
                         'package' => $this->_packageInfo['extbinrelease']['srcpackage']);
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
            include_once 'Archive/Tar.php';
            $tar = &new Archive_Tar($this->_archiveFile);
            $tar->pushErrorHandling(PEAR_ERROR_RETURN);
            $file = $tar->extractInString($file);
            $tar->popErrorHandling();
            if (PEAR::isError($file)) {
                return PEAR::raiseError("Cannot locate file '$file' in archive");
            }
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
    function _insertBefore(&$array, $keys, $contents, $newkey)
    {
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                return $array = $this->_ksplice($array, $key, $contents, $newkey);
            }
        }
        $array[$newkey] = $contents;
    }

    function validate($state = PEAR_VALIDATE_NORMAL)
    {
        if (!isset($this->_packageInfo) || !is_array($this->_packageInfo)) {
            return false;
        }
        if (!isset($this->_v2Validator)) {
            include_once 'PEAR/PackageFile/v2/Validator.php';
            $this->_v2Validator = new PEAR_PackageFile_v2_Validator;
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