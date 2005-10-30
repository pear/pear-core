--TEST--
identity encoding trap
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
echo "-TEST\n";

$message =
"HTTP/1.1 200 Ok\n".
"Transfer-Encoding: identity\n".
"Content-Length: 3\n".
"Content-Type: text/plain\n\n".
"Hi!foo";

print_r(http_parse_message($message));

echo "Done\n";
--EXPECTF--
%sTEST
stdClass Object
(
    [type] => 2
    [httpVersion] => 1.1
    [responseCode] => 200
    [responseStatus] => Ok
    [headers] => Array
        (
            [Transfer-Encoding] => identity
            [Content-Length] => 3
            [Content-Type] => text/plain
        )

    [body] => Hi!
    [parentMessage] => 
)
Done
