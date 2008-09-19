--TEST--
remote-info command, package is installed
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(1803);
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
$pearweb->addXmlrpcConfig("pear.php.net", "package.info",     array(
    0 =>
        "Archive_Zip",
    ),     array(
    'packageid' =>
        "252",
    'name' =>
        "Archive_Zip",
    'type' =>
        "pear",
    'categoryid' =>
        "33",
    'category' =>
        "File Formats",
    'stable' =>
        "",
    'license' =>
        "PHP License",
    'summary' =>
        "Zip file management class",
    'homepage' =>
        "",
    'description' =>
        "This class provides handling of zip files in PHP.
It supports creating, listing, extracting and adding to zip files.",
    'cvs_link' =>
        "http://cvs.php.net/cvs.php/pear/Archive_Zip",
    'doc_link' =>
        "",
    'releases' =>
        array(
        '1.3.3.1' =>
            array(
            'id' =>
                "1803",
            'doneby' =>
                "cellog",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2004-11-12 02:04:57",
            'releasenotes' =>
                "add RunTest.php to package.xml, make run-tests display failed tests, and use ui",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "php",
                    'relation' =>
                        "ge",
                    'version' =>
                        "4.2",
                    'name' =>
                        "PHP",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                5 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        ),
    'notes' =>
        array(
        ),
    ));

$e = $command->run('remote-info', array(), array('Archive_Zip'));
$phpunit->assertNoErrors('Archive_Zip');
$phpunit->assertEquals(array (
  0 =>
  array (
    'info' =>
    array (
      'packageid' => '252',
      'name' => 'Archive_Zip',
      'type' => 'pear',
      'categoryid' => '33',
      'category' => 'File Formats',
      'stable' => '',
      'license' => 'PHP License',
      'summary' => 'Zip file management class',
      'homepage' => '',
      'description' => 'This class provides handling of zip files in PHP.
It supports creating, listing, extracting and adding to zip files.',
      'cvs_link' => 'http://cvs.php.net/cvs.php/pear/Archive_Zip',
      'doc_link' => '',
      'releases' =>
      array (
        '1.3.3.1' =>
        array (
          'id' => '1803',
          'doneby' => 'cellog',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2004-11-12 02:04:57',
          'releasenotes' => 'add RunTest.php to package.xml, make run-tests display failed tests, and use ui',
          'state' => 'stable',
          'deps' =>
          array (
            0 =>
            array (
              'type' => 'php',
              'relation' => 'ge',
              'version' => '4.2',
              'name' => 'PHP',
              'optional' => '0',
            ),
            1 =>
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            2 =>
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            3 =>
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            4 =>
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            5 =>
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
      ),
      'notes' =>
      array (
      ),
      'installed' => '1.0.0',
    ),
    'cmd' => 'remote-info',
  ),
), $fakelog->getLog(), 'Archive_Zip log');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
