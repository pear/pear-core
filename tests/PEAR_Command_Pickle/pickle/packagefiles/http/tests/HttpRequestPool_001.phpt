--TEST--
HttpRequestPool
--SKIPIF--
<?php
include 'skip.inc';
checkver(5);
checkcls('HttpRequestPool');
checkurl('www.php.net');
checkurl('de.php.net');
checkurl('ch.php.net');
checkurl('at.php.net');
checkurl('dev.iworks.at');
?>
--FILE--
<?php
echo "-TEST\n";
$pool = new HttpRequestPool(
    new HttpRequest('http://www.php.net/', HTTP_METH_HEAD),
    new HttpRequest('http://at.php.net/', HTTP_METH_HEAD),
    new HttpRequest('http://de.php.net/', HTTP_METH_HEAD),
    new HttpRequest('http://ch.php.net/', HTTP_METH_HEAD),
    $post = new HttpRequest('http://dev.iworks.at/.print_request.php', HTTP_METH_POST)
);
$post->addPostFields(array('a'=>1,'b'=>2)) ;
$pool->send();
foreach ($pool as $req) {
    echo $req->getUrl(), '=',
        $req->getResponseCode(), PATH_SEPARATOR,
        $req->getResponseMessage()->getResponseCode(), "\n";
}
foreach ($pool as $req) {
	try {
		$pool->attach(new HttpRequest('http://foo.bar'));
	} catch (HttpRequestPoolException $x) {
		echo ".\n";
	}
}
foreach ($pool as $req) {
	$pool->detach($req);
}
echo "Done\n";
?>
--EXPECTF--
%sTEST
http://www.php.net/=200:200
http://at.php.net/=200:200
http://de.php.net/=200:200
http://ch.php.net/=200:200
http://dev.iworks.at/.print_request.php=200:200
.
.
.
.
.
Done
