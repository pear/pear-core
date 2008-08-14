<?php
/**
 * PEAR_Common, the base class for the PEAR Installer
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V. V. Cox <cox@idecnet.com>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2008 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 0.1.0
 * @deprecated File deprecated since Release 1.4.0a1
 */

/**
 * Include error handling
 */
require_once 'PEAR.php';

/**
 * PEAR_Common error when an invalid PHP file is passed to PEAR_Common::analyzeSourceCode()
 */
define('PEAR_COMMON_ERROR_INVALIDPHP', 1);
define('_PEAR_COMMON_PACKAGE_NAME_PREG', '[A-Za-z][a-zA-Z0-9_]+');
define('PEAR_COMMON_PACKAGE_NAME_PREG', '/^' . _PEAR_COMMON_PACKAGE_NAME_PREG . '\\z/');

// this should allow: 1, 1.0, 1.0RC1, 1.0dev, 1.0dev123234234234, 1.0a1, 1.0b1, 1.0pl1
define('_PEAR_COMMON_PACKAGE_VERSION_PREG', '\d+(?:\.\d+)*(?:[a-zA-Z]+\d*)?');
define('PEAR_COMMON_PACKAGE_VERSION_PREG', '/^' . _PEAR_COMMON_PACKAGE_VERSION_PREG . '\\z/i');

// XXX far from perfect :-)
define('_PEAR_COMMON_PACKAGE_DOWNLOAD_PREG', '(' . _PEAR_COMMON_PACKAGE_NAME_PREG .
    ')(-([.0-9a-zA-Z]+))?');
define('PEAR_COMMON_PACKAGE_DOWNLOAD_PREG', '/^' . _PEAR_COMMON_PACKAGE_DOWNLOAD_PREG .
    '\\z/');

define('_PEAR_CHANNELS_NAME_PREG', '[A-Za-z][a-zA-Z0-9\.]+');
define('PEAR_CHANNELS_NAME_PREG', '/^' . _PEAR_CHANNELS_NAME_PREG . '\\z/');

// this should allow any dns or IP address, plus a path - NO UNDERSCORES ALLOWED
define('_PEAR_CHANNELS_SERVER_PREG', '[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*(\/[a-zA-Z0-9\-]+)*');
define('PEAR_CHANNELS_SERVER_PREG', '/^' . _PEAR_CHANNELS_SERVER_PREG . '\\z/i');

define('_PEAR_CHANNELS_PACKAGE_PREG',  '(' ._PEAR_CHANNELS_SERVER_PREG . ')\/('
         . _PEAR_COMMON_PACKAGE_NAME_PREG . ')');
define('PEAR_CHANNELS_PACKAGE_PREG', '/^' . _PEAR_CHANNELS_PACKAGE_PREG . '\\z/i');

define('_PEAR_COMMON_CHANNEL_DOWNLOAD_PREG', '(' . _PEAR_CHANNELS_NAME_PREG . ')::('
    . _PEAR_COMMON_PACKAGE_NAME_PREG . ')(-([.0-9a-zA-Z]+))?');
define('PEAR_COMMON_CHANNEL_DOWNLOAD_PREG', '/^' . _PEAR_COMMON_CHANNEL_DOWNLOAD_PREG . '\\z/');

/**
 * List of temporary files and directories registered by
 * PEAR_Common::addTempFile().
 * @var array
 */
$GLOBALS['_PEAR_Common_tempfiles'] = array();

/**
 * Valid maintainer roles
 * @var array
 */
$GLOBALS['_PEAR_Common_maintainer_roles'] = array('lead','developer','contributor','helper');

/**
 * Valid release states
 * @var array
 */
$GLOBALS['_PEAR_Common_release_states'] = array('alpha','beta','stable','snapshot','devel');

/**
 * Valid dependency types
 * @var array
 */
$GLOBALS['_PEAR_Common_dependency_types'] = array('pkg','ext','php','prog','ldlib','rtlib','os','websrv','sapi');

/**
 * Valid dependency relations
 * @var array
 */
