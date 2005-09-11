<?php
/**
 * Don't use this, it's an abortive attempt - although it could work
 */
require_once 'PEAR/PackageFile/v1.php';
require_once 'PEAR/Validate.php';
class PEAR_PackageFile_PHP5_v1 extends PEAR_PackageFile_v1
{
    /**
     * @var DOMDocument
     */
    public $dom;
    /**
     * @var SimpleXMLElement
     */
    private $sxe;
    private $sourcefile = false;
    private $_schemaValidateWarnings = array();
    private $_registry;
    private $_isValid = 0;
    /**
     * Never call this function directly - its only purpose
     * is to provide a callback for ->schemaValidate
     * @access private
     */
    public function _catchWarnings($errno, $errstr)
    {
        // fake a PEAR_ErrorStack warning
        $this->_schemaValidateWarnings[] =
            array('package' => 'PEAR_PackageFile_v1',
                  'level' => 'error',
                  'message' => $errstr);
    }

    private function _simpleValidateWarning($params, $code)
    {
        switch ($code) {
            case self::VALIDATE_EMPTY :
                $msg = 'tag "' . $params . '" is empty, and must contain content';
                $params = array('field' => $params);
            break;
            case self::VALIDATE_EMPTYATTRIBUTE :
                $msg = 'tag "' . $params[0] . '" attribute "' . $params[1] . '" is not present';
                $params = array('tag' => $params[0], 'attribute' => $params[1]);
            break;
            case self::VALIDATE_INVALIDATTRIBUTE :
                $msg = 'tag "' . $params[0] . '" attribute "' . $params[1] . '" value "' . $params[2] . '" is not valid';
                $params = array('tag' => $params[0], 'attribute' => $params[1], 'value' => $params[2]);
            break;
            case self::VALIDATE_NONPHPDEP :
                $msg = 'dependency type "' . $params . '" must have a name';
                $params = array('type' => $params);
            break;
            case self::VALIDATE_DEPNOVERSION :
                $msg = 'dependency "' . $params[0] . '" rel "' . $params[1] . '" must have a version';
                $params = array('name' => $params[0], 'rel' => $params[1]);
            break;
            case self::VALIDATE_INVALIDFILE :
                $msg = 'file "' . $params . '" is not contained in a filelist or dir tag';
                $params = array('file' => $params);
            break;
            case self::VALIDATE_INVALIDREPLACETYPE :
                $msg = 'invalid replacement type "' . $params[0] . '" for file "' . $params[1] . '"';
                $params = array('type' => $params[0], 'file' => $params[1]);
            break;
        }
        $this->_schemaValidateWarnings[] =
            array(
                'package' => 'PEAR_PackageFile_v1',
                'level' => 'error',
                'code' => $code,
                'message' => $msg,
                'params' => $params
            );
    }

    private function _validDepType($type)
    {
        return in_array($type, PEAR_Common::getDependencyTypes());
    }

    private function _validDepRel($type)
    {
        return in_array($type, PEAR_Common::getDependencyRelations());
    }

