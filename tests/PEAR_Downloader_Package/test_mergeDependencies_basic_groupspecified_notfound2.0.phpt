--TEST--
PEAR_Downloader_Package->detectDependencies() dependency group specified, but not found
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$mainpackage = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'main-1.0.tgz';
$sub1package = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'sub1-1.1.tgz';
$sub2package = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_mergeDependencies'. DIRECTORY_SEPARATOR . 'sub2-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/main-1.0.tgz', $mainpackage);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/sub1-1.0.tgz', $sub1package);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/sub2-1.0.tgz', $sub2package);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDownloadURL',
    array(array('package' => 'main', 'channel' => 'pear.php.net', 'group' => 'foo'), 'stable'),
    array('version' => '1.0',
          'info' =>
'<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>main</name>
 <channel>pear.php.net</channel>
 <summary>test</summary>
 <description>foo
hi there
 
 </description>
 <lead>
  <name>person</name>
  <user>single</user>
  <email>joe@example.com</email>
  <active>yes</active>
 </lead>
 <date>2004-12-10</date>
 <time>21:39:43</time>
 <version>
  <release>1.0</release>
  <api>1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>here are the
multi-line
release notes
  
 </notes>
 <contents>
  <dir name="/">
   <dir name="sunger">
    <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.dat" role="data">
     <tasks:replace from="@pv@" to="version" type="package-info" />
    </file>
   </dir> <!-- //sunger -->
   <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.php" role="php">
    <tasks:replace from="@pv@" to="version" type="package-info" />
   </file>
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.3.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
  <group name="test" hint="testing group">
   <package>
    <name>sub1</name>
    <channel>pear.php.net</channel>
    <min>1.1</min>
   </package>
   <package>
    <name>sub2</name>
    <channel>pear.php.net</channel>
    <min>1.1</min>
   </package>
  </group>
 </dependencies>
 <phprelease>
  <installconditions>
   <os>
    <name>*</name>
   </os>
  </installconditions>
  <filelist>
   <install as="merbl.dat" name="sunger/foo.dat" />
   <install as="merbl.php" name="foo.php" />
  </filelist>
 </phprelease>
</package>',
          'url' => 'http://www.example.com/main-1.0'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'sub1', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    array('version' => '1.1',
          'info' =>
'<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>sub1</name>
 <channel>pear.php.net</channel>
 <summary>Subgroup Package 1</summary>
 <description>Subgroup Package 1</description>
 <lead>
  <name>person</name>
  <user>single</user>
  <email>joe@example.com</email>
  <active>yes</active>
 </lead>
 <date>2004-12-10</date>
 <time>21:39:43</time>
 <version>
  <release>1.1</release>
  <api>1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>here are the
multi-line
release notes
  
 </notes>
 <contents>
  <dir name="/">
   <dir name="sunger">
    <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.dat" role="data">
     <tasks:replace from="@pv@" to="version" type="package-info" />
    </file>
   </dir> <!-- //sunger -->
   <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.php" role="php">
    <tasks:replace from="@pv@" to="version" type="package-info" />
   </file>
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.3.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a1</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease>
  <installconditions>
   <os>
    <name>*</name>
   </os>
  </installconditions>
  <filelist>
   <install as="merbl.dat" name="sunger/foo.dat" />
   <install as="merbl.php" name="foo.php" />
  </filelist>
 </phprelease>
</package>',
          'url' => 'http://www.example.com/sub1-1.1'));
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0', array('name' => 'sub2', 'channel' => 'pear.php.net', 'min' => '1.1'),
        array('channel' => 'pear.php.net', 'package' => 'main', 'version' => '1.0'), 'stable'),
    array('version' => '1.1',
          'info' =>
'<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>sub2</name>
 <channel>pear.php.net</channel>
 <summary>Subgroup Package 2</summary>
 <description>Subgroup Package 2</description>
 <lead>
  <name>person</name>
  <user>single</user>
  <email>joe@example.com</email>
  <active>yes</active>
 </lead>
 <date>2004-12-10</date>
 <time>21:39:43</time>
 <version>
  <release>1.1</release>
  <api>1.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/3_0.txt">PHP License</license>
 <notes>here are the
multi-line
release notes
  
 </notes>
 <contents>
  <dir name="/">
   <dir name="sunger">
    <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.dat" role="data">
     <tasks:replace from="@pv@" to="version" type="package-info" />
    </file>
   </dir> <!-- //sunger -->
   <file baseinstalldir="freeb" md5sum="8332264d2e0e3c3091ebd6d8cee5d3a3" name="foo.php" role="php">
    <tasks:replace from="@pv@" to="version" type="package-info" />
   </file>
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.3.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a1</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease>
  <installconditions>
   <os>
    <name>*</name>
   </os>
  </installconditions>
  <filelist>
   <install as="merbl.dat" name="sunger/foo.dat" />
   <install as="merbl.php" name="foo.php" />
  </filelist>
 </phprelease>
</package>',
          'url' => 'http://www.example.com/sub2-1.1.tgz'));
$dp = &newDownloaderPackage(array());
$result = $dp->initialize('main#foo');
$phpunit->assertNoErrors('after create 1');

$params = array(&$dp);
$dp->detectDependencies($params);
$phpunit->assertNoErrors('after detect');
$phpunit->assertEquals(array (
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->_downloader->getDownloadDir(),
  ),
  array (
    0 => 0,
    1 => 'Warning: package "pear/main" has no dependency group named "foo"',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download callback messages');
$phpunit->assertEquals(1, count($params), 'detectDependencies');
$result = PEAR_Downloader_Package::mergeDependencies($params);
$phpunit->assertNoErrors('after merge 1');
$phpunit->assertFalse($result, 'first return');
$phpunit->assertEquals(1, count($params), 'mergeDependencies');
$phpunit->assertEquals('main', $params[0]->getPackage(), 'main package');
echo 'tests done';
?>
--EXPECT--
tests done