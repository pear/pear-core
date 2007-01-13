<?php
class PEAR2_Package_Phar
{
    private $_fp;
    private $_packagename;
    private $_internalFileLength;
    private $_footerLength;
    private $_parent;
    private $_packagefile;
    static private $_tempfiles = array();

    /**
     * @param string $package path to package file
     */
    function __construct($package, PEAR2_Package $parent)
    {
        $this->_parent = $parent;
        $this->_packagename = $package;
        $fp = fopen($package, 'rb');
        if (!$fp) {
            throw new PEAR2_Package_Phar_Exception('Cannot open package ' . $package);
        }
        fclose($fp);
        if (!class_exists('Phar')) {
            throw new PEAR2_Package_Phar_Exception('Phar extension is required to ' .
                'read Phars');
        }
    }

    function offsetExists($offset)
    {
        $this->_extract();
        return $this->_packagefile->offsetExists($offset);
    }

    function offsetGet($offset)
    {
        $this->_extract();
        return $this->_packagefile->offsetGet($offset);
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
        $this->_extract();
        return $this->_packagefile->current();
    }

    function  key()
    {
        return $this->_packagefile->key();
    }

    function  next ()
    {
        $this->_extract();
        $this->_packagefile->next();
    }

    function  rewind()
    {
        $this->_extract();
        $this->_packagefile->rewind();
    }

    function __call($func, $args)
    {
        $this->_extract();
        // delegate to the internal object
        return call_user_func_array(array($this->_packagefile, $func), $args);
    }

    function __get($var)
    {
        $this->_extract();
        return $this->_packagefile->$var;
    }

    function getPackageFile()
    {
        $this->_extract();
        return $this->_packagefile->getPackageFile();
    }

    function  valid()
    {
        $this->_extract();
        return $this->_packagefile->valid();
    }

    /**
     * Detect and report a malicious file name
     *
     * @param string $file
     * @return bool
     * @access private
     */
    private function _maliciousFilename($file)
    {
        if (strpos($file, '/../') !== false) {
            return true;
        }
        if (strpos($file, '../') === 0) {
            return true;
        }
        return false;
    }

    /**
     * Extract the archive so we can work with the contents
     *
     */
    private function _extract()
    {
        if (isset($this->_packagefile)) {
            return;
        }
        $packagexml = false;
        $where = (string) PEAR2_Config::current()->temp_dir;
        $where = str_replace('\\', '/', $where);
        $where = str_replace('//', '/', $where);
        $where = str_replace('/', DIRECTORY_SEPARATOR, $where);
        if (!file_exists($where)) {
            mkdir($where, 0777, true);
        }
        $where = realpath($where);
        if (dirname($where . 'a') != $where) {
            $where .= DIRECTORY_SEPARATOR;
        }
        try {
            $phar = new Phar($this->_packagename);
        } catch (Exception $e) {
            throw new PEAR2_Package_Phar_Exception('Unable to open phar ' .
                $this->package, $e);
        }
        foreach ($phar as $file) {
            $extract = $where . $file->getFileName();
            $extract = str_replace('\\', '/', $extract);
            $extract = str_replace('//', '/', $extract);
            $extract = str_replace('/', DIRECTORY_SEPARATOR, $extract);
            self::_addTempFile($extract);
            if (!file_exists(dirname($extract))) {
                self::_addTempDirectory(dirname($extract));
                mkdir(dirname($extract), 0777, true);
            }
            $fp = fopen($extract, 'wb');
            $gp = fopen('phar://' . $this->_packagename . '/' . $file->getFileName());
            $amount = stream_copy_to_stream($gp, $fp, $file->getSize());
            if ($amount != $file->getSize()) {
                throw new PEAR2_Package_Phar_Exception(
                    'Unable to fully extract ' . $header['filename'] . ' from ' .
                    $this->_packagename);
            }
            if (preg_match('/package.xml$/', $file->getFileName()) &&
                  $file->getFileName() != 'package.xml') {
                $packagexml = $extract;
            }
            if (!$packagexml) {
                if ($header['filename'] == 'package2.xml') {
                    $packagexml = $extract;
                } elseif ($header['filename'] == 'package.xml') {
                    $packagexml = $extract;
                }
            }
        }
        if (!$packagexml) {
            throw new PEAR2_Package_Phar_Exception('Phar ' . $this->_packagename .
                ' does not contain a package.xml file');
        }
        $this->_packagefile = new PEAR2_Package_Xml($packagexml, $this->_parent);
    }
}