<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'download-all' => array(
            'summary' => 'Downloads each available package from the default channel',
            'function' => 'doDownloadAll',
            'shortcut' => 'da',
            'options' => array(
                'channel' =>
                    array(
                    'shortopt' => 'c',
                    'doc' => 'specify a channel other than the default channel',
                    'arg' => 'CHAN',
                    ),
                ),
            'doc' => '
Requests a list of available packages from the default channel ({config default_channel})
and downloads them to current working directory.  Note: only
packages within preferred_state ({config preferred_state}) will be downloaded'
            ),
        );
?>