--TEST--
PEAR_Installer->install() upgrade a pecl package when it switches from a pear channel to a pecl channel
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$_test_dep->setPEARVersion('1.4.0a1');
$_test_dep->setPHPVersion('5.0.3');
$pathtopackagexml = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_upgrade_pecl'. DIRECTORY_SEPARATOR . 'package.xml';
$pathtopackagexml2 = dirname(__FILE__)  . DIRECTORY_SEPARATOR .
    'test_upgrade_pecl'. DIRECTORY_SEPARATOR . 'SQLite-1.0.4.tgz';
$pearweb->addHtmlConfig('http://pecl.php.net/get/SQLite-1.0.4.tgz', $pathtopackagexml2);
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 => 
  array (
    'package' => 'SQLite',
    'channel' => 'pear.php.net',
  ),
  1 => 'stable',
), array(
    'version' => '1.0.4',
    'info' =>
'<?xml version="1.0"?>
<package packagerversion="1.4.0a1" version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0 http://pear.php.net/dtd/tasks-1.0.xsd http://pear.php.net/dtd/package-2.0 http://pear.php.net/dtd/package-2.0.xsd">
 <name>SQLite</name>
 <channel>pecl.php.net</channel>
 <summary>SQLite database bindings</summary>
 <description>SQLite is a C library that implements an embeddable SQL database engine.
Programs that link with the SQLite library can have SQL database access
without running a separate RDBMS process.
This extension allows you to access SQLite databases from within PHP.
Windows binary for PHP 4.3 is available from:
http://snaps.php.net/win32/PECL_4_3/php_sqlite.dll
**Note that this extension is built into PHP 5 by default**</description>
 <lead>
  <name>Wez Furlong</name>
  <user>wez</user>
  <email>wez@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Marcus Brger</name>
  <user>helly</user>
  <email>helly@php.net</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Ilia Alshanetsky</name>
  <user>iliaa</user>
  <email>ilia@php.net</email>
  <active>yes</active>
 </lead>
 <developer>
  <name>Tal Peer</name>
  <user>tal</user>
  <email>tal@php.net</email>
  <active>yes</active>
 </developer>
 <date>2005-02-20</date>
 <time>21:53:33</time>
 <version>
  <release>1.0.4</release>
  <api>1.0.0</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.example.com">PHP</license>
 <notes>Upgraded libsqlite to version 2.8.14

&quot;Fixed&quot; the bug where calling sqlite_query() with multiple SQL statements in a
single string would not work if you looked at the return value.  The fix for
this case is to use the new sqlite_exec() function instead. (Stas)</notes>
 <contents>
  <dir name="/">
   <file md5sum="bc58dae3960f2cfa31c64430404186e4" name="libsqlite/src/attach.c" role="php" />
   <file md5sum="56fce422846bf7d325cd7831aaed0f15" name="libsqlite/src/auth.c" role="php" />
   <file md5sum="db1b579030b2bbf9628a6cf33bca1402" name="libsqlite/src/btree.c" role="php" />
   <file md5sum="91c007e86cdb43e6657bdb81f11378bf" name="libsqlite/src/btree.h" role="php" />
   <file md5sum="9e98104ce2747a6be5e660cd3ba750b6" name="libsqlite/src/btree_rb.c" role="php" />
   <file md5sum="b777a9857c69061de0d49542257e2395" name="libsqlite/src/build.c" role="php" />
   <file md5sum="9d9ce6145e2aa1425cb388da76051f21" name="libsqlite/src/copy.c" role="php" />
   <file md5sum="b659d6db865969fabfe4ba0a9251ab22" name="libsqlite/src/date.c" role="php" />
   <file md5sum="4df088de12002aaf05bc6513f73703aa" name="libsqlite/src/delete.c" role="php" />
   <file md5sum="41282ba2e068bfc4e78f5c67d250b7a0" name="libsqlite/src/encode.c" role="php" />
   <file md5sum="5eba3683d5fe5c7d6458531b102fa63e" name="libsqlite/src/expr.c" role="php" />
   <file md5sum="2a032e54acb956b73b5c2d50d7405fd3" name="libsqlite/src/func.c" role="php" />
   <file md5sum="110abcb2ce326a28a57c7e8c81d61c8f" name="libsqlite/src/hash.c" role="php" />
   <file md5sum="b6ecfc1a8f70bef9f52613c68c6276c0" name="libsqlite/src/hash.h" role="php" />
   <file md5sum="a091b02a24136bb481e9a66da779a725" name="libsqlite/src/insert.c" role="php" />
   <file md5sum="902e46aeb797ec517be21b8b08de8f12" name="libsqlite/src/main.c" role="php" />
   <file md5sum="a849a2863ebec7545c76948e62bf6119" name="libsqlite/src/opcodes.c" role="php" />
   <file md5sum="c247a4816b1a739b7773186c26757c30" name="libsqlite/src/opcodes.h" role="php" />
   <file md5sum="7bea3e024dd131211369de99b78b7e13" name="libsqlite/src/os.c" role="php" />
   <file md5sum="bf7f16dc5945babfc159d04ccbeec976" name="libsqlite/src/os.h" role="php" />
   <file md5sum="2802ca71885714800243358d026666e3" name="libsqlite/src/pager.c" role="php" />
   <file md5sum="2d9cd6746103555b083eea0889654f7a" name="libsqlite/src/pager.h" role="php" />
   <file md5sum="f274a379c3ff15f47b9ed3d631f05728" name="libsqlite/src/parse.c" role="php" />
   <file md5sum="68fff0c038befd4328bc5d92cd34f8d6" name="libsqlite/src/parse.h" role="php" />
   <file md5sum="bba0433a6ced73dae9de24a9c0ffde40" name="libsqlite/src/parse.y" role="php" />
   <file md5sum="d03a7a1cbe45d29ca1659c203e262d69" name="libsqlite/src/pragma.c" role="php" />
   <file md5sum="302390bcb86cf65c737f5c35aa9b580c" name="libsqlite/src/printf.c" role="php" />
   <file md5sum="ba8b8db090d971084913097d180e5025" name="libsqlite/src/random.c" role="php" />
   <file md5sum="c113e9c77d677cfe90867fa700f463af" name="libsqlite/src/select.c" role="php" />
   <file md5sum="362e47d3daf94e9bffe78a6ce96ef662" name="libsqlite/src/sqlite.h.in" role="php" />
   <file md5sum="70f583203ccbece34f5d10634f5aa52a" name="libsqlite/src/sqlite.w32.h" role="php" />
   <file md5sum="d33978270de3476bf9bb44cf26370618" name="libsqlite/src/sqliteInt.h" role="php" />
   <file md5sum="df68c0424f16a18a8ec01e59db4da4ae" name="libsqlite/src/sqlite_config.w32.h" role="php" />
   <file md5sum="a40502c12ce1056aa9e6f9d30bf5799d" name="libsqlite/src/table.c" role="php" />
   <file md5sum="b06ccf499431206ff2c4d33a0e477bf2" name="libsqlite/src/tokenize.c" role="php" />
   <file md5sum="b104dcda532f7bc4c4e44f4ba4517f17" name="libsqlite/src/trigger.c" role="php" />
   <file md5sum="18f1bf988408d63a3c38445b1e69b4f2" name="libsqlite/src/update.c" role="php" />
   <file md5sum="cd0f952ace74b3f38876b82466ca3af2" name="libsqlite/src/util.c" role="php" />
   <file md5sum="722b6c876ad160244b5a14ee4a016ff3" name="libsqlite/src/vacuum.c" role="php" />
   <file md5sum="385523c346c71afa05286f81e9b79d9e" name="libsqlite/src/vdbe.c" role="php" />
   <file md5sum="0087bec7602331da09f430fe16603236" name="libsqlite/src/vdbe.h" role="php" />
   <file md5sum="8ab3778796f7fc6ce85f1320679b9e70" name="libsqlite/src/vdbeaux.c" role="php" />
   <file md5sum="33578d1527d1122ca4226a915a62bb79" name="libsqlite/src/vdbeInt.h" role="php" />
   <file md5sum="0e70b33c50fd42307b8da6e46c869183" name="libsqlite/src/where.c" role="php" />
   <file md5sum="591dab7f485ebbc37096a2b7b3b6baed" name="libsqlite/README" role="doc" />
   <file md5sum="2fb3983d2effcfdf431df38f6d68bb2c" name="libsqlite/VERSION" role="php" />
   <file md5sum="4da0503f57e4d2b1b8cedd1250f64951" name="config.m4" role="php" />
   <file md5sum="1cfbecae6ae02dca06d445f519a65de3" name="CREDITS" role="doc" />
   <file md5sum="8c41b2ea0943232437d8c66b217d3851" name="php_sqlite.def" role="php" />
   <file md5sum="93e29944ad4eb86b9972ca80a8dc0280" name="php_sqlite.h" role="php" />
   <file md5sum="5019e66d3f4205c6ecdab15dcd5168e3" name="README" role="doc" />
   <file md5sum="17286433633c5a5332d78fb6bf544711" name="sqlite.c" role="php" />
   <file md5sum="56a6eb22bdf80dd2058dc92cdd0674f0" name="sqlite.dsp" role="php" />
   <file md5sum="a479923062a900808367a8de614ef447" name="sqlite.php" role="doc" />
   <file md5sum="76b92a00871ca9f7da9ab7891c5250ea" name="TODO" role="doc" />
  </dir>
 </contents>
 <dependencies>
  <required>
   <php>
    <min>5.0.3</min>
    <max>6.0.0</max>
   </php>
   <pearinstaller>
    <min>1.4.0a1</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
 <changelog>
  <release>
   <version>
    <release>1.0.2</release>
    <api>1.0.2</api>
   </version>
   <stability>
    <release>stable</release>
    <api>stable</api>
   </stability>
   <date>2004-01-17</date>
   <license uri="http://www.example.com">PHP</license>
   <notes>Upgraded libsqlite to version 2.8.11
Fixed crash bug in module shutdown
Fixed crash with empty queries
Fixed column name mangling bug</notes>
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
   <date>2003-06-21</date>
   <license uri="http://www.example.com">PHP</license>
   <notes>Added:
	sqlite_udf_encode_binary() and sqlite_udf_decode_binary() for
	handling binary data in UDF callbacks.
	sqlite_popen() for persistent db connections.
	sqlite_unbuffered_query() for high performance queries.
	sqlite_last_error() returns error code from last operation.
	sqlite_error_string() returns description of error.
	sqlite_create_aggregate() for registering aggregating SQL functions.
	sqlite_create_function() for registering regular SQL functions.
	sqlite_fetch_string() for speedy access to first column of result sets.
	sqlite_fetch_all() to receive all rowsets from a query as an array.
	iteration interface
	sqlite_query() functions accept resource/query string in either order,
	for compatibility with mysql and postgresql extensions.
Fixed some build issues for thread-safe builds.
Increase the default busy timeout interval to 60 seconds.</notes>
  </release>
 </changelog>
</package>',
'url' => 'http://pecl.php.net/get/SQLite-1.0.4'));
$dp = &new test_PEAR_Downloader($fakelog, array('upgrade' => true), $config);
$result = $dp->download(array($pathtopackagexml));
$installer = &new test_PEAR_Installer($ui);
$installer->setOptions(array());
$installer->sortPackagesForInstall($result);
$installer->setDownloadedPackages($result);
$installer->install($result[0]);
$phpunit->assertNoErrors('setup for upgrade');
$fakelog->getLog();
$fakelog->getDownload();
$phpunit->assertEquals(array('sqlite'), $reg->listPackages(), 'pear');
$phpunit->assertEquals(array(), $reg->listPackages('pecl'), 'pecl');
$dp = &new test_PEAR_Downloader($fakelog, array('upgrade' => true), $config);
$phpunit->assertNoErrors('after create');
$result = $dp->download(array('SQLite'));
$phpunit->assertEquals(1, count($result), 'return');
$phpunit->assertIsa('test_PEAR_Downloader_Package', $result[0], 'right class');
$phpunit->assertIsa('PEAR_PackageFile_v2', $pf = $result[0]->getPackageFile(), 'right kind of pf');
$phpunit->assertEquals('SQLite', $pf->getPackage(), 'right package');
$phpunit->assertEquals('pecl.php.net', $pf->getChannel(), 'right channel');
$dlpackages = $dp->getDownloadedPackages();
$phpunit->assertEquals(1, count($dlpackages), 'downloaded packages count');
$phpunit->assertEquals(3, count($dlpackages[0]), 'internals package count');
$phpunit->assertEquals(array('file', 'info', 'pkg'), array_keys($dlpackages[0]), 'indexes');
$phpunit->assertIsa('PEAR_PackageFile_v2',
    $dlpackages[0]['info'], 'info');
