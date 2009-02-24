<?php
/**
 * install-pear-nozlib.phar creator.  Requires PHP_Archive version 0.11.0 or newer
 *
 * PHP version 5.1+
 *
 * To use, in pear-core/PEAR create a directory
 * named go-pear-tarballs, and run these commands in the directory
 *
 * <pre>
 * $ pear download -Z PEAR
 * $ pear download -Z Archive_Tar
 * $ pear download -Z Console_Getopt
 * $ pear download -Z Structures_Graph
 * </pre>
 *
 * finally, run this script using PHP 5.1's cli php

 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 */

function replaceVersion($contents, $path)
{
    return str_replace(array('@PEAR-VER@', '@package_version@'), $GLOBALS['pearver'], $contents);
}

$peardir = dirname(__FILE__);

$dp = @opendir(dirname(__FILE__) . '/PEAR/go-pear-tarballs');
if (empty($dp)) {
    die("while locating packages to install: opendir('" .
        dirname(__FILE__) . "/PEAR/go-pear-tarballs') failed");
}
$packages = array();
while (false !== ($entry = readdir($dp))) {
    if ($entry{0} == '.' || !in_array(substr($entry, -4), array('.tar'))) {
        continue;
    }
    ereg('([A-Za-z0-9_:]+)-.*\.tar$', $entry, $matches);
    if ($matches[1] == 'PEAR') {
    	$pearentry = $entry;
    	continue;
    }
    $packages[$matches[1]] = $entry;
}
$packages['PEAR'] = $pearentry;
$x = explode(PATH_SEPARATOR, get_include_path());
$y = array();
foreach ($x as $path) {
    if ($path == '.') {
        continue;
    }
    $y[] = $path;
}
// remove current dir, we will otherwise include CVS files, which is not good
set_include_path(implode(PATH_SEPARATOR, $y));
require_once 'PEAR/PackageFile.php';
require_once 'PEAR/Config.php';
require_once 'PHP/Archive/Creator.php';
$config = &PEAR_Config::singleton();

chdir($peardir);

$pkg = &new PEAR_PackageFile($config);
$pf = $pkg->fromPackageFile($peardir . DIRECTORY_SEPARATOR . 'package2.xml', PEAR_VALIDATE_NORMAL);
$pearver = $pf->getVersion();

$creator = new PHP_Archive_Creator('index.php', 'install-pear-nozlib.phar'); // no compression
$creator->useDefaultFrontController('PEAR.php');
$creator->useSHA1Signature();
$install_files = '$install_files = array(';
foreach ($packages as $name => $package) {
    echo "$name => $package\n";
    $install_files .= "'$name' => 'phar://install-pear-nozlib.phar/$package'," . "\n";
    $creator->addFile("PEAR/go-pear-tarballs/$package", "$package");
}
$install_files .= ');';
echo "install_files is $install_files";

$commandcontents = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install-pear.php');
$commandcontents = str_replace(
    array(
        'include_once \'',
        '$install_files = array();'
    ),
    array(
        'include_once \'phar://install-pear-nozlib.phar/',
        $install_files
    ), $commandcontents);
$creator->addString($commandcontents, 'index.php');

$commandcontents = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '/PEAR/Frontend.php');
$commandcontents = str_replace(
    array(
        "\$file = str_replace('_', '/', \$uiclass) . '.php';"
    ),
    array(
        "\$file = 'phar://install-pear-nozlib.phar/' . str_replace('_', '/', \$uiclass) . '.php';"
    ), $commandcontents);
$commandcontents = replaceVersion($commandcontents, '');
$creator->addString($commandcontents, 'PEAR/Frontend.php');

$commandcontents = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . '/PEAR/PackageFile/v2.php');
$commandcontents = str_replace(
    array(
        '$fp = @fopen("PEAR/Task/$taskfile.php", \'r\', true);',
    ),
    array(
        '$fp = @fopen("phar://install-pear-nozlib.phar/PEAR/Task/$taskfile.php", \'r\', true);'
    ), $commandcontents);
$commandcontents = replaceVersion($commandcontents, '');
$commandcontents = $creator->tokenMagicRequire($commandcontents, 'a.php');
$creator->addString($commandcontents, 'PEAR/PackageFile/v2.php');

