--TEST--
PEAR_PackageFile_v2_Validator->analyzeSourceCode test
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
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'Parser'. DIRECTORY_SEPARATOR .
    'test_basicparse'. DIRECTORY_SEPARATOR . 'package2.xml';
$pf = &$parser->parse(implode('', file($pathtopackagexml)), $pathtopackagexml);
require_once 'PEAR/PackageFile/v2/Validator.php';
$val = new PEAR_PackageFile_v2_Validator;
$val->validate($pf); // setup purposes only
$phpunit->assertNoErrors('setup');

$x = $val->analyzeSourceCode('=+"\\//452');
echo "first test: returns false with non-existing filename? ";
echo $x ? "no\n" : "yes\n";

$testdir = $statedir;
@mkdir($testdir);

$test1 = '
<?php
::error();
?>
';
$fp = fopen($testdir . DIRECTORY_SEPARATOR . 'test1.php', 'w');
fwrite($fp, $test1);
fclose($fp);

$ret = $val->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test1.php');
echo "second test: returns false with invalid PHP? ";
echo $ret ? "no\n" : "yes\n";
unlink($testdir . DIRECTORY_SEPARATOR . 'test1.php');

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

$ret = $val->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test3.php');
echo "fourth test: returns false with invalid PHP? ";
echo $ret ? "no\n" : "yes\n";
unlink($testdir . DIRECTORY_SEPARATOR . 'test3.php');

$test4 = '
<?php
function test()
{
    class test2 {
    }
}
?>
';
$fp = fopen($testdir . DIRECTORY_SEPARATOR . 'test4.php', 'w');
fwrite($fp, $test4);
fclose($fp);

$ret = $val->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test4.php');
echo "fifth test: returns false with invalid PHP? ";
echo $ret ? "no\n" : "yes\n";
unlink($testdir . DIRECTORY_SEPARATOR . 'test4.php');

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

$ret = $val->analyzeSourceCode($testdir . DIRECTORY_SEPARATOR . 'test5.php');
echo "sixth test: returns false with valid PHP? ";
echo $ret ? "no\n" : "yes\n";
$ret['source_file'] = str_replace(array(dirname(__FILE__),DIRECTORY_SEPARATOR), array('', '/'), $ret['source_file']);
var_dump($ret);

cleanall($testdir);
?>
--EXPECT--
first test: returns false with non-existing filename? yes
second test: returns false with invalid PHP? yes
fourth test: returns false with invalid PHP? yes
fifth test: returns false with invalid PHP? yes
sixth test: returns false with valid PHP? no
array(8) {
  ["source_file"]=>
  string(26) "/registry_tester/test5.php"
  ["declared_classes"]=>
  array(2) {
    [0]=>
    string(5) "test2"
    [1]=>
    string(4) "blah"
  }
  ["declared_interfaces"]=>
  array(0) {
  }
  ["declared_methods"]=>
  array(2) {
    ["test2"]=>
    array(1) {
      [0]=>
      string(5) "test2"
    }
    ["blah"]=>
    array(1) {
      [0]=>
      string(4) "blah"
    }
  }
  ["declared_functions"]=>
  array(2) {
    [0]=>
    string(4) "test"
    [1]=>
    string(4) "fool"
  }
  ["used_classes"]=>
  array(2) {
    [0]=>
    string(4) "Greg"
    [1]=>
    string(6) "Pierre"
  }
  ["inheritance"]=>
  array(1) {
    ["blah"]=>
    string(5) "test2"
  }
  ["implements"]=>
  array(0) {
  }
}