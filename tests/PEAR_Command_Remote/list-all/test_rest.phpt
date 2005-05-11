--TEST--
list-all command (REST-based channel)
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
$reg = &$config->getRegistry();
$pf = new PEAR_PackageFile_v1;
$pf->setConfig($config);
$pf->setPackage('Archive_Zip');
$pf->setSummary('foo');
$pf->setDate(date('Y-m-d'));
$pf->setDescription('foo');
$pf->setVersion('1.0.0');
$pf->setState('stable');
$pf->setLicense('PHP License');
$pf->setNotes('foo');
$pf->addMaintainer('lead', 'cellog', 'Greg', 'cellog@php.net');
$pf->addFile('', 'foo.dat', array('role' => 'data'));
$pf->addPhpDep('4.0.0', 'ge');
$pf->validate();
$phpunit->assertNoErrors('setup');
$reg->addPackage2($pf);
$chan = $reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);
$pearweb->addRESTConfig("http://pear.php.net/rest/p/packages.xml", '<?xml version="1.0" ?>
<a xmlns="http://pear.php.net/dtd/rest.allpackages"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allpackages
    http://pear.php.net/dtd/rest.allpackages.xsd">
<c>pear.php.net</c>
 <p>Archive_Tar</p>
 <p>Archive_Zip</p>
 <p>Auth</p>
 <p>Auth_Enterprise</p>
 <p>Auth_HTTP</p>
 <p>Auth_PrefManager</p>
 <p>Auth_PrefManager2</p>
 <p>Auth_RADIUS</p>
 <p>Auth_SASL</p>
 <p>Benchmark</p>
 <p>Cache</p>
 <p>Cache_Lite</p>
 <p>Calendar</p>
 <p>Config</p>
 <p>Console_Color</p>
 <p>Console_Getargs</p>
 <p>Console_Getopt</p>
 <p>Console_ProgressBar</p>
 <p>Console_Table</p>
 <p>Contact_AddressBook</p>
 <p>Contact_Vcard_Build</p>
 <p>Contact_Vcard_Parse</p>
 <p>Crypt_Blowfish</p>
 <p>Crypt_CBC</p>
 <p>Crypt_CHAP</p>
 <p>Crypt_Crypt</p>
 <p>Crypt_HMAC</p>
 <p>Crypt_RC4</p>
 <p>Crypt_RSA</p>
 <p>Crypt_Xtea</p>
 <p>Date</p>
 <p>Date_Holidays</p>
 <p>DB</p>
 <p>DBA</p>
 <p>DBA_Relational</p>
 <p>DB_ado</p>
 <p>DB_DataObject</p>
 <p>DB_DataObject_FormBuilder</p>
 <p>DB_ldap</p>
 <p>DB_ldap2</p>
 <p>DB_NestedSet</p>
 <p>DB_odbtp</p>
 <p>DB_Pager</p>
 <p>DB_QueryTool</p>
 <p>DB_Sqlite_Tools</p>
 <p>DB_Table</p>
 <p>Event_Dispatcher</p>
 <p>File</p>
 <p>File_Archive</p>
 <p>File_Bittorrent</p>
 <p>File_DICOM</p>
 <p>File_DNS</p>
 <p>File_Find</p>
 <p>File_Fstab</p>
 <p>File_Gettext</p>
 <p>File_HtAccess</p>
 <p>File_IMC</p>
 <p>File_Ogg</p>
 <p>File_Passwd</p>
 <p>File_PDF</p>
 <p>File_SearchReplace</p>
 <p>File_SMBPasswd</p>
 <p>FSM</p>
 <p>Games_Chess</p>
 <p>Genealogy_Gedcom</p>
 <p>Gtk_FileDrop</p>
 <p>Gtk_MDB_Designer</p>
 <p>Gtk_ScrollingLabel</p>
 <p>Gtk_Styled</p>
 <p>Gtk_VarDump</p>
 <p>HTML_BBCodeParser</p>
 <p>HTML_Common</p>
 <p>HTML_Common2</p>
 <p>HTML_Crypt</p>
 <p>HTML_CSS</p>
 <p>HTML_Form</p>
 <p>HTML_Javascript</p>
 <p>HTML_Menu</p>
 <p>HTML_Page</p>
 <p>HTML_Page2</p>
 <p>HTML_Progress</p>
 <p>HTML_Progress2</p>
 <p>HTML_QuickForm</p>
 <p>HTML_QuickForm_Controller</p>
 <p>HTML_QuickForm_SelectFilter</p>
 <p>HTML_Select</p>
 <p>HTML_Select_Common</p>
 <p>HTML_Table</p>
 <p>HTML_Table_Matrix</p>
 <p>HTML_Template_Flexy</p>
 <p>HTML_Template_IT</p>
 <p>HTML_Template_PHPLIB</p>
 <p>HTML_Template_Sigma</p>
 <p>HTML_Template_Xipe</p>
 <p>HTML_TreeMenu</p>
 <p>HTTP</p>
 <p>HTTP_Client</p>
 <p>HTTP_Download</p>
 <p>HTTP_Header</p>
 <p>HTTP_Request</p>
 <p>HTTP_Server</p>
 <p>HTTP_Session</p>
 <p>HTTP_Session2</p>
 <p>HTTP_SessionServer</p>
 <p>HTTP_Upload</p>
 <p>HTTP_WebDAV_Client</p>
 <p>HTTP_WebDAV_Server</p>
 <p>I18N</p>
 <p>I18Nv2</p>
 <p>I18N_UnicodeString</p>
 <p>Image_Barcode</p>
 <p>Image_Color</p>
 <p>Image_GIS</p>
 <p>Image_Graph</p>
 <p>Image_GraphViz</p>
 <p>Image_IPTC</p>
 <p>Image_Remote</p>
 <p>Image_Text</p>
 <p>Image_Tools</p>
 <p>Image_Transform</p>
 <p>Image_XBM</p>
 <p>Inline_C</p>
 <p>LiveUser</p>
 <p>LiveUser_Admin</p>
 <p>Log</p>
 <p>Mail</p>
 <p>Mail_IMAP</p>
 <p>Mail_IMAPv2</p>
 <p>Mail_Mbox</p>
 <p>Mail_Mime</p>
 <p>Mail_Queue</p>
 <p>Math_Basex</p>
 <p>Math_BinaryUtils</p>
 <p>Math_Complex</p>
 <p>Math_Fibonacci</p>
 <p>Math_Fraction</p>
 <p>Math_Histogram</p>
 <p>Math_Integer</p>
 <p>Math_Matrix</p>
 <p>Math_Numerical_RootFinding</p>
 <p>Math_Quaternion</p>
 <p>Math_RPN</p>
 <p>Math_Stats</p>
 <p>Math_TrigOp</p>
 <p>Math_Vector</p>
 <p>MDB</p>
 <p>MDB2</p>
 <p>MDB2_Schema</p>
 <p>MDB_QueryTool</p>
 <p>Message</p>
 <p>MIME_Type</p>
 <p>MP3_ID</p>
 <p>MP3_Playlist</p>
 <p>Net_CheckIP</p>
 <p>Net_Curl</p>
 <p>Net_Cyrus</p>
 <p>Net_Dict</p>
 <p>Net_Dig</p>
 <p>Net_DIME</p>
 <p>Net_DNS</p>
 <p>Net_DNSBL</p>
 <p>Net_Finger</p>
 <p>Net_FTP</p>
 <p>Net_FTP2</p>
 <p>Net_GameServerQuery</p>
 <p>Net_Geo</p>
 <p>Net_GeoIP</p>
 <p>Net_HL7</p>
 <p>Net_Ident</p>
 <p>Net_IDNA</p>
 <p>Net_IMAP</p>
 <p>Net_IPv4</p>
 <p>Net_IPv6</p>
 <p>Net_IRC</p>
 <p>Net_LDAP</p>
 <p>Net_LMTP</p>
 <p>Net_Monitor</p>
 <p>Net_NNTP</p>
 <p>Net_Ping</p>
 <p>Net_POP3</p>
 <p>Net_Portscan</p>
 <p>Net_Server</p>
 <p>Net_Sieve</p>
 <p>Net_SmartIRC</p>
 <p>Net_SMS</p>
 <p>Net_SMTP</p>
 <p>Net_Socket</p>
 <p>Net_Traceroute</p>
 <p>Net_URL</p>
 <p>Net_UserAgent_Detect</p>
 <p>Net_UserAgent_Mobile</p>
 <p>Net_Whois</p>
 <p>Numbers_Roman</p>
 <p>Numbers_Words</p>
 <p>OLE</p>
 <p>Pager</p>
 <p>Pager_Sliding</p>
 <p>Payment_Clieop</p>
 <p>Payment_DTA</p>
 <p>Payment_Process</p>
 <p>PEAR</p>
 <p>PEAR_Delegator</p>
 <p>PEAR_ErrorStack</p>
 <p>PEAR_Frontend_Gtk</p>
 <p>PEAR_Frontend_Web</p>
 <p>PEAR_Info</p>
 <p>PEAR_PackageFileManager</p>
 <p>PEAR_PackageFileManager_GUI_Gtk</p>
 <p>PHPDoc</p>
 <p>PhpDocumentor</p>
 <p>PHPUnit</p>
 <p>PHPUnit2</p>
 <p>PHP_Archive</p>
 <p>PHP_Beautifier</p>
 <p>PHP_Compat</p>
 <p>PHP_CompatInfo</p>
 <p>PHP_Fork</p>
 <p>PHP_Parser</p>
 <p>RDF</p>
 <p>RDF_N3</p>
 <p>RDF_NTriple</p>
 <p>RDF_RDQL</p>
 <p>Science_Chemistry</p>
 <p>Search_Mnogosearch</p>
 <p>Services_Amazon</p>
 <p>Services_Delicious</p>
 <p>Services_Ebay</p>
 <p>Services_ExchangeRates</p>
 <p>Services_Google</p>
 <p>Services_Technorati</p>
 <p>Services_Trackback</p>
 <p>Services_Weather</p>
 <p>Services_Yahoo</p>
 <p>SOAP</p>
 <p>SOAP_Interop</p>
 <p>Spreadsheet_Excel_Writer</p>
 <p>SQL_Parser</p>
 <p>Stream_SHM</p>
 <p>Stream_Var</p>
 <p>Structures_DataGrid</p>
 <p>Structures_Graph</p>
 <p>System_Command</p>
 <p>System_Mount</p>
 <p>System_ProcWatch</p>
 <p>System_Socket</p>
 <p>Text_CAPTCHA</p>
 <p>Text_Diff</p>
 <p>Text_Figlet</p>
 <p>Text_Highlighter</p>
 <p>Text_Huffman</p>
 <p>Text_Password</p>
 <p>Text_Statistics</p>
 <p>Text_TeXHyphen</p>
 <p>Text_Wiki</p>
 <p>Translation</p>
 <p>Translation2</p>
 <p>Tree</p>
 <p>UDDI</p>
 <p>Validate</p>
 <p>Var_Dump</p>
 <p>VersionControl_SVN</p>
 <p>VFS</p>
 <p>XML_Beautifier</p>
 <p>XML_CSSML</p>
 <p>XML_DTD</p>
 <p>XML_FastCreate</p>
 <p>XML_fo2pdf</p>
 <p>XML_FOAF</p>
 <p>XML_HTMLSax</p>
 <p>XML_HTMLSax3</p>
 <p>XML_image2svg</p>
 <p>XML_Indexing</p>
 <p>XML_MXML</p>
 <p>XML_NITF</p>
 <p>XML_Parser</p>
 <p>XML_RDDL</p>
 <p>XML_RPC</p>
 <p>XML_RSS</p>
 <p>XML_SaxFilters</p>
 <p>XML_Serializer</p>
 <p>XML_sql2xml</p>
 <p>XML_Statistics</p>
 <p>XML_SVG</p>
 <p>XML_svg2image</p>
 <p>XML_Transformer</p>
 <p>XML_Tree</p>
 <p>XML_Util</p>
 <p>XML_Wddx</p>
 <p>XML_XPath</p>
 <p>XML_XSLT_Wrapper</p>
 <p>XML_XUL</p>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/p/archive_tar/info.xml", '<?xml version="1.0"?>
<p xmlns="http://pear.php.net/dtd/rest.package"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.package
    http://pear.php.net/dtd/rest.package.xsd">
 <n>Archive_Tar</n>
 <c>pear.php.net</c>
 <ca xlink:href="/rest/c/File+Formats">File Formats</ca>
 <l>PHP License</l>
 <s>Tar file management class</s>
 <d>This class provides handling of tar files in PHP.
It supports creating, listing, extracting and adding to tar files.
Gzip support is available if PHP has the zlib extension built-in or
loaded. Bz2 compression is also supported with the bz2 extension loaded.</d>
 <r xlink:href="/rest/r/archive_tar"/>
</p>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/archive_tar/allreleases.xml", '<?xml version="1.0"?>
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
$pearweb->addRESTConfig("http://pear.php.net/rest/r/archive_tar/deps.1.3.1.txt", 'b:0;', 'text/plain');
$pearweb->addRESTConfig("http://pear.php.net/rest/p/archive_zip/info.xml", '<?xml version="1.0"?>
<p xmlns="http://pear.php.net/dtd/rest.package"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.package
    http://pear.php.net/dtd/rest.package.xsd">
 <n>Archive_Zip</n>
 <c>pear.php.net</c>
 <ca xlink:href="/rest/c/File+Formats">File Formats</ca>
 <l>PHP License</l>
 <s>Zip file management class</s>
 <d>This class provides handling of zip files in PHP.
It supports creating, listing, extracting and adding to zip files.</d>
 <r xlink:href="/rest/r/archive_zip"/>
</p>', 'text/xml');
$command->run('list-all', array(), array());
echo 'tests done';
?>
--EXPECT--
tests done
