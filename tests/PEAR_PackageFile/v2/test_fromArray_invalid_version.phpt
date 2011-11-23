--TEST--
PEAR_PackageFile_Parser_v2->getTask
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$config = array();
$pf = new PEAR_PackageFile($config);
$a['package']['attribs']['version'] = '9.9';
$pe = $pf->fromArray($a);
$phpunit->assertPEARError($pe, 'Invalid package version.');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
