<?php
require_once 'PEAR/Installer/Role/Common.php';
//$GLOBALS['_PEAR_INSTALLER_ROLES'] = array();
/**
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Installer_Role
{
    /**
     * Set up any additional configuration variables that file roles require
     *
     * Never call this directly, it is called by the PEAR_Config constructor
     * @param PEAR_Config
     * @access private
     * @static
     */
    function initializeConfig(&$config)
    {
        if (!isset($GLOBALS['_PEAR_INSTALLER_ROLES'])) {
            PEAR_Installer_Role::registerRoles();
        }
        foreach ($GLOBALS['_PEAR_INSTALLER_ROLES'] as $class => $unused) {
            $config->_addConfigVars($class);
        }
    }

    /**
     * @param PEAR_PackageFile_v2
     * @param string role name
     * @param PEAR_Config
     * @return PEAR_Installer_Role_Common
     * @static
     */
    function &factory($pkg, $role, &$config)
    {
        if (!isset($GLOBALS['_PEAR_INSTALLER_ROLES'])) {
            PEAR_Installer_Role::registerRoles();
        }
        if (!in_array($role, PEAR_Installer_Role::getValidRoles($pkg->getPackageType()))) {
            $a = false;
            return $a;
        }
        $a = "PEAR_Installer_Role_$role";
        $b = new $a($config);
        return $b;
    }

    /**
     * Get a list of file roles that are valid for the particular release type.
     *
     * For instance, src files serve no purpose in regular php releases.  php files
     * serve no purpose in extsrc or extbin releases
     * @param string
     * @return array
     * @static
     */
    function getValidRoles($release)
    {
        if (!isset($GLOBALS['_PEAR_INSTALLER_ROLES'])) {
            PEAR_Installer_Role::registerRoles();
        }
        static $ret = array();
        if (isset($ret[$release])) {
            return $ret[$release];
        }
        $ret[$release] = array();
        foreach ($GLOBALS['_PEAR_INSTALLER_ROLES'] as $role => $okreleases) {
            if (in_array($release, $okreleases['releasetypes'])) {
                $ret[$release][] = strtolower(str_replace('PEAR_Installer_Role_', '', $role));
            }
        }
        return $ret[$release];
    }

    /**
     * Get a list of roles that require their files to be installed
     *
     * Most roles must be installed, but src and package roles, for instance
     * are pseudo-roles.  src files are compiled into a new extension.  Package
     * roles are actually fully bundled releases of a package
     * @return array
     * @static
     */
    function getInstallableRoles()
    {
        if (!isset($GLOBALS['_PEAR_INSTALLER_ROLES'])) {
            PEAR_Installer_Role::registerRoles();
        }
        static $ret;
        if (!isset($ret)) {
            foreach ($GLOBALS['_PEAR_INSTALLER_ROLES'] as $role => $okreleases) {
                if ($okreleases['installable']) {
                    $ret[] = strtolower(str_replace('PEAR_Installer_Role_', '', $role));
                }
            }
        }
        return $ret;
    }

    /**
     * Return an array of roles that are affected by the baseinstalldir attribute
     *
     * Most roles ignore this attribute, and instead install directly into:
     * PackageName/filepath
     * so a tests file tests/file.phpt is installed into PackageName/tests/filepath.php
     * @static
     */
    function getBaseinstallRoles()
    {
        if (!isset($GLOBALS['_PEAR_INSTALLER_ROLES'])) {
            PEAR_Installer_Role::registerRoles();
        }
        static $ret;
        if (!isset($ret)) {
            foreach ($GLOBALS['_PEAR_INSTALLER_ROLES'] as $role => $okreleases) {
                if ($okreleases['honorsbaseinstall']) {
                    $ret[] = strtolower(str_replace('PEAR_Installer_Role_', '', $role));
                }
            }
        }
        return $ret;
    }

    /**
     * Return an array of file roles that should be analyzed for PHP content at package time,
     * like the "php" role.
     * @return array
     * @static
     */
    function getPhpRoles()
    {
        if (!isset($GLOBALS['_PEAR_INSTALLER_ROLES'])) {
            PEAR_Installer_Role::registerRoles();
        }
        static $ret;
        if (!isset($ret)) {
            foreach ($GLOBALS['_PEAR_INSTALLER_ROLES'] as $role => $okreleases) {
                if ($okreleases['phpfile']) {
                    $ret[] = strtolower(str_replace('PEAR_Installer_Role_', '', $role));
                }
            }
        }
        return $ret;
    }

    /**
     * Scan through the Command directory looking for classes
     * and see what commands they implement.
     * @param string which directory to look for classes, defaults to
     *               the Installer/Roles subdirectory of
     *               the directory from where this file (__FILE__) is
     *               included.
     *
     * @return bool TRUE on success, a PEAR error on failure
     * @access public
     * @static
     */
    function registerRoles($dir = null)
    {
        if ($dir === null) {
            $dir = dirname(__FILE__) . '/Role';
        }
        $dp = @opendir($dir);
        if (empty($dp)) {
            return PEAR::raiseError("registerRoles: opendir($dir) failed");
        }
        while ($entry = readdir($dp)) {
            if ($entry{0} == '.' || substr($entry, -4) != '.php' || $entry == 'Common.php') {
                continue;
            }
            $class = "PEAR_Installer_Role_".substr($entry, 0, -4);
            $file = "$dir/$entry";
            include_once $file;
            // List of commands
            if (empty($GLOBALS['_PEAR_INSTALLER_ROLES'][$class])) {
                $GLOBALS['_PEAR_INSTALLER_ROLES'][$class] =
                    call_user_func(array($class, 'getInfo'));
            }
        }
        @closedir($dp);
        return true;
    }
}
?>