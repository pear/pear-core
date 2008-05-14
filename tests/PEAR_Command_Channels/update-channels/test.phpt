--TEST--
update-channels command
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(1803);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$c = &$reg->getChannel(strtolower('pear.php.net'));
$c->setName('zornk.ornk.org');
$reg->addChannel($c);
$c->setName('horde.orde.de');
$reg->addChannel($c);
$lastmod = $c->lastModified();
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'channel.listAll',
    null,
    array(
        array('pear.php.net'),
        array('zornk.ornk.org'),
        array('horde.orde.de'),
    ));
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'files'. DIRECTORY_SEPARATOR . 'pearchannel.xml';
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/channel.xml', $pathtochannelxml);
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'files'. DIRECTORY_SEPARATOR . 'zornkchannel.xml';
$GLOBALS['pearweb']->addHtmlConfig('http://zornk.ornk.org/channel.xml', $pathtochannelxml);
$pathtochannelxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'files'. DIRECTORY_SEPARATOR . 'hordechannel.xml';
$GLOBALS['pearweb']->addHtmlConfig('http://horde.orde.de/channel.xml', $pathtochannelxml);

$e = $command->run('update-channels', array(), array());
$phpunit->assertNoErrors('after');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'Updating channel "horde.orde.de"',
    'cmd' => 'channel-update',
  ),
  1 => 
  array (
    'info' => 'Update of Channel "horde.orde.de" succeeded',
    'cmd' => 'no command',
  ),
  2 => 
  array (
    'info' => 'Updating channel "pear.php.net"',
    'cmd' => 'channel-update',
  ),
  3 => 
  array (
    'info' => 'Update of Channel "pear.php.net" succeeded',
    'cmd' => 'no command',
  ),
  4 => 
  array (
    'info' => 'Updating channel "pecl.php.net"',
    'cmd' => 'channel-update',
  ),
  5 => 
  array (
    'info' => 'Cannot retrieve channel.xml for channel "pecl.php.net" (File http://pecl.php.net:80/channel.xml not valid (received: HTTP/1.1 404 http://pecl.php.net/channel.xml Is not valid))',
    'cmd' => 'update-channels',
  ),
  6 => 
  array (
    'info' => 'Updating channel "zornk.ornk.org"',
    'cmd' => 'channel-update',
  ),
  7 => 
  array (
    'info' => 'Update of Channel "zornk.ornk.org" succeeded',
    'cmd' => 'no command',
  ),
)
, $fakelog->getLog(), 'log');

$reg = &new PEAR_Registry($temp_path . DIRECTORY_SEPARATOR . 'php');
$chan = $reg->getChannel('pear.php.net');
$phpunit->assertIsA('PEAR_ChannelFile', $chan, 'updated ok?');
$phpunit->assertEquals('pear.php.net', $chan->getName(), 'name ok?');
$phpunit->assertEquals('foo', $chan->getSummary(), 'summary ok?');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
