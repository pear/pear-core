<?php
class PEAR_Installer_Role_Extf extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('extbin'),
            'installable' => true,
            'locationconfig' => 'ext_dir',
            'honorsbaseinstall' => true,
            'unusualbaseinstall' => false,
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
            'unusualbaseinstall' => false,
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