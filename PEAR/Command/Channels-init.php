<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'list-channels' => array(
            'summary' => 'List Available Channels',
            'function' => 'doList',
            'shortcut' => 'lc',
            'options' => array(),
            'doc' => '
List all available channels for installation.
',
            ),
        'update-channels' => array(
            'summary' => 'Update the Channel List',
            'function' => 'doUpdateAll',
            'shortcut' => 'uc',
            'options' => array(),
            'doc' => '
List all installed packages in all channels.
'
            ),
        'channel-delete' => array(
            'summary' => 'Remove a Channel From the List',
            'function' => 'doDelete',
            'shortcut' => 'cde',
            'options' => array(),
            'doc' => '<channel name>
Delete a channel from the registry.  You may not
remove any channel that has installed packages.
'
            ),
        'channel-add' => array(
            'summary' => 'Add a Channel',
            'function' => 'doAdd',
            'shortcut' => 'ca',
            'options' => array(),
            'doc' => '<channel.xml>
Add a private channel to the channel list.  Note that all
public channels should be synced using "update-channels".
Parameter may be either a local file or remote URL to a
channel.xml.
'
            ),
        'channel-update' => array(
            'summary' => 'Update an Existing Channel',
            'function' => 'doUpdate',
            'shortcut' => 'cu',
            'options' => array(
                'force' => array(
                    'shortopt' => 'f',
                    'doc' => 'will force download of new channel.xml if an existing channel name is used',
                    ),
                'channel' => array(
                    'shortopt' => 'c',
                    'arg' => 'CHANNEL',
                    'doc' => 'will force download of new channel.xml if an existing channel name is used',
                    ),
),
            'doc' => '[<channel.xml>|<channel name>]
Update a channel in the channel list directly.  Note that all
public channels can be synced using "update-channels".
Parameter may be a local or remote channel.xml, or the name of
an existing channel.
'
            ),
        'channel-info' => array(
            'summary' => 'Retrieve Information on a Channel',
            'function' => 'doInfo',
            'shortcut' => 'ci',
            'options' => array(),
            'doc' => '<package>
List the files in an installed package.
'
            ),
        'channel-alias' => array(
            'summary' => 'Specify an alias to a channel name',
            'function' => 'doAlias',
            'shortcut' => 'cha',
            'options' => array(),
            'doc' => '<channel> <alias>
Specify a specific alias to use for a channel name.
The alias may not be an existing channel name or
alias.
'
            ),
        'channel-discover' => array(
            'summary' => 'Initialize a Channel from its server',
            'function' => 'doDiscover',
            'shortcut' => 'di',
            'options' => array(),
            'doc' => '<package>
List the files in an installed package.
'
            ),
        );
?>