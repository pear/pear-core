--TEST--
PEAR_PackageFile_Parser_v2->installBinary()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
require_once 'PEAR/ChannelFile.php';
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_installBinary'. DIRECTORY_SEPARATOR . 'foo_win-1.1.0.tgz';
$pathtopackagexml2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_installBinary'. DIRECTORY_SEPARATOR . 'foo_linux-1.1.0.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/foo_win-1.1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addXmlrpcConfig('grob', 'package.getDownloadURL',
    array(array('channel' => 'grob', 'package' => 'foo_win', ), 'stable'),
    array('version' => '1.1.0',
          'info' =>
          array(
            'channel' => 'grob',
            'package' => 'foo_win',
            'license' => 'PHP License',
            'summary' => 'Test Package',
            'description' => 'Test Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
            'deps' =>
            array(
                'required' =>
                array(
                    'php' =>
                    array(
                        'min' => '4.2',
                        'max' => '6.0.0',
                        ),
                    'pearinstaller' =>
                    array(
                        'min' => '1.4.0dev13',
                        ),
                    'os' =>
                    array(
                        'name' => 'windows'
                        ),
                ),
            ),
          ),
          'url' => 'http://www.example.com/foo_win-1.1.0'));
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/foo_linux-1.1.0.tgz', $pathtopackagexml2);
$GLOBALS['pearweb']->addXmlrpcConfig('grob', 'package.getDownloadURL',
    array(array('channel' => 'grob', 'package' => 'foo_linux', ), 'stable'),
    array('version' => '1.1.0',
          'info' =>
          array(
            'channel' => 'grob',
            'package' => 'foo_linux',
            'license' => 'PHP License',
            'summary' => 'Test Package',
            'description' => 'Test Package',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'apiversion' => '1.0',
            'xsdversion' => '2.0',
            'deps' =>
            array(
                'required' =>
                array(
                    'php' =>
                    array(
                        'min' => '4.2',
                        'max' => '6.0.0',
                        ),
                    'pearinstaller' =>
                    array(
                        'min' => '1.4.0dev13',
                        ),
                    'os' =>
                    array(
                        'name' => 'linux'
                        ),
                ),
            ),
          ),
          'url' => 'http://www.example.com/foo_linux-1.1.0'));

$_test_dep->setPHPVersion('4.3.9');
$_test_dep->setPEARVersion('1.4.0a1');

$cf = new PEAR_ChannelFile;
$cf->setName('grob');
$cf->setServer('grob');
$cf->setSummary('grob');
$cf->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$reg->addChannel($cf);
$phpunit->assertNoErrors('channel add');

$a = new test_PEAR_Installer($fakelog);
$pf = new test_PEAR_PackageFile_v2;
$pf->setConfig($config);
$pf->setPackageType('extsrc');
$pf->addBinarypackage('foo_win');
$pf->setPackage('foo');
$pf->setChannel('grob');
$pf->setAPIStability('stable');
$pf->setReleaseStability('stable');
$pf->setAPIVersion('1.0.0');
$pf->setReleaseVersion('1.0.0');
$pf->setDate('2004-11-12');
$pf->setDescription('foo source');
$pf->setSummary('foo');
$pf->setLicense('PHP License');
$pf->setLogger($fakelog);
$pf->clearContents();
$pf->addFile('', 'foo.grop', array('role' => 'src'));
$pf->addBinarypackage('foo_linux');
$pf->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$pf->setNotes('blah');
$pf->setPearinstallerDep('1.4.0a1');
$pf->setPhpDep('4.2.0', '5.0.0');
$pf->setProvidesExtension('foo');

$phpunit->assertNotFalse($pf->validate(), 'first pf');

