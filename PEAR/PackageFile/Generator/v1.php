<?php
class PEAR_PackageFile_Generator_v1
{
    /**
     * @var PEAR_PackageFile_v1
     */
    var $_packagefile;
    function PEAR_PackageFile_Generator_v1(&$packagefile)
    {
        $this->_packagefile = &$packagefile;
    }

    /**
     * Return an XML document based on the package info (as returned
     * by the PEAR_Common::infoFrom* methods).
     *
     * @return string XML data
     */
    function toXml()
    {
        if (!$this->_packagefile->validate()) {
            return false;
        }
        $pkginfo = $this->_packagefile->toArray();
        static $maint_map = array(
            "handle" => "user",
            "name" => "name",
            "email" => "email",
            "role" => "role",
            );
        $ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n";
        $ret .= "<!DOCTYPE package SYSTEM \"http://pear.php.net/dtd/package-1.0\">\n";
        $ret .= "<package version=\"1.0\" packagerversion=\"@PEAR-VER@\">
  <name>$pkginfo[package]</name>
  <summary>".htmlspecialchars($pkginfo['summary'])."</summary>
  <description>".htmlspecialchars($pkginfo['description'])."</description>
  <maintainers>
";
        foreach ($pkginfo['maintainers'] as $maint) {
            $ret .= "    <maintainer>\n";
            foreach ($maint_map as $idx => $elm) {
                $ret .= "      <$elm>";
                $ret .= htmlspecialchars($maint[$idx]);
                $ret .= "</$elm>\n";
            }
            $ret .= "    </maintainer>\n";
        }
        $ret .= "  </maintainers>\n";
        $ret .= $this->_makeReleaseXml($pkginfo);
        if (@sizeof($pkginfo['changelog']) > 0) {
            $ret .= "  <changelog>\n";
            foreach ($pkginfo['changelog'] as $oldrelease) {
                $ret .= $this->_makeReleaseXml($oldrelease, true);
            }
            $ret .= "  </changelog>\n";
        }
        $ret .= "</package>\n";
        return $ret;
    }

    // }}}
    // {{{ _makeReleaseXml()

