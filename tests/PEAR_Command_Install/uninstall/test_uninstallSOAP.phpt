--TEST--
uninstall command - real-world example (uninstall the SOAP package)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
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
$p1 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'SOAP-0.8.1.tgz';
$p2 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Mail_Mime-1.2.1.tgz';
$p3 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'HTTP_Request-1.2.4.tgz';
$p4 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Net_URL-1.0.14.tgz';
$p5 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Net_DIME-0.3.tgz';
$p6 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Net_Socket-1.0.5.tgz';

for ($i = 1; $i <= 6; $i++) {
    $packages[] = ${"p$i"};
}

$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setExtensions(array('pcre' => '1.0'));
$command->run('install', array(), $packages);
$phpunit->assertNoErrors('after install');
$fakelog->getLog();
$paramnames = array('Mail_Mime', 'SOAP', 'Net_DIME', 'HTTP_Request', 'Net_URL', 'Net_Socket');
$command->run('uninstall', array(), $paramnames);
$phpunit->assertNoErrors('after uninstall');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Base.php',
  ),
  1 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Client.php',
  ),
  2 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Disco.php',
  ),
  3 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Fault.php',
  ),
  4 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Parser.php',
  ),
  5 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server.php',
  ),
  6 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport.php',
  ),
  7 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Value.php',
  ),
  8 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'WSDL.php',
  ),
  9 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'attachment.php',
  ),
  10 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'client.php',
  ),
  11 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'com_client.php',
  ),
  12 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'disco_server.php',
  ),
  13 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_client.php',
  ),
  14 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_gateway.php',
  ),
  15 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_gateway.php',
  ),
  16 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_server.php',
  ),
  17 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_server.php',
  ),
  18 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_server.php',
  ),
  19 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_types.php',
  ),
  20 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server.php',
  ),
  21 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server2.php',
  ),
  22 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'smtp.php',
  ),
  23 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'stockquote.php',
  ),
  24 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_client.php',
  ),
  25 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_daemon.pl',
  ),
  26 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_server.php',
  ),
  27 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'wsdl_client.php',
  ),
  28 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'genproxy.php',
  ),
  29 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'HTTP.php',
  ),
  30 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'SMTP.php',
  ),
  31 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  32 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email.php',
  ),
  33 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email_Gateway.php',
  ),
  34 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  35 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'dateTime.php',
  ),
  36 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'duration.php',
  ),
  37 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'hexBinary.php',
  ),
  38 => 
  array (
    0 => 2,
    1 => 'about to commit 38 file operations',
  ),
  39 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Base.php',
  ),
  40 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Client.php',
  ),
  41 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Disco.php',
  ),
  42 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Fault.php',
  ),
  43 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Parser.php',
  ),
  44 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server.php',
  ),
  45 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport.php',
  ),
  46 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Value.php',
  ),
  47 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'WSDL.php',
  ),
  48 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'attachment.php',
  ),
  49 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'client.php',
  ),
  50 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'com_client.php',
  ),
  51 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'disco_server.php',
  ),
  52 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_client.php',
  ),
  53 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_gateway.php',
  ),
  54 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_gateway.php',
  ),
  55 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_server.php',
  ),
  56 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_server.php',
  ),
  57 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_server.php',
  ),
  58 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_types.php',
  ),
  59 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server.php',
  ),
  60 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server2.php',
  ),
  61 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'smtp.php',
  ),
  62 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'stockquote.php',
  ),
  63 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_client.php',
  ),
  64 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_daemon.pl',
  ),
  65 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_server.php',
  ),
  66 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'wsdl_client.php',
  ),
  67 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'genproxy.php',
  ),
  68 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'HTTP.php',
  ),
  69 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'SMTP.php',
  ),
  70 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  71 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email.php',
  ),
  72 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email_Gateway.php',
  ),
  73 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  74 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'dateTime.php',
  ),
  75 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'duration.php',
  ),
  76 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'hexBinary.php',
  ),
  77 => 
  array (
    0 => 2,
    1 => 'successfully committed 38 file operations',
  ),
  78 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'tools',
  ),
  79 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type',
  ),
  80 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport',
  ),
  81 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server',
  ),
  82 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP',
  ),
  83 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example',
  ),
  84 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP',
  ),
  85 => 
  array (
    0 => 2,
    1 => 'about to commit 7 file operations',
  ),
  86 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'tools',
  ),
  87 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Type',
  ),
  88 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport',
  ),
  89 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'Server',
  ),
  90 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'SOAP',
  ),
  91 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP' . DIRECTORY_SEPARATOR . 'example',
  ),
  92 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'SOAP',
  ),
  93 => 
  array (
    0 => 2,
    1 => 'successfully committed 7 file operations',
  ),
  94 => 
  array (
    'info' => 'uninstall ok: channel://pear.php.net/SOAP-0.8.1',
    'cmd' => 'uninstall',
  ),
  95 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'Request.php',
  ),
  96 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'Request' . DIRECTORY_SEPARATOR . 'Listener.php',
  ),
  97 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  98 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'download-progress.php',
  ),
  99 => 
  array (
    0 => 2,
    1 => 'about to commit 4 file operations',
  ),
  100 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'Request.php',
  ),
  101 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'Request' . DIRECTORY_SEPARATOR . 'Listener.php',
  ),
  102 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  103 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'download-progress.php',
  ),
  104 => 
  array (
    0 => 2,
    1 => 'successfully committed 4 file operations',
  ),
  105 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'Request',
  ),
  106 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP',
  ),
  107 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs',
  ),
  108 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request',
  ),
  109 => 
  array (
    0 => 2,
    1 => 'about to commit 4 file operations',
  ),
  110 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'Request',
  ),
  111 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'HTTP',
  ),
  112 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs',
  ),
  113 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'HTTP_Request',
  ),
  114 => 
  array (
    0 => 2,
    1 => 'successfully committed 4 file operations',
  ),
  115 => 
  array (
    'info' => 'uninstall ok: channel://pear.php.net/HTTP_Request-1.2.4',
    'cmd' => 'uninstall',
  ),
  116 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net' . DIRECTORY_SEPARATOR . 'Socket.php',
  ),
  117 => 
  array (
    0 => 2,
    1 => 'about to commit 1 file operations',
  ),
  118 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net' . DIRECTORY_SEPARATOR . 'Socket.php',
  ),
  119 => 
  array (
    0 => 2,
    1 => 'successfully committed 1 file operations',
  ),
  120 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net',
  ),
  121 => 
  array (
    0 => 2,
    1 => 'about to commit 1 file operations',
  ),
  122 => 
  array (
    0 => 2,
    1 => 'successfully committed 1 file operations',
  ),
  123 => 
  array (
    'info' => 'uninstall ok: channel://pear.php.net/Net_Socket-1.0.5',
    'cmd' => 'uninstall',
  ),
  124 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net' . DIRECTORY_SEPARATOR . 'URL.php',
  ),
  125 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  126 => 
  array (
    0 => 2,
    1 => 'about to commit 2 file operations',
  ),
  127 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net' . DIRECTORY_SEPARATOR . 'URL.php',
  ),
  128 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  129 => 
  array (
    0 => 2,
    1 => 'successfully committed 2 file operations',
  ),
  130 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net',
  ),
  131 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs',
  ),
  132 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'Net_URL',
  ),
  133 => 
  array (
    0 => 2,
    1 => 'about to commit 3 file operations',
  ),
  134 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs',
  ),
  135 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'Net_URL',
  ),
  136 => 
  array (
    0 => 2,
    1 => 'successfully committed 3 file operations',
  ),
  137 => 
  array (
    'info' => 'uninstall ok: channel://pear.php.net/Net_URL-1.0.14',
    'cmd' => 'uninstall',
  ),
  138 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net' . DIRECTORY_SEPARATOR . 'DIME.php',
  ),
  139 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_message_test.php',
  ),
  140 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_record_test.php',
  ),
  141 => 
  array (
    0 => 2,
    1 => 'about to commit 3 file operations',
  ),
  142 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net' . DIRECTORY_SEPARATOR . 'DIME.php',
  ),
  143 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_message_test.php',
  ),
  144 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_record_test.php',
  ),
  145 => 
  array (
    0 => 2,
    1 => 'successfully committed 3 file operations',
  ),
  146 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test',
  ),
  147 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME',
  ),
  148 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net',
  ),
  149 => 
  array (
    0 => 2,
    1 => 'about to commit 3 file operations',
  ),
  150 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test',
  ),
  151 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'Net_DIME',
  ),
  152 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Net',
  ),
  153 => 
  array (
    0 => 2,
    1 => 'successfully committed 3 file operations',
  ),
  154 => 
  array (
    'info' => 'uninstall ok: channel://pear.php.net/Net_DIME-0.3',
    'cmd' => 'uninstall',
  ),
  155 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . 'mime.php',
  ),
  156 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . 'mimeDecode.php',
  ),
  157 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . 'mimePart.php',
  ),
  158 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.dtd',
  ),
  159 => 
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.xsl',
  ),
  160 => 
  array (
    0 => 2,
    1 => 'about to commit 5 file operations',
  ),
  161 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . 'mime.php',
  ),
  162 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . 'mimeDecode.php',
  ),
  163 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . 'mimePart.php',
  ),
  164 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.dtd',
  ),
  165 => 
  array (
    0 => 3,
    1 => '+ rm ' . $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.xsl',
  ),
  166 => 
  array (
    0 => 2,
    1 => 'successfully committed 5 file operations',
  ),
  167 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail',
  ),
  168 => 
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Mail_Mime',
  ),
  169 => 
  array (
    0 => 2,
    1 => 'about to commit 2 file operations',
  ),
  170 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'Mail',
  ),
  171 => 
  array (
    0 => 3,
    1 => '+ rmdir ' . $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Mail_Mime',
  ),
  172 => 
  array (
    0 => 2,
    1 => 'successfully committed 2 file operations',
  ),
  173 => 
  array (
    'info' => 'uninstall ok: channel://pear.php.net/Mail_Mime-1.2.1',
    'cmd' => 'uninstall',
  ),
), $fakelog->getLog(), 'log after uninstall');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
