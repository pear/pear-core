--TEST--
PEAR_PackageFile_Parser_v1->parse() invalid xml
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pathtopackagexml = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'package.xml';
$result = &$parser->parse('<xmlbad', PEAR_VALIDATE_NORMAL, $pathtopackagexml);
$message = version_compare(phpversion(), '5.0.0', '<') ? 'XML error: unclosed token at line 1'
    : 'XML error: XML_ERR_GT_REQUIRED at line 1';
$phpunit->assertErrors(array(
    'message' => $message,
    'package' => 'PEAR_Error',
),'invalid xml parse');
$phpunit->assertPEARError($result, 'return of invalid parse');
echo 'tests done';
?>
--EXPECT--
tests done