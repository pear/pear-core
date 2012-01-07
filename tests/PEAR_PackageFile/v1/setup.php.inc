<?php
require_once dirname(dirname(dirname(__FILE__))) . '/phpt_test.php.inc';
require_once 'download_test_classes.php.inc';

require_once 'PEAR/PackageFile/Parser/v1.php';
require_once 'PEAR/Config.php';

$statedir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pear-core-test';
cleanall($statedir);
mkdir($statedir);
$config = serialize(
array(
    'php_dir' => $statedir . DIRECTORY_SEPARATOR . 'php',
)
);
$fp = fopen($statedir . DIRECTORY_SEPARATOR . 'pear.ini', 'wb');
fwrite($fp, $config, strlen($config));
fclose($fp);
$config = &new PEAR_Config($statedir . DIRECTORY_SEPARATOR . 'pear.ini');
$parser = new PEAR_PackageFile_Parser_v1;
$parser->setConfig($config);
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'phpt_test.php.inc';
$phpunit = &new PEAR_PHPTest(true);
$fakelog = new fake_log;
$parser->setLogger($fakelog);
?>
