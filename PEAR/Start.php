<?php
require_once 'PEAR.php';
require_once 'System.php';
require_once 'PEAR/Config.php';
require_once 'PEAR/Command.php';
require_once 'PEAR/Common.php';
class PEAR_Start extends PEAR
{
    var $bin_dir;
    var $data_dir;
    var $install_pfc;
    var $corePackages =
        array(
            'PEAR_ErrorStack',
            'Archive_Tar',
            'Console_Getopt',
            'XML_RPC',
            'PEAR',
        );
    var $local_dir = array();
    var $origpwd;
    var $pfc_packages = array(
            'DB',
            'Net_Socket',
            'Net_SMTP',
            'Mail',
            'XML_Parser',
            'PHPUnit'
        );
    var $php_dir;
    var $php_bin;
    var $pear_conf;
    var $validPHPBin = false;
    var $test_dir;
    var $config =
        array(
            'prefix',
            'bin_dir',
            'php_dir',
            'doc_dir',
            'data_dir',
            'test_dir',
            'pear_conf',
        );
    var $prefix;
    var $progress = 0;
    var $configPrompt =
        array(
            'prefix' => 'Installation base ($prefix)',
            'bin_dir' => 'Binaries directory',
            'php_dir' => 'PHP code directory ($php_dir)',
            'doc_dir' => 'Documentation directory',
            'data_dir' => 'Data directory',
            'test_dir' => 'Tests directory',
            'pear_conf' => 'Name of configuration file',
        );
    var $localInstall;
    var $PEARConfig;
    var $tarball = array();
    function PEAR_Start()
    {
        parent::PEAR();
        if (OS_WINDOWS) {
            $this->configPrompt['php_bin'] = 'Path to CLI php.exe';
            $this->config[] = 'php_bin';
            $this->prefix = getcwd();

            if (!@is_dir($this->prefix)) {
                if (@is_dir('c:\php5')) {
                    $this->prefix = 'c:\php5';
                } elseif (@is_dir('c:\php4')) {
                    $this->prefix = 'c:\php4';
                } elseif (@is_dir('c:\php')) {
                    $this->prefix = 'c:\php';
                }
            }

            $this->localInstall = false;
            $this->bin_dir   = '$prefix';
            $this->php_dir   = '$prefix\pear';
            $this->doc_dir   = '$php_dir\docs';
            $this->data_dir  = '$php_dir\data';
            $this->test_dir  = '$php_dir\tests';
            if (OS_WINDOWS) {
                $this->pear_conf = PEAR_CONFIG_SYSCONFDIR . '\\pear.ini';
            } else {
                $this->pear_conf = PEAR_CONFIG_SYSCONFDIR . '/pear.conf';
            }
            /*
             * Detects php.exe
             */
            $this->validPHPBin = true;
            if ($t = $this->safeGetenv('PHP_PEAR_PHP_BIN')) {
                $this->php_bin   = dirname($t);
            } elseif ($t = $this->safeGetenv('PHP_BIN')) {
                $this->php_bin   = dirname($t);
            } elseif ($t = System::which('php')) {
                $this->php_bin = dirname($t);
            } elseif (is_file($this->prefix . '\cli\php.exe')) {
                $this->php_bin = $this->prefix . '\cli';
            } elseif (is_file($this->prefix . '\php.exe')) {
                $this->php_bin = $this->prefix;
            }
            $phpexe = OS_WINDOWS ? '\\php.exe' : '/php';
            if ($this->php_bin && !is_file($this->php_bin . $phpexe)) {
                $this->php_bin = '';
            } else {
                if (!ereg(":", $this->php_bin)) {
                    $this->php_bin = getcwd() . DIRECTORY_SEPARATOR . $this->php_bin;
                }
            }
            if (!is_file($this->php_bin . $phpexe)) {
                if (is_file('c:/php/cli/php.exe')) {
                    $this->php_bin = 'c"\\php\\cli';
                } elseif (is_file('c:/php5/php.exe')) {
                    $this->php_bin = 'c:\\php5';
                } elseif (is_file('c:/php4/cli/php.exe')) {
                    $this->php_bin = 'c:\\php4\\cli';
                } else {
                    $this->validPHPBin = false;
                }
            }
        } else {
            $this->prefix = dirname(PHP_BINDIR);
            if (get_current_user() != 'root') {
                $this->prefix = $this->safeGetenv('HOME') . '/pear';
            }
            $this->bin_dir   = '$prefix/bin';
            $this->php_dir   = '$prefix/share/pear';
            $this->doc_dir   = '$php_dir/docs';
            $this->data_dir  = '$php_dir/data';
            $this->test_dir  = '$php_dir/tests';
            // check if the user has installed PHP with PHP or GNU layout
            if (@is_dir("$this->prefix/lib/php/.registry")) {
                $this->php_dir = '$this->prefix/lib/php';
            } elseif (@is_dir("$this->prefix/share/pear/lib/.registry")) {
                $this->php_dir = '$prefix/share/pear/lib';
                $this->doc_dir   = '$prefix/share/pear/docs';
                $this->data_dir  = '$prefix/share/pear/data';
                $this->test_dir  = '$prefix/share/pear/tests';
            } elseif (@is_dir("$this->prefix/share/php/.registry")) {
                $this->php_dir = '$prefix/share/php';
            }
        }
    }

