<?php
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
function skipif($if, $skip) { if ($if) { fprintf(STDOUT, "skip $skip"); exit(); }}
function checkcgi() { skipif(!strncasecmp('CLI', PHP_SAPI, 3), 'need CGI SAPI'); }
function checkext($ext) { skipif(!extension_loaded($ext), "need ext/$ext"); }
function checkver($ver) { skipif(round($ver,1) > round((double) PHP_VERSION,1), "need PHP v$ver"); }
function checkurl($url) { skipif(!@fsockopen($url, 80), "$url not responsive"); }
function checkcls($cls) { skipif(!class_exists($cls), "need class $cls"); }
checkext('http');
?>
