--TEST--
install command, package.xml 1.0 package depends on pecl
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$chan = $reg->getChannel('pecl.php.net');
$chan->resetREST();
$reg->updateChannel($chan);
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'dependsonpecl.xml';
PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'radius',
    'channel' => 'pear.php.net',
    'package' => 'radius',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
    'version' => '1.4.0a1',
  ),
  3 => 'stable',
), new PEAR_Error('no package "radius" exists'));
PEAR::popErrorHandling();
$pearweb->addXmlrpcConfig("pecl.php.net", "package.getDepDownloadURL", array (
  0 => '2.0',
  1 => 
  array (
    'channel' => 'pecl.php.net',
    'name' => 'radius',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
    'version' => '1.4.0a1',
  ),
  3 => 'stable',
),     array('version' => '1.5.2',
             'info' => '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>peclpkg</name>
 <channel>pecl.php.net</channel>
 <summary>extension package source package</summary>
 <description>extension source
 </description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.3.0</release>
  <api>1.3.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>stuff
 </notes>
 <contents>
  <dir name="/">
   <file name="foo.php" role="src"/>
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2.0</min>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <providesextension>extpkg</providesextension>
 <extsrcrelease/>
</package>',
             'url' => 'http://pecl.php.net/get/peclpackage-1.3.0')
);
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'install failed')
), 'after install');
$dl = &$command->getDownloader(1, array());
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dl->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 3,
    1 => 'Notice: package "pear/PEAR" required dependency "pecl/radius" will not be automatically downloaded',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Did not download dependencies: pecl/radius, use --alldeps or --onlyreqdeps to download automatically',
  ),
  3 => 
  array (
    0 => 0,
    1 => 'pear/PEAR requires package "pear/radius"',
  ),
  4 => 
  array (
    'info' => 
    array (
      'data' => 
      array (
        0 => 
        array (
          0 => 'No valid packages found',
        ),
      ),
      'headline' => 'Install Errors',
    ),
    'cmd' => 'no command',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals( array (
  0 => 
  array (
    0 => 'pear.php.net',
    1 => 'package.getDepDownloadURL',
    2 => 
    array (
      0 => '1.0',
      1 => 
      array (
        'type' => 'pkg',
        'rel' => 'has',
        'name' => 'radius',
        'channel' => 'pear.php.net',
        'package' => 'radius',
      ),
      2 => 
      array (
        'channel' => 'pear.php.net',
        'package' => 'PEAR',
        'version' => '1.4.0a1',
      ),
      3 => 'stable',
    ),
  ),
  1 => 
  array (
    0 => 'pecl.php.net',
    1 => 'package.getDepDownloadURL',
    2 => 
    array (
      0 => '2.0',
      1 => 
      array (
        'channel' => 'pecl.php.net',
        'name' => 'radius',
      ),
      2 => 
      array (
        'channel' => 'pear.php.net',
        'package' => 'PEAR',
        'version' => '1.4.0a1',
      ),
      3 => 'stable',
    ),
  ),
 )
, $pearweb->getXmlrpcCalls(), 'xmlrpc calls');
echo 'tests done';
?>
--EXPECT--
tests done
