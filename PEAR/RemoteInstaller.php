<?php
/**
 * PEAR_Installer
 *
 * PHP versions 4 and 5
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V.V. Cox <cox@idecnet.com>
 * @author     Martin Jansen <mj@php.net>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  2005-2009 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 0.1.0
 */

/**
 * parent class
 */
require_once 'PEAR/Installer.php';

/**
 * Administration class used to install PEAR packages and maintain the
 * installed package database.
 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  2005-2009 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 0.1.0
 */
class PEAR_RemoteInstaller extends PEAR_Installer
{

    /**
     * Delete a package's installed files, does not remove empty directories.
     *
     * @param string package name
     * @param string channel name
     * @param bool if true, then files are backed up first
     * @return bool TRUE on success, or a PEAR error on failure
     * @access protected
     */
    function _deletePackageFiles($package, $channel = false, $backup = false)
    {
        if (!$channel) {
            $channel = 'pear.php.net';
        }
        if (!strlen($package)) {
            return $this->raiseError("No package to uninstall given");
        }
        if (strtolower($package) == 'pear' && $channel == 'pear.php.net') {
            // to avoid race conditions, include all possible needed files
            require_once 'PEAR/Task/Common.php';
            require_once 'PEAR/Task/Replace.php';
            require_once 'PEAR/Task/Unixeol.php';
            require_once 'PEAR/Task/Windowseol.php';
            require_once 'PEAR/PackageFile/v1.php';
            require_once 'PEAR/PackageFile/v2.php';
            require_once 'PEAR/PackageFile/Generator/v1.php';
            require_once 'PEAR/PackageFile/Generator/v2.php';
        }
        $filelist = $this->_registry->packageInfo($package, 'filelist', $channel);
        if ($filelist == null) {
            return $this->raiseError("$channel/$package not installed");
        }
        $ret = array();
        if ($this->config->isDefinedLayer('ftp') && isset($this->_options['upgrade'])) {
            $pkg = $this->_registry->getPackage($package, $channel);
            $this->ftpUninstall($pkg); // no error checking
        }
        return parent::_deletePackageFiles($package, $channel, $backup);
    }

    /**
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     */
    public function ftpUninstall($pkg)
    {
        $ftp = &$this->config->getFTP();
        if (!$ftp) {
            return $this->raiseError('FTP client not initialized');
        }
        $this->log(2, 'Connect to FTP server');
        $e = $ftp->init();
        if (PEAR::isError($e)) {
            return $e;
        }
        PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        foreach ($pkg->getFilelist() as $file => $atts) {
            if ($pkg->getPackagexmlVersion() == '1.0') {
                $channel = 'pear.php.net';
                switch ($atts['role']) {
                    case 'doc':
                    case 'data':
                    case 'test':
                        $dest_dir = $this->config->get($atts['role'] . '_dir', 'ftp', $channel) .
                                    DIRECTORY_SEPARATOR . $pkg->getPackage();
                        unset($atts['baseinstalldir']);
                        break;
                    case 'ext':
                    case 'php':
                        $dest_dir = $this->config->get($atts['role'] . '_dir', 'ftp', $channel);
                        break;
                    case 'script':
                        $dest_dir = $this->config->get('bin_dir', 'ftp', $channel);
                        break;
                    default:
                        continue 2;
                }
                $save_destdir = $dest_dir;
                if (!empty($atts['baseinstalldir'])) {
                    $dest_dir .= DIRECTORY_SEPARATOR . $atts['baseinstalldir'];
                }
                if (dirname($file) != '.' && empty($atts['install-as'])) {
                    $dest_dir .= DIRECTORY_SEPARATOR . dirname($file);
                }
                if (empty($atts['install-as'])) {
                    $dest_file = $dest_dir . DIRECTORY_SEPARATOR . basename($file);
                } else {
                    $dest_file = $dest_dir . DIRECTORY_SEPARATOR . $atts['install-as'];
                }

                // Clean up the DIRECTORY_SEPARATOR mess
                $ds2 = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
                $dest_file = preg_replace(array('!\\\\+!', '!/!', "!$ds2+!"),
                                          array('/', '/', '/'),
                                          $dest_file);
            } else {
                $role = &PEAR_Installer_Role::factory($pkg, $atts['role'], $this->config);
                $role->setup($this, $pkg, $atts, $file);
                if (!$role->isInstallable()) {
                    continue; // this shouldn't happen
                }
                $tmp_path = ''; // unused
                list($save_destdir, $dest_dir, $dest_file, $orig_file) =
                    $role->processInstallation($pkg, $atts, $file, $tmp_path, 'ftp');
                $dest_file = str_replace(DIRECTORY_SEPARATOR, '/', $dest_file);
            }
            $this->log(2, 'Deleting "' . $dest_file . '"');
            $ftp->rm($dest_file);
        }
        PEAR::staticPopErrorHandling();
        $this->log(2, 'Disconnect from FTP server');
        return $ftp->disconnect();
    }

