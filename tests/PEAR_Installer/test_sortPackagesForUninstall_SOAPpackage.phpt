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

$p1 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'SOAP-0.8.1.tgz';
$p2 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Mail_Mime-1.2.1.tgz';
$p3 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'HTTP_Request-1.2.4.tgz';
$p4 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Net_URL-1.0.14.tgz';
$p5 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Net_DIME-0.3.tgz';
$p6 = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test_sortPackagesForUninstall' . DIRECTORY_SEPARATOR . 'Net_Socket-1.0.5.tgz';

for ($i = 1; $i <= 6; $i++) {
    $packages[] = ${"p$i"};
}
$dl = new PEAR_Installer($fakelog);
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
            $GLOBALS['__Stupid_php4_a'] = new test_PEAR_Downloader($this->ui, array(), $this->config);
        }
        return $GLOBALS['__Stupid_php4_a'];
    }

    function &getInstaller()
    {
        if (!isset($GLOBALS['__Stupid_php4_b'])) {
            $GLOBALS['__Stupid_php4_b'] = new test_PEAR_Installer($this->ui, array(), $this->config);
        }
        return $GLOBALS['__Stupid_php4_b'];
    }
}
$command = new test_PEAR_Command_Install($fakelog, $config);
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
