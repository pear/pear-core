--TEST--
PEAR_Installer_Role::getBaseinstallRoles()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
PEAR_Installer_Role::registerRoles(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sophisticated');
$phpunit->assertEquals(array (
  0 => 'ext',
  1 => 'honorsbaseinstall',
  2 => 'php',
  3 => 'script',
), PEAR_Installer_Role::getBaseinstallRoles(), 'test');
echo 'tests done';
?>
--EXPECT--
tests done