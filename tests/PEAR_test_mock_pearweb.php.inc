<?php
require_once 'XML/RPC/Server.php';

class PEAR_test_mock_pearweb {
    var $_config;
    var $_remote;
    var $_xmlrpcCalls = array();
    var $_continue = false;
    
    function setRemote(&$r)
    {
        $this->_remote = &$r;
    }

    function addHtmlConfig($address, $filename, $lastmodified = false)
    {
        $this->_config['html'][$address] = array(basename($filename), file_get_contents($filename),
            $lastmodified);
    }

    function addRESTConfig($address, $content, $content_type)
    {
        $this->_config['rest'][$address] = array($content, $content_type);
    }
    
    function addXmlrpcConfig($server, $method, $params, $return)
    {
        $this->_config['xmlrpc'][$server][$method][serialize($params)] = $return;
    }
    
    function _encode($val)
    {
        $val = XML_RPC_encode($val);
        $ser = new XML_RPC_Response($val);
        return $ser->serialize();
    }

    function receiveREST($address)
    {
        if (!isset($this->_config) || !is_array($this->_config)) {
            $ret = $this->tryParentREST($address);
            if ($this->_continue) {
                if (!$ret) {
                    return $this->do304($address);
                }
                return $this->do200() .
                "ETag: 12345678910\n" .
                "Last-Modified: 12345678910\n" .
                'Content-Type: ' . $ret[2]['content-type'] . "\n" .
                'Content-Length: ' . $ret[2]['content-length'] . "\n\n" .
                $ret[0];
            }
            die('No REST config for URL ' . $address);
        }
        if (!isset($this->_config['rest'][$address])) {
            $ret = $this->tryParentREST($address);
            if ($this->_continue) {
                if (!$ret) {
                    return $this->do304($address);
                }
                return $this->do200() .
                "ETag: 12345678910\n" .
                "Last-Modified: 12345678910\n" .
                'Content-Type: ' . $ret[2]['content-type'] . "\n" .
                'Content-Length: ' . $ret[2]['content-length'] . "\n\n" .
                $ret[0];
            }
            die('No REST config for URL ' . $address);
        } else {
            if (!$this->_config['rest'][$address][0]) {
                $this->_restCalls[] = array($address, '404');
                return $this->do404($address);
            }
            if ($this->_config['rest'][$address][0] === true) {
                $this->_restCalls[] = array($address, '304');
                return $this->do304($address);
            }
            $this->_restCalls[] = array($address, '200');
            return $this->do200() .
                "ETag: 12345678910\n" .
                "Last-Modified: 12345678910\n" .
                'Content-Type: ' . $this->_config['rest'][$address][1] . "\n" .
                'Content-Length: ' . strlen($this->_config['rest'][$address][0]) . "\n\n" .
                $this->_config['rest'][$address][0];
        }
    }

    function getRESTCalls()
    {
        $ret = $this->_restCalls;
        $this->_restCalls = array();
        return $ret;
    }

    function getXmlrpcCalls()
    {
        $ret = $this->_xmlrpcCalls;
        $this->_xmlrpcCalls = array();
        return $ret;
    }

    function tryParentREST($address)
    {
        require_once 'PEAR/REST.php';
        $a = new PEAR_Config;
        $rest = &new PEAR_REST($a, array());
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $ret = $rest->downloadHttp($address, false);
        PEAR::popErrorHandling();
        if (!PEAR::isError($ret)) {
            if (!$ret) {
                echo '$pearweb->addRESTConfig("' . $address . "\", true, 'text/xml');\n";
            } else {
                echo '$pearweb->addRESTConfig("' . $address . '", ' . var_export($ret[0], true)
                    . ", 'text/xml');\n";
            }
            return $ret;
        } else {
                echo '$pearweb->addRESTConfig("' . $address . "\", false, false);\n";
            return false;
        }
    }

