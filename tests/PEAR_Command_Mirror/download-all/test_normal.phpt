--TEST--
download-all command
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
$ch = new PEAR_ChannelFile;
$ch->setName('smoog');
$ch->setDefaultPEARProtocols();
$ch->setSummary('smoog');
$pathtoStableAPC = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'APC-1.3.0.tgz';
$pathtoAlphaAPC = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'APC-1.4.0a1.tgz';
$pathtoSmoogAPC = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'APC-1.5.0a1.tgz';
$pathtoAT = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'Archive_Tar-1.5.0a1.tgz';
$pearweb->addHtmlConfig('http://pear.php.net/get/APC-1.3.0.tgz', $pathtoStableAPC);
$pearweb->addHtmlConfig('http://pear.php.net/get/APC-1.4.0a1.tgz', $pathtoAlphaAPC);
$pearweb->addHtmlConfig('http://pear.php.net/get/Archive_Tar-1.5.0a1.tgz', $pathtoAT);
$pearweb->addHtmlConfig('http://smoog/get/APC-1.5.0a1.tgz', $pathtoSmoogAPC);
$pearweb->addXmlrpcConfig("smoog", "package.listAll",     array(true,true,true),     array(
    'APC' =>
        array(
        'packageid' =>
            "220",
        'categoryid' =>
            "3",
        'category' =>
            "Caching",
        'license' =>
            "PHP",
        'summary' =>
            "Alternative PHP Cache",
        'description' =>
            "APC is the Alternative PHP Cache. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.",
        'lead' =>
            "rasmus",
        'stable' =>
            "2.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    ));
$pearweb->addXmlrpcConfig("pear.php.net", "package.listAll",     array(true,true,true),     array(
    'APC' =>
        array(
        'packageid' =>
            "220",
        'categoryid' =>
            "3",
        'category' =>
            "Caching",
        'license' =>
            "PHP",
        'summary' =>
            "Alternative PHP Cache",
        'description' =>
            "APC is the Alternative PHP Cache. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.",
        'lead' =>
            "rasmus",
        'stable' =>
            "2.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Archive_Tar' =>
        array(
        'packageid' =>
            "24",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License",
        'summary' =>
            "Tar file management class",
        'description' =>
            "This class provides handling of tar files in PHP.
It supports creating, listing, extracting and adding to tar files.
Gzip support is available if PHP has the zlib extension built-in or
loaded. Bz2 compression is also supported with the bz2 extension loaded.",
        'lead' =>
            "vblavet",
        'stable' =>
            "1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
      )
);
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'APC',
    'channel' => 'pear.php.net',
  ),
  1 => 'stable',
), array (
  'version' => '1.3.0',
  'info' => 
  array (
    'id' => '1525',
    'doneby' => 'cellog',
    'license' => '',
    'summary' => '',
    'description' => '',
    'releasedate' => '2004-06-27 03:54:00',
    'releasenotes' => 'hi',
    'state' => 'stable',
    'package' => 'APC',
    'channel' => 'pear.php.net',
  ),
  'url' => 'http://pear.php.net/get/APC-1.3.0',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'Archive_Tar',
    'channel' => 'pear.php.net',
  ),
  1 => 'stable',
), array (
  'version' => '1.5.0a1',
  'info' => 
  array (
    'id' => '1525',
    'doneby' => 'cellog',
    'license' => '',
    'summary' => '',
    'description' => '',
    'releasedate' => '2004-06-27 03:54:00',
    'releasenotes' => 'hi',
    'state' => 'alpha',
    'package' => 'Archive_Tar',
    'channel' => 'pear.php.net',
  ),
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'Archive_Tar',
    'channel' => 'pear.php.net',
    'version' => '1.5.0a1',
  ),
  1 => 'stable',
), array (
  'version' => '1.5.0a1',
  'info' => 
  array (
    'id' => '1525',
    'doneby' => 'cellog',
    'license' => '',
    'summary' => '',
    'description' => '',
    'releasedate' => '2004-06-27 03:54:00',
    'releasenotes' => 'hi',
    'state' => 'alpha',
    'package' => 'Archive_Tar',
    'channel' => 'pear.php.net',
  ),
  'url' => 'http://pear.php.net/get/Archive_Tar-1.5.0a1',
));

$pearweb->addXmlrpcConfig("smoog", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'APC',
    'channel' => 'smoog',
  ),
  1 => 'stable',
), array (
  'version' => '1.5.0a1',
  'info' => 
  array (
    'id' => '1525',
    'doneby' => 'cellog',
    'license' => '',
    'summary' => '',
    'description' => '',
    'releasedate' => '2004-06-27 03:54:00',
    'releasenotes' => 'hi',
    'state' => 'alpha',
    'package' => 'APC',
    'channel' => 'pear.php.net',
  ),
));
$pearweb->addXmlrpcConfig("smoog", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'APC',
    'channel' => 'smoog',
    'version' => '1.5.0a1',
  ),
  1 => 'stable',
), array (
  'version' => '1.5.0a1',
  'info' => 
  array (
    'id' => '1525',
    'doneby' => 'cellog',
    'license' => '',
    'summary' => '',
    'description' => '',
    'releasedate' => '2004-06-27 03:54:00',
    'releasenotes' => 'hi',
    'state' => 'alpha',
    'package' => 'APC',
    'channel' => 'smoog',
  ),
  'url' => 'http://smoog/get/Archive_Tar-1.5.0a1',
));
$config->set('preferred_state', 'stable');
$save = getcwd();
chdir($temp_path);
$e = $command->run('download-all', array(), array());
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'Using Channel pear.php.net',
    'cmd' => 'no command',
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading APC-1.3.0.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download APC-1.3.0.tgz (516 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.',
  ),
  4 => 
  array (
    0 => 1,
    1 => '...done: 516 bytes',
  ),
  5 => 
  array (
    'info' => 'File ' . $temp_path . DIRECTORY_SEPARATOR . 'APC-1.3.0.tgz downloaded',
    'cmd' => 'download',
  ),
), $fakelog->getLog(), 'log');
$phpunit->assertFileExists($temp_path . DIRECTORY_SEPARATOR . 'APC-1.3.0.tgz', 'APC 1.3.0');
chdir($save);
echo 'tests done';
?>
--EXPECT--
tests done
