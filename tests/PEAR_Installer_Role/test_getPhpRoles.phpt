--TEST--
PEAR_Installer_Role::getPhpRoles()
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
  0 => 'php',
), PEAR_Installer_Role::getPhpRoles(), 'test');
echo 'tests done';
?>
--EXPECT--
tests done