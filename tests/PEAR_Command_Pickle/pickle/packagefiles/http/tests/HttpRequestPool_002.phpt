--TEST--
extending HttpRequestPool
--SKIPIF--
<?php
include 'skip.inc';
checkcls('HttpRequestPool');
?>
--FILE--
<?php
echo "-TEST\n";

class MyPool extends HttpRequestPool
{
	public function send()
	{
		while ($this->socketPerform()) {
			if (!$this->socketSelect()) {
				throw new HttpSocketException;
			}
		}
	}
	
	protected final function socketPerform()
	{
		$result = parent::socketPerform();
		
		echo ".";
		foreach ($this->getFinishedRequests() as $r) {
			echo "=", $r->getResponseCode(), "=";
			$this->detach($r);
		}
		
		return $result;
	}
}

$pool = new MyPool(
    new HttpRequest('http://www.php.net/', HTTP_METH_HEAD),
    new HttpRequest('http://at.php.net/', HTTP_METH_HEAD),
    new HttpRequest('http://de.php.net/', HTTP_METH_HEAD),
    new HttpRequest('http://ch.php.net/', HTTP_METH_HEAD)
);

$pool->send();

echo "\nDone\n";
?>
--EXPECTREGEX--
.+TEST
\.+=200=\.+=200=\.+=200=\.+=200=
Done
