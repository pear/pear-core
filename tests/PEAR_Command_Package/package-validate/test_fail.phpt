--TEST--
package-validate command failure
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
touch($temp_path . DIRECTORY_SEPARATOR . 'bloob.xml');
$ret = $command->run('package-validate', array(), array($temp_path . DIRECTORY_SEPARATOR . 'bloob.xml'));
if (version_compare(phpversion(), '5.0.0', '>=')) {
    $errmsg = 'XML error: XML_ERR_DOCUMENT_END at line 1';
} else {
    $errmsg = 'XML error: no element found at line 1';
}
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile', 'message' => 'package.xml "' .
        $temp_path . DIRECTORY_SEPARATOR . 'bloob.xml" has no package.xml <package> version'),
    array('package' => 'PEAR_Error', 'message' => $errmsg),
    array('package' => 'PEAR_Error', 'message' => $errmsg),
), 'ret 1');
$phpunit->assertIsa('PEAR_Error', $ret, 'bloob.xml');
$phpunit->assertEquals(array (
), $fakelog->getLog(), 'log');
echo 'tests done';
?>
--EXPECT--
tests done
