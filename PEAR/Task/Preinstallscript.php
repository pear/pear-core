<?php
/**
 * Implements the preinstallscript file task
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Task_Preinstallscript extends PEAR_Task_Common
{
    var $type = 'multiple';
    var $_class;
    var $_obj;

    /**
     * Validate the raw xml at parsing-time.
     *
     * This also attempts to validate the script to make sure it meets the criteria
     * for a pre-install script
     * @param PEAR_PackageFile_v2
     * @param array
     * @param PEAR_Config
     * @param array the entire parsed <file> tag
     * @static
     */
    function validateXml($pkg, $xml, &$config, $fileXml)
    {
        if ($fileXml['attribs']['role'] != 'php') {
            return array(PEAR_TASK_ERROR_INVALID, 'Pre-install script "' .
            $fileXml['attribs']['name'] . '" must be role="php"');
        }
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $file = $pkg->getFileContents($fileXml['attribs']['name']);
        if (PEAR::isError($file)) {
            return array(PEAR_TASK_ERROR_INVALID, 'Pre-install script "' .
                $fileXml['attribs']['name'] . '"is not valid: ' .
                $file->getMessage());
        } else {
            $analysis = $pkg->analyzeSourceCode($file, true);
            if (PEAR::isError($analysis)) {
                return array(PEAR_TASK_ERROR_INVALID, 'Analysis of pre-install script "' .
                    $fileXml['attribs']['name'] . '"failed');
            }
            if (count($analysis['declared_classes']) != 1) {
                return array(PEAR_TASK_ERROR_INVALID, 'Pre-install script "' .
                    $fileXml['attribs']['name'] . '"must declare exactly 1 class');
            }
            $class = $analysis['declared_classes'][0];
            if ($class != str_replace(array('/', '.php'), array('_', ''),
                  $fileXml['attribs']['name']) . '_postinstall') {
                return array(PEAR_TASK_ERROR_INVALID, 'Pre-install script "' .
                    $fileXml['attribs']['name'] . '" class "' . $class . '" must be named "' .
                    str_replace(array('/', '.php'), array('_', ''),
                    $fileXml['attribs']['name']) . '_preinstall"');
            }
            if (!isset($analysis['declared_methods'][$class])) {
                return array(PEAR_TASK_ERROR_INVALID, 'Pre-install script "' .
                    $fileXml['attribs']['name'] . '" must declare methods init() and run()');
            }
            $methods = array('init' => 0, 'run' => 1);
            foreach ($analysis['declared_methods'][$class] as $method) {
                if (isset($methods[$method])) {
                    unset($methods[$method]);
                }
            }
            if (count($methods)) {
                return array(PEAR_TASK_ERROR_INVALID, 'Pre-install script "' .
                    $fileXml['attribs']['name'] . '"must declare methods init() and run()');
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
        $this->_class = str_replace ('.php', '', $this->_class) . '_preinstall';
    }

    /**
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param string file contents
     * @param string the eventual final file location (informational only)
     * @return bool|PEAR_Error false to skip this file, PEAR_Error to fail
     *         (use $this->throwError)
     */
    function startSession($pkg, $contents, $dest)
    {
        $orig = $contents;
        $contents = str_replace(array('<?php', '?>'), array('', ''), $contents);
        $this->installer->log(0, 'Including external pre-installation script "' .
            $dest . '" - any fatal errors are in this script');
        eval($contents);
        if (class_exists($this->_class)) {
            $this->installer->log(0, 'Inclusion succeeded');
        } else {
            return $this->throwError('init of pre-install script class "' . $this->_class
                . '" failed');
        }
        $this->_obj = new $this->_class;
        $this->installer->log(1, 'running pre-install script "' . $this->_class . '->init()"');
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $res = $this->_obj->init($this->config, $this->installer->ui);
        PEAR::popErrorHandling();
        if ($res) {
            $this->installer->log(0, 'init succeeded');
        } else {
            return $this->throwError('init of pre-install script "' . $this->_class .
                '->init()" failed');
        }
        return $orig; // unchanged
    }

    /**
     * Run the pre-installation script
     * @param array an array of tasks
     * @access protected
     * @static
     */
    function run($tasks)
    {
        foreach ($tasks as $i => $task) {
            $tasks[$i]->_obj->run();
        }
    }
}
?>