    function safeGetenv($var)
    {
        if (is_array($_ENV) && isset($_ENV[$var])) {
            return $_ENV[$var];
        }
        return getenv($var);
    }

    function show($stuff)
    {
        print $stuff;
    }

    function locatePackagesToInstall()
    {
        $dp = @opendir(dirname(__FILE__) . '/go-pear-tarballs');
        if (empty($dp)) {
            return PEAR::raiseError("while locating packages to install: opendir('" .
                dirname(__FILE__) . "/go-pear-tarballs') failed");
        }
        $potentials = array();
        while (false !== ($entry = readdir($dp))) {
            if ($entry{0} == '.' || !in_array(substr($entry, -4), array('.tar', '.tgz'))) {
                continue;
            }
            $potentials[] = $entry;
        }
        closedir($dp);
        $notfound = array();
        foreach ($this->corePackages as $package) {
            foreach ($potentials as $i => $candidate) {
                if (preg_match('/^' . $package . '-' . _PEAR_COMMON_PACKAGE_VERSION_PREG
                      . '\.(tar|tgz)$/', $candidate)) {
                    $this->tarball[$package] = dirname(__FILE__) . '/go-pear-tarballs/' . $candidate;
                    unset($potentials[$i]);
                    continue 2;
                }
            }
            $notfound[] = $package;
        }
        if (count($notfound)) {
            return PEAR::raiseError("No tarballs found for core packages: " .
                    implode(', ', $notfound));
        }
        $this->tarball = array_merge($this->tarball, $potentials);
    }

    function setupTempStuff()
    {
        if (!($this->ptmp = System::mktemp(array('-d')))) {
            $this->show("System's Tempdir failed, trying to use \$prefix/tmp ...");
            $res = System::mkDir(array($this->prefix . '/tmp'));
            if (!$res) {
                return PEAR::raiseError('mkdir ' . $this->prefix . '/tmp ... failed');
            }
            $_temp = tempnam($this->prefix . '/tmp', 'gope');

            System::rm(array('-rf', $_temp));
            System::mkdir(array('-p','-m', '0700', $_temp));
            $this->ptmp = $this->prefix . '/tmp';
            $ok = @chdir($this->ptmp);

            if (!$ok) { // This should not happen, really ;)
                $this->bail('chdir ' . $this->ptmp . ' ... failed');
            }
    
            print "ok\n";
    
            // Adjust TEMPDIR envvars
            if (!isset($_ENV)) {
                $_ENV = array();
            };
            $_ENV['TMPDIR'] = $_ENV['TEMP'] = $this->prefix . '/tmp';
        }
        return @chdir($this->ptmp);
    }

