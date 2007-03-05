--TEST--
PHP_Parser: test @tags
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//tags(A) ::= tag(B).
//tag(A) ::= DOCBLOCK_TAG(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**@tag*/');
$b = new PHP_Parser_DocblockParser($a, false);
while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//tag(A) ::= DOCBLOCK_TAG(B) text(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**@tag hi there*/');
$b = new PHP_Parser_DocblockParser($a, false);
while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//tags(A) ::= tags(B) tag(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**
* hi booya
* @tag hi there
* @another {@tag me}
*/');
$b = new PHP_Parser_DocblockParser($a, false);
while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
?>
===DONE===
--EXPECT--
array(2) {
  ["desc"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
  ["tags"]=>
  array(1) {
    ["tag"]=>
    array(1) {
      [0]=>
      array(2) {
        ["tag"]=>
        string(3) "tag"
        ["text"]=>
        string(0) ""
      }
    }
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
  ["tags"]=>
  array(1) {
    ["tag"]=>
    array(1) {
      [0]=>
      array(2) {
        ["tag"]=>
        string(3) "tag"
        ["text"]=>
        string(9) " hi there"
      }
    }
  }
}
array(2) {
  ["desc"]=>
  string(12) "
 hi booya
 "
  ["tags"]=>
  array(2) {
    ["tag"]=>
    array(1) {
      [0]=>
      array(2) {
        ["tag"]=>
        string(3) "tag"
        ["text"]=>
        string(11) " hi there
 "
      }
    }
    ["another"]=>
    array(1) {
      [0]=>
      array(2) {
        ["tag"]=>
        string(7) "another"
        ["text"]=>
        array(3) {
          [0]=>
          string(1) " "
          [1]=>
          array(3) {
            ["type"]=>
            string(6) "inline"
            ["tag"]=>
            string(3) "tag"
            ["contents"]=>
            string(3) " me"
          }
          [2]=>
          string(1) "
"
        }
      }
    }
  }
}
===DONE===