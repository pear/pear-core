<?php

$server = 'pear.Chiara';
//$server = 'test.pear.php.net';
$temp_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testinstallertemp';

function cleanall($dir = null)
{
    if ($dir !== null) {
        $statedir = $dir;
    } else {
        $statedir = $GLOBALS['temp_path'];
    }
    if (file_exists($statedir)) {
        $dp = opendir($statedir);
        while ($ent = readdir($dp)) {
            if (in_array($ent, array('.', '..'))) {
                continue;
            }
            if (is_dir($statedir . DIRECTORY_SEPARATOR . $ent)) {
                cleanall($statedir . DIRECTORY_SEPARATOR . $ent);
                continue;
            }
            unlink($statedir . DIRECTORY_SEPARATOR . $ent);
        }
        closedir($dp);
        rmdir($statedir);
    }
}

register_shutdown_function('cleanall');
cleanall($temp_path);
if (!is_dir($temp_path)) {
    mkdir($temp_path);
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'php')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'php');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'data')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'data');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'doc')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'doc');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'test')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'test');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'ext')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'ext');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'script')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'script');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'tmp')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'tmp');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'bin')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'bin');
}
if (!is_dir($temp_path . DIRECTORY_SEPARATOR . 'cache')) {
    mkdir($temp_path . DIRECTORY_SEPARATOR . 'cache');
}
// make the fake configuration - we'll use one of these and it should work
$config = serialize(array('master_server' => $server,
    'preferred_state' => 'stable',
    'cache_dir' => $temp_path . DIRECTORY_SEPARATOR . 'cache',
    'php_dir' => $temp_path . DIRECTORY_SEPARATOR . 'php',
    'ext_dir' => $temp_path . DIRECTORY_SEPARATOR . 'ext',
    'data_dir' => $temp_path . DIRECTORY_SEPARATOR . 'data',
    'doc_dir' => $temp_path . DIRECTORY_SEPARATOR . 'doc',
    'test_dir' => $temp_path . DIRECTORY_SEPARATOR . 'test',
    'bin_dir' => $temp_path . DIRECTORY_SEPARATOR . 'bin',));
touch($temp_path . DIRECTORY_SEPARATOR . 'pear.conf');
$fp = fopen($temp_path . DIRECTORY_SEPARATOR . 'pear.conf', 'w');
fwrite($fp, $config);
fclose($fp);
touch($temp_path . DIRECTORY_SEPARATOR . 'pear.ini');
$fp = fopen($temp_path . DIRECTORY_SEPARATOR . 'pear.ini', 'w');
fwrite($fp, $config);
fclose($fp);

putenv('PHP_PEAR_SYSCONF_DIR='.$temp_path);
$home = getenv('HOME');
if (!empty($home)) {
    // for PEAR_Config initialization
    putenv('HOME="'.$temp_path);
}
require_once "PEAR/Downloader.php";
require_once dirname(dirname(dirname(__FILE__))) . '/download_test_classes.php.inc';
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'phpt_test.php.inc';
$phpunit = &new PEAR_PHPTest(true);
class fake_log
{
    var $_log = array();
    var $_download = array();
    function log($level, $message)
    {
        $this->_log[] = array($level, $message);
    }

    function _downloadCallback($msg, $params)
    {
        if ($msg == 'setup') {
            $params = 'self';
        }
        $this->_download[] = array($msg, $params);
    }

    function getLog()
    {
        $log = $this->_log;
        $this->_log = array();
        return $log;
    }

    function getDownload()
    {
        $log = $this->_download;
        $this->_download = array();
        return $log;
    }
}
$fakelog = new fake_log;
$config = &PEAR_Config::singleton();//($temp_path . '/pear.ini');
require_once 'PEAR/Downloader/Package.php';
class test_PEAR_Downloader_Package extends PEAR_Downloader_Package
{

