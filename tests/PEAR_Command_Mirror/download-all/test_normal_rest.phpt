--TEST--
download-all command (REST)
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
$chan = &$reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);
$ch = new PEAR_ChannelFile;
$ch->setName('smoog');
$ch->setBaseURL('REST1.0', 'http://smoog/rest/');
$ch->setSummary('smoog');
$reg->addChannel($ch);
$pathtoStableAPC = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'APC-1.3.0.tgz';
$pathtoAlphaAPC = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'APC-1.4.0a1.tgz';
$pathtoSmoogAPC = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR .
    'APC-1.5.0a1.tgz';
$pearweb->addHtmlConfig('http://pear.php.net/get/APC-1.3.0.tgz', $pathtoStableAPC);
$pearweb->addHtmlConfig('http://pear.php.net/get/APC-1.4.0a1.tgz', $pathtoAlphaAPC);
$pearweb->addHtmlConfig('http://smoog/get/APC-1.5.0a1.tgz', $pathtoSmoogAPC);
$pearweb->addRESTConfig("http://smoog/rest/p/packages.xml", '<?xml version="1.0" ?>
<a xmlns="http://pear.php.net/dtd/rest.allpackages"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allpackages
    http://pear.php.net/dtd/rest.allpackages.xsd">
<c>pear.php.net</c>
 <p>APC</p>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/apc/allreleases.xml", '<?xml version="1.0"?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>APC</p>
 <c>pear.php.net</c>
 <r><v>1.4.0a1</v><s>alpha</s></r>
 <r><v>1.3.0</v><s>stable</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://smoog/rest/r/apc/allreleases.xml", '<?xml version="1.0"?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>APC</p>
 <c>smoog</c>
 <r><v>1.5.0a1</v><s>alpha</s></r>
</a>', 'text/xml');
$pearweb->addRESTConfig("http://smoog/rest/r/apc/1.5.0a1.xml", '<?xml version="1.0"?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/apc">APC</p>
 <c>smoog</c>
 <v>1.5.0a1</v>
 <st>alpha</st>
 <l>PHP License</l>
 <m>rasmus</m>
 <s>Alternative PHP Cache</s>
 <d>APC is the Alternative PHP Cache. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.</d>
 <da>2005-04-17 18:40:51</da>
 <n>Release notes</n>
 <f>252733</f>
 <g>http://smoog/get/APC-1.5.a1</g>
 <x xlink:href="package.1.5.0a1.xml"/>

</r>', 'text/xml');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/apc/1.3.0.xml", '<?xml version="1.0"?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/apc">APC</p>
 <c>pear.php.net</c>
 <v>1.3.0</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>rasmus</m>
 <s>Alternative PHP Cache</s>
 <d>APC is the Alternative PHP Cache. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.</d>
 <da>2005-04-17 18:40:51</da>
 <n>Release notes</n>
 <f>252733</f>
 <g>http://pear.php.net/get/APC-1.3.0</g>
 <x xlink:href="package.1.3.0.xml"/>

</r>', 'text/xml');
$pearweb->addRESTConfig("http://smoog/rest/p/apc/deps.1.5.0a1.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:2:{s:3:"min";s:3:"4.2";s:3:"max";s:5:"6.0.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:7:"1.4.0dev13";}}}', 'text/plain');
$pearweb->addRESTConfig("http://pear.php.net/rest/r/apc/deps.1.3.0.txt", 'b:0;', 'text/plain');
$pearweb->addRESTConfig("http://pear.php.net/rest/p/packages.xml", '<?xml version="1.0" ?>
<a xmlns="http://pear.php.net/dtd/rest.allpackages"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.allpackages
    http://pear.php.net/dtd/rest.allpackages.xsd">
<c>pear.php.net</c>
 <p>APC</p>
</a>', 'text/xml');
$config->set('preferred_state', 'stable');
$save = getcwd();
chdir($temp_path);
$e = $command->run('download-all', array(), array());
$phpunit->assertNoErrors('after');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'Using Channel pear.php.net',
    'cmd' => 'no command',
  ),
  1 => 
  array (
    'info' => 'Using Preferred State of stable',
    'cmd' => 'no command',
  ),
  2 => 
  array (
    'info' => 'Gathering release information, please wait...',
    'cmd' => 'no command',
  ),
  3 => 
  array (
    0 => 1,
    1 => 'downloading APC-1.3.0.tgz ...',
  ),
  4 => 
  array (
    0 => 1,
    1 => 'Starting to download APC-1.3.0.tgz (516 bytes)',
  ),
  5 => 
  array (
    0 => 1,
    1 => '.',
  ),
  6 => 
  array (
    0 => 1,
    1 => '...done: 516 bytes',
  ),
  7 => 
  array (
    'info' => 'File ' . $temp_path . DIRECTORY_SEPARATOR . 'APC-1.3.0.tgz downloaded',
    'cmd' => 'download',
  ),
), $fakelog->getLog(), 'log');
$phpunit->assertFileExists($temp_path . DIRECTORY_SEPARATOR . 'APC-1.3.0.tgz', 'APC 1.3.0');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'http://pear.php.net/rest/p/packages.xml',
    1 => '200',
  ),
  1 => 
  array (
    0 => 'http://pear.php.net/rest/r/apc/allreleases.xml',
    1 => '200',
  ),
  2 => 
  array (
    0 => 'http://pear.php.net/rest/r/apc/1.3.0.xml',
    1 => '200',
  ),
  3 => 
  array (
    0 => 'http://pear.php.net/rest/r/apc/deps.1.3.0.txt',
    1 => '200',
  ),
)
, $pearweb->getRESTCalls(), 'rest calls');
chdir($save);
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
