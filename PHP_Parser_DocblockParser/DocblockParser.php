<?php
/* Driver template for the PHP_PHP_Parser_DocblockParserrGenerator parser generator. (PHP port of LEMON)
*/

/**
 * This can be used to store both the string representation of
 * a token, and any useful meta-data associated with the token.
 *
 * meta-data should be stored as an array
 */
class PHP_Parser_DocblockParseryyToken implements ArrayAccess
{
    public $string = '';
    public $metadata = array();

    function __construct($s, $m = array())
    {
        if ($s instanceof PHP_Parser_DocblockParseryyToken) {
            $this->string = $s->string;
            $this->metadata = $s->metadata;
        } else {
            $this->string = (string) $s;
            if ($m instanceof PHP_Parser_DocblockParseryyToken) {
                $this->metadata = $m->metadata;
            } elseif (is_array($m)) {
                $this->metadata = $m;
            }
        }
    }

    function __toString()
    {
        return $this->_string;
    }

    function offsetExists($offset)
    {
        return isset($this->metadata[$offset]);
    }

    function offsetGet($offset)
    {
        return $this->metadata[$offset];
    }

    function offsetSet($offset, $value)
    {
        if ($offset === null) {
            if (isset($value[0])) {
                $x = ($value instanceof PHP_Parser_DocblockParseryyToken) ?
                    $value->metadata : $value;
                $this->metadata = array_merge($this->metadata, $x);
                return;
            }
            $offset = count($this->metadata);
        }
        if ($value === null) {
            return;
        }
        if ($value instanceof PHP_Parser_DocblockParseryyToken) {
            if ($value->metadata) {
                $this->metadata[$offset] = $value->metadata;
            }
        } elseif ($value) {
            $this->metadata[$offset] = $value;
        }
    }

    function offsetUnset($offset)
    {
        unset($this->metadata[$offset]);
    }
}

/** The following structure represents a single element of the
 * parser's stack.  Information stored includes:
 *
 *   +  The state number for the parser at this level of the stack.
 *
 *   +  The value of the token stored at this level of the stack.
 *      (In other words, the "major" token.)
 *
 *   +  The semantic value stored at this level of the stack.  This is
 *      the information used by the action routines in the grammar.
 *      It is sometimes called the "minor" token.
 */
class PHP_Parser_DocblockParseryyStackEntry
{
    public $stateno;       /* The state-number */
    public $major;         /* The major token value.  This is the code
                     ** number for the token at this stack level */
    public $minor; /* The user-supplied minor token value.  This
                     ** is the value of the token  */
};

// code external to the class is included here

// declare_class is output here
#line 2 "DocblockParser.y"
class PHP_Parser_DocblockParser#line 102 "DocblockParser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 22 "DocblockParser.y"

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
#line 160 "DocblockParser.php"

/* Next is all token values, as class constants
*/
/* 
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands. 
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const DOCBLOCK_SIMPLELISTBULLET      =  1;
    const DOCBLOCK_LIOPEN                =  2;
    const DOCBLOCK_POPEN                 =  3;
    const DOCBLOCK_INLINETAG             =  4;
    const DOCBLOCK_ENDINLINETAG          =  5;
    const DOCBLOCK_INLINETAGCONTENTS     =  6;
    const DOCBLOCK_ESCAPEDINLINEEND      =  7;
    const DOCBLOCK_BOPEN                 =  8;
    const DOCBLOCK_BCLOSE                =  9;
    const DOCBLOCK_IOPEN                 = 10;
    const DOCBLOCK_ICLOSE                = 11;
    const DOCBLOCK_CODEOPEN              = 12;
    const DOCBLOCK_CODECLOSE             = 13;
    const DOCBLOCK_PREOPEN               = 14;
    const DOCBLOCK_PRECLOSE              = 15;
    const DOCBLOCK_SAMPOPEN              = 16;
    const DOCBLOCK_SAMPCLOSE             = 17;
    const DOCBLOCK_VAROPEN               = 18;
    const DOCBLOCK_VARCLOSE              = 19;
    const DOCBLOCK_KBDOPEN               = 20;
    const DOCBLOCK_KBDCLOSE              = 21;
    const DOCBLOCK_BR                    = 22;
    const DOCBLOCK_ESCAPEDHTML           = 23;
    const DOCBLOCK_ESCAPEDINLINETAG      = 24;
    const DOCBLOCK_SIMPLELISTSTART       = 25;
    const DOCBLOCK_SIMPLELISTEND         = 26;
    const DOCBLOCK_OLOPEN                = 27;
    const DOCBLOCK_OLCLOSE               = 28;
    const DOCBLOCK_ULOPEN                = 29;
    const DOCBLOCK_ULCLOSE               = 30;
    const DOCBLOCK_LICLOSE               = 31;
    const DOCBLOCK_NEWLINE               = 32;
    const DOCBLOCK_WHITESPACE            = 33;
    const DOCBLOCK_TEXT                  = 34;
    const DOCBLOCK_INTERNAL              = 35;
    const DOCBLOCK_ENDINTERNAL           = 36;
    const DOCBLOCK_PCLOSE                = 37;
    const DOCBLOCK_TAG                   = 38;
    const YY_NO_ACTION = 194;
    const YY_ACCEPT_ACTION = 193;
    const YY_ERROR_ACTION = 192;

/* Next are that tables used to determine what action to take based on the
** current state and lookahead token.  These tables are used to implement
** functions that take a state number and lookahead value and return an
** action integer.  
**
** Suppose the action integer is N.  Then the action is determined as
** follows
**
**   0 <= N < self::YYNSTATE                              Shift N.  That is,
**                                                        push the lookahead
**                                                        token onto the stack
**                                                        and goto state N.
**
**   self::YYNSTATE <= N < self::YYNSTATE+self::YYNRULE   Reduce by rule N-YYNSTATE.
**
**   N == self::YYNSTATE+self::YYNRULE                    A syntax error has occurred.
**
**   N == self::YYNSTATE+self::YYNRULE+1                  The parser accepts its
**                                                        input. (and concludes parsing)
**
**   N == self::YYNSTATE+self::YYNRULE+2                  No such action.  Denotes unused
**                                                        slots in the yy_action[] table.
**
** The action table is constructed as a single large static array $yy_action.
** Given state S and lookahead X, the action is computed as
**
**      self::$yy_action[self::$yy_shift_ofst[S] + X ]
**
** If the index value self::$yy_shift_ofst[S]+X is out of range or if the value
** self::$yy_lookahead[self::$yy_shift_ofst[S]+X] is not equal to X or if
** self::$yy_shift_ofst[S] is equal to self::YY_SHIFT_USE_DFLT, it means that
** the action is not in the table and that self::$yy_default[S] should be used instead.  
**
** The formula above is for computing the action when the lookahead is
** a terminal symbol.  If the lookahead is a non-terminal (as occurs after
** a reduce action) then the static $yy_reduce_ofst array is used in place of
** the static $yy_shift_ofst array and self::YY_REDUCE_USE_DFLT is used in place of
** self::YY_SHIFT_USE_DFLT.
**
** The following are the tables generated in this section:
**
**  self::$yy_action        A single table containing all actions.
**  self::$yy_lookahead     A table containing the lookahead for each entry in
**                          yy_action.  Used to detect hash collisions.
**  self::$yy_shift_ofst    For each state, the offset into self::$yy_action for
**                          shifting terminals.
**  self::$yy_reduce_ofst   For each state, the offset into self::$yy_action for
**                          shifting non-terminals after a reduce.
**  self::$yy_default       Default action for each state.
*/
    const YY_SZ_ACTTAB = 928;
