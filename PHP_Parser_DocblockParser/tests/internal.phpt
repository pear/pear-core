--TEST--
PHP_Parser: test {@internal}}
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
$a = new PHP_Parser_DocblockParser_Tokenizer('/**
 * {@internal hi }}*/');
$b = new PHP_Parser_DocblockParser($a, false);
while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
$a = new PHP_Parser_DocblockParser_Tokenizer('/**
 * {@internal hi }}*/');
$b = new PHP_Parser_DocblockParser($a, true);
while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
try {
    $a = new PHP_Parser_DocblockParser_Tokenizer('/**
     * {@internal hi {@internal oops}}}}*/');
    $b = new PHP_Parser_DocblockParser($a, true);
    while ($a->advance()) {
        $b->doParse($a->token, $a->getValue(), $a);
    }
    $b->doParse(0, 0);
    var_dump($b->data);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
===DONE===
--EXPECT--
array(2) {
  ["desc"]=>
  string(2) "
 "
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  string(6) "
  hi "
  ["tags"]=>
  array(0) {
  }
}
Syntax error on line 2: Cannot nest {@internal}}
===DONE===