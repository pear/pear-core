--TEST--
PEAR_Channelfile
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
include "PEAR/Channelfile.php";
require_once 'PEAR/ErrorStack.php';
function postprocess($string)
{
    return str_replace("\r", "\n", str_replace("\r\n", "\n", $string));
}
function logStack($err)
{
    echo "caught Error Stack error:\n";
    echo $err['message'] . "\n";
    echo 'code : ' . $err['code'] . "\n";
}
PEAR_ErrorStack::setDefaultCallback('logStack');
$chf = new PEAR_ChannelFile;
$chf->fromXmlString($first = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
  </xmlrpc>
 </protocols>
</channel>');

echo "after parsing\n";
if (!$chf->validate()) {
    echo "test default failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    if (var_export($chf->toArray(), true) !=
    var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
    )),
  ),
), true))
    {
        echo "Parsed array of default is not correct\n";
        var_export($chf->toArray());
        echo "Expected\n";
            var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
)));
    } // if toArray() doesn't match

    if (var_export($chf->toXml(), true) !=
          var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
  </xmlrpc>
 </protocols>
</channel>
'), true)) {
        echo "Re-generated XML of default is not correct\n";
        var_export($chf->toXml());
    } // if toXml() doesn't match
}
$chf->fromXmlString($chf->toXml());

echo "after re-parsing\n";
if (!$chf->validate()) {
    echo "test default failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    if (var_export($chf->toArray(), true) !=
    var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
), true))
    {
        echo "Parsed re-generated array of default is not correct\n";
        var_export($chf->toArray());
        echo "Expected\n";
            var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
    ),)
  ),
));
    } // if toArray() doesn't match
}
echo "test compatibility\n";
$chf->fromXmlString($first = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
  </xmlrpc>
 </protocols>
</channel>');

echo "after parsing (compatibility)\n";
if (!$chf->validate()) {
    echo "test default failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    if (var_export($chf->toArray(), true) !=
    var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
), true))
    {
        echo "Parsed array of default is not correct (compatibility)\n";
        var_export($chf->toArray());
        echo "Expected\n";
            var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
));
    } // if toArray() doesn't match

    if (var_export($chf->toXml(), true) !=
          var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
  </xmlrpc>
 </protocols>
</channel>
'), true)) {
        echo "Re-generated XML of default is not correct (compatibility)\n";
        var_export($chf->toXml());
    } // if toXml() doesn't match
}
$chf->fromXmlString($chf->toXml());

echo "after re-parsing\n";
if (!$chf->validate()) {
    echo "test default failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    if (var_export($chf->toArray(), true) !=
    var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
), true))
    {
        echo "Parsed re-generated array of default is not correct (compatibility)\n";
        var_export($chf->toArray());
        echo "Expected\n";
            var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
));
    } // if toArray() doesn't match
}
echo "\ntest add validatepackage\n";
$chf->setValidationPackage('PEAR_Validate', '1.0');
if (var_export($chf->toXml(), true) != var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
  </xmlrpc>
 </protocols>
</channel>
'), true)) {
    echo "add validatepackage did not match\n";
    var_dump(var_export($chf->toXml(), true));
    echo "Expected\n";
    var_dump(var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
  </xmlrpc>
 </protocols>
</channel>
'), true));
} // if validatepackage xml is wrong
$chf->fromXmlString($chf->toXml());

echo "after re-parsing\n";
if (!$chf->validate()) {
    echo "re-parse validatepackage failed\n";
    var_export($chf->toArray());
    var_export($chf->toXml());
} else {
    if (var_export($chf->toArray(), true) !=
    var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'validatepackage' =>
  array (
    'version' => '1.0',
    'name' => 'PEAR_Validate',
  ),
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
), true))
    {
        echo "Parsed array of re-parsed validatepackage is not correct\n";
        var_export($chf->toArray());
        echo "Expected\n";
            var_export(array (
  'version' => '1.0',
  'name' => 'pear',
  'summary' => 'PHP Extension and Application Repository',
  'validatepackage' =>
  array (
    'version' => '1.0',
    'name' => 'PEAR_Validate',
  ),
  'protocols' => array('xmlrpc' => 
  array (
    'host' => 'pear.php.net',
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
  ),)
));
    } // if toArray() doesn't match
}
echo "\ntest add protocols\n";
$chf->addFunction('xmlrpc', '1.0', 'gronk.dothis');
$chf->addFunction('soap', '1.0', 'release.list');
$chf->toXml();
$chf->getErrors(true);
$chf->setServer('pear.php.net', 'soap');
$chf->getErrors(true);
if (var_export($chf->toXml(), true) != var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
</channel>
'), true)) {
    echo "After adding protocols, xml was incorrect\n";
    var_export($chf->toXml());
    echo "\nExpected\n";
    var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
</channel>
'), true);
} // if toXml() is wrong after adding protocols
$chf->fromXmlString($chf->toXml());

