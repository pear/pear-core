--TEST--
PEAR_Channelfile->resetFunctions() (rest)
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
require_once './setup.php.inc';$chf->fromXmlString($first = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pear.php.net/dtd/channel-1.0.xsd">
 <name>pear.php.net</name>
 <suggestedalias>pear</suggestedalias>
 <summary>PHP Extension and Application Repository</summary>
 <servers>
  <primary>
   <rest>
    <function version="1.0" uri="logintest.xml">logintest</function>
    <function version="1.0" uri="package.listLatestReleases.xml">package.listLatestReleases</function>
    <function version="1.0" uri="package.listAll.xml">package.listAll</function>
    <function version="1.0" uri="package.info.xml">package.info</function>
    <function version="1.0" uri="package.getDownloadURL.xml">package.getDownloadURL</function>
    <function version="1.0" uri="channel.listAll.xml">channel.listAll</function>
    <function version="1.0" uri="channel.update.xml">channel.update</function>
   </rest>
  </primary>
 </servers>
</channel>');
$phpt->assertTrue($chf->validate(), 'initial parse');
$phpt->assertEquals(array (
  'attribs' => 
  array (
    'version' => '1.0',
    'xmlns' => 'http://pear.php.net/channel-1.0',
    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
    'xsi:schemaLocation' => 'http://pear.php.net/dtd/channel-1.0.xsd',
  ),
  'name' => 'pear.php.net',
  'suggestedalias' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'servers' => 
  array (
    'primary' => 
    array (
      'rest' => 
      array (
        'function' => 
        array (
          0 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'logintest.xml',
            ),
            '_content' => 'logintest',
          ),
          1 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'package.listLatestReleases.xml',
            ),
            '_content' => 'package.listLatestReleases',
          ),
          2 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'package.listAll.xml',
            ),
            '_content' => 'package.listAll',
          ),
          3 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'package.info.xml',
            ),
            '_content' => 'package.info',
          ),
          4 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'package.getDownloadURL.xml',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          5 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'channel.listAll.xml',
            ),
            '_content' => 'channel.listAll',
          ),
          6 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
              'uri' => 'channel.update.xml',
            ),
            '_content' => 'channel.update',
          ),
        ),
      ),
    ),
  ),
), $chf->toArray(), 'Parsed array of default is not correct');
$chf->fromXmlString($chf->toXml());
$chf->resetFunctions('rest');

$phpt->assertTrue($chf->validate(), 're-parsing validate');
$phpt->assertEquals(array (
  'attribs' => 
  array (
    'version' => '1.0',
    'xmlns' => 'http://pear.php.net/channel-1.0',
    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
    'xsi:schemaLocation' => 'http://pear.php.net/dtd/channel-1.0 http://pear.php.net/dtd/channel-1.0.xsd',
  ),
  'name' => 'pear.php.net',
  'summary' => 'PHP Extension and Application Repository',
  'suggestedalias' => 'pear',
  'servers' => 
  array (
    'primary' => 
    array (
    ),
  ),
), $chf->toArray(), 'Re-parsed array of default is not correct');
echo 'tests done';
?>
--EXPECT--
tests done