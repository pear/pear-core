<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'run-tests' => array(
            'summary' => 'Run Regression Tests',
            'function' => 'doRunTests',
            'shortcut' => 'rt',
            'options' => array(
                'recur' => array(
                    'shortopt' => 'r',
                    'doc' => 'Run tests in child directories, recursively.  4 dirs deep maximum',
                ),
                'ini' => array(
                    'shortopt' => 'i',
                    'doc' => 'actual string of settings to pass to php in format " -d setting=blah"',
                    'arg' => 'SETTINGS'
                ),
                'realtimelog' => array(
                    'shortopt' => 'l',
                    'doc' => 'Log test runs/results as they are run',
                ),
                'quiet' => array(
                    'shortopt' => 'q',
                    'doc' => 'Only display detail for failed tests',
                ),
                'simple' => array(
                    'shortopt' => 's',
                    'doc' => 'Display simple output for all tests',
                ),
                'package' => array(
                    'shortopt' => 'p',
                    'doc' => 'Treat parameters as installed packages from which to run tests',
                ),
            ),
            'doc' => '[testfile|dir ...]
Run regression tests with PHP\'s regression testing script (run-tests.php).',
            ),
        );
?>