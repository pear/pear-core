--TEST--
list-all command, bug #4072 - installed packages not listed for list-all -c option
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
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setPEARVersion('1.4.0a10');
$reg = &$config->getRegistry();
$ch = new PEAR_ChannelFile;
$ch->setName('smoog');
$ch->setSUmmary('smoog');
$ch->setDefaultPEARProtocols();
$reg->addChannel($ch);
$pf = new PEAR_PackageFile_v2_rw;
$pf->setConfig($config);
$pf->setPackage('APC');
$pf->setChannel('smoog');
$pf->setAPIStability('stable');
$pf->setReleaseStability('stable');
$pf->setAPIVersion('1.2.0');
$pf->setReleaseVersion('1.2.0');
$pf->setSummary('foo');
$pf->setDate(date('Y-m-d'));
$pf->setDescription('foo');
$pf->setLicense('PHP License');
$pf->setNotes('foo');
$pf->addMaintainer('lead', 'cellog', 'Greg', 'cellog@php.net');
$pf->setPackageType('php');
$pf->clearContents();
$pf->addFile('', 'foo.dat', array('role' => 'data'));
$pf->setPhpDep('4.0.0', '6.0.0');
$pf->setPearinstallerDep('1.4.0a10');
$pf->addRelease();
$pf->validate();
$phpunit->assertNoErrors('setup');
$reg->addPackage2($pf);
$pearweb->addXmlrpcConfig("smoog", "package.listAll",     array(true, true),
     array(
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
$reg = &$config->getRegistry();
$e = $command->run('list-all', array('channel' => 'smoog'), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'All packages:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Latest',
        2 => 'Local',
      ),
      'data' => 
      array (
        'Caching' => 
        array (
          0 => 
          array (
            0 => 'smoog/APC',
            1 => '2.0.4',
            2 => '1.2.0',
            3 => 'Alternative PHP Cache',
            4 => 
            array (
            ),
          ),
        ),
      ),
    ),
    'cmd' => 'list-all',
  ),
), $fakelog->getLog(), 'smoog log');
$phpunit->assertNoErrors('smoog');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
