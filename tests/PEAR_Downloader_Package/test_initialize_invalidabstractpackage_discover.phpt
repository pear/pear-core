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
          array(
            'license' => 'PHP License',
            'channel' => 'pear.foo.com',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'beta',
          ),
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
--EXPECT--
tests done