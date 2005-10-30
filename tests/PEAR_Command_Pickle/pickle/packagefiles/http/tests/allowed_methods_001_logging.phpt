--TEST--
logging allowed methods
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
log_content(_AMETH_LOG);
echo "Done";
?>
--EXPECTF--
%sTEST
%d%d%d%d-%d%d-%d%d %d%d:%d%d:%d%d	[405-ALLOWED]	Allow: POST	<%s>
Done
