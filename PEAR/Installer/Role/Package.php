<?php
class PEAR_Installer_Role_Package extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('php', 'extsrc', 'extbin', 'bundle'),
            'installable' => false,
            'locationconfig' => false,
            'honorsbaseinstall' => false,
            'phpfile' => false,
             'executable' => false,
       );
    function getInfo()
    {
        return array(
            'releasetypes' => array('php', 'extsrc', 'extbin', 'bundle'),
            'installable' => false,
            'locationconfig' => false,
            'honorsbaseinstall' => false,
            'phpfile' => false,
            'executable' => false,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
    }
}
?>