--TEST--
http_send_data() etag caching
--SKIPIF--
<?php 
include 'skip.inc';
checkcgi();
?>
--ENV--
HTTP_IF_NONE_MATCH="0bee89b07a248e27c83fc3d5951213c1"
--FILE--
<?php
include 'log.inc';
log_prepare(_CACHE_LOG);
http_cache_etag();
http_send_data("abc\n");
?>
--EXPECTF--
Status: 304
Content-type: text/html
X-Powered-By: PHP/%s
Cache-Control: private, must-revalidate, max-age=0
%s
