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
$packageDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR;
$phpDir     = $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR;
$docDir     = $temp_path . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR;
$dataDir    = $temp_path . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
$testDir    = $temp_path . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR;

$packages[] = $packageDir . 'SOAP-0.8.1.tgz';
$packages[] = $packageDir . 'Mail_Mime-1.2.1.tgz';
$packages[] = $packageDir . 'HTTP_Request-1.2.4.tgz';
$packages[] = $packageDir . 'Net_URL-1.0.14.tgz';
$packages[] = $packageDir . 'Net_DIME-0.3.tgz';
$packages[] = $packageDir . 'Net_Socket-1.0.5.tgz';

$_test_dep->setPHPVersion('4.3.10');
$_test_dep->setExtensions(array('pcre' => '1.0'));
$command->run('install', array(), $packages);
$phpunit->assertNoErrors('after install');

$fakelog->getLog();
$paramnames = array('Mail_Mime', 'SOAP', 'Net_DIME', 'HTTP_Request', 'Net_URL', 'Net_Socket');
$command->run('uninstall', array(), $paramnames);

$phpunit->assertNoErrors('after uninstall');

$logging = $fakelog->getLog();
$phpunit->assertEquals(array (
  0 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Base.php',
  ),
  1 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Client.php',
  ),
  2 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Disco.php',
  ),
  3 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Fault.php',
  ),
  4 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Parser.php',
  ),
  5 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server.php',
  ),
  6 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport.php',
  ),
  7 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Value.php',
  ),
  8 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'WSDL.php',
  ),
  9 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'attachment.php',
  ),
  10 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'client.php',
  ),
  11 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'com_client.php',
  ),
  12 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'disco_server.php',
  ),
  13 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_client.php',
  ),
  14 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_gateway.php',
  ),
  15 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_gateway.php',
  ),
  16 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_server.php',
  ),
  17 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_server.php',
  ),
  18 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_server.php',
  ),
  19 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_types.php',
  ),
  20 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server.php',
  ),
  21 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server2.php',
  ),
  22 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'smtp.php',
  ),
  23 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'stockquote.php',
  ),
  24 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_client.php',
  ),
  25 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_daemon.pl',
  ),
  26 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_server.php',
  ),
  27 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'wsdl_client.php',
  ),
  28 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'genproxy.php',
  ),
  29 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'HTTP.php',
  ),
  30 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'SMTP.php',
  ),
  31 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  32 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email.php',
  ),
  33 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email_Gateway.php',
  ),
  34 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  35 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'dateTime.php',
  ),
  36 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'duration.php',
  ),
  37 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'hexBinary.php',
  ),
  38 =>
  array (
    0 => 2,
    1 => 'about to commit 38 file operations for SOAP',
  ),
  39 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Base.php',
  ),
  40 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Client.php',
  ),
  41 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Disco.php',
  ),
  42 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Fault.php',
  ),
  43 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Parser.php',
  ),
  44 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server.php',
  ),
  45 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport.php',
  ),
  46 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Value.php',
  ),
  47 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'WSDL.php',
  ),
  48 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'attachment.php',
  ),
  49 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'client.php',
  ),
  50 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'com_client.php',
  ),
  51 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'disco_server.php',
  ),
  52 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_client.php',
  ),
  53 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_gateway.php',
  ),
  54 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_gateway.php',
  ),
  55 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_pop_server.php',
  ),
  56 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'email_server.php',
  ),
  57 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_server.php',
  ),
  58 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'example_types.php',
  ),
  59 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server.php',
  ),
  60 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'server2.php',
  ),
  61 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'smtp.php',
  ),
  62 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'stockquote.php',
  ),
  63 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_client.php',
  ),
  64 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_daemon.pl',
  ),
  65 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'tcp_server.php',
  ),
  66 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example' . DIRECTORY_SEPARATOR . 'wsdl_client.php',
  ),
  67 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'genproxy.php',
  ),
  68 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'HTTP.php',
  ),
  69 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'SMTP.php',
  ),
  70 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  71 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email.php',
  ),
  72 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'Email_Gateway.php',
  ),
  73 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server' . DIRECTORY_SEPARATOR . 'TCP.php',
  ),
  74 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'dateTime.php',
  ),
  75 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'duration.php',
  ),
  76 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR . 'hexBinary.php',
  ),
  77 =>
  array (
    0 => 2,
    1 => 'successfully committed 38 file operations',
  ),
  78 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'tools',
  ),
  79 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type',
  ),
  80 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport',
  ),
  81 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server',
  ),
  82 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'SOAP',
  ),
  83 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example',
  ),
  84 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $docDir . 'SOAP',
  ),
  85 =>
  array (
    0 => 2,
    1 => 'about to commit 7 file operations for SOAP',
  ),
  86 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'tools',
  ),
  87 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Type',
  ),
  88 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Transport',
  ),
  89 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'SOAP' . DIRECTORY_SEPARATOR . 'Server',
  ),
  90 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'SOAP',
  ),
  91 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $docDir . 'SOAP' . DIRECTORY_SEPARATOR . 'example',
  ),
  92 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $docDir . 'SOAP',
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
    1 => 'adding to transaction: delete ' . $phpDir . 'HTTP' . DIRECTORY_SEPARATOR . 'Request.php',
  ),
  96 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'HTTP' . DIRECTORY_SEPARATOR . 'Request' . DIRECTORY_SEPARATOR . 'Listener.php',
  ),
  97 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  98 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'download-progress.php',
  ),
  99 =>
  array (
    0 => 2,
    1 => 'about to commit 4 file operations for HTTP_Request',
  ),
  100 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'HTTP' . DIRECTORY_SEPARATOR . 'Request.php',
  ),
  101 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'HTTP' . DIRECTORY_SEPARATOR . 'Request' . DIRECTORY_SEPARATOR . 'Listener.php',
  ),
  102 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  103 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'download-progress.php',
  ),
  104 =>
  array (
    0 => 2,
    1 => 'successfully committed 4 file operations',
  ),
  105 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'HTTP' . DIRECTORY_SEPARATOR . 'Request',
  ),
  106 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'HTTP',
  ),
  107 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $docDir . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs',
  ),
  108 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $docDir . 'HTTP_Request',
  ),
  109 =>
  array (
    0 => 2,
    1 => 'about to commit 4 file operations for HTTP_Request',
  ),
  110 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'HTTP' . DIRECTORY_SEPARATOR . 'Request',
  ),
  111 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'HTTP',
  ),
  112 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $docDir . 'HTTP_Request' . DIRECTORY_SEPARATOR . 'docs',
  ),
  113 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $docDir . 'HTTP_Request',
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
    1 => 'adding to transaction: delete ' . $phpDir . 'Net' . DIRECTORY_SEPARATOR . 'Socket.php',
  ),
  117 =>
  array (
    0 => 2,
    1 => 'about to commit 1 file operations for Net_Socket',
  ),
  118 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'Net' . DIRECTORY_SEPARATOR . 'Socket.php',
  ),
  119 =>
  array (
    0 => 2,
    1 => 'successfully committed 1 file operations',
  ),
  120 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'Net',
  ),
  121 =>
  array (
    0 => 2,
    1 => 'about to commit 1 file operations for Net_Socket',
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
    1 => 'adding to transaction: delete ' . $phpDir . 'Net' . DIRECTORY_SEPARATOR . 'URL.php',
  ),
  125 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $docDir . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  126 =>
  array (
    0 => 2,
    1 => 'about to commit 2 file operations for Net_URL',
  ),
  127 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'Net' . DIRECTORY_SEPARATOR . 'URL.php',
  ),
  128 =>
  array (
    0 => 3,
    1 => '+ rm ' . $docDir . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'example.php',
  ),
  129 =>
  array (
    0 => 2,
    1 => 'successfully committed 2 file operations',
  ),
  130 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'Net',
  ),
  131 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $docDir . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs',
  ),
  132 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $docDir . 'Net_URL',
  ),
  133 =>
  array (
    0 => 2,
    1 => 'about to commit 3 file operations for Net_URL',
  ),
  134 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $docDir . 'Net_URL' . DIRECTORY_SEPARATOR . 'docs',
  ),
  135 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $docDir . 'Net_URL',
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
    1 => 'adding to transaction: delete ' . $phpDir . 'Net' . DIRECTORY_SEPARATOR . 'DIME.php',
  ),
  139 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $testDir . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_message_test.php',
  ),
  140 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $testDir . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_record_test.php',
  ),
  141 =>
  array (
    0 => 2,
    1 => 'about to commit 3 file operations for Net_DIME',
  ),
  142 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'Net' . DIRECTORY_SEPARATOR . 'DIME.php',
  ),
  143 =>
  array (
    0 => 3,
    1 => '+ rm ' . $testDir . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_message_test.php',
  ),
  144 =>
  array (
    0 => 3,
    1 => '+ rm ' . $testDir . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'dime_record_test.php',
  ),
  145 =>
  array (
    0 => 2,
    1 => 'successfully committed 3 file operations',
  ),
  146 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $testDir . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test',
  ),
  147 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $testDir . 'Net_DIME',
  ),
  148 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'Net',
  ),
  149 =>
  array (
    0 => 2,
    1 => 'about to commit 3 file operations for Net_DIME',
  ),
  150 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $testDir . 'Net_DIME' . DIRECTORY_SEPARATOR . 'test',
  ),
  151 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $testDir . 'Net_DIME',
  ),
  152 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'Net',
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
    1 => 'adding to transaction: delete ' . $phpDir . 'Mail' . DIRECTORY_SEPARATOR . 'mime.php',
  ),
  156 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'Mail' . DIRECTORY_SEPARATOR . 'mimeDecode.php',
  ),
  157 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $phpDir . 'Mail' . DIRECTORY_SEPARATOR . 'mimePart.php',
  ),
  158 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $dataDir . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.dtd',
  ),
  159 =>
  array (
    0 => 3,
    1 => 'adding to transaction: delete ' . $dataDir . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.xsl',
  ),
  160 =>
  array (
    0 => 2,
    1 => 'about to commit 5 file operations for Mail_Mime',
  ),
  161 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'Mail' . DIRECTORY_SEPARATOR . 'mime.php',
  ),
  162 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'Mail' . DIRECTORY_SEPARATOR . 'mimeDecode.php',
  ),
  163 =>
  array (
    0 => 3,
    1 => '+ rm ' . $phpDir . 'Mail' . DIRECTORY_SEPARATOR . 'mimePart.php',
  ),
  164 =>
  array (
    0 => 3,
    1 => '+ rm ' . $dataDir . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.dtd',
  ),
  165 =>
  array (
    0 => 3,
    1 => '+ rm ' . $dataDir . 'Mail_Mime' . DIRECTORY_SEPARATOR . 'xmail.xsl',
  ),
  166 =>
  array (
    0 => 2,
    1 => 'successfully committed 5 file operations',
  ),
  167 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $phpDir . 'Mail',
  ),
  168 =>
  array (
    0 => 3,
    1 => 'adding to transaction: rmdir ' . $dataDir . 'Mail_Mime',
  ),
  169 =>
  array (
    0 => 2,
    1 => 'about to commit 2 file operations for Mail_Mime',
  ),
  170 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $phpDir . 'Mail',
  ),
  171 =>
  array (
    0 => 3,
    1 => '+ rmdir ' . $dataDir . 'Mail_Mime',
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
), $logging, 'log after uninstall');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
