--TEST--
install command, binary package install
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
$ch = new PEAR_ChannelFile;
$ch->setName('smork');
$ch->setSummary('smork');
$ch->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$phpunit->assertTrue($reg->addChannel($ch), 'smork setup');
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'package2.xml';
$pathtobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Bar-1.5.2.tgz';
$pathtofoobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Foobar-1.5.0a1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Bar-1.5.2.tgz', $pathtobarxml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Foobar-1.5.0a1.tgz', $pathtofoobarxml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0',
         array('name' => 'Bar', 'channel' => 'pear.php.net', 'min' => '1.0.0'),
         array('channel' => 'pear.php.net', 'package' => 'PEAR1', 'version' => '1.5.0a1'), 'alpha'),
    array('version' => '1.5.2',
          'info' =>
          '<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0 http://pear.php.net/dtd/tasks-1.0.xsd http://pear.php.net/dtd/package-2.0 http://pear.php.net/dtd/package-2.0.xsd">
 <name>Bar</name>
 <channel>pear.php.net</channel>
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
 * the PEAR base class</description>
 <lead>
  <name>Stig Bakken</name>
  <user>ssb</user>
  <email>stig@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Tomas V.V.Cox</name>
  <user>cox</user>
  <email>cox@idecnet.com</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Pierre-Alain Joye</name>
  <user>pajoye</user>
  <email>pajoye@pearfr.org</email>
  <active>yes</active>
 </lead>
 <developer>
  <name>Martin Jansen</name>
  <user>mj</user>
  <email>mj@php.net</email>
  <active>yes</active>
 </developer>
 <date>2004-12-29</date>
 <time>21:21:51</time>
 <version>
  <release>1.5.2</release>
  <api>1.5.2</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>Installer Roles/Tasks:

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
   and HTML_Template_Flexy installation was attempted)</notes>
 <contents>
  <dir name="/">
   <file md5sum="ed0384ad29e60110b310a02e95287ee6" name="foo1.php" role="php" />
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.3.6</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a1</min>
   </pearinstaller>
   <package>
    <name>Foobar</name>
    <channel>smork</channel>
   </package>
  </required>
 </dependencies>
 <phprelease />
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
 * fix warning in PEAR_Registry for windows 98 users</notes>
  </release>
 </changelog>
</package>',
          'url' => 'http://www.example.com/Bar-1.5.2'));
$GLOBALS['pearweb']->addXmlrpcConfig('smork', 'package.getDepDownloadURL',
    array('2.0',
         array('name' => 'Foobar', 'channel' => 'smork'),
         array('channel' => 'pear.php.net', 'package' => 'Bar', 'version' => '1.5.2'), 'alpha'),
    array('version' => '1.5.0a1',
          'info' =>
          '<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0 http://pear.php.net/dtd/tasks-1.0.xsd http://pear.php.net/dtd/package-2.0 http://pear.php.net/dtd/package-2.0.xsd">
 <name>Foobar</name>
 <channel>smork</channel>
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
 * the PEAR base class</description>
 <lead>
  <name>Stig Bakken</name>
  <user>ssb</user>
  <email>stig@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Tomas V.V.Cox</name>
  <user>cox</user>
  <email>cox@idecnet.com</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Pierre-Alain Joye</name>
  <user>pajoye</user>
  <email>pajoye@pearfr.org</email>
  <active>yes</active>
 </lead>
 <developer>
  <name>Martin Jansen</name>
  <user>mj</user>
  <email>mj@php.net</email>
  <active>yes</active>
 </developer>
 <date>2004-12-29</date>
 <time>21:23:24</time>
 <version>
  <release>1.5.0a1</release>
  <api>1.5.0a1</api>
 </version>
 <stability>
  <release>alpha</release>
  <api>alpha</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>Installer Roles/Tasks:

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
   and HTML_Template_Flexy installation was attempted)</notes>
 <contents>
  <dir name="/">
   <file md5sum="ed0384ad29e60110b310a02e95287ee6" name="foo12.php" role="php" />
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.3.6</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a1</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
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
 * fix warning in PEAR_Registry for windows 98 users</notes>
  </release>
 </changelog>
</package>',
          'url' => 'http://www.example.com/Foobar-1.5.0a1'));