    /**
     * Upload an installed package - does not work with register-only packages!
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     */
    public function ftpInstall($pkg)
    {
        if ($pkg->getPackageType() != 'php') {
            return PEAR::raiseError('Error: can only install PHP scripts remotely,' .
                ' no PHP extensions can be compiled remotely');
        }
        $ftp = &$this->config->getFTP();
        if (!$ftp) {
            return PEAR::raiseError('FTP client not initialized');
        }
        $this->log(2, 'Connect to FTP server');
        $e = $ftp->init();
        if (PEAR::isError($e)) {
            return $e;
        }
        $pf = &$this->_registry->getPackage($pkg->getPackage(), $pkg->getChannel());
        foreach ($pf->getFilelist() as $file => $atts) {
            if ($pf->getPackagexmlVersion() == '1.0') {
                $channel = 'pear.php.net';
                switch ($atts['role']) {
                    case 'doc':
                    case 'data':
                    case 'test':
                        $dest_dir = $this->config->get($atts['role'] . '_dir', 'ftp', $channel) .
                                    DIRECTORY_SEPARATOR . $pf->getPackage();
                        unset($atts['baseinstalldir']);
                        break;
                    case 'ext':
                    case 'php':
                        $dest_dir = $this->config->get($atts['role'] . '_dir', 'ftp', $channel);
                        break;
                    case 'script':
                        $dest_dir = $this->config->get('bin_dir', 'ftp', $channel);
                        break;
                    default:
                        continue 2;
                }
                $save_destdir = $dest_dir;
                if (!empty($atts['baseinstalldir'])) {
                    $dest_dir .= DIRECTORY_SEPARATOR . $atts['baseinstalldir'];
                }
                if (dirname($file) != '.' && empty($atts['install-as'])) {
                    $dest_dir .= DIRECTORY_SEPARATOR . dirname($file);
                }
                if (empty($atts['install-as'])) {
                    $dest_file = $dest_dir . DIRECTORY_SEPARATOR . basename($file);
                } else {
                    $dest_file = $dest_dir . DIRECTORY_SEPARATOR . $atts['install-as'];
                }

                // Clean up the DIRECTORY_SEPARATOR mess
                $ds2 = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
                $dest_file = preg_replace(array('!\\+!', '!/!', "!$ds2+!"),
                                          array('/', '/', '/'),
                                          $dest_file);
            } else {
                $role = &PEAR_Installer_Role::factory($pf, $atts['role'], $this->config);
                $role->setup($this, $pf, $atts, $file);
                if (!$role->isInstallable()) {
                    continue; // this shouldn't happen
                }
                list($save_destdir, $dest_dir, $dest_file, $orig_file) =
                    $role->processInstallation($pkg, $atts, $file, $file /* not used */, 'ftp');
                $dest_file = str_replace(DIRECTORY_SEPARATOR, '/', $dest_file);
            }
            $installedas = $atts['installed_as'];
            $this->log(2, 'Uploading "' . $installedas . '" to "' . $dest_file . '"');
            if (PEAR::isError($e = $ftp->installFile($installedas, $dest_file))) {
                return $e;
            }
        }
        $this->log(2, 'Disconnect from FTP server');
        return $ftp->disconnect();
    }
}
?>
