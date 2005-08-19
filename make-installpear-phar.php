<?php
$peardir = dirname(__FILE__);
$xmlrpcdir = 'C:\php5\pear\XML';

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
require_once 'PEAR/PackageFile.php';
require_once 'PEAR/Config.php';
require_once 'PHP/Archive/Creator.php';
$config = &PEAR_Config::singleton();

chdir($peardir);

$pkg = &new PEAR_PackageFile($config);
$pf = $pkg->fromPackageFile($peardir . DIRECTORY_SEPARATOR . 'package2.xml', PEAR_VALIDATE_NORMAL);
$pearver = $pf->getVersion();

$creator = new PHP_Archive_Creator('index.php', true);
foreach ($packages as $package) {
    echo "adding PEAR/go-pear-tarballs/$package\n";
    $creator->addFile("PEAR/go-pear-tarballs/$package", "PEAR/go-pear-tarballs/$package");
}
$commandcontents = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install-pear.php');
$commandcontents = str_replace('require_once \'', 'require_once \'phar://go-pear.phar/', $commandcontents);
$creator->addString($commandcontents, 'index.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Command.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
    ),
    array(
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
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
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
        'if (true) {', // we're self-contained, so this should work
        "include_once \"phar://go-pear.phar/PEAR/Task/\$task.php",
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
        "include_once 'phar://go-pear.phar/' . ",
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
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
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
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
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
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
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
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
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
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile/Generator/v2.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'OS' .
    DIRECTORY_SEPARATOR . 'Guess.php');
$commandcontents = str_replace(
    array(
        "include_once \"",
        "@package_version@",
    ),
    array(
        "include_once \"phar://go-pear.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'OS/Guess.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'Dependency2.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/Dependency2.php');

$commandcontents = file_get_contents($peardir . DIRECTORY_SEPARATOR . 'PEAR' .
    DIRECTORY_SEPARATOR . 'PackageFile.php');
$commandcontents = str_replace(
    array(
        "require_once '",
        "include_once '",
        "@PEAR-VER@",
    ),
    array(
        "require_once 'phar://go-pear.phar/",
        "include_once 'phar://go-pear.phar/",
        $pearver,
    ),
    $commandcontents);
$creator->addString($commandcontents, 'PEAR/PackageFile.php');

$creator->addFile($xmlrpcdir . DIRECTORY_SEPARATOR . 'RPC.php', 'XML/RPC.php', true);
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
    '*PEAR/Frontend.php'),
    array(
        '*PEAR/ChannelFile/Parser.php',
        '*PEAR/Command/Install-init.php',
        '*PEAR/Command/Install.php',
        '*PEAR/Downloader/Package.php',
        '*PEAR/Frontend/CLI.php',
        '*PEAR/Installer/Role.php',
        '*PEAR/Installer/Role/Common.php',
        '*PEAR/Installer/Role/Data.php',
        '*PEAR/Installer/Role/Doc.php',
        '*PEAR/Installer/Role/Php.php',
        '*PEAR/Installer/Role/Script.php',
        '*PEAR/Installer/Role/Test.php',
        '*PEAR/PackageFile/v1.php',
        '*PEAR/PackageFile/v2.php',
        '*PEAR/PackageFile/Parser/v1.php',
        '*PEAR/PackageFile/Parser/v2.php',
        '*PEAR/PackageFile/Generator/v1.php',
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
        '*PEAR/PackageFile.php',
        '*PEAR/Registry.php',
        '*PEAR/Remote.php',
        '*PEAR/Validate.php',
        '*PEAR/XMLParser.php',
        'PEAR.php',
        '*Archive/Tar.php',
        '*Console/Getopt.php',
        'System.php',
    ), 'install-pear.phar');
$creator->savePhar(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install-pear.phar');
?>