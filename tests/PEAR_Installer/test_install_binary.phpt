--TEST--
PEAR_Installer->install() (binary package)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
require_once 'PEAR/PackageFile.php';
$_test_dep->setOs('windows');
$_test_dep->setPEARVersion('1.4.0dev13');
$_test_dep->setPHPVersion('5.0.0');

$pf = &new test_PEAR_PackageFile($config);
$oldpackage = &$pf->fromPackageFile(dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'test_install_binary' . DIRECTORY_SEPARATOR . 'package.xml', PEAR_VALIDATE_INSTALLING);
$phpunit->assertNoErrors('oldpackage');
$package = new test_PEAR_PackageFile_v2;
$package->setConfig($config);
$package->setPackagefile(dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'test_install_binary' . DIRECTORY_SEPARATOR . 'package.xml');
$package->fromArray($oldpackage->getArray());

$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_install_binary'. DIRECTORY_SEPARATOR . 'test-1.1.0.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('channel' => 'pear.php.net', 'package' => 'test', ), 'stable'),
    array('version' => '1.1.0',
          'info' =>
          '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>test</name>
 <channel>pear.php.net</channel>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 </description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.1.0</release>
  <api>1.1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>Installer Roles/Tasks:
 </notes>
 <contents>
  <dir name="/">
   <file name="template.spec" role="data" />
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
   <os>
    <name>windows</name>
   </os>
  </required>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/test-1.1.0'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('channel' => 'pear.php.net','package' => 'fail'), 'stable'),
    array('version' => '1.0',
          'info' =>
          '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>fail</name>
 <channel>pear.php.net</channel>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 </description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.0</release>
  <api>1.1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>Installer Roles/Tasks:
 </notes>
 <contents>
  <dir name="/">
   <file name="template.spec" role="data" />
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
   <os>
    <name>linux</name>
   </os>
  </required>
 </dependencies>
 <phprelease/>
</package>',
          'url' => 'http://www.example.com/fail-1.0'));


$phpunit->assertNoErrors('setup');
$dp = &new test_PEAR_Downloader_Package($installer);
$dp->setPackageFile($package);
$params = array(&$dp);
$installer->setDownloadedPackages($params);
$phpunit->assertNoErrors('prior to install');
$package->installBinary($installer);
$phpunit->assertNoErrors('install');
$tampered = $fakelog->getLog();
if (OS_WINDOWS) {
$phpunit->assertEquals(array (
      0 => 
      array (
        0 => 0,
        1 => 'Attempting to download binary version of extension "foo"',
      ),
      1 => 
      array (
        0 => 3,
        1 => '+ tmp dir created at ' . $last_dl->getDownloadDir(),
      ),
      2 => 
      array (
        0 => 0,
        1 => 'Cannot install pear/fail on windows operating system, can only install on linux',
      ),
      3 => 
      array (
        0 => 1,
        1 => 'downloading test-1.1.0.tgz ...',
      ),
      4 => 
      array (
        0 => 1,
        1 => 'Starting to download test-1.1.0.tgz (721 bytes)',
      ),
      5 => 
      array (
        0 => 1,
        1 => '.',
      ),
      6 => 
      array (
        0 => 1,
        1 => '...done: 721 bytes',
      ),
      7 => 
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $last_dl->getDownloadDir()) . DIRECTORY_SEPARATOR . 'test-1.1.0' .
            DIRECTORY_SEPARATOR . 'foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      8 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      9 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll 1',
      ),
      10 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR .
            'foo.dll ' . $ext_dir . ' ' . DIRECTORY_SEPARATOR
      ),
      11 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      12 => 
      array (
        0 => 3,
        1 => '+ mv ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      13 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      14 => 
      array (
        0 => 0,
        1 => 'Download and install of binary extension "pear/test" successful',
      ),
    ), $tampered, 'install');
} else {
    $phpunit->assertEquals(array (
      0 => 
      array (
        0 => 0,
        1 => 'Attempting to download binary version of extension "foo"',
      ),
      1 => 
      array (
        0 => 3,
        1 => '+ tmp dir created at ' . $last_dl->getDownloadDir(),
      ),
      2 => 
      array (
        0 => 0,
        1 => 'Cannot install pear/fail on windows operating system, can only install on linux',
      ),
      3 => 
      array (
        0 => 1,
        1 => 'downloading test-1.1.0.tgz ...',
      ),
      4 => 
      array (
        0 => 1,
        1 => 'Starting to download test-1.1.0.tgz (721 bytes)',
      ),
      5 => 
      array (
        0 => 1,
        1 => '.',
      ),
      6 => 
      array (
        0 => 1,
        1 => '...done: 721 bytes',
      ),
      7 => 
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $last_dl->getDownloadDir()) . DIRECTORY_SEPARATOR . 'test-1.1.0' .
            DIRECTORY_SEPARATOR . 'foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      8 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      9 =>
      array (
        0 => 3,
        1 => 'adding to transaction: chmod 644 ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      10 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll 1',
      ),
      11 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR .
            'foo.dll ' . $ext_dir . ' ' . DIRECTORY_SEPARATOR
      ),
      12 => 
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      13 => 
      array (
        0 => 3,
        1 => '+ chmod 644 ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      14 => 
      array (
        0 => 3,
        1 => '+ mv ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      15 => 
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      16 => 
      array (
        0 => 0,
        1 => 'Download and install of binary extension "pear/test" successful',
      ),
    ), $tampered, 'install');
}
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'setup',
    1 => 'self',
  ),
  1 => 
  array (
    0 => 'saveas',
    1 => 'test-1.1.0.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'test-1.1.0.tgz',
      1 => '721',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 721,
  ),
  4 => 
  array (
    0 => 'done',
    1 => 721,
  ),
), $fakelog->getDownload(), 'install');
echo 'tests done';
?>
--EXPECT--
tests done