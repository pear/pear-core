--TEST--
HttpRequest GET/POST
--SKIPIF--
<?php
include 'skip.inc';
checkver(5);
checkcls('HttpRequest');
checkurl('www.google.com');
checkurl('dev.iworks.at');
?>
--FILE--
<?php
echo "-TEST\n";

$r = new HttpRequest('http://www.google.com', HttpRequest::METH_GET);
$r->send();
print_r($r->getResponseInfo());

$r = new HttpRequest('http://dev.iworks.at/.print_request.php', HTTP_METH_POST);
$r->addCookies(array('MyCookie' => 'foobar'));
$r->addQueryData(array('gq'=>'foobar','gi'=>10));
$r->addPostFields(array('pq'=>'foobar','pi'=>10));
$r->addPostFile('upload', dirname(__FILE__).'/data.txt', 'text/plain');
$r->send();
echo $r->getResponseBody();
var_dump($r->getResponseMessage()->getResponseCode());

echo "Done";
?>
--EXPECTF--
%sTEST
Array
(
    [effective_url] => http://www.google.com/
    [response_code] => %d
    [http_connectcode] => %d
    [filetime] => %s
    [total_time] => %f
    [namelookup_time] => %f
    [connect_time] => %f
    [pretransfer_time] => %f
    [starttransfer_time] => %f
    [redirect_time] => %f
    [redirect_count] => %f
    [size_upload] => %f
    [size_download] => %d
    [speed_download] => %d
    [speed_upload] => %d
    [header_size] => %d
    [request_size] => %d
    [ssl_verifyresult] => %d
    [content_length_download] => %d
    [content_length_upload] => %d
    [content_type] => %s
    [httpauth_avail] => %d
    [proxyauth_avail] => %s
)
Array
(
    [gq] => foobar
    [gi] => 10
    [pq] => foobar
    [pi] => 10
    [MyCookie] => foobar
)
Array
(
    [upload] => Array
        (
            [name] => data.txt
            [type] => text/plain
            [tmp_name] => %s
            [error] => 0
            [size] => 1010
        )

)

int(200)
Done
