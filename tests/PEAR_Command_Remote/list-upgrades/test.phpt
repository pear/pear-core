--TEST--
remote-list command
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
$pf->setPackage('Archive_Zip');
$pf->setSummary('foo');
$pf->setDate(date('Y-m-d'));
$pf->setDescription('foo');
$pf->setVersion('1.0.0');
$pf->setState('stable');
$pf->setLicense('PHP License');
$pf->setNotes('foo');
$pf->addMaintainer('lead', 'cellog', 'Greg', 'cellog@php.net');
$pf->addFile('', 'foo.dat', array('role' => 'data'));
$pf->validate();
$phpunit->assertNoErrors('setup');
$reg->addPackage2($pf);
$pearweb->addXmlrpcConfig("empty", "package.listLatestReleases",     array(
    0 =>
        'stable',
    ),     array(
    ));
$pearweb->addXmlrpcConfig("smoog", "package.listLatestReleases",     array(
    0 =>
        'stable',
    ),     array(
    'APC' =>
        array(
        'version' =>
            "2.0.4",
        'state' =>
            "stable",
        'fullpath' =>
            '/blah/blah.tgz',
        'filesize' =>
            23456
        ),
    ));
$pearweb->addXmlrpcConfig("pear.php.net", "package.listLatestReleases",     array(
    0 =>
        'stable',
    ),     array(
    'APC' =>
        array(
        'version' =>
            "2.0.4",
        'state' =>
            "stable",
        'fullpath' =>
            '/blah/blah.tgz',
        'filesize' =>
            23456
        ),
    'Archive_Zip' =>
        array(
        'version' =>
            "2.0.5",
        'state' =>
            "stable",
        'fullpath' =>
            '/blah/blah.tgz',
        'filesize' =>
            23456789012
        ),
    ));
$reg = &$config->getRegistry();
$ch = new PEAR_ChannelFile;
$ch->setName('smoog');
$ch->setSUmmary('smoog');
$ch->setDefaultPEARProtocols();
$reg->addChannel($ch);
$ch->setName('empty');
$reg->addChannel($ch);
$e = $command->run('list-upgrades', array(), array());
$phpunit->assertNoErrors('pear.php.net');
$workingcopy = array (
  'empty' => 
  array (
    'info' => 'Channel empty: No upgrades available',
    'cmd' => 'no command',
  ),
  'pear.php.net' => 
  array (
    'info' => 
    array (
      'caption' => 'pear.php.net Available Upgrades (stable):',
      'border' => 1,
      'headline' => 
      array (
        0 => 'Channel',
        1 => 'Package',
        2 => 'Local',
        3 => 'Remote',
        4 => 'Size',
      ),
      'data' => 
      array (
        0 => 
        array (
          0 => 'pear.php.net',
          1 => 'Archive_Zip',
          2 => '1.0.0 (stable)',
          3 => '2.0.5 (stable)',
          4 => '22907021kB',
        ),
      ),
    ),
    'cmd' => 'list-upgrades',
  ),
  'smoog' => 
  array (
    'info' => 'Channel smoog: No upgrades available',
    'cmd' => 'no command',
  ),
);
$actual = array();
// this is because channels are queried in the order returned from listChannels(),
// which differs between windows and linux
foreach ($reg->listChannels() as $chan) {
    if ($chan == '__uri') {
        continue;
    }
    $actual[] = $workingcopy[$chan];
}
$phpunit->assertEquals($actual
, $fakelog->getLog(), 'pear log');
echo 'tests done';
?>
--EXPECT--
tests done
