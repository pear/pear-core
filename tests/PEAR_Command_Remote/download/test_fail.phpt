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
    array('package' => 'PEAR_Error', 'message' => 'download failed'),
), '404');
$phpunit->assertEquals( array (
  0 => 
  array (
    0 => 0,
    1 => 'Could not download from "http://www.example.com/bloo.tgz"',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'Invalid or missing remote package file',
  ),
  2 => 
  array (
    'info' => 
    array (
      'data' => 
      array (
        0 => 
        array (
          0 => 'Package "http://www.example.com/bloo.tgz" is not valid',
        ),
      ),
      'headline' => 'Download Errors',
    ),
    'cmd' => 'no command',
  ),
 )
, $fakelog->getLog(), 'log');
echo 'tests done';
?>
--EXPECT--
tests done
