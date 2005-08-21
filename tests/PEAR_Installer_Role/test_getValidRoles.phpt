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
  'data',
  'doc',
  'php',
  'script',
  'src',
  'test',
), PEAR_Installer_Role::getValidRoles('extsrc'), 'extsrc');
$phpunit->assertEquals(array (
  'data',
  'doc',
  'ext',
  'php',
  'script',
  'test',
), PEAR_Installer_Role::getValidRoles('extbin'), 'extbin');
$phpunit->assertEquals(array(), PEAR_Installer_Role::getValidRoles('bundle'), 'bundle');

PEAR_Installer_Role::registerRoles(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sophisticated');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'honorsbaseinstall',
  3 => 'isphp',
  4 => 'noextbin',
  5 => 'noextsrc',
  6 => 'nohonorsbaseinstall',
  7 => 'notphp',
  8 => 'php',
  9 => 'script',
  10 => 'test',
), PEAR_Installer_Role::getValidRoles('php'), 'php');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'honorsbaseinstall',
  3 => 'isphp',
  4 => 'noextbin',
  5 => 'nohonorsbaseinstall',
  6 => 'nophp',
  7 => 'notphp',
  8 => 'php',
  9 => 'script',
  10 => 'src',
  11 => 'test',
), PEAR_Installer_Role::getValidRoles('extsrc'), 'extsrc');
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'ext',
  3 => 'honorsbaseinstall',
  4 => 'isphp',
  5 => 'noextsrc',
  6 => 'nohonorsbaseinstall',
  7 => 'nophp',
  8 => 'notphp',
  9 => 'php',
  10 => 'script',
  11 => 'test',
), PEAR_Installer_Role::getValidRoles('extbin'), 'extbin');
echo 'tests done';
?>
--EXPECT--
tests done