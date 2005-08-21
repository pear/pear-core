--TEST--
PEAR_PackageFile_Parser_v2_Validator->validate(), extsrcrelease tag validation
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
    'test_release'. DIRECTORY_SEPARATOR . 'package4.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf, 'ret');
$pf->validate();
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'File "6" in directory "<dir name="/">" has invalid role "ext", should be one of data, doc, php, script, src, test'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'Only one <extsrcrelease> tag may exist in a package.xml'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => '<extsrcrelease> packages cannot specify a source code package, only extension binaries may use the <srcpackage> tag'),
), '1');

$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_release'. DIRECTORY_SEPARATOR . 'package5.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf, 'ret');
$pf->validate();
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'File "6" in directory "<dir name="/">" has invalid role "ext", should be one of data, doc, php, script, src, test'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => '<extsrcrelease> packages cannot specify a source code package, only extension binaries may use the <srcpackage> tag'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'Invalid tag order in <extsrcrelease>, found <installconditions> expected one of "configureoption, binarypackage"'),
), '2');

$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_release'. DIRECTORY_SEPARATOR . 'package6.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf, 'ret');
$pf->validate();
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'tag <configureoption> has no attributes in context "<extsrcrelease>"'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'tag <configureoption> in context "<extsrcrelease>" has no attribute "prompt"'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'tag <configureoption> in context "<extsrcrelease>" has no attribute "name"'),
    array('package' => 'PEAR_PackageFile_v2', 'message' => '<binarypackage> tags must contain the name of a package that is a compiled version of this extsrc package'),
), '3');
echo 'tests done';
?>
--EXPECT--
tests done