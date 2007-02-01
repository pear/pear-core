<?php
class PEAR2_Downloader
{
    /**
     * Download using the cURL extension
     *
     * @param unknown_type $url
     * @param unknown_type $ui
     * @param unknown_type $save_dir
     * @param unknown_type $callback
     * @param unknown_type $lastmodified
     * @param unknown_type $accept
     */
    static function downloadCurlHttp($url, &$ui, $save_dir = '.', $callback = null, $lastmodified = null,
                              $accept = false)
    {
        if (!function_exists('curl_init')) {
            throw new PEAR2_Exception('Cannot download with cURL - cURL extension is not enabled');
        }
        $c = curl_init();
        if (is_array($url)) {
            foreach ($url as $u) {
                curl_setopt($c, CURLOPT_URL, $u);
            }
        } else {
            curl_setopt($c, CURLOPT_URL, $url);
        }
        if ($config->http_proxy && 
              $proxy = parse_url($config->http_proxy)) {
            curl_setopt($c, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            $proxy_host = isset($proxy['host']) ? $proxy['host'] : null;
            $proxy_host = $proxy['scheme'] . '://' . $proxy_host;
            $proxy_port = isset($proxy['port']) ? $proxy['port'] : 8080;
            $proxy_user = isset($proxy['user']) ? urldecode($proxy['user']) : null;
            $proxy_pass = isset($proxy['pass']) ? urldecode($proxy['pass']) : null;
            curl_setopt($c, CURLOPT_PROXY, $proxy_host);
            curl_setopt($c, CURLOPT_PROXYPORT, $proxy_port);
            curl_setopt($c, CURLOPT_PROXYUSERPWD, $proxy_user . ':' . $proxy_pass);

            if ($callback) {
                call_user_func($callback, 'message', "Using HTTP proxy $host:$port");
            }
        } else {
            
        }
    }

    /**
     * Download a file through HTTP.  Considers suggested file name in
     * Content-disposition: header and can run a callback function for
     * different events.  The callback will be called with two
     * parameters: the callback type, and parameters.  The implemented
     * callback types are:
     *
     *  'setup'       called at the very beginning, parameter is a UI object
     *                that should be used for all output
     *  'message'     the parameter is a string with an informational message
     *  'saveas'      may be used to save with a different file name, the
     *                parameter is the filename that is about to be used.
     *                If a 'saveas' callback returns a non-empty string,
     *                that file name will be used as the filename instead.
     *                Note that $save_dir will not be affected by this, only
     *                the basename of the file.
     *  'start'       download is starting, parameter is number of bytes
     *                that are expected, or -1 if unknown
     *  'bytesread'   parameter is the number of bytes read so far
     *  'done'        download is complete, parameter is the total number
     *                of bytes read
     *  'connfailed'  if the TCP/SSL connection fails, this callback is called
     *                with array(host,port,errno,errmsg)
     *  'writefailed' if writing to disk fails, this callback is called
     *                with array(destfile,errmsg)
     *
     * If an HTTP proxy has been configured (http_proxy PEAR_Config
     * setting), the proxy will be used.
     *
     * @param string  $url       the URL to download
     * @param object  $ui        PEAR_Frontend_* instance
     * @param object  $config    PEAR_Config instance
     * @param string  $save_dir  directory to save file in
     * @param mixed   $callback  function/method to call for status
     *                           updates
     * @param false|string|array $lastmodified header values to check against for caching
     *                           use false to return the header values from this download
     * @param false|array $accept Accept headers to send
     * @return string|array  Returns the full path of the downloaded file or a PEAR
     *                       error on failure.  If the error is caused by
     *                       socket-related errors, the error object will
     *                       have the fsockopen error code available through
     *                       getCode().  If caching is requested, then return the header
     *                       values.
     *
     */
    static function get($url, $ui, $save_dir = '.', $callback = null, $lastmodified = null,
                          $accept = false)
    {
        try {
            return self::downloadCurlHttp($url, $ui, $save_dir, $callback, $lastmodified, $accept);
        } catch (Exception $e) {
            // fall back to fsockopen()
        }
        if (is_array($url)) {
            // download multiple urls
            $ret = array();
            foreach ($url as $u) {
                try {
                    $ret[$u] = $this->get($u, $ui, $save_dir, $callback, $lastmodified, $accept);
                } catch (Exception $e) {
                    $ret[$u] = null;
                }
            }
            return $ret;
        }
        static $redirect = 0;
        // allways reset , so we are clean case of error
        $wasredirect = $redirect;
        $redirect = 0;
        if ($callback) {
            call_user_func($callback, 'setup', array(&$ui));
        }
        $info = parse_url($url);
        if (!isset($info['scheme']) || !in_array($info['scheme'], array('http', 'https'))) {
            throw new PEAR2_Exception('Cannot download non-http URL "' . $url . '"');
        }
        if (!isset($info['host'])) {
            throw new PEAR2_Exception('Cannot download from non-URL "' . $url . '"');
        } else {
            $host = isset($info['host']) ? $info['host'] : null;
            $port = isset($info['port']) ? $info['port'] : null;
            $path = isset($info['path']) ? $info['path'] : null;
        }
        if (isset($this)) {
            $config = &$this->config;
        } else {
            $config = PEAR2_Config::singleton();
        }
        $proxy_host = $proxy_port = $proxy_user = $proxy_pass = '';
        if ($config->http_proxy && 
              $proxy = parse_url($config->http_proxy)) {
            $proxy_host = isset($proxy['host']) ? $proxy['host'] : null;
            if (isset($proxy['scheme']) && $proxy['scheme'] == 'https') {
                $proxy_host = 'ssl://' . $proxy_host;
            }
            $proxy_port = isset($proxy['port']) ? $proxy['port'] : 8080;
            $proxy_user = isset($proxy['user']) ? urldecode($proxy['user']) : null;
            $proxy_pass = isset($proxy['pass']) ? urldecode($proxy['pass']) : null;

            if ($callback) {
                call_user_func($callback, 'message', "Using HTTP proxy $host:$port");
            }
        }
        if (empty($port)) {
            if (isset($info['scheme']) && $info['scheme'] == 'https') {
                $port = 443;
            } else {
                $port = 80;
            }
        }
        if ($proxy_host != '') {
            $fp = @fsockopen($proxy_host, $proxy_port, $errno, $errstr);
            if (!$fp) {
                if ($callback) {
                    call_user_func($callback, 'connfailed', array($proxy_host, $proxy_port,
                                                                  $errno, $errstr));
                }
                throw new PEAR2_Exception("Connection to `$proxy_host:$proxy_port' failed: $errstr", $errno);
            }
            if ($lastmodified === false || $lastmodified) {
                $request = "GET $url HTTP/1.1\r\n";
            } else {
                $request = "GET $url HTTP/1.0\r\n";
            }
        } else {
            if (isset($info['scheme']) && $info['scheme'] == 'https') {
                $host = 'ssl://' . $host;
            }
            $fp = @fsockopen($host, $port, $errno, $errstr);
            if (!$fp) {
                if ($callback) {
                    call_user_func($callback, 'connfailed', array($host, $port,
                                                                  $errno, $errstr));
                }
                throw new PEAR2_Exception("Connection to `$host:$port' failed: $errstr", $errno);
            }
            if ($lastmodified === false || $lastmodified) {
                $request = "GET $path HTTP/1.1\r\n";
                $request .= "Host: $host:$port\r\n";
            } else {
                $request = "GET $path HTTP/1.0\r\n";
                $request .= "Host: $host\r\n";
            }
        }
        $ifmodifiedsince = '';
        if (is_array($lastmodified)) {
            if (isset($lastmodified['Last-Modified'])) {
                $ifmodifiedsince = 'If-Modified-Since: ' . $lastmodified['Last-Modified'] . "\r\n";
            }
            if (isset($lastmodified['ETag'])) {
                $ifmodifiedsince .= "If-None-Match: $lastmodified[ETag]\r\n";
            }
        } else {
            $ifmodifiedsince = ($lastmodified ? "If-Modified-Since: $lastmodified\r\n" : '');
        }
        $request .= $ifmodifiedsince . "User-Agent: PEAR/@package_version@/PHP/" .
            PHP_VERSION . "\r\n";
        if (isset($this)) { // only pass in authentication for non-static calls
            $username = $config->get('username');
            $password = $config->get('password');
            if ($username && $password) {
                $tmp = base64_encode("$username:$password");
                $request .= "Authorization: Basic $tmp\r\n";
            }
        }
        if ($proxy_host != '' && $proxy_user != '') {
            $request .= 'Proxy-Authorization: Basic ' .
                base64_encode($proxy_user . ':' . $proxy_pass) . "\r\n";
        }
        if ($accept) {
            $request .= 'Accept: ' . implode(', ', $accept) . "\r\n";
        }
        $request .= "Connection: close\r\n";
        $request .= "\r\n";
        fwrite($fp, $request);
        $headers = array();
        $reply = 0;
        while (trim($line = fgets($fp, 1024))) {
            if (preg_match('/^([^:]+):\s+(.*)\s*$/', $line, $matches)) {
                $headers[strtolower($matches[1])] = trim($matches[2]);
            } elseif (preg_match('|^HTTP/1.[01] ([0-9]{3}) |', $line, $matches)) {
                $reply = (int) $matches[1];
                if ($reply == 304 && ($lastmodified || ($lastmodified === false))) {
                    return false;
                }
                if (! in_array($reply, array(200, 301, 302, 303, 305, 307))) {
                    throw new PEAR2_Exception("File http://$host:$port$path not valid (received: $line)");
                }
            }
        }
        if ($reply != 200) {
            if (isset($headers['location'])) {
                if ($wasredirect < 5) {
                    $redirect = $wasredirect + 1;
                    return $this->downloadHttp($headers['location'],
                            $ui, $save_dir, $callback, $lastmodified, $accept);
                } else {
                    throw new PEAR2_Exception("File http://$host:$port$path not valid (redirection looped more than 5 times)");
                }
            } else {
                throw new PEAR2_Exception("File http://$host:$port$path not valid (redirected but no location)");
            }
        }
        if (isset($headers['content-disposition']) &&
            preg_match('/\sfilename=\"([^;]*\S)\"\s*(;|$)/', $headers['content-disposition'], $matches)) {
            $save_as = basename($matches[1]);
        } else {
            $save_as = basename($url);
        }
        if ($callback) {
            $tmp = call_user_func($callback, 'saveas', $save_as);
            if ($tmp) {
                $save_as = $tmp;
            }
        }
        $dest_file = $save_dir . DIRECTORY_SEPARATOR . $save_as;
        if (!$wp = @fopen($dest_file, 'wb')) {
            fclose($fp);
            if ($callback) {
                call_user_func($callback, 'writefailed', array($dest_file, $php_errormsg));
            }
            throw new PEAR2_Exception("could not open $dest_file for writing");
        }
        if (isset($headers['content-length'])) {
            $length = $headers['content-length'];
        } else {
            $length = -1;
        }
        $bytes = 0;
        if ($callback) {
            call_user_func($callback, 'start', array(basename($dest_file), $length));
        }
        while ($data = fread($fp, 1024)) {
            $bytes += strlen($data);
            if ($callback) {
                call_user_func($callback, 'bytesread', $bytes);
            }
            if (!@fwrite($wp, $data)) {
                fclose($fp);
                if ($callback) {
                    call_user_func($callback, 'writefailed', array($dest_file, $php_errormsg));
                }
                throw new PEAR2_Exception("$dest_file: write failed ($php_errormsg)");
            }
        }
        fclose($fp);
        fclose($wp);
        if ($callback) {
            call_user_func($callback, 'done', $bytes);
        }
        if ($lastmodified === false || $lastmodified) {
            if (isset($headers['etag'])) {
                $lastmodified = array('ETag' => $headers['etag']);
            }
            if (isset($headers['last-modified'])) {
                if (is_array($lastmodified)) {
                    $lastmodified['Last-Modified'] = $headers['last-modified'];
                } else {
                    $lastmodified = $headers['last-modified'];
                }
            }
            return array($dest_file, $lastmodified, $headers);
        }
        return $dest_file;
    }
}