    /**
     * Generate part of an XML description with release information.
     *
     * @param array  $pkginfo    array with release information
     * @param bool   $changelog  whether the result will be in a changelog element
     *
     * @return string XML data
     *
     * @access private
     */
    function _makeReleaseXml($pkginfo, $changelog = false)
    {
        // XXX QUOTE ENTITIES IN PCDATA, OR EMBED IN CDATA BLOCKS!!
        $indent = $changelog ? "  " : "";
        $ret = "$indent  <release>\n";
        if (!empty($pkginfo['version'])) {
            $ret .= "$indent    <version>$pkginfo[version]</version>\n";
        }
        if (!empty($pkginfo['release_date'])) {
            $ret .= "$indent    <date>$pkginfo[release_date]</date>\n";
        }
        if (!empty($pkginfo['release_license'])) {
            $ret .= "$indent    <license>$pkginfo[release_license]</license>\n";
        }
        if (!empty($pkginfo['release_state'])) {
            $ret .= "$indent    <state>$pkginfo[release_state]</state>\n";
        }
        if (!empty($pkginfo['release_notes'])) {
            $ret .= "$indent    <notes>".htmlspecialchars($pkginfo['release_notes'])."</notes>\n";
        }
        if (!empty($pkginfo['release_warnings'])) {
            $ret .= "$indent    <warnings>".htmlspecialchars($pkginfo['release_warnings'])."</warnings>\n";
        }
        if (isset($pkginfo['release_deps']) && sizeof($pkginfo['release_deps']) > 0) {
            $ret .= "$indent    <deps>\n";
            foreach ($pkginfo['release_deps'] as $dep) {
                $ret .= "$indent      <dep type=\"$dep[type]\" rel=\"$dep[rel]\"";
                if (isset($dep['version'])) {
                    $ret .= " version=\"$dep[version]\"";
                }
                if (isset($dep['optional'])) {
                    $ret .= " optional=\"$dep[optional]\"";
                }
                if (isset($dep['name'])) {
                    $ret .= ">$dep[name]</dep>\n";
                } else {
                    $ret .= "/>\n";
                }
            }
            $ret .= "$indent    </deps>\n";
        }
        if (isset($pkginfo['configure_options'])) {
            $ret .= "$indent    <configureoptions>\n";
            foreach ($pkginfo['configure_options'] as $c) {
                $ret .= "$indent      <configureoption name=\"".
                    htmlspecialchars($c['name']) . "\"";
                if (isset($c['default'])) {
                    $ret .= " default=\"" . htmlspecialchars($c['default']) . "\"";
                }
                $ret .= " prompt=\"" . htmlspecialchars($c['prompt']) . "\"";
                $ret .= "/>\n";
            }
            $ret .= "$indent    </configureoptions>\n";
        }
        if (isset($pkginfo['provides'])) {
            foreach ($pkginfo['provides'] as $key => $what) {
                $ret .= "$indent    <provides type=\"$what[type]\" ";
                $ret .= "name=\"$what[name]\" ";
                if (isset($what['extends'])) {
                    $ret .= "extends=\"$what[extends]\" ";
                }
                $ret .= "/>\n";
            }
        }
        if (isset($pkginfo['filelist'])) {
            $ret .= "$indent    <filelist>\n";
            foreach ($pkginfo['filelist'] as $file => $fa) {
                @$ret .= "$indent      <file role=\"$fa[role]\"";
                if (isset($fa['baseinstalldir'])) {
                    $ret .= ' baseinstalldir="' .
                        htmlspecialchars($fa['baseinstalldir']) . '"';
                }
                if (isset($fa['md5sum'])) {
                    $ret .= " md5sum=\"$fa[md5sum]\"";
                }
                if (isset($fa['platform'])) {
                    $ret .= " platform=\"$fa[platform]\"";
                }
                if (!empty($fa['install-as'])) {
                    $ret .= ' install-as="' .
                        htmlspecialchars($fa['install-as']) . '"';
                }
                $ret .= ' name="' . htmlspecialchars($file) . '"';
                if (empty($fa['replacements'])) {
                    $ret .= "/>\n";
                } else {
                    $ret .= ">\n";
                    foreach ($fa['replacements'] as $r) {
                        $ret .= "$indent        <replace";
                        foreach ($r as $k => $v) {
                            $ret .= " $k=\"" . htmlspecialchars($v) .'"';
                        }
                        $ret .= "/>\n";
                    }
                    @$ret .= "$indent      </file>\n";
                }
            }
            $ret .= "$indent    </filelist>\n";
        }
        $ret .= "$indent  </release>\n";
        return $ret;
    }
    // {{{ _unIndent()