$GLOBALS['_PEAR_Common_dependency_relations'] = array('has','eq','lt','le','gt','ge','not', 'ne');

/**
 * Valid file roles
 * @var array
 */
$GLOBALS['_PEAR_Common_file_roles'] = array('php','ext','test','doc','data','src','script');

/**
 * Valid replacement types
 * @var array
 */
$GLOBALS['_PEAR_Common_replacement_types'] = array('php-const', 'pear-config', 'package-info');

/**
 * Valid "provide" types
 * @var array
 */
$GLOBALS['_PEAR_Common_provide_types'] = array('ext', 'prog', 'class', 'function', 'feature', 'api');

/**
 * Valid "provide" types
 * @var array
 */
$GLOBALS['_PEAR_Common_script_phases'] = array('pre-install', 'post-install', 'pre-uninstall', 'post-uninstall', 'pre-build', 'post-build', 'pre-configure', 'post-configure', 'pre-setup', 'post-setup');

/**
 * Class providing common functionality for PEAR administration classes.
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V. V. Cox <cox@idecnet.com>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2008 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 1.4.0a1
 * @deprecated This class will disappear, and its components will be spread
 *             into smaller classes, like the AT&T breakup, as of Release 1.4.0a1
 */
class PEAR_Common extends PEAR
{
    /**
     * User Interface object (PEAR_Frontend_* class).  If null,
     * the log() method uses print.
     * @var object
     */
    var $ui = null;

    /**
     * Configuration object (PEAR_Config).
     * @var PEAR_Config
     */
    var $config = null;

    /**
     * PEAR_Common constructor
     *
     * @access public
     */
    function PEAR_Common()
    {
        parent::PEAR();
        $this->config = &PEAR_Config::singleton();
        $this->debug = $this->config->get('verbose');
    }

