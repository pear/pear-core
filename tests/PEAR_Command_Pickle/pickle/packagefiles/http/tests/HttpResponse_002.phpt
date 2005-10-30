--TEST--
HttpResponse - send gzipped file
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
HttpResponse::setFile(__FILE__);
HttpResponse::send();
?>
--EXPECTF--
X-Powered-By: PHP/%s
Content-Type: %s
Accept-Ranges: bytes
Content-Encoding: gzip
Vary: Accept-Encoding

%s
