<?php
require_once 'PEAR/PackageFile/v2.php';
class PEAR_PackageFile_PHP4_v2 extends PEAR_PackageFile_v2
{

    /**
     * Parsed package information
     * @var array
     * @access private
     */
    var $_packageInfo;
    
    var $_flatFilelist;
    
    var $_filelist;

    /**
     * @var PEAR_ErrorStack
     */
    var $_stack;
    function PEAR_PackageFile_PHP4_v2()
    {
        $this->_stack = new PEAR_ErrorStack('PEAR_PackageFile_v2', false, null);
    }

    function fromArray($pinfo)
    {
        unset($pinfo['old']);
        $this->_packageInfo = $pinfo;
    }

    function getArray($forReg = false)
    {
        if ($forReg) {
            $this->_packageInfo['old'] = array();
            $this->_packageInfo['old']['package'] = $this->getPackage();
            $this->_packageInfo['old']['summary'] = $this->getSummary();
            $this->_packageInfo['old']['description'] = $this->getDescription();
            $this->_packageInfo['old']['release_version'] = $this->getVersion();
            $this->_packageInfo['old']['release_date'] = $this->getDate();
            $this->_packageInfo['old']['release_state'] = $this->getState();
            $this->_packageInfo['old']['release_license'] = $this->getLicense();
            $this->_packageInfo['old']['release_notes'] = $this->getNotes();
            $this->_packageInfo['old']['release_deps'] = $this->getDeps();
            $this->_packageInfo['old']['maintainers'] = $this->getMaintainers();
        }
        return $this->_packageInfo;
    }

    function getChannel()
    {
        if (isset($this->_packageInfo['name']['attribs']['channel'])) {
            return $this->_packageInfo['name']['attribs']['channel'];
        }
        return 'pear.php.net';
    }

    function getName()
    {
        return $this->getPackage();
    }

    function getPackage()
    {
        if (isset($this->_packageInfo['name']['_content'])) {
            return $this->_packageInfo['name']['_content'];
        }
        return false;
    }

    /**
     * @param package|api version category to return
     */
    function getVersion($key = 'package')
    {
        if (isset($this->_packageInfo['version']['attribs'][$key])) {
            return $this->_packageInfo['version']['attribs'][$key];
        }
        return false;
    }

    function getMaintainers($raw = false)
    {
        
        if (isset($this->_packageInfo['leads']) ||
              isset($this->_packageInfo['maintainers'])) {
            if ($raw) {
                $ret = (isset($this->_packageInfo['leads'])) ?
                    array('leads' => $this->_packageInfo['leads']) : array();
                (isset($this->_packageInfo['maintainers'])) ?
                    $ret['maintainers'] = $this->_packageInfo['maintainers'] :null;
                return $ret;
            } else {
                $ret = array();
                if (isset($this->_packageInfo['leads'])) {
                    foreach ($this->_packageInfo['leads'] as $lead) {
                        $s = $lead['attribs'];
                        $s['role'] = 'lead';
                        $ret[] = $s;
                    }
                }
                if (isset($this->_packageInfo['maintainers'])) {
                    foreach ($this->_packageInfo['maintainers'] as $maintainer) {
                        $s = $maintainer['attribs'];
                        $ret[] = $s;
                    }
                }
            }
        }
        return false;
    }

    function getStability()
    {
        if (isset($this->_packageInfo['stability'])) {
            return $this->_packageInfo['stability'];
        }
        return false;
    }

    function getState($key = 'package')
    {
        if (isset($this->_packageInfo['stability']['attribs'][$key])) {
            return $this->_packageInfo['stability']['attribs'][$key];
        }
        return false;
    }

    function getDate()
    {
        if (isset($this->_packageInfo['date'])) {
            return $this->_packageInfo['date'];
        }
        return false;
    }

    function getLicense($raw = false)
    {
        if (isset($this->_packageInfo['license'])) {
            if ($raw) {
                return $this->_packageInfo['license'];
            }
            return $this->_packageInfo['license']['_content'];
        }
        return false;
    }

    function getSummary()
    {
        if (isset($this->_packageInfo['summary'])) {
            return $this->_packageInfo['summary'];
        }
        return false;
    }

    function getDescription()
    {
        if (isset($this->_packageInfo['description'])) {
            return $this->_packageInfo['description'];
        }
        return false;
    }

