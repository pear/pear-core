--TEST--
System commands tests
--SKIPIF--
<?php
if (getenv('TRAVIS')) {
    // this test is a frequent false failure on Travis CI due to RunTests timing it out
    echo 'skip ';
}
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip ';
}
if (!($fp = @fsockopen('pear.php.net', 80))) {
    echo 'skip internet is down';
}
@fclose($fp);
?>
--FILE--
<?php

require_once 'System.php';

 /*******************
         cat
 ********************/
echo "Testing: cat online\n";
$catfile = System::mktemp('tst');

// Concat from url wrapper
$cat = 'http://www.php.net/ http://pear.php.net/ > ' . $catfile;

$success = false;
$attempts = 0;
while ($attempts < 10) {
    if (!System::cat($cat)) {
        ++$attempts;
        sleep(1);
    } else {
        $success = true;
        break;
    }
}
if (!$success) {
    print "System::cat('$cat') failed\n";
}

// Clean up
unlink($catfile);

print "end\n";
?>
--EXPECT--
Testing: cat online
end