$creator->addMagicRequireCallback(array($creator, 'limitedSmartMagicRequire'));
$creator->addMagicRequireCallback('replaceVersion');
$creator->addFile($peardir . '/PEAR/Command.php', 'PEAR/Command.php');

$creator->clearMagicRequire();
$creator->addMagicRequireCallback(array($creator, 'tokenMagicRequire'));
$creator->addMagicRequireCallback('replaceVersion');
$creator->addDir($peardir . DIRECTORY_SEPARATOR . 'PEAR', array(),
    array(
        '*PEAR/Dependency2.php',
        '*PEAR/PackageFile/Generator/v1.php',
        '*PEAR/PackageFile/Generator/v2.php',
        '*PEAR/PackageFile/v2/Validator.php',
        '*PEAR/Downloader/Package.php',
        '*PEAR/Installer/Role.php',
        '*PEAR/ChannelFile/Parser.php',
        '*PEAR/Command/Install.xml',
        '*PEAR/Command/Install.php',
        '*PEAR/Downloader/Package.php',
        '*PEAR/Frontend/CLI.php',
        '*PEAR/Installer/Role/Common.php',
        '*PEAR/Installer/Role/Data.php',
        '*PEAR/Installer/Role/Doc.php',
        '*PEAR/Installer/Role/Php.php',
        '*PEAR/Installer/Role/Script.php',
        '*PEAR/Installer/Role/Test.php',
        '*PEAR/Installer/Role/Data.xml',
        '*PEAR/Installer/Role/Doc.xml',
        '*PEAR/Installer/Role/Php.xml',
        '*PEAR/Installer/Role/Script.xml',
        '*PEAR/Installer/Role/Test.xml',
        '*PEAR/PackageFile.php',
        '*PEAR/PackageFile/v1.php',
        '*PEAR/PackageFile/Parser/v1.php',
        '*PEAR/PackageFile/Parser/v2.php',
        '*PEAR/PackageFile/Generator/v1.php',
        '*PEAR/REST.php',
        '*PEAR/REST/10.php',
        '*PEAR/Task/Common.php',
        '*PEAR/Task/Postinstallscript.php',
        '*PEAR/Task/Postinstallscript/rw.php',
        '*PEAR/Task/Replace.php',
        '*PEAR/Task/Replace/rw.php',
        '*PEAR/Task/Windowseol.php',
        '*PEAR/Task/Windowseol/rw.php',
        '*PEAR/Task/Unixeol.php',
        '*PEAR/Task/Unixeol/rw.php',
        '*PEAR/Validator/PECL.php',
        '*PEAR/ChannelFile.php',
        '*PEAR/Command/Common.php',
        '*PEAR/Common.php',
        '*PEAR/Config.php',
        '*PEAR/Dependency2.php',
        '*PEAR/DependencyDB.php',
        '*PEAR/Downloader.php',
        '*PEAR/ErrorStack.php',
        '*PEAR/Installer.php',
        '*PEAR/Registry.php',
        '*PEAR/Remote.php',
        '*PEAR/Start.php',
        '*PEAR/Start/CLI.php',
        '*PEAR/Validate.php',
        '*PEAR/XMLParser.php',
    ), false, $peardir);
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'PEAR.php', 'PEAR.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'Archive/Tar.php', 'Archive/Tar.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'Console/Getopt.php', 'Console/Getopt.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'System.php', 'System.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'OS/Guess.php', 'OS/Guess.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'Structures_Graph/Structures/Graph.php', 'Structures/Graph.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'Structures_Graph/Structures/Graph/Node.php', 'Structures/Graph/Node.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'Structures_Graph/Structures/Graph/Manipulator/AcyclicTest.php', 'Structures/Graph/Manipulator/AcyclicTest.php');
$creator->addFile($peardir . DIRECTORY_SEPARATOR . 'Structures_Graph/Structures/Graph/Manipulator/TopologicalSorter.php', 'Structures/Graph/Manipulator/TopologicalSorter.php');
$creator->savePhar(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install-pear-nozlib.phar');
?>
