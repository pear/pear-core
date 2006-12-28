<?php
/**
 * This is the central registry, that is used for all installer options
 * 
 * Registry information that must be stored:
 *
 * - A list of installed packages
 * - the files in each package
 * - known channels
 * 
 * The SQLite database has this structure:
 * 
 * <pre>
 * CREATE TABLE packages (
 *  name VARCHAR(80) NOT NULL,
 *  channel VARCHAR(255) NOT NULL,
 *  version VARCHAR(20) NOT NULL,
 *  apiversion VARCHAR(20) NOT NULL,
 *  summary TEXT NOT NULL,
 *  description TEXT NOT NULL,
 *  stability VARCHAR(8) NOT NULL,
 *  apistability VARCHAR(8) NOT NULL,
 *  releasedate DATE NOT NULL,
 *  releasetime TIME,
 *  license VARCHAR(50) NOT NULL,
 *  licenseuri TEXT,
 *  licensepath TEXT,
 *  releasenotes TEXT,
 *  lastinstalledversion VARCHAR(20),
 *  installedwithpear VARCHAR(20),
 *  installtimeconfig VARCHAR(50), -- the path to configuration as stored
 *  PRIMARY KEY (name, channel)
 * );
 * 
 * CREATE TABLE maintainers (
 *  packages_name VARCHAR(80) NOT NULL,
 *  packages_channel VARCHAR(255) NOT NULL,
 *  role VARCHAR(11) NOT NULL,
 *  user VARCHAR(20) NOT NULL,
 *  email VARCHAR(100) NOT NULL,
 *  active CHAR(3) NOT NULL,
 *  PRIMARY KEY (packages_name, packages_channel, user)
 * );
 * 
 * CREATE TABLE files (
 *  packages_name VARCHAR(80) NOT NULL,
 *  packages_channel VARCHAR(255) NOT NULL,
 *  packagepath VARCHAR(255) NOT NULL,
 *  role VARCHAR(30) NOT NULL,
 *  rolepath VARCHAR(255) NOT NULL,
 *  PRIMARY KEY (packagepath, role, rolepath),
 *  UNIQUE (packages_name, packages_channel, packagepath)
 * );
 *
 * CREATE TABLE package_dependencies (
 *  required BOOL NOT NULL,
 *  packages_name VARCHAR(80) NOT NULL,
 *  packages_channel VARCHAR(255) NOT NULL,
 *  deppackage VARCHAR(80) NOT NULL,
 *  depchannel VARCHAR(255) NOT NULL,
 *  conflicts BOOL NOT NULL,
 *  min VARCHAR(20),
 *  max VARCHAR(20),
 *  PRIMARY KEY (required, packages_name, packages_channel, deppackage, depchannel)
 * );
 *
 * CREATE TABLE package_dependencies_exclude (
 *  required BOOL NOT NULL,
 *  packages_name VARCHAR(80) NOT NULL,
 *  packages_channel VARCHAR(255) NOT NULL,
 *  deppackage VARCHAR(80) NOT NULL,
 *  depchannel VARCHAR(255) NOT NULL,
 *  conflicts BOOL NOT NULL,
 *  exclude VARCHAR(20),
 *  PRIMARY KEY (required, packages_name, packages_channel, deppackage, depchannel)
 * );
 * 
 * CREATE TABLE channels (
 *  channel TEXT NOT NULL,
 *  summary TEXT NOT NULL,
 *  suggestedalias VARCHAR(50) NOT NULL,
 *  alias VARCHAR(50) NOT NULL,
 *  validatepackageversion VARCHAR(20) NOT NULL default "default",
 *  validatepackage NOT NULL default "PEAR_Validate",
 *  lastmodified DATETIME,
 *  PRIMARY KEY (channel),
 *  UNIQUE(alias)
 * );
 *
 * CREATE TABLE channel_servers (
 *  channel TEXT NOT NULL,
 *  server TEXT NOT NULL,
 *  ssl integer NOT NULL default 0,
 *  port integer NOT NULL default 80,
 *  PRIMARY KEY (channel, server)
 * );
 * 
 * CREATE TABLE channel_server_xmlrpc (
 *  channel TEXT NOT NULL,
 *  server TEXT NOT NULL,
 *  function TEXT NOT NULL,
 *  version VARCHAR(20) NOT NULL,
 *  PRIMARY KEY (channel, server, function, version)
 * );
 * 
 * CREATE TABLE channel_server_soap (
 *  channel TEXT NOT NULL,
 *  server TEXT NOT NULL,
 *  function TEXT NOT NULL,
 *  version VARCHAR(20) NOT NULL,
 *  PRIMARY KEY (channel, server, function, version)
 * );
 * 
 * CREATE TABLE channel_server_rest (
 *  channel TEXT NOT NULL,
 *  server TEXT NOT NULL,
 *  type TEXT NOT NULL,
 *  baseurl TEXT NOT NULL,
 *  PRIMARY KEY (channel, server, baseurl, type)
 * );
 *
 * CREATE TABLE pearregistryversion (
 *  version VARCHAR(20) NOT NULL default "1.0.0"
 * );
 * 
 * INSERT INTO pearregistryversion VALUES("1.0.0");
 *
 * CREATE TRIGGER package_delete DELETE ON packages
 *   FOR EACH ROW BEGIN
 *     DELETE FROM maintainers
 *     WHERE
 *       maintainers.packages_name = old.name AND
 *       maintainers.packages_channel = old.channel;
 *     DELETE FROM files
 *     WHERE
 *       files.packages_name = old.name AND
 *       files.packages_channel = old.channel;
 *     DELETE FROM package_dependencies
 *     WHERE
 *       package_dependencies.packages_name = old.name AND
 *       package_dependencies.packages_channel = old.channel;
 *     DELETE FROM package_dependencies_exclude
 *     WHERE
 *       package_dependencies_exclude.packages_name = old.name AND
 *       package_dependencies_exclude.packages_channel = old.channel;
 *   END;
 *
 * CREATE TRIGGER channel_delete DELETE ON channels
 *   FOR EACH ROW BEGIN
 *     DELETE FROM channel_servers
 *     WHERE
 *       channel_servers.channel = old.channel;
 *     DELETE FROM channel_server_xmlrpc
 *     WHERE
 *       channel_server_xmlrpc.channel = old.channel;
 *     DELETE FROM channel_server_soap
 *     WHERE
 *       channel_server_soap.channel = old.channel;
 *     DELETE FROM channel_server_rest
 *     WHERE
 *       channel_server_rest.channel = old.channel;
 *   END;
 * CREATE VIEW deps AS
 *   SELECT
 *       packages_name,
 *       packages_channel
 *       deppackage,
 *       depchannel,
 *       null as exclude,
 *       conflicts,
 *       min,
 *       max
 *   FROM package_dependencies
 *   UNION
 *   SELECT
 *       packages_name,
 *       packages_channel
 *       deppackage,
 *       depchannel,
 *       exclude,
 *       conflicts,
 *       null as min,
 *       null as max
 *   FROM package_dependencies_exclude
 *
 * CREATE VIEW protocols AS
 *  SELECT
 *      channel,
 *      server,
 *      function,
 *      version,
 *      "xmlrpc" as protocol
 *  FROM channel_server_xmlrpc
 *  UNION
 *  SELECT
 *      channel,
 *      server,
 *      function,
 *      version,
 *      "soap" as protocol
 *  FROM channel_server_soap
 *  UNION
 *  SELECT
 *      channel,
 *      server,
 *      baseurl as function,
 *      type as version,
 *      "rest" as protocol
 *  FROM channel_server_rest
 *
 * </pre>
 */
