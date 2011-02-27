--TEST--
PEAR_PackageFile_Parser_v2_Validator->analyzeSourceCode test
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
if (!function_exists('token_get_all')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';

require_once 'PEAR/PackageFile/v2/Validator.php';
$validator = new PEAR_PackageFile_v2_Validator;
$validator->_stack = new PEAR_ErrorStack('PEAR_PackageFile_v2', false, null);

$testdir = $statedir;
@mkdir($testdir);

$test3 = '
<?php
class test
{
    class test2 {
    }
}
?>
';
$fp = fopen($testdir . DIRECTORY_SEPARATOR . 'test3.php', 'w');
fwrite($fp, $test3);
fclose($fp);

$ret = $validator->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test3.php');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' =>
    'Parser error: invalid PHP found in file "' . $testdir . DIRECTORY_SEPARATOR . 'test3.php"')),
    'more invalid php');
$phpunit->assertFalse($ret, 'wrong return value, 2nd invalid PHP test');
unlink($testdir . DIRECTORY_SEPARATOR . 'test3.php');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
