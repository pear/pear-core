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


$test6 = <<<'TEST'
<?php
class SqlDiff_Version {
    /**
     * The current version
     *
     * @var string
     */
    static protected $id = '@package_version@';

    /**
     * Get the version number only
     *
     * @return string
     */
    static public function getVersionNumber() {
        if (strpos(static::$id, '@package_version') === 0) {
            return 'dev';
        }

        return static::$id;
    }

    /**
     * Get the version string
     *
     * @return string
     */
    static public function getVersionString() {
        return 'SqlDiff-' . static::getVersionNumber() . ' by Christer Edvartsen.' . PHP_EOL;
    }
}
?>
TEST;
$fp = fopen($testdir . DIRECTORY_SEPARATOR . 'test6.php', 'w');
fwrite($fp, $test6);
fclose($fp);

$ret = $validator->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test6.php');
unlink($testdir . DIRECTORY_SEPARATOR . 'test6.php');

$phpunit->assertNoErrors('1st valid PHP');
$phpunit->showall();
$phpunit->assertEquals(array(
  "source_file" => $testdir . DIRECTORY_SEPARATOR . 'test6.php',
  "declared_classes"=> array(
    0 => "SqlDiff_Version"
  ),
  "declared_interfaces" => array(),
  "declared_methods" => array(
    "SqlDiff_Version" => array(
      0 => "getVersionNumber",
      1 => "getVersionString"
    ),
  ),
  "declared_functions" => array(),
  "used_classes" => array(
    0 => "static"
  ),
  "inheritance" => array(),
  "implements" => array(),
), $ret, 'LSB, 1st valid PHP test');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
