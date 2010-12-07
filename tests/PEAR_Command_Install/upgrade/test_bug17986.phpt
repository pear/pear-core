--TEST--
Bug #17986: PEAR Installer cannot handle files moved between packages
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';

$chan = $reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);

$ch = new PEAR_ChannelFile;
$ch->setName('pear.phpunit.de');
$ch->setSummary('phpunit');
$ch->setAlias('phpunit');
$ch->setBaseURL('REST1.0', 'http://pear.phpunit.de/rest/');
$ch->setBaseURL('REST1.1', 'http://pear.phpunit.de/rest/');
$ch->setBaseURL('REST1.2', 'http://pear.phpunit.de/rest/');
$ch->setBaseURL('REST1.3', 'http://pear.phpunit.de/rest/');

$phpunit->assertTrue($reg->addChannel($ch), 'phpunit setup');

$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bug17986'. DIRECTORY_SEPARATOR;

$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHPUnit-3.4.14.tgz', $path . 'PHPUnit-3.4.14.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHPUnit-3.5.5.tgz', $path . 'PHPUnit-3.5.5.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/File_Iterator-1.2.3.tgz', $path . 'File_Iterator-1.2.3.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/Text_Template-1.1.0.tgz', $path . 'Text_Template-1.1.0.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHP_Timer-1.0.0.tgz', $path . 'PHP_Timer-1.0.0.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHPUnit_MockObject-1.0.3.tgz', $path . 'PHPUnit_MockObject-1.0.3.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHPUnit_Selenium-1.0.1.tgz', $path . 'PHPUnit_Selenium-1.0.1.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHP_CodeCoverage-1.0.2.tgz', $path . 'PHP_CodeCoverage-1.0.2.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/DbUnit-1.0.0.tgz', $path . 'DbUnit-1.0.0.tgz');
$GLOBALS['pearweb']->addHtmlConfig('http://pear.phpunit.de/get/PHP_TokenStream-1.0.1.tgz', $path . 'PHP_TokenStream-1.0.1.tgz');

// Setup the required files for installing PHPUnit 3.4.14

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>PHPUnit</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>3.5.5</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.5.4</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.5.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.5.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.5.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.5.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.5.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.5.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.5.0beta1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.15</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.14</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.13</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.12</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.11</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.10</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.9</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.8</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.7</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.6</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.5</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.4.0RC3</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0beta6</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0beta5</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0beta4</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0beta3</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0beta2</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0beta1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.4.0alpha4</v>
        <s>alpha</s>
    </r>
    <r>
        <v>3.4.0alpha3</v>
        <s>alpha</s>
    </r>
    <r>
        <v>3.4.0alpha2</v>
        <s>alpha</s>
    </r>
    <r>
        <v>3.3.17</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.16</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.15</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.14</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.13</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.12</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.11</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.10</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.9</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.8</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.7</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.6</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.5</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.4</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.3.0RC3</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.3.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.3.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.3.0beta4</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.3.0beta3</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.3.0beta2</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.3.0beta1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.2.21</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.20</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.19</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.18</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.17</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.16</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.15</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.14</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.14RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>3.2.13</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.12</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.11</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.10</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.9</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.8</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.7</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.6</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.5</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.4</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.2.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.9</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.8</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.7</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.6</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.5</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.4</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.1.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.6</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.5</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.4</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>3.0.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>2.3.6</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.3.3</v>
        <s>stable</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>PHPUnit</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>3.5.5</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.4</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.3</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.2</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.1</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.0RC2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.0RC1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.5.0beta1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>3.4.15</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.14</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.13</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.12</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.11</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.10</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.9</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.8</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.7</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.6</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.5</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.3</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.2</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.1</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0RC3</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0RC2</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0RC1</v>
        <s>beta</s>
        <m>5.2.10</m>
    </r>
    <r>
        <v>3.4.0beta6</v>
        <s>beta</s>
        <m>5.2.10</m>
    </r>
    <r>
        <v>3.4.0beta5</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0beta4</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0beta3</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0beta2</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0beta1</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0alpha4</v>
        <s>alpha</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0alpha3</v>
        <s>alpha</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.4.0alpha2</v>
        <s>alpha</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.17</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.16</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.15</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.14</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.13</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.12</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.11</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.10</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.9</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.8</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.7</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.6</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.5</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.4</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.3</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.2</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.1</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0RC3</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0RC2</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0RC1</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0beta4</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0beta3</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0beta2</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.3.0beta1</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.21</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.20</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.19</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.18</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.17</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.16</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.15</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.14</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.14RC1</v>
        <s>beta</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.13</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.12</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.11</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.10</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.9</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.8</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.7</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.6</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.5</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.4</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.3</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.2</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.1</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.2.0</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.9</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.8</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.7</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.6</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.5</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.4</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.3</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.2</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.1.1</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.6</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.5</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.4</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.3</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.2</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.1</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>3.0.0</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>2.3.6</v>
        <s>stable</s>
        <m>5.0.2</m>
    </r>
    <r>
        <v>1.3.3</v>
        <s>stable</s>
        <m>4.1.0</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/phpunit/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>PHPUnit</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Regression testing framework for unit tests.</s>
