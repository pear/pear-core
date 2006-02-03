--TEST--
config-help command
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$e = $command->run('config-help', array(), array());
$phpunit->assertNoErrors('test');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Config help',
      'headline' => 
      array (
        0 => 'Name',
        1 => 'Type',
        2 => 'Description',
      ),
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => 'master_server',
          1 => 'string',
          2 => 'name of the main PEAR server [NOT USED IN THIS VERSION]',
        ),
        1 => 
        array (
          0 => 'preferred_state',
          1 => 'set',
          2 => 'the installer will prefer releases with this state when installing packages without a version or state specified
Valid set: stable beta alpha devel snapshot',
        ),
        2 => 
        array (
          0 => 'cache_dir',
          1 => 'directory',
          2 => 'directory which is used for XMLRPC cache',
        ),
        3 => 
        array (
          0 => 'php_dir',
          1 => 'directory',
          2 => 'directory where .php files are installed',
        ),
        4 => 
        array (
          0 => 'ext_dir',
          1 => 'directory',
          2 => 'directory where loadable extensions are installed',
        ),
        5 => 
        array (
          0 => 'data_dir',
          1 => 'directory',
          2 => 'directory where data files are installed',
        ),
        6 => 
        array (
          0 => 'doc_dir',
          1 => 'directory',
          2 => 'directory where documentation is installed',
        ),
        7 => 
        array (
          0 => 'test_dir',
          1 => 'directory',
          2 => 'directory where regression tests are installed',
        ),
        8 => 
        array (
          0 => 'bin_dir',
          1 => 'directory',
          2 => 'directory where executables are installed',
        ),
        9 => 
        array (
          0 => 'default_channel',
          1 => 'string',
          2 => 'the default channel to use for all non explicit commands',
        ),
        10 => 
        array (
          0 => 'preferred_mirror',
          1 => 'string',
          2 => 'the default server or mirror to use for channel actions',
        ),
        11 => 
        array (
          0 => 'remote_config',
          1 => 'password',
          2 => 'ftp url of remote configuration file to use for synchronized install',
        ),
        12 => 
        array (
          0 => 'auto_discover',
          1 => 'integer',
          2 => 'whether to automatically discover new channels',
        ),
        13 => 
        array (
          0 => 'http_proxy',
          1 => 'string',
          2 => 'HTTP proxy (host:port) to use when downloading packages',
        ),
        14 => 
        array (
          0 => 'php_bin',
          1 => 'file',
          2 => 'PHP CLI/CGI binary for executing scripts',
        ),
        15 => 
        array (
          0 => 'username',
          1 => 'string',
          2 => '(maintainers) your PEAR account name',
        ),
        16 => 
        array (
          0 => 'password',
          1 => 'password',
          2 => '(maintainers) your PEAR account password',
        ),
        17 => 
        array (
          0 => 'verbose',
          1 => 'integer',
          2 => 'verbosity level
0: really quiet
1: somewhat quiet
2: verbose
3: debug',
        ),
        18 => 
        array (
          0 => 'umask',
          1 => 'mask',
          2 => 'umask used when creating files (Unix-like systems only)',
        ),
        19 => 
        array (
          0 => 'cache_ttl',
          1 => 'integer',
          2 => 'amount of secs where the local cache is used and not updated',
        ),
        20 => 
        array (
          0 => 'sig_type',
          1 => 'set',
          2 => 'which package signature mechanism to use
Valid set: gpg',
        ),
        21 => 
        array (
          0 => 'sig_bin',
          1 => 'string',
          2 => 'which package signature mechanism to use',
        ),
        22 => 
        array (
          0 => 'sig_keyid',
          1 => 'string',
          2 => 'which key to use for signing with',
        ),
        23 => 
        array (
          0 => 'sig_keydir',
          1 => 'directory',
          2 => 'directory where signature keys are located',
        ),
      ),
    ),
    'cmd' => 'config-help',
  ),
), $fakelog->getLog(), 'log');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
