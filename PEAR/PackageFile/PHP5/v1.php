<?php
class PEAR_PackageFile_PHP5_v1
{
    /**
     * @var DOMDocument
     */
    protected $dom;
    private $_schemaValidateWarnings = array();
    /**
     * Never call this function directly - its only purpose
     * is to provide a callback for ->schemaValidate
     * @access private
     */
    public function _catchWarnings($errno, $errstr)
    {
        $this->_schemaValidateWarnings[] = $errstr;
    }

    public function validate()
    {
        set_error_handler(array($this, '_catchWarnings'));
        restore_error_handler();
    }

    public function fromDom($dom)
    {
        $this->dom = $dom;
        $this->validate();
    }

    /**
     * BC hack to allow PEAR_Common::infoFromString() to sort of
     * work with the version 2.0 format
     * @param PEAR_PackageFile_v2
     */
    public function fromV2($packagefile)
    {
    }

    /**
     * Build a "provides" array from data returned by
     * analyzeSourceCode().  The format of the built array is like
     * this:
     *
     *  array(
     *    'class;MyClass' => 'array('type' => 'class', 'name' => 'MyClass'),
     *    ...
     *  )
     *
     *
     * @param array $srcinfo array with information about a source file
     * as returned by the analyzeSourceCode() method.
     *
     * @return void
     *
     * @access private
     *
     */
    function _buildProvidesArray($srcinfo)
    {
        if (!$this->_isValid) {
            return false;
        }
        $file = basename($srcinfo['source_file']);
        $pn = $this->_packageInfo['package'];
        $pnl = strlen($pn);
        foreach ($srcinfo['declared_classes'] as $class) {
            $key = "class;$class";
            if (isset($this->_packageInfo['provides'][$key])) {
                continue;
            }
            $this->_packageInfo['provides'][$key] =
                array('file'=> $file, 'type' => 'class', 'name' => $class);
            if (isset($srcinfo['inheritance'][$class])) {
                $this->_packageInfo['provides'][$key]['extends'] =
                    $srcinfo['inheritance'][$class];
            }
        }
        foreach ($srcinfo['declared_methods'] as $class => $methods) {
            foreach ($methods as $method) {
                $function = "$class::$method";
                $key = "function;$function";
                if ($method{0} == '_' || !strcasecmp($method, $class) ||
                    isset($this->_packageInfo['provides'][$key])) {
                    continue;
                }
                $this->_packageInfo['provides'][$key] =
                    array('file'=> $file, 'type' => 'function', 'name' => $function);
            }
        }

        foreach ($srcinfo['declared_functions'] as $function) {
            $key = "function;$function";
            if ($function{0} == '_' || isset($this->_packageInfo['provides'][$key])) {
                continue;
            }
            if (!strstr($function, '::') && strncasecmp($function, $pn, $pnl)) {
                $warnings[] = "in1 " . $file . ": function \"$function\" not prefixed with package name \"$pn\"";
            }
            $this->_packageInfo['provides'][$key] =
                array('file'=> $file, 'type' => 'function', 'name' => $function);
        }
    }

    // }}}
    // {{{ analyzeSourceCode()

    /**
     * Analyze the source code of the given PHP file
     *
     * @param  string Filename of the PHP file
     * @return mixed
     * @access private
     */
    function _analyzeSourceCode($file)
    {
        if (!function_exists("token_get_all")) {
            return false;
        }
        if (!$fp = @fopen($file, "r")) {
            return false;
        }
        $contents = @fread($fp, filesize($file));
        if (!$contents) {
            return false;
        }
        $tokens = token_get_all($contents);
/*
        for ($i = 0; $i < sizeof($tokens); $i++) {
            @list($token, $data) = $tokens[$i];
            if (is_string($token)) {
                var_dump($token);
            } else {
                print token_name($token) . ' ';
                var_dump(rtrim($data));
            }
        }
*/
        $look_for = 0;
        $paren_level = 0;
        $bracket_level = 0;
        $brace_level = 0;
        $lastphpdoc = '';
        $current_class = '';
        $current_class_level = -1;
        $current_function = '';
        $current_function_level = -1;
        $interface = false;
        $declared_classes = array();
        $declared_functions = array();
        $declared_methods = array();
        $used_classes = array();
        $used_functions = array();
        $extends = array();
        $nodeps = array();
        $inquote = false;
        for ($i = 0; $i < sizeof($tokens); $i++) {
            if (is_array($tokens[$i])) {
                list($token, $data) = $tokens[$i];
            } else {
                $token = $tokens[$i];
                $data = '';
            }
            if ($inquote) {
                if ($token != '"') {
                    continue;
                } else {
                    $inquote = false;
                }
            }
            switch ($token) {
                case '"':
                    $inquote = true;
                    break;
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case ';':
                    if ($interface) {
                        $current_function = '';
                        $current_function_level = -1;
                        continue 2;
                    }
                case '{':
                    $brace_level++;
                    continue 2;
                case '}':
                    $brace_level--;
                    if ($current_class_level == $brace_level) {
                        $current_class = '';
                        $interface = false;
                        $current_class_level = -1;
                    }
                    if ($current_function_level == $brace_level) {
                        $current_function = '';
                        $current_function_level = -1;
                    }
                    continue 2;
                case '[': $bracket_level++; continue 2;
                case ']': $bracket_level--; continue 2;
                case '(': $paren_level++;   continue 2;
                case ')': $paren_level--;   continue 2;
                case T_INTERFACE:
                    $interface = true;
                case T_CLASS:
                    if (($current_class_level != -1) || ($current_function_level != -1)) {
                        $this>_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE,
                            array('file' => $file));
                        return false;
                    }
                case T_FUNCTION:
                case T_NEW:
                case T_EXTENDS:
                    $look_for = $token;
                    continue 2;
                case T_STRING:
                    if ($look_for == T_CLASS) {
                        $current_class = $data;
                        $current_class_level = $brace_level;
                        $declared_classes[] = $current_class;
                    } elseif ($look_for == T_EXTENDS) {
                        $extends[$current_class] = $data;
                    } elseif ($look_for == T_FUNCTION) {
                        if ($current_class) {
                            $current_function = "$current_class::$data";
                            $declared_methods[$current_class][] = $data;
                        } else {
                            $current_function = $data;
                            $declared_functions[] = $current_function;
                        }
                        $current_function_level = $brace_level;
                        $m = array();
                    } elseif ($look_for == T_NEW) {
                        $used_classes[$data] = true;
                    }
                    $look_for = 0;
                    continue 2;
                case T_VARIABLE:
                    $look_for = 0;
                    continue 2;
                case T_COMMENT:
                    if (preg_match('!^/\*\*\s!', $data)) {
                        $lastphpdoc = $data;
                        if (preg_match_all('/@nodep\s+(\S+)/', $lastphpdoc, $m)) {
                            $nodeps = array_merge($nodeps, $m[1]);
                        }
                    }
                    continue 2;
                case T_DOUBLE_COLON:
                    if ($tokens[$i - 1][0] != T_STRING) {
                        $this>_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE,
                            array('file' => $file));
                        return false;
                    }
                    $class = $tokens[$i - 1][1];
                    if (strtolower($class) != 'parent') {
                        $used_classes[$class] = true;
                    }
                    continue 2;
            }
        }
        return array(
            "source_file" => $file,
            "declared_classes" => $declared_classes,
            "declared_methods" => $declared_methods,
            "declared_functions" => $declared_functions,
            "used_classes" => array_diff(array_keys($used_classes), $nodeps),
            "inheritance" => $extends,
            );
    }
}
?>