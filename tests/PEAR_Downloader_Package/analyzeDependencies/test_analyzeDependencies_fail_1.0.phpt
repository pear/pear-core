--TEST--
PEAR_Downloader_Package::analyzeDependencies() fail tests package.xml 1.0
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$mainpackage = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'mainold-1.1.tgz';
$requiredpackage = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'required-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/mainold-1.1.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/required-1.1.tgz', $requiredpackage);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'mainold', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.1',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'mainold',
            'license' => 'PHP License',
            'summary' => 'Main Package',
            'description' => 'Main Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'xsdversion' => '1.0',
            'deps' =>
            array(
                array(
                    'type' => 'pkg',
                    'rel' => 'ge',
                    'name' => 'optional',
                    'version' => '1.1',
                    'optional' => 'yes',
                ),
                array(
                    'type' => 'pkg',
                    'rel' => 'ge',
                    'name' => 'required',
                    'version' => '1.1',
                ),
                array(
                    'type' => 'ext',
                    'name' => 'foo',
                    'rel' => 'has',
                )
            ),
          ),
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
        'channel' =>
            "pear.php.net",
        'package' =>
            "required",
        ),
        array('channel' => 'pear.php.net', 'package' => 'mainold', 'version' => '1.1'), 'stable'),
    array('version' => '1.1',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'optional',
            'license' => 'PHP License',
            'summary' => 'Required Package',
            'description' => 'Required Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
          ),
          'url' => 'http://www.example.com/required-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0', array(
        'type' =>
            "pkg",
        'rel' =>
            "ge",
        'name' =>
            "optional",
        'version' =>
            "1.1",
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
          array(
            'channel' => 'pear.php.net',
            'package' => 'required',
            'license' => 'PHP License',
            'summary' => 'Required Package',
            'description' => 'Required Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
          ),
          'url' => 'http://www.example.com/required-1.1'));
$dp = &newFakeDownloaderPackage(array());
$result = $dp->initialize('mainold');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('setup');

$checker = &test_PEAR_Dependency2::singleton();
$checker->setExtensions(array('bar' => '1.0'));
$err = test_PEAR_Downloader_Package::analyzeDependencies($params);
$phpunit->assertNoErrors('end');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'Notice: package "pear.php.net/mainold" optional dependency "channel://pear.php.net/optional" will not be automatically downloaded, use --alldeps to automatically download required and optional dependencies',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'Notice: package "pear.php.net/mainold" required dependency "channel://pear.php.net/required" will not be automatically downloaded, use --alldeps to automatically download required and optional dependencies, --onlyreqdeps to automatically download only required dependencies',
  ),
  2 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/mainold-1.1 can optionally use package "channel://pear.php.net/optional" version 1.1 or greater',
  ),
  3 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/mainold-1.1 requires package "channel://pear.php.net/required" version 1.1 or greater',
  ),
  4 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/mainold-1.1 requires PHP extension "foo"',
  ),
), $fakelog->getLog(), 'end log');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'end download');

$dp = newFakeDownloaderPackage(array('nodeps' => true));
$result = $dp->initialize('mainold');
$phpunit->assertNoErrors('after create 2');
$err = test_PEAR_Downloader_Package::analyzeDependencies($params);
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'channel://pear.php.net/mainold-1.1 can optionally use package "channel://pear.php.net/optional" version 1.1 or greater',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'warning: channel://pear.php.net/mainold-1.1 requires package "channel://pear.php.net/required" version 1.1 or greater',
  ),
  2 => 
  array (
    0 => 0,
    1 => 'warning: channel://pear.php.net/mainold-1.1 requires PHP extension "foo"',
  ),
), $fakelog->getLog(), 'end log 2');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'end download 2');
echo 'tests done';
?>
--EXPECT--
tests done