--TEST--
install command, bug #5513 test
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
$phpunit->assertTrue($reg->addChannel($ch), 'smork setup');
$chan = &$reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'phing-current.tgz';
$pathtobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'agavi-current.tgz';
$_test_dep->setPHPVersion('5.0.4');
$_test_dep->setPEARVersion('1.4.1');
$config->set('preferred_state', 'alpha');
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
$fakelog->getLog();
$res = $command->run('install', array(), array($pathtobarxml));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
echo 'tests done';
?>
--EXPECT--
tests done
