<?php
/**
 * Registry manager
 *
 * The registry for PEAR2 consists of four related components
 * 
 *  - an sqlite database
 *  - saved original package.xml for each installed package
 *  - saved original channel.xml for each discovered channel
 *  - configuration values at package installation time
 */
class PEAR2_Registry
{
    public $sqlite;
    static private $_registries = array();
    protected function __construct($path)
    {
        $this->sqlite = new PEAR2_Registry_Sqlite($path);
        $this->_xml = new PEAR2_Registry_Xml($path, $this->sqlite);
    }

    static public function singleton($path)
    {
        if (!isset(self::$_registries[$path])) {
            self::$_registries[$path] = new PEAR2_Registry($path);
        }
        return self::$_registries[$path];
    }

    public function installPackage(PEAR2_PackageFile_v2 $info)
    {
        $this->sqlite->installPackage($info);
        $this->_xml->installPackage($info);
    }

    public function upgradePackage(PEAR2_PackageFile_v2 $info)
    {
        $this->_xml->upgradePackage($info);
        $this->sqlite->upgradePackage($info);
    }

    public function uninstallPackage($name, $channel)
    {
        $version = $this->sqlite->package[$channel . '/' . $name]->version;
        unset($this->sqlite->package[$channel . '/' . $name]);
        $this->_xml->uninstallPackage($name, $channel, $version);
    }

    public function addChannel(PEAR2_ChannelFile $channel)
    {
        $this->sqlite->channel['add'] = $channel;
    }

    public function updateChannel(PEAR2_ChannelFile $channel)
    {
        $this->sqlite->channel['update'] = $channel;
    }

    public function deleteChannel($channel)
    {
        unset($this->sqlite->channel[$channel]);
    }

    static public function parsePackageName($pname) 
    {
        if (!count(self::$_registries)) {
            $registry = new PEAR2_Registry_Sqlite(false);
        } else {
            reset(self::$_registries);
            $registry = current(self::$_registries);
        }
        return $registry->parsePackageName($pname);
    }
}