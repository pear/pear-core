--TEST--
download-all command, preferred_state = alpha
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
$pearweb->addXmlrpcConfig("smoog", "package.listAll",     array(true,true,false),     array(
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
$pearweb->addXmlrpcConfig("pear.php.net", "package.listAll",     array(true,false,false),     array(
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
            '<?xml version="1.0" encoding="ISO-8859-1"?>
<!--DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0"-->
<package version="1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/package-1.0 http://pear.php.net/dtd/package-1.0.xsd">
 <name>APC</name>
 <summary>test</summary>
 <description>test</description>
 <maintainers>
  <maintainer>
   <user>cellog</user>
   <role>lead</role>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
  </maintainer>
 </maintainers>
 <release>
  <version>1.3.0</version>
  <date>2004-10-21</date>
  <license>PHP License</license>
  <state>stable</state>
  <notes>
Installer Roles/Tasks:
  </notes>
  <filelist>
   <dir name="/">
    <file name="foo12.php" role="php"/>
   </dir>
  </filelist>
 </release>
</package>
',
  'url' => 'http://pear.php.net/get/APC-1.3.0',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'APC',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
), array (
  'version' => '1.4.0a1',
  'info' => 
            '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0a1">
 <name>APC</name>       
 <summary>APC pear</summary>
 <description>APC pear
 </description>
 <maintainers>
  <maintainer>
   <user>cellog</user>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
   <role>lead</role>
  </maintainer>
  </maintainers>
 <release>
  <version>1.4.0a1</version>
  <date>2004-12-31</date>
  <license>PHP License</license>
  <state>alpha</state>
  <notes>hi
  </notes>
  <filelist>
   <file role="php" md5sum="ed0384ad29e60110b310a02e95287ee6" name="foo.php"/>
  </filelist>
 </release>
</package>',
  'url' => 'http://pear.php.net/get/APC-1.4.0a1',
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
          '<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0 http://pear.php.net/dtd/tasks-1.0.xsd http://pear.php.net/dtd/package-2.0 http://pear.php.net/dtd/package-2.0.xsd">
 <name>Archive_Tar</name>
 <channel>pear.php.net</channel>
 <summary>Archive_Tar</summary>
 <description>Archive_Tar</description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-12-31</date>
 <time>18:59:24</time>
 <version>
  <release>1.5.0a1</release>
  <api>1.4.0</api>
 </version>
 <stability>
  <release>alpha</release>
  <api>alpha</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>stuff</notes>
 <contents>
  <dir name="/">
   <file md5sum="ed0384ad29e60110b310a02e95287ee6" name="foo.php" role="php" />
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
</package>',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'Archive_Tar',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
), array (
  'version' => '1.5.0a1',
  'info' => 
          '<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0 http://pear.php.net/dtd/tasks-1.0.xsd http://pear.php.net/dtd/package-2.0 http://pear.php.net/dtd/package-2.0.xsd">
 <name>Archive_Tar</name>
 <channel>pear.php.net</channel>
 <summary>Archive_Tar</summary>
 <description>Archive_Tar</description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-12-31</date>
 <time>18:59:24</time>
 <version>
  <release>1.5.0a1</release>
  <api>1.4.0</api>
 </version>
 <stability>
  <release>alpha</release>
  <api>alpha</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>stuff</notes>
 <contents>
  <dir name="/">
   <file md5sum="ed0384ad29e60110b310a02e95287ee6" name="foo.php" role="php" />
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
</package>',
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
  '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>APC</name>
 <channel>smoog</channel>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 </description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.5.0a1</release>
  <api>1.4.0</api>
 </version>
 <stability>
  <release>alpha</release>
  <api>alpha</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>Installer Roles/Tasks:
 </notes>
 <contents>
  <dir name="/">
   <file name="template.spec" role="data" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease/>
</package>',
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
  '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>APC</name>
 <channel>smoog</channel>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 </description>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <date>2004-09-30</date>
 <version>
  <release>1.5.0a1</release>
  <api>1.4.0</api>
 </version>
 <stability>
  <release>alpha</release>
  <api>alpha</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>Installer Roles/Tasks:
 </notes>
 <contents>
  <dir name="/">
   <file name="template.spec" role="data" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease/>
</package>',
  'url' => 'http://smoog/get/Archive_Tar-1.5.0a1',
));
$config->set('preferred_state', 'alpha');
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
    'info' => 'Using Preferred State of alpha',
    'cmd' => 'no command',
  ),
  2 => 
  array (
    'info' => 'Gathering release information, please wait...',
    'cmd' => 'no command',
  ),
  array (
    0 => 3,
    1 => 'Downloading "http://pear.php.net/get/APC-1.4.0a1.tgz"',
  ),
  array (
    0 => 1,
    1 => 'downloading APC-1.4.0a1.tgz ...',
  ),
  array (
    0 => 1,
    1 => 'Starting to download APC-1.4.0a1.tgz (514 bytes)',
  ),
  array (
    0 => 1,
    1 => '.',
  ),
  array (
    0 => 1,
    1 => '...done: 514 bytes',
  ),
  array (
    0 => 3,
    1 => 'Downloading "http://pear.php.net/get/Archive_Tar-1.5.0a1.tgz"',
  ),
  array (
    0 => 1,
    1 => 'downloading Archive_Tar-1.5.0a1.tgz ...',
  ),
  array (
    0 => 1,
    1 => 'Starting to download Archive_Tar-1.5.0a1.tgz (687 bytes)',
  ),
  array (
    0 => 1,
    1 => '...done: 687 bytes',
  ),
  array (
    'info' => 'File ' . $temp_path . DIRECTORY_SEPARATOR . 'APC-1.4.0a1.tgz downloaded',
    'cmd' => 'download',
  ),
  array (
    'info' => 'File ' . $temp_path . DIRECTORY_SEPARATOR . 'Archive_Tar-1.5.0a1.tgz downloaded',
    'cmd' => 'download',
  ),
), $fakelog->getLog(), 'log');
$phpunit->assertFileExists($temp_path . DIRECTORY_SEPARATOR . 'APC-1.4.0a1.tgz', 'APC 1.4.0a1');
$phpunit->assertFileExists($temp_path . DIRECTORY_SEPARATOR . 'Archive_Tar-1.5.0a1.tgz', 'Archive_Tar 1.5.0a1');
chdir($save);
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
