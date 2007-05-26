--TEST--
PEAR_Installer->sortPackagesForUninstall() - real-world example (uninstall the SOAP package)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'Mail_Mime',
    'channel' => 'pear.php.net',
    'package' => 'Mail_Mime',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'SOAP',
    'version' => '0.8.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.2.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<package version="1.0">
  <name>Mail_Mime</name>
  <summary>Provides classes to create and decode mime messages.</summary>
  <description>Provides classes to deal with creation and manipulation of mime messages:</description>
  <maintainers>
    <maintainer>
      <user>richard</user>
      <name>Richard Heyes</name>
      <email>richard@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>cox</user>
      <name>Tomas V.V.Cox</name>
      <email>cox@idecnet.com</email>
      <role>contributor</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.2.1</version>
    <date>2002-07-27</date>
    <license>PHP</license>
    <state>stable</state>
    <notes>o License change
o Applied a few changes From Ilia Alshanetsky</notes>
    <filelist>
      <file role="php" baseinstalldir="Mail" md5sum="0caaff707bc5a6c22a799320de3fef37" name="mime.php"/>
      <file role="php" baseinstalldir="Mail" md5sum="26c14ff366dd6f3a1d6336083b05c1f1" name="mimeDecode.php"/>
      <file role="php" baseinstalldir="Mail" md5sum="248efdb87e5fad7f6c4ad00e3b5675ce" name="mimePart.php"/>
      <file role="data" baseinstalldir="Mail" md5sum="194810c478066eaeb28f51116b88e25a" name="xmail.dtd"/>
      <file role="data" baseinstalldir="Mail" md5sum="61cea06fb6b4bd3a4b5e2d37384e14a9" name="xmail.xsl"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.2</version>
      <date>2002-07-14</date>
      <state>stable</state>
      <notes>o Added header encoding
o Altered mimePart to put boundary parameter on newline
o Changed addFrom() to setFrom()
o Added setSubject()
o Made mimePart inherit crlf setting from mime
      
</notes>
    </release>
    <release>
      <version>1.1</version>
      <date>2002-04-03</date>
      <state>stable</state>
      <notes>This is a maintenance release with various bugfixes and minor enhancements.
</notes>
    </release>
    <release>
      <version>1.0</version>
      <date>2001-12-28</date>
      <state>stable</state>
      <notes>This is the initial release of the Mime_Mail package.
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Mail_Mime-1.2.1',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'HTTP_Request',
    'channel' => 'pear.php.net',
    'package' => 'HTTP_Request',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'SOAP',
    'version' => '0.8.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.2.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>HTTP_Request</name>
  <summary>Provides an easy way to perform HTTP requests</summary>
  <description>Supports GET/POST/HEAD/TRACE/PUT/DELETE, Basic authentication, Proxy,
