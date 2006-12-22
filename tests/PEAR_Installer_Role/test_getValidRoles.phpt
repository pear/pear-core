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
  0 => 'honorsbaseinstall',
  1 => 'isphp',
  2 => 'noextbin',
  3 => 'noextsrc',
  4 => 'nohonorsbaseinstall',
  5 => 'notphp',
), PEAR_Installer_Role::getValidRoles('php'), 'php');
$phpunit->assertEquals(array (
  0 => 'honorsbaseinstall',
  1 => 'isphp',
  2 => 'noextbin',
  3 => 'nohonorsbaseinstall',
  4 => 'nophp',
  5 => 'notphp',
), PEAR_Installer_Role::getValidRoles('extsrc'), 'extsrc');
$phpunit->assertEquals(array (
  0 => 'honorsbaseinstall',
  1 => 'isphp',
  2 => 'noextsrc',
  3 => 'nohonorsbaseinstall',
  4 => 'nophp',
  5 => 'notphp',
), PEAR_Installer_Role::getValidRoles('extbin'), 'extbin');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done