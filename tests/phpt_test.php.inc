<?php
@include_once 'Text/Diff.php';
@include_once 'Text/Diff/Renderer.php';
@include_once 'Text/Diff/Renderer/unified.php';
class PEAR_PHPTest
{
    var $_diffonly;
    function PEAR_PHPTest($diffonly = false)
    {
        $this->_diffonly = $diffonly;
    }

    function assertEquals($control, $test, $message)
    {
        if (var_export($control, true) != var_export($test, true)) {
            $this->_failTest(debug_backtrace(), $message);
            if (class_exists('Text_Diff')) {
                echo "Diff of expecting/received:\n";
                $diff = &new Text_Diff(
                    explode("\n", var_export($control, true)),
                    explode("\n", var_export($test, true)));

                // Output the diff in unified format.
                $renderer = &new Text_Diff_Renderer_unified();
                echo $renderer->render($diff);
            }
            if ($this->_diffonly) {
                return;
            }
            echo "Expecting:\n";
            var_export($control);
            echo "\nReceived:\n";
            var_export($test);
        }
    }

    function _failTest($trace, $message)
    {
        echo 'Test Failure: "' . $message  . "\"\n in " . $trace[0]['file'] . ' line ' .
            $trace[0]['line'] . "\n";
    }
}
?>