<?php
/**
 * PEAR's master configuration manager
 *
 * Unlike PEAR version 1.x, the new PEAR configuration manager is tightly bound
 * to include_path, and will search through include_path for system configuration
 * PEAR installations.
 * 
 * The User configuration file will be looked for in these locations:
 *
 * Unix:
 * 
 * - home directory
 * - current directory
 *
 * Windows:
 *
 * - local settings directory on windows for the current user.
 *   This is looked up directly in the windows registry using COM
 * - current directory
 */
class PEAR2_Config
{
    private $pearDir;
    private $_userFile;
    static private $_configs;
    static private $_current;
    static private $defaults =
        array(
            'php_dir' => '@php_dir@', // pseudo-value in this implementation
            'ext_dir' => '@php_dir@/ext_dir',
            'doc_dir' => '@php_dir@/docs',
            'bin_dir' => PHP_BINDIR,
            'data_dir' => '@php_dir@/data',
            'test_dir' => '@php_dir@/tests',
            'php_bin' => '',
            'php_ini' => '',
            'default_channel' => 'pear.php.net',
            'preferred_mirror' => 'pear.php.net',
            'auto_discover' => 0,
            'http_proxy' => '',
            'cache_dir' => '@php_dir@/cache',
            'temp_dir' => '@php_dir@/temp',
            'download_dir' => '@php_dir@/downloads',
            'username' => '',
            'password' => '',
            'verbose' => 1,
            'preferred_state' => 'stable',
            'umask' => 0644,
            'cache_ttl' => 3600,
            'sig_type' => '',
            'sig_bin' => '',
            'sig_keyid' => '',
            'sig_keydir' => ''
        );
    static private $configs = array();
    static private $userConfigs = array();
    static private $pearConfigNames = array(
            'php_dir', // pseudo-value in this implementation
            'ext_dir',
            'doc_dir',
            'bin_dir',
            'data_dir',
            'test_dir',
            'php_bin',
            'php_ini',
        );
    static private $userConfigNames = array(
            'default_channel',
            'preferred_mirror',
            'auto_discover',
            'http_proxy',
            'cache_dir',
            'temp_dir',
            'download_dir',
            'username',
            'password',
            'verbose',
            'preferred_state',
            'umask',
            'cache_ttl',
            'sig_type',
            'sig_bin',
            'sig_keyid',
            'sig_keydir'
        );

    private function _constructDefaults()
    {
        static $called = false;
        if ($called) {
            return;
        }
        $called = true;
        foreach (self::$pearConfigNames as $name) {
            // make sure we've got valid paths for the underlying OS
            self::$defaults[$name] = str_replace('/', DIRECTORY_SEPARATOR,
                                                 self::$defaults[$name]);
        }
        if (ini_get('extension_dir')) {
            self::$defaults['ext_dir'] = ini_get('extension_dir');
        }
        ob_start();
        phpinfo(INFO_GENERAL);
        $stuff = ob_get_clean();
        if (preg_match('@Configuration File (php.ini) Path => (.*)@', 
              $stuff, $climatch)) {
            self::$defaults['php_ini'] = $climatch[0];
        } elseif (preg_match('@(?<="v">).+php\.ini@', $stuff, $htmlmatch)) {
            self::$defaults['php_ini'] = $htmlmatch[0];
        }
    }

    function __construct($pearDirectory, $userfile = false)
    {
        $pearDirectory = str_replace('\\', '/', $pearDirectory);
        $pearDirectory = str_replace('//', '/', $pearDirectory);
        $pearDirectory = str_replace('/', DIRECTORY_SEPARATOR, $pearDirectory);
        self::_constructDefaults();
        $this->loadConfigFile($pearDirectory, $userfile);
        $this->pearDir = $pearDirectory;
        self::$_configs[$pearDirectory] = $this;
        if (!isset(self::$_current)) {
            self::$_current = $this;
        }
    }

    /**
     * Retrieve the currently active primary configuration
     *
     */
    static public function current()
    {
        if (isset(self::$_current)) {
            return self::$_current;
        }
        // default
        return new PEAR2_Config(getcwd());
    }

