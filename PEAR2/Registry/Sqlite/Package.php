<?php
class PEAR2_Registry_Sqlite_Package extends PEAR2_Registry_Sqlite implements ArrayAccess
{
    private $_packagename;
    function __construct(PEAR2_Registry_Sqlite $cloner)
    {
        parent::__construct($cloner->getDatabase());
    }

    function offsetExists($offset)
    {
 	    $info = $this->parsePackageName($offset);
        return $this->packageExists($info['package'], $info['channel']);
    }

    function offsetGet($offset)
 	{
 	    $this->_packagename = $offset;
 	    $ret = clone $this;
 	    unset($this->_packagename);
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

 	function __get($var)
 	{
 	    if (!isset($this->_packagename)) {
 	        throw new PEAR2_Registry_Exception('Attempt to retrieve ' . $var .
                ' from unknown package');
 	    }
 	    $info = $this->parsePackageName($this->_packagename);
 	    return $this->packageInfo($info['package'], $info['channel'], $var);
 	}
}