--TEST--
PEAR_Downloader_Package->detectDependencies(), optional dep package.xml 1.0 --onlyreqdeps
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
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'mainold-1.1.tgz';
$requiredpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'required-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/mainold-1.1.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/required-1.1.tgz', $requiredpackage);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'mainold', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.1',
          'info' =>
          '<?xml version="1.0" encoding="UTF-8"?>
<package xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://pear.php.net/dtd/package-1.0.xsd" version="1.0">
 <name>mainold</name>
 <summary>Main Package</summary>
 <description>Main Package</description>
 <maintainers>
  <maintainer>
   <user>cellog</user>
   <role>lead</role>
   <email>cellog@php.net</email>
   <name>Greg Beaver</name>
  </maintainer>
 </maintainers>
 <release>
  <version>1.1</version>
  <date>2004-10-10</date>
  <license>PHP License</license>
  <state>stable</state>
  <notes>test</notes>
  <deps>
   <dep type="pkg" rel="ge" name="required" version="1.1" optional="yes">required</dep>
  </deps>
  <filelist>
   <dir name="test" baseinstalldir="test">
    <file name="test.php" role="php"/>
    <file name="test2.php" role="php" install-as="hi.php"/>
    <file name="test3.php" role="php" install-as="another.php" platform="windows"/>
    <file name="test4.php" role="data">
     <replace from="@1@" to="version" type="package-info"/>
     <replace from="@2@" to="data_dir" type="pear-config"/>
     <replace from="@3@" to="DIRECTORY_SEPARATOR" type="php-const"/>
    </file>
   </dir>
  </filelist>
 </release>
 <changelog>
  <release>
   <version>1.0</version>
   <date>2004-10-10</date>
   <license>PHP License</license>
   <state>stable</state>
   <notes>test</notes>
  </release>
 </changelog>
</package>
',
          'url' => 'http://www.example.com/mainold-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0', array(
        'type' =>
            "pkg",
        'rel' =>
            "ge",
        'name' =>
            "required",
        'version' =>
            "1.1",
        'optional' =>
            "yes",
        'channel' =>
            "pear.php.net",
        'package' =>
            "required",
        ),
        array('channel' => 'pear.php.net', 'package' => 'mainold', 'version' => '1.1'), 'stable'),
    array('version' => '1.1',
          'info' =>
          '<?xml version="1.0" encoding="UTF-8"?>
<package xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://pear.php.net/dtd/package-1.0.xsd" version="1.0">
 <name>required</name>
 <summary>Required Package</summary>
 <description>Required Package</description>
 <maintainers>
  <maintainer>
   <user>cellog</user>
   <role>lead</role>
   <email>cellog@php.net</email>
   <name>Greg Beaver</name>
  </maintainer>
 </maintainers>
 <release>
  <version>1.1</version>
  <date>2004-10-10</date>
  <license>PHP License</license>
  <state>stable</state>
  <notes>test</notes>
  <filelist>
   <dir name="test" baseinstalldir="test">
    <file name="test.php" role="php"/>
    <file name="test2.php" role="php" install-as="hi.php"/>
    <file name="test3.php" role="php" install-as="another.php" platform="windows"/>
    <file name="test4.php" role="data">
     <replace from="@1@" to="version" type="package-info"/>
     <replace from="@2@" to="data_dir" type="pear-config"/>
     <replace from="@3@" to="DIRECTORY_SEPARATOR" type="php-const"/>
    </file>
   </dir>
  </filelist>
 </release>
 <changelog>
  <release>
   <version>1.0</version>
   <date>2004-10-10</date>
   <license>PHP License</license>
   <state>stable</state>
   <notes>test</notes>
  </release>
 </changelog>
</package>
',
          'url' => 'http://www.example.com/required-1.1'));
$dp = &newDownloaderPackage(array('onlyreqdeps' => true));
$result = $dp->initialize('mainold');
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
    0 => 3,
    1 => 'Notice: package "pear/mainold" optional dependency "pear/required" will not be automatically downloaded',
  ),
  array (
    0 => 1,
    1 => 'Did not download dependencies: pear/required, use --alldeps or --onlyreqdeps to download automatically',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge');
$phpunit->assertFalse($result, 'return of mergeDependencies');
$phpunit->assertEquals(1, count($params), 'mergeDependencies');
$phpunit->assertEquals('mainold', $params[0]->getPackage(), 'main package');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done