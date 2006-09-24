--TEST--
upgrade command, test for bug #4060 - install/upgrade of package with an os installcondition * fails
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
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setPEARVersion('1.4.0a10');
$p1 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.4.tgz';
$p2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Auth-1.3.0r3.tgz';
$p3 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'packages'. DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1.tgz';
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '0.9.5',
    'optional' => 'yes',
    'name' => 'File_Passwd',
    'channel' => 'pear.php.net',
    'package' => 'File_Passwd',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '1.1.3',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0a4">
 <name>File_Passwd</name>
 <summary>Manipulate many kinds of password files</summary>
 <description>Provides methods to manipulate and authenticate against standard Unix, 
SMB server, AuthUser (.htpasswd), AuthDigest (.htdigest), CVS pserver 
and custom formatted password files.
 </description>
 <maintainers>
  <maintainer>
   <user>mike</user>
   <name>Michael Wallner</name>
   <email>mike@php.net</email>
   <role>lead</role>
  </maintainer>
  </maintainers>
 <release>
  <version>1.1.3</version>
  <date>2005-03-15</date>
  <license>PHP</license>
  <state>stable</state>
  <notes>* Performance improvements for APR MD5 encryption by Ants Aasma &lt;ants.aasma@mig.ee&gt;
* Fixed bug in File_Passwd_Common::_open(): could not safe into non-existant file
* Fixed security bug in File_Passwd_Common::_auth(): one could be authenticated 
  with a substring of ones actual user name
  </notes>
  <deps>
   <dep type="pkg" rel="has" optional="no">PEAR</dep>
   <dep type="pkg" rel="ge" version="1.0.0" optional="yes">Crypt_CHAP</dep>
   <dep type="php" rel="ge" version="4.3.0" optional="yes"/>
   <dep type="php" rel="ge" version="4.0.6" optional="no"/>
   <dep type="ext" rel="has" optional="no">pcre</dep>
  </deps>
  <provides type="class" name="File_Passwd" />
  <provides type="function" name="File_Passwd::apiVersion" />
  <provides type="function" name="File_Passwd::salt" />
  <provides type="function" name="File_Passwd::crypt_plain" />
  <provides type="function" name="File_Passwd::crypt_des" />
  <provides type="function" name="File_Passwd::crypt_md5" />
  <provides type="function" name="File_Passwd::crypt_sha" />
  <provides type="function" name="File_Passwd::crypt_apr_md5" />
  <provides type="function" name="File_Passwd::factory" />
  <provides type="class" name="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Common::parse" />
  <provides type="function" name="File_Passwd_Common::save" />
  <provides type="function" name="File_Passwd_Common::load" />
  <provides type="function" name="File_Passwd_Common::setFile" />
  <provides type="function" name="File_Passwd_Common::getFile" />
  <provides type="function" name="File_Passwd_Common::userExists" />
  <provides type="function" name="File_Passwd_Common::delUser" />
  <provides type="function" name="File_Passwd_Common::listUser" />
  <provides type="class" name="File_Passwd_Unix" extends="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Unix::staticAuth" />
  <provides type="function" name="File_Passwd_Unix::save" />
  <provides type="function" name="File_Passwd_Unix::parse" />
  <provides type="function" name="File_Passwd_Unix::setMode" />
  <provides type="function" name="File_Passwd_Unix::listModes" />
  <provides type="function" name="File_Passwd_Unix::getMode" />
  <provides type="function" name="File_Passwd_Unix::useMap" />
  <provides type="function" name="File_Passwd_Unix::setMap" />
  <provides type="function" name="File_Passwd_Unix::getMap" />
  <provides type="function" name="File_Passwd_Unix::isShadowed" />
  <provides type="function" name="File_Passwd_Unix::addUser" />
  <provides type="function" name="File_Passwd_Unix::modUser" />
  <provides type="function" name="File_Passwd_Unix::changePasswd" />
  <provides type="function" name="File_Passwd_Unix::verifyPasswd" />
  <provides type="function" name="File_Passwd_Unix::generatePasswd" />
  <provides type="function" name="File_Passwd_Unix::generatePassword" />
  <provides type="class" name="File_Passwd_Cvs" extends="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Cvs::staticAuth" />
  <provides type="function" name="File_Passwd_Cvs::save" />
  <provides type="function" name="File_Passwd_Cvs::parse" />
  <provides type="function" name="File_Passwd_Cvs::addUser" />
  <provides type="function" name="File_Passwd_Cvs::verifyPasswd" />
  <provides type="function" name="File_Passwd_Cvs::changePasswd" />
  <provides type="function" name="File_Passwd_Cvs::changeSysUser" />
  <provides type="function" name="File_Passwd_Cvs::generatePasswd" />
  <provides type="function" name="File_Passwd_Cvs::generatePassword" />
  <provides type="class" name="File_Passwd_Smb" extends="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Smb::staticAuth" />
  <provides type="function" name="File_Passwd_Smb::parse" />
  <provides type="function" name="File_Passwd_Smb::addUser" />
  <provides type="function" name="File_Passwd_Smb::modUser" />
  <provides type="function" name="File_Passwd_Smb::changePasswd" />
  <provides type="function" name="File_Passwd_Smb::verifyEncryptedPasswd" />
  <provides type="class" name="File_Passwd_Authbasic" extends="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Authbasic::staticAuth" />
  <provides type="function" name="File_Passwd_Authbasic::save" />
  <provides type="function" name="File_Passwd_Authbasic::addUser" />
  <provides type="function" name="File_Passwd_Authbasic::changePasswd" />
  <provides type="function" name="File_Passwd_Authbasic::verifyPasswd" />
  <provides type="function" name="File_Passwd_Authbasic::getMode" />
  <provides type="function" name="File_Passwd_Authbasic::listModes" />
  <provides type="function" name="File_Passwd_Authbasic::setMode" />
  <provides type="function" name="File_Passwd_Authbasic::parse" />
  <provides type="function" name="File_Passwd_Authbasic::generatePasswd" />
  <provides type="function" name="File_Passwd_Authbasic::generatePassword" />
  <provides type="class" name="File_Passwd_Authdigest" extends="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Authdigest::staticAuth" />
  <provides type="class" name="File_Passwd_Custom" extends="File_Passwd_Common" />
  <provides type="function" name="File_Passwd_Custom::staticAuth" />
  <provides type="function" name="File_Passwd_Custom::setDelim" />
  <provides type="function" name="File_Passwd_Custom::getDelim" />
  <provides type="function" name="File_Passwd_Custom::setEncFunc" />
  <provides type="function" name="File_Passwd_Custom::getEncFunc" />
  <provides type="function" name="File_Passwd_Custom::useMap" />
  <provides type="function" name="File_Passwd_Custom::setMap" />
  <provides type="function" name="File_Passwd_Custom::getMap" />
  <provides type="function" name="File_Passwd_Custom::save" />
  <provides type="function" name="File_Passwd_Custom::parse" />
  <provides type="function" name="File_Passwd_Custom::addUser" />
  <provides type="function" name="File_Passwd_Custom::modUser" />
  <provides type="function" name="File_Passwd_Custom::changePasswd" />
  <provides type="function" name="File_Passwd_Custom::verifyPasswd" />
  <filelist>
   <file role="php" baseinstalldir="File" md5sum="93a3235828642efb50c48f8a37e67051" name="Passwd.php"/>
   <file role="php" baseinstalldir="File" md5sum="a407c06e7dd3f02a0436b7166646bfd5" name="Passwd' . DIRECTORY_SEPARATOR . 'Common.php"/>
   <file role="php" baseinstalldir="File" md5sum="eee830309b07ccd1de2e23c496e89a2b" name="Passwd' . DIRECTORY_SEPARATOR . 'Unix.php"/>
   <file role="php" baseinstalldir="File" md5sum="eb1d7e514ed934f031d95d4ca765b2da" name="Passwd' . DIRECTORY_SEPARATOR . 'Cvs.php"/>
   <file role="php" baseinstalldir="File" md5sum="b802a3ab2810d3e1d7eadd93745a5656" name="Passwd' . DIRECTORY_SEPARATOR . 'Smb.php"/>
   <file role="php" baseinstalldir="File" md5sum="52632a73535cf0e7a42dda9603e4bf63" name="Passwd' . DIRECTORY_SEPARATOR . 'Authbasic.php"/>
   <file role="php" baseinstalldir="File" md5sum="ebfba9efac70bf439a4d09b0179f1e8b" name="Passwd' . DIRECTORY_SEPARATOR . 'Authdigest.php"/>
   <file role="php" baseinstalldir="File" md5sum="7fdd9a89baa22b73d54b716298be119d" name="Passwd' . DIRECTORY_SEPARATOR . 'Custom.php"/>
   <file role="test" baseinstalldir="File" md5sum="9e8c3441677e73c1d36424a7c992c660" name="tests' . DIRECTORY_SEPARATOR . 'testsuite.php"/>
   <file role="test" baseinstalldir="File" md5sum="65b1509315923e226066149ce6bcc061" name="tests' . DIRECTORY_SEPARATOR . 'test_file_passwd.php"/>
   <file role="test" baseinstalldir="File" md5sum="db4d14b3e9424be5cd70a207bd7873a5" name="tests' . DIRECTORY_SEPARATOR . 'test_common.php"/>
   <file role="test" baseinstalldir="File" md5sum="754a6b711ec029b24e930c8c608c996d" name="tests' . DIRECTORY_SEPARATOR . 'test_unix.php"/>
   <file role="test" baseinstalldir="File" md5sum="94976c8c33d8dd525bacd9987ec95de9" name="tests' . DIRECTORY_SEPARATOR . 'test_smb.php"/>
   <file role="test" baseinstalldir="File" md5sum="e5d198f80894519bee0e5dc376386381" name="tests' . DIRECTORY_SEPARATOR . 'test_cvs.php"/>
   <file role="test" baseinstalldir="File" md5sum="b8e499aac1c63dd3243905dbb96290d4" name="tests' . DIRECTORY_SEPARATOR . 'test_authbasic.php"/>
   <file role="test" baseinstalldir="File" md5sum="0284aa2535f6be2c50e992ba000a5a13" name="tests' . DIRECTORY_SEPARATOR . 'test_authdigest.php"/>
   <file role="test" baseinstalldir="File" md5sum="6c0463f3395a96da41cc99822913f4ef" name="tests' . DIRECTORY_SEPARATOR . 'test_custom.php"/>
   <file role="test" baseinstalldir="File" md5sum="04383934ec236d8c07eb7bd15befc513" name="tests' . DIRECTORY_SEPARATOR . 'README.txt"/>
   <file role="test" baseinstalldir="File" md5sum="d459ec4b54d51ede8e80a1b23b8cad7d" name="tests' . DIRECTORY_SEPARATOR . 'common.txt"/>
   <file role="test" baseinstalldir="File" md5sum="e4353b15620be68a34cf62eaa5b9b4b1" name="tests' . DIRECTORY_SEPARATOR . 'passwd.unix.txt"/>
   <file role="test" baseinstalldir="File" md5sum="aedc5da39fdaf9afee96f5a195ff6fa3" name="tests' . DIRECTORY_SEPARATOR . 'passwd.cvs.txt"/>
   <file role="test" baseinstalldir="File" md5sum="6edb929abda117ea975aec221f34062c" name="tests' . DIRECTORY_SEPARATOR . 'passwd.smb.txt"/>
   <file role="test" baseinstalldir="File" md5sum="c0a6ae29c440dcff3dacb00c93d54117" name="tests' . DIRECTORY_SEPARATOR . 'passwd.authbasic.txt"/>
   <file role="test" baseinstalldir="File" md5sum="c738a5224f46cfd80ed95080b9624e11" name="tests' . DIRECTORY_SEPARATOR . 'passwd.authdigest.txt"/>
   <file role="test" baseinstalldir="File" md5sum="572d1572f6e89f934d1e626a8e70cbaf" name="tests' . DIRECTORY_SEPARATOR . 'passwd.custom.txt"/>
  </filelist>
 </release>
</package>',
  'url' => 'http://pear.php.net/get/File_Passwd-1.1.3',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.3',
    'optional' => 'yes',
    'name' => 'Net_POP3',
    'channel' => 'pear.php.net',
    'package' => 'Net_POP3',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '1.3.6',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Net_POP3</name>
  <summary>Provides a POP3 class to access POP3 server.</summary>
  <description>Provides a POP3 class to access POP3 server. Support all POP3 commands
including UIDL listings, APOP authentication,DIGEST-MD5 and CRAM-MD5 using optional Auth_SASL package</description>
  <maintainers>
    <maintainer>
      <user>richard</user>
      <name>Richard Heyes</name>
      <email>richard@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>damian</user>
      <name>Damian Fernandez Sosa</name>
      <email>damlists@cnba.uba.ar</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>gschlossnagle</user>
      <name>George Schlossnagle</name>
      <email>george@omniti.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.3.6</version>
    <date>2005-04-04</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>* Fixed Bug #3551 Bug #2663 not fixed yet. 
* Fixed Bug #3410 Error handling in _sendCmd
* Fixed Bug #1942 wrong parameter-type specification in Net_POP3::login
* Fixed Bug #239 Missing phpdoc tag.</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">Net_Socket</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="Net" name="POP3.php"/>
      <file role="test" baseinstalldir="Net" name="Net_POP3_example.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.3.5</version>
      <date>2005-02-02</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Fixed Bug #3141 $pop3-&gt;getListing() returns empty fields. thanks to  culmat at gmx dot net
    
</notes>
    </release>
    <release>
      <version>1.3.4</version>
      <date>2004-12-05</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Fixed Bug #2440 thanks to erickoh at jobsfactory dot com
* Fixed Bug #2453 :Mistake in PEAR Doc for Net_POP3 example. thanks to erickoh at jobsfactory dot com
* Removed double quotes in _authLogin() fixes Bug #2454 :AUTH LOGIN user and pass need to be enclosed in double quotes?. thanks to erickoh at jobsfactory dot com
* Fixed Bug #2523 : Unable to login to Qmail servers. thanks to johann dot hoehn at ecommerce dot com
* Fixed Bug #2646 :APOP attempted when not supported. thanks to osdave at nospam_davepar dot com
    
    
</notes>
    </release>
    <release>
      <version>1.3.3</version>
      <date>2004-09-15</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* _sendCmd  now return PEAR_Error on error instead of false making error check easy
* Fixes bug #2175 login() which uses DIGEST-MD5 always returns true.
* All the Login methods now uses PEAR_Error instead of \'false\' finding the error cause is now easy
* Disabling DIGEST-MD5 until I can make work whit saslv2 (saslv2 and saslv1 responses are different )
    
</notes>
    </release>
    <release>
      <version>1.3.2</version>
      <date>2004-06-26</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Fixes bug #1505

    
</notes>
    </release>
    <release>
      <version>1.3.1</version>
      <date>2004-03-18</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Solves a bug in the auth code when the AUTH method is set to APOP but the server fall back to user and pass

    
</notes>
    </release>
    <release>
      <version>1.3</version>
      <date>2004-03-03</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Added debug capabilities
* Added _recvLn() and _send($data) to support debug and better check socket errors
* Added SASL AUTH capabilities
* if installed automatically uses Auth_SASL
* Added LOGIN,PLAIN,DIGEST-MD5 and CRAM-MD5
* Modified APOP and USER auths
* Now the class automagically selects the best auth method
    
</notes>
    </release>
    <release>
      <version>1.2</version>
      <date>2002-07-27</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>License change
</notes>
    </release>
    <release>
      <version>1.1</version>
      <date>2002-02-13</date>
      <state>stable</state>
      <notes>Renamed file to POP3.php
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Net_POP3-1.3.6',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'optional' => 'yes',
    'name' => 'DB',
    'channel' => 'pear.php.net',
    'package' => 'DB',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '1.7.5',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0a9">
 <name>DB</name>
 <summary>Database Abstraction Layer</summary>
 <description>DB is a database abstraction layer providing:
