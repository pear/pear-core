<?php
class PEAR_Installer_Role_Script extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('php', 'extsrc', 'extbin'),
            'installable' => true,
            'locationconfig' => 'bin_dir',
            'honorsbaseinstall' => true,
            'phpfile' => false,
            'executable' => true,
        );
    function getInfo()
    {
        return array(
            'releasetypes' => array('php', 'extsrc', 'extbin'),
            'installable' => true,
            'locationconfig' => 'bin_dir',
            'honorsbaseinstall' => true,
            'phpfile' => false,
            'executable' => true,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
    }
}
?>