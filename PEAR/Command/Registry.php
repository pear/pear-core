<?php
// /* vim: set expandtab tabstop=4 shiftwidth=4: */
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
// | Author: Stig Bakken <ssb@php.net>                                    |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'PEAR/Command/Common.php';
require_once 'PEAR/Registry.php';
require_once 'PEAR/Config.php';

class PEAR_Command_Registry extends PEAR_Command_Common
{
    // {{{ properties

    var $commands = array(
        'list' => array(
            'summary' => 'List Installed Packages In The Default Channel',
            'function' => 'doList',
            'shortcut' => 'l',
            'options' => array(
                'channel' => array(
                    'shortopt' => 'c',
                    'doc' => 'list installed packages from this channel',
                    ),
                ),
            'doc' => '<package>
If invoked without parameters, this command lists the PEAR packages
installed in your php_dir ({config php_dir}).  With a parameter, it
lists the files in a package.
',
            ),
        'list-installed' => array(
            'summary' => 'List All Installed Packages In All Channels',
            'function' => 'doListAll',
            'shortcut' => 'la',
            'options' => array(),
            'doc' => '
List all installed packages in all channels.
'
            ),
        'list-files' => array(
            'summary' => 'List Files In Installed Package',
            'function' => 'doFileList',
            'shortcut' => 'fl',
            'options' => array(
                'channel' => array(
                    'shortopt' => 'c',
                    'doc' => 'package is in this channel',
                    ),
                ),
            'doc' => '<package>
List the files in an installed package.
'
            ),
        'shell-test' => array(
            'summary' => 'Shell Script Test',
            'function' => 'doShellTest',
            'shortcut' => 'st',
            'options' => array(),
            'doc' => '<package> [[relation] version]
Tests if a package is installed in the system. Will exit(1) if it is not.
   <relation>   The version comparison operator. One of:
                <, lt, <=, le, >, gt, >=, ge, ==, =, eq, !=, <>, ne
   <version>    The version to compare with
'),
        'info' => array(
            'summary'  => 'Display information about a package',
            'function' => 'doInfo',
            'shortcut' => 'in',
            'options'  => array(),
            'doc'      => '<package>
Displays information about a package. The package argument may be a
local package file, an URL to a package file, or the name of an
installed package.'
            )
        );

    // }}}
    // {{{ constructor

    /**
     * PEAR_Command_Registry constructor.
     *
     * @access public
     */
    function PEAR_Command_Registry(&$ui, &$config)
    {
        parent::PEAR_Command_Common($ui, $config);
    }

    // }}}

    // {{{ doList()

    function _sortinfo($a, $b)
    {
        return strcmp($a['package'], $b['package']);
    }

    function doList($command, $options, $params)
    {
        $reg = &$this->config->getRegistry();
        if (sizeof($params) == 1) {
            return $this->doFileList($command, $options, $params);
        }
        if (isset($options['channel'])) {
            if ($reg->channelExists($options['channel'])) {
                $channel = $reg->channelName($options['channel']);
            } else {
                return $this->raiseError("Channel `$options[channel]' does not exist");
            }
        } else {
            $channel = $this->config->get('default_channel');
        }
        $installed = $reg->packageInfo(null, null, $channel);
        usort($installed, array(&$this, '_sortinfo'));
        $i = $j = 0;
        $data = array(
            'caption' => 'Installed packages, channel ' .
                $channel . ':',
            'border' => true,
            'headline' => array('Package', 'Version', 'State')
            );
        foreach ($installed as $package) {
            $data['data'][] = array($package['package'],
                                      $package['version'],
                                      @$package['release_state']);
        }
        if (count($installed)==0) {
            $data = '(no packages installed)';
        }
        $this->ui->outputData($data, $command);
        return true;
    }
    
    function doListAll($command, $options, $params)
    {
        $reg = &$this->config->getRegistry();
        if (sizeof($params) == 0) {
            $installed = $reg->packageInfo(null, null, null);
            foreach ($installed as $channel => $packages) {
                usort($packages, array($this, '_sortinfo'));
                $i = $j = 0;
                $data = array(
                    'caption' => 'Installed packages, channel ' . $channel . ':',
                    'border' => true,
                    'headline' => array('Channel', 'Package', 'Version', 'State')
                    );
                foreach ($packages as $package) {
                    $data['data'][] = array($channel,
                                              $package['package'],
                                              $package['version'],
                                              @$package['release_state']);
                }
                if (count($packages)==0) {
                    $data = array(
                        'caption' => 'Installed packages, channel ' . $channel . ':',
                        'border' => true,
                        'data' => array(array('(no packages installed)')),
                        );
                }
                $this->ui->outputData($data, $command);
            }
        } else {
            $this->ui->outputData($data, $command);
        }
        return true;
    }
    
    function doFileList($command, $options, $params)
    {
        $reg = &$this->config->getRegistry();
        if ((file_exists($params[0]) && !is_dir($params[0])) || $fp = @fopen($params[0], 'r')) {
            @fclose($fp);
            include_once 'PEAR/PackageFile.php';
            $pkg = &new PEAR_PackageFile($this->config, $this->_debug);
            $info = &$pkg->fromAnyFile($params[0], PEAR_VALIDATE_NORMAL);
            $headings = array('Package File', 'Install Path');
            $installed = false;
        } else {
            $parsed = $reg->parsePackageName($params[0]);
            if (PEAR::isError($parsed)) {
                return $this->raiseError($parsed);
            }
            $info = &$reg->getPackage($parsed['package'], $parsed['channel']);
            $headings = array('Type', 'Install Path');
            $installed = true;
        }
        if (PEAR::isError($info)) {
            return $this->raiseError($info);
        }
        if ($info === null) {
            return $this->raiseError("`$params[0]' not installed");
        }
        $list = ($info->getPackagexmlVersion() == '1.0' || $installed) ?
            $info->getFilelist() : $info->getContents();
        if ($installed) {
            $caption = 'Installed Files For ' . $params[0];
        } else {
            $caption = 'Contents of ' . basename($params[0]);
        }
        $data = array(
            'caption' => $caption,
            'border' => true,
            'headline' => $headings);
        if ($info->getPackagexmlVersion() == '1.0') {
            foreach ($list as $file => $att) {
                if ($installed) {
                    if (empty($att['installed_as'])) {
                        continue;
                    }
                    $data['data'][] = array($att['role'], $att['installed_as']);
                } else {
                    if (isset($att['baseinstalldir']) && !in_array($att['role'],
                          array('test', 'data', 'doc'))) {
                        $dest = $att['baseinstalldir'] . DIRECTORY_SEPARATOR .
                            $file;
                    } else {
                        $dest = $file;
                    }
                    switch ($att['role']) {
                        case 'test':
                        case 'data':
                        case 'doc':
                            $role = $att['role'];
                            if ($role == 'test') {
                                $role .= 's';
                            }
                            $dest = $this->config->get($role . '_dir') . DIRECTORY_SEPARATOR .
                                $info->getPackage() . DIRECTORY_SEPARATOR . $dest;
                            break;
                        case 'php':
                        default:
                            $dest = $this->config->get('php_dir') . DIRECTORY_SEPARATOR .
                                $dest;
                    }
                    $dest = preg_replace('!/+!', '/', $dest);
                    $file = preg_replace('!/+!', '/', $file);
                    $data['data'][] = array($file, $dest);
                }
            }
        } else { // package.xml 2.0, not installed
            if (isset($list['file']['attribs'])) {
                $list['file'] = array($list['file']);
            }
            foreach ($list['file'] as $att) {
                $att = $att['attribs'];
                $file = $att['name'];
                if (isset($att['baseinstalldir']) && !in_array($att['role'],
                      array('test', 'data', 'doc'))) {
                    $dest = $att['baseinstalldir'] . DIRECTORY_SEPARATOR .
                        $file;
                } else {
                    $dest = $file;
                }
                switch ($att['role']) {
                    case 'test':
                    case 'data':
                    case 'doc':
                        $role = $att['role'];
                        if ($role == 'test') {
                            $role .= 's';
                        }
                        $dest = $this->config->get($role . '_dir') . DIRECTORY_SEPARATOR .
                            $info->getPackage() . DIRECTORY_SEPARATOR . $dest;
                        break;
                    case 'php':
                    default:
                        $dest = $this->config->get('php_dir') . DIRECTORY_SEPARATOR .
                            $dest;
                }
                $dest = preg_replace('!/+!', '/', $dest);
                $file = preg_replace('!/+!', '/', $file);
                $data['data'][] = array($file, $dest);
            }
        }
        $this->ui->outputData($data, $command);
        return true;
    }

    // }}}
    // {{{ doShellTest()

    function doShellTest($command, $options, $params)
    {
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $reg = &new PEAR_Registry($this->config->get('php_dir'));
        // "pear shell-test Foo"
        if (sizeof($params) == 1) {
            if (!$reg->packageExists($params[0])) {
                exit(1);
            }
            // "pear shell-test Foo 1.0"
        } elseif (sizeof($params) == 2) {
            $v = $reg->packageInfo($params[0], 'version');
            if (!$v || !version_compare("$v", "{$params[1]}", "ge")) {
                exit(1);
            }
            // "pear shell-test Foo ge 1.0"
        } elseif (sizeof($params) == 3) {
            $v = $reg->packageInfo($params[0], 'version');
            if (!$v || !version_compare("$v", "{$params[2]}", $params[1])) {
                exit(1);
            }
        } else {
            $this->popErrorHandling();
            $this->raiseError("$command: expects 1 to 3 parameters");
            exit(1);
        }
    }

    // }}}
    // {{{ doInfo

    function doInfo($command, $options, $params)
    {
        // $params[0] The package for showing info
        if (sizeof($params) != 1) {
            return $this->raiseError("This command only accepts one param: ".
                                     "a package name");
        }
        $info = false;
        $reg = &$this->config->getRegistry();
        if ((@is_file($params[0]) && !is_dir($params[0])) || $fp = @fopen($params[0], 'r')) {
            @fclose($fp);
            $pkg = &new PEAR_PackageFile($this->config, $this->_debug);
            PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
            $obj = &$pkg->fromAnyFile($params[0], PEAR_VALIDATE_NORMAL);
            PEAR::staticPopErrorHandling();
            if (PEAR::isError($obj)) {
                foreach ($obj->getUserInfo() as $message) {
                    if (is_array($message)) {
                        $message = $message['message'];
                    }
                    $this->ui->outputData($message);
                }
                $this->ui->outputData($obj->getMessage());
                return true;
            }
            if ($obj->getPackagexmlVersion() == '1.0') {
                $info = $obj->toArray();
            } else {
                return $this->_doList2($command, $options, $params, $obj);
            }
        } else {
            $parsed = $reg->parsePackageName($params[0]);
            if (PEAR::isError($parsed)) {
                return $this->raiseError($parsed);
            }
            $package = $parsed['package'];
            $channel = $parsed['channel'];
            $info = $reg->packageInfo($package, null, $channel);
            if (isset($info['old'])) {
                $obj = $reg->getPackage($package, $channel);
                return $this->_doList2($command, $options, $params, $obj);
            }
        }
        if (PEAR::isError($info)) {
            return $info;
        }
        if (empty($info)) {
            $this->raiseError("No information found for `$params[0]'");
            return;
        }
        unset($info['filelist']);
        unset($info['dirtree']);
        unset($info['changelog']);
        if (isset($info['xsdversion'])) {
            $info['package.xml version'] = $info['xsdversion'];
            unset($info['xsdversion']);
        }
        if (isset($info['packagerversion'])) {
            $info['packaged with PEAR version'] = $info['packagerversion'];
            unset($info['packagerversion']);
        }
        $keys = array_keys($info);
        $longtext = array('description', 'summary');
        foreach ($keys as $key) {
            if (is_array($info[$key])) {
                switch ($key) {
                    case 'maintainers': {
                        $i = 0;
                        $mstr = '';
                        foreach ($info[$key] as $m) {
                            if ($i++ > 0) {
                                $mstr .= "\n";
                            }
                            $mstr .= $m['name'] . " <";
                            if (isset($m['email'])) {
                                $mstr .= $m['email'];
                            } else {
                                $mstr .= $m['handle'] . '@php.net';
                            }
                            $mstr .= "> ($m[role])";
                        }
                        $info[$key] = $mstr;
                        break;
                    }
                    case 'release_deps': {
                        $i = 0;
                        $dstr = '';
                        foreach ($info[$key] as $d) {
                            if (isset($this->_deps_rel_trans[$d['rel']])) {
                                $rel = $this->_deps_rel_trans[$d['rel']];
                            } else {
                                $rel = $d['rel'];
                            }
                            if (isset($this->_deps_type_trans[$d['type']])) {
                                $type = ucfirst($this->_deps_type_trans[$d['type']]);
                            } else {
                                $type = $d['type'];
                            }
                            if (isset($d['name'])) {
                                $name = $d['name'] . ' ';
                            } else {
                                $name = '';
                            }
                            if (isset($d['version'])) {
                                $version = $d['version'] . ' ';
                            } else {
                                $version = '';
                            }
                            $dstr .= "$type $name$rel $version\n";
                        }
                        $info[$key] = $dstr;
                        break;
                    }
                    case 'provides' : {
                        $debug = $this->config->get('verbose');
                        if ($debug < 2) {
                            $pstr = 'Classes: ';
                        } else {
                            $pstr = '';
                        }
                        $i = 0;
                        foreach ($info[$key] as $p) {
                            if ($debug < 2 && $p['type'] != "class") {
                                continue;
                            }
                            // Only print classes when verbosity mode is < 2
                            if ($debug < 2) {
                                if ($i++ > 0) {
                                    $pstr .= ", ";
                                }
                                $pstr .= $p['name'];
                            } else {
                                if ($i++ > 0) {
                                    $pstr .= "\n";
                                }
                                $pstr .= ucfirst($p['type']) . " " . $p['name'];
                                if (isset($p['explicit']) && $p['explicit'] == 1) {
                                    $pstr .= " (explicit)";
                                }
                            }
                        }
                        $info[$key] = $pstr;
                        break;
                    }
                    default: {
                        $info[$key] = implode(", ", $info[$key]);
                        break;
                    }
                }
            }
            if ($key == '_lastmodified') {
                $hdate = date('Y-m-d', $info[$key]);
                unset($info[$key]);
                $info['Last Modified'] = $hdate;
            } else {
                $info[$key] = trim($info[$key]);
                if (in_array($key, $longtext)) {
                    $info[$key] = preg_replace('/  +/', ' ', $info[$key]);
                }
            }
        }
        $caption = 'About ' . $info['package'] . '-' . $info['version'];
        $data = array(
            'caption' => $caption,
            'border' => true);
        foreach ($info as $key => $value) {
            $key = ucwords(trim(str_replace('_', ' ', $key)));
            $data['data'][] = array($key, $value);
        }
        $data['raw'] = $info;

        $this->ui->outputData($data, 'package-info');
    }

    // }}}

    function _doList2($command, $options, $params, &$obj)
    {
        $caption = 'About ' . $obj->getChannel() . '/' .$obj->getPackage() . '-' .
            $obj->getVersion();
        $data = array(
            'caption' => $caption,
            'border' => true);
        switch ($obj->getReleaseType()) {
            case 'php' :
                $release = 'PEAR-style PHP-based Package';
            break;
            case 'extsrc' :
                $release = 'PECL-style PHP extension (source code)';
            break;
            case 'extbin' :
                $release = 'PECL-style PHP extension (binary)';
            break;
            case 'bundle' :
                $release = 'Package bundle (collection of packages)';
            break;
        }
        $extends = $obj->getExtends();
        $extends = $extends ?
            $obj->getPackage() . ' (extends ' . $extends . ')' : $obj->getPackage();
        if ($src = $obj->getSourcePackage()) {
            $extends .= ' (source package ' . $src['channel'] . '/' . $src['package'] . ')';
        }
        $info = array(
            'Release Type' => $release,
            'Name' => $extends,
            'Channel' => $obj->getChannel(),
            'Summary' => preg_replace('/  +/', ' ', $obj->getSummary()),
            'Description' => preg_replace('/  +/', ' ', $obj->getDescription()),
            );
        $info['Maintainers'] = '';
        foreach (array('lead', 'developer', 'contributor', 'helper') as $role) {
            $leads = $obj->{"get{$role}s"}();
            if (!$leads) {
                continue;
            }
            if (isset($leads['active'])) {
                $leads = array($leads);
            }
            foreach ($leads as $lead) {
                if (!empty($info['Maintainers'])) {
                    $info['Maintainers'] .= "\n";
                }
                $info['Maintainers'] .= $lead['name'] . ' <';
                $info['Maintainers'] .= $lead['email'] . "> ($role)";
            }
        }
        $info['Release Date'] = $obj->getDate();
        if ($time = $obj->getTime()) {
            $info['Release Date'] .= ' ' . $time;
        }
        $info['Release Version'] = $obj->getVersion() . ' (' . $obj->getState() . ')';
        $info['API Version'] = $obj->getVersion('api') . ' (' . $obj->getState('api') . ')';
        $info['License'] = $obj->getLicense();
        $lic = $obj->getLicense(true);
        if (isset($lic['attribs']['uri'])) {
            $info['License'] .= ' (' . $lic['attribs']['uri'] . ')';
        }
        $info['Release Notes'] = $obj->getNotes();
        if ($compat = $obj->getCompatible()) {
            $info['Compatible with'] = '';
            foreach ($compat as $package) {
                $info['Compatible with'] .= $package['channel'] . '/' . $package['package'] .
                    "\nVersions >= " . $package['min'] . ', <= ' . $package['max'];
                if (isset($package['exclude'])) {
                    if (is_array($package['exclude'])) {
                        $package['exclude'] = implode(', ', $package['exclude']);
                    }
                    if (!isset($info['Not Compatible with'])) {
                        $info['Not Compatible with'] = '';
                    } else {
                        $info['Not Compatible with'] .= "\n";
                    }
                    $info['Not Compatible with'] .= $package['channel'] . '/' .
                        $package['package'] . "\nVersions " . $package['exclude'];
                }
            }
        }
        $deps = $obj->getDependencies();
        $info['Required Dependencies'] = 'PHP version ' . $deps['required']['php']['min'] .
            '-' . $deps['required']['php']['max'] . "\n";
        if (isset($deps['required']['php']['exclude'])) {
            if (!isset($info['Not Compatible with'])) {
                $info['Not Compatible with'] = '';
            } else {
                $info['Not Compatible with'] .= "\n";
            }
            if (is_array($deps['required']['php']['exclude'])) {
                $deps['required']['php']['exclude'] =
                    implode(', ', $deps['required']['php']['exclude']);
            }
            $info['Not Compatible with'] .= "PHP versions\n  " .
                $deps['required']['php']['exclude'];
        }
        $info['Required Dependencies'] .= 'PEAR installer version';
        if (isset($deps['required']['pearinstaller']['max'])) {
            $info['Required Dependencies'] .= 's ' .
                $deps['required']['pearinstaller']['min'] . '-' .
                $deps['required']['pearinstaller']['max'];
        } else {
            $info['Required Dependencies'] .= ' ' .
                $deps['required']['pearinstaller']['min'] . ' or newer';
        }
        if (isset($deps['required']['pearinstaller']['exclude'])) {
            if (!isset($info['Not Compatible with'])) {
                $info['Not Compatible with'] = '';
            } else {
                $info['Not Compatible with'] .= "\n";
            }
            if (is_array($deps['required']['pearinstaller']['exclude'])) {
                $deps['required']['pearinstaller']['exclude'] =
                    implode(', ', $deps['required']['pearinstaller']['exclude']);
            }
            $info['Not Compatible with'] .= "PEAR installer\n  Versions " .
                $deps['required']['pearinstaller']['exclude'];
        }
        foreach (array('Package', 'Extension') as $type) {
            $index = strtolower($type);
            if (isset($deps['required'][$index])) {
                if (isset($deps['required'][$index]['name'])) {
                    $deps['required'][$index] = array($deps['required'][$index]);
                }
                foreach ($deps['required'][$index] as $package) {
                    if (isset($package['conflicts']) && $package['conflicts'] == 'yes') {
                        $infoindex = 'Not Compatible with';
                        if (!isset($info['Not Compatible with'])) {
                            $info['Not Compatible with'] = '';
                        } else {
                            $info['Not Compatible with'] .= "\n";
                        }
                    } else {
                        $infoindex = 'Required Dependencies';
                        $info[$infoindex] .= "\n";
                    }
                    if ($index == 'extension') {
                        $name = $package['name'];
                    } else {
                        if (isset($package['channel'])) {
                            $name = $package['channel'] . '/' . $package['name'];
                        } else {
                            $name = '__private/' . $package['name'] . ' (static URI)';
                        }
                    }
                    $info[$infoindex] .= "$type $name";
                    if (isset($package['uri'])) {
                        $info[$infoindex] .= "\n  Download URI: $package[uri]";
                        continue;
                    }
                    if ($infoindex == 'Not Compatible with') {
                        // conflicts is only used to say that all versions conflict
                        continue;
                    }
                    if (isset($package['max']) && isset($package['min'])) {
                        $info[$infoindex] .= " \n  Versions " .
                            $package['min'] . '-' . $package['max'];
                    } elseif (isset($package['min'])) {
                        $info[$infoindex] .= " \n  Version " .
                            $package['min'] . ' or newer';
                    } elseif (isset($package['max'])) {
                        $info[$infoindex] .= " \n  Version " .
                            $package['min'] . ' or older';
                    }
                    if (isset($package['recommended'])) {
                        $info[$infoindex] .= "\n  Recommended version: $package[recommended]";
                    }
                    if (isset($package['exclude'])) {
                        if (!isset($info['Not Compatible with'])) {
                            $info['Not Compatible with'] = '';
                        } else {
                            $info['Not Compatible with'] .= "\n";
                        }
                        if (is_array($package['exclude'])) {
                            $package['exclude'] = implode(', ', $package['exclude']);
                        }
                        $info['Not Compatible with'] .= "Package $package\n  Versions " .
                            $package['exclude'];
                    }
                }
            }
        }
        if (isset($deps['required']['os'])) {
            if (isset($deps['required']['os']['name'])) {
                $dep['required']['os']['name'] = array($dep['required']['os']['name']);
            }
            foreach ($dep['required']['os'] as $os) {
                if (isset($os['conflicts']) && $os['conflicts'] == 'yes') {
                    if (!isset($info['Not Compatible with'])) {
                        $info['Not Compatible with'] = '';
                    } else {
                        $info['Not Compatible with'] .= "\n";
                    }
                    $info['Not Compatible with'] .= "$os[name] Operating System";
                } else {
                    $info['Required Dependencies'] .= "\n";
                    $info['Required Dependencies'] .= "$os[name] Operating System";
                }
            }
        }
        if (isset($deps['required']['arch'])) {
            if (isset($deps['required']['arch']['pattern'])) {
                $dep['required']['arch']['pattern'] = array($dep['required']['os']['pattern']);
            }
            foreach ($dep['required']['arch'] as $os) {
                if (isset($os['conflicts']) && $os['conflicts'] == 'yes') {
                    if (!isset($info['Not Compatible with'])) {
                        $info['Not Compatible with'] = '';
                    } else {
                        $info['Not Compatible with'] .= "\n";
                    }
                    $info['Not Compatible with'] .= "OS/Arch matching pattern '/$os[pattern]/'";
                } else {
                    $info['Required Dependencies'] .= "\n";
                    $info['Required Dependencies'] .= "OS/Arch matching pattern '/$os[pattern]/'";
                }
            }
        }
        if (isset($deps['optional'])) {
            foreach (array('Package', 'Extension') as $type) {
                $index = strtolower($type);
                if (isset($deps['optional'][$index])) {
                    if (isset($deps['optional'][$index]['name'])) {
                        $deps['optional'][$index] = array($deps['optional'][$index]);
                    }
                    foreach ($deps['optional'][$index] as $package) {
                        if (isset($package['conflicts']) && $package['conflicts'] == 'yes') {
                            $infoindex = 'Not Compatible with';
                            if (!isset($info['Not Compatible with'])) {
                                $info['Not Compatible with'] = '';
                            } else {
                                $info['Not Compatible with'] .= "\n";
                            }
                        } else {
                            $infoindex = 'Optional Dependencies';
                            if (!isset($info['Optional Dependencies'])) {
                                $info['Optional Dependencies'] = '';
                            } else {
                                $info['Optional Dependencies'] .= "\n";
                            }
                        }
                        if ($index == 'extension') {
                            $name = $package['name'];
                        } else {
                            if (isset($package['channel'])) {
                                $name = $package['channel'] . '/' . $package['name'];
                            } else {
                                $name = '__private/' . $package['name'] . ' (static URI)';
                            }
                        }
                        $info[$infoindex] .= "$type $name";
                        if (isset($package['uri'])) {
                            $info[$infoindex] .= "\n  Download URI: $package[uri]";
                            continue;
                        }
                        if ($infoindex == 'Not Compatible with') {
                            // conflicts is only used to say that all versions conflict
                            continue;
                        }
                        if (isset($package['max']) && isset($package['min'])) {
                            $info[$infoindex] .= " \n  Versions " .
                                $package['min'] . '-' . $package['max'];
                        } elseif (isset($package['min'])) {
                            $info[$infoindex] .= " \n  Version " .
                                $package['min'] . ' or newer';
                        } elseif (isset($package['max'])) {
                            $info[$infoindex] .= " \n  Version " .
                                $package['min'] . ' or older';
                        }
                        if (isset($package['recommended'])) {
                            $info[$infoindex] .= "\n  Recommended version: $package[recommended]";
                        }
                        if (isset($package['exclude'])) {
                            if (!isset($info['Not Compatible with'])) {
                                $info['Not Compatible with'] = '';
                            } else {
                                $info['Not Compatible with'] .= "\n";
                            }
                            if (is_array($package['exclude'])) {
                                $package['exclude'] = implode(', ', $package['exclude']);
                            }
                            $info['Not Compatible with'] .= "Package $package\n  Versions " .
                                $package['exclude'];
                        }
                    }
                }
            }
        }
        if (isset($deps['group'])) {
            if (!isset($deps['group'][0])) {
                $deps['group'] = array($deps['group']);
            }
            foreach ($deps['group'] as $group) {
                $info['Dependency Group ' . $group['attribs']['name']] = $group['attribs']['hint'];
                $groupindex = $group['attribs']['name'] . ' Contents';
                $info[$groupindex] = '';
                foreach (array('Package', 'Extension') as $type) {
                    $index = strtolower($type);
                    if (isset($group[$index])) {
                        if (isset($group[$index]['name'])) {
                            $group[$index] = array($group[$index]);
                        }
                        foreach ($group[$index] as $package) {
                            if (!empty($info[$groupindex])) {
                                $info[$groupindex] .= "\n";
                            }
                            if ($index == 'extension') {
                                $name = $package['name'];
                            } else {
                                if (isset($package['channel'])) {
                                    $name = $package['channel'] . '/' . $package['name'];
                                } else {
                                    $name = '__private/' . $package['name'] . ' (static URI)';
                                }
                            }
                            if (isset($package['uri'])) {
                                if (isset($package['conflicts']) && $package['conflicts'] == 'yes') {
                                    $info[$groupindex] .= "Not Compatible with $type $name";
                                } else {
                                    $info[$groupindex] .= "$type $name";
                                }
                                $info[$groupindex] .= "\n  Download URI: $package[uri]";
                                continue;
                            }
                            if (isset($package['conflicts']) && $package['conflicts'] == 'yes') {
                                $info[$groupindex] .= "Not Compatible with $type $name";
                                continue;
                            }
                            $info[$groupindex] .= "$type $name";
                            if (isset($package['max']) && isset($package['min'])) {
                                $info[$groupindex] .= " \n  Versions " .
                                    $package['min'] . '-' . $package['max'];
                            } elseif (isset($package['min'])) {
                                $info[$groupindex] .= " \n  Version " .
                                    $package['min'] . ' or newer';
                            } elseif (isset($package['max'])) {
                                $info[$groupindex] .= " \n  Version " .
                                    $package['min'] . ' or older';
                            }
                            if (isset($package['recommended'])) {
                                $info[$groupindex] .= "\n  Recommended version: $package[recommended]";
                            }
                            if (isset($package['exclude'])) {
                                if (!isset($info['Not Compatible with'])) {
                                    $info['Not Compatible with'] = '';
                                } else {
                                    $info[$groupindex] .= "Not Compatible with\n";
                                }
                                if (is_array($package['exclude'])) {
                                    $package['exclude'] = implode(', ', $package['exclude']);
                                }
                                $info[$groupindex] .= "  Package $package\n  Versions " .
                                    $package['exclude'];
                            }
                        }
                    }
                }
            }
        }
        if ($obj->getReleaseType() == 'bundle') {
            $info['Bundled Packages'] = '';
            foreach ($obj->getBundledPackages() as $package) {
                if (!empty($info['Bundled Packages'])) {
                    $info['Bundled Packages'] .= "\n";
                }
                if (isset($package['uri'])) {
                    $info['Bundled Packages'] .= '__private/' . $package['name'];
                    $info['Bundled Packages'] .= "\n  (URI: $package[uri]";
                } else {
                    $info['Bundled Packages'] .= $package['channel'] . '/' . $package['name'];
                }
            }
        }
        $info['package.xml version'] = '2.0';
        foreach ($info as $key => $value) {
            $data['data'][] = array($key, $value);
        }
        $data['raw'] = $obj->toArray();

        $this->ui->outputData($data, 'package-info');
    }
}

?>
