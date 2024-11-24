--TEST--
PEAR_ErrorStack test context, in-method create_function()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$stack = &PEAR_ErrorStack::singleton('test');
$a = function() { $GLOBALS["stack"]->push(3); };
$testline = __LINE__ - 1;
class test8
{
    function test7()
    {
        global $a;
        $a();
    }
}
$z = new test8;
$z->test7();

$ret = $stack->pop();
print_r($ret['context']);
?>
--EXPECTF--
Array
(
    [file] => %s/test_inmethod_context_createfunction.php
    [line] => 4
    [function] => {closur%s}
)
