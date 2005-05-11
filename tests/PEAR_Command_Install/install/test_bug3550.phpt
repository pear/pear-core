--TEST--
install command, simplest possible test
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
$packagexml = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' .
    'PEAR_Frontend_Web-0.4.tgz';
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'PEAR_Frontend_Web',
    'channel' => 'pear.php.net',
    'version' => '0.4',
  ),
  1 => 'stable',
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
  'url' => 'http://pear.php.net/get/PEAR_Frontend_Web-0.4',
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
    'version' => '1.4.0a1',
  ),
  3 => 'stable',
), array (
  'version' => '0.3',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<package version="1.0">
  <name>PEAR_Frontend_Gtk</name>
  <summary>Gtk (Desktop) PEAR Package Manager</summary>
  <description>Desktop Interface to the PEAR Package Manager, Requires PHP-GTK</description>
  <maintainers>
    <maintainer>
      <user>alan_k</user>
      <name>Alan Knowles</name>
      <email>alan@akbkhome.com</email>
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
    <version>0.3</version>
    <date>2002-07-25</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>Attempt to fix package file so it installs,
           some of the warnings have been fixed</notes>
    <filelist>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="e4be5b3a5263eddc74c2b61e75be9f14" name="Gtk.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="43f980e244d7fadd1585a01623fbd915" name="Gtk/Config.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="241bca542f590036ad2b7404cfd604e9" name="Gtk/DirSelect.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="f32fee8dd69cc8854862347194909e38" name="Gtk/Documentation.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="7804108f16ff949e47d43b54bc7456ef" name="Gtk/Info.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="5c51ecf48c0929a9c29aa09b7d23c90b" name="Gtk/Install.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="aa44455fe0742dd4a8fabb57cd59fb43" name="Gtk/PackageData.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="59e0751781f81a05a6ac3d7703459d9e" name="Gtk/Packages.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="d31aca89e13ad6d97a150e644bac7d40" name="Gtk/Summary.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="0519d8543853cbe45ec1df23a9a9351a" name="Gtk/WidgetHTML.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="dc025107b797ce26a50e38359d3bac4c" name="Gtk/installer.glade"/>
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
    'version' => '1.4.0a1',
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
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'PEAR_Frontend_Gtk',
    'channel' => 'pear.php.net',
    'version' => '0.3',
  ),
  1 => 'stable',
), array (
  'version' => '0.3',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<package version="1.0">
  <name>PEAR_Frontend_Gtk</name>
  <summary>Gtk (Desktop) PEAR Package Manager</summary>
  <description>Desktop Interface to the PEAR Package Manager, Requires PHP-GTK</description>
  <maintainers>
    <maintainer>
      <user>alan_k</user>
      <name>Alan Knowles</name>
      <email>alan@akbkhome.com</email>
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
    <version>0.3</version>
    <date>2002-07-25</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>Attempt to fix package file so it installs,
           some of the warnings have been fixed</notes>
    <filelist>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="e4be5b3a5263eddc74c2b61e75be9f14" name="Gtk.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="43f980e244d7fadd1585a01623fbd915" name="Gtk/Config.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="241bca542f590036ad2b7404cfd604e9" name="Gtk/DirSelect.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="f32fee8dd69cc8854862347194909e38" name="Gtk/Documentation.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="7804108f16ff949e47d43b54bc7456ef" name="Gtk/Info.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="5c51ecf48c0929a9c29aa09b7d23c90b" name="Gtk/Install.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="aa44455fe0742dd4a8fabb57cd59fb43" name="Gtk/PackageData.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="59e0751781f81a05a6ac3d7703459d9e" name="Gtk/Packages.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="d31aca89e13ad6d97a150e644bac7d40" name="Gtk/Summary.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="0519d8543853cbe45ec1df23a9a9351a" name="Gtk/WidgetHTML.php"/>
      <file role="php" baseinstalldir="PEAR/Frontend" md5sum="dc025107b797ce26a50e38359d3bac4c" name="Gtk/installer.glade"/>
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
  'url' => 'http://pear.php.net/get/PEAR_Frontend_Gtk-0.3',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'PEAR_Frontend_Web',
    'channel' => 'pear.php.net',
    'state' => 'beta',
  ),
  1 => 'beta',
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
  'url' => 'http://pear.php.net/get/PEAR_Frontend_Web-0.4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'Net_UserAgent_Detect',
    'channel' => 'pear.php.net',
    'package' => 'Net_UserAgent_Detect',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR_Frontend_Web',
    'version' => '0.4',
  ),
  3 => 'beta',
  4 => '2.0.1',
), array (
  'version' => '2.0.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Net_UserAgent_Detect</name>
  <summary>Net_UserAgent_Detect determines the Web browser, version, and platform from an HTTP user agent string</summary>
  <description>The Net_UserAgent object does a number of tests on an HTTP user
agent string.  The results of these tests are available via methods of
the object.

This module is based upon the JavaScript browser detection code
available at http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html.
This module had many influences from the lib/Browser.php code in
version 1.3 of Horde.</description>
  <maintainers>
    <maintainer>
      <user>jrust</user>
      <name>Jason Rust</name>
      <email>jrust@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>dallen</user>
      <name>Dan Allen</name>
      <email>dallen@php.net</email>
      <role>helper</role>
    </maintainer>
    <maintainer>
      <user>gurugeek</user>
      <name>David Costa</name>
      <email>gurugeek@php.net</email>
      <role>helper</role>
    </maintainer>
  </maintainers>
  <release>
    <version>2.0.1</version>
    <date>2004-03-11</date>
    <license>PHP 2.01</license>
    <state>stable</state>
    <notes>* Made it PHP5 compatible in a way that is BC
* Added support for Safari
* Bumped konq\'s javascript version up to 1.4
* Fix bug where w3m caused warnings
* fixed a notice error when the user agent is empty
* Fixed missing windows xp detection and added nested_table_render_bug quirk for netscape 4.8 and below. 
* Code cleanups.</notes>
    <deps>
      <dep type="php" rel="ge" version="4.1.0"/>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="Net/UserAgent" name="Detect.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0</version>
      <date>2002-05-21</date>
      <license>PHP 2.01</license>
      <state>stable</state>
      <notes>This is the initial independent PEAR release.
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Net_UserAgent_Detect-2.0.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'HTML_Template_IT',
    'channel' => 'pear.php.net',
    'package' => 'HTML_Template_IT',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'PEAR_Frontend_Web',
    'version' => '0.4',
  ),
  3 => 'beta',
  4 => '1.1',
), array (
  'version' => '1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<package version="1.0">
  <name>HTML_Template_IT</name>
  <summary>Integrated Templates</summary>
  <description>HTML_Template_IT:
Simple template API.
The Isotemplate API is somewhat tricky for a beginner although it is the best
one you can build. template::parse() [phplib template = Isotemplate] requests
you to name a source and a target where the current block gets parsed into.
Source and target can be block names or even handler names. This API gives you
a maximum of fexibility but you always have to know what you do which is
quite unusual for php skripter like me.

I noticed that I do not any control on which block gets parsed into which one.
If all blocks are within one file, the script knows how they are nested and in
which way you have to parse them. IT knows that inner1 is a child of block2, there\'s
no need to tell him about this.
Features :
  * Nested blocks
  * Include external file
  * Custom tags format (default {mytag})

HTML_Template_ITX :
With this class you get the full power of the phplib template class.
You may have one file with blocks in it but you have as well one main file
and multiple files one for each block. This is quite usefull when you have
user configurable websites. Using blocks not in the main template allows
you to modify some parts of your layout easily.</description>
  <maintainers>
    <maintainer>
      <user>uw</user>
      <name>Ulf Wendel</name>
      <email>ulf.wendel@phpdoc.de</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>pajoye</user>
      <name>Pierre-Alain Joye</name>
      <email>paj@pearfr.org</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.1</version>
    <date>2003-03-11</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>- Added str_replace optionnal parsing (fixes problems 
  with regular expression like \'$\' symbols) (Alexey, pajoye)
- Added preserve_data to substitute/preserve variables inside
  in datas already passed through setVariable (Alexey)
A special thank to Alexey Borzov to provide a patch for his fixes</notes>
    <filelist>
      <file role="php" baseinstalldir="HTML/Template" md5sum="f24437fe8aaabc57b58550d64ab4a7b1" name="IT.php"/>
      <file role="php" baseinstalldir="HTML/Template" md5sum="77915a3c42c935e7572221d241226d8e" name="ITX.php"/>
      <file role="php" baseinstalldir="HTML/Template" md5sum="3db8ec57f15668ed27ee9087b31d491f" name="IT_Error.php"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="e50de49b74a1d96bb77ddae2d0abdfc3" name="tests/templates/addblock.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="22e56437378e53d08ba07d3dd2aa4b85" name="tests/templates/blockiteration.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="4173bf97eec43787532e247ef9b2611a" name="tests/templates/blocks.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="2611d6ec574a65716f1bc2ca95cb8c63" name="tests/templates/globals.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="310552db4653b34dbb0f993847572fc5" name="tests/templates/__include.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="db5b226eff0218c831749c07042529f2" name="tests/templates/include.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="d9a6425eebdfc6981465b4a228dbee51" name="tests/templates/loadtemplatefile.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="5c7e2e9c32306db4b6667d2b57f1c0ac" name="tests/templates/replaceblock.html"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="9bd9d964363904b5026972dff0d198da" name="tests/Console_TestListener.php"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="e624be47a36948a1e44d231260de808a" name="tests/IT_api_testcase.php"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="3836b3fc1dd5a2a4d09258be575736c9" name="tests/IT_usage_testcase.php"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="d5d4362e47e4a633e47c87f3daf8459e" name="tests/ITX_api_testcase.php"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="ec630d63df8163f75c4aa3b1e3051d2d" name="tests/ITX_usage_testcase.php"/>
      <file role="test" baseinstalldir="HTML/Template" md5sum="7a2c8eab843daf1cb9acebfd7fa5913d" name="tests/test.php"/>
      <file role="doc" baseinstalldir="HTML/Template" md5sum="0b87796dbcef946df62d9a68e928ac80" name="examples/sample_it.php"/>
      <file role="doc" baseinstalldir="HTML/Template" md5sum="834e1e6d40b2f34906aa17f9969bd891" name="examples/templates/main.tpl.htm"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/HTML_Template_IT-1.1',
));
$nu = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'Net_UserAgent_Detect-2.0.1.tgz';
$hi = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'HTML_Template_IT-1.1.tgz';
$at = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'Archive_Tar-1.2.tgz';
$cg = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'Console_Getopt-1.2.tgz';
$xr = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'XML_RPC-1.2.0RC6.tgz';
$pe = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'PEAR-1.4.0a1.tgz';
$_test_dep->setPEARVersion('1.4.0a1');
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setExtensions(array('xml' => '1.0', 'pcre' => '1.0'));
$res = $command->run('install', array(), array($nu, $hi, $at, $cg, $xr, $pe));
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1', 'message' => 'Package.xml contains non-ISO-8859-1 characters, and may not validate'),
), 'after install');
$phpunit->assertTrue($res, 'result');
$fakelog->getLog();
$fakelog->getDownload();
$res = $command->run('install', array(), array('PEAR_Frontend_Web-beta'));
$phpunit->assertErrors(array(array('package' => 'PEAR_Error', 'message' => 'install failed')), 
'after install');
$phpunit->assertEquals( array (
  0 => 
  array (
    0 => 3,
    1 => 'Notice: package "pear/PEAR_Frontend_Web" required dependency "pear/Net_UserAgent_Detect" will not be automatically downloaded',
  ),
  1 => 
  array (
    0 => 3,
    1 => 'Skipping required dependency "pear/Net_UserAgent_Detect", already installed as version 2.0.1',
  ),
  2 => 
  array (
    0 => 3,
    1 => 'Notice: package "pear/PEAR_Frontend_Web" required dependency "pear/HTML_Template_IT" will not be automatically downloaded',
  ),
  3 => 
  array (
    0 => 3,
    1 => 'Skipping required dependency "pear/HTML_Template_IT", already installed as version 1.1',
  ),
  4 => 
  array (
    0 => 0,
    1 => 'pear/pear requires package "pear/PEAR_Frontend_Web" (version >= 0.5.0), downloaded version is 0.4',
  ),
  5 => 
  array (
    0 => 0,
    1 => 'pear/PEAR_Frontend_Web cannot be installed, conflicts with installed packages',
  ),
  6 => 
  array (
    'info' => 
    array (
      'data' => 
      array (
        0 => 
        array (
          0 => 'Cannot install, dependencies failed',
        ),
      ),
      'headline' => 'Install Errors',
    ),
    'cmd' => 'no command',
  ),
 )
, $fakelog->getLog(), 'log');
echo 'tests done';
?>
--EXPECT--
tests done
