--TEST--
http_send_data() NIL-NUM range
--SKIPIF--
<?php 
include 'skip.inc';
checkcgi();
?>
--ENV--
HTTP_RANGE=bytes=-5
--FILE--
<?php
http_send_content_type('text/plain');
http_send_data(str_repeat('123abc', 1000));
?>
--EXPECTF--
Status: 206
X-Powered-By: PHP/%s
Content-Type: text/plain
Accept-Ranges: bytes
Content-Range: bytes 5995-5999/6000
Content-Length: 5

23abc
