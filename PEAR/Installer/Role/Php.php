<?php
class PEAR_Installer_Role_Php extends PEAR_Installer_Role_Common
{
    var $_setup =
        array(
            'releasetypes' => array('php'),
            'installable' => true,
            'locationconfig' => 'php_dir',
            'honorsbaseinstall' => true,
            'phpfile' => true,
            'executable' => false,
            'phpextension' => false,
        );
    function getInfo()
    {
        return array(
            'releasetypes' => array('php'),
            'installable' => true,
            'locationconfig' => 'php_dir',
            'honorsbaseinstall' => true,
            'phpfile' => true,
            'executable' => false,
            'phpextension' => false,
        );
    }

    function setup(&$installer, $pkg, $atts, $file)
    {
    }
}
?>