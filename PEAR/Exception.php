<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker: */
// +----------------------------------------------------------------------+
// | PEAR_Exception                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 The PEAR Group                                    |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Tomas V.V.Cox <cox@idecnet.com>                             |
// |          Hans Lellelid <hans@velum.net>                              |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$

define('PEAR_OBSERVER_PRINT',      -2);
define('PEAR_OBSERVER_TRIGGER',    -4);
define('PEAR_OBSERVER_DIE',        -8);

/**
 * Base PEAR_Exception Class
 *
 * 1) Features:
 *
 * - Nestable exceptions (throw new PEAR_Exception($msg, $prev_exception))
 * - Definable triggers, shot when exceptions occur
 * - Pretty and informative error messages
 * - Added more context info avaible (like class, method or cause)
 *
 * 2) Ideas:
 *
 * - Maybe a way to define a 'template' for the output
 *
 * 3) Inherited properties from PHP Exception Class:
 *
 * protected $message
 * protected $code
 * protected $line
 * protected $file
 * private   $trace
 *
 * 4) Inherited methods from PHP Exception Class:
 *
 * __clone
 * __construct
 * getMessage
 * getCode
 * getFile
 * getLine
 * getTrace
 * getTraceAsString
 * __toString
 *
 * 5) Usage example
 *
 * <code>
 *  require_once 'PEAR/Exception.php';
 *
 *  class Test {
 *     function foo() {
 *         throw new PEAR_Exception('Error Message', ERROR_CODE);
 *     }
 *  }
 *
 *  function myLogger($pear_exception) {
 *     echo $pear_exception->getMessage();
 *  }
 *  // each time a exception is thrown the 'myLogger' will be called
 *  // (its use is completely optional)
 *  PEAR_Exception::addObserver('myLogger');
 *  $test = new Test;
 *  try {
 *     $test->foo();
 *  } catch (PEAR_Exception $e) {
 *     print $e;
 *  }
 * </code>
 *
 * @since PHP 5
 * @package PEAR
 * @version $Revision$
 * @author Tomas V.V.Cox <cox@idecnet.com>
 * @author Hans Lellelid <hans@velum.net>
 *
 */
class PEAR_Exception extends Exception
{
    protected $cause;
    protected $error_class;
    protected $error_method;

    private $_method;
    private static $_observers = array();

    private static $_warnings  = array();
    private static $_warnings_callback = null;
    private static $_warnings_stack_l = 1;

    /**
     * Supported signatures:
     * PEAR_Exception(string $message);
     * PEAR_Exception(string $message, int $code);
     * PEAR_Exception(string $message, Exception $cause);
     * PEAR_Exception(string $message, Exception $cause, int $code);
     */
    public function __construct($message, $p2 = null, $p3 = null)
    {
        $code = null;
        $cause = null;
        if (is_int($p3) && $p2 instanceof Exception) {
            $code = $p3;
            $cause = $p2;
        } elseif (is_int($p2)) {
            $code = $p2;
        } elseif ($p2 instanceof Exception) {
            $cause = $p2;
        }
        $this->cause = $cause;
        $trace       = parent::getTrace();
        $this->error_class  = $trace[0]['class'];
        $this->error_method = $trace[0]['function'];
        $this->_method = $this->error_class . $trace[0]['type'] . $this->error_method . '()';
        parent::__construct($message, $code);

        $this->_signalObservers();
    }

    /**
     * @param mixed $callback  - A valid php callback, see php func is_callable()
     *                         - A PEAR_OBSERVER_* constant
     *                         - An array(const PEAR_OBSERVER_*, mixed $options)
     *
     * @param string $context  The observer only runs for this class name
     *                         (defaults to reserved word 'all').
     *
     * @param string $label    The name of the observer. Use this if you want
     *                         to remove it later with delObserver()
     *
     * @return string $label   The label for the new observer (an internal name is set
     *                         when no label is given)
     *
     */
    public static function addObserver($callback, $context = 'all', $label = null)
    {
        static $counter = 1;
        if ($label == null) {
            $label = 'default_' . $counter++;
        }
        self::$_observers[$label]['callback'] = $callback;
        self::$_observers[$label]['context']  = $context;
        return $label;
    }

