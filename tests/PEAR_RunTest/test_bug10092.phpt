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
var_dump($_POST);
?>
--EXPECT--
string(5) "hooba"
array(1) {
  ["test"]=>
  string(2) "hi"
}