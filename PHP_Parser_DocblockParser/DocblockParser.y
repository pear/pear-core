%name PHP_Parser_DocblockParser
%declare_class {class PHP_Parser_DocblockParser}

%syntax_error {
/* ?><?php */
    echo "Syntax Error on line " . $this->lex->line . ": token '" . 
        $this->lex->value . "' while parsing rule:";
    foreach ($this->yystack as $entry) {
        echo $this->tokenName($entry->major) . ' ';
    }
    foreach ($this->yy_get_expected_tokens($yymajor) as $token) {
        $expect[] = self::$yyTokenName[$token];
    }
    if (count($expect) > 5) {
        $expect = array_slice($expect, 0, 5);
        $expect[] = '...';
    }
    throw new Exception('Unexpected ' . $this->tokenName($yymajor) . '(' . $TOKEN
        . '), expected one of: ' . implode(',', $expect));
}

%include_class {
    static public $transTable = array (
        1 => self::DOCBLOCK_NEWLINE,
        2 => self::DOCBLOCK_WHITESPACE,
        5 => self::DOCBLOCK_TAG,
        6 => self::DOCBLOCK_INTERNAL,
        7 => self::DOCBLOCK_INLINETAG,
        9 => self::DOCBLOCK_ESCAPEDINLINETAG,
        10 => self::DOCBLOCK_INLINETAGCONTENTS,
        11 => self::DOCBLOCK_ESCAPEDHTML,
        12 => self::DOCBLOCK_CODEOPEN,
        13 => self::DOCBLOCK_PREOPEN,
        14 => self::DOCBLOCK_SAMPOPEN,
        15 => self::DOCBLOCK_VAROPEN,
        16 => self::DOCBLOCK_KBDOPEN,
        17 => self::DOCBLOCK_POPEN,
        18 => self::DOCBLOCK_BOPEN,
        19 => self::DOCBLOCK_IOPEN,
        20 => self::DOCBLOCK_OLOPEN,
        21 => self::DOCBLOCK_ULOPEN,
        22 => self::DOCBLOCK_LIOPEN,
        23 => self::DOCBLOCK_CODECLOSE,
        24 => self::DOCBLOCK_PRECLOSE,
        25 => self::DOCBLOCK_SAMPCLOSE,
        26 => self::DOCBLOCK_VARCLOSE,
        27 => self::DOCBLOCK_KBDCLOSE,
        28 => self::DOCBLOCK_PCLOSE,
        29 => self::DOCBLOCK_BCLOSE,
        30 => self::DOCBLOCK_ICLOSE,
        31 => self::DOCBLOCK_OLCLOSE,
        32 => self::DOCBLOCK_ULCLOSE,
        33 => self::DOCBLOCK_LICLOSE,
        34 => self::DOCBLOCK_BR,
        36 => self::DOCBLOCK_TEXT,
        37 => self::DOCBLOCK_ENDINLINETAG,
        38 => self::DOCBLOCK_ENDINTERNAL,
        39 => self::DOCBLOCK_ESCAPEDINLINEEND,
        42 => self::DOCBLOCK_SIMPLELISTSTART,
        43 => self::DOCBLOCK_SIMPLELISTEND,
        44 => self::DOCBLOCK_SIMPLELISTBULLET,
        );

    function __construct($lex, $processInternal = false)
    {
        $this->lex = $lex;
        $this->_processInternal = $processInternal;
    }

    public $data;
    private $_processInternal;
    private $_inInternal = false;
    private $_inP = false;
}

%parse_accept {
}

%left DOCBLOCK_SIMPLELISTBULLET DOCBLOCK_LIOPEN.
%left DOCBLOCK_POPEN.

start ::= docblock(A). {$this->data = A;}

docblock(A) ::= description(B) tags(C). {A = array('desc' => B, 'tags' => C);}
docblock(A) ::= description(B). {A = array('desc' => B, 'tags' => array());}

tags(A) ::= tags(B) tag(C). {
    A = B;
    A[C['tag']][] = C;
}
tags(A) ::= tag(B). {A = array(B['tag'] => array(B));}

%ifndef JAVADOC
description(A) ::= text(B). {A = B;}
%endif
description(A) ::= paragraphs(B). {A = B;}
description(A) ::= text(WHITESPACE) paragraphs(B). {
    if (!is_string(WHITESPACE) || trim(WHITESPACE)) {
        throw new Exception('Invalid docblock: cannot mix text-based paragraphs' .
            ' with P-based paragraphs');
    }
    A = B;
}
description(A) ::= . {A = array('');}

special(A) ::= html_tag(B). {A = B;}
special(A) ::= inline_tag(B). {A = B;}
special(A) ::= list(B). {A = B;}