<d>PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP. This is the version to be used with PHP 5.</d>
<r xlink:href="/rest/r/phpunit" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/3.4.14.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/phpunit">PHPUnit</p>
    <c>pear.phpunit.de</c>
    <v>3.4.14</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Regression testing framework for unit tests.</s>
    <d>PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP. This is the version to be used with PHP 5.</d>
    <da>2010-06-16 15:39:13</da>
    <n>
http://github.com/sebastianbergmann/phpunit/blob/3.4/README.markdown
 </n>
    <f>254983</f>
    <g>http://pear.phpunit.de/get/PHPUnit-3.4.14</g>
    <x xlink:href="package.3.4.14.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/deps.3.4.14.txt", 'a:2:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.1.4";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.8.1";}s:9:"extension";a:4:{i:0;a:1:{s:4:"name";s:3:"dom";}i:1;a:1:{s:4:"name";s:4:"pcre";}i:2;a:1:{s:4:"name";s:10:"reflection";}i:3;a:1:{s:4:"name";s:3:"spl";}}}s:8:"optional";a:2:{s:7:"package";a:3:{i:0;a:3:{s:4:"name";s:14:"Image_GraphViz";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"1.2.1";}i:1;a:2:{s:4:"name";s:3:"Log";s:7:"channel";s:12:"pear.php.net";}i:2;a:3:{s:4:"name";s:4:"YAML";s:7:"channel";s:24:"pear.symfony-project.com";s:3:"min";s:5:"1.0.2";}}s:9:"extension";a:7:{i:0;a:1:{s:4:"name";s:4:"json";}i:1;a:1:{s:4:"name";s:3:"pdo";}i:2;a:1:{s:4:"name";s:9:"pdo_mysql";}i:3;a:1:{s:4:"name";s:10:"pdo_sqlite";}i:4;a:1:{s:4:"name";s:4:"soap";}i:5;a:1:{s:4:"name";s:9:"tokenizer";}i:6;a:2:{s:4:"name";s:6:"xdebug";s:3:"min";s:5:"2.0.5";}}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/image_graphviz/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Image_GraphViz</p>
 <c>pear.php.net</c>
 <r><v>1.3.0</v><s>stable</s></r>
 <r><v>1.3.0RC3</v><s>beta</s></r>
 <r><v>1.3.0RC2</v><s>beta</s></r>
 <r><v>1.3.0RC1</v><s>beta</s></r>
 <r><v>1.2.1</v><s>stable</s></r>
 <r><v>1.2.0</v><s>stable</s></r>
 <r><v>1.1.0</v><s>stable</s></r>
 <r><v>1.1.0beta1</v><s>beta</s></r>
 <r><v>1.0.3</v><s>stable</s></r>
 <r><v>1.0.2</v><s>stable</s></r>
 <r><v>1.0.1</v><s>stable</s></r>
 <r><v>1.0</v><s>stable</s></r>
 <r><v>0.4</v><s>stable</s></r>
 <r><v>0.3</v><s>stable</s></r>
 <r><v>0.2</v><s></s></r>
