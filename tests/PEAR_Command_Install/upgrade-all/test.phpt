--TEST--
upgrade-all command - real-world example from Bug #3388
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$chan = &$reg->getChannel('pear.php.net');
$chan->resetREST();
$reg->updateChannel($chan);
$chan = &$reg->getChannel('pecl.php.net');
$chan->resetREST();
$reg->updateChannel($chan);
$pearweb->addXmlrpcConfig("pecl.php.net", "package.listLatestReleases", array (
  0 => 'alpha',
), array (
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.listLatestReleases", array (
  0 => 'alpha',
), array (
  'Archive_Tar' =>
  array (
    'version' => '1.2',
    'state' => 'stable',
    'filesize' => 14792,
  ),
  'Auth' =>
  array (
    'version' => '1.3.0r3',
    'state' => 'beta',
    'filesize' => 34636,
  ),
  'Auth_HTTP' =>
  array (
    'version' => '2.1.4',
    'state' => 'stable',
    'filesize' => 7835,
  ),
  'Auth_PrefManager' =>
  array (
    'version' => '1.1.3',
    'state' => 'stable',
    'filesize' => 4729,
  ),
  'Auth_PrefManager2' =>
  array (
    'version' => '2.0.0dev1',
    'state' => 'alpha',
    'filesize' => 7987,
  ),
  'Auth_RADIUS' =>
  array (
    'version' => '1.0.4',
    'state' => 'stable',
    'filesize' => 8232,
  ),
  'Auth_SASL' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 5293,
  ),
  'Benchmark' =>
  array (
    'version' => '1.2.2',
    'state' => 'stable',
    'filesize' => 5966,
  ),
  'Cache' =>
  array (
    'version' => '1.5.4',
    'state' => 'stable',
    'filesize' => 30690,
  ),
  'Cache_Lite' =>
  array (
    'version' => '1.4.0',
    'state' => 'stable',
    'filesize' => 20046,
  ),
  'Calendar' =>
  array (
    'version' => '0.5.2',
    'state' => 'beta',
    'filesize' => 60164,
  ),
  'Config' =>
  array (
    'version' => '1.10.3',
    'state' => 'stable',
    'filesize' => 18349,
  ),
  'Console_Color' =>
  array (
    'version' => '0.0.3',
    'state' => 'beta',
    'filesize' => 5394,
  ),
  'Console_Getargs' =>
  array (
    'version' => '1.2.1',
    'state' => 'stable',
    'filesize' => 16199,
  ),
  'Console_Getopt' =>
  array (
    'version' => '1.2',
    'state' => 'stable',
    'filesize' => 3370,
  ),
  'Console_ProgressBar' =>
  array (
    'version' => '0.2',
    'state' => 'beta',
    'filesize' => 4271,
  ),
  'Console_Table' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 3319,
  ),
  'Contact_Vcard_Build' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 11747,
  ),
  'Contact_Vcard_Parse' =>
  array (
    'version' => '1.30',
    'state' => 'stable',
    'filesize' => 6814,
  ),
  'Crypt_CBC' =>
  array (
    'version' => '0.4',
    'state' => 'stable',
    'filesize' => 2938,
  ),
  'Crypt_CHAP' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 5437,
  ),
  'Crypt_HMAC' =>
  array (
    'version' => '0.9',
    'state' => 'beta',
    'filesize' => 1721,
  ),
  'Crypt_RC4' =>
  array (
    'version' => '1.0.2',
    'state' => 'stable',
    'filesize' => 1850,
  ),
  'Crypt_Xtea' =>
  array (
    'version' => '1.1.0RC4',
    'state' => 'beta',
    'filesize' => 9132,
  ),
  'Date' =>
  array (
    'version' => '1.4.3',
    'state' => 'stable',
    'filesize' => 42048,
  ),
  'Date_Holidays' =>
  array (
    'version' => '0.12.0',
    'state' => 'alpha',
    'filesize' => 25984,
  ),
  'DB' =>
  array (
    'version' => '1.6.8',
    'state' => 'stable',
    'filesize' => 92460,
  ),
  'DBA' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 12578,
  ),
  'DB_ado' =>
  array (
    'version' => '1.3',
    'state' => 'stable',
    'filesize' => 13686,
  ),
  'DB_DataObject' =>
  array (
    'version' => '1.7.2',
    'state' => 'stable',
    'filesize' => 45244,
  ),
  'DB_DataObject_FormBuilder' =>
  array (
    'version' => '0.11.1',
    'state' => 'beta',
    'filesize' => 35494,
  ),
  'DB_ldap' =>
  array (
    'version' => '1.1.0',
    'state' => 'stable',
    'filesize' => 8245,
  ),
  'DB_ldap2' =>
  array (
    'version' => '0.4',
    'state' => 'beta',
    'filesize' => 19260,
  ),
  'DB_NestedSet' =>
  array (
    'version' => '1.3.6',
    'state' => 'beta',
    'filesize' => 48291,
  ),
  'DB_odbtp' =>
  array (
    'version' => '1.0.2',
    'state' => 'stable',
    'filesize' => 12871,
  ),
  'DB_Pager' =>
  array (
    'version' => '0.7',
    'state' => 'stable',
    'filesize' => 3447,
  ),
  'DB_QueryTool' =>
  array (
    'version' => '0.11.1',
    'state' => 'stable',
    'filesize' => 31614,
  ),
  'DB_Sqlite_Tools' =>
  array (
    'version' => '0.1.3',
    'state' => 'alpha',
    'filesize' => 17706,
  ),
  'DB_Table' =>
  array (
    'version' => '0.23.0',
    'state' => 'beta',
    'filesize' => 28750,
  ),
  'File' =>
  array (
    'version' => '1.1.0RC5',
    'state' => 'beta',
    'filesize' => 14739,
  ),
  'File_Bittorrent' =>
  array (
    'version' => '0.1.5',
    'state' => 'beta',
    'filesize' => 30784,
  ),
  'File_DICOM' =>
  array (
    'version' => '0.3',
    'state' => 'beta',
    'filesize' => 26759,
  ),
  'File_Find' =>
  array (
    'version' => '0.3.1',
    'state' => 'beta',
    'filesize' => 4759,
  ),
  'File_Fstab' =>
  array (
    'version' => '2.0.1',
    'state' => 'stable',
    'filesize' => 6275,
  ),
  'File_Gettext' =>
  array (
    'version' => '0.3.3',
    'state' => 'beta',
    'filesize' => 4864,
  ),
  'File_HtAccess' =>
  array (
    'version' => '1.1.0',
    'state' => 'stable',
    'filesize' => 3020,
  ),
  'File_IMC' =>
  array (
    'version' => '0.3',
    'state' => 'beta',
    'filesize' => 20369,
  ),
  'File_Ogg' =>
  array (
    'version' => '0.1.2',
    'state' => 'alpha',
    'filesize' => 8412,
  ),
  'File_Passwd' =>
  array (
    'version' => '1.1.2',
    'state' => 'stable',
    'filesize' => 23282,
  ),
  'File_PDF' =>
  array (
    'version' => '0.0.1',
    'state' => 'beta',
    'filesize' => 25181,
  ),
  'File_SearchReplace' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 3839,
  ),
  'File_SMBPasswd' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 4917,
  ),
  'FSM' =>
  array (
    'version' => '1.2.2',
    'state' => 'stable',
    'filesize' => 5415,
  ),
  'Games_Chess' =>
  array (
    'version' => '0.8.1',
    'state' => 'alpha',
    'filesize' => 61245,
  ),
  'Gtk_FileDrop' =>
  array (
    'version' => '0.1.0',
    'state' => 'beta',
    'filesize' => 5678,
  ),
  'Gtk_MDB_Designer' =>
  array (
    'version' => '0.1',
    'state' => 'beta',
    'filesize' => 17775,
  ),
  'Gtk_ScrollingLabel' =>
  array (
    'version' => '0.3.0beta1',
    'state' => 'beta',
    'filesize' => 8565,
  ),
  'Gtk_Styled' =>
  array (
    'version' => '0.9.0beta1',
    'state' => 'beta',
    'filesize' => 12946,
  ),
  'Gtk_VarDump' =>
  array (
    'version' => '0.2.0',
    'state' => 'beta',
    'filesize' => 4759,
  ),
  'HTML_BBCodeParser' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 8821,
  ),
  'HTML_Common' =>
  array (
    'version' => '1.2.1',
    'state' => 'stable',
    'filesize' => 3637,
  ),
  'HTML_Crypt' =>
  array (
    'version' => '1.2.2',
    'state' => 'stable',
    'filesize' => 2620,
  ),
  'HTML_CSS' =>
  array (
    'version' => '0.3.4',
    'state' => 'beta',
    'filesize' => 16249,
  ),
  'HTML_Form' =>
  array (
    'version' => '1.1.1',
    'state' => 'stable',
    'filesize' => 13550,
  ),
  'HTML_Javascript' =>
  array (
    'version' => '1.1.0',
    'state' => 'stable',
    'filesize' => 8362,
  ),
  'HTML_Menu' =>
  array (
    'version' => '2.1.1',
    'state' => 'stable',
    'filesize' => 13106,
  ),
  'HTML_Page' =>
  array (
    'version' => '2.0.0RC2',
    'state' => 'beta',
    'filesize' => 10847,
  ),
  'HTML_Page2' =>
  array (
    'version' => '0.5.0beta',
    'state' => 'beta',
    'filesize' => 15467,
  ),
  'HTML_Progress' =>
  array (
    'version' => '1.2.0',
    'state' => 'stable',
    'filesize' => 359992,
  ),
  'HTML_QuickForm' =>
  array (
    'version' => '3.2.4pl1',
    'state' => 'stable',
    'filesize' => 93144,
  ),
  'HTML_QuickForm_Controller' =>
  array (
    'version' => '1.0.4',
    'state' => 'stable',
    'filesize' => 15631,
  ),
  'HTML_QuickForm_SelectFilter' =>
  array (
    'version' => '1.0.0RC1',
    'state' => 'beta',
    'filesize' => 2436,
  ),
  'HTML_Select' =>
  array (
    'version' => '1.2.1',
    'state' => 'beta',
    'filesize' => 3480,
  ),
  'HTML_Select_Common' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 5592,
  ),
  'HTML_Table' =>
  array (
    'version' => '1.5',
    'state' => 'stable',
    'filesize' => 6276,
  ),
  'HTML_Table_Matrix' =>
  array (
    'version' => '1.0.6',
    'state' => 'stable',
    'filesize' => 5331,
  ),
  'HTML_Template_Flexy' =>
  array (
    'version' => '1.2.1',
    'state' => 'stable',
    'filesize' => 117996,
  ),
  'HTML_Template_IT' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 18563,
  ),
  'HTML_Template_PHPLIB' =>
  array (
    'version' => '1.3.1',
    'state' => 'stable',
    'filesize' => 6299,
  ),
  'HTML_Template_Sigma' =>
  array (
    'version' => '1.1.2',
    'state' => 'stable',
    'filesize' => 26442,
  ),
  'HTML_Template_Xipe' =>
  array (
    'version' => '1.7.6',
    'state' => 'stable',
    'filesize' => 56848,
  ),
  'HTML_TreeMenu' =>
  array (
    'version' => '1.1.9',
    'state' => 'stable',
    'filesize' => 49213,
  ),
  'HTTP' =>
  array (
    'version' => '1.3.3',
    'state' => 'stable',
    'filesize' => 4574,
  ),
  'HTTP_Client' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 6396,
  ),
  'HTTP_Download' =>
  array (
    'version' => '1.0.0RC5',
    'state' => 'beta',
    'filesize' => 10431,
  ),
  'HTTP_Header' =>
  array (
    'version' => '1.1.2RC1',
    'state' => 'beta',
    'filesize' => 9568,
  ),
  'HTTP_Request' =>
  array (
    'version' => '1.2.4',
    'state' => 'stable',
    'filesize' => 13212,
  ),
  'HTTP_Server' =>
  array (
    'version' => '0.4.0',
    'state' => 'alpha',
    'filesize' => 6884,
  ),
  'HTTP_Session' =>
  array (
    'version' => '0.4',
    'state' => 'beta',
    'filesize' => 8587,
  ),
  'HTTP_SessionServer' =>
  array (
    'version' => '0.4.0',
    'state' => 'alpha',
    'filesize' => 8278,
  ),
  'HTTP_Upload' =>
  array (
    'version' => '0.9.1',
    'state' => 'stable',
    'filesize' => 9460,
  ),
  'HTTP_WebDAV_Client' =>
  array (
    'version' => '0.9.7',
    'state' => 'beta',
    'filesize' => 7504,
  ),
  'HTTP_WebDAV_Server' =>
  array (
    'version' => '0.99.1',
    'state' => 'beta',
    'filesize' => 24646,
  ),
  'I18N' =>
  array (
    'version' => '0.8.6',
    'state' => 'beta',
    'filesize' => 33383,
  ),
  'I18Nv2' =>
  array (
    'version' => '0.11.0',
    'state' => 'beta',
    'filesize' => 313762,
  ),
  'I18N_UnicodeString' =>
  array (
    'version' => '0.1.0',
    'state' => 'beta',
    'filesize' => 5433,
  ),
  'Image_Barcode' =>
  array (
    'version' => '0.5',
    'state' => 'stable',
    'filesize' => 7530,
  ),
  'Image_Color' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 7718,
  ),
  'Image_GIS' =>
  array (
    'version' => '1.1.1',
    'state' => 'stable',
    'filesize' => 5915,
  ),
  'Image_Graph' =>
  array (
    'version' => '0.2.1',
    'state' => 'alpha',
    'filesize' => 35948,
  ),
  'Image_GraphViz' =>
  array (
    'version' => '1.1.0',
    'state' => 'stable',
    'filesize' => 4452,
  ),
  'Image_IPTC' =>
  array (
    'version' => '1.0.2',
    'state' => 'stable',
    'filesize' => 3530,
  ),
  'Image_Remote' =>
  array (
    'version' => '1.0',
    'state' => 'beta',
    'filesize' => 4764,
  ),
  'Image_Text' =>
  array (
    'version' => '0.5.2beta2',
    'state' => 'beta',
    'filesize' => 12603,
  ),
  'Image_Tools' =>
  array (
    'version' => '0.2',
    'state' => 'alpha',
    'filesize' => 3468,
  ),
  'Image_Transform' =>
  array (
    'version' => '0.8',
    'state' => 'alpha',
    'filesize' => 16087,
  ),
  'Inline_C' =>
  array (
    'version' => '0.1',
    'state' => 'alpha',
    'filesize' => 4349,
  ),
  'LiveUser' =>
  array (
    'version' => '0.14.0',
    'state' => 'beta',
    'filesize' => 64484,
  ),
  'LiveUser_Admin' =>
  array (
    'version' => '0.1.0',
    'state' => 'beta',
    'filesize' => 35391,
  ),
  'Log' =>
  array (
    'version' => '1.8.7',
    'state' => 'stable',
    'filesize' => 32693,
  ),
  'Mail' =>
  array (
    'version' => '1.1.4',
    'state' => 'stable',
    'filesize' => 14548,
  ),
  'Mail_IMAP' =>
  array (
    'version' => '1.1.0RC2',
    'state' => 'beta',
    'filesize' => 23618,
  ),
  'Mail_Mbox' =>
  array (
    'version' => '0.3.0',
    'state' => 'beta',
    'filesize' => 5798,
  ),
  'Mail_Mime' =>
  array (
    'version' => '1.2.1',
    'state' => 'stable',
    'filesize' => 15268,
  ),
  'Mail_Queue' =>
  array (
    'version' => '1.1.3',
    'state' => 'stable',
    'filesize' => 14721,
  ),
  'Math_Basex' =>
  array (
    'version' => '0.3',
    'state' => 'stable',
    'filesize' => 5243,
  ),
  'Math_BinaryUtils' =>
  array (
    'version' => '0.2.0',
    'state' => 'alpha',
    'filesize' => 5966,
  ),
  'Math_Complex' =>
  array (
    'version' => '0.8.5',
    'state' => 'beta',
    'filesize' => 40930,
  ),
  'Math_Fibonacci' =>
  array (
    'version' => '0.8',
    'state' => 'stable',
    'filesize' => 22101,
  ),
  'Math_Fraction' =>
  array (
    'version' => '0.3.0',
    'state' => 'alpha',
    'filesize' => 3347,
  ),
  'Math_Histogram' =>
  array (
    'version' => '0.9.0',
    'state' => 'beta',
    'filesize' => 10754,
  ),
  'Math_Integer' =>
  array (
    'version' => '0.8',
    'state' => 'stable',
    'filesize' => 3952,
  ),
  'Math_Matrix' =>
  array (
    'version' => '0.8.5',
    'state' => 'beta',
    'filesize' => 14485,
  ),
  'Math_Numerical_RootFinding' =>
  array (
    'version' => '0.3.0',
    'state' => 'alpha',
    'filesize' => 10377,
  ),
  'Math_Quaternion' =>
  array (
    'version' => '0.7.1',
    'state' => 'beta',
    'filesize' => 6340,
  ),
  'Math_RPN' =>
  array (
    'version' => '1.1.1',
    'state' => 'stable',
    'filesize' => 5139,
  ),
  'Math_Stats' =>
  array (
    'version' => '0.9.0beta3',
    'state' => 'beta',
    'filesize' => 19917,
  ),
  'Math_TrigOp' =>
  array (
    'version' => '1.0',
    'state' => 'stable',
    'filesize' => 1613,
  ),
  'Math_Vector' =>
  array (
    'version' => '0.6.2',
    'state' => 'beta',
    'filesize' => 8768,
  ),
  'MDB' =>
  array (
    'version' => '1.3.0',
    'state' => 'stable',
    'filesize' => 218957,
  ),
  'MDB2' =>
  array (
    'version' => '2.0.0beta2',
    'state' => 'beta',
    'filesize' => 176377,
  ),
  'MDB_QueryTool' =>
  array (
    'version' => '0.11.1',
    'state' => 'stable',
    'filesize' => 31778,
  ),
  'Message' =>
  array (
    'version' => '0.6',
    'state' => 'beta',
    'filesize' => 12793,
  ),
  'MIME_Type' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 4404,
  ),
  'MP3_ID' =>
  array (
    'version' => '1.1.3',
    'state' => 'stable',
    'filesize' => 7719,
  ),
  'Net_CheckIP' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 1385,
  ),
  'Net_Curl' =>
  array (
    'version' => '1.0.1beta',
    'state' => 'beta',
    'filesize' => 3336,
  ),
  'Net_Cyrus' =>
  array (
    'version' => '0.3.1',
    'state' => 'beta',
    'filesize' => 5164,
  ),
  'Net_Dict' =>
  array (
    'version' => '1.0.3',
    'state' => 'stable',
    'filesize' => 5441,
  ),
  'Net_Dig' =>
  array (
    'version' => '0.1',
    'state' => 'stable',
    'filesize' => 3046,
  ),
  'Net_DIME' =>
  array (
    'version' => '0.3',
    'state' => 'beta',
    'filesize' => 6740,
  ),
  'Net_DNS' =>
  array (
    'version' => '1.00b2',
    'state' => 'beta',
    'filesize' => 25981,
  ),
  'Net_DNSBL' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 4196,
  ),
  'Net_Finger' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 1401,
  ),
  'Net_FTP' =>
  array (
    'version' => '1.3.0RC2',
    'state' => 'beta',
    'filesize' => 19442,
  ),
  'Net_GameServerQuery' =>
  array (
    'version' => '0.2.0',
    'state' => 'alpha',
    'filesize' => 15175,
  ),
  'Net_Geo' =>
  array (
    'version' => '1.0',
    'state' => 'stable',
    'filesize' => 6345,
  ),
  'Net_HL7' =>
  array (
    'version' => '0.1.0',
    'state' => 'alpha',
    'filesize' => 13228,
  ),
  'Net_Ident' =>
  array (
    'version' => '1.0',
    'state' => 'stable',
    'filesize' => 3183,
  ),
  'Net_IDNA' =>
  array (
    'version' => '0.5.0',
    'state' => 'beta',
    'filesize' => 41534,
  ),
  'Net_IMAP' =>
  array (
    'version' => '1.0.3',
    'state' => 'stable',
    'filesize' => 27192,
  ),
  'Net_IPv4' =>
  array (
    'version' => '1.2',
    'state' => 'stable',
    'filesize' => 4187,
  ),
  'Net_IPv6' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 2534,
  ),
  'Net_IRC' =>
  array (
    'version' => '0.0.7',
    'state' => 'beta',
    'filesize' => 92503,
  ),
  'Net_LDAP' =>
  array (
    'version' => '0.6.5',
    'state' => 'beta',
    'filesize' => 30302,
  ),
  'Net_LMTP' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 5596,
  ),
  'Net_Monitor' =>
  array (
    'version' => '0.1.0',
    'state' => 'beta',
    'filesize' => 11222,
  ),
  'Net_NNTP' =>
  array (
    'version' => '1.2.0',
    'state' => 'alpha',
    'filesize' => 19804,
  ),
  'Net_Ping' =>
  array (
    'version' => '2.4',
    'state' => 'stable',
    'filesize' => 8408,
  ),
  'Net_POP3' =>
  array (
    'version' => '1.3.5',
    'state' => 'stable',
    'filesize' => 9931,
  ),
  'Net_Portscan' =>
  array (
    'version' => '1.0.2',
    'state' => 'stable',
    'filesize' => 2553,
  ),
  'Net_Server' =>
  array (
    'version' => '0.12.0',
    'state' => 'alpha',
    'filesize' => 9226,
  ),
  'Net_Sieve' =>
  array (
    'version' => '1.1.1',
    'state' => 'stable',
    'filesize' => 9750,
  ),
  'Net_SmartIRC' =>
  array (
    'version' => '0.5.5p1',
    'state' => 'stable',
    'filesize' => 186781,
  ),
  'Net_SMS' =>
  array (
    'version' => '0.0.1',
    'state' => 'beta',
    'filesize' => 9866,
  ),
  'Net_SMTP' =>
  array (
    'version' => '1.2.6',
    'state' => 'stable',
    'filesize' => 9106,
  ),
  'Net_Socket' =>
  array (
    'version' => '1.0.5',
    'state' => 'stable',
    'filesize' => 4208,
  ),
  'Net_Traceroute' =>
  array (
    'version' => '0.21',
    'state' => 'alpha',
    'filesize' => 4987,
  ),
  'Net_URL' =>
  array (
    'version' => '1.0.14',
    'state' => 'stable',
    'filesize' => 5173,
  ),
  'Net_UserAgent_Detect' =>
  array (
    'version' => '2.0.1',
    'state' => 'stable',
    'filesize' => 8230,
  ),
  'Net_UserAgent_Mobile' =>
  array (
    'version' => '0.22.0',
    'state' => 'beta',
    'filesize' => 27873,
  ),
  'Net_Whois' =>
  array (
    'version' => '1.0',
    'state' => 'stable',
    'filesize' => 2921,
  ),
  'Numbers_Roman' =>
  array (
    'version' => '1.0.1',
    'state' => 'beta',
    'filesize' => 3778,
  ),
  'Numbers_Words' =>
  array (
    'version' => '0.12.0',
    'state' => 'beta',
    'filesize' => 40815,
  ),
  'OLE' =>
  array (
    'version' => '0.5',
    'state' => 'beta',
    'filesize' => 9058,
  ),
  'Pager' =>
  array (
    'version' => '2.2.6',
    'state' => 'stable',
    'filesize' => 20103,
  ),
  'Pager_Sliding' =>
  array (
    'version' => '1.6',
    'state' => 'stable',
    'filesize' => 10419,
  ),
  'Payment_Clieop' =>
  array (
    'version' => '0.1.1',
    'state' => 'stable',
    'filesize' => 5884,
  ),
  'Payment_DTA' =>
  array (
    'version' => '1.2.0',
    'state' => 'stable',
    'filesize' => 11211,
  ),
  'Payment_Process' =>
  array (
    'version' => '0.5.8',
    'state' => 'beta',
    'filesize' => 24136,
  ),
  'PEAR' =>
  array (
    'version' => '1.3.4',
    'state' => 'stable',
    'filesize' => 107207,
  ),
  'PEAR_Frontend_Gtk' =>
  array (
    'version' => '0.3',
    'state' => 'beta',
    'filesize' => 70008,
  ),
  'PEAR_Frontend_Web' =>
  array (
    'version' => '0.4',
    'state' => 'beta',
    'filesize' => 32386,
  ),
  'PEAR_Info' =>
  array (
    'version' => '1.6.0',
    'state' => 'stable',
    'filesize' => 6564,
  ),
  'PEAR_PackageFileManager' =>
  array (
    'version' => '1.2.1',
    'state' => 'stable',
    'filesize' => 38907,
  ),
  'PHPDoc' =>
  array (
    'version' => '0.1.0',
    'state' => 'beta',
    'filesize' => 88108,
  ),
  'PhpDocumentor' =>
  array (
    'version' => '1.3.0RC3',
    'state' => 'beta',
    'filesize' => 2711672,
  ),
  'PHPUnit' =>
  array (
    'version' => '1.2.2',
    'state' => 'stable',
    'filesize' => 21058,
  ),
  'PHPUnit2' =>
  array (
    'version' => '2.2.0beta4',
    'state' => 'beta',
    'filesize' => 38776,
  ),
  'PHP_Beautifier' =>
  array (
    'version' => '0.1.2',
    'state' => 'beta',
    'filesize' => 40424,
  ),
  'PHP_Compat' =>
  array (
    'version' => '1.3.1',
    'state' => 'stable',
    'filesize' => 34720,
  ),
  'PHP_CompatInfo' =>
  array (
    'version' => '1.0.0RC3',
    'state' => 'beta',
    'filesize' => 94804,
  ),
  'PHP_Fork' =>
  array (
    'version' => '0.2.0',
    'state' => 'beta',
    'filesize' => 10796,
  ),
  'RDF' =>
  array (
    'version' => '0.1.0alpha1',
    'state' => 'alpha',
    'filesize' => 56104,
  ),
  'RDF_N3' =>
  array (
    'version' => '0.1.0alpha1',
    'state' => 'alpha',
    'filesize' => 21216,
  ),
  'RDF_NTriple' =>
  array (
    'version' => '0.1.0alpha1',
    'state' => 'alpha',
    'filesize' => 1719,
  ),
  'RDF_RDQL' =>
  array (
    'version' => '0.1.0alpha1',
    'state' => 'alpha',
    'filesize' => 29102,
  ),
  'Science_Chemistry' =>
  array (
    'version' => '1.1.0',
    'state' => 'stable',
    'filesize' => 85881,
  ),
  'Search_Mnogosearch' =>
  array (
    'version' => '0.1.0',
    'state' => 'alpha',
    'filesize' => 18186,
  ),
  'Services_Amazon' =>
  array (
    'version' => '0.2.0',
    'state' => 'beta',
    'filesize' => 8086,
  ),
  'Services_Delicious' =>
  array (
    'version' => '0.2.0beta',
    'state' => 'beta',
    'filesize' => 5336,
  ),
  'Services_Ebay' =>
  array (
    'version' => '0.11.0',
    'state' => 'alpha',
    'filesize' => 82855,
  ),
  'Services_ExchangeRates' =>
  array (
    'version' => '0.5.0',
    'state' => 'beta',
    'filesize' => 9342,
  ),
  'Services_Google' =>
  array (
    'version' => '0.1.1',
    'state' => 'alpha',
    'filesize' => 2886,
  ),
  'Services_Weather' =>
  array (
    'version' => '1.3.1',
    'state' => 'stable',
    'filesize' => 44818,
  ),
  'SOAP' =>
  array (
    'version' => '0.8.1',
    'state' => 'beta',
    'filesize' => 69177,
  ),
  'SOAP_Interop' =>
  array (
    'version' => '0.8',
    'state' => 'beta',
    'filesize' => 32097,
  ),
  'Spreadsheet_Excel_Writer' =>
  array (
    'version' => '0.8',
    'state' => 'beta',
    'filesize' => 55402,
  ),
  'Stream_SHM' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 2579,
  ),
  'Stream_Var' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 4919,
  ),
  'Structures_DataGrid' =>
  array (
    'version' => '0.6.2',
    'state' => 'beta',
    'filesize' => 25297,
  ),
  'Structures_Graph' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 7060,
  ),
  'System_Command' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 5324,
  ),
  'System_Mount' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 3264,
  ),
  'System_ProcWatch' =>
  array (
    'version' => '0.4.2',
    'state' => 'beta',
    'filesize' => 12757,
  ),
  'System_Socket' =>
  array (
    'version' => '0.4.1',
    'state' => 'alpha',
    'filesize' => 13097,
  ),
  'Text_CAPTCHA' =>
  array (
    'version' => '0.1.2',
    'state' => 'alpha',
    'filesize' => 3827,
  ),
  'Text_Diff' =>
  array (
    'version' => '0.0.4',
    'state' => 'beta',
    'filesize' => 11566,
  ),
  'Text_Figlet' =>
  array (
    'version' => '0.8.0',
    'state' => 'beta',
    'filesize' => 22756,
  ),
  'Text_Highlighter' =>
  array (
    'version' => '0.6.2',
    'state' => 'beta',
    'filesize' => 55103,
  ),
  'Text_Huffman' =>
  array (
    'version' => '0.2.0',
    'state' => 'beta',
    'filesize' => 10561,
  ),
  'Text_Password' =>
  array (
    'version' => '1.0',
    'state' => 'stable',
    'filesize' => 3707,
  ),
  'Text_Statistics' =>
  array (
    'version' => '1.0',
    'state' => 'stable',
    'filesize' => 3949,
  ),
  'Text_TeXHyphen' =>
  array (
    'version' => '0.1.0',
    'state' => 'alpha',
    'filesize' => 148654,
  ),
  'Text_Wiki' =>
  array (
    'version' => '0.25.0',
    'state' => 'beta',
    'filesize' => 46425,
  ),
  'Translation' =>
  array (
    'version' => '1.2.6pl1',
    'state' => 'stable',
    'filesize' => 16252,
  ),
  'Translation2' =>
  array (
    'version' => '2.0.0beta6',
    'state' => 'beta',
    'filesize' => 47192,
  ),
  'Tree' =>
  array (
    'version' => '0.2.4',
    'state' => 'beta',
    'filesize' => 48824,
  ),
  'UDDI' =>
  array (
    'version' => '0.2.0alpha4',
    'state' => 'alpha',
    'filesize' => 6492,
  ),
  'Validate' =>
  array (
    'version' => '0.4.1',
    'state' => 'alpha',
    'filesize' => 37615,
  ),
  'Var_Dump' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 14843,
  ),
  'VersionControl_SVN' =>
  array (
    'version' => '0.3.0alpha1',
    'state' => 'alpha',
    'filesize' => 33829,
  ),
  'VFS' =>
  array (
    'version' => '0.0.4',
    'state' => 'beta',
    'filesize' => 21675,
  ),
  'XML_Beautifier' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 9854,
  ),
  'XML_CSSML' =>
  array (
    'version' => '1.1',
    'state' => 'stable',
    'filesize' => 10002,
  ),
  'XML_DTD' =>
  array (
    'version' => '0.4.2',
    'state' => 'alpha',
    'filesize' => 33790,
  ),
  'XML_FastCreate' =>
  array (
    'version' => '0.9',
    'state' => 'beta',
    'filesize' => 51853,
  ),
  'XML_fo2pdf' =>
  array (
    'version' => '0.98',
    'state' => 'stable',
    'filesize' => 6267,
  ),
  'XML_FOAF' =>
  array (
    'version' => '0.2',
    'state' => 'alpha',
    'filesize' => 136793,
  ),
  'XML_HTMLSax' =>
  array (
    'version' => '2.1.2',
    'state' => 'stable',
    'filesize' => 16099,
  ),
  'XML_HTMLSax3' =>
  array (
    'version' => '3.0.0RC1',
    'state' => 'beta',
    'filesize' => 19267,
  ),
  'XML_image2svg' =>
  array (
    'version' => '0.1',
    'state' => 'stable',
    'filesize' => 25805,
  ),
  'XML_Indexing' =>
  array (
    'version' => '0.3.5',
    'state' => 'alpha',
    'filesize' => 10936,
  ),
  'XML_MXML' =>
  array (
    'version' => '0.3.0',
    'state' => 'alpha',
    'filesize' => 32178,
  ),
  'XML_NITF' =>
  array (
    'version' => '1.0.1',
    'state' => 'stable',
    'filesize' => 6056,
  ),
  'XML_Parser' =>
  array (
    'version' => '1.2.4',
    'state' => 'stable',
    'filesize' => 10858,
  ),
  'XML_RDDL' =>
  array (
    'version' => '0.9',
    'state' => 'beta',
    'filesize' => 4424,
  ),
  'XML_RPC' =>
  array (
    'version' => '1.2.0RC6',
    'state' => 'beta',
    'filesize' => 18691,
  ),
  'XML_RSS' =>
  array (
    'version' => '0.9.2',
    'state' => 'stable',
    'filesize' => 3515,
  ),
  'XML_SaxFilters' =>
  array (
    'version' => '0.3.0',
    'state' => 'beta',
    'filesize' => 25577,
  ),
  'XML_Serializer' =>
  array (
    'version' => '0.14.1',
    'state' => 'beta',
    'filesize' => 16460,
  ),
  'XML_sql2xml' =>
  array (
    'version' => '0.3.2',
    'state' => 'beta',
    'filesize' => 18646,
  ),
  'XML_Statistics' =>
  array (
    'version' => '0.1',
    'state' => 'beta',
    'filesize' => 10893,
  ),
  'XML_SVG' =>
  array (
    'version' => '0.0.3',
    'state' => 'stable',
    'filesize' => 4643,
  ),
  'XML_svg2image' =>
  array (
    'version' => '0.1',
    'state' => 'beta',
    'filesize' => 7436,
  ),
  'XML_Transformer' =>
  array (
    'version' => '1.1.0',
    'state' => 'stable',
    'filesize' => 29877,
  ),
  'XML_Tree' =>
  array (
    'version' => '2.0.0RC2',
    'state' => 'beta',
    'filesize' => 8827,
  ),
  'XML_Util' =>
  array (
    'version' => '1.1.1',
    'state' => 'stable',
    'filesize' => 8358,
  ),
  'XML_Wddx' =>
  array (
    'version' => '1.0.0',
    'state' => 'stable',
    'filesize' => 3915,
  ),
  'XML_XPath' =>
  array (
    'version' => '1.2',
    'state' => 'beta',
    'filesize' => 17826,
  ),
  'XML_XSLT_Wrapper' =>
  array (
    'version' => '0.2.1',
    'state' => 'alpha',
    'filesize' => 16284,
  ),
  'XML_XUL' =>
  array (
    'version' => '0.8.1',
    'state' => 'alpha',
    'filesize' => 24730,
  ),
));
$pearweb->addHtmlConfig('http://pear.php.net/get/file-1.1.0RC5.tgz', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'File-1.1.0RC5.tgz');
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 =>
  array (
    'package' => 'file',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
  2 => '1.1.0RC3',
), array (
  'version' => '1.1.0RC5',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0" packagerversion="1.4.0a1">
 <name>File</name>
 <summary>Common file and directory routines</summary>
 <description>Provides easy access to read/write to files along with
some common routines to deal with paths. Also provides
interface for handling CSV files.
 </description>
 <maintainers>
  <maintainer>
   <user>richard</user>
   <name>Richard Heyes</name>
   <email>richard@php.net</email>
   <role>lead</role>
  </maintainer>
  <maintainer>
   <user>tal</user>
   <name>Tal Peer</name>
   <email>tal@php.net</email>
   <role>lead</role>
  </maintainer>
  <maintainer>
   <user>cox</user>
   <name>Tomas V.V. Cox</name>
   <email>cox@idecnet.com</email>
   <role>developer</role>
  </maintainer>
  <maintainer>
   <user>mike</user>
   <name>Michael Wallner</name>
   <email>mike@php.net</email>
   <role>lead</role>
  </maintainer>
  <maintainer>
   <user>dufuz</user>
   <name>Helgi Bormar</name>
   <email>dufuz@php.net</email>
   <role>developer</role>
  </maintainer>
  </maintainers>
 <release>
  <version>1.1.0RC5</version>
  <date>2005-02-21</date>
  <license>PHP</license>
  <state>beta</state>
  <notes>* Bug #3364 fixed, typo
  </notes>
  <deps>
   <dep type="php" rel="ge" version="4.2.0" optional="no"/>
   <dep type="php" rel="ge" version="4.3.0" optional="yes"/>
   <dep type="ext" rel="has" optional="no">pcre</dep>
   <dep type="pkg" rel="has" optional="no">PEAR</dep>
  </deps>
  <provides type="class" name="File" extends="PEAR" />
  <provides type="class" name="File_Util" />
  <provides type="function" name="File_Util::buildPath" />
  <provides type="function" name="File_Util::skipRoot" />
  <provides type="function" name="File_Util::tmpDir" />
  <provides type="function" name="File_Util::tmpFile" />
  <provides type="function" name="File_Util::isAbsolute" />
  <provides type="function" name="File_Util::relativePath" />
  <provides type="function" name="File_Util::realPath" />
  <provides type="function" name="File_Util::pathInRoot" />
  <provides type="function" name="File_Util::listDir" />
  <provides type="function" name="File_Util::sortFiles" />
  <provides type="class" name="File_CSV" />
  <provides type="function" name="File_CSV::raiseError" />
  <provides type="function" name="File_CSV::getPointer" />
  <provides type="function" name="File_CSV::unquote" />
  <provides type="function" name="File_CSV::readQuoted" />
  <provides type="function" name="File_CSV::read" />
  <filelist>
   <file role="php" md5sum="ec3145cc0031907166c15cc03872760f" name="File.php"/>
   <file role="php" md5sum="406cf1cfffe617ff58117ddd5139b20b" name="File/Util.php"/>
   <file role="php" md5sum="0d40627d63db2773d2a4f50b73c8ef3f" name="File/CSV.php"/>
   <file role="test" md5sum="df6b5898ff597b7c96511b821cd8145c" name="tests/parser.php"/>
   <file role="test" md5sum="cbecf1c21e14ad72f69b472c240099c8" name="tests/test.csv"/>
   <file role="test" md5sum="9a474bcc00b1b4163bbd20416ac64c5e" name="tests/FileTest.php"/>
   <file role="test" md5sum="708ad2ca285581f533dde34c5aecf313" name="tests/CSV/001.phpt"/>
   <file role="test" md5sum="172b0f71a0dced754eb2a11ff89c6c14" name="tests/CSV/001.csv"/>
   <file role="test" md5sum="00b4ca7e170475a069eff70e448fb943" name="tests/CSV/002.phpt"/>
   <file role="test" md5sum="b1e626843f63e4eb7946429317cd2392" name="tests/CSV/002.csv"/>
   <file role="test" md5sum="57bc6c6e04f5d57b8606490ef3bf2ed6" name="tests/CSV/003.phpt"/>
   <file role="test" md5sum="30054dc637ea7a5f4154495a40fec458" name="tests/CSV/003.csv"/>
   <file role="test" md5sum="ae5dbc3aca2c7fbc3f2cb745ba67a689" name="tests/CSV/004.phpt"/>
   <file role="test" md5sum="6606cc2f2161dafd85ee7bf7fb16b50e" name="tests/CSV/004.csv"/>
   <file role="test" md5sum="08ae198463e38f65d564bd6e35460080" name="tests/CSV/005.phpt"/>
   <file role="test" md5sum="8e850791c4df407f073d5633a18823e1" name="tests/CSV/005.csv"/>
   <file role="test" md5sum="50b09f7334858fda160b5d2c2029157d" name="tests/CSV/tests.txt"/>
  </filelist>
 </release>
 <changelog>
   <release>
    <version>1.1.0RC4</version>
    <date>2005-02-02</date>
    <license>PHP</license>
    <state>beta</state>
    <notes>* Required PHP dep now 4.2.0 because of PEAR (dufuz)
* Patch from Firman Wandayandi for File_CSV (dufuz)
  - Fixed bugs: Fields count less nor more than expected handling
  - Added Mac EOL support (Only loaded on PHP 4.3.0 and higher)
  - Added few tests
* added kind of a filter callback for File_Util::listDir() (mike)
* Fixed Bug #3355 (missing delimiter of preg_quote() in File_Util::buildPath()) (mike)
* Fixed Bug #3357 (infinite loop in File_Util::realPath()) (mike)
    </notes>
   </release>
   <release>
    <version>1.1.0RC3</version>
    <date>2005-01-13</date>
    <license>PHP</license>
    <state>beta</state>
    <notes>* now really containing the fix for File_CSV
    </notes>
   </release>
   <release>
    <version>1.1.0RC2</version>
    <date>2005-01-12</date>
    <license>PHP</license>
    <state>beta</state>
    <notes>* added File_Util containing all methods not handling file I/O (mike)
* deprecated methods are now available in File_Util (still in File for BC) (mike)
* fixed bug #2827 (File_CSV::discoverFormat() is unable to discover format in
  one column CSV file), allows 1 field per line in discoverFormat as well as
  the config overall, with no separator (which is the standard), removed
  one error check to fix this issue as well as moving error checking around
  in _conf, might give some people issues (can\'t see how tho), also added a
  new param to discoverFormat so one can inject a check for $ as a separator
  or something like that (dufuz)
    </notes>
   </release>
   <release>
    <version>1.1.0RC1</version>
    <date>2004-12-17</date>
    <license>PHP</license>
    <state>beta</state>
    <notes>* Fixed Bug #2810 (Can not call readAll two times)
* Fixed file locking
- Code cleanup (vastly)
+ Implemented Request #1542 (File::relativePath(), File::realPath())
    </notes>
   </release>
   <release>
    <version>1.0.3</version>
    <date>2003-01-28</date>
    <license>PHP</license>
    <state>stable</state>
    <notes>Fixed handling of paths containing \'..\' and \'~\' in File::isAbsolute().
    </notes>
   </release>
   <release>
    <version>1.0.2</version>
    <date>2002-05-26</date>
    <license>PHP</license>
    <state>stable</state>
    <notes>Revert to mode specification instead of using _checkAppend() function
    </notes>
   </release>
   <release>
    <version>1.0.1</version>
    <date>2002-05-03</date>
    <license>PHP</license>
    <state>stable</state>
    <notes>Bugfix in _checkAppend() usage
    </notes>
   </release>
   <release>
    <version>1.0.0</version>
    <date>2002-05-02</date>
    <license>PHP</license>
    <state>stable</state>
    <notes>Stable release
    </notes>
   </release>
   <release>
    <version>0.9.2</version>
    <date>2002-04-24</date>
    <license>PHP</license>
    <state>beta</state>
    <notes>Fixed bug apparent when using fopen wrappers
    </notes>
   </release>
   <release>
    <version>0.9.1</version>
    <date>2002-04-09</date>
    <license>PHP</license>
    <state>beta</state>
    <notes>Initial release
    </notes>
   </release>
 </changelog>
</package>
',
  'url' => 'http://pear.php.net/get/File-1.1.0RC5',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 =>
  array (
    'package' => 'net_sieve',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
  2 => '1.1.0',
), array (
  'version' => '1.1.1',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Net_Sieve</name>
  <summary>Handles talking to timsieved</summary>
  <description>Provides an API to talk to the timsieved server that comes
with Cyrus IMAPd. Can be used to install, remove, mark active etc
sieve scripts.</description>
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
  </maintainers>
  <release>
    <version>1.1.1</version>
    <date>2005-02-02</date>
    <license>BSD</license>
    <state>stable</state>
    <notes>* Fixed Bug #3242 cyrus murder referrals not followed</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0">Net_Socket</dep>
    </deps>
    <filelist>
      <file role="php" baseinstalldir="Net" name="Sieve.php"/>
      <file role="test" baseinstalldir="Net" name="test_sieve.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.1.0</version>
      <date>2004-12-18</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Fixed Bug #2728 Linebreaks not being read using getScript()
</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2004-03-13</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Fixed BUG #1006

</notes>
    </release>
    <release>
      <version>1.0.0</version>
      <date>2004-03-10</date>
      <license>BSD</license>
      <state>stable</state>
      <notes>* Fixed DIGEST-MD5 sasl version handling (sasl v1.xx responses are diferent than v2.xx)
* Fixed LOGIN Method

</notes>
    </release>
    <release>
      <version>0.9.1</version>
      <date>2004-02-29</date>
      <license>BSD</license>
      <state>beta</state>
      <notes>* There is an issue whith the DIGEST-MD5 method. in one installation it does not work but in my server it works perfect! please send me debug info to solve the problem if
it affects you or disable DIGEST-MD5
* some optimizations to the code
* added haveSpace() to check if the server has space to store the script. Use with care HAVESPACE seems to be broken in cyrus 2.0.16
* added hasExtension()
* added getExtensions()
* added referral support and automatic following of them. (it also handles the following of multireferrals).
* removed _getResponse replaced by _doCmd. (thanks to Etienne Goyer for this)
* added supportsAuthMech()
* if installed automatically uses Auth_SASL
* added CRAM-MD5 auth Method
* added DIGEST-MD5 auth Method
* added getAuthMechs() returns an array containing all the auth methods the server supports
* added hasAuthMech() to check if the server has a particular auth method
* _connect --&gt; connect: now is a public method (without breaking BC)
* _login --&gt; login: now is a public method (without breaking BC)
* fix typo  cmdAuthenticate() ---&gt;  _cmdAuthenticate()
* _doCmd() now parses string responses also.

</notes>
    </release>
    <release>
      <version>0.9.0</version>
      <date>2004-01-31</date>
      <license>BSD</license>
      <state>beta</state>
      <notes>* Added setDebug() method and debugging capabilities
* added disconnect() method
* added sample file test_sieve.php
* fixed bug #591
* automagically selects the best auth method

</notes>
    </release>
    <release>
      <version>0.8.1</version>
      <date>2002-07-27</date>
      <license>BSD</license>
      <state>beta</state>
      <notes>Initial release
</notes>
    </release>
    <release>
      <version>0.8</version>
      <date>2002-05-10</date>
      <license>PHP</license>
      <state>beta</state>
      <notes>Initial release
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Net_Sieve-1.1.1',
));
$pearweb->addHtmlConfig('http://pear.php.net/get/Net_Sieve-1.1.1.tgz', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Net_Sieve-1.1.1.tgz');
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 =>
  array (
    'package' => 'text_highlighter',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
  2 => '0.6.1',
), array (
  'version' => '0.6.2',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Text_Highlighter</name>
  <summary>Syntax highlighting</summary>
  <description>Text_Highlighter is a package for syntax highlighting.

It provides a base class provining all the functionality,
and a descendent classes geneator class.

The main idea is to simplify creation of subclasses
implementing syntax highlighting for particular language.
Subclasses do not implement any new functioanality,
they just provide syntax highlighting rules.
The rules sources are in XML format.

To create a highlighter for a language, there is no need
to code a new class manually. Simply describe the rules
in XML file and use Text_Highlighter_Generator to create
a new class.</description>
  <maintainers>
    <maintainer>
      <user>blindman</user>
      <name>Andrey Demenev</name>
      <email>demenev@gmail.com</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>0.6.2</version>
    <date>2005-02-04</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>- fixed Bug #3060 : Wrong render with HL_NUMBERS_TABLE option
- fixed Bug #3063 : Output buffer is not cleared before rendering in HTML renderer</notes>
    <deps>
      <dep type="pkg" rel="ge" version="1.0" optional="no">PEAR</dep>
      <dep type="pkg" rel="ge" version="1.0.1" optional="no">XML_Parser</dep>
      <dep type="pkg" rel="ge" version="1.0" optional="no">Console_Getopt</dep>
    </deps>
    <provides type="class" name="Text_Highlighter_CPP" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_CSS" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_DIFF" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_DTD" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_Generator" extends="XML_Parser" />
    <provides type="function" name="Text_Highlighter_Generator::setInputFile" />
    <provides type="function" name="Text_Highlighter_Generator::generate" />
    <provides type="function" name="Text_Highlighter_Generator::getCode" />
    <provides type="function" name="Text_Highlighter_Generator::saveCode" />
    <provides type="function" name="Text_Highlighter_Generator::hasErrors" />
    <provides type="function" name="Text_Highlighter_Generator::getErrors" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Default" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Region" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Block" />
    <provides type="function" name="Text_Highlighter_Generator::cdataHandler" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Comment" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_PartGroup" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_PartClass" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Keywords" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Keyword" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Contains" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_But" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Onlyin" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Author" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Highlight" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Comment_" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Region_" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Keywords_" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Block_" />
    <provides type="function" name="Text_Highlighter_Generator::xmltag_Highlight_" />
    <provides type="class" name="Text_Highlighter_JAVASCRIPT" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_MYSQL" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_PERL" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_PHP" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_PYTHON" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_Renderer" />
    <provides type="function" name="Text_Highlighter_Renderer::reset" />
    <provides type="function" name="Text_Highlighter_Renderer::preprocess" />
    <provides type="function" name="Text_Highlighter_Renderer::acceptToken" />
    <provides type="function" name="Text_Highlighter_Renderer::finalize" />
    <provides type="function" name="Text_Highlighter_Renderer::getOutput" />
    <provides type="class" name="Text_Highlighter_RUBY" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_SQL" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_XML" extends="Text_Highlighter" />
    <provides type="class" name="Text_Highlighter_Renderer_Console" extends="Text_Highlighter_Renderer" />
    <provides type="function" name="Text_Highlighter_Renderer_Console::preprocess" />
    <provides type="function" name="Text_Highlighter_Renderer_Console::reset" />
    <provides type="function" name="Text_Highlighter_Renderer_Console::acceptToken" />
    <provides type="function" name="Text_Highlighter_Renderer_Console::finalize" />
    <provides type="function" name="Text_Highlighter_Renderer_Console::getOutput" />
    <provides type="class" name="Text_Highlighter_Renderer_Html" extends="Text_Highlighter_Renderer" />
    <provides type="function" name="Text_Highlighter_Renderer_Html::preprocess" />
    <provides type="function" name="Text_Highlighter_Renderer_Html::reset" />
    <provides type="function" name="Text_Highlighter_Renderer_Html::acceptToken" />
    <provides type="function" name="Text_Highlighter_Renderer_Html::finalize" />
    <provides type="function" name="Text_Highlighter_Renderer_Html::getOutput" />
    <provides type="class" name="Text_Highlighter" />
    <provides type="function" name="Text_Highlighter::factory" />
    <provides type="function" name="Text_Highlighter::setRenderer" />
    <provides type="function" name="Text_Highlighter::highlight" />
    <filelist>
      <file role="php" baseinstalldir="Text" md5sum="ab011d2bb3c6011c96fd5e247f5b504c" name="Highlighter/CPP.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="b23bdc7430c5a6fe6651c3e6ebb965a9" name="Highlighter/CSS.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="0fba874c68b8d396c44a851211521b64" name="Highlighter/DIFF.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="978e60aebd154dfa70b09fcef041d39c" name="Highlighter/DTD.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="95a43c24e2786f177ca37c1fb889dff1" name="Highlighter/Generator.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="ed7a1307f42fc92c93c6058ec7ffac3e" name="Highlighter/JAVASCRIPT.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="614ddde4eb6674c0f97d0bc9734e47f4" name="Highlighter/MYSQL.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="6d247ac40d5fde7e843f580f9cd8929a" name="Highlighter/PERL.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="044038bb91cb9c0b57a4837d6d46651a" name="Highlighter/PHP.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="9efffc8c82cb9231abe4123a1ed822fd" name="Highlighter/PYTHON.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="1a6f578358519cb73d9d4d8efdb318a7" name="Highlighter/Renderer.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="681db31fc2a794dc6d7119574e30d7bc" name="Highlighter/RUBY.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="145c0bbe45132bececbaf958268aef4e" name="Highlighter/SQL.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="5c4e34c2fb7521ff193b88ce28deee6a" name="Highlighter/XML.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="be439e0e3ebcb8dced7383b977e4fa97" name="Highlighter/Renderer/Console.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="d1d081845d29b648d0f4c07615330449" name="Highlighter/Renderer/Html.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="data" baseinstalldir="Text" md5sum="2a95351a26501d4fb18e24893282431e" name="cpp.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="7d0040b04a3ad06b8052ec5b0a34a5b9" name="css.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="6df7736ab924daa9009008ee4baa7a07" name="diff.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="d8091175157862f9f47c8a6bf08c523d" name="dtd.xml"/>
      <file role="script" baseinstalldir="Text/Highlighter" md5sum="dc31dd02ef6649840b180472d190ed63" platform="(*ix|*ux)" name="generate">
        <replace type="pear-config" from="@php_dir@" to="php_dir"/>
        <replace type="pear-config" from="@php_bin@" to="php_bin"/>
      </file>
      <file role="script" baseinstalldir="Text/Highlighter" md5sum="f30e2972374e3ef56b31077aaf227636" platform="windows" name="generate.bat">
        <replace type="pear-config" from="@php_dir@" to="php_dir"/>
        <replace type="pear-config" from="@php_bin@" to="php_bin"/>
      </file>
      <file role="php" baseinstalldir="Text" md5sum="fac248207d689bb39cb5548207d65046" name="Highlighter.php">
        <replace type="package-info" from="@package_version@" to="version"/>
      </file>
      <file role="data" baseinstalldir="Text" md5sum="40efd9777f0bba0889d2de265bb8d5ba" name="javascript.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="72abda2e45e6415be081dfb22f14ada0" name="mysql.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="1dd58cb5ae6417755eaefefce516f90c" name="perl.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="154f59322d22d7e09b7a167083858115" name="php.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="aeba033092a6577c45482a185e83a0e8" name="python.xml"/>
      <file role="doc" baseinstalldir="Text" md5sum="f2ae32c65d208a952806351856b77f1b" name="README"/>
      <file role="data" baseinstalldir="Text" md5sum="cbc0ec08690014c2961a95a568e50347" name="ruby.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="e71ccfaa999caf45492e4b0210212126" name="sql.xml"/>
      <file role="data" baseinstalldir="Text" md5sum="88798cafb7bcfb2d5797de2b26b0a26a" name="xml.xml"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>0.4.1</version>
      <date>2004-06-19</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>First beta release
</notes>
    </release>
    <release>
      <version>0.5.0</version>
      <date>2004-10-08</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>- fixed #1991 Output is not W3C compliant (&lt;/li&gt; missing!)
- new highlighters : perl, c/c++, ruby
- removed dependency on ErrorStack
- added renderers support (default HTML renderer and Console renderer are available)
- removed PHPDoc tutorials. I am too lazy to maintain those XML files. See documentation in README
</notes>
    </release>
    <release>
      <version>0.5.1</version>
      <date>2004-10-31</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>- fixed HTML renderer to work correctly with IE (bug reported by Laurent Laville)
</notes>
    </release>
    <release>
      <version>0.6.0</version>
      <date>2004-11-11</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>[-] fixed #2588 : Missing nbsp in first line
[+] new, much faster, highlighting engine
</notes>
    </release>
    <release>
      <version>0.6.1</version>
      <date>2005-02-04</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>bugfix release

[-] fixed bug #2730 : Notice: Undefined offset in Highlighter.php
</notes>
    </release>
    <release>
      <version>0.6.2</version>
      <date>2005-02-04</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>- fixed Bug #3060 : Wrong render with HL_NUMBERS_TABLE option
- fixed Bug #3063 : Output buffer is not cleared before rendering in HTML renderer
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Text_Highlighter-0.6.2',
));
$pearweb->addHtmlConfig('http://pear.php.net/get/Text_Highlighter-0.6.2.tgz', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Text_Highlighter-0.6.2.tgz');
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 =>
  array (
    'package' => 'text_wiki',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
  2 => '0.23.1',
), array (
  'version' => '0.25.0',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Text_Wiki</name>
  <summary>Abstracts parsing and rendering rules for Wiki markup in structured plain text.</summary>
  <description>Abstracts parsing and rendering rules for Wiki markup in structured plain text.</description>
  <maintainers>
    <maintainer>
      <user>pmjones</user>
      <name>Paul M. Jones</name>
      <email>pmjones@ciaweb.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>0.25.0</version>
    <date>2005-02-01</date>
    <license>LGPL</license>
    <state>beta</state>
    <notes>* moved all parsing rules from Text/Wiki/Parse to Text/Wiki/Parse/Default (this will help separate entire parsing rule sets, e.g. BBCode)
* changed Wiki.php to use the new Parse/Default directory as the default directory
* fixed interwiki regex so that page names starting with : are not honored (it was messing up wikilinks with 2 colons in the text)</notes>
    <filelist>
      <file role="php" md5sum="8bb64aa85074f654932da9608a334717" name="Text/Wiki.php"/>
      <file role="php" md5sum="708b5472456cb509393aa774936a0880" name="Text/Wiki/Parse.php"/>
      <file role="php" md5sum="d4a8511f56a16cb0f81f08f8f04aa44d" name="Text/Wiki/Parse/Default/Anchor.php"/>
      <file role="php" md5sum="011503667a676c127f4b74f1d7e029db" name="Text/Wiki/Parse/Default/Blockquote.php"/>
      <file role="php" md5sum="02bfddeb690b3a9bbc15b296ce67e851" name="Text/Wiki/Parse/Default/Bold.php"/>
      <file role="php" md5sum="b5c7886bbd92df74edf2232c20900255" name="Text/Wiki/Parse/Default/Break.php"/>
      <file role="php" md5sum="84ab272755d82d2cc1dd7ca67c0f89a0" name="Text/Wiki/Parse/Default/Center.php"/>
      <file role="php" md5sum="eb432591c8508cc263a67e2075fca761" name="Text/Wiki/Parse/Default/Code.php"/>
      <file role="php" md5sum="dfd4081f0ef16820ff4312615fe55f93" name="Text/Wiki/Parse/Default/Colortext.php"/>
      <file role="php" md5sum="95f037a98bac536d4b2ddc33ac3ba83b" name="Text/Wiki/Parse/Default/Deflist.php"/>
      <file role="php" md5sum="44274c0ff3a8743402f21f503f973fd7" name="Text/Wiki/Parse/Default/Delimiter.php"/>
      <file role="php" md5sum="3091640def7420e6150a4d62ef71495c" name="Text/Wiki/Parse/Default/Embed.php"/>
      <file role="php" md5sum="77bf1fe3d8d7c78eb3abb018320112ad" name="Text/Wiki/Parse/Default/Emphasis.php"/>
      <file role="php" md5sum="2c4fd152f94c82a2dab546b23ad907aa" name="Text/Wiki/Parse/Default/Freelink.php"/>
      <file role="php" md5sum="e86fb4092fb9736b02522dd0fed3dbd5" name="Text/Wiki/Parse/Default/Function.php"/>
      <file role="php" md5sum="5f5e332837e9bbb9f37306563be06bfe" name="Text/Wiki/Parse/Default/Heading.php"/>
      <file role="php" md5sum="875fe633b724da2a22c612d3f92dc8e4" name="Text/Wiki/Parse/Default/Horiz.php"/>
      <file role="php" md5sum="29bcd83e6ccf84da35bd3883d21a9b50" name="Text/Wiki/Parse/Default/Html.php"/>
      <file role="php" md5sum="571b31236b8d3c62702bf9edad1dca9e" name="Text/Wiki/Parse/Default/Image.php"/>
      <file role="php" md5sum="b623f5543c192a418f7eaa939ec503b6" name="Text/Wiki/Parse/Default/Include.php"/>
      <file role="php" md5sum="a2bb08c22b15b3ede9c5d172dbc5ea93" name="Text/Wiki/Parse/Default/Interwiki.php"/>
      <file role="php" md5sum="a87447fcea49a603812d840f0fca83af" name="Text/Wiki/Parse/Default/Italic.php"/>
      <file role="php" md5sum="14714f1963281caa0ef8eb0e0692401d" name="Text/Wiki/Parse/Default/List.php"/>
      <file role="php" md5sum="cb490ce60c1846d2f5512efd43b2778c" name="Text/Wiki/Parse/Default/Newline.php"/>
      <file role="php" md5sum="e425d6439f7e8685924e3f12053111bd" name="Text/Wiki/Parse/Default/Paragraph.php"/>
      <file role="php" md5sum="b061b0cbf5d3f8e0f12f895238db8c83" name="Text/Wiki/Parse/Default/Phplookup.php"/>
      <file role="php" md5sum="001302b748dc5b4cd56518f0c939ea32" name="Text/Wiki/Parse/Default/Prefilter.php"/>
      <file role="php" md5sum="4669c1b086f924f3113baf75b25bb8fc" name="Text/Wiki/Parse/Default/Raw.php"/>
      <file role="php" md5sum="5a5bd459d498ba86de033f6ee6fab599" name="Text/Wiki/Parse/Default/Revise.php"/>
      <file role="php" md5sum="304dad68afdb379ad0c9ede71b7ea106" name="Text/Wiki/Parse/Default/Strong.php"/>
      <file role="php" md5sum="8c8a4cadbb8813e2fb020bf021a60350" name="Text/Wiki/Parse/Default/Superscript.php"/>
      <file role="php" md5sum="1f5701301e0cc57bccfd8d8de7988e25" name="Text/Wiki/Parse/Default/Table.php"/>
      <file role="php" md5sum="affb2bc3558faa40c288842ccfc912e8" name="Text/Wiki/Parse/Default/Tighten.php"/>
      <file role="php" md5sum="711826afcb2a4d2e6842b9160cc0260d" name="Text/Wiki/Parse/Default/Toc.php"/>
      <file role="php" md5sum="ad54187d1f45b17410a3dbff7b898f94" name="Text/Wiki/Parse/Default/Tt.php"/>
      <file role="php" md5sum="d094dc0552fb114adc1a46fe28ad7773" name="Text/Wiki/Parse/Default/Url.php"/>
      <file role="php" md5sum="57b278c15e7a1137a24b50ca5912aea4" name="Text/Wiki/Parse/Default/Wikilink.php"/>
      <file role="php" md5sum="3005771d12970a102e65334dc7355859" name="Text/Wiki/Render.php"/>
      <file role="php" md5sum="56fce0f63ac95b01ab5a7dc5b9375c08" name="Text/Wiki/Render/Xhtml.php"/>
      <file role="php" md5sum="60af7eb93cc42cc00d341fb4ce03f834" name="Text/Wiki/Render/Xhtml/Anchor.php"/>
      <file role="php" md5sum="24ae57917d8b4a1481f5674f4aa8ef2c" name="Text/Wiki/Render/Xhtml/Blockquote.php"/>
      <file role="php" md5sum="d6dfc22c0fb83cd09e96c63b2c2d1d5b" name="Text/Wiki/Render/Xhtml/Bold.php"/>
      <file role="php" md5sum="55bad52e5a51ead9a08d2e777cd5b4e6" name="Text/Wiki/Render/Xhtml/Break.php"/>
      <file role="php" md5sum="86ed38ee7270df30450482a07229ca21" name="Text/Wiki/Render/Xhtml/Center.php"/>
      <file role="php" md5sum="d0427a132aea4ee281d565c0c92cba46" name="Text/Wiki/Render/Xhtml/Code.php"/>
      <file role="php" md5sum="40290aa1f7bfd53add05c09843b04a31" name="Text/Wiki/Render/Xhtml/Colortext.php"/>
      <file role="php" md5sum="f6a05c3733c57d3e0f457c9d5e627f51" name="Text/Wiki/Render/Xhtml/Deflist.php"/>
      <file role="php" md5sum="da1cd5872383a959d52dbff84ed7dbda" name="Text/Wiki/Render/Xhtml/Delimiter.php"/>
      <file role="php" md5sum="2effcfed3eb41d2751e132575d14b195" name="Text/Wiki/Render/Xhtml/Embed.php"/>
      <file role="php" md5sum="7e61e000482d5be36b5362a9144bb4c9" name="Text/Wiki/Render/Xhtml/Emphasis.php"/>
      <file role="php" md5sum="d5a09071e6dfaf91eb9d02e6c84a1335" name="Text/Wiki/Render/Xhtml/Freelink.php"/>
      <file role="php" md5sum="cd276ddabeb95ec7c28f2b9f7602ec5d" name="Text/Wiki/Render/Xhtml/Function.php"/>
      <file role="php" md5sum="61b3d0b4d5983169f9703f00a656d8c2" name="Text/Wiki/Render/Xhtml/Heading.php"/>
      <file role="php" md5sum="461ef84de7f74da953462b80750996a7" name="Text/Wiki/Render/Xhtml/Horiz.php"/>
      <file role="php" md5sum="92550ac1ebc1eb0cf399b33f18d70be5" name="Text/Wiki/Render/Xhtml/Html.php"/>
      <file role="php" md5sum="c8f30d5748437f4cbcb8c281bfd90aa6" name="Text/Wiki/Render/Xhtml/Image.php"/>
      <file role="php" md5sum="ab42d41981363c910af1cadd31e2f355" name="Text/Wiki/Render/Xhtml/Include.php"/>
      <file role="php" md5sum="f15e720841e170c75ad2d56089d391fe" name="Text/Wiki/Render/Xhtml/Interwiki.php"/>
      <file role="php" md5sum="1079eb024a28cba5508db5110866c5d3" name="Text/Wiki/Render/Xhtml/Italic.php"/>
      <file role="php" md5sum="048a982b5107017f4b1d7381c44938d7" name="Text/Wiki/Render/Xhtml/List.php"/>
      <file role="php" md5sum="ec802349936827a5370ac32bed0271f2" name="Text/Wiki/Render/Xhtml/Newline.php"/>
      <file role="php" md5sum="ce40b62d2b50fc2d4260abf0d55583a6" name="Text/Wiki/Render/Xhtml/Paragraph.php"/>
      <file role="php" md5sum="529130dd5d56fc3bd291da95b8f50a69" name="Text/Wiki/Render/Xhtml/Phplookup.php"/>
      <file role="php" md5sum="c2354d2e6620f67c0afb073632698605" name="Text/Wiki/Render/Xhtml/Prefilter.php"/>
      <file role="php" md5sum="4ff1d9331475c1c500aeea3dbefbb772" name="Text/Wiki/Render/Xhtml/Raw.php"/>
      <file role="php" md5sum="2a1500b49bcd38d82ea80ce62309ae51" name="Text/Wiki/Render/Xhtml/Revise.php"/>
      <file role="php" md5sum="96fc8a2eb51f5f0c7c91098a10196b50" name="Text/Wiki/Render/Xhtml/Strong.php"/>
      <file role="php" md5sum="9f3bd02d737a88988f2fdeffe7b8c12a" name="Text/Wiki/Render/Xhtml/Superscript.php"/>
      <file role="php" md5sum="695dfb592bc6f69e730b22eda80a219d" name="Text/Wiki/Render/Xhtml/Table.php"/>
      <file role="php" md5sum="4596154d8dbbed5074556c97fad669c6" name="Text/Wiki/Render/Xhtml/Tighten.php"/>
      <file role="php" md5sum="08f4ae9504d6d852e4bb07d6a2722f5e" name="Text/Wiki/Render/Xhtml/Toc.php"/>
      <file role="php" md5sum="f6bb62e61593597f628c5bd1970b53bc" name="Text/Wiki/Render/Xhtml/Tt.php"/>
      <file role="php" md5sum="4aafb5eb1a265cda61bc27bba40f5e69" name="Text/Wiki/Render/Xhtml/Url.php"/>
      <file role="php" md5sum="814c0068436c3551229ffb46766f0c09" name="Text/Wiki/Render/Xhtml/Wikilink.php"/>
      <file role="php" md5sum="0d70c70075d825bdd93756431dc212fb" name="Text/Wiki/Render/Latex.php"/>
      <file role="php" md5sum="a1e49f6100be24162e93ef9ee218e7f1" name="Text/Wiki/Render/Latex/Anchor.php"/>
      <file role="php" md5sum="0dd1343df1aebdc4bc622eb6c084fe81" name="Text/Wiki/Render/Latex/Blockquote.php"/>
      <file role="php" md5sum="ffec6e3faa7374fb4fc1d9bdf7adaa98" name="Text/Wiki/Render/Latex/Bold.php"/>
      <file role="php" md5sum="1e7630da3aca818aa2d8d4697e1724ea" name="Text/Wiki/Render/Latex/Break.php"/>
      <file role="php" md5sum="ae525fa78fd7512fbd14fc0406ea14f3" name="Text/Wiki/Render/Latex/Center.php"/>
      <file role="php" md5sum="2c67a15ef6bf0ee026f6a9d175623af3" name="Text/Wiki/Render/Latex/Code.php"/>
      <file role="php" md5sum="68b9ab44e987aac5c146132a69e798ee" name="Text/Wiki/Render/Latex/Colortext.php"/>
      <file role="php" md5sum="42fdf8ab39c310e57eeed8e6703432f3" name="Text/Wiki/Render/Latex/Deflist.php"/>
      <file role="php" md5sum="79ce6516b8020c228e1635e7ef656de2" name="Text/Wiki/Render/Latex/Delimiter.php"/>
      <file role="php" md5sum="3e6e365fb2207875cdd2dba0a52e7d16" name="Text/Wiki/Render/Latex/Embed.php"/>
      <file role="php" md5sum="c2eca3b73200ff24cc90c103486fe2bc" name="Text/Wiki/Render/Latex/Emphasis.php"/>
      <file role="php" md5sum="93dea6c89341068785fe0d0274de3dbe" name="Text/Wiki/Render/Latex/Freelink.php"/>
      <file role="php" md5sum="033a9697576b4876aa3084c906b7d084" name="Text/Wiki/Render/Latex/Function.php"/>
      <file role="php" md5sum="767c567be9804be8d0959ddd0622a998" name="Text/Wiki/Render/Latex/Heading.php"/>
      <file role="php" md5sum="6367311f52591f558d1593ba81986749" name="Text/Wiki/Render/Latex/Horiz.php"/>
      <file role="php" md5sum="46273f2b5851397db321e620b2e11364" name="Text/Wiki/Render/Latex/Html.php"/>
      <file role="php" md5sum="c9a4ea853a2308877b8cef08bab751a0" name="Text/Wiki/Render/Latex/Image.php"/>
      <file role="php" md5sum="0081d66276d151571a0770da03cd1a79" name="Text/Wiki/Render/Latex/Include.php"/>
      <file role="php" md5sum="378bfff5cca0dc219b55b58f9f7823c5" name="Text/Wiki/Render/Latex/Interwiki.php"/>
      <file role="php" md5sum="f187d0e8d4c4261dc9451649cd41d03a" name="Text/Wiki/Render/Latex/Italic.php"/>
      <file role="php" md5sum="14e559fb82f37a631937787a5adc0fde" name="Text/Wiki/Render/Latex/List.php"/>
      <file role="php" md5sum="a8e639cfa11c7261bb0e6ba295c582a9" name="Text/Wiki/Render/Latex/Newline.php"/>
      <file role="php" md5sum="b806e22811794d51ad7dbb104a06dc01" name="Text/Wiki/Render/Latex/Paragraph.php"/>
      <file role="php" md5sum="a8d661efe52a5dfaba3bbf86a3d0717c" name="Text/Wiki/Render/Latex/Phplookup.php"/>
      <file role="php" md5sum="06360298f6b28273ff178a5284b286de" name="Text/Wiki/Render/Latex/Prefilter.php"/>
      <file role="php" md5sum="a5441e310a340ef1c3cc141d279592dd" name="Text/Wiki/Render/Latex/Raw.php"/>
      <file role="php" md5sum="25b17d6af4f3a12161e5ab043c80a629" name="Text/Wiki/Render/Latex/Revise.php"/>
      <file role="php" md5sum="ae58a265d24dffc85993241718bd0a7a" name="Text/Wiki/Render/Latex/Strong.php"/>
      <file role="php" md5sum="c84507b26b49fa7ad62c2f3731cdb010" name="Text/Wiki/Render/Latex/Superscript.php"/>
      <file role="php" md5sum="7761d20812f6e7b9efd4d36b197d114b" name="Text/Wiki/Render/Latex/Table.php"/>
      <file role="php" md5sum="65f9cd0727a39325107315d2beacb957" name="Text/Wiki/Render/Latex/Tighten.php"/>
      <file role="php" md5sum="e20194c4b167432a43082db72afe3f2e" name="Text/Wiki/Render/Latex/Toc.php"/>
      <file role="php" md5sum="65b46ecfd73cf106544deb8cdfd639d8" name="Text/Wiki/Render/Latex/Tt.php"/>
      <file role="php" md5sum="a395736d197a8a589fd779c9136268f2" name="Text/Wiki/Render/Latex/Url.php"/>
      <file role="php" md5sum="9ac40d1fa9cdce3f28dbf2c177444938" name="Text/Wiki/Render/Latex/Wikilink.php"/>
      <file role="php" md5sum="319d8299fb9171e68423e2e873579f37" name="Text/Wiki/Render/Plain.php"/>
      <file role="php" md5sum="1effdf33d995c78b9207a7db5e3fb25f" name="Text/Wiki/Render/Plain/Anchor.php"/>
      <file role="php" md5sum="cab5c05d3385f912a2aed8315a50aa91" name="Text/Wiki/Render/Plain/Blockquote.php"/>
      <file role="php" md5sum="57f3d4651dd2597b671423af4a4dc873" name="Text/Wiki/Render/Plain/Bold.php"/>
      <file role="php" md5sum="83e8e710ba96f7b669df4dbf0b0da20c" name="Text/Wiki/Render/Plain/Break.php"/>
      <file role="php" md5sum="cd08b238c707ca6b208c6ea289abe450" name="Text/Wiki/Render/Plain/Center.php"/>
      <file role="php" md5sum="4d0fca8dc932811117cc08465bcdff05" name="Text/Wiki/Render/Plain/Code.php"/>
      <file role="php" md5sum="e411cba244717f16d59c5c7750203519" name="Text/Wiki/Render/Plain/Colortext.php"/>
      <file role="php" md5sum="820dc4e5c73f3aef2b5cfa7b275293c5" name="Text/Wiki/Render/Plain/Deflist.php"/>
      <file role="php" md5sum="96bc0e94126df90bb39d3c91f64243e9" name="Text/Wiki/Render/Plain/Delimiter.php"/>
      <file role="php" md5sum="3316a6cc07794e1c00b2800090e3a6a6" name="Text/Wiki/Render/Plain/Embed.php"/>
      <file role="php" md5sum="51b79aee81fdac474cc7dcced5207385" name="Text/Wiki/Render/Plain/Emphasis.php"/>
      <file role="php" md5sum="1e787a7c385caf5efc7cbe548b1b1bd1" name="Text/Wiki/Render/Plain/Freelink.php"/>
      <file role="php" md5sum="ab3fde28fa2df4e504b90066e3e1eb76" name="Text/Wiki/Render/Plain/Function.php"/>
      <file role="php" md5sum="2c13626095638f492b481881001f3097" name="Text/Wiki/Render/Plain/Heading.php"/>
      <file role="php" md5sum="a1777d454b1aa3ae8a54886341202dce" name="Text/Wiki/Render/Plain/Horiz.php"/>
      <file role="php" md5sum="075039f21e42316f00dcca7f0a5c306a" name="Text/Wiki/Render/Plain/Html.php"/>
      <file role="php" md5sum="cb632ba340758b2a3c6b9cfc1ec1f98b" name="Text/Wiki/Render/Plain/Image.php"/>
      <file role="php" md5sum="10bd9df02b09436c1bc19a84df21496b" name="Text/Wiki/Render/Plain/Include.php"/>
      <file role="php" md5sum="64c29c127f5804a57b80b5bad9094c27" name="Text/Wiki/Render/Plain/Interwiki.php"/>
      <file role="php" md5sum="9b4864a0f423a26795532dcfbe46527b" name="Text/Wiki/Render/Plain/Italic.php"/>
      <file role="php" md5sum="9527979e32d6c89a23bdb105f0c095d3" name="Text/Wiki/Render/Plain/List.php"/>
      <file role="php" md5sum="545ab97a6e7385f740ac2a4fe649015b" name="Text/Wiki/Render/Plain/Newline.php"/>
      <file role="php" md5sum="d5cb7e888c60e7c00941b761bd0aa401" name="Text/Wiki/Render/Plain/Paragraph.php"/>
      <file role="php" md5sum="4026c26ec55254d709aff632147cb8fc" name="Text/Wiki/Render/Plain/Phplookup.php"/>
      <file role="php" md5sum="486c04e78e4962d33a34c5752d395c5b" name="Text/Wiki/Render/Plain/Prefilter.php"/>
      <file role="php" md5sum="c36575344b43fbdb18e433181a656c78" name="Text/Wiki/Render/Plain/Raw.php"/>
      <file role="php" md5sum="6f8ec07c2dc649728b65b48346a8c16a" name="Text/Wiki/Render/Plain/Revise.php"/>
      <file role="php" md5sum="b76c1add68657e1d8854a33edc26e338" name="Text/Wiki/Render/Plain/Strong.php"/>
      <file role="php" md5sum="2c0ca289d15135ca6af96f336899d20f" name="Text/Wiki/Render/Plain/Superscript.php"/>
      <file role="php" md5sum="40b5ffd2cddd3c6235b979a0f96d646e" name="Text/Wiki/Render/Plain/Table.php"/>
      <file role="php" md5sum="073176bb09d77e865e627a9fb01365a5" name="Text/Wiki/Render/Plain/Tighten.php"/>
      <file role="php" md5sum="44191bbd6feeb95e523569e74778f739" name="Text/Wiki/Render/Plain/Toc.php"/>
      <file role="php" md5sum="a6176de398a7057c4f22b2a41c602b63" name="Text/Wiki/Render/Plain/Tt.php"/>
      <file role="php" md5sum="806ce9bf4e0836f2607fa5abfb2a62d9" name="Text/Wiki/Render/Plain/Url.php"/>
      <file role="php" md5sum="cfe1cf8287b05b872b00509f91cc07ef" name="Text/Wiki/Render/Plain/Wikilink.php"/>
    </filelist>
  </release>
</package>',
  'url' => 'http://pear.php.net/get/Text_Wiki-0.25.0',
));
$pearweb->addHtmlConfig('http://pear.php.net/get/Text_Wiki-0.25.0.tgz', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Text_Wiki-0.25.0.tgz');
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDownloadURL", array (
  0 =>
  array (
    'package' => 'xml_rpc',
    'channel' => 'pear.php.net',
  ),
  1 => 'alpha',
  2 => '1.2.0RC3',
), array (
  'version' => '1.2.0RC6',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>XML_RPC</name>
  <summary>PHP implementation of the XML-RPC protocol</summary>
  <description>A PEAR-ified version of Useful Inc\'s XML-RPC for PHP.
It has support for HTTP transport, proxies and authentication.</description>
  <maintainers>
    <maintainer>
      <user>ssb</user>
      <name>Stig Bakken</name>
      <email>stig@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>danielc</user>
      <name>Daniel Convissor</name>
      <email>danielc@php.net</email>
      <role>lead</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.2.0RC6</version>
    <date>2005-01-25</date>
    <license>PHP License</license>
    <state>beta</state>
    <notes>- Don\'t put the protocol in the Host field of the POST data.  (danielc)</notes>
    <filelist>
      <file role="php" baseinstalldir="XML" md5sum="db767af87fa232fcdd8a46098524e43d" name="RPC.php">
        <replace from="@package_version@" to="version" type="package-info"/>
      </file>
      <file role="php" baseinstalldir="XML/RPC" md5sum="97e69d2e0a564b80b2b5bc16843a89b8" name="Server.php">
        <replace from="@package_version@" to="version" type="package-info"/>
      </file>
      <file role="php" baseinstalldir="XML/RPC" md5sum="9268c6782db7da230d0eaafb02b942e3" name="Dump.php">
        <replace from="@package_version@" to="version" type="package-info"/>
      </file>
      <file role="test" md5sum="212da95e321196f9353db9dbf345d78f" name="tests\\protoport.php">
        <replace from="@package_version@" to="version" type="pear-config"/>
      </file>
      <file role="test" md5sum="29df692176c5bb4035bc2d3b9f63fa65" name="tests\\test_Dump.php">
        <replace from="@package_version@" to="version" type="pear-config"/>
      </file>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.2.0RC5</version>
      <date>2005-01-24</date>
      <state>beta</state>
      <notes>- If $port is 443 but a protocol isn\'t specified in $server, assume ssl:// is the protocol.

</notes>
    </release>
    <release>
      <version>1.2.0RC4</version>
      <date>2005-01-24</date>
      <state>beta</state>
      <notes>- When a connection attempt fails, have the method return 0.  (danielc)
- Move the protocol/port checking/switching and the property settings from sendPayloadHTTP10() to the XML_RPC_Client constructor.  (danielc)
- Add tests for setting the client properties.  (danielc)
- Remove $GLOBALS[\'XML_RPC_twoslash\'] since it\'s not used.  (danielc)
- Bundle the tests with the package.  (danielc)

</notes>
    </release>
    <release>
      <version>1.2.0RC3</version>
      <date>2005-01-19</date>
      <state>beta</state>
      <notes>- ssl uses port 443, not 445.

</notes>
    </release>
    <release>
      <version>1.2.0RC2</version>
      <date>2005-01-11</date>
      <state>beta</state>
      <notes>- Handle ssl:// in the $server string.  (danielc)
- Also default to port 445 for ssl:// requests as well.  (danielc)
- Enhance debugging in the server.  (danielc)

</notes>
    </release>
    <release>
      <version>1.2.0RC1</version>
      <date>2004-12-30</date>
      <state>beta</state>
      <notes>- Make things work with SSL.  Bug 2489.  (nkukard lbsd net)
- Allow array function callbacks (Matt Kane)
- Some minor speed-ups (Matt Kane)
- Add Dump.php to the package (Christian Weiske)
- Replace all line endings with \\r\\n.  Had only done replacements on \\n.  Bug 2521.  (danielc)
- Silence fsockopen() errors.  Bug 1714.  (danielc)
- Encode empty arrays as an array. Bug 1493.  (danielc)
- Eliminate undefined index notice when submitting empty arrays to XML_RPC_Encode().  Bug 1819.  (danielc)
- Speed up check for enumerated arrays in XML_RPC_Encode().  (danielc)
- Prepend &quot;XML_RPC_&quot; to ERROR_NON_NUMERIC_FOUND, eliminating problem when eval()\'ing error messages.  (danielc)
- Use XML_RPC_Base::raiseError() instead of PEAR::raiseError() in XML_RPC_ee() because PEAR.php is lazy loaded.  (danielc)
- Allow raiseError() to be called statically.  (danielc)
- Stop double escaping of character entities.  Bug 987.  (danielc)
  NOTICE: the following have been removed:
    * XML_RPC_dh()
    * $GLOBALS[\'XML_RPC_entities\']
    * XML_RPC_entity_decode()
    * XML_RPC_lookup_entity()
- Determine the XML\'s encoding via the encoding attribute in the XML declaration.  Bug 52.  (danielc)

</notes>
    </release>
    <release>
      <version>1.1.0</version>
      <date>2004-03-15</date>
      <state>stable</state>
      <notes>- Added support for sequential arrays to XML_RPC_encode() (mroch)
- Cleaned up new XML_RPC_encode() changes a bit (mroch, pierre)
- Remove &quot;require_once \'PEAR.php\'&quot;, include only when needed to raise an error
- Replace echo and error_log() with raiseError() (mroch)
- Make all classes extend XML_RPC_Base, which will handle common functions  (mroch)
- be tolerant of junk after methodResponse (Luca Mariano, mroch)
- Silent notice even in the error log (pierre)
- fix include of shared xml extension on win32 (pierre)

</notes>
    </release>
    <release>
      <version>1.0.4</version>
      <date>2002-10-02</date>
      <state>stable</state>
      <notes>* added HTTP proxy authorization support (thanks to Arnaud Limbourg)

</notes>
    </release>
    <release>
      <version>1.0.3</version>
      <date>2002-05-19</date>
      <state>stable</state>
      <notes>* fix bug when parsing responses with boolean types

</notes>
    </release>
    <release>
      <version>1.0.2</version>
      <date>2002-04-16</date>
      <state>stable</state>
      <notes>* E_ALL fixes
* fix HTTP response header parsing

</notes>
    </release>
    <release>
      <version>1.0.1</version>
      <date>2001-09-25</date>
      <state>stable</state>
      <notes>This is a PEAR-ified version of Useful Inc\'s 1.0.1 release.
Includes an urgent security fix identified by Dan Libby &lt;dan@libby.com&gt;.

</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/XML_RPC-1.2.0RC6',
));
$pearweb->addHtmlConfig('http://pear.php.net/get/XML_RPC-1.2.0RC6.tgz', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'XML_RPC-1.2.0RC6.tgz');
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 =>
  array (
    'type' => 'pkg',
    'rel' => 'has',
    'optional' => 'no',
    'name' => 'PEAR',
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
  ),
  2 =>
  array (
    'channel' => 'pear.php.net',
    'package' => 'File',
    'version' => '1.1.0RC5',
  ),
  3 => 'alpha',
  4 => '1.3.4',
), array (
  'version' => '1.3.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>PEAR</name>
  <summary>PEAR Base System</summary>
  <description>The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception php5-only exception class
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling common operations
   with files and directories
 * the PEAR base class</description>
  <maintainers>
    <maintainer>
      <user>ssb</user>
      <name>Stig Bakken</name>
      <email>stig@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>cox</user>
      <name>Tomas V.V.Cox</name>
      <email>cox@idecnet.com</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>cellog</user>
      <name>Greg Beaver</name>
      <email>cellog@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>pajoye</user>
      <name>Pierre-Alain Joye</name>
      <email>pajoye@pearfr.org</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>mj</user>
      <name>Martin Jansen</name>
      <email>mj@php.net</email>
      <role>developer</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.3.4</version>
    <date>2005-01-01</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>* fix a serious problem caused by a bug in all versions of PHP that caused multiple registration
  of the shutdown function of PEAR.php
* fix Bug #2861: package.dtd does not define NUMBER
* fix Bug #2946: ini_set warning errors
* fix Bug #3026: Dependency type &quot;ne&quot; is needed, &quot;not&quot; is not handled
  properly
* fix Bug #3061: potential warnings in PEAR_Exception
* implement Request #2848: PEAR_ErrorStack logger extends, PEAR_ERRORSTACK_DIE
* implement Request #2914: Dynamic Include Path for run-tests command
* make pear help listing more useful (put how-to-use info at the bottom of the listing)</notes>
    <deps>
      <dep type="php" rel="ge" version="4.2"/>
      <dep type="pkg" rel="ge" version="1.1">Archive_Tar</dep>
      <dep type="pkg" rel="ge" version="1.2">Console_Getopt</dep>
      <dep type="pkg" rel="ge" version="1.0.4">XML_RPC</dep>
      <dep type="ext" rel="has">xml</dep>
      <dep type="ext" rel="has">pcre</dep>
    </deps>
    <provides type="class" name="OS_Guess" />
    <provides type="class" name="System" />
    <provides type="function" name="md5_file" />
    <filelist>
      <file role="php" md5sum="7f552f5a5476a5ef8d180290d7d2a90f" name="OS/Guess.php"/>
      <file role="php" md5sum="f257b9252172a6e174b36499296bb972" name="PEAR/Command/Auth.php"/>
      <file role="php" md5sum="b0c210a914fb6c25507bfb948ff71bac" name="PEAR/Command/Build.php"/>
      <file role="php" md5sum="d90bfb54cf2505747999d8ad1f6c470f" name="PEAR/Command/Common.php"/>
      <file role="php" md5sum="303bbf44d112d510dd3a87ea7e55becf" name="PEAR/Command/Config.php"/>
      <file role="php" md5sum="6fee5ff129e8846d32e54dd5952c214d" name="PEAR/Command/Install.php"/>
      <file role="php" md5sum="91cb07145443768c47f4f8b63d4c5c20" name="PEAR/Command/Package.php"/>
      <file role="php" md5sum="87a9582c0ba5ec6c9fbaba2d518e33dd" name="PEAR/Command/Registry.php"/>
      <file role="php" md5sum="db11793e282f070ad9dcadf2a644aeec" name="PEAR/Command/Remote.php"/>
      <file role="php" md5sum="a0f44e37e237f81404c6f73819a58206" name="PEAR/Command/Mirror.php"/>
      <file role="php" md5sum="8e310f4f947bf7079778ef0a71fcc5b3" name="PEAR/Frontend/CLI.php"/>
      <file role="php" md5sum="3940b7d27d339d72f019b8ab7e8e81b0" name="PEAR/Autoloader.php"/>
      <file role="php" md5sum="7fe4074ba2914cea3d17913b96c0088c" name="PEAR/Command.php"/>
      <file role="php" md5sum="435431d9bec9802f440845fce49f7b4b" name="PEAR/Common.php"/>
      <file role="php" md5sum="cea7df54a1491f7acf6d5290d68cd4ae" name="PEAR/Config.php"/>
      <file role="php" md5sum="e807f3abd241e82703725709c6a405c5" name="PEAR/Dependency.php"/>
      <file role="php" md5sum="bd1e073d4d42516164fe9da30bad9e75" name="PEAR/Downloader.php"/>
      <file role="php" md5sum="3b598325201802e8bb6498ec8c72128e" name="PEAR/Exception.php"/>
      <file role="php" md5sum="119d0fc70323e7a01bbc45a74c7840e4" name="PEAR/ErrorStack.php"/>
      <file role="php" md5sum="cfffe3a0577e4c3c14479b6b962b9f51" name="PEAR/Builder.php"/>
      <file role="php" md5sum="3d3f8c71261fe5c7fa8571b1ccf962fb" name="PEAR/Installer.php"/>
      <file role="php" md5sum="cf9a5b9cbd6cf1d43bbb6151c77a5b4c" name="PEAR/Packager.php"/>
      <file role="php" md5sum="6840ca9ca43e611da23aee935657a67d" name="PEAR/Registry.php"/>
      <file role="php" md5sum="a2a46e11af74a5b73cd1095f54ad5e51" name="PEAR/Remote.php"/>
      <file role="php" md5sum="f7bf4c43bb93ea7524102ae72d8432ad" name="PEAR/RunTest.php"/>
      <file role="script" baseinstalldir="/" md5sum="a3bc543b3f7174ab74108449496cad8b" install-as="pear" name="scripts/pear.sh">
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@php_dir@" to="php_dir" type="pear-config"/>
        <replace from="@pear_version@" to="version" type="package-info"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
      <file role="script" baseinstalldir="/" md5sum="9ba3c9c4bd09c5dbd18af6dab0dab7b4" platform="windows" install-as="pear.bat" name="scripts/pear.bat">
        <replace from="@bin_dir@" to="bin_dir" type="pear-config"/>
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
      <file role="php" baseinstalldir="/" md5sum="ea4d7860cf26ab30a3f9426f8a7df8c1" install-as="pearcmd.php" name="scripts/pearcmd.php">
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@php_dir@" to="php_dir" type="pear-config"/>
        <replace from="@pear_version@" to="version" type="package-info"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
      <file role="data" baseinstalldir="/" md5sum="72ce49e8fe0ec14277d29e15d0f6166f" name="package.dtd"/>
      <file role="data" baseinstalldir="/" md5sum="f2abf8db08a36295645d19b51e319a32" name="template.spec"/>
      <file role="php" baseinstalldir="/" md5sum="58a98a6d63e1089d7e389bc0249eac36" name="PEAR.php"/>
      <file role="php" baseinstalldir="/" md5sum="57012786babadc058fab98c6e6468689" name="System.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.3.1</version>
      <date>2004-04-06</date>
      <state>stable</state>
      <notes>PEAR Installer:

 * Bug #534  pear search doesn\'t list unstable releases
 * Bug #933  CMD Usability Patch
 * Bug #937  throwError() treats every call as static
 * Bug #964 PEAR_ERROR_EXCEPTION causes fatal error
 * Bug #1008 safe mode raises warning

PEAR_ErrorStack:

 * Added experimental error handling, designed to eventually replace
   PEAR_Error.  It should be considered experimental until explicitly marked
   stable.  require_once \'PEAR/ErrorStack.php\' to use.


</notes>
    </release>
    <release>
      <version>1.3.3</version>
      <date>2004-10-28</date>
      <state>stable</state>
      <notes>Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common $_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role=&quot;doc&quot;

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users

</notes>
    </release>
    <release>
      <version>1.3.3.1</version>
      <date>2004-11-08</date>
      <state>stable</state>
      <notes>add RunTest.php to package.xml, make run-tests display failed tests, and use ui
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/PEAR-1.3.4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 =>
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'name' => 'Net_Socket',
    'channel' => 'pear.php.net',
    'package' => 'Net_Socket',
  ),
  2 =>
  array (
    'channel' => 'pear.php.net',
    'package' => 'Net_Sieve',
    'version' => '1.1.1',
  ),
  3 => 'alpha',
  4 => '1.0.5',
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
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 =>
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'optional' => 'no',
    'name' => 'PEAR',
    'channel' => 'pear.php.net',
    'package' => 'PEAR',
  ),
  2 =>
  array (
    'channel' => 'pear.php.net',
    'package' => 'Text_Highlighter',
    'version' => '0.6.2',
  ),
  3 => 'alpha',
  4 => '1.3.4',
), array (
  'version' => '1.3.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>PEAR</name>
  <summary>PEAR Base System</summary>
  <description>The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception php5-only exception class
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling common operations
   with files and directories
 * the PEAR base class</description>
  <maintainers>
    <maintainer>
      <user>ssb</user>
      <name>Stig Bakken</name>
      <email>stig@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>cox</user>
      <name>Tomas V.V.Cox</name>
      <email>cox@idecnet.com</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>cellog</user>
      <name>Greg Beaver</name>
      <email>cellog@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>pajoye</user>
      <name>Pierre-Alain Joye</name>
      <email>pajoye@pearfr.org</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>mj</user>
      <name>Martin Jansen</name>
      <email>mj@php.net</email>
      <role>developer</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.3.4</version>
    <date>2005-01-01</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>* fix a serious problem caused by a bug in all versions of PHP that caused multiple registration
  of the shutdown function of PEAR.php
* fix Bug #2861: package.dtd does not define NUMBER
* fix Bug #2946: ini_set warning errors
* fix Bug #3026: Dependency type &quot;ne&quot; is needed, &quot;not&quot; is not handled
  properly
* fix Bug #3061: potential warnings in PEAR_Exception
* implement Request #2848: PEAR_ErrorStack logger extends, PEAR_ERRORSTACK_DIE
* implement Request #2914: Dynamic Include Path for run-tests command
* make pear help listing more useful (put how-to-use info at the bottom of the listing)</notes>
    <deps>
      <dep type="php" rel="ge" version="4.2"/>
      <dep type="pkg" rel="ge" version="1.1">Archive_Tar</dep>
      <dep type="pkg" rel="ge" version="1.2">Console_Getopt</dep>
      <dep type="pkg" rel="ge" version="1.0.4">XML_RPC</dep>
      <dep type="ext" rel="has">xml</dep>
      <dep type="ext" rel="has">pcre</dep>
    </deps>
    <provides type="class" name="OS_Guess" />
    <provides type="class" name="System" />
    <provides type="function" name="md5_file" />
    <filelist>
      <file role="php" md5sum="7f552f5a5476a5ef8d180290d7d2a90f" name="OS/Guess.php"/>
      <file role="php" md5sum="f257b9252172a6e174b36499296bb972" name="PEAR/Command/Auth.php"/>
      <file role="php" md5sum="b0c210a914fb6c25507bfb948ff71bac" name="PEAR/Command/Build.php"/>
      <file role="php" md5sum="d90bfb54cf2505747999d8ad1f6c470f" name="PEAR/Command/Common.php"/>
      <file role="php" md5sum="303bbf44d112d510dd3a87ea7e55becf" name="PEAR/Command/Config.php"/>
      <file role="php" md5sum="6fee5ff129e8846d32e54dd5952c214d" name="PEAR/Command/Install.php"/>
      <file role="php" md5sum="91cb07145443768c47f4f8b63d4c5c20" name="PEAR/Command/Package.php"/>
      <file role="php" md5sum="87a9582c0ba5ec6c9fbaba2d518e33dd" name="PEAR/Command/Registry.php"/>
      <file role="php" md5sum="db11793e282f070ad9dcadf2a644aeec" name="PEAR/Command/Remote.php"/>
      <file role="php" md5sum="a0f44e37e237f81404c6f73819a58206" name="PEAR/Command/Mirror.php"/>
      <file role="php" md5sum="8e310f4f947bf7079778ef0a71fcc5b3" name="PEAR/Frontend/CLI.php"/>
      <file role="php" md5sum="3940b7d27d339d72f019b8ab7e8e81b0" name="PEAR/Autoloader.php"/>
      <file role="php" md5sum="7fe4074ba2914cea3d17913b96c0088c" name="PEAR/Command.php"/>
      <file role="php" md5sum="435431d9bec9802f440845fce49f7b4b" name="PEAR/Common.php"/>
      <file role="php" md5sum="cea7df54a1491f7acf6d5290d68cd4ae" name="PEAR/Config.php"/>
      <file role="php" md5sum="e807f3abd241e82703725709c6a405c5" name="PEAR/Dependency.php"/>
      <file role="php" md5sum="bd1e073d4d42516164fe9da30bad9e75" name="PEAR/Downloader.php"/>
      <file role="php" md5sum="3b598325201802e8bb6498ec8c72128e" name="PEAR/Exception.php"/>
      <file role="php" md5sum="119d0fc70323e7a01bbc45a74c7840e4" name="PEAR/ErrorStack.php"/>
      <file role="php" md5sum="cfffe3a0577e4c3c14479b6b962b9f51" name="PEAR/Builder.php"/>
      <file role="php" md5sum="3d3f8c71261fe5c7fa8571b1ccf962fb" name="PEAR/Installer.php"/>
      <file role="php" md5sum="cf9a5b9cbd6cf1d43bbb6151c77a5b4c" name="PEAR/Packager.php"/>
      <file role="php" md5sum="6840ca9ca43e611da23aee935657a67d" name="PEAR/Registry.php"/>
      <file role="php" md5sum="a2a46e11af74a5b73cd1095f54ad5e51" name="PEAR/Remote.php"/>
      <file role="php" md5sum="f7bf4c43bb93ea7524102ae72d8432ad" name="PEAR/RunTest.php"/>
      <file role="script" baseinstalldir="/" md5sum="a3bc543b3f7174ab74108449496cad8b" install-as="pear" name="scripts/pear.sh">
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@php_dir@" to="php_dir" type="pear-config"/>
        <replace from="@pear_version@" to="version" type="package-info"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
      <file role="script" baseinstalldir="/" md5sum="9ba3c9c4bd09c5dbd18af6dab0dab7b4" platform="windows" install-as="pear.bat" name="scripts/pear.bat">
        <replace from="@bin_dir@" to="bin_dir" type="pear-config"/>
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
      <file role="php" baseinstalldir="/" md5sum="ea4d7860cf26ab30a3f9426f8a7df8c1" install-as="pearcmd.php" name="scripts/pearcmd.php">
        <replace from="@php_bin@" to="php_bin" type="pear-config"/>
        <replace from="@php_dir@" to="php_dir" type="pear-config"/>
        <replace from="@pear_version@" to="version" type="package-info"/>
        <replace from="@include_path@" to="php_dir" type="pear-config"/>
      </file>
      <file role="data" baseinstalldir="/" md5sum="72ce49e8fe0ec14277d29e15d0f6166f" name="package.dtd"/>
      <file role="data" baseinstalldir="/" md5sum="f2abf8db08a36295645d19b51e319a32" name="template.spec"/>
      <file role="php" baseinstalldir="/" md5sum="58a98a6d63e1089d7e389bc0249eac36" name="PEAR.php"/>
      <file role="php" baseinstalldir="/" md5sum="57012786babadc058fab98c6e6468689" name="System.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.3.1</version>
      <date>2004-04-06</date>
      <state>stable</state>
      <notes>PEAR Installer:

 * Bug #534  pear search doesn\'t list unstable releases
 * Bug #933  CMD Usability Patch
 * Bug #937  throwError() treats every call as static
 * Bug #964 PEAR_ERROR_EXCEPTION causes fatal error
 * Bug #1008 safe mode raises warning

PEAR_ErrorStack:

 * Added experimental error handling, designed to eventually replace
   PEAR_Error.  It should be considered experimental until explicitly marked
   stable.  require_once \'PEAR/ErrorStack.php\' to use.


</notes>
    </release>
    <release>
      <version>1.3.3</version>
      <date>2004-10-28</date>
      <state>stable</state>
      <notes>Installer:
 * fix Bug #1186 raise a notice error on PEAR::Common $_packageName
 * fix Bug #1249 display the right state when using --force option
 * fix Bug #2189 upgrade-all stops if dependancy fails
 * fix Bug #1637 The use of interface causes warnings when packaging with PEAR
 * fix Bug #1420 Parser bug for T_DOUBLE_COLON
 * fix Request #2220 pear5 build fails on dual php4/php5 system
 * fix Bug #1163  pear makerpm fails with packages that supply role=&quot;doc&quot;

Other:
 * add PEAR_Exception class for PHP5 users
 * fix critical problem in package.xml for linux in 1.3.2
 * fix staticPopCallback() in PEAR_ErrorStack
 * fix warning in PEAR_Registry for windows 98 users

</notes>
    </release>
    <release>
      <version>1.3.3.1</version>
      <date>2004-11-08</date>
      <state>stable</state>
      <notes>add RunTest.php to package.xml, make run-tests display failed tests, and use ui
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/PEAR-1.3.4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 =>
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0.1',
    'optional' => 'no',
    'name' => 'XML_Parser',
    'channel' => 'pear.php.net',
    'package' => 'XML_Parser',
  ),
  2 =>
  array (
    'channel' => 'pear.php.net',
    'package' => 'Text_Highlighter',
    'version' => '0.6.2',
  ),
  3 => 'alpha',
  4 => '1.2.4',
), array (
  'version' => '1.2.4',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>XML_Parser</name>
  <summary>XML parsing class based on PHP\'s bundled expat</summary>
  <description>This is an XML parser based on PHPs built-in xml extension.
It supports two basic modes of operation: &quot;func&quot; and &quot;event&quot;.  In &quot;func&quot; mode, it will look for a function named after each element (xmltag_ELEMENT for start tags and xmltag_ELEMENT_ for end tags), and in &quot;event&quot; mode it uses a set of generic callbacks.

Since version 1.2.0 there\'s a new XML_Parser_Simple class that makes parsing of most XML documents easier, by automatically providing a stack for the elements.
Furthermore its now possible to split the parser from the handler object, so you do not have to extend XML_Parser anymore in order to parse a document with it.</description>
  <maintainers>
    <maintainer>
      <user>schst</user>
      <name>Stephan Schmidt</name>
      <email>schst@php-tools.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>ssb</user>
      <name>Stig S?ther Bakken</name>
      <email>stig@php.net</email>
      <role>developer</role>
    </maintainer>
    <maintainer>
      <user>cox</user>
      <name>Tomas V.V.Cox</name>
      <email>cox@php.net</email>
      <role>developer</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.2.4</version>
    <date>2005-01-18</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>- fixed a bug in XML_Parser_Simple when trying to register more than the default handlers and a separate callback object (schst)</notes>
    <deps>
      <dep type="php" rel="ge" version="4.2.0" optional="no"/>
      <dep type="pkg" rel="has" optional="no">PEAR</dep>
    </deps>
    <filelist>
      <file role="doc" baseinstalldir="XML" md5sum="ac28f43f0454ea58be3ad94087888387" name="examples\\xml_parser_file.php"/>
      <file role="doc" baseinstalldir="XML" md5sum="c8f618c3025a7cb684f8a39676cfdc34" name="examples\\xml_parser_file.xml"/>
      <file role="doc" baseinstalldir="XML" md5sum="e1a89d04b270c611e9adac2e4c5e1a24" name="examples\\xml_parser_handler.php"/>
      <file role="doc" baseinstalldir="XML" md5sum="79de8c9caead22bcd7fd0f8216c983f9" name="examples\\xml_parser_simple1.php"/>
      <file role="doc" baseinstalldir="XML" md5sum="75ed659c0ef8f0f572fa5fa0fc03dca8" name="examples\\xml_parser_simple1.xml"/>
      <file role="doc" baseinstalldir="XML" md5sum="0159a812fb317d6cc567c5cbf4a311a1" name="examples\\xml_parser_simple2.php"/>
      <file role="doc" baseinstalldir="XML" md5sum="8cdf0221658ca428972b0404d9f48165" name="examples\\xml_parser_simple2.xml"/>
      <file role="doc" baseinstalldir="XML" md5sum="5b6bdd7bbd9253995ca54a23de94a814" name="examples\\xml_parser_simple_handler.php"/>
      <file role="php" baseinstalldir="XML" md5sum="6832873b5f1b0abc08e9653328b592d5" name="Parser\\Simple.php"/>
      <file role="test" baseinstalldir="XML" md5sum="0ae1afefbab5cb0af203091ae033af7e" name="tests\\001.phpt"/>
      <file role="test" baseinstalldir="XML" md5sum="1074e3c4fe56d4fd2364319ef08e8b86" name="tests\\002.phpt"/>
      <file role="test" baseinstalldir="XML" md5sum="862205cb09dc03c3d412ba657578600d" name="tests\\003.phpt"/>
      <file role="test" baseinstalldir="XML" md5sum="fbb7aba2bcd86c9d937fc9b0f591bdab" name="tests\\004.phpt"/>
      <file role="test" baseinstalldir="XML" md5sum="24bb9c1c927993b689bd0390396c8ecf" name="tests\\005.phpt"/>
      <file role="test" baseinstalldir="XML" md5sum="e87a71928018aa9bde05a9b4b42cfa58" name="tests\\test2.xml"/>
      <file role="test" baseinstalldir="XML" md5sum="e87a71928018aa9bde05a9b4b42cfa58" name="tests\\test3.xml"/>
      <file role="php" baseinstalldir="XML" md5sum="7657ca06627fa479b22da0fcbd03a001" name="Parser.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.1.0beta1</version>
      <date>2004-04-16</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>- Fixed memory leaks parsing many documents or big files (mroch)
- Fixed setInput() url detection regex (mroch)
- Added setInputString() method, allowing strings to be passed as input (schst)
- Error handling rewritten (cox)
- Increased the overall parsing speed (cox)
- Added free() method (schst
- Added reset() method, that is called when parsing a document so it is possible to parse more than one document per instance (schst)
- Added error codes (schst)
- revamped documentation (cox, schst)
- Fixed bug #516 (url fopen and safe mode) (schst)
- Fixed bug #637 (dependency on PEAR) (schst)
- improved parse() and parseString() to be able to parse more than one document (schst)
- added PHP5 constructor (schst)
- moved xml_parser_create() to _create() for PHP5 compatibility (schst)
- added dependency on PHP 4.2

Thanks to Marshall Roch for commments and contributions and Tomas V.V. Cox
for applying a lot of fixes and improvements.
</notes>
    </release>
    <release>
      <version>1.1.0beta2</version>
      <date>2004-04-18</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>beta2:
- Fixed calling of __construct

beta1:
- Fixed memory leaks parsing many documents or big files (mroch)
- Fixed setInput() url detection regex (mroch)
- Added setInputString() method, allowing strings to be passed as input (schst)
- Error handling rewritten (cox)
- Increased the overall parsing speed (cox)
- Added free() method (schst
- Added reset() method, that is called when parsing a document so it is possible to parse more than one document per instance (schst)
- Added error codes (schst)
- revamped documentation (cox, schst)
- Fixed bug #516 (url fopen and safe mode) (schst)
- Fixed bug #637 (dependency on PEAR) (schst)
- improved parse() and parseString() to be able to parse more than one document (schst)
- added PHP5 constructor (schst)
- moved xml_parser_create() to _create() for PHP5 compatibility (schst)
- added dependency on PHP 4.2

Thanks to Marshall Roch for commments and contributions and Tomas V.V. Cox
for applying a lot of fixes and improvements.
</notes>
    </release>
    <release>
      <version>1.1.0</version>
      <date>2004-04-23</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>- Fixed memory leaks parsing many documents or big files (mroch)
- Fixed setInput() url detection regex (mroch)
- Added setInputString() method, allowing strings to be passed as input (schst)
- Error handling rewritten (cox)
- Increased the overall parsing speed (cox)
- Added free() method (schst
- Added reset() method, that is called when parsing a document so it is possible to parse more than one document per instance (schst)
- Added error codes (schst)
- revamped documentation (cox, schst)
- Fixed bug #516 (url fopen and safe mode) (schst)
- Fixed bug #637 (dependency on PEAR) (schst)
- improved parse() and parseString() to be able to parse more than one document (schst)
- added PHP5 constructor (schst)
- moved xml_parser_create() to _create() for PHP5 compatibility (schst)
- added dependency on PHP 4.2

Thanks to Marshall Roch for commments and contributions and Tomas V.V. Cox
for applying a lot of fixes and improvements.
</notes>
    </release>
    <release>
      <version>1.2.0beta1</version>
      <date>2004-05-17</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>added new class XML_Parser_Simple that provides a stack for the elements so the user only needs to implement one method to handle the tag and cdata.
</notes>
    </release>
    <release>
      <version>1.2.0beta2</version>
      <date>2004-05-24</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>XML_Parser:
- fixed bug with setMode()
- moved the init routines for the handlers in _initHandlers()
XML_Parser_Simple:
- fixed bug with character data (did not get parsed)
- fixed bug with setMode()
- some refactoring
- added getCurrentDepth() to retrieve the tag depth
- added addToData()
- added new example
</notes>
    </release>
    <release>
      <version>1.2.0beta3</version>
      <date>2004-05-25</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>- added setHandlerObj() which allows you to have the parser separate from the handler methods
</notes>
    </release>
    <release>
      <version>1.2.0</version>
      <date>2004-05-28</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>- added setHandlerObj() which allows you to have the parser separate from the handler methods
- fixed bug with setMode()
- moved the init routines for the handlers in _initHandlers()
- added new examples
- fixed test files so they do not fail because of different resource ids
XML_Parser_Simple:
- added new class XML_Parser_Simple that provides a stack for the elements so the user only needs to implement one method to handle the tag and cdata.
</notes>
    </release>
    <release>
      <version>1.2.1</version>
      <date>2004-10-04</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>fixed bug #2442: Call to &quot;xmltag_ELEMENT_&quot; not correctly managed in function funcEndHandler
</notes>
    </release>
    <release>
      <version>1.2.2beta1</version>
      <date>2004-12-22</date>
      <license>PHP License</license>
      <state>beta</state>
      <notes>- fixed small notice in XML_Parser::free(),
- fixed Bug #2939: bug in error routine leads to segmentation fault (raiseError does not free the internal resources anymore)
</notes>
    </release>
    <release>
      <version>1.2.2</version>
      <date>2004-12-22</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>- fixed small notice in XML_Parser::free(),
- fixed Bug #2939: bug in error routine leads to segmentation fault (raiseError does not free the internal resources anymore)
</notes>
    </release>
    <release>
      <version>1.2.3</version>
      <date>2005-01-17</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>- fixed a bug that occured when using \'func\' mode and setHandlerObj() (schst)
- added default handlers for \'func\' mode (schst)
</notes>
    </release>
    <release>
      <version>1.2.4</version>
      <date>2005-01-18</date>
      <license>PHP License</license>
      <state>stable</state>
      <notes>- fixed a bug in XML_Parser_Simple when trying to register more than the default handlers and a separate callback object (schst)
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/XML_Parser-1.2.4',
));
$pearweb->addXmlrpcConfig("pear.php.net", "package.getDepDownloadURL", array (
  0 => '1.0',
  1 =>
  array (
    'type' => 'pkg',
    'rel' => 'ge',
    'version' => '1.0',
    'optional' => 'no',
    'name' => 'Console_Getopt',
    'channel' => 'pear.php.net',
    'package' => 'Console_Getopt',
  ),
  2 =>
  array (
    'channel' => 'pear.php.net',
    'package' => 'Text_Highlighter',
    'version' => '0.6.2',
  ),
  3 => 'alpha',
  4 => '1.2',
), array (
  'version' => '1.2',
  'info' => '<?xml version="1.0" encoding="ISO-8859-1" ?>
<!DOCTYPE package SYSTEM "http://pear.php.net/dtd/package-1.0">
<package version="1.0">
  <name>Console_Getopt</name>
  <summary>Command-line option parser</summary>
  <description>This is a PHP implementation of &quot;getopt&quot; supporting both
short and long options.</description>
  <maintainers>
    <maintainer>
      <user>andrei</user>
      <name>Andrei Zmievski</name>
      <email>andrei@php.net</email>
      <role>lead</role>
    </maintainer>
    <maintainer>
      <user>ssb</user>
      <name>Stig Bakken</name>
      <email>stig@php.net</email>
      <role>developer</role>
    </maintainer>
  </maintainers>
  <release>
    <version>1.2</version>
    <date>2003-12-11</date>
    <license>PHP License</license>
    <state>stable</state>
    <notes>Fix to preserve BC with 1.0 and allow correct behaviour for new users</notes>
    <provides type="class" name="Console_Getopt" />
    <provides type="function" name="Console_Getopt::getopt2" />
    <provides type="function" name="Console_Getopt::getopt" />
    <provides type="function" name="Console_Getopt::doGetopt" />
    <provides type="function" name="Console_Getopt::readPHPArgv" />
    <filelist>
      <file role="php" md5sum="add0781a1cae0b3daf5e8521b8a954cc" name="Console/Getopt.php"/>
    </filelist>
  </release>
  <changelog>
    <release>
      <version>1.0</version>
      <date>2002-09-13</date>
      <state>stable</state>
      <notes>Stable release
</notes>
    </release>
    <release>
      <version>0.11</version>
      <date>2002-05-26</date>
      <state>beta</state>
      <notes>POSIX getopt compatibility fix: treat first element of args
       array as command name

</notes>
    </release>
    <release>
      <version>0.10</version>
      <date>2002-05-12</date>
      <state>beta</state>
      <notes>Packaging fix
</notes>
    </release>
    <release>
      <version>0.9</version>
      <date>2002-05-12</date>
      <state>beta</state>
      <notes>Initial release
</notes>
    </release>
  </changelog>
</package>',
  'url' => 'http://pear.php.net/get/Console_Getopt-1.2',
));
$pearweb->addHtmlConfig('http://pear.php.net/get/File-1.1.0RC5.tgz', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'File-1.1.0RC5.tgz');
$p1 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'File-1.1.0RC3.tgz';
$p2 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Net_Sieve-1.1.0.tgz';
$p3 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Text_Highlighter-0.6.1.tgz';
$p4 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Text_Wiki-0.23.1.tgz';
$p5 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'XML_RPC-1.2.0RC3.tgz';
$p6 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Net_Socket-1.0.5.tgz';
$p7 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'PEAR-1.3.4.tgz';
$p8 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Console_Getopt-1.2.tgz';
$p9 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'XML_Parser-1.2.4.tgz';
$p10 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'Archive_Tar-1.2.tgz';

for ($i = 1; $i <= 10; $i++) {
    $packages[] = ${"p$i"};
}

$config->set('preferred_state', 'alpha');
$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setExtensions(array('xml' => '1.0', 'pcre' => '1.0'));
$command->run('install', array(), $packages);
$phpunit->assertNoErrors('after install');
$fakelog->getLog();
$command->_reset_downloader();
$command->run('upgrade-all', array(), array());
$phpunit->assertNoErrors('after upgrade');
$phpunit->assertEquals(
array (
  0 =>
  array (
    'info' =>
    array (
      'data' => 'Will upgrade channel://pear.php.net/file',
    ),
    'cmd' => 'upgrade-all',
  ),
  1 =>
  array (
    'info' =>
    array (
      'data' => 'Will upgrade channel://pear.php.net/net_sieve',
    ),
    'cmd' => 'upgrade-all',
  ),
  2 =>
  array (
    'info' =>
    array (
      'data' => 'Will upgrade channel://pear.php.net/text_highlighter',
    ),
    'cmd' => 'upgrade-all',
  ),
  3 =>
  array (
    'info' =>
    array (
      'data' => 'Will upgrade channel://pear.php.net/text_wiki',
    ),
    'cmd' => 'upgrade-all',
  ),
  4 =>
  array (
    'info' =>
    array (
      'data' => 'Will upgrade channel://pear.php.net/xml_rpc',
    ),
    'cmd' => 'upgrade-all',
  ),
  5 =>
  array (
    0 => 3,
    1 => 'Notice: package "pear/File" required dependency "pear/PEAR" will not be automatically downloaded',
  ),
), array_slice($fakelog->getLog(), 0, 6),
'upgrade-all log');

$reg = &$config->getRegistry();
$phpunit->assertEquals('1.1.0RC5', $reg->packageInfo('File', 'version'), 'File');
$phpunit->assertEquals('1.1.1', $reg->packageInfo('Net_Sieve', 'version'), 'Net_Sieve');
$phpunit->assertEquals('0.6.2', $reg->packageInfo('Text_Highlighter', 'version'), 'Text_Highlighter');
$phpunit->assertEquals('0.25.0', $reg->packageInfo('Text_Wiki', 'version'), 'Text_Wiki');
$phpunit->assertEquals('1.2.0RC6', $reg->packageInfo('XML_RPC', 'version'), 'XML_RPC');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
