--TEST--
PEAR_Downloader_Package->initialize() with downloadable package.tgz
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_initialize_downloadurl'. DIRECTORY_SEPARATOR . 'test-1.0.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tgz', $pathtopackagexml);
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$result = $dp->initialize('http://www.example.com/test-1.0.tgz');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading test-1.0.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download test-1.0.tgz (785 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.',
  ),
  4 => 
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
$phpunit->assertTrue($result, 'after initialize');
$phpunit->assertNotNull($file = &$dp->getPackageFile(), 'downloadable test');
$phpunit->assertEquals('test', $file->getPackage(), 'package name test');
$phpunit->assertEquals($dp->_downloader->getDownloadDir() . '/package.xml',
    $file->getPackageFile(), 'package location test');
$phpunit->assertEquals($dp->_downloader->getDownloadDir() . DIRECTORY_SEPARATOR . 'test-1.0.tgz',
    $file->getArchiveFile(), 'package archive location test');
echo 'tests done';
?>
--EXPECT--
tests done