    /**
     * Try to detect the kind of SAPI used by the
     * the given php.exe.
     * @author Pierrre-Alain Joye
     */
    function win32DetectPHPSAPI()
    {
        if ($this->php_bin != '') {
            if (OS_WINDOWS) {
                exec('"' . $this->php_bin . '\\php.exe" -v', $res);
            } else {
                exec('"' . $this->php_bin . '/php" -v', $res);
            }
            if (is_array($res)) {
                if (isset($res[0]) && strpos($res[0],"(cli)")) {
                    return 'cli';
                }
                if (isset($res[0]) && strpos($res[0],"cgi")) {
                    return 'cgi';
                }
                if (isset($res[0]) && strpos($res[0],"cgi-fcgi")) {
                    return 'cgi';
                } else {
                    return 'unknown';
                }
            }
        }
        return 'unknown';
    }

    function doInstall()
    {
        print "Beginning install...\n";
        $this->PEARConfig = &PEAR_Config::singleton($this->pear_conf, $this->pear_conf);
        $this->PEARConfig->set('preferred_state', 'stable');
        foreach ($this->config as $var) {
            if ($var == 'pear_conf' || $var == 'prefix') {
                continue;
            }
            $this->PEARConfig->set($var, $this->$var);
        }
        
        $this->PEARConfig->store();
        print "Configuration written to $this->pear_conf...\n";
        $this->registry = &$this->PEARConfig->getRegistry();
        print "Initialized registry...\n";
        $install = &PEAR_Command::factory('install', $this->PEARConfig);
        print "Preparing to install...\n";
        $install_options = array(
            'nodeps' => true,
            'force' => true,
            );
        foreach ($this->tarball as $pkg => $src) {
            $options = $install_options;
            if ($this->registry->packageExists($pkg)) {
                $options['upgrade'] = true;
            }

            print "installing $src...\n";
            $install->run('install', $options, array($src));
        
            $this->displayHTMLProgress($this->progress += round(26 / count($this->tarball)));
        }
    }

    function postProcessConfigVars()
    {
        foreach ($this->config as $n => $var) {
            for ($m = 1; $m <= count($this->config); $m++) {
                $var2 = $this->config[$m];
                $this->$var = str_replace('$'.$var2, $this->$var2, $this->$var);
            }
        }

        foreach ($this->config as $var) {
            $dir = $this->$var;
        
            if (!preg_match('/_dir$/', $var)) {
                continue;
            }
        
            if (!@is_dir($dir)) {
                if (!System::mkDir(array('-p', $dir))) {
                    $root = OS_WINDOWS ? 'administrator' : 'root';
                    return PEAR::raiseError("Unable to create {$this->configPrompt[$var]} $dir.
Run this script as $root or pick another location.\n");
                }
            }
        }
    }

    /**
     * Get the php.ini file used with the current
     * process or with the given php.exe
     *
     * Horrible hack, but well ;)
     *
     * Not used yet, will add the support later
     * @author Pierre-Alain Joye <paj@pearfr.org>
     */
    function getPhpiniPath()
    {
        $pathIni = get_cfg_var('cfg_file_path');
        if ($pathIni && is_file($pathIni)) {
            return $pathIni;
        }
    
        // Oh well, we can keep this too :)
        // I dunno if get_cfg_var() is safe on every OS
        if (WINDOWS) {
            // on Windows, we can be pretty sure that there is a php.ini
            // file somewhere
            do {
                $php_ini = PHP_CONFIG_FILE_PATH . DIRECTORY_SEPARATOR . 'php.ini';
                if (@file_exists($php_ini)) {
                    break;
                }
                $php_ini = 'c:\winnt\php.ini';
                if (@file_exists($php_ini)) {
                    break;
                }
                $php_ini = 'c:\windows\php.ini';
            } while (false);
        } else {
            $php_ini = PHP_CONFIG_FILE_PATH . DIRECTORY_SEPARATOR . 'php.ini';
        }
    
        if (@is_file($php_ini)) {
            return $php_ini;
        }
    
        // We re running in hackz&troubles :)
        ob_implicit_flush(false);
        ob_start();
        phpinfo(INFO_GENERAL);
        $strInfo = ob_get_contents();
        ob_end_clean();
        ob_implicit_flush(true);
    
        if (php_sapi_name() != 'cli') {
            $strInfo = strip_tags($strInfo,'<td>');
            $arrayInfo = explode("</td>", $strInfo );
            $cli = false;
        } else {
            $arrayInfo = explode("\n", $strInfo);
            $cli = true;
        }
    
        foreach ($arrayInfo as $val) {
            if (strpos($val,"php.ini")) {
                if ($cli) {
                    list(,$pathIni) = explode('=>', $val);
                } else {
                    $pathIni = strip_tags(trim($val));
                }
                $pathIni = trim($pathIni);
                if (is_file($pathIni)) {
                    return $pathIni;
                }
            }
        }

        return false;
    }
}

class PEAR_Start_CLI extends PEAR_Start
{

