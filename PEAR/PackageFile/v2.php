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


    function packageInfo($field)
    {
        $map = array(
            'release_notes' => 'notes',
            'release-license' => 'license',
            'release_date' => 'date');
        $field = isset($map[$field]) ? $map[$field] : $field;
        if (!in_array($field, 
            array('name', 'summary', 'channel', 'notes', 'extends', 'description',
                    'release_notes', 'license', 'license-uri',
                    'version', 'api-version', 'state', 'api-state',
                    'date', 'time'))) {
            return false;
        }
        if (method_exists($this, "get$field")) {
            $test = $this->{"get$field"}();
            if (is_string($test)) {
                return $test;
            }
        }
        if ($field == 'license-uri') {
            if (isset($this->_packageInfo['license']['attribs']['uri'])) {
                return $this->_packageInfo['license']['attribs']['uri'];
            }
        }
        if ($field == 'api-state') {
            return $this->getState('api');
        }
        if ($field == 'api-version') {
            return $this->getVersion('api');
        }
        return false;
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
            $depchecker = &new PEAR_Dependency2($this->_registry, array(),
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
     * @todo construct _filelist and use it
     */
    function setInstalledAs($file, $path)
    {
        if ($path) {
            return $this->_packageInfo['filelist'][$file]['installed_as'] = $path;
        }
        unset($this->_packageInfo['filelist'][$file]['installed_as']);
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

    function setFileAttribute($file, $attr, $value, $index)
    {
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

    function getChannel()
    {
        if (isset($this->_packageInfo['channel'])) {
            return $this->_packageInfo['channel'];
        }
        return false;
    }

    function setChannel($channel)
    {
        $this->_packageInfo['channel'] = $channel;
        $this->_isValid = 0;
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
        $this->_packageInfo['package'] = $package;
        $this->_isValid = 0;
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
        $this->_packageInfo['extends'] = $extends;
        $this->_isValid = 0;
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

    function setVersion($version, $key = 'release')
    {
        if (!in_array($key, array('release', 'api'))) {
            return false;
        }
        $this->_packageInfo['version'][$key] = $version;
        $this->_isValid = 0;
        return true;
    }

    /**
     * Adds a new maintainer - no checking of duplicates is performed, use
     * updatemaintainer for that purpose.
     */
    function addMaintainer($role, $handle, $name, $email)
    {
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
            $this->_packageInfo[$role] =
                array(
                    'name' => $name,
                    'user' => $handle,
                    'email' => $email,
                    'active' => $active,
                );
        }
    }

    function updateMaintainer($role, $handle, $name, $email)
    {
        $found = false;
        if (!isset($this->_packageInfo[$role]) ||
              !is_array($this->_packageInfo[$role])) {
            return $this->addMaintainer($role, $handle, $name, $email);
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
            $this->_packageInfo[$role] = array_values($this->_packageInfo[$role]);
        }
        $this->addMaintainer($role, $handle, $name, $email);
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
                if ($maintainer['handle'] == $handle) {
                    $found = $i;
                    break;
                }
            }
            if ($found !== false) {
                unset($this->_packageInfo[$role][$found]);
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

    function getDate()
    {
        if (isset($this->_packageInfo['date'])) {
            return $this->_packageInfo['date'];
        }
        return false;
    }

    function setDate($date)
    {
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
        $this->_packageInfo['time'] = $time;
        $this->_isValid = 0;
    }

    function getLicense($raw = false)
    {
        if (isset($this->_packageInfo['license'])) {
            if ($raw) {
                return $this->_packageInfo['license'];
            }
            return $this->_packageInfo['license']['_content'];
        }
        return false;
    }

    function setLicense($license, $uri = false, $filesource = false)
    {
        if ($uri || $filesource) {
            $attribs = arary();
            if ($uri) {
                $attribs['uri'] = $uri;
            }
            $uri = true; // for test below
            if ($filesource) {
                $attribs['filesource'] = $filesource;
            }
        }
        $license = $uri ? array('attribs' => $attribs, '_content' => $license) : $license;
        $this->_packageInfo['time'] = $time;
        $this->_isValid = 0;
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
        $this->_packageInfo['summary'] = $summary;
        $this->_isValid = 0;
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
        $this->_packageInfo['description'] = $desc;
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
        $this->_packageInfo['notes'] = $notes;
        $this->_isValid = 0;
    }

    function getCompatible()
    {
        if (isset($this->_packageInfo['compatible'])) {
            $compat = $this->_packageInfo['compatible'];
            if (isset($compat['package'])) {
                return array($compat);
            }
            return $compat;
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
        if (!isset($this->_packageInfo['dependencies']['php'])) {
            $this->_packageInfo['dependencies']['php'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['php'][] = $dep;
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
        if (!isset($this->_packageInfo['dependencies']['pearinstaller'])) {
            $this->_packageInfo['dependencies']['pearinstaller'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['pearinstaller'][] = $dep;
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
                                      $recommended = false)
    {
        if (count($args) > 6) {
            $exclude = array_slice($args, 6);
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
        if (!isset($this->_packageInfo['dependencies']['package'])) {
            $this->_packageInfo['dependencies']['package'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['package'][] = $dep;
        }
    }

    function addPackageDepWithUri($type, $name, $uri)
    {
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
                                      $recommended = false)
    {
        if (count($args) > 6) {
            $exclude = array_slice($args, 6);
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
        if (!isset($this->_packageInfo['dependencies']['subpackage'])) {
            $this->_packageInfo['dependencies']['subpackage'] = $dep;
        } else {
            $this->_packageInfo['dependencies']['subpackage'][] = $dep;
        }
    }

    function addSubpackageDepWithUri($type, $name, $uri)
    {
        $dep = array('name' => $name, 'uri' => $uri);
        if (!isset($this->_packageInfo['dependencies'][$type]['subpackage'])) {
            $this->_packageInfo['dependencies'][$type]['subpackage'] = $dep;
        } else {
            $this->_packageInfo['dependencies'][$type]['subpackage'][] = $dep;
        }
    }

    function addExtensionDep($name, $version, $rel, $optional = 'no')
    {
    }

    function getPackageType()
    {
        if (isset($this->_packageInfo['php'])) {
            return 'php';
        }
        if (isset($this->_packageInfo['extsrc'])) {
            return 'extsrc';
        }
        if (isset($this->_packageInfo['extbin'])) {
            return 'extbin';
        }
        return false;
    }

    function hasDeps()
    {
        return isset($this->_packageInfo['dependencies']);
    }

    function getPackagexmlVersion()
    {
        return '2.0';
    }

    function getReleaseType()
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

    function validate($state = PEAR_VALIDATE_NORMAL)
    {
        $this->_stack->getErrors(true);
        if (($this->_isValid & $state) == $state) {
            return true;
        }
        if (!isset($this->_packageInfo) || !is_array($this->_packageInfo)) {
            return false;
        }
        if (!isset($this->_packageInfo['attribs']['version']) ||
              $this->_packageInfo['attribs']['version'] != '2.0') {
            $this->_noPackageVersion();
        }
        $structure =
        array(
            'name',
            'channel|uri',
            '*extends', // can't be multiple, but this works fine
            'summary',
            'description',
            '+lead', // these all need content checks
            '*developer',
            '*contributor',
            '*helper',
            'date',
            '*time',
            'version',
            'stability',
            'license->?uri->?filesource',
            'notes',
            'contents', //special validation needed
            '*compatible',
            'dependencies', //special validation needed
            '+phprelease|extsrcrelease|+extbinrelease|bundle' //special validation needed
        );
        $test = $this->_packageInfo;
        unset($test['attribs']);
        if (!$this->_stupidSchemaValidate($structure,
                                          $test, '<package>')) {
            return false;
        }
        if (empty($this->_packageInfo['name'])) {
            $this->_tagCannotBeEmpty('name');
        }
        if (empty($this->_packageInfo['channel'])) {
            $this->_tagCannotBeEmpty('channel');
        }
        if (is_array($this->_packageInfo['license']) &&
              !isset($this->_packageInfo['license']['_content'])) {
            $this->_tagCannotBeEmpty('license');
        }
        if (isset($this->_packageInfo['dependencies'])) {
            $this->_validateDependencies();
        }
        if (isset($this->_packageInfo['compatible'])) {
            $this->_validateCompatible();
        }
        if (!isset($this->_packageInfo['contents']['dir'])) {
            $this->_filelistMustContainDir('contents');
            return false;
        }
        if (isset($this->_packageInfo['contents']['file'])) {
            $this->_filelistCannotContainFile('contents');
            return false;
        }
        $this->_validateMaintainers();
        $this->_validateStabilityVersion();
        $this->_validateFilelist();
        $this->_validateRelease();
        if (!$this->_stack->hasErrors()) {
            $chan = $this->_registry->getChannel($this->getChannel());
            if (!$chan) {
                $this->_unknownChannel($this->getChannel());
            } else {
                $validator = $chan->getValidationObject();
                $validator->setPackageFile($this);
                $validator->validate($state);
                $failures = $validator->getFailures();
                foreach ($failures['errors'] as $error) {
                    $this->_stack->push(__FUNCTION__, 'error', $error,
                        'Channel validator error: field "%field%" - %reason%');
                }
                foreach ($failures['warnings'] as $warning) {
                    $this->_stack->push(__FUNCTION__, 'warning', $warning,
                        'Channel validator warning: field "%field%" - %reason%');
                }
            }
        }
        $this->_isValid = !$this->_stack->hasErrors('error');
        if ($this->_isValid && $state == PEAR_VALIDATE_PACKAGING) {
            if (!$this->_analyzePhpFiles()) {
                $this->_isValid = 0;
            }
        }
        if ($this->_isValid) {
            return $this->_isValid = $state;
        }
        return $this->_isValid = 0;
    }

    function _stupidSchemaValidate($structure, $xml, $root)
    {
        $keys = array_keys($xml);
        reset($keys);
        $key = current($keys);
        foreach ($structure as $struc) {
            $test = $this->_processStructure($struc);
            if (!$key && @$struc['multiple'] == '*') {
                continue;
            }
            $tag = $xml[$key];
            if (isset($test['choices'])) {
                foreach ($test['choices'] as $choice) {
                    if ($key == $choice['tag']) {
                        if ($this->_processAttribs($choice, $tag, $root)) {
                            $key = next($keys);
                            continue 2;
                        }
                        return false;
                    }
                }
                $this->_invalidTagOrder($test['choices'], $key, $root);
                return false;
            } else {
                if ($key != $test['tag']) {
                    if (isset($test['multiple']) && $test['multiple'] != '*') {
                        $this->_invalidTagOrder($test['tag'], $key, $root);
                        return false;
                    }
                    if (!isset($test['multiple'])) {
                        $this->_invalidTagOrder($test['tag'], $key, $root);
                        return false;
                    }
                    continue;
                }
                if ($this->_processAttribs($test, $tag, $root)) {
                    $key = next($keys);
                    continue;
                }
                return false;
            }
        }
        return true;
    }

    function _processAttribs($choice, $tag, $context)
    {
        if (isset($choice['attribs'])) {
            if (isset($choice['multiple'])) {
                $tags = $tag;
                foreach ($tags as $i => $tag) {
                    if (!is_int($i)) {
                        unset($choice['multiple']);
                        return $this->_processAttribs($choice, $tags, $context);
                    }
                    if (!isset($tag['attribs'])) {
                        return $this->_tagHasNoAttribs($choice['tag'],
                            $context);
                    }
                    foreach ($choice['attribs'] as $attrib) {
                        if ($attrib{0} != '?') {
                            if (!isset($tag['attribs'][$attrib])) {
                                return $this->_tagMissingAttribute($choice['tag'],
                                    $attrib, $context);
                            }
                        }
                    }
                }
            } else {
                if (!isset($tag['attribs'])) {
                    return $this->_tagHasNoAttribs($choice['tag'],
                        $context);
                }
                foreach ($choice['attribs'] as $attrib) {
                    if ($attrib{0} != '?') {
                        if (!isset($tag['attribs'][$attrib])) {
                            return $this->_tagMissingAttribute($choice['tag'], $attrib,
                                $context);
                        }
                    }
                }
            }
        }
        return true;
    }

    function _processStructure($key)
    {
        $ret = array();
        if (count($pieces = explode('|', $key)) > 1) {
            foreach ($pieces as $piece) {
                $ret['choices'][] = $this->_processStructure($piece);
            }
            return $ret;
        }
        $multi = $key{0};
        if ($multi == '+' || $multi == '*') {
            $ret['multiple'] = $key{0};
            $key = substr($key, 1);
        }
        if (count($attrs = explode('->', $key)) > 1) {
            $ret['tag'] = array_shift($attrs);
            $ret['attribs'] = $attrs;
        } else {
            $ret['tag'] = $key;
        }
        return $ret;
    }

    function _validateStabilityVersion()
    {
        $structure = array('release', 'api');
        $a = $this->_stupidSchemaValidate($structure, $this->_packageInfo['version'], '<version>');
        $a &= $this->_stupidSchemaValidate($structure, $this->_packageInfo['stability'], '<stability>');
        if ($a) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $this->_packageInfo['version']['release'])) {
                $this->_invalidVersion('release', $this->_packageInfo['version']['release']);
            }
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $this->_packageInfo['version']['api'])) {
                $this->_invalidVersion('api', $this->_packageInfo['version']['api']);
            }
            if (!in_array($this->_packageInfo['stability']['release'],
                  array('snapshot', 'devel', 'alpha', 'beta', 'stable'))) {
                $this->_invalidState('release', $this->_packageinfo['stability']['release']);
            }
            if (!in_array($this->_packageInfo['stability']['api'],
                  array('devel', 'alpha', 'beta', 'stable'))) {
                $this->_invalidState('api', $this->_packageinfo['stability']['api']);
            }
        }
    }

    function _validateMaintainers()
    {
        $structure =
            array(
                'name',
                'user',
                'email',
                'active',
            );
        foreach (array('lead', 'developer', 'contributor', 'helper') as $type) {
            if (!isset($this->_packageInfo[$type])) {
                continue;
            }
            if (isset($this->_packageInfo[$type][0])) {
                foreach ($this->_packageInfo[$type] as $lead) {
                    $this->_stupidSchemaValidate($structure, $lead, '<' . $type . '>');
                }
            } else {
                $this->_stupidSchemaValidate($structure, $this->_packageInfo[$type],
                    '<' . $type . '>');
            }
        }
    }

    function _validatePhpDep($dep)
    {
        $structure = array(
            'min',
            'max',
            '*exclude',
        );
        $this->_stupidSchemaValidate($structure, $dep, '<dependencies><php>');
        if (isset($dep['min'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['min'])) {
                $this->_invalidVersion('<dep><min>', $dep['min']);
            }
        }
        if (isset($dep['max'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['max'])) {
                $this->_invalidVersion('<php><max>', $dep['max']);
            }
        }
    }

    function _validatePearinstallerDep($dep)
    {
        $structure = array(
            'min',
            '*max',
            '*recommended',
            '*exclude',
        );
        $this->_stupidSchemaValidate($structure, $dep, '<dependencies><pearinstaller>');
        if (isset($dep['min'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['min'])) {
                $this->_invalidVersion('<pearinstaller><min>', $dep['min']);
            }
        }
        if (isset($dep['max'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['max'])) {
                $this->_invalidVersion('<pearinstaller><max>', $dep['max']);
            }
        }
        if (isset($dep['recommended'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['recommended'])) {
                $this->_invalidVersion('<pearinstaller><recommended>', $dep['recommended']);
            }
        }
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
            foreach ($dep['exclude'] as $exclude) {
                if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                      $exclude)) {
                    $this->_invalidVersion('<pearinstaller><exclude>', $exclude);
                }
            }
        }
    }

    function _validatePackageDep($dep, $type = '<package>')
    {
        if (isset($dep['uri'])) {
            $structure = array(
                'name',
                'uri',
                '*providesextension',
                '*conflicts',
            );
        } else {
            $structure = array(
                'name',
                'channel',
                '*min',
                '*max',
                '*recommended',
                '*exclude',
                '*providesextension',
                '*conflicts',
            );
        }
        $this->_stupidSchemaValidate($structure, $dep, '<dependencies>' . $type);
        if (isset($dep['min'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['min'])) {
                $this->_invalidVersion($type . '<min>', $dep['min']);
            }
        }
        if (isset($dep['max'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['max'])) {
                $this->_invalidVersion($type . '<max>', $dep['max']);
            }
        }
        if (isset($dep['recommended'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['recommended'])) {
                $this->_invalidVersion($type . '<recommended>', $dep['recommended']);
            }
        }
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
            foreach ($dep['exclude'] as $exclude) {
                if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                      $exclude)) {
                    $this->_invalidVersion($type . '<exclude>', $exclude);
                }
            }
        }
    }

    function _validateSubpackageDep($dep)
    {
        return $this->_validatePackageDep($dep, '<supackage>');
    }

    function _validateExtensionDep($dep)
    {
        $structure = array(
            'name',
            'channel',
            '*min',
            '*max',
            '*recommended',
            '*exclude',
            '*conflicts',
        );
        $this->_stupidSchemaValidate($structure, $dep, '<dependencies><extension>');
        $this->_stupidSchemaValidate($structure, $dep, '<dependencies><package>');
        if (isset($dep['min'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['min'])) {
                $this->_invalidVersion('<extension><min>', $dep['min']);
            }
        }
        if (isset($dep['max'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['max'])) {
                $this->_invalidVersion('<extension><max>', $dep['max']);
            }
        }
        if (isset($dep['recommended'])) {
            if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                  $dep['recommended'])) {
                $this->_invalidVersion('<extension><recommended>', $dep['recommended']);
            }
        }
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                $dep['exclude'] = array($dep['exclude']);
            }
            foreach ($dep['exclude'] as $exclude) {
                if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                      $exclude)) {
                    $this->_invalidVersion('<extension><exclude>', $exclude);
                }
            }
        }
    }

    function _validateOsDep($dep)
    {
        $structure = array(
            'name',
            '*conflicts',
        );
        return $this->_stupidSchemaValidate($structure, $dep, '<dependencies><os>');
    }

    function _validateArchDep($dep)
    {
        $structure = array(
            'pattern',
            '*conflicts',
        );
        return $this->_stupidSchemaValidate($structure, $dep, '<dependencies><arch>');
    }

    function _validateInstallConditions($cond, $release)
    {
        $structure = array(
            '*php',
            '*extension',
            '*os',
            '*arch',
        );
        if (!$this->_stupidSchemaValidate($structure,
              $cond, $release . '<installconditions>')) {
            return false;
        }
        foreach (array('php', 'pearinstaller', 'os', 'arch') as $type) {
            if (isset($cond[$type])) {
                $iter = $cond[$type];
                if (!isset($iter[0])) {
                    $iter = array($iter);
                }
                foreach ($iter as $package) {
                    $this->{"_validate{$type}Dep"}($package);
                }
            }
        }
    }

    function _validateDependencies()
    {
        $structure = array(
            'required',
            '*optional',
            '*group->name->hint'
        );
        if (!$this->_stupidSchemaValidate($structure,
              $this->_packageInfo['dependencies'], '<dependencies>')) {
            return false;
        }
        foreach (array('required', 'optional') as $simpledep) {
            if (isset($this->_packageInfo['dependencies'][$simpledep])) {
                if ($simpledep == 'optional') {
                    $structure = array(
                        '*package',
                        '*subpackage',
                        '*extension',
                    );
                } else {
                    $structure = array(
                        'php',
                        'pearinstaller',
                        '*package',
                        '*subpackage',
                        '*extension',
                        '*os',
                        '*arch',
                    );
                }
                if ($this->_stupidSchemaValidate($structure,
                      $this->_packageInfo['dependencies'][$simpledep], "<$simpledep>")) {
                    foreach (array('package', 'subpackage', 'extension') as $type) {
                        if (isset($this->_packageInfo['dependencies'][$simpledep][$type])) {
                            $iter = $this->_packageInfo['dependencies'][$simpledep][$type];
                            if (!isset($iter[0])) {
                                $iter = array($iter);
                            }
                            foreach ($iter as $package) {
                                if (isset($package['url'])) {
                                    if (isset($package['channel'])) {
                                        $this->_UrlOrChannel($type,
                                            $package['name']);
                                    }
                                } else {
                                    if ($type == 'extension') {
                                        continue;
                                    }
                                    if (!isset($package['channel'])) {
                                        $this->_NoChannel($type, $package['name']);
                                    }
                                }
                                $this->{"_validate{$type}Dep"}($package);
                            }
                        }
                    }
                    if ($simpledep == 'optional') {
                        continue;
                    }
                    foreach (array('php', 'pearinstaller', 'os', 'arch') as $type) {
                        if (isset($this->_packageInfo['dependencies'][$simpledep][$type])) {
                            $iter = $this->_packageInfo['dependencies'][$simpledep][$type];
                            if (!isset($iter[0])) {
                                $iter = array($iter);
                            }
                            foreach ($iter as $package) {
                                $this->{"_validate{$type}Dep"}($package);
                            }
                        }
                    }
                }
            }
        }
        if (isset($this->_packageInfo['dependencies']['group'])) {
            $structure = array(
                '*package',
                '*subpackage',
                '*extension',
            );
            if ($this->_stupidSchemaValidate($structure,
                  $this->_packageInfo['dependencies']['group'], '<group>')) {
                $groups = $this->_packageInfo['dependencies']['group'];
                if (!isset($groups[0])) {
                    $groups = array($groups);
                }
                foreach ($groups as $group) {
                    foreach (array('package', 'subpackage', 'extension') as $type) {
                        if (isset($group[$type])) {
                            $iter = $group[$type];
                            if (!isset($iter[0])) {
                                $iter = array($iter);
                            }
                            foreach ($iter as $package) {
                                if (isset($package['url'])) {
                                    if (isset($package['channel'])) {
                                        $this->_UrlOrChannelGroup($type,
                                            $package['name'],
                                            $group['name']);
                                    }
                                } else {
                                    if (!isset($package['channel'])) {
                                        $this->_NoChannelGroup($type,
                                            $package['name'],
                                            $group['name']);
                                    }
                                }
                                $this->{"_validate{$type}Dep"}($package);
                            }
                        }
                    }
                }
            }
        }
    }

    function _validateCompatible()
    {
        $compat = $this->_packageInfo['compatible'];
        if (isset($compat['name'])) {
            $compat = array($compat);
        }
        $required = array('name', 'channel', 'min', 'max', '*exclude');
        foreach ($compat as $package) {
            $this->_stupidSchemaValidate($required, $package, '<compatible>');
            if (isset($package['min'])) {
                if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                      $package['min'])) {
                    $this->_invalidVersion('<compatible><min>', $package['min']);
                }
            }
            if (isset($package['max'])) {
                if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                      $package['max'])) {
                    $this->_invalidVersion('<compatible><max>', $package['max']);
                }
            }
            if (isset($package['exclude'])) {
                if (!is_array($package['exclude'])) {
                    $package['exclude'] = array($package['exclude']);
                }
                foreach ($package['exclude'] as $exclude) {
                    if (!preg_match('/^\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?$/',
                          $exclude)) {
                        $this->_invalidVersion('<compatible><exclude>', $exclude);
                    }
                }
            }
        }
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

    function _validateFilelist($list = false, $filetag = 'file', $allowignore = false)
    {
        if (!$list) {
            $list = $this->_packageInfo['contents'];
        }
        if (isset($this->_packageInfo['bundle'])) {
            if (!isset($list['bundledpackage'])) {
                return $this->_NoBundledPackages();
            }
            if (!isset($list['bundledpackage'][0])) {
                return $this->_AtLeast2BundledPackages();
            }
            foreach ($list['bundledpackage'] as $package) {
                if (!isset($package['filename'])) {
                    return $this->_noChildTag('<filename>', '<bundledpackage>');
                }
                if (!isset($package['name'])) {
                    return $this->_noChildTag('<name>', '<bundledpackage>');
                }
                if (isset($package['uri'])) {
                    if (isset($package['channel'])) {
                        return $this->_ChannelOrUri($package['name']);
                    }
                } elseif (!isset($package['channel'])) {
                    return $this->_ChannelOrUri($package['name']);
                }
            }
            return;
        }
        if (isset($list[$filetag])) {
            if (isset($list[$filetag]['attribs'])) {
                // single file
                if (!isset($list[$filetag]['attribs'])) {
                    return $this->_tagHasNoAttribs($filetag,
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                if (!isset($list[$filetag]['attribs']['name'])) {
                    return $this->_tagMissingAttribute($filetag, 'name',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                if (!$allowignore && !isset($list[$filetag]['attribs']['role'])) {
                    return $this->_tagMissingAttribute($filetag, 'role',
                        '<dir name="' . $list['attribs']['name'] . '"><file name="' .
                        $list[$filetag]['attribs']['name'] . '">');
                }
                if (!$allowignore && !$this->_validateRole($list[$filetag]['attribs']['role'])) {
                    return $this->_invalidFileRole($list[$filetag]['attribs']['name'],
                        $list['attribs']['name']);
                }
                $file = $list[$filetag];
                unset($list[$filetag]['attribs']);
                if (count($list[$filetag])) { // has tasks
                    foreach ($list[$filetag] as $task => $value) {
                        if ($tagClass = $this->getTask($task)) {
                                if (!isset($value[0])) {
                                    $value = array($value);
                                }
                                foreach ($value as $v) {
                                    $ret = call_user_func(array($tagClass, 'validateXml'),
                                        $this, $v, $this->_config, $file);
                                    if (is_array($ret)) {
                                        return $this->_invalidTask($task, $ret,
                                            $file['attribs']['name']);
                                    }
                                }
                            if (!isset($value[0])) {
                                $value = array($value);
                            }
                            foreach ($value as $v) {
                                $ret = call_user_func(array($tagClass, 'validateXml'),
                                    $this, $v, $this->_config, $file);
                                if (is_array($ret)) {
                                    return $this->_invalidTask($task, $ret,
                                        $list[$filetag]['attribs']['name']);
                                }
                            }
                        } else {
                            $this->_unknownTask($task, $list[$filetag]['attribs']['name']);
                        }
                    }
                }
            } else {
                if (!is_array($list[$filetag])) {
                    return $this->_tagHasNoAttribs($filetag,
                        '<dir name="' . $list['attribs']['name'] . '"><file>' .
                        $list[$filetag] . '</file>');
                }
                foreach ($list[$filetag] as $i => $file) {
                    if (!is_int($i)) {
                        return $this->_tagHasNoAttribs($filetag,
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs'])) {
                        return $this->_tagHasNoAttribs($filetag,
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs']['name'])) {
                        return $this->_tagMissingAttribute($filetag, 'name',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!$allowignore && !$this->_validateRole($file['attribs']['role'])) {
                        return $this->_invalidFileRole($file['attribs']['name'],
                            $list['attribs']['name'], $file['attribs']['role']);
                    }
                    $f = $file;
                    unset($f['attribs']);
                    if (count($f)) { // has tasks
                        foreach ($f as $task => $value) {
                            if ($tagClass = $this->getTask($task)) {
                                if (!is_array($value) || !isset($value[0])) {
                                    $value = array($value);
                                }
                                foreach ($value as $v) {
                                    $ret = call_user_func(array($tagClass, 'validateXml'),
                                        $this, $v, $this->_config, $file);
                                    if (is_array($ret)) {
                                        return $this->_invalidTask($task, $ret,
                                            $file['attribs']['name']);
                                    }
                                }
                            } else {
                                $this->_unknownTask($task, $file['attribs']['name']);
                            }
                        }
                    }
                }
            }
        }
        if (isset($list['ignore'])) {
            if (!$allowignore) {
                $this->_ignoreNotAllowed();
            }
            if (isset($list['ignore']['attribs'])) {
                // single file
                if (!isset($list['ignore']['attribs'])) {
                    return $this->_tagHasNoAttribs('ignore',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                if (!isset($list['ignore']['attribs']['name'])) {
                    return $this->_tagMissingAttribute('ignore', 'name',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
            } else {
                if (!is_array($list['ignore'])) {
                    return $this->_tagHasNoAttribs('ignore',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                foreach ($list['ignore'] as $i => $file) {
                    if (!is_int($i)) {
                        return $this->_tagHasNoAttribs('ignore',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs'])) {
                        return $this->_tagHasNoAttribs('ignore',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs']['name'])) {
                        return $this->_tagMissingAttribute('ignore', 'name',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                }
            }
        }
        if (isset($list['dir'])) {
            if ($this->_processDir($list['dir'])) {
                if (!isset($list['dir']['attribs'])) {
                    foreach ($list['dir'] as $dir) {
                        $this->_validateFilelist($dir, $filetag, $allowignore);
                        return;
                    }
                }
            }
            return $this->_validateFilelist($list['dir'], $filetag, $allowignore);
        }
    }

    function _processDir($dirs)
    {
        if (!isset($dirs['attribs'])) {
            foreach ($dirs as $i => $dir) {
                if (!is_int($i)) {
                    return $this->_tagHasNoAttribs('dir',
                        'unknown');
                }
            }
            return true;
        } else {
            if (!isset($dirs['attribs']['name'])) {
                return $this->_tagMissingAttribute('dir', 'name',
                    'unknown');
            }
        }
        return true;
    }

    function _validateRelease()
    {
        if (isset($this->_packageInfo['phprelease'])) {
            $release = 'phprelease';
        }
        if (isset($this->_packageInfo['extsrcrelease'])) {
            $release = 'extsrcrelease';
        }
        if (isset($this->_packageInfo['extbinrelease'])) {
            $release = 'extbinrelease';
        }
        if (isset($this->_packageInfo['bundle'])) {
            $release = 'bundle';
        }
        if (is_array($this->_packageInfo[$release]) &&
              isset($this->_packageInfo[$release][0])) { 
            foreach ($this->_packageInfo[$release] as $rel) {
                if (isset($rel['installconditions'])) {
                    $this->_validateInstallConditions($rel['installconditions'], "<$rel>");
                } elseif ($release == 'extbinrelease') {
                    $this->_invalidTagOrder(array('installconditions'), 'filelist',
                        '<extbinrelease>');
                }
                if (isset($rel['filelist'])) {
                    $this->_validateFilelist($rel['filelist'], 'install', true);
                }
            }
        } else {
            if (isset($this->_packageInfo[$release]['filelist'])) {
                $this->_validateFilelist($this->_packageInfo[$release]['filelist'], 'install', true);
            }
        }
    }

    /**
     * This is here to allow role extension through plugins
     * @param string
     */
    function _validateRole($role)
    {
        return in_array($role, PEAR_Installer_Role::getValidRoles($this->getReleaseType()));
    }

    function _detectFilelist()
    {
        $this->_flatFilelist = isset($this->_filelist);
    }

    function _invalidTagOrder($oktags, $actual, $root)
    {
        $this->_stack->push(__FUNCTION__, 'error',
            array('oktags' => $oktags, 'actual' => $actual, 'root' => $root),
            'Invalid tag order in %root%, found <%actual%> expected one of "%oktags%"');
    }

    function _ignoreNotAllowed()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            '<ignore> is not allowed inside global <contents>, only inside ' .
            '<phprelease>/<extsrcrelease>/<extbinrelease>/<bundle>');
    }

    function _tagMissingAttribute($tag, $attr, $context)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $tag,
            'attribute' => $attr, 'context' => $context),
            'tag <%tag%> in context "%context%" has no attribute "%attr%"');
    }

    function _tagHasNoAttribs($tag, $context)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $tag,
            'context' => $context),
            'tag <%tag%> has no attributes in context "%context%"');
    }

    function _invalidInternalStructure()
    {
        $this->_stack->push(__FUNCTION__, 'exception', array(),
            'internal array was not generated by compatible parser, or extreme parser error, cannot continue');
    }

    function _invalidFileRole($file, $dir, $role)
    {
        $this->_stack->push(__FUNCTION__, 'error', array(
            'file' => $file, 'dir' => $dir, 'role' => $role,
            'roles' => PEAR_Common::getFileRoles()),
            'File "%file%" in directory "%dir%" has invalid role "%role%", should be one of %roles%');
    }

    function _filelistCannotContainFile($filelist)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $filelist),
            '<%filelist%> can only contain <dir>, contains <file>.  Use ' .
            '<dir name="/"> as the first dir element');
    }

    function _filelistMustContainDir($filelist)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $filelist),
            '<%filelist%> must contain <dir>.  Use <dir name="/"> as the ' .
            'first dir element');
    }

    function _tagCannotBeEmpty($tag)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $tag),
            '<%tag%> cannot be empty (<%tag%/>)');
    }

    function _UrlOrChannel($type, $name)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('type' => $type,
            'name' => $name),
            'Required dependency <%type%> "%name%" can have either url OR ' .
            'channel attributes, and not both');
    }

    function _NoChannel($type, $name)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('type' => $type,
            'name' => $name),
            'Required dependency <%type%> "%name%" must have either url OR ' .
            'channel attributes');
    }

    function _UrlOrChannelGroup($type, $name, $group)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('type' => $type,
            'name' => $name, 'group' => $group),
            'Group "%group%" dependency <%type%> "%name%" can have either url OR ' .
            'channel attributes, and not both');
    }

    function _NoChannelGroup($type, $name, $group)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('type' => $type,
            'name' => $name, 'group' => $group),
            'Group "%group%" dependency <%type%> "%name%" must have either url OR ' .
            'channel attributes');
    }

    function _unknownChannel($channel)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('channel' => $channel),
            'Unknown channel "%channel%"');
    }

    function _noPackageVersion()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            'package.xml <package> tag has no version attribute, or version is not 2.0');
    }

    function _NoBundledPackages()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            'No <bundledpackage> tag was found in <contents>, required for bundle packages');
    }

    function _AtLeast2BundledPackages()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            'At least 2 packages must be bundled in a bundle package');
    }

    function _ChannelOrUri($name)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('name' => $name),
            'Bundled package "%name%" can have either a uri or a channel, not both');
    }

    function _noChildTag($child, $tag)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('child' => $child, 'tag' => $tag),
            'Tag <%tag%> is missing child tag <%child%>');
    }

    function _invalidVersion($type, $value)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('type' => $type, 'value' => $value),
            'Version type <%type%> is not a valid version (%value%)');
    }

    function _invalidState($type, $value)
    {
        $states = array('stable', 'beta', 'alpha', 'devel');
        if ($type != 'api') {
            $states[] = 'snapshot';
        }
        $this->_stack->push(__FUNCTION__, 'error', array('type' => $type, 'value' => $value,
            'types' => $states),
            'Stability type <%type%> is not a valid stability (%value%), must be one of ' .
            '%types%');
    }

    function _invalidTask($task, $ret, $file)
    {
        switch ($ret[0]) {
            case PEAR_TASK_ERROR_MISSING_ATTRIB :
                $info = array('attrib' => $ret[1], 'task' => $task);
                $msg = 'task <%task%> is missing attribute "%attrib%" in file %file%';
            break;
            case PEAR_TASK_ERROR_NOATTRIBS :
                $info = array('task' => $task);
                $msg = 'task <%task%> has no attributes in file %file%';
            break;
            case PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE :
                $info = array('attrib' => $ret[1], 'values' => $ret[3],
                    'was' => $ret[2], 'task' => $task);
                $msg = 'task <%task%> attribute "%attrib%" has the wrong value "%was%" '.
                    'in file %file%, expecting one of "%values%"';
            break;
            case PEAR_TASK_ERROR_INVALID :
                $info = array('reason' => $ret[1], 'task' => $task);
                $msg = 'task <%task%> is invalid because of "%reason%"';
            break;
        }
        $this->_stack->push(__FUNCTION__, 'error', $info, $msg);
    }

    function _unknownTask($task, $file)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('task' => $task),
            'Unknown task "%task%" passed in file "%file%"');
    }

    function _analyzePhpFiles()
    {
        if (!$this->_isValid) {
            return false;
        }
        if (!isset($this->_packageFile)) {
            return false;
        }
        $dir_prefix = dirname($this->_packageFile);
        $log = isset($this->_logger) ? array(&$this->_logger, 'log') :
            array('PEAR_Common', 'log');
        $info = $this->getContents();
        $info = $info['dir']['file'];
        if (isset($info['attribs'])) {
            $info = array($info);
        }
        $provides = array();
        foreach ($info as $fa) {
            $fa = $fa['attribs'];
            $file = $fa['name'];
            if (in_array($fa['role'], PEAR_Installer_Role::getPhpRoles()) && $dir_prefix) {
                call_user_func_array($log, array(1, "Analyzing $file"));
                if (!file_exists($dir_prefix . DIRECTORY_SEPARATOR . $file)) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_FILE_NOTFOUND,
                        array('file' => $dir_prefix . DIRECTORY_SEPARATOR . $file));
                    continue;
                }
                $srcinfo = $this->analyzeSourceCode($dir_prefix . DIRECTORY_SEPARATOR . $file);
                if ($srcinfo) {
                    $provides = array_merge($provides, $this->_buildProvidesArray($srcinfo));
                }
            }
        }
        $this->_packageName = $pn = $this->getPackage();
        $pnl = strlen($pn);
        foreach ($provides as $key => $what) {
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
                $this->_stack->push(__FUNCTION__, 'warning',
                    array('file' => $file, 'type' => $type, 'name' => $name, 'package' => $pn),
                    'in %file%: %type% "%name%" not prefixed with package name "%package%"');
            } elseif ($type == 'function') {
                if (strstr($name, '::') || !strncasecmp($name, $pn, $pnl)) {
                    continue;
                }
                $this->_stack->push(__FUNCTION__, 'warning',
                    array('file' => $file, 'type' => $type, 'name' => $name, 'package' => $pn),
                    'in %file%: %type% "%name%" not prefixed with package name "%package%"');
            }
        }
        return $this->_isValid;
    }

    // {{{ analyzeSourceCode()
    /**
     * Analyze the source code of the given PHP file
     *
     * @param  string Filename of the PHP file
     * @param  boolean whether to analyze $file as the file contents
     * @return mixed
     */
    function analyzeSourceCode($file, $string = false)
    {
        if (!function_exists("token_get_all")) {
            return false;
        }
        if (!defined('T_DOC_COMMENT')) {
            define('T_DOC_COMMENT', T_COMMENT);
        }
        if (!defined('T_INTERFACE')) {
            define('T_INTERFACE', -1);
        }
        if (!defined('T_IMPLEMENTS')) {
            define('T_IMPLEMENTS', -1);
        }
        if ($string) {
            $contents = $file;
        } else {
            if (!$fp = @fopen($file, "r")) {
                return false;
            }
            $contents = fread($fp, filesize($file));
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
        $current_interface = '';
        $current_class_level = -1;
        $current_function = '';
        $current_function_level = -1;
        $declared_classes = array();
        $declared_interfaces = array();
        $declared_functions = array();
        $declared_methods = array();
        $used_classes = array();
        $used_functions = array();
        $extends = array();
        $implements = array();
        $nodeps = array();
        $inquote = false;
        $interface = false;
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
                case T_WHITESPACE :
                    continue;
                case ';':
                    if ($interface) {
                        $current_function = '';
                        $current_function_level = -1;
                    }
                    break;
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
                case T_INTERFACE:
                    $interface = true;
                case T_CLASS:
                    if (($current_class_level != -1) || ($current_function_level != -1)) {
                        $this->_stack->push(__FUNCTION__, 'error', array('file' => $file),
                        'Parser error: Invalid PHP file %file%');
                        return false;
                    }
                case T_FUNCTION:
                case T_NEW:
                case T_EXTENDS:
                case T_IMPLEMENTS:
                    $look_for = $token;
                    continue 2;
                case T_STRING:
                    if (version_compare(zend_version(), '2.0', '<')) {
                        if (in_array(strtolower($data),
                            array('public', 'private', 'protected', 'abstract',
                                  'interface', 'implements', 'clone', 'throw') 
                                 )) {
                            $this->_stack->push(__FUNCTION__, 'warning', array(),
                                'Error, PHP5 token encountered, analysis should be in PHP5');
                        }
                    }
                    if ($look_for == T_CLASS) {
                        $current_class = $data;
                        $current_class_level = $brace_level;
                        $declared_classes[] = $current_class;
                    } elseif ($look_for == T_INTERFACE) {
                        $current_interface = $data;
                        $current_class_level = $brace_level;
                        $declared_interfaces[] = $current_interface;
                    } elseif ($look_for == T_IMPLEMENTS) {
                        $implements[$current_class] = $data;
                    } elseif ($look_for == T_EXTENDS) {
                        $extends[$current_class] = $data;
                    } elseif ($look_for == T_FUNCTION) {
                        if ($current_class) {
                            $current_function = "$current_class::$data";
                            $declared_methods[$current_class][] = $data;
                        } elseif ($current_interface) {
                            $current_function = "$current_interface::$data";
                            $declared_methods[$current_interface][] = $data;
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
                case T_DOC_COMMENT:
                case T_COMMENT:
                    if (preg_match('!^/\*\*\s!', $data)) {
                        $lastphpdoc = $data;
                        if (preg_match_all('/@nodep\s+(\S+)/', $lastphpdoc, $m)) {
                            $nodeps = array_merge($nodeps, $m[1]);
                        }
                    }
                    continue 2;
                case T_DOUBLE_COLON:
                    if (!($tokens[$i - 1][0] == T_WHITESPACE || $tokens[$i - 1][0] == T_STRING)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_FILE, array('file' => $file));
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
            "declared_interfaces" => $declared_interfaces,
            "declared_methods" => $declared_methods,
            "declared_functions" => $declared_functions,
            "used_classes" => array_diff(array_keys($used_classes), $nodeps),
            "inheritance" => $extends,
            "implements" => $implements,
            );
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
        $providesret = array();
        $file = basename($srcinfo['source_file']);
        $pn = $this->getPackage();
        $pnl = strlen($pn);
        foreach ($srcinfo['declared_classes'] as $class) {
            $key = "class;$class";
            if (isset($providesret[$key])) {
                continue;
            }
            $providesret[$key] =
                array('file'=> $file, 'type' => 'class', 'name' => $class);
            if (isset($srcinfo['inheritance'][$class])) {
                $providesret[$key]['extends'] =
                    $srcinfo['inheritance'][$class];
            }
        }
        foreach ($srcinfo['declared_methods'] as $class => $methods) {
            foreach ($methods as $method) {
                $function = "$class::$method";
                $key = "function;$function";
                if ($method{0} == '_' || !strcasecmp($method, $class) ||
                    isset($providesret[$key])) {
                    continue;
                }
                $providesret[$key] =
                    array('file'=> $file, 'type' => 'function', 'name' => $function);
            }
        }

        foreach ($srcinfo['declared_functions'] as $function) {
            $key = "function;$function";
            if ($function{0} == '_' || isset($providesret[$key])) {
                continue;
            }
            if (!strstr($function, '::') && strncasecmp($function, $pn, $pnl)) {
                $warnings[] = "in1 " . $file . ": function \"$function\" not prefixed with package name \"$pn\"";
            }
            $providesret[$key] =
                array('file'=> $file, 'type' => 'function', 'name' => $function);
        }
        return $providesret;
    }

    // }}}
}
?>