* an OO-style query API
* portability features that make programs written for one DBMS work with other DBMS\'s
* a DSN (data source name) format for specifying database servers
* prepare/execute (bind) emulation for databases that don\'t support it natively
* a result object for each query response
* portable error codes
* sequence emulation
* sequential and non-sequential row fetching as well as bulk fetching
* formats fetched rows as associative arrays, ordered arrays or objects
* row limit support
* transactions support
* table information interface
* DocBook and phpDocumentor API documentation

DB layers itself on top of PHP\'s existing
database extensions.

Drivers for the following extensions pass
the complete test suite and provide
interchangeability when all of DB\'s
portability options are enabled:

  fbsql, ibase, informix, msql, mssql,
  mysql, mysqli, oci8, odbc, pgsql,
  sqlite and sybase.

There is also a driver for the dbase
extension, but it can\'t be used
interchangeably because dbase doesn\'t
support many standard DBMS features.

DB is compatible with both PHP 4 and PHP 5.
 </description>
 <maintainers>
  <maintainer>
   <user>ssb</user>
   <name>Stig Bakken</name>
   <email>stig@php.net</email>
   <role>developer</role>
  </maintainer>
  <maintainer>
   <user>cox</user>
   <name>Tomas V.V.Cox</name>
   <email>cox@idecnet.com</email>
   <role>developer</role>
  </maintainer>
  <maintainer>
   <user>danielc</user>
   <name>Daniel Convissor</name>
   <email>danielc@php.net</email>
   <role>lead</role>
  </maintainer>
  <maintainer>
   <user>lsmith</user>
   <name>Lukas Kahwe Smith</name>
   <email>smith@backendmedia.com</email>
   <role>helper</role>
  </maintainer>
  </maintainers>
 <release>
  <version>1.7.5</version>
  <date>2005-03-29</date>
  <license>PHP License</license>
  <state>stable</state>
  <notes>common:
* Have buildManipSQL() return any errors that were raised.  Bug 3954.
* Have autoExecute() check for errors coming back from autoPrepare().
* Have autoPrepare() check for errors coming back from buildManipSQL().

mysql:
* Don\'t pass new_link to mysql_pconnect().  Bug 3993.

sqlite:
* Map error message for multi-column unique constraints.
  </notes>
  <deps>
   <dep type="php" rel="ge" version="4.2.0"/>
   <dep type="pkg" rel="ge" version="1.0b1">PEAR</dep>
  </deps>
  <provides type="class" name="DB" />
  <provides type="function" name="DB::factory" />
  <provides type="class" name="DB_common" extends="PEAR" />
  <provides type="function" name="DB_common::toString" />
  <provides type="function" name="DB_common::quoteString" />
  <provides type="function" name="DB_common::quote" />
  <provides type="function" name="DB_common::quoteIdentifier" />
  <provides type="function" name="DB_common::quoteSmart" />
  <provides type="function" name="DB_common::escapeSimple" />
  <provides type="function" name="DB_common::provides" />
  <provides type="function" name="DB_common::setFetchMode" />
  <provides type="function" name="DB_common::setOption" />
  <provides type="class" name="DB_dbase" extends="DB_common" />
  <provides type="function" name="DB_dbase::connect" />
  <provides type="function" name="DB_dbase::disconnect" />
  <provides type="function" name="DB_dbase::query" />
  <provides type="function" name="DB_dbase::fetchInto" />
  <provides type="function" name="DB_dbase::numCols" />
  <provides type="function" name="DB_dbase::numRows" />
  <provides type="function" name="DB_dbase::quoteSmart" />
  <provides type="function" name="DB_dbase::tableInfo" />
  <provides type="class" name="DB_fbsql" extends="DB_common" />
  <provides type="function" name="DB_fbsql::connect" />
  <provides type="function" name="DB_fbsql::disconnect" />
  <provides type="function" name="DB_fbsql::simpleQuery" />
  <provides type="class" name="DB_ibase" extends="DB_common" />
  <provides type="function" name="DB_ibase::connect" />
  <provides type="function" name="DB_ibase::disconnect" />
  <provides type="function" name="DB_ibase::simpleQuery" />
  <provides type="function" name="DB_ibase::modifyLimitQuery" />
  <provides type="class" name="DB_ifx" extends="DB_common" />
  <provides type="function" name="DB_ifx::connect" />
  <provides type="function" name="DB_ifx::disconnect" />
  <provides type="function" name="DB_ifx::simpleQuery" />
  <provides type="function" name="DB_ifx::nextResult" />
  <provides type="function" name="DB_ifx::affectedRows" />
  <provides type="function" name="DB_ifx::fetchInto" />
  <provides type="function" name="DB_ifx::numCols" />
  <provides type="function" name="DB_ifx::freeResult" />
  <provides type="function" name="DB_ifx::autoCommit" />
  <provides type="function" name="DB_ifx::commit" />
  <provides type="function" name="DB_ifx::rollback" />
  <provides type="function" name="DB_ifx::ifxRaiseError" />
  <provides type="function" name="DB_ifx::errorNative" />
  <provides type="function" name="DB_ifx::errorCode" />
  <provides type="function" name="DB_ifx::tableInfo" />
  <provides type="class" name="DB_msql" extends="DB_common" />
  <provides type="function" name="DB_msql::connect" />
  <provides type="function" name="DB_msql::disconnect" />
  <provides type="function" name="DB_msql::simpleQuery" />
  <provides type="function" name="DB_msql::nextResult" />
  <provides type="function" name="DB_msql::fetchInto" />
  <provides type="function" name="DB_msql::freeResult" />
  <provides type="function" name="DB_msql::numCols" />
  <provides type="function" name="DB_msql::numRows" />
  <provides type="function" name="DB_msql::affectedRows" />
  <provides type="function" name="DB_msql::nextId" />
  <provides type="class" name="DB_mssql" extends="DB_common" />
  <provides type="function" name="DB_mssql::connect" />
  <provides type="function" name="DB_mssql::disconnect" />
  <provides type="function" name="DB_mssql::simpleQuery" />
  <provides type="function" name="DB_mssql::nextResult" />
  <provides type="function" name="DB_mssql::fetchInto" />
  <provides type="function" name="DB_mssql::freeResult" />
  <provides type="function" name="DB_mssql::numCols" />
  <provides type="function" name="DB_mssql::numRows" />
  <provides type="function" name="DB_mssql::autoCommit" />
  <provides type="function" name="DB_mssql::commit" />
  <provides type="function" name="DB_mssql::rollback" />
  <provides type="function" name="DB_mssql::affectedRows" />
  <provides type="function" name="DB_mssql::nextId" />
  <provides type="class" name="DB_mysql" extends="DB_common" />
  <provides type="function" name="DB_mysql::connect" />
  <provides type="function" name="DB_mysql::disconnect" />
  <provides type="function" name="DB_mysql::simpleQuery" />
  <provides type="function" name="DB_mysql::nextResult" />
  <provides type="function" name="DB_mysql::fetchInto" />
  <provides type="function" name="DB_mysql::freeResult" />
  <provides type="function" name="DB_mysql::numCols" />
  <provides type="function" name="DB_mysql::numRows" />
  <provides type="function" name="DB_mysql::autoCommit" />
  <provides type="function" name="DB_mysql::commit" />
  <provides type="function" name="DB_mysql::rollback" />
  <provides type="function" name="DB_mysql::affectedRows" />
  <provides type="function" name="DB_mysql::nextId" />
  <provides type="class" name="DB_mysqli" extends="DB_common" />
  <provides type="function" name="DB_mysqli::connect" />
  <provides type="function" name="DB_mysqli::disconnect" />
  <provides type="function" name="DB_mysqli::simpleQuery" />
  <provides type="function" name="DB_mysqli::nextResult" />
  <provides type="function" name="DB_mysqli::fetchInto" />
  <provides type="function" name="DB_mysqli::freeResult" />
  <provides type="function" name="DB_mysqli::numCols" />
  <provides type="function" name="DB_mysqli::numRows" />
  <provides type="function" name="DB_mysqli::autoCommit" />
  <provides type="function" name="DB_mysqli::commit" />
  <provides type="function" name="DB_mysqli::rollback" />
  <provides type="function" name="DB_mysqli::affectedRows" />
  <provides type="function" name="DB_mysqli::nextId" />
  <provides type="class" name="DB_oci8" extends="DB_common" />
  <provides type="function" name="DB_oci8::connect" />
  <provides type="function" name="DB_oci8::disconnect" />
  <provides type="function" name="DB_oci8::simpleQuery" />
  <provides type="function" name="DB_oci8::nextResult" />
  <provides type="function" name="DB_oci8::fetchInto" />
  <provides type="function" name="DB_oci8::freeResult" />
  <provides type="function" name="DB_oci8::freePrepared" />
  <provides type="function" name="DB_oci8::numRows" />
  <provides type="function" name="DB_oci8::numCols" />
  <provides type="function" name="DB_oci8::prepare" />
  <provides type="function" name="DB_oci8::execute" />
  <provides type="class" name="DB_odbc" extends="DB_common" />
  <provides type="function" name="DB_odbc::connect" />
  <provides type="function" name="DB_odbc::disconnect" />
  <provides type="function" name="DB_odbc::simpleQuery" />
  <provides type="function" name="DB_odbc::nextResult" />
  <provides type="function" name="DB_odbc::fetchInto" />
  <provides type="function" name="DB_odbc::freeResult" />
  <provides type="function" name="DB_odbc::numCols" />
  <provides type="function" name="DB_odbc::affectedRows" />
  <provides type="function" name="DB_odbc::numRows" />
  <provides type="function" name="DB_odbc::quoteIdentifier" />
  <provides type="function" name="DB_odbc::quote" />
  <provides type="function" name="DB_odbc::nextId" />
  <provides type="class" name="DB_pgsql" extends="DB_common" />
  <provides type="function" name="DB_pgsql::connect" />
  <provides type="function" name="DB_pgsql::disconnect" />
  <provides type="function" name="DB_pgsql::simpleQuery" />
  <provides type="function" name="DB_pgsql::nextResult" />
  <provides type="function" name="DB_pgsql::fetchInto" />
  <provides type="function" name="DB_pgsql::freeResult" />
  <provides type="function" name="DB_pgsql::quote" />
  <provides type="function" name="DB_pgsql::quoteSmart" />
  <provides type="function" name="DB_pgsql::escapeSimple" />
  <provides type="function" name="DB_pgsql::numCols" />
  <provides type="function" name="DB_pgsql::numRows" />
  <provides type="function" name="DB_pgsql::autoCommit" />
  <provides type="function" name="DB_pgsql::commit" />
  <provides type="function" name="DB_pgsql::rollback" />
  <provides type="function" name="DB_pgsql::affectedRows" />
  <provides type="function" name="DB_pgsql::nextId" />
  <provides type="class" name="DB_sybase" extends="DB_common" />
  <provides type="function" name="DB_sybase::connect" />
  <provides type="function" name="DB_sybase::disconnect" />
  <provides type="function" name="DB_sybase::simpleQuery" />
  <provides type="function" name="DB_sybase::nextResult" />
  <provides type="function" name="DB_sybase::fetchInto" />
  <provides type="function" name="DB_sybase::freeResult" />
  <provides type="function" name="DB_sybase::numCols" />
  <provides type="function" name="DB_sybase::numRows" />
  <provides type="function" name="DB_sybase::affectedRows" />
  <provides type="function" name="DB_sybase::nextId" />
  <provides type="class" name="DB_storage" extends="PEAR" />
  <provides type="function" name="DB_storage::setup" />
  <provides type="function" name="DB_storage::insert" />
  <provides type="class" name="DB_sqlite" extends="DB_common" />
  <provides type="function" name="DB_sqlite::connect" />
  <provides type="function" name="DB_sqlite::disconnect" />
  <provides type="function" name="DB_sqlite::simpleQuery" />
  <provides type="function" name="DB_sqlite::nextResult" />
  <provides type="function" name="DB_sqlite::fetchInto" />
  <provides type="function" name="DB_sqlite::freeResult" />
  <provides type="function" name="DB_sqlite::numCols" />
  <provides type="function" name="DB_sqlite::numRows" />
  <provides type="function" name="DB_sqlite::affectedRows" />
  <provides type="function" name="DB_sqlite::dropSequence" />
  <provides type="function" name="DB_sqlite::createSequence" />
  <filelist>
   <file role="php" baseinstalldir="/" md5sum="ea7037643a27c7d1eb0f818efcbf35ee" name="DB.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="98e2a06769ecd82e65629959395ec0d2" name="DB/common.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="921d3cde2d8295ca978caae22ab48761" name="DB/dbase.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="830899b5b2ee3dbc93a0ccaab184c153" name="DB/fbsql.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="64376b17c209ebc78745e7a532ccdd86" name="DB/ibase.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="a27b90a13bfc9580d3954ca420d87c01" name="DB/ifx.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="0fc78181842e1b47893a0be27f680bd9" name="DB/msql.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="c7f21c8c526d7cf698095d594acc0c7c" name="DB/mssql.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="3838d8d75ce62c730814bdb87799e79c" name="DB/mysql.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="908762bfcf17b2195a61ec9219cbf10e" name="DB/mysqli.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="9bb0c8b7e05138ec7fc243c888e28bff" name="DB/oci8.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="8d6d0c54cfcec2cf46eace182886d8aa" name="DB/odbc.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="7018bbfb1cd7f5e283688747d83fe18b" name="DB/pgsql.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="fdc093d71420407c0034206c4068d332" name="DB/sybase.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="0a920908d899cf8ce620449207de5e89" name="DB/storage.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="php" md5sum="7726118beb7c038e531783542b816a33" name="DB/sqlite.php">
    <replace from="@package_version@" to="version" type="package-info"/>
   </file>
   <file role="doc" md5sum="651a644b6f3495fc39279d75b8099372" name="doc/IDEAS"/>
   <file role="doc" md5sum="8c5779871e07720a032615177403b691" name="doc/MAINTAINERS"/>
   <file role="doc" md5sum="30bc4ceeccd51413ab81fa98c1fb9aa8" name="doc/STATUS"/>
   <file role="doc" md5sum="31f276d6ff710a1f048c50cd533ffe5c" name="doc/TESTERS"/>
   <file role="test" md5sum="2e7f987503b8b5e2a7fc4c3c30e79c13" name="tests/db_error.phpt"/>
   <file role="test" md5sum="ca733f1d806681c6522ab993c999b12f" name="tests/db_parsedsn.phpt"/>
   <file role="test" md5sum="79e88e6db0c25ca1ee5e2aac35a24d6c" name="tests/db_factory.phpt"/>
   <file role="test" md5sum="859cffe6ae0f54122485879805629261" name="tests/db_ismanip.phpt"/>
   <file role="test" md5sum="5f5068a8a1a3742ff0810be61b57288d" name="tests/db_error2.phpt"/>
   <file role="test" md5sum="0ebba9b5012622df59dc2066e884cce1" name="tests/errors.inc"/>
   <file role="test" md5sum="3732edbe1c159b16d82c0cefb23fb283" name="tests/fetchmode_object.inc"/>
   <file role="test" md5sum="2cdad3e62c059414ddab8a410781458c" name="tests/fetchmodes.inc"/>
   <file role="test" md5sum="f3d663fdf145e5bb3797c96b3d0dcf47" name="tests/include.inc">
    <replace from="@include_path@" to="php_dir" type="pear-config"/>
   </file>
   <file role="test" md5sum="600316395dc9ebe05f8249b4252088e6" name="tests/limit.inc"/>
   <file role="test" md5sum="3abeeb0a61cdd7f4108a647cccb55810" name="tests/numcols.inc"/>
   <file role="test" md5sum="26fb3581b281991838b2dfacf4e86f5d" name="tests/numrows.inc"/>
   <file role="test" md5sum="06f6cd517eb324113c8cedf1c64a1e3e" name="tests/prepexe.inc"/>
   <file role="test" md5sum="aac444f47ed3ad1642013539d99f5757" name="tests/run.cvs"/>
   <file role="test" md5sum="0a3b6c14fb3a8cb6e3cd8ece9736e9eb" name="tests/sequences.inc"/>
   <file role="test" md5sum="8f773eb10ee19145937296dca60d296e" name="tests/simplequery.inc"/>
   <file role="test" md5sum="8b42ffcce8bbe68507f0ed21dab3200c" name="tests/transactions.inc"/>
   <file role="test" md5sum="5f6f6b62f9779b97adf57bdbbcffe450" name="tests/skipif.inc"/>
   <file role="test" md5sum="2cbff4d99f59d9ad71adf0833794f9e5" name="tests/driver/01connect.phpt"/>
   <file role="test" md5sum="0261a7e827ff9581e4aca6dd6b13642e" name="tests/driver/02fetch.phpt"/>
   <file role="test" md5sum="b671efeac9fd34b83309de8413531317" name="tests/driver/03simplequery.phpt"/>
   <file role="test" md5sum="a5ecf473f648022af5dc9fbb2f33e371" name="tests/driver/04numcols.phpt"/>
   <file role="test" md5sum="1ab9a3b8a98c691a222a510eb8134355" name="tests/driver/05sequences.phpt"/>
   <file role="test" md5sum="8d651d2da580619ed5abeaaa9e1f71ad" name="tests/driver/06prepexec.phpt"/>
   <file role="test" md5sum="b2e5ebe28916e63d8502845d58f74d49" name="tests/driver/08affectedrows.phpt"/>
   <file role="test" md5sum="7efee695096e0cf6e243e5590915b6fc" name="tests/driver/09numrows.phpt"/>
   <file role="test" md5sum="b2e481fc6f310db41e249a1e53f353c2" name="tests/driver/10errormap.phpt"/>
   <file role="test" md5sum="f53ca06e8370629e5ab6717bf7bbe2a7" name="tests/driver/11transactions.phpt"/>
   <file role="test" md5sum="7f2d525fc8d2038157a736b64b774811" name="tests/driver/13limit.phpt"/>
   <file role="test" md5sum="13870a67a986287a1dd7b8616538cb90" name="tests/driver/14fetchmode_object.phpt"/>
   <file role="test" md5sum="2cf853766a1c1dc21f0b38988cd5a406" name="tests/driver/15quote.phpt"/>
   <file role="test" md5sum="a7db7211ac1faebce9248ffba535472e" name="tests/driver/16tableinfo.phpt"/>
   <file role="test" md5sum="f796eae81fce16bc2a03cbea5af80b49" name="tests/driver/17query.phpt"/>
   <file role="test" md5sum="8363274c9471e5b8038856ec4b111bea" name="tests/driver/18get.phpt"/>
   <file role="test" md5sum="871bdd4a90291602c206042742922a71" name="tests/driver/19getlistof.phpt"/>
   <file role="test" md5sum="4180a5d038d41a1262d1cc41951f0f3d" name="tests/driver/connect.inc"/>
   <file role="test" md5sum="6f4a4b1e45c94733717a21ef26f388ba" name="tests/driver/mktable.inc"/>
   <file role="test" md5sum="4af9cff841e14f1c94e358346747e7b6" name="tests/driver/multiconnect.php"/>
   <file role="test" md5sum="7023d979e8bcb94a93d48597d864feb3" name="tests/driver/run.cvs"/>
   <file role="test" md5sum="a7ee27ff0a2aacf0ef906eea8569718f" name="tests/driver/setup.inc.cvs">
    <replace from="@include_path@" to="php_dir" type="pear-config"/>
   </file>
   <file role="test" md5sum="5e3ad6fb4ab28735d788396ab80a63b5" name="tests/driver/skipif.inc"/>
  </filelist>
 </release>
