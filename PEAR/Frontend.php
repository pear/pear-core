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
// | Author: Greg Beaver <cellog@php.net>                                 |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * Singleton-based frontend for PEAR user input/output
 * @author Greg Beaver
 * @package PEAR
 */
class PEAR_Frontend
{
    /**
     * Retrieve the frontend object
     * @return PEAR_Frontend_CLI|PEAR_Frontend_Web|PEAR_Frontend_Gtk
     * @static
     */
    function &singleton($type = null)
    {
        if ($type === null) {
            if (!isset($GLOBALS['_PEAR_FRONTEND_SINGLETON'])) {
                $a = false;
                return $a;
            }
            return $GLOBALS['_PEAR_FRONTEND_SINGLETON'];
        } else {
            $class = 'PEAR_Frontend_' . $type;
            if (class_exists($class)) {
                $GLOBALS['_PEAR_FRONTEND_SINGLETON'] = &new $class;
            } else {
                die('UNRECOVERABLE ERROR: invalid frontend type "' . $type . '"');
            }
        }
    }

    /**
     * Use this to initialize a custom frontend object as the singleton
     * @param PEAR_Frontend_* object
     */
    function setSingleton(&$obj)
    {
        $GLOBALS['_PEAR_FRONTEND_SINGLETON'] = &$obj;
    }
}
?>