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

$test5 = '
<?php
function test()
{
}

if (trytofool) {
    function fool()
    {
    }
}
class test2 {
    function test2() {
        parent::unused();
        Greg::classes();
        $a = new Pierre;
    }
}

class blah extends test2 {
    /**
     * @nodep Stig
     */
    function blah()
    {
        Stig::rules();
    }
}
?>
';
$fp = fopen($testdir . DIRECTORY_SEPARATOR . 'test5.php', 'w');
fwrite($fp, $test5);
fclose($fp);

$ret = $validator->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test5.php');
$phpunit->assertNoErrors('1st valid PHP');
$phpunit->showall();
$phpunit->assertEquals(array (
  'source_file' => $testdir . DIRECTORY_SEPARATOR . 'test5.php',
  'declared_classes' =>
  array (
    0 => 'test2',
    1 => 'blah',
  ),
  'declared_interfaces' =>
  array (
  ),
  'declared_methods' =>
  array (
    'test2' =>
    array (
      0 => 'test2',
    ),
    'blah' =>
    array (
      0 => 'blah',
    ),
  ),
  'declared_functions' =>
  array (
    0 => 'test',
    1 => 'fool',
  ),
  'used_classes' =>
  array (
    0 => 'Greg',
    1 => 'Pierre',
  ),
  'inheritance' =>
  array (
    'blah' => 'test2',
  ),
  'implements' =>
  array (
  ),
), $ret, 'wrong return value, 1st valid PHP test');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
