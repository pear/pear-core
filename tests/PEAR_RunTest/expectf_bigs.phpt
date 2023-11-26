--TEST--
PEAR_RunTest EXPECTF %S formatter
--FILE--
<?php
$oops->method();
// PHP5: Fatal error:
// PHP7: Catchable fatal error:
/*
- - EXPECT F - -
%s: Undefined variable: %Soops in %s.php on line %d

Fatal error: %SCall to a member function method() on %s in %s.php%S%d
%S
%S
%S
*/
?>
--EXPECTF--
%s: Undefined variable%soops in %s on line 2

Fatal error:%S Call to a member function method() on %s in %s on line 2
