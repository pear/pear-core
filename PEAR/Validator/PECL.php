<?php
/**
 * Channel Validator for the pecl.php.net channel
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 * @version $Id$
 */
/**
 * This is the parent class for all validators
 */
require_once 'PEAR/Validate.php';
/**
 * Channel Validator for the pecl.php.net channel
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 * @version $Id$
 */
class PEAR_Validator_PECL extends PEAR_Validate
{
    function validateVersion()
    {
        return true;
    }
}
?>