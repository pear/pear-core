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
$phpunit->assertEquals(array (
  0 => 'ext',
  1 => 'php',
  2 => 'script',
), PEAR_Installer_Role::getBaseinstallRoles(), 'test');
echo 'tests done';
?>
--EXPECT--
tests done