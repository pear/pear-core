<?php
require_once 'PEAR/PackageFile/Parser/v2.php';
require_once 'PEAR/PackageFile/v2.php';
require_once 'PEAR/Config.php';

function cleanall($dir = null)
{
    if ($dir !== null) {
        $statedir = $dir;
    } else {
        $statedir = $GLOBALS['statedir'];
    }
    if (file_exists($statedir)) {
        $dp = opendir($statedir);
        while ($ent = readdir($dp)) {
            if (in_array($ent, array('.', '..'))) {
                continue;
            }
            if (is_dir($statedir . DIRECTORY_SEPARATOR . $ent)) {
                cleanall($statedir . DIRECTORY_SEPARATOR . $ent);
                continue;
            }
            unlink($statedir . DIRECTORY_SEPARATOR . $ent);
        }
        closedir($dp);
        rmdir($statedir);
    }
}

register_shutdown_function('cleanall');
$statedir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'registry_tester';
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
putenv('PHP_PEAR_SYSCONF_DIR='.$statedir);
$home = getenv('HOME');
if (!empty($home)) {
    // for PEAR_Config initialization
    putenv('HOME="'.$statedir);
}
$config = &PEAR_Config::singleton($statedir . DIRECTORY_SEPARATOR . 'pear.ini');
$parser = new PEAR_PackageFile_Parser_v2;
$parser->setConfig($config);
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'phpt_test.php.inc';
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'download_test_classes.php.inc';
$phpunit = &new PEAR_PHPTest(true);
$fakelog = new fake_log;
$parser->setLogger($fakelog);
test_PEAR_Dependency2::singleton($config);
/**
 * @param array
 * @return test_PEAR_Downloader_Package
 */
function &newFakeDownloaderPackage($options)
{
    global $config, $fakelog;
    $downloader = &new test_PEAR_Downloader($fakelog, $options, $config);
    $p = &new test_PEAR_Downloader_Package($downloader);
    return $p;
}
?>