--TEST--
PEAR_Registry->hasWriteAccess() (API v1.1)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    die('skip use "pear run-tests" to execute these tests');
}
if (version_compare(PHP_VERSION, '5.4', '>=')) {
    die('skip safe mode no longer exists');
}
require_once 'PEAR/Registry.php';
$pv = phpversion() . '';
$av = $pv{0} == '4' ? 'apiversion' : 'apiVersion';
if (!in_array($av, get_class_methods('PEAR_Registry'))) {
    die('skip missing PEAR_Registry');
}
if (PEAR_Registry::apiVersion() != '1.1') {
    die('skip test is for API Version 1.1.');
}
?>
--INI--
safe_mode=1
safe_mode_include_dir=/
safe_mode_allowed_env_vars=HOME,PHP_
--FILE--
<?php
$prior_er = error_reporting(error_reporting() & ~E_WARNING);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
error_reporting($prior_er);
if (OS_UNIX) {
    $phpunit->assertErrorsF(array(
        array('package' => 'PEAR_Error', 'message' => 'registerRoles: opendir(%sPEAR/Installer/Role) failed: does not exist/is not directory')
    ), 'err');
}
if (OS_WINDOWS) {
    $reg->install_dir = '/';
} else {
    $reg->install_dir = '\\';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 1);
if (OS_WINDOWS) {
    $reg->install_dir = '\\';
} else {
    $reg->install_dir = '/';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 2);
if (OS_WINDOWS) {
    $reg->install_dir = '$:\\$*#';
} else {
    $reg->install_dir = '/usr/$*#^';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 3);
if (OS_WINDOWS) {
    $reg->install_dir = '\\windows';
} else {
    $reg->install_dir = '/usr/local/lib/foo/bar';
}
$phpunit->assertFalse($reg->hasWriteAccess(), 4);
?>
===DONE===
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
===DONE===