    var $descLength;
    var $descFormat;
    var $first;
    var $last;
    var $origpwd;
    var $tty;

    function PEAR_Start_CLI()
    {
        parent::PEAR_Start();
        ini_set('html_errors', 0);
        define('WIN32GUI', OS_WINDOWS && php_sapi_name() == 'cli' && System::which('cscript'));
        $this->tty = OS_WINDOWS ? @fopen('\con', 'r') : @fopen('/dev/tty', 'r');

        if (!$this->tty) {
            $this->tty = fopen('php://stdin', 'r');
        }
        $this->origpwd = getcwd();
        $this->config = array_keys($this->configPrompt);
        
        // make indices run from 1...
        array_unshift($this->config, "");
        unset($this->config[0]);
        reset($this->config);
        $this->descLength = max(array_map('strlen', $this->configPrompt));
        $this->descFormat = "%-{$this->descLength}s";
        $this->first = key($this->config);
        end($this->config);
        $this->last = key($this->config);
        PEAR_Command::setFrontendType('CLI');
    }

    function _PEAR_Start_CLI()
    {
        if ($this->tty) {
            @fclose($this->tty);
        }
    }

    function run()
    {
        if (PEAR::isError($err = $this->locatePackagesToInstall())) {
            return $err;
        }
        $this->startupQuestion();
        $this->setupTempStuff();
        $this->getInstallLocations();
        $this->displayPreamble();
        if (PEAR::isError($err = $this->postProcessConfigVars())) {
            return $err;
        }
        $this->doInstall();
        $this->finishInstall();
    }

    function startupQuestion()
    {
        if (OS_WINDOWS) {
            print "
Are you installing a system-wide PEAR or a local copy? [system] : ";
            $tmp = trim(fgets($this->tty, 1024));
            if (!empty($tmp)) {
                if (strtolower($tmp) !== 'system') {
                    print "Please confirm local copy by typing 'yes' : ";
                    $tmp = trim(fgets($this->tty, 1024));
                    if (strtolower($tmp) == 'yes') {
                        $this->localInstall = true;
                        $this->pear_conf = '$prefix\\pear.ini';
                    }
                }
            }
        } else {
            if (get_current_user() == 'root') {
                return;
            }
            $this->pear_conf = $this->safeGetenv('HOME') . '/.pearrc';
        }
    }

    function getInstallLocations()
    {
        while (true) {
            print "
Below is a suggested file layout for your new PEAR installation.  To
change individual locations, type the number in front of the
directory.  Type 'all' to change all of them or simply press Enter to
accept these locations.

";

            foreach ($this->config as $n => $var) {
                $fullvar = $this->$var;
                foreach ($this->config as $blah => $unused) {
                    foreach ($this->config as $m => $var2) {
                        $fullvar = str_replace('$'.$var2, $this->$var2, $fullvar);
                    }
                }
                printf("%2d. $this->descFormat : %s\n", $n, $this->configPrompt[$var], $fullvar);
            }
    
            print "\n$this->first-$this->last, 'all' or Enter to continue: ";
            $tmp = trim(fgets($this->tty, 1024));
            if (empty($tmp)) {
                if (OS_WINDOWS && !$this->validPHPBin) {
                    echo "**ERROR**
Please, enter the php.exe path.

";
                } else {
                    break;
                }
            }
            if (isset($this->config[(int)$tmp])) {
                $var = $this->config[(int)$tmp];
                $desc = $this->configPrompt[$var];
                $current = $this->$var;
                if (WIN32GUI && $var != 'pear_conf'){
                    $tmp = $this->win32BrowseForFolder("Choose a Folder for $desc [$current] :");
                } else {
                    print "(Use \$prefix as a shortcut for '$this->prefix', etc.)
$desc [$current] : ";
                    $tmp = trim(fgets($this->tty, 1024));
                }
                $old = $this->$var;
                $this->$var = $$var = $tmp;
                if (OS_WINDOWS && $var=='php_bin') {
                    if ($this->validatePhpExecutable($tmp)) {
                        $this->php_bin = $tmp;
                    } else {
                        $this->php_bin = $old;
                    }
                }
            } elseif ($tmp == 'all') {
                foreach ($this->config as $n => $var) {
                    $desc = $this->configPrompt[$var];
                    $current = $this->$var;
                    print "$desc [$current] : ";
                    $tmp = trim(fgets($this->tty, 1024));
                    if (!empty($tmp)) {
                        $this->$var = $tmp;
                    }
                }
            }
        }
    }