</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/p/image_graphviz/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package"    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"    xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
 <n>Image_GraphViz</n>
 <c>pear.php.net</c>
 <ca xlink:href="/rest/c/Images">Images</ca>
 <l>PHP License</l>
 <s>Interface to AT&amp;amp;amp;T\'s GraphViz tools</s>
 <d>The GraphViz class allows for the creation of and the work with directed and undirected graphs and their visualization with AT&amp;amp;amp;T\'s GraphViz tools.</d>
 <r xlink:href="/rest/r/image_graphviz"/>
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/image_graphviz/1.3.0.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/image_graphviz">Image_GraphViz</p>
 <c>pear.php.net</c>
 <v>1.3.0</v>
 <st>stable</st>
 <l>PHP License</l>
 <m>doconnor</m>
 <s>Interface to AT&amp;T\'s GraphViz tools</s>
 <d>The GraphViz class allows for the creation of and the work with directed and undirected graphs and their visualization with AT&amp;T\'s GraphViz tools.</d>
 <da>2010-10-24 06:22:07</da>
 <n>QA release
SVN  dir layout
Request #12913 	Error report upon renderDotFile error
Bug #15019 	addCluster using attributes twice
Request #15943 	adding subgraph to subgraph?
Request #15943 	adding subgraph to subgraph?
Bug #16326 	image() doesn\'t return error on unknown format.
Bug #16569 	Image_GraphViz uses @package GraphViz
Bug #16872 	Cluster display
Bug #17746 	Nested Clusters not working</n>
 <f>16706</f>
 <g>http://pear.php.net/get/Image_GraphViz-1.3.0</g>
 <x xlink:href="package.1.3.0.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/image_graphviz/deps.1.3.0.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"4.0.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.6.2";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Log</p>
 <c>pear.php.net</c>
 <r><v>1.12.3</v><s>stable</s></r>
 <r><v>1.12.2</v><s>stable</s></r>
 <r><v>1.12.1</v><s>stable</s></r>
 <r><v>1.12.0</v><s>stable</s></r>
 <r><v>1.12.0RC1</v><s>beta</s></r>
 <r><v>1.11.6</v><s>stable</s></r>
 <r><v>1.11.5</v><s>stable</s></r>
 <r><v>1.11.4</v><s>stable</s></r>
 <r><v>1.11.3</v><s>stable</s></r>
 <r><v>1.11.2</v><s>stable</s></r>
 <r><v>1.11.1</v><s>stable</s></r>
 <r><v>1.11.0</v><s>stable</s></r>
 <r><v>1.10.1</v><s>stable</s></r>
 <r><v>1.10.0</v><s>stable</s></r>
 <r><v>1.9.16</v><s>stable</s></r>
 <r><v>1.9.15</v><s>stable</s></r>
 <r><v>1.9.14</v><s>stable</s></r>
 <r><v>1.9.13</v><s>stable</s></r>
 <r><v>1.9.12</v><s>stable</s></r>
 <r><v>1.9.11</v><s>stable</s></r>
 <r><v>1.9.10</v><s>stable</s></r>
 <r><v>1.9.9</v><s>stable</s></r>
 <r><v>1.9.8</v><s>stable</s></r>
 <r><v>1.9.7</v><s>stable</s></r>
 <r><v>1.9.6</v><s>stable</s></r>
 <r><v>1.9.5</v><s>stable</s></r>
 <r><v>1.9.4</v><s>stable</s></r>
 <r><v>1.9.3</v><s>stable</s></r>
 <r><v>1.9.2</v><s>stable</s></r>
 <r><v>1.9.1</v><s>stable</s></r>
 <r><v>1.9.0</v><s>stable</s></r>
 <r><v>1.8.7</v><s>stable</s></r>
 <r><v>1.8.6</v><s>stable</s></r>
 <r><v>1.8.5</v><s>stable</s></r>
 <r><v>1.8.4</v><s>stable</s></r>
 <r><v>1.8.3</v><s>stable</s></r>
 <r><v>1.8.2</v><s>stable</s></r>
 <r><v>1.8.1</v><s>stable</s></r>
 <r><v>1.8.0</v><s>stable</s></r>
 <r><v>1.7.1</v><s>stable</s></r>
 <r><v>1.7.0</v><s>stable</s></r>
 <r><v>1.6.7</v><s>stable</s></r>
 <r><v>1.6.6</v><s>stable</s></r>
 <r><v>1.6.5</v><s>stable</s></r>
 <r><v>1.6.4</v><s>stable</s></r>
 <r><v>1.6.3</v><s>stable</s></r>
 <r><v>1.6.2</v><s>stable</s></r>
 <r><v>1.6.1</v><s>stable</s></r>
 <r><v>1.6.0</v><s>stable</s></r>
 <r><v>1.5.3</v><s>stable</s></r>
 <r><v>1.5.2</v><s>stable</s></r>
 <r><v>1.5.1</v><s>stable</s></r>
 <r><v>1.5</v><s>stable</s></r>
 <r><v>1.4</v><s>stable</s></r>
 <r><v>1.3</v><s>stable</s></r>
 <r><v>1.2</v><s>stable</s></r>
 <r><v>1.1</v><s>stable</s></r>
 <r><v>1.0</v><s></s></r>
