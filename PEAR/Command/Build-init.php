<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'build' => array(
            'summary' => 'Build an Extension From C Source',
            'function' => 'doBuild',
            'shortcut' => 'b',
            'options' => array(),
            'doc' => '[package.xml]
Builds one or more extensions contained in a package.'
            ),
        );
?>