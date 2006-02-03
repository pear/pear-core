--TEST--
PEAR_Downloader_Package->initialize() with unknown channel, auto_discover off
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
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_initialize_abstractpackage_discover'. DIRECTORY_SEPARATOR . 'channel.xml';
$csize = filesize($pathtochannelxml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.foo.com/channel.xml', $pathtochannelxml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.foo.com', 'package.getDownloadURL',
    array(array('package' => 'test', 'channel' => 'pear.foo.com'), 'stable'),
    array('version' => '1.0',
          'info' =>
          '<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>foo</name>
 <channel>pear.foo.com</channel>
 <summary>test</summary>
 <description>foo
hi there
 
 </description>
 <lead>
  <name>person</name>
  <user>single</user>
  <email>joe@example.com</email>
  <active>yes</active>
 </lead>
 <date>2004-12-10</date>
 <time>21:39:43</time>
 <version>
  <release>1.0</release>
  <api>1.0</api>
 </version>
 <stability>
  <release>beta</release>
  <api>beta</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>here are the
multi-line
release notes
  
 </notes>
 <contents>
  <dir name="/">
   <dir name="sunger">
    <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.dat" role="data">
     <tasks:replace from="@pv@" to="version" type="package-info" />
    </file>
   </dir> <!-- //sunger -->
   <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.php" role="php">
    <tasks:replace from="@pv@" to="version" type="package-info" />
   </file>
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.3.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a1</min>
   </pearinstaller>
   <package>
    <name>Console_Getopt</name>
    <channel>pear.php.net</channel>
    <max>1.2</max>
    <exclude>1.2</exclude>
   </package>
  </required>
  <optional>
   <extension>
    <name>xmlrpc</name>
    <min>1.0</min>
   </extension>
  </optional>
 </dependencies>
 <phprelease>
  <installconditions>
   <os>
    <name>*</name>
   </os>
  </installconditions>
  <filelist>
   <install as="merbl.dat" name="sunger/foo.dat" />
   <install as="merbl.php" name="foo.php" />
  </filelist>
 </phprelease>
 <changelog>
  <release>
   <version>
    <release>1.3.3</release>
    <api>1.3.3</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2004-10-28</date>
   <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
   <notes>Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common $_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role=&quot;doc&quot;

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users
   
   </notes>
  </release>
  <release>
   <version>
    <release>1.3.2</release>
    <api>1.3.2</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2004-10-28</date>
   <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
   <notes>Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common $_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role=&quot;doc&quot;

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users
   
   </notes>
  </release>
 </changelog>
</package>',
          'url' => 'http://www.example.com/test-1.0.tgz'));
$dp = &newDownloaderPackage(array());
$phpunit->assertNoErrors('after create');
$result = $dp->initialize('pear.foo.com/test');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' =>
        'invalid package name/package file "pear.foo.com/test"'),
    array('package' => 'PEAR_Error', 'message' =>
        "Cannot initialize 'pear.foo.com/test', invalid or missing package file",
    )), 'wrong errors');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 1,
    1 => 'Attempting to discover channel "pear.foo.com"...',
  ),
  1 =>
  array (
    0 => 1,
    1 => 'downloading channel.xml ...',
  ),
  2 =>
  array (
    0 => 1,
    1 => 'Starting to download channel.xml (' . $csize . ' bytes)',
  ),
  3 =>
  array (
    0 => 1,
    1 => '.',
  ),
  4 =>
  array (
    0 => 1,
    1 => '...done: ' . $csize . ' bytes',
  ),
  5 =>
  array (
    0 => 0,
    1 => 'Channel "pear.foo.com" is not initialized, use "pear channel-discover pear.foo.com" to initializeor pear config-set auto_discover 1',
  ),
  6 =>
  array (
    0 => 0,
    1 => 'unknown channel "pear.foo.com" in "pear.foo.com/test"',
  ),
  7 =>
  array (
    0 => 0,
    1 => 'invalid package name/package file "pear.foo.com/test"',
  ),
  8 =>
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
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
    1 => 'channel.xml',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'channel.xml',
      1 => "$csize",
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => $csize,
  ),
  4 => 
  array (
    0 => 'done',
    1 => $csize,
  ),
), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertIsa('PEAR_Error', $result, 'after initialize');
$phpunit->assertNull($dp->getPackageFile(), 'downloadable test');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done