<?php
class PEAR2_FileTransactions_Rename implements PEAR2_IFileTransaction
{
    public function check($data, &$errors)
    {
        if (!file_exists($data[0])) {
            $errors[] = "cannot rename file $data[0], doesn't exist";
        }
        // check that dest dir. is writable
        if (!is_writable(dirname($data[1]))) {
            $errors[] = "permission denied ($type): $data[1]";
        }
    }

    public function commit($data, &$errors)
    {
        if (file_exists($data[1])) {
            $test = @unlink($data[1]);
        } else {
            $test = null;
        }
        if (!$test && file_exists($data[1])) {
            if ($data[2]) {
                $extra = ', this extension must be installed manually.  Rename to "' .
                    basename($data[1]) . '"';
            } else {
                $extra = '';
            }
            if (!isset($this->_options['soft'])) {
                PEAR2_Log::log(1, 'Could not delete ' . $data[1] . ', cannot rename ' .
                    $data[0] . $extra);
            }
            if (!isset($this->_options['ignore-errors'])) {
                return false;
            }
        }
        // permissions issues with rename - copy() is far superior
        $perms = @fileperms($data[0]);
        if (!@copy($data[0], $data[1])) {
            PEAR2_Log::log(1, 'Could not rename ' . $data[0] . ' to ' . $data[1] .
                ' ' . $php_errormsg);
            return false;
        }
        // copy over permissions, otherwise they are lost
        @chmod($data[1], $perms);
        @unlink($data[0]);
        PEAR2_Log::log(3, "+ mv $data[0] $data[1]");
    }

    public function rollback($data, &$errors)
    {
        @unlink($data[0]);
        PEAR2_Log::log(3, "+ rm $data[0]");
    }

    public function cleanup(){}
}