    const VALIDATE_EMPTY = 1;
    const VALIDATE_EMPTYATTRIBUTE = 2;
    const VALIDATE_INVALIDATTRIBUTE = 3;
    const VALIDATE_NONPHPDEP = 4;
    const VALIDATE_DEPNOVERSION = 5;
    const VALIDATE_INVALIDFILE = 6;
    const VALIDATE_INVALIDREPLACETYPE = 7;
    protected function simpleValidate()
    {
        include_once 'PEAR/Common.php';
        $this->_schemaValidateWarnings = array();
        $sx = simplexml_import_dom($this->dom);
        if (!isset($sx->name) || $sx->name == '') {
            $this->_simpleValidateWarning('name', self::VALIDATE_EMPTY);
        }
        if (!isset($sx->summary) || $sx->summary == '') {
            $this->_simpleValidateWarning('summary', self::VALIDATE_EMPTY);
        }
        if (!isset($sx->description) || $sx->description == '') {
            $this->_simpleValidateWarning('description', self::VALIDATE_EMPTY);
        }
        if (!isset($sx->release)) {
            $this->_simpleValidateWarning('release', self::VALIDATE_EMPTY);
        } else {
            if (isset($sx->release->deps)) {
                foreach ($sx->release->deps->dep as $dep) {
                    if (!isset($dep['type'])) {
                        $this->_simpleValidateWarning(array('dep', 'type'),
                            self::VALIDATE_EMPTYATTRIBUTE);
                    } else {
                        if (strtolower($dep['type']) != 'php' &&
                              !strlen((string) $dep)) {
                            $this->_simpleValidateWarning($dep['type'],
                                self::VALIDATE_NONPHPDEP);
                        }
                        if (!$this->_validDepType($dep['type'])) {
                            $this->_simpleValidateWarning(array('dep', 'type',
                                $dep['type']), self::VALIDATE_INVALIDATTRIBUTE);
                        }
                        if (!$this->_validDepRel($dep['rel'])) {
                            $this->_simpleValidateWarning(array('dep', 'rel',
                                $dep['rel']), self::VALIDATE_INVALIDATTRIBUTE);
                        } else {
                            if ($dep['rel'] != 'has' && !isset($dep['version'])) {
                                $this->_simpleValidateWarning(array((string) $dep,
                                    $dep['rel']), self::VALIDATE_DEPNOVERSION);
                            }
                        }
                    }
                }
            }
            if (!isset($sx->release->version) || $sx->release->version == '') {
                $this->_simpleValidateWarning('release->version', self::VALIDATE_EMPTY);
            }
            if (!isset($sx->release->state) || $sx->release->state == '') {
                $this->_simpleValidateWarning('release->state', self::VALIDATE_EMPTY);
            }
            if (!isset($sx->release->date) || $sx->release->date == '') {
                $this->_simpleValidateWarning('release->date', self::VALIDATE_EMPTY);
            }
            if (!isset($sx->release->notes) || $sx->release->notes == '') {
                $this->_simpleValidateWarning('release->notes', self::VALIDATE_EMPTY);
            }
            if (!isset($sx->release->filelist)) {
                $this->_simpleValidateWarning('release->filelist', self::VALIDATE_EMPTY);
            } else {
                foreach ($this->dom->documentElement->getElementsByTagname(
                      'file') as $node) {
                    if (!in_array($node->parentNode->nodeName,
                          array('filelist', 'dir'))) {
                        $this->_simpleValidateWarning($node->getAttribute('name'),
                            self::VALIDATE_INVALIDFILE);
                        continue;
                    }
                    try {
                        $this->_getParentFilelist($node);
                        if (!$node->hasAttribute('name')) {
                            $this->_simpleValidateWarning(array('file', 'name'),
                                self::VALIDATE_EMPTYATTRIBUTE);
                        }
                        if (!$node->hasAttribute('role')) {
                            $this->_simpleValidateWarning(array('file', 'role'),
                                self::VALIDATE_EMPTYATTRIBUTE);
                        }
                    } catch (Exception $e) {
                        $this->_simpleValidateWarning($node->getAttribute('name'),
                            self::VALIDATE_INVALIDFILE);
                    }
                    foreach ($node->getElementsByTagname('replace') as $replace) {
                        if (!$replace->hasAttribute('from')) {
                            $this->_simpleValidateWarning(array('replace', 'from'),
                                self::VALIDATE_EMPTYATTRIBUTE);
                        }
                        if (!$replace->hasAttribute('to')) {
                            $this->_simpleValidateWarning(array('replace', 'to'),
                                self::VALIDATE_EMPTYATTRIBUTE);
                        }
                        if (!$replace->hasAttribute('type')) {
                            $this->_simpleValidateWarning(array('replace', 'type'),
                                self::VALIDATE_EMPTYATTRIBUTE);
                        } else {
                            if (!in_array($replace->getAttribute('type'),
                                  array('package-info', 'pear-config', 'php-const'))) {
                                $this->_simpleValidateWarning(
                                    array($replace->getAttribute('type'),
                                        $node->getAttribute('name')),
                                    self::VALIDATE_INVALIDREPLACETYPE);
                            }
                        }
                    }
                }
            }
        }
        return (bool) !count($this->_schemaValidateWarnings);
    }

