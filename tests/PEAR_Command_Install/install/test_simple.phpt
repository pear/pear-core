--TEST--
install command, simplest possible test
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
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'simplepackage.xml';
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
$dl = &$command->getDownloader();
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dl->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 3,
    1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php',
  ),
  2 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php ',
  ),
  3 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php ' . DIRECTORY_SEPARATOR,
  ),
  4 => 
  array (
    0 => 2,
    1 => 'about to commit 2 file operations',
  ),
  5 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php',
  ),
  6 => 
  array (
    0 => 2,
    1 => 'successfully committed 2 file operations',
  ),
  7 => 
  array (
    'info' => 
    array (
      'data' => 'install ok: channel://pear.php.net/PEAR-1.4.0a1',
    ),
    'cmd' => 'install',
   ),
), $fakelog->getLog(), 'log messages');

echo 'tests done';
?>
--EXPECT--
tests done
