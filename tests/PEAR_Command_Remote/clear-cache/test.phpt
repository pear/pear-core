--TEST--
clear-cache command
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
$remote = &$config->getRemote();
$rest = &$config->getREST();
$remote->saveCache(array('blah', 'blah'), 'hi');
$remote->saveCache(array('blah1', 'blah1'), 'hi');
$remote->saveCache(array('blah2', 'blah2'), 'hi');
$rest->saveCache('http://www.example.com/hi', 'hi', array('hi', date('r')));
$rest->saveCache('http://www.example.com/hi2', 'hi2', array('hi2', date('r')));
$e = $command->run('clear-cache', array(), array());
$phpunit->assertNoErrors('clear-cache');
$phpunit->showall();
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 'reading directory ' . $config->get('cache_dir') . '
7 cache entries cleared',
    'cmd' => 'clear-cache',
  ),
), $fakelog->getLog(), 'clear-cache log');
echo 'tests done';
?>
--EXPECT--
tests done
