<?php
require_once 'PEAR/Task/Common.php';
/**
 * Implements the unixlinendings file task
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Task_Unixeol extends PEAR_Task_Common
{
    var $type = 'simple';
    var $phase = PEAR_TASK_PACKAGE;
    var $_replacements;

    /**
     * Validate the raw xml at parsing-time.
     * @param PEAR_PackageFile_v2
     * @param array raw, parsed xml
     * @param PEAR_Config
     * @static
     */
    function validateXml($pkg, $xml, &$config, $fileXml)
    {
        if ($xml != '') {
            return array(PEAR_TASK_ERROR_INVALID, 'no attributes allowed');
        }
    }

    /**
     * Initialize a task instance with the parameters
     * @param array raw, parsed xml
     * @param unused
     */
    function init($xml, $attribs)
    {
    }

    /**
     * Replace all line endings with line endings customized for the current OS
     *
     * See validateXml() source for the complete list of allowed fields
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param string file contents
     * @param string the eventual final file location (informational only)
     * @return string|false|PEAR_Error false to skip this file, PEAR_Error to fail
     *         (use $this->throwError), otherwise return the new contents
     */
    function startSession($pkg, $contents, $dest)
    {
        $this->logger->log(3, "replacing all line endings with \\n in $dest");
        return preg_replace("/\r\n|\n\r|\r|\n/", "\n", $contents);
    }
}
?>