--TEST--
PEAR_PackageFile_Generator_v2->toTgz2() (dual package.xml version for BC, failure)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
$save____dir = getcwd();
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
chdir($temp_path);
require_once 'PEAR/Packager.php';
require_once 'PEAR/PackageFile/Parser/v1.php';
$v1parser = &new PEAR_PackageFile_Parser_v1;
$v1parser->setConfig($config);
$v1parser->setLogger($fakelog);
$pf1 = &$v1parser->parse(implode('', file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'invalidv1.xml')), dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'invalidv1.xml');
$v1generator = &$pf1->getDefaultGenerator();
$pf = &$parser->parse(implode('', file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'phprelease1.xml')), dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'phprelease1.xml');
$generator = &$pf->getDefaultGenerator();
$packager = &new PEAR_Packager;
mkdir($temp_path . DIRECTORY_SEPARATOR . 'gron');
$e = $generator->toTgz2($packager, $pf1, true, $temp_path . DIRECTORY_SEPARATOR . 'gron');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'package.xml 1.0 file "validv1.xml" is not present in <contents>'),
    array('package' => 'PEAR_Error', 'message' => 'PEAR_Packagefile_v2::toTgz: "invalidv1.xml" is not equivalent to "phprelease1.xml"'),
), 'errors');
$phpunit->assertEquals(array (), $fakelog->getLog(), 'packaging log');
chdir($save____dir);
echo 'tests done';
?>
--EXPECT--
tests done
