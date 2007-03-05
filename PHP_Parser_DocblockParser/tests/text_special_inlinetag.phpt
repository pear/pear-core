--TEST--
PHP_Parser: test docblock description text/special/inline tag
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//text(A) ::= special(B).
//special(A) ::= inline_tag(B). {A = B;}
//inline_tag(A) ::= DOCBLOCK_INLINETAG(B) inline_tag_contents(C) DOCBLOCK_ENDINLINETAG.
//inline_tag_contents(A) ::= DOCBLOCK_INLINETAGCONTENTS(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@hi there}*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//inline_tag_contents(A) ::= DOCBLOCK_ESCAPEDINLINEEND(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@hi\}}*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//inline_tag_contents(A) ::= inline_tag_contents(B) DOCBLOCK_INLINETAGCONTENTS(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@hi\} woo}*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//inline_tag_contents(A) ::= inline_tag_contents(B) DOCBLOCK_ESCAPEDINLINEEND(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@hi woo\}}*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//inline_tag(A) ::= DOCBLOCK_INLINETAG(B) DOCBLOCK_ENDINLINETAG.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**{@hi}*/');
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
  array(3) {
    ["type"]=>
    string(6) "inline"
    ["tag"]=>
    string(2) "hi"
    ["contents"]=>
    string(6) " there"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(3) {
    ["type"]=>
    string(6) "inline"
    ["tag"]=>
    string(2) "hi"
    ["contents"]=>
    string(1) "}"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(3) {
    ["type"]=>
    string(6) "inline"
    ["tag"]=>
    string(2) "hi"
    ["contents"]=>
    string(5) "} woo"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(3) {
    ["type"]=>
    string(6) "inline"
    ["tag"]=>
    string(2) "hi"
    ["contents"]=>
    string(5) " woo}"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(3) {
    ["type"]=>
    string(6) "inline"
    ["tag"]=>
    string(2) "hi"
    ["contents"]=>
    string(0) ""
  }
  ["tags"]=>
  array(0) {
  }
}
===DONE===