--TEST--
PHP Bug #14744: destructor emulation problem
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php

require_once "PEAR.php";

// test for bug http://bugs.php.net/bug.php?id=14744
class Other extends PEAR {

    var $a = 'default value';

    function Other() {
        $this->PEAR();
    }

    function _Other() {
        // $a was modified but here misteriously returns to be
        // the original value. That makes the destructor useless
        // The correct value for $a in the destructor shoud be "new value"
        echo "#bug 14744# Other class destructor: other->a == '" . $this->a ."'\n";
    }
}

echo "testing bug #14744\n";
$other =& new Other;
echo "#bug 14744# Other class constructor: other->a == '" . $other->a ."'\n";
// Modify $a
$other->a = 'new value';
echo "#bug 14744# Other class modified: other->a == '" . $other->a ."'\n";

print "..\n";
print "script exiting...\n";
print "..\n";
?>
--EXPECT--
testing bug #14744
#bug 14744# Other class constructor: other->a == 'default value'
#bug 14744# Other class modified: other->a == 'new value'
..
script exiting...
..
#bug 14744# Other class destructor: other->a == 'new value'