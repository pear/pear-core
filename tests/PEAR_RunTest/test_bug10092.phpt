--TEST--
PEAR_RunTest --INI--
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--INI--
open_basedir=hooba
--FILE--
<?php
var_dump(ini_get('open_basedir'));
?>
--EXPECT--
string(5) "hooba"