</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/p/log/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package"    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"    xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
 <n>Log</n>
 <c>pear.php.net</c>
 <ca xlink:href="/rest/c/Logging">Logging</ca>
 <l>MIT License</l>
 <s>Logging Framework</s>
 <d>The Log package provides an abstracted logging framework.  It includes output handlers for log files, databases, syslog, email, Firebug, and the console.  It also provides composite and subject-observer logging mechanisms.</d>
 <r xlink:href="/rest/r/log"/>
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/1.12.3.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/log">Log</p>
 <c>pear.php.net</c>
 <v>1.12.3</v>
 <st>stable</st>
 <l>MIT License</l>
 <m>jon</m>
 <s>Logging Framework</s>
 <d>The Log package provides an abstracted logging framework.  It includes output handlers for log files, databases, syslog, email, Firebug, and the console.  It also provides composite and subject-observer logging mechanisms.</d>
 <da>2010-09-28 04:16:02</da>
 <n>- The unit tests now set the timezone. (Bug 17830)
- The composite handler now opens child handlers lazily (on demand). (Bug 17785)</n>
 <f>46020</f>
 <g>http://pear.php.net/get/Log-1.12.3</g>
 <x xlink:href="package.1.12.3.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/deps.1.12.3.txt", 'a:2:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"5.0.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.4.3";}}s:8:"optional";a:2:{s:7:"package";a:3:{i:0;a:3:{s:4:"name";s:2:"DB";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:3:"1.3";}i:1;a:3:{s:4:"name";s:4:"MDB2";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:8:"2.0.0RC1";}i:2;a:2:{s:4:"name";s:4:"Mail";s:7:"channel";s:12:"pear.php.net";}}s:9:"extension";a:1:{s:4:"name";s:6:"sqlite";}}}', 'text/xml');

// Install PHPUnit 3.4.14
$_test_dep->setPHPVersion('5.2.10');
$_test_dep->setPEARVersion('1.9.1');
$_test_dep->setExtensions(array('dom' => 1, 'pcre' => 1, 'reflection' => 1, 'spl' => 1));

