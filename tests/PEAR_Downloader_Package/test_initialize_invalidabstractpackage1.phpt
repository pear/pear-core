--TEST--
PEAR_Downloader_Package->initialize() with invalid abstract package (Package not found)
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
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'test', 'channel' => 'pear.php.net'), 'stable'),
    false);
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$result = $dp->initialize('test');
$phpunit->assertErrors(array(array('package' => 'PEAR_Error', 'message' =>
    'No releases for package "pear/test" exist'),
    array('package' => 'PEAR_Error', 'message' =>
    "Cannot initialize 'test', invalid or missing package file"),
), 'after initialize');

$dd_dir = $dp->_downloader->getDownloadDir();

if (!empty($dd_dir) && is_dir($dd_dir)) {
    $phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'No releases for package "pear/test" exist',
  ),
), $fakelog->getLog(), 'log messages');
} else {
    $phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'No releases for package "pear/test" exist',
  ),
  1 =>
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
), $fakelog->getLog(), 'log messages');
}

$phpunit->assertEquals(array (), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertIsa('PEAR_Error', $result, 'after initialize');
$phpunit->assertNull($dp->getPackageFile(), 'downloadable test');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
