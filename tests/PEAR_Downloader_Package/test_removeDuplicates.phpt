--TEST--
PEAR_Downloader_Package::removeDuplicates()
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
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/test-1.0.tgz', $pathtopackagexml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'test', 'channel' => 'pear.php.net', 'group' => 'subgroup'), 'stable'),
    array('version' => '1.0',
          'info' =>
          array(
            'channel' => 'pear.php.net',
            'package' => 'test',
            'license' => 'PHP License',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'beta',
          ),
          'url' => 'http://www.example.com/test-1.0.tgz'));
$dp1 = &newDownloaderPackage(array());
$result = $dp1->initialize('test#subgroup');
$phpunit->assertNoErrors('after create 1');

$dp2 = &newDownloaderPackage(array());
$result = $dp2->initialize('http://www.example.com/test-1.0.tgz');
$phpunit->assertNoErrors('after create 2');

$dp3 = &newDownloaderPackage(array());
$result = $dp3->initialize($pathtopackagexml);
$phpunit->assertNoErrors('after create 3');

$pathtopackagexml2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_removeDuplicates'. DIRECTORY_SEPARATOR . 'package.xml';
$dp4 = &newDownloaderPackage(array());
$result = $dp4->initialize($pathtopackagexml2);
$phpunit->assertNoErrors('after create 4');

$params = array(&$dp1, &$dp2, &$dp3, &$dp4);
PEAR_Downloader_Package::removeDuplicates($params);
$phpunit->assertEquals(3, count($params), 'unsuccessful removal');
$phpunit->assertEquals('test', $params[0]->getPackage(), 'first one');
$phpunit->assertEquals('subgroup', $params[0]->getGroup(), 'first one group');
$phpunit->assertEquals('test', $params[1]->getPackage(), 'second one');
$phpunit->assertEquals('default', $params[1]->getGroup(), 'second one group');
$phpunit->assertEquals('test2', $params[2]->getPackage(), 'third one');
echo 'tests done';
?>
--EXPECT--
tests done