$result = $command->run('install', array(), array($path . 'PHPUnit-3.4.14.tgz'));
$phpunit->assertNoErrors('setup');
$phpunit->assertEquals(1, count($reg->listPackages('phpunit')), 'num packages');
$phpunit->assertEquals('3.4.14', $reg->packageInfo('phpunit', 'version', 'phpunit'), 'PHPUnit version');

$file = $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'PHPUnit' . DIRECTORY_SEPARATOR . 'Framework' . DIRECTORY_SEPARATOR . 'MockObject' . DIRECTORY_SEPARATOR . 'Generator.php';
$phpunit->assertFileExists($file, 'installed file');

$info = array(
    'package' => 'phpunit',
    'channel' => 'phpunit',
);

$phpunit->assertSame(array(), array_diff($info, $reg->checkFileMap($file, $info, '1.1')), 'Belongs to right package');

// Reset logs
$fakelog->getLog();
$fakelog->getDownload();

// Setup for PHPUnit 3.5.5 upgrade with package depenencies

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/packages.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allpackages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.allpackages http://pear.php.net/dtd/rest.allpackages.xsd">
    <c>pear.phpunit.de</c>
  <p>DbUnit</p>
  <p>File_Iterator</p>
  <p>PHPUnit</p>
  <p>PHPUnit_MockObject</p>
  <p>PHPUnit_Selenium</p>
  <p>PHP_CodeBrowser</p>
  <p>PHP_CodeCoverage</p>
  <p>PHP_Timer</p>
  <p>PHP_TokenStream</p>
  <p>Text_Template</p>
  <p>bytekit</p>
  <p>phpUnderControl</p>
  <p>phpcpd</p>
  <p>phpdcd</p>
  <p>phploc</p>
  <p>test_helpers</p>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/3.5.5.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/phpunit">PHPUnit</p>
    <c>pear.phpunit.de</c>
    <v>3.5.5</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Regression testing framework for unit tests.</s>
    <d>PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP. This is the version to be used with PHP 5.</d>
    <da>2010-11-22 11:42:15</da>
    <n>
http://github.com/sebastianbergmann/phpunit/blob/master/README.markdown
 </n>
    <f>116148</f>
    <g>http://pear.phpunit.de/get/PHPUnit-3.5.5</g>
    <x xlink:href="package.3.5.5.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/deps.3.5.5.txt", 'a:2:{s:8:"required";a:4:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}s:7:"package";a:8:{i:0;a:3:{s:4:"name";s:6:"DbUnit";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.0";}i:1;a:3:{s:4:"name";s:13:"File_Iterator";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.2.3";}i:2;a:3:{s:4:"name";s:13:"Text_Template";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.0";}i:3;a:3:{s:4:"name";s:16:"PHP_CodeCoverage";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.2";}i:4;a:3:{s:4:"name";s:9:"PHP_Timer";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.0";}i:5;a:3:{s:4:"name";s:18:"PHPUnit_MockObject";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.3";}i:6;a:3:{s:4:"name";s:16:"PHPUnit_Selenium";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.1";}i:7;a:3:{s:4:"name";s:4:"YAML";s:7:"channel";s:24:"pear.symfony-project.com";s:3:"min";s:5:"1.0.2";}}s:9:"extension";a:4:{i:0;a:1:{s:4:"name";s:3:"dom";}i:1;a:1:{s:4:"name";s:4:"pcre";}i:2;a:1:{s:4:"name";s:10:"reflection";}i:3;a:1:{s:4:"name";s:3:"spl";}}}s:8:"optional";a:1:{s:9:"extension";a:5:{i:0;a:1:{s:4:"name";s:4:"dbus";}i:1;a:1:{s:4:"name";s:4:"json";}i:2;a:1:{s:4:"name";s:3:"pdo";}i:3;a:1:{s:4:"name";s:4:"soap";}i:4;a:1:{s:4:"name";s:9:"tokenizer";}}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/dbunit/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>DbUnit</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/dbunit/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>DbUnit</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>DbUnit port for PHP/PHPUnit.</s>
