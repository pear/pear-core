<?php
namespace {
$path = false;
$force = false;
$norender = false;
if (isset($_SERVER['argv'][1])) {
    $arg = $_SERVER['argv'][1];
    if ($arg === '--force') {
        $force = true;
        if (!isset($_SERVER['argv'][2])) {
            goto skippy;
        }
        // check if we only want to rebuild the coverage db
        if ($_SERVER['argv'][2] === '--norender') {
            $norender = true;
            if (!isset($_SERVER['argv'][3])) {
                goto skippy;
            }
            $arg = $_SERVER['argv'][3];
        } else {
            $arg = $_SERVER['argv'][2];
        }
    } elseif ($arg === '--norender') {
        $norender = true;
        if (!isset($_SERVER['argv'][2])) {
            goto skippy;
        }
        if ($_SERVER['argv'][2] === '--force') {
            $force = true;
            if (!isset($_SERVER['argv'][3])) {
                goto skippy;
            }
            $arg = $_SERVER['argv'][3];
        } else {
            $arg = $_SERVER['argv'][2];
        }
    }
    $path = realpath($arg);
    if ($path) {
        $path = realpath($path . '/Pyrus_Developer/src/Pyrus/Developer/CoverageAnalyzer');
    }
}
skippy:
if (!$path) {
    $path = realpath(__DIR__ . '/../all/Pyrus_Developer/src/Pyrus/Developer/CoverageAnalyzer');
}
if (!$path) {
    die("Usage:
php test-modified.php [--force] [--norender] [/path/to/all]
 --force:
 Generate coverage even if no changes or failed tests
 --norender:
 Do not generate coverage html files, just rebuild the database
 /path/to/all:
 Pass in path to checkout of http://svn.pear.php.net/PEAR2/all,
 by default, we assume ../all
");
}
function __autoload($c)
{
    $c = str_replace(array('PEAR2\Pyrus\Developer\CoverageAnalyzer\\',
                           '\\'), array('', '/'), $c);
    include $GLOBALS['path'] . '/' . $c . '.php';
}
$e = error_reporting();
error_reporting(0);
require_once 'PEAR/Command/Test.php';
require_once 'PEAR/Frontend/CLI.php';
require_once 'PEAR/Config.php';
$cli = new PEAR_Frontend_CLI;
$config = @PEAR_Config::singleton();
$test = new PEAR_Command_Test($cli, $config);
error_reporting($e);
}
namespace PEAR2\Pyrus\Developer\CoverageAnalyzer {
    $pear = @fopen('PEAR.php', 'r', 1);
    if (!$pear) {
        die("Install PEAR before attempting to run the tests\n");
    }
    fclose($pear);
    $olddir = getcwd();
try_again:
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $includepath) {
        if (file_exists($includepath . DIRECTORY_SEPARATOR . 'PEAR.php')) {
            if ($includepath === '.') {
                chdir('tests');
                goto try_again;
            }
            $codepath = $includepath;
            break;
        }
    }
    if (!isset($codepath)) {
        die("Something is wrong - PEAR.php exists, but was not within include_path\n");
    }
    $testpath = realpath(__DIR__ . '/tests');
    $sqlite = new Sqlite($testpath . '/pear2coverage.db', $codepath, $testpath);
    $modified = $sqlite->getModifiedTests();
    if (!$force && !count($modified)) {
        echo "No changes to coverage needed.  Bye!\n";
        exit;
    }
    if (!count($modified) && $force) {
        goto norunnie;
    }
    $dir = getcwd();
    chdir($testpath);
    error_reporting(0);
    $test->doRunTests('run-tests', array('coverage' => true), $modified);
    chdir($olddir);
    error_reporting($e);
    chdir($dir);
    if (!$force && file_exists($testpath . '/run-tests.log')) {
        // tests failed
        echo "Tests failed - not regenerating coverage data\n";
        exit;
    }
norunnie:
    $a = new Aggregator($testpath,
                        $codepath,
                        $testpath . '/pear2coverage.db');
    if ($norender) {
        exit;
    }
    if (file_exists(__DIR__ . '/coverage')) {
        echo "Removing old coverage HTML...";
        foreach (new \DirectoryIterator(__DIR__ . '/coverage') as $file) {
            if ($file->isDot()) continue;
            unlink($file->getPathName());
        }
        echo "done\n";
    } else {
        mkdir(__DIR__ . '/coverage');
    }
    echo "Rendering\n";
    $a->render(__DIR__ . '/coverage');
    echo "Done rendering\n";
}
?>
