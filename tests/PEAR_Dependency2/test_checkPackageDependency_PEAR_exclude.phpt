--TEST--
PEAR_Dependency2->checkPackageDependency() exclude failure
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$dep = &new test_PEAR_Dependency2($config, array(), array('channel' => 'pear.php.net',
    'package' => 'mine'), PEAR_VALIDATE_INSTALLING);
$phpunit->assertNoErrors('create 1');

require_once 'PEAR/PackageFile/v1.php';
$package = new PEAR_PackageFile_v1;
$package->setPackage('foo');
$package->setSummary('foo');
$package->setDescription('foo');
$package->setDate('2004-10-01');
$package->setLicense('PHP License');
$package->setVersion('1.0');
$package->setState('stable');
$package->setNotes('foo');
$package->addFile('/', 'foo.php', array('role' => 'php'));
$package->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$reg = $config->getRegistry();
$reg->addPackage2($package);

$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.9',
        'max' => '1.9',
        'exclude' => '1.0',
    ), true, array());
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error',
          'message' => 'channel://pear.php.net/mine is not compatible with installed package "channel://pear.php.net/foo" version 1.0')
), 'exclude 1');
$phpunit->assertIsa('PEAR_Error', $result, 'exclude 1');

$reg->deletePackage('foo');

$dep = &new test_PEAR_Dependency2($config, array(), array('channel' => 'pear.php.net',
    'package' => 'mine'), PEAR_VALIDATE_DOWNLOADING);
$phpunit->assertNoErrors('create 1');


require_once 'PEAR/Downloader/Package.php';
require_once 'PEAR/Downloader.php';
$down = new PEAR_Downloader($fakelog, array(), $config);
$dp = &new PEAR_Downloader_Package($down);
$dp->initialize(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'package.xml');
$params = array(&$dp);

$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.9',
        'max' => '1.9',
        'exclude' => '1.0',
    ), true, $params);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error',
          'message' => 'channel://pear.php.net/mine is not compatible with downloaded package "channel://pear.php.net/foo" version 1.0')
), 'exclude 2');
$phpunit->assertIsa('PEAR_Error', $result, 'exclude 2');

echo 'tests done';
?>
--EXPECT--
tests done