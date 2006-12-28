<?php
class PEAR2_Registry_Sqlite_Channel extends PEAR2_Registry_Sqlite
    implements ArrayAccess, PEAR2_IChannelFile
{
    private $_channelname;
    private $_mirror;
    function __construct(PEAR2_Registry_Sqlite $cloner)
    {
        parent::__construct(dirname($cloner->getDatabase()));
    }

    function offsetExists($offset)
    {
        if ($offset[0] == '#') {
            return $this->channelExists(substr($offset, 1), false);
        }
        return $this->channelExists($offset);
    }

    function offsetGet($offset)
 	{
 	    $this->_channelname = $offset;
 	    $ret = clone $this;
 	    return $ret;
 	}
 	
 	function offsetSet($offset, $value)
 	{
 	    if ($offset == 'update') {
 	        $this->updateChannel($value);
 	    }
 	    if ($offset == 'add') {
 	        $this->addChannel($value);
 	    }
 	}

 	function offsetUnset($offset)
 	{
 	    $this->deleteChannel($offset);
 	}

 	function getName()
 	{
 	    return $this->_channelName;
 	}

 	function getSummary()
 	{
 	    return $this->database->singleQuery('SELECT summary FROM channels WHERE ' .
 	          'channel=\'' . sqlite_escape_string($this->_channelname) . '\'');
 	}

 	function getPort($mirror = false)
 	{
 	    return $this->database->singleQuery('SELECT port FROM channel_servers WHERE
 	          channel=\'' . sqlite_escape_string($this->_channelname) . '\' AND
 	          server=\'' . sqlite_escape_string($this->_channelname) . '\'');
 	}

 	function getSSL($mirror = false)
 	{
 	    return $this->database->singleQuery('SELECT ssl FROM channel_servers WHERE
 	          channel=\'' . sqlite_escape_string($this->_channelname) . '\' AND
 	          server=\'' . sqlite_escape_string($this->_channelname) . '\'');
 	}

 	function getValidatePackage($packagename)
 	{
 	    $r = $this->database->singleQuery('SELECT validatepackage ' .
 	          'FROM channels WHERE ' .
 	          'channel=\'' . sqlite_escape_string($this->_channelname) . '\'');
        if ($r == $packagename) {
            return 'PEAR2_Validate';
        }
        if ($r == 'PEAR_Validate' || $r == 'PEAR_Validate_PECL') {
            return str_replace('PEAR_', 'PEAR2_', $r);
        }
        return $r;
 	}

 	function getValidationObject($package)
 	{
 	    $a = $this->getValidatePackage($package);
 	    return new $a;
 	}

 	function __get($value)
 	{
 	    if (!isset($this->_channelname)) {
 	        throw new PEAR2_Registry_Exception('Action requested for unknown channel');
 	    }
 	    switch ($value) {
 	        case 'mirror' :
 	            $a = new PEAR2_Registry_Sqlite_Channel_Mirror($this, $this->_channelname);
 	            return $a;
 	        case 'mirrors' :
 	            $a = new PEAR2_Registry_Sqlite_Channel_Mirrors($this, $this->_channelname);
 	            return $a;
 	    }
 	}
}