<?php
require_once dirname(dirname(dirname(__FILE__))) . '/phpt_test.php.inc';
require_once 'PEAR/ErrorStack.php';
$phpunit = new PEAR_PHPTest(true, true);
$stack = &PEAR_ErrorStack::singleton('test');
class Burflog
{
    var $info;
    function log($err)
    {
        global $wasLogged;
        $wasLogged = true;
        $this->info = array($err);
    }
}
?>
