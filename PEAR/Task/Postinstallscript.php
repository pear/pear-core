<?php
/**
 * Implements the postinstallscript file task
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Task_Postinstallscript extends PEAR_Task_Common
{
    var $type = 'multiple';
    var $class;

    /**
     * Validate the raw xml at parsing-time.
     *
     * This also attempts to validate the script to make sure it meets the criteria
     * for a post-install script
     * @param PEAR_PackageFile_v2
     * @param array
     * @param PEAR_Config
     * @param array the entire parsed <file> tag
     * @static
     */
    function validateXml($pkg, $xml, &$config, $fileXml)
    {
        if ($fileXml['attribs']['role'] != 'php') {
            return array(PEAR_TASK_ERROR_INVALID, 'Post-install scripts must be role="php"');
        }
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $file = $pkg->getFileContents($fileXml['attribs']['name']);
        if (PEAR::isError($file)) {
            return array(PEAR_TASK_ERROR_INVALID, 'Post-install script is not valid: ' .
                $file->getMessage());
        } else {
            if (count($file['declared_classes']) != 1) {
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script must declare exactly 1'
                    . ' class');
            }
            $class = $file['declared_classes'][0];
            if (!isset($file['declared_methods'][$class])) {
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script must declare methods' .
                    ' init() and run()');
            }
            $methods = array('init' => 0, 'run' => 1);
            foreach ($file['declared_methods'][$class] as $method) {
                if (isset($methods[$method])) {
                    unset($methods[$method]);
                }
            }
            if (count($methods)) {
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script must declare methods' .
                    ' init() and run()');
            }
        }
        PEAR::popErrorHandling();
        return true;
    }

    /**
     * Initialize a task instance with the parameters
     * @param array raw, parsed xml
     * @param array attributes from the <file> tag containing this task
     */
    function init($xml, $fileattribs)
    {
        $this->_class = str_replace('/', '_', $fileattribs['name']);
        $this->_filename = $fileattribs['name'];
        $this->_class = dirname($this->_class);
        str_replace ('.php', '', $this->_class);
    }

    /**
     * Do a package.xml 1.0 replacement, with additional package-info fields available
     *
     * See validateXml() source for the complete list of allowed fields
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param string file contents
     * @param string the eventual final file location (informational only)
     * @return bool|PEAR_Error false to skip this file, PEAR_Error to fail
     *         (use $this->throwError)
     */
    function startSession($pkg, $contents, $dest)
    {
        $this->installer->log(0, 'Including external post-installation script "' .
            $dest . '" - any fatal errors are in this script');
        eval($contents);
        $this->installer->log(0, 'Inclusion succeeded');
        $this->installer->log(1, 'running post-install script "' . $this->_class . '::init()"');
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $res = call_user_func_array(array($this->_class, 'init'),
            array($this->config, $this->installer->ui));
        PEAR::popErrorHandling();
        if ($res) {
            $this->installer->log(0, 'init succeeded');
        } else {
            return PEAR::raiseError('init of post-install script "' . $this->_class .
                '::init()" failed');
        }
        return $contents; // unchanged
    }

    /**
     * Run the post-installation script
     * @param array an array of tasks
     * @access protected
     * @static
     */
    function run($tasks)
    {
        call_user_func(array($this->_class, 'run'));
    }
}
?>