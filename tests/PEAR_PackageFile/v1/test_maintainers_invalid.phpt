--TEST--
PEAR_PackageFile_Parser_v1 maintainer management, invalid
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
    'test_basicparse'. DIRECTORY_SEPARATOR . 'package.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
$pf->clearMaintainers();
$phpunit->assertFalse($pf->getMaintainers(), 'clear failed');
$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1',
        'message' => 'No maintainers found, at least one must be defined')
        ), 'after validation 1');
$phpunit->assertNotTrue($result, 'return 1' );
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log 1');

$pf->addMaintainer('lead', '', 'greg', 'greg@example.com');
$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1',
        'message' => 'Maintainer 1 has no handle (user ID at channel server)')
        ), 'after validation 1');
$phpunit->assertNotTrue($result, 'return 1' );
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log 1');
$pf->clearMaintainers();

$pf->addMaintainer('', 'foo', 'greg', 'greg@example.com');
$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1',
        'message' => 'Maintainer 1 has no role')
        ), 'after validation 1');
$phpunit->assertNotTrue($result, 'return 1' );
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log 1');
$pf->clearMaintainers();

$pf->addMaintainer('lead', 'foo', '', 'greg@example.com');
$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1',
        'message' => 'Maintainer 1 has no name')
        ), 'after validation 1');
$phpunit->assertNotTrue($result, 'return 1' );
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log 1');
$pf->clearMaintainers();

$pf->addMaintainer('lead', 'foo', 'greg', '');
$result = $pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1',
        'message' => 'Maintainer 1 has no email')
        ), 'after validation 1');
$phpunit->assertNotTrue($result, 'return 1' );
$phpunit->assertEquals(array(), $fakelog->getLog(), 'normal validate empty log 1');
$pf->clearMaintainers();
echo 'tests done';
?>
--EXPECT--
tests done