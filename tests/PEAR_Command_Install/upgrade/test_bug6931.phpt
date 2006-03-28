--TEST--
upgrade command, test for bug #6931
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pearweb->addRESTConfig("http://pear.php.net/rest/r/xml_rpc/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>XML_RPC</p>
 <c>pear.php.net</c>
 <r><v>1.4.5</v><s>stable</s></r>
 <r><v>1.4.4</v><s>stable</s></r>
 <r><v>1.4.3</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.1</max></co>
</r>
 <r><v>1.4.2</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a14</max></co>
</r>
 <r><v>1.4.1</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a14</max></co>
</r>
 <r><v>1.4.0</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a12</max></co>
</r>
 <r><v>1.3.3</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a12</max></co>
</r>
 <r><v>1.3.2</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a12</max></co>
</r>
 <r><v>1.3.1</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a12</max></co>
</r>
 <r><v>1.3.0</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a12</max></co>
</r>
 <r><v>1.3.0RC3</v><s>beta</s></r>
 <r><v>1.3.0RC2</v><s>beta</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a10</max></co>
</r>
 <r><v>1.3.0RC1</v><s>beta</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a10</max></co>
</r>
 <r><v>1.2.2</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a4</max></co>
</r>
 <r><v>1.2.1</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a2</max></co>
</r>
 <r><v>1.2.0</v><s>stable</s><co><c>pear.php.net</c><p>PEAR</p><min>1.4.0a1</min><max>1.4.0a1</max></co>
</r>
 <r><v>1.2.0RC7</v><s>beta</s></r>
 <r><v>1.2.0RC6</v><s>beta</s></r>
 <r><v>1.2.0RC5</v><s>beta</s></r>
 <r><v>1.2.0RC4</v><s>beta</s></r>
 <r><v>1.2.0RC3</v><s>beta</s></r>
 <r><v>1.2.0RC2</v><s>beta</s></r>
 <r><v>1.2.0RC1</v><s>beta</s></r>
 <r><v>1.1.0</v><s>stable</s></r>
 <r><v>1.0.4</v><s>stable</s></r>
 <r><v>1.0.3</v><s>stable</s></r>
 <r><v>1.0.2</v><s>stable</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/xml_rpc/1.4.5.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/xml_rpc">XML_RPC</p>
 <c>pear.php.net</c>
 <v>1.4.5</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>danielc</m>
 <s>PHP implementation of the XML-RPC protocol</s>
 <d>A PEAR-ified version of Useful Inc\'s XML-RPC for PHP.

It has support for HTTP/HTTPS transport, proxies and authentication.</d>
 <da>2006-01-14 17:34:28</da>
 <n>* Have server send headers individualy as opposed to sending them all at once. Necessary due to changes PHP 4.4.2.</n>
 <f>29172</f>
 <g>http://pear.php.net/get/XML_RPC-1.4.5</g>
 <x xlink:href="package.1.4.5.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/xml_rpc/deps.1.4.5.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"4.2.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:7:"1.4.0a1";}}}', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>PEAR</p>
 <c>pear.php.net</c>
 <r><v>1.4.8</v><s>stable</s></r>
 <r><v>1.4.7</v><s>stable</s></r>
 <r><v>1.4.6</v><s>stable</s></r>
 <r><v>1.4.5</v><s>stable</s></r>
 <r><v>1.4.4</v><s>stable</s></r>
 <r><v>1.4.3</v><s>stable</s></r>
 <r><v>1.4.2</v><s>stable</s></r>
 <r><v>1.4.1</v><s>stable</s></r>
 <r><v>1.4.0</v><s>stable</s></r>
 <r><v>1.4.0RC2</v><s>beta</s></r>
 <r><v>1.4.0RC1</v><s>beta</s></r>
 <r><v>1.4.0b2</v><s>beta</s></r>
 <r><v>1.4.0b1</v><s>beta</s></r>
 <r><v>1.3.6</v><s>stable</s></r>
 <r><v>1.4.0a12</v><s>alpha</s></r>
 <r><v>1.4.0a11</v><s>alpha</s></r>
 <r><v>1.4.0a10</v><s>alpha</s></r>
 <r><v>1.4.0a9</v><s>alpha</s></r>
 <r><v>1.4.0a8</v><s>alpha</s></r>
 <r><v>1.4.0a7</v><s>alpha</s></r>
 <r><v>1.4.0a6</v><s>alpha</s></r>
 <r><v>1.4.0a5</v><s>alpha</s></r>
 <r><v>1.4.0a4</v><s>alpha</s></r>
 <r><v>1.4.0a3</v><s>alpha</s></r>
 <r><v>1.4.0a2</v><s>alpha</s></r>
 <r><v>1.4.0a1</v><s>alpha</s></r>
 <r><v>1.3.5</v><s>stable</s></r>
 <r><v>1.3.4</v><s>stable</s></r>
 <r><v>1.3.3.1</v><s>stable</s></r>
 <r><v>1.3.3</v><s>stable</s></r>
 <r><v>1.3.1</v><s>stable</s></r>
 <r><v>1.3</v><s>stable</s></r>
 <r><v>1.3b6</v><s>beta</s></r>
 <r><v>1.3b5</v><s>beta</s></r>
 <r><v>1.3b3</v><s>beta</s></r>
 <r><v>1.3b2</v><s>beta</s></r>
 <r><v>1.3b1</v><s>beta</s></r>
 <r><v>1.2.1</v><s>stable</s></r>
 <r><v>1.2</v><s>stable</s></r>
 <r><v>1.2b5</v><s>beta</s></r>
 <r><v>1.2b4</v><s>beta</s></r>
 <r><v>1.2b3</v><s>beta</s></r>
 <r><v>1.2b2</v><s>beta</s></r>
 <r><v>1.2b1</v><s>beta</s></r>
 <r><v>1.1</v><s>stable</s></r>
 <r><v>1.0.1</v><s>stable</s></r>
 <r><v>1.0</v><s>stable</s></r>
 <r><v>1.0b3</v><s>stable</s></r>
 <r><v>1.0b2</v><s>stable</s></r>
 <r><v>1.0b1</v><s>stable</s></r>
 <r><v>0.90</v><s>beta</s></r>
 <r><v>0.11</v><s>beta</s></r>
 <r><v>0.10</v><s>beta</s></r>
 <r><v>0.9</v><s>stable</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear/1.4.0a12.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/pear">PEAR</p>
 <c>pear.php.net</c>
 <v>1.4.0a12</v>
 <st>alpha</st>
 <l>PHP License</l>
 <m>cellog</m>
 <s>PEAR Base System</s>
 <d>The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception PHP5 error handling mechanism
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling of common operations
   with files and directories
 * the PEAR base class</d>
 <da>2005-05-28 23:19:58</da>
 <n>This is a major milestone release for PEAR.  In addition to several killer features,
  every single element of PEAR has a regression test, and so stability is much higher
  than any previous PEAR release, even with the alpha label.

  New features in a nutshell:
  * full support for channels
  * pre-download dependency validation
  * new package.xml 2.0 format allows tremendous flexibility while maintaining BC
  * support for optional dependency groups and limited support for sub-packaging
  * robust dependency support
  * full dependency validation on uninstall
  * support for binary PECL packages
  * remote install for hosts with only ftp access - no more problems with
    restricted host installation
  * full support for mirroring
  * support for bundling several packages into a single tarball
  * support for static dependencies on a url-based package

  Specific changes from 1.3.5:
  * Implement request #1789: SSL support for xml-rpc and download
  * Everything above here that you just read

  Specific changes from 1.4.0a1:
  * Fix Bug #3610: fix for PDO package in 1.3.5 was never merged to 1.4.0a1
  * Fix Bug #3612: fatal error in PEAR_Downloader_Package
  * Use 1.2.0 as recommended version of XML_RPC

  Specific changes from 1.4.0a2:
 BC BREAK FOR PECL DEVS ONLY:
 In order to circumvent strict package-validation, use
 &quot;pear channel-update pecl.php.net&quot; prior to packaging
 a pecl release.
  * Fix package.xml version 2.0 generation from package.xml 1.0
  * Fix Bug #3634: still too many pear-specific restrictions on package valid
  * Implement Request #3647: &quot;pear package&quot; only includes one package.xml
  * Fix Bug #3677: Post-Install script message needs to display channel name

  Specific changes from 1.4.0a3:
  * upgrade suggested XML_RPC version to 1.2.1

  Specific changes from 1.4.0a4:
  * upgrade suggested XML_RPC version to 1.2.2
  * attempt to address memory issues
  * relax validation further
  * disable debug_backtrace() in PEAR_Error constructor of PEAR installer
  * fix a strange version number condition when two packages were upgraded at the same time.
  * fix Bug #3808 channel packages with non-baseinstalldir files will conflict on upgrade
  * fix Bug #3801 [PATCH] analyzeSourceCode() reports PHP4 code as PHP5
  * fix Bug #3671 Installing package features doesn\'t work as expected
  * implement Request #3717 [Patch] Implement Simple run-tests output

  Specific changes from 1.4.0a5:
  * fix Bug #3860 PEAR_Dependency2 not included in 1 case

  Specific changes from 1.4.0a6:
  * implement &lt;usesrole&gt;/&lt;usestask&gt; for custom role/task graceful failure
  * REALLY fix the debug_backtrace() issue (modified wrong pearcmd.php)
  * fix Bug #3864 Invalid dependency relation
  * fix Bug #3863 illogical warning about PEAR_Frontend_Gtk 0.4.0 with PEAR 1.4.0a6

  Specific changes from 1.4.0a7:
  * greatly improve the flexibility of post-install scripts
    - &lt;param&gt; is no longer required
    - skipParamgroup() method in Frontends allows dynamic manipulation of what input is
      requested from users
  * make error message when a user has no write access absolutely clear and unmistakable
  * update to new header comment block standard
  * slight improvement to speed and possibly memory use of Installer when a lot of
    package.xml version 1.0 packages are installed
  * add &quot;peardev&quot; command for those with memory_limit issue
  * make package-validate command work on packaged .tgz files

  Specific changes from 1.4.0a8:
  * add --package option to run-tests command, to run installed .phpt tests
  * significantly drop pear\'s memory footprint for all commands
  * fix fatal errors when installing pecl packages
  * make download command work for non-root in a shared environment
  * make sure that if 1.4.0a8 (alpha) is installed, and 1.3.6 (newer) exists, pear will not
    attempt to &quot;upgrade&quot; to 1.3.6
  * split PEAR_PackageFile_v2 into two classes, read-only PEAR_PackageFile_v2, and read-write
    PEAR_PackageFile_v2_rw

  Specific changes from 1.4.0a9:
  * add support for writeable tasks
  * fix potential fatal errors in run-tests command, -p option
  * fix --installroot option for installation
  * move run-tests command into its own file (testing may expand)
  * fix fatal error if package.xml has no version=&quot;X.0&quot;
  * fix Bug #3966: Improper path in PEAR/PackageFile/v2.php
  * fix Bug #3990: PEAR_Error PEAR_EXCEPTION broken
  * fix Bug #4021: PEAR_Config file_exists can cause warnings
  * fix Bug #1870: pear makerpm dependancies
  * fix Bug #4038: Array to string conversion in PEAR/Frontend/CLI.php
  * fix Bug #4060: pear upgrade Auth_HTTP fails
  * fix Bug #4072: pear list-all -c channel does not list installed packages

  Specific changes from 1.4.0a10:
  * Add new &quot;unusualbaseinstall&quot; role type that allows custom roles similar
    data/test/doc to honor the baseinstalldir attribute
  * fix Bug #4095: System::rm does not handle links correctly
  * fix Bug #4097: Wrong logging in PEAR_Command_Test
  * make pear/pecl commands list only pear/pecl packages
  * fix Bug #4161: pear download always leaves a package.xml in the dir
  * make PEAR_Remote messages more helpful (include server name)
  * make list-upgrades only search channels from which we have installed packages
  * remove &lt;max&gt; tag requirement for php dependency

  Specific changes from 1.4.0a11:
  * Implement REST 1.0 as per Request #2781
  * REST is the default connection method if available
  * fix bugs in PEAR_ChannelFile REST handling
  * fix Bug #4069: pear list-all -c &lt;ChannelAlias&gt; does not work
  * fix Bug #4249: download-all broken in 1.4.0a11
  * fix Bug #4257: if rel=&quot;has&quot; is used with a version=&quot;&quot; attribute, the warning does not work
  * fix Bug #4278: Parser V1: error handling borked !
  * fix Bug #4279: Typo in DependencyDB (_version)
  * fix Bug #4285: pear install *.tgz miss dependencies
  * fix Bug #4353: fatal error if using remote_config variable
  * fix Bug #4354: Remote PEAR upgrade and uninstall operation fail
  * fix Bug #4355: PEAR 1.4.0a11 ftpInstall chokes on package2.xml packages
  * fix Bug #4400: pear download chiara/Chiara_XML_RPC5-alpha fails
  * fix Bug #4458: packaging error message better description
  * implement Request #2781: support for static channel releases.xml summary
  * implement versioned conflicting dependencies
  * fix major problems in subpackages
  * The next version will split off PEAR_ErrorStack into its own package
  * fix problems with zero-length files that have tasks on installation
  * add a check for channel.xml up-to-dateness and gentle warning to users on
    installing a package from that channel</n>
 <f>264326</f>
 <g>http://pear.php.net/get/PEAR-1.4.0a12</g>
 <x xlink:href="package.1.4.0a12.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear/deps.1.4.0a12.txt", 'a:3:{s:8:"required";a:4:{s:3:"php";a:2:{s:3:"min";s:3:"4.2";s:3:"max";s:5:"6.0.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:8:"1.4.0a10";}s:7:"package";a:3:{i:0;a:5:{s:4:"name";s:11:"Archive_Tar";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:3:"1.1";s:11:"recommended";s:5:"1.3.1";s:7:"exclude";s:5:"1.3.0";}i:1;a:4:{s:4:"name";s:14:"Console_Getopt";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:3:"1.2";s:11:"recommended";s:3:"1.2";}i:2;a:4:{s:4:"name";s:7:"XML_RPC";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:8:"1.2.0RC1";s:11:"recommended";s:5:"1.2.2";}}s:9:"extension";a:2:{i:0;a:1:{s:4:"name";s:3:"xml";}i:1;a:1:{s:4:"name";s:4:"pcre";}}}s:8:"optional";a:1:{s:7:"package";a:2:{i:0;a:3:{s:4:"name";s:17:"PEAR_Frontend_Web";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"0.5.0";}i:1;a:3:{s:4:"name";s:17:"PEAR_Frontend_Gtk";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"0.4.0";}}}s:5:"group";a:3:{i:0;a:2:{s:7:"attribs";a:2:{s:4:"hint";s:59:"adds the ability to install packages to a remote ftp server";s:4:"name";s:13:"remoteinstall";}s:7:"package";a:4:{s:4:"name";s:7:"Net_FTP";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:8:"1.3.0RC1";s:11:"recommended";s:5:"1.3.1";}}i:1;a:2:{s:7:"attribs";a:2:{s:4:"hint";s:26:"PEAR\'s web-based installer";s:4:"name";s:12:"webinstaller";}s:7:"package";a:3:{s:4:"name";s:17:"PEAR_Frontend_Web";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"0.5.0";}}i:2;a:2:{s:7:"attribs";a:2:{s:4:"hint";s:30:"PEAR\'s PHP-GTK-based installer";s:4:"name";s:12:"gtkinstaller";}s:7:"package";a:3:{s:4:"name";s:17:"PEAR_Frontend_Gtk";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"0.4.0";}}}}', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/archive_tar/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Archive_Tar</p>
 <c>pear.php.net</c>
 <r><v>1.3.1</v><s>stable</s></r>
 <r><v>1.3.0</v><s>stable</s></r>
 <r><v>1.2</v><s>stable</s></r>
 <r><v>1.1</v><s>stable</s></r>
 <r><v>1.0</v><s>stable</s></r>
 <r><v>0.10-b1</v><s>beta</s></r>
 <r><v>0.9</v><s>stable</s></r>
 <r><v>0.4</v><s>stable</s></r>
 <r><v>0.3</v><s>stable</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/archive_tar/1.3.1.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/archive_tar">Archive_Tar</p>
 <c>pear.php.net</c>
 <v>1.3.1</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>vblavet</m>
 <s>Tar file management class</s>
 <d>This class provides handling of tar files in PHP.
