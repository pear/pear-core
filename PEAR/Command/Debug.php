<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Stig Bakken <ssb@php.net>                                    |
// +----------------------------------------------------------------------+
//
// $Id$

require_once "PEAR/Command/Common.php";
require_once "PEAR/Remote.php";
require_once "PEAR/Config.php";

/**
 * PEAR commands for managing configuration data.
 *
 */
class PEAR_Command_Debug extends PEAR_Command_Common
{
    // {{{ properties

    var $commands = array(
        'debugrpc' => array(
            'summary' => 'displays output from a call to an XML-RPC function on the default server',
            'shortcut' => 'dr',
            'function' => 'doRPC',
            'options' => array(),
            'doc' => '<method> [params...]
params are interpreted as php values and evaled - be careful',
            )

        );

    // }}}


    /**
     * Execute the 'login' command.
     *
     * @param string $command command name
     *
     * @param array $options option_name => value
     *
     * @param array $params list of additional parameters
     *
     * @return bool TRUE on success, FALSE for unknown commands, or
     * a PEAR error on failure
     *
     * @access public
     */
    function doRPC($command, $options, $params)
    {
        if (!count($params)) {
            return true;
        }
        $remote = &$this->config->getRemote();
        $method = array_shift($params);
        if (count($params)) {
            $params = '$params=array(' . implode(', ', $params) . ');';
            $this->ui->outputData('Evaling "' . $params . '"');
            eval($params);
        }
        $remote->clearCache($method, $params);
        array_unshift($params, $method);
        var_dump(call_user_func_array(array(&$remote, 'call'), $params));
        return true;
    }

    // }}}
}

?>