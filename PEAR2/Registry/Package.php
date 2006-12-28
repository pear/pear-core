<?php
class PEAR2_Registry_Package extends PEAR2_Registry implements ArrayAccess
{
    private $_packagename;
    function __construct(PEAR2_Registry_Sqlite $cloner)
    {
        parent::__construct($cloner->getDatabase());
    }

    function offsetExists($offset)
    {
 	    $info = $this->sqlite->parsePackageName($offset);
        return $this->sqlite->packageExists($info['package'], $info['channel']);
    }

    function offsetGet($offset)
 	{
 	    $this->_packagename = $offset;
 	    $ret = clone $this;
 	    return $ret;
 	}
 	
 	function offsetSet($offset, $value)
 	{
 	    if ($offset == 'upgrade') {
 	        $this->upgradePackage($value);
 	    }
 	    if ($offset == 'install') {
 	        $this->installPackage($value);
 	    }
 	}

 	function offsetUnset($offset)
 	{
 	    $info = $this->parsePackageName($offset);
 	    $this->uninstallPackage($info['package'], $info['channel']);
 	}
}