    function validatePhpExecutable($tmp)
    {
        if (OS_WINDOWS) {
            if (strpos($tmp, 'php.exe')) {
                $tmp = str_replace('php.exe', '', $tmp);
            }
            if (file_exists($tmp . DIRECTORY_SEPARATOR . 'php.exe')) {
                $tmp = $tmp . DIRECTORY_SEPARATOR . 'php.exe';
                $this->php_bin_sapi = $this->win32DetectPHPSAPI();
                if ($this->php_bin_sapi=='cgi'){
                    print "
******************************************************************************
NOTICE! We found php.exe under $this->php_bin, it uses a $this->php_bin_sapi SAPI.
PEAR commandline tool works well with it.
If you have a CLI php.exe available, we recommend using it.

Press Enter to continue...";
                    $tmp = trim(fgets($this->tty, 1024));
                } elseif ($this->php_bin_sapi=='unknown') {
                    print "
******************************************************************************
WARNING! We found php.exe under $this->php_bin, it uses an $this->php_bin_sapi SAPI.
PEAR commandline tool has NOT been tested with it.
If you have a CLI (or CGI) php.exe available, we strongly recommend using it.

Press Enter to continue...";
                    $tmp = trim(fgets($this->tty, 1024));
                }
                echo "php.exe (sapi: $this->php_bin_sapi) found.\n\n";
                return $this->validPHPBin = true;
            } else {
                echo "**ERROR**: not a folder, or no php.exe found in this folder.
Press Enter to continue...";
                $tmp = trim(fgets($this->tty, 1024));
                return $this->validPHPBin = false;
            }
        }
    }

    /**
     * Create a vbs script to browse the getfolder dialog, called
     * by cscript, if it's available.
     * $label is the label text in the header of the dialog box
     *
     * TODO:
     * - Do not show Control panel
     * - Replace WSH with calls to w32 as soon as callbacks work
     * @author Pierrre-Alain Joye
     */
    function win32BrowseForFolder($label)
    {
        static $wshSaved=false;
        static $cscript='';
    $wsh_browserfolder = 'Option Explicit
Dim ArgObj, var1, var2, sa, sFld
Set ArgObj = WScript.Arguments
Const BIF_EDITBOX = &H10
Const BIF_NEWDIALOGSTYLE = &H40
Const BIF_RETURNONLYFSDIRS   = &H0001
Const BIF_DONTGOBELOWDOMAIN  = &H0002
Const BIF_STATUSTEXT         = &H0004
Const BIF_RETURNFSANCESTORS  = &H0008
Const BIF_VALIDATE           = &H0020
Const BIF_BROWSEFORCOMPUTER  = &H1000
Const BIF_BROWSEFORPRINTER   = &H2000
Const BIF_BROWSEINCLUDEFILES = &H4000
Const OFN_LONGNAMES = &H200000
Const OFN_NOLONGNAMES = &H40000
Const ssfDRIVES = &H11
Const ssfNETWORK = &H12
Set sa = CreateObject("Shell.Application")
var1=ArgObj(0)
Set sFld = sa.BrowseForFolder(0, var1, BIF_EDITBOX + BIF_VALIDATE + BIF_BROWSEINCLUDEFILES + BIF_RETURNFSANCESTORS+BIF_NEWDIALOGSTYLE , ssfDRIVES )
if not sFld is nothing Then
    if not left(sFld.items.item.path,1)=":" Then
        WScript.Echo sFld.items.item.path
    Else
        WScript.Echo "invalid"
    End If
Else
    WScript.Echo "cancel"
End If
';
        if( !$wshSaved){
            $cscript = $this->ptmp . DIRECTORY_SEPARATOR . "bf.vbs";
            $fh = fopen($cscript, "wb+");
            fwrite($fh, $wsh_browserfolder, strlen($wsh_browserfolder));
            fclose($fh);
            $wshSaved  = true;
        }
        exec('cscript ' . $cscript . ' "' . $label . '" //noLogo', $arPath);
        if (!count($arPath) || $arPath[0]=='' || $arPath[0]=='cancel') {
            return '';
        } elseif ($arPath[0]=='invalid') {
            echo "Invalid Path.\n";
            return '';
        }
        return $arPath[0];
    }