    public function validate($state)
    {
        static $attempt = false;
        if ($this->_isValid & $state) {
            return true;
        }
        if ($state == PEAR_VALIDATE_INSTALLING) {
            if ($this->simpleValidate()) {
                $this->_isValid |= $state;
                return true;
            }
        }
        if ($state == PEAR_VALIDATE_PACKAGING) {
            // be strict when packaging
            $attempt = true;
        }
        $this->_schemaValidateWarnings = array();
        set_error_handler(array($this, '_catchWarnings'));
        $this->dom->schemaValidate(dirname(__FILE__) . DIRECTORY_SEPARATOR .
            'package-1.0.xsd');
        restore_error_handler();
        $this->_isValid = 0;
        $ret = false;
        if (count($this->_schemaValidateWarnings)) {
            if (!$attempt) {
                $attempt = true;
                $this->_salvageCrappySyntax();
                $ret = $this->validate($state);
            }
            $attempt = false;
            return $ret;
        }
        $this->_isValid |= PEAR_VALIDATE_NORMAL;
        if ($state == PEAR_VALIDATE_PACKAGING) {
            $attempt = false;
            // validate each file here
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
                    return $this->_reorderRelease($sx, $rel, $this->dom->documentElement);
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
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('name')->item(0));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('summary')->item(0));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('description')->item(0));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('maintainers')->item(0));
        $package->appendChild($this->dom->documentElement->
            getElementsByTagname('release')->item(0));
        $this->dom->replaceChild($this->dom->documentElement, $package);
    }

    private function _reorderRelease($sx, $rel, $par)
    {
        $release = $this->dom->createElement('release');
        $release->appendChild($rel->
            getElementsByTagname('version')->item(0));
        $release->appendChild($rel->
            getElementsByTagname('date')->item(0));
        $release->appendChild($rel->
            getElementsByTagname('license')->item(0));
        $release->appendChild($rel->
            getElementsByTagname('state')->item(0));
        $release->appendChild($rel->
            getElementsByTagname('notes')->item(0));
        if (isset($sx->release->warnings)) {
            $release->appendChild($rel->
                getElementsByTagname('warnings')->item(0));
        }
        if (isset($sx->release->provides)) {
            foreach ($rel->getElementsByTagname('provides') as $provide) {
                $release->appendChild($provide);
            }
        }
        if (isset($sx->release->deps)) {
            $release->appendChild($rel->
                getElementsByTagname('deps')->item(0));
        }
        if (isset($sx->release->configureoptions)) {
            $release->appendChild($rel->
                getElementsByTagname('configureoptions')->item(0));
        }
        $release->appendChild($rel->
            getElementsByTagname('filelist')->item(0));
        $par->replaceChild(
            $release,
            $rel);
    }

    public function fromDom($dom, $state = PEAR_VALIDATE_NORMAL, $file = false)
    {
        $this->dom = $dom;
        $this->flattenFilelist();
        $this->sxe = simplexml_import_dom($dom->cloneNode(true));
        $this->_sourcefile = $file;
        $this->validate($state);
    }

    public function fromArray($arr)
    {
        // convert from format used in php4 version to internal dom
        $this->dom = new DOMDocument('<package version="1.0"/>');
        $d = $this->dom->documentElement;
        $d->appendChild($n = $this->dom->createElement('name'));
        $n->appendChild($d->createTextNode($arr['package']));

        $d->appendChild($n = $this->dom->createElement('summary'));
        $n->appendChild($d->createTextNode($arr['summary']));

        $d->appendChild($n = $this->dom->createElement('description'));
        $n->appendChild($d->createTextNode($arr['description']));

        $d->appendChild($m = $this->dom->createElement('maintainers'));
        foreach ($arr['maintainers'] as $maintainer) {
            $m->appendChild($n = $this->dom->createElement('maintainer'));
            $n->appendChild($this->dom->createElement('user'));
            $n->appendChild($d->createTextNode($maintainer['user']));
            $n->appendChild($this->dom->createElement('name'));
            $n->appendChild($d->createTextNode($maintainer['name']));
            $n->appendChild($this->dom->createElement('email'));
            $n->appendChild($d->createTextNode($maintainer['email']));
            $n->appendChild($this->dom->createElement('role'));
            $n->appendChild($d->createTextNode($maintainer['role']));
        }

        $d->appendChild($r = $this->dom->createElement('release'));

        $r->appendChild($n = $this->dom->createElement('version'));
        $n->appendChild($d->createTextNode($arr['release_version']));

        $r->appendChild($n = $this->dom->createElement('date'));
        $n->appendChild($d->createTextNode($arr['release_date']));

        $r->appendChild($n = $this->dom->createElement('license'));
        $n->appendChild($d->createTextNode($arr['release_license']));

        $r->appendChild($n = $this->dom->createElement('state'));
        $n->appendChild($d->createTextNode($arr['release_state']));

        $r->appendChild($n = $this->dom->createElement('notes'));
        $n->appendChild($d->createTextNode($arr['release_notes']));

        $r->appendChild($de = $this->dom->createElement('deps'));
        foreach ($arr['release_deps'] as $dep) {
            $de->appendChild($n = $this->dom->createElement('dep'));
            foreach ($dep as $attr => $value) {
                if ($attr == 'name') {
                    $n->appendChild($this->dom->createTextNode($value));
                } else {
                    $n->setAttribute($attr, $value);
                }
            }
        }
        $r->appendChild($f = $this->dom->createElement('filelist'));
        $f->appendChild($d = $this->dom->createElement('dir'));
        $d->setAttribute('name', '/');
        foreach ($arr['filelist'] as $file) {
            $d->appendChild($fi = $this->dom->createElement('file'));
            foreach ($file as $attr => $value) {
                if ($attr == 'replacements') {
                    foreach ($value as $replace) {
                        $fi->appendChild($re = $this->dom->createElement('replace'));
                        foreach ($replace as $attr => $value) {
                            $re->setAttribute($attr, $value);
                        }
                    }
                } else {
                    $fi->setAttribute($attr, $value);
                }
            }
        }
    }

    public function setRegistry($r)
    {
        $this->_registry = $r;
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

    private function _cloneFile($file, $indent)
    {
        $new = $file->cloneNode(true);
        $remove = array();
        foreach ($new->childNodes as $node) {
            if ($node->nodeType != XML_ELEMENT_NODE) {
                $remove[] = $node;
            }
        }
        foreach ($remove as $node) {
            $new->removeChild($node);
        }
        $kid = $new->firstChild;
        while ($kid) {
            $kid = $kid->nextSibling;
        }
        return $new;
    }

    public function flattenFilelist($cmp = null, $depth = '  ')
    {
        $d = $this->dom;
        if ($cmp === null) {
            $cmp = $d->documentElement->getElementsByTagname('filelist')->item(0);
        }
        $sx = $d->createElement('filelist');
        $cmp->parentNode->replaceChild($sx, $cmp);
        $sx->appendChild($dir = $d->createElement('dir'));
        $dir->setAttribute('name', '/');
        $files = array();
        foreach ($cmp->getElementsByTagname('file') as $node) {
            if ($this->_getParentFilelist($node) === $cmp) {
                $el = $this->_cloneFile($node, '    ');
                $el->setAttribute('role', $this->_getRole($node));
                $el->setAttribute('name', $this->_getFullPath($node));
                $files[] = $el;
            }
        }
        usort($files, array($this, 'sortdomfiles'));
        foreach ($files as $node) {
            $dir->appendChild($node);
        }
        return $files;
    }


    /**
     * Analyze the source code of the given PHP file
     *
     * @param  string Filename of the PHP file
     * @return mixed
     */
    protected function analyzeSourceCode($file)
    {
        if (!function_exists("token_get_all")) {
            return false;
        }
        if (!$fp = @fopen($file, "r")) {
            return false;
        }
        if (function_exists('file_get_contents')) {
            fclose($fp);
            $contents = file_get_contents($file);
        } else {
            $contents = @fread($fp, filesize($file));
            fclose($fp);
        }
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

    function getDefaultGenerator()
    {
        include_once 'PEAR/PackageFile/Generator/PHP5/v1.php';
        return new PEAR_PackageFile_Generator_PHP5_v1($this);
    }

    function getChannel()
    {
        return 'pear.php.net';
    }

    function getPackagexmlVersion()
    {
        return '1.0';
    }

    function getName()
    {
        return $this->getPackage();
    }

    function setName($name)
    {
        $this->_isValid = 0;
        if (!isset($this->sxe->name)) {
            $this->sxe = null;
            if ($this->dom->documentElement->childNodes->length == 0) {
            $this->dom->documentElement->appendChild(
                $this->dom->documentElement->createTextNode(' '));
            }
            $this->dom->documentElement->insertBefore(
                $n = $this->dom->documentElement->createElement('name'),
                $this->dom->documentElement->firstChild);
            $n->appendChild($this->dom->documentElement->createTextNode($name));
            $this->sxe = simplexml_import_dom($this->dom);
        } else {
            $this->sxe->name = $name;
        }
    }

    function getPackage()
    {
        if (isset($this->sxe->name)) {
            return (string) $this->sxe->name;
        }
        return false;
    }

    function getVersion()
    {
        if (isset($this->sxe->release->version)) {
            return (string) $this->sxe->release->version;
        }
        return false;
    }

    function getMaintainers()
    {
        if (isset($this->sxe->maintainers)) {
            $ret = array();
            foreach ($this->sxe->maintainers->maintainer as $maintainer) {
                $ret[] = array(
                    'user' => (string) $maintainer->user,
                    'name' => (string) $maintainer->name,
                    'email' => (string) $maintainer->email,
                    'role' => (string) $maintainer->role,
                    );
            }
            return $ret;
        }
        return false;
    }

    function getState()
    {
        if (isset($this->sxe->release->state)) {
            return (string) $this->sxe->release->state;
        }
        return false;
    }

    function getDate()
    {
        if (isset($this->sxe->release->date)) {
            return (string) $this->sxe->release->date;
        }
        return false;
    }

    function getLicense()
    {
        if (isset($this->sxe->release->license)) {
            return (string) $this->sxe->release->license;
        }
        return false;
    }

    function getSummary()
    {
        if (isset($this->sxe->summary)) {
            return (string) $this->sxe->summary;
        }
        return false;
    }

    function getDescription()
    {
        if (isset($this->sxe->description)) {
            return (string) $this->sxe->description;
        }
        return false;
    }

    function getNotes()
    {
        if (isset($this->sxe->release->notes)) {
            return (string) $this->sxe->release->notes;
        }
        return false;
    }

    function getDeps()
    {
        if ($this->hasDeps()) {
            $ret = array();
            foreach ($this->sxe->release->deps->dep as $dep) {
                $d = array();
                foreach ($dep->attributes() as $a => $v) {
                    $d[$a] = (string) $v;
                }
                if ((string) $dep) {
                    $d['name'] = (string) $dep;
                }
                $ret[] = $d;
            }
            return $ret;
        }
        return false;
    }

    public function hasDeps()
    {
        return isset($this->sxe->release->deps);
    }

    function getConfigureOptions()
    {
        if ($this->hasConfigureOptions()) {
            $ret = array();
            foreach ($this->sxe->release->configureoptions->configureoption as $config) {
                $d = array();
                foreach ($config->attributes() as $a => $v) {
                    $d[$a] = (string) $v;
                }
                $ret[] = $d;
            }
            return $ret;
        }
        return false;
    }

    public function hasConfigureOptions()
    {
        return isset($this->sxe->release->configureoptions);
    }

    public function getChangelog()
    {
        if (isset($this->sxe->changelog)) {
            foreach ($this->sxe->changelog->release as $release) {
                $ret[] =
                    array(
                        'version' => (string) $release->version,
                        'release_state' => (string) $release->state,
                        'release_date' => (string) $release->date,
                        'release_license' => (string) $release->license,
                        'release_notes' => (string) $release->notes,
                    );
            }
            return $ret;
        }
        return false;
    }

    public function getProvides()
    {
        if (isset($this->sxe->provides)) {
            $ret = array();
            foreach ($this->sxe->provides as $provides) {
                foreach ($provides->attributes() as $a => $v) {
                    $ret[$provides['type'] . ';' . $provides['name']][$a] =
                        (string) $v;
                }
            }
            return $ret;
        }
        return false;
    }

    public function getFilelist()
    {
        $this->flattenFilelist();
        foreach ($this->dom->documentElement->getElementsByTagname('file') as $file) {
            $ret[$file->getAttribute('name')]['role'] =
                $file->getAttribute('role');
            if ($file->getAttribute('install-as')) {
                $ret[$file->getAttribute('name')]['install-as'] =
                    $file->getAttribute('install-as');
            }
            if ($file->getAttribute('platform')) {
                $ret[$file->getAttribute('name')]['platform'] =
                    $file->getAttribute('platform');
            }
            if ($file->getAttribute('md5sum')) {
                $ret[$file->getAttribute('name')]['md5sum'] =
                    $file->getAttribute('md5sum');
            }
            if ($file->getAttribute('baseinstalldir')) {
                $ret[$file->getAttribute('name')]['baseinstalldir'] =
                    $file->getAttribute('baseinstalldir');
            }
            foreach ($file->getElementsByTagname('replace') as $replace) {
                $ret[$file->getAttribute('name')]['replacements'][]
                    = array('from' => $replace->getAttribute('from'),
                            'to' => $replace->getAttribute('to'),
                            'type' => $replace->getAttribute('type'));
            }
        }
        return $ret;
    }

    public function setFileList($filelist)
    {
        $filelist = $this->dom->createElement('filelist');
        $dir = $this->dom->createElement('dir');
        $filelist->appendChild($dir);
        $dir->setAttribute('name', '/');
        foreach ($filelist as $file) {
            $domfile = $this->dom->createElement('file');
            foreach ($file as $attr => $value) {
                $domfile->setAttribute($attr, $value);
            }
            $dir->appendChild($file);
        }
        $this->dom->documentElement->replaceChild(
            $filelist,
            $this->dom->documentElement->getElementsByName('filelist')->item(0));
    }
}
?>