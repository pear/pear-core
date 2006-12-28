<?php
class PEAR2_FileTransactions
{
    private $fileOperations;
    static private $_registeredTransactions =
        array(
            'backup' => false,
            'chmod' => false,
            'delete' => false,
            'removebackup' => false,
            'mkdir' => false,
            /* to enable the next three, use registerTransaction() */
//            'rename' => false,
//            'rmdir' => false,
//            'installedas' => false,
        );

    /**
     * Add a file operation to the current file transaction.
     *
     * @see begin()
     * @param string $type This can be one of:
     *    - rename:  rename a file ($data has 3 values)
     *    - backup:  backup an existing file ($data has 1 value)
     *    - removebackup:  clean up backups created during install ($data has 1 value)
     *    - chmod:   change permissions on a file ($data has 2 values)
     *    - delete:  delete a file ($data has 1 value)
     *    - rmdir:   delete a directory if empty ($data has 1 value)
     *    - installedas: mark a file as installed ($data has 4 values).
     * @param array $data For all file operations, this array must contain the
     *    full path to the file or directory that is being operated on.  For
     *    the rename command, the first parameter must be the file to rename,
     *    the second its new name, the third whether this is a PHP extension.
     *
     *    The installedas operation contains 4 elements in this order:
     *    1. Filename as listed in the filelist element from package.xml
     *    2. Full path to the installed file
     *    3. Full path from the php_dir configuration variable used in this
     *       installation
     *    4. Relative path from the php_dir that this file is installed in
     */
    function __call($type, $data)
    {
        if ($type == 'chmod') {
            $octmode = decoct($data[0]);
            PEAR2_Log::log(3, "adding to transaction: $type $octmode $data[1]");
        } else {
            PEAR2_Log::log(3, "adding to transaction: $type " . implode(" ", $data));
        }
        $this->fileOperations[] = array($type, $data);
    }

    function begin($rollback_in_case = false)
    {
        if (count($this->fileOperations) && $rollback_in_case) {
            $this->rollback();
        }
        $this->fileOperations = array();
    }

    function commit()
    {
        $n = count($this->fileOperations);
        PEAR2_Log::log(2, "about to commit $n file operations");
        // {{{ first, check permissions and such manually
        $errors = array();
        
        foreach ($this->fileOperations as $tr) {
            list($type, $data) = $tr;
            switch ($type) {
                case 'backup' :
                    break;
                case 'chmod' :
                    // check that file is writable
                    if (!is_writable($data[1])) {
                        $errors[] = "permission denied ($type): $data[1] " . decoct($data[0]);
                    }
                    break;
                case 'delete' :
                    if (!file_exists($data[0])) {
                        PEAR2_Log::log(2, "warning: file $data[0] doesn'" .
                            "t exist, can't be deleted");
                    }
                    // check that directory is writable
                    if (file_exists($data[0])) {
                        if (!is_writable(dirname($data[0]))) {
                            $errors[] = "permission denied ($type): $data[0]";
                        } else {
                            // make sure the file to be deleted can be opened for writing
                            $fp = false;
                            if (!is_dir($data[0]) &&
                                  (!is_writable($data[0]) || !($fp = @fopen($data[0], 'a')))) {
                                $errors[] = "permission denied ($type): $data[0]";
                            } elseif ($fp) {
                                fclose($fp);
                            }
                        }
                    }
                    break;
                case 'mkdir' :
                case 'removebackup' :
                    break;
                default :
                    $callback = self::$_registeredTransactions[$type];
                    $callback->check($data, $errors);
            }
        }
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                if (!isset($this->_options['soft'])) {
                    PEAR2_Log::log(1, $error);
                }
            }
            if (!isset($this->_options['ignore-errors'])) {
                return false;
            }
        }
        $this->_dirtree = array();
        foreach ($this->fileOperations as $tr) {
            list($type, $data) = $tr;
            switch ($type) {
                case 'backup' :
                    if (!@copy($data[0], $data[0] . '.bak')) {
                        PEAR2_Log::log(1, 'Could not copy ' . $data[0] . ' to ' . $data[0] .
                            '.bak ' . $php_errormsg);
                        return false;
                    }
                    PEAR2_Log::log(3, "+ backup $data[0] to $data[0].bak");
                    break;
                case 'chmod' :
                    if (!@chmod($data[1], $data[0])) {
                        PEAR2_Log::log(1, 'Could not chmod ' . $data[1] . ' to ' .
                            decoct($data[0]) . ' ' . $php_errormsg);
                        return false;
                    }
                    $octmode = decoct($data[0]);
                    PEAR2_Log::log(3, "+ chmod $octmode $data[1]");
                    break;
                case 'delete' :
                    if (file_exists($data[0])) {
                        if (!@unlink($data[0])) {
                            PEAR2_Log::log(1, 'Could not delete ' . $data[0] . ' ' .
                                $php_errormsg);
                            return false;
                        }
                        PEAR2_Log::log(3, "+ rm $data[0]");
                    }
                    break;
                case 'mkdir' :
                    break;
                case 'removebackup' :
                    if (file_exists($data[0] . '.bak') && is_writable($data[0] . '.bak')) {
                        unlink($data[0] . '.bak');
                        PEAR2_Log::log(3, "+ rm backup of $data[0] ($data[0].bak)");
                    }
                    break;
                default :
                    $callback = self::$_registeredTransactions[$type];
                    $callback->commit($data, $errors);
            }
        }
        PEAR2_Log::log(2, "successfully committed $n file operations");
        $this->fileOperations = array();
        return true;
    }

    function registerTransaction($name, PEAR2_IFileTransaction $callback)
    {
        if (in_array($name, array_keys(self::$_registeredTransactions), true)) {
            throw new PEAR2_FileTransactions_Exception('transaction type ' . $name .
                ' is already registered');
        }
        self::$_registeredTransactions[$name] = $callback;
    }

    function rollback()
    {
        $n = count($this->fileOperations);
        PEAR2_Log::log(2, "rolling back $n file operations");
        foreach ($this->fileOperations as $tr) {
            list($type, $data) = $tr;
            switch ($type) {
                case 'backup' :
                    if (file_exists($data[0] . '.bak')) {
                        @unlink($data[0]);
                        @copy($data[0] . '.bak', $data[0]);
                        PEAR2_Log::log(3, "+ restore $data[0] from $data[0].bak");
                    }
                    break;
                case 'chmod' :
                case 'delete' :
                case 'removebackup' :
                    break;
                case 'mkdir' :
                    @rmdir($data[0]);
                    PEAR2_Log::log(3, "+ rmdir $data[0]");
                    break;
                default :
                    $callback = self::$_registeredTransactions[$type];
                    $callback->rollback($data, $errors);
            }
        }
        foreach ($this->_registeredTransactionas as $callback) {
            $callback->cleanup();
        }
        $this->fileOperations = array();
    }
}