<?php
include "PEAR/Channelfile.php";
require_once 'PEAR/ErrorStack.php';
require_once dirname(dirname(__FILE__)) . '/phpt_test.php.inc';
function postprocess($string)
{
    return str_replace("\r", "\n", str_replace("\r\n", "\n", $string));
}
function logStack($err)
{
    echo "caught Error Stack error:\n";
    echo $err['message'] . "\n";
    echo 'code : ' . $err['code'] . "\n";
}
PEAR_ErrorStack::setDefaultCallback('logStack');
$chf = new PEAR_ChannelFile;
$phpt = new PEAR_PHPTest(true);
?>