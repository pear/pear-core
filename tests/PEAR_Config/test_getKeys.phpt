--TEST--
PEAR_Config->getKeys()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$config = new PEAR_Config;
$phpunit->assertEquals(array (
  0 => 'master_server',
  1 => 'preferred_state',
  2 => 'cache_dir',
  3 => 'php_dir',
  4 => 'ext_dir',
  5 => 'data_dir',
  6 => 'doc_dir',
  7 => 'test_dir',
  8 => 'bin_dir',
  9 => 'default_channel',
  10 => 'remote_config',
  11 => 'auto_discover',
  12 => 'http_proxy',
  13 => 'php_bin',
  14 => 'username',
  15 => 'password',
  16 => 'verbose',
  17 => 'umask',
  18 => 'cache_ttl',
  19 => 'sig_type',
  20 => 'sig_bin',
  21 => 'sig_keyid',
  22 => 'sig_keydir',
), $config->getKeys(), 'keys');
echo 'tests done';
?>
--EXPECT--
tests done