    function getNotes()
    {
        if (isset($this->_packageInfo['release_notes'])) {
            return $this->_packageInfo['release_notes'];
        }
        return false;
    }

    /**
     * @todo handle <exclude>
     */
    function getDeps($raw = false)
    {
        $type = $this->getPackageType();
        if (!$type) {
            return false;
        }
        if (isset($this->_packageInfo[$type]['dependencies'])) {
            if ($raw) {
                return $this->_packageInfo[$type]['dependencies'];
            }
            $ret = array();
            $map = array(
                'php' => 'php',
                'package' => 'pkg',
                'extension' => 'ext',
                'sapi' => 'sapi',
                'os' => 'os'
                );
            foreach ($this->_packageInfo[$type]['dependendencies'] as $dtype => $deps) {
                foreach ($deps as $dep) {
                    $s = array('type' => $map[$dtype],
                        'channel' => $t = @$dep['attribs']['channel'] ? $t : 'pear.php.net');
                    if (!isset($dep['attribs']['min']) &&
                          !isset($dep['attribs']['max'])) {
                        $s['rel'] = 'has';
                    } elseif (isset($dep['attribs']['min']) &&
                          isset($dep['attribs']['max'])) {
                        $s['rel'] = 'ge';
                        $s1 = $s;
                        $s['version'] = $dep['attribs']['min'];
                        $s1['version'] = $dep['attribs']['max'];
                        if ($dtype != 'php') {
                            $s['name'] = $dep['_content'];
                            $s1['name'] = $dep['_content'];
                        }
                        $s['optional'] = $dep['attribs']['optional'];
                        $s1['optional'] = $dep['attribs']['optional'];
                        $ret[] = $s1;
                    } elseif (isset($dep['attribs']['min'])) {
                        $s['rel'] = 'ge';
                        $s['version'] = $dep['attribs']['min'];
                        $s['optional'] = $dep['attribs']['optional'];
                        if ($dtype != 'php') {
                            $s['name'] = $dep['_content'];
                        }
                    } elseif (isset($dep['attribs']['max'])) {
                        $s['rel'] = 'le';
                        $s['version'] = $dep['attribs']['min'];
                        $s['optional'] = $dep['attribs']['optional'];
                        if ($dtype != 'php') {
                            $s['name'] = $dep['_content'];
                        }
                    }
                    $ret[] = $s;
                }
            }
            return $ret;
        }
        return false;
    }

    function getPackageType()
    {
        if (isset($this->_packageInfo['php'])) {
            return 'php';
        }
        if (isset($this->_packageInfo['extsrc'])) {
            return 'extsrc';
        }
        if (isset($this->_packageInfo['extbin'])) {
            return 'extbin';
        }
        return false;
    }

    function hasDeps()
    {
        $type = $this->getPackageType();
        if (!$type) {
            return false;
        }
        return isset($this->_packageInfo[$type]['dependencies']);
    }

    function getPackagexmlVersion()
    {
        return '2.0';
    }

    function validate()
    {
        $this->_stack->getErrors(true);
        if (!isset($this->_packageInfo) || !is_array($this->_packageInfo)) {
            return false;
        }

        $keys = array_keys($this->_packageInfo);
        $structure =
        array(
            'name->channel',
            'summary',
            'description',
            '+lead->user->email->name->active',
            '*maintainer->role->user->email->name->active',
            'date',
            'version->api->package',
            'license->?uri->?filesource',
            'stability->api->package',
            'notes',
            '*bundle->name', //special validation needed
            'filelist', //special validation needed
            '+php|+extsrc|+extbin' //special validation needed
        );
        unset($keys[0]);
        foreach ($keys as $key) {
            $tag = $this->_packageInfo[$key];
            $test = $this->_processStructure(array_shift($structure));
            if (isset($test['choices'])) {
                foreach ($test['choices'] as $choice) {
                    if ($key == $choice['tag']) {
                        if ($this->_processAttribs($choice, $tag, '<package>')) {
                            continue 2;
                        }
                        return false;
                    }
                }
                $this->_invalidTagOrder($test['choices'], $key);
                return false;
            } else {
                if ($key != $test['tag']) {
                    if (isset($test['multiple']) && $test['multiple'] != '*') {
                        $this->_invalidTagOrder($test['tag'], $key);
                        return false;
                    }
                    continue;
                }
                if ($this->_processAttribs($test, $tag, '<package>')) {
                    continue;
                }
                return false;
            }
        }
        if (!isset($this->_packageInfo['name']['_content'])) {
            $this->_tagCannotBeEmpty('name');
        }
        if (is_array($this->_packageInfo['license']) &&
              !isset($this->_packageInfo['license']['_content'])) {
            $this->_tagCannotBeEmpty('license');
        }
        if (isset($this->_packageInfo['bundle'])) {
            $this->_validateBundle();
        }
        if (!isset($this->_packageInfo['filelist']['dir'])) {
            $this->_filelistMustContainDir();
            return false;
        }
        if (isset($this->_packageInfo['filelist']['file'])) {
            $this->_filelistCannotContainFile();
            return false;
        }
        $this->_validateFilelist();
        $this->_validateRelease();
        return $this->_stack->hasErrors();
    }

