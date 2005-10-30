--TEST--
logging caching
--SKIPIF--
<?php
include 'skip.inc';
checkcgi();
?>
--ENV--
HTTP_HOST=example.com
--FILE--
<?php
echo "-TEST\n";
include 'log.inc';
log_content(_CACHE_LOG);
echo "Done";
?>
--EXPECTF--
%sTEST
%d%d%d%d-%d%d-%d%d %d%d:%d%d:%d%d	[304-CACHE]	ETag: "%s"	<%s>
Done
