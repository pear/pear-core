--TEST--
search command
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
$pf = new PEAR_PackageFile_v1;
$pf->setConfig($config);
$pf->setPackage('Archive_Tar');
$pf->setSummary('foo');
$pf->setDate(date('Y-m-d'));
$pf->setDescription('foo');
$pf->setVersion('1.0.0');
$pf->setState('stable');
$pf->setLicense('PHP License');
$pf->setNotes('foo');
$pf->addMaintainer('lead', 'cellog', 'Greg', 'cellog@php.net');
$pf->addFile('', 'foo.dat', array('role' => 'data'));
$pf->clearDeps();
$pf->validate();
$phpunit->assertNoErrors('setup');
$reg->addPackage2($pf);
$pearweb->addXmlrpcConfig("pear.php.net", "package.search",     array('Ar', false, true, true, true),     array(
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
$pearweb->addXmlrpcConfig("pear.php.net", "package.search",     array('XZ', false, true, true, true),     array(
    )
);
$e = $command->run('search', array(), array('Ar'));
$phpunit->assertNoErrors('after');
$phpunit->assertTrue($e, 'after');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'Matched packages, channel pear.php.net:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Stable/(Latest)',
        2 => 'Local',
      ),
      'channel' => 'pear.php.net',
      'data' => 
      array (
        'File Formats' => 
        array (
          0 => 
          array (
            0 => 'Archive_Tar',
            1 => '1.2',
            2 => '1.0.0',
            3 => 'Tar file management class',
          ),
        ),
      ),
    ),
    'cmd' => 'search',
  ),
), $fakelog->getLog(), 'log after');
$e = $command->run('search', array(), array('XZ'));
$phpunit->assertNoErrors('errors');
$phpunit->assertEquals(array (
  0 => 
   array (
    'info' => 'no packages found that match pattern "XZ", for channel pear.php.net.',
    'cmd' => 'no command',
   ),
), $fakelog->getLog(), 'log notfound');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
