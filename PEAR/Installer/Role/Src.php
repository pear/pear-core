<?php
class PEAR_Installer_Role_Src extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('extsrc'),
            'installable' => false,
            'locationconfig' => false,
            'honorsbaseinstall' => false,
            'phpfile' => false,
            'executable' => false,
            'phpextension' => false,
        );
    function getInfo()
    {
        return array(
            'releasetypes' => array('extsrc'),
            'installable' => false,
            'locationconfig' => false,
            'honorsbaseinstall' => false,
            'phpfile' => false,
            'executable' => false,
            'phpextension' => false,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
        $installer->source_files++;
    }
}
?>