--TEST--
HttpRequestPool chain
--SKIPIF--
<?php
include 'skip.inc';
checkcls('HttpRequest');
checkcls('HttpRequestPool');
?>
--FILE--
<?php

echo "-TEST\n";

set_time_limit(0);
ini_set('error_reporting', E_ALL);

class Pool extends HttpRequestPool
{
	private $all;
	private $rem;
	private $dir;
	
	public final function __construct($urls_file = 'urls.txt', $cache_dir = 'HttpRequestPool_cache')
	{
		$this->dir = (is_dir($cache_dir) or @mkdir($cache_dir)) ? $cache_dir : null;
		
		foreach (array_map('trim', file($urls_file)) as $url) {
			$this->all[$url] = $this->dir ? $this->dir .'/'. md5($url) : null;
		}
		
		$this->send();
	}
	
	public final function send()
	{
		if (RMAX) {
			$now = array_slice($this->all, 0, RMAX);
			$this->rem = array_slice($this->all, RMAX);
		} else {
			$now = $urls;
			$this->rem = array();
		}
		
		foreach ($now as $url => $file) {
			$this->attach(
				new HttpRequest(
					$url,
					HttpRequest::METH_GET,
					array(
						'redirect'	=> 5,
                        'compress'  => GZIP,
						'timeout'	=> TOUT,
						'connecttimeout' => TOUT,
						'lastmodified' => is_file($file)?filemtime($file):0
					)
				)
			);
		}
		
		while ($this->socketPerform()) {
			if (!$this->socketSelect()) {
				throw new HttpSocketException;
			}
		}
	}
	
	protected final function socketPerform()
	{
		try {
			$rc = parent::socketPerform();
		} catch (HttpRequestException $x) {
			// a request may have thrown an exception,
			// but it is still save to continue
			echo $x->getMessage(), "\n";
		}
		
		foreach ($this->getFinishedRequests() as $r) {
			$this->detach($r);
			
			$u = $r->getUrl();
			$c = $r->getResponseCode();
			$b = $r->getResponseBody();
			
			printf("%d %s %d\n", $c, $u, strlen($b));
			
			if ($c == 200 && $this->dir) {
				file_put_contents($this->all[$u], $b);
			}
			
			if ($a = each($this->rem)) {
				list($url, $file) = $a;
				$this->attach(
					new HttpRequest(
						$url,
						HttpRequest::METH_GET,
						array(
							'redirect'	=> 5,
							'compress'	=> GZIP,
							'timeout'	=> TOUT,
							'connecttimeout' => TOUT,
							'lastmodified' => is_file($file)?filemtime($file):0
						)
					)
				);
			}
		}
		return $rc;
	}
}

define('GZIP', true);
define('TOUT', 50);
define('RMAX', 10);
chdir(dirname(__FILE__));

$time = microtime(true);
$p = new Pool();
printf("Elapsed: %0.3fs\n", microtime(true)-$time);

echo "Done\n";
?>
--EXPECTF--
%sTEST
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
%d %s %d
Elapsed: %fs
Done
