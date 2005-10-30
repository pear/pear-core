--TEST--
HttpResponse - send data with caching headers
--SKIPIF--
<?php 
include 'skip.inc';
checkver(5);
checkcgi();
?>
--FILE--
<?php
HttpResponse::setCache(true);
HttpResponse::setCacheControl('public', 3600);
HttpResponse::setData('foobar');
HttpResponse::send();
?>
--EXPECTF--
X-Powered-By: PHP/%s
ETag: "3858f62230ac3c915f300c664312c63f"
Cache-Control: public, must-revalidate, max_age=3600
Last-Modified: %s, %d %s 20%d %d:%d:%d GMT
Content-Type: %s
Accept-Ranges: bytes
Content-Length: 6

foobar