    function _processAttribs($choice, $tag, $context)
    {
        if (isset($choice['attribs'])) {
            if (isset($choice['multiple'])) {
                $tags = $tag;
                foreach ($tags as $i => $tag) {
                    if (!is_int($i)) {
                        unset($choice['multiple']);
                        return $this->_processAttribs($choice, $tags, $context);
                    }
                    if (!isset($tag['attribs'])) {
                        return $this->_tagHasNoAttribs($choice['tag'],
                            $context);
                    }
                    foreach ($choice['attribs'] as $attrib) {
                        if ($attrib{0} != '?') {
                            if (!isset($tag['attribs'][$attrib])) {
                                return $this->_tagMissingAttribute($choice['tag'],
                                    $attrib, $context);
                            }
                        }
                    }
                }
            } else {
                if (!isset($tag['attribs'])) {
                    return $this->_tagHasNoAttribs($choice['tag'],
                        $context);
                }
                foreach ($choice['attribs'] as $attrib) {
                    if ($attrib{0} != '?') {
                        if (!isset($tag['attribs'][$attrib])) {
                            return $this->_tagMissingAttribute($choice['tag'], $attrib,
                                $context);
                        }
                    }
                }
            }
        }
        return true;
    }

    function _processStructure($key)
    {
        $ret = array();
        if (count($pieces = explode('|', $key)) > 1) {
            foreach ($pieces as $piece) {
                $ret['choices'][] = $this->_processStructure($piece);
            }
            return $ret;
        }
        $multi = $key{0};
        if ($multi == '+' || $multi == '*') {
            $ret['multiple'] = $key{0};
            $key = substr($key, 1);
        }
        if (count($attrs = explode('->', $key)) > 1) {
            $ret['tag'] = array_shift($attrs);
            $ret['attribs'] = $attrs;
        } else {
            $ret['tag'] = $key;
        }
        return $ret;
    }

    function _validateBundle()
    {
    }

