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
// | Author: Stig Bakken <ssb@php.net>                                    |
// |         Tomas V.V.Cox <cox@idecnet.com>                              |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$

/*
TODO:
    - Transform into singleton()
    - Add application level lock (avoid change the registry from the cmdline
      while using the GTK interface, for ex.)
*/
require_once 'System.php';
require_once 'PEAR.php';
require_once 'PEAR/Installer/Role.php';

define('PEAR_REGISTRY_ERROR_LOCK',   -2);
define('PEAR_REGISTRY_ERROR_FORMAT', -3);
define('PEAR_REGISTRY_ERROR_FILE',   -4);
define('PEAR_REGISTRY_ERROR_CONFLICT', -5);
define('PEAR_REGISTRY_ERROR_CHANNEL_FILE', -6);

/**
 * Administration class used to maintain the installed package database.
 */
class PEAR_Registry extends PEAR
{
    // {{{ properties

    /**
     * File containing all channel information.
     * @var string
     */
    var $channels = '';

    /** Directory where registry files are stored.
     * @var string
     */
    var $statedir = '';

    /** File where the file map is stored
     * @var string
     */
    var $filemap = '';

    /** Directory where registry files for channels are stored.
     * @var string
     */
    var $channelsdir = '';

    /** Name of file used for locking the registry
     * @var string
     */
    var $lockfile = '';

    /** File descriptor used during locking
     * @var resource
     */
    var $lock_fp = null;

    /** Mode used during locking
     * @var int
     */
    var $lock_mode = 0; // XXX UNUSED

    /** Cache of package information.  Structure:
     * array(
     *   'package' => array('id' => ... ),
     *   ... )
     * @var array
     */
    var $pkginfo_cache = array();

    /** Cache of file map.  Structure:
     * array( '/path/to/file' => 'package', ... )
     * @var array
     */
    var $filemap_cache = array();

    /**
     * @var false|PEAR_ChannelFile
     */
    var $_pearChannel;
    // }}}

    // {{{ constructor

    /**
     * PEAR_Registry constructor.
     *
     * @param string (optional) PEAR install directory (for .php files)
     * @param PEAR_ChannelFile PEAR_ChannelFile object representing the PEAR channel, if
     *        default values are not desired.  Only used the very first time a PEAR
     *        repository is initialized
     *
     * @access public
     */
    function PEAR_Registry($pear_install_dir = PEAR_INSTALL_DIR, $pear_channel = false)
    {
        parent::PEAR();
        $ds = DIRECTORY_SEPARATOR;
        $this->install_dir = $pear_install_dir;
        $this->channelsdir = $pear_install_dir.$ds.'.channels';
        $this->statedir = $pear_install_dir.$ds.'.registry';
        $this->filemap  = $pear_install_dir.$ds.'.filemap';
        $this->lockfile = $pear_install_dir.$ds.'.lock';
        $this->_pearChannel = $pear_channel;
    }

