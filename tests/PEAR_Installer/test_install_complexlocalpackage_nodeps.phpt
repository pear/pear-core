--TEST--
PEAR_Installer->install() with complex local package.xml [nodeps, preferred_state = alpha]
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
    'packages'. DIRECTORY_SEPARATOR . 'Bar-1.5.0.tgz';
$pathtofoobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Foobar-1.4.0a1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Bar-1.5.0.tgz', $pathtobarxml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Foobar-1.4.0a1.tgz', $pathtofoobarxml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0',
         array('type' => 'pkg', 'rel' => 'ge', 'version' => '1.0.0',
               'name' => 'Bar', 'channel' => 'pear.php.net', 'package' => 'Bar'),
         array('channel' => 'pear.php.net', 'package' => 'PEAR1', 'version' => '1.4.0a1'), 'alpha'),
    array('version' => '1.5.0',
          'info' =>
          array(
            'package' => 'Bar',
            'channel' => 'pear.php.net',
            'license' => 'PHP License',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'deps' =>
            array(
                array(
                    'type' => 'pkg',
                    'rel' => 'has',
                    'name' => 'Foobar',
                    'optional' => 'no',
                )
            ),
          ),
          'url' => 'http://www.example.com/Bar-1.5.0'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('1.0',
         array('type' => 'pkg', 'rel' => 'has',
               'name' => 'Foobar', 'optional' => 'no',
               'channel' => 'pear.php.net', 'package' => 'Foobar'),
         array('channel' => 'pear.php.net', 'package' => 'Bar', 'version' => '1.5.0'), 'alpha'),
    array('version' => '1.4.0a1',
          'info' =>
          array(
            'package' => 'Foobar',
            'channel' => 'pear.php.net',
            'license' => 'PHP License',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'alpha',
          ),
          'url' => 'http://www.example.com/Foobar-1.4.0a1'));
$_test_dep->setPHPVersion('4.3.11');
$_test_dep->setPEARVersion('1.4.0a1');
$dp = &new test_PEAR_Downloader($fakelog, array('nodeps' => true), $config);
$phpunit->assertNoErrors('after create');
$config->set('preferred_state', 'alpha');
$result = &$dp->download(array($pathtopackagexml));
$phpunit->assertEquals(1, count($result), 'return');
$phpunit->assertIsa('test_PEAR_Downloader_Package', $result[0], 'right class 0');
$phpunit->assertIsa('PEAR_PackageFile_v1', $pf = $result[0]->getPackageFile(), 'right kind of pf 0');
$phpunit->assertEquals('PEAR1', $pf->getPackage(), 'right package');
$phpunit->assertEquals('pear.php.net', $pf->getChannel(), 'right channel');
$dlpackages = $dp->getDownloadedPackages();
$phpunit->assertEquals(1, count($dlpackages), 'downloaded packages count');
$phpunit->assertEquals(3, count($dlpackages[0]), 'internals package count');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[0]), 'indexes');
$phpunit->assertEquals($pathtopackagexml,
    $dlpackages[0]['file'], 'file');
$phpunit->assertIsa('PEAR_PackageFile_v1',
    $dlpackages[0]['info'], 'info');
$phpunit->assertEquals('PEAR1',
    $dlpackages[0]['pkg'], 'PEAR1');
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
    0 => 0,
    1 => 'warning: pear/PEAR1 requires package "pear/Bar" (version >= 1.0.0)',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array (
), $fakelog->getDownload(), 'download callback messages');

