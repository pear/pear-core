--TEST--
PEAR_Validate->validate(), Bug #17744: Empty Changelog causes a fatal error
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';

$pf = &$v2parser->parse('<?xml version="1.0" encoding="UTF-8"?>
<package version="2.0" xmlns="http://pear.php.net/dtd/package-2.0" xmlns:tasks="http://pear.php.net/dtd/tasks-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
http://pear.php.net/dtd/tasks-1.0.xsd
http://pear.php.net/dtd/package-2.0
http://pear.php.net/dtd/package-2.0.xsd">
 <name>Net_Socket</name>
 <channel>pear.php.net</channel>
 <summary>Network Socket Interface</summary>
 <description>
  Net_Socket is a class interface to TCP sockets.  It provides blocking
  and non-blocking operation, with different reading and writing modes
  (byte-wise, block-wise, line-wise and special formats like network
  byte-order ip addresses).
 </description>

 <lead>
  <name>Chuck Hagenbuch</name>
  <user>chagenbu</user>
  <email>chuck@horde.org</email>
  <active>yes</active>
 </lead>
 <lead>
  <name>Stig Bakken</name>
  <user>ssb</user>
  <email>stig@php.net</email>
  <active>no</active>
 </lead>

 <date>2008-07-11</date>
 <time>00:33:32</time>

 <version>
  <release>1.0.10</release>
  <api>1.0.9</api>
 </version>
 <stability>
  <release>stable</release>
  <api>stable</api>
 </stability>
 <license uri="http://www.php.net/license/2_02.txt">PHP License</license>
 <notes>
- Configurable newline sequence (PEAR Bug #14181)
- Make $size parameter to gets() optional (PEAR Bug #14433)
- Don\'t overwrite $errstr set by fsockopen (PEAR Bug #14448)
- Avoid an infinite loop if fwrite() returns 0 (PEAR Bug #14619)
- CS cleanup (PEAR Bug #14803)
 </notes>

 <contents>
  <dir name="/">
   <file baseinstalldir="Net" name="Socket.php" role="php" />
  </dir> <!-- / -->
 </contents>
 <dependencies>
  <required>
   <php>
    <min>4.0.0</min>
   </php>
   <pearinstaller>
    <min>1.4.0b1</min>
   </pearinstaller>
  </required>
 </dependencies>
 <phprelease />
 <changelog />
</package>', 'package2.xml');
$a = &$pf->getRW();
$pf = &$a;
$phpunit->assertNoErrors('parse');
$pf->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertNoErrors('validate');
$pf->flattenFilelist();
$val->setPackageFile($pf);

$res = $val->validate(PEAR_VALIDATE_NORMAL);
$phpunit->assertNoErrors('$val->validate');
$phpunit->assertTrue($res, '$val->validate');

$pf->setChangelogEntry('1.0.10', $pf->generateChangeLogEntry('Fake Changelog'));

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
