<?php
/**
 * PEAR_Installer_Role_Cfg
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 1.7.0
 */

/**
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2006 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 1.7.0
 */
class PEAR_Installer_Role_Cfg extends PEAR_Installer_Role_Common
{
    var $actualfilename = false;
    /**
     * Do any unusual setup here
     * @param PEAR_Installer
     * @param PEAR_PackageFile_v2
     * @param array file attributes
     * @param string file name
     */
    function setup(&$installer, $pkg, $atts, $file)
    {
        $fake = '.';
        $this->actualfilename = $file;
        $test = parent::processInstallation($pkg, $atts, $file, $fake);
        if (@file_exists($test[2])) {
            // configuration has already been installed, check for mods
            if (md5_file($test[2]) !== md5_file($file)) {
                // configuration has been modified, so save our version as
                // configfile-version
                $this->actualfilename = '.new-' . $pkg->getVersion();
            }
        }
    }

    function processInstallation($pkg, $atts, $file, $tmp_path, $layer = null)
    {
        $ret = parent::processInstallation($pkg, $atts, $file, $tmp_path, $layer);
        if ($this->actualfilename) {
            // substitute our temporary name
            $ret[3] = $this->actualfilename;
        }
        return $ret;
    }
}
?>