<?php
require_once dirname(dirname(__FILE__)) . '/phpt_test.php.inc';
require_once 'download_test_classes.php.inc';

require_once 'PEAR/RunTest.php';

$statedir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pear-core-test';
cleanall();
mkdir($statedir);

$phpunit = &new PEAR_PHPTest(true);
$tester = &new PEAR_RunTest($fakelog);
