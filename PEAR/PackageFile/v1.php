<?php
/**
 * Error code if the package.xml <package> tag does not contain a valid version
 */
define('PEAR_PACKAGEFILE_ERROR_NO_PACKAGEVERSION', 1);
/**
 * Error code if the package.xml <package> tag version is not supported (version 1.0 and 1.1 are the only supported versions,
 * currently
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_PACKAGEVERSION', 2);

/**
 * Error code if parsing is attempted with no xml extension
 */
define('PEAR_PACKAGEFILE_ERROR_NO_XML_EXT', 3);

/**
 * Error code if creating the xml parser resource fails
 */
define('PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER', 4);

/**
 * Error code used for all sax xml parsing errors
 */
define('PEAR_PACKAGEFILE_ERROR_PARSER_ERROR', 5);

/**
 * Error code used when there is no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_NAME', 6);

/**
 * Error code when a package name is not valid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_NAME', 7);

/**
 * Error code used when no summary is parsed
 */
define('PEAR_PACKAGEFILE_ERROR_NO_SUMMARY', 8);

/**
 * Error code for summaries that are more than 1 line
 */
define('PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY', 9);

/**
 * Error code used when no description is present
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION', 10);

/**
 * Error code used when no license is present
 */
define('PEAR_PACKAGEFILE_ERROR_NO_LICENSE', 11);

/**
 * Error code used when a <version> version number is not present
 */
define('PEAR_PACKAGEFILE_ERROR_NO_VERSION', 12);

/**
 * Error code used when a <version> version number is invalid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_VERSION', 13);

/**
 * Error code when release state is missing
 */
define('PEAR_PACKAGEFILE_ERROR_NO_STATE', 14);

/**
 * Error code when release state is invalid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_STATE', 15);

/**
 * Error code when release state is missing
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DATE', 16);

/**
 * Error code when release state is invalid
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DATE', 17);

/**
 * Error code when no release notes are found
 */
define('PEAR_PACKAGEFILE_ERROR_NO_NOTES', 18);

/**
 * Error code when no maintainers are found
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTAINERS', 19);

/**
 * Error code when a maintainer has no handle
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE', 20);

/**
 * Error code when a maintainer has no handle
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE', 21);

/**
 * Error code when a maintainer has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME', 22);

/**
 * Error code when a maintainer has no email
 */
define('PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL', 23);

/**
 * Error code when a maintainer has no handle
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE', 24);

/**
 * Error code when a dependency is not a PHP dependency, but has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPNAME', 25);

/**
 * Error code when a dependency has no type (pkg, php, etc.)
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE', 26);

/**
 * Error code when a dependency has no relation (lt, ge, has, etc.)
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPREL', 27);

/**
 * Error code when a dependency is not a 'has' relation, but has no version
 */
define('PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION', 28);

/**
 * Error code when a dependency has an invalid relation
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL', 29);

/**
 * Error code when a dependency has an invalid type
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE', 30);

/**
 * Error code when a dependency has an invalid optional option
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL', 31);

/**
 * Error code when a dependency is a pkg dependency, and has an invalid package name
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME', 32);

/**
 * Error code when a dependency has a channel="foo" attribute, and foo is not a registered channel
 */
define('PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL', 33);

/**
 * Error code when rel="has" and version attribute is present.
 */
define('PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED', 34);

/**
 * Error code when type="php" and dependency name is present
 */
define('PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED', 35);

/**
 * Error code when a configure option has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_CONFNAME', 36);

/**
 * Error code when a configure option has no name
 */
define('PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT', 37);

/**
 * Error code when a file in the filelist has an invalid role
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE', 38);

/**
 * Error code when a file in the filelist has no role
 */
define('PEAR_PACKAGEFILE_ERROR_NO_FILEROLE', 39);

/**
 * Error code when analyzing a php source file that has parse errors
 */
define('PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE', 40);

/**
 * Error code when analyzing a php source file reveals a source element
 * without a package name prefix
 */
define('PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX', 41);

/**
 * Error code when an unknown channel is specified
 */
define('PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL', 42);

/**
 * Error code when no files are found in the filelist
 */
define('PEAR_PACKAGEFILE_ERROR_NO_FILES', 43);

class PEAR_PackageFile_v1
{
    /**
     * @access private
     * @var PEAR_ErrorStack
     * @access private
     */
    var $_stack;

