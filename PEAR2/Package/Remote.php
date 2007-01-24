<?php
class PEAR2_Package_Remote
{
    private $parent;
    private $info;
    /**
     * @param string $package path to package file
     */
    function __construct($package, PEAR2_Package $parent)
    {
        $this->_parent = $parent;
        $this->_info = $package;
        
    }

    /**
     * Convert this remote packagefile into a local .tar, .tgz or .phar
     *
     * @return PEAR2_Package_Tar|PEAR2_Package_Tgz|PEAR2_Package_Phar
     */
    function download()
    {
        
    }

    private function _fromUrl($param, $saveparam = '')
    {
        if (!is_array($param) &&
              (preg_match('#^(http|ftp)://#', $param))) {
            $options = $this->_downloader->getOptions();
            $this->_type = 'url';
            $callback = $this->_downloader->ui ?
                array(&$this->_downloader, '_downloadCallback') : null;
            $dir = PEAR2_Config::current()->download_dir;
            $file = $this->_downloader->downloadHttp($param, $this->_downloader->ui,
                $dir, $callback);
            $this->_downloader->popErrorHandling();
            if (PEAR::isError($file)) {
                if (!empty($saveparam)) {
                    $saveparam = ", cannot download \"$saveparam\"";
                }
                $err = PEAR::raiseError('Could not download from "' . $param .
                    '"' . $saveparam . ' (' . $file->getMessage() . ')');
                    return $err;
            }
            if ($this->_rawpackagefile) {
                require_once 'Archive/Tar.php';
                $tar = &new Archive_Tar($file);
                $packagexml = $tar->extractInString('package2.xml');
                if (!$packagexml) {
                    $packagexml = $tar->extractInString('package.xml');
                }
                if (str_replace(array("\n", "\r"), array('',''), $packagexml) !=
                      str_replace(array("\n", "\r"), array('',''), $this->_rawpackagefile)) {
                    if ($this->getChannel() == 'pear.php.net') {
                        // be more lax for the existing PEAR packages that have not-ok
                        // characters in their package.xml
                        $this->_downloader->log(0, 'CRITICAL WARNING: The "' .
                            $this->getPackage() . '" package has invalid characters in its ' .
                            'package.xml.  The next version of PEAR may not be able to install ' .
                            'this package for security reasons.  Please open a bug report at ' .
                            'http://pear.php.net/package/' . $this->getPackage() . '/bugs');
                    } else {
                        return PEAR::raiseError('CRITICAL ERROR: package.xml downloaded does ' .
                            'not match value returned from xml-rpc');
                    }
                }
            }
            // whew, download worked!
            if (isset($options['downloadonly'])) {
                $pkg = &$this->getPackagefileObject($this->_config, $this->_downloader->debug);
            } else {
                if (PEAR::isError($dir = $this->_downloader->getDownloadDir())) {
                    return $dir;
                }
                $pkg = &$this->getPackagefileObject($this->_config, $this->_downloader->debug,
                    $dir);
            }
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $pf = &$pkg->fromAnyFile($file, PEAR_VALIDATE_INSTALLING);
            PEAR::popErrorHandling();
            if (PEAR::isError($pf)) {
                if (is_array($pf->getUserInfo())) {
                    foreach ($pf->getUserInfo() as $err) {
                        if (is_array($err)) {
                            $err = $err['message'];
                        }
                        if (!isset($options['soft'])) {
                            $this->_downloader->log(0, "Validation Error: $err");
                        }
                    }
                }
                if (!isset($options['soft'])) {
                    $this->_downloader->log(0, $pf->getMessage());
                }
                $err = PEAR::raiseError('Download of "' . ($saveparam ? $saveparam :
                    $param) . '" succeeded, but it is not a valid package archive');
                $this->_valid = false;
                return $err;
            }
            $this->_packagefile = &$pf;
            $this->setGroup('default'); // install the default dependency group
            return $this->_valid = true;
        }
        return $this->_valid = false;
    }

    /**
     *
     * @param string|array pass in an array of format
     *                     array(
     *                      'package' => 'pname',
     *                     ['channel' => 'channame',]
     *                     ['version' => 'version',]
     *                     ['state' => 'state',])
     *                     or a string of format [channame/]pname[-version|-state]
     */
    private function _fromString($param)
    {
        $options = $this->_downloader->getOptions();
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $pname = $this->_registry->parsePackageName($param,
            $this->_config->get('default_channel'));
        PEAR::popErrorHandling();
        if (PEAR::isError($pname)) {
            if ($pname->getCode() == 'invalid') {
                $this->_valid = false;
                return false;
            }
            if ($pname->getCode() == 'channel') {
                $parsed = $pname->getUserInfo();
                if ($this->_downloader->discover($parsed['channel'])) {
                    if ($this->_config->get('auto_discover')) {
                        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                        $pname = $this->_registry->parsePackageName($param,
                            $this->_config->get('default_channel'));
                        PEAR::popErrorHandling();
                    } else {
                        if (!isset($options['soft'])) {
                            $this->_downloader->log(0, 'Channel "' . $parsed['channel'] .
                                '" is not initialized, use ' .
                                '"pear channel-discover ' . $parsed['channel'] . '" to initialize' .
                                'or pear config-set auto_discover 1');
                        }
                    }
                }
                if (PEAR::isError($pname)) {
                    if (!isset($options['soft'])) {
                        $this->_downloader->log(0, $pname->getMessage());
                    }
                    if (is_array($param)) {
                        $param = $this->_registry->parsedPackageNameToString($param);
                    }
                    $err = PEAR::raiseError('invalid package name/package file "' .
                        $param . '"');
                    $this->_valid = false;
                    return $err;
                }
            } else {
                if (!isset($options['soft'])) {
                    $this->_downloader->log(0, $pname->getMessage());
                }
                $err = PEAR::raiseError('invalid package name/package file "' .
                    $param . '"');
                $this->_valid = false;
                return $err;
            }
        }
        if (!isset($this->_type)) {
            $this->_type = 'xmlrpc';
        }
        $this->_parsedname = $pname;
        if (isset($pname['state'])) {
            $this->_explicitState = $pname['state'];
        } else {
            $this->_explicitState = false;
        }
        if (isset($pname['group'])) {
            $this->_explicitGroup = true;
        } else {
            $this->_explicitGroup = false;
        }
        $info = $this->_downloader->_getPackageDownloadUrl($pname);
        if (PEAR::isError($info)) {
            if ($info->getCode() != -976 && $pname['channel'] == 'pear.php.net') {
                // try pecl
                $pname['channel'] = 'pecl.php.net';
                if ($test = $this->_downloader->_getPackageDownloadUrl($pname)) {
                    if (!PEAR::isError($test)) {
                        $info = PEAR::raiseError($info->getMessage() . ' - package ' .
                            $this->_registry->parsedPackageNameToString($pname, true) .
                            ' can be installed with "pecl install ' . $pname['package'] .
                            '"');
                    } else {
                        $pname['channel'] = 'pear.php.net';
                    }
                } else {
                    $pname['channel'] = 'pear.php.net';
                }
            }
            return $info;
        }
        $this->_rawpackagefile = $info['raw'];
        $ret = $this->_analyzeDownloadURL($info, $param, $pname);
        if (PEAR::isError($ret)) {
            return $ret;
        }
        if ($ret) {
            $this->_downloadURL = $ret;
            return $this->_valid = (bool) $ret;
        }
    }
}