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
          '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>foo</name>
 <channel>pear.php.net</channel>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 </description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2005-01-01</date>
 <version>
  <release>1.10</release>
  <api>1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>
  This is a major milestone release for PEAR.  In addition to several killer features,
 </notes>
 <contents>
  <dir name="/">
   <file name="template.spec" role="data" />
  </dir> <!-- / -->
 </contents>
 <compatible>
  <name>mine</name>
  <channel>pear.php.net</channel>
  <min>0.9</min>
  <max>2.0</max>
 </compatible>
 <dependencies>
  <required>
   <php>
    <min>4.2</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease/>
</package>',
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
$phpunit->assertEquals(array(
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
), $fakelog->getLog(), 'compatible local works log');
$phpunit->assertTrue($result, 'compatible local works');
echo 'tests done';
?>
--EXPECT--
tests done