static public $yy_action = array(
 /*     0 */    46,   17,   69,   16,    5,   55,    9,   42,    7,   14,
 /*    10 */    10,   12,    6,   14,    2,   14,    4,   49,   86,   85,
 /*    20 */    98,   48,   56,   35,   41,   34,  111,   61,  100,  100,
 /*    30 */   100,  102,   46,   62,   62,   65,    5,  117,    9,   74,
 /*    40 */     7,   53,   10,   78,    6,    8,    2,   84,    4,   54,
 /*    50 */    86,   85,   98,   48,   14,   35,   51,   34,   47,   67,
 /*    60 */   100,  100,  100,  102,   57,   46,   64,   64,   63,    5,
 /*    70 */    92,    9,  140,    7,  140,   10,   50,    6,   52,    2,
 /*    80 */   114,    4,   87,   86,   85,   98,   48,  140,   35,  140,
 /*    90 */    34,  140,  140,  100,  100,  100,  102,  140,   46,   58,
 /*   100 */    37,   44,    5,  140,    9,  140,    7,  140,   10,  140,
 /*   110 */     6,  140,    2,  140,    4,  140,   86,   85,   98,   48,
 /*   120 */   140,   35,  140,   34,  140,  140,  100,  100,  100,  102,
 /*   130 */   101,   46,   59,   36,   43,    5,  140,    9,  140,    7,
 /*   140 */   140,   10,   79,    6,  140,    2,  140,    4,  140,   86,
 /*   150 */    85,   98,   48,  140,   35,  140,   34,  140,  140,  100,
 /*   160 */   100,  100,  102,  140,   46,   62,   62,   65,    5,   82,
 /*   170 */     9,  140,    7,  140,   10,  140,    6,  140,    2,  140,
 /*   180 */     4,  140,   86,   85,   98,   48,  140,   35,  140,   34,
 /*   190 */   140,  140,  100,  100,  100,  102,  140,   46,   68,   11,
 /*   200 */   140,    5,  140,    9,  140,    7,  140,   10,  140,    6,
 /*   210 */   140,    2,  140,    4,  140,   86,   85,   98,   48,  140,
 /*   220 */    35,  140,   34,  140,  140,  100,  100,  100,  102,  140,
 /*   230 */    45,   69,   46,  140,  140,  140,    5,  140,    9,  140,
 /*   240 */     7,  140,   10,  140,    6,  140,    2,  140,    4,  140,
 /*   250 */    86,   85,   98,   48,  140,   35,  140,   34,  140,  140,
 /*   260 */   106,  106,  106,  102,  140,   46,  140,  140,  140,    5,
 /*   270 */   140,    9,  140,    7,   80,   10,  140,    6,  140,    2,
 /*   280 */   140,    4,  140,   86,   85,   98,   48,  140,   35,  140,
 /*   290 */    34,  140,  140,  100,  100,  100,  102,  140,   46,  140,
 /*   300 */   140,  140,    5,  140,    9,   81,    7,  140,   10,  140,
 /*   310 */     6,  140,    2,  140,    4,  140,   86,   85,   98,   48,
 /*   320 */   140,   35,  140,   34,  140,  140,  100,  100,  100,  102,
 /*   330 */   140,   46,  140,  140,   16,    5,  140,    9,  140,    7,
 /*   340 */   140,   10,  140,    6,  140,    2,  140,    4,  140,   86,
 /*   350 */    85,   98,   48,  140,   35,  140,   34,  140,   60,  100,
 /*   360 */   100,  100,  102,   46,   64,   64,   63,    5,  140,    9,
 /*   370 */   140,    7,  140,   10,  140,    6,  140,    2,  140,    4,
 /*   380 */   140,   86,   85,   98,   48,  140,   35,  140,   34,  140,
 /*   390 */   108,  100,  100,  100,  102,   46,  140,  140,  140,    5,
 /*   400 */   140,    9,  140,    7,  140,   10,  140,    6,   83,    2,
 /*   410 */   140,    4,  140,   86,   85,   98,   48,  140,   35,  140,
 /*   420 */    34,  140,  140,  100,  100,  100,  102,   69,   46,  140,
 /*   430 */   140,  140,    5,  140,    9,  140,    7,  140,   10,  140,
 /*   440 */     6,  140,    2,  140,    4,  140,   86,   85,   98,   48,
 /*   450 */   140,   35,  140,   34,  140,  140,  100,  100,  100,  102,
 /*   460 */   140,  193,  116,   40,  140,  140,    1,   38,  118,   91,
 /*   470 */    90,   93,  140,  105,   99,  112,  140,  110,  113,   95,
 /*   480 */    89,   88,   15,   76,   70,   11,  140,   46,  140,  140,
 /*   490 */   140,    5,  140,    9,  140,    7,  140,   10,  140,    6,
 /*   500 */   140,    2,  140,    4,  140,   86,   85,   98,   48,  140,
 /*   510 */    35,  140,   34,  140,  140,  106,  106,  106,  102,  140,
 /*   520 */    46,  140,  140,  140,    5,  140,    9,  140,    7,  140,
 /*   530 */    10,  140,    6,  140,    2,  140,    4,  140,   86,   85,
 /*   540 */    98,   48,  140,   35,  140,   34,  140,  140,  100,  100,
 /*   550 */   100,  102,   39,  104,   91,   90,   93,  140,  103,   99,
 /*   560 */   112,  140,  110,  113,   94,   97,   96,   15,  107,   70,
 /*   570 */    11,  140,   28,  140,  118,   91,   90,   93,  140,  105,
 /*   580 */    99,  112,  140,  110,  113,  140,  140,  140,   15,   76,
 /*   590 */   140,   32,  140,  118,   91,   90,   93,  140,  105,   99,
 /*   600 */   112,  140,  110,  113,  140,  140,  140,   15,   76,   27,
 /*   610 */   140,  118,   91,   90,   93,  140,  105,   99,  112,  140,
 /*   620 */   110,  113,  140,  140,  140,   15,   76,   29,  140,  118,
 /*   630 */    91,   90,   93,  140,  105,   99,  112,  140,  110,  113,
 /*   640 */   140,  140,  140,   15,   76,   24,  140,  118,   91,   90,
 /*   650 */    93,  140,  105,   99,  112,  140,  110,  113,  140,  140,
 /*   660 */   140,   15,   76,   22,  140,  118,   91,   90,   93,  140,
 /*   670 */   105,   99,  112,  140,  110,  113,  140,  140,  140,   15,
 /*   680 */    76,   21,  140,  118,   91,   90,   93,  140,  105,   99,
 /*   690 */   112,  140,  110,  113,  140,  140,  140,   15,   76,   20,
 /*   700 */   140,  118,   91,   90,   93,  140,  105,   99,  112,  140,
 /*   710 */   110,  113,  140,  140,  140,   15,   76,   26,  140,  118,
 /*   720 */    91,   90,   93,  140,  105,   99,  112,  140,  110,  113,
 /*   730 */   140,  140,  140,   15,   76,   31,  140,  118,   91,   90,
 /*   740 */    93,  140,  105,   99,  112,  140,  110,  113,  140,  140,
 /*   750 */   140,   15,   76,   25,  140,  118,   91,   90,   93,  140,
 /*   760 */   105,   99,  112,  140,  110,  113,  140,  140,  140,   15,
 /*   770 */    76,   33,  140,  118,   91,   90,   93,  140,  105,   99,
 /*   780 */   112,  140,  110,  113,  140,  140,  140,   15,   76,   18,
 /*   790 */   140,  118,   91,   90,   93,  140,  105,   99,  112,  140,
 /*   800 */   110,  113,  140,  140,  140,   15,   76,   30,  140,  118,
 /*   810 */    91,   90,   93,  140,  105,   99,  112,  140,  110,  113,
 /*   820 */   140,  140,  140,   15,   76,   19,  140,  118,   91,   90,
 /*   830 */    93,  140,  105,   99,  112,  140,  110,  113,  140,  140,
 /*   840 */   140,   15,   76,   23,  140,  118,   91,   90,   93,    3,
 /*   850 */   105,   99,  112,  140,  110,  113,  140,   13,  140,   15,
 /*   860 */    76,  140,  104,   91,   90,   93,   13,  103,   99,  112,
 /*   870 */   140,  110,  113,  140,    3,   72,   15,  107,  140,   64,
 /*   880 */    64,   63,   13,   66,  140,    3,  140,   62,   62,   65,
 /*   890 */     3,  140,   71,  140,  140,   13,   62,   62,   65,  140,
 /*   900 */   140,  140,  115,  140,   64,   64,   63,  140,  140,  140,
 /*   910 */   109,   75,   62,   62,   65,   64,   64,   63,   77,  140,
 /*   920 */    64,   64,   63,   73,  140,   62,   62,   65,
    );
    static public $yy_lookahead = array(
 /*     0 */     4,    1,    3,    2,    8,   58,   10,   60,   12,    2,
 /*    10 */    14,   38,   16,    2,   18,    2,   20,   59,   22,   23,
 /*    20 */    24,   25,   58,   27,   60,   29,   26,   31,   32,   33,
 /*    30 */    34,   35,    4,   32,   33,   34,    8,   30,   10,   28,
 /*    40 */    12,   59,   14,   30,   16,    1,   18,   19,   20,   51,
 /*    50 */    22,   23,   24,   25,    2,   27,   59,   29,   43,   44,
 /*    60 */    32,   33,   34,   35,   55,    4,   32,   33,   34,    8,
 /*    70 */    44,   10,   65,   12,   65,   14,   59,   16,   59,   18,
 /*    80 */    28,   20,   21,   22,   23,   24,   25,   65,   27,   65,
 /*    90 */    29,   65,   65,   32,   33,   34,   35,   65,    4,   58,
 /*   100 */    59,   60,    8,   65,   10,   65,   12,   65,   14,   65,
 /*   110 */    16,   65,   18,   65,   20,   65,   22,   23,   24,   25,
 /*   120 */    65,   27,   65,   29,   65,   65,   32,   33,   34,   35,
 /*   130 */    36,    4,   58,   59,   60,    8,   65,   10,   65,   12,
 /*   140 */    65,   14,   15,   16,   65,   18,   65,   20,   65,   22,
 /*   150 */    23,   24,   25,   65,   27,   65,   29,   65,   65,   32,
 /*   160 */    33,   34,   35,   65,    4,   32,   33,   34,    8,    9,
 /*   170 */    10,   65,   12,   65,   14,   65,   16,   65,   18,   65,
 /*   180 */    20,   65,   22,   23,   24,   25,   65,   27,   65,   29,
 /*   190 */    65,   65,   32,   33,   34,   35,   65,    4,   63,   64,
 /*   200 */    65,    8,   65,   10,   65,   12,   65,   14,   65,   16,
 /*   210 */    65,   18,   65,   20,   65,   22,   23,   24,   25,   65,
 /*   220 */    27,   65,   29,   65,   65,   32,   33,   34,   35,   65,
 /*   230 */    37,    3,    4,   65,   65,   65,    8,   65,   10,   65,
 /*   240 */    12,   65,   14,   65,   16,   65,   18,   65,   20,   65,
 /*   250 */    22,   23,   24,   25,   65,   27,   65,   29,   65,   65,
 /*   260 */    32,   33,   34,   35,   65,    4,   65,   65,   65,    8,
 /*   270 */    65,   10,   65,   12,   13,   14,   65,   16,   65,   18,
 /*   280 */    65,   20,   65,   22,   23,   24,   25,   65,   27,   65,
 /*   290 */    29,   65,   65,   32,   33,   34,   35,   65,    4,   65,
 /*   300 */    65,   65,    8,   65,   10,   11,   12,   65,   14,   65,
 /*   310 */    16,   65,   18,   65,   20,   65,   22,   23,   24,   25,
 /*   320 */    65,   27,   65,   29,   65,   65,   32,   33,   34,   35,
 /*   330 */    65,    4,   65,   65,    2,    8,   65,   10,   65,   12,
 /*   340 */    65,   14,   65,   16,   65,   18,   65,   20,   65,   22,
 /*   350 */    23,   24,   25,   65,   27,   65,   29,   65,   31,   32,
 /*   360 */    33,   34,   35,    4,   32,   33,   34,    8,   65,   10,
 /*   370 */    65,   12,   65,   14,   65,   16,   65,   18,   65,   20,
 /*   380 */    65,   22,   23,   24,   25,   65,   27,   65,   29,   65,
 /*   390 */    31,   32,   33,   34,   35,    4,   65,   65,   65,    8,
 /*   400 */    65,   10,   65,   12,   65,   14,   65,   16,   17,   18,
 /*   410 */    65,   20,   65,   22,   23,   24,   25,   65,   27,   65,
 /*   420 */    29,   65,   65,   32,   33,   34,   35,    3,    4,   65,
 /*   430 */    65,   65,    8,   65,   10,   65,   12,   65,   14,   65,
 /*   440 */    16,   65,   18,   65,   20,   65,   22,   23,   24,   25,
 /*   450 */    65,   27,   65,   29,   65,   65,   32,   33,   34,   35,
 /*   460 */    65,   40,   41,   42,   65,   65,   45,   46,   47,   48,
 /*   470 */    49,   50,   65,   52,   53,   54,   65,   56,   57,    5,
 /*   480 */     6,    7,   61,   62,   63,   64,   65,    4,   65,   65,
 /*   490 */    65,    8,   65,   10,   65,   12,   65,   14,   65,   16,
 /*   500 */    65,   18,   65,   20,   65,   22,   23,   24,   25,   65,
 /*   510 */    27,   65,   29,   65,   65,   32,   33,   34,   35,   65,
 /*   520 */     4,   65,   65,   65,    8,   65,   10,   65,   12,   65,
 /*   530 */    14,   65,   16,   65,   18,   65,   20,   65,   22,   23,
 /*   540 */    24,   25,   65,   27,   65,   29,   65,   65,   32,   33,
 /*   550 */    34,   35,   46,   47,   48,   49,   50,   65,   52,   53,
 /*   560 */    54,   65,   56,   57,    5,    6,    7,   61,   62,   63,
 /*   570 */    64,   65,   45,   65,   47,   48,   49,   50,   65,   52,
 /*   580 */    53,   54,   65,   56,   57,   65,   65,   65,   61,   62,
 /*   590 */    65,   45,   65,   47,   48,   49,   50,   65,   52,   53,
 /*   600 */    54,   65,   56,   57,   65,   65,   65,   61,   62,   45,
 /*   610 */    65,   47,   48,   49,   50,   65,   52,   53,   54,   65,
 /*   620 */    56,   57,   65,   65,   65,   61,   62,   45,   65,   47,
 /*   630 */    48,   49,   50,   65,   52,   53,   54,   65,   56,   57,
 /*   640 */    65,   65,   65,   61,   62,   45,   65,   47,   48,   49,
 /*   650 */    50,   65,   52,   53,   54,   65,   56,   57,   65,   65,
 /*   660 */    65,   61,   62,   45,   65,   47,   48,   49,   50,   65,
 /*   670 */    52,   53,   54,   65,   56,   57,   65,   65,   65,   61,
 /*   680 */    62,   45,   65,   47,   48,   49,   50,   65,   52,   53,
 /*   690 */    54,   65,   56,   57,   65,   65,   65,   61,   62,   45,
 /*   700 */    65,   47,   48,   49,   50,   65,   52,   53,   54,   65,
 /*   710 */    56,   57,   65,   65,   65,   61,   62,   45,   65,   47,
 /*   720 */    48,   49,   50,   65,   52,   53,   54,   65,   56,   57,
 /*   730 */    65,   65,   65,   61,   62,   45,   65,   47,   48,   49,
 /*   740 */    50,   65,   52,   53,   54,   65,   56,   57,   65,   65,
 /*   750 */    65,   61,   62,   45,   65,   47,   48,   49,   50,   65,
 /*   760 */    52,   53,   54,   65,   56,   57,   65,   65,   65,   61,
 /*   770 */    62,   45,   65,   47,   48,   49,   50,   65,   52,   53,
 /*   780 */    54,   65,   56,   57,   65,   65,   65,   61,   62,   45,
 /*   790 */    65,   47,   48,   49,   50,   65,   52,   53,   54,   65,
 /*   800 */    56,   57,   65,   65,   65,   61,   62,   45,   65,   47,
 /*   810 */    48,   49,   50,   65,   52,   53,   54,   65,   56,   57,
 /*   820 */    65,   65,   65,   61,   62,   45,   65,   47,   48,   49,
 /*   830 */    50,   65,   52,   53,   54,   65,   56,   57,   65,   65,
 /*   840 */    65,   61,   62,   45,   65,   47,   48,   49,   50,    2,
 /*   850 */    52,   53,   54,   65,   56,   57,   65,    2,   65,   61,
 /*   860 */    62,   65,   47,   48,   49,   50,    2,   52,   53,   54,
 /*   870 */    65,   56,   57,   65,    2,   28,   61,   62,   65,   32,
 /*   880 */    33,   34,    2,   28,   65,    2,   65,   32,   33,   34,
 /*   890 */     2,   65,   28,   65,   65,    2,   32,   33,   34,   65,
 /*   900 */    65,   65,   30,   65,   32,   33,   34,   65,   65,   65,
 /*   910 */    30,   28,   32,   33,   34,   32,   33,   34,   30,   65,
 /*   920 */    32,   33,   34,   30,   65,   32,   33,   34,
);
    const YY_SHIFT_USE_DFLT = -28;
    const YY_SHIFT_MAX = 59;
    static public $yy_shift_ofst = array(
 /*     0 */   228,  424,  483,  483,  483,  483,  483,  483,  483,  483,
 /*    10 */   483,  483,  483,  483,  483,  483,  483,  483,  359,  127,
 /*    20 */    94,   61,  391,   -4,   28,  160,  193,  327,  294,  261,
 /*    30 */   516,  516,  516,  516,  332,  332,    1,    1,   -1,   -1,
 /*    40 */   -27,  883,  872,  847,  888,   34,  474,  -27,   44,  855,
 /*    50 */   880,  893,  864,  133,  559,   13,   11,    0,    7,   52,
);
    const YY_REDUCE_USE_DFLT = -54;
    const YY_REDUCE_MAX = 48;
    static public $yy_reduce_ofst = array(
 /*     0 */   421,  506,  600,  564,  636,  708,  618,  582,  546,  527,
 /*    10 */   780,  672,  726,  744,  762,  654,  798,  690,  815,  815,
 /*    20 */   815,  815,  815,  815,  815,  815,  815,  815,  815,  815,
 /*    30 */   815,  815,  815,  815,   41,   74,  -36,  -53,  135,  135,
 /*    40 */    15,  -42,   17,   19,   -3,  -18,   -2,   26,    9,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(3, 4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 1 */ array(3, 4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 2 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 3 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 4 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 5 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 6 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 7 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 8 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 9 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 10 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 11 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 12 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 13 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 14 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 15 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 16 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 17 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 18 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 31, 32, 33, 34, 35, ),
        /* 19 */ array(4, 8, 10, 12, 14, 15, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 20 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, 36, ),
        /* 21 */ array(4, 8, 10, 12, 14, 16, 18, 20, 21, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 22 */ array(4, 8, 10, 12, 14, 16, 17, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 23 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 31, 32, 33, 34, 35, ),
        /* 24 */ array(4, 8, 10, 12, 14, 16, 18, 19, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 25 */ array(4, 8, 9, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 26 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, 37, ),
        /* 27 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 31, 32, 33, 34, 35, ),
        /* 28 */ array(4, 8, 10, 11, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 29 */ array(4, 8, 10, 12, 13, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 30 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 31 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 32 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 33 */ array(4, 8, 10, 12, 14, 16, 18, 20, 22, 23, 24, 25, 27, 29, 32, 33, 34, 35, ),
        /* 34 */ array(2, 32, 33, 34, ),
        /* 35 */ array(2, 32, 33, 34, ),
        /* 36 */ array(2, 32, 33, 34, ),
        /* 37 */ array(2, 32, 33, 34, ),
        /* 38 */ array(3, ),
        /* 39 */ array(3, ),
        /* 40 */ array(38, ),
        /* 41 */ array(2, 28, 32, 33, 34, ),
        /* 42 */ array(2, 30, 32, 33, 34, ),
        /* 43 */ array(2, 28, 32, 33, 34, ),
        /* 44 */ array(2, 30, 32, 33, 34, ),
        /* 45 */ array(32, 33, 34, ),
        /* 46 */ array(5, 6, 7, ),
        /* 47 */ array(38, ),
        /* 48 */ array(1, ),
        /* 49 */ array(2, 28, 32, 33, 34, ),
        /* 50 */ array(2, 30, 32, 33, 34, ),
        /* 51 */ array(2, 30, 32, 33, 34, ),
        /* 52 */ array(2, 28, 32, 33, 34, ),
        /* 53 */ array(32, 33, 34, ),
        /* 54 */ array(5, 6, 7, ),
        /* 55 */ array(2, 30, ),
        /* 56 */ array(2, 28, ),
        /* 57 */ array(1, 26, ),
        /* 58 */ array(2, 30, ),
        /* 59 */ array(2, 28, ),
        /* 60 */ array(),
        /* 61 */ array(),
        /* 62 */ array(),
        /* 63 */ array(),
        /* 64 */ array(),
        /* 65 */ array(),
        /* 66 */ array(),
        /* 67 */ array(),
        /* 68 */ array(),
        /* 69 */ array(),
        /* 70 */ array(),
        /* 71 */ array(),
        /* 72 */ array(),
        /* 73 */ array(),
        /* 74 */ array(),
        /* 75 */ array(),
        /* 76 */ array(),
        /* 77 */ array(),
        /* 78 */ array(),
        /* 79 */ array(),
        /* 80 */ array(),
        /* 81 */ array(),
        /* 82 */ array(),
        /* 83 */ array(),
        /* 84 */ array(),
        /* 85 */ array(),
        /* 86 */ array(),
        /* 87 */ array(),
        /* 88 */ array(),
        /* 89 */ array(),
        /* 90 */ array(),
        /* 91 */ array(),
        /* 92 */ array(),
        /* 93 */ array(),
        /* 94 */ array(),
        /* 95 */ array(),
        /* 96 */ array(),
        /* 97 */ array(),
        /* 98 */ array(),
        /* 99 */ array(),
        /* 100 */ array(),
        /* 101 */ array(),
        /* 102 */ array(),
        /* 103 */ array(),
        /* 104 */ array(),
        /* 105 */ array(),
        /* 106 */ array(),
        /* 107 */ array(),
        /* 108 */ array(),
        /* 109 */ array(),
        /* 110 */ array(),
        /* 111 */ array(),
        /* 112 */ array(),
        /* 113 */ array(),
        /* 114 */ array(),
        /* 115 */ array(),
        /* 116 */ array(),
        /* 117 */ array(),
        /* 118 */ array(),
);
    static public $yy_default = array(
 /*     0 */   127,  124,  192,  192,  192,  192,  192,  192,  192,  192,
 /*    10 */   192,  192,  191,  192,  192,  192,  192,  192,  192,  192,
 /*    20 */   192,  192,  192,  167,  192,  192,  192,  192,  192,  192,
 /*    30 */   166,  150,  151,  190,  192,  192,  192,  192,  125,  126,
 /*    40 */   121,  192,  192,  192,  192,  180,  192,  120,  192,  192,
 /*    50 */   192,  192,  192,  181,  192,  192,  192,  192,  192,  192,
 /*    60 */   168,  170,  171,  174,  173,  172,  159,  123,  177,  179,
 /*    70 */   178,  158,  156,  164,  155,  157,  189,  162,  161,  140,
 /*    80 */   139,  138,  137,  141,  142,  145,  144,  143,  136,  135,
 /*    90 */   129,  128,  122,  130,  131,  132,  134,  133,  146,  147,
 /*   100 */   182,  176,  175,  183,  184,  187,  186,  185,  169,  165,
 /*   110 */   152,  149,  148,  153,  154,  163,  119,  160,  188,
);
/* The next thing included is series of defines which control
** various aspects of the generated parser.
**    self::YYNOCODE      is a number which corresponds
**                        to no legal terminal or nonterminal number.  This
**                        number is used to fill in empty slots of the hash 
**                        table.
**    self::YYFALLBACK    If defined, this indicates that one or more tokens
**                        have fall-back values which should be used if the
**                        original value of the token will not parse.
**    self::YYSTACKDEPTH  is the maximum depth of the parser's stack.
**    self::YYNSTATE      the combined number of states.
**    self::YYNRULE       the number of rules in the grammar
**    self::YYERRORSYMBOL is the code number of the error symbol.  If not
**                        defined, then do no error processing.
*/
    const YYNOCODE = 66;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 119;
    const YYNRULE = 73;
    const YYERRORSYMBOL = 39;
    const YYERRSYMDT = 'yy0';
    const YYFALLBACK = 0;
    /** The next table maps tokens into fallback tokens.  If a construct
     * like the following:
     * 
     *      %fallback ID X Y Z.
     *
     * appears in the grammer, then ID becomes a fallback token for X, Y,
     * and Z.  Whenever one of the tokens X, Y, or Z is input to the parser
     * but it does not parse, the type of the token is changed to ID and
     * the parse is retried before an error is thrown.
     */
    static public $yyFallback = array(
    );
    /**
     * Turn parser tracing on by giving a stream to which to write the trace
     * and a prompt to preface each trace message.  Tracing is turned off
     * by making either argument NULL 
     *
     * Inputs:
     * 
     * - A stream resource to which trace output should be written.
     *   If NULL, then tracing is turned off.
     * - A prefix string written at the beginning of every
     *   line of trace output.  If NULL, then tracing is
     *   turned off.
     *
     * Outputs:
     * 
     * - None.
     * @param resource
     * @param string
     */
    static function Trace($TraceFILE, $zTracePrompt)
    {
        if (!$TraceFILE) {
            $zTracePrompt = 0;
        } elseif (!$zTracePrompt) {
            $TraceFILE = 0;
        }
        self::$yyTraceFILE = $TraceFILE;
        self::$yyTracePrompt = $zTracePrompt;
    }

    /**
     * Output debug information to output (php://output stream)
     */
    static function PrintTrace()
    {
        self::$yyTraceFILE = fopen('php://output', 'w');
        self::$yyTracePrompt = '';
    }

    /**
     * @var resource|0
     */
    static public $yyTraceFILE;
    /**
     * String to prepend to debug output
     * @var string|0
     */
    static public $yyTracePrompt;
    /**
     * @var int
     */
    public $yyidx;                    /* Index of top element in stack */
    /**
     * @var int
     */
    public $yyerrcnt;                 /* Shifts left before out of the error */
    /**
     * @var array
     */
    public $yystack = array();  /* The parser's stack */

    /**
     * For tracing shifts, the names of all terminals and nonterminals
     * are required.  The following table supplies these names
     * @var array
     */
    static public $yyTokenName = array( 
  '$',             'DOCBLOCK_SIMPLELISTBULLET',  'DOCBLOCK_LIOPEN',  'DOCBLOCK_POPEN',
  'DOCBLOCK_INLINETAG',  'DOCBLOCK_ENDINLINETAG',  'DOCBLOCK_INLINETAGCONTENTS',  'DOCBLOCK_ESCAPEDINLINEEND',
  'DOCBLOCK_BOPEN',  'DOCBLOCK_BCLOSE',  'DOCBLOCK_IOPEN',  'DOCBLOCK_ICLOSE',
  'DOCBLOCK_CODEOPEN',  'DOCBLOCK_CODECLOSE',  'DOCBLOCK_PREOPEN',  'DOCBLOCK_PRECLOSE',
  'DOCBLOCK_SAMPOPEN',  'DOCBLOCK_SAMPCLOSE',  'DOCBLOCK_VAROPEN',  'DOCBLOCK_VARCLOSE',
  'DOCBLOCK_KBDOPEN',  'DOCBLOCK_KBDCLOSE',  'DOCBLOCK_BR',   'DOCBLOCK_ESCAPEDHTML',
  'DOCBLOCK_ESCAPEDINLINETAG',  'DOCBLOCK_SIMPLELISTSTART',  'DOCBLOCK_SIMPLELISTEND',  'DOCBLOCK_OLOPEN',
  'DOCBLOCK_OLCLOSE',  'DOCBLOCK_ULOPEN',  'DOCBLOCK_ULCLOSE',  'DOCBLOCK_LICLOSE',
  'DOCBLOCK_NEWLINE',  'DOCBLOCK_WHITESPACE',  'DOCBLOCK_TEXT',  'DOCBLOCK_INTERNAL',
  'DOCBLOCK_ENDINTERNAL',  'DOCBLOCK_PCLOSE',  'DOCBLOCK_TAG',  'error',       
  'start',         'docblock',      'description',   'tags',        
  'tag',           'text',          'paragraphs',    'special',     
  'html_tag',      'inline_tag',    'list',          'inline_tag_contents',
  'escape',        'simple_list',   'html_list',     'simplelist_contents',
  'ordered_list',  'unordered_list',  'htmllist_contents',  'ignored_whitespace',
  'htmllist_contents_close',  'stopinternal',  'internal',      'paragraph',   
  'nonestingp',  
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "start ::= docblock",
 /*   1 */ "docblock ::= description tags",
 /*   2 */ "docblock ::= description",
 /*   3 */ "tags ::= tags tag",
 /*   4 */ "tags ::= tag",
 /*   5 */ "description ::= text",
 /*   6 */ "description ::= paragraphs",
 /*   7 */ "description ::= text paragraphs",
 /*   8 */ "description ::=",
 /*   9 */ "special ::= html_tag",
 /*  10 */ "special ::= inline_tag",
 /*  11 */ "special ::= list",
 /*  12 */ "inline_tag ::= DOCBLOCK_INLINETAG inline_tag_contents DOCBLOCK_ENDINLINETAG",
 /*  13 */ "inline_tag ::= DOCBLOCK_INLINETAG DOCBLOCK_ENDINLINETAG",
 /*  14 */ "inline_tag_contents ::= inline_tag_contents DOCBLOCK_INLINETAGCONTENTS",
 /*  15 */ "inline_tag_contents ::= inline_tag_contents DOCBLOCK_ESCAPEDINLINEEND",
 /*  16 */ "inline_tag_contents ::= DOCBLOCK_INLINETAGCONTENTS",
 /*  17 */ "inline_tag_contents ::= DOCBLOCK_ESCAPEDINLINEEND",
 /*  18 */ "html_tag ::= DOCBLOCK_BOPEN text DOCBLOCK_BCLOSE",
 /*  19 */ "html_tag ::= DOCBLOCK_IOPEN text DOCBLOCK_ICLOSE",
 /*  20 */ "html_tag ::= DOCBLOCK_CODEOPEN text DOCBLOCK_CODECLOSE",
 /*  21 */ "html_tag ::= DOCBLOCK_PREOPEN text DOCBLOCK_PRECLOSE",
 /*  22 */ "html_tag ::= DOCBLOCK_SAMPOPEN text DOCBLOCK_SAMPCLOSE",
 /*  23 */ "html_tag ::= DOCBLOCK_VAROPEN text DOCBLOCK_VARCLOSE",
 /*  24 */ "html_tag ::= DOCBLOCK_KBDOPEN text DOCBLOCK_KBDCLOSE",
 /*  25 */ "html_tag ::= DOCBLOCK_BR",
 /*  26 */ "escape ::= DOCBLOCK_ESCAPEDHTML",
 /*  27 */ "escape ::= DOCBLOCK_ESCAPEDINLINETAG",
 /*  28 */ "list ::= simple_list",
 /*  29 */ "list ::= html_list",
 /*  30 */ "simple_list ::= DOCBLOCK_SIMPLELISTSTART simplelist_contents DOCBLOCK_SIMPLELISTEND",
 /*  31 */ "simplelist_contents ::= simplelist_contents DOCBLOCK_SIMPLELISTBULLET text",
 /*  32 */ "simplelist_contents ::= DOCBLOCK_SIMPLELISTBULLET text",
 /*  33 */ "html_list ::= ordered_list",
 /*  34 */ "html_list ::= unordered_list",
 /*  35 */ "ordered_list ::= DOCBLOCK_OLOPEN htmllist_contents DOCBLOCK_OLCLOSE",
 /*  36 */ "ordered_list ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents DOCBLOCK_OLCLOSE",
 /*  37 */ "ordered_list ::= DOCBLOCK_OLOPEN htmllist_contents_close DOCBLOCK_OLCLOSE",
 /*  38 */ "ordered_list ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents_close DOCBLOCK_OLCLOSE",
 /*  39 */ "ordered_list ::= DOCBLOCK_OLOPEN htmllist_contents_close ignored_whitespace DOCBLOCK_OLCLOSE",
 /*  40 */ "ordered_list ::= DOCBLOCK_OLOPEN ignored_whitespace htmllist_contents_close ignored_whitespace DOCBLOCK_OLCLOSE",
 /*  41 */ "unordered_list ::= DOCBLOCK_ULOPEN htmllist_contents DOCBLOCK_ULCLOSE",
 /*  42 */ "unordered_list ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents DOCBLOCK_ULCLOSE",
 /*  43 */ "unordered_list ::= DOCBLOCK_ULOPEN htmllist_contents_close DOCBLOCK_ULCLOSE",
 /*  44 */ "unordered_list ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents_close DOCBLOCK_ULCLOSE",
 /*  45 */ "unordered_list ::= DOCBLOCK_ULOPEN htmllist_contents_close ignored_whitespace DOCBLOCK_ULCLOSE",
 /*  46 */ "unordered_list ::= DOCBLOCK_ULOPEN ignored_whitespace htmllist_contents_close ignored_whitespace DOCBLOCK_ULCLOSE",
 /*  47 */ "htmllist_contents ::= htmllist_contents DOCBLOCK_LIOPEN text",
 /*  48 */ "htmllist_contents ::= DOCBLOCK_LIOPEN text",
 /*  49 */ "htmllist_contents_close ::= htmllist_contents_close DOCBLOCK_LIOPEN text DOCBLOCK_LICLOSE",
 /*  50 */ "htmllist_contents_close ::= htmllist_contents_close ignored_whitespace DOCBLOCK_LIOPEN text DOCBLOCK_LICLOSE",
 /*  51 */ "htmllist_contents_close ::= DOCBLOCK_LIOPEN text DOCBLOCK_LICLOSE",
 /*  52 */ "ignored_whitespace ::= ignored_whitespace DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE",
 /*  53 */ "ignored_whitespace ::= ignored_whitespace DOCBLOCK_TEXT",
 /*  54 */ "ignored_whitespace ::= DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE",
 /*  55 */ "ignored_whitespace ::= DOCBLOCK_TEXT",
 /*  56 */ "stopinternal ::= DOCBLOCK_INTERNAL",
 /*  57 */ "internal ::= stopinternal text DOCBLOCK_ENDINTERNAL",
 /*  58 */ "paragraphs ::= paragraphs paragraph",
 /*  59 */ "paragraphs ::= paragraph",
 /*  60 */ "nonestingp ::= DOCBLOCK_POPEN",
 /*  61 */ "paragraph ::= nonestingp text DOCBLOCK_PCLOSE",
 /*  62 */ "paragraph ::= nonestingp text DOCBLOCK_PCLOSE ignored_whitespace",
 /*  63 */ "text ::= text DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE|DOCBLOCK_TEXT",
 /*  64 */ "text ::= text escape",
 /*  65 */ "text ::= text special",
 /*  66 */ "text ::= text internal",
 /*  67 */ "text ::= DOCBLOCK_NEWLINE|DOCBLOCK_WHITESPACE|DOCBLOCK_TEXT",
 /*  68 */ "text ::= escape",
 /*  69 */ "text ::= special",
 /*  70 */ "text ::= internal",
 /*  71 */ "tag ::= DOCBLOCK_TAG text",
 /*  72 */ "tag ::= DOCBLOCK_TAG",
    );

    /**
     * This function returns the symbolic name associated with a token
     * value.
     * @param int
     * @return string
     */
    function tokenName($tokenType)
    {
        if ($tokenType === 0) {
            return 'End of Input';
        }
        if ($tokenType > 0 && $tokenType < count(self::$yyTokenName)) {
            return self::$yyTokenName[$tokenType];
        } else {
            return "Unknown";
        }
    }

    /**
     * The following function deletes the value associated with a
     * symbol.  The symbol can be either a terminal or nonterminal.
     * @param int the symbol code
     * @param mixed the symbol's value
     */
    static function yy_destructor($yymajor, $yypminor)
    {
        switch ($yymajor) {
        /* Here is inserted the actions which take place when a
        ** terminal or non-terminal is destroyed.  This can happen
        ** when the symbol is popped from the stack during a
        ** reduce or during error processing or when a parser is 
        ** being destroyed before it is finished parsing.
        **
        ** Note: during a reduce, the only symbols destroyed are those
        ** which appear on the RHS of the rule, but which are not used
        ** inside the C code.
        */
            default:  break;   /* If no destructor action specified: do nothing */
        }
    }

    /**
     * Pop the parser's stack once.
     *
     * If there is a destructor routine associated with the token which
     * is popped from the stack, then call it.
     *
     * Return the major token number for the symbol popped.
     * @param PHP_Parser_DocblockParseryyParser
     * @return int
     */
    function yy_pop_parser_stack()
    {
        if (!count($this->yystack)) {
            return;
        }
        $yytos = array_pop($this->yystack);
        if (self::$yyTraceFILE && $this->yyidx >= 0) {
            fwrite(self::$yyTraceFILE,
                self::$yyTracePrompt . 'Popping ' . self::$yyTokenName[$yytos->major] .
                    "\n");
        }
        $yymajor = $yytos->major;
        self::yy_destructor($yymajor, $yytos->minor);
        $this->yyidx--;
        return $yymajor;
    }

    /**
     * Deallocate and destroy a parser.  Destructors are all called for
     * all stack elements before shutting the parser down.
     */
    function __destruct()
    {
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        if (is_resource(self::$yyTraceFILE)) {
            fclose(self::$yyTraceFILE);
        }
    }

    /**
     * Based on the current state and parser stack, get a list of all
     * possible lookahead tokens
     * @param int
     * @return array
     */
    function yy_get_expected_tokens($token)
    {
        $state = $this->yystack[$this->yyidx]->stateno;
        $expected = self::$yyExpectedTokens[$state];
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return $expected;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return array_unique($expected);
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate])) {
                        $expected += self::$yyExpectedTokens[$nextstate];
                            if (in_array($token,
                                  self::$yyExpectedTokens[$nextstate], true)) {
                            $this->yyidx = $yyidx;
                            $this->yystack = $stack;
                            return array_unique($expected);
                        }
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new PHP_Parser_DocblockParseryyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return array_unique($expected);
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return $expected;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return array_unique($expected);
    }

    /**
     * Based on the parser state and current parser stack, determine whether
     * the lookahead token is possible.
     * 
     * The parser will convert the token value to an error token if not.  This
     * catches some unusual edge cases where the parser would fail.
     * @param int
     * @return bool
     */
    function yy_is_expected_token($token)
    {
        if ($token === 0) {
            return true; // 0 is not part of this
        }
        $state = $this->yystack[$this->yyidx]->stateno;
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return true;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return true;
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate]) &&
                          in_array($token, self::$yyExpectedTokens[$nextstate], true)) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        return true;
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new PHP_Parser_DocblockParseryyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        if (!$token) {
                            // end of input: this is valid
                            return true;
                        }
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return false;
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return true;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        $this->yyidx = $yyidx;
        $this->yystack = $stack;
        return true;
    }

    /**
     * Find the appropriate action for a parser given the terminal
     * look-ahead token iLookAhead.
     *
     * If the look-ahead token is YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return YY_NO_ACTION.
     * @param int The look-ahead token
     */
    function yy_find_shift_action($iLookAhead)
    {
        $stateno = $this->yystack[$this->yyidx]->stateno;
     
        /* if ($this->yyidx < 0) return self::YY_NO_ACTION;  */
        if (!isset(self::$yy_shift_ofst[$stateno])) {
            // no shift actions
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_shift_ofst[$stateno];
        if ($i === self::YY_SHIFT_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            if (count(self::$yyFallback) && $iLookAhead < count(self::$yyFallback)
                   && ($iFallback = self::$yyFallback[$iLookAhead]) != 0) {
                if (self::$yyTraceFILE) {
                    fwrite(self::$yyTraceFILE, self::$yyTracePrompt . "FALLBACK " .
                        self::$yyTokenName[$iLookAhead] . " => " .
                        self::$yyTokenName[$iFallback] . "\n");
                }
                return $this->yy_find_shift_action($iFallback);
            }
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Find the appropriate action for a parser given the non-terminal
     * look-ahead token $iLookAhead.
     *
     * If the look-ahead token is self::YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return self::YY_NO_ACTION.
     * @param int Current state number
     * @param int The look-ahead token
     */
    function yy_find_reduce_action($stateno, $iLookAhead)
    {
        /* $stateno = $this->yystack[$this->yyidx]->stateno; */

        if (!isset(self::$yy_reduce_ofst[$stateno])) {
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_reduce_ofst[$stateno];
        if ($i == self::YY_REDUCE_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Perform a shift action.
     * @param int The new state to shift in
     * @param int The major token to shift in
     * @param mixed the minor token to shift in
     */
    function yy_shift($yyNewState, $yyMajor, $yypMinor)
    {
        $this->yyidx++;
        if ($this->yyidx >= self::YYSTACKDEPTH) {
            $this->yyidx--;
            if (self::$yyTraceFILE) {
                fprintf(self::$yyTraceFILE, "%sStack Overflow!\n", self::$yyTracePrompt);
            }
            while ($this->yyidx >= 0) {
                $this->yy_pop_parser_stack();
            }
            /* Here code is inserted which will execute if the parser
            ** stack ever overflows */
            return;
        }
        $yytos = new PHP_Parser_DocblockParseryyStackEntry;
        $yytos->stateno = $yyNewState;
        $yytos->major = $yyMajor;
        $yytos->minor = $yypMinor;
        array_push($this->yystack, $yytos);
        if (self::$yyTraceFILE && $this->yyidx > 0) {
            fprintf(self::$yyTraceFILE, "%sShift %d\n", self::$yyTracePrompt,
                $yyNewState);
            fprintf(self::$yyTraceFILE, "%sStack:", self::$yyTracePrompt);
            for($i = 1; $i <= $this->yyidx; $i++) {
                fprintf(self::$yyTraceFILE, " %s",
                    self::$yyTokenName[$this->yystack[$i]->major]);
            }
            fwrite(self::$yyTraceFILE,"\n");
        }
    }

    /**
     * The following table contains information about every rule that
     * is used during the reduce.
     *
     * <pre>
     * array(
     *  array(
     *   int $lhs;         Symbol on the left-hand side of the rule
     *   int $nrhs;     Number of right-hand side symbols in the rule
     *  ),...
     * );
     * </pre>
     */
    static public $yyRuleInfo = array(
  array( 'lhs' => 40, 'rhs' => 1 ),
  array( 'lhs' => 41, 'rhs' => 2 ),
  array( 'lhs' => 41, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 2 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 1 ),
  array( 'lhs' => 42, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 0 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 47, 'rhs' => 1 ),
  array( 'lhs' => 49, 'rhs' => 3 ),
  array( 'lhs' => 49, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 2 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 51, 'rhs' => 1 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 3 ),
  array( 'lhs' => 48, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 52, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 50, 'rhs' => 1 ),
  array( 'lhs' => 53, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 3 ),
  array( 'lhs' => 55, 'rhs' => 2 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 54, 'rhs' => 1 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 3 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 4 ),
  array( 'lhs' => 56, 'rhs' => 5 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 3 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 4 ),
  array( 'lhs' => 57, 'rhs' => 5 ),
  array( 'lhs' => 58, 'rhs' => 3 ),
  array( 'lhs' => 58, 'rhs' => 2 ),
  array( 'lhs' => 60, 'rhs' => 4 ),
  array( 'lhs' => 60, 'rhs' => 5 ),
  array( 'lhs' => 60, 'rhs' => 3 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 2 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 59, 'rhs' => 1 ),
  array( 'lhs' => 61, 'rhs' => 1 ),
  array( 'lhs' => 62, 'rhs' => 3 ),
  array( 'lhs' => 46, 'rhs' => 2 ),
  array( 'lhs' => 46, 'rhs' => 1 ),
  array( 'lhs' => 64, 'rhs' => 1 ),
  array( 'lhs' => 63, 'rhs' => 3 ),
  array( 'lhs' => 63, 'rhs' => 4 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 2 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 45, 'rhs' => 1 ),
  array( 'lhs' => 44, 'rhs' => 2 ),
  array( 'lhs' => 44, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 5,
        9 => 5,
        10 => 5,
        11 => 5,
        16 => 5,
        28 => 5,
        29 => 5,
        33 => 5,
        34 => 5,
        67 => 5,
        68 => 5,
        69 => 5,
        70 => 5,
        7 => 7,
        8 => 8,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        30 => 30,
        41 => 30,
        42 => 30,
        43 => 30,
        44 => 30,
        31 => 31,
        32 => 32,
        35 => 35,
        36 => 35,
        37 => 35,
        38 => 35,
        39 => 39,
        40 => 39,
        45 => 45,
        46 => 45,
        47 => 47,
        48 => 48,
        49 => 49,
        50 => 50,
        51 => 51,
        53 => 53,
        55 => 53,
        56 => 56,
        57 => 57,
        58 => 58,
        59 => 59,
        60 => 60,
        61 => 61,
        62 => 62,
        63 => 63,
        64 => 63,
        65 => 65,
        66 => 66,
        71 => 71,
        72 => 72,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 82 "DocblockParser.y"
    function yy_r0(){$this->data = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1334 "DocblockParser.php"
#line 84 "DocblockParser.y"
    function yy_r1(){$this->_retvalue = array('desc' => $this->yystack[$this->yyidx + -1]->minor, 'tags' => $this->yystack[$this->yyidx + 0]->minor);    }
#line 1337 "DocblockParser.php"
#line 85 "DocblockParser.y"
    function yy_r2(){$this->_retvalue = array('desc' => $this->yystack[$this->yyidx + 0]->minor, 'tags' => array());    }
#line 1340 "DocblockParser.php"
#line 87 "DocblockParser.y"
    function yy_r3(){
    $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
    $this->_retvalue[$this->yystack[$this->yyidx + 0]->minor['tag']][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1346 "DocblockParser.php"
#line 91 "DocblockParser.y"
    function yy_r4(){$this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor['tag'] => array($this->yystack[$this->yyidx + 0]->minor));    }
#line 1349 "DocblockParser.php"
#line 94 "DocblockParser.y"
    function yy_r5(){$this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;    }
#line 1352 "DocblockParser.php"
#line 97 "DocblockParser.y"
    function yy_r7(){
    if (!is_string($this->yystack[$this->yyidx + -1]->minor) || trim($this->yystack[$this->yyidx + -1]->minor)) {
        throw new Exception('Invalid docblock: cannot mix text-based paragraphs' .
            ' with P-based paragraphs');
    }
    $this->_retvalue = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1361 "DocblockParser.php"
#line 104 "DocblockParser.y"
    function yy_r8(){$this->_retvalue = array('');    }
#line 1364 "DocblockParser.php"
#line 110 "DocblockParser.y"
    function yy_r12(){
    $this->_retvalue = array('type' => 'inline', 'tag' => substr($this->yystack[$this->yyidx + -2]->minor, 2), 'contents' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1369 "DocblockParser.php"
#line 113 "DocblockParser.y"
    function yy_r13(){
    $this->_retvalue = array('type' => 'inline', 'tag' => substr($this->yystack[$this->yyidx + -1]->minor, 2), 'contents' => '');
    }
#line 1374 "DocblockParser.php"
#line 117 "DocblockParser.y"
    function yy_r14(){
    $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1379 "DocblockParser.php"
#line 120 "DocblockParser.y"
    function yy_r15(){
    $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . '}';
    }
#line 1384 "DocblockParser.php"
#line 124 "DocblockParser.y"
    function yy_r17(){$this->_retvalue = '}';    }
#line 1387 "DocblockParser.php"
#line 126 "DocblockParser.y"
    function yy_r18(){
    $this->_retvalue = array('type' => 'b', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1392 "DocblockParser.php"
#line 129 "DocblockParser.y"
    function yy_r19(){
    $this->_retvalue = array('type' => 'i', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1397 "DocblockParser.php"
#line 132 "DocblockParser.y"
    function yy_r20(){
    $this->_retvalue = array('type' => 'code', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1402 "DocblockParser.php"
#line 135 "DocblockParser.y"
    function yy_r21(){
    $this->_retvalue = array('type' => 'pre', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1407 "DocblockParser.php"
#line 138 "DocblockParser.y"
    function yy_r22(){
    $this->_retvalue = array('type' => 'samp', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1412 "DocblockParser.php"
#line 141 "DocblockParser.y"
    function yy_r23(){
    $this->_retvalue = array('type' => 'var', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1417 "DocblockParser.php"
#line 144 "DocblockParser.y"
    function yy_r24(){
    $this->_retvalue = array('type' => 'kbd', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1422 "DocblockParser.php"
#line 147 "DocblockParser.y"
    function yy_r25(){$this->_retvalue = array('type' => 'br');    }
#line 1425 "DocblockParser.php"
#line 149 "DocblockParser.y"
    function yy_r26(){$this->_retvalue = substr($this->yystack[$this->yyidx + 0]->minor, 1, strlen($this->yystack[$this->yyidx + 0]->minor) - 2);    }
#line 1428 "DocblockParser.php"
#line 150 "DocblockParser.y"
    function yy_r27(){
    if ($this->yystack[$this->yyidx + 0]->minor == '{@*}') {
        $this->_retvalue = '*/';
    } else {
        $this->_retvalue = '{@';
    }
    }
#line 1437 "DocblockParser.php"
#line 161 "DocblockParser.y"
    function yy_r30(){$this->_retvalue = array('list' => $this->yystack[$this->yyidx + -1]->minor);    }
#line 1440 "DocblockParser.php"
#line 163 "DocblockParser.y"
    function yy_r31(){
    $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    $this->_retvalue[] = array('index' => $this->yystack[$this->yyidx + -1]->minor, 'text' =>$this->yystack[$this->yyidx + 0]->minor);
    }
#line 1446 "DocblockParser.php"
#line 167 "DocblockParser.y"
    function yy_r32(){
    $this->_retvalue = array(array('index' => $this->yystack[$this->yyidx + -1]->minor, 'text' => $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1451 "DocblockParser.php"
#line 174 "DocblockParser.y"
    function yy_r35(){
    $this->_retvalue = array();
    foreach ($this->yystack[$this->yyidx + -1]->minor as $i => $index) {
        $this->_retvalue[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    $this->_retvalue = array('list' => $this->_retvalue);
    }
#line 1460 "DocblockParser.php"
#line 202 "DocblockParser.y"
    function yy_r39(){
    $this->_retvalue = array();
    foreach ($this->yystack[$this->yyidx + -2]->minor as $i => $index) {
        $this->_retvalue[] = array('index' => $i + 1, 'text' => $index['text']);
    }
    $this->_retvalue = array('list' => $this->_retvalue);
    }
#line 1469 "DocblockParser.php"
#line 221 "DocblockParser.y"
    function yy_r45(){$this->_retvalue = array('list' => $this->yystack[$this->yyidx + -2]->minor);    }
#line 1472 "DocblockParser.php"
#line 224 "DocblockParser.y"
    function yy_r47(){
    $this->_retvalue = $this->yystack[$this->yyidx + -2]->minor;
    $this->_retvalue[] = array('index' => '*', 'text' => $this->yystack[$this->yyidx + 0]->minor);
    }
#line 1478 "DocblockParser.php"
#line 228 "DocblockParser.y"
    function yy_r48(){
    $this->_retvalue = array(array('index' => '*', 'text' => $this->yystack[$this->yyidx + 0]->minor));
    }
#line 1483 "DocblockParser.php"
#line 232 "DocblockParser.y"
    function yy_r49(){
    $this->_retvalue = $this->yystack[$this->yyidx + -3]->minor;
    $this->_retvalue[] = array('index' => '*', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1489 "DocblockParser.php"
#line 236 "DocblockParser.y"
    function yy_r50(){
    $this->_retvalue = $this->yystack[$this->yyidx + -4]->minor;
    $this->_retvalue[] = array('index' => '*', 'text' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1495 "DocblockParser.php"
#line 240 "DocblockParser.y"
    function yy_r51(){
    $this->_retvalue = array(array('index' => '*', 'text' => $this->yystack[$this->yyidx + -1]->minor));
    }
#line 1500 "DocblockParser.php"
#line 245 "DocblockParser.y"
    function yy_r53(){
    if (trim($this->yystack[$this->yyidx + 0]->minor)) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Unexpected DOCBLOCK_TEXT, expected one of DOCBLOCK_NEWLINE, DOCBLOCK_WHITESPACE');
    }
    }
#line 1507 "DocblockParser.php"
#line 257 "DocblockParser.y"
    function yy_r56(){
    if ($this->_inInternal) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Cannot nest {@internal}}');
    }
    $this->_inInternal = true;
    }
#line 1515 "DocblockParser.php"
#line 263 "DocblockParser.y"
    function yy_r57(){
    $this->_inInternal = false;
    $this->_retvalue = '';
    if ($this->_processInternal) {
        $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor; 
    }
    }
#line 1524 "DocblockParser.php"
#line 271 "DocblockParser.y"
    function yy_r58(){
    $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
    $this->_retvalue[] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1530 "DocblockParser.php"
#line 275 "DocblockParser.y"
    function yy_r59(){
    $this->_retvalue = array($this->yystack[$this->yyidx + 0]->minor);
    }
#line 1535 "DocblockParser.php"
#line 285 "DocblockParser.y"
    function yy_r60(){
    if ($this->_inP) {
        throw new Exception("Syntax Error on line " . $this->lex->line . ': Cannot nest <p>');
    }
    $this->_inP = true;
    }
#line 1543 "DocblockParser.php"
#line 291 "DocblockParser.y"
    function yy_r61(){
    $this->_inP = false;
    $this->_retvalue = array('paragraph' => $this->yystack[$this->yyidx + -1]->minor);
    }
#line 1549 "DocblockParser.php"
#line 295 "DocblockParser.y"
    function yy_r62(){
    $this->_inP = false;
    $this->_retvalue = array('paragraph' => $this->yystack[$this->yyidx + -2]->minor);
    }
#line 1555 "DocblockParser.php"
#line 301 "DocblockParser.y"
    function yy_r63(){
    if (is_string($this->yystack[$this->yyidx + -1]->minor)) {
        $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;
    } else {
        $i = count($this->yystack[$this->yyidx + -1]->minor) - 1;
        if (is_string($this->yystack[$this->yyidx + -1]->minor[$i])) {
            $this->yystack[$this->yyidx + -1]->minor[$i] .= $this->yystack[$this->yyidx + 0]->minor;
        } else {
            $this->yystack[$this->yyidx + -1]->minor[] = $this->yystack[$this->yyidx + 0]->minor;
        }
        $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
    }
    }
#line 1570 "DocblockParser.php"
#line 327 "DocblockParser.y"
    function yy_r65(){
    if ($this->yystack[$this->yyidx + 0]->minor === '') {
        return $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
    }
    if (is_string($this->yystack[$this->yyidx + 0]->minor) && is_string($this->yystack[$this->yyidx + -1]->minor)) {
        return $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;
    }
    if (is_string($this->yystack[$this->yyidx + -1]->minor)) {
        $this->yystack[$this->yyidx + -1]->minor = array($this->yystack[$this->yyidx + -1]->minor);
    }
    $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
    $this->_retvalue[] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1585 "DocblockParser.php"
#line 340 "DocblockParser.y"
    function yy_r66(){
    if ($this->yystack[$this->yyidx + 0]->minor === '') {
        return $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
    }
    if (is_string($this->yystack[$this->yyidx + 0]->minor) && is_string($this->yystack[$this->yyidx + -1]->minor)) {
        return $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor . $this->yystack[$this->yyidx + 0]->minor;
    }
    if (is_string($this->yystack[$this->yyidx + -1]->minor)) {
        $this->yystack[$this->yyidx + -1]->minor = array($this->yystack[$this->yyidx + -1]->minor);
    }
    // check to see if $this->yystack[$this->yyidx + -1]->minor's last entry and $this->yystack[$this->yyidx + 0]->minor's first are strings
    if (isset($this->yystack[$this->yyidx + 0]->minor[0])) {
        if (is_string($this->yystack[$this->yyidx + -1]->minor[count($this->yystack[$this->yyidx + -1]->minor) - 1]) && is_string($this->yystack[$this->yyidx + 0]->minor[0])) {
            $this->yystack[$this->yyidx + -1]->minor[count($this->yystack[$this->yyidx + -1]->minor) - 1] .= $this->yystack[$this->yyidx + 0]->minor[0];
            array_shift($this->yystack[$this->yyidx + 0]->minor);
        }
        $this->_retvalue = array_merge($this->yystack[$this->yyidx + -1]->minor, $this->yystack[$this->yyidx + 0]->minor);
    } else {
        $this->_retvalue = $this->yystack[$this->yyidx + -1]->minor;
        $this->_retvalue[] = $this->yystack[$this->yyidx + 0]->minor;
    }
    }
#line 1609 "DocblockParser.php"
#line 367 "DocblockParser.y"
    function yy_r71(){
    $this->_retvalue = array(
        'tag' => substr($this->yystack[$this->yyidx + -1]->minor, 1),
        'text' => $this->yystack[$this->yyidx + 0]->minor,
    );
    }
#line 1617 "DocblockParser.php"
#line 373 "DocblockParser.y"
    function yy_r72(){
    $this->_retvalue = array(
        'tag' => substr($this->yystack[$this->yyidx + 0]->minor, 1),
        'text' => '',
    );
    }
#line 1625 "DocblockParser.php"

    /**
     * placeholder for the left hand side in a reduce operation.
     * 
     * For a parser with a rule like this:
     * <pre>
     * rule(A) ::= B. { A = 1; }
     * </pre>
     * 
     * The parser will translate to something like:
     * 
     * <code>
     * function yy_r0(){$this->_retvalue = 1;}
     * </code>
     */
    private $_retvalue;

    /**
     * Perform a reduce action and the shift that must immediately
     * follow the reduce.
     * 
     * For a rule such as:
     * 
     * <pre>
     * A ::= B blah C. { dosomething(); }
     * </pre>
     * 
     * This function will first call the action, if any, ("dosomething();" in our
     * example), and then it will pop three states from the stack,
     * one for each entry on the right-hand side of the expression
     * (B, blah, and C in our example rule), and then push the result of the action
     * back on to the stack with the resulting state reduced to (as described in the .out
     * file)
     * @param int Number of the rule by which to reduce
     */
    function yy_reduce($yyruleno)
    {
        //int $yygoto;                     /* The next state */
        //int $yyact;                      /* The next action */
        //mixed $yygotominor;        /* The LHS of the rule reduced */
        //PHP_Parser_DocblockParseryyStackEntry $yymsp;            /* The top of the parser's stack */
        //int $yysize;                     /* Amount to pop the stack */
        $yymsp = $this->yystack[$this->yyidx];
        if (self::$yyTraceFILE && $yyruleno >= 0 
              && $yyruleno < count(self::$yyRuleName)) {
            fprintf(self::$yyTraceFILE, "%sReduce (%d) [%s].\n",
                self::$yyTracePrompt, $yyruleno,
                self::$yyRuleName[$yyruleno]);
        }

        $this->_retvalue = $yy_lefthand_side = null;
        if (array_key_exists($yyruleno, self::$yyReduceMap)) {
            // call the action
            $this->_retvalue = null;
            $this->{'yy_r' . self::$yyReduceMap[$yyruleno]}();
            $yy_lefthand_side = $this->_retvalue;
        }
        $yygoto = self::$yyRuleInfo[$yyruleno]['lhs'];
        $yysize = self::$yyRuleInfo[$yyruleno]['rhs'];
        $this->yyidx -= $yysize;
        for($i = $yysize; $i; $i--) {
            // pop all of the right-hand side parameters
            array_pop($this->yystack);
        }
        $yyact = $this->yy_find_reduce_action($this->yystack[$this->yyidx]->stateno, $yygoto);
        if ($yyact < self::YYNSTATE) {
            /* If we are not debugging and the reduce action popped at least
            ** one element off the stack, then we can push the new element back
            ** onto the stack here, and skip the stack overflow test in yy_shift().
            ** That gives a significant speed improvement. */
            if (!self::$yyTraceFILE && $yysize) {
                $this->yyidx++;
                $x = new PHP_Parser_DocblockParseryyStackEntry;
                $x->stateno = $yyact;
                $x->major = $yygoto;
                $x->minor = $yy_lefthand_side;
                $this->yystack[$this->yyidx] = $x;
            } else {
                $this->yy_shift($yyact, $yygoto, $yy_lefthand_side);
            }
        } elseif ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
            $this->yy_accept();
        }
    }

    /**
     * The following code executes when the parse fails
     * 
     * Code from %parse_fail is inserted here
     */
    function yy_parse_failed()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sFail!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser fails */
    }

    /**
     * The following code executes when a syntax error first occurs.
     * 
     * %syntax_error code is inserted here
     * @param int The major type of the error token
     * @param mixed The minor type of the error token
     */
    function yy_syntax_error($yymajor, $TOKEN)
    {
#line 4 "DocblockParser.y"

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
#line 1755 "DocblockParser.php"
    }

    /**
     * The following is executed when the parser accepts
     * 
     * %parse_accept code is inserted here
     */
    function yy_accept()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sAccept!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $stack = $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser accepts */
#line 76 "DocblockParser.y"

#line 1776 "DocblockParser.php"
    }

    /**
     * The main parser program.
     * 
     * The first argument is the major token number.  The second is
     * the token value string as scanned from the input.
     *
     * @param int the token number
     * @param mixed the token value
     * @param mixed any extra arguments that should be passed to handlers
     */
    function doParse($yymajor, $yytokenvalue)
    {
//        $yyact;            /* The parser action. */
//        $yyendofinput;     /* True if we are at the end of input */
        $yyerrorhit = 0;   /* True if yymajor has invoked an error */
        
        /* (re)initialize the parser, if necessary */
        if ($this->yyidx === null || $this->yyidx < 0) {
            /* if ($yymajor == 0) return; // not sure why this was here... */
            $this->yyidx = 0;
            $this->yyerrcnt = -1;
            $x = new PHP_Parser_DocblockParseryyStackEntry;
            $x->stateno = 0;
            $x->major = 0;
            $this->yystack = array();
            array_push($this->yystack, $x);
        }
        $yyendofinput = ($yymajor==0);
        
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sInput %s\n",
                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
        }
        
        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yymajor < self::YYERRORSYMBOL &&
                  !$this->yy_is_expected_token($yymajor)) {
                // force a syntax error
                $yyact = self::YY_ERROR_ACTION;
            }
            if ($yyact < self::YYNSTATE) {
                $this->yy_shift($yyact, $yymajor, $yytokenvalue);
                $this->yyerrcnt--;
                if ($yyendofinput && $this->yyidx >= 0) {
                    $yymajor = 0;
                } else {
                    $yymajor = self::YYNOCODE;
                }
            } elseif ($yyact < self::YYNSTATE + self::YYNRULE) {
                $this->yy_reduce($yyact - self::YYNSTATE);
            } elseif ($yyact == self::YY_ERROR_ACTION) {
                if (self::$yyTraceFILE) {
                    fprintf(self::$yyTraceFILE, "%sSyntax Error!\n",
                        self::$yyTracePrompt);
                }
                if (self::YYERRORSYMBOL) {
                    /* A syntax error has occurred.
                    ** The response to an error depends upon whether or not the
                    ** grammar defines an error token "ERROR".  
                    **
                    ** This is what we do if the grammar does define ERROR:
                    **
                    **  * Call the %syntax_error function.
                    **
                    **  * Begin popping the stack until we enter a state where
                    **    it is legal to shift the error symbol, then shift
                    **    the error symbol.
                    **
                    **  * Set the error count to three.
                    **
                    **  * Begin accepting and shifting new tokens.  No new error
                    **    processing will occur until three tokens have been
                    **    shifted successfully.
                    **
                    */
                    if ($this->yyerrcnt < 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $yymx = $this->yystack[$this->yyidx]->major;
                    if ($yymx == self::YYERRORSYMBOL || $yyerrorhit ){
                        if (self::$yyTraceFILE) {
                            fprintf(self::$yyTraceFILE, "%sDiscard input token %s\n",
                                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
                        }
                        $this->yy_destructor($yymajor, $yytokenvalue);
                        $yymajor = self::YYNOCODE;
                    } else {
                        while ($this->yyidx >= 0 &&
                                 $yymx != self::YYERRORSYMBOL &&
        ($yyact = $this->yy_find_shift_action(self::YYERRORSYMBOL)) >= self::YYNSTATE
                              ){
                            $this->yy_pop_parser_stack();
                        }
                        if ($this->yyidx < 0 || $yymajor==0) {
                            $this->yy_destructor($yymajor, $yytokenvalue);
                            $this->yy_parse_failed();
                            $yymajor = self::YYNOCODE;
                        } elseif ($yymx != self::YYERRORSYMBOL) {
                            $u2 = 0;
                            $this->yy_shift($yyact, self::YYERRORSYMBOL, $u2);
                        }
                    }
                    $this->yyerrcnt = 3;
                    $yyerrorhit = 1;
                } else {
                    /* YYERRORSYMBOL is not defined */
                    /* This is what we do if the grammar does not define ERROR:
                    **
                    **  * Report an error message, and throw away the input token.
                    **
                    **  * If the input token is $, then fail the parse.
                    **
                    ** As before, subsequent error messages are suppressed until
                    ** three input tokens have been successfully shifted.
                    */
                    if ($this->yyerrcnt <= 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $this->yyerrcnt = 3;
                    $this->yy_destructor($yymajor, $yytokenvalue);
                    if ($yyendofinput) {
                        $this->yy_parse_failed();
                    }
                    $yymajor = self::YYNOCODE;
                }
            } else {
                $this->yy_accept();
                $yymajor = self::YYNOCODE;
            }            
        } while ($yymajor != self::YYNOCODE && $this->yyidx >= 0);
    }
}