    /**
     * PEAR_Common destructor
     *
     * @access private
     */
    function _PEAR_Common()
    {
        // doesn't work due to bug #14744
        //$tempfiles = $this->_tempfiles;
        $tempfiles =& $GLOBALS['_PEAR_Common_tempfiles'];
        while ($file = array_shift($tempfiles)) {
            if (@is_dir($file)) {
                if (!class_exists('System')) {
                    require_once 'System.php';
                }
                System::rm(array('-rf', $file));
            } elseif (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Register a temporary file or directory.  When the destructor is
     * executed, all registered temporary files and directories are
     * removed.
     *
     * @param string  $file  name of file or directory
     *
     * @return void
     *
     * @access public
     */
    function addTempFile($file)
    {
        if (!class_exists('PEAR_Frontend')) {
            require_once 'PEAR/Frontend.php';
        }
        PEAR_Frontend::addTempFile($file);
    }

    /**
     * Wrapper to System::mkDir(), creates a directory as well as
     * any necessary parent directories.
     *
     * @param string  $dir  directory name
     *
     * @return bool TRUE on success, or a PEAR error
     *
     * @access public
     */
    function mkDirHier($dir)
    {
        $this->log(2, "+ create dir $dir");
        if (!class_exists('System')) {
            require_once 'System.php';
        }
        return System::mkDir(array('-p', $dir));
    }

    /**
     * Logging method.
     *
     * @param int    $level  log level (0 is quiet, higher is noisier)
     * @param string $msg    message to write to the log
     *
     * @return void
     *
     * @access public
     * @static
     */
    function log($level, $msg, $append_crlf = true)
    {
        if ($this->debug >= $level) {
            if (!class_exists('PEAR_Frontend')) {
                require_once 'PEAR/Frontend.php';
            }
            $ui = &PEAR_Frontend::singleton();
            if (is_a($ui, 'PEAR_Frontend')) {
                $ui->log($msg, $append_crlf);
            } else {
                print "$msg\n";
            }
        }
    }

    /**
     * Create and register a temporary directory.
     *
     * @param string $tmpdir (optional) Directory to use as tmpdir.
     *                       Will use system defaults (for example
     *                       /tmp or c:\windows\temp) if not specified
     *
     * @return string name of created directory
     *
     * @access public
     */
    function mkTempDir($tmpdir = '')
    {
        if ($tmpdir) {
            $topt = array('-t', $tmpdir);
        } else {
            $topt = array();
        }
        $topt = array_merge($topt, array('-d', 'pear'));
        if (!class_exists('System')) {
            require_once 'System.php';
        }
        if (!$tmpdir = System::mktemp($topt)) {
            return false;
        }
        $this->addTempFile($tmpdir);
        return $tmpdir;
    }

    /**
     * Set object that represents the frontend to be used.
     *
     * @param  object Reference of the frontend object
     * @return void
     * @access public
     */
    function setFrontendObject(&$ui)
    {
        $this->ui = &$ui;
    }

    /**
     * Return an array containing all of the states that are more stable than
     * or equal to the passed in state
     *
     * @param string Release state
     * @param boolean Determines whether to include $state in the list
     * @return false|array False if $state is not a valid release state
     */
    function betterStates($state, $include = false)
    {
        static $states = array('snapshot', 'devel', 'alpha', 'beta', 'stable');
        $i = array_search($state, $states);
        if ($i === false) {
            return false;
        }
        if ($include) {
            $i--;
        }
        return array_slice($states, $i + 1);
    }

    /**
     * Get the valid roles for a PEAR package maintainer
     *
     * @return array
     * @static
     */
    function getUserRoles()
    {
        return $GLOBALS['_PEAR_Common_maintainer_roles'];
    }

    /**
     * Get the valid package release states of packages
     *
     * @return array
     * @static
     */
    function getReleaseStates()
    {
        return $GLOBALS['_PEAR_Common_release_states'];
    }

    /**
     * Get the implemented dependency types (php, ext, pkg etc.)
     *
     * @return array
     * @static
     */
    function getDependencyTypes()
    {
        return $GLOBALS['_PEAR_Common_dependency_types'];
    }

    /**
     * Get the implemented dependency relations (has, lt, ge etc.)
     *
     * @return array
     * @static
     */
    function getDependencyRelations()
    {
        return $GLOBALS['_PEAR_Common_dependency_relations'];
    }

    /**
     * Get the implemented file roles
     *
     * @return array
     * @static
     */
    function getFileRoles()
    {
        return $GLOBALS['_PEAR_Common_file_roles'];
    }

    /**
     * Get the implemented file replacement types in
     *
     * @return array
     * @static
     */
    function getReplacementTypes()
    {
        return $GLOBALS['_PEAR_Common_replacement_types'];
    }

    /**
     * Get the implemented file replacement types in
     *
     * @return array
     * @static
     */
    function getProvideTypes()
    {
        return $GLOBALS['_PEAR_Common_provide_types'];
    }

    /**
     * Get the implemented file replacement types in
     *
     * @return array
     * @static
     */
    function getScriptPhases()
    {
        return $GLOBALS['_PEAR_Common_script_phases'];
    }

    /**
     * Test whether a string contains a valid package name.
     *
     * @param string $name the package name to test
     *
     * @return bool
     *
     * @access public
     */
    function validPackageName($name)
    {
        return (bool)preg_match(PEAR_COMMON_PACKAGE_NAME_PREG, $name);
    }

    /**
     * Test whether a string contains a valid package version.
     *
     * @param string $ver the package version to test
     *
     * @return bool
     *
     * @access public
     */
    function validPackageVersion($ver)
    {
        return (bool)preg_match(PEAR_COMMON_PACKAGE_VERSION_PREG, $ver);
    }

    /**
     * @param string $path relative or absolute include path
     * @return boolean
     * @static
     */
    function isIncludeable($path)
    {
        if (file_exists($path) && is_readable($path)) {
            return true;
        }
        $ipath = explode(PATH_SEPARATOR, ini_get('include_path'));
        foreach ($ipath as $include) {
            $test = realpath($include . DIRECTORY_SEPARATOR . $path);
            if (file_exists($test) && is_readable($test)) {
                return true;
            }
        }
        return false;
    }
}

require_once 'PEAR/Config.php';
require_once 'PEAR/PackageFile.php';