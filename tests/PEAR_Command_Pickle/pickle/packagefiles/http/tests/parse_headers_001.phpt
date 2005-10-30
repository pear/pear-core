--TEST--
http_parse_headers()
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";
print_r(http_parse_headers(
"Host: localhost\r\n".
"Host: ambigious\r\n".
"Nospace:here\r\n".
"Muchspace:  there   \r\n".
"Empty:\r\n".
"Empty2: \r\n".
": invalid\r\n".
" : bogus\r\n".
"Folded: one\r\n".
"\ttwo\r\n".
"  three\r\n".
"stop\r\n"
));
?>
--EXPECTF--
%sTEST
Array
(
    [Host] => Array
        (
            [0] => localhost
            [1] => ambigious
        )

    [Nospace] => here
    [Muchspace] => there
    [Empty] => 
    [Empty2] => 
    [Folded] => one
	two
  three
)

