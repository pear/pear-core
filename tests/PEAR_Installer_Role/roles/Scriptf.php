<?php
class PEAR_Installer_Role_Scriptf extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('php', 'extsrc', 'extbin'),
            'installable' => true,
            'locationconfig' => 'bin_dir',
            'honorsbaseinstall' => true,
            'unusualbaseinstall' => false,
            'phpfile' => false,
            'executable' => true,
            'phpextension' => false,
        );
    function getInfo()
    {
        return array(
            'releasetypes' => array('php', 'extsrc', 'extbin'),
            'installable' => true,
            'locationconfig' => 'bin_dir',
            'honorsbaseinstall' => true,
            'unusualbaseinstall' => false,
            'phpfile' => false,
            'executable' => true,
            'phpextension' => false,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
    }
}
?>