--TEST--
PEAR_Dependency2->checkPackageDependency() recommended version (downloaded)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
require_once 'PEAR/PackageFile/v1.php';
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'foo', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.10',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'foo',
            'license' => 'PHP License',
            'summary' => 'Main Package',
            'description' => 'Main Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
            'compatible' =>
            array(
                'name' => 'mine',
                'channel' => 'pear.php.net',
                'min' => '0.9',
                'max' => '2.0',
            ),
            'deps' =>
            array(
                'required' =>
                array(
                    'php' =>
                    array(
                        'min' => '4.2',
                        'max' => '6.0.0',
                        ),
                    'pearinstaller' =>
                    array(
                        'min' => '1.4.0dev13',
                        ),
                ),
            ),
          ),
          'url' => 'http://www.example.com/main-1.0'));
$dep = &new test_PEAR_Dependency2($config, array(), array('channel' => 'pear.php.net',
    'package' => 'mine'), PEAR_VALIDATE_DOWNLOADING);
$phpunit->assertNoErrors('create 1');
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
        'max' => '1.13',
        'recommended' => '1.9'
    ), true, $params);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error',
          'message' => 'pear/mine dependency package "pear/foo" downloaded version 1.0 is not the recommended version 1.9, but may be compatible, use --force to install')
), 'recommended 1');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'recommended 1 log');
$phpunit->assertIsa('PEAR_Error', $result, 'recommended 1');

$pf = &$dp->getPackageFile();
$pf->setVersion('1.9');
$dp->setPackageFile($pf);

$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.9',
        'max' => '1.9',
        'recommended' => '1.9'
    ), true, $params);
$phpunit->assertNoErrors('recommended works');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'recommended works log');
$phpunit->assertTrue($result, 'recommended works');

$dp = &new PEAR_Downloader_Package($down);
$dp->initialize(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'compatpackage.xml');

$parent = new PEAR_PackageFile_v1;
$parent->setPackage('mine');
$parent->setSummary('foo');
$parent->setDescription('foo');
$parent->setDate('2004-10-01');
$parent->setLicense('PHP License');
$parent->setVersion('1.10');
$parent->setState('stable');
$parent->setNotes('foo');
$parent->addFile('/', 'foo.php', array('role' => 'php'));
$parent->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$parent->setConfig($config);
$dl = &new test_PEAR_Downloader($fakelog, array(), $config);
$dp2 = &new test_PEAR_Downloader_Package($dl);
$dp2->setPackageFile($parent);
$params = array(&$dp, &$dp2);

$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.9',
        'max' => '2.0',
        'recommended' => '1.8'
    ), true, $params);
$phpunit->assertNoErrors('compatible local works');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'compatible local works log');
$phpunit->assertTrue($result, 'compatible local works');

$dp = &newFakeDownloaderPackage(array());
$dp->initialize('foo');
$params = array(&$dp, &$dp2);
$result = $dep->validatePackageDependency(
    array(
        'name' => 'foo',
        'channel' => 'pear.php.net',
        'min' => '0.9',
        'max' => '2.0',
        'recommended' => '1.8'
    ), true, $params);
$phpunit->assertNoErrors('compatible local works');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'compatible local works log');
$phpunit->assertTrue($result, 'compatible local works');
echo 'tests done';
?>
--EXPECT--
tests done