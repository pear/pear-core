--TEST--
remote-info command failure
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
$reg = &$config->getRegistry();
$pearweb->addXmlrpcConfig("pear.php.net", "package.info",     array('boog'),     array(
        'releases' => array(),
        'stable' => '',
        'notes' => array(),
    ));
$e = $command->run('remote-info', array(), array());
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'remote-info expects one param: the remote package name'),
), 'wrong params');
$e = $command->run('remote-info', array(), array('smoog/boog'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'unknown channel "smoog" in "smoog/boog"'),
    array('package' => 'PEAR_Error', 'message' => 'Invalid package name "smoog/boog"'),
), 'unknown channel');
$e = $command->run('remote-info', array(), array('boog'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'No remote package "boog" was found'),
), 'boog');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
