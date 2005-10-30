--TEST--
INI entries
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";
ini_set('http.cache_log', 'cache.log');
var_dump(ini_get('http.cache_log'));
ini_set('http.allowed_methods', 'POST, HEAD, GET');
var_dump(ini_get('http.allowed_methods'));
ini_set('http.only_exceptions', true);
var_dump(ini_get('http.only_exceptions'));
echo "Done\n";
?>
--EXPECTF--
%sTEST
string(9) "cache.log"
string(15) "POST, HEAD, GET"
string(1) "1"
Done

