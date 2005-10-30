--TEST--
negotiation
--SKIPIF--
<?php
include 'skip.inc';
?>
--ENV--
HTTP_ACCEPT_LANGUAGE=de-AT,de-DE;q=0.8,en-GB;q=0.3,en-US;q=0.2
HTTP_ACCEPT_CHARSET=ISO-8859-1,utf-8;q=0.7,*;q=0.7
--FILE--
<?php
echo "-TEST\n";
$langs = array(
	array('de', 'en', 'es'),
);
$csets = array(
	array('utf-8', 'iso-8859-1'),
);
var_dump(http_negotiate_language($langs[0]));
var_dump(http_negotiate_language($langs[0], $lresult));
var_dump(http_negotiate_charset($csets[0]));
var_dump(http_negotiate_charset($csets[0], $cresult));
print_r($lresult);
print_r($cresult);
echo "Done\n";
--EXPECTF--
%sTEST
string(2) "de"
string(2) "de"
string(10) "iso-8859-1"
string(10) "iso-8859-1"
Array
(
    [de] => 900
    [en] => 0.27
)
Array
(
    [iso-8859-1] => 1000
    [utf-8] => 0.7
)
Done
