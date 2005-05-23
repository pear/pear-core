--TEST--
PEAR_Channelfile->getPath()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php

error_reporting(E_ALL);
chdir(dirname(__FILE__));
require_once './setup.php.inc';
$chf->fromXmlString($first = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pear.php.net/dtd/channel-1.0.xsd">
 <name>pear.php.net</name>
 <suggestedalias>pear</suggestedalias>
 <summary>PHP Extension and Application Repository</summary>
 <servers>
  <primary>
   <xmlrpc>
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap>
    <function version="1.0">channel.update</function>
   </soap>
  </primary>
  <mirror host="blah">
   <xmlrpc>
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap>
    <function version="1.0">channel.update</function>
   </soap>
  </mirror>
 </servers>
</channel>');
$phpt->assertTrue($chf->validate(), 'default parse');
$phpt->assertEquals('xmlrpc.php', $chf->getPath('xmlrpc'), 'default xmlrpc path, primary');
$phpt->assertEquals('xmlrpc.php', $chf->getPath('xmlrpc', 'blah'), 'default xmlrpc path, mirror');
$phpt->assertEquals('soap.php', $chf->getPath('soap'), 'default soap path, primary');
$phpt->assertEquals('soap.php', $chf->getPath('soap', 'blah'), 'default soap path, mirror');
$chf->fromXmlString($first = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pear.php.net/dtd/channel-1.0.xsd">
 <name>pear.php.net</name>
 <suggestedalias>pear</suggestedalias>
 <summary>PHP Extension and Application Repository</summary>
 <servers>
  <primary>
   <xmlrpc path="234">
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap path="hi/foo.php">
    <function version="1.0">channel.update</function>
   </soap>
  </primary>
  <mirror host="blah">
   <xmlrpc path="345">
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap path="hiow">
    <function version="1.0">channel.update</function>
   </soap>
  </mirror>
 </servers>
</channel>');
$phpt->assertTrue($chf->validate(), 'default parse');
$phpt->assertEquals('234', $chf->getPath('xmlrpc'), '234 xmlrpc path, primary');
$phpt->assertEquals('345', $chf->getPath('xmlrpc', 'blah'), 'hi/foo.php xmlrpc path, mirror');
$phpt->assertEquals('hi/foo.php', $chf->getPath('soap'), '345 soap path, primary');
$phpt->assertEquals('hiow', $chf->getPath('soap', 'blah'), 'hiow soap path, mirror');
echo 'tests done';
?>
--EXPECT--
tests done