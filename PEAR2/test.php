<?php
// this shows how it works
set_include_path(dirname(dirname(__FILE__)) . PATH_SEPARATOR . 'C:/php5/pear');
require 'PEAR2/Autoload.php';
function __autoload($class)
{
    PEAR2_Autoload($class);
}
define('OS_WINDOWS', true);
$g = new PEAR2_Config('C:/development/pear-core/testpear');
$g->saveConfig();
$a = new PEAR2_Package('C:/development/pear-core/PEAR-1.5.0a1.tgz');
$b = new PEAR2_Installer;
$b->install($a);