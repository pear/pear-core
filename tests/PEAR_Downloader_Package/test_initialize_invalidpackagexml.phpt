--TEST--
PEAR_Downloader_Package->initialize() with package.xml
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
    'test_initialize_invalidpackagexml'. DIRECTORY_SEPARATOR . 'invalid.xml';
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$message = version_compare(phpversion(), '5.0.0', '<') ?
    'XML error: not well-formed (invalid token) at line 1' :
    'XML error: Empty document at line 1';
$result = $dp->initialize($pathtopackagexml);
$phpunit->assertErrors(array('package' => 'PEAR_Error', 'message' => $message), 'after initialize');
$phpunit->assertEquals(array(
    array(3, '+ tmp dir created at ' . $dp->_downloader->getDownloadDir())),
    $fakelog->getLog(), 'after initialize log');
$phpunit->assertIsa('PEAR_Error', $result, 'no error returned');
$phpunit->assertEquals($message, $result->getMessage(), 'wrong error message');


$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_initialize_invalidpackagexml'. DIRECTORY_SEPARATOR . 'package.xml';
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$result = $dp->initialize($pathtopackagexml);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile', 'message' => 'Missing Package Name'),
    array('package' => 'PEAR_Error', 'message' =>
        'Parsing of package.xml from file "' . $pathtopackagexml . '" failed')),
        'after initialize');
$phpunit->assertEquals(array(
    array(3, '+ tmp dir created at ' . $dp->_downloader->getDownloadDir()),
    array(0, 'Missing Package Name')), $fakelog->getLog(), 'after initialize log');
$phpunit->assertIsa('PEAR_Error', $result, 'no error returned');
$phpunit->assertEquals('Parsing of package.xml from file "' . $pathtopackagexml . '" failed',
    $result->getMessage(), 'wrong error message');
echo 'tests done';
?>
--EXPECT--
tests done