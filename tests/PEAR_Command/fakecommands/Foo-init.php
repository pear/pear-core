<?php
$implements = array(
        'smong' => array(
            'summary' => 'Connects and authenticates to remote server',
            'shortcut' => 'sm',
            'function' => 'doLogin',
            'options' => array(
                'channel' => array(
                    'shortopt' => 'c',
                    'doc' => 'list installed packages from this channel',
                    'arg' => 'CHAN',
                    ),
            ),
            'doc' => '
Log in to the remote server.  To use remote functions in the installer
that require any kind of privileges, you need to log in first.  The
username and password you enter here will be stored in your per-user
PEAR configuration (~/.pearrc on Unix-like systems).  After logging
in, your username and password will be sent along in subsequent
operations on the remote server.',
            ),
        'yertl' => array(
            'summary' => 'Logs out from the remote server',
            'shortcut' => 'ye',
            'function' => 'doLogout',
            'options' => array(
                'channel' => array(
                    'shortopt' => 'c',
                    'doc' => 'list installed packages from this channel',
                    ),
                ),
            'doc' => '
Logs out from the remote server.  This command does not actually
connect to the remote server, it only deletes the stored username and
password from your user configuration.',
            )

        );
?>