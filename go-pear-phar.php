<?php
while (@ob_end_flush());

require_once 'PEAR/Start/CLI.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);
$a = new PEAR_Start_CLI;
$a->run();
?>