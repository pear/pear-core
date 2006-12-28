<?php
class PEAR2_Registry_Channel extends PEAR2_Registry implements ArrayAccess
{
    private $_channelname;
    function __construct(PEAR2_Registry_Sqlite $cloner)
    {
        parent::__construct($cloner->getDatabase());
    }

    function offsetExists($offset)
    {
        if ($offset[0] == '#') {
            return $this->sqlite->channelExists(substr($offset, 1), false);
        }
        return $this->sqlite->channelExists($offset);
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
}