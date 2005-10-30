--TEST--
http_absolute_uri() with proto
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";
echo http_absolute_uri('sec', 'https'), "\n";
echo http_absolute_uri('/pub', 'ftp'), "\n";
echo http_absolute_uri('/', null), "\n";
?>
--EXPECTF--
%sTEST
https://localhost/sec
ftp://localhost/pub
http://localhost/

