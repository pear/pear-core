--TEST--
PEAR_Downloader_Package->detectDependencies(), required dep package.xml 2.0 static uri
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$mainpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'main-1.0.tgz';
$requiredpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'foo-1.0.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/main-1.0.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/foo-1.0.tgz', $requiredpackage);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'main', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.1',
          'info' =>
'<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>main</name>
 <channel>pear.php.net</channel>
 <summary>Main Package</summary>
 <description>Main Package</description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.0</release>
  <api>1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>test</notes>
 <contents>
  <dir name="/">
   <file baseinstalldir="/" name="main.php" role="php" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
   <package>
    <name>foo</name>
    <uri>http://www.example.com/foo-1.0</uri>
   </package>
  </required>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/main-1.0'));
$dp = &newDownloaderPackage(array());
$result = $dp->initialize('main');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
$phpunit->assertNoErrors('after detect');

$dd_dir =  $dp->_downloader->getDownloadDir();
if (!empty($dd_dir) && is_dir($dd_dir)) {
    $phpunit->assertEquals(array(), $fakelog->getLog(), 'log messages');
} else {
    $phpunit->assertEquals(array(
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
), $fakelog->getLog(), 'log messages');
}

$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 1');
$phpunit->assertEquals(array (
  array (
    0 => 1,
    1 => 'downloading foo-1.0.tgz ...',
  ),
  array (
    0 => 1,
    1 => 'Starting to download foo-1.0.tgz (638 bytes)',
  ),
  array (
    0 => 1,
    1 => '.',
  ),
  array (
    0 => 1,
    1 => '...done: 638 bytes',
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
    1 => 'foo-1.0.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'foo-1.0.tgz',
      1 => '638',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 638,
  ),
  4 => 
  array (
    0 => 'done',
    1 => 638,
  ),
), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertTrue($result, 'first return');
$phpunit->assertEquals(2, count($params), 'mergeDependencies');
$phpunit->assertEquals('main', $params[0]->getPackage(), 'main package');
$phpunit->assertEquals('foo', $params[1]->getPackage(), 'foo package');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 2');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertFalse($result, 'second return');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