    /**
     * Unindent given string (?)
     *
     * @param string $str The string that has to be unindented.
     * @return string
     * @access private
     */
    function _unIndent($str)
    {
        // remove leading newlines
        $str = preg_replace('/^[\r\n]+/', '', $str);
        // find whitespace at the beginning of the first line
        $indent_len = strspn($str, " \t");
        $indent = substr($str, 0, $indent_len);
        $data = '';
        // remove the same amount of whitespace from following lines
        foreach (explode("\n", $str) as $line) {
            if (substr($line, 0, $indent_len) == $indent) {
                $data .= substr($line, $indent_len) . "\n";
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    function toArray()
    {
        if (!$this->_packagefile->validate(PEAR_VALIDATE_NORMAL)) {
            return false;
        }
        return $this->_packagefile->getArray();
    }

    /**
     * Convert a package.xml version 1.0 into version 2.0
     *
     * Note that this does a basic conversion, to allow more advanced
     * features like bundles and multiple releases
     * @return PEAR_PackageFile_v2
     */
    function &toV2()
    {
        $arr = array(
            'name' => array(
                'attribs' => array(
                        'channel' => 'pear',
                    ),
                '_content' => $this->_packagefile->getPackage(),
            )
        );
        if ($extends = $this->_packagefile->getExtends()) {
            $arr['extends'] = $extends;
        }
        $arr['summary'] = $this->_packagefile->getSummary();
        $arr['description'] = $this->_packagefile->getDescription();
        $maintainers = $this->_packagefile->getMaintainers();
        foreach ($maintainers as $maintainer) {
            if ($maintainer['role'] != 'lead') {
                continue;
            }
            unset($maintainer['role']);
            $maintainer['active'] = 'yes';
            $maintainer['user'] = $maintainer['handle'];
            unset($maintainer['handle']);
            $arr['lead'][] = array('attribs' => $maintainer);
        }
        if (count($arr['lead']) == 1) {
            $arr['lead'] = $arr['lead'][0];
        }
        foreach ($maintainers as $maintainer) {
            if ($maintainer['role'] == 'lead') {
                continue;
            }
            $maintainer['active'] = 'yes';
            $maintainer['user'] = $maintainer['handle'];
            unset($maintainer['handle']);
            $arr['maintainer'][] = array('attribs' => $maintainer);
        }
        if (count($arr['maintainer']) == 1) {
            $arr['maintainer'] = $arr['maintainer'][0];
        }
        $arr['date'] = $this->_packagefile->getDate();
        $arr['version'] = array(
                'attribs' =>
                    array('api' => $this->_packagefile->getVersion(),
                          'package' => $this->_packagefile->getVersion()
                         )
            );
        $licensemap =
            array(
                'php license' => 'http://www.php.net/license/3_0.txt',
                'lgpl' => 'http://www.gnu.org/copyleft/lesser.html',
                'bsd' => 'http://www.opensource.org/licenses/bsd-license.php',
                'mit' => 'http://www.opensource.org/licenses/mit-license.php',
                'gpl' => 'http://www.gnu.org/copyleft/gpl.html',
                'apache' => 'http://www.opensource.org/licenses/apache2.0.php'
            );
        if (isset($licensemap[strtolower($this->_packagefile->getLicense())])) {
            $uri = $licensemap[strtolower($this->_packagefile->getLicense())];
        } else {
            $uri = 'http://www.example.com';
        }
        $arr['license'] = array(
            'attribs' => array('uri' => $uri),
            '_content' => $this->_packagefile->getLicense()
            );
        $arr['stability'] = array(
                'attribs' =>
                    array('api' => $this->_packagefile->getState(),
                          'package' => $this->_packagefile->getState()
                         )
            );
        $arr['notes'] = $this->_packagefile->getNotes();
        $arr['filelist'] = $this->_convertFilelist2_0();
        $release = $this->_packagefile->getConfigureOptions() ? 'extsrc' : 'php';
        $arr[$release] = array();
        $this->_convertRelease2_0($arr[$release]);
        if ($cl = $this->_packagefile->getChangelog()) {
            foreach ($cl as $release) {
                $rel = array();
                $rel['version'] = array(
                        'attribs' =>
                            array('api' => $release['version'],
                                  'package' => $release['version']
                                 )
                    );
                $rel['date'] = $release['release_date'];
                if (isset($release['release_license'])) {
                    if (isset($licensemap[strtolower($release['release_license'])])) {
                        $uri = $licensemap[strtolower($release['release_license'])];
                    } else {
                        $uri = 'http://www.example.com';
                    }
                    $rel['license'] = array(
                            'attribs' => array('uri' => $uri),
                            '_content' => $release['release_license']
                        );
                } else {
                    $rel['license'] = $arr['license'];
                }
                $rel['stability'] = array(
                        'attribs' =>
                            array('api' => $release['release_state'],
                                  'package' => $release['release_state']
                                 )
                    );
                $rel['notes'] = $release['release_notes'];
                $arr['changelog']['release'][] = $rel;
            }
        }
        include_once 'PEAR/PackageFile/v2.php';
        $ret = new PEAR_PackageFile_v2;
        $ret->fromArray($arr);
        return $ret;
    }

    function _convertFilelist2_0()
    {
        $ret = array('dir' =>
                    array(
                        'attribs' => array('name' => '/'),
                        'file' => array()
                        )
                    );
        foreach ($this->_packagefile->getFilelist() as $name => $file) {
            $file['name'] = $name;
            if (isset($file['replacements'])) {
                $repl = $file['replacements'];
                unset($file['replacements']);
            } else {
                unset($repl);
            }
            $file = array('attribs' => $file);
            if (isset($repl)) {
                foreach ($repl as $replace ) {
                    $file['tasks:replace'][] = array('attribs' => $replace);
                }
                if (count($repl) == 1) {
                    $file['tasks:replace'] = $file['tasks:replace'][0];
                }
            }
            $ret['dir']['file'][] = $file;
        }
        return $ret;
    }

    function _convertRelease2_0(&$release)
    {
        $release['dependencies'] = array();
        $release['dependencies']['pearinstaller'] =
            array('attribs' =>
                array('min' => '@PEAR-VER@'));
        if ($this->_packagefile->hasDeps()) {
            $deps = array();
            foreach ($this->_packagefile->getDeps() as $dep) {
                // organize deps by dependency type and name
                if (!isset($deps[$dep['type']])) {
                    $deps[$dep['type']] = array();
                }
                if (isset($dep['name'])) {
                    $deps[$dep['type']][$dep['name']][] = $dep;
                } else {
                    $deps[$dep['type']][] = $dep;
                }
            }
            do {
                if (isset($deps['php'])) {
                    $php = array();
                    if (count($deps['php']) > 1) {
                        $php = $this->_processMultipleDeps($deps['php']);
                    } else {
                        $php = $this->_processDep($deps['php'][0]);
                        if (!$php) {
                            break; // poor mans throw
                        }
                    }
                    $release['dependencies']['php'] = $php;
                }
            } while (false);
            do {
                if (isset($deps['pkg'])) {
                    $pkg = array();
                    if (count($deps['pkg']) > 1) {
                        $pkg = $this->_processMultipleDepsName($deps['pkg']);
                    } else {
                        $pkg = $this->_processDep($deps['pkg'][0]);
                        if (!$pkg) {
                            break; // poor mans throw
                        }
                    }
                    $release['dependencies']['package'] = $pkg;
                }
            } while (false);
            do {
                if (isset($deps['ext'])) {
                    $pkg = array();
                    if (count($deps['ext']) > 1) {
                        $pkg = $this->_processMultipleDepsName($deps['ext']);
                    } else {
                        $pkg = $this->_processDep($deps['ext'][0]);
                        if (!$pkg) {
                            break; // poor mans throw
                        }
                    }
                    $release['dependencies']['extension'] = $pkg;
                }
            } while (false);
            // skip sapi - it's not supported so nobody will have used it
            // skip os - it's not supported in 1.0
        }
    }

    function _processDep($dep)
    {
        if ($dep['type'] == 'php') {
            if ($dep['rel'] == 'has') {
                // come on - everyone has php!
                return false;
            }
        }
        $php = array();
        if ($dep['type'] != 'php') {
            $php['attribs']['name'] = $dep['name'];
            if ($dep['type'] == 'pkg') {
                // no way to guess for extensions, so we'll assume they
                // are NOT pecl
                $php['attribs']['channel'] = 'pear';
            }
        }
        if (isset($dep['optional'])) {
            $php['attribs']['optional'] = $dep['optional'];
        }
        switch ($dep['rel']) {
            case 'gt' :
                $php['exclude']['attribs']['version'] = $dep['version'];
            case 'ge' :
                $php['attribs']['min'] = $dep['version'];
            break;
            case 'lt' :
                $php['exclude']['attribs']['version'] = $dep['version'];
            case 'le' :
                $php['attribs']['max'] = $dep['version'];
            break;
            case 'eq' :
                $php['attribs']['max'] = $dep['version'];
                $php['attribs']['min'] = $dep['version'];
            break;
            case 'not' :
                $php['exclude']['attribs']['version'] = $dep['version'];
            break;
        }
        return $php;
    }

    function _processMultipleDeps($deps)
    {
        $test = array();
        foreach ($deps as $dep) {
            $test[] = $this->_processDep($dep);
        }
        $min = array();
        $max = array();
        foreach ($test as $dep) {
            if (!dep) {
                continue;
            }
            if (isset($dep['attribs']['min'])) {
                $min[$dep['attribs']['min']] = count($min);
            }
            if (isset($dep['attribs']['max'])) {
                $max[$dep['attribs']['max']] = count($max);
            }
        }
        if (count($min) > 0) {
            uksort($min, 'version_compare');
        }
        if (count($max) > 0) {
            uksort($max, 'version_compare');
        }
        if (count($min)) {
            // get the highest minimum
            $min = array_pop(array_flip($min));
        } else {
            $min = false;
        }
        if (count($max)) {
            // get the lowest maximum
            $max = array_shift(array_flip($max));
        } else {
            $max = false;
        }
        if ($min) {
            $php['attribs']['min'] = $min;
        }
        if ($max) {
            $php['attribs']['max'] = $max;
        }
        $exclude = array();
        foreach ($test as $dep) {
            if (!isset($dep['exclude'])) {
                continue;
            }
            $exclude[] = $dep['exclude'];
        }
        if (count($exclude)) {
            $php['exclude'] = $exclude;
        }
        return $php;
    }

    function _processMultipleDepsName($deps)
    {
        $test = array();
        foreach ($deps as $name => $dep) {
            foreach ($dep as $d) {
                $tests[$name][] = $this->_processDep($d);
            }
        }
        foreach ($tests as $name => $test) {
            $php = array();
            $min = array();
            $max = array();
            foreach ($test as $dep) {
                if (!$dep) {
                    continue;
                }
                if (isset($dep['attribs']['min'])) {
                    $min[$dep['attribs']['min']] = count($min);
                }
                if (isset($dep['attribs']['max'])) {
                    $max[$dep['attribs']['max']] = count($max);
                }
            }
            if (isset($dep['attribs']['channel'])) {
                $php['attribs']['channel'] = $dep['attribs']['channel'];
            }
            if (isset($dep['attribs']['optional'])) {
                $php['attribs']['optional'] = $dep['attribs']['optional'];
            }
            $php['attribs']['name'] = $name;
            if (count($min) > 0) {
                uksort($min, 'version_compare');
            }
            if (count($max) > 0) {
                uksort($max, 'version_compare');
            }
            if (count($min)) {
                // get the highest minimum
                $min = array_pop(array_flip($min));
            } else {
                $min = false;
            }
            if (count($max)) {
                // get the lowest maximum
                $max = array_shift(array_flip($max));
            } else {
                $max = false;
            }
            if ($min) {
                $php['attribs']['min'] = $min;
            }
            if ($max) {
                $php['attribs']['max'] = $max;
            }
            $exclude = array();
            foreach ($test as $dep) {
                if (!isset($dep['exclude'])) {
                    continue;
                }
                $exclude[] = $dep['exclude'];
            }
            if (count($exclude)) {
                $php['exclude'] = $exclude;
            }
            $ret[] = $php;
        }
        return $ret;
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
}
//set_include_path('C:/devel/pear_with_channels');
//require_once 'PEAR/PackageFile/Parser/v1.php';
//require_once 'PEAR/Registry.php';
//$a = new PEAR_PackageFile_Parser_v1;
//$r = new PEAR_Registry('C:\Program Files\php\pear');
//$a->setRegistry($r);
//$p = &$a->parse(file_get_contents('C:\devel\pear_with_channels\package-PEAR.xml'), PEAR_VALIDATE_NORMAL,
//    'C:\devel\pear_with_channels\package-PEAR.xml');
//$g = &$p->getDefaultGenerator();
//$v2 = &$g->toV2();
//$g = &$v2->getDefaultGenerator();
//echo $g->toXml();
?>