inline_tag(A) ::= DOCBLOCK_INLINETAG(B) inline_tag_contents(C) DOCBLOCK_ENDINLINETAG. {
    A = array('type' => 'inline', 'tag' => substr(B, 2), 'contents' => C);
}
inline_tag(A) ::= DOCBLOCK_INLINETAG(B) DOCBLOCK_ENDINLINETAG. {
    A = array('type' => 'inline', 'tag' => substr(B, 2), 'contents' => '');
}

inline_tag_contents(A) ::= inline_tag_contents(B) DOCBLOCK_INLINETAGCONTENTS(C). {
    A = B . C;
}
inline_tag_contents(A) ::= inline_tag_contents(B) DOCBLOCK_ESCAPEDINLINEEND(C). {
    A = B . '}';
}
inline_tag_contents(A) ::= DOCBLOCK_INLINETAGCONTENTS(B). {A = B;}
inline_tag_contents(A) ::= DOCBLOCK_ESCAPEDINLINEEND(B). {A = '}';}

html_tag(A) ::= DOCBLOCK_BOPEN text(B) DOCBLOCK_BCLOSE. {
    A = array('type' => 'b', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_IOPEN text(B) DOCBLOCK_ICLOSE. {
    A = array('type' => 'i', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_CODEOPEN text(B) DOCBLOCK_CODECLOSE. {
    A = array('type' => 'code', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_PREOPEN text(B) DOCBLOCK_PRECLOSE. {
    A = array('type' => 'pre', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_SAMPOPEN text(B) DOCBLOCK_SAMPCLOSE. {
    A = array('type' => 'samp', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_VAROPEN text(B) DOCBLOCK_VARCLOSE. {
    A = array('type' => 'var', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_KBDOPEN text(B) DOCBLOCK_KBDCLOSE. {
    A = array('type' => 'kbd', 'text' => B);
}
html_tag(A) ::= DOCBLOCK_BR. {A = array('type' => 'br');}

escape(A) ::= DOCBLOCK_ESCAPEDHTML(B). {A = substr(B, 1, strlen(B) - 2);}
escape(A) ::= DOCBLOCK_ESCAPEDINLINETAG(B). {
    if (B == '{@*}') {
        A = '*/';
    } else {
        A = '{@';
    }
}

list(A) ::= simple_list(B). {A = B;}
list(A) ::= html_list(B). {A = B;}

simple_list(A) ::= DOCBLOCK_SIMPLELISTSTART simplelist_contents(B) DOCBLOCK_SIMPLELISTEND. {A = array('list' => B);}

simplelist_contents(A) ::= simplelist_contents(B) DOCBLOCK_SIMPLELISTBULLET(C) text(D). {
    A = B;
    A[] = array('index' => C, 'text' =>D);
}
simplelist_contents(A) ::= DOCBLOCK_SIMPLELISTBULLET(B) text(C). {
    A = array(array('index' => B, 'text' => C));
}

html_list(A) ::= ordered_list(B). {A = B;}
html_list(A) ::= unordered_list(B). {A = B;}

ordered_list(A) ::= DOCBLOCK_OLOPEN htmllist_contents(B) DOCBLOCK_OLCLOSE. {
    A = array();
    foreach (B as $i => $index) {
        A[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    A = array('list' => A);
}
ordered_list(A) ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents(B) DOCBLOCK_OLCLOSE. {
    A = array();
    foreach (B as $i => $index) {
        A[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    A = array('list' => A);
}
ordered_list(A) ::= DOCBLOCK_OLOPEN htmllist_contents_close(B) DOCBLOCK_OLCLOSE. {
    A = array();
    foreach (B as $i => $index) {
        A[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    A = array('list' => A);
}
ordered_list(A) ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents_close(B) DOCBLOCK_OLCLOSE. {
    A = array();
    foreach (B as $i => $index) {
        A[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    A = array('list' => A);
}
ordered_list(A) ::= DOCBLOCK_OLOPEN htmllist_contents_close(B) ignored_whitespace DOCBLOCK_OLCLOSE. {
    A = array();
    foreach (B as $i => $index) {
        A[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    A = array('list' => A);
}
ordered_list(A) ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents_close(B) ignored_whitespace DOCBLOCK_OLCLOSE. {
    A = array();
    foreach (B as $i => $index) {
        A[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    A = array('list' => A);
}

unordered_list(A) ::= DOCBLOCK_ULOPEN htmllist_contents(B) DOCBLOCK_ULCLOSE. {A = array('list' => B);}
unordered_list(A) ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents(B) DOCBLOCK_ULCLOSE. {A = array('list' => B);}
unordered_list(A) ::= DOCBLOCK_ULOPEN htmllist_contents_close(B) DOCBLOCK_ULCLOSE. {A = array('list' => B);}
unordered_list(A) ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents_close(B) DOCBLOCK_ULCLOSE. {A = array('list' => B);}
unordered_list(A) ::= DOCBLOCK_ULOPEN htmllist_contents_close(B) ignored_whitespace DOCBLOCK_ULCLOSE. {A = array('list' => B);}
unordered_list(A) ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents_close(B) ignored_whitespace DOCBLOCK_ULCLOSE. {A = array('list' => B);}

htmllist_contents(A) ::= htmllist_contents(B) DOCBLOCK_LIOPEN text(C). {
    A = B;
    A[] = array('index' => '*', 'text' => C);
}
htmllist_contents(A) ::= DOCBLOCK_LIOPEN text(B). {
    A = array(array('index' => '*', 'text' => B));
}

htmllist_contents_close(A) ::= htmllist_contents_close(B) DOCBLOCK_LIOPEN text(C) DOCBLOCK_LICLOSE. {
    A = B;
    A[] = array('index' => '*', 'text' => C);
}
htmllist_contents_close(A) ::= htmllist_contents_close(B) ignored_whitespace DOCBLOCK_LIOPEN text(C) DOCBLOCK_LICLOSE. {
    A = B;
    A[] = array('index' => '*', 'text' => C);
}
htmllist_contents_close(A) ::= DOCBLOCK_LIOPEN text(B) DOCBLOCK_LICLOSE. {
    A = array(array('index' => '*', 'text' => B));
}

ignored_whitespace ::= ignored_whitespace DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE.
ignored_whitespace ::= ignored_whitespace DOCBLOCK_TEXT(B). {
    if (trim(B)) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Unexpected DOCBLOCK_TEXT, expected one of DOCBLOCK_NEWLINE, DOCBLOCK_WHITESPACE');
    }
}
ignored_whitespace ::= DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE.
ignored_whitespace ::= DOCBLOCK_TEXT(B). {
    if (trim(B)) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Unexpected DOCBLOCK_TEXT, expected one of DOCBLOCK_NEWLINE, DOCBLOCK_WHITESPACE');
    }
}

stopinternal ::= DOCBLOCK_INTERNAL. {
    if ($this->_inInternal) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Cannot nest {@internal}}');
    }
    $this->_inInternal = true;
}
internal(A) ::= stopinternal text(B) DOCBLOCK_ENDINTERNAL. {
    $this->_inInternal = false;
    A = '';
    if ($this->_processInternal) {
        A = B; 
    }
}

paragraphs(A) ::= paragraphs(B) paragraph(C). {
    A = B;
    A[] = C;
}
paragraphs(A) ::= paragraph(C). {
    A = array(C);
}

%ifdef JAVADOC
paragraph(A) ::= DOCBLOCK_POPEN text(B). {
    A = array('paragraph' => B);
}
%endif
%ifndef JAVADOC
nonestingp ::= DOCBLOCK_POPEN. {
    if ($this->_inP) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Cannot nest <p>');
    }
    $this->_inP = true;
}
paragraph(A) ::= nonestingp text(B) DOCBLOCK_PCLOSE. {
    $this->_inP = false;
    A = array('paragraph' => B);
}
paragraph(A) ::= nonestingp text(B) DOCBLOCK_PCLOSE ignored_whitespace. {
    $this->_inP = false;
    A = array('paragraph' => B);
}
%endif

text(A) ::= text(B) DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE|DOCBLOCK_TEXT(C). {
    if (is_string(B)) {
        A = B . C;
    } else {
        $i = count(B) - 1;
        if (is_string(B[$i])) {
            B[$i] .= C;
        } else {
            B[] = C;
        }
        A = B;
    }
}
text(A) ::= text(B) escape(C). {
    if (is_string(B)) {
        A = B . C;
    } else {
        $i = count(B) - 1;
        if (is_string(B[$i])) {
            B[$i] .= C;
        } else {
            B[] = C;
        }
        A = B;
    }
}
text(A) ::= text(B) special(C). {
    if (C === '') {
        return A = B;
    }
    if (is_string(C) && is_string(B)) {
        return A = B . C;
    }
    if (is_string(B)) {
        B = array(B);
    }
    A = B;
    A[] = C;
}
text(A) ::= text(B) internal(C). {
    if (C === '') {
        return A = B;
    }
    if (is_string(C) && is_string(B)) {
        return A = B . C;
    }
    if (is_string(B)) {
        B = array(B);
    }
    // check to see if B's last entry and C's first are strings
    if (isset(C[0])) {
        if (is_string(B[count(B) - 1]) && is_string(C[0])) {
            B[count(B) - 1] .= C[0];
            array_shift(C);
        }
        A = array_merge(B, C);
    } else {
        A = B;
        A[] = C;
    }
}
text(A) ::= DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE|DOCBLOCK_TEXT(B). {A = B;}
text(A) ::= escape(B). {A = B;}
text(A) ::= special(B). {A = B;}
text(A) ::= internal(B). {A = B;}

tag(A) ::= DOCBLOCK_TAG(B) text(C). {
    A = array(
        'tag' => substr(B, 1),
        'text' => C,
    );
}
tag(A) ::= DOCBLOCK_TAG(B). {
    A = array(
        'tag' => substr(B, 1),
        'text' => '',
    );
}