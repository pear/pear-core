--TEST--
PHP_Parser: test empty docblock
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
$a = new PHP_Parser_DocblockParser_Tokenizer('/***/');
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
  array(1) {
    [0]=>
    string(0) ""
  }
  ["tags"]=>
  array(0) {
  }
}
===DONE===