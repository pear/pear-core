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

    function assertTrue($test, $message)
    {
        if ($test === true) {
            return true;
        }
        $this->_failTest(debug_backtrace(), $message);
        echo "Unexpected non-true value: \n";
        var_export($test);
        echo "\n'$message'\n";
        return false;
    }

    function assertNull($test, $message)
    {
        if ($test === null) {
            return true;
        }
        $this->_failTest(debug_backtrace(), $message);
        echo "Unexpected non-null value: \n";
        var_export($test);
        echo "\n'$message'\n";
        return false;
    }

    function assertNotNull($test, $message)
    {
        if ($test !== null) {
            return true;
        }
        $this->_failTest(debug_backtrace(), $message);
        echo "Unexpected null: \n";
        var_export($test);
        echo "\n'$message'\n";
        return false;
    }

    function assertFalse($test, $message)
    {
        if ($test === false) {
            return true;
        }
        $this->_failTest(debug_backtrace(), $message);
        echo "Unexpected non-false value: \n";
        var_export($test);
        echo "\n'$message'\n";
        return false;
    }

    function assertNotTrue($test, $message)
    {
        if (!$test) {
            return true;
        }
        $this->_failTest(debug_backtrace(), $message);
        echo "Unexpected loose true value: \n";
        var_export($test);
        echo "\n'$message'\n";
        return false;
    }

    function assertNotFalse($test, $message)
    {
        if ($test) {
            return true;
        }
        $this->_failTest(debug_backtrace(), $message);
        echo "Unexpected loose false value: \n";
        var_export($test);
        echo "\n'$message'\n";
        return false;
    }

    function assertEquals($control, $test, $message)
    {
        if (str_replace(array("\r", "\n"), array('', ''),
            var_export($control, true)) != str_replace(array("\r", "\n"), array('', ''),
            var_export($test, true))) {
            $this->_failTest(debug_backtrace(), $message);
            if (class_exists('Text_Diff')) {
                echo "Diff of expecting/received:\n";
                $diff = &new Text_Diff(
                    explode("\n", var_export($control, true)),
                    explode("\n", var_export($test, true)));

                // Output the diff in unified format.
                $renderer = &new Text_Diff_Renderer_unified();
                echo $renderer->render($diff);
                if ($this->_diffonly) {
                    return false;
                }
            }
            echo "Expecting:\n";
            var_export($control);
            echo "\nReceived:\n";
            var_export($test);
            return false;
        }
        return true;
    }

    function assertRegEquals($dump, &$reg, $message)
    {
        $actualdump = var_export(trim($this->dumpReg($reg)), true);
        $testdump = var_export(trim($dump), true);
        return $this->assertEquals($testdump, $actualdump, $message);
    }

    function assertPackageInfoEquals($control, $test, $message)
    {
        if (isset($control[0])) {
            if (!isset($test[0]) || (count($control) != count($test))) {
                echo "Invalid packageInfo\n";
                $ret = $this->assertEquals($control, $test, $message);
            }
            $ret = true;
            foreach ($control as $i => $packageinfo) {
                $ret = $ret &&
                    $this->assertPackageInfoEquals($packageinfo, $test[$i], $message . $i);
            }
            return $ret;
        }
        if (isset($control['_lastmodified'])) {
            if (!isset($test['_lastmodified'])) {
                echo "_lastmodified is not set in packageInfo() output\n";
                $this->_failTest(debug_backtrace(), $message);
                return false;
            }
        }
        $savecontrol = $control;
        $savetest = $test;
        unset($control['_lastmodified']);
        unset($test['_lastmodified']);
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
                if ($this->_diffonly) {
                    return false;
                }
            }
            echo "Expecting:\n";
            var_export($savecontrol);
            echo "\nReceived:\n";
            var_export($savetest);
            return false;
        }
        return true;
    }

    function dumpReg(&$reg)
    {
        ob_start();
        print "dumping registry...\n";
        $infos = $reg->allPackageInfo();
        foreach ($infos as $channel => $info) {
            echo "channel $channel:\n";
            foreach ($info as $pkg) {
                print $pkg["name"] . ":";
                unset($pkg["name"]);
                foreach ($pkg as $k => $v) {
                    if ($k == '_lastmodified') {
                        print " _lastmodified is set";
                        continue;
                    }
                    if (is_array($v) && $k == 'filelist') {
                        print " $k=array(";
                        $i = 0;
                        foreach ($v as $k2 => $v2) {
                            if ($i++ > 0) print ",";
                            print "{$k2}[";
                            $j = 0;
                            foreach ($v2 as $k3 => $v3) {
                                if ($j++ > 0) print ",";
                                print "$k3=$v3";
                            }
                            print "]";
                        }
                        print ")";
                    } else {
                        print " $k=\"$v\"";
                    }
                }
                print "\n";
            }
        }
        print "dump done\n";
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    function _failTest($trace, $message)
    {
        echo 'Test Failure: "' . $message  . "\"\n in " . $trace[0]['file'] . ' line ' .
            $trace[0]['line'] . "\n";
    }

    function showAll()
    {
        $this->_diffonly = false;
    }
}
?>