    function displayPreamble()
    {
        if (OS_WINDOWS) {
            /*
             * Checks PHP SAPI version under windows/CLI
             */
            if ($this->php_bin == '') {
                print "
We do not find any php.exe, please select the php.exe folder (CLI is
recommended, usually in c:\php\cli\php.exe)
";
                $this->validPHPBin = false;
            } elseif (strlen($this->php_bin)) {
                $this->php_bin_sapi = $this->win32DetectPHPSAPI();
                $this->validPHPBin = true;
                switch ($this->php_bin_sapi) {
                    case 'cli':
                    break;
                    case 'cgi':
                    case 'cgi-fcgi':
                        print "
*NOTICE*
We found php.exe under $this->php_bin, it uses a $this->php_bin_sapi SAPI. PEAR commandline
tool works well with it, if you have a CLI php.exe available, we
recommend using it.
";
                    break;
                    default:
                        print "
*WARNING*
We found php.exe under $this->php_bin, it uses an unknown SAPI. PEAR commandline
tool has not been tested with it, if you have a CLI (or CGI) php.exe available,
we strongly recommend using it.

";
                    break;
                }
            }
        }
    }

    function finishInstall()
    {
        $sep = OS_WINDOWS ? ';' : ':';
        $include_path = explode($sep, ini_get('include_path'));
        if (OS_WINDOWS) {
            $found = false;
            $t = strtolower($this->php_dir);
            foreach ($include_path as $path) {
                if ($t == strtolower($path)) {
                    $found = true;
                    break;
                }
            }
        } else {
            $found = in_array($this->php_dir, $include_path);
        }
        if (!$found) {
            print "
******************************************************************************
WARNING!  The include_path defined in the currently used php.ini does not
contain the PEAR PHP directory you just specified:
<$this->php_dir>
If the specified directory is also not in the include_path used by
your scripts, you will have problems getting any PEAR packages working.
";
    
            if ($php_ini = $this->getPhpiniPath()) {
                print "\n\nWould you like to alter php.ini <$php_ini>? [Y/n] : ";
                $alter_phpini = !stristr(fgets($this->tty, 1024), "n");
                if ($alter_phpini) {
                    $this->alterPhpIni($php_ini);
                } else {
                    if (OS_WINDOWS) {
                        print "
Please look over your php.ini file to make sure
$this->php_dir is in your include_path.";
                    } else {
                        print "
I will add a workaround for this in the 'pear' command to make sure
the installer works, but please look over your php.ini or Apache
configuration to make sure $this->php_dir is in your include_path.
";
                    }
                }
            }
    
        print "
Current include path           : ".ini_get('include_path')."
Configured directory           : $this->php_dir
Currently used php.ini (guess) : $php_ini
";
    
            print "Press Enter to continue: ";
            fgets($this->tty, 1024);
        }
    
        $pear_cmd = $this->bin_dir . DIRECTORY_SEPARATOR . 'pear';
        $pear_cmd = OS_WINDOWS ? strtolower($pear_cmd).'.bat' : $pear_cmd;
    
        // check that the installed pear and the one in the path are the same (if any)
        $pear_old = System::which(OS_WINDOWS ? 'pear.bat' : 'pear', $this->bin_dir);
        if ($pear_old && ($pear_old != $pear_cmd)) {
            // check if it is a link or symlink
            $islink = OS_WINDOWS ? false : is_link($pear_old) ;
            if ($islink && readlink($pear_old) != $pear_cmd) {
                print "\n** WARNING! The link $pear_old does not point to the " .
                      "installed $pear_cmd\n";
            } elseif (!$this->localInstall && is_writable($pear_old) && !is_dir($pear_old)) {
                rename($pear_old, "{$pear_old}_old");
                print "\n** WARNING! Backed up old pear to {$pear_old}_old\n";
            } else {
                print "\n** WARNING! Old version found at $pear_old, please remove it or ".
                      "be sure to use the new $pear_cmd command\n";
            }
        }
    
        print "\nThe 'pear' command is now at your service at $pear_cmd\n";
    
        // Alert the user if the pear cmd is not in PATH
        $old_dir = $pear_old ? dirname($pear_old) : false;
        if (!$this->which('pear', $old_dir)) {
            print "
** The 'pear' command is not currently in your PATH, so you need to
** use '$pear_cmd' until you have added
** '$this->bin_dir' to your PATH environment variable.

";
    
        print "Run it without parameters to see the available actions, try 'pear list'
to see what packages are installed, or 'pear help' for help.

For more information about PEAR, see:

  http://pear.php.net/faq.php
  http://pear.php.net/manual/

Thanks for using go-pear!

";
        }
    
        if (OS_WINDOWS && !$this->localInstall) {
            $this->win32CreateRegEnv();
        }
    }

