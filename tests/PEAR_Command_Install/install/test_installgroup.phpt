--TEST--
install command, test pear install Installed#group where Installed is already installed
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'simplepackage.xml';
$cg = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Console_Getopt-1.2.tgz';
$pearweb->addRESTConfig("http://pear.php.net/rest/r/console_getopt/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Console_Getopt</p>
 <c>pear.php.net</c>
 <r><v>1.2</v><s>stable</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/p/console_getopt/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package"    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"    xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
 <n>Console_Getopt</n>
 <c>pear.php.net</c>
 <ca xlink:href="/rest/c/Console">Console</ca>
 <l>PHP License</l>
 <s>Command-line option parser</s>
 <d>This is a PHP implementation of &quot;getopt&quot; supporting both
short and long options.</d>
 <r xlink:href="/rest/r/console_getopt"/>
</p>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/console_getopt/1.2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/console_getopt">Console_Getopt</p>
 <c>pear.php.net</c>
 <v>1.2</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>andrei</m>
 <s>Command-line option parser</s>
 <d>This is a PHP implementation of &quot;getopt&quot; supporting both
short and long options.
</d>
 <da>2003-12-11 14:26:46</da>
 <n>Fix to preserve BC with 1.0 and allow correct behaviour for new users
</n>
 <f>3370</f>
 <g>http://pear.php.net/get/Console_Getopt-1.2</g>
 <x xlink:href="package.1.2.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/console_getopt/deps.1.2.txt", 'b:0;', 'text/xml');
$pearweb->addHTMLConfig('http://pear.php.net/get/Console_Getopt-1.2.tgz', $cg);
$_test_dep->setPHPVersion('5.2.1');
$_test_dep->setPEARVersion('1.5.1');
$reg = &$config->getRegistry();
$chan = &$reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$chan->setBaseURL('REST1.1', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'installed.xml';
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
$fakelog->getLog();
// install sub-group

$res = $command->run('install', array(), array($pathtopackagexml . '#test'));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
$phpunit->showAll();
$dl = &$command->getDownloader(1, array());
if (OS_WINDOWS) {
    $phpunit->assertEquals(array (
  0 =>
  array (
    0 => 1,
    1 => 'Skipping package "pear/Installed", already installed as version 1.4.0a1',
  ),
  1 =>
  array (
    0 => 3,
    1 => 'Downloading "http://pear.php.net/get/Console_Getopt-1.2.tgz"',
  ),
  2 =>
  array (
    0 => 1,
    1 => 'downloading Console_Getopt-1.2.tgz ...',
  ),
  3 =>
  array (
    0 => 1,
    1 => 'Starting to download Console_Getopt-1.2.tgz (3,371 bytes)',
  ),
  4 =>
  array (
    0 => 1,
    1 => '.',
  ),
  5 =>
  array (
    0 => 1,
    1 => '...done: 3,371 bytes',
  ),
  6 =>
  array (
    0 => 3,
    1 => 'adding to transaction: mkdir ' . $temp_path . '\\php\\Console',
  ),
  7 =>
  array (
    0 => 2,
    1 => '+ create dir ' . $temp_path . '\\php\\Console',
  ),
  8 =>
  array (
    0 => 3,
    1 => '+ mkdir ' . $temp_path . '\\php\\Console',
  ),
  9 =>
  array (
    0 => 3,
    1 => '+ cp ' . $temp_path . '\\tmp\\Console_Getopt-1.2\\Console\\Getopt.php ' . $temp_path . '\\php\\Console\\.tmpGetopt.php',
  ),
  10 =>
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '\\php\\Console\\Getopt.php',
  ),
  11 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '\\php\\Console\\.tmpGetopt.php ' . $temp_path . '\\php\\Console\\Getopt.php ',
  ),
  12 =>
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as Console/Getopt.php ' . $temp_path . '\\php\\Console\\Getopt.php ' . $temp_path . '\\php \\Console',
  ),
  13 =>
  array (
    0 => 2,
    1 => 'about to commit 3 file operations',
  ),
  14 =>
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '\\php\\Console\\.tmpGetopt.php ' . $temp_path . '\\php\\Console\\Getopt.php',
  ),
  15 =>
  array (
    0 => 2,
    1 => 'successfully committed 3 file operations',
  ),
  16 =>
  array (
    'info' =>
    array (
      'data' => 'install ok: channel://pear.php.net/Console_Getopt-1.2',
    ),
    'cmd' => 'install',
  ),
)
, $fakelog->getLog(), 'log');
} else {
    // Don't forget umask ! permission of new file is 0666
    $umask = decoct(0666 & ( 0777 - umask()));
    $phpunit->assertEquals(array (
  array (
    0 => 1,
    1 => 'Skipping package "pear/Installed", already installed as version 1.4.0a1',
  ),
  array (
    0 => 3,
    1 => 'Downloading "http://pear.php.net/get/Console_Getopt-1.2.tgz"',
  ),
  array (
    0 => 1,
    1 => 'downloading Console_Getopt-1.2.tgz ...',
  ),
  array (
    0 => 1,
    1 => 'Starting to download Console_Getopt-1.2.tgz (3,371 bytes)',
  ),
  array (
    0 => 1,
    1 => '.',
  ),
  array (
    0 => 1,
    1 => '...done: 3,371 bytes',
  ),
  array (
    0 => 3,
    1 => 'adding to transaction: mkdir ' . $temp_path . '/php/Console',
  ),
  array (
    0 => 2,
    1 => '+ create dir ' . $temp_path . '/php/Console',
  ),
  8 =>
  array (
    0 => 3,
    1 => '+ mkdir ' . $temp_path . '/php/Console',
  ),
  9 =>
  array (
    0 => 3,
    1 => '+ cp ' . $temp_path . '/tmp' . DIRECTORY_SEPARATOR . '*/Console_Getopt-1.2/Console/Getopt.php ' . $temp_path . '/php/Console/.tmpGetopt.php',
  ),
  10 =>
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/php/Console/Getopt.php',
  ),
  11 =>
  array (
    0 => 3,
    1 => 'adding to transaction: chmod '.$umask.' ' . $temp_path . '/php/Console/.tmpGetopt.php',
  ),
  12 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/php/Console/.tmpGetopt.php ' . $temp_path . '/php/Console/Getopt.php ',
  ),
  13 =>
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as Console/Getopt.php ' . $temp_path . '/php/Console/Getopt.php ' . $temp_path . '/php /Console',
  ),
  14 =>
  array (
    0 => 2,
    1 => 'about to commit 4 file operations for Console_Getopt',
  ),
  15 =>
  array (
    0 => 3,
    1 => '+ chmod '.$umask.' ' . $temp_path . '/php/Console/.tmpGetopt.php',
  ),
  16 =>
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/php/Console/.tmpGetopt.php ' . $temp_path . '/php/Console/Getopt.php',
  ),
  17 =>
  array (
    0 => 2,
    1 => 'successfully committed 4 file operations',
  ),
  18 =>
  array (
    'info' =>
    array (
      'data' => 'install ok: channel://pear.php.net/Console_Getopt-1.2',
    ),
    'cmd' => 'install',
  ),
)
, $fakelog->getLog(), 'log');
}
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
