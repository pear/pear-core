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
    
    var $_prettyFilelists = false;

    /**
     * @var PEAR_ErrorStack
     */
    var $_stack;
    function PEAR_PackageFile_v2()
    {
        $this->_stack = new PEAR_ErrorStack('PEAR_PackageFile_v2', false, null);
    }

    function setRegistry(&$registry)
    {
        $this->_registry = &$registry;
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
        if (method_exists($this, $field)) {
            $test = $this->{"get$field"}();
            if (is_string($test)) {
                return $test;
            }
        }
        return false;
    }

    /**
     * @todo construct _filelist and use it
     */
    function setInstalledAs($file, $path)
    {
        if ($path) {
            return $this->_filelist[$file]['installed_as'] = $path;
        }
        unset($this->_filelist[$file]['installed_as']);
    }

    function setDirtree($path)
    {
        $this->_packageInfo['dirtree'][$path] = true;
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
        $this->_packageInfo = $pinfo;
    }

    /**
     * @return array
     */
    function toArray()
    {
        if (!$this->validate(PEAR_VALIDATE_NORMAL)) {
            return false;
        }
        return $this->getArray();
    }

    function getArray($forReg = false)
    {
        if ($forReg) {
            $this->_packageInfo['old'] = array();
            $this->_packageInfo['old']['package'] = $this->getPackage();
            $this->_packageInfo['old']['channel'] = $this->getChannel();
            $this->_packageInfo['old']['summary'] = $this->getSummary();
            $this->_packageInfo['old']['description'] = $this->getDescription();
            $this->_packageInfo['old']['release_version'] = $this->getVersion();
            $this->_packageInfo['old']['release_date'] = $this->getDate();
            $this->_packageInfo['old']['release_state'] = $this->getState();
            $this->_packageInfo['old']['release_license'] = $this->getLicense();
            $this->_packageInfo['old']['release_notes'] = $this->getNotes();
            $this->_packageInfo['old']['release_deps'] = $this->getDeps();
            $this->_packageInfo['old']['maintainers'] = $this->getMaintainers();
            if ($this->getExtends()) {
                $this->_packageInfo['old']['extends'] = $this->getExtends();
            }
            return $this->_packageInfo;
        } else {
            $info = $this->_packageInfo;
            unset($info['dirtree']);
            return $info;
        }
    }

    function getFlattenedFilelist()
    {
    }

    function getPrettyFilelist()
    {
    }

    function getChannel()
    {
        if (isset($this->_packageInfo['channel'])) {
            return $this->_packageInfo['channel'];
        }
        return false;
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
    
    function getExtends()
    {
        if (isset($this->_packageInfo['extends'])) {
            return $this->_packageInfo['extends'];
        }
        return false;
    }

    /**
     * @param package|api version category to return
     */
    function getVersion($key = 'package')
    {
        if (isset($this->_packageInfo['version'][$key])) {
            return $this->_packageInfo['version'][$key];
        }
        return false;
    }

    function getMaintainers($raw = false)
    {
        
        if (isset($this->_packageInfo['leads']) ||
              isset($this->_packageInfo['maintainers'])) {
            if ($raw) {
                $ret = (isset($this->_packageInfo['leads'])) ?
                    array('leads' => $this->_packageInfo['leads']) : array();
                (isset($this->_packageInfo['maintainers'])) ?
                    $ret['maintainers'] = $this->_packageInfo['maintainers'] :null;
                return $ret;
            } else {
                $ret = array();
                if (isset($this->_packageInfo['leads'])) {
                    foreach ($this->_packageInfo['leads'] as $lead) {
                        $s = $lead;
                        $s['role'] = 'lead';
                        $ret[] = $s;
                    }
                }
                if (isset($this->_packageInfo['maintainers'])) {
                    foreach ($this->_packageInfo['maintainers'] as $maintainer) {
                        $s = $maintainer;
                        $ret[] = $s;
                    }
                }
            }
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

    function getState($key = 'package')
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
    
    function getTime()
    {
        if (isset($this->_packageInfo['time'])) {
            return $this->_packageInfo['time'];
        }
        return false;
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
        if (isset($this->_packageInfo['notes'])) {
            return $this->_packageInfo['notes'];
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
                'extension' => 'ext',
                'os' => 'os'
                );
            if (isset($this->_packageInfo['dependendencies']['required'])) {
                foreach ($this->_packageInfo['dependendencies']['required']
                      as $dtype => $deps) {
                    if (!isset($deps[0])) {
                        $deps = array($deps);
                    }
                    foreach ($deps as $dep) {
                        if (!isset($map[$dtype])) {
                            continue;
                        }
                        $s = array('type' => $map[$dtype],
                            'channel' => $t = @$dep['channel'] ? $t : 'pear.php.net');
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
            }
            if (isset($this->_packageInfo['dependendencies']['optional'])) {
                foreach ($this->_packageInfo['dependendencies']['optional']
                      as $dtype => $deps) {
                    if (!isset($deps[0])) {
                        $deps = array($deps);
                    }
                    foreach ($deps as $dep) {
                        if (!isset($map[$dtype])) {
                            continue;
                        }
                        $s = array('type' => $map[$dtype],
                            'channel' => $t = @$dep['channel'] ? $t : 'pear.php.net');
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
            if (isset($this->_packageInfo['dependendencies']['group'])) {
                foreach ($this->_packageInfo['dependendencies']['group']
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

    function &getDefaultGenerator()
    {
        include_once 'PEAR/PackageFile/Generator/v2.php';
        $a = &new PEAR_PackageFile_Generator_v2($this);
        return $a;
    }

    function validate()
    {
        $this->_stack->getErrors(true);
        if (!isset($this->_packageInfo) || !is_array($this->_packageInfo)) {
            return false;
        }
        $structure =
        array(
            'name',
            'channel',
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
            '*dependencies', //special validation needed
            '+phprelease|+extsrcrelease|+extbinrelease|+bundle' //special validation needed
        );
        if (!$this->_stupidSchemaValidate($structure,
                                          $this->_packageInfo, '<package>')) {
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
        return !$this->_stack->hasErrors();
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
        $this->_stupidSchemaValidate($structure, $this->_packageInfo['version'], '<version>');
        $this->_stupidSchemaValidate($structure, $this->_packageInfo['stability'], '<stability>');
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
        return $this->_stupidSchemaValidate($structure, $dep, '<dependencies><php>');
            
    }

    function _validatePearinstallerDep($dep)
    {
        $structure = array(
            'min',
            '*max',
            '*recommended',
            '*exclude',
        );
        return $this->_stupidSchemaValidate($structure, $dep, '<dependencies><php>');
            
    }

    function _validatePackageDep($dep)
    {
        if (isset($dep['uri'])) {
            $structure = array(
                'name',
                'uri',
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
                '*conflicts',
            );
        }
        return $this->_stupidSchemaValidate($structure, $dep, '<dependencies><package>');
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
        return $this->_stupidSchemaValidate($structure, $dep, '<dependencies><extension>');
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
            '*requires',
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
                        '*extension',
                    );
                } else {
                    $structure = array(
                        'php',
                        'pearinstaller',
                        '*package',
                        '*extension',
                        '*os',
                        '*arch',
                    );
                }
                if ($this->_stupidSchemaValidate($structure,
                      $this->_packageInfo['dependencies'][$simpledep], "<$simpledep>")) {
                    foreach (array('package', 'extension') as $type) {
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
                '*extension',
            );
            if ($this->_stupidSchemaValidate($structure,
                  $this->_packageInfo['dependencies']['group'], '<group>')) {
                $groups = $this->_packageInfo['dependencies']['group'];
                if (!isset($groups[0])) {
                    $groups = array($groups);
                }
                foreach ($groups as $group) {
                    foreach (array('package', 'extension') as $type) {
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
        }
    }

    function _validateFilelist($list = false, $filetag = 'file', $allowignore = false)
    {
        if (!$list) {
            $list = $this->_packageInfo['contents'];
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
                        return $this->_invalidFileRole($list[$filetag]['attribs']['name'],
                            $list['attribs']['name']);
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
        if (isset($this->_packageInfo[$release][0])) {
            foreach ($this->_packageInfo[$release] as $rel) {
                $this->_validateInstallConditions($rel['installconditions'], "<$rel>");
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
        include_once 'PEAR/Common.php';
        return in_array($role, PEAR_Common::getFileRoles());
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
}
?>