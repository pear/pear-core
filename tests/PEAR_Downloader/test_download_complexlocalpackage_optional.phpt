--TEST--
PEAR_Downloader->download() with complex local package.xml [alldeps, preferred_state = alpha], optional dep
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
    'packages'. DIRECTORY_SEPARATOR . 'depspackage.xml';
$pathtobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Bar-1.5.1.tgz';
$pathtofoobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Foobar-1.4.0a1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Bar-1.5.1.tgz', $pathtobarxml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Foobar-1.4.0a1.tgz', $pathtofoobarxml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0',
         array('type' => 'pkg', 'rel' => 'ge', 'version' => '1.0.0',
               'name' => 'Bar', 'channel' => 'pear.php.net', 'package' => 'Bar'),
         array('channel' => 'pear.php.net', 'package' => 'PEAR1', 'version' => '1.4.0a1'), 'alpha'),
    array('version' => '1.5.1',
          'info' =>
'<?xml version="1.0" encoding="ISO-8859-1"?>
<!--DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0"-->
<package version="1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/package-1.0 http://pear.php.net/dtd/package-1.0.xsd">
 <name>Bar</name>
 <summary>PEAR Base System</summary>
 <description>small
 </description>
 <maintainers>
  <maintainer>
   <user>cellog</user>
   <role>lead</role>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
  </maintainer>
 </maintainers>
 <release>
  <version>1.5.1</version>
  <date>2004-10-21</date>
  <license>PHP License</license>
  <state>stable</state>
  <notes>hi
  </notes>
  <provides type="class" name="OS_Guess"/>
  <provides type="class" name="System"/>
  <provides type="function" name="md5_file"/>
  <deps>
   <dep type="pkg" rel="has" optional="yes">Foobar</dep>
  </deps>
  <filelist>
   <dir name="/">
    <file name="foo1.php" role="php"/>
   </dir>
  </filelist>
 </release>
</package>
',
          'url' => 'http://www.example.com/Bar-1.5.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0',
         array('type' => 'pkg', 'rel' => 'has', 'optional' => 'yes',
               'name' => 'Foobar',
               'channel' => 'pear.php.net', 'package' => 'Foobar'),
         array('channel' => 'pear.php.net', 'package' => 'Bar', 'version' => '1.5.1'), 'alpha'),
    array('version' => '1.4.0a1',
          'info' =>
'<?xml version="1.0" encoding="ISO-8859-1"?>
<!--DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0"-->
<package version="1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/package-1.0 http://pear.php.net/dtd/package-1.0.xsd">
 <name>Foobar</name>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception PHP5 error handling mechanism
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling of common operations
   with files and directories
 * the PEAR base class
 </description>
 <maintainers>
  <maintainer>
   <user>ssb</user>
   <role>lead</role>
   <name>Stig Bakken</name>
   <email>stig@php.net</email>
  </maintainer>
  <maintainer>
   <user>cellog</user>
   <role>lead</role>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
  </maintainer>
  <maintainer>
   <user>cox</user>
   <role>lead</role>
   <name>Tomas V.V.Cox</name>
   <email>cox@idecnet.com</email>
  </maintainer>
  <maintainer>
   <user>pajoye</user>
   <role>lead</role>
   <name>Pierre-Alain Joye</name>
   <email>pajoye@pearfr.org</email>
  </maintainer>
  <maintainer>
   <user>mj</user>
   <role>developer</role>
   <name>Martin Jansen</name>
   <email>mj@php.net</email>
  </maintainer>
 </maintainers>
 <release>
  <version>1.4.0a1</version>
  <date>2004-10-21</date>
  <license>PHP License</license>
  <state>alpha</state>
  <notes>
Installer Roles/Tasks:

 * package.xml 2.0 uses a command pattern, allowing extensibility
 * implement the replace, postinstallscript, and preinstallscript tasks

Installer Dependency Support:

 * package.xml 2.0 has continued to improve and evolve
 * Downloader/Package.php is now used to coordinate downloading.  Old code
   has not yet been deleted, as error handling is crappy right now.  Uninstall
   ordering is broken, and needs to be redone.
 * Pre-download dependency resolution works, mostly.
 * There is no way to disable dependency resolution at the moment, this will be done.
 * Dependency2.php is used by the new PEAR_Downloader_Channel to resolve dependencies
   and include downloaded files in the calculations.
 * DependencyDB.php is used to resolve complex dependencies between installed packages
   and any dependencies installed later (a conflicts/not dependency cannot be honored
   without this DB)

Installer Channel Support:

 * channel XSD is available on pearweb
 * add channel.listAll and channel.update to default PEAR protocols
 * add ability to &quot;pear channel-update channelname&quot; to
   retrieve updates manually for individual channels
 * fix channel.xml generation to use a valid schema declaration

Installer:

 * with --remoteconfig option, it is possible to remotely install and uninstall packages
   to an FTP server.  It works by mirroring a local installation, and requires a
   special, separate local install.
 * Channels implemented
 * Bug #1242: array-to-string conversion
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * Major bug in Registry - false file conflicts on data/doc/test role
   was possible (and would happen if HTML_Template_IT was installed
   and HTML_Template_Flexy installation was attempted)
  </notes>
  <provides type="class" name="OS_Guess"/>
  <provides type="class" name="System"/>
  <provides type="function" name="md5_file"/>
  <filelist>
   <dir name="/">
    <file name="foo12.php" role="php"/>
   </dir>
  </filelist>
 </release>
 <changelog>
  <release>
   <version>1.3.3</version>
   <date>2004-10-28</date>
   <state>stable</state>
   <notes>
Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common $_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role="doc"

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users
   </notes>
  </release>
 </changelog>
</package>
',
          'url' => 'http://www.example.com/Foobar-1.4.0a1'));
