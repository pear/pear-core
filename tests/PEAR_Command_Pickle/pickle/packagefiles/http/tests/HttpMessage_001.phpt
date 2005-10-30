--TEST--
HttpMessage
--SKIPIF--
<?php
include 'skip.inc';
checkver(5);
?>
--FILE--
<?php
echo "-TEST\n";
$m = new HttpMessage(
	"HTTP/1.1 301\r\n".
	"Location: /anywhere\r\n".
	"HTTP/1.1 302\r\n".
	"Location: /somwhere\r\n".
	"HTTP/1.1 206\r\n".
	"Content-Range: bytes=2-3\r\n".
	"Transfer-Encoding: chunked\r\n".
	"\r\n".
	"01\r\n".
	"X\r\n".
	"00"
);

var_dump($m->getBody());
var_dump($m->toString(true));
var_dump(HttpMessage::fromString($m->toString(true))->toString(true));
?>
--EXPECTF--
%sTEST
string(1) "X"
string(134) "HTTP/1.1 301
Location: /anywhere
HTTP/1.1 302
Location: /somwhere
HTTP/1.1 206
Content-Range: bytes=2-3
Content-Length: 1

X
"
string(134) "HTTP/1.1 301
Location: /anywhere
HTTP/1.1 302
Location: /somwhere
HTTP/1.1 206
Content-Range: bytes=2-3
Content-Length: 1

X
"