$_test_dep->setPHPVersion('4.3.11');
$_test_dep->setPEARVersion('1.4.0a1');
$config->set('preferred_state', 'alpha');
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
$dl = &$command->getDownloader(1, array());
if (OS_WINDOWS) {
    $phpunit->assertEquals(array (
      array (
        0 => 3,
        1 => 'Downloading "http://www.example.com/Bar-1.5.2.tgz"',
      ),
      array (
        0 => 1,
        1 => 'downloading Bar-1.5.2.tgz ...',
      ),
      array (
        0 => 1,
        1 => 'Starting to download Bar-1.5.2.tgz (2,212 bytes)',
      ),
      array (
        0 => 1,
        1 => '.',
      ),
      array (
        0 => 1,
        1 => '...done: 2,212 bytes',
      ),
      array (
        0 => 3,
        1 => 'Downloading "http://www.example.com/Foobar-1.5.0a1.tgz"',
      ),
      array (
        0 => 1,
        1 => 'downloading Foobar-1.5.0a1.tgz ...',
      ),
      array (
        0 => 1,
        1 => 'Starting to download Foobar-1.5.0a1.tgz (2,207 bytes)',
      ),
      array (
        0 => 1,
        1 => '...done: 2,207 bytes',
      ),
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . DIRECTORY_SEPARATOR . 'Foobar-1.5.0a1'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://smork/Foobar-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . ''  . DIRECTORY_SEPARATOR . 'Bar-1.5.2'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/Bar-1.5.2',
        ),
        'cmd' => 'install',
      ),
      array (
        0 => 3,
        1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php',
      ),
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/PEAR1-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
    ), $fakelog->getLog(), 'log messages');
} else {
    // Don't forget umask ! permission of new file is 0666
    $umask = decoct(0666 & ( 0777 - umask()));
    $phpunit->assertEquals(array (
      array (
        0 => 3,
        1 => 'Downloading "http://www.example.com/Bar-1.5.2.tgz"',
      ),
      array (
        0 => 1,
        1 => 'downloading Bar-1.5.2.tgz ...',
      ),
      array (
        0 => 1,
        1 => 'Starting to download Bar-1.5.2.tgz (2,212 bytes)',
      ),
      array (
        0 => 1,
        1 => '.',
      ),
      array (
        0 => 1,
        1 => '...done: 2,212 bytes',
      ),
      array (
        0 => 3,
        1 => 'Downloading "http://www.example.com/Foobar-1.5.0a1.tgz"',
      ),
      array (
        0 => 1,
        1 => 'downloading Foobar-1.5.0a1.tgz ...',
      ),
      array (
        0 => 1,
        1 => 'Starting to download Foobar-1.5.0a1.tgz (2,207 bytes)',
      ),
      array (
        0 => 1,
        1 => '...done: 2,207 bytes',
      ),
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . DIRECTORY_SEPARATOR . 'Foobar-1.5.0a1'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: chmod '.$umask.' ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      array (
        0 => 3,
        1 => '+ chmod '.$umask.' ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://smork/Foobar-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . ''  . DIRECTORY_SEPARATOR . 'Bar-1.5.2'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: chmod '.$umask.' ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      array (
        0 => 3,
        1 => '+ chmod '.$umask.' ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/Bar-1.5.2',
        ),
        'cmd' => 'install',
      ),
      array (
        0 => 3,
        1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: chmod '.$umask.' ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ',
      ),
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      array (
        0 => 3,
        1 => '+ chmod '.$umask.' ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php',
      ),
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/PEAR1-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
    ), $fakelog->getLog(), 'log messages');
}
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
