--TEST--
Bug #16077: PEAR5::getStaticProperty does not return a reference to the property
--FILE--
<?php
require_once 'PEAR.php';

$skiptrace = &PEAR5::getStaticProperty('PEAR_Error', 'skiptrace');
$skiptrace = true;
var_dump(PEAR5::getStaticProperty('PEAR_Error', 'skiptrace'));
?>
--EXPECT--
bool(true)
