--TEST--
PEAR_PackageFile_Parser_v2_Validator->validate(), multiple root <dir> tags (bug #7021)
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
    'test_bug7021'. DIRECTORY_SEPARATOR . 'package.xml';
$pf = &$parser->parse(file_get_contents($pathtopackagexml), $pathtopackagexml);
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf, 'ret');
$pf->validate();
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'Multiple top-level <dir> tags are not allowed.  Enclose them in a <dir name="/">'),
), '1');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done