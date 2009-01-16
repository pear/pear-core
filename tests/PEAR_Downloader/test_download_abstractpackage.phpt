--TEST--
PEAR_Downloader->download() with downloadable abstract package
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
require_once $dir . 'setup.php.inc';
$pathtopackagexml = $dir .'packages'. DIRECTORY_SEPARATOR . 'test-1.0.tgz';

$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tgz', $pathtopackagexml);

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
</package>
',
          'url' => 'http://www.example.com/test-1.0'));

$dp = &new test_PEAR_Downloader($fakelog, array(), $config);
$phpunit->assertNoErrors('after create');
$result = $dp->download(array('test'));
$phpunit->assertEquals(1, count($result), 'return');
$phpunit->assertIsa('test_PEAR_Downloader_Package', $result[0], 'right class');
$phpunit->assertIsa('PEAR_PackageFile_v1', $pf = $result[0]->getPackageFile(), 'right kind of pf');
$phpunit->assertEquals('test', $pf->getPackage(), 'right package');
$phpunit->assertEquals('pear.php.net', $pf->getChannel(), 'right channel');
$dlpackages = $dp->getDownloadedPackages();
$phpunit->assertEquals(1, count($dlpackages), 'downloaded packages count');
$phpunit->assertEquals(3, count($dlpackages[0]), 'internals package count');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[0]), 'indexes');
$phpunit->assertEquals($dp->getDownloadDir() . DIRECTORY_SEPARATOR . 'test-1.0.tgz',
    $dlpackages[0]['file'], 'file');
$phpunit->assertIsa('PEAR_PackageFile_v1',
    $dlpackages[0]['info'], 'info');
$phpunit->assertEquals('test',
    $dlpackages[0]['pkg'], 'test');
$after = $dp->getDownloadedPackages();
$phpunit->assertEquals(0, count($after), 'after getdp count');

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
), $fakelog->getLog(), 'log messages');

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
), $fakelog->getDownload(), 'download callback messages');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
