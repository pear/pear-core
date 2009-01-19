--TEST--
PEAR_Downloader_Package->initialize() with unknown channel, auto_discover on
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
$dir = dirname(__FILE__)  . DIRECTORY_SEPARATOR;
require_once $dir . 'setup.php.inc';
$pathtopackagexml = $dir . 'test_initialize_downloadurl'. DIRECTORY_SEPARATOR . 'test-1.0.tgz';
$pathtochannelxml = $dir . 'test_initialize_abstractpackage_discover'. DIRECTORY_SEPARATOR . 'channel.xml';
$csize = filesize($pathtochannelxml);

$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.foo.com/channel.xml', $pathtochannelxml);

$pearweb->addRESTConfig("http://pear.foo.com/rest/r/test/allreleases2.xml", '<?xml version="1.0"?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>test</p>
 <c>pear.foo.com</c>
 <r>
  <v>1.0</v>
  <s>beta</s>
  <m>4.3</m>
 </r>
</a>', 'text/xml');

$dp = &newDownloaderPackage(array());
$dp->_downloader->config->set('auto_discover', 1);

$phpunit->assertNoErrors('after create');
$result = $dp->initialize('pear.foo.com/test');
$phpunit->assertNoErrors('wrong errors');

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
    0 => 1,
    1 => 'Auto-discovered channel "pear.foo.com", alias "foo", adding to registry',
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

$phpunit->assertTrue($result, 'after initialize');
$phpunit->assertNull($dp->getPackageFile(), 'downloadable test');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
