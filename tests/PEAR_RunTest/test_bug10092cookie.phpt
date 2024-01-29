--TEST--
PEAR_RunTest --COOKIE--
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--COOKIE--
cookie1=val1  ; cookie2=val2%20; cookie3=val 3.; cookie 4= value 4 %3B; cookie1=bogus; %20cookie1=ignore;+cookie1=ignore;cookie1;cookie  5=%20 value; cookie%206=�;cookie+7=;$cookie.8;cookie-9=1;;;- & % $cookie 10=10
--FILE--
<?php
//if (version_compare(PHP_VERSION, '7.2.34') === -1) {
// although behavior is changed only in 7.2.34, test behavior implies that Ubuntu may have backported the cookie fix to 7.1, 7.0, and 5.6
if (version_compare(PHP_VERSION, '5.6.40') === -1) {
    $expected = array(
        'cookie1'=> 'val1  ',
        'cookie2'=> 'val2 ',
        'cookie3'=> 'val 3.',
        'cookie_4'=> ' value 4 ;',
        'cookie__5'=> '  value',
        'cookie_6'=> '�',
        'cookie_7'=> '',
        '$cookie_8'=> '',
        'cookie-9'=> '1',
        '-_&_%_$cookie_10'=> '10',
    );
} else {
    // "As of PHP 7.2.34, the _names_ of incoming cookies are no longer url-decoded for security reasons."
    // - https://www.php.net/manual/en/migration72.incompatible.php
    $expected = array(
        'cookie1'=> 'val1  ',
        '+cookie1'=> 'ignore',	  // no longer resolving to 'cookie1' key
        '%20cookie1'=> 'ignore',  // no longer resolving to 'cookie1' key
        'cookie2'=> 'val2 ',
        'cookie3'=> 'val 3.',
        'cookie_4'=> ' value 4 ;',
        'cookie__5'=> '  value',
        'cookie_6'=> '�',
        'cookie%206'=> '�',       // no longer resolving to 'cookie_6' key
        'cookie+7'=> '',          // no longer resolving to 'cookie_7' key
        '$cookie_8'=> '',
        'cookie-9'=> '1',
        '-_&_%_$cookie_10'=> '10',
    );
}
print_r(array_diff($_COOKIE, $expected));
?>
--EXPECT--
Array
(
)
