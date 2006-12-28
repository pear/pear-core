<?php
class PEAR2_Installer
{
    private $_transact;
    private $_installedas;
    function __construct()
    {
        $this->_transact = new PEAR2_FileTransactions;
        $this->_installedas = new PEAR2_FileTransactions_Installedas;
        $this->_transact->registerTransaction('installedas', $this->_installedas);
        $this->_transact->registerTransaction('rmdir', new PEAR2_FileTransactions_Rmdir);
        $this->_transact->registerTransaction('rename', new PEAR2_FileTransactions_Rename);
    }

    /**
     * Install a fully downloaded package
     *
     * Using PEAR2_FileTransactions and the work of PEAR2_Installer_Role* to
     * group files in appropriate locations, the install() method then passes
     * on the registration of installation to PEAR2_Registry.  If necessary,
     * PEAR2_Config will update the install-time snapshots of configuration
     * @param PEAR2_Package $package
     */
    function install(PEAR2_Package $package)
    {
        $this->_installedas->reset($package);
        $tmp_path = 'C:/development/pear-core';
        $this->_options = array();
        
        $this->_transact->begin();
        foreach ($package->installcontents as $file) {
            $channel = $package->getChannel();
            // {{{ assemble the destination paths
            if (!in_array($file->role,
                  PEAR2_Installer_Role::getValidRoles($package->getPackageType()))) {
                throw new PEAR2_Installer_Exception('Invalid role `' .
                        $file->role .
                        "' for file $file");
            }
            $role = PEAR2_Installer_Role::factory($package, $file->role,
                PEAR2_Config::current());
            $role->setup($this, $package, $file['attribs'], $file->name);
            if (!$role->isInstallable()) {
                continue;
            }
            $info = $role->processInstallation($package, $file['attribs'],
                $file->name, $tmp_path);
            list($save_destdir, $dest_dir, $dest_file, $orig_file) = $info;
            $final_dest_file = $installed_as = $dest_file;
            if (isset($this->_options['packagingroot'])) {
                $final_dest_file = $this->_prependPath($final_dest_file,
                    $this->_options['packagingroot']);
            }
            $dest_dir = dirname($final_dest_file);
            $dest_file = $dest_dir . DIRECTORY_SEPARATOR . '.tmp' .
                basename($final_dest_file);
            // }}}
    
            if (empty($this->_options['register-only'])) {
                if (!file_exists($dest_dir) || !is_dir($dest_dir)) {
                    if (!mkdir($dest_dir, 0755, true)) {
                        throw new PEAR2_Installer_Exception("failed to mkdir $dest_dir");
                    }
                    PEAR2_Log::log(3, "+ mkdir $dest_dir");
                }
            }
            // pretty much nothing happens if we are only registering the install
            if (empty($this->_options['register-only'])) {
                if (!count($file->tasks)) { // no tasks
                    if (!file_exists($orig_file)) {
                        throw new PEAR2_Installer_Exception("file $orig_file does not exist");
                    }
                    if (!@copy($orig_file, $dest_file)) {
                        throw new PEAR2_Installer_Exception("failed to write $dest_file: $php_errormsg");
                    }
                    PEAR2_Log::log(3, "+ cp $orig_file $dest_file");
                    if (isset($attribs['md5sum'])) {
                        $md5sum = md5_file($dest_file);
                    }
                } else { // file with tasks
                    if (!file_exists($orig_file)) {
                        throw new PEAR2_Installer_Exception("file $orig_file does not exist");
                    }
                    $contents = file_get_contents($orig_file);
                    if ($contents === false) {
                        $contents = '';
                    }
                    if (isset($file['md5sum'])) {
                        $md5sum = md5($contents);
                    }
                    foreach ($file->tasks as $tag => $raw) {
                        $tag = str_replace(array($package->getTasksNs() . ':', '-'), 
                            array('', '_'), $tag);
                        $task = "PEAR2_Task_" . ucfirst($tag);
                        $task = new $task(PEAR2_Config::current(), PEAR2_TASK_INSTALL);
                        if (!$task->isScript()) { // scripts are only handled after installation
                            $task->init($raw, $file['attribs'],
                                $package->getLastInstalledVersion());
                            $res = $task->startSession($package, $contents, $final_dest_file);
                            if ($res === false) {
                                continue; // skip this file
                            }
                            $contents = $res; // save changes
                        }
                        $wp = @fopen($dest_file, "wb");
                        if (!is_resource($wp)) {
                            throw new PEAR2_Installer_Exception(
                                "failed to create $dest_file: $php_errormsg");
                        }
                        if (fwrite($wp, $contents) === false) {
                            throw new PEAR2_Installer_Exception(
                                "failed writing to $dest_file: $php_errormsg");
                        }
                        fclose($wp);
                    }
                }
                // {{{ check the md5
                if (isset($md5sum)) {
                    if (strtolower($md5sum) == strtolower($file['md5sum'])) {
                        PEAR2_Log::log(2, "md5sum ok: $final_dest_file");
                    } else {
                        if (empty($options['force'])) {
                            // delete the file
                            if (file_exists($dest_file)) {
                                unlink($dest_file);
                            }
                            if (!isset($options['ignore-errors'])) {
                                throw new PEAR2_Installer_Exception(
                                    "bad md5sum for file $final_dest_file");
                            } else {
                                if (!isset($options['soft'])) {
                                    PEAR2_Log::log(0,
                                        "warning : bad md5sum for file $final_dest_file");
                                }
                            }
                        } else {
                            if (!isset($options['soft'])) {
                                PEAR2_Log::log(0,
                                    "warning : bad md5sum for file $final_dest_file");
                            }
                        }
                    }
                }
                // }}}
                // {{{ set file permissions
                if (!OS_WINDOWS) {
                    if ($role->isExecutable()) {
                        $mode = 0777 & ~(int)octdec(PEAR2_Config::current()->umask);
                        PEAR2_Log::log(3, "+ chmod +x $dest_file");
                    } else {
                        $mode = 0666 & ~(int)octdec(PEAR2_Config::current()->umask);
                    }
                    $this->_transact->chmod($mode, $dest_file);
                    if (!@chmod($dest_file, $mode)) {
                        if (!isset($options['soft'])) {
                            PEAR2_Log::log(0,
                                "failed to change mode of $dest_file: $php_errormsg");
                        }
                    }
                }
                // }}}
                $this->_transact->rename($dest_file, $final_dest_file, $role->isExtension());
            }
            // Store the full path where the file was installed for easy uninstall
            $this->_transact->installedas($file->name, $installed_as,
                                $save_destdir, dirname(substr($dest_file,
                                 strlen($save_destdir))));
        }
        try {
            $this->_transact->commit();
        } catch (Exception $e) {
            $this->_transact->rollback();
        }
        PEAR2_Config::current()->registry->installPackage($package->getPackageFile()->info);
    }
}
