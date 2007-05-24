--TEST--
PEAR_Channelfile->setPath()
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
$phpt->assertTrue($chf->setPath('xmlrpc', 'hi'), 'first time');
$phpt->assertEquals('hi', $chf->getPath('xmlrpc'), 'first');
$phpt->assertTrue($chf->setPath('soap', 'byebye'), 'first time soap');
$phpt->assertEquals('byebye', $chf->getPath('soap'), 'first soap');
$res = $chf->setPath('oops', 'notfound');
$phpt->assertFalse($res, 'notfound oops');
$res = $chf->setPath('xmlrpc', 'bye', 'notfound');
$phpt->assertErrors(array(
    array('package' => 'PEAR_ChannelFile', 'message' => 'Mirror "notfound" does not exist')
), 'errors');
$phpt->assertFalse($res, 'notfound time');
$chf->setName('hi');
$chf->addMirror('blah');
$res = $chf->setPath('xmlrpc', 'gorgle', 'blah');
$phpt->assertEquals('gorgle', $chf->getPath('xmlrpc', 'blah'), 'blah second');
$chf->addMirror('greg');
$res = $chf->setPath('soap', 'flue', 'greg');
$phpt->assertEquals('gorgle', $chf->getPath('xmlrpc', 'blah'), 'blah xmlrpc');
$phpt->assertEquals('soap.php', $chf->getPath('soap', 'blah'), 'blah soap');
$phpt->assertEquals('xmlrpc.php', $chf->getPath('xmlrpc', 'greg'), 'greg xmlrpc');
$phpt->assertEquals('flue', $chf->getPath('soap', 'greg'), 'greg soap');
$phpt->assertEquals('hi', $chf->getPath('xmlrpc'), 'main xmlrpc');
$phpt->assertEquals('byebye', $chf->getPath('soap'), 'main soap');
echo 'tests done';
?>
--EXPECT--
tests done