Proxy Authentication, SSL, file uploads etc.</description>
  <maintainers>
    <maintainer>
      <user>richard</user>
      <name>Richard Heyes</name>
      <email>richard@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>avb</user>
      <name>Alexey Borzov</name>
      <email>avb@php.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.2.4</version>
    <date>2004-12-30</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>* Notice was raised when processing a response containing secure 
  cookies (bug #2741)
* Warning was raised when processing a response with empty body and
  chunked Transfer-encoding (bug #2792)
* Improved inline documentation on constructor parameters (bug #2751)</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0.12">Net_URL</dep>
      <dep type="pkg" rel="ge" version="1.0.2">Net_Socket</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="HTTP" md5sum="1b824f1a337b29135281ca4151f25238" name="Request.php"/>
      <file role="php" baseinstalldir="HTTP" md5sum="d980b02bbae806dfb5eb06f94d4b94ca" name="Request/Listener.php"/>
      <file role="doc" baseinstalldir="HTTP" md5sum="ce18584968bde4ed5117e4750062d021" name="docs/example.php"/>
      <file role="doc" baseinstalldir="HTTP" md5sum="98cbb649ee22bf0799f0be3505218548" name="docs/download-progress.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.2.3</version>
      <date>2004-10-01</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Auth information is properly extracted from URLs of the form http://user:pass@host/
  (bug #1507)
* Connection to server is closed after performing request (bug #1692)
* Use correct argument separator for generated query stings (bug #1857, see
  also bug #704 for Net_URL)
* Do not use gzip encoding if certain string functions are overloaded by
  mbstring extension (bug #1781)
* addPostData() now properly handles multidimensional arrays (bug #2233)
      
</notes>
    </release>
    <release>
      <version>1.2.2</version>
      <date>2004-05-19</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Bug fixes:
* Fixed #1037 (unable to connect to port 80 through HTTPS). This relies
  on fix for Net_URL bug #1036, thus Net_URL 1.0.12 is now required.
* Fixed #1333 (sending POST data on non-POST requests).
* Fixed #1433 (overwriting the variable name when adding multiple files 
  for upload).
      
</notes>
    </release>
    <release>
      <version>1.2.1</version>
      <date>2004-04-29</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Additions and changes:
 * Applied patch from #851 (First parameter of constructor is now optional)
 * Implemented #526 (It is now possible to set timeout on socket, via
   parameter readTimeout)
 * Implemented #1141 (It is now possible to pass options to socket via 
   parameter socketOptions, Net_Socket 1.0.2 is needed for this functionality)
 
Fixes:
 * Fixed #842 (Doc comments incorrectly described the possible return values)
 * Fixed #1152 (Incorrect handling of cookies with \'=\' in value)
 * Fixed #1158 (Cookie parameters are not necessarily lowercase)
 * Fixed #1080 (Cookies should not be urlencoded/urldecoded)
      
</notes>
    </release>
    <release>
      <version>1.2</version>
      <date>2003-10-27</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Feature additions:
 * Support for multipart/form-data POST requests and file uploads (partly based on Christian Stocker\'s work)
 * Brackets [] after array variables are optional (on by default, controlled by useBrackets parameter)
 * HTTP_Request now implements a Subject-Observer design pattern. It is possible to add Listeners
   to the Request object to e.g. draw a progress bar when downloading a large file. This is partly
   based on Stefan Walk\'s work. A usage example for this is available.

Migration to 1.2:
 * Redirect support is now OFF by default
 * Redirect support is DEPRECATED
 * Methods clearCookies(), clearPostData(), reset() are DEPRECATED

Fixes:
 * Fixed PEAR bug #18 (Lowercased headers, fix by Dave Mertens)
 * Fixed PEAR bug #131 (Domain without trailing slash)
 * Fixed PHP bug #25486 (100 Continue handling)
 * Fixed PEAR bug #150 (Notices being generated)
 * Fixed problems with HTTP responses without bodies
      
</notes>
    </release>
    <release>
      <version>1.1.1</version>
      <date>2003-01-30</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Added redirect support. Net_URL 1.0.7 is now required.
</notes>
    </release>
    <release>
      <version>1.1.0</version>
      <date>2003-01-20</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Added SSL support as long as you have PHP 4.3.0+ and the OpenSSL extension. Net_URL 1.0.6 is now required.
</notes>
    </release>
    <release>
      <version>1.0.2</version>
      <date>2002-09-16</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Added cookie support
</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2002-07-27</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>License change
</notes>
    </release>
    <release>
      <version>1.0</version>
      <date>2002-02-17</date>
      <state>stable</state>
      <notes>Initial release of the HTTP_Request package.
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/HTTP_Request-1.2.4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'Net_URL',
    'channel' => 'pear.php.net',
    'package' => 'Net_URL',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'SOAP',
    'version' => '0.8.1',
  ),
  3 => 'stable',
), array (
  'version' => '1.0.14',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Net_URL</name>
  <summary>Easy parsing of Urls</summary>
  <description>Provides easy parsing of URLs and their constituent parts.</description>
  <maintainers>
    <maintainer>
      <user>richard</user>
      <name>Richard heyes</name>
      <email>richard@php.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.0.14</version>
    <date>2004-06-19</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>Whitespace</notes>
    <filelist>
      <file role="php" baseinstalldir="Net" name="URL.php"/>
      <file role="doc" baseinstalldir="Net" name="docs/example.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0.13</version>
      <date>2004-06-05</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Fix bug 1558
</notes>
    </release>
    <release>
      <version>1.0.12</version>
      <date>2004-05-08</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Bug fixes release (#704 and #1036)
</notes>
    </release>
    <release>
      <version>1.0.11</version>
      <date>2004-01-17</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Bug fixes release (#83 and #471)
</notes>
    </release>
    <release>
      <version>1.0.10</version>
      <date>2002-04-06</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Be more flexible in what constitutes a scheme
</notes>
    </release>
    <release>
      <version>1.0.9</version>
      <date>2002-04-05</date>
      <state>stable</state>
      <notes>Fix couple of absolute URL bugs.
</notes>
    </release>
    <release>
      <version>1.0.8</version>
      <date>2002-03-06</date>
      <state>stable</state>
      <notes>Various bugs. Remove auto setting of default url to \'/\' if a url is supplied
to the constructor. May cause BC issues.
</notes>
    </release>
    <release>
      <version>1.0.7</version>
      <date>2002-12-07</date>
      <state>stable</state>
      <notes>Added method to resolve URL paths of //, ../ and ./
</notes>
    </release>
    <release>
      <version>1.0.6</version>
      <date>2002-12-07</date>
      <state>stable</state>
      <notes>Make usage of [] optional
</notes>
    </release>
    <release>
      <version>1.0.5</version>
      <date>2002-11-14</date>
      <state>stable</state>
      <notes>Allow for URLS such as ...?foo
</notes>
    </release>
    <release>
      <version>1.0.4</version>
      <date>2002-07-27</date>
      <state>stable</state>
      <notes>License change

</notes>
    </release>
    <release>
      <version>1.0.3</version>
      <date>2002-06-20</date>
      <state>stable</state>
      <notes>Now uses HTTP_HOST if available.
</notes>
    </release>
    <release>
      <version>1.0.2</version>
      <date>2002-04-28</date>
      <state>stable</state>
      <notes>updated to fix a minor irritation when running on windows
</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2002-04-28</date>
      <state>stable</state>
      <notes>Maintenance release. Bugs fixed with path detection and defaults.
</notes>
    </release>
    <release>
      <version>1.0</version>
      <date>2002-02-17</date>
      <state>stable</state>
      <notes>This is the initial release of the Net_URL package.
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Net_URL-1.0.14',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'name' => 'Net_DIME',
    'channel' => 'pear.php.net',
    'package' => 'Net_DIME',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'SOAP',
    'version' => '0.8.1',
  ),
  3 => 'stable',
), array (
  'version' => '0.3',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<package version="1.0">
  <name>Net_DIME</name>
  <summary>The PEAR::Net_DIME class implements DIME encoding</summary>
  <description>This is the initial independent release of the Net_DIME package.
Provides an implementation of DIME as defined at
http://search.ietf.org/internet-drafts/draft-nielsen-dime-02.txt</description>
  <maintainers>
    <maintainer>
      <user>shane</user>
      <name>Shane Caraveo</name>
      <email>shane@caraveo.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>0.3</version>
    <date>2002-07-07</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>Updated support for the DIME spec from 17 June 2002</notes>
    <filelist>
      <file role="php" baseinstalldir="Net" md5sum="32739790aca53ae2efea2dcae2ecc832" name="DIME.php"/>
      <file role="test" baseinstalldir="Net" md5sum="725cf93747c7be8d8208fe9310717b0a" name="test\\dime_message_test.php"/>
      <file role="test" baseinstalldir="Net" md5sum="8306728f0d5ca4e3c58a8cd9093ecdfb" name="test\\dime_record_test.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>0.2.1</version>
      <date>2002-05-12</date>
      <state>beta</state>
      <notes>Change names from DIME_* to Net_DIME_*.
</notes>
    </release>
    <release>
      <version>0.2</version>
      <date>2002-05-12</date>
      <state>beta</state>
      <notes>Some of the code probably needs to be PEAR-ified a bit more.
This needs to be integrated with streams, if there is anything needed to do that.
More testing needs to be done, but it encodes/decodes it\'s own messages.
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
    'rel' => 'ge',
    'version' => '1.0.12',
    'name' => 'Net_URL',
    'channel' => 'pear.php.net',
    'package' => 'Net_URL',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'HTTP_Request',
    'version' => '1.2.4',
  ),
  3 => 'stable',
), array (
  'version' => '1.0.14',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Net_URL</name>
  <summary>Easy parsing of Urls</summary>
  <description>Provides easy parsing of URLs and their constituent parts.</description>
  <maintainers>
    <maintainer>
      <user>richard</user>
      <name>Richard heyes</name>
      <email>richard@php.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.0.14</version>
    <date>2004-06-19</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>Whitespace</notes>
    <filelist>
      <file role="php" baseinstalldir="Net" name="URL.php"/>
      <file role="doc" baseinstalldir="Net" name="docs/example.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0.13</version>
      <date>2004-06-05</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Fix bug 1558
</notes>
    </release>
    <release>
      <version>1.0.12</version>
      <date>2004-05-08</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Bug fixes release (#704 and #1036)
</notes>
    </release>
    <release>
      <version>1.0.11</version>
      <date>2004-01-17</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Bug fixes release (#83 and #471)
</notes>
    </release>
    <release>
      <version>1.0.10</version>
      <date>2002-04-06</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>Be more flexible in what constitutes a scheme
</notes>
    </release>
    <release>
      <version>1.0.9</version>
      <date>2002-04-05</date>
      <state>stable</state>
      <notes>Fix couple of absolute URL bugs.
</notes>
    </release>
    <release>
      <version>1.0.8</version>
      <date>2002-03-06</date>
      <state>stable</state>
      <notes>Various bugs. Remove auto setting of default url to \'/\' if a url is supplied
to the constructor. May cause BC issues.
</notes>
    </release>
    <release>
      <version>1.0.7</version>
      <date>2002-12-07</date>
      <state>stable</state>
      <notes>Added method to resolve URL paths of //, ../ and ./
</notes>
    </release>
    <release>
      <version>1.0.6</version>
      <date>2002-12-07</date>
      <state>stable</state>
      <notes>Make usage of [] optional
</notes>
    </release>
    <release>
      <version>1.0.5</version>
      <date>2002-11-14</date>
      <state>stable</state>
      <notes>Allow for URLS such as ...?foo
</notes>
    </release>
    <release>
      <version>1.0.4</version>
      <date>2002-07-27</date>
      <state>stable</state>
      <notes>License change

</notes>
    </release>
    <release>
      <version>1.0.3</version>
      <date>2002-06-20</date>
      <state>stable</state>
      <notes>Now uses HTTP_HOST if available.
</notes>
    </release>
    <release>
      <version>1.0.2</version>
      <date>2002-04-28</date>
      <state>stable</state>
      <notes>updated to fix a minor irritation when running on windows
</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2002-04-28</date>
      <state>stable</state>
      <notes>Maintenance release. Bugs fixed with path detection and defaults.
</notes>
    </release>
    <release>
      <version>1.0</version>
      <date>2002-02-17</date>
      <state>stable</state>
      <notes>This is the initial release of the Net_URL package.
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Net_URL-1.0.14',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 => 
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0.2',
    'name' => 'Net_Socket',
    'channel' => 'pear.php.net',
    'package' => 'Net_Socket',
  ),
  2 => 
  array (
    'channel' => 'pear.php.net',
    'package' => 'HTTP_Request',
    'version' => '1.2.4',
  ),
  3 => 'stable',
), array (
  'version' => '1.0.5',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Net_Socket</name>
  <summary>Network Socket Interface</summary>
  <description>Net_Socket is a class interface to TCP sockets.  It provides blocking
and non-blocking operation, with different reading and writing modes
(byte-wise, block-wise, line-wise and special formats like network
byte-order ip addresses).</description>
  <maintainers>
    <maintainer>
      <user>ssb</user>
      <name>Stig S??ther Bakken</name>
      <email>stig@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>chagenbu</user>
      <name>Chuck Hagenbuch</name>
      <email>chuck@horde.org</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.0.5</version>
    <date>2005-01-11</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>Don\'t rely on gethostbyname() for error checking (Bug #3100).</notes>
    <filelist>
      <file role="php" baseinstalldir="Net" name="Socket.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0.0</version>
      <date>2002-04-01</date>
      <state>stable</state>
      <notes>First independent release of Net_Socket.
</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2002-04-04</date>
      <state>stable</state>
      <notes>Touch up error handling.
</notes>
    </release>
    <release>
      <version>1.0.2</version>
      <date>2004-04-26</date>
      <state>stable</state>
      <notes>Fixes for several longstanding bugs. Allow setting of stream
context. Correctly read lines that only end in \\n. Suppress
PHP warnings.
</notes>
    </release>
    <release>
      <version>1.0.3</version>
      <date>2004-12-08</date>
      <state>stable</state>
      <notes>Optimize away some duplicate is_resource() calls.
Better solution for eof() on blocking sockets [#1427].
Add select() implementation [#1428].
</notes>
    </release>
    <release>
      <version>1.0.4</version>
      <date>2004-12-13</date>
      <state>stable</state>
      <notes>Restore support for unix sockets (Bug #2961).
</notes>
    </release>
    <release>
      <version>1.0.4</version>
      <date>2005-01-11</date>
      <state>stable</state>
      <notes>Don\'t rely on gethostbyname() for error checking (Bug #3100).
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Net_Socket-1.0.5',
));
$p1 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'SOAP-0.8.1.tgz';
$p2 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Mail_Mime-1.2.1.tgz';
$p3 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'HTTP_Request-1.2.4.tgz';
$p4 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Net_URL-1.0.14.tgz';
$p5 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Net_DIME-0.3.tgz';
$p6 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Net_Socket-1.0.5.tgz';

for ($i = 1; $i <= 6; $i++) {
    $packages[] = ${"p$i"};
}
$dl = &new PEAR_Installer($fakelog);
$config = &test_PEAR_Config::singleton($temp_path . '/pear.ini', $temp_path . '/pear.conf');

test_PEAR_Dependency2::singleton($config);
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setExtensions(array('pcre' => '1.0'));
require_once 'PEAR/Command/Install.php';
class test_PEAR_Command_Install extends PEAR_Command_Install
{
    function &getDownloader()
    {
        if (!isset($GLOBALS['__Stupid_php4_a'])) {
            $GLOBALS['__Stupid_php4_a'] = &new test_PEAR_Downloader($this->ui, array(), $this->config);
        }
        return $GLOBALS['__Stupid_php4_a'];
    }

    function &getInstaller()
    {
        if (!isset($GLOBALS['__Stupid_php4_b'])) {
            $GLOBALS['__Stupid_php4_b'] = &new test_PEAR_Installer($this->ui, array(), $this->config);
        }
        return $GLOBALS['__Stupid_php4_b'];
    }
}
$command = &new test_PEAR_Command_Install($fakelog, $config);
$command->run('install', array(), $packages);
$phpunit->assertNoErrors('after install');
$fakelog->getLog();
$paramnames = array('Mail_Mime', 'SOAP', 'Net_DIME', 'HTTP_Request', 'Net_URL', 'Net_Socket');
$reg = &$config->getRegistry();
$params = array();
foreach ($paramnames as $name) {
    $params[] = &$reg->getPackage($name);
}
$dl->sortPackagesForUninstall($params);
$phpunit->assertEquals('Mail_Mime', $params[5]->getPackage(), '5');
$phpunit->assertEquals('Net_DIME', $params[4]->getPackage(), '4');
$phpunit->assertEquals('Net_URL', $params[3]->getPackage(), '3');
$phpunit->assertEquals('Net_Socket', $params[2]->getPackage(), '2');
$phpunit->assertEquals('HTTP_Request', $params[1]->getPackage(), '1');
$phpunit->assertEquals('SOAP', $params[0]->getPackage(), '0');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
