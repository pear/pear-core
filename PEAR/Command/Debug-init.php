<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'debugrpc' => array(
            'summary' => 'displays output from a call to an XML-RPC function on the default server',
            'shortcut' => 'dr',
            'function' => 'doRPC',
            'options' => array(),
            'doc' => '<method> [params...]
params are interpreted as php values and evaled - be careful',
            )

        );
?>