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
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/foo_linux-1.1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addXmlrpcConfig('grob', 'package.getDownloadURL',
    array(array('channel' => 'grob', 'package' => 'foo_linux', ), 'stable'),
    array('version' => '1.1.0',
          'info' =>
          array(
            'channel' => 'pear.php.net',
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
                        'name' => 'windows'
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
$_test_dep->setOs('windows');
$pf->installBinary($a);
$phpunit->assertNoErrors('post-install');
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
        0 => 3,
        1 => '+ tmp dir created at ' . $dld,
      ),
      2 => 
      array (
        0 => 1,
        1 => 'downloading foo_win-1.1.0.tgz ...',
      ),
      3 => 
      array (
        0 => 1,
        1 => 'Starting to download foo_win-1.1.0.tgz (725 bytes)',
      ),
      4 => 
      array (
        0 => 1,
        1 => '.',
      ),
      5 => 
      array (
        0 => 1,
        1 => '...done: 725 bytes',
      ),
      6 => 
      array (
        0 => 3,
        1 => '+ cp ' . $cleandld . DIRECTORY_SEPARATOR . 'foo_win-1.1.0' . DIRECTORY_SEPARATOR .
            'foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      7 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      8 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $ext_dir . DIRECTORY_SEPARATOR .
            '.tmpfoo.dll ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll 1',
      ),
      9 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR .
            'foo.dll ' . $ext_dir . ' ' . DIRECTORY_SEPARATOR
      ),
      10 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      11 => 
      array (
        0 => 3,
        1 => '+ mv ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      12 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      13 => 
      array (
        0 => 0,
        1 => 'Download and install of binary extension "grob/foo_win" successful',
      ),
    ), $fakelog->getLog(), 'log');
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
        1 => '+ tmp dir created at ' . $dld,
      ),
      2 => 
      array (
        0 => 1,
        1 => 'downloading foo_win-1.1.0.tgz ...',
      ),
      3 => 
      array (
        0 => 1,
        1 => 'Starting to download foo_win-1.1.0.tgz (725 bytes)',
      ),
      4 => 
      array (
        0 => 1,
        1 => '.',
      ),
      5 => 
      array (
        0 => 1,
        1 => '...done: 725 bytes',
      ),
      6 => 
      array (
        0 => 3,
        1 => '+ cp ' . $cleandld . DIRECTORY_SEPARATOR . 'foo_win-1.1.0' . DIRECTORY_SEPARATOR .
            'foo.dll ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      7 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      8 =>
      array (
        0 => 3,
        1 => 'adding to transaction: chmod 644 ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      9 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $ext_dir . DIRECTORY_SEPARATOR .
            '.tmpfoo.dll ' . $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll 1',
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
        1 => 'about to commit 3 file operations',
      ),
      12 =>
      array (
        0 => 3,
        1 => '+ chmod 644 ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll',
      ),
      13 => 
      array (
        0 => 3,
        1 => '+ mv ' . $ext_dir . DIRECTORY_SEPARATOR . '.tmpfoo.dll ' .
            $ext_dir . DIRECTORY_SEPARATOR . 'foo.dll',
      ),
      14 => 
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      15 => 
      array (
        0 => 0,
        1 => 'Download and install of binary extension "grob/foo_win" successful',
      ),
    ), $fakelog->getLog(), 'log');
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
    1 => 'foo_win-1.1.0.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'foo_win-1.1.0.tgz',
      1 => '725',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 725,
  ),
  4 => 
  array (
    0 => 'done',
    1 => 725,
  ),
), $fakelog->getDownload(), 'log');
$phpunit->assertFileExists($ext_dir . DIRECTORY_SEPARATOR . 'foo.dll', 'not installed');
echo 'tests done';
?>
--EXPECT--
tests done