It supports creating, listing, extracting and adding to tar files.
Gzip support is available if PHP has the zlib extension built-in or
loaded. Bz2 compression is also supported with the bz2 extension loaded.
</d>
 <da>2005-03-17 16:09:16</da>
 <n>Correct Bug #3855
</n>
 <f>15102</f>
 <g>http://pear.php.net/get/Archive_Tar-1.3.1</g>
 <x xlink:href="package.1.3.1.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/archive_tar/deps.1.3.1.txt", 'b:0;', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/console_getopt/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Console_Getopt</p>
 <c>pear.php.net</c>
 <r><v>1.2</v><s>stable</s></r>
 <r><v>1.0</v><s>stable</s></r>
 <r><v>0.11</v><s>beta</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/console_getopt/1.2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/console_getopt">Console_Getopt</p>
 <c>pear.php.net</c>
 <v>1.2</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>andrei</m>
 <s>Command-line option parser</s>
 <d>This is a PHP implementation of &quot;getopt&quot; supporting both
short and long options.
</d>
 <da>2003-12-11 14:26:46</da>
 <n>Fix to preserve BC with 1.0 and allow correct behaviour for new users
</n>
 <f>3370</f>
 <g>http://pear.php.net/get/Console_Getopt-1.2</g>
 <x xlink:href="package.1.2.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/console_getopt/deps.1.2.txt", 'b:0;', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/xml_rpc/1.4.3.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/xml_rpc">XML_RPC</p>
 <c>pear.php.net</c>
 <v>1.4.3</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>danielc</m>
 <s>PHP implementation of the XML-RPC protocol</s>
 <d>A PEAR-ified version of Useful Inc\'s XML-RPC for PHP.