    /**
     * @param array all packages to be installed
     * @static
     */
    function analyzeDependencies($params)
    {
        foreach ($params as $param) {
            $deps = $param->getDeps();
            if (count($deps)) {
                $depchecker = &test_PEAR_Dependency2::singleton($param->_config,
                    $param->_downloader->getOptions(), $param->getParsedPackage(),
                    PEAR_VALIDATE_DOWNLOADING);
                PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
                $failed = false;
                if (isset($deps['required'])) {
                    foreach ($deps['required'] as $type => $dep) {
                        if (!isset($dep[0])) {
                            if (PEAR::isError($e =
                                  $depchecker->{"validate{$type}Dependency"}($dep,
                                  true, $params))) {
                                $failed = true;
                                $param->_downloader->log(0, $e->getMessage());
                            } elseif (is_array($e)) {
                                $param->_downloader->log(0, $e[0]);
                            }
                        } else {
                            foreach ($dep as $d) {
                                if (PEAR::isError($e =
                                      $depchecker->{"validate{$type}Dependency"}($d,
                                      true, $params))) {
                                    $failed = true;
                                    $param->_downloader->log(0, $e->getMessage());
                                } elseif (is_array($e)) {
                                    $param->_downloader->log(0, $e[0]);
                                }
                            }
                        }
                    }
                    if (isset($deps['optional'])) {
                        foreach ($deps['optional'] as $type => $dep) {
                            if (!isset($dep[0])) {
                                if (PEAR::isError($e =
                                      $depchecker->{"validate{$type}Dependency"}($dep,
                                      false, $params))) {
                                    $failed = true;
                                    $param->_downloader->log(0, $e->getMessage());
                                } elseif (is_array($e)) {
                                    $param->_downloader->log(0, $e[0]);
                                }
                            } else {
                                foreach ($dep as $d) {
                                    if (PEAR::isError($e =
                                          $depchecker->{"validate{$type}Dependency"}($d,
                                          false, $params))) {
                                        $failed = true;
                                        $param->_downloader->log(0, $e->getMessage());
                                    } elseif (is_array($e)) {
                                        $param->_downloader->log(0, $e[0]);
                                    }
                                }
                            }
                        }
                    }
                    $groupname = $param->getGroup();
                    if (isset($deps['group']) && $groupname) {
                        if (!isset($deps['group'][0])) {
                            $deps['group'] = array($deps['group']);
                        }
                        $found = false;
                        foreach ($deps['group'] as $group) {
                            if ($group['attribs']['name'] == $groupname) {
                                $found = true;
                                break;
                            }
                        }
                        if ($found) {
                            unset($group['attribs']);
                            foreach ($group as $type => $dep) {
                                if (!isset($dep[0])) {
                                    if (PEAR::isError($e =
                                          $depchecker->{"validate{$type}Dependency"}($dep,
                                          false, $params))) {
                                        $failed = true;
                                        $param->_downloader->log(0, $e->getMessage());
                                    } elseif (is_array($e)) {
                                        $param->_downloader->log(0, $e[0]);
                                    }
                                } else {
                                    foreach ($dep as $d) {
                                        if (PEAR::isError($e =
                                              $depchecker->{"validate{$type}Dependency"}($d,
                                              false, $params))) {
                                            $failed = true;
                                            $param->_downloader->log(0, $e->getMessage());
                                        } elseif (is_array($e)) {
                                            $param->_downloader->log(0, $e[0]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($deps as $dep) {
                        if (PEAR::isError($e = $depchecker->validateDependency1($dep, $params))) {
                            $failed = true;
                            $param->_downloader->log(0, $e->getMessage());
                        } elseif (is_array($e)) {
                            $param->_downloader->log(0, $e[0]);
                        }
                    }
                }
                PEAR::staticPopErrorHandling();
                if ($failed) {
                    return PEAR::raiseError("Cannot install, dependencies failed");
                }
            }
        }
    }
}

class test_PEAR_Dependency2 extends PEAR_Dependency2
{
    var $_fakeOS;
    var $_phpVersion;
    var $_extensions;

    function &singleton($configOrRegistry = null, $installoptions = null, $package = null,
                              $state = PEAR_VALIDATE_INSTALLING)
    {
        if (!isset($GLOBALS['_test_dep'])) {
            $GLOBALS['_test_dep'] = &new test_PEAR_Dependency2($configOrRegistry,
                $installoptions, $package, $state);
        } else {
            $GLOBALS['_test_dep']->PEAR_Dependency2($configOrRegistry, $installoptions,
                $package, $state);
        }
        return $GLOBALS['_test_dep'];
    }

    function setOS($os)
    {
        $this->_fakeOS = $os;
    }

    function setArch($uname, $extra)
    {
        $this->extra = $extra;
        list($this->sysname,
             $this->release,
             $this->cpu,
             $this->extra,
             $this->nodename) = $this->parseSignature($uname, $extra);
    }

    function setPHPversion($version)
    {
        $this->_phpVersion = $version;
    }

    function setExtensions($exts)
    {
        $this->_extensions = $exts;
    }

    /**
     * Specify a dependency on an OS.  Use arch for detailed os/processor information
     *
     * There are two generic OS dependencies that will be the most common, unix and windows.
     * Other options are linux, freebsd, darwin (OS X), sunos, irix, hpux, aix
     */
    function validateOsDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            $not = true;
        } else {
            $not = false;
        }
        switch (strtolower($dep['name'])) {
            case 'windows' :
                if ($not) {
                    if (substr($this->_fakeOS, 0, 3) == 'WIN') {
                        return $this->raiseError("Cannot install %s on Windows");
                    }
                } else {
                    if (substr($this->_fakeOS, 0, 3) != 'WIN') {
                        return $this->raiseError("Can only install %s on Windows");
                    }
                }
            break;
            case 'unix' :
                $unices = array('linux', 'freebsd', 'darwin', 'sunos', 'irix', 'hpux', 'aix');
                if ($not) {
                    if (in_array($this->_fakeOS, $unices)) {
                        return $this->raiseError("Cannot install %s on any Unix system");
                    }
                } else {
                    if (!in_array($this->_fakeOS, $unices)) {
                        return $this->raiseError("Can only install %s on a Unix system");
                    }
                }
            break;
            default :
                if ($not) {
                    if ($dep['name'] == $this->_fakeOS) {
                        return $this->raiseError('Cannot install %s on ' . $dep['name']);
                    }
                } else {
                    if ($dep['name'] != $this->_fakeOS) {
                        return $this->raiseError('Cannot install %s on ' . $this->_fakeOS .
                            ', can only install on ' . $dep['name']);
                    }
                }
        }
        return true;
    }

    /**
     * Specify a complex dependency on an OS/processor/kernel version,
     * Use OS for simple operating system dependency.
     *
     * This is the only dependency that accepts an eregable pattern.  The pattern
     * will be matched against the php_uname() output parsed by OS_Guess
     */
    function validateArchDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING) {
            return true;
        }
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            $not = true;
        }
        if (!$this->matchSignature($dep['pattern'])) {
            if (!$not) {
                return $this->raiseError('%s Architecture dependency failed, cannot match "' .
                    $dep['pattern'] . "'");
            }
            return true;
        } else {
            if ($not) {
                return $this->raiseError('%s Architecture dependency failed, required "' .
                    $dep['pattern'] . "'");
            }
            return true;
        }
    }
    function validatePhpDependency($dep)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        $version = $this->_phpVersion;
        if (!version_compare($version, $dep['min'], '>=')) {
            if (!isset($this->_options['nodeps'])) {
                return $this->raiseError('%s requires PHP version '. $dep['min'] .
                    ' or greater');
            } else {
                return $this->warning('warning: %s requires PHP version ' .
                    $dep['min'] . ' or greater');
            }
        }
        if (!version_compare($version, $dep['max'], '<=')) {
            if (!isset($this->_options['nodeps'])) {
                return $this->raiseError('%s requires PHP version ' .
                    $dep['max'] . ' or less');
            } else {
                return $this->warning('%s requires PHP version ' . $dep['max'] .
                    ' or less');
            }
        }
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                if (version_compare($version, $dep['exclude'], '==')) {
                    if (!isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PHP version "' .
                            $dep['name'] . '" version ' .
                            $dep['exclude']);
                    } else {
                        return $this->warning('warning: %s is not compatible with PHP version "' .
                            $dep['name'] . '" version ' .
                            $dep['exclude']);
                    }
                }
            } else {
                foreach ($dep['exclude'] as $exclude) {
                    if (version_compare($version, $exclude, '==')) {
                        if (!isset($this->_options['force'])) {
                            return $this->raiseError('%s is not compatible with PHP version "' .
                                $dep['name'] . '"');
                        } else {
                            return $this->warning('warning: %s is not compatible with PHP version "' .
                                $dep['name'] . '"');
                        }
                    }
                }
            }
        }
    }

    function validateExtensionDependency($dep, $required = true)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        $loaded = $this->extension_loaded($dep['name']);
        if (isset($dep['conflicts']) && $dep['conflicts'] == 'yes') {
            if ($loaded) {
                if (!isset($this->_options['nodeps'])) {
                    return $this->raiseError('%s conflicts with PHP extension "' .
                        $dep['name'] . '"');
                } else {
                    return $this->warning('warning: %s conflicts with PHP extension "' .
                        $dep['name'] . '"');
                }
            } else {
                return true;
            }
        }
        if (!isset($dep['min']) && !isset($dep['max']) &&
              !isset($dep['recommended'])) {
            if ($loaded) {
                return true;
            } else {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['name'] . '"');
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['name'] . '"');
                    }
                } else {
                    return $this->warning('%s can optionally use PHP extension "' .
                        $dep['name'] . '"');
                }
            }
        }
        $version = (string) $this->phpversion($dep['name']);
        if (empty($version)) {
            $version = '0';
        }
        if (isset($dep['min'])) {
            if (!version_compare($version, $dep['min'], '>=')) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['name'] . '" version ' . $dep['min'] .
                            ' or greater');
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['name'] . '" version ' . $dep['min'] .
                            ' or greater');
                    }
                } else {
                    return $this->warning('warning: %s optionally requires PHP extension "' .
                        $dep['name'] . '" version ' . $dep['min'] .
                        ' or greater');
                }
            }
        }
        if (isset($dep['max'])) {
            if (!version_compare($version, $dep['max'], '<=')) {
                if ($required) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s requires PHP extension "' .
                            $dep['name'] . '" version ' . $dep['max'] .
                            ' or less');
                    } else {
                        return $this->warning('warning: %s requires PHP extension "' .
                            $dep['name'] . '" version ' . $dep['max'] .
                            ' or less');
                    }
                } else {
                    return $this->warning('warning: %s requires PHP extension "' .
                        $dep['name'] . '" version ' . $dep['max'] .
                        ' or less');
                }
            }
        }
        if (isset($dep['exclude'])) {
            if (!is_array($dep['exclude'])) {
                if (version_compare($version, $dep['exclude'], '==')) {
                    if (!isset($this->_options['force'])) {
                        return $this->raiseError('%s is not compatible with PHP extension "' .
                            $dep['name'] . '" version ' .
                            $dep['exclude']);
                    } else {
                        return $this->warning('warning: %s is not compatible with PHP extension "' .
                            $dep['name'] . '" version ' .
                            $dep['exclude']);
                    }
                }
            } else {
                foreach ($dep['exclude'] as $exclude) {
                    if (version_compare($version, $exclude, '==')) {
                        if (!isset($this->_options['force'])) {
                            return $this->raiseError('%s is not compatible with PHP extension "' .
                                $dep['name'] . '" version ' .
                                $exclude);
                        } else {
                            return $this->warning('warning: %s is not compatible with PHP extension "' .
                                $dep['name'] . '" version ' .
                                $exclude);
                        }
                    }
                }
            }
        }
        if (isset($dep['recommended'])) {
            if (version_compare($version, $dep['recommended'], '==')) {
                return true;
            } else {
                if (!isset($this->_options['force'])) {
                    return $this->warning('%s dependency: PHP extension ' . $dep['name'] .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['recommended'].'"');
                } else {
                    return $this->raiseError('%s dependency: PHP extension ' . $dep['name'] .
                        ' version "' . $version . '"' .
                        ' is not the recommended version "' . $dep['recommended'] .
                        '", but may be compatible, use --force to install');
                }
            }
        }
    }

    function validatePackageDependency($dep, $required, $params, $mustbeinstalled = false)
    {
        if ($this->_state != PEAR_VALIDATE_INSTALLING &&
              $this->_state != PEAR_VALIDATE_DOWNLOADING) {
            return true;
        }
        if (isset($dep['providesextension'])) {
            if ($this->extension_loaded($dep['providesextension'])) {
                if (isset($dep['conflicts'])) {
                    if (!isset($this->_options['nodeps'])) {
                        return $this->raiseError('%s conflicts with PHP extension "' .
                            $dep['providesextension'] . '"');
                    } else {
                        return $this->warning('warning: %s conflicts with PHP extension "' .
                            $dep['providesextension'] . '"');
                    }
                } else {
                    $subdep = array(
                        'name' => $dep['providesextension']
                    );
                    if (isset($dep['min'])) {
                        $subdep['min'] = $dep['min'];
                    }
                    if (isset($dep['max'])) {
                        $subdep['min'] = $dep['max'];
                    }
                    if (isset($dep['max'])) {
                        $subdep['max'] = $dep['max'];
                    }
                    $ret = $this->validateExtensionDependency($subdep, $required);
                }
            }
        }
        if ($this->_state == PEAR_VALIDATE_INSTALLING) {
            return $this->_validatePackageInstall($dep, $required);
        }
        if ($this->_state == PEAR_VALIDATE_DOWNLOADING) {
            return $this->_validatePackageDownload($dep, $required, $params, $mustbeinstalled);
        }
    }

    /**
     *
     */
    function parseSignature($uname, $extra)
    {
        static $sysmap = array(
            'HP-UX' => 'hpux',
            'IRIX64' => 'irix',
        );
        static $cpumap = array(
            'i586' => 'i386',
            'i686' => 'i386',
            'ppc' => 'powerpc',
        );
        if ($uname === null) {
            $uname = php_uname();
        }
        $parts = split('[[:space:]]+', trim($uname));
        $n = count($parts);

        $release = $machine = $cpu = '';
        $sysname = $parts[0];
        $nodename = $parts[1];
        $cpu = $parts[$n-1];
        $extra = '';
        if ($cpu == 'unknown') {
            $cpu = $parts[$n-2];
        }

        switch ($sysname) {
            case 'AIX' :
                $release = "$parts[3].$parts[2]";
                break;
            case 'Windows' :
                switch ($parts[1]) {
                    case '95/98':
                        $release = '9x';
                        break;
                    default:
                        $release = $parts[1];
                        break;
                }
                $cpu = 'i386';
                break;
            case 'Linux' :
                $extra = $this->_detectGlibcVersion();
                // use only the first two digits from the kernel version
                $release = ereg_replace('^([[:digit:]]+\.[[:digit:]]+).*', '\1', $parts[2]);
                break;
            case 'Mac' :
                $sysname = 'darwin';
                $nodename = $parts[2];
                $release = $parts[3];
                if ($cpu == 'Macintosh') {
                    if ($parts[$n - 2] == 'Power') {
                        $cpu = 'powerpc';
                    }
                }
                break;
            case 'Darwin' :
                if ($cpu == 'Macintosh') {
                    if ($parts[$n - 2] == 'Power') {
                        $cpu = 'powerpc';
                    }
                }
                $release = ereg_replace('^([[:digit:]]+\.[[:digit:]]+).*', '\1', $parts[2]);
                break;
            default:
                $release = ereg_replace('-.*', '', $parts[2]);
                break;
        }


        if (isset($sysmap[$sysname])) {
            $sysname = $sysmap[$sysname];
        } else {
            $sysname = strtolower($sysname);
        }
        if (isset($cpumap[$cpu])) {
            $cpu = $cpumap[$cpu];
        }
        return array($sysname, $release, $cpu, $extra, $nodename);
    }

    function _detectGlibcVersion()
    {
        return $this->extra;
    }

    function matchSignature($match)
    {
        if (is_array($match)) {
            $fragments = $match;
        } else {
            $fragments = explode('-', $match);
        }
        $n = count($fragments);
        $matches = 0;
        if ($n > 0) {
            $matches += $this->_matchFragment($fragments[0], $this->sysname);
        }
        if ($n > 1) {
            $matches += $this->_matchFragment($fragments[1], $this->release);
        }
        if ($n > 2) {
            $matches += $this->_matchFragment($fragments[2], $this->cpu);
        }
        if ($n > 3) {
            $matches += $this->_matchFragment($fragments[3], $this->extra);
        }
        return ($matches == $n);
    }

    function _matchFragment($fragment, $value)
    {
        if (strcspn($fragment, '*?') < strlen($fragment)) {
            $reg = '^' . str_replace(array('*', '?', '/'), array('.*', '.', '\\/'), $fragment) . '$';
            return eregi($reg, $value);
        }
        return ($fragment == '*' || !strcasecmp($fragment, $value));
    }

    function extension_loaded($name)
    {
        return isset($this->_extensions[$name]);
    }

    function phpversion($ext)
    {
        if ($this->extension_loaded($ext)) {
            return $this->_extensions[$ext];
        } else {
            return false;
        }
    }
}

/**
 * @param array
 * @return test_PEAR_Downloader_Package
 */
function &newFakeDownloaderPackage($options)
{
    global $config, $fakelog;
    $downloader = &new test_PEAR_Downloader($fakelog, $options, $config);
    $p = &new test_PEAR_Downloader_Package($downloader);
    return $p;
}
/**
 * @param array
 * @return PEAR_Downloader_Package
 */
function &newDownloaderPackage($options)
{
    global $config, $fakelog;
    $downloader = &new test_PEAR_Downloader($fakelog, $options, $config);
    $p = &new PEAR_Downloader_Package($downloader);
    return $p;
}
?>