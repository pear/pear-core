<?php
require_once 'PEAR/PackageFile/Generator/PHP5/Common.php';
class PEAR_PackageFile_Generator_PHP5_v1 extends PEAR_PackageFile_Generator_PHP5_Common
{
    public function toXml()
    {
        // re-format the dom stuff
        parent::toXml();
        // do special formatting for short tags like name
        return $this->pf->dom->saveXml();
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
    private function _buildProvidesArray($srcinfo)
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

    public function toArray()
    {
        $ret = array(
            'xsdversion' => '1.0',
            'package' => $this->pf->getPackage(),
            'summary' => $this->pf->getSummary(),
            'description' => $this->pf->getDescription(),
            'version' => $this->pf->getVersion(),
            'maintainers' => $this->pf->getMaintainers(),
            'release_state' => $this->pf->getState(),
            'release_date' => $this->pf->getDate(),
            'release_license' => $this->pf->getLicense(),
            'release_notes' => $this->pf->getNotes(),
        );
        if ($changelog = $this->pf->getChangelog()) {
            $ret['changelog'] = $changelog;
        }
        if ($this->pf->hasDeps()) {
            $ret['release_deps'] = $this->pf->getDeps();
        }
        if ($this->pf->hasConfigureOptions()) {
            $ret['configure_options'] = $this->pf->getConfigureOptions();
        }
        if ($provides = $this->pf->getProvides()) {
            $ret['provides'] = $provides;
        }
        $ret['filelist'] = $this->pf->getFilelist();
        return $ret;
    }
}
?>