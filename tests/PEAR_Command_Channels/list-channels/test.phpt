--TEST--
list-channels command
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

$e = $command->run('list-channels', array(), array());
$phpunit->assertNoErrors('1');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Registered Channels:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Channel',
        1 => 'Server',
        2 => 'Summary',
      ),
      'data' => 
      array (
        0 => 
        array (
          0 => 'pear.php.net',
          1 => 'pear.php.net',
          2 => 'PHP Extension and Application Repository',
        ),
        1 => 
        array (
          0 => '__uri',
          1 => 'www.example.com',
          2 => 'Pseudo-channel for static packages',
        ),
      ),
    ),
    'cmd' => 'list-channels',
  ),
), $fakelog->getLog(), 'log 1');

$ch = new PEAR_ChannelFile;
$ch->setName('fake');
$ch->setSummary('fake');
$ch->setServer('poor.php.net');
$ch->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$reg->addChannel($ch);
$e = $command->run('list-channels', array(), array());
$phpunit->assertNoErrors('1');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Registered Channels:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Channel',
        1 => 'Server',
        2 => 'Summary',
      ),
      'data' => 
      array (
        0 => 
        array (
          0 => 'fake',
          1 => 'poor.php.net',
          2 => 'fake',
        ),
        1 => 
        array (
          0 => 'pear.php.net',
          1 => 'pear.php.net',
          2 => 'PHP Extension and Application Repository',
        ),
        2 => 
        array (
          0 => '__uri',
          1 => 'www.example.com',
          2 => 'Pseudo-channel for static packages',
        ),
      ),
    ),
    'cmd' => 'list-channels',
  ),
), $fakelog->getLog(), 'log 2');
echo 'tests done';
?>
--EXPECT--
tests done