    /**
     * A registry object, used to access the package name validation regex for non-standard channels
     * @var PEAR_Registry
     * @access private
     */
    var $_registry;

    /**
     * Parsed package information
     * @var array
     * @access private
     */
    var $_packageInfo;

    /**
     * path to package.xml
     * @var string
     * @access private
     */
    var $_packageFile;

    /**
     * path to package .tgz or false if this is a local/extracted package.xml
     * @var string
     * @access private
     */
    var $_archiveFile;

    /**
     * @var boolean
     * @access private
     */
    var $_isValid = false;

    /**
     * @param bool determines whether to return a PEAR_Error object, or use the PEAR_ErrorStack
     * @param string Name of Error Stack class to use.  This allows inheritance with stacks that have the
     *        same constructor as the parent PEAR_ErrorStack class
     */
    function PEAR_PackageFile_v1($compatibility = false, $stackclass = 'PEAR_ErrorStack')
    {
        if (!class_exists('PEAR_ErrorStack')) {
            include_once 'PEAR/ErrorStack.php';
        }
        $this->_stack = &new $stackclass('PEAR_PackageFile', false,
            false, $compatibility, 'Exception');
        $this->_stack->setErrorMessageTemplate($this->_getErrorMessage());
        $this->_isValid = false;
        $this->_compatibility = $compatibility;
    }

    function setRegistry(&$registry)
    {
        $this->_registry = &$registry;
    }

    function setPackagefile($file, $archive = false)
    {
        $this->_packageFile = $file;
        $this->_archiveFile = $archive ? $archive : $file;
    }

    function getPackageFile()
    {
        return $this->_packageFile;
    }

    function getArchiveFile()
    {
        return $this->_archiveFile;
    }

    function packageInfo($field)
    {
        if (!isset($this->_packageInfo[$field]) ||
              !is_string($this->_packageInfo[$field])) {
            return false;
        }
        return $this->_packageInfo[$field];
    }

    function setDirtree($path)
    {
        $this->_packageInfo['dirtree'][$path] = true;
    }

    function getDirtree()
    {
        if (isset($this->_packageInfo['dirtree']) && count($this->_packageInfo['dirtree'])) {
            return $this->_packageInfo['dirtree'];
        }
        return false;
    }

    function resetDirtree()
    {
        unset($this->_packageInfo['dirtree']);
    }

    function fromArray($pinfo)
    {
        $this->_packageInfo = $pinfo;
    }

    function getChannel()
    {
        return 'pear';
    }

    function getArray()
    {
        return $this->_packageInfo;
    }

    function getName()
    {
        return $this->getPackage();
    }

    function getPackage()
    {
        if (isset($this->_packageInfo['package'])) {
            return $this->_packageInfo['package'];
        }
        return false;
    }

    function getExtends()
    {
        if (isset($this->_packageInfo['extends'])) {
            return $this->_packageInfo['extends'];
        }
        return false;
    }

    function getVersion()
    {
        if (isset($this->_packageInfo['version'])) {
            return $this->_packageInfo['version'];
        }
        return false;
    }

    function getMaintainers()
    {
        if (isset($this->_packageInfo['maintainers'])) {
            return $this->_packageInfo['maintainers'];
        }
        return false;
    }

    function getState()
    {
        if (isset($this->_packageInfo['release_state'])) {
            return $this->_packageInfo['release_state'];
        }
        return false;
    }

    function getDate()
    {
        if (isset($this->_packageInfo['release_date'])) {
            return $this->_packageInfo['release_date'];
        }
        return false;
    }

