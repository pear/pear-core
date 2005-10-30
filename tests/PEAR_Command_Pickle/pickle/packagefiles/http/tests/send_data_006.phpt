--TEST--
http_send_data() multiple ranges
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
http_send_data(str_repeat('123abc', 1000));
?>
--EXPECTF--
Status: 206
X-Powered-By: PHP/%s
Accept-Ranges: bytes
Content-Type: multipart/byteranges; boundary=%d.%d


--%d.%d
Content-Type: text/plain
Content-Range: bytes 0-3/6000

123a
--%d.%d
Content-Type: text/plain
Content-Range: bytes 4-5/6000

bc
--%d.%d
Content-Type: text/plain
Content-Range: bytes 9-11/6000

abc
--%d.%d--
