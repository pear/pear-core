--TEST--
PEAR_Installer_Role::registerRoles()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
} else {
    echo 'info low-level test, could fail and still be OK';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
PEAR_Installer_Role::registerRoles(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'roles');
$phpunit->assertEquals(array (
  'PEAR_Installer_Role_Data' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'data_dir',
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Dataf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'data_dir',
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Doc' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'doc_dir',
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Docf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'doc_dir',
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Ext' => 
  array (
    'releasetypes' => 
    array (
      0 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'ext_dir',
    'honorsbaseinstall' => true,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => true,
  ),
  'PEAR_Installer_Role_Extf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'ext_dir',
    'honorsbaseinstall' => true,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => true,
  ),
  'PEAR_Installer_Role_Php' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
    ),
    'installable' => true,
    'locationconfig' => 'php_dir',
    'honorsbaseinstall' => true,
    'unusualbaseinstall' => false,
    'phpfile' => true,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Phpf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
    ),
    'installable' => true,
    'locationconfig' => 'php_dir',
    'honorsbaseinstall' => true,
    'unusualbaseinstall' => false,
    'phpfile' => true,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Script' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'bin_dir',
    'honorsbaseinstall' => true,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => true,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Scriptf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'bin_dir',
    'honorsbaseinstall' => true,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => true,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Src' => 
  array (
    'releasetypes' => 
    array (
      0 => 'extsrc',
    ),
    'installable' => false,
    'locationconfig' => false,
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Srcf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'extsrc',
    ),
    'installable' => false,
    'locationconfig' => false,
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
  ),
  'PEAR_Installer_Role_Test' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'test_dir',
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
  'PEAR_Installer_Role_Testf' => 
  array (
    'releasetypes' => 
    array (
      0 => 'php',
      1 => 'extsrc',
      2 => 'extbin',
    ),
    'installable' => true,
    'locationconfig' => 'test_dir',
    'honorsbaseinstall' => false,
    'unusualbaseinstall' => false,
    'phpfile' => false,
    'executable' => false,
    'phpextension' => false,
  ),
), $GLOBALS['_PEAR_INSTALLER_ROLES'], 'registered');
echo 'tests done';
?>
--EXPECT--
tests done