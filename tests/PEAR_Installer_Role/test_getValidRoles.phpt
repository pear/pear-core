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

PEAR_Installer_Role::registerRoles(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sophisticated');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'php',
  3 => 'script',
  4 => 'test',
  5 => 'honorsbaseinstall',
  6 => 'isphp',
  7 => 'noextbin',
  8 => 'noextsrc',
  9 => 'nohonorsbaseinstall',
  10 => 'notphp',
), PEAR_Installer_Role::getValidRoles('php'), 'php');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'script',
  3 => 'src',
  4 => 'test',
  5 => 'honorsbaseinstall',
  6 => 'isphp',
  7 => 'noextbin',
  8 => 'nohonorsbaseinstall',
  9 => 'nophp',
  10 => 'notphp',
), PEAR_Installer_Role::getValidRoles('extsrc'), 'extsrc');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'ext',
  3 => 'script',
  4 => 'test',
  5 => 'honorsbaseinstall',
  6 => 'isphp',
  7 => 'noextsrc',
  8 => 'nohonorsbaseinstall',
  9 => 'nophp',
  10 => 'notphp',
), PEAR_Installer_Role::getValidRoles('extbin'), 'extbin');
echo 'tests done';
?>
--EXPECT--
tests done