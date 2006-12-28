<?php
// this shows how it works
set_include_path(dirname(dirname(__FILE__)) . PATH_SEPARATOR . 'C:/php5/pear');
require 'PEAR2/Autoload.php';
function __autoload($class)
{
    PEAR2_Autoload($class);
}
define('OS_WINDOWS', true);
$g = new PEAR2_Config('G:/Documents/PEAR2/testpear');
$g->saveConfig();
$a = new PEAR2_Package('C:/development/pear-core/package2.xml');
$b = new PEAR2_Installer;
$b->install($a);