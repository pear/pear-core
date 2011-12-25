<?php
require_once dirname(dirname(__FILE__)) . '/phpt_test.php.inc';
include "PEAR/ChannelFile.php";
function postprocess($string)
{
    return str_replace("\r", "\n", str_replace("\r\n", "\n", $string));
}
$chf = &new PEAR_ChannelFile;
$phpt = &new PEAR_PHPTest(true);
?>
