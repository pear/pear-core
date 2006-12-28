<?php
class PEAR2_Registry_Sqlite_Channel_Mirror extends PEAR2_Registry_Sqlite
    implements ArrayAccess, Iterator
{
    private $_channelname;
    private $_mirror;
    private $_mirrorIndex;
    function __construct(PEAR2_Registry_Sqlite $cloner, $channel)
    {
        $this->_channelname = $channel;
        parent::__construct($cloner->getDatabase());
    }

    function offsetExists($offset)
    {
        return $this->mirrorExists($offset);
    }

    function offsetGet($offset)
 	{
 	    $this->_mirror = $offset;
 	    $ret = clone $this;
 	    return $ret;
 	}
 	
 	function offsetSet($offset, $value)
 	{
 	    return false;
 	}

 	function offsetUnset($offset)
 	{
 	    return false;
 	}

 	function current()
    {
        
    }

    function key()
    {
        
    }

    function next()
    {
        
    }

    function rewind()
    {
        $this->_mirrorIndex = 0;
    }

    function valid()
    {
        
    }

 	function __get($value)
 	{
 	    if (!isset($this->_channelname)) {
 	        throw new PEAR2_Registry_Exception('Action requested for unknown channel');
 	    }
 	    if (!isset($this->_mirror)) {
 	        throw new PEAR2_Registry_Exception('Action requested for unknown mirror of ' .
 	          'channel ' . $this->_channelname);
 	    }
 	    switch ($value) {
 	        case 'mirror' :
 	        case 'mirrors' :
 	        case 'summary' :
 	        case 'validatepackage' :
 	        case 'validator' :
 	    }
 	}
}