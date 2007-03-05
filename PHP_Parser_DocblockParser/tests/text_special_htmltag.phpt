--TEST--
PHP_Parser: test docblock description text/special/htmltag
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//text(A) ::= text(B) special(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/** hi<b>hi</b>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//text(A) ::= special(B).
//special(A) ::= html_tag(B). {A = B;}
//html_tag(A) ::= DOCBLOCK_BOPEN text(B) DOCBLOCK_BCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<b>hi</b>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_IOPEN text(B) DOCBLOCK_ICLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<i>hi</i>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_CODEOPEN text(B) DOCBLOCK_CODECLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<code>hi</code>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_PREOPEN text(B) DOCBLOCK_PRECLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<pre>hi</pre>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_SAMPOPEN text(B) DOCBLOCK_SAMPCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<samp>hi</samp>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_VAROPEN text(B) DOCBLOCK_VARCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<var>hi</var>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_KBDOPEN text(B) DOCBLOCK_KBDCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<kbd>hi</kbd>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_tag(A) ::= DOCBLOCK_BR.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<br>*/');
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
  array(2) {
    [0]=>
    string(3) " hi"
    [1]=>
    array(2) {
      ["type"]=>
      string(1) "b"
      ["text"]=>
      string(2) "hi"
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(1) "b"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(1) "i"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(4) "code"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(3) "pre"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(4) "samp"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(3) "var"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(2) {
    ["type"]=>
    string(3) "kbd"
    ["text"]=>
    string(2) "hi"
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["type"]=>
    string(2) "br"
  }
  ["tags"]=>
  array(0) {
  }
}
===DONE===