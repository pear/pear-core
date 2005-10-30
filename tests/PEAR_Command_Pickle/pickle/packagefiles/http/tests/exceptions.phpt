--TEST--
exceptions
--SKIPIF--
<?php
include 'skip.inc';
checkver(5);
?>
--FILE--
<?php
echo "-TEST\n";

ini_set('http.only_exceptions', true);

$e = array(
	 HTTP_E_RUNTIME				=> 'Runtime',
	 HTTP_E_INVALID_PARAM		=> 'InvalidParam',
	 HTTP_E_HEADER				=> 'Header',
	 HTTP_E_MALFORMED_HEADERS	=> 'MalformedHeaders',
	 HTTP_E_REQUEST_METHOD		=> 'RequestMethod',
	 HTTP_E_MESSAGE_TYPE		=> 'MessageType',
	 HTTP_E_ENCODING			=> 'Encoding',
	 HTTP_E_REQUEST				=> 'Request',
	 HTTP_E_REQUEST_POOL		=> 'RequestPool',
	 HTTP_E_SOCKET				=> 'Socket',
	 HTTP_E_RESPONSE			=> 'Response',
	 HTTP_E_URL					=> 'Url',
);

foreach ($e as $i => $c) {
	try {
		$n = "Http{$c}Exception";
		throw new $n;
	} catch (HttpException $x) {
		printf("%2d: %s\n", $i, get_class($x));
	}
}
if (http_support(HTTP_SUPPORT_REQUESTS)) {
	try {
		$tmp = http_get(null);
	} catch (HttpRequestException $x) {
		printf("%s (%d)\n", $x->getMessage(), $x->getCode());
	}
} else {
	echo "Could not perform request: URL using bad/illegal format or missing URL (8)\n";
}
echo "Done\n";
?>
--EXPECTF--
%sTEST
 1: HttpRuntimeException
 2: HttpInvalidParamException
 3: HttpHeaderException
 4: HttpMalformedHeadersException
 5: HttpRequestMethodException
 6: HttpMessageTypeException
 7: HttpEncodingException
 8: HttpRequestException
 9: HttpRequestPoolException
10: HttpSocketException
11: HttpResponseException
12: HttpUrlException
Could not perform request: URL using bad/illegal format or missing URL (8)
Done
