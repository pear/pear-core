--TEST--
PHP_Parser: test docblock description text/escape
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//text(A) ::= text(B) escape(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**hi<<code>>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//text(A) ::= escape(B).
//escape(A) ::= DOCBLOCK_ESCAPEDHTML(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<<code>>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//escape(A) ::= DOCBLOCK_ESCAPEDINLINETAG(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@*}*/');
$b = new PHP_Parser_DocblockParser($a);

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
  string(8) "hi<code>"
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  string(6) "<code>"
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  string(2) "*/"
  ["tags"]=>
  array(0) {
  }
}
===DONE===