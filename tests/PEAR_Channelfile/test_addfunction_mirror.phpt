--TEST--
PEAR_Channelfile->addFunction() (mirror)
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
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <servers>
  <primary host="pear.php.net">
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.listAll</function>
    <function version="1.0">channel.update</function>
   </xmlrpc>
  </primary>
 </servers>
</channel>');

echo "after parsing\n";
if (!$chf->validate()) {
    echo "test default failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    $phpt->assertEquals(array (
  'mirrors' => 
  array (
  ),
  'subchannels' => 
  array (
  ),
  'version' => '1.0',
  'name' => 'pear.php.net',
  'suggestedalias' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'validatepackage' =>
  array(
    'version' => '1.0',
    'name' => 'PEAR_Validate',
  ),
  'server' => 'pear.php.net',
  'port' => 80,
  'protocols' => 
  array (
    'xmlrpc' => 
    array (
      'functions' => 
      array (
        1 => 
        array (
          'version' => '1.0',
          'name' => 'logintest',
        ),
        2 => 
        array (
          'version' => '1.0',
          'name' => 'package.listLatestReleases',
        ),
        3 => 
        array (
          'version' => '1.0',
          'name' => 'package.listAll',
        ),
        4 => 
        array (
          'version' => '1.0',
          'name' => 'package.info',
        ),
        5 => 
        array (
          'version' => '1.0',
          'name' => 'package.getDownloadURL',
        ),
        6 => 
        array (
          'version' => '1.0',
          'name' => 'channel.listAll',
        ),
        7 => 
        array (
          'version' => '1.0',
          'name' => 'channel.update',
        ),
      ),
    ),
  ),
), $chf->toArray(), 'Parsed array of default is not correct');
}
$chf->addMirror('mirror.php.net');
$chf->addFunction('xmlrpc', '2.0', 'foobar', 'mirror.php.net');
$chf->addFunction('soap', '1.0', 'foobar', 'mirror.php.net');

echo "after add\n";
if (!$chf->validate()) {
    echo "test default failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    $phpt->assertEquals(array (
  'mirrors' => 
  array (
    1 =>
    array(
      'server' => 'mirror.php.net',
      'protocols' => array (
        'xmlrpc' =>
        array (
          'functions' =>
          array (
            1 =>
            array (
              'version' => '2.0',
              'name' => 'foobar',
            ),
          ),
        ),
        'soap' =>
        array (
          'functions' =>
          array (
            1 =>
            array (
              'version' => '1.0',
              'name' => 'foobar',
            ),
          ),
        ),
      ),
    ),
  ),
  'subchannels' => 
  array (
  ),
  'version' => '1.0',
  'name' => 'pear.php.net',
  'suggestedalias' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'validatepackage' =>
  array(
    'version' => '1.0',
    'name' => 'PEAR_Validate',
  ),
  'server' => 'pear.php.net',
  'port' => 80,
  'protocols' => 
  array (
    'xmlrpc' => 
    array (
      'functions' => 
      array (
        1 => 
        array (
          'version' => '1.0',
          'name' => 'logintest',
        ),
        2 => 
        array (
          'version' => '1.0',
          'name' => 'package.listLatestReleases',
        ),
        3 => 
        array (
          'version' => '1.0',
          'name' => 'package.listAll',
        ),
        4 => 
        array (
          'version' => '1.0',
          'name' => 'package.info',
        ),
        5 => 
        array (
          'version' => '1.0',
          'name' => 'package.getDownloadURL',
        ),
        6 => 
        array (
          'version' => '1.0',
          'name' => 'channel.listAll',
        ),
        7 => 
        array (
          'version' => '1.0',
          'name' => 'channel.update',
        ),
      ),
    ),
  ),
), $chf->toArray(), 'addMirror() did not work as expected');
}

?>
--EXPECT--
after parsing
after add
