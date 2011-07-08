--TEST--
Bug #18656: E_WARNING errors from is_a() usage in PEAR::isError on PHP 5.4+
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once 'PEAR.php';

var_dump(PEAR::isError('string to trigger warning', 'PEAR_Error'));
?>
--EXPECT--
bool(false)