<?php
/**
 * go-pear.phar creator.  Requires PHP_Archive version 0.10.0 or newer
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
 * Next, check out pear/Structure_Graph and copy it into pear-core/
 * 
 * Next, check out pear/Console_Getopt and copy it into pear-core/
 * 
 * finally, run this script using PHP 5.1's cli php
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  2005-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 */

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
    $packages[] = $entry;
}
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
if (PEAR::isError($pf)) {
    foreach ($pf->getUserInfo() as $warn) {
        echo $warn['message'] . "\n";
    }
    die($pf->getMessage());
}
$pearver = $pf->getVersion();

$creator = new PHP_Archive_Creator('index.php', 'go-pear.phar', false);
$creator->useDefaultFrontController();
foreach ($packages as $package) {
    echo "adding PEAR/go-pear-tarballs/$package\n";
    $creator->addFile("PEAR/go-pear-tarballs/$package", "PEAR/go-pear-tarballs/$package");
}
$commandcontents = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'go-pear-phar.php');
$commandcontents = str_replace('require_once \'', 'require_once \'phar://go-pear.phar/', $commandcontents);
$creator->addString($commandcontents, 'index.php');

function replaceVersion($contents, $path)
{
    return str_replace(array('@PEAR-VER@', '@package_version@'), $GLOBALS['pearver'], $contents);
}

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
        '*PEAR/PackageFile/v2.php',
        '*PEAR/PackageFile/v2/Validator.php',
        '*PEAR/Downloader/Package.php',
        '*PEAR/Installer/Role.php',
        '*PEAR/Frontend.php',
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
        '*PEAR/PackageFile/v2.php',
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
        '*PEAR/Frontend.php',
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
$creator->savePhar(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'go-pear.phar');
?>
