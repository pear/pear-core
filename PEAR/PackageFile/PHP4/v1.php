<?php
require_once 'PEAR/PackageFile/v1.php';
class PEAR_PackageFile_PHP4_v1 extends PEAR_PackageFile_v1
{

    /**
     * Parsed package information
     * @var array
     * @access private
     */
    var $_packageInfo;

    function fromArray($pinfo)
    {
        $this->_packageInfo = $pinfo;
    }

    function getChannel()
    {
        if (isset($this->_packageInfo['channel'])) {
            return $this->_packageInfo['channel'];
        }
        return 'pear';
    }

    function getName()
    {
        return $this->getPackage();
    }

    function getPackage()
    {
        if (isset($this->_packageInfo['package'])) {
            return $this->_packageInfo['package'];
        }
        return false;
    }

    function getVersion()
    {
        if (isset($this->_packageInfo['version'])) {
            return $this->_packageInfo['version'];
        }
        return false;
    }

    function getMaintainers()
    {
        if (isset($this->_packageInfo['maintainers'])) {
            return $this->_packageInfo['maintainers'];
        }
        return false;
    }

    function getState()
    {
        if (isset($this->_packageInfo['release_state'])) {
            return $this->_packageInfo['release_state'];
        }
        return false;
    }

    function getDate()
    {
        if (isset($this->_packageInfo['release_date'])) {
            return $this->_packageInfo['release_date'];
        }
        return false;
    }

    function getLicense()
    {
        if (isset($this->_packageInfo['release_license'])) {
            return $this->_packageInfo['release_license'];
        }
        return false;
    }

    function getSummary()
    {
        if (isset($this->_packageInfo['summary'])) {
            return $this->_packageInfo['summary'];
        }
        return false;
    }

    function getDescription()
    {
        if (isset($this->_packageInfo['description'])) {
            return $this->_packageInfo['description'];
        }
        return false;
    }

    function getNotes()
    {
        if (isset($this->_packageInfo['release_notes'])) {
            return $this->_packageInfo['release_notes'];
        }
        return false;
    }

    function getDeps()
    {
        if (isset($this->_packageInfo['release_deps'])) {
            return $this->_packageInfo['release_deps'];
        }
        return false;
    }

    function hasDeps()
    {
        return isset($this->_packageInfo['release_deps']) &&
            count($this->_packageInfo['release_deps']);
    }
}
?>