--TEST--
PEAR_RunTest Bug #10286 - no output from fatal errors displayed.
--SKIPIF--
<?php
if (version_compare(PHP_VERSION, '8.0.0') == -1) {
    echo 'skip';
}
?>
--FILE--
<?php
$oops->method();
// PHP5: Fatal error:
// PHP7: Catchable fatal error:
?>
--EXPECTF--
Warning: Undefined variable $oops in %s/bug10286.php on line %d

Fatal error: Uncaught Error: Call to a member function method() on null in %s/bug10286.php:%d
Stack trace:
#0 {main}
  thrown in %s/bug10286.php on line %d
