<?php
require_once 'Net/FTP.php';
class PEAR_FTP extends Net_FTP
{
    var $_parsed;

    function init($url = null)
    {
        if ($url !== null) {
            $this->_parsed = parse_url($url);
        }
        if (!isset($this->_parsed['host'])) {
            return PEAR::raiseError('No FTP Host specified');
        }
        if (!isset($this->_parsed['path'])) {
            return PEAR::raiseError('No file path specified');
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
        $e = $this->cd(dirname($path));
        if (PEAR::isError($e)) {
            $this->popErrorHandling();
            return $e;
        }
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
        if ($recursive === false){
            $res = @ftp_mkdir($this->_handle, $dir);
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

    function installFile($local, $remote)
    {
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $this->mkdir(dirname($remote), true);
        $this->popErrorHandling();
        return $this->put($local, $remote, true);
    }
}
?>