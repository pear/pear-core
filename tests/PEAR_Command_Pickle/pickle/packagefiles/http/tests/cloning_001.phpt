--TEST--
cloning
--SKIPIF--
<?php
include 'skip.inc';
checkver(5);
checkcls('HttpRequest');
?>
--FILE--
<?php
echo "-TEST\n";

$r1 = new HttpRequest;
$r2 = clone $r1;
$r1->setOptions(array('redirect' => 3));
var_dump($r1->getOptions() == $r2->getOptions());
$r1->setUrl('http://www.google.com/');
var_dump($r1->getUrl() == $r2->getUrl());
$r1->send();
var_dump($r1->getResponseInfo() == $r2->getResponseInfo());

echo "Done\n";
?>
--EXPECTF--
%sTEST
bool(false)
bool(false)
bool(false)
Done
