--TEST--
PEAR_Config->getREST()
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
// kind of a silly test, I guess :)
$config = new PEAR_Config($temp_path . DIRECTORY_SEPARATOR . 'pear.ini');
$phpunit->assertIsa('PEAR_REST', $config->getREST(), 'test');
echo 'tests done';
?>
--EXPECT--
tests done