    function receiveHttp($address)
    {
        if (!isset($this->_config) || !is_array($this->_config)) {
            return $this->do404($address);
        }
        if (!isset($this->_config['html'][$address])) {
            return $this->do404($address);
        } elseif (isset($this->_config['html'][$address][2])) {
            return $this->do200() .
                $this->_config['html'][$address][2] .
                'Content-Length: ' . strlen($this->_config['html'][$address][1]) . "\n\n" .
                $this->_config['html'][$address][1];
        } else {
            return $this->do200() .
                'Content-Length: ' . strlen($this->_config['html'][$address][1]) . "\n\n" .
                $this->_config['html'][$address][1];
        }
    }
    
    function receiveXmlrpc($postpayload, $server)
    {
        $info = $this->parseRequest($postpayload);
        if (!isset($this->_config['xmlrpc'][$server][$info['method']])) {
            return $this->doXmlrpcFault($info);
        }
        if (!isset($this->_config['xmlrpc'][$server][serialize($info['params'])])) {
            var_dump($info['param']);
            foreach (debug_backtrace() as $i => $trace) {
                echo "#$i $trace[class]$trace[type]$trace[function] from line $trace[line]\n";
            }
            die("Error - parameters not configured properly for $info[method]");
        }
        return $this->do200() .
            $this->_encode(
                $this->_config['xmlrpc'][$server][$info['method']][serialize($info['params'])]);
    }
    
