--TEST--
PEAR::isError test
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once 'PEAR.php';

$error = PEAR::throwError('test', 123, 'Wooop');
var_dump(PEAR::isError($error));

$error = PEAR::throwError('test', 123);
var_dump(PEAR::isError($error));

$error = PEAR::throwError('test');
var_dump(PEAR::isError($error));

$error = PEAR::throwError();
var_dump(PEAR::isError($error));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)