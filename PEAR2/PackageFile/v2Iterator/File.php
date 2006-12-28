<?php
/**
 * All file iterators for package.xml 2.0
 * @package PEAR2
 */

/**
 * Traverse the complete <contents> tag, one <dir> at a time
 */
class PEAR2_PackageFile_v2Iterator_File extends RecursiveIteratorIterator
{
    function next()
    {
        parent::next();
        $x = $this->current();
        if (isset($x[0])) {
            parent::next();
            $x = $this->current();
        }
    }
}

/**
 * Store the path to the current file recursively
 * 
 * Information can be accessed in three ways:
 * 
 * - $file['attribs'] as an array directly
 * - $file->name      as object member, to access attributes
 * - $file->tasks     as pseudo-object, to access each task
 */
class PEAR2_PackageFile_v2Iterator_FileTag extends ArrayObject
{
    public $dir;
    /**
     * @var PEAR2_PackageFile_v2
     */
    private $_packagefile;
    function __construct($a, $t, $parent)
    {
        $this->_packagefile = $parent;
        parent::__construct($a);
        $this->dir = $t;
        if ($this->dir && $this->dir != '/') $this->dir .= '/';
    }

    /**
     * Hide the install-as attribute (it is merged into the "name" attribute)
     *
     * @param string $offset
     * @return mixed
     */
    function offsetGet($offset)
    {
        if ($offset == 'attribs') {
            $ret = parent::offsetGet('attribs');
            if (isset($ret['install-as'])) {
                unset($ret['install-as']);
            }
            return $ret;
        }
        if ($offset == 'install-as') {
            $ret = parent::offsetGet('attribs');
            return $ret['install-as'];
        }
    }

    function __get($var)
    {
        if ($var == 'name') {
            if (isset($this['install-as'])) {
                return $this['install-as'];
            }
            return $this->dir . $this['attribs']['name'];
        }
        if ($var == 'tasks') {
            $ret = $this->getArrayCopy();
            unset($ret['attribs']);
            return $ret;
        }
        return $this['attribs'][$var];
    }

    /**
     * Allow setting of attributes and tasks directly
     *
     * @param string $var
     * @param string|object $value
     */
    function __set($var, $value)
    {
        if (strpos($var, $this->_packagefile->getTasksNs()) === 0) {
            // setting a file task
            if ($value instanceof PEAR2_Task_Common) {
                $this->_packagefile->setFileAttribute($this->_dir .
                    $this['attribs']['name'], $var, $value->getArrayCopy());
                return;
            }
            throw new PEAR2_PackageFile_Exception('Cannot set ' . $var . ' to non-' .
                'PEAR2_Task_Common object in file ' . $this->dir .
                $this['attribs']['name']);
        }
        $this->_packagefile->setFileAttribute($this->dir . $this['attribs']['name'],
            $var, $value);
    }
}

/**
 * Traverse the current <dir> in the <contents> tag
 */
class PEAR2_PackageFile_v2Iterator_FileContents extends RecursiveArrayIterator
{
    protected $tag;
    protected $dir = '';
    private $_packagefile;
    function __construct($arr, $tag, PEAR2_PackageFile_v2 $parent, $dir = '')
    {
        $this->tag = $tag;
        $this->dir = $dir;
        $this->_packagefile = $parent;
        if ($arr instanceof PEAR2_PackageFile_v2Iterator_FileTag) {
            $arr = $arr->getArrayCopy();
        }
        parent::__construct($arr);
    }

    function getChildren ()
    {
        $arr = $this->current();
        $now = '';
        if ($this->key() == 'dir' && !isset($arr[0])) {
            $now = $arr['attribs']['name'];
            if (!$this->dir && $now == '/') {
                $now = '';
            }
        }
        $dir = $this->dir;
        if ($now && $dir) {
            if ($dir[strlen($dir) - 1] != '/') {
                $dir .= '/';
            }
        }
        if (isset($arr['attribs'])) unset($arr['attribs']);
        if (isset($arr[0])) {
            return new PEAR2_PackageFile_v2Iterator_FileContentsMulti($arr, $this->key(),
                $this->_packagefile, $dir . $now);
        }
        return new PEAR2_PackageFile_v2Iterator_FileContents($arr, $this->key(),
            $this->_packagefile, $dir . $now);
    }

