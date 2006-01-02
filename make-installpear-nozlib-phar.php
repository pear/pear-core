<?php
/**
 * install-pear-nozlib.phar creator.  Requires PHP_Archive version 0.6.0 or newer
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
 * </pre>
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
 * @copyright  2005 The PHP Group
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
    ereg('([A-Za-z0-9_:]+)-.*\.tar$', $entry, $matches);
    if ($matches[1] == 'PEAR') {
    	$pearentry = $entry;
    	continue;
    }
    $packages[$matches[1]] = $entry;
}
$packages['PEAR'] = $pearentry;
require_once 'PEAR/PackageFile.php';
require_once 'PEAR/Config.php';
require_once 'PHP/Archive/Creator.php';
$config = &PEAR_Config::singleton();

chdir($peardir);

$pkg = &new PEAR_PackageFile($config);
$pf = $pkg->fromPackageFile($peardir . DIRECTORY_SEPARATOR . 'package2.xml', PEAR_VALIDATE_NORMAL);
$pearver = $pf->getVersion();

$creator = new PHP_Archive_Creator('index.php', false, 'install-pear-nozlib.phar'); // no compression
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

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Command.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/Command.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile' . DIRECTORY_SEPARATOR . 'v2.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        'if (file_exists($path . "/PEAR/Task/$task.php")) {',
        "include_once \"PEAR/Task/\$task.php",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        'if (true) {', // we're self-contained, so this should work
        "include_once \"phar://install-pear-nozlib.phar/PEAR/Task/\$task.php",
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile/v2.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Frontend.php');
$commandcontents = str_replace(
    array(
        "include_once ",
        'PEAR_Frontend::isIncludeable($file)',
    ),
    array(
        "include_once 'phar://install-pear-nozlib.phar/' . ",
        'true',
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/Frontend.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Downloader' . DIRECTORY_SEPARATOR . 'Package.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/Downloader/Package.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile' . DIRECTORY_SEPARATOR . 'v2' .
    DIRECTORY_SEPARATOR . 'Validator.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile/v2/Validator.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile' . DIRECTORY_SEPARATOR . 'v2' .
    DIRECTORY_SEPARATOR . 'rw.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@package_version@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile/v2/rw.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile' . DIRECTORY_SEPARATOR . 'Generator' .
    DIRECTORY_SEPARATOR . 'v1.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile/Generator/v1.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile' . DIRECTORY_SEPARATOR . 'Generator' .
    DIRECTORY_SEPARATOR . 'v2.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);

$creator->addString($commandcontents, 'PEAR/PackageFile/Generator/v2.php');
$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Installer' .
    DIRECTORY_SEPARATOR . 'Role.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "require_once str_replace('_', '/', \$a)",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        "require_once 'phar://install-pear-nozlib.phar/' . str_replace('_', '/', \$a)",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/Installer/Role.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Dependency2.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/Dependency2.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'OS' .
    DIRECTORY_SEPARATOR . 'Guess.php');
$commandcontents = str_replace(
    array(
        "include_once \"",
        "@package_version@",
    ),
    array(
        "include_once \"phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'OS/Guess.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://install-pear-nozlib.phar/",
        "include_once 'phar://install-pear-nozlib.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile.php');

$creator->addDir($peardir, array('tests/',
    'scripts/',
    'go-pear-phar.php',
    '*OS/Guess.php',
    '*PEAR/Command.php',
    '*PEAR/Dependency2.php',
    '*PEAR/PackageFile/Generator/v1.php',
    '*PEAR/PackageFile/Generator/v2.php',
    '*PEAR/PackageFile/v2.php',
    '*PEAR/PackageFile.php',
    '*PEAR/Downloader/Package.php',
    '*PEAR/Start.php',
    '*PEAR/Start/CLI.php',
    '*PEAR/Installer/Role.php',
    '*PEAR/Frontend.php'),
    array(
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
        '*PEAR/Command.php',
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
        '*PEAR/Validate.php',
        '*PEAR/XMLParser.php',
        'PEAR.php',
        '*Archive/Tar.php',
        '*Console/Getopt.php',
        'System.php',
    ), 'install-pear-nozlib.phar');
$creator->savePhar(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install-pear-nozlib.phar');
?>
