<?php
class PEAR_Installer_Role_Srcf extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('extsrc'),
            'installable' => false,
            'locationconfig' => false,
            'honorsbaseinstall' => false,
            'unusualbaseinstall' => false,
            'phpfile' => false,
            'executable' => false,
        );
    function getInfo()
    {
        return array(
            'releasetypes' => array('extsrc'),
            'installable' => false,
            'locationconfig' => false,
            'honorsbaseinstall' => false,
            'unusualbaseinstall' => false,
            'phpfile' => false,
            'executable' => false,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
        $installer->source_files++;
    }
}
?>