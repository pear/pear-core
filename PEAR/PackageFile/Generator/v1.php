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
?>