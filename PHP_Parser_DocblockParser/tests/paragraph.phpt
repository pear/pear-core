--TEST--
PHP_Parser: test <p>paragraphs</p>
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//description(A) ::= text(WHITESPACE) paragraphs(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**
 * <p>hi</p>
 * <p>there</p>*/');
$b = new PHP_Parser_DocblockParser($a, false);
while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//description(A) ::= paragraphs(B). {A = B;}
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<p>hi</p><p>there</p>*/');
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
  array(2) {
    [0]=>
    array(1) {
      ["paragraph"]=>
      string(2) "hi"
    }
    [1]=>
    array(1) {
      ["paragraph"]=>
      string(5) "there"
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    [0]=>
    array(1) {
      ["paragraph"]=>
      string(2) "hi"
    }
    [1]=>
    array(1) {
      ["paragraph"]=>
      string(5) "there"
    }
  }
  ["tags"]=>
  array(0) {
  }
}
===DONE===