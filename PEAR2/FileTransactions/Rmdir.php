<?php
class PEAR2_FileTransactions_Rmdir implements PEAR2_IFileTransaction
{
    public function check($data, &$errors)
    {
        
    }

    public function commit($data, &$errors)
    {
        if (file_exists($data[0])) {
            do {
                $testme = opendir($data[0]);
                while (false !== ($entry = readdir($testme))) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    closedir($testme);
                    break 2; // this directory is not empty and can't be
                             // deleted
                }
                closedir($testme);
                if (!@rmdir($data[0])) {
                    PEAR2_Log::log(1, 'Could not rmdir ' . $data[0] . ' ' .
                        $php_errormsg);
                    return false;
                }
                $this->log(3, "+ rmdir $data[0]");
            } while (false);
        }
    }

    public function rollback($data, &$errors)
    {
        
    }

    public function cleanup(){}
}