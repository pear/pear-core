--TEST--
PEAR_DependencyDB->setConfig()
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
$statedir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pear-core-test';
if (file_exists($statedir)) {
    // don't delete existing directories!
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
