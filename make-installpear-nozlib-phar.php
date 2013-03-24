<?php
error_reporting(1803);

/**
 * install-pear-nozlib.phar creator.  Requires PHP_Archive version 0.11.0 or newer
 *
 * PHP version 5.1+
 *
 * To use, in pear-core create a directory
 * named go-pear-tarballs, and run these commands in the directory
 *
 * <pre>
 * $ pear download -Z PEAR Archive_Tar Console_Getopt Structures_Graph XML_Util
 * </pre>
 *
 * finally, run this script using PHP 5.1's cli php

 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  2005-2009 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    CVS: $Id$
 */

$y = array();
foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
    if ($path == '.') {
        continue;
    }

    $y[] = $path;
}

// remove current dir, we will otherwise include git files, which is not good
set_include_path(implode(PATH_SEPARATOR, $y));
require_once 'PEAR/PackageFile.php';
require_once 'PEAR/Config.php';
require_once 'PHP/Archive/Creator.php';
$config = &PEAR_Config::singleton();

function replaceVersion($contents, $path)
{
    return str_replace(array('@PEAR-VER@', '@package_version@'), $GLOBALS['pearver'], $contents);
}

$outputFile = 'install-pear-nozlib.phar';

$tardir = __DIR__ . '/go-pear-tarballs';
$dp = @scandir($tardir);
if ($dp === false) {
    die("while locating packages to install: scandir('" . $tardir. "') failed\n");
}

$required = array('Archive_Tar', 'Console_Getopt', 'PEAR', 'Structures_Graph', 'XML_Util');
$packages = array();
foreach ($dp as $entry) {
    if ($entry{0} == '.' || !in_array(substr($entry, -4), array('.tar'))) {
        continue;
    }

    preg_match('|([A-Za-z0-9_:]+)-.*?\.tar$|', $entry, $matches);
    if ($matches[1] == 'PEAR') {
        $pearentry = $entry;
        continue;
    }

    $package = strstr($entry, '-', true);
    $key = array_search($package, $required);
    if ($key !== false) {
        unset($required[$key]);
    }

    $packages[$matches[1]] = $entry;
}
$packages['PEAR'] = $pearentry;

if (!empty($required)) {
    die('Following packages were not available in tar format in go-pear-tarballs: ' . implode(', ', $required). "\n");
}

if (!file_exists("$tardir/tmp")) {
    mkdir("$tardir/tmp");
}

// Use the tar files for required Phar files
require_once 'Archive/Tar.php';
require_once 'System.php';

foreach ($packages as $package) {
    $name = substr($package, 0, -4);
    $tar = new Archive_Tar("$tardir/$package");
    $tar->extractModify("$tardir/tmp", $name);
}

chdir(__DIR__);

$pkg = new PEAR_PackageFile($config);
$pf = $pkg->fromPackageFile(__DIR__ . DIRECTORY_SEPARATOR . 'package2.xml', PEAR_VALIDATE_NORMAL);
if (PEAR::isError($pf)) {
    foreach ($pf->getUserInfo() as $warn) {
        echo $warn['message'] . "\n";
    }
    die($pf->getMessage());
}
$pearver = $pf->getVersion();

$creator = new PHP_Archive_Creator('index.php', $outputFile); // no compression
$creator->useDefaultFrontController('PEAR.php');
$creator->useSHA1Signature();

$install_files = '$install_files = array(';
foreach ($packages as $name => $package) {
    echo "$name => $package\n";
    $install_files .= "'$name' => 'phar://" . $outputFile . "/$package'," . "\n";
    $creator->addFile("go-pear-tarballs/$package", "$package");
}

$install_files .= ');';
echo "install_files is $install_files";

$commandcontents = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'install-pear.php');
$commandcontents = str_replace(
    array(
        'include_once \'',
        '$install_files = array();'
    ),
    array(
        'include_once \'phar://' . $outputFile . '/',
        $install_files
    ), $commandcontents);
$creator->addString($commandcontents, 'index.php');

$commandcontents = file_get_contents($tardir . '/tmp/PEAR/Frontend.php');
$commandcontents = str_replace(
    array(
        "\$file = str_replace('_', '/', \$uiclass) . '.php';"
    ),
    array(
        "\$file = 'phar://" . $outputFile . "/' . str_replace('_', '/', \$uiclass) . '.php';"
    ), $commandcontents);
$commandcontents = replaceVersion($commandcontents, '');
$creator->addString($commandcontents, 'PEAR/Frontend.php');

$commandcontents = file_get_contents($tardir . '/tmp/PEAR/PackageFile/v2.php');
$commandcontents = str_replace(
    array(
        '$fp = @fopen("PEAR/Task/$taskfile.php", \'r\', true);',
    ),
    array(
        '$fp = @fopen("phar://' . $outputFile . '/PEAR/Task/$taskfile.php", \'r\', true);'
    ), $commandcontents);
$commandcontents = replaceVersion($commandcontents, '');
$commandcontents = $creator->tokenMagicRequire($commandcontents, 'a.php');
$creator->addString($commandcontents, 'PEAR/PackageFile/v2.php');

$creator->addMagicRequireCallback(array($creator, 'limitedSmartMagicRequire'));
$creator->addMagicRequireCallback('replaceVersion');
$creator->addFile($tardir . '/tmp/PEAR/Command.php', 'PEAR/Command.php');

$creator->clearMagicRequire();
$creator->addMagicRequireCallback(array($creator, 'tokenMagicRequire'));
$creator->addMagicRequireCallback('replaceVersion');

$creator->addDir($tardir . '/tmp/PEAR', array(), array('*PEAR/*'), false, $tardir . '/tmp');

$creator->addFile($tardir . '/tmp/PEAR.php', 'PEAR.php');
$creator->addFile($tardir . '/tmp/PEAR5.php', 'PEAR5.php');
$creator->addFile($tardir . '/tmp/System.php', 'System.php');
$creator->addFile($tardir . '/tmp/OS/Guess.php', 'OS/Guess.php');

// Other packages
$creator->addFile($tardir . '/tmp/PEAR/Exception.php', 'PEAR/Exception.php');
$creator->addFile($tardir . '/tmp/Archive/Tar.php', 'Archive/Tar.php');
$creator->addFile($tardir . '/tmp/Util.php', 'XML/Util.php');
$creator->addFile($tardir . '/tmp/Console/Getopt.php', 'Console/Getopt.php');
$creator->addFile($tardir . '/tmp/Structures/Graph.php', 'Structures/Graph.php');
$creator->addFile($tardir . '/tmp/Structures/Graph/Node.php', 'Structures/Graph/Node.php');
$creator->addFile($tardir . '/tmp/Structures/Graph/Manipulator/AcyclicTest.php', 'Structures/Graph/Manipulator/AcyclicTest.php');
$creator->addFile($tardir . '/tmp/Structures/Graph/Manipulator/TopologicalSorter.php', 'Structures/Graph/Manipulator/TopologicalSorter.php');

// Include Start scripts speficially since they are never in the releases
$creator->addFile(__DIR__ . '/PEAR/Start.php', 'PEAR/Start.php');
$creator->addFile(__DIR__ . '/PEAR/Start/CLI.php', 'PEAR/Start/CLI.php');

$creator->useSHA1Signature();
$creator->savePhar(__DIR__ . DIRECTORY_SEPARATOR . $outputFile);

System::rm(array("-rf", "$tardir/tmp"));