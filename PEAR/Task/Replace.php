<?php
/**
 * Implements the replace file task
 * @package PEAR
 * @author Greg Beaver <cellog@php.net>
 */
class PEAR_Task_Replace extends PEAR_Task_Common
{
    var $type = 'simple';
    var $_replacements;

    /**
     * Validate the raw xml at parsing-time.
     * @param array raw, parsed xml
     */
    function validateXml($xml, &$config)
    {
        if (!isset($xml['attribs'])) {
            return array(PEAR_TASK_ERROR_NOATTRIBS);
        }
        if (!isset($xml['attribs']['type'])) {
            return array(PEAR_TASK_ERROR_MISSING_ATTRIB, 'type');
        }
        if (!isset($xml['attribs']['to'])) {
            return array(PEAR_TASK_ERROR_MISSING_ATTRIB, 'to');
        }
        if (!isset($xml['attribs']['from'])) {
            return array(PEAR_TASK_ERROR_MISSING_ATTRIB, 'from');
        }
        if ($xml['attribs']['type'] == 'php-const') {
            if (preg_match('/^[a-z0-9_]+$/i', $xml['attribs']['to'])) {
                return true;
            } else {
                return array(PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE, $xml['attribs']['type'],
                    array('/^[a-z0-9_]+$/i'));
            }
        } elseif ($xml['attribs']['type'] == 'pear-config') {
            if (!in_array($xml['attribs']['to'], $config->getKeys())) {
                return array(PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE, 'to', $xml['attribs']['to'],
                    $config->getKeys());
            }
        } elseif ($xml['attribs']['type'] == 'package-info') {
            if (in_array($xml['attribs']['to'],
                array('name', 'summary', 'channel', 'notes', 'extends', 'description',
                    'release_notes', 'license', 'release-license', 'license-uri',
                    'version', 'api-version', 'state', 'api-state', 'release_date',
                    'date', 'time'))) {
                return true;
            } else {
                return array(PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE, 'to', $xml['attribs']['to'],
                    array('name', 'summary', 'channel', 'notes', 'extends', 'description',
                    'release_notes', 'license', 'release-license', 'license-uri',
                    'version', 'api-version', 'state', 'api-state', 'release_date',
                    'date', 'time'));
            }
        } else {
            return array(PEAR_TASK_ERROR_WRONG_ATTRIB_VALUE, 'type', $xml['attribs']['type'],
                array('php-const', 'pear-config', 'package-info'));
        }
    }

    /**
     * Initialize a task instance with the parameters
     * @param array raw, parsed xml
     */
    function init($xml)
    {
        $this->_replacements = isset($xml['attribs']) ? array($xml) : $xml;
    }

    /**
     * Do a package.xml 1.0 replacement, with additional package-info fields available
     *
     * See validateXml() source for the complete list of allowed fields
     * @param PEAR_PackageFile_v1|PEAR_PackageFile_v2
     * @param string location this file will temporarily install to
     * @param string final location this file will go to
     * @return false|PEAR_Error false to skip this file, PEAR_Error to fail
     *         (use $this->throwError)
     */
    function startSession($pkg, $temp, $dest)
    {
        $subst_from = $subst_to = array();
        foreach ($this->_replacements as $a) {
            $to = '';
            if ($a['type'] == 'php-const') {
                if (preg_match('/^[a-z0-9_]+$/i', $a['to'])) {
                    eval("\$to = $a[to];");
                } else {
                    $this->installer->log(0, "invalid php-const replacement: $a[to]");
                    return false;
                }
            } elseif ($a['type'] == 'pear-config') {
                if ($a['to'] == 'master_server') {
                    $chan = $this->registry->getChannel($pkg->getChannel());
                    if ($chan) {
                        $to = $chan->getServer();
                    } else {
                        $this->installer->log(0, "invalid pear-config replacement: $a[to]");
                        return false;
                    }
                } else {
                    $to = $this->config->get($a['to'], null, $channel);
                }
                if (is_null($to)) {
                    $this->installer->log(0, "invalid pear-config replacement: $a[to]");
                    return false;
                }
            } elseif ($a['type'] == 'package-info') {
                if ($t = $this->pkginfo->packageInfo($a['to'])) {
                    $to = $t;
                } else {
                    $this->installer->log(0, "invalid package-info replacement: $a[to]");
                    return false;
                }
            }
            if (!is_null($to)) {
                $subst_from[] = $a['from'];
                $subst_to[] = $to;
            }
        }
        $this->installer->log(3, "doing " . sizeof($subst_from) .
            " substitution(s) for $final_dest_file");
        if (sizeof($subst_from)) {
            $contents = str_replace($subst_from, $subst_to, $contents);
        }
        $wp = @fopen($dest_file, "wb");
        if (!is_resource($wp)) {
            return $this->throwError("failed to create $dest_file: $php_errormsg",
                                     PEAR_INSTALLER_FAILED);
        }
        if (!fwrite($wp, $contents)) {
            return $this->throwError("failed writing to $dest_file: $php_errormsg",
                                     PEAR_INSTALLER_FAILED);
        }
        fclose($wp);
    }
}
?>