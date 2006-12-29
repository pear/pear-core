<?php
class PEAR2_Package_Xml implements ArrayAccess, Iterator
{
    private $_parent;
    private $_packagefile;
    function __construct($package, PEAR2_Package $parent)
    {
        $this->_parent = $parent;
        $this->_packagefile = new PEAR2_PackageFile($package);
    }

    function offsetExists($offset)
    {
        return $this->_packagefile->info->hasFile($offset);
    }

    function offsetGet($offset)
    {
        if (strpos($offset, 'contents://') === 0) {
            return $this->_packagefile->info->getFileContents(substr($offset, 11));
        }
        return $this->_packagefile->info->getFile($offset);
    }

    function offsetSet($offset, $value)
    {
        return;
    }

    function offsetUnset($offset)
    {
        return;
    }

    function current()
    {
        return key($this->_packagefile->info->_packageInfo['filelist']);
    }

    function  key()
    {
        return 1;
    }

    function  next()
    {
        next($this->_packagefile->info->_packageInfo['filelist']);
    }

    function  rewind()
    {
        reset($this->_packagefile->info->_packageInfo['filelist']);
    }

    function __call($func, $args)
    {
        // delegate to the internal object
        return call_user_func_array(array($this->_packagefile->info, $func), $args);
    }

    function __get($var)
    {
        return $this->_packagefile->info->$var;
    }

    function getPackageFile()
    {
        return $this->_packagefile;
    }

    function  valid()
    {
        return key($this->_packagefile->info->_packageInfo['filelist']);
    }
}