$dp = &newFakeDownloaderPackage(array());
$dp->setPackageFile($pf);
$b = array(&$dp);
$a->setDownloadedPackages($b);
$_test_dep->setOs('linux');
$pf->installBinary($a);
$phpunit->assertNoErrors('post-install linux');
$dld = $last_dl->getDownloadDir();
$cleandld = str_replace('\\\\', '\\', $last_dl->getDownloadDir());
if (OS_WINDOWS) {
    $phpunit->assertEquals(array (
      0 => 
      array (
        0 => 0,
        1 => 'Attempting to download binary version of extension "foo"',
      ),
      1 =>
      array (
        0 => 0,
        1 => 'Can only install grob/foo_win on Windows',
      ),
      2 => 
      array (
        0 => 3,
        1 => '+ tmp dir created at ' . $dld,
      ),
      3 => 
      array (
        0 => 1,
        1 => 'downloading foo_linux-1.1.0.tgz ...',
      ),
      4 => 
      array (
        0 => 1,
        1 => 'Starting to download foo_linux-1.1.0.tgz (723 bytes)',
      ),
      5 => 
      array (
        0 => 1,
        1 => '.',
      ),
      6 => 
      array (
        0 => 1,
        1 => '...done: 723 bytes',
      ),
      7 => 
      array (
        0 => 3,
        1 => '+ cp ' . $cleandld . DIRECTORY_SEPARATOR . 'foo_linux-1.1.0' . DIRECTORY_SEPARATOR .
            'foo.so ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.so',
      ),
      8 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.so',
      ),
      9 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $ext_dir . DIRECTORY_SEPARATOR .
            '.tmpfoo.so ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.so 1',
      ),
      10 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.so ' . $ext_dir . DIRECTORY_SEPARATOR .
            'foo.so ' . $ext_dir . ' ' . DIRECTORY_SEPARATOR
      ),
      11 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      12 => 
      array (
        0 => 3,
        1 => '+ mv ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.so ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.so',
      ),
      13 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      14 => 
      array (
        0 => 0,
        1 => 'Download and install of binary extension "grob/foo_linux" successful',
      ),
    ), $fakelog->getLog(), 'log linux');
} else {
    $phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'Attempting to download binary version of extension "foo"',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'Can only install grob/foo_win on Windows',
  ),
  2 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dld,
  ),
  3 => 
  array (
    0 => 1,
    1 => 'downloading foo_linux-1.1.0.tgz ...',
  ),
  4 => 
  array (
    0 => 1,
    1 => 'Starting to download foo_linux-1.1.0.tgz (723 bytes)',
  ),
  5 => 
  array (
    0 => 1,
    1 => '.',
  ),
  6 => 
  array (
    0 => 1,
    1 => '...done: 723 bytes',
  ),
  7 => 
  array (
    0 => 3,
    1 => '+ cp ' . $cleandld . DIRECTORY_SEPARATOR . 'foo_linux-1.1.0' . DIRECTORY_SEPARATOR .
            'foo.so ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.so',
  ),
  8 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.so',
  ),
  9 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.so',
  ),
  10 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $ext_dir . DIRECTORY_SEPARATOR .
            '.tmpfoo.so ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.so 1',
  ),
  11 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as foo.so ' . $ext_dir . DIRECTORY_SEPARATOR .
            'foo.so ' . $ext_dir . ' ' . DIRECTORY_SEPARATOR
  ),
  12 => 
  array (
    0 => 2,
    1 => 'about to commit 3 file operations',
  ),
  13 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.so',
  ),
  14 => 
  array (
    0 => 3,
    1 => '+ mv ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.so ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.so',
  ),
  15 => 
  array (
    0 => 2,
    1 => 'successfully committed 3 file operations',
  ),
  16 => 
  array (
    0 => 0,
    1 => 'Download and install of binary extension "grob/foo_linux" successful',
  ),
 ), $fakelog->getLog(), 'log linux');
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
    1 => 'foo_linux-1.1.0.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'foo_linux-1.1.0.tgz',
      1 => '723',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 723,
  ),
  4 => 
  array (
    0 => 'done',
    1 => 723,
  ),
), $fakelog->getDownload(), 'dl log');
$phpunit->assertFileExists($ext_dir . DIRECTORY_SEPARATOR . 'foo.so', 'not installed');
echo 'tests done';
?>
--EXPECT--
tests done