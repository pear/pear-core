<?php
/**
 * PEAR_FTP
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
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 1.4.0a1
 */

/**
 * FTP class used for PEAR's remote installation feature
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 1.4.0a1
 */
class PEAR_FTP extends Net_FTP
{
    /**
     * @var array
     * @access private
     */
    var $_parsed;

    /**
     * @param string full url to remote config file
     * @return true|PEAR_Error
     */
    function init($url = null)
    {
        if ($url !== null) {
            $this->_parsed = @parse_url($url);
        }
        if (!isset($this->_parsed['host'])) {
            return PEAR::raiseError('No FTP Host specified');
        }
        if (!isset($this->_parsed['path'])) {
            return PEAR::raiseError('No FTP file path to remote config specified');
        }
        $host = $this->_parsed['host'];
        $user = @$this->_parsed['user'];
        $pass = @$this->_parsed['pass'];
        $port = @$this->_parsed['port'];
        $path = @$this->_parsed['path'];
        $this->Net_FTP($host, $port, 30); // 30 second timeout
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $e = $this->connect();
        if (PEAR::isError($e)) {
            $this->popErrorHandling();
            return $e;
        }
        $e  = $this->login($user, $pass);
        if (PEAR::isError($e)) {
            $this->popErrorHandling();
            return $e;
        }
        $path = dirname($path);
        if ($path == '\\') { // windows will do this
            $path = '/';
        }
        $e = $this->cd($path);
        if (PEAR::isError($e)) {
            $this->popErrorHandling();
            return $e;
        }
        return true;
    }

    /**
     * This works similar to the mkdir-command on your local machine. You can either give
     * it an absolute or relative path. The relative path will be completed with the actual
     * selected server-path. (see: pwd())
     *
     * @access  public
     * @param   string $dir       Absolute or relative dir-path
     * @param   bool   $recursive (optional) Create all needed directories
     * @return  mixed             True on success, otherwise PEAR::Error
     * @see     NET_FTP_ERR_CREATEDIR_FAILED
     */
    function mkdir($dir, $recursive = false)
    {
        $dir = $this->_construct_path($dir);
        $savedir = $this->pwd();
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $e = $this->cd($dir);
        $this->popErrorHandling();
        if ($e === true) {
            $this->cd($savedir);
            return true;
        }
        $this->cd($savedir);
        if ($recursive === false) {
            if (method_exists($this, '_testftp_mkdir')) {
                $res = $this->_testftp_mkdir($this->_handle, $dir);
            } else {
                $res = @ftp_mkdir($this->_handle, $dir);
            }
            if (!$res) {
                return $this->raiseError("Creation of '$dir' failed", NET_FTP_ERR_CREATEDIR_FAILED);
            } else {
                return true;
            }
        } else {
            if(strpos($dir, '/') === false) {
                return $this->mkdir($dir,false);
            }
            $pos = 0;
            $res = $this->mkdir(dirname($dir), true);
            $res = $this->mkdir($dir, false);
            if ($res !== true) {
                return $res;
            }
            return true;
        }
    }

    /**
     * @param string full path to local file
     * @param string full path to remote file
     */
    function installFile($local, $remote)
    {
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $this->mkdir(dirname($remote), true);
        $this->popErrorHandling();
        return $this->put($local, $remote, true);
    }
}
?>