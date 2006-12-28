<?php
class PEAR2_Registry_Xml
{
    private $_path;
    private $_sqlite;
    function __construct($path, PEAR2_Registry_Sqlite $sqlite)
    {
        $this->_path = $path;
        $this->_sqlite = $sqlite;
    }

    /**
     * Create the Channel!PackageName-Version-package.xml file
     *
     * @param PEAR2_PackageFile_v2 $pf
     */
    function installPackage(PEAR2_PackageFile_v2 $pf)
    {
        file_put_contents(PEAR2_Config::current()->path . DIRECTORY_SEPARATOR .
            str_replace('/', '!', $pf->getChannel()) . '!' . $pf->getPackage() .
            '-' . $pf->getVersion() . '-package.xml', $pf->asXml());
    }

    function upgradePackage(PEAR2_PackageFile_v2 $pf)
    {
        @unlink(str_replace('/', '!', $pf->getChannel()) . '!' . $pf->getPackage() .
            '-' . $this->_sqlite->package[$pf->getChannel() . '/' . $pf->getPackage()]->version .
            '-package.xml');
        $this->installPackage($pf);
    }

    function uninstallPackage($package, $channel, $version)
    {
        @unlink(str_replace('/', '!', $channel) . '!' . $package .
            '-' . $version . '-package.xml');
    }
}