    private function _locateLocalSettingsDirectory()
    {
        if (class_exists('COM')) {
            // windows, grab current user My Documents folder
            $info = new COM('winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\cimv2');
            $users = $info->ExecQuery("Select * From Win32_ComputerSystem");
            foreach ($users as $user) {
                $d = explode('\\', $user->UserName);
                $curuser = $d[1];
            }
            $registry = new COM('Wscript.Shell');
            return $registry->RegRead(
                'HKLM\\Software\\Microsoft\\Windows\\CurrentVersion\\' .
                'Explorer\\DocFolderPaths\\' . $curuser);
        } else {
            return getenv('HOME');
        }
    }

    /**
     * Extract configuration from system + user configuration files
     *
     * Configuration is stored in XML format, in two locations.
     * 
     * The system configuration contains all of the important directory
     * configuration variables like data_dir, and the location of php.ini and
     * the php executable php.exe or php.  This configuration is tightly bound
     * to the repository, and cannot be moved.  As such, php_dir is auto-defined
     * as dirname(/path/to/pear/.config), or /path/to/pear.
     * 
     * Only 1 user configuration file is allowed, and contains user-specific
     * settings, including the locations where to download package releases
     * and where to cache files downloaded from the internet.  If false is passed
     * in, PEAR2_Config will attempt to guess at the config file location as
     * documented in the class docblock {@link PEAR2_Config}.
     * @param string $pearDirectory
     * @param string|false $userfile
     */
    private function loadConfigFile($pearDirectory, $userfile = false)
    {
        if (!isset(self::$configs[$pearDirectory]) &&
              file_exists($pearDirectory . DIRECTORY_SEPARATOR . '.config')) {
            libxml_use_internal_errors(true);
            $x = simplexml_load_file($pearDirectory . DIRECTORY_SEPARATOR . '.config');
            if (!$x) {
                $errors = libxml_get_errors();
                libxml_clear_errors();
                throw PEAR2_Config_Exception::invalidConfig($pearDirectory, $errors);
            }
            $unsetvalues = array_diff(array_keys((array) $x), self::$pearConfigNames);
            // remove values that are not recognized system config variables
            foreach ($unsetvalues as $value)
            {
                if ($value == '@attributes') {
                    continue;
                }
                unset($x->$value);
            }
            self::$configs[$pearDirectory] = $x;
        }
        if (!$userfile) {
            if (class_exists('COM')) {
                $userfile = $this->_locateLocalSettingsDirectory() . DIRECTORY_SEPARATOR .
                    'pear' . DIRECTORY_SEPARATOR . 'pearconfig.xml';
            } else {
                $userfile = $this->_locateLocalSettingsDirectory() . DIRECTORY_SEPARATOR .
                    '.pear' . DIRECTORY_SEPARATOR . 'pearconfig.xml';
            }
            if (!file_exists($userfile)) {
                $test = realpath(getcwd() . DIRECTORY_SEPARATOR . 'pearconfig.xml');
                if ($test && file_exists($test)) {
                    $userfile = $test;
                }
            }
        }
        $this->_userFile = $userfile;
        if (!file_exists($userfile)) {
            return;
        }
        if (isset(self::$userConfigs[$userfile])) {
            return;
        }
        libxml_use_internal_errors(true);
        $x = simplexml_load_file($userfile);
        if (!$x) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw PEAR2_Config_Exception::invalidConfig($userfile, $errors);
        }
        $unsetvalues = array_diff(array_keys((array) $x), self::$userConfigNames);
        // remove values that are not recognized user config variables
        foreach ($unsetvalues as $value)
        {
            if ($value == '@attributes') {
                continue;
            }
            unset($x->$value);
        }
        self::$configs[$pearDirectory] = $x;
    }

    function saveConfig($userfile = false)
    {
        if (!$userfile) {
            if (class_exists('COM')) {
                $userfile = $this->_locateLocalSettingsDirectory() . DIRECTORY_SEPARATOR .
                    'pear' . DIRECTORY_SEPARATOR . 'pearconfig.xml';
            } else {
                $userfile = $this->_locateLocalSettingsDirectory() . DIRECTORY_SEPARATOR .
                    '.pear' . DIRECTORY_SEPARATOR . 'pearconfig.xml';
            }
        }
        $userfile = str_replace('\\', '/', $userfile);
        $userfile = str_replace('//', '/', $userfile);
        $userfile = str_replace('/', DIRECTORY_SEPARATOR, $userfile);
        $x = simplexml_load_string('<pearconfig version="1.0"></pearconfig>');
        foreach (self::$userConfigNames as $var) {
            $x->$var = $this->$var;
        }
        if (!file_exists(dirname($userfile))) {
            mkdir(dirname($userfile), 0777, true);
        }
        file_put_contents($userfile, $x->asXML());

        $system = $this->pearDir . '.config';
        if (dirname($system) != $this->pearDir) {
            $system = $this->pearDir . DIRECTORY_SEPARATOR . '.config';
        }
        if (!file_exists(dirname($system))) {
            mkdir(dirname($system), null, true);
        }
        $x = simplexml_load_string('<pearconfig version="1.0"></pearconfig>');
        foreach (self::$pearConfigNames as $var) {
            $x->$var = $this->$var;
            file_put_contents(dirname($system) . DIRECTORY_SEPARATOR .
                $var . '.txt', $this->$var);
        }
        file_put_contents($system, $x->asXML());
    }

    /**
     * Save a snapshot of the current config, and return the file name
     *
     * If the latest snapshot is the same as the existing configuration,
     * simply return the filename
     * @return string basename of the snapshot file of the current configuration
     */
    static public function configSnapshot()
    {
        $conf = self::current();
        $snapshotdir = $conf->pearDir . DIRECTORY_SEPARATOR . '.configsnapshots';
        if (!file_exists($snapshotdir)) {
            // this will be simple - no snapshots exist yet
            mkdir($snapshotdir, null, true);
            $snapshot = 'configsnapshot-' . date('Ymd') . '.xml';
            $x = simplexml_load_string('<pearconfig version="1.0"></pearconfig>');
            foreach (self::$pearConfigNames as $var) {
                $x->$var = $conf->$var;
            }
            file_put_contents($snapshotdir . DIRECTORY_SEPARATOR . $snapshot, $x->asXML());
            return $snapshot;
        }
        // scan existing snapshots, if any, for a match
        $dir = opendir($snapshotdir);
        while (false !== ($snapshot = readdir($dir))) {
            if ($snapshot[0] == '.') continue;
            $x = simplexml_load_file($snapshotdir . DIRECTORY_SEPARATOR . $snapshot);
            foreach (self::$pearConfigNames as $var) {
                if ($x->$var != $conf->$var) continue 2;
            }
            // found a match
            return $snapshot;
        }
        // no matches found
        $snapshot = 'configsnapshot-' . date('Ymd') . '.xml';
        $i = 0;
        while (file_exists($snapshotdir . DIRECTORY_SEPARATOR . $snapshot)) {
            $i++;
            // keep appending ".1" until we get a unique filename
            $snapshot = 'configsnapshot-' . date('Ymd') . str_repeat('.1', $i) . '.xml';
        }
        // save the snapshot
        $x = simplexml_load_string('<pearconfig version="1.0"></pearconfig>');
        foreach (self::$pearConfigNames as $var) {
            $x->$var = $conf->$var;
        }
        file_put_contents($snapshotdir . DIRECTORY_SEPARATOR . $snapshot, $x->asXML());
        return $snapshot;
    }

    /**
     * Load a configuration
     */
    static public function addConfigValue($key, $default, $system = true)
    {
        
    }

    public function __get($value)
    {
        if ($value == 'registry') {
            return PEAR2_Registry::singleton($this->pearDir);
        }
        if ($value == 'systemvars') {
            return self::$pearConfigNames;
        }
        if ($value == 'uservars') {
            return self::$userConfigNames;
        }
        if ($value == 'path') {
            return $this->pearDir;
        }
        if (!in_array($value, array_merge(self::$pearConfigNames, self::$userConfigNames))) {
            throw PEAR2_Config_Exception::unknownValue($this->pearDir, $value);
        }
        if (!isset($this->$value)) {
            return str_replace('@php_dir@', $this->pearDir, self::$defaults[$value]);
        }
        return (string) self::$configs[$this->pearDir]->$value;
    }

    public function __isset($value)
    {
        return isset(self::$configs[$this->pearDir]->$value);
    }

    public function __set($key, $value)
    {
        if ($key == 'php_dir') {
            throw new PEAR2_Config_Exception('Cannot set php_dir, move the repository');
        }
        if (!isset(self::$defaults[$key])) {
            throw PEAR2_Config_Exception::unknownValue($key);
        }
        if (isset(self::$pearConfigNames[$key])) {
            // global config
        } else {
            // local config
        }
    }
}
