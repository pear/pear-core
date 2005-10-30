--TEST--
get request data
--SKIPIF--
<?php
include 'skip.inc';
?>
--ENV--
HTTP_ACCEPT_CHARSET=iso-8859-1, *
HTTP_ACCEPT_ENCODING=none
HTTP_USER_AGENT=Mozilla/5.0
HTTP_HOST=localhost
--POST--
a=b&c=d
--FILE--
<?php
echo "-TEST\n";
$h = http_get_request_headers();
ksort($h);
print_r($h);
$b = http_get_request_body();
if (php_sapi_name() == 'cli' || $b == 'a=b&c=d') {
	echo "OK\n";
}
?>
===DONE===
--EXPECTF--
%sTEST
Array
(
    [Accept-Charset] => iso-8859-1, *
    [Accept-Encoding] => none
    [Host] => localhost
    [User-Agent] => Mozilla/5.0
)
OK
===DONE===
