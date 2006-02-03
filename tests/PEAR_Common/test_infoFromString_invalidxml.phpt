--TEST--
PEAR_Common::infoFromString test (invalid xml)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
if (!function_exists('token_get_all')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';

$php5 = version_compare(phpversion(), '5.0.0', '>=');
$ret = $common->infoFromString('\\goober');
$message = $php5 ? 'XML error: Empty document at line 1' :
    'XML error: not well-formed (invalid token) at line 1';
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => $message),
    array('package' => 'PEAR_PackageFile', 'message' => 'package.xml "" has no package.xml <package> version')), 'error message');
$phpunit->assertIsa('PEAR_Error', $ret, 'return');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done