$phpunit->assertEquals('SQLite',
    $dlpackages[0]['pkg'], 'SQLite');
$after = $dp->getDownloadedPackages();
$phpunit->assertEquals(0, count($after), 'after getdp count');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => '+ tmp dir created at ' . $dp->getDownloadDir(),
  ),
  1 => 
  array (
    0 => 1,
    1 => 'downloading SQLite-1.0.4.tgz ...',
  ),
  2 => 
  array (
    0 => 1,
    1 => 'Starting to download SQLite-1.0.4.tgz (371,000 bytes)',
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
    1 => '.',
  ),
  6 => 
  array (
    0 => 1,
    1 => '.',
  ),
  7 => 
  array (
    0 => 1,
    1 => '.',
  ),
  8 => 
  array (
    0 => 1,
    1 => '.',
  ),
  9 => 
  array (
    0 => 1,
    1 => '.',
  ),
  10 => 
  array (
    0 => 1,
    1 => '.',
  ),
  11 => 
  array (
    0 => 1,
    1 => '.',
  ),
  12 => 
  array (
    0 => 1,
    1 => '.',
  ),
  13 => 
  array (
    0 => 1,
    1 => '.',
  ),
  14 => 
  array (
    0 => 1,
    1 => '.',
  ),
  15 => 
  array (
    0 => 1,
    1 => '.',
  ),
  16 => 
  array (
    0 => 1,
    1 => '.',
  ),
  17 => 
  array (
    0 => 1,
    1 => '.',
  ),
  18 => 
  array (
    0 => 1,
    1 => '.',
  ),
  19 => 
  array (
    0 => 1,
    1 => '.',
  ),
  20 => 
  array (
    0 => 1,
    1 => '.',
  ),
  21 => 
  array (
    0 => 1,
    1 => '.',
  ),
  22 => 
  array (
    0 => 1,
    1 => '.',
  ),
  23 => 
  array (
    0 => 1,
    1 => '.',
  ),
  24 => 
  array (
    0 => 1,
    1 => '.',
  ),
  25 => 
  array (
    0 => 1,
    1 => '.',
  ),
  26 => 
  array (
    0 => 1,
    1 => '.',
  ),
  27 => 
  array (
    0 => 1,
    1 => '.',
  ),
  28 => 
  array (
    0 => 1,
    1 => '.',
  ),
  29 => 
  array (
    0 => 1,
    1 => '.',
  ),
  30 => 
  array (
    0 => 1,
    1 => '.',
  ),
  31 => 
  array (
    0 => 1,
    1 => '.',
  ),
  32 => 
  array (
    0 => 1,
    1 => '.',
  ),
  33 => 
  array (
    0 => 1,
    1 => '.',
  ),
  34 => 
  array (
    0 => 1,
    1 => '.',
  ),
  35 => 
  array (
    0 => 1,
    1 => '.',
  ),
  36 => 
  array (
    0 => 1,
    1 => '.',
  ),
  37 => 
  array (
    0 => 1,
    1 => '.',
  ),
  38 => 
  array (
    0 => 1,
    1 => '.',
  ),
  39 => 
  array (
    0 => 1,
    1 => '.',
  ),
  40 => 
  array (
    0 => 1,
    1 => '.',
  ),
  41 => 
  array (
    0 => 1,
    1 => '.',
  ),
  42 => 
  array (
    0 => 1,
    1 => '.',
  ),
  43 => 
  array (
    0 => 1,
    1 => '.',
  ),
  44 => 
  array (
    0 => 1,
    1 => '.',
  ),
  45 => 
  array (
    0 => 1,
    1 => '.',
  ),
  46 => 
  array (
    0 => 1,
    1 => '.',
  ),
  47 => 
  array (
    0 => 1,
    1 => '.',
  ),
  48 => 
  array (
    0 => 1,
    1 => '.',
  ),
  49 => 
  array (
    0 => 1,
    1 => '.',
  ),
  50 => 
  array (
    0 => 1,
    1 => '.',
  ),
  51 => 
  array (
    0 => 1,
    1 => '.',
  ),
  52 => 
  array (
    0 => 1,
    1 => '.',
  ),
  53 => 
  array (
    0 => 1,
    1 => '.',
  ),
  54 => 
  array (
    0 => 1,
    1 => '.',
  ),
  55 => 
  array (
    0 => 1,
    1 => '.',
  ),
  56 => 
  array (
    0 => 1,
    1 => '.',
  ),
  57 => 
  array (
    0 => 1,
    1 => '.',
  ),
  58 => 
  array (
    0 => 1,
    1 => '.',
  ),
  59 => 
  array (
    0 => 1,
    1 => '.',
  ),
  60 => 
  array (
    0 => 1,
    1 => '.',
  ),
  61 => 
  array (
    0 => 1,
    1 => '.',
  ),
  62 => 
  array (
    0 => 1,
    1 => '.',
  ),
  63 => 
  array (
    0 => 1,
    1 => '.',
  ),
  64 => 
  array (
    0 => 1,
    1 => '.',
  ),
  65 => 
  array (
    0 => 1,
    1 => '.',
  ),
  66 => 
  array (
    0 => 1,
    1 => '.',
  ),
  67 => 
  array (
    0 => 1,
    1 => '.',
  ),
  68 => 
  array (
    0 => 1,
    1 => '.',
  ),
  69 => 
  array (
    0 => 1,
    1 => '.',
  ),
  70 => 
  array (
    0 => 1,
    1 => '.',
  ),
  71 => 
  array (
    0 => 1,
    1 => '.',
  ),
  72 => 
  array (
    0 => 1,
    1 => '.',
  ),
  73 => 
  array (
    0 => 1,
    1 => '.',
  ),
  74 => 
  array (
    0 => 1,
    1 => '.',
  ),
  75 => 
  array (
    0 => 1,
    1 => '.',
  ),
  76 => 
  array (
    0 => 1,
    1 => '...done: 371,000 bytes',
  ),
), $fakelog->getLog(), 'log messages');
$phpunit->assertEquals( array (
  0 => 
  array (
    0 => 'setup',
    1 => 'self',
  ),
  1 => 
  array (
    0 => 'saveas',
    1 => 'SQLite-1.0.4.tgz',
  ),
  2 => 
  array (
    0 => 'start',
    1 => 
    array (
      0 => 'SQLite-1.0.4.tgz',
      1 => '371000',
    ),
  ),
  3 => 
  array (
    0 => 'bytesread',
    1 => 1024,
  ),
  4 => 
  array (
    0 => 'bytesread',
    1 => 2048,
  ),
  5 => 
  array (
    0 => 'bytesread',
    1 => 3072,
  ),
  6 => 
  array (
    0 => 'bytesread',
    1 => 4096,
  ),
  7 => 
  array (
    0 => 'bytesread',
    1 => 5120,
  ),
  8 => 
  array (
    0 => 'bytesread',
    1 => 6144,
  ),
  9 => 
  array (
    0 => 'bytesread',
    1 => 7168,
  ),
  10 => 
  array (
    0 => 'bytesread',
    1 => 8192,
  ),
  11 => 
  array (
    0 => 'bytesread',
    1 => 9216,
  ),
  12 => 
  array (
    0 => 'bytesread',
    1 => 10240,
  ),
  13 => 
  array (
    0 => 'bytesread',
    1 => 11264,
  ),
  14 => 
  array (
    0 => 'bytesread',
    1 => 12288,
  ),
  15 => 
  array (
    0 => 'bytesread',
    1 => 13312,
  ),
  16 => 
  array (
    0 => 'bytesread',
    1 => 14336,
  ),
  17 => 
  array (
    0 => 'bytesread',
    1 => 15360,
  ),
  18 => 
  array (
    0 => 'bytesread',
    1 => 16384,
  ),
  19 => 
  array (
    0 => 'bytesread',
    1 => 17408,
  ),
  20 => 
  array (
    0 => 'bytesread',
    1 => 18432,
  ),
  21 => 
  array (
    0 => 'bytesread',
    1 => 19456,
  ),
  22 => 
  array (
    0 => 'bytesread',
    1 => 20480,
  ),
  23 => 
  array (
    0 => 'bytesread',
    1 => 21504,
  ),
  24 => 
  array (
    0 => 'bytesread',
    1 => 22528,
  ),
  25 => 
  array (
    0 => 'bytesread',
    1 => 23552,
  ),
  26 => 
  array (
    0 => 'bytesread',
    1 => 24576,
  ),
  27 => 
  array (
    0 => 'bytesread',
    1 => 25600,
  ),
  28 => 
  array (
    0 => 'bytesread',
    1 => 26624,
  ),
  29 => 
  array (
    0 => 'bytesread',
    1 => 27648,
  ),
  30 => 
  array (
    0 => 'bytesread',
    1 => 28672,
  ),
  31 => 
  array (
    0 => 'bytesread',
    1 => 29696,
  ),
  32 => 
  array (
    0 => 'bytesread',
    1 => 30720,
  ),
  33 => 
  array (
    0 => 'bytesread',
    1 => 31744,
  ),
  34 => 
  array (
    0 => 'bytesread',
    1 => 32768,
  ),
  35 => 
  array (
    0 => 'bytesread',
    1 => 33792,
  ),
  36 => 
  array (
    0 => 'bytesread',
    1 => 34816,
  ),
  37 => 
  array (
    0 => 'bytesread',
    1 => 35840,
  ),
  38 => 
  array (
    0 => 'bytesread',
    1 => 36864,
  ),
  39 => 
  array (
    0 => 'bytesread',
    1 => 37888,
  ),
  40 => 
  array (
    0 => 'bytesread',
    1 => 38912,
  ),
  41 => 
  array (
    0 => 'bytesread',
    1 => 39936,
  ),
  42 => 
  array (
    0 => 'bytesread',
    1 => 40960,
  ),
  43 => 
  array (
    0 => 'bytesread',
    1 => 41984,
  ),
  44 => 
  array (
    0 => 'bytesread',
    1 => 43008,
  ),
  45 => 
  array (
    0 => 'bytesread',
    1 => 44032,
  ),
  46 => 
  array (
    0 => 'bytesread',
    1 => 45056,
  ),
  47 => 
  array (
    0 => 'bytesread',
    1 => 46080,
  ),
  48 => 
  array (
    0 => 'bytesread',
    1 => 47104,
  ),
  49 => 
  array (
    0 => 'bytesread',
    1 => 48128,
  ),
  50 => 
  array (
    0 => 'bytesread',
    1 => 49152,
  ),
  51 => 
  array (
    0 => 'bytesread',
    1 => 50176,
  ),
  52 => 
  array (
    0 => 'bytesread',
    1 => 51200,
  ),
  53 => 
  array (
    0 => 'bytesread',
    1 => 52224,
  ),
  54 => 
  array (
    0 => 'bytesread',
    1 => 53248,
  ),
  55 => 
  array (
    0 => 'bytesread',
    1 => 54272,
  ),
  56 => 
  array (
    0 => 'bytesread',
    1 => 55296,
  ),
  57 => 
  array (
    0 => 'bytesread',
    1 => 56320,
  ),
  58 => 
  array (
    0 => 'bytesread',
    1 => 57344,
  ),
  59 => 
  array (
    0 => 'bytesread',
    1 => 58368,
  ),
  60 => 
  array (
    0 => 'bytesread',
    1 => 59392,
  ),
  61 => 
  array (
    0 => 'bytesread',
    1 => 60416,
  ),
  62 => 
  array (
    0 => 'bytesread',
    1 => 61440,
  ),
  63 => 
  array (
    0 => 'bytesread',
    1 => 62464,
  ),
  64 => 
  array (
    0 => 'bytesread',
    1 => 63488,
  ),
  65 => 
  array (
    0 => 'bytesread',
    1 => 64512,
  ),
  66 => 
  array (
    0 => 'bytesread',
    1 => 65536,
  ),
  67 => 
  array (
    0 => 'bytesread',
    1 => 66560,
  ),
  68 => 
  array (
    0 => 'bytesread',
    1 => 67584,
  ),
  69 => 
  array (
    0 => 'bytesread',
    1 => 68608,
  ),
  70 => 
  array (
    0 => 'bytesread',
    1 => 69632,
  ),
  71 => 
  array (
    0 => 'bytesread',
    1 => 70656,
  ),
  72 => 
  array (
    0 => 'bytesread',
    1 => 71680,
  ),
  73 => 
  array (
    0 => 'bytesread',
    1 => 72704,
  ),
  74 => 
  array (
    0 => 'bytesread',
    1 => 73728,
  ),
  75 => 
  array (
    0 => 'bytesread',
    1 => 74752,
  ),
  76 => 
  array (
    0 => 'bytesread',
    1 => 75776,
  ),
  77 => 
  array (
    0 => 'bytesread',
    1 => 76800,
  ),
  78 => 
  array (
    0 => 'bytesread',
    1 => 77824,
  ),
  79 => 
  array (
    0 => 'bytesread',
    1 => 78848,
  ),
  80 => 
  array (
    0 => 'bytesread',
    1 => 79872,
  ),
  81 => 
  array (
    0 => 'bytesread',
    1 => 80896,
  ),
  82 => 
  array (
    0 => 'bytesread',
    1 => 81920,
  ),
  83 => 
  array (
    0 => 'bytesread',
    1 => 82944,
  ),
  84 => 
  array (
    0 => 'bytesread',
    1 => 83968,
  ),
  85 => 
  array (
    0 => 'bytesread',
    1 => 84992,
  ),
  86 => 
  array (
    0 => 'bytesread',
    1 => 86016,
  ),
  87 => 
  array (
    0 => 'bytesread',
    1 => 87040,
  ),
  88 => 
  array (
    0 => 'bytesread',
    1 => 88064,
  ),
  89 => 
  array (
    0 => 'bytesread',
    1 => 89088,
  ),
  90 => 
  array (
    0 => 'bytesread',
    1 => 90112,
  ),
  91 => 
  array (
    0 => 'bytesread',
    1 => 91136,
  ),
  92 => 
  array (
    0 => 'bytesread',
    1 => 92160,
  ),
  93 => 
  array (
    0 => 'bytesread',
    1 => 93184,
  ),
  94 => 
  array (
    0 => 'bytesread',
    1 => 94208,
  ),
  95 => 
  array (
    0 => 'bytesread',
    1 => 95232,
  ),
  96 => 
  array (
    0 => 'bytesread',
    1 => 96256,
  ),
  97 => 
  array (
    0 => 'bytesread',
    1 => 97280,
  ),
  98 => 
  array (
    0 => 'bytesread',
    1 => 98304,
  ),
  99 => 
  array (
    0 => 'bytesread',
    1 => 99328,
  ),
  100 => 
  array (
    0 => 'bytesread',
    1 => 100352,
  ),
  101 => 
  array (
    0 => 'bytesread',
    1 => 101376,
  ),
  102 => 
  array (
    0 => 'bytesread',
    1 => 102400,
  ),
  103 => 
  array (
    0 => 'bytesread',
    1 => 103424,
  ),
  104 => 
  array (
    0 => 'bytesread',
    1 => 104448,
  ),
  105 => 
  array (
    0 => 'bytesread',
    1 => 105472,
  ),
  106 => 
  array (
    0 => 'bytesread',
    1 => 106496,
  ),
  107 => 
  array (
    0 => 'bytesread',
    1 => 107520,
  ),
  108 => 
  array (
    0 => 'bytesread',
    1 => 108544,
  ),
  109 => 
  array (
    0 => 'bytesread',
    1 => 109568,
  ),
  110 => 
  array (
    0 => 'bytesread',
    1 => 110592,
  ),
  111 => 
  array (
    0 => 'bytesread',
    1 => 111616,
  ),
  112 => 
  array (
    0 => 'bytesread',
    1 => 112640,
  ),
  113 => 
  array (
    0 => 'bytesread',
    1 => 113664,
  ),
  114 => 
  array (
    0 => 'bytesread',
    1 => 114688,
  ),
  115 => 
  array (
    0 => 'bytesread',
    1 => 115712,
  ),
  116 => 
  array (
    0 => 'bytesread',
    1 => 116736,
  ),
  117 => 
  array (
    0 => 'bytesread',
    1 => 117760,
  ),
  118 => 
  array (
    0 => 'bytesread',
    1 => 118784,
  ),
  119 => 
  array (
    0 => 'bytesread',
    1 => 119808,
  ),
  120 => 
  array (
    0 => 'bytesread',
    1 => 120832,
  ),
  121 => 
  array (
    0 => 'bytesread',
    1 => 121856,
  ),
  122 => 
  array (
    0 => 'bytesread',
    1 => 122880,
  ),
  123 => 
  array (
    0 => 'bytesread',
    1 => 123904,
  ),
  124 => 
  array (
    0 => 'bytesread',
    1 => 124928,
  ),
  125 => 
  array (
    0 => 'bytesread',
    1 => 125952,
  ),
  126 => 
  array (
    0 => 'bytesread',
    1 => 126976,
  ),
  127 => 
  array (
    0 => 'bytesread',
    1 => 128000,
  ),
  128 => 
  array (
    0 => 'bytesread',
    1 => 129024,
  ),
  129 => 
  array (
    0 => 'bytesread',
    1 => 130048,
  ),
  130 => 
  array (
    0 => 'bytesread',
    1 => 131072,
  ),
  131 => 
  array (
    0 => 'bytesread',
    1 => 132096,
  ),
  132 => 
  array (
    0 => 'bytesread',
    1 => 133120,
  ),
  133 => 
  array (
    0 => 'bytesread',
    1 => 134144,
  ),
  134 => 
  array (
    0 => 'bytesread',
    1 => 135168,
  ),
  135 => 
  array (
    0 => 'bytesread',
    1 => 136192,
  ),
  136 => 
  array (
    0 => 'bytesread',
    1 => 137216,
  ),
  137 => 
  array (
    0 => 'bytesread',
    1 => 138240,
  ),
  138 => 
  array (
    0 => 'bytesread',
    1 => 139264,
  ),
  139 => 
  array (
    0 => 'bytesread',
    1 => 140288,
  ),
  140 => 
  array (
    0 => 'bytesread',
    1 => 141312,
  ),
  141 => 
  array (
    0 => 'bytesread',
    1 => 142336,
  ),
  142 => 
  array (
    0 => 'bytesread',
    1 => 143360,
  ),
  143 => 
  array (
    0 => 'bytesread',
    1 => 144384,
  ),
  144 => 
  array (
    0 => 'bytesread',
    1 => 145408,
  ),
  145 => 
  array (
    0 => 'bytesread',
    1 => 146432,
  ),
  146 => 
  array (
    0 => 'bytesread',
    1 => 147456,
  ),
  147 => 
  array (
    0 => 'bytesread',
    1 => 148480,
  ),
  148 => 
  array (
    0 => 'bytesread',
    1 => 149504,
  ),
  149 => 
  array (
    0 => 'bytesread',
    1 => 150528,
  ),
  150 => 
  array (
    0 => 'bytesread',
    1 => 151552,
  ),
  151 => 
  array (
    0 => 'bytesread',
    1 => 152576,
  ),
  152 => 
  array (
    0 => 'bytesread',
    1 => 153600,
  ),
  153 => 
  array (
    0 => 'bytesread',
    1 => 154624,
  ),
  154 => 
  array (
    0 => 'bytesread',
    1 => 155648,
  ),
  155 => 
  array (
    0 => 'bytesread',
    1 => 156672,
  ),
  156 => 
  array (
    0 => 'bytesread',
    1 => 157696,
  ),
  157 => 
  array (
    0 => 'bytesread',
    1 => 158720,
  ),
  158 => 
  array (
    0 => 'bytesread',
    1 => 159744,
  ),
  159 => 
  array (
    0 => 'bytesread',
    1 => 160768,
  ),
  160 => 
  array (
    0 => 'bytesread',
    1 => 161792,
  ),
  161 => 
  array (
    0 => 'bytesread',
    1 => 162816,
  ),
  162 => 
  array (
    0 => 'bytesread',
    1 => 163840,
  ),
  163 => 
  array (
    0 => 'bytesread',
    1 => 164864,
  ),
  164 => 
  array (
    0 => 'bytesread',
    1 => 165888,
  ),
  165 => 
  array (
    0 => 'bytesread',
    1 => 166912,
  ),
  166 => 
  array (
    0 => 'bytesread',
    1 => 167936,
  ),
  167 => 
  array (
    0 => 'bytesread',
    1 => 168960,
  ),
  168 => 
  array (
    0 => 'bytesread',
    1 => 169984,
  ),
  169 => 
  array (
    0 => 'bytesread',
    1 => 171008,
  ),
  170 => 
  array (
    0 => 'bytesread',
    1 => 172032,
  ),
  171 => 
  array (
    0 => 'bytesread',
    1 => 173056,
  ),
  172 => 
  array (
    0 => 'bytesread',
    1 => 174080,
  ),
  173 => 
  array (
    0 => 'bytesread',
    1 => 175104,
  ),
  174 => 
  array (
    0 => 'bytesread',
    1 => 176128,
  ),
  175 => 
  array (
    0 => 'bytesread',
    1 => 177152,
  ),
  176 => 
  array (
    0 => 'bytesread',
    1 => 178176,
  ),
  177 => 
  array (
    0 => 'bytesread',
    1 => 179200,
  ),
  178 => 
  array (
    0 => 'bytesread',
    1 => 180224,
  ),
  179 => 
  array (
    0 => 'bytesread',
    1 => 181248,
  ),
  180 => 
  array (
    0 => 'bytesread',
    1 => 182272,
  ),
  181 => 
  array (
    0 => 'bytesread',
    1 => 183296,
  ),
  182 => 
  array (
    0 => 'bytesread',
    1 => 184320,
  ),
  183 => 
  array (
    0 => 'bytesread',
    1 => 185344,
  ),
  184 => 
  array (
    0 => 'bytesread',
    1 => 186368,
  ),
  185 => 
  array (
    0 => 'bytesread',
    1 => 187392,
  ),
  186 => 
  array (
    0 => 'bytesread',
    1 => 188416,
  ),
  187 => 
  array (
    0 => 'bytesread',
    1 => 189440,
  ),
  188 => 
  array (
    0 => 'bytesread',
    1 => 190464,
  ),
  189 => 
  array (
    0 => 'bytesread',
    1 => 191488,
  ),
  190 => 
  array (
    0 => 'bytesread',
    1 => 192512,
  ),
  191 => 
  array (
    0 => 'bytesread',
    1 => 193536,
  ),
  192 => 
  array (
    0 => 'bytesread',
    1 => 194560,
  ),
  193 => 
  array (
    0 => 'bytesread',
    1 => 195584,
  ),
  194 => 
  array (
    0 => 'bytesread',
    1 => 196608,
  ),
  195 => 
  array (
    0 => 'bytesread',
    1 => 197632,
  ),
  196 => 
  array (
    0 => 'bytesread',
    1 => 198656,
  ),
  197 => 
  array (
    0 => 'bytesread',
    1 => 199680,
  ),
  198 => 
  array (
    0 => 'bytesread',
    1 => 200704,
  ),
  199 => 
  array (
    0 => 'bytesread',
    1 => 201728,
  ),
  200 => 
  array (
    0 => 'bytesread',
    1 => 202752,
  ),
  201 => 
  array (
    0 => 'bytesread',
    1 => 203776,
  ),
  202 => 
  array (
    0 => 'bytesread',
    1 => 204800,
  ),
  203 => 
  array (
    0 => 'bytesread',
    1 => 205824,
  ),
  204 => 
  array (
    0 => 'bytesread',
    1 => 206848,
  ),
  205 => 
  array (
    0 => 'bytesread',
    1 => 207872,
  ),
  206 => 
  array (
    0 => 'bytesread',
    1 => 208896,
  ),
  207 => 
  array (
    0 => 'bytesread',
    1 => 209920,
  ),
  208 => 
  array (
    0 => 'bytesread',
    1 => 210944,
  ),
  209 => 
  array (
    0 => 'bytesread',
    1 => 211968,
  ),
  210 => 
  array (
    0 => 'bytesread',
    1 => 212992,
  ),
  211 => 
  array (
    0 => 'bytesread',
    1 => 214016,
  ),
  212 => 
  array (
    0 => 'bytesread',
    1 => 215040,
  ),
  213 => 
  array (
    0 => 'bytesread',
    1 => 216064,
  ),
  214 => 
  array (
    0 => 'bytesread',
    1 => 217088,
  ),
  215 => 
  array (
    0 => 'bytesread',
    1 => 218112,
  ),
  216 => 
  array (
    0 => 'bytesread',
    1 => 219136,
  ),
  217 => 
  array (
    0 => 'bytesread',
    1 => 220160,
  ),
  218 => 
  array (
    0 => 'bytesread',
    1 => 221184,
  ),
  219 => 
  array (
    0 => 'bytesread',
    1 => 222208,
  ),
  220 => 
  array (
    0 => 'bytesread',
    1 => 223232,
  ),
  221 => 
  array (
    0 => 'bytesread',
    1 => 224256,
  ),
  222 => 
  array (
    0 => 'bytesread',
    1 => 225280,
  ),
  223 => 
  array (
    0 => 'bytesread',
    1 => 226304,
  ),
  224 => 
  array (
    0 => 'bytesread',
    1 => 227328,
  ),
  225 => 
  array (
    0 => 'bytesread',
    1 => 228352,
  ),
  226 => 
  array (
    0 => 'bytesread',
    1 => 229376,
  ),
  227 => 
  array (
    0 => 'bytesread',
    1 => 230400,
  ),
  228 => 
  array (
    0 => 'bytesread',
    1 => 231424,
  ),
  229 => 
  array (
    0 => 'bytesread',
    1 => 232448,
  ),
  230 => 
  array (
    0 => 'bytesread',
    1 => 233472,
  ),
  231 => 
  array (
    0 => 'bytesread',
    1 => 234496,
  ),
  232 => 
  array (
    0 => 'bytesread',
    1 => 235520,
  ),
  233 => 
  array (
    0 => 'bytesread',
    1 => 236544,
  ),
  234 => 
  array (
    0 => 'bytesread',
    1 => 237568,
  ),
  235 => 
  array (
    0 => 'bytesread',
    1 => 238592,
  ),
  236 => 
  array (
    0 => 'bytesread',
    1 => 239616,
  ),
  237 => 
  array (
    0 => 'bytesread',
    1 => 240640,
  ),
  238 => 
  array (
    0 => 'bytesread',
    1 => 241664,
  ),
  239 => 
  array (
    0 => 'bytesread',
    1 => 242688,
  ),
  240 => 
  array (
    0 => 'bytesread',
    1 => 243712,
  ),
  241 => 
  array (
    0 => 'bytesread',
    1 => 244736,
  ),
  242 => 
  array (
    0 => 'bytesread',
    1 => 245760,
  ),
  243 => 
  array (
    0 => 'bytesread',
    1 => 246784,
  ),
  244 => 
  array (
    0 => 'bytesread',
    1 => 247808,
  ),
  245 => 
  array (
    0 => 'bytesread',
    1 => 248832,
  ),
  246 => 
  array (
    0 => 'bytesread',
    1 => 249856,
  ),
  247 => 
  array (
    0 => 'bytesread',
    1 => 250880,
  ),
  248 => 
  array (
    0 => 'bytesread',
    1 => 251904,
  ),
  249 => 
  array (
    0 => 'bytesread',
    1 => 252928,
  ),
  250 => 
  array (
    0 => 'bytesread',
    1 => 253952,
  ),
  251 => 
  array (
    0 => 'bytesread',
    1 => 254976,
  ),
  252 => 
  array (
    0 => 'bytesread',
    1 => 256000,
  ),
  253 => 
  array (
    0 => 'bytesread',
    1 => 257024,
  ),
  254 => 
  array (
    0 => 'bytesread',
    1 => 258048,
  ),
  255 => 
  array (
    0 => 'bytesread',
    1 => 259072,
  ),
  256 => 
  array (
    0 => 'bytesread',
    1 => 260096,
  ),
  257 => 
  array (
    0 => 'bytesread',
    1 => 261120,
  ),
  258 => 
  array (
    0 => 'bytesread',
    1 => 262144,
  ),
  259 => 
  array (
    0 => 'bytesread',
    1 => 263168,
  ),
  260 => 
  array (
    0 => 'bytesread',
    1 => 264192,
  ),
  261 => 
  array (
    0 => 'bytesread',
    1 => 265216,
  ),
  262 => 
  array (
    0 => 'bytesread',
    1 => 266240,
  ),
  263 => 
  array (
    0 => 'bytesread',
    1 => 267264,
  ),
  264 => 
  array (
    0 => 'bytesread',
    1 => 268288,
  ),
  265 => 
  array (
    0 => 'bytesread',
    1 => 269312,
  ),
  266 => 
  array (
    0 => 'bytesread',
    1 => 270336,
  ),
  267 => 
  array (
    0 => 'bytesread',
    1 => 271360,
  ),
  268 => 
  array (
    0 => 'bytesread',
    1 => 272384,
  ),
  269 => 
  array (
    0 => 'bytesread',
    1 => 273408,
  ),
  270 => 
  array (
    0 => 'bytesread',
    1 => 274432,
  ),
  271 => 
  array (
    0 => 'bytesread',
    1 => 275456,
  ),
  272 => 
  array (
    0 => 'bytesread',
    1 => 276480,
  ),
  273 => 
  array (
    0 => 'bytesread',
    1 => 277504,
  ),
  274 => 
  array (
    0 => 'bytesread',
    1 => 278528,
  ),
  275 => 
  array (
    0 => 'bytesread',
    1 => 279552,
  ),
  276 => 
  array (
    0 => 'bytesread',
    1 => 280576,
  ),
  277 => 
  array (
    0 => 'bytesread',
    1 => 281600,
  ),
  278 => 
  array (
    0 => 'bytesread',
    1 => 282624,
  ),
  279 => 
  array (
    0 => 'bytesread',
    1 => 283648,
  ),
  280 => 
  array (
    0 => 'bytesread',
    1 => 284672,
  ),
  281 => 
  array (
    0 => 'bytesread',
    1 => 285696,
  ),
  282 => 
  array (
    0 => 'bytesread',
    1 => 286720,
  ),
  283 => 
  array (
    0 => 'bytesread',
    1 => 287744,
  ),
  284 => 
  array (
    0 => 'bytesread',
    1 => 288768,
  ),
  285 => 
  array (
    0 => 'bytesread',
    1 => 289792,
  ),
  286 => 
  array (
    0 => 'bytesread',
    1 => 290816,
  ),
  287 => 
  array (
    0 => 'bytesread',
    1 => 291840,
  ),
  288 => 
  array (
    0 => 'bytesread',
    1 => 292864,
  ),
  289 => 
  array (
    0 => 'bytesread',
    1 => 293888,
  ),
  290 => 
  array (
    0 => 'bytesread',
    1 => 294912,
  ),
  291 => 
  array (
    0 => 'bytesread',
    1 => 295936,
  ),
  292 => 
  array (
    0 => 'bytesread',
    1 => 296960,
  ),
  293 => 
  array (
    0 => 'bytesread',
    1 => 297984,
  ),
  294 => 
  array (
    0 => 'bytesread',
    1 => 299008,
  ),
  295 => 
  array (
    0 => 'bytesread',
    1 => 300032,
  ),
  296 => 
  array (
    0 => 'bytesread',
    1 => 301056,
  ),
  297 => 
  array (
    0 => 'bytesread',
    1 => 302080,
  ),
  298 => 
  array (
    0 => 'bytesread',
    1 => 303104,
  ),
  299 => 
  array (
    0 => 'bytesread',
    1 => 304128,
  ),
  300 => 
  array (
    0 => 'bytesread',
    1 => 305152,
  ),
  301 => 
  array (
    0 => 'bytesread',
    1 => 306176,
  ),
  302 => 
  array (
    0 => 'bytesread',
    1 => 307200,
  ),
  303 => 
  array (
    0 => 'bytesread',
    1 => 308224,
  ),
  304 => 
  array (
    0 => 'bytesread',
    1 => 309248,
  ),
  305 => 
  array (
    0 => 'bytesread',
    1 => 310272,
  ),
  306 => 
  array (
    0 => 'bytesread',
    1 => 311296,
  ),
  307 => 
  array (
    0 => 'bytesread',
    1 => 312320,
  ),
  308 => 
  array (
    0 => 'bytesread',
    1 => 313344,
  ),
  309 => 
  array (
    0 => 'bytesread',
    1 => 314368,
  ),
  310 => 
  array (
    0 => 'bytesread',
    1 => 315392,
  ),
  311 => 
  array (
    0 => 'bytesread',
    1 => 316416,
  ),
  312 => 
  array (
    0 => 'bytesread',
    1 => 317440,
  ),
  313 => 
  array (
    0 => 'bytesread',
    1 => 318464,
  ),
  314 => 
  array (
    0 => 'bytesread',
    1 => 319488,
  ),
  315 => 
  array (
    0 => 'bytesread',
    1 => 320512,
  ),
  316 => 
  array (
    0 => 'bytesread',
    1 => 321536,
  ),
  317 => 
  array (
    0 => 'bytesread',
    1 => 322560,
  ),
  318 => 
  array (
    0 => 'bytesread',
    1 => 323584,
  ),
  319 => 
  array (
    0 => 'bytesread',
    1 => 324608,
  ),
  320 => 
  array (
    0 => 'bytesread',
    1 => 325632,
  ),
  321 => 
  array (
    0 => 'bytesread',
    1 => 326656,
  ),
  322 => 
  array (
    0 => 'bytesread',
    1 => 327680,
  ),
  323 => 
  array (
    0 => 'bytesread',
    1 => 328704,
  ),
  324 => 
  array (
    0 => 'bytesread',
    1 => 329728,
  ),
  325 => 
  array (
    0 => 'bytesread',
    1 => 330752,
  ),
  326 => 
  array (
    0 => 'bytesread',
    1 => 331776,
  ),
  327 => 
  array (
    0 => 'bytesread',
    1 => 332800,
  ),
  328 => 
  array (
    0 => 'bytesread',
    1 => 333824,
  ),
  329 => 
  array (
    0 => 'bytesread',
    1 => 334848,
  ),
  330 => 
  array (
    0 => 'bytesread',
    1 => 335872,
  ),
  331 => 
  array (
    0 => 'bytesread',
    1 => 336896,
  ),
  332 => 
  array (
    0 => 'bytesread',
    1 => 337920,
  ),
  333 => 
  array (
    0 => 'bytesread',
    1 => 338944,
  ),
  334 => 
  array (
    0 => 'bytesread',
    1 => 339968,
  ),
  335 => 
  array (
    0 => 'bytesread',
    1 => 340992,
  ),
  336 => 
  array (
    0 => 'bytesread',
    1 => 342016,
  ),
  337 => 
  array (
    0 => 'bytesread',
    1 => 343040,
  ),
  338 => 
  array (
    0 => 'bytesread',
    1 => 344064,
  ),
  339 => 
  array (
    0 => 'bytesread',
    1 => 345088,
  ),
  340 => 
  array (
    0 => 'bytesread',
    1 => 346112,
  ),
  341 => 
  array (
    0 => 'bytesread',
    1 => 347136,
  ),
  342 => 
  array (
    0 => 'bytesread',
    1 => 348160,
  ),
  343 => 
  array (
    0 => 'bytesread',
    1 => 349184,
  ),
  344 => 
  array (
    0 => 'bytesread',
    1 => 350208,
  ),
  345 => 
  array (
    0 => 'bytesread',
    1 => 351232,
  ),
  346 => 
  array (
    0 => 'bytesread',
    1 => 352256,
  ),
  347 => 
  array (
    0 => 'bytesread',
    1 => 353280,
  ),
  348 => 
  array (
    0 => 'bytesread',
    1 => 354304,
  ),
  349 => 
  array (
    0 => 'bytesread',
    1 => 355328,
  ),
  350 => 
  array (
    0 => 'bytesread',
    1 => 356352,
  ),
  351 => 
  array (
    0 => 'bytesread',
    1 => 357376,
  ),
  352 => 
  array (
    0 => 'bytesread',
    1 => 358400,
  ),
  353 => 
  array (
    0 => 'bytesread',
    1 => 359424,
  ),
  354 => 
  array (
    0 => 'bytesread',
    1 => 360448,
  ),
  355 => 
  array (
    0 => 'bytesread',
    1 => 361472,
  ),
  356 => 
  array (
    0 => 'bytesread',
    1 => 362496,
  ),
  357 => 
  array (
    0 => 'bytesread',
    1 => 363520,
  ),
  358 => 
  array (
    0 => 'bytesread',
    1 => 364544,
  ),
  359 => 
  array (
    0 => 'bytesread',
    1 => 365568,
  ),
  360 => 
  array (
    0 => 'bytesread',
    1 => 366592,
  ),
  361 => 
  array (
    0 => 'bytesread',
    1 => 367616,
  ),
  362 => 
  array (
    0 => 'bytesread',
    1 => 368640,
  ),
  363 => 
  array (
    0 => 'bytesread',
    1 => 369664,
  ),
  364 => 
  array (
    0 => 'bytesread',
    1 => 370688,
  ),
  365 => 
  array (
    0 => 'bytesread',
    1 => 371000,
  ),
  366 => 
  array (
    0 => 'done',
    1 => 371000,
  ),
 )
, $fakelog->getDownload(), 'download callback messages');

$installer->setOptions($dp->getOptions());
$installer->sortPackagesForInstall($result);
$installer->setDownloadedPackages($result);
$phpunit->assertNoErrors('set of downloaded packages');
$ret = &$installer->install($result[0], $dp->getOptions());
$phpunit->assertNoErrors('after install');
$phpunit->assertEquals(array(), $reg->listPackages(), 'pear');
$phpunit->assertEquals(array('sqlite'), $reg->listPackages('pecl'), 'pecl');
echo 'tests done';
?>
--EXPECT--
tests done