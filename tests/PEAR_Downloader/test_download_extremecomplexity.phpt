--TEST--
PEAR_Downloader->download() with downloadable abstract package, extreme dependency complexity [stable]
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pkg1 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'pkg1-1.1.tgz';
$pkg2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'pkg2-1.1.tgz';
$pkg3 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'pkg3-1.1.tgz';
$pkg4 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'pkg4-1.1.tgz';
$pkg5 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'pkg5-1.1.tgz';
$pkg6 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'pkg6-1.1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/get/pkg1-1.1.tgz', $pkg1);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/get/pkg2-1.1.tgz', $pkg2);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/get/pkg3-1.1.tgz', $pkg3);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/get/pkg4-1.1.tgz', $pkg4);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/get/pkg5-1.1.tgz', $pkg5);
$GLOBALS['pearweb']->addHtmlConfig('http://pear.php.net/get/pkg6-1.1.tgz', $pkg6);
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'pkg1',
    'channel' => 'pear.php.net',
  ),
  1 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg1</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">pkg2</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="grob" name="zoorb.php"/>
      <file role="php" baseinstalldir="grob" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="grob" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg1-1.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'pkg2',
    'channel' => 'pear.php.net',
    'package' => 'pkg2',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'pkg1',
    'version' => '1.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg2</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">pkg3</dep>
      <dep type="php" rel="ge" version="1.0"/>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="borg" name="zoorb.php"/>
      <file role="php" baseinstalldir="borg" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="borg" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg2-1.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'pkg3',
    'channel' => 'pear.php.net',
    'package' => 'pkg3',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'pkg2',
    'version' => '1.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg3</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">pkg4</dep>
      <dep type="pkg" rel="ge" version="1.0">pkg5</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="borger" name="zoorb.php"/>
      <file role="php" baseinstalldir="borger" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="borger" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg3-1.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'pkg4',
    'channel' => 'pear.php.net',
    'package' => 'pkg4',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'pkg3',
    'version' => '1.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg4</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">pkg6</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="snarf" name="zoorb.php"/>
      <file role="php" baseinstalldir="snarf" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="snarf" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg4-1.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'pkg5',
    'channel' => 'pear.php.net',
    'package' => 'pkg5',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'pkg3',
    'version' => '1.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg5</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">pkg6</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="barf" name="zoorb.php"/>
      <file role="php" baseinstalldir="barf" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="barf" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg5-1.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'pkg6',
    'channel' => 'pear.php.net',
    'package' => 'pkg6',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'pkg4',
    'version' => '1.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg6</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <filelist>
      <file role="php" baseinstalldir="groob" name="zoorb.php"/>
      <file role="php" baseinstalldir="groob" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="groob" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg6-1.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'pkg6',
    'channel' => 'pear.php.net',
    'package' => 'pkg6',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'pkg5',
    'version' => '1.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>pkg6</name>
  <summary>required test for PEAR_Installer</summary>
  <description>fake package</description>
  <maintainers>
    <maintainer>
      <user>fakeuser</user>
      <name>Joe Shmoe</name>
      <email>nobody@example.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-09-09</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>required dependency test</notes>
    <filelist>
      <file role="php" baseinstalldir="groob" name="zoorb.php"/>
      <file role="php" baseinstalldir="groob" name="goompness\\oggbrzitzkee.php"/>
      <file role="php" baseinstalldir="groob" name="goompness\\Mopreeb.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/pkg6-1.1',
));

$_test_dep->setPHPversion('4.3.10');
$dp = &new test_PEAR_Downloader($fakelog, array('alldeps' => true), $config);
$phpunit->assertNoErrors('after create');
$reg = &$config->getRegistry();
$result = $dp->download(array('pkg1'));
$phpunit->assertEquals(6, count($result), 'return');
$dlpackages = $dp->getDownloadedPackages();
$phpunit->assertEquals(6, count($dlpackages), 'downloaded packages count');

$dd_dir = $dp->getDownloadDir();

if (!empty($dd_dir) && is_dir($dd_dir)) {
    $phpunit->assertEquals(array (
  0 => 
  array (
    0 => 1,
    1 => 'downloading pkg1-1.1.tgz ...',
  ),
  1 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg1-1.1.tgz (700 bytes)',
  ),
  2 => 
  array (
    0 => 1,
    1 => '.'
  ),
  3 => 
  array (
    0 => 1,
    1 => '...done: 700 bytes',
  ),
  4 => 
  array (
    0 => 1,
    1 => 'downloading pkg2-1.1.tgz ...',
  ),
  5 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg2-1.1.tgz (704 bytes)',
  ),
  6 => 
  array (
    0 => 1,
    1 => '...done: 704 bytes',
  ),
  7 => 
  array (
    0 => 1,
    1 => 'downloading pkg3-1.1.tgz ...',
  ),
  8 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg3-1.1.tgz (714 bytes)',
  ),
  9 => 
  array (
    0 => 1,
    1 => '...done: 714 bytes',
  ),
  10 => 
  array (
    0 => 1,
    1 => 'downloading pkg4-1.1.tgz ...',
  ),
  11 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg4-1.1.tgz (702 bytes)',
  ),
  12 => 
  array (
    0 => 1,
    1 => '...done: 702 bytes',
  ),
  13 => 
  array (
    0 => 1,
    1 => 'downloading pkg5-1.1.tgz ...',
  ),
  14 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg5-1.1.tgz (706 bytes)',
  ),
  15 => 
  array (
    0 => 1,
    1 => '...done: 706 bytes',
  ),
  16 => 
  array (
    0 => 1,
    1 => 'downloading pkg6-1.1.tgz ...',
  ),
  17 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg6-1.1.tgz (673 bytes)',
  ),
  18 => 
  array (
    0 => 1,
    1 => '...done: 673 bytes',
   ),
), $fakelog->getLog(), 'log messages');
} else {
    $phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading pkg1-1.1.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg1-1.1.tgz (700 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.'
  ),
  4 => 
  array (
    0 => 1,
    1 => '...done: 700 bytes',
  ),
  5 => 
  array (
    0 => 1,
    1 => 'downloading pkg2-1.1.tgz ...',
  ),
  6 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg2-1.1.tgz (704 bytes)',
  ),
  7 => 
  array (
    0 => 1,
    1 => '...done: 704 bytes',
  ),
  8 => 
  array (
    0 => 1,
    1 => 'downloading pkg3-1.1.tgz ...',
  ),
  9 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg3-1.1.tgz (714 bytes)',
  ),
  10 => 
  array (
    0 => 1,
    1 => '...done: 714 bytes',
  ),
  11 => 
  array (
    0 => 1,
    1 => 'downloading pkg4-1.1.tgz ...',
  ),
  12 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg4-1.1.tgz (702 bytes)',
  ),
  13 => 
  array (
    0 => 1,
    1 => '...done: 702 bytes',
  ),
  14 => 
  array (
    0 => 1,
    1 => 'downloading pkg5-1.1.tgz ...',
  ),
  15 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg5-1.1.tgz (706 bytes)',
  ),
  16 => 
  array (
    0 => 1,
    1 => '...done: 706 bytes',
  ),
  17 => 
  array (
    0 => 1,
    1 => 'downloading pkg6-1.1.tgz ...',
  ),
  18 => 
  array (
    0 => 1,
    1 => 'Starting to download pkg6-1.1.tgz (673 bytes)',
  ),
  19 => 
  array (
    0 => 1,
    1 => '...done: 673 bytes',
   ),
), $fakelog->getLog(), 'log messages');
}

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