    function _initializeDirs()
    {
        static $called = false;
        if ($called) {
            return;
        }
        $ds = DIRECTORY_SEPARATOR;
        $called = true;
        // XXX Compatibility code should be removed in the future
        // rename all registry files if any to lowercase
        if (!OS_WINDOWS && $handle = @opendir($this->statedir)) {
            $dest = $this->statedir . $ds;
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/^.*[A-Z].*\.reg$/', $file)) {
                    rename($dest . $file, $dest . strtolower($file));
                }
            }
            closedir($handle);
        }
        if (!is_dir($this->channelsdir) ||
              !file_exists($this->channelsdir . $ds . 'pear.php.net.reg')) {
            $pear_channel = $this->_pearChannel;
            if (!is_a($pear_channel, 'PEAR_ChannelFile') || !$pear_channel->validate()) {
                include_once 'PEAR/ChannelFile.php';
                $pear_channel = new PEAR_ChannelFile;
                $pear_channel->setName('pear.php.net');
                $pear_channel->setAlias('pear');
                $pear_channel->setServer('pear.php.net');
                $pear_channel->setSummary('PHP Extension and Application Repository');
                $pear_channel->setDefaultPEARProtocols();
            } else {
                $pear_channel->setName('pear.php.net');
                $pear_channel->setAlias('pear');
            }
            $pear_channel->validate();
            $this->addChannel($pear_channel);
            $this->rebuildFileMap();
        } elseif (!file_exists($this->filemap)) {
            $this->rebuildFileMap();
        }
    }
    // }}}
    // {{{ destructor

    /**
     * PEAR_Registry destructor.  Makes sure no locks are forgotten.
     *
     * @access private
     */
    function _PEAR_Registry()
    {
        parent::_PEAR();
        if (is_resource($this->lock_fp)) {
            $this->_unlock();
        }
    }

    // }}}

    // {{{ _assertStateDir()

    /**
     * Make sure the directory where we keep registry files exists.
     *
     * @return bool TRUE if directory exists, FALSE if it could not be
     * created
     *
     * @access private
     */
    function _assertStateDir($channel = false)
    {
        if ($channel && $this->_getChannelFromAlias($channel) != 'pear.php.net') {
            return $this->_assertChannelStateDir($channel);
        }
        if (!@is_dir($this->statedir)) {
            if (!System::mkdir(array('-p', $this->statedir))) {
                return $this->raiseError("could not create directory '{$this->statedir}'");
            }
        }
        return true;
    }

    // }}}
    // {{{ _assertChannelStateDir()

    /**
     * Make sure the directory where we keep registry files exists for a non-standard channel.
     *
     * @param string channel name
     * @return bool TRUE if directory exists, FALSE if it could not be
     * created
     *
     * @access private
     */
    function _assertChannelStateDir($channel)
    {
        if (!$channel || $this->_getChannelFromAlias($channel) == 'pear.php.net') {
            return $this->_assertStateDir($channel);
        }
        $channel = strtolower($channel);
        if (!@is_dir($this->statedir . DIRECTORY_SEPARATOR . '.channel.' . strtolower($channel))) {
            if (!System::mkdir(array('-p', $this->statedir . DIRECTORY_SEPARATOR . '.channel.' . strtolower($channel)))) {
                return $this->raiseError("could not create directory '" . 
                    $this->statedir . DIRECTORY_SEPARATOR . '.channel.' . strtolower($channel) . "'");
            }
        }
        return true;
    }

    // }}}
    // {{{ _assertChannelDir()

    /**
     * Make sure the directory where we keep registry files for channels exists
     *
     * @return bool TRUE if directory exists, FALSE if it could not be
     * created
     *
     * @access private
     */
    function _assertChannelDir()
    {
        if (!@is_dir($this->channelsdir)) {
            if (!System::mkdir(array('-p', $this->channelsdir))) {
                return $this->raiseError("could not create directory '{$this->channelsdir}'");
            }
        }
        if (!@is_dir($this->channelsdir . DIRECTORY_SEPARATOR . '.alias')) {
            if (!System::mkdir(array('-p', $this->channelsdir . DIRECTORY_SEPARATOR . '.alias'))) {
                return $this->raiseError("could not create directory '{$this->channelsdir}/.alias'");
            }
        }
        return true;
    }

    // }}}
    // {{{ _packageFileName()

    /**
     * Get the name of the file where data for a given package is stored.
     *
     * @param string channel name, or false if this is a PEAR package
     * @param string package name
     *
     * @return string registry file name
     *
     * @access public
     */
    function _packageFileName($package, $channel = false)
    {
        if ($channel && $this->_getChannelFromAlias($channel) != 'pear.php.net') {
            $package = '.channel.' . strtolower($channel) . DIRECTORY_SEPARATOR . $package;
        }
        return $this->statedir . DIRECTORY_SEPARATOR . strtolower($package) . '.reg';
    }

    // }}}
    // {{{ _channelFileName()

    /**
     * Get the name of the file where data for a given channel is stored.
     * @param string channel name
     *
     * @return string registry file name
     *
     * @access public
     */
    function _channelFileName($channel)
    {
        $this->_initializeDirs();
        if (!$channel) {
            $channel = 'pear.php.net';
        }
        if (file_exists($this->channelsdir . DIRECTORY_SEPARATOR . '.alias' .
              DIRECTORY_SEPARATOR . strtolower($channel) . '.txt')) {
            // translate an alias to an actual channel
            $channel = implode('', file($this->channelsdir . DIRECTORY_SEPARATOR . '.alias' .
                DIRECTORY_SEPARATOR . strtolower($channel) . '.txt'));
        }
        return $this->channelsdir . DIRECTORY_SEPARATOR . strtolower($channel) . '.reg';
    }

    // }}}
    // {{{ getChannelAliasFileName()

    /**
     * @param string
     * @return string
     */
    function _getChannelAliasFileName($alias)
    {
        return $this->channelsdir . DIRECTORY_SEPARATOR . '.alias' .
              DIRECTORY_SEPARATOR . strtolower($alias) . '.txt';
    }

    // }}}
    // {{{ _getChannelFromAlias()

    /**
     * Get the name of a channel from its alias
     */
    function _getChannelFromAlias($channel)
    {
        $this->_initializeDirs();
        if (!$this->_channelExists($channel)) {
            if ($channel == 'pear.php.net') {
                return 'pear.php.net';
            }
            return false;
        }
        $channel = strtolower($channel);
        if (file_exists($this->_getChannelAliasFileName($channel))) {
            // translate an alias to an actual channel
            return implode('', file($this->_getChannelAliasFileName($channel)));
        } else {
            return $channel;
        }
    }    
    // }}}
    // {{{ _channelDirectoryName()

    /**
     * Get the name of the file where data for a given package is stored.
     *
     * @param string channel name, or false if this is a PEAR package
     * @param string package name
     *
     * @return string registry file name
     *
     * @access public
     */
    function _channelDirectoryName($channel)
    {
        if (!$channel || $this->_getChannelFromAlias($channel) == 'pear.php.net') {
            return $this->statedir;
        } else {
            return $this->statedir . DIRECTORY_SEPARATOR . strtolower('.channel.' . $channel);
        }
    }

    // }}}
    // {{{ _openPackageFile()

    function _openPackageFile($package, $mode, $channel = false)
    {
        $this->_initializeDirs();
        $this->_assertStateDir($channel);
        $file = $this->_packageFileName($package, $channel);
        $fp = @fopen($file, $mode);
        if (!$fp) {
            return null;
        }
        return $fp;
    }

    // }}}
    // {{{ _closePackageFile()

    function _closePackageFile($fp)
    {
        fclose($fp);
    }

    // }}}
    // {{{ _openPackageFile()

    function _openChannelFile($channel, $mode)
    {
        $this->_initializeDirs();
        $this->_assertChannelDir();
        $file = $this->_channelFileName($channel);
        $fp = @fopen($file, $mode);
        if (!$fp) {
            return null;
        }
        return $fp;
    }

    // }}}
    // {{{ _closePackageFile()

    function _closeChannelFile($fp)
    {
        fclose($fp);
    }

    // }}}
    // {{{ rebuildFileMap()

    function rebuildFileMap()
    {
        $channels = $this->listAllPackages();
        $files = array();
        foreach ($channels as $channel => $packages) {
            foreach ($packages as $package) {
                $version = $this->packageInfo($package, 'version', $channel);
                $filelist = $this->packageInfo($package, 'filelist', $channel);
                if (!is_array($filelist)) {
                    continue;
                }
                foreach ($filelist as $name => $attrs) {
                    // it is possible for conflicting packages in different channels to
                    // conflict with data files/doc files
                    if ($name == 'dirtree') {
                        continue;
                    }
                    if (isset($attrs['role']) && !in_array($attrs['role'],
                          PEAR_Installer_Role::getInstallableRoles())) {
                        // these are not installed
                        continue;
                    }
                    if (isset($attrs['role']) && !in_array($attrs['role'],
                          PEAR_Installer_Role::getBaseinstallRoles())) {
                        $attrs['baseinstalldir'] = $package;
                    }
                    if (isset($attrs['baseinstalldir'])) {
                        $file = $attrs['baseinstalldir'].DIRECTORY_SEPARATOR.$name;
                    } else {
                        $file = $name;
                    }
                    $file = preg_replace(',^/+,', '', $file);
                    if ($channel != 'pear.php.net') {
                        $files[$attrs['role']][$file] = array(strtolower($channel), $package);
                    } else {
                        $files[$attrs['role']][$file] = $package;
                    }
                }
            }
        }
        $this->_assertStateDir();
        $fp = @fopen($this->filemap, 'wb');
        if (!$fp) {
            return false;
        }
        $this->filemap_cache = $files;
        fwrite($fp, serialize($files));
        fclose($fp);
        return true;
    }

    // }}}
    // {{{ readFileMap()

    function readFileMap()
    {
        $this->_initializeDirs();
        $fp = @fopen($this->filemap, 'r');
        if (!$fp) {
            return $this->raiseError('PEAR_Registry: could not open filemap', PEAR_REGISTRY_ERROR_FILE, null, null, $php_errormsg);
        }
        $fsize = filesize($this->filemap);
        $rt = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        $data = fread($fp, $fsize);
        set_magic_quotes_runtime($rt);
        fclose($fp);
        $tmp = unserialize($data);
        if (!$tmp && $fsize > 7) {
            return $this->raiseError('PEAR_Registry: invalid filemap data', PEAR_REGISTRY_ERROR_FORMAT, null, null, $data);
        }
        $this->filemap_cache = $tmp;
        return true;
    }

    // }}}
    // {{{ _lock()

    /**
     * Lock the registry.
     *
     * @param integer lock mode, one of LOCK_EX, LOCK_SH or LOCK_UN.
     *                See flock manual for more information.
     *
     * @return bool TRUE on success, FALSE if locking failed, or a
     *              PEAR error if some other error occurs (such as the
     *              lock file not being writable).
     *
     * @access private
     */
    function _lock($mode = LOCK_EX)
    {
        if (!eregi('Windows 9', php_uname())) {
            if ($mode != LOCK_UN && is_resource($this->lock_fp)) {
                // XXX does not check type of lock (LOCK_SH/LOCK_EX)
                return true;
            }
            if (PEAR::isError($err = $this->_assertStateDir())) {
                return $err;
            }
            $open_mode = 'w';
            // XXX People reported problems with LOCK_SH and 'w'
            if ($mode === LOCK_SH || $mode === LOCK_UN) {
                if (@!is_file($this->lockfile)) {
                    touch($this->lockfile);
                }
                $open_mode = 'r';
            }

            $this->lock_fp = @fopen($this->lockfile, $open_mode);

            if (!is_resource($this->lock_fp)) {
                return $this->raiseError("could not create lock file" .
                                         (isset($php_errormsg) ? ": " . $php_errormsg : ""));
            }
            if (!(int)flock($this->lock_fp, $mode)) {
                switch ($mode) {
                    case LOCK_SH: $str = 'shared';    break;
                    case LOCK_EX: $str = 'exclusive'; break;
                    case LOCK_UN: $str = 'unlock';    break;
                    default:      $str = 'unknown';   break;
                }
                return $this->raiseError("could not acquire $str lock ($this->lockfile)",
                                         PEAR_REGISTRY_ERROR_LOCK);
            }
        }
        return true;
    }

    // }}}
    // {{{ _unlock()

    function _unlock()
    {
        $ret = $this->_lock(LOCK_UN);
        $this->lock_fp = null;
        return $ret;
    }

    // }}}
    // {{{ _packageExists()

    function _packageExists($package, $channel = false)
    {
        return file_exists($this->_packageFileName($package, $channel));
    }

    // }}}
    // {{{ _channelExists()

    /**
     * Determine whether a channel exists in the registry
     * @param string Channel name
     * @return boolean
     */
    function _channelExists($channel)
    {
        $this->_initializeDirs();
        return file_exists($this->_getChannelAliasFileName($channel)) ||
            file_exists($this->_channelFileName($channel));
    }

    // }}}
    // {{{ _isChannelAlias()

    /**
     * Determine whether a channel exists in the registry
     * @param string Channel Alias
     * @return boolean
     */
    function _isChannelAlias($alias)
    {
        $this->_initializeDirs();
        return file_exists($this->_getChannelAliasFileName($alias));
    }

    // }}}
    // {{{ _packageInfo()

    function _packageInfo($package = null, $key = null, $channel = false)
    {
        if ($package === null) {
            if ($channel === null) {
                $channels = $this->_listChannels();
                $ret = array();
                foreach ($channels as $channel) {
                    $channel = strtolower($channel);
                    $ret[$channel] = array();
                    $packages = $this->_listPackages($channel);
                    foreach ($packages as $package) {
                        $ret[$channel][] = $this->_packageInfo($package, null, $channel);
                    }
                }
                return $ret;
            }
            return array_map(array(&$this, '_packageInfo'),
                             $this->_listPackages($channel));
        }
        $fp = $this->_openPackageFile($package, 'r', $channel);
        if ($fp === null) {
            return null;
        }
        $rt = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        $data = fread($fp, filesize($this->_packageFileName($package, $channel)));
        set_magic_quotes_runtime($rt);
        $this->_closePackageFile($fp);
        $data = unserialize($data);
        if ($key === null) {
            return $data;
        }
        // compatibility for package.xml version 2.0
        if (isset($data['old'][$key])) {
            return $data['old'][$key];
        }
        if (isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    // }}}
    // {{{ _channelInfo()

    /**
     * @param string Channel name
     * @return array|false
     */
    function _channelInfo($channel, $key = null)
    {
        $fp = $this->_openChannelFile($channel, 'r');
        if ($fp === null) {
            return null;
        }
        $rt = get_magic_quotes_runtime();
        set_magic_quotes_runtime(0);
        $data = fread($fp, filesize($this->_channelFileName($channel)));
        set_magic_quotes_runtime($rt);
        $this->_closeChannelFile($fp);
        $data = unserialize($data);
        if ($key === null) {
            return $data;
        }
        if (isset($data[$key])) {
            return $data[$key];
        }
        return null;
    }

    // }}}
    // {{{ _listChannels()

    function _listChannels()
    {
        $this->_initializeDirs();
        $channellist = array();
        $dp = @opendir($this->channelsdir);
        if (!$dp) {
            return array('pear.php.net');
        }
        while ($ent = readdir($dp)) {
            if ($ent{0} == '.' || substr($ent, -4) != '.reg') {
                continue;
            }
            $channellist[] = substr($ent, 0, -4);
        }
        closedir($dp);
        return $channellist;
    }

    // }}}
    // {{{ _listPackages()

    function _listPackages($channel = false)
    {
        if ($channel && $this->_getChannelFromAlias($channel) != 'pear.php.net') {
            return $this->_listChannelPackages($channel);
        }
        $this->_initializeDirs();
        $pkglist = array();
        $dp = @opendir($this->statedir);
        if (!$dp) {
            return $pkglist;
        }
        while ($ent = readdir($dp)) {
            if ($ent{0} == '.' || substr($ent, -4) != '.reg') {
                continue;
            }
            $pkglist[] = substr($ent, 0, -4);
        }
        closedir($dp);
        return $pkglist;
    }

    // }}}
    // {{{ _listChannelPackages()

    function _listChannelPackages($channel)
    {
        $this->_initializeDirs();
        $pkglist = array();
        $dp = @opendir($this->statedir . DIRECTORY_SEPARATOR . '.channel.' . strtolower($channel));
        if (!$dp) {
            return $pkglist;
        }
        while ($ent = readdir($dp)) {
            if ($ent{0} == '.' || substr($ent, -4) != '.reg') {
                continue;
            }
            $pkglist[] = substr($ent, 0, -4);
        }
        closedir($dp);
        return $pkglist;
    }

    // }}}
    
    function _listAllPackages()
    {
        $ret = array();
        foreach ($this->_listChannels() as $channel) {
            $ret[$channel] = $this->_listPackages($channel);
        }
        return $ret;
    }

    // {{{ packageExists()

    function packageExists($package, $channel = false)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_packageExists($package, $channel);
        $this->_unlock();
        return $ret;
    }

    // }}}

    // {{{ channelExists()

    function channelExists($channel)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_channelExists($channel);
        $this->_unlock();
        return $ret;
    }

    // }}}

    // {{{ isAlias()

    function isAlias($alias)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_isChannelAlias($alias);
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ packageInfo()

    function packageInfo($package = null, $key = null, $channel = false)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_packageInfo($package, $key, $channel);
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ packageInfo()

    function channelInfo($channel = null, $key = null)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_channelInfo($channel, $key);
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ packageInfo()

    function channelName($channel = null)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_getChannelFromAlias($channel);
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ allPackageInfo()

    function allPackageInfo()
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_packageInfo(null, null, null);
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ listPackages()

    function listPackages($channel = false)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_listPackages($channel);
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ listAllPackages()

    function listAllPackages()
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_listAllPackages();
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ listChannel()

    function listChannels()
    {
        if (PEAR::isError($e = $this->_lock(LOCK_SH))) {
            return $e;
        }
        $ret = $this->_listChannels();
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ addPackage()

    function addPackage($package, $info, $channel = false)
    {
        if (is_object($info)) {
            return $this->addPackage2($info);
        }
        if ($this->packageExists($package, $channel)) {
            return false;
        }
        if (!$this->channelExists($channel)) {
            return false;
        }
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $fp = $this->_openPackageFile($package, 'wb', $channel);
        if ($fp === null) {
            $this->_unlock();
            return false;
        }
        $info['_lastmodified'] = time();
        fwrite($fp, serialize($info));
        $this->_closePackageFile($fp);
        if (isset($info['filelist'])) {
            $this->rebuildFileMap();
        }
        $this->_unlock();
        return true;
    }

    // }}}
    // {{{ addPackage2()

    function addPackage2($info)
    {
        if (!is_object($info)) {
            return $this->addPackage($info['package'], $info);
        }
        $channel = $info->getChannel();
        $package = $info->getPackage();
        if ($this->packageExists($package, $channel)) {
            return false;
        }
        if (!$this->channelExists($channel)) {
            return false;
        }
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $fp = $this->_openPackageFile($package, 'wb', $channel);
        if ($fp === null) {
            $this->_unlock();
            return false;
        }
        $info = $info->toArray(true);
        $info['_lastmodified'] = time();
        fwrite($fp, serialize($info));
        $this->_closePackageFile($fp);
        if (isset($info['filelist'])) {
            $this->rebuildFileMap();
        }
        $this->_unlock();
        return true;
    }

    // }}}
    // {{{ updateChannel()

    /**
     * For future expandibility purposes, separate this
     * @param PEAR_ChannelFile
     */
    function updateChannel($channel)
    {
        return $this->addChannel($channel, true);
    }

    // }}}
    // {{{ deleteChannel()

    /**
     * Deletion fails if there are any packages installed from the channel
     * @param string|PEAR_ChannelFile channel name
     * @return boolean|PEAR_Error True on deletion, false if it doesn't exist
     */
    function deleteChannel($channel)
    {
        if (!is_string($channel)) {
            if (is_a($channel, 'PEAR_ChannelFile')) {
                if (!$channel->validate()) {
                    return false;
                }
                $channel = $channel->getName();
            } else {
                return false;
            }
        }
        if (!$channel || $this->_getChannelFromAlias($channel) == 'pear.php.net') {
            return false;
        }
        $channel = $this->_getChannelFromAlias($channel);
        if ($channel == 'pear.php.net') {
            return false;
        }
        if (!$this->channelExists($channel)) {
            return false;
        }
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $test = $this->_listChannelPackages($channel);
        if (count($test)) {
            $this->_unlock();
            return false;
        }
        $test = @rmdir($this->_channelDirectoryName($channel));
        if (!$test) {
            $this->_unlock();
            return false;
        }
        $file = $this->_channelFileName($channel);
        $ret = @unlink($file);
        $this->_unlock();
        return true;
    }

    // }}}
    // {{{ addChannel()

    /**
     * @param PEAR_ChannelFile Channel object
     * @return boolean|PEAR_Error True on creation, false if it already exists
     */
    function addChannel($channel, $update = false)
    {
        if (!is_a($channel, 'PEAR_ChannelFile')) {
            return false;
        }
        if (!$channel->validate()) {
            return false;
        }
        if ($this->channelExists($channel->getName())) {
            if (!$update) {
                return false;
            }
        } else {
            if ($update) {
                return false;
            }
        }
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $ret = $this->_assertChannelDir();
        if (PEAR::isError($ret)) {
            $this->_unlock();
            return $ret;
        }
        $ret = $this->_assertChannelStateDir($channel->getName());
        if (PEAR::isError($ret)) {
            $this->_unlock();
            return $ret;
        }
        if ($channel->getAlias() != $channel->getName()) {
            if (file_exists($this->_getChannelAliasFileName($channel->getAlias())) &&
                  $this->channelName($channel->getAlias()) != $channel->getName()) {
                $channel->setAlias($channel->getName());
            }
            $fp = @fopen($this->_getChannelAliasFileName($channel->getAlias()), 'w');
            if (!$fp) {
                $this->_unlock();
                return false;
            }
            fwrite($fp, $channel->getName());
            fclose($fp);
        }
        $fp = @fopen($this->_channelFileName($channel->getName()), 'wb');
        if (!$fp) {
            $this->_unlock();
            return false;
        }
        $info = $channel->toArray();
        $info['_lastmodified'] = time();
        fwrite($fp, serialize($info));
        fclose($fp);
        $this->_unlock();
        return true;
    }

    // }}}
    // {{{ deletePackage()

    function deletePackage($package, $channel = false)
    {
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $file = $this->_packageFileName($package, $channel);
        $ret = @unlink($file);
        $this->rebuildFileMap();
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ updatePackage()

    function updatePackage($package, $info, $merge = true, $channel = false)
    {
        if (is_object($info)) {
            return $this->updatePackage2($info, $merge);
        }
        $oldinfo = $this->packageInfo($package, null, $channel);
        if (empty($oldinfo)) {
            return false;
        }
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $fp = $this->_openPackageFile($package, 'w', $channel);
        if ($fp === null) {
            $this->_unlock();
            return false;
        }
        if (is_object($info)) {
            $info = $info->toArray();
        }
        $info['_lastmodified'] = time();
        $newinfo = $info;
        if ($merge) {
            $info = array_merge($oldinfo, $info);
        } else {
            $diff = $info;
        }
        if (isset($newinfo['filelist'])) {
            $diff = array_diff(array_keys($info['filelist']), array_keys($oldinfo['filelist']));
            if (count($diff)) {
                $test = array();
                foreach ($diff as $key) {
                    $test[$key] = $info['filelist'][$key];
                }
                if ($conflictarr = $this->checkFileMap($test, $package)) {
                $text = array();
                foreach ($conflictarr as $item) {
                    if (is_array($item)) {
                        $text[] = $item[1] . '::' . $item[0];
                    } else {
                        $text[] = "pear::$item";
                    }
                }
                if (!$channel) {
                    $channel = 'pear.php.net';
                }
                require_once 'PEAR/ErrorStack.php';
                $text = implode(', ', array_unique($text));
                    PEAR_ErrorStack::staticPush('PEAR_Registry', PEAR_REGISTRY_ERROR_CONFLICT,
                        'error', array('package' => $package, 'conflicts' => $conflictarr),
                        "package $channel/$package has files that conflict with installed packages $text");
                    $this->_closePackageFile($fp);
                    return false;
                }
            }
        }
        fwrite($fp, serialize($info));
        $this->_closePackageFile($fp);
        if (isset($newinfo['filelist'])) {
            $this->rebuildFileMap();
        }
        $this->_unlock();
        return true;
    }

    // }}}
    // {{{ updatePackage2()

    function updatePackage2($info, $merge = true)
    {
        if (!is_object($info)) {
            return $this->updatePackage($info['package'], $info, $merge);
        }
        $package = $info->getPackage();
        $channel = $info->getChannel();
        $oldinfo = $this->packageInfo($package, null, $channel);
        if (empty($oldinfo)) {
            return false;
        }
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        $fp = $this->_openPackageFile($package, 'w', $channel);
        if ($fp === null) {
            $this->_unlock();
            return false;
        }
        if (is_object($info)) {
            $info = $info->toArray(true);
        }
        $info['_lastmodified'] = time();
        $newinfo = $info;
        if ($merge) {
            $info = array_merge($oldinfo, $info);
        } else {
            $diff = $info;
        }
        if (isset($newinfo['filelist'])) {
            $diff = array_diff(array_keys($info['filelist']), array_keys($oldinfo['filelist']));
            if (count($diff)) {
                $test = array();
                foreach ($diff as $key) {
                    $test[$key] = $info['filelist'][$key];
                }
                if ($conflictarr = $this->checkFileMap($test, $package)) {
                $text = array();
                foreach ($conflictarr as $item) {
                    if (is_array($item)) {
                        $text[] = $item[1] . '/' . $item[0];
                    } else {
                        $text[] = "pear.php.net/$item";
                    }
                }
                require_once 'PEAR/ErrorStack.php';
                $text = implode(', ', array_unique($text));
                    PEAR_ErrorStack::staticPush('PEAR_Registry', PEAR_REGISTRY_ERROR_CONFLICT,
                        'error', array('package' => $package, 'conflicts' => $conflictarr),
                        "package $channel/$package has files that conflict with installed packages $text");
                    $this->_closePackageFile($fp);
                    return false;
                }
            }
        }
        fwrite($fp, serialize($info));
        $this->_closePackageFile($fp);
        if (isset($newinfo['filelist'])) {
            $this->rebuildFileMap();
        }
        $this->_unlock();
        return true;
    }

    // }}}
    // {{{ getChannel()
    /**
     * @param string channel name
     * @return PEAR_ChannelFile|false
     */
    function getChannel($channel)
    {
        if (!class_exists('PEAR_ChannelFile')) {
            include_once 'PEAR/ChannelFile.php';
        }
        $ch = &PEAR_ChannelFile::fromArray($this->_channelInfo($channel));
        if ($ch) {
            return $ch;
        }
        if ($this->_getChannelFromAlias($channel) == 'pear.php.net') {
            // the registry is not properly set up, so use defaults
            $pear_channel = new PEAR_ChannelFile;
            $pear_channel->setName('pear.php.net');
            $pear_channel->setAlias('pear');
            $pear_channel->setServer('pear.php.net');
            $pear_channel->setSummary('PHP Extension and Application Repository');
            $pear_channel->setDefaultPEARProtocols();
            return $pear_channel;
        }
        return false;
    }

    // }}}
    // {{{ getPackage()
    /**
     * @param string package name
     * @param string channel name
     * @return PEAR_PackageFile_v1|PEAR_PackageFile_v2|null
     */
    function &getPackage($package, $channel = 'pear.php.net')
    {
        if (!class_exists('PEAR_PackageFile')) {
            include_once 'PEAR/PackageFile.php';
        }
        $info = $this->_packageInfo($package, null, $channel);
        if ($info === null) {
            return $info;
        }
        $pf = &PEAR_PackageFile::fromArray($info);
        return $pf;
    }

    // }}}
    // {{{ getChannelValidator()
    /**
     * @param string channel name
     * @return PEAR_Validate|false
     */
    function &getChannelValidator($channel)
    {
        $chan = $this->getChannel($channel);
        if (!$chan) {
            return $chan;
        }
        $val = $chan->getValidationObject();
        return $val;
    }
    // }}}
    // {{{ getChannels()
    /**
     * @param string channel name
     * @return PEAR_ChannelFile|false
     */
    function getChannels()
    {
        $ret = array();
        if (PEAR::isError($e = $this->_lock(LOCK_EX))) {
            return $e;
        }
        foreach ($this->listChannels() as $channel) {
            $ret[] = $this->getChannel($channel);
        }
        $this->_unlock();
        return $ret;
    }

    // }}}
    // {{{ checkFileMap()

    /**
     * Test whether a file belongs to a package.
     *
     * @param string|array $path file path, absolute or relative to the pear
     * install dir
     * @param string name of package that is not installed
     *
     * @return array|false which package and channel the file belongs to, or an empty
     * string if the file does not belong to an installed package
     *
     * @access public
     */
    function checkFileMap($path, $package, $attrs = false)
    {
        if (is_array($path)) {
            static $notempty;
            if (empty($notempty)) {
                $notempty = create_function('$a','return !empty($a);');
            }
            $pkgs = array();
            foreach ($path as $name => $attrs) {
                if (is_array($attrs)) {
                    if (!in_array($attrs['role'], PEAR_Installer_Role::getInstallableRoles())) {
                        // these are not installed
                        continue;
                    }
                    if (!in_array($attrs['role'], PEAR_Installer_Role::getBaseinstallRoles())) {
                        $attrs['baseinstalldir'] = $package;
                    }
                    if (isset($attrs['baseinstalldir'])) {
                        $name = $attrs['baseinstalldir'].DIRECTORY_SEPARATOR.$name;
                    }
                }
                $pkgs[$name] = $this->checkFileMap($name, $package, $attrs);
            }
            return array_filter($pkgs, $notempty);
        }
        if (empty($this->filemap_cache) && PEAR::isError($err = $this->readFileMap())) {
            return $err;
        }
        if (!$attrs) {
            $attrs = array('role' => 'php'); // any old call would be for PHP role only
        }
        if (isset($this->filemap_cache[$attrs['role']][$path])) {
            return $this->filemap_cache[$attrs['role']][$path];
        }
        $l = strlen($this->install_dir);
        if (substr($path, 0, $l) == $this->install_dir) {
            $path = preg_replace('!^'.DIRECTORY_SEPARATOR.'+!', '', substr($path, $l));
        }
        if (isset($this->filemap_cache[$attrs['role']][$path])) {
            return $this->filemap_cache[$attrs['role']][$path];
        }
        return false;
    }

    // }}}
    // {{{ apiVersion()
    /**
     * Get the expected API version.  Channels API is version 1.1, as it is backwards
     * compatible with 1.0
     * @return string
     */
    function apiVersion()
    {
        return '1.1';
    }
    // }}}


    /**
     * Parse a package name, or validate a parsed package name array
     * @param string|array pass in an array of format
     *                     array(
     *                      'package' => 'pname',
     *                     ['channel' => 'channame',]
     *                     ['version' => 'version',]
     *                     ['state' => 'state',]
     *                     ['group' => 'groupname'])
     *                     or a string of format
     *                     [channel://][channame/]pname[-version|-state][/group=groupname]
     * @return array|PEAR_Error
     */
    function parsePackageName($param, $defaultchannel = 'pear.php.net')
    {
        $saveparam = $param;
        if (is_array($param)) {
            // convert to string for error messages
            $saveparam = '';
            if (isset($param['channel'])) {
                $saveparam = $param['channel'] . '/';
            }
            $saveparam .= $param['package'];
            if (isset($param['state']) || isset($param['version'])) {
                $saveparam .= '-'. (isset($param['state']) ? $param['state'] :
                    $param['version']);
            }
            if (isset($param['group'])) {
                $saveparam .= '#' . $param['group'];
            }
            // process the array
            if (!isset($param['package'])) {
                return PEAR::raiseError('parsePackageName(): array $param ' .
                    'must contain a valid package name in index "param"',
                    'package', null, null, $param);
            }
            if (!isset($param['channel'])) {
                $param['channel'] = $defaultchannel;
            }
        } else {
            $components = parse_url($param);
            if (isset($components['scheme']) && $components['scheme'] != 'channel') {
                return PEAR::raiseError('parsePackageName(): only channel:// uris may ' .
                    'be downloaded, not "' . $param . '"', 'invalid', null, null, $param);
            }
            if (!isset($components['path'])) {
                return PEAR::raiseError('parsePackageName(): array $param ' .
                    'must contain a valid package name in "' . $param . '"',
                    'package', null, null, $param);
            }
            if (isset($components['host'])) {
                // remove the leading "/"
                $components['path'] = substr($components['path'], 1);
            }
            if (!isset($components['scheme'])) {
                if (strpos($components['path'], '/')) {
                    $parts = explode('/', $components['path']);
                    $components['host'] = array_shift($parts);
                    $components['path'] = implode('/', $parts);
                } else {
                    $components['host'] = $defaultchannel;
                }
            }
            $param = array(
                'package' => $components['path']
                );
            if (isset($components['host'])) {
                $param['channel'] = $components['host'];
            }
            if (isset($components['user'])) {
                $param['user'] = $components['user'];
            }
            if (isset($components['pass'])) {
                $param['pass'] = $components['pass'];
            }
            if (isset($components['query'])) {
                parse_str($components['query'], $param['opts']);
            }
            // check for extension
            $pathinfo = pathinfo($param['package']);
            if (isset($pathinfo['extension']) && $pathinfo['extension']) {
                $param['extension'] = $pathinfo['extension'];
                $param['package'] = $pathinfo['basename'];
            }
            // check for version
            if (strpos($param['package'], '-')) {
                $test = explode('-', $param['package']);
                if (count($test) != 2) {
                    return PEAR::raiseError('parsePackageName(): only one version/state ' .
                        'delimiter "-" is allowed in "' . $saveparam . '"',
                        'version', null, null, $param);
                }
                list($param['package'], $param['version']) = $test;
            }
        }
        // validation
        if (!$this->channelExists($param['channel'])) {
            return PEAR::raiseError('unknown channel "' . $param['channel'] .
                '" in "' . $saveparam . '"', 'channel', null, null, $param);
        }
        $chan = $this->getChannel($param['channel']);
        if (!$chan) {
            return PEAR::raiseError("Exception: corrupt registry, could not " .
                "retrieve channel " . $param['channel'] . " information",
                'registry', null, null, $param);
        }
        $validate = $chan->getValidationObject();
        // validate package name
        if (!$validate->validPackageName($param['package'])) {
            return PEAR::raiseError('parsePackageName(): invalid package name "' .
                $param['package'] . '" in "' . $saveparam . '"',
                'package', null, null, $param);
        }
        if (isset($param['state'])) {
            if (!in_array(strtolower($param['state']), $validate->getValidStates())) {
                return PEAR::raiseError('parsePackageName(): state "' . $param['state']
                    . '" is not a valid state in "' . $saveparam . '"',
                    'state', null, null, $param);
            }
        }
        if (isset($param['version'])) {
            if (isset($param['state'])) {
                return PEAR::raiseError('parsePackageName(): cannot contain both ' .
                    'a version and a stability (state)',
                    'version/state', null, null, $param);
            }
            // check whether version is actually a state
            if (in_array(strtolower($param['version']), $validate->getValidStates())) {
                $param['state'] = strtolower($param['version']);
                unset($param['version']);
            } else {
                if (!$validate->validVersion($param['version'])) {
                    return PEAR::raiseError('parsePackageName(): content after ' .
                        'version/state delimiter "-" "' . $param['version'] .
                        '" is neither a valid version nor a valid state in "' .
                        $saveparam . '"', 'version/state', null, null, $param);
                }                    
            }
        }
        return $param;
    }

    function parsedPackageNameToString($parsed)
    {
        if (is_object($parsed)) {
            $p = $parsed;
            $parsed = array(
                'package' => $p->getPackage(),
                'channel' => $p->getChannel(),
                'version' => $p->getVersion(),
            );
        }
        $upass = '';
        if (isset($parsed['user'])) {
            $upass = $parsed['user'];
            if (isset($parsed['pass'])) {
                $upass .= ':' . $parsed['pass'];
            }
            $upass = "$upass@";
        }
        $ret = 'channel://' . $upass . $parsed['channel'] . '/' . $parsed['package'];
        if (isset($parsed['version']) || isset($parsed['state'])) {
            $ret .= '-' . @$parsed['version'] . @$parsed['state'];
        }
        if (isset($parsed['extension'])) {
            $ret .= '.' . $parsed['extension'];
        }
        if (isset($parsed['opts'])) {
            $ret .= '?';
            foreach ($parsed['opts'] as $name => $value) {
                $parsed['opts'][$name] = "$name=$value";
            }
            $ret .= implode('&', $parsed['opts']);
        }
        return $ret;
    }
}

?>
