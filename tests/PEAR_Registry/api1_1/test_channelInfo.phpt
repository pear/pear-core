--TEST--
PEAR_Registry->channelInfo() (API v1.1)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
require_once 'PEAR/Registry.php';
$pv = phpversion() . '';
$av = $pv{0} == '4' ? 'apiversion' : 'apiVersion';
if (!in_array($av, get_class_methods('PEAR_Registry'))) {
    echo 'skip';
}
if (PEAR_Registry::apiVersion() != '1.1') {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(1803);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$ch = new PEAR_ChannelFile;
$ch->setName('test.test.test');
$ch->setAlias('foo');
$ch->setSummary('blah');
$ch->setDefaultPEARProtocols();
$reg->addChannel($ch);
$phpunit->assertNoErrors('setup');
$ret = $reg->channelInfo('snark');
$phpunit->assertNull($ret, 'snark');
$ret = $reg->channelInfo('foo', true);
$phpunit->assertNull($ret, 'foo strict');
$ret = $reg->channelInfo('foo');
$ret1 = $reg->channelInfo('test.test.test');
$ret2 = $reg->channelInfo('test.test.test', true);
$phpunit->assertTrue(isset($ret['_lastmodified']), 'lastmodified is set');
unset($ret['_lastmodified']);
$phpunit->assertTrue(isset($ret1['_lastmodified']), 'lastmodified is set1');
unset($ret1['_lastmodified']);
$phpunit->assertTrue(isset($ret2['_lastmodified']), 'lastmodified is set2');
unset($ret2['_lastmodified']);
$phpunit->assertEquals(array (
  'name' => 'test.test.test',
  'suggestedalias' => 'foo',
  'summary' => 'blah',
  'servers' => 
  array (
    'primary' => 
    array (
      'xmlrpc' => 
      array (
        'function' => 
        array (
          0 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'logintest',
          ),
          1 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.listLatestReleases',
          ),
          2 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.listAll',
          ),
          3 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.info',
          ),
          4 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          5 => 
          array (
            'attribs' => 
            array (
              'version' => '1.1',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          6 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.getDepDownloadURL',
          ),
          7 => 
          array (
            'attribs' => 
            array (
              'version' => '1.1',
            ),
            '_content' => 'package.getDepDownloadURL',
          ),
          8 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.search',
          ),
          9 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'channel.listAll',
          ),
        ),
      ),
    ),
  ),
), $ret, 'foo');
$phpunit->assertEquals(array (
  'name' => 'test.test.test',
  'suggestedalias' => 'foo',
  'summary' => 'blah',
  'servers' => 
  array (
    'primary' => 
    array (
      'xmlrpc' => 
      array (
        'function' => 
        array (
          0 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'logintest',
          ),
          1 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.listLatestReleases',
          ),
          2 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.listAll',
          ),
          3 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.info',
          ),
          4 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          5 => 
          array (
            'attribs' => 
            array (
              'version' => '1.1',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          6 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.getDepDownloadURL',
          ),
          7 => 
          array (
            'attribs' => 
            array (
              'version' => '1.1',
            ),
            '_content' => 'package.getDepDownloadURL',
          ),
          8 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.search',
          ),
          9 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'channel.listAll',
          ),
        ),
      ),
    ),
  ),
), $ret1, 'test.test.test');
$phpunit->assertEquals(array (
  'name' => 'test.test.test',
  'suggestedalias' => 'foo',
  'summary' => 'blah',
  'servers' => 
  array (
    'primary' => 
    array (
      'xmlrpc' => 
      array (
        'function' => 
        array (
          0 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'logintest',
          ),
          1 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.listLatestReleases',
          ),
          2 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.listAll',
          ),
          3 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.info',
          ),
          4 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          5 => 
          array (
            'attribs' => 
            array (
              'version' => '1.1',
            ),
            '_content' => 'package.getDownloadURL',
          ),
          6 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.getDepDownloadURL',
          ),
          7 => 
          array (
            'attribs' => 
            array (
              'version' => '1.1',
            ),
            '_content' => 'package.getDepDownloadURL',
          ),
          8 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'package.search',
          ),
          9 => 
          array (
            'attribs' => 
            array (
              'version' => '1.0',
            ),
            '_content' => 'channel.listAll',
          ),
        ),
      ),
    ),
  ),
), $ret, 'test.test.test strict');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(__FILE__) . '/teardown.php.inc';
?>
--EXPECT--
tests done
