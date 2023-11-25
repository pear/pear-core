<?php
/**
 * PEAR, the PHP Extension and Application Repository
 *
 * PHP versions 4 and 5
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://pear.php.net/package/PEAR
 */

require_once "PEAR/Command/Common.php";

/**
 * PEAR commands for managing configuration data.
 *
 */
class PEAR_Command_Grunk extends PEAR_Command_Common
{
    // {{{ properties

    var $commands = array(
        'login' => array(
            'summary' => 'Connects and authenticates to remote server',
            'shortcut' => 'li',
            'function' => 'doLogin',
            'options' => array(),
            'doc' => '
Log in to the remote server.  To use remote functions in the installer
that require any kind of privileges, you need to log in first.  The
username and password you enter here will be stored in your per-user
PEAR configuration (~/.pearrc on Unix-like systems).  After logging
in, your username and password will be sent along in subsequent
operations on the remote server.',
            ),
        'logout' => array(
            'summary' => 'Logs out from the remote server',
            'shortcut' => 'lo',
            'function' => 'doLogout',
            'options' => array(),
            'doc' => '
Logs out from the remote server.  This command does not actually
connect to the remote server, it only deletes the stored username and
password from your user configuration.',
            )

        );

    // }}}

    // {{{ doLogin()

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
    function doLogin($command, $options, $params)
    {
    }

    // }}}
    // {{{ doLogout()

    /**
     * Execute the 'logout' command.
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
    function doLogout($command, $options, $params)
    {
    }

    // }}}
}

?>
