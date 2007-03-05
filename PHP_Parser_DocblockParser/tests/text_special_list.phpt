--TEST--
PHP_Parser: test docblock description text/special/list
--FILE--
<?php
require_once 'PHP/Parser/DocblockParser.php';
require_once 'PHP/Parser/DocblockParser/Tokenizer.php';
//text(A) ::= special(B).
//special(A) ::= list(B). {A = B;}
//list(A) ::= simple_list(B).
//simple_list(A) ::= DOCBLOCK_SIMPLELISTSTART simplelist_contents(B) DOCBLOCK_SIMPLELISTEND.
//simplelist_contents(A) ::= DOCBLOCK_SIMPLELISTBULLET(B) text(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/** - hi*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//simplelist_contents(A) ::= simplelist_contents(B) DOCBLOCK_SIMPLELISTBULLET(C) text(D).
$a = new PHP_Parser_DocblockParser_Tokenizer('/** - hi
 * - there*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//list(A) ::= html_list(B).
//html_list(A) ::= ordered_list(B).
//ordered_list(A) ::= DOCBLOCK_OLOPEN htmllist_contents(B) DOCBLOCK_OLCLOSE.
//htmllist_contents(A) ::= DOCBLOCK_LIOPEN text(B).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol><li>hi</ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//htmllist_contents(A) ::= htmllist_contents(B) DOCBLOCK_LIOPEN text(C).
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol><li>hi<li>there</ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//ordered_list(A) ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents(B) DOCBLOCK_OLCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol>
 * <li>hi<li>there</ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//ordered_list(A) ::= DOCBLOCK_OLOPEN htmllist_contents_close(B) DOCBLOCK_OLCLOSE.
//htmllist_contents_close(A) ::= DOCBLOCK_LIOPEN text(B) DOCBLOCK_LICLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol><li>hi</li></ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//htmllist_contents_close(A) ::= htmllist_contents_close(B) DOCBLOCK_LIOPEN text(C) DOCBLOCK_LICLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol><li>hi</li><li>there</li></ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//htmllist_contents_close(A) ::= htmllist_contents_close(B) ignored_whitespace DOCBLOCK_LIOPEN text(B) DOCBLOCK_LICLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol><li>hi</li> 
* <li>there</li></ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//ordered_list(A) ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents_close(B) DOCBLOCK_OLCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol>
 * <li>hi</li></ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//ordered_list(A) ::= DOCBLOCK_OLOPEN htmllist_contents_close(B) ignored_whitespace DOCBLOCK_OLCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol><li>hi</li>
* </ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//ordered_list(A) ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents_close(B) ignored_whitespace DOCBLOCK_OLCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ol>
 * <li>hi</li>
 * </ol>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//html_list(A) ::= unordered_list(B).
//unordered_list(A) ::= DOCBLOCK_ULOPEN htmllist_contents(B) DOCBLOCK_ULCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ul><li>hi</ul>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//unordered_list(A) ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents(B) DOCBLOCK_ULCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ul>
 * <li>hi</ul>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//unordered_list(A) ::= DOCBLOCK_ULOPEN htmllist_contents_close(B) DOCBLOCK_ULCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ul><li>hi</li></ul>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//unordered_list(A) ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents_close(B) DOCBLOCK_ULCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ul>
 * <li>hi</li></ul>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//unordered_list(A) ::= DOCBLOCK_ULOPEN htmllist_contents_close(B) ignored_whitespace DOCBLOCK_ULCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ul><li>hi</li>
 * </ul>*/');
$b = new PHP_Parser_DocblockParser($a);

while ($a->advance()) {
    $b->doParse($a->token, $a->getValue(), $a);
}
$b->doParse(0, 0);
var_dump($b->data);
//unordered_list(A) ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents_close(B) ignored_whitespace DOCBLOCK_ULCLOSE.
$a = new PHP_Parser_DocblockParser_Tokenizer('/**<ul>
 * <li>hi</li>
 * </ul>*/');
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
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "-"
        ["text"]=>
        string(3) " hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(2) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "-"
        ["text"]=>
        string(5) " hi
 "
      }
      [1]=>
      array(2) {
        ["index"]=>
        string(1) "-"
        ["text"]=>
        string(6) " there"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(2) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
      [1]=>
      array(2) {
        ["index"]=>
        int(2)
        ["text"]=>
        string(5) "there"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(2) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
      [1]=>
      array(2) {
        ["index"]=>
        int(2)
        ["text"]=>
        string(5) "there"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(2) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
      [1]=>
      array(2) {
        ["index"]=>
        int(2)
        ["text"]=>
        string(5) "there"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(2) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
      [1]=>
      array(2) {
        ["index"]=>
        int(2)
        ["text"]=>
        string(5) "there"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        int(1)
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "*"
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "*"
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "*"
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "*"
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "*"
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
array(2) {
  ["desc"]=>
  array(1) {
    ["list"]=>
    array(1) {
      [0]=>
      array(2) {
        ["index"]=>
        string(1) "*"
        ["text"]=>
        string(2) "hi"
      }
    }
  }
  ["tags"]=>
  array(0) {
  }
}
===DONE===