--TEST--
upgrade command, test for bug #16046 - stability stable and a package tries to upgrade to beta
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';

$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packages'. DIRECTORY_SEPARATOR;
$chan = $reg->getChannel('pear.php.net');
$chan->setBaseURL('REST1.0', 'http://pear.php.net/rest/');
$chan->setBaseURL('REST1.1', 'http://pear.php.net/rest/');
$chan->setBaseURL('REST1.3', 'http://pear.php.net/rest/');
$reg->updateChannel($chan);

$ch = new PEAR_ChannelFile;
$ch->setName('pear.phpunit.de');
$ch->setSummary('PHPUnit');
$ch->setAlias('phpunit');
$ch->setBaseURL('REST1.0', 'http://pear.phpunit.de/rest/');
$ch->setBaseURL('REST1.1', 'http://pear.phpunit.de/rest/');
$ch->setBaseURL('REST1.2', 'http://pear.phpunit.de/rest/');
$ch->setBaseURL('REST1.3', 'http://pear.phpunit.de/rest/');

$phpunit->assertTrue($reg->addChannel($ch), 'PHPUnit channel setup');

$pearweb->addHTMLConfig('http://pear.phpunit.de/get/PHPUnit-3.4.0beta1.tgz', $dir . 'PHPUnit-3.4.0beta1.tgz');
$pearweb->addHTMLConfig('http://pear.phpunit.de/get/PHPUnit-3.3.12.tgz',     $dir . 'PHPUnit-3.3.12.tgz');

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

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/3.3.12.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/phpunit">PHPUnit</p>
    <c>pear.phpunit.de</c>
    <v>3.3.12</v>
    <st>stable</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Regression testing framework for unit tests.</s>
    <d>PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP. This is the version to be used with PHP 5.</d>
    <da>2009-01-26 16:58:43</da>
    <n>http://www.phpunit.de/wiki/ChangeLog</n>
    <f>271757</f>
    <g>http://pear.phpunit.de/get/PHPUnit-3.3.12</g>
    <x xlink:href="package.3.3.12.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/deps.3.3.12.txt", 'a:2:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.1.4";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.7.1";}s:9:"extension";a:4:{i:0;a:1:{s:4:"name";s:3:"dom";}i:1;a:1:{s:4:"name";s:4:"pcre";}i:2;a:1:{s:4:"name";s:10:"reflection";}i:3;a:1:{s:4:"name";s:3:"spl";}}}s:8:"optional";a:2:{s:7:"package";a:2:{i:0;a:3:{s:4:"name";s:14:"Image_GraphViz";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"1.2.1";}i:1;a:2:{s:4:"name";s:3:"Log";s:7:"channel";s:12:"pear.php.net";}}s:9:"extension";a:6:{i:0;a:1:{s:4:"name";s:4:"json";}i:1;a:1:{s:4:"name";s:3:"pdo";}i:2;a:1:{s:4:"name";s:9:"pdo_mysql";}i:3;a:1:{s:4:"name";s:10:"pdo_sqlite";}i:4;a:1:{s:4:"name";s:9:"tokenizer";}i:5;a:2:{s:4:"name";s:6:"xdebug";s:3:"min";s:5:"2.0.0";}}}}', 'text/xml');

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