<d>DbUnit port for PHP/PHPUnit</d>
<r xlink:href="/rest/r/dbunit" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/dbunit/1.0.0.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/dbunit">DbUnit</p>
    <c>pear.phpunit.de</c>
    <v>1.0.0</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>DbUnit port for PHP/PHPUnit.</s>
    <d>DbUnit port for PHP/PHPUnit</d>
    <da>2010-09-25 01:27:25</da>
    <n>
http://github.com/sebastianbergmann/dbunit/blob/master/README.markdown
 </n>
    <f>38183</f>
    <g>http://pear.phpunit.de/get/DbUnit-1.0.0</g>
    <x xlink:href="package.1.0.0.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/dbunit/deps.1.0.0.txt", 'a:1:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}s:7:"package";a:3:{s:4:"name";s:4:"YAML";s:7:"channel";s:24:"pear.symfony-project.com";s:3:"min";s:5:"1.0.2";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/dbunit/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>DbUnit</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/file_iterator/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>File_Iterator</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.2.3</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.2.2</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.2.1</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.2.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.1.1</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>1.1.0</v>
        <s>stable</s>
        <m>5.2.0</m>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.0</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/file_iterator/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>File_Iterator</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>FilterIterator implementation that filters files based on a list of suffixes.</s>
<d>FilterIterator implementation that filters files based on a list of suffixes.</d>
<r xlink:href="/rest/r/file_iterator" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/file_iterator/1.2.3.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/file_iterator">File_Iterator</p>
    <c>pear.phpunit.de</c>
    <v>1.2.3</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>FilterIterator implementation that filters files based on a list of suffixes.</s>
    <d>FilterIterator implementation that filters files based on a list of suffixes.</d>
    <da>2010-09-09 17:09:36</da>
    <n>
http://github.com/sebastianbergmann/php-file-iterator/tree
 </n>
    <f>3406</f>
    <g>http://pear.phpunit.de/get/File_Iterator-1.2.3</g>
    <x xlink:href="package.1.2.3.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/file_iterator/deps.1.2.3.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/file_iterator/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>File_Iterator</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.2.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.2.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.2.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.2.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.1.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.1.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/text_template/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>Text_Template</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.1.0</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.1.4</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/text_template/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>Text_Template</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Simple template engine.</s>
<d>Simple template engine.</d>
<r xlink:href="/rest/r/text_template" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/text_template/1.1.0.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/text_template">Text_Template</p>
    <c>pear.phpunit.de</c>
    <v>1.1.0</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Simple template engine.</s>
    <d>Simple template engine.</d>
    <da>2010-12-04 16:43:14</da>
    <n>
http://github.com/sebastianbergmann/php-text-template
 </n>
    <f>2783</f>
    <g>http://pear.phpunit.de/get/Text_Template-1.1.0</g>
    <x xlink:href="package.1.1.0.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/text_template/deps.1.1.0.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"5.1.4";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.8.1";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/text_template/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>Text_Template</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.1.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_codecoverage/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>PHP_CodeCoverage</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.2</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>0.9.1</v>
        <s>alpha</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>0.9.0</v>
        <s>alpha</s>
        <m>5.2.7</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/php_codecoverage/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>PHP_CodeCoverage</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Library that provides collection, processing, and rendering functionality for PHP code coverage information.</s>
<d>Library that provides collection, processing, and rendering functionality for PHP code coverage information.</d>
<r xlink:href="/rest/r/php_codecoverage" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_codecoverage/1.0.2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/php_codecoverage">PHP_CodeCoverage</p>
    <c>pear.phpunit.de</c>
    <v>1.0.2</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Library that provides collection, processing, and rendering functionality for PHP code coverage information.</s>
    <d>Library that provides collection, processing, and rendering functionality for PHP code coverage information.</d>
    <da>2010-11-14 08:31:23</da>
    <n>
