--TEST--
remote-info command
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(1803);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$pearweb->addXmlrpcConfig("pear.php.net", "package.info",     array(
    0 =>
        "Archive_Zip",
    ),     array(
    'packageid' =>
        "252",
    'name' =>
        "Archive_Zip",
    'type' =>
        "pear",
    'categoryid' =>
        "33",
    'category' =>
        "File Formats",
    'stable' =>
        "",
    'license' =>
        "PHP License",
    'summary' =>
        "Zip file management class",
    'homepage' =>
        "",
    'description' =>
        "This class provides handling of zip files in PHP.
It supports creating, listing, extracting and adding to zip files.",
    'cvs_link' =>
        "http://cvs.php.net/cvs.php/pear/Archive_Zip",
    'doc_link' =>
        "",
    'releases' =>
        array(
        ),
    'notes' =>
        array(
        ),
    ));
$pearweb->addXmlrpcConfig("pear.php.net", "package.info",     array(
    0 =>
        "PEAR",
    ),     array(
    'packageid' =>
        "14",
    'name' =>
        "PEAR",
    'type' =>
        "pear",
    'categoryid' =>
        "19",
    'category' =>
        "PEAR",
    'stable' =>
        "1.3.3.1",
    'license' =>
        "PHP License",
    'summary' =>
        "PEAR Base System",
    'homepage' =>
        "",
    'description' =>
        "The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception php5-only exception class
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling common operations
   with files and directories
 * the PEAR base class",
    'cvs_link' =>
        "http://cvs.php.net/cvs.php/pear-core/",
    'doc_link' =>
        "",
    'releases' =>
        array(
        '1.3.3.1' =>
            array(
            'id' =>
                "1803",
            'doneby' =>
                "cellog",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2004-11-12 02:04:57",
            'releasenotes' =>
                "add RunTest.php to package.xml, make run-tests display failed tests, and use ui",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "php",
                    'relation' =>
                        "ge",
                    'version' =>
                        "4.2",
                    'name' =>
                        "PHP",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                5 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3.3' =>
            array(
            'id' =>
                "1772",
            'doneby' =>
                "cellog",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2004-10-28 13:40:34",
            'releasenotes' =>
                "Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common \$_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role=\"doc\"

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "php",
                    'relation' =>
                        "ge",
                    'version' =>
                        "4.2",
                    'name' =>
                        "PHP",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                5 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3.1' =>
            array(
            'id' =>
                "1274",
            'doneby' =>
                "cellog",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2004-04-06 20:19:35",
            'releasenotes' =>
                "PEAR Installer:

 * Bug #534  pear search doesn\'t list unstable releases
 * Bug #933  CMD Usability Patch 
 * Bug #937  throwError() treats every call as static 
 * Bug #964 PEAR_ERROR_EXCEPTION causes fatal error 
 * Bug #1008 safe mode raises warning

PEAR_ErrorStack:

 * Added experimental error handling, designed to eventually replace
   PEAR_Error.  It should be considered experimental until explicitly marked
   stable.  require_once \'PEAR/ErrorStack.php\' to use.",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "php",
                    'relation' =>
                        "ge",
                    'version' =>
                        "4.2",
                    'name' =>
                        "PHP",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                5 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3' =>
            array(
            'id' =>
                "1142",
            'doneby' =>
                "pajoye",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2004-02-20 10:40:19",
            'releasenotes' =>
                "PEAR Installer:

* Bug #171 --alldeps with a rel=\"eq\" should install the required version, if possible
* Bug #249 installing from an url doesnt work
* Bug #248 --force command does not work as expected
* Bug #293 [Patch] PEAR_Error not calling static method callbacks for error-handler
* Bug #324 pear -G gives Fatal Error (PHP-GTK not installed, but error is at engine level)
* Bug #594 PEAR_Common::analyzeSourceCode fails on string with \$var and {
* Bug #521 Incorrect filename in naming warnings
* Moved download code into its own class
* Fully unit tested the installer, packager, downloader, and PEAR_Common",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "php",
                    'relation' =>
                        "ge",
                    'version' =>
                        "4.1",
                    'name' =>
                        "PHP",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                5 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3b6' =>
            array(
            'id' =>
                "1092",
            'doneby' =>
                "pajoye",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2004-01-25 20:57:03",
            'releasenotes' =>
                "PEAR Installer:

* Bug #171 --alldeps with a rel=\"eq\" should install the required version, if possible
* Bug #249 installing from an url doesnt work
* Bug #248 --force command does not work as expected
* Bug #293 [Patch] PEAR_Error not calling static method callbacks for error-handler
* Bug #324 pear -G gives Fatal Error (PHP-GTK not installed, but error is at engine level)
* Bug #594 PEAR_Common::analyzeSourceCode fails on string with \$var and {
* Bug #521 Incorrect filename in naming warnings
* Moved download code into its own class
* Fully unit tested the installer, packager, downloader, and PEAR_Common",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "php",
                    'relation' =>
                        "ge",
                    'version' =>
                        "4.1",
                    'name' =>
                        "PHP",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                5 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3b5' =>
            array(
            'id' =>
                "1024",
            'doneby' =>
                "pajoye",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-12-19 09:44:01",
            'releasenotes' =>
                "PEAR Installer:

* Bug #171 --alldeps with a rel=\"eq\" should install the required version, if possible
* Bug #249 installing from an url doesnt work
* Bug #248 --force command does not work as expected
* Bug #293 [Patch] PEAR_Error not calling static method callbacks for error-handler
* Bug #324 pear -G gives Fatal Error (PHP-GTK not installed, but error is at engine level)
* Moved download code into its own class
* Fully unit tested the installer, packager, downloader, and PEAR_Common",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.2",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xml",
                    'optional' =>
                        "0",
                    ),
                4 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "pcre",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3b3' =>
            array(
            'id' =>
                "919",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-10-20 16:02:00",
            'releasenotes' =>
                "PEAR Installer:

* Bug #25413 Add local installed packages to list-all (Christian DickMann)
* Bug #23221 Pear installer - extension re-install segfault
* Better error detecting and reporting in \"install/upgrade\"
* Various other bugfixes and cleanups",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xmlrpc",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3b2' =>
            array(
            'id' =>
                "887",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-10-02 11:53:00",
            'releasenotes' =>
                "PEAR Installer:

* Updated deps for Archive_Tar and Console_Getopt
* Fixed #45 preferred_state works incorrectly
* Fixed optional dependency attrib removed from
  package.xml, making them a requirement",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.1",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xmlrpc",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.3b1' =>
            array(
            'id' =>
                "875",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-09-29 13:19:00",
            'releasenotes' =>
                "PEAR Base Class:

* Fixed static calls to PEAR error-handling methods in classes
* Added ability to use a static method callback for error-handling,
  and removed use of inadvisable @ in setErrorHandling

PEAR Installer:

* Fixed #25117 - MD5 checksum should be case-insensitive
* Added dependency on XML_RPC, and optional dependency on xmlrpc extension
* Added --alldeps and --onlyreqdeps options to pear install/pear upgrade
* Sorting of installation/uninstallation so package order on the command-line is
  insignificant (fixes upgrade-all if every package is installed)
* pear upgrade will now install if the package is not installed (necessary for
  pear upgrade --alldeps, as installation is often necessary for new
  dependencies)
* fixed pear.bat if PHP is installed in a path like C:\\Program Files\\php
* Added ability to specify \"pear install package-version\" or
  \"pear install package-state\". For example: \"pear install DB-1.2\",
  or \"pear install DB-stable\"
* Fix #25008 - unhelpful error message
* Fixed optional dependencies in Dependency.php
* Fix #25322 - bad md5sum should be fatal error
* Package uninstall now also removes empty directories
* Fixed locking problems for reading commands (pear list, pear info)

OS_Guess Class:

* Fixed #25131 - OS_Guess warnings on empty lines from
  popen(\"/usr/bin/cpp \$tmpfile\", \"r\");

System Class:

* Fixed recursion deep param in _dirToStruct()
* Added the System::find() command (read API doc for more info)",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                2 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "1.0.4",
                    'name' =>
                        "XML_RPC",
                    'optional' =>
                        "0",
                    ),
                3 =>
                    array(
                    'type' =>
                        "ext",
                    'relation' =>
                        "has",
                    'version' =>
                        "",
                    'name' =>
                        "xmlrpc",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2.1' =>
            array(
            'id' =>
                "774",
            'doneby' =>
                "pajoye",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-08-15 13:48:00",
            'releasenotes' =>
                "- Set back the default library path (BC issues)",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2' =>
            array(
            'id' =>
                "771",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-08-13 22:35:00",
            'releasenotes' =>
                "Changes from 1.1:

* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command \"download-all\" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Various bugfixes
* Added new pear \"bundle\" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2b5' =>
            array(
            'id' =>
                "753",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-08-05 16:32:00",
            'releasenotes' =>
                "* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command \"download-all\" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Various bugfixes
* Added new pear \"bundle\" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2b4' =>
            array(
            'id' =>
                "752",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-08-05 03:26:00",
            'releasenotes' =>
                "* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command \"download-all\" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Various bugfixes
* Added new pear \"bundle\" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2b3' =>
            array(
            'id' =>
                "750",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-08-03 19:45:00",
            'releasenotes' =>
                "* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command \"download-all\" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Added new pear \"bundle\" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2b2' =>
            array(
            'id' =>
                "675",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-06-23 13:33:00",
            'releasenotes' =>
                "* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build <pecl-package>, now exposes the compilation progress
* Added new pear bundle command, which downloads and uncompress a <pecl-package>.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.2b1' =>
            array(
            'id' =>
                "674",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-06-23 10:07:00",
            'releasenotes' =>
                "* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* pear makerpm, now works and generates a better system independant spec file
* pear install|build <pecl-package>, now exposes the compilation progress
* Added new pear bundle command, which downloads and uncompress a <pecl-package>.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.",
            'state' =>
                "beta",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.1' =>
            array(
            'id' =>
                "587",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-05-10 23:27:00",
            'releasenotes' =>
                "PEAR BASE CLASS:

* PEAR_Error now supports exceptions when using Zend Engine 2.  Set the
  error mode to PEAR_ERROR_EXCEPTION to make PEAR_Error throw itself
  as an exception (invoke PEAR errors with raiseError() or throwError()
  just like before).

PEAR INSTALLER:

* Packaging and validation now parses PHP source code (unless
  ext/tokenizer is disabled) and does some coding standard conformance
  checks.  Specifically, the names of classes and functions are
  checked to ensure that they are prefixed with the package name.  If
  your package has symbols that should be without this prefix, you can
  override this warning by explicitly adding a \"provides\" entry in
  your package.xml file.  See the package.xml file for this release
  for an example (OS_Guess, System and md5_file).

  All classes and non-private (not underscore-prefixed) methods and
  functions are now registered during \"pear package\".",
            'state' =>
                "stable",
            'deps' =>
                array(
                0 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.4",
                    'name' =>
                        "Archive_Tar",
                    'optional' =>
                        "0",
                    ),
                1 =>
                    array(
                    'type' =>
                        "pkg",
                    'relation' =>
                        "ge",
                    'version' =>
                        "0.11",
                    'name' =>
                        "Console_Getopt",
                    'optional' =>
                        "0",
                    ),
                ),
            ),
        '1.0.1' =>
            array(
            'id' =>
                "372",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2003-01-10 01:26:00",
            'releasenotes' =>
                "* PEAR_Error class has call backtrace available by
  calling getBacktrace().  Available if used with
  PHP 4.3 or newer.

* PEAR_Config class uses getenv() rather than $_ENV
  to read environment variables.

* System::which() Windows fix, now looks for
  exe/bat/cmd/com suffixes rather than just exe

* Added \"pear cvsdiff\" command

* Windows output buffering bugfix for \"pear\" command",
            'state' =>
                "stable",
            ),
        '1.0' =>
            array(
            'id' =>
                "353",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-12-27 19:37:00",
            'releasenotes' =>
                "* set default cache_ttl to 1 hour
* added \"clear-cache\" command",
            'state' =>
                "stable",
            ),
        '1.0b3' =>
            array(
            'id' =>
                "345",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-12-13 02:24:00",
            'releasenotes' =>
                "* fixed \"info\" shortcut (conflicted with \"install\")
* added \"php_bin\" config parameter
* all \"non-personal\" config parameters now use
  environment variables for defaults (very useful
  to override the default php_dir on Windows!)",
            'state' =>
                "stable",
            ),
        '1.0b2' =>
            array(
            'id' =>
                "306",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-11-26 01:43:00",
            'releasenotes' =>
                "Changes, Installer:
* --force option no longer ignores errors, use
  --ignore-errors instead
* installer transactions: failed installs abort
  cleanly, without leaving half-installed packages
  around",
            'state' =>
                "stable",
            ),
        '1.0b1' =>
            array(
            'id' =>
                "259",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-10-12 14:21:00",
            'releasenotes' =>
                "New Features, Installer:
* new command: \"pear makerpm\"
* new command: \"pear search\"
* new command: \"pear upgrade-all\"
* new command: \"pear config-help\"
* new command: \"pear sign\"
* Windows support for \"pear build\" (requires
  msdev)
* new dependency type: \"zend\"
* XML-RPC results may now be cached (see
  cache_dir and cache_ttl config)
* HTTP proxy authorization support
* install/upgrade install-root support

Bugfixes, Installer:
* fix for XML-RPC bug that made some remote
  commands fail
* fix problems under Windows with
  DIRECTORY_SEPARATOR
* lots of other minor fixes
* --force option did not work for \"pear install
  Package\"
* http downloader used \"4.2.1\" rather than
  \"PHP/4.2.1\" as user agent
* bending over a little more to figure out how
  PHP is installed
* \"platform\" file attribute was not included
  during \"pear package\"

New Features, PEAR Library:
* added PEAR::loadExtension(\$ext)
* added PEAR::delExpect()
* System::mkTemp() now cleans up at shutdown
* defined PEAR_ZE2 constant (boolean)
* added PEAR::throwError() with a simpler API
  than raiseError()

Bugfixes, PEAR Library:
* ZE2 compatibility fixes
* use getenv() as fallback for \$_ENV",
            'state' =>
                "stable",
            ),
        '0.91-dev' =>
            array(
            'id' =>
                "140",
            'doneby' =>
                "cox",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-07-04 16:15:00",
            'releasenotes' =>
                "New Features:
* Added PEAR::loadExtension(\$ext) - OS independant PHP extension load
* System::mkTemp() automatically remove created tmp files/dirs at script shutdown
* New command \"pear search\"

Fixed Bugs:
* fix for XML-RPC bug that made some remote commands fail
* fix problems under Windows with the DIRECTORY_SEPARATOR
* lot of other minor fixes",
            'state' =>
                "beta",
            ),
        '0.90' =>
            array(
            'id' =>
                "117",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-06-06 11:34:00",
            'releasenotes' =>
                "* fix: \"help\" command was broken
* new command: \"info\"
* new command: \"config-help\"
* un-indent multi-line data from xml description files
* new command: \"build\"
* fix: config-set did not work with \"set\" parameters
* disable magic_quotes_runtime
* \"install\" now builds and installs C extensions
* added PEAR::delExpect()
* System class no longer inherits PEAR
* grouped PEAR_Config parameters
* add --nobuild option to install/upgrade commands
* new and more generic Frontend API",
            'state' =>
                "beta",
            ),
        '0.11' =>
            array(
            'id' =>
                "99",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-05-28 01:24:00",
            'releasenotes' =>
                "* fix: \"help\" command was broken
* new command: \"info\"
* new command: \"config-help\"
* un-indent multi-line data from xml description files
* new command: \"build\"
* fix: config-set did not work with \"set\" parameters
* disable magic_quotes_runtime",
            'state' =>
                "beta",
            ),
        '0.10' =>
            array(
            'id' =>
                "93",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-05-26 12:55:00",
            'releasenotes' =>
                "Lots of stuff this time.  0.9 was not actually self-hosting, even
though it claimed to be.  This version finally is self-hosting
(really!), meaning you can upgrade the installer with the command
\"pear upgrade PEAR\".

* new config paramers: http_proxy and umask
* HTTP proxy support when downloading packages
* generalized command handling code
* and fixed the bug that would not let commands have the
  same options as \"pear\" itself
* added long options to every command
* added command shortcuts (\"pear help shortcuts\")
* added stub for Gtk installer
* some phpdoc fixes
* added class dependency detector (using ext/tokenizer)
* dependency handling fixes
* added OS_Guess class for detecting OS
* install files with the \"platform\" attribute set
  only on matching operating systems
* PEAR_Remote now falls back to the XML_RPC package
  if xmlrpc-epi is not available
* renamed command: package-list -> list
* new command: package-dependencies
* lots of minor fixes",
            'state' =>
                "beta",
            ),
        '0.9' =>
            array(
            'id' =>
                "38",
            'doneby' =>
                "ssb",
            'license' =>
                "",
            'summary' =>
                "",
            'description' =>
                "",
            'releasedate' =>
                "2002-04-13 01:04:00",
            'releasenotes' =>
                "First package release.  Commands implemented:
   remote-package-info
   list-upgrades
   list-remote-packages
   download
   config-show
   config-get
   config-set
   list-installed
   shell-test
   install
   uninstall
   upgrade
   package
   package-list
   package-info
   login
   logout",
            'state' =>
                "stable",
            ),
        ),
    'notes' =>
        array(
        ),
    ));
$e = $command->run('remote-info', array(), array('Archive_Zip'));
$phpunit->assertNoErrors('Archive_Zip');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'packageid' => '252',
      'name' => 'Archive_Zip',
      'type' => 'pear',
      'categoryid' => '33',
      'category' => 'File Formats',
      'stable' => '',
      'license' => 'PHP License',
      'summary' => 'Zip file management class',
      'homepage' => '',
      'description' => 'This class provides handling of zip files in PHP.
It supports creating, listing, extracting and adding to zip files.',
      'cvs_link' => 'http://cvs.php.net/cvs.php/pear/Archive_Zip',
      'doc_link' => '',
      'releases' => 
      array (
      ),
      'notes' => 
      array (
      ),
      'installed' => '- no -',
    ),
    'cmd' => 'remote-info',
  ),
), $fakelog->getLog(), 'Archive_Zip log');
$e = $command->run('remote-info', array(), array('PEAR'));
$phpunit->assertNoErrors('PEAR');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'packageid' => '14',
      'name' => 'PEAR',
      'type' => 'pear',
      'categoryid' => '19',
      'category' => 'PEAR',
      'stable' => '1.3.3.1',
      'license' => 'PHP License',
      'summary' => 'PEAR Base System',
      'homepage' => '',
      'description' => 'The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception php5-only exception class
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling common operations
   with files and directories
 * the PEAR base class',
      'cvs_link' => 'http://cvs.php.net/cvs.php/pear-core/',
      'doc_link' => '',
      'releases' => 
      array (
        '1.3.3.1' => 
        array (
          'id' => '1803',
          'doneby' => 'cellog',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2004-11-12 02:04:57',
          'releasenotes' => 'add RunTest.php to package.xml, make run-tests display failed tests, and use ui',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'php',
              'relation' => 'ge',
              'version' => '4.2',
              'name' => 'PHP',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            4 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            5 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
        '1.3.3' => 
        array (
          'id' => '1772',
          'doneby' => 'cellog',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2004-10-28 13:40:34',
          'releasenotes' => 'Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common $_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role="doc"

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'php',
              'relation' => 'ge',
              'version' => '4.2',
              'name' => 'PHP',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            4 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            5 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
        '1.3.1' => 
        array (
          'id' => '1274',
          'doneby' => 'cellog',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2004-04-06 20:19:35',
          'releasenotes' => 'PEAR Installer:

 * Bug #534  pear search doesn\\\'t list unstable releases
 * Bug #933  CMD Usability Patch 
 * Bug #937  throwError() treats every call as static 
 * Bug #964 PEAR_ERROR_EXCEPTION causes fatal error 
 * Bug #1008 safe mode raises warning

PEAR_ErrorStack:

 * Added experimental error handling, designed to eventually replace
   PEAR_Error.  It should be considered experimental until explicitly marked
   stable.  require_once \\\'PEAR/ErrorStack.php\\\' to use.',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'php',
              'relation' => 'ge',
              'version' => '4.2',
              'name' => 'PHP',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            4 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            5 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
        '1.3' => 
        array (
          'id' => '1142',
          'doneby' => 'pajoye',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2004-02-20 10:40:19',
          'releasenotes' => 'PEAR Installer:

* Bug #171 --alldeps with a rel="eq" should install the required version, if possible
* Bug #249 installing from an url doesnt work
* Bug #248 --force command does not work as expected
* Bug #293 [Patch] PEAR_Error not calling static method callbacks for error-handler
* Bug #324 pear -G gives Fatal Error (PHP-GTK not installed, but error is at engine level)
* Bug #594 PEAR_Common::analyzeSourceCode fails on string with $var and {
* Bug #521 Incorrect filename in naming warnings
* Moved download code into its own class
* Fully unit tested the installer, packager, downloader, and PEAR_Common',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'php',
              'relation' => 'ge',
              'version' => '4.1',
              'name' => 'PHP',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            4 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            5 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
        '1.3b6' => 
        array (
          'id' => '1092',
          'doneby' => 'pajoye',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2004-01-25 20:57:03',
          'releasenotes' => 'PEAR Installer:

* Bug #171 --alldeps with a rel="eq" should install the required version, if possible
* Bug #249 installing from an url doesnt work
* Bug #248 --force command does not work as expected
* Bug #293 [Patch] PEAR_Error not calling static method callbacks for error-handler
* Bug #324 pear -G gives Fatal Error (PHP-GTK not installed, but error is at engine level)
* Bug #594 PEAR_Common::analyzeSourceCode fails on string with $var and {
* Bug #521 Incorrect filename in naming warnings
* Moved download code into its own class
* Fully unit tested the installer, packager, downloader, and PEAR_Common',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'php',
              'relation' => 'ge',
              'version' => '4.1',
              'name' => 'PHP',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            4 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            5 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
        '1.3b5' => 
        array (
          'id' => '1024',
          'doneby' => 'pajoye',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-12-19 09:44:01',
          'releasenotes' => 'PEAR Installer:

* Bug #171 --alldeps with a rel="eq" should install the required version, if possible
* Bug #249 installing from an url doesnt work
* Bug #248 --force command does not work as expected
* Bug #293 [Patch] PEAR_Error not calling static method callbacks for error-handler
* Bug #324 pear -G gives Fatal Error (PHP-GTK not installed, but error is at engine level)
* Moved download code into its own class
* Fully unit tested the installer, packager, downloader, and PEAR_Common',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.2',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xml',
              'optional' => '0',
            ),
            4 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'pcre',
              'optional' => '0',
            ),
          ),
        ),
        '1.3b3' => 
        array (
          'id' => '919',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-10-20 16:02:00',
          'releasenotes' => 'PEAR Installer:

* Bug #25413 Add local installed packages to list-all (Christian DickMann)
* Bug #23221 Pear installer - extension re-install segfault
* Better error detecting and reporting in "install/upgrade"
* Various other bugfixes and cleanups',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xmlrpc',
              'optional' => '0',
            ),
          ),
        ),
        '1.3b2' => 
        array (
          'id' => '887',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-10-02 11:53:00',
          'releasenotes' => 'PEAR Installer:

* Updated deps for Archive_Tar and Console_Getopt
* Fixed #45 preferred_state works incorrectly
* Fixed optional dependency attrib removed from
  package.xml, making them a requirement',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.1',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xmlrpc',
              'optional' => '0',
            ),
          ),
        ),
        '1.3b1' => 
        array (
          'id' => '875',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-09-29 13:19:00',
          'releasenotes' => 'PEAR Base Class:

* Fixed static calls to PEAR error-handling methods in classes
* Added ability to use a static method callback for error-handling,
  and removed use of inadvisable @ in setErrorHandling

PEAR Installer:

* Fixed #25117 - MD5 checksum should be case-insensitive
* Added dependency on XML_RPC, and optional dependency on xmlrpc extension
* Added --alldeps and --onlyreqdeps options to pear install/pear upgrade
* Sorting of installation/uninstallation so package order on the command-line is
  insignificant (fixes upgrade-all if every package is installed)
* pear upgrade will now install if the package is not installed (necessary for
  pear upgrade --alldeps, as installation is often necessary for new
  dependencies)
* fixed pear.bat if PHP is installed in a path like C:\\Program Files\\php
* Added ability to specify "pear install package-version" or
  "pear install package-state". For example: "pear install DB-1.2",
  or "pear install DB-stable"
* Fix #25008 - unhelpful error message
* Fixed optional dependencies in Dependency.php
* Fix #25322 - bad md5sum should be fatal error
* Package uninstall now also removes empty directories
* Fixed locking problems for reading commands (pear list, pear info)

OS_Guess Class:

* Fixed #25131 - OS_Guess warnings on empty lines from
  popen("/usr/bin/cpp $tmpfile", "r");

System Class:

* Fixed recursion deep param in _dirToStruct()
* Added the System::find() command (read API doc for more info)',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
            2 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '1.0.4',
              'name' => 'XML_RPC',
              'optional' => '0',
            ),
            3 => 
            array (
              'type' => 'ext',
              'relation' => 'has',
              'version' => '',
              'name' => 'xmlrpc',
              'optional' => '0',
            ),
          ),
        ),
        '1.2.1' => 
        array (
          'id' => '774',
          'doneby' => 'pajoye',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-08-15 13:48:00',
          'releasenotes' => '- Set back the default library path (BC issues)',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.2' => 
        array (
          'id' => '771',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-08-13 22:35:00',
          'releasenotes' => 'Changes from 1.1:

* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command "download-all" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Various bugfixes
* Added new pear "bundle" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.2b5' => 
        array (
          'id' => '753',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-08-05 16:32:00',
          'releasenotes' => '* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command "download-all" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Various bugfixes
* Added new pear "bundle" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.2b4' => 
        array (
          'id' => '752',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-08-05 03:26:00',
          'releasenotes' => '* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command "download-all" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Various bugfixes
* Added new pear "bundle" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.2b3' => 
        array (
          'id' => '750',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-08-03 19:45:00',
          'releasenotes' => '* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build pecl-package, now exposes the compilation progress
* Installer now checks dependencies on package uninstall
* Added proxy support for remote commands using the xmlrcp C ext (Adam Ashley)
* Added the command "download-all" (Alex Merz)
* Made package dependency checking back to work
* Added support for spaces in path names (Greg)
* Added new pear "bundle" command, which downloads and uncompress a PECL package.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.2b2' => 
        array (
          'id' => '675',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-06-23 13:33:00',
          'releasenotes' => '* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* Made upgrade and uninstall package case insensitive
* pear makerpm, now works and generates a better system independant spec file
* pear install|build <pecl-package>, now exposes the compilation progress
* Added new pear bundle command, which downloads and uncompress a <pecl-package>.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.2b1' => 
        array (
          'id' => '674',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-06-23 10:07:00',
          'releasenotes' => '* Changed license from PHP 2.02 to 3.0
* Added support for optional dependencies
* pear makerpm, now works and generates a better system independant spec file
* pear install|build <pecl-package>, now exposes the compilation progress
* Added new pear bundle command, which downloads and uncompress a <pecl-package>.
The main purpouse of this command is for easily adding extensions to the PHP sources
before compiling it.',
          'state' => 'beta',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.1' => 
        array (
          'id' => '587',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-05-10 23:27:00',
          'releasenotes' => 'PEAR BASE CLASS:

* PEAR_Error now supports exceptions when using Zend Engine 2.  Set the
  error mode to PEAR_ERROR_EXCEPTION to make PEAR_Error throw itself
  as an exception (invoke PEAR errors with raiseError() or throwError()
  just like before).

PEAR INSTALLER:

* Packaging and validation now parses PHP source code (unless
  ext/tokenizer is disabled) and does some coding standard conformance
  checks.  Specifically, the names of classes and functions are
  checked to ensure that they are prefixed with the package name.  If
  your package has symbols that should be without this prefix, you can
  override this warning by explicitly adding a "provides" entry in
  your package.xml file.  See the package.xml file for this release
  for an example (OS_Guess, System and md5_file).

  All classes and non-private (not underscore-prefixed) methods and
  functions are now registered during "pear package".',
          'state' => 'stable',
          'deps' => 
          array (
            0 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.4',
              'name' => 'Archive_Tar',
              'optional' => '0',
            ),
            1 => 
            array (
              'type' => 'pkg',
              'relation' => 'ge',
              'version' => '0.11',
              'name' => 'Console_Getopt',
              'optional' => '0',
            ),
          ),
        ),
        '1.0.1' => 
        array (
          'id' => '372',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2003-01-10 01:26:00',
          'releasenotes' => '* PEAR_Error class has call backtrace available by
  calling getBacktrace().  Available if used with
  PHP 4.3 or newer.

* PEAR_Config class uses getenv() rather than Array
  to read environment variables.

* System::which() Windows fix, now looks for
  exe/bat/cmd/com suffixes rather than just exe

* Added "pear cvsdiff" command

* Windows output buffering bugfix for "pear" command',
          'state' => 'stable',
        ),
        '1.0' => 
        array (
          'id' => '353',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-12-27 19:37:00',
          'releasenotes' => '* set default cache_ttl to 1 hour
* added "clear-cache" command',
          'state' => 'stable',
        ),
        '1.0b3' => 
        array (
          'id' => '345',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-12-13 02:24:00',
          'releasenotes' => '* fixed "info" shortcut (conflicted with "install")
* added "php_bin" config parameter
* all "non-personal" config parameters now use
  environment variables for defaults (very useful
  to override the default php_dir on Windows!)',
          'state' => 'stable',
        ),
        '1.0b2' => 
        array (
          'id' => '306',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-11-26 01:43:00',
          'releasenotes' => 'Changes, Installer:
* --force option no longer ignores errors, use
  --ignore-errors instead
* installer transactions: failed installs abort
  cleanly, without leaving half-installed packages
  around',
          'state' => 'stable',
        ),
        '1.0b1' => 
        array (
          'id' => '259',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-10-12 14:21:00',
          'releasenotes' => 'New Features, Installer:
* new command: "pear makerpm"
* new command: "pear search"
* new command: "pear upgrade-all"
* new command: "pear config-help"
* new command: "pear sign"
* Windows support for "pear build" (requires
  msdev)
* new dependency type: "zend"
* XML-RPC results may now be cached (see
  cache_dir and cache_ttl config)
* HTTP proxy authorization support
* install/upgrade install-root support

Bugfixes, Installer:
* fix for XML-RPC bug that made some remote
  commands fail
* fix problems under Windows with
  DIRECTORY_SEPARATOR
* lots of other minor fixes
* --force option did not work for "pear install
  Package"
* http downloader used "4.2.1" rather than
  "PHP/4.2.1" as user agent
* bending over a little more to figure out how
  PHP is installed
* "platform" file attribute was not included
  during "pear package"

New Features, PEAR Library:
* added PEAR::loadExtension($ext)
* added PEAR::delExpect()
* System::mkTemp() now cleans up at shutdown
* defined PEAR_ZE2 constant (boolean)
* added PEAR::throwError() with a simpler API
  than raiseError()

Bugfixes, PEAR Library:
* ZE2 compatibility fixes
* use getenv() as fallback for $_ENV',
          'state' => 'stable',
        ),
        '0.91-dev' => 
        array (
          'id' => '140',
          'doneby' => 'cox',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-07-04 16:15:00',
          'releasenotes' => 'New Features:
* Added PEAR::loadExtension($ext) - OS independant PHP extension load
* System::mkTemp() automatically remove created tmp files/dirs at script shutdown
* New command "pear search"

Fixed Bugs:
* fix for XML-RPC bug that made some remote commands fail
* fix problems under Windows with the DIRECTORY_SEPARATOR
* lot of other minor fixes',
          'state' => 'beta',
        ),
        '0.90' => 
        array (
          'id' => '117',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-06-06 11:34:00',
          'releasenotes' => '* fix: "help" command was broken
* new command: "info"
* new command: "config-help"
* un-indent multi-line data from xml description files
* new command: "build"
* fix: config-set did not work with "set" parameters
* disable magic_quotes_runtime
* "install" now builds and installs C extensions
* added PEAR::delExpect()
* System class no longer inherits PEAR
* grouped PEAR_Config parameters
* add --nobuild option to install/upgrade commands
* new and more generic Frontend API',
          'state' => 'beta',
        ),
        '0.11' => 
        array (
          'id' => '99',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-05-28 01:24:00',
          'releasenotes' => '* fix: "help" command was broken
* new command: "info"
* new command: "config-help"
* un-indent multi-line data from xml description files
* new command: "build"
* fix: config-set did not work with "set" parameters
* disable magic_quotes_runtime',
          'state' => 'beta',
        ),
        '0.10' => 
        array (
          'id' => '93',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-05-26 12:55:00',
          'releasenotes' => 'Lots of stuff this time.  0.9 was not actually self-hosting, even
though it claimed to be.  This version finally is self-hosting
(really!), meaning you can upgrade the installer with the command
"pear upgrade PEAR".

* new config paramers: http_proxy and umask
* HTTP proxy support when downloading packages
* generalized command handling code
* and fixed the bug that would not let commands have the
  same options as "pear" itself
* added long options to every command
* added command shortcuts ("pear help shortcuts")
* added stub for Gtk installer
* some phpdoc fixes
* added class dependency detector (using ext/tokenizer)
* dependency handling fixes
* added OS_Guess class for detecting OS
* install files with the "platform" attribute set
  only on matching operating systems
* PEAR_Remote now falls back to the XML_RPC package
  if xmlrpc-epi is not available
* renamed command: package-list -> list
* new command: package-dependencies
* lots of minor fixes',
          'state' => 'beta',
        ),
        '0.9' => 
        array (
          'id' => '38',
          'doneby' => 'ssb',
          'license' => '',
          'summary' => '',
          'description' => '',
          'releasedate' => '2002-04-13 01:04:00',
          'releasenotes' => 'First package release.  Commands implemented:
   remote-package-info
   list-upgrades
   list-remote-packages
   download
   config-show
   config-get
   config-set
   list-installed
   shell-test
   install
   uninstall
   upgrade
   package
   package-list
   package-info
   login
   logout',
          'state' => 'stable',
        ),
      ),
      'notes' => 
      array (
      ),
      'installed' => '- no -',
    ),
    'cmd' => 'remote-info',
  ),
), $fakelog->getLog(), 'PEAR log');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
