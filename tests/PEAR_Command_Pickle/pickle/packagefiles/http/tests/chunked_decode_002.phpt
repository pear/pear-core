--TEST--
http_chunked_decode() "\n"
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";
$data =
"02\n".
"ab\n".
"04\n".
"ra\nc\n".
"06\n".
"adabra\n".
"0\n".
"hidden\n";
var_dump(http_chunked_decode($data));
?>
--EXPECTF--
%sTEST
string(12) "abra
cadabra"