$installer->setOptions($dp->getOptions());
$installer->sortPackagesForInstall($result);
$installer->setDownloadedPackages($result);
$phpunit->assertNoErrors('set of downloaded packages');
$ret = &$installer->install($result[0], $dp->getOptions());
$phpunit->assertNoErrors('after install');
$phpunit->assertEquals(array (
  'provides' => 
  array (
    'class;OS_Guess' => 
    array (
      'type' => 'class',
      'name' => 'OS_Guess',
      'explicit' => true,
    ),
    'class;System' => 
    array (
      'type' => 'class',
      'name' => 'System',
      'explicit' => true,
    ),
    'function;md5_file' => 
    array (
      'type' => 'function',
      'name' => 'md5_file',
      'explicit' => true,
    ),
  ),
  'filelist' => 
  array (
    'foo.php' => 
    array (
      'role' => 'php',
      'installed_as' => $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php',
    ),
  ),
  'xsdversion' => '1.0',
  'package' => 'PEAR1',
  'summary' => 'PEAR Base System',
  'description' => 'The PEAR package contains:
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
 
',
  'maintainers' => 
  array (
    0 => 
    array (
      'handle' => 'ssb',
      'role' => 'lead',
      'name' => 'Stig Bakken',
      'email' => 'stig@php.net',
    ),
    1 => 
    array (
      'handle' => 'cellog',
      'role' => 'lead',
      'name' => 'Greg Beaver',
      'email' => 'cellog@php.net',
    ),
    2 => 
    array (
      'handle' => 'cox',
      'role' => 'lead',
      'name' => 'Tomas V.V.Cox',
      'email' => 'cox@idecnet.com',
    ),
    3 => 
    array (
      'handle' => 'pajoye',
      'role' => 'lead',
      'name' => 'Pierre-Alain Joye',
      'email' => 'pajoye@pearfr.org',
    ),
    4 => 
    array (
      'handle' => 'mj',
      'role' => 'developer',
      'name' => 'Martin Jansen',
      'email' => 'mj@php.net',
    ),
  ),
  'version' => '1.4.0a1',
  'release_date' => '2004-10-21',
  'release_license' => 'PHP License',
  'release_state' => 'alpha',
  'release_notes' => 'Installer Roles/Tasks:

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
 * add ability to "pear channel-update channelname" to
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
  
',
  'release_deps' =>
  array (
    1 =>
    array (
      'type' => 'php',
      'rel' => 'ge',
      'version' => '4.2.0',
    ),
    2 =>
    array (
      'type' => 'pkg', 
      'rel' => 'not',
      'name' => 'Foo',
    ),
    3 => array (
      'type' => 'pkg',
      'rel' => 'ge',
      'version' => '1.0.0',
      'name' => 'Bar',
    ),
  ),
  'changelog' => 
  array (
    0 => 
    array (
      'version' => '1.3.3',
      'release_date' => '2004-10-28',
      'release_state' => 'stable',
      'release_notes' => 'Installer:
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
   
',
    ),
  ),
  'dirtree' => 
  array (
    $temp_path . DIRECTORY_SEPARATOR . 'php' => true,
  ),
), $ret, 'return of install');
$phpunit->assertFileExists($temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php',
    'installed file');
$reg = &$config->getRegistry();
$info = $reg->packageInfo('PEAR1');
$phpunit->assertTrue(isset($info['_lastmodified']), 'lastmodified is set?');
unset($info['_lastmodified']);
$phpunit->assertEquals($ret, $info, 'test installation, PEAR1');
if (OS_WINDOWS) {
    $phpunit->assertEquals(array (
      0 => 
      array (
        0 => 0,
        1 => 'warning: pear/PEAR1 requires package "pear/Bar" (version >= 1.0.0)',
      ),
      1 => 
      array (
        0 => 3,
        1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      2 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php ',
      ),
      3 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php ' . DIRECTORY_SEPARATOR,
      ),
      4 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      5 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php',
      ),
      6 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
    ), $fakelog->getLog(), 'log');
} else {
    $phpunit->assertEquals(array (
      0 => 
      array (
        0 => 0,
        1 => 'warning: pear/PEAR1 requires package "pear/Bar" (version >= 1.0.0)',
      ),
      1 => 
      array (
        0 => 3,
        1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      2 => 
      array (
        0 => 3,
        1 => 'adding to transaction: chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      3 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php ',
      ),
      4 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php ' . DIRECTORY_SEPARATOR,
      ),
      5 => 
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      6 => 
      array (
        0 => 3,
        1 => '+ chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      7 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'foo.php',
      ),
      8 => 
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
    ), $fakelog->getLog(), 'log');
}
echo 'tests done';
?>
--EXPECT--
tests done