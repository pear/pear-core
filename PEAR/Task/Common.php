<?php
define('PEAR_TASK_ERROR_NOATTRIBS', 1);
define('PEAR_TASK_ERROR_MISSING_ATTRIB', 2);
define('PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE', 3);
/**
 * @abstract
 */
class PEAR_Task_Common
{
    /**
     * Valid types for this version are 'simple' and 'multiple'
     *
     * - simple tasks operate on the contents of a file and write out changes to disk
     * - multiple tasks operate on the contents of many files and write out the
     *   changes directly to disk
     *
     * Child task classes must override this property.
     * @access protected
     */
    var $type = 'simple';
    /**
     * @access protected
     */
    var $config;
    /**
     * @access protected
     */
    var $registry;
    /**
     * @access protected
     */
    var $installer;
    /**
     * @param PEAR_Config
     * @param PEAR_Installer
     */
    function PEAR_Task_Common(&$config, &$installer)
    {
        $this->config = &$config;
        $this->registry = &$config->getRegistry();
        $this->installer = &$installer;
        if ($this->type == 'multiple') {
            $GLOBALS['_PEAR_TASK_INSTANCES'][get_class($this)][] = &$this;
        }
    }

    /**
     * Validate the basic contents of a task tag.
     * @param array
     * @param PEAR_Config
     * @return true|array On error, return an array in format:
     *    array(PEAR_TASK_ERROR_???[, param1][, param2][, ...])
     *
     *    For PEAR_TASK_ERROR_MISSING_ATTRIB, pass the attribute name in
     *    For PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE, pass the attribute name and an array
     *    of legal values in
     * @static
     */
    function validXml($xml, &$config)
    {
    }

    /**
     * Initialize a task instance with the parameters
     * @param array raw, parsed xml
     */
    function init($xml)
    {
    }

    /**
     * Begin a task processing session.  All multiple tasks will be processed after each file
     * has been successfully installed, all simple tasks should perform their task here and
     * return any errors using the custom throwError() method to allow forward compatibility
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param string location this file will temporarily install to
     * @param string final location this file will go to
     * @return false|PEAR_Error false to skip this file, PEAR_Error to fail
     *         (use $this->throwError)
     */
    function startSession($pkg, $temp, $dest)
    {
    }

    /**
     * This method is used to process each of the tasks for a particular multiple class
     * type.  Simple tasks need not implement this method.
     * @param array an array of tasks
     * @access protected
     * @static
     * @abstract
     */
    function run($tasks)
    {
    }

    /**
     * @static
     */
    function hasTasks()
    {
        return isset($GLOBALS['_PEAR_TASK_INSTANCES']);
    }

    /**
     * @static
     * @final
     */
    function runTasks()
    {
        foreach ($GLOBALS['_PEAR_TASK_INSTANCES'] as $class => $tasks) {
            $err = call_user_func(array($class, 'run'), $tasks);
            if ($err) {
                return PEAR_Task_Common::throwError($err);
            }
        }
        unset($GLOBALS['_PEAR_TASK_INSTANCES']);
    }

    function throwError($msg, $code = -1)
    {
        include_once 'PEAR.php';
        return PEAR::raiseError($msg, $code);
    }
}
?>