--TEST--
PEAR_Config->remove()
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
$phpunit->assertTrue($config->set('umask', '123'), 'umask set');
$phpunit->assertEquals('123', $config->get('umask'), 'after set');
$phpunit->assertFalse($config->remove('foo'), 'foo');
$phpunit->assertTrue($config->remove('umask'), 'umask');
$phpunit->assertEquals('0', $config->get('umask'), 'after remove');
echo 'tests done';
?>
--EXPECT--
tests done
