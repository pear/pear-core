--TEST--
http_date() with timestamp
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";
echo http_date(1), "\n";
echo http_date(1234567890), "\n";
?>
--EXPECTF--
%sTEST
Thu, 01 Jan 1970 00:00:01 GMT
Fri, 13 Feb 2009 23:31:30 GMT

