<?php
// quick-load, small memory footprint command configuration
$implements = array(
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
?>