--TEST--
PEAR_Downloader_Package->detectDependencies(), required dep package.xml 2.0 static uri
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$mainpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'main-1.0.tgz';
$requiredpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'foo-1.0.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/main-1.0.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/foo-1.0.tgz', $requiredpackage);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'main', 'channel' => 'pear.php.net'), 'stable'),
    array('version' => '1.0',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'main',
            'license' => 'PHP License',
            'summary' => 'Main Package',
            'description' => 'Main Package',
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
                    'package' =>
                        array(
                            'name' => 'required',
                            'uri' => 'http://www.example.com/foo-1.0',
                        ),
                ),
             ),
          ),
          'url' => 'http://www.example.com/main-1.0'));
$dp = &newDownloaderPackage(array());
$result = $dp->initialize('main');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
$phpunit->assertNoErrors('after detect');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 1');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading foo-1.0.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download foo-1.0.tgz (638 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.',
  ),
  4 => 
  array (
    0 => 1,
    1 => '...done: 638 bytes',
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
    1 => 'foo-1.0.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'foo-1.0.tgz',
      1 => '638',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 638,
  ),
  4 => 
  array (
    0 => 'done',
    1 => 638,
  ),
), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertTrue($result, 'first return');
$phpunit->assertEquals(2, count($params), 'mergeDependencies');
$phpunit->assertEquals('main', $params[0]->getPackage(), 'main package');
$phpunit->assertEquals('foo', $params[1]->getPackage(), 'foo package');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 2');
$phpunit->assertEquals(array(), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertFalse($result, 'second return');
echo 'tests done';
?>
--EXPECT--
tests done