--TEST--
PEAR_Installer->install() upgrade a pecl package when it switches from a pear channel to a pecl channel
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
if (substr('PHP_OS', 0, 3) == 'WIN') {
    echo 'skip only unix can run this test';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$_test_dep->setPEARVersion('1.4.0a1');
$_test_dep->setPHPVersion('4.3.11');
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_upgrade_pecl'. DIRECTORY_SEPARATOR . 'package.xml';
$pathtopackagexml2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_upgrade_pecl'. DIRECTORY_SEPARATOR . 'package2.xml';
$dp = &new test_PEAR_Downloader($fakelog, array('upgrade' => true), $config);
$result = $dp->download(array($pathtopackagexml));
$installer->setOptions(array());
$installer->sortPackagesForInstall($result);
$installer->setDownloadedPackages($result);
$installer->install($result[0]);
$phpunit->assertNoErrors('setup for upgrade');
$fakelog->getLog();
$fakelog->getDownload();
$dp = &new test_PEAR_Downloader($fakelog, array('upgrade' => true), $config);
$phpunit->assertNoErrors('after create');
$result = $dp->download(array($pathtopackagexml2));
$phpunit->assertEquals(1, count($result), 'return');
$phpunit->assertIsa('test_PEAR_Downloader_Package', $result[0], 'right class');
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf = $result[0]->getPackageFile(), 'right kind of pf');
$phpunit->assertEquals('sqlite', $pf->getPackage(), 'right package');
$phpunit->assertEquals('pecl.php.net', $pf->getChannel(), 'right channel');
$dlpackages = $dp->getDownloadedPackages();
$phpunit->assertEquals(1, count($dlpackages), 'downloaded packages count');
$phpunit->assertEquals(3, count($dlpackages[0]), 'internals package count');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[0]), 'indexes');
$phpunit->assertEquals($pathtopackagexml,
    $dlpackages[0]['file'], 'file');
$phpunit->assertIsa('PEAR_PackageFile_v2',
    $dlpackages[0]['info'], 'info');
$phpunit->assertEquals('PEAR',
    $dlpackages[0]['pkg'], 'PEAR');
$after = $dp->getDownloadedPackages();
$phpunit->assertEquals(0, count($after), 'after getdp count');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->getDownloadDir(),
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
echo 'tests done';
?>
--EXPECT--
tests done