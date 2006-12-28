<?php
class PEAR2_Log
{
    static public $log = array();
    static public function log($level, $message)
    {
        for ($i = $level; $i; $i--) {
            self::$log[$i][] = $message;
        }
    }
}