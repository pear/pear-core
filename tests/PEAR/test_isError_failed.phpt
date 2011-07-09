--TEST--
PEAR::isError test failures
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once 'PEAR.php';

var_dump(PEAR::isError('string'));
var_dump(PEAR::isError(array()));
var_dump(PEAR::isError(array('test')));
var_dump(PEAR::isError(true));
var_dump(PEAR::isError(false));
var_dump(PEAR::isError(null));
var_dump(PEAR::isError(10));
var_dump(PEAR::isError(1.));
var_dump(PEAR::isError(new stdClass));
?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)