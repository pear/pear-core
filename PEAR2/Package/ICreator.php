<?php
interface PEAR2_Package_ICreator
{
    /**
     * save a file inside this package
     * @param string relative path within the package
     * @param string|resource file contents or open file handle
     */
    function addFile($path, $fileOrStream);
    /**
     * Initialize the package creator
     */
    function init();
    /**
     * Create an internal directory, creating parent directories as needed
     * @param string $dir
     */
    function mkdir($dir);
    /**
     * Finish saving the package
     */
    function close();
}