It has support for HTTP/HTTPS transport, proxies and authentication.</d>
 <da>2005-09-24 14:22:55</da>
 <n>* Make XML_RPC_encode() properly handle dateTime.iso8601.  Request 5117.</n>
 <f>27198</f>
 <g>http://pear.php.net/get/XML_RPC-1.4.3</g>
 <x xlink:href="package.1.4.3.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/xml_rpc/deps.1.4.3.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"4.2.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:7:"1.4.0a1";}}}', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear_frontend_web/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>PEAR_Frontend_Web</p>
 <c>pear.php.net</c>
 <r><v>0.5.0</v><s>alpha</s></r>
 <r><v>0.4</v><s>beta</s></r>
 <r><v>0.3</v><s>beta</s></r>
 <r><v>0.2.2</v><s>beta</s></r>
 <r><v>0.2.1</v><s>beta</s></r>
 <r><v>0.2</v><s>beta</s></r>
 <r><v>0.1</v><s>beta</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear_frontend_web/0.5.0.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/pear_frontend_web">PEAR_Frontend_Web</p>
 <c>pear.php.net</c>
 <v>0.5.0</v>
 <st>alpha</st>
 <l>PHP License</l>
 <m>pajoye</m>
 <s>HTML (Web) PEAR Installer</s>
 <d>Web Interface to the PEAR Installer

