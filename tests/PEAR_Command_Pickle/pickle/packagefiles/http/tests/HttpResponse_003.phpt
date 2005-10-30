--TEST--
HttpResponse - send gzipped file with caching headers
--SKIPIF--
<?php 
include 'skip.inc';
checkver(5);
checkcgi();
checkext('zlib');
?>
--ENV--
HTTP_ACCEPT_ENCODING=gzip
--FILE--
<?php
HttpResponse::setGzip(true);
HttpResponse::setCache(true);
HttpResponse::setCacheControl('public', 3600);
HttpResponse::setFile(__FILE__);
HttpResponse::send();
?>
--EXPECTF--
X-Powered-By: PHP/%s
ETag: "%s"
Cache-Control: public, must-revalidate, max_age=3600
Last-Modified: %s, %d %s 20%d %d:%d:%d GMT
Content-Type: %s
Accept-Ranges: bytes
Content-Encoding: gzip
Vary: Accept-Encoding

%s
