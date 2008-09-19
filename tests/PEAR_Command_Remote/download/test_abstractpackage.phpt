--TEST--
download command (abstract package)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(1803);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'test-1.0.tgz';
$pathtopackagexml2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'test-1.0.tar';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tar', $pathtopackagexml2);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'test', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.0',
          'info' =>
          '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0dev13">
 <name>test</name>
 <summary>test</summary>
 <description>test
 </description>
 <maintainers>
  <maintainer>
   <user>cellog</user>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
   <role>lead</role>
  </maintainer>
  </maintainers>
 <release>
  <version>1.0</version>
  <date>2004-10-10</date>
  <license>PHP License</license>
  <state>stable</state>
  <notes>test
  </notes>
  <filelist>
   <file role="php" baseinstalldir="test" md5sum="31140babf23de55c049f8b56818133eb" name="test/test.php"/>
   <file role="php" baseinstalldir="test" md5sum="31140babf23de55c049f8b56818133eb" install-as="hi.php" name="test/test2.php"/>
   <file role="php" baseinstalldir="test" md5sum="31140babf23de55c049f8b56818133eb" platform="windows" install-as="another.php" name="test/test3.php"/>
   <file role="data" baseinstalldir="test" md5sum="31140babf23de55c049f8b56818133eb" name="test/test4.php">
    <replace from="@1@" to="version" type="package-info"/>
    <replace from="@2@" to="data_dir" type="pear-config"/>
    <replace from="@3@" to="DIRECTORY_SEPARATOR" type="php-const"/>
   </file>
  </filelist>
 </release>
 <changelog>
   <release>
    <version>1.0</version>
    <date>2004-10-10</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>test
    </notes>
   </release>
 </changelog>
</package>',
          'url' => 'http://www.example.com/test-1.0'));
mkdir($temp_path . DIRECTORY_SEPARATOR . 'bloob');
chdir($temp_path . DIRECTORY_SEPARATOR . 'bloob');
$e = $command->run('download', array(), array('test'));
$phpunit->assertNoErrors('download');
$phpunit->assertEquals(array (
  array (
    0 => 3,
    1 => 'Downloading "http://www.example.com/test-1.0.tgz"',
  ),
  array (
    0 => 1,
    1 => 'downloading test-1.0.tgz ...',
  ),
  array (
    0 => 1,
    1 => 'Starting to download test-1.0.tgz (785 bytes)',
  ),
  array (
    0 => 1,
    1 => '.',
  ),
  array (
    0 => 1,
    1 => '...done: 785 bytes',
  ),
  array (
    'info' => 'File ' . $temp_path . DIRECTORY_SEPARATOR . 'bloob' .
        DIRECTORY_SEPARATOR . 'test-1.0.tgz downloaded',
    'cmd' => 'download',
  ),
), $fakelog->getLog(), 'log');
$phpunit->assertEquals(array (
  0 =>
  array (
    0 => 'setup',
    1 => 'self',
  ),
  1 =>
  array (
    0 => 'saveas',
    1 => 'test-1.0.tgz',
  ),
  2 =>
  array (
    0 => 'start',
    1 =>
    array (
      0 => 'test-1.0.tgz',
      1 => '785',
    ),
  ),
  3 =>
  array (
    0 => 'bytesread',
    1 => 785,
  ),
  4 =>
  array (
    0 => 'done',
    1 => 785,
  ),
), $fakelog->getDownload(), 'download log');

$e = $command->run('download', array('nocompress' => true), array('test'));
$phpunit->assertNoErrors('download --nocompress');
$phpunit->assertEquals(array (
  array (
    0 => 3,
    1 => 'Downloading "http://www.example.com/test-1.0.tar"',
  ),
  array (
    0 => 1,
    1 => 'downloading test-1.0.tar ...',
  ),
  array (
    0 => 1,
    1 => 'Starting to download test-1.0.tar (6,656 bytes)',
  ),
  array (
    0 => 1,
    1 => '...done: 6,656 bytes',
  ),
  array (
    'info' => 'File ' . $temp_path . DIRECTORY_SEPARATOR . 'bloob' .
        DIRECTORY_SEPARATOR . 'test-1.0.tar downloaded',
    'cmd' => 'download',
  ),
), $fakelog->getLog(), '--nocompress log');
$phpunit->assertEquals(array (
  0 =>
  array (
    0 => 'setup',
    1 => 'self',
  ),
  1 =>
  array (
    0 => 'saveas',
    1 => 'test-1.0.tar',
  ),
  2 =>
  array (
    0 => 'start',
    1 =>
    array (
      0 => 'test-1.0.tar',
      1 => '6656',
    ),
  ),
  3 =>
  array (
    0 => 'bytesread',
    1 => 1024,
  ),
  4 =>
  array (
    0 => 'bytesread',
    1 => 2048,
  ),
  5 =>
  array (
    0 => 'bytesread',
    1 => 3072,
  ),
  6 =>
  array (
    0 => 'bytesread',
    1 => 4096,
  ),
  7 =>
  array (
    0 => 'bytesread',
    1 => 5120,
  ),
  8 =>
  array (
    0 => 'bytesread',
    1 => 6144,
  ),
  9 =>
  array (
    0 => 'bytesread',
    1 => 6656,
  ),
  10 =>
  array (
    0 => 'done',
    1 => 6656,
  ),
), $fakelog->getDownload(), 'download --nocompress log');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
