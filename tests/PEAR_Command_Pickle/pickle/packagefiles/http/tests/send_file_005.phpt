--TEST--
http_send_file() multiple ranges
--SKIPIF--
<?php 
include 'skip.inc';
checkcgi();
?>
--ENV--
HTTP_RANGE=bytes=0-3, 4-5,9-11
--FILE--
<?php
http_send_content_type('text/plain');
http_send_file('data.txt');
?>
--EXPECTF--
Status: 206
X-Powered-By: PHP/%s
Accept-Ranges: bytes
Content-Type: multipart/byteranges; boundary=%d.%d


--%d.%d
Content-Type: text/plain
Content-Range: bytes 0-3/1010

0123
--%d.%d
Content-Type: text/plain
Content-Range: bytes 4-5/1010

45
--%d.%d
Content-Type: text/plain
Content-Range: bytes 9-11/1010

901
--%d.%d--
