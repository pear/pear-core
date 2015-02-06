--TEST--
PEAR_RunTest Bug #10286 - no output from fatal errors displayed.
--FILE--
<?php
$oops->method();
?>
--EXPECTF--
Notice: Undefined variable: oops in %sbug10286.php on line %d

Fatal error: Call to a member function method() on %s in %sbug10286.php on line %d
