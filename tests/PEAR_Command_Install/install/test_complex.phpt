--TEST--
install command, complex test
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
$ch = new PEAR_ChannelFile;
$ch->setName('smork');
$ch->setSummary('smork');
$ch->setDefaultPEARProtocols();
$reg = &$config->getRegistry();
$phpunit->assertTrue($reg->addChannel($ch), 'smork setup');
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'package2.xml';
$pathtobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Bar-1.5.2.tgz';
$pathtofoobarxml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Foobar-1.5.0a1.tgz';
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Bar-1.5.2.tgz', $pathtobarxml);
$GLOBALS['pearweb']->addHtmlConfig('http://www.example.com/Foobar-1.5.0a1.tgz', $pathtofoobarxml);
$GLOBALS['pearweb']->addXmlrpcConfig('pear.php.net', 'package.getDepDownloadURL',
    array('2.0',
         array('name' => 'Bar', 'channel' => 'pear.php.net', 'min' => '1.0.0'),
         array('channel' => 'pear.php.net', 'package' => 'PEAR1', 'version' => '1.5.0a1'), 'alpha'),
    array('version' => '1.5.2',
          'info' =>
          array(
            'package' => 'Bar',
            'channel' => 'pear.php.net',
            'license' => 'PHP License',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'stable',
            'deps' =>
            array(
                'required' =>
                array(
                    'php' =>
                    array(
                        'min' => '4.3.6',
                        'max' => '6.0.0',
                    ),
                    'pearinstaller' =>
                    array(
                        'min' => '1.4.0a1',
                    ),
                    'package' =>
                    array(
                        'name' => 'Foobar',
                        'channel' => 'smork',
                    ),
                ),
            ),
          ),
          'url' => 'http://www.example.com/Bar-1.5.2'));
$GLOBALS['pearweb']->addXmlrpcConfig('smork', 'package.getDepDownloadURL',
    array('2.0',
         array('name' => 'Foobar', 'channel' => 'smork'),
         array('channel' => 'pear.php.net', 'package' => 'Bar', 'version' => '1.5.2'), 'alpha'),
    array('version' => '1.5.0a1',
          'info' =>
          array(
            'package' => 'Foobar',
            'channel' => 'smork',
            'license' => 'PHP License',
            'summary' => 'test',
            'description' => 'test',
            'releasedate' => '2003-12-06 00:26:42',
            'state' => 'alpha',
          ),
          'url' => 'http://www.example.com/Foobar-1.5.0a1'));