http://github.com/sebastianbergmann/php-code-coverage/
 </n>
    <f>109280</f>
    <g>http://pear.phpunit.de/get/PHP_CodeCoverage-1.0.2</g>
    <x xlink:href="package.1.0.2.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_codecoverage/deps.1.0.2.txt", 'a:2:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}s:7:"package";a:4:{i:0;a:3:{s:4:"name";s:12:"ConsoleTools";s:7:"channel";s:16:"components.ez.no";s:3:"min";s:3:"1.6";}i:1;a:3:{s:4:"name";s:13:"File_Iterator";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.2.2";}i:2;a:3:{s:4:"name";s:15:"PHP_TokenStream";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.0";}i:3;a:3:{s:4:"name";s:13:"Text_Template";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.0";}}}s:8:"optional";a:1:{s:9:"extension";a:2:{s:4:"name";s:6:"xdebug";s:3:"min";s:5:"2.0.5";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_codecoverage/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>PHP_CodeCoverage</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
    </r>
    <r>
        <v>0.9.1</v>
        <s>alpha</s>
    </r>
    <r>
        <v>0.9.0</v>
        <s>alpha</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_timer/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>PHP_Timer</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/php_timer/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>PHP_Timer</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Utility class for timing</s>
<d>Utility class for timing</d>
<r xlink:href="/rest/r/php_timer" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_timer/1.0.0.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/php_timer">PHP_Timer</p>
    <c>pear.phpunit.de</c>
    <v>1.0.0</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Utility class for timing</s>
    <d>Utility class for timing</d>
    <da>2010-05-08 12:39:41</da>
    <n>
http://github.com/sebastianbergmann/php-timer/blob/master/README.markdown
 </n>
    <f>2536</f>
    <g>http://pear.phpunit.de/get/PHP_Timer-1.0.0</g>
    <x xlink:href="package.1.0.0.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_timer/deps.1.0.0.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.0";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_timer/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>PHP_Timer</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_mockobject/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>PHPUnit_MockObject</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.3</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.2</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/phpunit_mockobject/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>PHPUnit_MockObject</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Mock Object library for PHPUnit</s>
<d>Mock Object library for PHPUnit</d>
<r xlink:href="/rest/r/phpunit_mockobject" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_mockobject/1.0.3.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/phpunit_mockobject">PHPUnit_MockObject</p>
    <c>pear.phpunit.de</c>
    <v>1.0.3</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Mock Object library for PHPUnit</s>
    <d>Mock Object library for PHPUnit</d>
    <da>2010-11-22 10:41:04</da>
    <n>
http://github.com/sebastianbergmann/phpunit-mock-objects/blob/master/README.markdown
 </n>
    <f>17333</f>
    <g>http://pear.phpunit.de/get/PHPUnit_MockObject-1.0.3</g>
    <x xlink:href="package.1.0.3.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_mockobject/deps.1.0.3.txt", 'a:1:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}s:7:"package";a:3:{s:4:"name";s:13:"Text_Template";s:7:"channel";s:15:"pear.phpunit.de";s:3:"min";s:5:"1.0.0";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_mockobject/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>PHPUnit_MockObject</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.3</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.2</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_selenium/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>PHPUnit_Selenium</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/phpunit_selenium/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>PHPUnit_Selenium</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Selenium RC integration for PHPUnit</s>
<d>Selenium RC integration for PHPUnit</d>
<r xlink:href="/rest/r/phpunit_selenium" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_selenium/1.0.1.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/phpunit_selenium">PHPUnit_Selenium</p>
    <c>pear.phpunit.de</c>
    <v>1.0.1</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Selenium RC integration for PHPUnit</s>
    <d>Selenium RC integration for PHPUnit</d>
    <da>2010-11-17 12:22:42</da>
    <n>
