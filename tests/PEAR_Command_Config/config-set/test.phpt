--TEST--
config-set command
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
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'php', $config->get('php_dir'), 'setup');
$command->run('config-set', array(), array('php_dir', 'poo'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'config-set succeeded',
    'cmd' => 'config-set',
  ),
), $fakelog->getLog(), 'ui log');
$phpunit->assertEquals('poo', $config->get('php_dir'), 'php_dir');
$phpunit->assertEquals(null, $config->get('php_dir', 'system'), 'setup system');
$command->run('config-set', array(), array('php_dir', 'poo', 'system'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'config-set succeeded',
    'cmd' => 'config-set',
  ),
), $fakelog->getLog(), 'ui log');
$phpunit->assertEquals('poo', $config->get('php_dir', 'system'), 'php_dir');
echo 'tests done';
?>
--EXPECT--
tests done
