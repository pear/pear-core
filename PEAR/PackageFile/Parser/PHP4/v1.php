<?php
/**
 * Parser for package.xml version 2.0
 */
class PEAR_PackageFile_Parser_PHP4_v1
{
    /**
     * BC hack to allow PEAR_Common::infoFromString() to sort of
     * work with the version 2.0 format
     * @param PEAR_PackageFile_v2
     */
    function fromV2($packagefile)
    {
    }

    /**
     * @param string contents of package.xml file, version 1.0
     * @return bool success of parsing
     */
    function parse($data, &$pf)
    {
        require_once('PEAR/Dependency.php');
        if (PEAR_Dependency::checkExtension($error, 'xml')) {
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_NO_XML_EXT, 'exception', array('error' => $error));
            return $this->_isValid = false;
        }
        $xp = @xml_parser_create();
        if (!$xp) {
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER, 'exception');
            return $this->_isValid = false;
        }
        xml_set_object($xp, $this);
        xml_set_element_handler($xp, '_element_start_1_0', '_element_end_1_0');
        xml_set_character_data_handler($xp, '_pkginfo_cdata_1_0');
        xml_parser_set_option($xp, XML_OPTION_CASE_FOLDING, false);

        $this->element_stack = array();
        $this->_packageInfo = array('provides' => array());
        $this->current_element = false;
        unset($this->dir_install);
        $this->_packageInfo['filelist'] = array();
        $this->filelist =& $this->_packageInfo['filelist'];
        $this->dir_names = array();
        $this->in_changelog = false;
        $this->d_i = 0;
        $this->cdata = '';
        $this->_isValid = true;

        if (!xml_parse($xp, $data, 1)) {
            $code = xml_get_error_code($xp);
            $msg = sprintf("XML error: %s at line %d",
                           $str = xml_error_string($code),
                           $line = xml_get_current_line_number($xp));
            xml_parser_free($xp);
            $this->_stack->push(PEAR_PACKAGEFILE_ERROR_PARSER_ERROR, 'error',
                array('error' => $msg, 'code' => $code, 'message' => $str, 'line' => $line));
            return false;
        }

        xml_parser_free($xp);

        foreach ($this->_packageInfo as $k => $v) {
            if (!is_array($v)) {
                $this->_packageInfo[$k] = trim($v);
            }
        }
        $this->validate();
        if ($this->_isValid) {
            $pf->fromArray($this->_packageInfo);
        }
        return $this->_isValid;
    }

    // Support for package DTD v1.0:
    // {{{ _element_start_1_0()

    /**
     * XML parser callback for ending elements.  Used for version 1.0
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _element_start_1_0($xp, $name, $attribs)
    {
        array_push($this->element_stack, $name);
        $this->current_element = $name;
        $spos = sizeof($this->element_stack) - 2;
        $this->prev_element = ($spos >= 0) ? $this->element_stack[$spos] : '';
        $this->current_attributes = $attribs;
        $this->cdata = '';
        switch ($name) {
            case 'dir':
                if ($this->in_changelog) {
                    break;
                }
                if ($attribs['name'] != '/') {
                    $this->dir_names[] = $attribs['name'];
                }
                if (isset($attribs['baseinstalldir'])) {
                    $this->dir_install = $attribs['baseinstalldir'];
                }
                if (isset($attribs['role'])) {
                    $this->dir_role = $attribs['role'];
                }
                break;
            case 'file':
                if ($this->in_changelog) {
                    break;
                }
                if (isset($attribs['name'])) {
                    $path = '';
                    if (count($this->dir_names)) {
                        foreach ($this->dir_names as $dir) {
                            $path .= $dir . DIRECTORY_SEPARATOR;
                        }
                    }
                    $path .= $attribs['name'];
                    unset($attribs['name']);
                    $this->current_path = $path;
                    $this->filelist[$path] = $attribs;
                    // Set the baseinstalldir only if the file don't have this attrib
                    if (!isset($this->filelist[$path]['baseinstalldir']) &&
                        isset($this->dir_install))
                    {
                        $this->filelist[$path]['baseinstalldir'] = $this->dir_install;
                    }
                    // Set the Role
                    if (!isset($this->filelist[$path]['role']) && isset($this->dir_role)) {
                        $this->filelist[$path]['role'] = $this->dir_role;
                    }
                }
                break;
            case 'replace':
                if (!$this->in_changelog) {
                    $this->filelist[$this->current_path]['replacements'][] = $attribs;
                }
                break;
            case 'maintainers':
                $this->_packageInfo['maintainers'] = array();
                $this->m_i = 0; // maintainers array index
                break;
            case 'maintainer':
                // compatibility check
                if (!isset($this->_packageInfo['maintainers'])) {
                    $this->_packageInfo['maintainers'] = array();
                    $this->m_i = 0;
                }
                $this->_packageInfo['maintainers'][$this->m_i] = array();
                $this->current_maintainer =& $this->_packageInfo['maintainers'][$this->m_i];
                break;
            case 'changelog':
                $this->_packageInfo['changelog'] = array();
                $this->c_i = 0; // changelog array index
                $this->in_changelog = true;
                break;
            case 'release':
                if ($this->in_changelog) {
                    $this->_packageInfo['changelog'][$this->c_i] = array();
                    $this->current_release = &$this->_packageInfo['changelog'][$this->c_i];
                } else {
                    $this->current_release = &$this->_packageInfo;
                }
                break;
            case 'deps':
                if (!$this->in_changelog) {
                    $this->_packageInfo['release_deps'] = array();
                }
                break;
            case 'dep':
                // dependencies array index
                if (!$this->in_changelog) {
                    $this->d_i++;
                    isset($attribs['type']) ? ($attribs['type'] = strtolower($attribs['type'])) : false;
                    $this->_packageInfo['release_deps'][$this->d_i] = $attribs;
                }
                break;
            case 'configureoptions':
                if (!$this->in_changelog) {
                    $this->_packageInfo['configure_options'] = array();
                }
                break;
            case 'configureoption':
                if (!$this->in_changelog) {
                    $this->_packageInfo['configure_options'][] = $attribs;
                }
                break;
            case 'provides':
                if (empty($attribs['type']) || empty($attribs['name'])) {
                    break;
                }
                $attribs['explicit'] = true;
                $this->_packageInfo['provides']["$attribs[type];$attribs[name]"] = $attribs;
                break;
            case 'package' :
                if (isset($attribs['version'])) {
                    $this->_packageInfo['_packagexml_version'] = $attribs['version'];
                } else {
                    $this->_packageInfo['_packagexml_version'] = '1.0';
                }
                break;
        }
    }

    // }}}
    // {{{ _element_end_1_0()

    /**
     * XML parser callback for ending elements.  Used for version 1.0
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  name of ending element
     *
     * @return void
     *
     * @access private
     */
    function _element_end_1_0($xp, $name)
    {
        $data = trim($this->cdata);
        switch ($name) {
            case 'name':
                switch ($this->prev_element) {
                    case 'package':
                        $this->_packageInfo['package'] = $data;
                        if ($this->_packageInfo['_packagexml_version'] == '1.0') {
                            if (!isset($this->_packageInfo['package'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
//                            } elseif (!PEAR_Common::validPackageName($this->_packageInfo['package'])) {
//                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
//                                    array('name' => $this->_packageInfo['package']));
                            }
                        }
                        break;
                    case 'maintainer':
                        $this->current_maintainer['name'] = $data;
                        break;
                }
                break;
            case 'summary':
                if (empty($data)) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_SUMMARY);
                } elseif (strpos($data, "\n") !== false) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY,
                        array('summary' => $data));
                }
                $this->_packageInfo['summary'] = $data;
                break;
            case 'description':
                $data = $this->_unIndent($this->cdata);
                if (empty($data)) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION);
                }
                $this->_packageInfo['description'] = $data;
                break;
            case 'user':
                $this->current_maintainer['handle'] = $data;
                break;
            case 'email':
                $this->current_maintainer['email'] = $data;
                break;
            case 'role':
                $this->current_maintainer['role'] = $data;
                break;
            case 'version':
                //$data = ereg_replace ('[^a-zA-Z0-9._\-]', '_', $data);
                if ($this->in_changelog) {
                    $this->current_release['version'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_VERSION);
                    } elseif (!PEAR_Common::validPackageVersion($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_VERSION,
                            array('version' => $data));
                    }
                    $this->_packageInfo['version'] = $data;
                }
                break;
            case 'date':
                if ($this->in_changelog) {
                    $this->current_release['release_date'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DATE);
                    } elseif (!preg_match('/^\d{4}-\d\d-\d\d$/', $data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DATE,
                            array('date' => $data));
                    }
                    $this->_packageInfo['release_date'] = $data;
                }
                break;
            case 'notes':
                // try to "de-indent" release notes in case someone
                // has been over-indenting their xml ;-)
                $data = $this->_unIndent($this->cdata);
                if ($this->in_changelog) {
                    $this->current_release['release_notes'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NOTES);
                    }
                    $this->_packageInfo['release_notes'] = $data;
                }
                break;
            case 'warnings':
                if ($this->in_changelog) {
                    $this->current_release['release_warnings'] = $data;
                } else {
                    $this->_packageInfo['release_warnings'] = $data;
                }
                break;
            case 'state':
                if ($this->in_changelog) {
                    $this->current_release['release_state'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_STATE);
                    } elseif (!in_array($data, PEAR_Common::getReleaseStates())) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_STATE,
                            array('state' => $data,
                                  'states' => PEAR_Common::getReleaseStates()));
                    }
                    $this->_packageInfo['release_state'] = $data;
                }
                break;
            case 'license':
                if ($this->in_changelog) {
                    $this->current_release['release_license'] = $data;
                } else {
                    if (empty($data)) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_LICENSE);
                    }
                    $this->_packageInfo['release_license'] = $data;
                }
                break;
            case 'dep':
                if ($data && !$this->in_changelog) {
                    $this->_packageInfo['release_deps'][$this->d_i]['name'] = $data;
                }
                break;
            case 'dir':
                if ($this->in_changelog) {
                    break;
                }
                array_pop($this->dir_names);
                break;
            case 'file':
                if ($this->in_changelog) {
                    break;
                }
                if ($data) {
                    $path = '';
                    if (count($this->dir_names)) {
                        foreach ($this->dir_names as $dir) {
                            $path .= $dir . DIRECTORY_SEPARATOR;
                        }
                    }
                    $path .= $data;
                    $this->filelist[$path] = $this->current_attributes;
                    // Set the baseinstalldir only if the file don't have this attrib
                    if (!isset($this->filelist[$path]['baseinstalldir']) &&
                        isset($this->dir_install))
                    {
                        $this->filelist[$path]['baseinstalldir'] = $this->dir_install;
                    }
                    // Set the Role
                    if (!isset($this->filelist[$path]['role']) && isset($this->dir_role)) {
                        $this->filelist[$path]['role'] = $this->dir_role;
                    }
                }
                break;
            case 'maintainer':
                if (empty($this->_packageInfo['maintainers'][$this->m_i]['role'])) {
                    $this->_packageInfo['maintainers'][$this->m_i]['role'] = 'lead';
                }
                $this->m_i++;
                break;
            case 'release':
                if ($this->in_changelog) {
                    $this->c_i++;
                }
                break;
            case 'changelog':
                $this->in_changelog = false;
                break;
        }
        array_pop($this->element_stack);
        $spos = sizeof($this->element_stack) - 1;
        $this->current_element = ($spos > 0) ? $this->element_stack[$spos] : '';
        $this->cdata = '';
    }

    // }}}
    // {{{ _pkginfo_cdata_1_0()

    /**
     * XML parser callback for character data.  Used for version 1.0
     * packages.
     *
     * @param resource  $xp    XML parser resource
     * @param string    $name  character data
     *
     * @return void
     *
     * @access private
     */
    function _pkginfo_cdata_1_0($xp, $data)
    {
        if (isset($this->cdata)) {
            $this->cdata .= $data;
        }
    }

    // }}}


    /**
     * Validate XML package definition file.
     *
     * @access public
     * @return boolean
     */
    function validate()
    {
        $this->_isValid = true;
        $info = $this->_packageInfo;
        $errors = array();
        $warnings = array();
        $channel = isset($info['channel']) ? $info['channel'] : 'pear';
        $chan = isset($this->_registry) ? $this->_registry->getChannel($channel) : false;
        if (!$chan) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL,
                array('channel' => $channel));
            if (!isset($info['package'])) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
            } elseif (!PEAR_Common::validPackageName($info['package'])) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                    array('name' => $info['package']));
            }
        } else {
            if (!empty($info['package'])) {
                if (!$chan->validPackageName($info['package'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                        array('name' => $info['package']));
                }
            } else {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
            }
        }
        $this->_packageName = $pn = $info['package'];

        if (empty($info['summary'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_SUMMARY);
        } elseif (strpos(trim($info['summary']), "\n") !== false) {
            $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY,
                array('summary' => $info['summary']));
        }
        if (empty($info['description'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION);
        }
        if (empty($info['release_license'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_LICENSE);
        }
        if (empty($info['version'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_VERSION);
        } elseif (!PEAR_Common::validPackageVersion($info['version'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_VERSION,
                array('version' => $info['version']));
        }
        if (empty($info['release_state'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_STATE);
        } elseif (!in_array($info['release_state'], PEAR_Common::getReleaseStates())) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_STATE,
                array('state' => $info['release_state'],
                      'states' => PEAR_Common::getReleaseStates()));
        }
        if (empty($info['release_date'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DATE);
        } elseif (!preg_match('/^\d{4}-\d\d-\d\d$/', $info['release_date'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DATE,
                array('date' => $info['release_date']));
        }
        if (empty($info['release_notes'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NOTES);
        }
        if (empty($info['maintainers'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTAINERS);
        } else {
            $i = 1;
            foreach ($info['maintainers'] as $m) {
                if (empty($m['handle'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE,
                        array('index' => $i));
                }
                if (empty($m['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE,
                        array('index' => $i, 'roles' => PEAR_Common::getUserRoles()));
                } elseif (!in_array($m['role'], PEAR_Common::getUserRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE,
                        array('index' => $i, 'role' => $m['role'], 'roles' =>
                            PEAR_Common::getUserRoles()));
                }
                if (empty($m['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME,
                        array('index' => $i));
                }
                if (empty($m['email'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (!empty($info['deps'])) {
            $i = 1;
            foreach ($info['deps'] as $d) {
                if (empty($d['type'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes()));
                } elseif (!in_array($d['type'], PEAR_Common::getDependencyTypes())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyTypes(),
                              'type' => $d['type']));
                }
                if (empty($d['rel'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPREL,
                        array('index' => $i, 'rels' => PEAR_Common::getDependencyRelations()));
                } elseif (!in_array($d['rel'], PEAR_Common::getDependencyRelations())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL,
                        array('index' => $i, 'types' => PEAR_Common::getDependencyRelations(),
                              'type' => $d['rel']));
                }
                if (!empty($d['optional'])) {
                    if (!in_array($d['optional'], array('yes', 'no'))) {
                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL,
                            array('index' => $i, 'opt' => $d['optional']));
                    }
                }
                if ($d['rel'] != 'has' && empty($d['version'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION,
                        array('index' => $i));
                } elseif ($d['rel'] == 'has' && !empty($d['version'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED,
                        array('index' => $i, 'version' => $d['version']));
                }
                if ($d['type'] == 'php' && !empty($d['name'])) {
                    $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED,
                        array('index' => $i, 'name' => $d['name']));
                } elseif ($d['type'] != 'php' && empty($d['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_DEPNAME,
                        array('index' => $i));
                }
                if (isset($this->_registry)) {
                    if (isset($d['channel'])) {
                        if (!$this->_registry->channelExists($d['channel'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL,
                                array('index' => $i, 'channel' => $d['channel']));
                        } else {
                            if ($d['type'] == 'pkg' && !empty($d['name'])) {
                                $channel = $this->_registry->getChannel($d['channel']);
                                if ($channel) {
                                    if (!$channel->validPackageName($d['name'])) {
                                        $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                            array('index' => $i, 'name' => $d['name']));
                                    }
                                }
                            }
                        }
                    } else {
                        if ($d['type'] == 'pkg' && !empty($d['name'])) {
                            if (!PEAR_Common::validPackageName($d['name'])) {
                                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                    array('index' => $i, 'name' => $d['name']));
                            }
                        }
                    }
                } else {
                    if ($d['type'] == 'pkg' && !empty($d['name'])) {
                        if (!PEAR_Common::validPackageName($d['name'])) {
                            $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME,
                                array('index' => $i, 'name' => $d['name']));
                        }
                    }
                }
                $i++;
            }
        }
        if (!empty($info['configure_options'])) {
            $i = 1;
            foreach ($info['configure_options'] as $c) {
                if (empty($c['name'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFNAME,
                        array('index' => $i));
                }
                if (empty($c['prompt'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT,
                        array('index' => $i));
                }
                $i++;
            }
        }
        if (empty($info['filelist'])) {
            $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILES);
            $errors[] = 'no files';
        } else {
            foreach ($info['filelist'] as $file => $fa) {
                if (empty($fa['role'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_FILEROLE,
                        array('file' => $file));
                    continue;
                } elseif (!in_array($fa['role'], PEAR_Common::getFileRoles())) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE,
                        array('file' => $file, 'role' => $fa['role'], 'roles' => PEAR_Common::getFileRoles()));
                }
            }
        }
        return $this->_isValid;
    }

    function analyzePhpFiles($dir_prefix)
    {
        if (!$this->_isValid) {
            return false;
        }
        $info = $this->_packageInfo;
        foreach ($info['filelist'] as $file => $fa) {
            if ($fa['role'] == 'php' && $dir_prefix) {
                PEAR_Common::log(1, "Analyzing $file");
                $srcinfo = $this->_analyzeSourceCode($dir_prefix . DIRECTORY_SEPARATOR . $file);
                if ($srcinfo) {
                    $this->_buildProvidesArray($srcinfo);
                }
            }
        }
        $this->_packageName = $pn = $info['package'];
        $pnl = strlen($pn);
        foreach ((array)$this->_packageInfo['provides'] as $key => $what) {
            if (isset($what['explicit'])) {
                // skip conformance checks if the provides entry is
                // specified in the package.xml file
                continue;
            }
            extract($what);
            if ($type == 'class') {
                if (!strncasecmp($name, $pn, $pnl)) {
                    continue;
                }
                $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX,
                    array('file' => $file, 'type' => $type, 'name' => $name, 'package' => $pn));
                $warnings[] = "in $file: class \"$name\" not prefixed with package name \"$pn\"";
            } elseif ($type == 'function') {
                if (strstr($name, '::') || !strncasecmp($name, $pn, $pnl)) {
                    continue;
                }
                $this->_validateWarning(PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX,
                    array('file' => $file, 'type' => $type, 'name' => $name, 'package' => $pn));
                $warnings[] = "in $file: function \"$name\" not prefixed with package name \"$pn\"";
            }
        }
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
                case '{': $brace_level++; continue 2;
                case '}':
                    $brace_level--;
                    if ($current_class_level == $brace_level) {
                        $current_class = '';
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