</d>
 <da>2006-03-01 22:53:24</da>
 <n>Major features addition: channel support
Also, support for post-install scripts on install, and package.xml 2.0

</n>
 <f>38606</f>
 <g>http://pear.php.net/get/PEAR_Frontend_Web-0.5.0</g>
 <x xlink:href="package.0.5.0.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear_frontend_web/deps.0.5.0.txt", 'a:3:{i:1;a:3:{s:4:"type";s:3:"pkg";s:3:"rel";s:3:"has";s:4:"name";s:20:"Net_UserAgent_Detect";}i:2;a:3:{s:4:"type";s:3:"pkg";s:3:"rel";s:3:"has";s:4:"name";s:16:"HTML_Template_IT";}i:3;a:4:{s:4:"type";s:3:"pkg";s:3:"rel";s:2:"ge";s:7:"version";s:7:"1.4.0a1";s:4:"name";s:4:"PEAR";}}', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear_frontend_gtk/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>PEAR_Frontend_Gtk</p>
 <c>pear.php.net</c>
 <r><v>0.4.0</v><s>beta</s></r>
 <r><v>0.3</v><s>beta</s></r>
 <r><v>0.2</v><s>snapshot</s></r>
 <r><v>0.1</v><s>snapshot</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear_frontend_gtk/0.4.0.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/pear_frontend_gtk">PEAR_Frontend_Gtk</p>
 <c>pear.php.net</c>
 <v>0.4.0</v>
 <st>beta</st>
 <l>PHP License</l>
 <m>alan_k</m>
 <s>Gtk (Desktop) PEAR Package Manager</s>
 <d>Desktop Interface to the PEAR Package Manager, Requires PHP-GTK