    function _validateFilelist($list = false, $allowignorefile = false)
    {
        if (!$list) {
            $list = $this->_packageInfo['filelist'];
        }
        if (isset($list['file'])) {
            if (isset($list['file']['attribs'])) {
                // single file
                if (!isset($list['file']['attribs'])) {
                    return $this->_tagHasNoAttribs('file',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                if (!isset($list['file']['attribs']['name'])) {
                    return $this->_tagMissingAttribute('file', 'name',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                if (!isset($list['file']['attribs']['role'])) {
                    return $this->_tagMissingAttribute('file', 'role',
                        '<dir name="' . $list['attribs']['name'] . '"><file name="' .
                        $list['file']['attribs']['name'] . '">');
                }
                if (!$allowignorefile && !$this->_validateRole($list['file']['attribs']['role'])) {
                    return $this->_invalidFileRole($list['file']['attribs']['name'],
                        $list['attribs']['name']);
                }
            } else {
                if (!is_array($list['file'])) {
                    return $this->_tagHasNoAttribs('file',
                        '<dir name="' . $list['attribs']['name'] . '"><file>' .
                        $list['file'] . '</file>');
                }
                foreach ($list['file'] as $i => $file) {
                    if (!is_int($i)) {
                        return $this->_tagHasNoAttribs('file',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs'])) {
                        return $this->_tagHasNoAttribs('file',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs']['name'])) {
                        return $this->_tagMissingAttribute('file', 'name',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!$allowignorefile && !$this->_validateRole($file['attribs']['role'])) {
                        return $this->_invalidFileRole($list['file']['attribs']['name'],
                            $list['attribs']['name']);
                    }
                }
            }
        }
        if (isset($list['ignorefile'])) {
            if (!$allowignorefile) {
                $this->_ignorefileNotAllowed();
            }
            if (isset($list['ignorefile']['attribs'])) {
                // single file
                if (!isset($list['ignorefile']['attribs'])) {
                    return $this->_tagHasNoAttribs('ignorefile',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                if (!isset($list['ignorefile']['attribs']['name'])) {
                    return $this->_tagMissingAttribute('ignorefile', 'name',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
            } else {
                if (!is_array($list['ignorefile'])) {
                    return $this->_tagHasNoAttribs('ignorefile',
                        '<dir name="' . $list['attribs']['name'] . '">');
                }
                foreach ($list['ignorefile'] as $i => $file) {
                    if (!is_int($i)) {
                        return $this->_tagHasNoAttribs('ignorefile',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs'])) {
                        return $this->_tagHasNoAttribs('ignorefile',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                    if (!isset($file['attribs']['name'])) {
                        return $this->_tagMissingAttribute('ignorefile', 'name',
                            '<dir name="' . $list['attribs']['name'] . '">');
                    }
                }
            }
        }
        if (isset($list['dir'])) {
            if ($this->_processDir($list['dir'])) {
                foreach ($list['dir'] as $dir) {
                    $this->_validateFilelist($dir);
                    return;
                }
            }
            return $this->_validateFilelist($list['dir'], $allowignorefile);
        }
    }

    function _processDir($dirs)
    {
        if (!isset($dirs['attribs'])) {
            foreach ($dirs as $i => $dir) {
                if (!is_int($i)) {
                    return $this->_tagHasNoAttribs('dir',
                        'unknown');
                }
            }
            return true;
        } else {
            if (!isset($dirs['attribs']['name'])) {
                return $this->_tagMissingAttribute('dir', 'name',
                    'unknown');
            }
        }
    }

    function _validateRelease()
    {
        if (isset($this->_packageInfo['php'])) {
            $release = 'php';
        }
        if (isset($this->_packageInfo['extsrc'])) {
            $release = 'extsrc';
        }
        if (isset($this->_packageInfo['extbin'])) {
            $release = 'extbin';
        }
        if (isset($this->_packageInfo[$release][0])) {
            foreach ($this->_packageInfo[$release] as $rel) {
                $this->_validateFilelist($rel['filelist'], true);
            }
        } else {
            $this->_validateFilelist($this->_packageInfo[$release]['filelist'], true);
        }
    }

    /**
     * This is here to allow role extension through plugins
     * @param string
     */
    function _validateRole($role)
    {
        include_once 'PEAR/Common.php';
        return in_array($role, PEAR_Common::getFileRoles());
    }

    function _detectFilelist()
    {
        $this->_flatFilelist = isset($this->_filelist);
    }

    function _ignorefileNotAllowed()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            '<ignorefile> is not allowed inside global <filelist>, only inside <php>/<extsrc>/<extbin>');
    }

    function _tagMissingAttribute($tag, $attr, $context)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $tag,
            'attribute' => $attr, 'context' => $context),
            'tag <%tag%> in context "%context%" has no attribute "%attr%"');
    }

    function _tagHasNoAttribs($tag, $context)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $tag,
            'context' => $context),
            'tag <%tag%> has no attributes in context "%context%"');
    }

    function _invalidInternalStructure()
    {
        $this->_stack->push(__FUNCTION__, 'exception', array(),
            'internal array was not generated by compatible parser, or extreme parser error, cannot continue');
    }

    function _invalidFileRole($file, $dir, $role)
    {
        $this->_stack->push(__FUNCTION__, 'error', array(
            'file' => $file, 'dir' => $dir, 'role' => $role,
            'roles' => PEAR_Common::getFileRoles()),
            'File "%file%" in directory "%dir%" has invalid role "%role%", should be one of %roles%');
    }

    function _filelistCannotContainFile()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            '<filelist> can only contain <dir>, contains <file>.  Use <dir name="/"> as the first dir element');
    }

    function _filelistMustContainDir()
    {
        $this->_stack->push(__FUNCTION__, 'error', array(),
            '<filelist> must contain <dir>.  Use <dir name="/"> as the first dir element');
    }

    function _tagCannotBeEmpty($tag)
    {
        $this->_stack->push(__FUNCTION__, 'error', array('tag' => $tag),
            '<%tag%> cannot be empty (<%tag%/>)');
    }
}
?>