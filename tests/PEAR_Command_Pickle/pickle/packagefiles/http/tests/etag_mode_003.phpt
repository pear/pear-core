--TEST--
md5 etag
--SKIPIF--
<?php
include 'skip.inc';
checkcgi();
?>
--FILE--
<?php
ini_set('http.etag_mode', HTTP_ETAG_MD5);
http_cache_etag();
http_send_data("abc\n");
?>
--EXPECTF--
Content-type: %s
X-Powered-By: PHP/%s
Cache-Control: private, must-revalidate, max-age=0
Accept-Ranges: bytes
ETag: "0bee89b07a248e27c83fc3d5951213c1"
Content-Length: 4

abc
