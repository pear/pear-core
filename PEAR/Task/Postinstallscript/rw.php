<?php
/**
 * <tasks:postinstallscript> - read/write version
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 1.4.0a10
 */
/**
 * Base class
 */
require_once 'PEAR/Task/Postinstallscript.php';
/**
 * Abstracts the postinstallscript file task xml.
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 1.4.0a10
 */
class PEAR_Task_Postinstallscript_rw extends PEAR_Task_Postinstallscript
{
    function PEAR_Task_Postinstallscript_rw(&$pkg, &$config, &$logger, $fileXml)
    {
        parent::PEAR_Task_Common($config, $logger, PEAR_TASK_PACKAGE);
        $this->_contents = $fileXml;
        $this->_pkg = &$pkg;
        $this->_params = array();
    }

    function validate()
    {
        return $this->validateXml($this->_pkg, $this->_params, $this->config, $this->_contents);
    }

    function addParamGroup($id, $params)
    {
        if (isset($params[0]) && !isset($params[1])) {
            $params = $params[0];
        }
        $this->_params[] =
            array(
                'id' => $id,
                'param' => $params,
            );
    }

    function addConditionTypeGroup($id, $oldgroup, $param, $value, $conditiontype = '=')
    {
        if (isset($params[0]) && !isset($params[1])) {
            $params = $params[0];
        }
        $this->_params[] =
            array(
                'id' => $id,
                'name' => $oldgroup . '::' . $param,
                'conditiontype' => $conditiontype,
                'value' => $value,
                'param' => $params,
            );
    }

    function getXml()
    {
        return $this->_params;
    }

    /**
     * Use to set up a param tag for use in creating a paramgroup
     * @static
     */
    function getParam($name, $prompt, $type = 'string', $default = null)
    {
        if ($default !== null) {
            return 
            array(
                'name' => $name,
                'prompt' => $prompt,
                'type' => $type,
                'default' => $default
            );
        }
        return
            array(
                'name' => $name,
                'prompt' => $prompt,
                'type' => $type,
            );
    }
}
?>