    function getLicense()
    {
        if (isset($this->_packageInfo['release_license'])) {
            return $this->_packageInfo['release_license'];
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

    function getDeps()
    {
        if (isset($this->_packageInfo['release_deps'])) {
            return $this->_packageInfo['release_deps'];
        }
        return false;
    }

    function hasDeps()
    {
        return isset($this->_packageInfo['release_deps']) &&
            count($this->_packageInfo['release_deps']);
    }

    function getConfigureOptions()
    {
        if (isset($this->_packageInfo['configure_options'])) {
            return $this->_packageInfo['configure_options'];
        }
        return false;
    }

    function getProvides()
    {
        if (isset($this->_packageInfo['provides'])) {
            return $this->_packageInfo['provides'];
        }
        return false;
    }

    function hasConfigureOptions()
    {
        return isset($this->_packageInfo['configure_options']) &&
            count($this->_packageInfo['configure_options']);
    }

    function getFilelist()
    {
        if (isset($this->_packageInfo['filelist'])) {
            return $this->_packageInfo['filelist'];
        }
        return false;
    }

    function resetFilelist()
    {
        $this->_packageInfo['filelist'] = array();
    }

    function setInstalledAs($file, $path)
    {
        if ($path) {
            return $this->_packageInfo['filelist'][$file]['installed_as'] = $path;
        }
        unset($this->_packageInfo['filelist'][$file]['installed_as']);
    }

    function installedFile($file, $atts)
    {
        if (isset($this->_packageInfo['filelist'][$file])) {
            $this->_packageInfo['filelist'][$file] =
                array_merge($this->_packageInfo['filelist'][$file], $atts);
        } else {
            $this->_packageInfo['filelist'][$file] = $atts;
        }
    }

    function getChangelog()
    {
        if (isset($this->_packageInfo['changelog'])) {
            return $this->_packageInfo['changelog'];
        }
        return false;
    }

    function getPackagexmlVersion()
    {
        return '1.0';
    }

    /**
     * Wrapper to {@link PEAR_ErrorStack::getErrors()}
     * @param boolean determines whether to purge the error stack after retrieving
     * @return array
     */
    function getValidationWarnings($purge = true)
    {
        return $this->_stack->getErrors($purge);
    }

    // }}}
    /**
     * Validation error.  Also marks the object contents as invalid
     * @param error code
     * @param array error information
     * @access private
     */
    function _validateError($code, $params = array())
    {
        $this->_stack->push($code, 'error', $params, false, false, debug_backtrace());
        $this->_isValid = false;
    }

    /**
     * Validation warning.  Does not mark the object contents invalid.
     * @param error code
     * @param array error information
     * @access private
     */
    function _validateWarning($code, $params = array())
    {
        $this->_stack->push($code, 'warning', $params, false, false, debug_backtrace());
    }

    /**
     * @param integer error code
     * @access protected
     */
    function _getErrorMessage()
    {
        return array(
                PEAR_PACKAGEFILE_ERROR_INVALID_PACKAGEVERSION =>
                    'While parsing package.xml, an invalid <package> version number "%version% was passed in, expecting one of %versions%',
                PEAR_PACKAGEFILE_ERROR_NO_PACKAGEVERSION =>
                    'No version number found in <package> tag',
                PEAR_PACKAGEFILE_ERROR_NO_XML_EXT =>
                    '%error%',
                PEAR_PACKAGEFILE_ERROR_CANT_MAKE_PARSER =>
                    'Unable to create XML parser',
                PEAR_PACKAGEFILE_ERROR_PARSER_ERROR =>
                    '%error%',
                PEAR_PACKAGEFILE_ERROR_NO_NAME =>
                    'Missing Package Name',
                PEAR_PACKAGEFILE_ERROR_INVALID_NAME =>
                    'Invalid Package Name "%name%"',
                PEAR_PACKAGEFILE_ERROR_NO_SUMMARY =>
                    'No summary found',
                PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL =>
                    'Unknown channel, "%channel%"',
                PEAR_PACKAGEFILE_ERROR_MULTILINE_SUMMARY =>
                    'Summary should be on one line',
                PEAR_PACKAGEFILE_ERROR_NO_DESCRIPTION =>
                    'Missing description',
                PEAR_PACKAGEFILE_ERROR_NO_LICENSE =>
                    'Missing license',
                PEAR_PACKAGEFILE_ERROR_INVALID_VERSION =>
                    'Invalid <version> version "%version%"',
                PEAR_PACKAGEFILE_ERROR_NO_VERSION =>
                    'No <version> version found',
                PEAR_PACKAGEFILE_ERROR_NO_STATE =>
                    'No state found',
                PEAR_PACKAGEFILE_ERROR_INVALID_STATE =>
                    'Invalid state "%state%", expecting one of "%states%"',
                PEAR_PACKAGEFILE_ERROR_NO_DATE =>
                    'No release date found',
                PEAR_PACKAGEFILE_ERROR_INVALID_DATE =>
                    'Invalid release date "%date%", format is YYYY-MM-DD',
                PEAR_PACKAGEFILE_ERROR_NO_NOTES =>
                    'No release notes found',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTAINERS =>
                    'No maintainers found, at least one must be defined',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTHANDLE =>
                    'Maintainer %index% has no handle (user ID at channel server)',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTROLE =>
                    'Maintainer %index% has no role, must be one of %roles%',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTNAME =>
                    'Maintainer %index% has no name',
                PEAR_PACKAGEFILE_ERROR_NO_MAINTEMAIL =>
                    'Maintainer %index% has no email',
                PEAR_PACKAGEFILE_ERROR_INVALID_MAINTROLE =>
                    'Maintainer %index% has invalid role "%role%", must be one of %roles%',
                PEAR_PACKAGEFILE_ERROR_NO_DEPNAME =>
                    'Dependency %index% is not a php dependency, and has no name',
                PEAR_PACKAGEFILE_ERROR_NO_DEPREL =>
                    'Dependency %index% has no relation, expecting one of %rels%',
                PEAR_PACKAGEFILE_ERROR_NO_DEPTYPE =>
                    'Dependency %index% has no type, expecting one of %types%',
                PEAR_PACKAGEFILE_ERROR_NO_DEPVERSION =>
                    'Dependency %index% is not a rel="has" dependency, and has no version',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPREL =>
                    'Dependency %index% has invalid relation "%rel%", expecting one of %rels%',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPTYPE =>
                    'Dependency %index% has invalid type "%type%", expecting one of %types%',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPNAME =>
                    'Dependency %index% has a package dependency with invalid package name "%name%"',
                PEAR_PACKAGEFILE_ERROR_INVALID_DEPOPTIONAL =>
                    'Dependency %index% has invalid optional value "%opt%", should be yes or no',
                PEAR_PACKAGEFILE_ERROR_UNKNOWN_DEPCHANNEL =>
                    'Dependency %index% requires unknown channel "%channel%"',
                PEAR_PACKAGEFILE_ERROR_DEPNAME_IGNORED =>
                    'Dependency %index% is type="php", name "%name%" will be ignored',
                PEAR_PACKAGEFILE_ERROR_DEPVERSION_IGNORED =>
                    'Dependency %index% is rel="has", version "%version%" will be ignored',
                PEAR_PACKAGEFILE_ERROR_NO_CONFNAME =>
                    'Configure Option %index% has no name',
                PEAR_PACKAGEFILE_ERROR_NO_CONFPROMPT =>
                    'Configure Option %index% has no prompt',
                PEAR_PACKAGEFILE_ERROR_NO_FILES =>
                    'No files in <filelist> section of package.xml',
                PEAR_PACKAGEFILE_ERROR_NO_FILEROLE =>
                    'File "%file%" has no role, expecting one of "%roles%"',
                PEAR_PACKAGEFILE_ERROR_INVALID_FILEROLE =>
                    'File "%file%" has invalid role "%role%", expecting one of "%roles%"',
                PEAR_PACKAGEFILE_ERROR_INVALID_PHPFILE =>
                    'Parser error: Invalid PHP file "%file%"',
                PEAR_PACKAGEFILE_ERROR_NO_PNAME_PREFIX =>
                    'in %file%: %type% "%name%" not prefixed with package name "%package%"',
            );
    }

    /**
     * Validate XML package definition file.
     *
     * @access public
     * @return boolean
     */
    function validate($state)
    {
        $this->_isValid = true;
        $info = $this->_packageInfo;
        $channel = isset($info['channel']) ? $info['channel'] : 'pear';
        $chan = isset($this->_registry) ? $this->_registry->getChannel($channel) : false;
        if (!$chan) {
            if ($channel != 'pear') {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_UNKNOWN_CHANNEL,
                    array('channel' => $channel));
            }
            if (!isset($info['package'])) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_NO_NAME);
            } elseif (!PEAR_Common::validPackageName($info['package'])) {
                $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_NAME,
                    array('name' => $info['package']));
            }
            if (isset($info['extends'])) {
                if (!PEAR_Common::validPackageName($info['extends'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_EXTENDS,
                        array('extends' => $info['extends']));
                }
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
            if (isset($info['extends'])) {
                if (!$chan->validPackageName($info['extends'])) {
                    $this->_validateError(PEAR_PACKAGEFILE_ERROR_INVALID_EXTENDS,
                        array('extends' => $info['extends']));
                }
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

    function &getDefaultGenerator()
    {
        include_once 'PEAR/PackageFile/Generator/v1.php';
        $a = &new PEAR_PackageFile_Generator_v1($this);
        return $a;
    }

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