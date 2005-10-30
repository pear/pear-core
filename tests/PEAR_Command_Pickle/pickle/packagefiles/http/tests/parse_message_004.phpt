--TEST--
http_parse_message() recursive
--SKIPIF--
<?php
include 'skip.inc';
?>
--FILE--
<?php

echo "-TEST\n";
$message =
"HEAD / HTTP/1.1
Host: www.example.com
Accept: */*
HTTP/1.1 200 Ok
Server: Funky/1.0
Content-Length: 10
GET / HTTP/1.1
Host: www.example.com
Accept: */*
HTTP/1.1 200 Ok
Server: Funky/1.0
Content-Length: 10

1234567890
";

var_dump(http_parse_message($message));

echo "Done\n";
?>
--EXPECTF--
%sTEST
object(stdClass)#1 (7) {
  ["type"]=>
  int(2)
  ["httpVersion"]=>
  float(1.1)
  ["responseCode"]=>
  int(200)
  ["responseStatus"]=>
  string(2) "Ok"
  ["headers"]=>
  array(2) {
    ["Server"]=>
    string(9) "Funky/1.0"
    ["Content-Length"]=>
    string(2) "10"
  }
  ["body"]=>
  string(10) "1234567890"
  ["parentMessage"]=>
  object(stdClass)#2 (7) {
    ["type"]=>
    int(1)
    ["httpVersion"]=>
    float(1.1)
    ["requestMethod"]=>
    string(3) "GET"
    ["requestUri"]=>
    string(1) "/"
    ["headers"]=>
    array(2) {
      ["Host"]=>
      string(15) "www.example.com"
      ["Accept"]=>
      string(3) "*/*"
    }
    ["body"]=>
    string(0) ""
    ["parentMessage"]=>
    object(stdClass)#3 (7) {
      ["type"]=>
      int(2)
      ["httpVersion"]=>
      float(1.1)
      ["responseCode"]=>
      int(200)
      ["responseStatus"]=>
      string(2) "Ok"
      ["headers"]=>
      array(2) {
        ["Server"]=>
        string(9) "Funky/1.0"
        ["Content-Length"]=>
        string(2) "10"
      }
      ["body"]=>
      string(0) ""
      ["parentMessage"]=>
      object(stdClass)#4 (7) {
        ["type"]=>
        int(1)
        ["httpVersion"]=>
        float(1.1)
        ["requestMethod"]=>
        string(4) "HEAD"
        ["requestUri"]=>
        string(1) "/"
        ["headers"]=>
        array(2) {
          ["Host"]=>
          string(15) "www.example.com"
          ["Accept"]=>
          string(3) "*/*"
        }
        ["body"]=>
        string(0) ""
        ["parentMessage"]=>
        NULL
      }
    }
  }
}
Done
