--TEST--
PEAR_Installer_Role::getValidRoles()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'php',
  3 => 'script',
  4 => 'test',
), PEAR_Installer_Role::getValidRoles('php'), 'php');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'script',
  3 => 'src',
  4 => 'test',
), PEAR_Installer_Role::getValidRoles('extsrc'), 'extsrc');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'ext',
  3 => 'script',
  4 => 'test',
), PEAR_Installer_Role::getValidRoles('extbin'), 'extbin');
$phpunit->assertEquals(array(), PEAR_Installer_Role::getValidRoles('bundle'), 'bundle');
echo 'tests done';
?>
--EXPECT--
tests done