class PEAR2_Registry_Sqlite
{
    /**
     * The database resource
     *
     * @var SQLiteDatabase
     */
    protected $database;
    private $_path;

    /**
     * Initialize the registry
     *
     * @param unknown_type $path
     */
    function __construct($path)
    {
        if ($path) {
            if ($path != ':memory:') {
                if (dirname($path . '.pear2registry') != $path) {
                    $path = $path . DIRECTORY_SEPARATOR . '.pear2registry';
                }
            }
        }
        $this->_init($path);
        $this->_path = $path;
    }
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
            $saveparam = $this->parsedPackageNameToString($param);
            // process the array
            if (!isset($param['package'])) {
                throw new PEAR2_Registry_Exception('parsePackageName(): array $param ' .
                    'must contain a valid package name in index "param"',
                    'package');
            }
            if (!isset($param['uri'])) {
                if (!isset($param['channel'])) {
                    $param['channel'] = $defaultchannel;
                }
            } else {
                $param['channel'] = '__uri';
            }
        } else {
            $components = @parse_url((string) $param);
            if (isset($components['scheme'])) {
                if ($components['scheme'] == 'http') {
                    // uri package
                    $param = array('uri' => $param, 'channel' => '__uri');
                } elseif($components['scheme'] != 'channel') {
                    throw new PEAR2_Registry_Exception('parsePackageName(): only channel:// uris may ' .
                        'be downloaded, not "' . $param . '"');//, 'invalid');
                }
            }
            if (!isset($components['path'])) {
                throw new PEAR2_Registry_Exception('parsePackageName(): array $param ' .
                    'must contain a valid package name in "' . $param . '"',
                    'package');
            }
            if (isset($components['host'])) {
                // remove the leading "/"
                $components['path'] = substr($components['path'], 1);
            }
            if (!isset($components['scheme'])) {
                if (strpos($components['path'], '/') !== false) {
                    if ($components['path']{0} == '/') {
                        throw new PEAR2_Registry_Exception('parsePackageName(): this is not ' .
                            'a package name, it begins with "/" in "' . $param . '"',
                            'invalid');
                    }
                    $parts = explode('/', $components['path']);
                    $components['host'] = array_shift($parts);
                    if (count($parts) > 1) {
                        $components['path'] = array_pop($parts);
                        $components['host'] .= '/' . implode('/', $parts);
                    } else {
                        $components['path'] = implode('/', $parts);
                    }
                } else {
                    $components['host'] = $defaultchannel;
                }
            } else {
                if (strpos($components['path'], '/')) {
                    $parts = explode('/', $components['path']);
                    $components['path'] = array_pop($parts);
                    $components['host'] .= '/' . implode('/', $parts);
                }
            }

            if (is_array($param)) {
                $param['package'] = $components['path'];
            } else {
                $param = array(
                    'package' => $components['path']
                    );
                if (isset($components['host'])) {
                    $param['channel'] = $components['host'];
                }
            }
            if (isset($components['fragment'])) {
                $param['group'] = $components['fragment'];
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
            if (isset($pathinfo['extension']) &&
                  in_array(strtolower($pathinfo['extension']), array('tgz', 'tar'))) {
                $param['extension'] = $pathinfo['extension'];
                $param['package'] = substr($pathinfo['basename'], 0,
                    strlen($pathinfo['basename']) - 4);
            }
            // check for version
            if (strpos($param['package'], '-')) {
                $test = explode('-', $param['package']);
                if (count($test) != 2) {
                    throw new PEAR2_Registry_Exception('parsePackageName(): only one version/state ' .
                        'delimiter "-" is allowed in "' . $saveparam . '"',
                        'version');
                }
                list($param['package'], $param['version']) = $test;
            }
        }
        // validation
        $info = $this->channelExists($param['channel']);
        if (!$info) {
            throw new PEAR2_Registry_Exception('unknown channel "' . $param['channel'] .
                '" in "' . $saveparam . '"', 'channel');
        }
        $chan = $this->getChannel($param['channel']);
        if (!$chan) {
            throw new PEAR2_Registry_Exception("Exception: corrupt registry, could not " .
                "retrieve channel " . $param['channel'] . " information",
                'registry');
        }
        $param['channel'] = $chan->getName();
        $validate = $chan->getValidationObject();
        $vpackage = $chan->getValidationPackage();
        // validate package name
        if (!$validate->validPackageName($param['package'], $vpackage['_content'])) {
            throw new PEAR2_Registry_Exception('parsePackageName(): invalid package name "' .
                $param['package'] . '" in "' . $saveparam . '"',
                'package');
        }
        if (isset($param['group'])) {
            if (!PEAR_Validate::validGroupName($param['group'])) {
                throw new PEAR2_Registry_Exception('parsePackageName(): dependency group "' . $param['group'] .
                    '" is not a valid group name in "' . $saveparam . '"', 'group', null, null,
                    $param);
            }
        }
        if (isset($param['state'])) {
            if (!in_array(strtolower($param['state']), $validate->getValidStates())) {
                throw new PEAR2_Registry_Exception('parsePackageName(): state "' . $param['state']
                    . '" is not a valid state in "' . $saveparam . '"',
                    'state');
            }
        }
        if (isset($param['version'])) {
            if (isset($param['state'])) {
                throw new PEAR2_Registry_Exception('parsePackageName(): cannot contain both ' .
                    'a version and a stability (state) in "' . $saveparam . '"',
                    'version/state');
            }
            // check whether version is actually a state
            if (in_array(strtolower($param['version']), $validate->getValidStates())) {
                $param['state'] = strtolower($param['version']);
                unset($param['version']);
            } else {
                if (!$validate->validVersion($param['version'])) {
                    throw new PEAR2_Registry_Exception('parsePackageName(): "' . $param['version'] .
                        '" is neither a valid version nor a valid state in "' .
                        $saveparam . '"', 'version/state');
                }                    
            }
        }
        return $param;
    }
    /**
     * @param array
     * @return string
     */
    function parsedPackageNameToString($parsed, $brief = false)
    {
        if (is_string($parsed)) {
            return $parsed;
        }
        if (is_object($parsed)) {
            $p = $parsed;
            $parsed = array(
                'package' => $p->getPackage(),
                'channel' => $p->getChannel(),
                'version' => $p->getVersion(),
            );
        }
        if (isset($parsed['uri'])) {
            return $parsed['uri'];
        }
        if ($brief) {
            if ($channel = $this->channelAlias($parsed['channel'])) {
                return $channel . '/' . $parsed['package'];
            }
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
            $ver = isset($parsed['version']) ? $parsed['version'] : '';
            $ver .= isset($parsed['state']) ? $parsed['state'] : '';
            $ret .= '-' . $ver;
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
        if (isset($parsed['group'])) {
            $ret .= '#' . $parsed['group'];
        }
        return $ret;
    }

    function getDatabase()
    {
        return $this->_path;
    }

    private function _init($path)
    {
        $error = '';
        if (!$path) {
            $path = ':memory:';
        }
        $this->database = new SQLiteDatabase($path, 0666, $error);
        if (!$this->database) {
            throw new PEAR2_Registry_Exception('Cannot open SQLite registry: ' . $error);
        }
        if (@$this->database->singleQuery('SELECT version FROM pearregistryversion') == '1.0.0') {
            return;
        }
        if (!$this->database->queryExec('BEGIN', $error)) {
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
          CREATE TABLE packages (
           name VARCHAR(80) NOT NULL,
           channel VARCHAR(255) NOT NULL,
           version VARCHAR(20) NOT NULL,
           apiversion VARCHAR(20) NOT NULL,
           summary TEXT NOT NULL,
           description TEXT NOT NULL,
           stability VARCHAR(8) NOT NULL,
           apistability VARCHAR(8) NOT NULL,
           releasedate DATE NOT NULL,
           releasetime TIME,
           license VARCHAR(50) NOT NULL,
           licenseuri TEXT,
           licensepath TEXT,
           releasenotes TEXT,
           lastinstalledversion VARCHAR(20),
           installedwithpear VARCHAR(20),
           installtimeconfig VARCHAR(50),
           PRIMARY KEY (name, channel)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
          
        $query = '
          CREATE TABLE maintainers (
           packages_name VARCHAR(80) NOT NULL,
           packages_channel VARCHAR(255) NOT NULL,
           role VARCHAR(11) NOT NULL,
           user VARCHAR(20) NOT NULL,
           email VARCHAR(100) NOT NULL,
           active CHAR(3) NOT NULL,
           PRIMARY KEY (packages_name, packages_channel, user)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE files (
           packages_name VARCHAR(80) NOT NULL,
           packages_channel VARCHAR(255) NOT NULL,
           packagepath VARCHAR(255) NOT NULL,
           role VARCHAR(30) NOT NULL,
           rolepath VARCHAR(255) NOT NULL,
           PRIMARY KEY (packagepath, role, rolepath),
           UNIQUE (packages_name, packages_channel, packagepath)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE package_dependencies (
           required BOOL NOT NULL,
           packages_name VARCHAR(80) NOT NULL,
           packages_channel VARCHAR(255) NOT NULL,
           deppackage VARCHAR(80) NOT NULL,
           depchannel VARCHAR(255) NOT NULL,
           conflicts BOOL NOT NULL,
           min VARCHAR(20),
           max VARCHAR(20),
           PRIMARY KEY (required, packages_name, packages_channel, deppackage, depchannel)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE package_dependencies_exclude (
           required BOOL NOT NULL,
           packages_name VARCHAR(80) NOT NULL,
           packages_channel VARCHAR(255) NOT NULL,
           deppackage VARCHAR(80) NOT NULL,
           depchannel VARCHAR(255) NOT NULL,
           exclude VARCHAR(20),
           conflicts BOOL NOT NULL,
           PRIMARY KEY (required, packages_name, packages_channel, deppackage, depchannel)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE channels (
           channel TEXT NOT NULL,
           summary TEXT NOT NULL,
           suggestedalias VARCHAR(50) NOT NULL,
           alias VARCHAR(50) NOT NULL,
           validatepackageversion VARCHAR(20) NOT NULL default "default",
           validatepackage NOT NULL default "PEAR_Validate",
           lastmodified TEXT,
           PRIMARY KEY (channel),
           UNIQUE(alias)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE channel_servers (
           channel TEXT NOT NULL,
           server TEXT NOT NULL,
           ssl integer NOT NULL default 0,
           port integer NOT NULL default 80,
           PRIMARY KEY (channel, server)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE channel_server_xmlrpc (
           channel TEXT NOT NULL,
           server TEXT NOT NULL,
           function TEXT NOT NULL,
           version VARCHAR(20) NOT NULL,
           PRIMARY KEY (channel, server, function, version)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE channel_server_soap (
           channel TEXT NOT NULL,
           server TEXT NOT NULL,
           function TEXT NOT NULL,
           version VARCHAR(20) NOT NULL,
           PRIMARY KEY (channel, server, function, version)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE channel_server_rest (
           channel TEXT NOT NULL,
           server TEXT NOT NULL,
           type TEXT NOT NULL,
           baseurl TEXT NOT NULL,
           PRIMARY KEY (channel, server, baseurl, type)
          );';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TABLE pearregistryversion (
           version VARCHAR(20) NOT NULL
          );
          
          INSERT INTO pearregistryversion VALUES("1.0.0");
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TRIGGER package_delete DELETE ON packages
            FOR EACH ROW BEGIN
              DELETE FROM maintainers
              WHERE
                maintainers.packages_name = old.name AND
                maintainers.packages_channel = old.channel;
              DELETE FROM files
              WHERE
                files.packages_name = old.name AND
                files.packages_channel = old.channel;
              DELETE FROM package_dependencies
              WHERE
                package_dependencies.packages_name = old.name AND
                package_dependencies.packages_channel = old.channel;
              DELETE FROM package_dependencies_exclude
              WHERE
                package_dependencies_exclude.packages_name = old.name AND
                package_dependencies_exclude.packages_channel = old.channel;
            END;
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
CREATE TRIGGER channel_check BEFORE DELETE ON channels
            BEGIN
             SELECT RAISE(ROLLBACK, \'Cannot delete channel, installed packages use it\')
             WHERE old.channel IN (SELECT channel FROM packages);
            END;';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE TRIGGER channel_delete DELETE ON channels
            FOR EACH ROW BEGIN
              DELETE FROM channel_servers
              WHERE
                channel_servers.channel = old.channel;
              DELETE FROM channel_server_xmlrpc
              WHERE
                channel_server_xmlrpc.channel = old.channel;
              DELETE FROM channel_server_soap
              WHERE
                channel_server_soap.channel = old.channel;
              DELETE FROM channel_server_rest
              WHERE
                channel_server_rest.channel = old.channel;
            END;
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE VIEW deps AS
            SELECT
                packages_name,
                packages_channel
                deppackage,
                depchannel,
                null as exclude,
                conflicts,
                min,
                max
            FROM package_dependencies
            UNION
            SELECT
                packages_name,
                packages_channel
                deppackage,
                depchannel,
                exclude,
                conflicts,
                null as min,
                null as max
            FROM package_dependencies_exclude
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }

        $query = '
          CREATE VIEW protocols AS
            SELECT
                channel,
                server,
                function,
                version,
                "xmlrpc" as protocol
            FROM channel_server_xmlrpc
            UNION
            SELECT
                channel,
                server,
                function,
                version,
                "soap" as protocol
            FROM channel_server_soap
            UNION
            SELECT
                channel,
                server,
                baseurl as function,
                type as version,
                "rest" as protocol
            FROM channel_server_rest
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channels
             (channel, summary, suggestedalias, alias, lastmodified)
            VALUES(
             "pear.php.net",
             "PHP Extension and Application Repository",
             "pear",
             "pear",
             datetime("now")
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channel_server_rest
             (channel, server, type, baseurl)
            VALUES(
             "pear.php.net",
             "pear.php.net",
             "REST1.0",
             "http://pear.php.net/rest/"
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channel_server_rest
             (channel, server, type, baseurl)
            VALUES(
             "pear.php.net",
             "pear.php.net",
             "REST1.1",
             "http://pear.php.net/rest/"
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channels
             (channel, summary, suggestedalias, alias, validatepackageversion,
              validatepackage, lastmodified)
            VALUES(
             "pecl.php.net",
             "PHP Extension and Community Library",
             "pecl",
             "pecl",
             "default",
             "PEAR_Validate_PECL",
             datetime("now")
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channel_server_rest
             (channel, server, type, baseurl)
            VALUES(
             "pecl.php.net",
             "pecl.php.net",
             "REST1.0",
             "http://pecl.php.net/rest/"
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channel_server_rest
             (channel, server, type, baseurl)
            VALUES(
             "pecl.php.net",
             "pecl.php.net",
             "REST1.1",
             "http://pecl.php.net/rest/"
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        $query = '
            INSERT INTO channels
             (channel, summary, suggestedalias, alias, lastmodified)
            VALUES(
             "__uri",
             "pseudo-channel for static packages",
             "__uri",
             "__uri",
             datetime("now")
            )
        ';
        $worked = @$this->database->queryExec($query, $error);
        if (!$worked) {
            @$this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Cannot initialize SQLite registry: ' . $error);
        }
        @$this->database->queryExec('COMMIT');
    }

    /**
     * Add an installed package to the registry
     *
     * @param PEAR2_PackageFile_v2 $pf
     */
    function installPackage(PEAR2_PackageFile_v2 $pf)
    {
        if ($this->database->singleQuery('SELECT name FROM packages WHERE name="' .
              $pf->getName() . '" AND channel="' . $pf->getChannel() . '"')) {
            throw new PEAR2_Registry_Exception('Error: package ' .
                $pf->getChannel() . '/' . $pf->getName() . ' has already been installed');
        }
        $this->database->queryExec('BEGIN');
        $licloc = $pf->getLicenseLocation();
        $licuri = isset($licloc['uri']) ? '"' .
            sqlite_escape_string($licloc['uri']) . '"' : 'NULL';
        $licpath = isset($licloc['path']) ? '"' .
            sqlite_escape_string($licloc['path']) . '"' : 'NULL';
        if (!@$this->database->queryExec('
             INSERT INTO packages
              (name, channel, version, apiversion, summary,
               description, stability, apistability, releasedate,
               releasetime, license, licenseuri, licensepath,
               releasenotes, lastinstalledversion, installedwithpear,
               installtimeconfig)
             VALUES(
              "' . $pf->getName() . '",
              "' . $pf->getChannel() . '",
              "' . $pf->getVersion('release') . '",
              "' . $pf->getVersion('api') . '",
              \'' . sqlite_escape_string($pf->getSummary()) . '\',
              \'' . sqlite_escape_string($pf->getDescription()) . '\',
              "' . $pf->getState('release') . '",
              "' . $pf->getState('api') . '",
              "' . $pf->getDate() . '",
              ' . ($pf->getTime() ? '"' . $pf->getTime() . '"' : 'NULL') . ',
              "' . $pf->getLicense() . '",
              ' . $licuri . ',
              ' . $licpath . ',
              \'' . sqlite_escape_string($pf->getNotes()) . '\',
              NULL,
              "2.0.0",
              "' . PEAR2_Config::configSnapshot() . '"
             )
            ')) {
            $this->database->queryExec('ROLLBACK');
            throw new PEAR2_Registry_Exception('Error: package ' .
                $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
        }
        foreach ($pf->getMaintainers() as $maintainer) {
            if (!@$this->database->queryExec('
                 INSERT INTO maintainers
                  (packages_name, packages_channel, role, user,
                   email, active)
                 VALUES(
                  "' . $pf->getName() . '",
                  "' . $pf->getChannel() . '",
                  "' . $maintainer['role'] . '",
                  "' . $maintainer['handle'] . '",
                  "' . $maintainer['email'] . '",
                  "' . $maintainer['active'] . '"
                 )
                ')) {
                $this->database->queryExec('ROLLBACK');
                throw new PEAR2_Registry_Exception('Error: package ' .
                    $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
            }
        }
        $curconfig = PEAR2_Config::current();
        $roles = array();
        foreach (PEAR2_Installer_Role::getValidRoles($pf->getPackageType()) as $role) {
            // set up a list of file role => configuration variable
            // for storing in the registry
            $roles[$role] =
                PEAR2_Installer_Role::factory($pf, $role)->getLocationConfig();
        }
        foreach ($pf->installcontents as $file) {
            if (!@$this->database->queryExec('
                 INSERT INTO files
                  (packages_name, packages_channel, packagepath, role, rolepath)
                 VALUES(
                  "' . $pf->getName() . '",
                  "' . $pf->getChannel() . '",
                  "' . $file->name . '",
                  "' . $file->role . '",
                  "' . $curconfig->{$roles[$file->role]} . '"
                 )
                ')) {
                $this->database->queryExec('ROLLBACK');
                throw new PEAR2_Registry_Exception('Error: package ' .
                    $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
            }
        }

        $deps = $pf->getDependencies();
        foreach (array('required', 'optional') as $required) {
            foreach (array('package', 'subpackage') as $package) {
                if (isset($deps[$required][$package])) {
                    $ds = isset($deps[$required][$package][0]) ?
                        $deps[$required][$package] :
                        array($deps[$required][$package]);
                    foreach ($ds as $d) {
                        $dchannel = isset($d['channel']) ?
                            $d['channel'] :
                            '__uri';
                        $dmin = isset($d['min']) ?
                            '"' . $d['min'] . '"':
                            'NULL';
                        $dmax = isset($d['max']) ?
                            '"' . $d['max'] . '"':
                            'NULL';
                        if (!@$this->database->queryExec('
                             INSERT INTO package_dependencies
                              (required, packages_name, packages_channel, deppackage,
                               depchannel, conflicts, min, max)
                             VALUES(
                              ' . ($required == 'required' ? 1 : 0) . ',
                              "' . $pf->getName() . '",
                              "' . $pf->getChannel() . '",
                              "' . $d['name'] . '",
                              "' . $dchannel . '",
                              "' . isset($d['conflicts']) . '",
                              ' . $dmin . ',
                              ' . $dmax . '
                             )
                            ')) {
                            $this->database->queryExec('ROLLBACK');
                            throw new PEAR2_Registry_Exception('Error: package ' .
                                $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
                        }
                        if (isset($d['exclude'])) {
                            if (!is_array($d['exclude'])) {
                                $d['exclude'] = array($d['exclude']);
                            }
                            foreach ($d['exclude'] as $exclude) {
                                if (!@$this->database->queryExec('
                                     INSERT INTO package_dependencies_exclude
                                      (required, packages_name, packages_channel,
                                       deppackage, depchannel, exclude, conflicts)
                                     VALUES(
                                      ' . ($required == 'required' ? 1 : 0) . ',
                                      "' . $pf->getName() . '",
                                      "' . $pf->getChannel() . '",
                                      "' . $d['name'] . '",
                                      "' . $dchannel . '",
                                      "' . $exclude . '",
                                      "' . isset($d['conflicts']) . '"
                                     )
                                    ')) {
                                    $this->database->queryExec('ROLLBACK');
                                    throw new PEAR2_Registry_Exception('Error: package ' .
                                        $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
                                }                        
                            }
                        }
                    }
                }
            }
        }
        if (!isset($deps['group'])) {
            $deps['group'] = array();
        } elseif (!isset($deps['group'][0])) {
            $deps['group'] = array($deps['group']);
        }
        foreach ($deps['group'] as $group) {
            foreach (array('package', 'subpackage') as $package) {
                if (isset($group[$package])) {
                    $ds = isset($group[$package][0]) ?
                        $group[$package] :
                        array($group[$package]);
                    foreach ($ds as $d) {
                        $dchannel = isset($d['channel']) ?
                            $d['channel'] :
                            '__uri';
                        $dmin = isset($d['min']) ?
                            '"' . $d['min'] . '"':
                            'NULL';
                        $dmax = isset($d['max']) ?
                            '"' . $d['max'] . '"':
                            'NULL';
                        if (!@$this->database->queryExec('
                             INSERT INTO package_dependencies
                              (required, packages_name, packages_channel, deppackage,
                               depchannel, conflicts, min, max)
                             VALUES(
                              0,
                              "' . $pf->getName() . '",
                              "' . $pf->getChannel() . '",
                              "' . $d['name'] . '",
                              "' . $dchannel . '",
                              "' . isset($d['conflicts']) . '",
                              ' . $dmin . ',
                              ' . $dmax . '
                             )
                            ')) {
                            $this->database->queryExec('ROLLBACK');
                            throw new PEAR2_Registry_Exception('Error: package ' .
                                $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
                        }
                        if (isset($d['exclude'])) {
                            if (!is_array($d['exclude'])) {
                                $d['exclude'] = array($d['exclude']);
                            }
                            foreach ($d['exclude'] as $exclude) {
                                if (!@$this->database->queryExec('
                                     INSERT INTO package_dependencies_exclude
                                      (required, packages_name, packages_channel,
                                       deppackage, depchannel, exclude, conflicts)
                                     VALUES(
                                      0,
                                      "' . $pf->getName() . '",
                                      "' . $pf->getChannel() . '",
                                      "' . $d['name'] . '",
                                      "' . $dchannel . '",
                                      "' . $exclude . '",
                                      "' . isset($d['conflicts']) . '",
                                     )
                                    ')) {
                                    $this->database->queryExec('ROLLBACK');
                                    throw new PEAR2_Registry_Exception('Error: package ' .
                                        $pf->getChannel() . '/' . $pf->getName() . ' could not be installed in registry');
                                }                        
                            }
                        }
                    }
                }
            }
        }
        $this->database->queryExec('COMMIT');
    }

    function uninstallPackage($package, $channel)
    {
        $channel = $this->aliasToChannel($channel);
        if (!$this->database->singleQuery('SELECT package FROM packages WHERE package="' .
              sqlite_escape_string($package) . '" AND channel = "' .
              sqlite_escape_string($channel) . '"')) {
            throw new PEAR2_Registry_Exception('Unknown package ' . $channel . '/' .
                $package);
        }
        $this->database->queryExec('DELETE FROM packages WHERE package="' .
              sqlite_escape_string($package) . '" AND channel = "' .
              sqlite_escape_string($channel) . '"');
    }

    function upgradePackage(PEAR2_PackageFile_v2 $package)
    {
        if (!$this->database->singleQuery('SELECT package FROM packages WHERE package="' .
              sqlite_escape_string($package) . '" AND channel = "' .
              sqlite_escape_string($channel) . '"')) {
            return $this->installPackage($package);
        }
        $lastversion = $this->database->singleQuery('
                SELECT version FROM packages WHERE package="' .
              sqlite_escape_string($package) . '" AND channel = "' .
              sqlite_escape_string($channel) . '"');
        $this->uninstallPackage($package->getPackage(), $package->getChannel());
        $this->installPackage($package);
        $this->database->queryExec('UPDATE packages set lastinstalledversion="' .
            sqlite_escape_string($lastversion) . '"');
    }

    function packageExists($package, $channel)
    {
        return $this->database->singleQuery('SELECT COUNT(*) FROM packages WHERE ' .
            'package=\'' . sqlite_escape_string($package) . '\' AND channel=\'' .
            sqlite_escape_string($channel) . '\'');
    }

    function channelAlias($channel)
    {
        if ($a = $this->database->singleQuery('SELECT channel FROM channels WHERE
              alias="' . sqlite_escape_string($channel) . '"')) {
            return $a;
        }
        if ($a = $this->database->singleQuery('SELECT channel FROM channels WHERE
              channel="' . sqlite_escape_string($channel) . '"')) {
            return $a;
        }
        throw new PEAR2_Registry_Exception('Unknown channel/alias: ' . $channel);
    }

    function channelExists($channel, $strict = true)
    {
        if (!$strict && $a = $this->database->singleQuery('SELECT channel FROM channels WHERE
              alias="' . sqlite_escape_string($channel) . '"')) {
            return true;
        }
        if ($a = $this->database->singleQuery('SELECT channel FROM channels WHERE
              channel="' . sqlite_escape_string($channel) . '"')) {
            return true;
        }
        return false;
    }

    function hasMirror($channel, $mirror)
    {
        
    }

    function setAlias($channel, $alias)
    {
        $error = '';
        $this->assertChannelExists($channel);
        if (!@$this->database->queryExec('UPDATE channels SET alias=\'' .
              sqlite_escape_string($alias) . '\'', $error)) {
            throw new PEAR2_Registry_Exception('Cannot set channel ' .
                $channel . ' alias to ' . $alias . ': ' . $error);
        }
    }

    function addChannel(PEAR_ChannelFile $channel)
    {
        if ($this->database->singleQuery('SELECT channel FROM channels WHERE channel="' .
              $channel->getName() . '"')) {
            throw new PEAR2_Registry_Exception('Error: channel ' .
                $channel->getName() . ' has already been discovered');
        }
        $validate = $channel->getValidationPackage();
        $this->database->queryExec('BEGIN');
        if (!@$this->database->queryExec('
            INSERT INTO channels
            (channel, summary, suggestedalias, alias, validatepackageversion,
            validatepackage, lastmodified)
            VALUES(
            "' . $channel->getName() . '",
            "' . sqlite_escape_string($channel->getSummary()) . '",
            "' . $channel->getAlias() . '",
            "' . $channel->getAlias() . '",
            "' . $validate['attribs']['version'] . '",
            "' . $validate['_content'] . '",
            \'' . sqlite_escape_string(serialize($channel->lastModified())) . '\'
            )
            ')) {
            throw new PEAR2_Registry_Exception('Error: channel ' . $channel->getName() .
                ' could not be added to the registry');    
        }
        if (!@$this->database->queryExec('
            INSERT INTO channel_servers
            (channel, server, ssl, port)
            VALUES(
            "' . $channel->getName() . '",
            "' . $channel->getName() . '",
            ' . ($channel->getSSL() ? 1 : '0') . ',
            ' . $channel->getPort() . '
            )
            ')) {
            throw new PEAR2_Registry_Exception('Error: channel ' . $channel->getName() .
                ' could not be added to the registry');    
        }
        $servers = array(false);
        $mirrors = $channel->getMirrors();
        if (count($mirrors)) {
            foreach ($mirrors as $mirror) {
                $servers[] = $mirror['attribs']['host'];
                if (!@$this->database->queryExec('
                    INSERT INTO channel_servers
                    (channel, server, ssl, port)
                    VALUES(
                    "' . $channel->getName() . '",
                    "' . $mirror['attribs']['host'] . '",
                    ' . ($channel->getSSL($mirror['attribs']['host']) ? 1 : '0') . ',
                    ' . $channel->getPort($mirror['attribs']['host']) . '
                    )
                    ')) {
                    throw new PEAR2_Registry_Exception('Error: channel ' . $channel->getName() .
                        ' could not be added to the registry');    
                }
            }
        }
        foreach ($servers as $server) {
            foreach (array('xmlrpc', 'soap', 'rest') as $protocol) {
                $functions = $channel->getFunctions($protocol, $server);
                if (!$functions) {
                    continue;
                }
                if (!isset($functions[0])) {
                    $functions = array($functions);
                }
                $actualserver = $server ? $server : $channel->getName();
                $attrib = $protocol == 'rest' ? 'type' : 'version';
                foreach ($functions as $function) {
                    if (!@$this->database->queryExec('
                        INSERT INTO channel_server_' . $protocol . '
                        (channel, server, ' . ($protocol == 'rest' ? 'baseurl' : 'function') .
                         ', ' . $attrib . ')
                        VALUES(
                        "' . $channel->getName() . '",
                        "' . $actualserver . '",
                        "' . $function['_content'] . '",
                        "' . $function['attribs'][$attrib] . '"
                        )
                        ')) {
                        throw new PEAR2_Registry_Exception('Error: channel ' . $channel->getName() .
                            ' could not be added to the registry');    
                    }
                }
            }
        }
        $this->database->queryExec('COMMIT');
    }

    function getMirrors($channel)
    {
        return $this->database->arrayQuery('SELECT server, ssl, port FROM
            channel_servers WHERE channel = \'' . sqlite_escape_string($channel) .
            '\' AND server <> channel', SQLITE_ASSOC);
    }

    function deleteChannel($channel)
    {
        $error = '';
        if (!@$this->database->queryExec('DELETE FROM channels WHERE channel="' .
              sqlite_escape_string($channel) . '"', $error)) {
            throw new PEAR2_Registry_Exception('Cannot delete channel ' .
                $channel . ': ' . $error);
        }
    }

    function __get($var)
    {
        if ($var == 'package') {
            return new PEAR2_Registry_Sqlite_Package($this);
        }
        if ($var == 'channel') {
            return new PEAR2_Registry_Sqlite_Channel($this);
        }
    }

    function packageInfo($package, $channel, $field)
    {
        $info = @$this->database->singleQuery('
            SELECT ' . $field . ' FROM packages WHERE
            package = \'' . sqlite_escape_string($package) . '\' AND
            channel = \'' . sqlite_escape_string($channel) . '\'', true);
        if (!$info) {
            throw new PEAR2_Registry_Exception('Cannot retrieve ' . $field .
                ': ' . $this->database->error_string($this->database->lastError()));
        }
        return $info;
    }
}
