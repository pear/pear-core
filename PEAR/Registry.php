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
require_once "System.php";
require_once "PEAR.php";

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
              !file_exists($this->channelsdir . $ds . 'pear.reg')) {
            if (!is_a($pear_channel, 'PEAR_ChannelFile') || !$pear_channel->validate()) {
                include_once 'PEAR/ChannelFile.php';
                $pear_channel = new PEAR_ChannelFile;
                $pear_channel->setName('pear');
                $pear_channel->setServer('pear.php.net');
                $pear_channel->setSummary('PHP Extension and Application Repository');
                $pear_channel->setDefaultPEARProtocols();
            } else {
                $pear_channel->setName('pear');
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
        if ($channel && strtolower($channel) != 'pear') {
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
        if (!$channel || $channel == 'pear') {
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
        if ($channel && strtolower($channel) != 'pear') {
            $package = '.channel.' . strtolower($channel) . DIRECTORY_SEPARATOR . $package;
        }
        return $this->statedir . DIRECTORY_SEPARATOR . strtolower($package) . '.reg';
    }

    // }}}
    // {{{ _packageFileName()

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
        if (!$channel) {
            $channel = 'pear';
        }
        return $this->channelsdir . DIRECTORY_SEPARATOR . strtolower($channel) . '.reg';
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
        if (!$channel || strtolower($channel) == 'pear') {
            return $this->statedir;
        } else {
            return $this->statedir . DIRECTORY_SEPARATOR . strtolower('.channel.' . $channel);
        }
    }

    // }}}
    // {{{ _openPackageFile()

    function _openPackageFile($package, $mode, $channel = false)
    {
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
                    if (isset($attrs['role']) && in_array($attrs['role'], array('src', 'extsrc'))) {
                        // these are not installed
                        continue;
                    }
                    if (isset($attrs['role']) && in_array($attrs['role'], array('doc', 'data', 'test'))) {
                        $attrs['baseinstalldir'] = $package;
                    }
                    if (isset($attrs['baseinstalldir'])) {
                        $file = $attrs['baseinstalldir'].DIRECTORY_SEPARATOR.$name;
                    } else {
                        $file = $name;
                    }
                    $file = preg_replace(',^/+,', '', $file);
                    if ($channel != 'pear') {
                        $files[$file] = array(strtolower($channel), $package);
                    } else {
                        $files[$file] = $package;
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
        return file_exists($this->_channelFileName($channel));
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
        $channellist = array();
        $dp = @opendir($this->channelsdir);
        if (!$dp) {
            return array('pear');
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
        if ($channel && strtolower($channel) != 'pear') {
            return $this->_listChannelPackages($channel);
        }
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
        $info = $info->getDefaultGenerator();
        $info = $info->toArray();
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
        if (!$channel) {
            $channel = 'pear';
        }
        $channel = strtolower($channel);
        if ($channel == 'pear') {
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
            $info = $info->getDefaultGenerator();
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
                    $channel = 'pear';
                }
                require_once 'PEAR/ErrorStack.php';
                $text = implode(', ', array_unique($text));
                    PEAR_ErrorStack::staticPush('PEAR_Registry', PEAR_REGISTRY_ERROR_CONFLICT,
                        'error', array('package' => $package, 'conflicts' => $conflictarr),
                        "package $channel::$package has files that conflict with installed packages $text");
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
            $info = $info->getDefaultGenerator();
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
                require_once 'PEAR/ErrorStack.php';
                $text = implode(', ', array_unique($text));
                    PEAR_ErrorStack::staticPush('PEAR_Registry', PEAR_REGISTRY_ERROR_CONFLICT,
                        'error', array('package' => $package, 'conflicts' => $conflictarr),
                        "package $channel::$package has files that conflict with installed packages $text");
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
        if (strtolower($channel) == 'pear') {
            // the registry is not properly set up, so use defaults
            $pear_channel = new PEAR_ChannelFile;
            $pear_channel->setName('pear');
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
    function &getPackage($package, $channel = 'pear')
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
    function checkFileMap($path, $package)
    {
        if (is_array($path)) {
            static $notempty;
            if (empty($notempty)) {
                $notempty = create_function('$a','return !empty($a);');
            }
            $pkgs = array();
            foreach ($path as $name => $attrs) {
                if (is_array($attrs)) {
                    if (in_array($attrs['role'], array('src', 'extsrc'))) {
                        // these are not installed
                        continue;
                    }
                    if (in_array($attrs['role'], array('doc', 'data', 'test'))) {
                        $attrs['baseinstalldir'] = $package;
                    }
                    if (isset($attrs['baseinstalldir'])) {
                        $name = $attrs['baseinstalldir'].DIRECTORY_SEPARATOR.$name;
                    }
                }
                $pkgs[$name] = $this->checkFileMap($name, $package);
            }
            return array_filter($pkgs, $notempty);
        }
        if (empty($this->filemap_cache) && PEAR::isError($err = $this->readFileMap())) {
            return $err;
        }
        if (isset($this->filemap_cache[$path])) {
            return $this->filemap_cache[$path];
        }
        $l = strlen($this->install_dir);
        if (substr($path, 0, $l) == $this->install_dir) {
            $path = preg_replace('!^'.DIRECTORY_SEPARATOR.'+!', '', substr($path, $l));
        }
        if (isset($this->filemap_cache[$path])) {
            return $this->filemap_cache[$path];
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

}

?>
