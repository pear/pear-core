--TEST--
channel-update command (remote channel, channel.xml changes primary server test)
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
$c = &$reg->getChannel(strtolower('pear.php.net'));
$lastmod = $c->lastModified();
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'channel.update',
    array($lastmod),
    '<?xml version="1.0" encoding="ISO-8859-1"?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/channel-1.0
http://pear.php.net/dtd/channel-1.0.xsd">
 <name>pear.php.net</name>
 <suggestedalias>pear</suggestedalias>
 <summary>foo</summary>
 <servers>
  <primary host="oops.we.changedit">
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.update</function>
    <function version="1.0">channel.listAll</function>
   </xmlrpc>
  </primary>
 </servers>
</channel>');
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'channel.update',
    array(1),
    '<?xml version="1.0" encoding="ISO-8859-1"?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/channel-1.0
http://pear.php.net/dtd/channel-1.0.xsd">
 <name>pear.php.net</name>
 <suggestedalias>pear</suggestedalias>
 <summary>foo</summary>
 <servers>
  <primary host="oops.we.changedit">
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.update</function>
    <function version="1.0">channel.listAll</function>
   </xmlrpc>
  </primary>
 </servers>
</channel>');
$e = $command->run('channel-update', array(), array('pear.php.net'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' =>
        'ERROR: primary server "pear.php.net" for channel "pear.php.net" would be changed to "oops.we.changedit", use --force to update anyways'),
), 'after');
$phpunit->assertEquals(array (
  0 =>
  array (
    'info' => 'Retrieving channel.xml from remote server',
    'cmd' => 'no command',
  ),
), $fakelog->getLog(), 'log');

$reg = &new PEAR_Registry($temp_path . DIRECTORY_SEPARATOR . 'php');
$chan = $reg->getChannel('pear.php.net');
$phpunit->assertIsA('PEAR_ChannelFile', $chan, 'updated ok?');
$phpunit->assertEquals('pear.php.net', $chan->getName(), 'name ok?');
$phpunit->assertEquals('pear.php.net', $chan->getServer(), 'server ok?');
$phpunit->assertEquals('PHP Extension and Application Repository', $chan->getSummary(), 'summary ok?');


$e = $command->run('channel-update', array('force' => true), array('pear.php.net'));
$phpunit->assertNoErrors('after force');
$phpunit->assertEquals(array (
  0 =>
  array (
    'info' => 'Retrieving channel.xml from remote server',
    'cmd' => 'no command',
  ),
  1 =>
  array (
    0 => 0,
    1 => 'WARNING: primary server "pear.php.net" for channel "pear.php.net" will be changed to "oops.we.changedit"',
  ),
  2 =>
  array (
    'info' => 'Update of Channel "pear.php.net" succeeded',
    'cmd' => 'no command',
  ),
), $fakelog->getLog(), 'log force');

$reg = &new PEAR_Registry($temp_path . DIRECTORY_SEPARATOR . 'php');
$chan = $reg->getChannel('pear.php.net');
$phpunit->assertIsA('PEAR_ChannelFile', $chan, 'updated ok?');
$phpunit->assertEquals('pear.php.net', $chan->getName(), 'name ok?');
$phpunit->assertEquals('oops.we.changedit', $chan->getServer(), 'server ok?');
$phpunit->assertEquals('foo', $chan->getSummary(), 'summary ok?');
echo 'tests done';
?>
--EXPECT--
tests done
