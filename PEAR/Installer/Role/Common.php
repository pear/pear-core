<?php
/**
 * Base class for all installation roles.
 *
 * This class allows extensibility of file roles.  Packages with complex
 * customization can now provide custom file roles along with the possibility of
 * adding configuration values to match.
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Installer_Role_Common
{
    /**
     * @var PEAR_Config
     * @access protected
     */
    var $config;

    /**
     * This must be the same as getInfo(), and is used by instances
     * @var array
     * @access private
     */
    var $_setup =
        array(
            'releasetypes' => array('php', 'extsrc', 'extbin', 'bundle'),
            'installable' => true,
            'locationconfig' => false,
            'honorsbaseinstall' => true,
            'phpfile' => false,
            'executable' => false,
            'phpextension' => false,
        );
    /**
     * This is used at startup to initialize the list of valid file roles, and what each role
     * means in terms of installation.  All values present in the base class must exist in
     * every custom role
     * @return array
     * @static
     */
    function getInfo()
    {
        return array(
            'releasetypes' => array('php', 'extsrc', 'extbin', 'bundle'),
            'installable' => true,
            'locationconfig' => false,
            'honorsbaseinstall' => true,
            'phpfile' => false,
            'executable' => false,
            'phpextension' => false,
        );
    }

    /**
     * This is called for each file to set up the directories and files
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param array attributes from the <file> tag
     * @param string file name
     * @return array an array consisting of:
     *
     *    1 the original, pre-baseinstalldir installation directory
     *    2 the final installation directory
     *    3 the full path to the final location of the file
     *    4 the location of the pre-installation file
     */
    function processInstallation($pkg, $atts, $file, $tmp_path, $layer = null)
    {
        if (!$this->_setup['locationconfig']) {
            return false;
        }
        if ($this->_setup['honorsbaseinstall']) {
            $dest_dir = $save_destdir = $this->config->get($this->_setup['locationconfig'], $layer,
                $pkg->getChannel());
            if (!empty($atts['baseinstalldir'])) {
                $dest_dir .= DIRECTORY_SEPARATOR . $atts['baseinstalldir'];
            }
        } else {
            $dest_dir = $save_destdir = $this->config->get($this->_setup['locationconfig'],
                    null, $pkg->getChannel()) . DIRECTORY_SEPARATOR . $pkg->getPackage();
        }
        if (dirname($file) != '.' && empty($atts['install-as'])) {
            $dest_dir .= DIRECTORY_SEPARATOR . dirname($file);
        }
        if (empty($atts['install-as'])) {
            $dest_file = $dest_dir . DIRECTORY_SEPARATOR . basename($file);
        } else {
            $dest_file = $dest_dir . DIRECTORY_SEPARATOR . $atts['install-as'];
        }
        $orig_file = $tmp_path . DIRECTORY_SEPARATOR . $file;

        // Clean up the DIRECTORY_SEPARATOR mess
        $ds2 = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
        
        list($dest_dir, $dest_file, $orig_file) = preg_replace(array('!\\\\+!', '!/!', "!$ds2+!"),
                                                    array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR,
                                                          DIRECTORY_SEPARATOR),
                                                    array($dest_dir, $dest_file, $orig_file));
        return array($save_destdir, $dest_dir, $dest_file, $orig_file);
    }

    /**
     * This method is called upon instantiating a PEAR_Config object.
     *
     * This method MUST an array of information for all new configuration
     * variables required by the file role.  addConfigVar() expects an array of
     * configuration information that is identical to what is used internally in PEAR_Config
     * @access protected
     * @param PEAR_Config
     */
    function getSupportingConfigVars()
    {
        return array();
    }

    /**
     * Get the name of the configuration variable that specifies the location of this file
     * @return string|false
     */
    function getLocationConfig()
    {
        return $this->_setup['locationconfig'];
    }

    /**
     * @param PEAR_Config
     */
    function PEAR_Installer_Role_Common(&$config)
    {
        $this->config = $config;
    }

    /**
     * Do any unusual setup here
     * @param PEAR_Installer
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param array file attributes
     * @param string file name
     */
    function setup(&$installer, $pkg, $atts, $file)
    {
    }

    function isExecutable()
    {
        return $this->_setup['executable'];
    }

    function isInstallable()
    {
        return $this->_setup['installable'];
    }

    function isExtension()
    {
        return $this->_setup['phpextension'];
    }
}
?>