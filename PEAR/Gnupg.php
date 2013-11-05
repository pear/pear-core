<?php
/**
 * PEAR_Gnupg for signing/verifying releases
 *
 * PHP versions 4 and 5
 *
 * @category   pear
 * @package    PEAR
 * @author     Michael Slusarz <slusarz@horde.org>
 * @copyright  2013 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://pear.php.net/package/PEAR
 */

/**
 * Administration class used to make a PEAR release tarball.
 *
 * @category   pear
 * @package    PEAR
 * @author     Michael Slusarz <slusarz@horde.org>
 * @copyright  2013 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 */
class PEAR_Gnupg
{
    /**
     * @var PEAR_Config
     */
    var $_config;

    function PEAR_Gnupg($config)
    {
        $this->_config = $config;
    }

    function createGpgCmd()
    {
        if ($this->_config->get('sig_type') != 'gpg') {
            return PEAR::raiseError("only support 'gpg' for signature type");
        }

        $sig_bin = $this->_config->get('sig_bin');
        if (empty($sig_bin) || !file_exists($sig_bin)) {
            return PEAR::raiseError("can't access gpg binary: $sig_bin");
        }

        $keyid = trim($this->_config->get('sig_keyid'));

        $keydir = trim($this->_config->get('sig_keydir'));
        if (strlen($keydir) &&
            !file_exists($keydir) &&
            !@mkdir($keydir)) {
            return PEAR::raiseError("sig_keydir '$keydir' doesn't exist or is not accessible");
        }

        $cmd = escapeshellcmd($sig_bin);
        if (strlen($keyid)) {
            $cmd .= " --default-key " . escapeshellarg($keyid);
        }
        if (strlen($keydir)) {
            $cmd .= " --homedir " . escapeshellarg($keydir);
        }

        return $cmd;
    }

    function validateSig($package, $sig)
    {
        // Skip if gpg is not configured.
        $cmd = $this->createGpgCmd();
        if (PEAR::isError($cmd)) {
            return true;
        }

        $cmd .= " --verify " . escapeshellarg($sig) . " " . escapeshellarg($package);

        $result = exec($cmd, $output, $return_var);

        // $return_var: 0 = verified, 1 = bad signature; 2 = no public key
        if ($return_var === 1) {
            return PEAR::raiseError("package signature is BAD!\n" . implode("\n", $output));
        }

        return true;
    }

}