$pearweb->addRESTConfig("http://pear.php.net/rest/r/image_graphviz/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2
    http://pear.php.net/dtd/rest.allreleases2.xsd">
 <p>Image_GraphViz</p>
 <c>pear.php.net</c>
 <r><v>1.3.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.3.0RC3</v><s>beta</s><m>4.0.0</m></r>
 <r><v>1.3.0RC2</v><s>beta</s><m>4.0.0</m></r>
 <r><v>1.3.0RC1</v><s>beta</s><m>4.0.0</m></r>
 <r><v>1.2.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.2.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.1.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.1.0beta1</v><s>beta</s><m>4.0.0</m></r>
 <r><v>1.0.3</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.0.2</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.0.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>0.4</v><s>stable</s><m>4.0.0</m></r>
 <r><v>0.3</v><s>stable</s><m>4.0.0</m></r>
 <r><v>0.2</v><s></s><m>4.0.0</m></r>
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

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2
    http://pear.php.net/dtd/rest.allreleases2.xsd">
 <p>Log</p>
 <c>pear.php.net</c>
 <r><v>1.12.4</v><s>stable</s><m>5.0.0</m></r>
 <r><v>1.12.3</v><s>stable</s><m>5.0.0</m></r>
 <r><v>1.12.2</v><s>stable</s><m>5.0.0</m></r>
 <r><v>1.12.1</v><s>stable</s><m>5.0.0</m></r>
 <r><v>1.12.0</v><s>stable</s><m>5.0.0</m></r>
 <r><v>1.12.0RC1</v><s>beta</s><m>5.0.0</m></r>
 <r><v>1.11.6</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.11.5</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.11.4</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.11.3</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.11.2</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.11.1</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.11.0</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.10.1</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.10.0</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.16</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.15</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.14</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.13</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.12</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.11</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.10</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.9</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.8</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.7</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.6</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.5</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.4</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.3</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.2</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.1</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.9.0</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.8.7</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.8.6</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.8.5</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.8.4</v><s>stable</s><m>4.3.0</m></r>
 <r><v>1.8.3</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.8.2</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.8.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.8.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.7.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.7.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.7</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.6</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.5</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.4</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.3</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.2</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.6.0</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.5.3</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.5.2</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.5.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.5</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.4</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.3</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.2</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.1</v><s>stable</s><m>4.0.0</m></r>
 <r><v>1.0</v><s></s><m>4.0.0</m></r>
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

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/1.12.4.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/log">Log</p>
 <c>pear.php.net</c>
 <v>1.12.4</v>
 <st>stable</st>
 <l>MIT License</l>
 <m>jon</m>
 <s>Logging Framework</s>
 <d>The Log package provides an abstracted logging framework.  It includes output handlers for log files, databases, syslog, email, Firebug, and the console.  It also provides composite and subject-observer logging mechanisms.</d>
 <da>2010-12-06 05:55:08</da>
 <n>- The display handler now has a \'rawText\' configuration option that disables HTML-safe message text conversion.
- Improved the way that the composite handler stores references its children.</n>
 <f>46307</f>
 <g>http://pear.php.net/get/Log-1.12.4</g>
 <x xlink:href="package.1.12.4.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/deps.1.12.4.txt", 'a:2:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"5.0.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.4.3";}}s:8:"optional";a:2:{s:7:"package";a:3:{i:0;a:3:{s:4:"name";s:2:"DB";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:3:"1.3";}i:1;a:3:{s:4:"name";s:4:"MDB2";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:8:"2.0.0RC1";}i:2;a:2:{s:4:"name";s:4:"Mail";s:7:"channel";s:12:"pear.php.net";}}s:9:"extension";a:1:{s:4:"name";s:6:"sqlite";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/log/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Log</p>
 <c>pear.php.net</c>
 <r><v>1.12.4</v><s>stable</s></r>
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

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/3.4.0beta1.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xsi:schemaLocation="http://pear.php.net/dtd/rest.release http://pear.php.net/dtd/rest.release.xsd">
    <p xlink:href="/rest/p/phpunit">PHPUnit</p>
    <c>pear.phpunit.de</c>
    <v>3.4.0beta1</v>
    <st>beta</st>
    <l>BSD License</l>
    <m>sb</m>
    <s>Regression testing framework for unit tests.</s>
    <d>PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP. This is the version to be used with PHP 5.</d>
    <da>2009-02-24 19:37:08</da>
    <n>http://www.phpunit.de/wiki/ChangeLog</n>
    <f>307043</f>
    <g>http://pear.phpunit.de/get/PHPUnit-3.4.0beta1</g>
    <x xlink:href="package.3.4.0beta1.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.phpunit.de/rest/r/phpunit/deps.3.4.0beta1.txt", 'a:2:{s:8:"required";a:3:{s:3:"php";a:1:{s:3:"min";s:5:"5.1.4";}s:13:"pearinstaller";a:1:{s:3:"min";s:5:"1.7.1";}s:9:"extension";a:4:{i:0;a:1:{s:4:"name";s:3:"dom";}i:1;a:1:{s:4:"name";s:4:"pcre";}i:2;a:1:{s:4:"name";s:10:"reflection";}i:3;a:1:{s:4:"name";s:3:"spl";}}}s:8:"optional";a:2:{s:7:"package";a:3:{i:0;a:3:{s:4:"name";s:14:"Image_GraphViz";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"1.2.1";}i:1;a:2:{s:4:"name";s:3:"Log";s:7:"channel";s:12:"pear.php.net";}i:2;a:3:{s:4:"name";s:9:"Text_Diff";s:7:"channel";s:12:"pear.php.net";s:3:"min";s:5:"1.1.0";}}s:9:"extension";a:7:{i:0;a:1:{s:4:"name";s:4:"json";}i:1;a:1:{s:4:"name";s:3:"pdo";}i:2;a:1:{s:4:"name";s:9:"pdo_mysql";}i:3;a:1:{s:4:"name";s:10:"pdo_sqlite";}i:4;a:1:{s:4:"name";s:4:"soap";}i:5;a:1:{s:4:"name";s:9:"tokenizer";}i:6;a:2:{s:4:"name";s:6:"xdebug";s:3:"min";s:5:"2.0.0";}}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/text_diff/allreleases2.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases2"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases2
    http://pear.php.net/dtd/rest.allreleases2.xsd">
 <p>Text_Diff</p>
 <c>pear.php.net</c>
 <r><v>1.1.1</v><s>stable</s><m>4.2.0</m></r>
 <r><v>1.1.0</v><s>stable</s><m>4.2.0</m></r>
 <r><v>1.0.0</v><s>stable</s><m>4.2.0</m></r>
 <r><v>0.3.2</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.3.1</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.3.0</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.2.1</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.2.0</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.1.1</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.1.0</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.0.5</v><s>beta</s><m>4.2.0</m></r>
 <r><v>0.0.4</v><s>beta</s><m>4.2.0+</m></r>
 <r><v>0.0.3</v><s>beta</s><m>4.2.0+</m></r>
 <r><v>0.0.2</v><s>alpha</s><m>4.2.0+</m></r>
</a>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/p/text_diff/info.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<p xmlns="http://pear.php.net/dtd/rest.package"    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"    xsi:schemaLocation="http://pear.php.net/dtd/rest.package    http://pear.php.net/dtd/rest.package.xsd">
 <n>Text_Diff</n>
 <c>pear.php.net</c>
 <ca xlink:href="/rest/c/Text">Text</ca>
 <l>LGPL</l>
 <s>Engine for performing and rendering text diffs</s>
 <d>This package provides a text-based diff engine and renderers for multiple diff output formats.</d>
 <r xlink:href="/rest/r/text_diff"/>
</p>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/text_diff/1.1.1.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<r xmlns="http://pear.php.net/dtd/rest.release"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xsi:schemaLocation="http://pear.php.net/dtd/rest.release
    http://pear.php.net/dtd/rest.release.xsd">
 <p xlink:href="/rest/p/text_diff">Text_Diff</p>
 <c>pear.php.net</c>
 <v>1.1.1</v>
 <st>stable</st>
 <l>LGPL</l>
 <m>yunosh</m>
 <s>Engine for performing and rendering text diffs</s>
 <d>This package provides a text-based diff engine and renderers for multiple diff output formats.</d>
 <da>2009-07-24 15:33:45</da>
 <n>* Make sure that the diff string ends with a line break (PEAR Bug #14533).
* Fix notices if xdiff engine creates empty lines (PEAR Bug #16240).
* Fix inline renderer if xdiff extension is installed.</n>
 <f>22009</f>
 <g>http://pear.php.net/get/Text_Diff-1.1.1</g>
 <x xlink:href="package.1.1.1.xml"/>
</r>', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/text_diff/deps.1.1.1.txt", 'a:2:{s:8:"required";a:2:{s:3:"php";a:1:{s:3:"min";s:5:"4.2.0";}s:13:"pearinstaller";a:1:{s:3:"min";s:7:"1.4.0b1";}}s:8:"optional";a:1:{s:9:"extension";a:1:{s:4:"name";s:5:"xdiff";}}}', 'text/xml');

$pearweb->addRESTConfig("http://pear.php.net/rest/r/text_diff/allreleases.xml", '<?xml version="1.0" encoding="UTF-8" ?>
<a xmlns="http://pear.php.net/dtd/rest.allreleases"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink"     xsi:schemaLocation="http://pear.php.net/dtd/rest.allreleases
    http://pear.php.net/dtd/rest.allreleases.xsd">
 <p>Text_Diff</p>
 <c>pear.php.net</c>
 <r><v>1.1.1</v><s>stable</s></r>
 <r><v>1.1.0</v><s>stable</s></r>
 <r><v>1.0.0</v><s>stable</s></r>
 <r><v>0.3.2</v><s>beta</s></r>
 <r><v>0.3.1</v><s>beta</s></r>
 <r><v>0.3.0</v><s>beta</s></r>
 <r><v>0.2.1</v><s>beta</s></r>
 <r><v>0.2.0</v><s>beta</s></r>
 <r><v>0.1.1</v><s>beta</s></r>
 <r><v>0.1.0</v><s>beta</s></r>
 <r><v>0.0.5</v><s>beta</s></r>
 <r><v>0.0.4</v><s>beta</s></r>
 <r><v>0.0.3</v><s>beta</s></r>
 <r><v>0.0.2</v><s>alpha</s></r>
</a>', 'text/xml');

$_test_dep->setPHPVersion('5.2.0');
$_test_dep->setPEARVersion('1.7.1');
$_test_dep->setExtensions(array('xml' => 1, 'pcre' => 1, 'spl' => 1, 'reflection' => 1, 'dom' => 1));
$config->set('preferred_state', 'stable');

$command->run('install', array(), array('phpunit/phpunit-3.3.12'));
$phpunit->assertNoErrors('setup');
$phpunit->assertEquals(1, count($reg->listPackages('phpunit')), 'num packages');
$phpunit->assertEquals('3.3.12', $reg->packageInfo('phpunit', 'version', 'phpunit'), 'PHPUnit version');

$fakelog->getLog();
$fakelog->getDownload();
unset($GLOBALS['__Stupid_php4_a']); // reset downloader

$command->run('upgrade', array(), array('phpunit/phpunit-3.4.0beta1'));
$phpunit->assertNoErrors('full test');

$phpunit->assertEquals('3.4.0beta1', $reg->packageInfo('phpunit', 'version', 'phpunit'), 'PHPUnit version');

echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
