<?php
if (function_exists('PEAR2_Autoload')) return;
function PEAR2_Autoload($class)
{
    require str_replace('_', '/', $class) . '.php';
}