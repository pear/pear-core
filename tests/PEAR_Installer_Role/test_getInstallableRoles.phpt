--TEST--
PEAR_Installer_Role::getInstallableRoles()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 'data',
  1 => 'doc',
  2 => 'ext',
  3 => 'php',
  4 => 'script',
  5 => 'test',
), array_slice(PEAR_Installer_Role::getInstallableRoles(), 0, 6), 'test');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