echo "after re-parsing\n";
if (var_export($chf->toXml(), true) != var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
</channel>
'), true)) {
    echo "After adding protocols, xml was incorrect\n";
    var_export($chf->toXml());
    echo "\nExpected\n";
    var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
</channel>
'), true);
} // if toXml() is wrong after adding protocols (re-parsing)

echo "\ntest add mirror\n";
$chf->addMirror('server', 'us2');
$chf->setServer('mirror.php.net', 'xmlrpc', 'us2');
$chf->addMirrorFunction('us2', 'xmlrpc', '1.0', 'package.listAll');
$chf->addMirrorFunction('us2', 'xmlrpc', '1.0', 'release.list');
if (var_export($chf->toXml(), true) != var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
 <mirrors>
  <mirror type="server" name="us2">
   <protocols>
    <xmlrpc host="mirror.php.net">
     <function version="1.0">package.listAll</function>
     <function version="1.0">release.list</function>
    </xmlrpc>
   </protocols>
  </mirror>
 </mirrors>
</channel>
'), true)) {
    echo "Wrong after adding mirror\n";
    var_export($chf->toXml());
    echo "\nExpecting\n";
    var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
 <mirrors>
  <mirror type="server" name="us2">
   <protocols>
    <xmlrpc host="mirror.php.net">
     <function version="1.0">package.listAll</function>
     <function version="1.0">release.list</function>
    </xmlrpc>
   </protocols>
  </mirror>
 </mirrors>
</channel>
'));
} // wrong xml mirror
$chf->fromXmlString($chf->toXml());

echo "after re-parsing\n";
if (var_export($chf->toXml(), true) != var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
 <mirrors>
  <mirror type="server" name="us2">
   <protocols>
    <xmlrpc host="mirror.php.net">
     <function version="1.0">package.listAll</function>
     <function version="1.0">release.list</function>
    </xmlrpc>
   </protocols>
  </mirror>
 </mirrors>
</channel>
'), true)) {
    echo "Wrong after adding mirror (re-parsing)\n";
    var_export($chf->toXml());
    echo "\nExpecting\n";
    var_export(postprocess('<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE channel SYSTEM "http://pear.php.net/dtd/channel-1.0.dtd">
<channel version="1.0">
 <name>pear</name>
 <summary>PHP Extension and Application Repository</summary>
 <validatepackage version="1.0">PEAR_Validate</validatepackage>
 <protocols>
  <xmlrpc host="pear.php.net">
   <function version="1.0">logintest</function>
   <function version="1.0">package.listLatestReleases</function>
   <function version="1.0">package.listAll</function>
   <function version="1.0">package.info</function>
   <function version="1.0">package.getDownloadURL</function>
   <function version="1.0">channel.listAll</function>
   <function version="1.0">channel.update</function>
   <function version="1.0">gronk.dothis</function>
  </xmlrpc>
  <soap host="pear.php.net">
   <function version="1.0">release.list</function>
  </soap>
 </protocols>
 <mirrors>
  <mirror type="server" name="us2">
   <protocols>
    <xmlrpc host="mirror.php.net">
     <function version="1.0">package.listAll</function>
     <function version="1.0">release.list</function>
    </xmlrpc>
   </protocols>
  </mirror>
 </mirrors>
</channel>
'));
} // wrong xml mirror (after re-parsing)
?>
--EXPECT--
after parsing
after re-parsing
test compatibility
after parsing (compatibility)
after re-parsing

test add validatepackage
after re-parsing

test add protocols
caught Error Stack error:
Missing channel server for soap protocol
code : 10
caught Error Stack error:
Cannot generate xml, contents are invalid
code : 23
after re-parsing

test add mirror
after re-parsing