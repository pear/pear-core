--TEST--
PEAR_Downloader_Package::analyzeDependencies() fail tests package.xml 1.0 [soft]
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
    'packages'. DIRECTORY_SEPARATOR . 'mainold-1.1.tgz';
$requiredpackage = dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'required-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/mainold-1.1.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/required-1.1.tgz', $requiredpackage);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'mainold', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.1',
          'info' =>
          '<?xml version="1.0"?>
<package version="1.0">
 <name>mainold</name>
 <summary>Main Package</summary>
 <description>Main Package</description>
 <maintainers>
  <maintainer>
   <name>Greg Beaver</name>
   <role>lead</role>
   <user>cellog</user>
   <email>cellog@php.net</email>
  </maintainer>
 </maintainers>
 <date>2004-09-30</date>
 <release>
  <version>1.1</version>
  <state>stable</state>
  <license>PHP License</license>
  <notes>test</notes>
  <filelist>
   <dir name="/">
    <file baseinstalldir="/" name="main.php" role="php" />
   </dir> <!-- / -->
  </filelist>
  <deps>
   <dep type="pkg" name="optional" version="1.1" rel="ge" optional="yes"/>
   <dep type="pkg" name="required" version="1.1" rel="ge"/>
   <dep type="ext" name="foo" rel="has"/>
  </deps>
 </release>
</package>',
          'url' => 'http://www.example.com/mainold-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0', array(
        'type' =>
            "pkg",
        'name' =>
            "optional",
        'version' =>
            "1.1",
        'rel' =>
            "ge",
        'optional' =>
            "yes",
        'channel' =>
            "pear.php.net",
        'package' =>
            "optional",
        ),
        array('channel' => 'pear.php.net', 'package' => 'mainold', 'version' => '1.1'), 'stable'),
    array('version' => '1.1',
          'info' =>
          '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>optional</name>
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
  <release>1.1</release>
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
  </required>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/optional-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0', array(
        'type' =>
            "pkg",
        'name' =>
            "required",
        'version' =>
            "1.1",
        'rel' =>
            "ge",
        'channel' =>
            "pear.php.net",
        'package' =>
            "required",
        ),
        array('channel' => 'pear.php.net', 'package' => 'mainold', 'version' => '1.1'), 'stable'),
    array('version' => '1.1',
          'info' =>
          '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>required</name>
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
  <release>1.1</release>
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
  </required>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/required-1.1'));
$dp = &newFakeDownloaderPackage(array('soft' => true));
$result = $dp->initialize('mainold');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('setup');

$_test_dep->setExtensions(array('bar' => '1.0'));
$err = $dp->_downloader->analyzeDependencies($params);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' =>
        'Cannot install, dependencies failed')
), 'end');
$phpunit->assertEquals(array (
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
), $fakelog->getLog(), 'end log');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'end download');
echo 'tests done';
?>
--EXPECT--
tests done