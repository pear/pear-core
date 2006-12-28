<?php
class PEAR2_FileTransactions_Installedas implements PEAR2_IFileTransaction
{
    private $_dirTree = array();
    private $pkginfo;

    public function reset(PEAR2_Package $package)
    {
        $this->pkginfo = $package;
        $this->_dirTree = array();
    }

    public function check($data, &$errors)
    {
        
    }

    public function commit($data, &$errors)
    {
        $this->pkginfo->setInstalledAs($data[0], $data[1]);
        if (!isset($this->_dirtree[dirname($data[1])])) {
            $this->_dirtree[dirname($data[1])] = true;
            $this->pkginfo->setDirtree(dirname($data[1]));

            while(!empty($data[3]) && $data[3] != '/' && $data[3] != '\\'
                  && $data[3] != '.') {
                $this->pkginfo->setDirtree($pp =
                    $this->_prependPath($data[3], $data[2]));
                $this->_dirtree[$pp] = true;
                $data[3] = dirname($data[3]);
            }
        }
    }
    function _prependPath($path, $prepend)
    {
        if (strlen($prepend) > 0) {
            if (OS_WINDOWS && preg_match('/^[a-z]:/i', $path)) {
                if (preg_match('/^[a-z]:/i', $prepend)) {
                    $prepend = substr($prepend, 2);
                } elseif ($prepend{0} != '\\') {
                    $prepend = "\\$prepend";
                }
                $path = substr($path, 0, 2) . $prepend . substr($path, 2);
            } else {
                $path = $prepend . $path;
            }
        }
        return $path;
    }

    public function rollback($data, &$errors)
    {
        $this->pkginfo->setInstalledAs($data[0], false);
    }

    public function cleanup()
    {
        $this->pkginfo->resetDirTree();
    }
}