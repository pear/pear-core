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
$e = $command->run('remote-info', array('channel' => 'smoog'), array('boog'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'Channel "smoog" does not exist'),
), 'unknown channel');
$e = $command->run('remote-info', array(), array('boog'));
$phpunit->assertNoErrors('boog');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'No remote package "boog" was found',
    'cmd' => 'no command',
  ),
 ), $fakelog->getLog(), 'boog log');
echo 'tests done';
?>
--EXPECT--
tests done