    function call($server, $method, $params = null)
    {
        $this->_xmlrpcCalls[] = array($server, $method, $params);
        if (!isset($this->_config['xmlrpc'][$server][$method])) {
            include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php_dump.php.inc';
            $args = $params;
            switch (count($args)) {
                case 0:
                    $result = $this->_remote->parentcall($method);
                break;
                case 1:
                    $result = $this->_remote->parentcall($method, $args[0]);
                break;
                case 2:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1]);
                break;
                case 3:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2]);
                break;
                case 4:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2], $args[3]);
                break;
                case 5:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2], $args[3], $args[4]);
                break;
                case 6:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
                break;
            }
            if (PEAR::isError($result)) {
                $result = null;
            }
            $dump = new PHP_Dump($result);
            $args = new PHP_Dump($args);
            if (!isset($this->_pearweb->_config['xmlrpc'][$server][$method][serialize($args)]))
            $GLOBALS['totalPHP'][$method . serialize($args)] = '$pearweb->addXmlrpcConfig("' .
                $server . '", "' .
                $method . '", ' .
                $args->toPHP() . ', ' .
                $dump->toPHP() .");";
            foreach($GLOBALS['totalPHP'] as $php) {
                echo $php . "\n";
            }
            foreach (debug_backtrace() as $i => $trace) {
                echo "#$i $trace[class]$trace[type]$trace[function] from line $trace[line]\n";
            }
            var_dump(array_keys($this->_config['xmlrpc'][$server][$method]), $params);
            die("Error - parameters not configured properly for $method");
            return false;
        }
        if (!isset($this->_config['xmlrpc'][$server][$method][serialize($params)])) {
            include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php_dump.php.inc';
            $args = $params;
            switch (count($args)) {
                case 0:
                    $result = $this->_remote->parentcall($method);
                break;
                case 1:
                    $result = $this->_remote->parentcall($method, $args[0]);
                break;
                case 2:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1]);
                break;
                case 3:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2]);
                break;
                case 4:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2], $args[3]);
                break;
                case 5:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2], $args[3], $args[4]);
                break;
                case 6:
                    $result = $this->_remote->parentcall($method, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
                break;
            }
            if (PEAR::isError($result)) {
                $result = null;
            }
            $dump = new PHP_Dump($result);
            $args = new PHP_Dump($args);
            if (!isset($this->_pearweb->_config['xmlrpc'][$server][$method][serialize($args)]))
            $GLOBALS['totalPHP'][$method . serialize($args)] = '$pearweb->addXmlrpcConfig("' .
                $server . '", "' .
                $method . '", ' .
                $args->toPHP() . ', ' .
                $dump->toPHP() .");";
            foreach($GLOBALS['totalPHP'] as $php) {
                echo $php . "\n";
            }
            var_dump(array_keys($this->_config['xmlrpc'][$server][$method]), $params);
            foreach (debug_backtrace() as $i => $trace) {
                echo "#$i $trace[class]$trace[type]$trace[function] from line $trace[line]\n";
            }
            die("Error - parameters not configured properly for $method");
        }
        return $this->_config['xmlrpc'][$server][$method][serialize($params)];
    }
    
    function doXmlrpcFault($info)
    {
        $r = new XML_RPC_Response(0, 1, 'Unknown method');
        return $this->do200() . $r->serialize();
    }
    
    function do200()
    {
        return "HTTP/1.1 200 \n";
    }
    
    function do404($address)
    {
        return 'HTTP/1.1 404 ' . $address . ' Is not valid';
    }

    function do304($address)
    {
        return 'HTTP/1.1 304 ' . $address . ' Unmodified';
    }

    /**
     * Parse an xmlrpc request
     * @param string fake HTTP_RAW_POST_DATA
     * @return string|array serialized fault string, or array containing method name and parameters
     */
    function parseRequest($data)
    {
        // copied from XML_RPC_Server
        global $XML_RPC_xh;
        global $XML_RPC_err, $XML_RPC_str, $XML_RPC_errxml,
            $XML_RPC_defencoding, $XML_RPC_Server_dmap;

        $parser = xml_parser_create($XML_RPC_defencoding);

        $XML_RPC_xh[$parser] = array();
        $XML_RPC_xh[$parser]['st'] = "";
        $XML_RPC_xh[$parser]['cm'] = 0;
        $XML_RPC_xh[$parser]['isf'] = 0;
        $XML_RPC_xh[$parser]['params'] = array();
        $XML_RPC_xh[$parser]['method'] = "";

        $plist = '';

        // decompose incoming XML into request structure

        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($parser, "XML_RPC_se", "XML_RPC_ee");
        xml_set_character_data_handler($parser, "XML_RPC_cd");
        xml_set_default_handler($parser, "XML_RPC_dh");
        if (!xml_parse($parser, $data, 1)) {
            // return XML error as a faultCode
            $r = new XML_RPC_Response(0,
                                      $XML_RPC_errxml+xml_get_error_code($parser),
                                      sprintf("XML error: %s at line %d",
                                              xml_error_string(xml_get_error_code($parser)),
                                              xml_get_current_line_number($parser)));
            xml_parser_free($parser);
            return $r->serialize();
        } else {
            xml_parser_free($parser);
            $params = array();
            // now add parameters in
            for ($i = 0; $i < sizeof($XML_RPC_xh[$parser]['params']); $i++) {
                // print "<!-- " . $XML_RPC_xh[$parser]['params'][$i]. "-->\n";
                $plist .= "$i - " . $XML_RPC_xh[$parser]['params'][$i] . " \n";
                eval('$val = ' . $XML_RPC_xh[$parser]['params'][$i] . ";");
                $param = $val->scalarval();
                $param = PEAR_test_mock_pearweb::_convertScalar($param);
                $params[] = $param;
            }
            return array('method' => $XML_RPC_xh[$parser]['method'], 'params' => $params);
        }
    }
    
    /**
     * Converts the mishmash returned from XML_RPC parsing into a regular PHP value,
     * handling nested arrays gracefully.
     * @param mixed
     * @return mixed
     */
    function _convertScalar($val)
    {
        if (is_a($val, 'XML_RPC_Value')) {
            $val = $val->scalarval();
        }
        if (!is_array($val)) {
            return $val;
        }
        $newval = array();
        foreach ($val as $i => $contents)
        {
            $newval[$i] = PEAR_test_mock_pearweb::_convertScalar($contents);
        }
        return $newval;
    }
}
?>