    function hasChildren()
    {
        $arr = $this->current();
        if (!($arr instanceof PEAR2_PackageFile_v2Iterator_FileTag) && !is_array($arr)) {
            return false;
        }
        if (isset($arr['file']) || isset($arr['dir']) || isset($arr[0])) {
            return true;
        }
        return false;
    }

    function current()
    {
        $x = parent::current();
        return new PEAR2_PackageFile_v2Iterator_FileTag($x, $this->dir, $this->_packagefile);
    }
}

/**
 * iterator for tags with multiple sub-tags
 */
class PEAR2_PackageFile_v2Iterator_FileContentsMulti extends PEAR2_PackageFile_v2Iterator_FileContents
{
    function key()
    {
        return $this->tag;
    }
}

/**
 * Filter out the attributes meta-information when traversing the file list
 */
class PEAR2_PackageFile_v2Iterator_FileAttribsFilter extends RecursiveFilterIterator
{
    function accept()
    {
        $it = $this->getInnerIterator(); 
        if (!$it->valid()) {
            return false;
        }
        $key = $it->key();
        if ($key === 'attribs') {
            return false;
        }
        return true;
    }
}

class PEAR2_PackageFile_v2Iterator_FileInstallationFilter extends
    PEAR2_PackageFile_v2Iterator_FileAttribsFilter
{
    static private $_parent;
    static private $_installGroup;
    static function setParent(PEAR2_PackageFile_v2 $parent)
    {
        self::$_parent = $parent;
        $depchecker = new PEAR2_Dependency_Validator(PEAR2_Config::current(), array(),
            array('channel' => self::$_parent->getChannel(),
                  'package' => self::$_parent->getPackage()),
            PEAR2_Validate::INSTALLING);
        foreach (self::$_parent->installGroup as $instance) {
            try {
                if (isset($instance['installconditions'])) {
                    $installconditions = $instance['installconditions'];
                    if (is_array($installconditions)) {
                        foreach ($installconditions as $type => $conditions) {
                            if (!isset($conditions[0])) {
                                $conditions = array($conditions);
                            }
                            foreach ($conditions as $condition) {
                                $ret = $depchecker->{"validate{$type}Dependency"}($condition);
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                // can't use this release
                continue;
            }
            $release = array('install' => array(), 'ignore' => array());
            // this is the release to use
            if (isset($instance['filelist'])) {
                // ignore files
                if (isset($instance['filelist']['ignore'])) {
                    $ignore = isset($instance['filelist']['ignore'][0]) ?
                        $instance['filelist']['ignore'] :
                        array($instance['filelist']['ignore']);
                    foreach ($ignore as $ig) {
                        $release['ignore'][$ig['attribs']['name']] = true;
                    }
                }
                // install files as this name
                if (isset($instance['filelist']['install'])) {
                    $installas = isset($instance['filelist']['install'][0]) ?
                        $instance['filelist']['install'] :
                        array($instance['filelist']['install']);
                    foreach ($installas as $as) {
                        $release['install'][$as['attribs']['name']] =
                            $as['attribs']['as'];
                    }
                }
            }
            self::$_installGroup = $release;
            return;
        }
    }

    function accept()
    {
        if (parent::accept()) {
            if ($this->getInnerIterator()->key() != 'file') {
                return true;
            }
            $curfile = $this->getInnerIterator()->current();
            if (isset($curfile[0])) {
                return true;
            }
            if (isset(self::$_installGroup['ignore'][$curfile->dir . $curfile->name])) {
                // skip ignored files
                return false;
            }
            if (isset(self::$_installGroup['install'][$curfile->dir . $curfile->name])) {
                // add the install-as attribute for these files
                $curfile->{'install-as'} =
                    self::$_installGroup['install'][$curfile->dir . $curfile->name];
            }
            return true;
        }
        return false;
    }
}