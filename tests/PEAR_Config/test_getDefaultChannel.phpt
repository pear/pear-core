--TEST--
PEAR_Config->getDefaultChannel()
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
$config = new PEAR_Config;
$phpunit->assertTrue($config->setChannels(array('pear.php.net', '__uri', 'mychannel')), 'set');
require_once 'PEAR/ChannelFile.php';
$ch = new PEAR_ChannelFile;
$ch->setName('mychannel');
$ch->setSummary('mychannel');
$ch->setServer('mychannel');
$ch->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$reg->addChannel($ch);

$phpunit->assertEquals('pear.php.net', $config->getDefaultChannel(), 'user');
$phpunit->assertTrue($config->set('default_channel', 'mychannel', 'user'), 'set');
$phpunit->assertEquals('mychannel', $config->getDefaultChannel(), 'user');
$phpunit->assertEquals('pear.php.net', $config->getDefaultChannel('system'), 'system');
$phpunit->assertTrue($config->set('default_channel', 'mychannel', 'user', 'mypackage'), 'channel set');
$phpunit->assertEquals('mychannel', $config->getDefaultChannel(), 'user');
$phpunit->assertEquals('pear.php.net', $config->getDefaultChannel('system'), 'system');
echo 'tests done';
?>
--EXPECT--
tests done
