<?php
class PEAR_PackageFile_PHP5_v1
{
    /**
     * @var DOMDocument
     */
    public $dom;
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
        static $attempt = false;
        $this->_schemaValidateWarnings = array();
        set_error_handler(array($this, '_catchWarnings'));
        $this->dom->schemaValidate(dirname(__FILE__) . DIRECTORY_SEPARATOR .
            'package-1.0.xsd');
        restore_error_handler();
        $ret = false;
        if (count($this->_schemaValidateWarnings)) {
            if (!$attempt) {
                $attempt = true;
                $this->_salvageCrappySyntax();
                $ret = $this->validate();
            }
            $attempt = false;
            return $ret;
        }
        return true;
    }

    public function getValidationWarnings()
    {
        return $this->_schemaValidateWarnings;
    }

    /**
     * for older, mal-formed package.xml, try to force it to be conformant first
     */
    private function _salvageCrappySyntax()
    {
        $working = $this->dom->cloneNode(true);
        $sx = simplexml_import_dom($working);
        $rel = $this->dom->documentElement->getElementsByTagname('release')->item(0);
        $fields = array_keys((array) $sx);
        if (!in_array('name', $fields) || !in_array('summary', $fields) ||
              !in_array('description', $fields) || !in_array('maintainers', $fields) ||
              !in_array('release', $fields)) {
            // if any of these are missing, it's hopeless
            return;
        }
        $releasefields = array_keys((array) $sx->release);
        if (!in_array('version', $releasefields) || !in_array('date', $releasefields) ||
              !in_array('state', $releasefields) || !in_array('notes', $releasefields) ||
              !in_array('filelist', $releasefields)) {
            // if any of these are missing, it's hopeless
            return;
        }
        if (!isset($sx->release->license) && isset($sx->license)) {
            // fix those package.xml that only set global license
            // by moving it into the release tag
            $license = $this->dom->documentElement->removeChild(
                $this->dom->documentElement->getElementsByTagname('license')->item(0));
            $this->dom->documentElement->
                getElementsByTagname('release')->item(0)->
                insertBefore(
                  $license,
                  $this->dom->documentElement->getElementsByTagname('state')->item(0));
            $this->_reorderBasic();
            return $this->_reorderRelease($sx);
        }
        $test = array('name', 'summary', 'description', '?license', 'maintainers', 'release','?changelog');
        $skip = false;
        foreach ($test as $next) {
            if (!$skip) {
                $tag = array_shift($fields);
            }
            $skip = false;
            if ($next{0} == '?') {
                $opt = true;
                $next = substr($next, 1);
            } else {
                $opt = false;
            }
            if ($next != $tag) {
                if (!$opt) {
                    $this->_reorderBasic();
                } else {
                    // skip missing optional tags
                    $skip = true;
                }
            }
        }
        if (is_array($fields) && count($fields)) {
            $this->_reorderBasic();
        }
        $test = array('version', 'date', 'license', 'state', 'notes', '?warnings', '?provides', '?deps', '?configureoptions', 'filelist');
        $skip = false;
        foreach ($test as $next) {
            if (!$skip) {
                $tag = array_shift($releasefields);
            }
            $skip = false;
            if ($next{0} == '?') {
                $opt = true;
                $next = substr($next, 1);
            } else {
                $opt = false;
            }
            if ($next != $tag) {
                if (!$opt) {
                    $this->_reorderRelease($sx);
                } else {
                    // skip missing optional tags
                    $skip = true;
                }
            }
        }
        if (is_array($releasefields) && count($releasefields)) {
            $this->_reorderRelease($sx, $rel, $this->dom->documentElement);
        }
    }

    private function _reorderBasic()
    {
        $package = $this->dom->createElement('package');
        $package->appendChild($this->dom->createTextNode("\n "));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('name')->item(0));
        $package->appendChild($this->dom->createTextNode("\n "));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('summary')->item(0));
        $package->appendChild($this->dom->createTextNode("\n "));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('description')->item(0));
        $package->appendChild($this->dom->createTextNode("\n "));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('maintainers')->item(0));
        $package->appendChild($this->dom->createTextNode("\n "));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('release')->item(0));
        $package->appendChild($this->dom->createTextNode("\n"));
        $this->dom->replaceChild($this->dom->documentElement, $package);
    }

    private function _reorderRelease($sx, $rel, $par)
    {
        $release = $this->dom->createElement('release');
        $release->appendChild($this->dom->createTextNode("\n  "));
        $release->appendChild($rel->
            getElementsByTagname('version')->item(0));
        $release->appendChild($this->dom->createTextNode("\n  "));
        $release->appendChild($rel->
            getElementsByTagname('date')->item(0));
        $release->appendChild($this->dom->createTextNode("\n  "));
        $release->appendChild($rel->
            getElementsByTagname('license')->item(0));
        $release->appendChild($this->dom->createTextNode("\n  "));
        $release->appendChild($rel->
            getElementsByTagname('state')->item(0));
        $release->appendChild($this->dom->createTextNode("\n  "));
        $release->appendChild($rel->
            getElementsByTagname('notes')->item(0));
        if (isset($sx->release->warnings)) {
            $release->appendChild($this->dom->createTextNode("\n  "));
            $release->appendChild($rel->
                getElementsByTagname('warnings')->item(0));
        }
        if (isset($sx->release->provides)) {
            foreach ($rel->getElementsByTagname('provides') as $provide) {
                $release->appendChild($this->dom->createTextNode("\n  "));
                $release->appendChild($provide);
            }
        }
        if (isset($sx->release->deps)) {
            $release->appendChild($this->dom->createTextNode("\n  "));
            $release->appendChild($rel->
                getElementsByTagname('deps')->item(0));
        }
        if (isset($sx->release->configureoptions)) {
            $release->appendChild($this->dom->createTextNode("\n  "));
            $release->appendChild($rel->
                getElementsByTagname('configureoptions')->item(0));
        }
        $release->appendChild($this->dom->createTextNode("\n  "));
        $release->appendChild($rel->
            getElementsByTagname('filelist')->item(0));
        $release->appendChild($this->dom->createTextNode("\n "));
        $par->replaceChild(
            $release,
            $rel);
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

    private function _getRole($node)
    {
        if ($node->hasAttribute('role')) {
            return $node->getAttribute('role');
        }
        $node = $node->parentNode;
        while($node->nodeName == 'dir') {
            if ($node->hasAttribute('role')) {
                return $node->getAttribute('role');
            }
        }        
    }

    private function _getFullPath($node)
    {
        $i = 0;
        $path = $node->getAttribute('name');
        $node = $node->parentNode;
        while($node->nodeName == 'dir' && $node->getAttribute('name') != '/') {
            $path = $node->getAttribute('name') . '/' . $path;
            $node = $node->parentNode;
        }
        return str_replace('//', '/', $path);
    }

    private function _getParentFilelist($node)
    {
        while ($node->nodeName != 'filelist' && $node->nodeName != 'package') {
            $node = $node->parentNode;
        }
        if ($node->nodeName == 'package') {
            throw new Exception('node is not a child of filelist');
        }
        return $node;
    }

    function sortdomfiles($a, $b)
    {
        $z = strnatcmp($a->getAttribute('name'), $b->getAttribute('name'));
        if ($z == 1) return -1;
        if ($z == -1) return 1;
        return 0;
    }

    public function flattenFilelist($cmp = null, $depth = '  ')
    {
        $d = $this->dom;
        if ($cmp === null) {
            $cmp = $d->documentElement->getElementsByTagname('filelist')->item(0);
        }
        $sx = $d->createElement('filelist');
        $cmp->parentNode->replaceChild($sx, $cmp);
        $sx->appendChild($d->createTextNode("\n$depth"));
        $sx->appendChild($dir = $d->createElement('dir'));
        $sx->appendChild($d->createTextNode("\n "));
        $dir->setAttribute('name', '/');
        $files = array();
        foreach ($cmp->getElementsByTagname('file') as $node) {
            if ($this->_getParentFilelist($node) === $cmp) {
                $el = $node->cloneNode();
                $el->setAttribute('role', $this->_getRole($node));
                $el->setAttribute('name', $this->_getFullPath($node));
                $files[] = $el;
            }
        }
        usort($files, array($this, 'sortdomfiles'));
        foreach ($files as $node) {
            $dir->appendChild($d->createTextNode("\n$depth "));
            $dir->appendChild($node);
        }
        $dir->appendChild($d->createTextNode("\n  "));
        return $files;
    }

    public function recursiveFilelist($cmp = null, $depth = '  ')
    {
        $files = array_reverse($this->flattenFilelist());
        $d = $this->dom;
        if ($cmp === null) {
            $cmp = $d->documentElement->getElementsByTagname('filelist')->item(0);
        }
        $sx = $d->createElement('filelist');
        $cmp->parentNode->replaceChild($sx, $cmp);
        $sx->appendChild($d->createTextNode("\n$depth"));
        $sx->appendChild($dirnode = $d->createElement('dir'));
        $dirnode->setAttribute('name', '/');
        $sx->appendChild($d->createTextNode("\n$depth"));
        $dirs = $mainfiles = array();
        foreach ($files as $file) {
            $name = explode('/', $file->getAttribute('name'));
            $filename = array_pop($name);
            $path = '';
            if (count($name)) {
                foreach ($name as $i => $dir) {
                    if ($path != '') {
                        $path .= '/';
                    }
                    $path .= $dir;
                    if (!isset($dirs[$path])) {
                        $dirs[$path] = $this->dom->createElement('dir');
                        $dirs[$path]->setAttribute('name', $dir);
                    }
                    if ($i == count($name) - 1) {
                        $newfile = $file->cloneNode();
                        $dirs[$path]->appendChild($this->dom->createTextNode("\n  $depth" .
                            str_repeat(' ', count($name))));
                        $dirs[$path]->appendChild($newfile);
                        $newfile->setAttribute('name', $filename);
                    }
                }
            } else {
                $mainfiles[$file->getAttribute('name')] = $file->cloneNode();
            }
        }
        uksort($dirs, 'strnatcmp');
        $dirs = array_reverse($dirs);
        $maindirs = array();
        foreach ($dirs as $path => $cdir) {
            if (basename($path) != $path && isset($dirs[dirname($path)])) {
                $cdir->appendChild($this->dom->createTextNode("\n  $depth" .
                        str_repeat(' ', count(explode('/', dirname($path))))));
                $parent = $dirs[dirname($path)];
                $parent->insertBefore($cdir, $parent->firstChild);
                $parent->insertBefore(
                    $this->dom->createTextNode("\n  $depth" .
                        str_repeat(' ', count(explode('/', dirname($path))))),
                    $parent->firstChild);
                $parent->insertBefore(
                    $co = $this->dom->createTextNode(' '),
                    $cdir->nextSibling);
                $parent->insertBefore(
                    $this->dom->createComment($path),
                    $co->nextSibling);
            } else {
                $cdir->appendChild($this->dom->createTextNode("\n  $depth"));
                array_unshift($maindirs, array($path, $cdir));
            }
        }
        foreach ($maindirs as $cdir) {
            $dirnode->appendChild($this->dom->createTextNode("\n  $depth"));
            $dirnode->appendChild($cdir[1]);
            $dirnode->appendChild($this->dom->createTextNode(" "));
            $dirnode->appendChild($this->dom->createComment(" $cdir[0] "));
        }
        uksort($mainfiles, 'strnatcmp');
        foreach ($mainfiles as $file) {
            $dirnode->appendChild($this->dom->createTextNode("\n  $depth"));
            $dirnode->appendChild($file);
        }
        $dirnode->appendChild($this->dom->createTextNode("\n $depth"));
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