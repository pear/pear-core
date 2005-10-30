--TEST--
http_chunked_decode() "\r\n"
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";
$data =
"02\r\n".
"ab\r\n".
"04\r\n".
"ra\nc\r\n".
"06\r\n".
"adabra\r\n".
"0\r\n".
"nothing\n";
var_dump(http_chunked_decode($data));
?>
--EXPECTF--
%sTEST
string(12) "abra
cadabra"