    /**
     * System::which() does not allow path exclusion
     */
    function which($program, $dont_search_in = false)
    {
        if (OS_WINDOWS) {
            if ($_path = $this->safeGetEnv('Path')) {
                $dirs = explode(';', $_path);
            } else {
                $dirs = explode(';', $this->safeGetEnv('PATH'));
            }
            foreach ($dirs as $i => $dir) {
                $dirs[$i] = strtolower(realpath($dir));
            }
            if ($dont_search_in) {
                $dont_search_in = strtolower(realpath($dont_search_in));
            }
            if ($dont_search_in &&
                ($key = array_search($dont_search_in, $dirs)) !== false)
            {
                unset($dirs[$key]);
            }
    
            foreach ($dirs as $dir) {
                $dir = str_replace('\\\\', '\\', $dir);
                if (!strlen($dir)) {
                    continue;
                }
                if ($dir{strlen($dir) - 1} != '\\') {
                    $dir .= '\\';
                }
                $tmp = $dir . $program;
                $info = pathinfo($tmp);
                if (isset($info['extension']) && in_array(strtolower($info['extension']),
                      array('exe', 'com', 'bat', 'cmd'))) {
                    if (file_exists($tmp)) {
                        return strtolower($tmp);
                    }
                } elseif (file_exists($ret = $tmp . '.exe') ||
                    file_exists($ret = $tmp . '.com') ||
                    file_exists($ret = $tmp . '.bat') ||
                    file_exists($ret = $tmp . '.cmd')) {
                    return strtolower($ret);
                }
            }
        } else {
            $dirs = explode(':', $this->safeGetEnv('PATH'));
            if ($dont_search_in &&
                ($key = array_search($dont_search_in, $dirs)) !== false)
            {
                unset($dirs[$key]);
            }
            foreach ($dirs as $dir) {
                if (is_executable("$dir/$program")) {
                    return "$dir/$program";
                }
            }
        }
        return false;
    }