</d>
 <da>2005-03-14 02:22:46</da>
 <n>Implement channels, support PEAR 1.4.0 (Greg Beaver)
    Tidy up logging a little.

</n>
 <f>69762</f>
 <g>http://pear.php.net/get/PEAR_Frontend_Gtk-0.4.0</g>
 <x xlink:href="package.0.4.0.xml"/>
</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/pear_frontend_gtk/deps.0.4.0.txt", 'b:0;', 'text/xml');
$dir = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR;
$pearweb->addHTMLConfig('http://pear.php.net/get/PEAR-1.4.0a12.tgz', $dir . 'PEAR-1.4.0a12.tgz');
$chan = $reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$chan->setBaseURL('REST1.1', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setPEARVersion('1.4.8');
$_test_dep->setExtensions(array('xml' => 0, 'pcre' => 1));
$command->run('install', array(), array($dir . 'PEAR-1.4.8.tgz',
    $dir . 'Console_Getopt-1.2.tgz', $dir . 'Archive_Tar-1.3.1.tgz', $dir . 'XML_RPC-1.4.3.tgz'));
$phpunit->assertNoErrors('setup');
$phpunit->assertEquals(4, count($reg->listPackages()), 'num packages');

$fakelog->getLog();
$fakelog->getDownload();
unset($GLOBALS['__Stupid_php4_a']); // reset downloader
$command->run('upgrade', array(), array('PEAR-alpha'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'upgrade failed')
), 'full test');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 0,
    1 => 'pear/PEAR is already installed and is newer than detected release version 1.4.0a12',
  ),
  1 => 
  array (
    0 => 0,
    1 => 'Cannot initialize \'PEAR-alpha\', invalid or missing package file',
  ),
  2 => 
  array (
    'info' => 
    array (
      'data' => 
      array (
        0 => 
        array (
          0 => 'Package "PEAR-alpha" is not valid',
        ),
      ),
      'headline' => 'Install Errors',
    ),
    'cmd' => 'no command',
  ),
  3 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $GLOBALS['__Stupid_php4_a']->getDownloadDir(),
  ),
), $fakelog->getLog(), 'bug part');
$phpunit->assertEquals(array(), $fakelog->getDownload(), 'download bug part');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
