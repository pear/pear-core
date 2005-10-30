--TEST--
http_chunked_decode() truncated message ending with NUL after a chunk
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
"0c\r\n".
"\nall we got\n";
var_dump(http_chunked_decode($data));
?>
--EXPECTF--
%sTEST
string(24) "abra
cadabra
all we got
"
