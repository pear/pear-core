<?php
require_once 'PEAR/Task/Common.php';
/**
 * Implements the postinstallscript file task
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Task_Postinstallscript extends PEAR_Task_Common
{
    var $type = 'postinstall-multiple';
    var $_class;
    var $_obj;
    var $_pkg;
    var $_contents;

    /**
     * Validate the raw xml at parsing-time.
     *
     * This also attempts to validate the script to make sure it meets the criteria
     * for a post-install script
     * @param PEAR_PackageFile_v2
     * @param array The XML contents of the <postinstallscript> tag
     * @param PEAR_Config
     * @param array the entire parsed <file> tag
     * @static
     */
    function validateXml($pkg, $xml, &$config, $fileXml)
    {
        if ($fileXml['role'] != 'php') {
            return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
            $fileXml['name'] . '" must be role="php"');
        }
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $file = $pkg->getFileContents($fileXml['name']);
        if (PEAR::isError($file)) {
            PEAR::popErrorHandling();
            return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                $fileXml['name'] . '"is not valid: ' .
                $file->getMessage());
        } else {
            $analysis = $pkg->analyzeSourceCode($file, true);
            if (PEAR::isError($analysis)) {
                PEAR::popErrorHandling();
                return array(PEAR_TASK_ERROR_INVALID, 'Analysis of post-install script "' .
                    $fileXml['name'] . '"failed');
            }
            if (count($analysis['declared_classes']) != 1) {
                PEAR::popErrorHandling();
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                    $fileXml['name'] . '"must declare exactly 1 class');
            }
            $class = $analysis['declared_classes'][0];
            if ($class != str_replace(array('/', '.php'), array('_', ''),
                  $fileXml['name']) . '_postinstall') {
                PEAR::popErrorHandling();
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                    $fileXml['name'] . '" class "' . $class . '" must be named "' .
                    str_replace(array('/', '.php'), array('_', ''),
                    $fileXml['name']) . '_postinstall"');
            }
            if (!isset($analysis['declared_methods'][$class])) {
                PEAR::popErrorHandling();
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                    $fileXml['name'] . '" must declare methods init() and run()');
            }
            $methods = array('init' => 0, 'run' => 1);
            foreach ($analysis['declared_methods'][$class] as $method) {
                if (isset($methods[$method])) {
                    unset($methods[$method]);
                }
            }
            if (count($methods)) {
                PEAR::popErrorHandling();
                return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                    $fileXml['name'] . '" must declare methods init() and run()');
            }
        }
        PEAR::popErrorHandling();
        $definedparams = array();
        if (isset($xml['paramgroup'])) {
            $params = $xml['paramgroup'];
            if (!is_array($params) || !isset($params[0])) {
                $params = array($params);
            }
            foreach ($params as $param) {
                if (!isset($param['id'])) {
                    return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                        $fileXml['name'] . '" <paramgroup> must have ' .
                        'an <id> tag');
                }
                if (isset($param['name'])) {
                    if (!in_array($param['name'], $definedparams)) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" <paramgroup> id "' . $param['id'] .
                            '" parameter "' . $param['name'] . '" has not been previously defined');
                    }
                    if (!isset($param['conditiontype'])) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" <paramgroup> id "' . $param['id'] .
                            '" must have a <conditiontype> tag containing either "=", ' .
                            '"!=", or "preg_match"');
                    }
                    if (!in_array($param['conditiontype'], array('=', '!=', 'preg_match'))) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" <paramgroup> id "' . $param['id'] .
                            '" must have a <conditiontype> tag containing either "=", ' .
                            '"!=", or "preg_match"');
                    }
                    if (!isset($param['value'])) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" <paramgroup> id "' . $param['id'] .
                            '" must have a <value> tag containing expected parameter value');
                    }
                }
                if (!isset($param['param'])) {
                    return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                        $fileXml['name'] . '" <paramgroup> id "' . $param['id'] .
                        '" must contain one or more <param> tags');
                }
                $subparams = $param['param'];
                if (!is_array($subparams) || !isset($subparams[0])) {
                    $subparams = array($subparams);
                }
                foreach ($subparams as $subparam) {
                    if (!isset($subparam['name'])) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" parameter for ' .
                            '<paramgroup> id "' . $param['id'] . '" must have ' .
                            'a <name> tag');
                    }
                    if (!preg_match('/[a-zA-Z0-9]+/', $subparam['name'])) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" parameter "' .
                            $subparam['name'] . '" for <paramgroup> id "' . $param['id'] .
                            '" is not a valid name.  Must contain only alphanumeric characters');
                    }
                    if (!isset($subparam['prompt'])) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" parameter "' .
                            $subparam['name'] . '" for <paramgroup> id "' . $param['id'] .
                            '" must have a <prompt> tag');
                    }
                    if (!isset($subparam['type'])) {
                        return array(PEAR_TASK_ERROR_INVALID, 'Post-install script "' .
                            $fileXml['name'] . '" parameter "' .
                            $subparam['name'] . '" for <paramgroup> id "' . $param['id'] .
                            '" must have a <type> tag');
                    }
                    $definedparams[] = $param['id'] . '::' . $subparam['name'];
                }
            }
        }
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
        $this->_class = str_replace ('.php', '', $this->_class) . '_postinstall';
        $this->_params = $xml;
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
        $this->installer->log(0, 'Including external post-installation script "' .
            $dest . '" - any fatal errors are in this script');
        eval($contents);
        if (class_exists($this->_class)) {
            $this->installer->log(0, 'Inclusion succeeded');
        } else {
            return $this->throwError('init of post-install script class "' . $this->_class
                . '" failed');
        }
        $this->_obj = new $this->_class;
        $this->installer->log(1, 'running post-install script "' . $this->_class . '->init()"');
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $res = $this->_obj->init($this->config, $this->installer->ui, $pkg);
        PEAR::popErrorHandling();
        if ($res) {
            $this->installer->log(0, 'init succeeded');
        } else {
            return $this->throwError('init of post-install script "' . $this->_class .
                '->init()" failed');
        }
        $this->_contents = $dest;
        $this->_pkg = $pkg;
        return $orig; // unchanged
    }

    /**
     * Run the post-installation script
     * @param array an array of tasks
     * @param string install or upgrade
     * @access protected
     * @static
     */
    function run($tasks, $installphase)
    {
        foreach ($tasks as $i => $task) {
            $tasks[$i]->installer->ui->runInstallScript($tasks[$i]->_params, $tasks[$i]->_obj,
                $installphase, $tasks[$i]->_pkg, $tasks[$i]->_contents);
        }
    }
}
?>