--TEST--
PEAR_Downloader_Package::removeDuplicates()
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
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'test', 'channel' => 'pear.php.net', 'group' => 'subgroup'), 'stable'),
    array('version' => '1.0',
          'info' =>
          '<?xml version="1.0" encoding="UTF-8"?>
<package xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://pear.php.net/dtd/package-1.0.xsd" version="1.0">
 <name>test</name>
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
  <version>1.0</version>
  <date>2004-10-10</date>
  <license>PHP License</license>
  <state>beta</state>
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
          'url' => 'http://www.example.com/test-1.0.tgz'));
$dp1 = &newDownloaderPackage(array());
$result = $dp1->initialize('test#subgroup');
$phpunit->assertNoErrors('after create 1');

$dp2 = &newDownloaderPackage(array());
$result = $dp2->initialize('http://www.example.com/test-1.0.tgz');
$phpunit->assertNoErrors('after create 2');

$dp3 = &newDownloaderPackage(array());
$result = $dp3->initialize($pathtopackagexml);
$phpunit->assertNoErrors('after create 3');

$pathtopackagexml2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_removeDuplicates'. DIRECTORY_SEPARATOR . 'package.xml';
$dp4 = &newDownloaderPackage(array());
$result = $dp4->initialize($pathtopackagexml2);
$phpunit->assertNoErrors('after create 4');

$params = array(&$dp1, &$dp2, &$dp3, &$dp4);
PEAR_Downloader_Package::removeDuplicates($params);
$phpunit->assertEquals(3, count($params), 'unsuccessful removal');
$phpunit->assertEquals('test', $params[0]->getPackage(), 'first one');
$phpunit->assertEquals('subgroup', $params[0]->getGroup(), 'first one group');
$phpunit->assertEquals('test', $params[1]->getPackage(), 'second one');
$phpunit->assertEquals('default', $params[1]->getGroup(), 'second one group');
$phpunit->assertEquals('test2', $params[2]->getPackage(), 'third one');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done