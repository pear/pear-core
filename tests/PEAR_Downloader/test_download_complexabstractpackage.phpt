--TEST--
PEAR_Downloader->download() with complex remote tgz [alldeps, preferred_state = alpha]
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
    'packages'. DIRECTORY_SEPARATOR . 'PEAR1-1.4.0a1.tgz';
$pathtobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Bar-1.5.0.tgz';
$pathtofoobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Foobar-1.4.0a1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/PEAR1-1.4.0a1.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Bar-1.5.0.tgz', $pathtobarxml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Foobar-1.4.0a1.tgz', $pathtofoobarxml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'PEAR1', 'channel' => 'pear.php.net'), 'alpha'),
    array('version' => '1.4.0a1',
          'info' =>
          array(
            'package' => 'PEAR1',
            'channel' => 'pear.php.net',
            'license' => 'PHP License',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'alpha',
            'deps' =>
            array(
                array(
                    'type' => 'pkg',
                    'rel' => 'ge',
                    'version' => '1.0.0',
                    'name' => 'Bar',
                    'channel' => 'pear.php.net',
                ),
                array(
                    'type' => 'pkg',
                    'rel' => 'not',
                    'name' => 'Foo',
                    'channel' => 'pear.php.net',
                    'optional' => 'no',
                )
            ),
          ),
          'url' => 'http://www.example.com/PEAR1-1.4.0a1'));
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
$dp = &new test_PEAR_Downloader($fakelog, array('alldeps' => true), $config);
$phpunit->assertNoErrors('after create');
$config->set('preferred_state', 'alpha');
$result = &$dp->download(array('PEAR1'));
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
$phpunit->assertEquals($result[1]->_downloader->getDownloadDir() . DIRECTORY_SEPARATOR .
    'PEAR1-1.4.0a1.tgz',
    $dlpackages[0]['file'], 'file');
$phpunit->assertIsa('PEAR_PackageFile_v1',
    $dlpackages[0]['info'], 'info');
$phpunit->assertEquals('PEAR1',
    $dlpackages[0]['pkg'], 'PEAR1');
$phpunit->assertEquals($result[1]->_downloader->getDownloadDir() . DIRECTORY_SEPARATOR .
    'Bar-1.5.0.tgz',
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
    1 => 'downloading PEAR1-1.4.0a1.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download PEAR1-1.4.0a1.tgz (2,112 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.',
  ),
  4 => 
  array (
    0 => 1,
    1 => '...done: 2,112 bytes',
  ),
  5 => 
  array (
    0 => 1,
    1 => 'downloading Bar-1.5.0.tgz ...',
  ),
  6 => 
  array (
    0 => 1,
    1 => 'Starting to download Bar-1.5.0.tgz (2,085 bytes)',
  ),
  7 => 
  array (
    0 => 1,
    1 => '...done: 2,085 bytes',
  ),
  8 => 
  array (
    0 => 1,
    1 => 'downloading Foobar-1.4.0a1.tgz ...',
  ),
  9 => 
  array (
    0 => 1,
    1 => 'Starting to download Foobar-1.4.0a1.tgz (2,062 bytes)',
  ),
  10 => 
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
    1 => 'PEAR1-1.4.0a1.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'PEAR1-1.4.0a1.tgz',
      1 => '2112',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 1024,
  ),
  4 => 
  array (
    0 => 'bytesread',
    1 => 2048,
  ),
  5 => 
  array (
    0 => 'bytesread',
    1 => 2112,
  ),
  6 => 
  array (
    0 => 'done',
    1 => 2112,
  ),
  7 => 
  array (
    0 => 'setup',
    1 => 'self',
  ),
  8 => 
  array (
    0 => 'saveas',
    1 => 'Bar-1.5.0.tgz',
  ),
  9 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'Bar-1.5.0.tgz',
      1 => '2085',
    ),
  ),
  10 => 
  array (
    0 => 'bytesread',
    1 => 1024,
  ),
  11 => 
  array (
    0 => 'bytesread',
    1 => 2048,
  ),
  12 => 
  array (
    0 => 'bytesread',
    1 => 2085,
  ),
  13 => 
  array (
    0 => 'done',
    1 => 2085,
  ),
  14 => 
  array (
    0 => 'setup',
    1 => 'self',
  ),
  15 => 
  array (
    0 => 'saveas',
    1 => 'Foobar-1.4.0a1.tgz',
  ),
  16 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'Foobar-1.4.0a1.tgz',
      1 => '2062',
    ),
  ),
  17 => 
  array (
    0 => 'bytesread',
    1 => 1024,
  ),
  18 => 
  array (
    0 => 'bytesread',
    1 => 2048,
  ),
  19 => 
  array (
    0 => 'bytesread',
    1 => 2062,
  ),
  20 => 
  array (
    0 => 'done',
    1 => 2062,
  ),
), $fakelog->getDownload(), 'download callback messages');
echo 'tests done';
?>
--EXPECT--
tests done