<?php
interface PEAR2_IFileTransaction
{
    public function check($data, &$errors);
    public function commit($data, &$errors);
    public function rollback($data, &$errors);
    public function cleanup();
}