--TEST--
PEAR_ErrorStack test context, in-static method create_function(), staticPush
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
$a = function() { PEAR_ErrorStack::staticPush("test", 3); };
$testline = __LINE__ - 1;
class test8
{
    static function test7()
    {
        global $a;
        $a();
    }
}
test8::test7();

$ret = $stack->pop();
print_r($ret['context']);
?>
--EXPECTF--
Array
(
    [file] => %s/test_instaticmethod_context_createfunction_staticPush.php
    [line] => 4
    [function] => {closur%s}
)
