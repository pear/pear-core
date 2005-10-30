--TEST--
HttpRequest SSL
--SKIPIF--
<?php
include 'skip.inc';
checkver(5);
checkcls('HttpRequest');
checkurl('arweb.info');
?>
--FILE--
<?php
echo "-TEST\n";
$r = new HttpRequest('https://ssl.arweb.info/iworks/data.txt');
$r->send();
var_dump($r->getResponseBody());
?>
--EXPECTF--
%sTEST
string(10) "1234567890"