    public static function delObserver($label)
    {
        unset(self::$_observers[$label]);
    }

    private function _signalObservers()
    {
        foreach (self::$_observers as $data) {
            $context = $data['context'];
            if ($context != 'all' &&
                strcasecmp($context, $this->getErrorClass()) != 0)
            {
                continue;
            }
            self::_signalOne($data['callback'], $this, $this->message, $this->code);
        }
    }

    private static function _signalOne($func, $func_params, $message, $code)
    {
        if (is_callable($func)) {
            call_user_func($func, $func_params);
            continue;
        }
        settype($func, 'array');
        switch ($func[0]) {
            case PEAR_OBSERVER_PRINT:
                $f = (isset($func[1])) ? $func[1] : '%s';
                printf($f, $message);
                break;
            case PEAR_OBSERVER_TRIGGER:
                $f = (isset($func[1])) ? $func[1] : E_USER_NOTICE;
                trigger_error($message, $f);
                break;
            case PEAR_OBSERVER_DIE:
                $f = (isset($func[1])) ? $func[1] : '%s';
                die(printf($f, $message));
                break;
            default:
                trigger_error('invalid observer type', E_USER_WARNING);
        }
    }

    private function _getCauseMessage()
    {
        $msg = "    #{$this->_method} at {$this->file} ({$this->line})\n" .
               "     {$this->message}\n";
        if ($this->cause instanceof Exception) {
            return $this->cause->_getCauseMessage() . $msg;
        }
        return $msg;
    }

    /**
     * @return Exception_object The context of the exception
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * Get the class name where the error occurred
     *
     * @return string The class name
     */
    public function getErrorClass()
    {
        return $this->error_class;
    }

    public function getErrorMethod()
    {
        return $this->error_method;
    }

    public function __toString()
    {
        $str = get_class($this) . " occurred: \n" .
               "  Error message: {$this->message}\n" .
               "  Error code   : {$this->code}\n" .
               "  File (Line)  : {$this->file} ({$this->line})\n" .
               "  Method       : {$this->_method}\n";
        if ($this->cause instanceof Exception) {
            $str .= "  Nested Error :\n" . $this->_getCauseMessage();
        }
        if (isset($_SERVER['REQUEST_URI'])) {
            return nl2br('<pre>'.htmlentities($str).'</pre>');
        }
        return $str;
    }

    /**
     *
     * @param string|array $callback A callback called each time a new
     *                               warning is added
     * @param int $stack_length The number of warnings to store in the
     *                          stack. -1 means max 99999999 warnings
     */
    public static function setWarningOptions($callback = null, $stack_length = 1)
    {
        self::$_warnings_stack_l = ($stack_length == -1) ? 99999999 : $stack_length;
        self::$_warnings_callback = $callback;
    }

    /**
     *
     * @param string     $message The warning descriptive message
     * @param int        $code    The code of the warning
     * @param int|string $context A place for the warning, it could be
     *                            for ex. the class where it occurred (string)
     *                            or a severity code (int)
     */
    public static function addWarning($message, $code = null, $context = 'all')
    {
         $w = array('message' => $message,
                    'code'    => $code,
                    'context' => $context);
        self::$_warnings[] = $w;
        // Stack size control
        if (count(self::$_warnings) > self::$_warnings_stack_l) {
            array_shift(self::$_warnings);
        }
        // Warning observer triggering
        if (self::$_warnings_callback) {
            self::_signalOne(self::$_warnings_callback, $w, $message, $code);
        }
    }

    /**
     * @param int|string $context see addWarning()
     */
    public static function getWarning($context = 'all')
    {
        $ret = false;
        foreach(self::$_warnings as $k => $v) {
            if ($context == 'all' ||
                $v['context'] == $context ||
                (is_int($v['context']) && is_int($context) && $v['context'] >= $context) )
            {
                $ret[] = $v;
                unset(self::$_warnings[$k]);
            }
        }
        return $ret;
    }
}

?>