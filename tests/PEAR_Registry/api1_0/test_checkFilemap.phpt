--TEST--
PEAR_Registry->checkFilemap() v1.0
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
$statedir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'registry_tester';
if (file_exists($statedir)) {
    // don't delete existing directories!
    echo 'skip';
}
include_once 'PEAR/Registry.php';
$pv = phpversion() . '';
$av = $pv{0} == '4' ? 'apiversion' : 'apiVersion';
if (!in_array($av, get_class_methods('PEAR_Registry'))) {
    echo 'skip';
}
if (PEAR_Registry::apiVersion() != '1.1') {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$phpunit->assertRegEquals('dumping registry...
channel pear.php.net:
dump done
', $reg, 'Initial dump is incorrect');

$reg->addPackage("pkg3", array("name" => "pkg3", "version" => "3.0", "filelist" => $files3));
$phpunit->assertRegEquals('dumping registry...
channel pear.php.net:
pkg3: version="3.0" filelist=array(pkg3-1.php[role=php],pkg3-2.php[role=php,baseinstalldir=pkg3]) _lastmodified is set
dump done
', $reg, 'after adding pkg3');

$reg->updatePackage("pkg3", array("version" => "3.1b1", "status" => "beta"));
$phpunit->assertRegEquals('dumping registry...
channel pear.php.net:
pkg3: version="3.1b1" filelist=array(pkg3-1.php[role=php],pkg3-2.php[role=php,baseinstalldir=pkg3]) _lastmodified is set status="beta"
dump done
', $reg, 'after update of pkg3');

$testing = $reg->checkFilemap(array_merge($files3, $files2), 'pkg3');
$phpunit->assertEquals(array(
    'pkg3-1.php' => 'pkg3',
    'pkg3' . DIRECTORY_SEPARATOR . 'pkg3-2.php' => 'pkg3'),
    $testing, '');
echo 'tests done';
?>
--EXPECT--
creating registry object
tests done