</package>',
  'url' => 'http://pear.php.net/get/DB-1.7.5',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'optional' => 'yes',
    'name' => 'MDB',
    'channel' => 'pear.php.net',
    'package' => 'MDB',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '1.3.0',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>MDB</name>
  <summary>database abstraction layer</summary>
  <description>PEAR MDB is a merge of the PEAR DB and Metabase php database abstraction layers.
It provides a common API for all support RDBMS. The main difference to most
other DB abstraction packages is that MDB goes much further to ensure
portability. Among other things MDB features:
* An OO-style query API
* A DSN (data source name) or array format for specifying database servers
* Datatype abstraction and on demand datatype conversion
* Portable error codes
* Sequential and non sequential row fetching as well as bulk fetching
* Ordered array and associative array for the fetched rows
* Prepare/execute (bind) emulation
* Sequence emulation
* Replace emulation
* Limited Subselect emulation
* Row limit support
* Transactions support
* Large Object support
* Index/Unique support
* Module Framework to load advanced functionality on demand
* Table information interface
* RDBMS management methods (creating, dropping, altering)
* RDBMS independent xml based schema definition management
* Altering of a DB from a changed xml schema
* Reverse engineering of xml schemas from an existing DB (currently only MySQL)
* Full integration into the PEAR Framework
* Wrappers for the PEAR DB and Metabase APIs
* PHPDoc API documentation
Currently supported RDBMS:
MySQL
PostGreSQL
Oracle
Frontbase
Querysim
Interbase/Firebird
MSSQL</description>
  <maintainers>
    <maintainer>
      <user>lsmith</user>
      <name>Lukas Kahwe Smith</name>
      <email>lsmith@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>dickmann</user>
      <name>Christian Dickmann</name>
      <email>chrisdicki@gmx.de</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>pgc</user>
      <name>Paul Cooper</name>
      <email>pgc@ucecom.com</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>ssb</user>
      <name>Stig S?ther Bakken</name>
      <email>stig@php.net</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>cox</user>
      <name>Tomas V.V.Cox</name>
      <email>cox@php.net</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>manuel</user>
      <name>Manuel Lemos</name>
      <email>mlemos@acm.org</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>fmk</user>
      <name>Frank M. Kromann</name>
      <email>frank@kromann.info</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>quipo</user>
      <name>Lorenzo Alberton</name>
      <email>l.alberton@quipo.it</email>
      <role>contributor</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.3.0</version>
    <date>2003-04-22</date>
    <license>BSD style</license>
    <state>stable</state>
    <notes>MDB requires PHP 4.2 from now on.
MDB:
- fixed PHP5 compatibility issue in MDB::isError()
all drivers:
- added quoteIdentifier() method
- added sequence_col_name option to make the column name inside sequence
  emulation tables configurable
- renamed toString() to __toString() in order to take advantage of new PHP5
  goodness and made it public
- unified the native error raising methods (tested on oracle, pgsql, mysql and ibase)
- fixed bug #1159 which would break index handling in getTableFieldDefinition()
  if not in portability mode
MDB_ibase:
- fixed several bugs in the buffering code
- fixed NULL management
- fixed replace()
MDB_oci8:
- fixed several bugs in the buffering code
- added native currId() implementation
MDB_Manager_oci8:
- added listTables() and listTableFields()
MDB_mysql:
- added quoteIdentifier() method
MDB_fbsql:
- removed broken implementations of currId()
MDB_mssql:
- removed broken implementations of currId()
- added quoteIdentifier() method
MDB_Manager_mysql:
- fixed mysql 4.0.13 issue in createSequence()
- several fixes to ensure the correct case is used when fetching data
  without the portability flag setting enabled
MDB_Manager_mssql:
- added listTables() and listTableFields()
- added getTableFieldDefinition() (still alpha quality)
test suite:
- added several test and applied PHP5 compatibility fixes
- fixed a wrong assumption in the fetchmode bug test
- moved set_time_limit() call to the setup script to be easier to customize</notes>
    <deps>
      <dep type="php" rel="ge" version="4.2.0"/>
      <dep type="pkg" rel="ge" version="1.0b1">PEAR</dep>
      <dep type="pkg" rel="has">XML_Parser</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="/" name="MDB.php"/>
      <file role="doc" name="README"/>
      <file role="doc" name="MAINTAINERS"/>
      <file role="doc" name="TODO"/>
      <file role="php" name="MDB/Common.php"/>
      <file role="php" name="MDB/querysim.php"/>
      <file role="php" name="MDB/mssql.php"/>
      <file role="php" name="MDB/ibase.php"/>
      <file role="php" name="MDB/oci8.php"/>
      <file role="php" name="MDB/fbsql.php"/>
      <file role="php" name="MDB/mysql.php"/>
      <file role="php" name="MDB/pgsql.php"/>
      <file role="php" name="MDB/Date.php"/>
      <file role="php" name="MDB/Manager.php"/>
      <file role="php" name="MDB/Parser.php"/>
      <file role="php" name="MDB/metabase_wrapper.php"/>
      <file role="php" name="MDB/peardb_wrapper.php"/>
      <file role="php" name="MDB/reverse_engineer_xml_schema.php"/>
      <file role="php" name="MDB/Modules/LOB.php"/>
      <file role="php" name="MDB/Modules/Manager/Common.php"/>
      <file role="php" name="MDB/Modules/Manager/mssql.php"/>
      <file role="php" name="MDB/Modules/Manager/ibase.php"/>
      <file role="php" name="MDB/Modules/Manager/oci8.php"/>
      <file role="php" name="MDB/Modules/Manager/fbsql.php"/>
      <file role="php" name="MDB/Modules/Manager/mysql.php"/>
      <file role="php" name="MDB/Modules/Manager/pgsql.php"/>
      <file role="doc" name="doc/tutorial.html"/>
      <file role="doc" name="doc/datatypes.html"/>
      <file role="doc" name="doc/xml_schema_documentation.html"/>
      <file role="doc" name="doc/xml_schema.xsl"/>
      <file role="doc" name="doc/skeleton.php"/>
      <file role="doc" name="doc/Modules_Manager_skeleton.php"/>
      <file role="test" name="tests/README"/>
      <file role="test" name="tests/test.php"/>
      <file role="test" name="tests/clitest.php"/>
      <file role="test" name="tests/testchoose.php"/>
      <file role="test" name="tests/MDB_api_testcase.php"/>
      <file role="test" name="tests/MDB_manager_testcase.php"/>
      <file role="test" name="tests/MDB_usage_testcase.php"/>
      <file role="test" name="tests/MDB_bugs_testcase.php"/>
      <file role="test" name="tests/HTML_TestListener.php"/>
      <file role="test" name="tests/Console_TestListener.php"/>
      <file role="test" name="tests/tests.css"/>
      <file role="test" name="tests/testUtils.php"/>
      <file role="test" name="tests/test_setup.php.dist"/>
      <file role="test" name="tests/test.schema"/>
      <file role="test" name="tests/MDB_test.php"/>
      <file role="test" name="tests/MDB_pear_wrapper_test.php"/>
      <file role="test" name="tests/metapear_test_db.schema"/>
      <file role="test" name="tests/driver_test_config.php"/>
      <file role="test" name="tests/driver_test.php"/>
      <file role="test" name="tests/setup_test.php"/>
      <file role="test" name="tests/driver_test.schema"/>
      <file role="test" name="tests/lob_test.schema"/>
      <file role="test" name="tests/templates/results.tpl"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.3</version>
      <date>2003-04-22</date>
      <state>stable</state>
      <notes>MDB requires PHP 4.2 from now on.
MDB:
- fixed PHP5 compatibility issue in MDB::isError()
all drivers:
- added quoteIdentifier() method
- added sequence_col_name option to make the column name inside sequence
  emulation tables configurable
- renamed toString() to __toString() in order to take advantage of new PHP5
  goodness and made it public
- unified the native error raising methods (tested on oracle, pgsql, mysql and ibase)
- fixed bug #1159 which would break index handling in getTableFieldDefinition()
  if not in portability mode
MDB_ibase:
- fixed several bugs in the buffering code
- fixed NULL management
- fixed replace()
MDB_oci8:
- fixed several bugs in the buffering code
- added native currId() implementation
MDB_Manager_oci8:
- added listTables() and listTableFields()
MDB_mysql:
- added quoteIdentifier() method
MDB_fbsql:
- removed broken implementations of currId()
MDB_mssql:
- removed broken implementations of currId()
- added quoteIdentifier() method
MDB_Manager_mysql:
- fixed mysql 4.0.13 issue in createSequence()
- several fixes to ensure the correct case is used when fetching data
  without the portability flag setting enabled
MDB_Manager_mssql:
- added listTables() and listTableFields()
- added getTableFieldDefinition() (still alpha quality)
test suite:
- added several test and applied PHP5 compatibility fixes
- fixed a wrong assumption in the fetchmode bug test
- moved set_time_limit() call to the setup script to be easier to customize
</notes>
    </release>
    <release>
      <version>1.2</version>
      <date>2004-01-11</date>
      <state>stable</state>
      <notes>- fixed potential memory leaks in the handling of metadata associated with
  result sets