    /**
     * Not optimized, but seems to work, if some nice
     * peardev will test it? :)
     *
     * @author Pierre-Alain Joye <paj@pearfr.org>
     */
    function alterPhpIni($pathIni='')
    {
        $iniSep = OS_WINDOWS ? ';' : ':';

        if ($pathIni=='') {
            $pathIni =  $this->getPhpiniPath();
        }

        $arrayIni = file($pathIni);
        $i=0;
        $found=0;

        // Looks for each active include_path directives
        foreach ($arrayIni as $iniLine) {
            $iniLine = trim($iniLine);
            $iniLine = str_replace(array("\n", "\r"), array('', ''), $iniLine);
            if (preg_match("/^include_path/", $iniLine)) {
                $foundAt[] = $i;
                $found++;
            }
            $i++;
        }

        if ($found) {
            $includeLine = $arrayIni[$foundAt[0]];
            list(, $currentPath) = explode('=', $includeLine);

            $currentPath = trim($currentPath);
            if (substr($currentPath,0,1) == '"') {
                $currentPath = substr($currentPath, 1, strlen($currentPath) - 2);
            }

            $arrayPath = explode($iniSep, $currentPath);
            if ($arrayPath[0]=='.') {
                $newPath[0] = '.';
                $newPath[1] = $this->php_dir;
                array_shift($arrayPath);
            } else {
                $newPath[0] = $this->php_dir;
            }
    
            foreach ($arrayPath as $path) {
                $newPath[]= $path;
            }
        } else {
            $newPath[0] = '.';
            $newPath[1] = $this->php_dir;

        }
        $nl = OS_WINDOWS ? "\r\n" : "\n";
        $includepath = 'include_path="' . implode($iniSep,$newPath) . '"';
        $newInclude = "$nl$nl;***** Added by go-pear$nl" .
                       $includepath .
                       $nl . ";*****" .
                       $nl . $nl;

        $arrayIni[$foundAt[0]] = $newInclude;

        for ($i=1; $i<$found; $i++) {
            $arrayIni[$foundAt[$i]]=';' . trim($arrayIni[$foundAt[$i]]);
        }

        $newIni = implode("", $arrayIni);
        if (!($fh = @fopen($pathIni, "wb+"))) {
            $prefixIni = $this->prefix . DIRECTORY_SEPARATOR . "php.ini-gopear";
            $fh = fopen($prefixIni, "wb+");
            if (!$fh) {
                echo "
******************************************************************************
WARNING: Cannot write to $pathIni nor in $this->prefix/php.ini-gopear. Please
modify manually your php.ini by adding:

$includepath

";
                return false;
            } else {
                fwrite($fh, $newIni, strlen($newIni));
                fclose($fh);
                echo "
******************************************************************************
WARNING: Cannot write to $pathIni, but php.ini was successfully created
at <$this->prefix/php.ini-gopear>. Please replace the file <$pathIni> with
<$prefixIni> or modify your php.ini by adding:

$includepath

";

            }
        } else {
            fwrite($fh, $newIni, strlen($newIni));
            fclose($fh);
            echo "
php.ini <$pathIni> include_path updated.
";
        }
        return true;
    }

    /**
     * Generates a registry addOn for Win32 platform
     * This addon set PEAR environment variables
     * @author Pierrre-Alain Joye
     */
    function win32CreateRegEnv()
    {
        $nl = "\r\n";
        $reg ='REGEDIT4'.$nl.
                '[HKEY_CURRENT_USER\Environment]'. $nl .
                '"PHP_PEAR_SYSCONF_DIR"="' . addslashes($this->prefix) . '"' . $nl .
                '"PHP_PEAR_INSTALL_DIR"="' . addslashes($this->php_dir) . '"' . $nl .
                '"PHP_PEAR_DOC_DIR"="' . addslashes($this->doc_dir) . '"' . $nl .
                '"PHP_PEAR_BIN_DIR"="' . addslashes($this->bin_dir) . '"' . $nl .
                '"PHP_PEAR_DATA_DIR"="' . addslashes($this->data_dir) . '"' . $nl .
                '"PHP_PEAR_PHP_BIN"="' . addslashes($this->php_bin) . '"' . $nl .
                '"PHP_PEAR_TEST_DIR"="' . addslashes($this->test_dir) . '"' . $nl;
    
        $fh = fopen($this->prefix . DIRECTORY_SEPARATOR . 'PEAR_ENV.reg', 'wb');
        if($fh){
            fwrite($fh, $reg, strlen($reg));
            fclose($fh);
            echo "

* WINDOWS ENVIRONMENT VARIABLES *
For convenience, a REG file is available under $this->prefix\\PEAR_ENV.reg .
This file creates ENV variables for the current user.

Double-click this file to add it to the current user registry.

";
        }
    }

    function displayHTMLProgress()
    {
    }
}
?>