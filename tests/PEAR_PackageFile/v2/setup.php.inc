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
$config = &new PEAR_Config($statedir . DIRECTORY_SEPARATOR . 'pear.ini');
$parser = new PEAR_PackageFile_Parser_v2;
$parser->setConfig($config);
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'phpt_test.php.inc';
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'download_test_classes.php.inc';
$phpunit = &new PEAR_PHPTest(true);
class fake_log
{
    var $_log = array();
    function log($level, $message)
    {
        $this->_log[] = array($level, $message);
    }

    function getLog()
    {
        $log = $this->_log;
        $this->_log = array();
        return $log;
    }
}
$fakelog = new fake_log;
$parser->setLogger($fakelog);
test_PEAR_Dependency2::singleton($config);
?>