- silenced all calls to native RDBMS API calls
MDB:
- fixed issue in MDB::singleton() when using array dsn\'s
MDB_Common:
- fixed typo in fetchCol (bug #523)
MDB_Driver_mssql:
- fixed parse error
MDB_Driver_oci:
- fixed bug in the result buffering code
test suite:
- fixed typo in the output of console test results
</notes>
    </release>
    <release>
      <version>1.1.4</version>
      <date>2004-01-05</date>
      <state>stable</state>
      <notes>This release marks the end of the feature additions to MDB 1.x. All further
feature additions will do into the MDB 2.x (aka MDB2) branch. MDB 1.x will
of course still be actively maintained and possibly new drivers may get added.
All:
- fixed issues with PHP5
- cosmetic fixes
MDB Class:
- added MDB::isConnection()
- fixed issues in MDB::singleton() if instances of MDB have been disconnected
test suite:
- minor improvements to the test suite
- fixed most CS issues in the test suite
- fixed bug in test suite (user_id was incorrectly set to type text instead of integer)
- added a test for MDB::singleton()
MDB_Common:
- fixed bug in support() that would result in always returning true
- fixed bug in getValue() when $type is empty
- fixed bug in getDSN() incorrect handling of port value
- fixed bug in currID() which would result in a fatal error
- fixed the common implementation of fetchInto()
- added MDB_FETCHMODE_ASSOC to the common implementation of fetchInto()
All drivers:
- backported several fixes from HEAD to each of the drivers
- fixed bug in extension detection in all drivers
- fixed bug 22328
- added notes at the top of the driver regarding driver specfic issues
- disconnect now unsets instead of overwriting with \'\' in $GLOBALS[\'_MDB_databases\']
- added optimize option
- lowercase keys in associative results if optimize option is set to portability
MySQL driver:
- fixed bug in the transaction support detection in the manager class
Interbase driver:
- now passes all but the transaction test
- now also supports associative fetching
- added missing getTypeDeclaration() method
- fixed replace emulation
- fixed bug in interbase driver LOB handling
- fixed autofree in fetchInto()
Oracle driver:
- fixed autofree in fetchInto()
- fixed a typo in convertResult()
MSSQL driver:
- now passes all tests
- numerous bug fixes
FBSQL driver:
- numerous bug fixes to all parts of the driver (especially to the connection handling,
datatype abstraction, limit support and manager class)
PGSQL driver:
- fixed a bug in the error code mapping due to changes in recent PostGreSQL versions

</notes>
    </release>
    <release>
      <version>1.1.3</version>
      <date>2003-06-13</date>
      <state>stable</state>
      <notes>- added MDB::singleton()
- added MDB_Common destructor
- fixed serious issue in fetch[One|Row|Col|All] which prevented result sets to be free-ed correctly
- improvements to the manager test suite
- added MSSQL driver (alpha)
- improved Frontbase driver
</notes>
    </release>
    <release>
      <version>1.1.3-RC2</version>
      <date>2003-06-03</date>
      <state>devel</state>
      <notes>- added MDB::singleton()
- added MDB_Common destructor
- fixed serious issue in fetch[One|Row|Col|All] which prevented result sets to be free-ed correctly
- improvements to the manager test suite
</notes>
    </release>
    <release>
      <version>1.1.3-RC1</version>
      <date>2003-06-01</date>
      <state>devel</state>
      <notes>- added MDB::singleton()
- added MDB_Common destructor
- fixed serious issue in fetch[One|Row|Col|All] which prevented result sets to be free-ed correctly
- improvements to the manager test suite
</notes>
    </release>
    <release>
      <version>1.1.2</version>
      <date>2003-04-23</date>
      <state>stable</state>
      <notes>- This is mainly a bug fix release
- 4 new driver were added
  Oracle (still Beta)
  Frontbase (still Alpha)
  Interbase/Firebird (still Alpha, due to missing features)
  Querysim
  - All get*Value() methods (excet get*lobValue() for now) will convert a php NULL into an SQL NULL
    (resulting in API changes in the NULL handling of the get*Value() and replace() methods)
  - REPLACE emulation now works more similar to how MySQLs REPLACE works
  - Moved code from the Common constructor into MDB::connect()
  - Moved code from the Driver constructor into the drivers connect method
  - PostGreSQL reverse engineering partly implemented
  - Made the MDB_Date classe behave more similar to PEAR::Date (especially in regards to daylight saving time)

</notes>
    </release>
    <release>
      <version>1.1.1</version>
      <date>2002-11-26</date>
      <state>stable</state>
      <notes>Since the changelog for the 1.1.0 release was incomplete here follows the complete list of changes from the 1.0 release:
Minor bugfixes and PHPDoc enhancements
PEAR-ized directory structure and class names
Added PHPUnit test suite (browser and cli)
Manager.php does not load MDB.php anymore (include MDB.php instead and use MDB::loadFile())
MDB::connect() does not need to be modified anymore to add support for a new driver
API changes:
- MDB_common::loadExtension renamed to MDB_common::loadModule
- MDB::assertExtension was dropped in favor of PEAR::loadExtension
- MDB::loadFile was added to load additional files (from now on only MDB.php will be included directly)
</notes>
    </release>
    <release>
      <version>1.1.0</version>
      <date>2002-11-24</date>
      <state>devel</state>
      <notes>PEAR-ized directory structure and class names
Added PHPUnit test suite (browser and cli)
Minor bugfixes and API changes
</notes>
    </release>
    <release>
      <version>1.1.0pl1</version>
      <date>2002-11-25</date>
      <state>devel</state>
      <notes>Fixed issue of metapear_test_db.schema being in the wrong dir
</notes>
    </release>
    <release>
      <version>1.0.1RC1</version>
      <date>2002-11-14</date>
      <state>devel</state>
      <notes>PEAR-ized directory structure and class names
Added PHPUnit test suite
Minor bugfixes and API changes
</notes>
    </release>
    <release>
      <version>1.0</version>
      <date>2002-09-08</date>
      <state>stable</state>
      <notes>First stable release (repackaged RC4). Added Paul Cooper to the list of contributors. Please see README.txt for details.
</notes>
    </release>
    <release>
      <version>1.0_RC4</version>
      <date>2002-09-07</date>
      <state>devel</state>
      <notes>Just minor bugs fixes and beautifications in several places. Added skeleton drivers to help driver authors.
</notes>
    </release>
    <release>
      <version>1.0_RC3</version>
      <date>2002-09-05</date>
      <state>devel</state>
      <notes>this is release candidate 3 for MDB 1.0 featuring: major fixes and improvements to the MDB manager; bug fixes to the parser and date; both the mysql and the postgresql driver can now run query() without being connected to a specific database; added xsl that can render xml schema files to html; added initial version of a tutorial
</notes>
    </release>
    <release>
      <version>1.0_RC2</version>
      <date>2002-08-21</date>
      <state>devel</state>
      <notes>this is release candidate 2 for MDB 1.0 featuring: major fixes and improvements to the MDB manager; totaly new XML_Parser based parser with much improved speed; moved date functions to a seperate class
</notes>
    </release>
    <release>
      <version>1.0_RC1</version>
      <date>2002-08-11</date>
      <state>devel</state>
      <notes>this is the first release candidate for MDB 1.0; it contains mostly cosmetic changes but also improvements to reverse engineering of xml schemas from existing MySQL DBs.
</notes>
    </release>
    <release>
      <version>0.9.11</version>
      <date>2002-08-05</date>
      <state>devel</state>
      <notes>made feature improvements and bug fixs to the manager; pgsql core driver now passes the driver test suite; lob support cleanup considerably
</notes>
    </release>
    <release>
      <version>0.9.10</version>
      <date>2002-07-30</date>
      <state>devel</state>
      <notes>cleanups all over MDB; large improvements to the MDB manager
</notes>
    </release>
    <release>
      <version>0.9.9</version>
      <date>2002-07-17</date>
      <state>beta</state>
      <notes>Further cleanups to the API;fixes to the pgsql driver; manager can now reverse engineer sequences into an xml schema
</notes>
    </release>
    <release>
      <version>0.9.8</version>
      <date>2002-07-04</date>
      <state>beta</state>
      <notes>Further cleanups to the API (especially for sending the types of to be fetched data);added initial pgsql manager class; formatting and eol fixes
</notes>
    </release>
    <release>
      <version>0.9.7.1</version>
      <date>2002-06-20</date>
      <state>beta</state>
      <notes>Bugfix release: fetchInto in the pgsql driver and baseFetchInto fixed to handle when now run numbers is passed to the method; mysql subselect emulation now returns NULL if no data is found;
</notes>
    </release>
    <release>
      <version>0.9.7</version>
      <date>2002-06-20</date>
      <state>beta</state>
      <notes>PHPDoc have now been added to all methods in common.php; some API changes, mostly to the transaction methods; introduced a simple subselect emulation; added postgresql driver; fix fetchInto in the pear db wrapper
</notes>
    </release>
    <release>
      <version>0.9.6</version>
      <date>2002-06-12</date>
      <state>beta</state>
      <notes>fixed a serious bug in parser.php that prevented tables from being initialized correctly; added several new methods to better match the PEAR DB feature set; added PHPDoc comments to most methods in common.php; more formating improvements
</notes>
    </release>
    <release>
      <version>0.9.5</version>
      <date>2002-06-05</date>
      <state>beta</state>
      <notes>added autofree option; dropped setup() infavor of class contructors; minor changes to the API; improvements to the Metabase wrapper
</notes>
    </release>
    <release>
      <version>0.9.4</version>
      <date>2002-05-31</date>
      <state>beta</state>
      <notes>Moved all DB management methods into a seperate class that is loaded on demand; MDB manager can now create an xml schema file from an existing DB;Improvements to the error handling, XMl schema manager and Metabase Wrapper as well as general formatting tweaks
</notes>
    </release>
    <release>
      <version>0.9.3</version>
      <date>2002-05-17</date>
      <state>beta</state>
      <notes>MDB now uses the currect include path in all situations; improvements to the error handling (thx to Christian Dickmann) and  the pear wrapper were made
</notes>
    </release>
    <release>
      <version>0.9.1</version>
      <date>2002-05-03</date>
      <state>beta</state>
      <notes>fixed errors in package.xml
</notes>
    </release>
    <release>
      <version>0.9</version>
      <date>2002-05-03</date>
      <state>beta</state>
      <notes>First packaged release of MDB
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/MDB-1.3.0',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'optional' => 'yes',
    'name' => 'MDB2',
    'channel' => 'pear.php.net',
    'package' => 'MDB2',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '2.0.0beta3',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>MDB2</name>
  <summary>database abstraction layer</summary>
  <description>PEAR MDB2 is a merge of the PEAR DB and Metabase php database abstraction layers.

Note that the API will be adapted to better fit with the new php5 only PDO
before the first stable release.

It provides a common API for all support RDBMS. The main difference to most
other DB abstraction packages is that MDB2 goes much further to ensure
portability. Among other things MDB2 features:
* An OO-style query API
* A DSN (data source name) or array format for specifying database servers
* Datatype abstraction and on demand datatype conversion
* Portable error codes
* Sequential and non sequential row fetching as well as bulk fetching
* Ability to make buffered and unbuffered queries
* Ordered array and associative array for the fetched rows
* Prepare/execute (bind) emulation
* Sequence emulation
* Replace emulation
* Limited Subselect emulation
* Row limit support
* Transactions support
* Large Object support
* Index/Unique support
* Module Framework to load advanced functionality on demand
* Table information interface
* RDBMS management methods (creating, dropping, altering)
* RDBMS independent xml based schema definition management
* Altering of a DB from a changed xml schema
* Reverse engineering of xml schemas from an existing DB (currently only MySQL)
* Full integration into the PEAR Framework
* PHPDoc API documentation

Currently supported RDBMS:
MySQL (mysql and mysqli extension)
PostGreSQL
Oracle
Frontbase
Querysim
Interbase/Firebird
MSSQL
SQLite
Other soon to follow.</description>
  <maintainers>
    <maintainer>
      <user>lsmith</user>
      <name>Lukas Kahwe Smith</name>
      <email>smith@backendmedia.com</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>pgc</user>
      <name>Paul Cooper</name>
      <email>pgc@ucecom.com</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>fmk</user>
      <name>Frank M. Kromann</name>
      <email>frank@kromann.info</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>quipo</user>
      <name>Lorenzo Alberton</name>
      <email>l.alberton@quipo.it</email>
      <role>contributor</role>
    </maintainer>
    <maintainer>
      <user>danielc</user>
      <name>Daniel Convissor</name>
      <email>danielc@php.net</email>
      <role>helper</role>
    </maintainer>
  </maintainers>
  <release>
    <version>2.0.0beta3</version>
    <date>2005-03-06</date>
    <license>BSD License</license>
    <state>beta</state>
    <notes>Warning: this release features numerous BC breaks to make the MDB2 API be as
similar as possible as the ext/pdo API! The next release is likely to also break
BC for the same reason. Check php.net/pdo for information on the pdo API.

Oracle NULL in LOB fields is broken.
The fbsql and mssql drivers are likely to be broken as they are largely untested.

MDB2 static class:
- &quot;xxx&quot; out password on connect error in MDB2::connect()
- MDB2::isError now also optionally accepts and error code to check for
- added LOAD DATA (port from DB) and SET to MDB2::isManip()

All drivers:
- use __construct() (PHP4 BC hacks are provided)
- allow null values to be set for options
- ensure we are returning a reference in all relevant places

- allow errorInfo() to be called when no connection has been established yet
- use MDB2_ERROR_UNSUPPORTED instead of MDB2_ERROR_NOT_CAPABLE in common implementations
- readded MDB2_Error as the baseclass for all MDB2 error objects
- updated error mappings from DB

- added MDB2_Driver_Common::getDatabase();
- reworked dsn default handling
- added ability to &quot;xxx&quot; out password in getDSN()

- use _close() method in several places where they previously were not used
- removed redundant code in _close() that dealt with transaction closing already
  done in disconnect()
- if the dbsyntax is set in the dsn it will be set in the dbsyntax property
- only disconnect persistant connections if disconnect() has been explicitly
  called by the user
- instead of having a generic implemention of disconnect() we will rename
  _close() to disconnect() to overwrite the generic implementation
- added support for \'new_link\' dsn option for all supported drivers (mysql, oci8, pgsql)

- transaction API moved over to PDO: removed autoCommit(), added beginTransaction()
  and refactored commit() (it doesn\'t start a new transaction automatically anymore)
- reworked handling of uncommited transaction for persistant connections when
  a given connection is no longer in use

- added \'disable_query\' option to be able to disable the execution of all queries
 (this might be useful in conjuntion with a custom debug handler to be able to
 dump all queries into a file instead of executing them)
- removed affectedRows() method in favor of returning affectedRows() on query if relevant
- added generic implementation of query() and moved driver specific code into _doQuery()
- added _modifyQuery() to any driver that did not yet have it yet
- standaloneQuery() now also supports SELECT querys
- remove redundant call to commit() since setting autoCommit() already commits in MDB2::replace()
- refactored standaloneQuery(), query(), _doQuery(), _wrapResult(); the most important change are:
  result are only wrapped if it is explicitly requested
  standaloneQuery() now works just as query() does but with its own connection
- allowing limits of 0 in setLimit()

- explicitly specify colum name in sequence emulation queries
- added getBeforeId() and getAfterId()
- added new supported feature \'auto_increment\'

- added default implementation for quoteCLOB() and quoteBLOB()
- reworked quote handling: moved all implementation details into the extension,
  made all quote methods private except for quote() itself, honor portability
  MDB2_PORTABILITY_EMPTY_TO_NULL in quote(), removed MDB2_TYPE_* constants
- reworked get*Declaration handling: moved all implementation details into the extension,
  made all quote methods private except for quote() itself
- placed convert methods after the portability conversions to ensure that the
  proper type is maintained after the conversion methods
- dont convert fetched null values in the Datatype module

- removed executeParams() and moved executeMultiple() from extended module

- updated tableInfo() code from DB

- made LIMIT handling more robust by taking some code from DB

All drivers result:
- performance tweak in fetchCol()
- added MDB2_FETCHMODE_OBJECT
- added MDB2_Driver_Result_Common::getRowCounter()
- added rownum handling to fetchRow()
- removed fetch() and resultIsNull()

All drivers prepared statements
- moved prepare/execute API towards PDO
- setParamsArray() can now handle non ordered arrays
- removed requirement for LOB inserts to pass the parameters as an array
- placeholders are now numbered starting from 0 (BC break in setParam() !)
- queries inside the prepared_queries property now start counting at 1 (performance tweak)
- refactored handling of filename LOB values (prefix with \'file://\')
- removed _executePrepared(), drivers need to overwrite execute() for now on
- add support for oracle style named parameters and modified test suite accordingly

MySQL driver:
- improved handling of MDB2_PORTABILITY_LOWERCASE in all the reverse
  methods inside the mysql driver to work coherently
- fixed several issues in the listTablefields() method of manager drivers

MSSQL driver:
- added code in MDB2_Driver_mssql::connect() to better handle date values
  independant of ini and locale settings inside the server
- use comma, rather than colon, to delimit port in MDB2_driver_mssql::connect().
  Bug 2140. (danielc)
- unified mssql standalone query with sqlite, mysql and others (not tested on
  mssql yet, but since mssql automatically reuses connections per dsn the old
  way could gurantee anything different from happening)

PgSQL driver:
- use track_errors to capture error messages in MDB2_driver_pgsql::connect().
  Bug 2011. (danielc)
- add port to connect string when protocol is unix in MDB2_driver_pgsql::connect().
  Bug 1919. (danielc)
- accommodate changes made to PostgreSQL so &quot;no such field&quot; errors get properly
  indicated rather than being mislabeled as &quot;no such table.&quot; (danielc)
- added &quot;permission denied&quot; to error regex in pgsql driver.
  Bug 2417. (stewart_linux-org-au)

OCI8 driver:
- fixed typo in MDB2_Driver_Manager_oci8::listTables() (fix for bug #2434)
- added emulate_database option (default true) to the Oracle driver that handles
  if the database_name should be used for connections of the username
- oci8 driver now uses native bind support for all types in prepare()/execute()

Interbase driver:
- completely revised ibase driver, now passing all tests under php5

Frontbase driver:
- fbsql: use correct error codes. Was using MySQL\'s codes by mistake.

MySQLi driver:
- added mysqli driver (passes all tests, but doesnt use native prepare yet)

DB wrapper
- fixed a large number of compatibility issues in the PEAR::DB wrapper

Iterator
- fixed several bugs and updated the interface to match the final php5 iterator API
- buffered result sets now implements seekable
- removed unnecessary returns
- throw pear error on rewind in unbuffered result set
- renamed size() to count() to match the upcoming Countable interface

Extended module:
- modified the signature of the auto*() methods to be compatible with DB (bug #3720)
- tweaked buildManipSQL() to not use loops (bug #3721)

MDB_Tools_Manager
- updated raiseError method in the Manager to be compatible with
  XML_Parser 1.1.x and return useful error message (fix bug #2055)
- major refactoring of MDB2_Manager resulting in several new methods being available
- fixed error in MDB2_Manager::_escapeSpecialCharacter() that would lead to
  incorrect handling of integer values (this needs to be explored in more detail)
- several typo fixes and minor logic errors (among others a fix for bug #2057)
- moved xml dumping in MDB2_Tools_Manager into separate Writer class
- fixed bugs in start value handling in create sequence (bug #3077)</notes>
    <deps>
      <dep type="php" rel="ge" version="4.2.0" optional="no"/>
      <dep type="pkg" rel="ge" version="1.0b1" optional="no">PEAR</dep>
      <dep type="pkg" rel="has" optional="no">XML_Parser</dep>
    </deps>
    <filelist>
      <file role="doc" baseinstalldir="/" md5sum="a253b37e185622112acfef6c94b79aef" name="docs' . DIRECTORY_SEPARATOR . 'CONTRIBUTORS"/>
      <file role="doc" baseinstalldir="/" md5sum="cc1befe78146094be02f89bbb201b4ab" name="docs' . DIRECTORY_SEPARATOR . 'datatypes.html"/>
      <file role="doc" baseinstalldir="/" md5sum="58cfee79a8e774d8c10e3a773af7fe80" name="docs' . DIRECTORY_SEPARATOR . 'Driver_Datatype_skeleton.php"/>
      <file role="doc" baseinstalldir="/" md5sum="b194e5252ada4e068b40fc9c9364e957" name="docs' . DIRECTORY_SEPARATOR . 'Driver_Manager_skeleton.php"/>
      <file role="doc" baseinstalldir="/" md5sum="5ff94c70cae0816c90ac45e7f3c0032e" name="docs' . DIRECTORY_SEPARATOR . 'Driver_Native_skeleton.php"/>
      <file role="doc" baseinstalldir="/" md5sum="a8e3f88b940affb93e1e31c6ed32b8f1" name="docs' . DIRECTORY_SEPARATOR . 'Driver_Reverse_skeleton.php"/>
      <file role="doc" baseinstalldir="/" md5sum="ec1836a099f671246452426c397522a2" name="docs' . DIRECTORY_SEPARATOR . 'Driver_skeleton.php"/>
      <file role="doc" baseinstalldir="/" md5sum="c1fe9863db1ef4da67e4be7c549b0290" name="docs' . DIRECTORY_SEPARATOR . 'MAINTAINERS"/>
      <file role="doc" baseinstalldir="/" md5sum="175c39961f74f1f57f8517754c020d7a" name="docs' . DIRECTORY_SEPARATOR . 'MDB.dtd"/>
      <file role="doc" baseinstalldir="/" md5sum="716a1121d17b957ca952c6f1e9c4e4b1" name="docs' . DIRECTORY_SEPARATOR . 'MDB.xsl"/>
      <file role="doc" baseinstalldir="/" md5sum="d614bf6554118eee444c248efd2c43ee" name="docs' . DIRECTORY_SEPARATOR . 'querysim_readme.txt"/>
      <file role="doc" baseinstalldir="/" md5sum="2804f898c7cedd55613e89c5d4249694" name="docs' . DIRECTORY_SEPARATOR . 'README"/>
      <file role="doc" baseinstalldir="/" md5sum="bc422988051fb70b3763a09675f2901b" name="docs' . DIRECTORY_SEPARATOR . 'STATUS"/>
      <file role="doc" baseinstalldir="/" md5sum="690f96be982ff89dae76a826818c4ece" name="docs' . DIRECTORY_SEPARATOR . 'TODO"/>
      <file role="doc" baseinstalldir="/" md5sum="fea6536d9408f162c444a9229f9dbffe" name="docs' . DIRECTORY_SEPARATOR . 'xml_schema_documentation.html"/>
      <file role="doc" baseinstalldir="/" md5sum="3be0a9aaac03cc609edd2844d5c2e7a9" name="docs' . DIRECTORY_SEPARATOR . 'examples' . DIRECTORY_SEPARATOR . 'example.php"/>
      <file role="doc" baseinstalldir="/" md5sum="74b2bb45de61eccbffed7d75d5268af9" name="docs' . DIRECTORY_SEPARATOR . 'examples' . DIRECTORY_SEPARATOR . 'metapear_test_db.schema"/>
      <file role="doc" baseinstalldir="/" md5sum="735ae01f0aa509ba72d31a8add91a7e5" name="docs' . DIRECTORY_SEPARATOR . 'examples' . DIRECTORY_SEPARATOR . 'peardb_wrapper_example.php"/>
      <file role="php" baseinstalldir="/" md5sum="45d0294ce10540496158c80d17ac908e" name="MDB2' . DIRECTORY_SEPARATOR . 'Date.php"/>
      <file role="php" baseinstalldir="/" md5sum="1c992057725fe0b8b4507cff42a0876e" name="MDB2' . DIRECTORY_SEPARATOR . 'Extended.php"/>
      <file role="php" baseinstalldir="/" md5sum="a904afeec5b522664703cff7cd65a1df" name="MDB2' . DIRECTORY_SEPARATOR . 'Iterator.php"/>
      <file role="php" baseinstalldir="/" md5sum="fb273b97f351e09c3a966bca9de4764e" name="MDB2' . DIRECTORY_SEPARATOR . 'LOB.php"/>
      <file role="php" baseinstalldir="/" md5sum="4158dfb0c58c2b17b5b58fe53c599bc1" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'fbsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="23e300b3a2300ff6d88333fd84c23c0e" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'ibase.php"/>
      <file role="php" baseinstalldir="/" md5sum="e487fea0d49974ce15f3def38897198b" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'mssql.php"/>
      <file role="php" baseinstalldir="/" md5sum="c13405af54d0cc6693b64afc9b5080f9" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'mysql.php"/>
      <file role="php" baseinstalldir="/" md5sum="8d74cd98539f7f1a53a730136e9a41b0" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'mysqli.php"/>
      <file role="php" baseinstalldir="/" md5sum="62ef1902875e226ac75c503290b9e294" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'oci8.php"/>
      <file role="php" baseinstalldir="/" md5sum="6760b8b3918dccde801a7d267a109441" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'pgsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="8231c0eee49b8e8146a2ec4bac61e3e9" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'querysim.php"/>
      <file role="php" baseinstalldir="/" md5sum="fdce1af9bbc3866aad8868a94a469ada" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'sqlite.php"/>
      <file role="php" baseinstalldir="/" md5sum="9675987b9ff88c10dc8615fc1328ebfb" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'Common.php"/>
      <file role="php" baseinstalldir="/" md5sum="58f67ee46445366df5bd8fa528343374" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'fbsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="095a0bdfa2c3115d3619a453f87ba865" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'ibase.php"/>
      <file role="php" baseinstalldir="/" md5sum="983b812fafb0985c4e67d485ca1e83bd" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'mssql.php"/>
      <file role="php" baseinstalldir="/" md5sum="ad53fc77f71267fc699bed4d1ae7d8e5" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'mysql.php"/>
      <file role="php" baseinstalldir="/" md5sum="1301b509979de3aed85168c0991b21e9" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'mysqli.php"/>
      <file role="php" baseinstalldir="/" md5sum="352d13715f4cca47b7eb0309e167667e" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'oci8.php"/>
      <file role="php" baseinstalldir="/" md5sum="aa035fee32372404334e4e81102374ed" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'pgsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="3559cd8414321dd87763fe64a59af595" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Datatype' . DIRECTORY_SEPARATOR . 'sqlite.php"/>
      <file role="php" baseinstalldir="/" md5sum="2c94c48bb2eaf9d2a4e456a38d398a6e" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'Common.php"/>
      <file role="php" baseinstalldir="/" md5sum="c710b9f259bd31f14d1412b102ab5a4a" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'fbsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="bd79e81249b46097917916785212c5b8" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'ibase.php"/>
      <file role="php" baseinstalldir="/" md5sum="fc6703c9b8d582ad047bb2d0eefbbeae" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'mssql.php"/>
      <file role="php" baseinstalldir="/" md5sum="8b1b0735823a3624ea425f8dfc240968" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'mysql.php"/>
      <file role="php" baseinstalldir="/" md5sum="29d33c545b1ac901c7dfecb64845d251" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'mysqli.php"/>
      <file role="php" baseinstalldir="/" md5sum="980db08c69b02ae0ac3217c7568552d1" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'oci8.php"/>
      <file role="php" baseinstalldir="/" md5sum="d7cac7ddf11407baca0538894a5d446c" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'pgsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="522de0edee7df9b8b3cfaa1af2ecf5c2" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'sqlite.php"/>
      <file role="php" baseinstalldir="/" md5sum="56ea6703638c5a7c83ebea82cbc6f995" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'fbsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="213e93b813a1c9fe819283f3d4d63c34" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'ibase.php"/>
      <file role="php" baseinstalldir="/" md5sum="a8ac9ce7d21be75f947dcd6140d7a1e0" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'mssql.php"/>
      <file role="php" baseinstalldir="/" md5sum="3019eaf36b2ddeffb493d7e57efb9a16" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'mysql.php"/>
      <file role="php" baseinstalldir="/" md5sum="5c2fd09831a1e5552fb56047c9b471c1" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'mysqli.php"/>
      <file role="php" baseinstalldir="/" md5sum="0081a0d842d903b1542941a4a1954616" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'oci8.php"/>
      <file role="php" baseinstalldir="/" md5sum="07449f71953283f61cbf16057f6bf056" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'pgsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="4e570a7fffc947a71a8cd67ba1ec8b93" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Native' . DIRECTORY_SEPARATOR . 'sqlite.php"/>
      <file role="php" baseinstalldir="/" md5sum="bb2880245c0fabc19cb6b1b20d5e0bcf" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'Common.php"/>
      <file role="php" baseinstalldir="/" md5sum="9c38d067d90825c0bb795024a9dc0e3e" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'fbsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="e423e9d18380c51b5026e480f2146815" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'ibase.php"/>
      <file role="php" baseinstalldir="/" md5sum="871d40b5e75b4f6ca42d43ed9e6a9f37" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'mssql.php"/>
      <file role="php" baseinstalldir="/" md5sum="e9dfd9a58860b5be98f9b271f3d60594" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'mysql.php"/>
      <file role="php" baseinstalldir="/" md5sum="84ee70e87071e2fa30bb6e77f4c188f0" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'mysqli.php"/>
      <file role="php" baseinstalldir="/" md5sum="6de9b4e2a2f08067feda2b7b7d7d7067" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'oci8.php"/>
      <file role="php" baseinstalldir="/" md5sum="1e7debedff2bf4ab66284ba0a410c6df" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'pgsql.php"/>
      <file role="php" baseinstalldir="/" md5sum="df41b9eb9180f231e05d70762feb8677" name="MDB2' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'Reverse' . DIRECTORY_SEPARATOR . 'sqlite.php"/>
      <file role="php" baseinstalldir="/" md5sum="4d49ccb01c70b9c7b0c841e6174f6216" name="MDB2' . DIRECTORY_SEPARATOR . 'Tools' . DIRECTORY_SEPARATOR . 'Manager.php"/>
      <file role="php" baseinstalldir="/" md5sum="fffb3c9daf0626e5251eb4e2a84dc955" name="MDB2' . DIRECTORY_SEPARATOR . 'Tools' . DIRECTORY_SEPARATOR . 'reverse_engineer_xml_schema.php"/>
      <file role="php" baseinstalldir="/" md5sum="3afb34ce29d69aa2e8c8ad515a30078f" name="MDB2' . DIRECTORY_SEPARATOR . 'Tools' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'Parser.php"/>
      <file role="php" baseinstalldir="/" md5sum="8f5833ffe7516c6148b3448215a05eca" name="MDB2' . DIRECTORY_SEPARATOR . 'Tools' . DIRECTORY_SEPARATOR . 'Manager' . DIRECTORY_SEPARATOR . 'Writer.php"/>
      <file role="php" baseinstalldir="/" md5sum="a7ecc20e67507056542cf0b610e1515f" name="MDB2' . DIRECTORY_SEPARATOR . 'Wrapper' . DIRECTORY_SEPARATOR . 'peardb.php"/>
      <file role="test" baseinstalldir="/" md5sum="e422576d1b0cb3ee0455c2dd64ab51b9" name="tests' . DIRECTORY_SEPARATOR . 'clitest.php"/>
      <file role="test" baseinstalldir="/" md5sum="2a2c534ab4afb0c05ca9d7ca47815bf5" name="tests' . DIRECTORY_SEPARATOR . 'Console_TestListener.php"/>
      <file role="test" baseinstalldir="/" md5sum="533b34483b07659817b51735978a461a" name="tests' . DIRECTORY_SEPARATOR . 'driver_test.schema"/>
      <file role="test" baseinstalldir="/" md5sum="d46a8f267dbd54f0c7ff55e479d33e7e" name="tests' . DIRECTORY_SEPARATOR . 'HTML_TestListener.php"/>
      <file role="test" baseinstalldir="/" md5sum="229d54dc8298b7513515cce639c83f38" name="tests' . DIRECTORY_SEPARATOR . 'lob_test.schema"/>
      <file role="test" baseinstalldir="/" md5sum="a3b2ee4233bfbbd7105e3e62af4e2d6a" name="tests' . DIRECTORY_SEPARATOR . 'MDB2_api_testcase.php"/>
      <file role="test" baseinstalldir="/" md5sum="3b6aac33c846ecf039364f50840622c0" name="tests' . DIRECTORY_SEPARATOR . 'MDB2_bugs_testcase.php"/>
      <file role="test" baseinstalldir="/" md5sum="9e687f2f00d844d0be7f99cba2783d60" name="tests' . DIRECTORY_SEPARATOR . 'MDB2_manager_testcase.php"/>
      <file role="test" baseinstalldir="/" md5sum="c7652c54de50b33a7bdd60d9267f23a8" name="tests' . DIRECTORY_SEPARATOR . 'MDB2_native_testcase.php"/>
      <file role="test" baseinstalldir="/" md5sum="6340b293c70ba7892846bfe684307899" name="tests' . DIRECTORY_SEPARATOR . 'MDB2_reverse_testcase.php"/>
      <file role="test" baseinstalldir="/" md5sum="efff8ec5e4cab5719fbf82612a8dfd9a" name="tests' . DIRECTORY_SEPARATOR . 'MDB2_usage_testcase.php"/>
      <file role="test" baseinstalldir="/" md5sum="59f2441ab897a919b75acc4409597a10" name="tests' . DIRECTORY_SEPARATOR . 'README"/>
      <file role="test" baseinstalldir="/" md5sum="52cb8846edd4bdc49d1c944d89993512" name="tests' . DIRECTORY_SEPARATOR . 'test.php"/>
      <file role="test" baseinstalldir="/" md5sum="aea7a3e5eaba197c7b7ff215d2305d61" name="tests' . DIRECTORY_SEPARATOR . 'testchoose.php"/>
      <file role="test" baseinstalldir="/" md5sum="3c4d2c9d89398c5692d36299d98f9c6e" name="tests' . DIRECTORY_SEPARATOR . 'tests.css"/>
      <file role="test" baseinstalldir="/" md5sum="435a37eb1acdea594dab91c5f11308ed" name="tests' . DIRECTORY_SEPARATOR . 'testUtils.php"/>
      <file role="test" baseinstalldir="/" md5sum="7494ce5eae49f6b03cc374cb61db8f08" name="tests' . DIRECTORY_SEPARATOR . 'test_setup.php.dist"/>
      <file role="test" baseinstalldir="/" md5sum="f243a1982517f94f116f23d21fa9d794" name="tests' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'results.tpl"/>
      <file role="php" baseinstalldir="/" md5sum="84624c8e4933d96a570964780a2dabe9" name="MDB2.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>2.0.0beta3</version>
      <date>2005-03-06</date>
      <license>BSD License</license>
      <state>beta</state>
      <notes>Warning: this release features numerous BC breaks to make the MDB2 API be as
similar as possible as the ext/pdo API! The next release is likely to also break
BC for the same reason. Check php.net/pdo for information on the pdo API.

Oracle NULL in LOB fields is broken.
The fbsql and mssql drivers are likely to be broken as they are largely untested.

MDB2 static class:
- &quot;xxx&quot; out password on connect error in MDB2::connect()
- MDB2::isError now also optionally accepts and error code to check for
- added LOAD DATA (port from DB) and SET to MDB2::isManip()

All drivers:
- use __construct() (PHP4 BC hacks are provided)
- allow null values to be set for options
- ensure we are returning a reference in all relevant places

- allow errorInfo() to be called when no connection has been established yet
- use MDB2_ERROR_UNSUPPORTED instead of MDB2_ERROR_NOT_CAPABLE in common implementations
- readded MDB2_Error as the baseclass for all MDB2 error objects
- updated error mappings from DB

- added MDB2_Driver_Common::getDatabase();
- reworked dsn default handling
- added ability to &quot;xxx&quot; out password in getDSN()

- use _close() method in several places where they previously were not used
- removed redundant code in _close() that dealt with transaction closing already
  done in disconnect()
- if the dbsyntax is set in the dsn it will be set in the dbsyntax property
- only disconnect persistant connections if disconnect() has been explicitly
  called by the user
- instead of having a generic implemention of disconnect() we will rename
  _close() to disconnect() to overwrite the generic implementation
- added support for \'new_link\' dsn option for all supported drivers (mysql, oci8, pgsql)

- transaction API moved over to PDO: removed autoCommit(), added beginTransaction()
  and refactored commit() (it doesn\'t start a new transaction automatically anymore)
- reworked handling of uncommited transaction for persistant connections when
  a given connection is no longer in use

- added \'disable_query\' option to be able to disable the execution of all queries
 (this might be useful in conjuntion with a custom debug handler to be able to
 dump all queries into a file instead of executing them)
- removed affectedRows() method in favor of returning affectedRows() on query if relevant
- added generic implementation of query() and moved driver specific code into _doQuery()
- added _modifyQuery() to any driver that did not yet have it yet
- standaloneQuery() now also supports SELECT querys
- remove redundant call to commit() since setting autoCommit() already commits in MDB2::replace()
- refactored standaloneQuery(), query(), _doQuery(), _wrapResult(); the most important change are:
  result are only wrapped if it is explicitly requested
  standaloneQuery() now works just as query() does but with its own connection
- allowing limits of 0 in setLimit()

- explicitly specify colum name in sequence emulation queries
- added getBeforeId() and getAfterId()
- added new supported feature \'auto_increment\'

- added default implementation for quoteCLOB() and quoteBLOB()
- reworked quote handling: moved all implementation details into the extension,
  made all quote methods private except for quote() itself, honor portability
  MDB2_PORTABILITY_EMPTY_TO_NULL in quote(), removed MDB2_TYPE_* constants
- reworked get*Declaration handling: moved all implementation details into the extension,
  made all quote methods private except for quote() itself
- placed convert methods after the portability conversions to ensure that the
  proper type is maintained after the conversion methods
- dont convert fetched null values in the Datatype module

- removed executeParams() and moved executeMultiple() from extended module

- updated tableInfo() code from DB

- made LIMIT handling more robust by taking some code from DB

All drivers result:
- performance tweak in fetchCol()
- added MDB2_FETCHMODE_OBJECT
- added MDB2_Driver_Result_Common::getRowCounter()
- added rownum handling to fetchRow()
- removed fetch() and resultIsNull()

All drivers prepared statements
- moved prepare/execute API towards PDO
- setParamsArray() can now handle non ordered arrays
- removed requirement for LOB inserts to pass the parameters as an array
- placeholders are now numbered starting from 0 (BC break in setParam() !)
- queries inside the prepared_queries property now start counting at 1 (performance tweak)
- refactored handling of filename LOB values (prefix with \'file://\')
- removed _executePrepared(), drivers need to overwrite execute() for now on
- add support for oracle style named parameters and modified test suite accordingly

MySQL driver:
- improved handling of MDB2_PORTABILITY_LOWERCASE in all the reverse
  methods inside the mysql driver to work coherently
- fixed several issues in the listTablefields() method of manager drivers

MSSQL driver:
- added code in MDB2_Driver_mssql::connect() to better handle date values
  independant of ini and locale settings inside the server
- use comma, rather than colon, to delimit port in MDB2_driver_mssql::connect().
  Bug 2140. (danielc)
- unified mssql standalone query with sqlite, mysql and others (not tested on
  mssql yet, but since mssql automatically reuses connections per dsn the old
  way could gurantee anything different from happening)

PgSQL driver:
- use track_errors to capture error messages in MDB2_driver_pgsql::connect().
  Bug 2011. (danielc)
- add port to connect string when protocol is unix in MDB2_driver_pgsql::connect().
  Bug 1919. (danielc)
- accommodate changes made to PostgreSQL so &quot;no such field&quot; errors get properly
  indicated rather than being mislabeled as &quot;no such table.&quot; (danielc)
- added &quot;permission denied&quot; to error regex in pgsql driver.
  Bug 2417. (stewart_linux-org-au)

OCI8 driver:
- fixed typo in MDB2_Driver_Manager_oci8::listTables() (fix for bug #2434)
- added emulate_database option (default true) to the Oracle driver that handles
  if the database_name should be used for connections of the username
- oci8 driver now uses native bind support for all types in prepare()/execute()

Interbase driver:
- completely revised ibase driver, now passing all tests under php5

Frontbase driver:
- fbsql: use correct error codes. Was using MySQL\'s codes by mistake.

MySQLi driver:
- added mysqli driver (passes all tests, but doesnt use native prepare yet)

DB wrapper
- fixed a large number of compatibility issues in the PEAR::DB wrapper

Iterator
- fixed several bugs and updated the interface to match the final php5 iterator API
- buffered result sets now implements seekable
- removed unnecessary returns
- throw pear error on rewind in unbuffered result set
- renamed size() to count() to match the upcoming Countable interface

Extended module:
- modified the signature of the auto*() methods to be compatible with DB (bug #3720)
- tweaked buildManipSQL() to not use loops (bug #3721)

MDB_Tools_Manager
- updated raiseError method in the Manager to be compatible with
  XML_Parser 1.1.x and return useful error message (fix bug #2055)
- major refactoring of MDB2_Manager resulting in several new methods being available
- fixed error in MDB2_Manager::_escapeSpecialCharacter() that would lead to
  incorrect handling of integer values (this needs to be explored in more detail)
- several typo fixes and minor logic errors (among others a fix for bug #2057)
- moved xml dumping in MDB2_Tools_Manager into separate Writer class
- fixed bugs in start value handling in create sequence (bug #3077)
</notes>
    </release>
    <release>
      <version>2.0.0beta2</version>
      <date>2004-04-25</date>
      <license>BSD License</license>
      <state>beta</state>
      <notes>The core of MDB2 is now fairly stable API-wise. The modules, especially the
manager and reverse module, might see some API refinement before the first
stable release.
- added listTables() and listTableFields() methods to MDB2_Driver_Manager_mssql
  and MDB2_Driver_Manager_oci8
- reversed parameter order of getValue(), type parameter is now optional and
  will then be autodetected (BC break!)
- renamed get*Value() to quote*() (BC break!)
- fixed LOB management in MDB2_Driver_ibase
- moved getOne, getRow, getCol, getAll back into the exteneded module (most
  users should be able to move to the queryOne, queryRow, queryCol and queryAll
  equivalent) (BC break!)
- added getAssoc to the extended module
- fixed bug in MDB2_Driver_Datatype_Common::implodeArray()
- added sequence_col_name option to make the column name inside sequence
  emulation tables configurable
- fixed a bug in the MDB2_Driver_oci8 and MDB2_Driver_ibase buffering emulation
  when using limit queries
- removed MDB2_PORTABILITY_NULL_TO_EMPTY in favor of MDB2_PORTABILITY_EMPTY_TO_NULL
  this means that DB and MDB2 work exactly the opposite now, but it seems more
  efficient to do things the way Oracle does since this is the RDBMS which
  creates the original issue to begin with (BC break!)
- fixed a typos in getAll, getAssoc and getCol
- test suite: moved set_time_limit() call to the setup script to be easier to customize
- renamed hasMore() to valid() due to changes in the PHP5 iterator API (BC break!)
- renamed toString() to __toString() in order to take advantage of new PHP5
  goodness and made it public
- MDB2_Driver_Datatype_Common::setResultTypes() can now handle missing elements
  inside type arrays: array(2 =&gt; \'boolean\', 4 =&gt; \'timestamp\')
- fixed potential warning due to manipulation query detection in the query*()
  and the get*() query+fetch methods
- added tests for fetchAll() and fetchCol()
- performance tweaks for fetchAll() and fetchCol()
- fixed MDB2_Driver_Manager_mysql::listTableIndexes()
- fixed MDB2_Driver_Common::debug()
- renamed MDB2::isResult() to MDB2::isResultCommon()
- added base result class MDB2_Result from which all result sets should be
  inherited and added MDB2::isResult() which checks if a given object extends from it
- added \'result_wrap_class\' option and optional parameter to query() to enable
  wrapping of result classes into an arbitrary class
- added $result_class param to all drivers where it was missing from the
  query() and _executePrepared() methods
- applied several fixes to the PEAR::DB wrapper
- fixed a typo in MDB2_Driver_Reverse_pgsql::tableInfo()
</notes>
    </release>
    <release>
      <version>2.0.0beta1</version>
      <date>2004-03-12</date>
      <license>BSD License</license>
      <state>alpha</state>
      <notes>- fixed bug in MDB2::singleton
- fixed minor bugs in prepare/execute
- added PEAR::DB wrapper (not working yet)
- fixed several bugs in the ibase driver
- fixed several PHP5 related issues
- fixed bug in sequence creation on MySQL
- fixed issues with nextid() ondemand handling in conjunction with currId()
- added native currId() implementation for the Oracle driver
- fixed sqlite driver (passes all but the REPLACE test due to a conformance issue in sqlite itself)
- removed decimal_factor property to allow changing of decimal_places option
- using native escape string methods in sqlite and mysql driver
- fixed minor conformance issues in tableInfo() in the oci8 and mysql driver
- removed optimize option and added portability option instead (ported from DB)
- added quoteIdentifier() method (ported from DB)
- added STATUS document to make the status of the drivers more transparent
- fixed a few bugs in querysim driver
- fixed issue in mysql reverse engineering: ensuring the correct case is used when
  doing assoc fetches based on portability flag setting
- updated reverse engineering script to the new MDB2 API
- removed broken implementations of currId() in the mssql and fbsql driver
- fixed a few instances of MDB_Common to the new class name of MDB_Driver_Common
</notes>
    </release>
    <release>
      <version>2.0.0alpha1</version>
      <date>2004-01-05</date>
      <license>BSD License</license>
      <state>alpha</state>
      <notes>This is the first alpha release of MDB2 2.0.

MDB2 2.x breaks backwards compatibility in many ways in order to simplify
the API for both users and drivers developers.

Please note that currently only the MySQL, the PostGreSQL and the Oracle driver
have been tested to pass the test suite.

Here follows a short list of the most important changes:
- all code that is not necessary for basic operation is now separateed
  into separate modules which can be loaded with the loadModule() method
- all datatype related methods have been moved to a dataype module with
  the notable exception of getValue() and the newly introduced getDeclaration()
- added extended module for highlevel methods
- all manager method are no longer available in the core class and or
  now only available in the manager module
- all reverse engineering methods have been taken from the manager class
  and are now available through the reverse module
- a new module has been added to allow the addition of methods with
  RDBMS specific functionality (like getting the last autoincrement ID)
- LOB handling has been greatly simplified
- several methods names have been shortend
- the fetch.+() methods do not free the result set anymore
- the Manager and the reverse_engineer_xml_schema have been moved into
  a Tools directory
- all parameters are now lowercased with underscores as separators
- all drivers now support all of the dsn options that PEAR DB supports
- several methods have been removed because they offered redundant functionality
- changed prepare API type is now passed to prepare and not to setParam*()
- results are now wrapped inside objects and all methods which operate
  on resultsets have been moved into respecitive classes
- there are two types of result object: buffered (default) and unbuffered
- totally rewrote buffering and limit emulation
</notes>
    </release>
  </changelog>
</package>',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'optional' => 'yes',
    'name' => 'Auth_RADIUS',
    'channel' => 'pear.php.net',
    'package' => 'Auth_RADIUS',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '1.0.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Auth_RADIUS</name>
  <summary>Wrapper Classes for the RADIUS PECL.</summary>
  <description>This package provides wrapper-classes for the RADIUS PECL.
There are different Classes for the different authentication methods.
If you are using CHAP-MD5 or MS-CHAP you need also the Crypt_CHAP package.
If you are using MS-CHAP you need also the mhash and mcrypt extension.</description>
  <maintainers>
    <maintainer>
      <user>mbretter</user>
      <name>Michael Bretterklieber</name>
      <email>mbretter@php.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.0.4</version>
    <date>2004-03-30</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>* BugFix: wrong dependencies</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.2.4">radius</dep>
    </deps>
    <provides type="class" name="Auth_RADIUS" extends="PEAR" />
    <provides type="class" name="Auth_RADIUS_PAP" extends="Auth_RADIUS" />
    <provides type="class" name="Auth_RADIUS_CHAP_MD5" extends="Auth_RADIUS_PAP" />
    <provides type="class" name="Auth_RADIUS_MSCHAPv1" extends="Auth_RADIUS_CHAP_MD5" />
    <provides type="class" name="Auth_RADIUS_MSCHAPv2" extends="Auth_RADIUS_MSCHAPv1" />
    <provides type="class" name="Auth_RADIUS_Acct" extends="Auth_RADIUS" />
    <provides type="class" name="Auth_RADIUS_Acct_Start" extends="Auth_RADIUS_Acct" />
    <provides type="class" name="Auth_RADIUS_Acct_Stop" extends="Auth_RADIUS_Acct" />
    <provides type="class" name="Auth_RADIUS_Acct_Update" extends="Auth_RADIUS_Acct" />
    <provides type="function" name="Auth_RADIUS::addServer" />
    <provides type="function" name="Auth_RADIUS::getError" />
    <provides type="function" name="Auth_RADIUS::setConfigfile" />
    <provides type="function" name="Auth_RADIUS::putAttribute" />
    <provides type="function" name="Auth_RADIUS::putVendorAttribute" />
    <provides type="function" name="Auth_RADIUS::dumpAttributes" />
    <provides type="function" name="Auth_RADIUS::open" />
    <provides type="function" name="Auth_RADIUS::createRequest" />
    <provides type="function" name="Auth_RADIUS::putStandardAttributes" />
    <provides type="function" name="Auth_RADIUS::putAuthAttributes" />
    <provides type="function" name="Auth_RADIUS::putServer" />
    <provides type="function" name="Auth_RADIUS::putConfigfile" />
    <provides type="function" name="Auth_RADIUS::start" />
    <provides type="function" name="Auth_RADIUS::send" />
    <provides type="function" name="Auth_RADIUS::getAttributes" />
    <provides type="function" name="Auth_RADIUS::close" />
    <provides type="function" name="Auth_RADIUS_PAP::open" />
    <provides type="function" name="Auth_RADIUS_PAP::createRequest" />
    <provides type="function" name="Auth_RADIUS_PAP::putAuthAttributes" />
    <provides type="function" name="Auth_RADIUS_CHAP_MD5::putAuthAttributes" />
    <provides type="function" name="Auth_RADIUS_CHAP_MD5::close" />
    <provides type="function" name="Auth_RADIUS_MSCHAPv1::putAuthAttributes" />
    <provides type="function" name="Auth_RADIUS_MSCHAPv2::putAuthAttributes" />
    <provides type="function" name="Auth_RADIUS_MSCHAPv2::close" />
    <provides type="function" name="Auth_RADIUS_Acct::open" />
    <provides type="function" name="Auth_RADIUS_Acct::createRequest" />
    <provides type="function" name="Auth_RADIUS_Acct::putAuthAttributes" />
    <filelist>
      <file role="php" baseinstalldir="Auth" md5sum="3760816c7ff57759b5a2bd7b20580340" name="RADIUS.php"/>
      <file role="doc" baseinstalldir="Auth" md5sum="9ecb8e207de80611ea554b9fe0b755df" name="examples/radius-acct.php"/>
      <file role="doc" baseinstalldir="Auth" md5sum="20862f2aec63044bed1a46149bbf2661" name="examples/radius-auth.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0.4</version>
      <date>2004-03-30</date>
      <state>stable</state>
      <notes>* BugFix: wrong dependencies

      
</notes>
    </release>
    <release>
      <version>1.0.3</version>
      <date>2004-03-25</date>
      <state>stable</state>
      <notes>* Changed the examples to for version 1.0.0 of Crypt_CHAP
* Added a new class for sending interim accounting updates

      
</notes>
    </release>
    <release>
      <version>1.0.2</version>
      <date>2003-07-17</date>
      <state>stable</state>
      <notes>* BugFix: wrong status_type in Auth_RADIUS_Acct_Start

      
</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2003-05-02</date>
      <state>stable</state>
      <notes>* Added support for LAN-Manager-Responses
* BugFix: The RADIUS_USER_NAME was sent twice
* Send also the RADIUS_NAS_PORT_TYPE

      
</notes>
    </release>
    <release>
      <version>1.0.0</version>
      <date>2003-02-01</date>
      <state>stable</state>
      <notes>* Fully functional and reasonably tested

      
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Auth_RADIUS-1.0.4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'optional' => 'yes',
    'name' => 'File_SMBPasswd',
    'channel' => 'pear.php.net',
    'package' => 'File_SMBPasswd',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth',
    'version' => '1.3.0r3',
  ),
  3 => 'stable',
), array (
  'version' => '1.0.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>File_SMBPasswd</name>
  <summary>Class for managing SAMBA style password files.</summary>
  <description>With this package, you can maintain smbpasswd-files, usualy used by SAMBA.</description>
  <maintainers>
    <maintainer>
      <user>mbretter</user>
      <name>Michael Bretterklieber</name>
      <email>mbretter@php.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.0.1</version>
    <date>2004-09-14</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>* Wrong order of NT-Hash/LM-Hash when using addAccount()/modAccount()</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0.0">Crypt_CHAP</dep>
      <dep type="ext" rel="has">mhash</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="File" name="SMBPasswd.php"/>
      <file role="doc" baseinstalldir="File" name="examples/smbpasswd.php"/>
      <file role="doc" baseinstalldir="File" name="examples/smbpasswd"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0.1</version>
      <date>2004-09-14</date>
      <state>stable</state>
      <notes>* Wrong order of NT-Hash/LM-Hash when using addAccount()/modAccount()

      
</notes>
    </release>
    <release>
      <version>1.0.0</version>
      <date>2004-03-25</date>
      <state>stable</state>
      <notes>* Stable version released

      
</notes>
    </release>
    <release>
      <version>0.9.0</version>
      <date>2003-05-02</date>
      <state>beta</state>
      <notes>* Inital release

      
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/File_SMBPasswd-1.0.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'Auth_HTTP',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
  2 => '2.1.4',
), array (
  'version' => '2.1.6RC1',
  'info' => '<?xml version="1.0"?>
<package packagerversion="1.4.0a9" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
 <name>Auth_HTTP</name>
 <channel>pear.php.net</channel>
 <summary>HTTP authentication</summary>
 <description>The PEAR::Auth_HTTP class provides methods for creating an HTTP
authentication system using PHP, that is similar to Apache&apos;s
realm-based .htaccess authentication.</description>
 <lead>
  <name>David Costa</name>
  <user>gurugeek</user>
  <email>gurugeek@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Rui Hirokawa</name>
  <user>hirokawa</user>
  <email>hirokawa@php.net</email>
  <active>yes</active>
 </lead>
 <date>2005-04-05</date>
 <time>06:09:20</time>
 <version>
  <release>2.1.6RC1</release>
  <api>2.1.6RC1</api>
 </version>
 <stability>
  <release>beta</release>
  <api>beta</api>
 </stability>
 <license uri="http://www.php.net/license">PHP License</license>
 <notes>- Fixed bug #4047.
     - Fixed backward compatibility with PHP 4.x
     - Added PHP_AUTH_DIGEST support.</notes>
 <contents>
  <dir name="/">
   <file baseinstalldir="Auth/HTTP" md5sum="9b7fe356f6793ccab49df1e3e39e2c6e" name="tests/sample.sql" role="test" />
   <file baseinstalldir="Auth/HTTP" md5sum="4fb0adc407d3382f1dd479dbfca47087" name="tests/test_basic_simple.php" role="test" />
   <file baseinstalldir="Auth/HTTP" md5sum="087081d0d90e01d115fa6a4e3105fdcf" name="tests/test_digest_get.php" role="test" />
   <file baseinstalldir="Auth/HTTP" md5sum="ecdf2118cb798c8d1db302e424b854f6" name="tests/test_digest_post.php" role="test" />
   <file baseinstalldir="Auth/HTTP" md5sum="94f05d563bd2aa19ffe053825aa8e75a" name="tests/test_digest_simple.php" role="test" />
   <file baseinstalldir="Auth" md5sum="bac8e2f23a41b19d89dfb1246ed37dd6" name="Auth_HTTP.php" role="php" />
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.1.0</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a2</min>
   </pearinstaller>
   <package>
    <name>Auth</name>
    <channel>pear.php.net</channel>
    <min>1.2.0</min>
   </package>
  </required>
 </dependencies>
 <phprelease>
  <installconditions>
   <os>
    <name>*</name>
   </os>
  </installconditions>
  <filelist>
   <install as="HTTP.php" name="Auth_HTTP.php" />
  </filelist>
 </phprelease>
 <changelog>
  <release>
   <version>
    <release>2.1.5</release>
    <api>2.1.5</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2005-04-02</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>- Fixed a bug #3630 getAuthData failes due to session rename.</notes>
  </release>
  <release>
   <version>
    <release>2.1.4</release>
    <api>2.1.4</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2005-01-02</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>- Fixed a bug #2380: constructor couldn&apos;t handle non-array option.
     - The first stable release with HTTP Digest Authenthication support.</notes>
  </release>
  <release>
   <version>
    <release>2.1.3rc1</release>
    <api>2.1.3rc1</api>
   </version>
   <stability>
    <release>beta</release>
    <api>beta</api>
   </stability>
   <date>2004-08-22</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>- Fixed a bug #2061 (importglobalvariable() was removed in Auth 1.3.0r2.)
- now Auth_HTTP requires Auth &gt;= 1.3.0r2.</notes>
  </release>
  <release>
   <version>
    <release>2.1.1</release>
    <api>2.1.1</api>
   </version>
   <stability>
    <release>beta</release>
    <api>beta</api>
   </stability>
   <date>2004-07-12</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>- Fixed a bug #1634 (URI parameter was handled incorrectry.)</notes>
  </release>
  <release>
   <version>
    <release>2.1.0</release>
    <api>2.1.0</api>
   </version>
   <stability>
    <release>beta</release>
    <api>beta</api>
   </stability>
   <date>2004-05-30</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>- Added _sessionName which fixes a major issue with realm sharing.
- Added sessionSharing option to use unique session id.
  Currently, this option is set to true by default to maintain
  backward compatibility.
- Added setOption and getOption to set/get option value.
- Starting with this release, HTTP Digest Authentication (RFC2617) is
  experimentally supported. The code for HTTP Digest Authentication is 
  originally developed by Tom Pike.</notes>
  </release>
  <release>
   <version>
    <release>2.0</release>
    <api>2.0</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2003-10-16</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>Starting with this release, the code will not be placed in
Auth_HTTP/Auth_HTTP.php anymore. Instead Auth/HTTP.php is used, which
conforms to the PEAR standards.
In order to make use of the new version, you will need to change your
scripts to include the file at the new location! The old version in
Auth_HTTP/Auth_HTTP.php will not be removed when upgrading.
Other changes:
 authentication credentials. (Patch by: Marko Karppinen)</notes>
  </release>
  <release>
   <version>
    <release>1.0</release>
    <api>1.0</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2001-08-23</date>
   <license uri="http://www.php.net/license">PHP License</license>
   <notes>This is the initial independent release of the Auth_HTTP package.</notes>
  </release>
 </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Auth_HTTP-2.1.6RC1',
));
$pearweb->addHtmlConfig('http://pear.php.net/get/Auth_HTTP-2.1.6RC1.tgz', $p3);
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '2.0',
  1 => 
  array (
    'name' => 'Auth',
    'channel' => 'pear.php.net',
    'min' => '1.2.0',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'Auth_HTTP',
    'version' => '2.1.6RC1',
  ),
  3 => 'alpha',
  4 => '1.3.0r3',
), array (
  'version' => '1.3.0r3',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Auth</name>
  <summary>Creating an authentication system.</summary>
  <description>The PEAR::Auth package provides methods for creating an authentication
system using PHP.

Currently it supports the following storage containers to read/write
the login data:

* All databases supported by the PEAR database layer
* All databases supported by the MDB database layer
* All databases supported by the MDB2 database layer
* Plaintext files
* LDAP servers
* POP3 servers
* IMAP servers
* vpopmail accounts
* RADIUS
* SAMBA password files
* SOAP</description>
  <maintainers>
    <maintainer>
      <user>MJ</user>
      <name>Martin Jansen</name>
      <email>mj@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>jflemer</user>
      <name>James E. Flemer</name>
      <email>jflemer@acm.jhu.edu</email>
      <role>developer</role>
    </maintainer>
    <maintainer>
      <user>yavo</user>
      <name>Yavor Shahpasov</name>
      <email>yavo@siava.org</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.3.0r3</version>
    <date>2004-08-07</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>* Moved login screen generation code to Auth/Frontend/Html.php 
  In the future the frontend will be configurable.
* Implemented support for Challenge / Responce password authenthication
  have to enable advanced security $auth-&gt;setAdvancedSecurity
  will work only with DB container and cryptType = none|md5
* Implemented setAllowLogin to control which pages are allowed to perform login, 
  to preservce BC. Previusly the showLogin flag was used to control this - yavo
* Implmented lazy loading for the storage constructor, constructor is only created when needed
  to make Auth more lightweight (this might be adding a bit more overhead to login and usermanagement functions)
* Removed include of PEAR, was not used anywhare in Auth.php
* Created a new storage container DBLite same as DB but with the user manipulation functions removed (50% smaller)
* Added a new method staticCheckAuth which can be called statically with only the auth options
* Auth::importGlobalVariable method was removed and replaced by references to global variables
* Removed all calls to $session[$this-&gt;_sessionName], made local reference session point to that instead
* Changed call_user_func to call_user_func_array for the callbacks, to avoid using @ for passing variables by reference
* Code Cleanup, removed most vi comments</notes>
    <deps>
      <dep type="pkg" rel="ge" version="0.9.5" optional="yes">File_Passwd</dep>
      <dep type="pkg" rel="ge" version="1.3" optional="yes">Net_POP3</dep>
      <dep type="pkg" rel="has" optional="yes">DB</dep>
      <dep type="pkg" rel="has" optional="yes">MDB</dep>
      <dep type="pkg" rel="has" optional="yes">MDB2</dep>
      <dep type="pkg" rel="has" optional="yes">Auth_RADIUS</dep>
      <dep type="pkg" rel="has" optional="yes">File_SMBPasswd</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="" name="Auth.php">
        <replace from="@version@" to="version" type="package-info"/>
      </file>
      <file role="php" baseinstalldir="" name="Auth' . DIRECTORY_SEPARATOR . 'Auth.php"/>
      <file role="php" baseinstalldir="" name="Auth' . DIRECTORY_SEPARATOR . 'Controller.php"/>
      <file role="php" baseinstalldir="Auth" name="Container.php"/>
      <file role="doc" baseinstalldir="Auth" name="README.Auth"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'DB.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'DBLite.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'File.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'IMAP.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'POP3.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'LDAP.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'MDB.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'MDB2.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'RADIUS.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'SMBPasswd.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'SOAP.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'vpopmail.php"/>
      <file role="php" baseinstalldir="Auth" name="Container' . DIRECTORY_SEPARATOR . 'PEAR.php"/>
      <file role="php" baseinstalldir="Auth" name="Frontend' . DIRECTORY_SEPARATOR . 'Html.php"/>
      <file role="php" baseinstalldir="Auth" name="Frontend' . DIRECTORY_SEPARATOR . 'md5.js"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'auth_container_db_options.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'DBContainer.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'auth_container_file_options.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'FileContainer.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'auth_container_pop3_options.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'auth_container_mdb_options.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'MDBContainer.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'auth_container_mdb2_options.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'MDB2Container.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'POP3Container.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'auth_container_pop3a_options.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'POP3aContainer.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'TestAuthContainer.php"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'users"/>
      <file role="test" baseinstalldir="Auth" name="tests' . DIRECTORY_SEPARATOR . 'tests.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.3.0r1</version>
      <date>2004-06-04</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>* Changes to LDAP container:
  - check for loaded ldap extension at startup as suggested by Markku Turunen
  - make ldap version configurable via config array
  - documentation fix for active directory default user container
  [ 14/Jun/2004 - jw]
* Added an Auth_Controller class, to manage automatic redirection to login page and redirect back
  to the calling page [04/06/2004 - Yavo]
* Changes to LDAP container:
  - additional attribute fetching to authData via new option attributes
  - utf8 encoding username for ldapv3 (fixes german umlaut problem)
  - make scope definable for user and group searching seperately
  - remove useroc, groupoc and replace them with userfilter, groupfilter which is way more flexible
  - updated documentation on all new and changed parameters
  As some of the parameters changed this one is not backwards compatible to earlier versions.
  Look at the top of the class where all parameters are explained in detail.
  [08/April/2004 - jw]
* Added new MDB2 container  [30/March/2004 - quipo]
* Implements changePassword and CS fixed, patch from Cipriano Groenendal &lt;cipri@cipri.com&gt;
  [29/March/2004 - yavo]
* Added options for changing the post variables, patch supplied by Moritz Heidkamp &lt;moritz.heidkamp@invision-team.de&gt;
  [03/March/2004 - yavo]
* Added method setAdvancedSecurity and set advanced security to off by default, if turned on auth will perform additional
  security checks if ip or user agent has changed across requests
* Login is now performed only if showLogin is true, do not allow for logins to be performed from any page which calls auth-&gt;start
  spotted by Matt Eaton &lt;pear@divinehawk.com&gt; [16/Jan/2004 - yavo] 
* Fixed bug noted by Jeroen Houben &lt;jeroen@terena.nl&gt;, calling loginFailedCallback
  would not have the proper status set [16/Jan/2004 - yavo]
* Added PEAR container, authenticate the user against the pear web site
  (probably php.net also) [16/Dec/2003 - yavo]

</notes>
    </release>
    <release>
      <version>1.2.3</version>
      <date>2003-09-08</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>* new Method to auth_container getUser()
* New Auth_Container_File, using new File_Passwd class. Provided by Michael Wallner &lt;mike@php.net&gt;
* Login/Logout callbacks now get a reference to auth
* New Login Failed Callback added (method setFailedLoginCallback)
* SOAP container patch to keep a reference to the Soap responce by Bruno Pedro &lt;bpedro@co.sapo.pt&gt;
* Auth is now installed in /pear-dir/Auth.php instead of /pear-dir/Auth/Auth.php, an
  empty file /pear-dev/Auth/Auth.php wich includes Auth.php is added for BC
* The contaner now gets a reference to the auth object ($auth-&gt;storage-&gt;_auth_obj)
*Some patches from the pear-dev list bellow
    -maka3d@yahoo.com.br - Patch to use a method of the container in Auth_Container::verifyPassword
    -Lorenzo Alberton &lt;l.alberton@quipo.it&gt; - Patch to use variable session variable name, untill now the variable auth was used
    -Marcos Neves &lt;maka3d@yahoo.com.br&gt; - Avaoid error when calling getAuthData() before the login

</notes>
    </release>
    <release>
      <version>1.2.2</version>
      <date>2003-07-29</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>* Added support for passing contaner as an object
* Added fix when db_fileds is *
* Added Test Suite (experimental)
* Added generic support for arbitrary password crypting functions
  different than MD5, DES and plain text. (Patch by Tom Anderson)
* Added new MDB storage container written by Lorenzo Alberton
* Added new Container for SAMBA password files (SMBPasswd)

</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Auth-1.3.0r3',
));
$res = $command->run('install', array(), array($p1, $p2));
$phpunit->assertNoErrors('setup install');
$fakelog->getDownload();
$fakelog->getLog();
$config->set('preferred_state', 'alpha');
test_PEAR_Command_Install::_reset_downloader();
$res = $command->run('upgrade', array(), array('Auth_HTTP'));

$dl = &$command->getDownloader(1, array());
if (OS_WINDOWS) {
    $nicedldir = str_replace('\\\\', '\\', $dl->getDownloadDir());
    $phpunit->assertEquals(array (
      0 =>
      array (
        0 => 3,
        1 => 'pear/Auth_HTTP: Skipping required dependency "pear/Auth" version 1.3.0r3, already installed as version 1.3.0r3',
      ),
      1 => 
      array (
        0 => 1,
        1 => 'downloading Auth_HTTP-2.1.6RC1.tgz ...',
      ),
      2 => 
      array (
        0 => 1,
        1 => 'Starting to download Auth_HTTP-2.1.6RC1.tgz (9,294 bytes)',
      ),
      3 => 
      array (
        0 => 1,
        1 => '.',
      ),
      4 => 
      array (
        0 => 1,
        1 => '.',
      ),
      5 => 
      array (
        0 => 1,
        1 => '...done: 9,294 bytes',
      ),
      6 => 
      array (
        0 => 3,
        1 => 'adding to transaction: backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php',
      ),
      7 => 
      array (
        0 => 3,
        1 => 'adding to transaction: delete ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php',
      ),
      8 => 
      array (
        0 => 3,
        1 => 'adding to transaction: backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql',
      ),
      9 => 
      array (
        0 => 3,
        1 => 'adding to transaction: delete ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql',
      ),
      10 => 
      array (
        0 => 3,
        1 => 'adding to transaction: backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php',
      ),
      11 => 
      array (
        0 => 3,
        1 => 'adding to transaction: delete ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php',
      ),
      12 => 
      array (
        0 => 3,
        1 => 'adding to transaction: backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php',
      ),
      13 => 
      array (
        0 => 3,
        1 => 'adding to transaction: delete ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php',
      ),
      14 => 
      array (
        0 => 3,
        1 => 'adding to transaction: backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php',
      ),
      15 => 
      array (
        0 => 3,
        1 => 'adding to transaction: delete ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php',
      ),
      16 => 
      array (
        0 => 3,
        1 => 'adding to transaction: backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php',
      ),
      17 => 
      array (
        0 => 3,
        1 => 'adding to transaction: delete ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php',
      ),
      18 => 
      array (
        0 => 3,
        1 => '+ cp ' . $nicedldir . DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmpsample.sql',
      ),
      19 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql',
      ),
      20 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmpsample.sql ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql ',
      ),
      21 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as tests/sample.sql ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP ' . DIRECTORY_SEPARATOR . 'tests',
      ),
      22 => 
      array (
        0 => 3,
        1 => '+ cp ' . $nicedldir . DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_basic_simple.php',
      ),
      23 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php',
      ),
      24 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_basic_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php ',
      ),
      25 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as tests/test_basic_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP ' . DIRECTORY_SEPARATOR . 'tests',
      ),
      26 => 
      array (
        0 => 3,
        1 => '+ cp ' . $nicedldir . DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_get.php',
      ),
      27 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php',
      ),
      28 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_get.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php ',
      ),
      29 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as tests/test_digest_get.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP ' . DIRECTORY_SEPARATOR . 'tests',
      ),
      30 => 
      array (
        0 => 3,
        1 => '+ cp ' . $nicedldir . DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_post.php',
      ),
      31 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php',
      ),
      32 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_post.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php ',
      ),
      33 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as tests/test_digest_post.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP ' . DIRECTORY_SEPARATOR . 'tests',
      ),
      34 => 
      array (
        0 => 3,
        1 => '+ cp ' . $nicedldir . DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_simple.php',
      ),
      35 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php',
      ),
      36 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php ',
      ),
      37 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as tests/test_digest_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP ' . DIRECTORY_SEPARATOR . 'tests',
      ),
      38 => 
      array (
        0 => 3,
        1 => '+ cp ' . $nicedldir . DIRECTORY_SEPARATOR . 'Auth_HTTP-2.1.6RC1' . DIRECTORY_SEPARATOR . 'Auth_HTTP.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . '.tmpHTTP.php',
      ),
      39 => 
      array (
        0 => 2,
        1 => 'md5sum ok: ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php',
      ),
      40 => 
      array (
        0 => 3,
        1 => 'adding to transaction: rename ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . '.tmpHTTP.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php ',
      ),
      41 => 
      array (
        0 => 3,
        1 => 'adding to transaction: installed_as Auth_HTTP.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php ' . DIRECTORY_SEPARATOR . 'Auth',
      ),
      42 => 
      array (
        0 => 3,
        1 => 'adding to transaction: removebackup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php',
      ),
      43 => 
      array (
        0 => 3,
        1 => 'adding to transaction: removebackup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql',
      ),
      44 => 
      array (
        0 => 3,
        1 => 'adding to transaction: removebackup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php',
      ),
      45 => 
      array (
        0 => 3,
        1 => 'adding to transaction: removebackup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php',
      ),
      46 => 
      array (
        0 => 3,
        1 => 'adding to transaction: removebackup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php',
      ),
      47 => 
      array (
        0 => 3,
        1 => 'adding to transaction: removebackup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php',
      ),
      48 => 
      array (
        0 => 2,
        1 => 'about to commit 30 file operations',
      ),
      49 => 
      array (
        0 => 3,
        1 => '+ backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php to ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php.bak',
      ),
      50 => 
      array (
        0 => 3,
        1 => '+ rm ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php',
      ),
      51 => 
      array (
        0 => 3,
        1 => '+ backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql to ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql.bak',
      ),
      52 => 
      array (
        0 => 3,
        1 => '+ rm ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql',
      ),
      53 => 
      array (
        0 => 3,
        1 => '+ backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php to ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php.bak',
      ),
      54 => 
      array (
        0 => 3,
        1 => '+ rm ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php',
      ),
      55 => 
      array (
        0 => 3,
        1 => '+ backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php to ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php.bak',
      ),
      56 => 
      array (
        0 => 3,
        1 => '+ rm ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php',
      ),
      57 => 
      array (
        0 => 3,
        1 => '+ backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php to ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php.bak',
      ),
      58 => 
      array (
        0 => 3,
        1 => '+ rm ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php',
      ),
      59 => 
      array (
        0 => 3,
        1 => '+ backup ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php to ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php.bak',
      ),
      60 => 
      array (
        0 => 3,
        1 => '+ rm ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php',
      ),
      61 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmpsample.sql ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql',
      ),
      62 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_basic_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php',
      ),
      63 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_get.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php',
      ),
      64 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_post.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php',
      ),
      65 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . '.tmptest_digest_simple.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php',
      ),
      66 => 
      array (
        0 => 3,
        1 => '+ mv ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . '.tmpHTTP.php ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php',
      ),
      67 => 
      array (
        0 => 3,
        1 => '+ rm backup of ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php (' . $temp_path . '' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'HTTP.php.bak)',
      ),
      68 => 
      array (
        0 => 3,
        1 => '+ rm backup of ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql (' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'sample.sql.bak)',
      ),
      69 => 
      array (
        0 => 3,
        1 => '+ rm backup of ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php (' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_basic_simple.php.bak)',
      ),
      70 => 
      array (
        0 => 3,
        1 => '+ rm backup of ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php (' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_simple.php.bak)',
      ),
      71 => 
      array (
        0 => 3,
        1 => '+ rm backup of ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php (' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_get.php.bak)',
      ),
      72 => 
      array (
        0 => 3,
        1 => '+ rm backup of ' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php (' . $temp_path . '' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Auth_HTTP' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'test_digest_post.php.bak)',
      ),
      73 => 
      array (
        0 => 2,
        1 => 'successfully committed 30 file operations',
      ),
      74 => 
      array (
        'info' => 
        array (
          'data' => 'upgrade ok: channel://pear.php.net/Auth_HTTP-2.1.6RC1',
        ),
        'cmd' => 'upgrade',
      ),
    ), $fakelog->getLog(), 'log messages');
} else {
    $phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => 'pear/Auth_HTTP: Skipping required dependency "pear/Auth" version 1.3.0r3, already installed as version 1.3.0r3',
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading Auth_HTTP-2.1.6RC1.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download Auth_HTTP-2.1.6RC1.tgz (9,294 bytes)',
  ),
  3 => 
  array (
    0 => 1,
    1 => '.',
  ),
  4 => 
  array (
    0 => 1,
    1 => '.',
  ),
  5 => 
  array (
    0 => 1,
    1 => '...done: 9,294 bytes',
  ),
  6 => 
  array (
    0 => 3,
    1 => 'adding to transaction: backup ' . $temp_path . '/php/Auth/HTTP.php',
  ),
  7 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . '/php/Auth/HTTP.php',
  ),
  8 => 
  array (
    0 => 3,
    1 => 'adding to transaction: backup ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql',
  ),
  9 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql',
  ),
  10 => 
  array (
    0 => 3,
    1 => 'adding to transaction: backup ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php',
  ),
  11 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php',
  ),
  12 => 
  array (
    0 => 3,
    1 => 'adding to transaction: backup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php',
  ),
  13 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php',
  ),
  14 => 
  array (
    0 => 3,
    1 => 'adding to transaction: backup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php',
  ),
  15 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php',
  ),
  16 => 
  array (
    0 => 3,
    1 => 'adding to transaction: backup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php',
  ),
  17 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php',
  ),
  18 => 
  array (
    0 => 3,
    1 => '+ cp ' . $dl->getDownloadDir() . '/Auth_HTTP-2.1.6RC1/tests/sample.sql ' . $temp_path . '/test/Auth_HTTP/tests/.tmpsample.sql',
  ),
  19 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql',
  ),
  20 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmpsample.sql',
  ),
  21 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/test/Auth_HTTP/tests/.tmpsample.sql ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql ',
  ),
  22 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as tests/sample.sql ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql ' . $temp_path . '/test/Auth_HTTP /tests',
  ),
  23 => 
  array (
    0 => 3,
    1 => '+ cp ' . $dl->getDownloadDir() . '/Auth_HTTP-2.1.6RC1/tests/test_basic_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_basic_simple.php',
  ),
  24 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php',
  ),
  25 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_basic_simple.php',
  ),
  26 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_basic_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php ',
  ),
  27 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as tests/test_basic_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php ' . $temp_path . '/test/Auth_HTTP /tests',
  ),
  28 => 
  array (
    0 => 3,
    1 => '+ cp ' . $dl->getDownloadDir() . '/Auth_HTTP-2.1.6RC1/tests/test_digest_get.php ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_get.php',
  ),
  29 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php',
  ),
  30 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_get.php',
  ),
  31 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_get.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php ',
  ),
  32 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as tests/test_digest_get.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php ' . $temp_path . '/test/Auth_HTTP /tests',
  ),
  33 => 
  array (
    0 => 3,
    1 => '+ cp ' . $dl->getDownloadDir() . '/Auth_HTTP-2.1.6RC1/tests/test_digest_post.php ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_post.php',
  ),
  34 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php',
  ),
  35 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_post.php',
  ),
  36 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_post.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php ',
  ),
  37 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as tests/test_digest_post.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php ' . $temp_path . '/test/Auth_HTTP /tests',
  ),
  38 => 
  array (
    0 => 3,
    1 => '+ cp ' . $dl->getDownloadDir() . '/Auth_HTTP-2.1.6RC1/tests/test_digest_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_simple.php',
  ),
  39 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php',
  ),
  40 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_simple.php',
  ),
  41 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php ',
  ),
  42 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as tests/test_digest_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php ' . $temp_path . '/test/Auth_HTTP /tests',
  ),
  43 => 
  array (
    0 => 3,
    1 => '+ cp ' . $dl->getDownloadDir() . '/Auth_HTTP-2.1.6RC1/Auth_HTTP.php ' . $temp_path . '/php/Auth/.tmpHTTP.php',
  ),
  44 => 
  array (
    0 => 2,
    1 => 'md5sum ok: ' . $temp_path . '/php/Auth/HTTP.php',
  ),
  45 => 
  array (
    0 => 3,
    1 => 'adding to transaction: chmod 644 ' . $temp_path . '/php/Auth/.tmpHTTP.php',
  ),
  46 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rename ' . $temp_path . '/php/Auth/.tmpHTTP.php ' . $temp_path . '/php/Auth/HTTP.php ',
  ),
  47 => 
  array (
    0 => 3,
    1 => 'adding to transaction: installed_as Auth_HTTP.php ' . $temp_path . '/php/Auth/HTTP.php ' . $temp_path . '/php /Auth',
  ),
  48 => 
  array (
    0 => 3,
    1 => 'adding to transaction: removebackup ' . $temp_path . '/php/Auth/HTTP.php',
  ),
  49 => 
  array (
    0 => 3,
    1 => 'adding to transaction: removebackup ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql',
  ),
  50 => 
  array (
    0 => 3,
    1 => 'adding to transaction: removebackup ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php',
  ),
  51 => 
  array (
    0 => 3,
    1 => 'adding to transaction: removebackup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php',
  ),
  52 => 
  array (
    0 => 3,
    1 => 'adding to transaction: removebackup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php',
  ),
  53 => 
  array (
    0 => 3,
    1 => 'adding to transaction: removebackup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php',
  ),
  54 => 
  array (
    0 => 2,
    1 => 'about to commit 36 file operations',
  ),
  55 => 
  array (
    0 => 3,
    1 => '+ backup ' . $temp_path . '/php/Auth/HTTP.php to ' . $temp_path . '/php/Auth/HTTP.php.bak',
  ),
  56 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . '/php/Auth/HTTP.php',
  ),
  57 => 
  array (
    0 => 3,
    1 => '+ backup ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql to ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql.bak',
  ),
  58 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql',
  ),
  59 => 
  array (
    0 => 3,
    1 => '+ backup ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php to ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php.bak',
  ),
  60 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php',
  ),
  61 => 
  array (
    0 => 3,
    1 => '+ backup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php to ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php.bak',
  ),
  62 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php',
  ),
  63 => 
  array (
    0 => 3,
    1 => '+ backup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php to ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php.bak',
  ),
  64 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php',
  ),
  65 => 
  array (
    0 => 3,
    1 => '+ backup ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php to ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php.bak',
  ),
  66 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php',
  ),
  67 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmpsample.sql',
  ),
  68 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/test/Auth_HTTP/tests/.tmpsample.sql ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql',
  ),
  69 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_basic_simple.php',
  ),
  70 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_basic_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php',
  ),
  71 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_get.php',
  ),
  72 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_get.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php',
  ),
  73 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_post.php',
  ),
  74 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_post.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php',
  ),
  75 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_simple.php',
  ),
  76 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/test/Auth_HTTP/tests/.tmptest_digest_simple.php ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php',
  ),
  77 => 
  array (
    0 => 3,
    1 => '+ chmod 644 ' . $temp_path . '/php/Auth/.tmpHTTP.php',
  ),
  78 => 
  array (
    0 => 3,
    1 => '+ mv ' . $temp_path . '/php/Auth/.tmpHTTP.php ' . $temp_path . '/php/Auth/HTTP.php',
  ),
  79 => 
  array (
    0 => 3,
    1 => '+ rm backup of ' . $temp_path . '/php/Auth/HTTP.php (' . $temp_path . '/php/Auth/HTTP.php.bak)',
  ),
  80 => 
  array (
    0 => 3,
    1 => '+ rm backup of ' . $temp_path . '/test/Auth_HTTP/tests/sample.sql (' . $temp_path . '/test/Auth_HTTP/tests/sample.sql.bak)',
  ),
  81 => 
  array (
    0 => 3,
    1 => '+ rm backup of ' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php (' . $temp_path . '/test/Auth_HTTP/tests/test_basic_simple.php.bak)',
  ),
  82 => 
  array (
    0 => 3,
    1 => '+ rm backup of ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php (' . $temp_path . '/test/Auth_HTTP/tests/test_digest_simple.php.bak)',
  ),
  83 => 
  array (
    0 => 3,
    1 => '+ rm backup of ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php (' . $temp_path . '/test/Auth_HTTP/tests/test_digest_get.php.bak)',
  ),
  84 => 
  array (
    0 => 3,
    1 => '+ rm backup of ' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php (' . $temp_path . '/test/Auth_HTTP/tests/test_digest_post.php.bak)',
  ),
  85 => 
  array (
    0 => 2,
    1 => 'successfully committed 36 file operations',
  ),
  86 => 
  array (
    'info' => 
    array (
      'data' => 'upgrade ok: channel://pear.php.net/Auth_HTTP-2.1.6RC1',
    ),
    'cmd' => 'upgrade',
  ),
)
, $fakelog->getLog(), 'log messages');
}
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
