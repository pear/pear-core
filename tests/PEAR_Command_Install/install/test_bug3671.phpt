--TEST--
install command, bug 3671
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
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'bug3671_1.xml';
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '2.0',
  1 => 
  array (
    'name' => 'PEAR_Frontend_Web',
    'channel' => 'pear.php.net',
    'min' => '0.5.0',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
    'version' => '1.4.0a5',
  ),
  3 => 'stable',
), array (
  'version' => '0.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>PEAR_Frontend_Web</name>
  <summary>HTML (Web) PEAR Package Manager</summary>
  <description>Web Interface to the PEAR Package Manager</description>
  <maintainers>
    <maintainer>
      <user>dickmann</user>
      <name>Christian Dickmann</name>
      <email>dickmann@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>pajoye</user>
      <name>Pierre-Alain Joye</name>
      <email>pajoye@pearfr.org</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>ssb</user>
      <name>Stig S?ther Bakken</name>
      <email>stig@php.net</email>
      <role>helper</role>
    </maintainer>
  </maintainers>
  <release>
    <version>0.4</version>
    <date>2003-06-07</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>Bugfixes release:
- Remove Pager dep
- Should work well on non apache system (ie IIS)
- The \'installed packages\' is now the entry page
  (no more remote connection during startup)</notes>
    <deps>
      <dep type="pkg" rel="has">Net_UserAgent_Detect</dep>
      <dep type="pkg" rel="has">HTML_Template_IT</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="PEAR" name="WebInstaller.php"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web.php"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/config.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/download.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/error.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/info.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/infoplus.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install_fail.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install_ok.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install_wait.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/login.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/logout.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/manual.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/pearsmall.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/trash.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/package.jpg"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/category.jpg"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/pkglist.png"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/pkgsearch.png"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/style.css"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/dhtml.css"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/dhtml.js"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/nodhtml.js"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/bottom.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/error.popup.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/error.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/footer.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/header.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/package.info.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/package.list.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/start.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/top.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/userDialog.tpl.html"/>
      <file role="doc" baseinstalldir="PEAR" name="docs/example.php">
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@bin_dir@" to="bin_dir" type="pear-config"/>
        <replace from="@php_dir@" to="php_dir" type="pear-config"/>
        <replace from="@pear_version@" to="version" type="package-info"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>0.3</version>
      <date>2003-05-28</date>
      <notes>Bugfixes release:
- Fix a bug while using both CLI and Web installer
  (Invalid characters, bug #23516)
- Installs correctly the CLI tools

</notes>
    </release>
  </changelog>
</package>',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '2.0',
  1 => 
  array (
    'name' => 'PEAR_Frontend_Gtk',
    'channel' => 'pear.php.net',
    'min' => '0.4.0',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
    'version' => '1.4.0a5',
  ),
  3 => 'stable',
), array (
  'version' => '0.4.0',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0a4">
 <name>PEAR_Frontend_Gtk</name>
 <summary>Gtk (Desktop) PEAR Package Manager</summary>
 <description>Desktop Interface to the PEAR Package Manager, Requires PHP-GTK
 </description>
 <maintainers>
  <maintainer>
   <user>alan_k</user>
   <name>Alan Knowles</name>
   <email>alan@akbkhome.com</email>
   <role>lead</role>
  </maintainer>
  <maintainer>
   <user>cellog</user>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
   <role>developer</role>
  </maintainer>
  <maintainer>
   <user>ssb</user>
   <name>Stig Bakken</name>
   <email>stig@php.net</email>
   <role>helper</role>
  </maintainer>
  </maintainers>
 <release>
  <version>0.4.0</version>
  <date>2005-03-14</date>
  <license>PHP License</license>
  <state>beta</state>
  <notes>Implement channels, support PEAR 1.4.0 (Greg Beaver)
    Tidy up logging a little.
  </notes>
  <provides type="class" name="PEAR_Frontend_Gtk" extends="PEAR_Frontend" />
  <provides type="function" name="PEAR_Frontend_Gtk::setConfig" />
  <provides type="class" name="PEAR_Frontend_Gtk_Config" />
  <provides type="function" name="PEAR_Frontend_Gtk_Config::loadConfig" />
  <provides type="function" name="PEAR_Frontend_Gtk_Config::buildConfig" />
  <provides type="class" name="PEAR_Frontend_Gtk_DirSelect" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onDirSelect" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onDirListSelectRow" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onDirListClick" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onCancel" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onOk" />
  <provides type="class" name="PEAR_Frontend_Gtk_Documentation" />
  <provides type="function" name="PEAR_Frontend_Gtk_Documentation::init" />
  <provides type="class" name="PEAR_Frontend_Gtk_Info" />
  <provides type="function" name="PEAR_Frontend_Gtk_Info::show" />
  <provides type="function" name="PEAR_Frontend_Gtk_Info::close" />
  <provides type="class" name="PEAR_Frontend_Gtk_Install" />
  <provides type="function" name="PEAR_Frontend_Gtk_Install::start" />
  <provides type="class" name="PEAR_Frontend_Gtk_PackageData" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::staticNewFromArray" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::merge" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::createNode" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::toggleRemove" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::toggleInstall" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::doQueue" />
  <provides type="class" name="PEAR_Frontend_Gtk_Packages" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::callbackSelectRow" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::expandAll" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::collapseAll" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::resetQueue" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::getQueue" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::loadPackageList" />
  <provides type="class" name="PEAR_Frontend_Gtk_Summary" />
  <provides type="function" name="PEAR_Frontend_Gtk_Summary::show" />
  <provides type="function" name="PEAR_Frontend_Gtk_Summary::toggle" />
  <provides type="function" name="PEAR_Frontend_Gtk_Summary::hide" />
  <provides type="class" name="PEAR_Frontend_Gtk_WidgetHTML" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::loadURL" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::loadTEXT" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::tokenize" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::build" />
  <filelist>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="3edf486653e75f4aac78c1d9d52e72ad" name="Gtk.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="d9847b8a0f3e2987f250c5112ef4032a" name="Gtk/Config.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="fc7320b7946a0e14b57c362e5fc4850a" name="Gtk/DirSelect.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="670090b44d1b3bfb721d5ed3086ecc04" name="Gtk/Documentation.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="9f7d56224b422250f09ad461789c3c7e" name="Gtk/Info.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="014545ba8b75edb68e6ca3b3aacdf4b9" name="Gtk/Install.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="63364265a332cf68a371e829451c1ec3" name="Gtk/PackageData.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="c26ac0ac6b0c8ae147c66b183349ec61" name="Gtk/Packages.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="f97939e86e7cebd5d5cd920a7b4f4e8e" name="Gtk/Summary.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="334c5172abe2cf83ead4aa8bd893502b" name="Gtk/WidgetHTML.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="5e2cc97f605b511bb42775117b2fe9da" name="Gtk/installer.glade"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="a65ef13fb4c2080404b465af28f67fe3" name="Gtk/xpm/black_close_icon.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="bcf01d6ad7db72033b549a71fc300ad0" name="Gtk/xpm/check_no.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="cf7bb81fd9067efa3a43cb4546610a90" name="Gtk/xpm/check_yes.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="3d075af534dc7474d30fc5213d38e55d" name="Gtk/xpm/downloading_image.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="1c0a9cf7ec836d67f090789c7ea51175" name="Gtk/xpm/folder_closed.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="1c0a9cf7ec836d67f090789c7ea51175" name="Gtk/xpm/folder_open.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="d2f693edec6a3a72410bbfe2635cca4c" name="Gtk/xpm/info_icon.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="14b1710cb02528017e352f2c9bb77c79" name="Gtk/xpm/nav_configuration.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="7a50b5f799249969ebde6aedbce9dbd6" name="Gtk/xpm/nav_documentation.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="11231b7afd52573bdb7b466e4ae575a4" name="Gtk/xpm/nav_installer.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="922ab909b15f2eeb85097c341dc9c45a" name="Gtk/xpm/package.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="195fe43665f08098ae0f59af1a471497" name="Gtk/xpm/pear.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="81cc4d8580e7a9000f8f151dc56f3769" name="Gtk/xpm/stock_delete-16.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="031b0d5f66e38031d54cd8ae89631cfe" name="Gtk/xpm/stock_delete-outline-16.xpm"/>
  </filelist>
 </release>
 <changelog>
   <release>
    <version>0.3</version>
    <date>2002-07-25</date>
    <state>beta</state>
    <notes>Attempt to fix package file so it installs,
           some of the warnings have been fixed
    </notes>
   </release>
   <release>
    <version>0.2</version>
    <date>2002-06-16</date>
    <state>snapshot</state>
    <notes>Snapshot - First Working Version
    </notes>
   </release>
   <release>
    <version>0.1</version>
    <date>2002-06-13</date>
    <state>snapshot</state>
    <notes>Snapshot - dont expect it to work
    </notes>
   </release>
 </changelog>
</package>',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'pear',
    'channel' => 'pear.php.net',
    'group' => 'webinstaller',
  ),
  1 => 'alpha',
), array (
  'version' => '1.4.0a5',
  'info' => '<?xml version="1.0"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>PEAR</name>
 <channel>pear.php.net</channel>
 <summary>PEAR Base System</summary>
 <description>The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception PHP5 error handling mechanism
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling of common operations
   with files and directories
 * the PEAR base class
 </description>
 <lead>
  <name>Stig Bakken</name>
  <user>ssb</user>
  <email>stig@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Tomas V.V.Cox</name>
  <user>cox</user>
  <email>cox@idecnet.com</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Pierre-Alain Joye</name>
  <user>pajoye</user>
  <email>pajoye@pearfr.org</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Greg Beaver</name>
  <user>cellog</user>
  <email>cellog@php.net</email>
  <active>yes</active>
 </lead>
 <developer>
  <name>Martin Jansen</name>
  <user>mj</user>
  <email>mj@php.net</email>
  <active>yes</active>
 </developer>
 <date>2005-03-12</date>
 <version>
  <release>1.4.0a5</release>
  <api>1.4.0</api>
 </version>
 <stability>
  <release>alpha</release>
  <api>alpha</api>
 </stability>
 <license uri="http://www.php.net/license">PHP License</license>
 <notes>
  This is a major milestone release for PEAR.  In addition to several killer features,
 </notes>
 <contents>
  <dir name="/">
   <file name="foo.php" role="php" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.2</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0dev13</min>
   </pearinstaller>
  </required>
  <optional>
   <package>
    <name>PEAR_Frontend_Web</name>
    <channel>pear.php.net</channel>
    <min>0.5.0</min>
   </package>
   <package>
    <name>PEAR_Frontend_Gtk</name>
    <channel>pear.php.net</channel>
    <min>0.4.0</min>
   </package>
  </optional>
  <group name="webinstaller" hint="PEAR\'s web-based installer">
   <package>
    <name>PEAR_Frontend_Web</name>
    <channel>pear.php.net</channel>
    <min>0.5.0</min>
   </package>
  </group>
  <group name="gtkinstaller" hint="PEAR\'s PHP-GTK-based installer">
   <package>
    <name>PEAR_Frontend_Gtk</name>
    <channel>pear.php.net</channel>
    <min>0.4.0</min>
   </package>
  </group>
 </dependencies>
 <phprelease/>
</package>',
  'url' => 'http://pear.php.net/get/pear-1.4.0a4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '2.0',
  1 => 
  array (
    'name' => 'PEAR_Frontend_Web',
    'channel' => 'pear.php.net',
    'min' => '0.5.0',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
    'version' => '1.4.0a5',
  ),
  3 => 'alpha',
), array (
  'version' => '0.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>PEAR_Frontend_Web</name>
  <summary>HTML (Web) PEAR Package Manager</summary>
  <description>Web Interface to the PEAR Package Manager</description>
  <maintainers>
    <maintainer>
      <user>dickmann</user>
      <name>Christian Dickmann</name>
      <email>dickmann@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>pajoye</user>
      <name>Pierre-Alain Joye</name>
      <email>pajoye@pearfr.org</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>ssb</user>
      <name>Stig S?ther Bakken</name>
      <email>stig@php.net</email>
      <role>helper</role>
    </maintainer>
  </maintainers>
  <release>
    <version>0.4</version>
    <date>2003-06-07</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>Bugfixes release:
- Remove Pager dep
- Should work well on non apache system (ie IIS)
- The \'installed packages\' is now the entry page
  (no more remote connection during startup)</notes>
    <deps>
      <dep type="pkg" rel="has">Net_UserAgent_Detect</dep>
      <dep type="pkg" rel="has">HTML_Template_IT</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="PEAR" name="WebInstaller.php"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web.php"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/config.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/download.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/error.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/info.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/infoplus.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install_fail.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install_ok.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/install_wait.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/login.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/logout.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/manual.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/pearsmall.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/trash.gif"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/package.jpg"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/category.jpg"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/pkglist.png"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/pkgsearch.png"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/style.css"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/dhtml.css"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/dhtml.js"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/nodhtml.js"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/bottom.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/error.popup.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/error.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/footer.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/header.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/package.info.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/package.list.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/start.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/top.inc.tpl.html"/>
      <file role="php" baseinstalldir="PEAR" name="Frontend/Web/userDialog.tpl.html"/>
      <file role="doc" baseinstalldir="PEAR" name="docs/example.php">
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@bin_dir@" to="bin_dir" type="pear-config"/>
        <replace from="@php_dir@" to="php_dir" type="pear-config"/>
        <replace from="@pear_version@" to="version" type="package-info"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>0.3</version>
      <date>2003-05-28</date>
      <notes>Bugfixes release:
- Fix a bug while using both CLI and Web installer
  (Invalid characters, bug #23516)
- Installs correctly the CLI tools

</notes>
    </release>
  </changelog>
</package>',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '2.0',
  1 => 
  array (
    'name' => 'PEAR_Frontend_Gtk',
    'channel' => 'pear.php.net',
    'min' => '0.4.0',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
    'version' => '1.4.0a5',
  ),
  3 => 'alpha',
), array (
  'version' => '0.4.0',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0a4">
 <name>PEAR_Frontend_Gtk</name>
 <summary>Gtk (Desktop) PEAR Package Manager</summary>
 <description>Desktop Interface to the PEAR Package Manager, Requires PHP-GTK
 </description>
 <maintainers>
  <maintainer>
   <user>alan_k</user>
   <name>Alan Knowles</name>
   <email>alan@akbkhome.com</email>
   <role>lead</role>
  </maintainer>
  <maintainer>
   <user>cellog</user>
   <name>Greg Beaver</name>
   <email>cellog@php.net</email>
   <role>developer</role>
  </maintainer>
  <maintainer>
   <user>ssb</user>
   <name>Stig Bakken</name>
   <email>stig@php.net</email>
   <role>helper</role>
  </maintainer>
  </maintainers>
 <release>
  <version>0.4.0</version>
  <date>2005-03-14</date>
  <license>PHP License</license>
  <state>beta</state>
  <notes>Implement channels, support PEAR 1.4.0 (Greg Beaver)
    Tidy up logging a little.
  </notes>
  <provides type="class" name="PEAR_Frontend_Gtk" extends="PEAR_Frontend" />
  <provides type="function" name="PEAR_Frontend_Gtk::setConfig" />
  <provides type="class" name="PEAR_Frontend_Gtk_Config" />
  <provides type="function" name="PEAR_Frontend_Gtk_Config::loadConfig" />
  <provides type="function" name="PEAR_Frontend_Gtk_Config::buildConfig" />
  <provides type="class" name="PEAR_Frontend_Gtk_DirSelect" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onDirSelect" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onDirListSelectRow" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onDirListClick" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onCancel" />
  <provides type="function" name="PEAR_Frontend_Gtk_DirSelect::onOk" />
  <provides type="class" name="PEAR_Frontend_Gtk_Documentation" />
  <provides type="function" name="PEAR_Frontend_Gtk_Documentation::init" />
  <provides type="class" name="PEAR_Frontend_Gtk_Info" />
  <provides type="function" name="PEAR_Frontend_Gtk_Info::show" />
  <provides type="function" name="PEAR_Frontend_Gtk_Info::close" />
  <provides type="class" name="PEAR_Frontend_Gtk_Install" />
  <provides type="function" name="PEAR_Frontend_Gtk_Install::start" />
  <provides type="class" name="PEAR_Frontend_Gtk_PackageData" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::staticNewFromArray" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::merge" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::createNode" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::toggleRemove" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::toggleInstall" />
  <provides type="function" name="PEAR_Frontend_Gtk_PackageData::doQueue" />
  <provides type="class" name="PEAR_Frontend_Gtk_Packages" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::callbackSelectRow" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::expandAll" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::collapseAll" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::resetQueue" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::getQueue" />
  <provides type="function" name="PEAR_Frontend_Gtk_Packages::loadPackageList" />
  <provides type="class" name="PEAR_Frontend_Gtk_Summary" />
  <provides type="function" name="PEAR_Frontend_Gtk_Summary::show" />
  <provides type="function" name="PEAR_Frontend_Gtk_Summary::toggle" />
  <provides type="function" name="PEAR_Frontend_Gtk_Summary::hide" />
  <provides type="class" name="PEAR_Frontend_Gtk_WidgetHTML" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::loadURL" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::loadTEXT" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::tokenize" />
  <provides type="function" name="PEAR_Frontend_Gtk_WidgetHTML::build" />
  <filelist>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="3edf486653e75f4aac78c1d9d52e72ad" name="Gtk.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="d9847b8a0f3e2987f250c5112ef4032a" name="Gtk/Config.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="fc7320b7946a0e14b57c362e5fc4850a" name="Gtk/DirSelect.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="670090b44d1b3bfb721d5ed3086ecc04" name="Gtk/Documentation.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="9f7d56224b422250f09ad461789c3c7e" name="Gtk/Info.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="014545ba8b75edb68e6ca3b3aacdf4b9" name="Gtk/Install.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="63364265a332cf68a371e829451c1ec3" name="Gtk/PackageData.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="c26ac0ac6b0c8ae147c66b183349ec61" name="Gtk/Packages.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="f97939e86e7cebd5d5cd920a7b4f4e8e" name="Gtk/Summary.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="334c5172abe2cf83ead4aa8bd893502b" name="Gtk/WidgetHTML.php"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="5e2cc97f605b511bb42775117b2fe9da" name="Gtk/installer.glade"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="a65ef13fb4c2080404b465af28f67fe3" name="Gtk/xpm/black_close_icon.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="bcf01d6ad7db72033b549a71fc300ad0" name="Gtk/xpm/check_no.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="cf7bb81fd9067efa3a43cb4546610a90" name="Gtk/xpm/check_yes.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="3d075af534dc7474d30fc5213d38e55d" name="Gtk/xpm/downloading_image.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="1c0a9cf7ec836d67f090789c7ea51175" name="Gtk/xpm/folder_closed.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="1c0a9cf7ec836d67f090789c7ea51175" name="Gtk/xpm/folder_open.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="d2f693edec6a3a72410bbfe2635cca4c" name="Gtk/xpm/info_icon.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="14b1710cb02528017e352f2c9bb77c79" name="Gtk/xpm/nav_configuration.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="7a50b5f799249969ebde6aedbce9dbd6" name="Gtk/xpm/nav_documentation.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="11231b7afd52573bdb7b466e4ae575a4" name="Gtk/xpm/nav_installer.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="922ab909b15f2eeb85097c341dc9c45a" name="Gtk/xpm/package.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="195fe43665f08098ae0f59af1a471497" name="Gtk/xpm/pear.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="81cc4d8580e7a9000f8f151dc56f3769" name="Gtk/xpm/stock_delete-16.xpm"/>
   <file role="php" baseinstalldir="PEAR/Frontend" md5sum="031b0d5f66e38031d54cd8ae89631cfe" name="Gtk/xpm/stock_delete-outline-16.xpm"/>
  </filelist>
 </release>
 <changelog>
   <release>
    <version>0.3</version>
    <date>2002-07-25</date>
    <state>beta</state>
    <notes>Attempt to fix package file so it installs,
           some of the warnings have been fixed
    </notes>
   </release>
   <release>
    <version>0.2</version>
    <date>2002-06-16</date>
    <state>snapshot</state>
    <notes>Snapshot - First Working Version
    </notes>
   </release>
   <release>
    <version>0.1</version>
    <date>2002-06-13</date>
    <state>snapshot</state>
    <notes>Snapshot - dont expect it to work
    </notes>
   </release>
 </changelog>
</package>',
  'url' => 'http://pear.php.net/get/PEAR_Frontend_Gtk-0.4.0',
));
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setPEARversion('1.4.0a5');
$res = $command->run('install', array(), array($pathtopackagexml));
$phpunit->assertNoErrors('after install setup');
$fakelog->getLog();
$fakelog->getDownload();
$config->set('preferred_state', 'alpha');
$res = $command->run('install', array(), array('pear#webinstaller'));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_Error', 'message' => 'install failed'),
), 'after install');
$phpunit->assertEquals(array(
  array (
    0 => 3,
    1 => 'Notice: package "pear/PEAR" optional dependency "pear/PEAR_Frontend_Web" will not be automatically downloaded',
  ),
  array (
    0 => 0,
    1 => 'Failed to download pear/PEAR_Frontend_Web (version >= 0.5.0), latest release is version 0.4, stability "beta", use "channel://pear.php.net/PEAR_Frontend_Web-0.4" to install',
  ),
  array (
    0 => 3,
    1 => 'Notice: package "pear/PEAR" optional dependency "pear/PEAR_Frontend_Gtk" will not be automatically downloaded',
  ),
  array (
    0 => 1,
    1 => 'Did not download optional dependencies: pear/PEAR_Frontend_Gtk, use --alldeps to download automatically',
  ),
  array (
    0 => 0,
    1 => 'Failed to download pear/PEAR_Frontend_Web (version >= 0.5.0), latest release is version 0.4, stability "beta", use "channel://pear.php.net/PEAR_Frontend_Web-0.4" to install',
  ),
  array (
    0 => 1,
    1 => 'Skipping package "pear/PEAR", already installed as version 1.4.0a5',
  ),
  array (
    'info' => 
    array (
      'data' => 
      array (
        0 => 
        array (
          0 => 'No valid packages found',
        ),
      ),
      'headline' => 'Install Errors',
    ),
    'cmd' => 'no command',
  ),
), $fakelog->getLog(), 'log');
echo 'tests done';
?>
--EXPECT--
tests done