$_test_dep->setPHPVersion('4.3.11');
$_test_dep->setPEARVersion('1.4.0a1');
$dp = &new test_PEAR_Downloader($fakelog, array('alldeps' => true), $config);
$phpunit->assertNoErrors('after create');
$config->set('preferred_state', 'alpha');
$result = &$dp->download(array($pathtopackagexml));
$phpunit->assertEquals(3, count($result), 'return');
$phpunit->assertIsa('test_PEAR_Downloader_Package', $result[0], 'right class 0');
$phpunit->assertIsa('PEAR_Downloader_Package', $result[1], 'right class 1');
$phpunit->assertIsa('PEAR_Downloader_Package', $result[2], 'right class 2');
$phpunit->assertIsa('PEAR_PackageFile_v1', $pf = $result[0]->getPackageFile(), 'right kind of pf 0');
$phpunit->assertIsa('PEAR_PackageFile_v1', $pf1 = $result[1]->getPackageFile(), 'right kind of pf 1');
$phpunit->assertIsa('PEAR_PackageFile_v1', $pf2 = $result[2]->getPackageFile(), 'right kind of pf 2');
$phpunit->assertEquals('PEAR1', $pf->getPackage(), 'right package');
$phpunit->assertEquals('pear.php.net', $pf->getChannel(), 'right channel');
$phpunit->assertEquals('Bar', $pf1->getPackage(), 'right package 1');
$phpunit->assertEquals('pear.php.net', $pf1->getChannel(), 'right channel 1');
$phpunit->assertEquals('Foobar', $pf2->getPackage(), 'right package 2');
$phpunit->assertEquals('pear.php.net', $pf2->getChannel(), 'right channel 2');
$dlpackages = $dp->getDownloadedPackages();
$phpunit->assertEquals(3, count($dlpackages), 'downloaded packages count');
$phpunit->assertEquals(3, count($dlpackages[0]), 'internals package count');
$phpunit->assertEquals(3, count($dlpackages[1]), 'internals package count 1');
$phpunit->assertEquals(3, count($dlpackages[2]), 'internals package count 2');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[0]), 'indexes');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[1]), 'indexes 1');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[2]), 'indexes 2');
$phpunit->assertEquals($pathtopackagexml,
    $dlpackages[0]['file'], 'file');
$phpunit->assertIsa('PEAR_PackageFile_v1',
    $dlpackages[0]['info'], 'info');
$phpunit->assertEquals('PEAR1',
    $dlpackages[0]['pkg'], 'PEAR1');
$phpunit->assertEquals($result[1]->_downloader->getDownloadDir() . DIRECTORY_SEPARATOR .
    'Bar-1.5.1.tgz',
    $dlpackages[1]['file'], 'file 1');
$phpunit->assertIsa('PEAR_PackageFile_v1',
    $dlpackages[1]['info'], 'info 1');
$phpunit->assertEquals('Bar',
    $dlpackages[1]['pkg'], 'Bar');
$phpunit->assertEquals($result[2]->_downloader->getDownloadDir() . DIRECTORY_SEPARATOR .
    'Foobar-1.4.0a1.tgz',
    $dlpackages[2]['file'], 'file 2');
$phpunit->assertIsa('PEAR_PackageFile_v1',
    $dlpackages[2]['info'], 'info 2');
$phpunit->assertEquals('Foobar',
    $dlpackages[2]['pkg'], 'Foobar');
$after = $dp->getDownloadedPackages();
$phpunit->assertEquals(0, count($after), 'after getdp count');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading Bar-1.5.1.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download Bar-1.5.1.tgz (610 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.',
  ),
  4 => 
  array (
    0 => 1,
    1 => '...done: 610 bytes',
  ),
  5 => 
  array (
    0 => 1,
    1 => 'downloading Foobar-1.4.0a1.tgz ...',
  ),
  6 => 
  array (
    0 => 1,
    1 => 'Starting to download Foobar-1.4.0a1.tgz (2,062 bytes)',
  ),
  7 => 
  array (
    0 => 1,
    1 => '...done: 2,062 bytes',
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
    1 => 'Bar-1.5.1.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'Bar-1.5.1.tgz',
      1 => '610',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 610,
  ),
  4 => 
  array (
    0 => 'done',
    1 => 610,
  ),
  5 => 
  array (
    0 => 'setup',
    1 => 'self',
  ),
  6 => 
  array (
    0 => 'saveas',
    1 => 'Foobar-1.4.0a1.tgz',
  ),
  7 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'Foobar-1.4.0a1.tgz',
      1 => '2062',
    ),
  ),
  8 => 
  array (
    0 => 'bytesread',
    1 => 1024,
  ),
  9 => 
  array (
    0 => 'bytesread',
    1 => 2048,
  ),
  10 => 
  array (
    0 => 'bytesread',
    1 => 2062,
  ),
  11 => 
  array (
    0 => 'done',
    1 => 2062,
  ),
), $fakelog->getDownload(), 'download callback messages');
echo 'tests done';
?>
--EXPECT--
tests done