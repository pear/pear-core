<?php
/**
 * Abstract representation of a package
 *
 * specific package types are:
 * 
 * - package.xml
 * - package.tgz/package.tar
 * - package.phar
 * - remote undownloaded package
 */
class PEAR2_Package implements IteratorAggregate, ArrayAccess
{
    /**
     * The actual package representation
     *
     * @var PEAR_Package_Xml|PEAR_Package_Tar|PEAR_Package_Phar
     */
    private $internal;

    function __construct($packagedescription, $forceremote = false)
    {
        if ($forceremote) {
            $this->internal = new PEAR_Package_Remote($packagedescription);
        } else {
            $class = $this->_parsePackageDescription($packagedescription);
            $this->internal = new $class($packagedescription, $this);
        }
    }

    function __get($var)
    {
        return $this->internal->$var;
    }

    function __call($func, $args)
    {
        // delegate to the internal object
        return call_user_func_array(array($this->internal, $func), $args);
    }

    function getIterator()
    {
        return $this->internal;
    }

    function offsetExists($offset)
    {
        return isset($this->internal[$offset]);
    }

    function offsetGet($offset)
    {
        return $this->internal[$offset];
    }

    function offsetSet($offset, $value)
    {
        $this->internal[$offset] = $value;
    }

    function offsetUnset($offset)
    {
        unset($this->internal[$offset]);
    }

    function isRemote()
    {
        return $this->internal instanceof PEAR2_Package_Remote;
    }

    function download()
    {
        if ($this->internal instanceof PEAR2_Package_Remote) {
            $this->internal = $this->internal->download();
        }
    }

    function _parsePackageDescription($package)
    {
        if (strpos($package, 'http://') === 0) {
            return 'PEAR2_Package_Remote';
        }
        try {
            $info = PEAR2_Registry::parsePackageName($package);
        } catch (Exception $e) {
            // not a remote package
            if (file_exists($package)) {
                $info = pathinfo($package);
                if (!isset($info['extension']) || !strlen($info['extension'])) {
                    // guess based on first 4 characters
                    $f = @fopen($package, 'r');
                    if ($f) {
                        $first4 = fread($f, 4);
                        fclose($f);
                        if ($first4 == '<?xml') {
                            return 'PEAR2_Package_Xml';
                        }
                        return 'PEAR2_Package_Tar';
                    }
                } else {
                    switch (strtolower($info['extension'])) {
                        case 'xml' :
                            return 'PEAR2_Package_Xml';
                        case 'tar' :
                        case 'tgz' :
                            return 'PEAR2_Package_Tar';
                        case 'phar' :
                            return 'PEAR2_Package_Phar';
                    }
                }
            }
            throw new PEAR2_Package_Exception('package "' . $package . '" is unknown');
        }
    }
}