<?php
class PEAR_Installer_Role_Ext extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('extbin'),
            'installable' => true,
            'locationconfig' => 'ext_dir',
            'honorsbaseinstall' => true,
            'phpfile' => false,
            'executable' => false,
            'phpextension' => true,
        );
    function getInfo()
    {
        return array(
            'releasetypes' => array('extbin'),
            'installable' => true,
            'locationconfig' => 'ext_dir',
            'honorsbaseinstall' => true,
            'phpfile' => false,
            'executable' => false,
            'phpextension' => true,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
    }
}
?>