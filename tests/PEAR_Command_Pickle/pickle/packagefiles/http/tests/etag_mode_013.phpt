--TEST--
ob md5 etag
--SKIPIF--
<?php
include 'skip.inc';
checkcgi();
?>
--FILE--
<?php
ini_set('http.etag_mode', HTTP_ETAG_MD5);
http_cache_etag();
print("abc\n");
?>
--EXPECTF--
Content-type: %s
X-Powered-By: PHP/%s
Cache-Control: private, must-revalidate, max-age=0
ETag: "0bee89b07a248e27c83fc3d5951213c1"

abc
