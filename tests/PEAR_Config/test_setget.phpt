--TEST--
PEAR_Config->set() and PEAR_Config->get()
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
$config = new PEAR_Config($temp_path . DIRECTORY_SEPARATOR . 'pear.ini');
// failures
$phpunit->assertFalse($config->set('__channels', 'oops'), '__channels');
$phpunit->assertFalse($config->set('###', 'oops'), '###');
$phpunit->assertFalse($config->set('php_dir', $temp_path . DIRECTORY_SEPARATOR . 'hi', 'gronk'), 'gronk layer');
// successes
$phpunit->assertTrue($config->set('data_dir', 'hi'), 'data_dir=hi');
$phpunit->assertEquals('hi', $config->get('data_dir', 'user', 'pear.php.net'),
    'confirm data_dir=hi 1');
$phpunit->assertEquals(null, $config->get('data_dir', 'system', 'pear.php.net'),
    'confirm data_dir=hi 2');
echo 'tests done';
?>
--EXPECT--
tests done