$_test_dep->setPHPVersion('4.3.11');
$_test_dep->setPEARVersion('1.4.0a1');
$config->set('preferred_state', 'alpha');
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertNoErrors('after install');
$phpunit->assertTrue($res, 'result');
$dl = &$command->getDownloader();
if (OS_WINDOWS) {
    $phpunit->assertEquals(array (
      0 => 
      array (
        0 => 3,
        1 => '+ tmp dir created at ' . $dl->getDownloadDir(),
      ),
      1 => 
      array (
        0 => 1,
        1 => 'downloading Bar-1.5.2.tgz ...',
      ),
      2 => 
      array (
        0 => 1,
        1 => 'Starting to download Bar-1.5.2.tgz (2,212 bytes)',
      ),
      3 => 
      array (
        0 => 1,
        1 => '.',
      ),
      4 => 
      array (
        0 => 1,
        1 => '...done: 2,212 bytes',
      ),
      5 => 
      array (
        0 => 1,
        1 => 'downloading Foobar-1.5.0a1.tgz ...',
      ),
      6 => 
      array (
        0 => 1,
        1 => 'Starting to download Foobar-1.5.0a1.tgz (2,207 bytes)',
      ),
      7 => 
      array (
        0 => 1,
        1 => '...done: 2,207 bytes',
      ),
      8 => 
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . DIRECTORY_SEPARATOR . 'Foobar-1.5.0a1'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      9 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      10 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ',
      ),
      11 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      12 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      13 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      14 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      15 => 
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://smork/Foobar-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
      16 => 
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . ''  . DIRECTORY_SEPARATOR . 'Bar-1.5.2'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      17 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      18 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ',
      ),
      19 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      20 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      21 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      22 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      23 => 
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/Bar-1.5.2',
        ),
        'cmd' => 'install',
      ),
      24 => 
      array (
        0 => 3,
        1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      25 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ',
      ),
      26 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      27 => 
      array (
        0 => 2,
        1 => 'about to commit 2 file operations',
      ),
      28 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php',
      ),
      29 => 
      array (
        0 => 2,
        1 => 'successfully committed 2 file operations',
      ),
      30 => 
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/PEAR1-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
    ), $fakelog->getLog(), 'log messages');
} else {
    $phpunit->assertEquals(array (
      0 => 
      array (
        0 => 3,
        1 => '+ tmp dir created at ' . $dl->getDownloadDir(),
      ),
      1 => 
      array (
        0 => 1,
        1 => 'downloading Bar-1.5.2.tgz ...',
      ),
      2 => 
      array (
        0 => 1,
        1 => 'Starting to download Bar-1.5.2.tgz (2,212 bytes)',
      ),
      3 => 
      array (
        0 => 1,
        1 => '.',
      ),
      4 => 
      array (
        0 => 1,
        1 => '...done: 2,212 bytes',
      ),
      5 => 
      array (
        0 => 1,
        1 => 'downloading Foobar-1.5.0a1.tgz ...',
      ),
      6 => 
      array (
        0 => 1,
        1 => 'Starting to download Foobar-1.5.0a1.tgz (2,207 bytes)',
      ),
      7 => 
      array (
        0 => 1,
        1 => '...done: 2,207 bytes',
      ),
      8 => 
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . DIRECTORY_SEPARATOR . 'Foobar-1.5.0a1'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      9 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      10 => 
      array (
        0 => 3,
        1 => 'adding to transaction: chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      11 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ',
      ),
      12 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      13 => 
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      14 => 
      array (
        0 => 3,
        1 => '+ chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php',
      ),
      15 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo12.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo12.php',
      ),
      16 => 
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      17=> 
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://smork/Foobar-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
      18 => 
      array (
        0 => 3,
        1 => '+ cp ' . str_replace('\\\\', '\\', $dl->getDownloadDir()) . ''  . DIRECTORY_SEPARATOR . 'Bar-1.5.2'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      19 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      20 => 
      array (
        0 => 3,
        1 => 'adding to transaction: chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      21 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ',
      ),
      22 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      23 => 
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      24 => 
      array (
        0 => 3,
        1 => '+ chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php',
      ),
      25 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo1.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo1.php',
      ),
      26 => 
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      27 => 
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/Bar-1.5.2',
        ),
        'cmd' => 'install',
      ),
      28 => 
      array (
        0 => 3,
        1 => '+ cp ' . dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      29 => 
      array (
        0 => 3,
        1 => 'adding to transaction: chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      30 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ',
      ),
      31 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php '  . DIRECTORY_SEPARATOR . '',
      ),
      32 => 
      array (
        0 => 2,
        1 => 'about to commit 3 file operations',
      ),
      33 => 
      array (
        0 => 3,
        1 => '+ chmod 644 ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php',
      ),
      34 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . '.tmpfoo.php ' . $temp_path . DIRECTORY_SEPARATOR . 'php'  . DIRECTORY_SEPARATOR . 'foo.php',
      ),
      35 => 
      array (
        0 => 2,
        1 => 'successfully committed 3 file operations',
      ),
      36 => 
      array (
        'info' => 
        array (
          'data' => 'install ok: channel://pear.php.net/PEAR1-1.5.0a1',
        ),
        'cmd' => 'install',
      ),
    ), $fakelog->getLog(), 'log messages');
}
echo 'tests done';
?>
--EXPECT--
tests done
