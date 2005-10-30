--TEST--
http_build_uri()
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php
$_SERVER['HTTP_HOST'] = 'www.example.com';
$url = '/path/?query#anchor';
echo "-TEST\n";
printf("-%s-\n", http_build_uri($url));
printf("-%s-\n", http_build_uri($url, 'https'));
printf("-%s-\n", http_build_uri($url, 'https', 'ssl.example.com'));
printf("-%s-\n", http_build_uri($url, 'ftp', 'ftp.example.com', 21));
echo "Done\n";
?>
--EXPECTF--
%sTEST
-http://www.example.com/path/?query#anchor-
-https://www.example.com/path/?query#anchor-
-https://ssl.example.com/path/?query#anchor-
-ftp://ftp.example.com/path/?query#anchor-
Done
