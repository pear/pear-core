<?php
class PEAR2_Registry_Sqlite_Channel_Mirrors extends PEAR2_Registry_Sqlite
    implements Iterator
{
    private $_channelname;
    private $_mirrors;
    private $_mirrorIndex;
    function __construct(PEAR2_Registry_Sqlite $cloner, $channel)
    {
        $this->_channelname = $channel;
        parent::__construct($cloner->getDatabase());
        $this->_mirrors = $this->getMirrors($channel);
    }

    function current()
    {
        $a = new PEAR2_Registry_Sqlite_Channel_Mirror($this, $this->_channelname);
        $z = current($this->_mirrors);
        return $a[$z['server']];
    }

    function key()
    {
        $a = current($this->_mirrors);
        return $a['server'];
    }

    function next()
    {
        next($this->_mirrors);
    }

    function rewind()
    {
        $this->_mirrorIndex = 0;
    }

    function valid()
    {
        return current($this->_mirrors);
    }
}