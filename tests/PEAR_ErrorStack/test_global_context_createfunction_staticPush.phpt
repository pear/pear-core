--TEST--
PEAR_ErrorStack test context, global create_function(), staticPush
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
$a();

$ret = $stack->pop();
print_r($ret['context']);
?>
--EXPECTF--
Array
(
    [file] => %s/test_global_context_createfunction_staticPush.php
    [line] => 4
    [function] => {closur%s}
)
