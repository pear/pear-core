--TEST--
PEAR_Downloader_Package::analyzeDependencies package.xml 2.0 [force]
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';

$_test_dep->setPhpversion('4.0');
$_test_dep->setPEARVersion('1.4.0dev13');

$mainpackage = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'main-1.0.tgz';
$requiredpackage = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'required-1.1.tgz';
$sub1package = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'sub1-1.1.tgz';
$sub2package = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'sub2-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/main-1.0.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/required-1.1.tgz', $requiredpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/sub1-1.0.tgz', $sub1package);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/sub2-1.0.tgz', $sub2package);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'main', 'channel' => 'pear.php.net', 'group' => 'foo'), 'stable'),
    array('version' => '1.0',
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
    <name>required</name>
    <channel>pear.php.net</channel>
    <min>1.1</min>
   </package>
  </required>
  <optional>
   <package>
    <name>optional</name>
    <channel>pear.php.net</channel>
    <min>1.1</min>
   </package>
  </optional>
  <group name="foo" hint="testing group">
   <package>
    <name>sub1</name>
    <channel>pear.php.net</channel>
    <min>1.1</min>
   </package>
   <package>
    <name>sub2</name>
    <channel>pear.php.net</channel>
    <min>1.1</min>
   </package>
  </group>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/main-1.0'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'optional', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    false);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'required', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    false);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'sub1', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    array('version' => '1.1',
          'info' =>
          '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>sub1</name>
 <channel>pear.php.net</channel>
 <summary>Subgroup Package 1</summary>
 <description>Subgroup Package 2</description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.1</release>
  <api>1.1</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>test</notes>
 <contents>
  <dir name="/">
   <file baseinstalldir="/" name="sub1.php" role="php" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/sub1-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'sub2', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    false);
$dp = &newFakeDownloaderPackage(array('force' => true));
$result = $dp->initialize('main#foo');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
$phpunit->assertNoErrors('after detect');
$phpunit->assertEquals(array (
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
  array (
    0 => 0,
    1 => 'No releases for package "pear/required" exist',
  ),
  array (
    0 => 3,
    1 => 'Notice: package "pear/main" optional dependency "pear/optional" will not be automatically downloaded',
  ),
  array (
    0 => 0,
    1 => 'No releases for package "pear/optional" exist',
  ),
  array (
    0 => 0,
    1 => 'No releases for package "pear/sub2" exist',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 1');

$err = $dp->_downloader->analyzeDependencies($params);
$phpunit->assertNoErrors('end');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'warning: pear/main requires PHP (version >= 4.2, version <= 6.0.0), installed version is 4.0',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'warning: pear/main requires package "pear/required" (version >= 1.1)',
  ),
  2 => 
  array (
    0 => 0,
    1 => 'pear/main can optionally use package "pear/optional" (version >= 1.1)',
  ),
  3 => 
  array (
    0 => 0,
    1 => 'pear/main can optionally use package "pear/sub2" (version >= 1.1)',
  ),
), $fakelog->getLog(), 'end log');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'end download');

echo 'tests done';
?>
--EXPECT--
tests done