--TEST--
PEAR_Config->readFTPConfigFile()
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
$config = new PEAR_Config($temp_path . DIRECTORY_SEPARATOR . 'pear.ini');
include_once dirname(__FILE__) . '/test_readFTPConfigFile/FTP.php.inc';
$ftp = &Net_FTP::singleton();
$ftp->addRemoteFile('/path/to/pear/config.ini', dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'test_readFTPConfigFile' . DIRECTORY_SEPARATOR . 'remote.ini');
$ftp->setDirsExisting(array('/path/to/pear'));
$e = $config->readFTPConfigFile('ftp://example.com/path/to/pear/config.ini');
$phpunit->assertNoErrors('test');
$phpunit->assertTrue($e, 'test');
$phpunit->assertEquals('/path/to/pear/pear/php', $config->get('php_dir', 'ftp'), 'php_dir');
$phpunit->assertEquals('/path/to/pear/pear/data', $config->get('data_dir', 'ftp'), 'data_dir');
$phpunit->assertEquals('/path/to/pear/pear/ext', $config->get('ext_dir', 'ftp'), 'ext_dir');
$phpunit->assertEquals('/path/to/pear/pear', $config->get('bin_dir', 'ftp'), 'bin_dir');
$phpunit->assertEquals('/path/to/pear/pear/docs', $config->get('doc_dir', 'ftp'), 'doc_dir');
$phpunit->assertEquals('/path/to/pear/pear/tests', $config->get('test_dir', 'ftp'), 'test_dir');
echo 'tests done';
?>
--EXPECT--
tests done
