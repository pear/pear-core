--TEST--
PEAR_PackageFile_Parser_v2->setLicense()
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
    'Parser'. DIRECTORY_SEPARATOR .
    'test_basicparse'. DIRECTORY_SEPARATOR . 'package2.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
$pfa = &$pf->getRW();
$pf = &$pfa;
$pf->flattenFilelist();
$phpunit->assertNoErrors('valid xml parse');
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf, 'return of valid parse');
$phpunit->assertEquals('PHP License', $pf->getLicense(), 'pre-set');
$phpunit->assertEquals(array('uri' => 'http://www.php.net/license/3_0.txt'),
    $pf->getLicenseLocation('http://www.php.net/license/3_0.txt'), 'pre-set uri');
$pf->setLicense('LGPL');
$phpunit->assertEquals('LGPL', $pf->getLicense(), 'set failed');
$phpunit->assertFalse($pf->getlicenseLocation(), 'uri set failed');
$pf->setLicense('LGPL', 'ftp://hoeey');
$phpunit->assertEquals('LGPL', $pf->getLicense(), 'set 2 failed');
$phpunit->assertEquals(array('uri' => 'ftp://hoeey'),
    $pf->getlicenseLocation(), 'uri set 2 failed');
$pf->setLicense('LGPL', false, 'LICENSE');
$phpunit->assertEquals('LGPL', $pf->getLicense(), 'set 3 failed');
$phpunit->assertEquals(array('filesource' => 'LICENSE'),
    $pf->getlicenseLocation(), 'uri set 3 failed');
$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log');
$phpunit->assertNoErrors('after validation');
$result = $pf->validate(PEAR_VALIDATE_INSTALLING);
$phpunit->assertEquals(array(), $fakelog->getLog(), 'installing validate empty log');
$phpunit->assertNoErrors('after validation');
$result = $pf->validate(PEAR_VALIDATE_DOWNLOADING);
$phpunit->assertEquals(array(), $fakelog->getLog(), 'downloading validate empty log');
$phpunit->assertNoErrors('after validation');
$result = $pf->validate(PEAR_VALIDATE_PACKAGING);
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 1,
    1 => 'Analyzing test/test.php',
  ),
  1 => 
  array (
    0 => 1,
    1 => 'Analyzing test/test2.php',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Analyzing test/test3.php',
  ),
), $fakelog->getLog(), 'packaging validate full log');
$phpunit->assertNoErrors('after validation');
echo 'tests done';
?>
--EXPECT--
tests done