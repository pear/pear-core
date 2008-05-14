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
  'cfg',
  'data',
  'doc',
  'ext',
  'php',
  'script',
  'src',
  'test',
), array_slice(PEAR_Installer_Role::getInstallableRoles(), 0, 8), 'test');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
