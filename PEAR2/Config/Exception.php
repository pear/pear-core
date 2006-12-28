<?php
class PEAR2_Config_Exception extends PEAR2_Exception
{
    static private $errors = array(
        'invalidConfig' => array(
            'en' => 'Unable to parse invalid PEAR configuration at "%s"',
        ),
        'unknownValue' => array(
            'en' => 'Unknown configuration variable "%s"',
        ),
    );

    static function invalidConfig($path)
    {
        return new PEAR2_Config_Exception(self::$errors, $path);
    }

    static function unknownValue($value)
    {
        return new PEAR2_Config_Exception(self::$errors, $value);
    }
}