http://github.com/sebastianbergmann/phpunit-selenium/blob/master/README.markdown
 </n>
    <f>15285</f>
    <g>http://pear.phpunit.de/get/PHPUnit_Selenium-1.0.1</g>
    <x xlink:href="package.1.0.1.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_selenium/deps.1.0.1.txt", 'a:1:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit_selenium/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>PHPUnit_Selenium</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0RC2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_tokenstream/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2 http://pear.php.net/dtd/rest.allreleases2.xsd">
    <p>PHP_TokenStream</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
        <m>5.2.7</m>
    </r>
    <r>
        <v>1.0.0beta2</v>
        <s>beta</s>
        <m>5.2.0</m>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
        <m>5.2.0</m>
    </r>
    <r>
        <v>0.9.1</v>
        <s>beta</s>
        <m>5.2.0</m>
    </r>
    <r>
        <v>0.9.0</v>
        <s>beta</s>
        <m>5.2.0</m>
    </r>

</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/p/php_tokenstream/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
<n>PHP_TokenStream</n>
<c>pear.phpunit.de</c>
<ca xlink:href="/rest/c/Default">Default</ca>
<l>BSD License</l>
<s>Wrapper around PHP\'s tokenizer extension.</s>
<d>Wrapper around PHP\'s tokenizer extension.</d>
<r xlink:href="/rest/r/php_tokenstream" />
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_tokenstream/1.0.1.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/php_tokenstream">PHP_TokenStream</p>
    <c>pear.phpunit.de</c>
    <v>1.0.1</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Wrapper around PHP\'s tokenizer extension.</s>
    <d>Wrapper around PHP\'s tokenizer extension.</d>
    <da>2010-10-27 09:11:08</da>
    <n>
http://github.com/sebastianbergmann/php-token-stream/tree
 </n>
    <f>7250</f>
    <g>http://pear.phpunit.de/get/PHP_TokenStream-1.0.1</g>
    <x xlink:href="package.1.0.1.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_tokenstream/deps.1.0.1.txt", 'a:1:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.2.7";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.9.1";}s:7:"package";a:3:{s:4:"name";s:12:"ConsoleTools";s:7:"channel";s:16:"components.ez.no";s:3:"min";s:3:"1.6";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/php_tokenstream/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases http://pear.php.net/dtd/rest.allreleases.xsd">
    <p>PHP_TokenStream</p>
    <c>pear.phpunit.de</c>
    <r>
        <v>1.0.1</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0</v>
        <s>stable</s>
    </r>
    <r>
        <v>1.0.0RC1</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta2</v>
        <s>beta</s>
    </r>
    <r>
        <v>1.0.0beta1</v>
        <s>beta</s>
    </r>
    <r>
        <v>0.9.1</v>
        <s>beta</s>
    </r>
    <r>
        <v>0.9.0</v>
        <s>beta</s>
    </r>

</a>', 'text/xml');



unset($GLOBALS['__Stupid_php4_a']); // reset downloader
$command->run('upgrade', array('force' => true), array('phpunit/phpunit-3.5.5'));
$phpunit->assertNoErrors('full test');
$phpunit->assertEquals(9, count($reg->listPackages('phpunit')), 'num packages 2');
$phpunit->assertEquals('3.5.5', $reg->packageInfo('phpunit', 'version', 'phpunit'), 'PHPUnit version 2');

$file = $temp_path . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'PHPUnit' . DIRECTORY_SEPARATOR . 'Framework' . DIRECTORY_SEPARATOR . 'MockObject' . DIRECTORY_SEPARATOR . 'Generator.php';
$phpunit->assertFileExists($file, 'installed file');

$info = array(
    'package' => 'phpunit_mockobject',
    'channel' => 'pear.phpunit.de',
);

$phpunit->assertSame(array(), array_diff($info, $reg->checkFileMap($file, $info, '1.1')), 'Belongs to right package');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
