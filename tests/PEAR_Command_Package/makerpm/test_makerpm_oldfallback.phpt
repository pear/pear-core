--TEST--
makerpm with fallback to OLD makerpm code in PEAR
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';

class test2_PEAR_Command_Package extends test_PEAR_Command_Package {
    function getCommandPackaging(&$ui, &$config)
    {
        return null;
    }
}

$command = &new test2_PEAR_Command_Package($fakelog, $config);

copy(dirname(__FILE__) . '/packagefiles/Net_SMTP-1.2.8.tgz', $temp_path . DIRECTORY_SEPARATOR . 'Net_SMTP-1.2.8.tgz');
chdir($temp_path);

$ret = $command->run('makerpm', array(), array('Net_SMTP-1.2.8.tgz'));

$phpunit->assertNoErrors('ret OK');
$phpunit->showall();
$phpunit->assertEquals(array(
    'info' => 'WARNING: "pear makerpm" is now deprecated; an improved version is available via "pear make-rpm-spec", which is available by installing PEAR_Command_Packaging',
    'cmd' => 'no command',
)
, array_shift($fakelog->getLog()),'warning about deprecation');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
