--TEST--
logging redirects
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
log_content(_REDIR_LOG);
echo "Done";
?>
--EXPECTF--
%sTEST
%d%d%d%d-%d%d-%d%d %d%d:%d%d:%d%d	[302-REDIRECT]	Location: http%s	<%s>
Done
