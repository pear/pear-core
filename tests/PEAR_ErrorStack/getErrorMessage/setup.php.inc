<?php
require_once dirname(dirname(dirname(__FILE__))) . '/phpt_test.php.inc';
require_once 'PEAR/ErrorStack.php';
$phpunit = new PEAR_PHPTest(true);
$stack = &PEAR_ErrorStack::singleton('test');
class testgemessage 
{
    function __toString()
    {
        return '__toString() called';
    }
}
class testgemessage1 {} 
?>
