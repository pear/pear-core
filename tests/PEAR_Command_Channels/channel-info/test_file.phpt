--TEST--
channel-info command (channel.xml file)
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
$c = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<channel version="1.0" xmlns="http://pear.php.net/channel-1.0"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://pear.php.net/dtd/channel-1.0.xsd">
 <name>froo</name>
 <suggestedalias>froo</suggestedalias>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <servers>
  <primary>
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.listAll</function>
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
   </soap>
  </primary>
  <mirror host="poor.php.net">
   <xmlrpc>
    <function version="1.0">logintest</function>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
    <function version="1.0">package.info</function>
    <function version="1.0">package.getDownloadURL</function>
    <function version="1.0">channel.listAll</function>
    <function version="1.0">channel.update</function>
   </xmlrpc>
   <soap>
    <function version="1.0">package.listLatestReleases</function>
    <function version="1.0">package.listAll</function>
   </soap>
  </mirror>
 </servers>
</channel>';
$fp = fopen($temp_path . DIRECTORY_SEPARATOR . 'channel.xml', 'wb');
fwrite($fp, $c);
fclose($fp);
$e = $command->run('channel-info', array(), array($temp_path . DIRECTORY_SEPARATOR . 'channel.xml'));
$phpunit->assertNoErrors('1');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Channel froo Information:',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => 'Name and Server',
          1 => 'froo',
        ),
        1 => 
        array (
          0 => 'Summary',
          1 => 'PHP Extension and Application Repository',
        ),
        2 => 
        array (
          0 => 'Validation Package Name',
          1 => 'PEAR_Validate',
        ),
        3 => 
        array (
          0 => 'Validation Package Version',
          1 => '1.0',
        ),
      ),
    ),
    'cmd' => 'channel-info',
  ),
  1 => 
  array (
    'info' => 
    array (
      'caption' => 'Server Capabilities',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'logintest',
          3 => '',
        ),
        1 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.listLatestReleases',
          3 => '',
        ),
        2 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.listAll',
          3 => '',
        ),
        3 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.info',
          3 => '',
        ),
        4 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.getDownloadURL',
          3 => '',
        ),
        5 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'channel.listAll',
          3 => '',
        ),
        6 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'channel.update',
          3 => '',
        ),
        7 => 
        array (
          0 => 'soap',
          1 => '1.0',
          2 => 'package.listLatestReleases',
          3 => '',
        ),
        8 => 
        array (
          0 => 'soap',
          1 => '1.0',
          2 => 'package.listAll',
          3 => '',
        ),
      ),
      'headline' => 
      array (
        0 => 'Type',
        1 => 'Version',
        2 => 'Function Name',
        3 => 'URI',
      ),
    ),
    'cmd' => 'no command',
  ),
  2 => 
  array (
    'info' => 
    array (
      'caption' => 'Channel froo Mirrors:',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => 'poor.php.net',
        ),
      ),
    ),
    'cmd' => 'no command',
  ),
  3 => 
  array (
    'info' => 
    array (
      'caption' => 'Mirror poor.php.net Capabilities',
      'border' => true,
      'data' => 
      array (
        0 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'logintest',
          3 => '',
        ),
        1 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.listLatestReleases',
          3 => '',
        ),
        2 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.listAll',
          3 => '',
        ),
        3 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.info',
          3 => '',
        ),
        4 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'package.getDownloadURL',
          3 => '',
        ),
        5 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'channel.listAll',
          3 => '',
        ),
        6 => 
        array (
          0 => 'xmlrpc',
          1 => '1.0',
          2 => 'channel.update',
          3 => '',
        ),
        7 => 
        array (
          0 => 'soap',
          1 => '1.0',
          2 => 'package.listLatestReleases',
          3 => '',
        ),
        8 => 
        array (
          0 => 'soap',
          1 => '1.0',
          2 => 'package.listAll',
          3 => '',
        ),
      ),
      'headline' => 
      array (
        0 => 'Type',
        1 => 'Version',
        2 => 'Function Name',
        3 => 'URI',
      ),
    ),
    'cmd' => 'no command',
  ),
), $fakelog->getLog(), 'log 1');
echo 'tests done';
?>
--EXPECT--
tests done