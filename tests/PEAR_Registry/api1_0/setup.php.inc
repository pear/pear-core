<?php
error_reporting(E_ALL);
include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'pear_registry_inc.php.inc';
include_once "PEAR/Registry.php";
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'phpt_test.php.inc';
PEAR::setErrorHandling(PEAR_ERROR_DIE, "%s\n");

// ------------------------------------------------------------------------- //

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

require_once 'PEAR/ErrorStack.php';
function dump_error($err)
{
    echo "caught ErrorStack error:\n";
    echo "message: ". $err['message'] . "\n";
    echo "code: ". $err['code'] . "\n";
}
PEAR_ErrorStack::setDefaultCallback('dump_error');
register_shutdown_function('cleanall');
$statedir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'registry_tester';
cleanall();

$files1 = array(
    "pkg1-1.php" => array(
        "role" => "php",
        ),
    "pkg1-2.php" => array(
        "role" => "php",
        "baseinstalldir" => "pkg1",
        ),
    );
$files1_test = array(
    "pkg12-1.php" => array(
        "role" => "php",
        ),
    "pkg12-2.php" => array(
        "role" => "php",
        "baseinstalldir" => "pkg1",
        ),
    );
$files1_grnok = array(
    "pkg32-1.php" => array(
        "role" => "php",
        ),
    "pkg32-2.php" => array(
        "role" => "php",
        "baseinstalldir" => "pkg1",
        ),
    );
$files2 = array(
    "pkg2-1.php" => array(
        "role" => "php",
        ),
    "pkg2-2.php" => array(
        "role" => "php",
        "baseinstalldir" => "pkg2",
        ),
    );
$files3 = array(
    "pkg3-1.php" => array(
        "role" => "php",
        ),
    "pkg3-2.php" => array(
        "role" => "php",
        "baseinstalldir" => "pkg3",
        ),
    );
$files3_new = array(
    "pkg3-3.php" => array(
        "role" => "php",
        "baseinstalldir" => "pkg3",
        ),
    "pkg3-4.php" => array(
        "role" => "php",
        ),
    );

print "creating registry object\n";
$reg = new PEAR_Registry($statedir);
$phpunit = new PEAR_PHPTest(true);
?>