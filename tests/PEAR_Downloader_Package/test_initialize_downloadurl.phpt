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
$phpunit->assertNull($result, 'after initialize');
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