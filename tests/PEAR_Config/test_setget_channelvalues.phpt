--TEST--
PEAR_Config->set() and PEAR_Config->get()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$config = new PEAR_Config;
// failures
$phpunit->assertFalse($config->set('php_dir', 'oops', 'user', 'unknown'), 'unknown channel');
$phpunit->assertFalse($config->set('sig_bin', 'oops', 'user', '__uri'), 'global value');
// successes

$config->setChannels(array('pear.php.net', '__uri'));
$phpunit->assertTrue($config->set('bin_dir', 'yay', 'system', '__uri'), 'global value');
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'bin',
    $config->get('bin_dir', 'user', 'pear.php.net'),
    'confirm bin_dir=yay 1');
$phpunit->assertEquals(null, $config->get('bin_dir', 'system', 'pear.php.net'),
    'confirm bin_dir=yay 2');
$phpunit->assertEquals(null, $config->get('bin_dir', 'system'),
    'confirm bin_dir=yay 3');
$phpunit->assertEquals('yay', $config->get('bin_dir', 'system', '__uri'),
    'confirm bin_dir=yay 4');

$config->set('default_channel', '__uri');
$phpunit->assertEquals($temp_path . DIRECTORY_SEPARATOR . 'bin', $config->get('bin_dir', 'user'), 'default __uri user');
$phpunit->assertEquals('yay', $config->get('bin_dir', 'system'), 'default __uri system');
$phpunit->assertEquals('yay', $config->get('bin_dir'), 'default __uri default');
$phpunit->assertTrue($config->set('default_channel', 'pear'), 'set pear');
$phpunit->assertEquals('pear.php.net', $config->get('default_channel'), 'pear default');
echo 'tests done';
?>
--EXPECT--
tests done