--TEST--
download command failure
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
$reg = &$config->getRegistry();
$e = $command->run('download', array(), array('http://www.example.com/bloo.tgz'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'Could not download from "http://www.example.com/bloo.tgz"'),
    array('package' => 'PEAR_Error', 'message' => 'Invalid or missing remote package file'),
    array('package' => 'PEAR_Error', 'message' => 'download failed'),
), '404');
echo 'tests done';
?>
--EXPECT--
tests done
