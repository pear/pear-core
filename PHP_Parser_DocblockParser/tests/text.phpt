--TEST--
PHP_Parser: test docblock description
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//text(A) ::= DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE|DOCBLOCK_TEXT(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**
 * basic text
 */');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//internal_without_paragraphs(A) ::= DOCBLOCK_INTERNAL text_without_internal(B) DOCBLOCK_ENDINTERNAL.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@internal hi }}*/');
$b = new PHP_Parser_DocblockParser($a, false);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//internal_without_paragraphs(A) ::= DOCBLOCK_INTERNAL text_without_internal(B) DOCBLOCK_ENDINTERNAL.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@internal hi }}*/');
$b = new PHP_Parser_DocblockParser($a, true);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//text(A) ::= text(B) DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE|DOCBLOCK_TEXT(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**
 * basic text{@inline}hi
 */');
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
  string(13) "
 basic text
"
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  string(0) ""
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  string(4) " hi "
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(3) {
    [0]=>
    string(12) "
 basic text"
    [1]=>
    array(3) {
      ["type"]=>
      string(6) "inline"
      ["tag"]=>
      string(6) "inline"
      ["contents"]=>
      string(0) ""
    }
    [2]=>
    string(3) "hi
"
  }
  ["tags"]=>
  array(0) {
  }
}
===DONE===