--TEST--
PEAR_Command::getFrontendObject()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$GLOBALS['_PEAR_Command_uiclass'] = 'fronk_oog_booger';
$err = &PEAR_Command::getFrontendObject();
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'no such class: fronk_oog_booger')
), 'invalid');
$GLOBALS['_PEAR_Command_uiclass'] = 'PEAR_Frontend_CLI';
$ok = &PEAR_Command::getFrontendObject();
$phpunit->assertIsa('PEAR_Frontend_CLI', $ok, 'ok');
$phpunit->assertIsa('PEAR_Error', $err, 'invalid');
echo 'tests done';
?>
--EXPECT--
tests done
