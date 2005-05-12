<?php
/**
 * PEAR_REST_10
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 1.4.0a12
 */

/**
 * For downloading REST xml/txt files
 */
require_once 'PEAR/REST.php';

/**
 * Implement REST 1.0
 *
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 1.4.0a12
 */
class PEAR_REST_10
{
    var $_rest;
    function PEAR_REST_10($config, $options = array())
    {
        $this->_rest = &new PEAR_REST($config, $options);
    }

    function getDownloadURL($base, $packageinfo, $prefstate, $installed)
    {
        $channel = $packageinfo['channel'];
        $package = $packageinfo['package'];
        $states = $this->betterStates($prefstate, true);
        if (!$states) {
            return PEAR::raiseError('"' . $prefstate . '" is not a valid state');
        }
        $state = $version = null;
        if (isset($packageinfo['state'])) {
            $state = $packageinfo['state'];
        }
        if (isset($packageinfo['version'])) {
            $version = $packageinfo['version'];
        }
        $info = $this->_rest->retrieveData($base . 'r/' . strtolower($package) . '/allreleases.xml');
        if (PEAR::isError($info)) {
            return $info;
        }
        if (!isset($info['r'])) {
            return false;
        }
        $found = false;
        $release = false;
        if (!is_array($info['r'])) {
            $info['r'] = array($info['r']);
        }
        foreach ($info['r'] as $release) {
            if (!isset($this->_rest->_options['force']) && ($installed &&
                  version_compare($release['v'], $installed, '<'))) {
                continue;
            }
            if (isset($state)) {
                if ($release['s'] == $state) {
                    $found = true;
                    break;
                }
            } elseif (isset($version)) {
                if ($release['v'] == $version) {
                    $found = true;
                    break;
                }
            } else {
                if (in_array($release['s'], $states)) {
                    $found = true;
                    break;
                }
            }
        }
        return $this->_returnDownloadURL($base, $package, $release, $info, $found);
    }

    function getDepDownloadURL($base, $xsdversion, $dependency, $deppackage,
                               $prefstate = 'stable', $installed = false)
    {
        $channel = $dependency['channel'];
        $package = $dependency['name'];
        $states = $this->betterStates($prefstate, true);
        if (!$states) {
            return PEAR::raiseError('"' . $prefstate . '" is not a valid state');
        }
        $state = $version = null;
        if (isset($packageinfo['state'])) {
            $state = $packageinfo['state'];
        }
        if (isset($packageinfo['version'])) {
            $version = $packageinfo['version'];
        }
        $info = $this->_rest->retrieveData($base . 'r/' . strtolower($package) . '/allreleases.xml');
        if (PEAR::isError($info)) {
            return $info;
        }
        if (!isset($info['r'])) {
            return false;
        }
        $exclude = array();
        $min = $max = $recommended = false;
        if ($xsdversion == '1.0') {
            $pinfo['package'] = $dependency['name'];
            $pinfo['channel'] = 'pear.php.net'; // this is always true - don't change this
            switch ($dependency['rel']) {
                case 'ge' :
                    $min = $dependency['version'];
                break;
                case 'gt' :
                    $min = $dependency['version'];
                    $exclude = array($dependency['version']);
                break;
                case 'eq' :
                    $recommended = $dependency['version'];
                break;
                case 'lt' :
                    $max = $dependency['version'];
                    $exclude = array($dependency['version']);
                break;
                case 'le' :
                    $max = $dependency['version'];
                break;
                case 'ne' :
                    $exclude = array($dependency['version']);
                break;
            }
        } else {
            $pinfo['package'] = $dependency['name'];
            $min = isset($dependency['min']) ? $dependency['min'] : false;
            $max = isset($dependency['max']) ? $dependency['max'] : false;
            $recommended = isset($dependency['recommended']) ?
                $dependency['recommended'] : false;
            if (isset($dependency['exclude'])) {
                if (!isset($dependency['exclude'][0])) {
                    $exclude = array($dependency['exclude']);
                }
            }
        }
        $found = false;
        $release = false;
        if (!is_array($info['r']) || !isset($info['r'][0])) {
            $info['r'] = array($info['r']);
        }
        foreach ($info['r'] as $release) {
            if (!isset($this->_rest->_options['force']) && ($installed &&
                  version_compare($release['v'], $installed, '<'))) {
                continue;
            }
            if (in_array($release['v'], $exclude)) { // skip excluded versions
                continue;
            }
            // allow newer releases to say "I'm OK with the dependent package"
            if ($xsdversion == '2.0' && isset($release['co'])) {
                if (isset($release['co'][$deppackage['channel']]
                      [$deppackage['p']]) && in_array($release['v'],
                        $release['co'][$deppackage['channel']]
                        [$deppackage['package']])) {
                    $recommended = $release['v'];
                }
            }
            if ($recommended) {
                if ($release['v'] != $recommended) { // if we want a specific
                    // version, then skip all others
                    continue;
                } else {
                    if (!in_array($release['s'], $states)) {
                        // the stability is too low, but we must return the
                        // recommended version if possible
                        return $this->_returnDownloadURL($base, $package, $release, $info, true);
                    }
                }
            }
            if ($min && version_compare($release['v'], $min, 'lt')) { // skip too old versions
                continue;
            }
            if ($max && version_compare($release['v'], $max, 'gt')) { // skip too new versions
                continue;
            }
            if ($installed && version_compare($release['v'], $installed, '<')) {
                continue;
            }
            if (in_array($release['s'], $states)) { // if in the preferred state...
                $found = true; // ... then use it
                break;
            }
        }
        return $this->_returnDownloadURL($base, $package, $release, $info, $found);
    }

    function _returnDownloadURL($base, $package, $release, $info, $found)
    {
        if ($found) {
            $releaseinfo = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/' . 
                $release['v'] . '.xml');
            if (PEAR::isError($releaseinfo)) {
                return $releaseinfo;
            }
            $packagexml = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/' .
                'deps.' . $release['v'] . '.txt', false, true);
            if (PEAR::isError($packagexml)) {
                return $packagexml;
            }
            $packagexml = unserialize($packagexml);
            if (!$packagexml) {
                $packagexml = array();
            }
            return 
                array('version' => $releaseinfo['v'],
                      'info' => $packagexml,
                      'package' => $releaseinfo['p']['_content'],
                      'stability' => $releaseinfo['st'],
                      'url' => $releaseinfo['g']);
        } else {
            $release = $info['r'][0];
            $releaseinfo = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/' . 
                $release['v'] . '.xml');
            if (PEAR::isError($releaseinfo)) {
                return $releaseinfo;
            }
            $packagexml = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/' .
                'deps.' . $release['v'] . '.txt', false, true);
            if (PEAR::isError($packagexml)) {
                return $packagexml;
            }
            $packagexml = unserialize($packagexml);
            if (!$packagexml) {
                $packagexml = array();
            }
            return
                array('version' => $releaseinfo['v'],
                      'package' => $releaseinfo['p']['_content'],
                      'stability' => $releaseinfo['st'],
                      'info' => $packagexml);
        }
    }

    function listPackages($base)
    {
        $packagelist = $this->_rest->retrieveData($base . 'p/packages.xml');
        if (PEAR::isError($packagelist)) {
            return $packagelist;
        }
        if (!is_array($packagelist['p'])) {
            $packagelist['p'] = array($packagelist['p']);
        }
        return $packagelist['p'];
    }

    function listAll($base, $dostable, $basic = true, $searchpackage = false, $searchsummary = false)
    {
        $packagelist = $this->_rest->retrieveData($base . 'p/packages.xml');
        if (PEAR::isError($packagelist)) {
            return $packagelist;
        }
        $ret = array();
        if (!is_array($packagelist['p'])) {
            $packagelist['p'] = array($packagelist['p']);
        }
        foreach ($packagelist['p'] as $package) {
            if ($basic) { // remote-list command
                PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                if ($dostable) {
                    $latest = $this->_rest->retrieveData($base . 'r/' . strtolower($package) .
                        '/stable.txt');
                } else {
                    $latest = $this->_rest->retrieveData($base . 'r/' . strtolower($package) .
                        '/latest.txt');
                }
                PEAR::popErrorHandling();
                if (PEAR::isError($latest)) {
                    $latest = false;
                }
                $info = array('stable' => $latest);
            } else { // list-all command
                $inf = $this->_rest->retrieveData($base . 'p/' . strtolower($package) . '/info.xml');
                if (PEAR::isError($inf)) {
                    return $inf;
                }
                if ($searchpackage) {
                    $found = (!empty($searchpackage) && stristr($package, $searchpackage) !== false);
                    if (!$found && !(isset($searchsummary) && !empty($searchsummary)
                        && (stristr($inf['s'], $searchsummary) !== false
                            || stristr($info['d'], $searchsummary) !== false)))
                    {
                        continue;
                    };
                }
                PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                $releases = $this->_rest->retrieveData($base . 'r/' . strtolower($package) .
                    '/allreleases.xml');
                if (PEAR::isError($releases)) {
                    continue;
                }
                if (!isset($releases['r'][0])) {
                    $releases['r'] = array($releases['r']);
                }
                unset($latest);
                unset($unstable);
                unset($stable);
                unset($state);
                foreach ($releases['r'] as $release) {
                    if (!isset($latest)) {
                        if ($dostable && $release['s'] == 'stable') {
                            $latest = $release['v'];
                            $state = 'stable';
                        }
                        if (!$dostable) {
                            $latest = $release['v'];
                            $state = $release['s'];
                        }
                    }
                    if (!isset($stable) && $release['s'] == 'stable') {
                        $stable = $release['v'];
                        if (!isset($unstable)) {
                            $unstable = $stable;
                        }
                    }
                    if (!isset($unstable) && $release['s'] != 'stable') {
                        $latest = $unstable = $release['v'];
                        $state = $release['s'];
                    }
                    if (isset($latest) && !isset($state)) {
                        $state = $release['s'];
                    }
                    if (isset($latest) && isset($stable) && isset($unstable)) {
                        break;
                    }
                }
                $deps = array();
                if (!isset($unstable)) {
                    $unstable = false;
                    $state = 'stable';
                    if (isset($stable)) {
                        $latest = $unstable = $stable;
                    }
                } else {
                    $latest = $unstable;
                }
                if (!isset($latest)) {
                    $latest = false;
                }
                if ($latest) {
                    $d = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/deps.' .
                        $latest . '.txt');
                    if (!PEAR::isError($d)) {
                        $d = unserialize($d);
                        if ($d) {
                            if (isset($d['required'])) {
                                if (!class_exists('PEAR_PackageFile_v2')) {
                                    require_once 'PEAR/PackageFile/v2.php';
                                }
                                if (!isset($pf)) {
                                    $pf = new PEAR_PackageFile_v2;
                                }
                                $pf->setDeps($d);
                                $tdeps = $pf->getDeps();
                            } else {
                                $tdeps = $d;
                            }
                            foreach ($tdeps as $dep) {
                                if ($dep['type'] !== 'pkg') {
                                    continue;
                                }
                                $deps[] = $dep;
                            }
                        }
                    }
                }
                PEAR::popErrorHandling();
                $info = array('stable' => $latest, 'summary' => $inf['s'], 'description' =>
                    $inf['d'], 'deps' => $deps, 'category' => $inf['ca']['_content'],
                    'unstable' => $unstable, 'state' => $state);
            }
            $ret[$package] = $info;
        }
        return $ret;
    }

    function listLatestUpgrades($base, $state, $installed, $channel, &$reg)
    {
        $packagelist = $this->_rest->retrieveData($base . 'p/packages.xml');
        if (PEAR::isError($packagelist)) {
            return $packagelist;
        }
        $ret = array();
        if (!is_array($packagelist['p'])) {
            $packagelist['p'] = array($packagelist['p']);
        }
        if ($state) {
            $states = $this->betterStates($state, true);
        }
        foreach ($packagelist['p'] as $package) {
            if (!isset($installed[strtolower($package)])) {
                continue;
            }
            $inst_version = $reg->packageInfo($package, 'version', $channel);
            PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
            $info = $this->_rest->retrieveData($base . 'r/' . strtolower($package) .
                '/allreleases.xml');
            PEAR::popErrorHandling();
            if (PEAR::isError($info)) {
                continue; // no remote releases
            }
            if (!isset($info['r'])) {
                continue;
            }
            $found = false;
            $release = false;
            if (!is_array($info['r'])) {
                $info['r'] = array($info['r']);
            }
            foreach ($info['r'] as $release) {
                if ($inst_version && version_compare($release['v'], $inst_version, '<=')) {
                    continue;
                }
                if ($state) {
                    if (in_array($release['s'], $states)) {
                        $found = true;
                        break;
                    }
                } else {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                continue;
            }
            $relinfo = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/' . 
                $release['v'] . '.xml');
            if (PEAR::isError($relinfo)) {
                return $relinfo;
            }
            $ret[$package] = array(
                    'version' => $release['v'],
                    'state' => $release['s'],
                    'filesize' => $relinfo['f'],
                );
        }
        return $ret;
    }

    function packageInfo($base, $package)
    {
        PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
        $info = $this->_rest->retrieveData($base . 'p/' . strtolower($package) . '/info.xml');
        $latest = $this->_rest->retrieveData($base . 'r/' . strtolower($package) . '/latest.txt');
        if (!PEAR::isError($latest)) {
            $d = $this->_rest->retrieveCacheFirst($base . 'r/' . strtolower($package) . '/deps.' .
                $latest . '.txt');
        } else {
            $d = serialize(array());
        }
        PEAR::popErrorHandling();
        if (PEAR::isError($info)) {
            return PEAR::raiseError('Unknown package: "' . $package . '"');
        }
        if (PEAR::isError($latest)) {
            $latest = '';
        }
        if (PEAR::isError($d)) {
            return $d;
        }
        $d = unserialize($d);
        if (isset($d['required'])) {
            if (!class_exists('PEAR_PackageFile_v2')) {
                require_once 'PEAR/PackageFile/v2.php';
            }
            $pf = new PEAR_PackageFile_v2;
            $pf->setDeps($d);
            $d = $pf->getDeps();
        }
        $deps = array();
        foreach ($d as $dep) {
            if ($dep['type'] != 'pkg') {
                continue;
            }
            $deps[] = $dep;
        }
        return array(
            'name' => $info['n'],
            'channel' => $info['c'],
            'category' => $info['ca']['_content'],
            'stable' => $latest,
            'license' => $info['l'],
            'summary' => $info['s'],
            'description' => $info['d'],
            'deps' => $deps,
            );
    }

    /**
     * Return an array containing all of the states that are more stable than
     * or equal to the passed in state
     *
     * @param string Release state
     * @param boolean Determines whether to include $state in the list
     * @return false|array False if $state is not a valid release state
     */
    function betterStates($state, $include = false)
    {
        static $states = array('snapshot', 'devel', 'alpha', 'beta', 'stable');
        $i = array_search($state, $states);
        if ($i === false) {
            return false;
        }
        if ($include) {
            $i--;
        }
        return array_slice($states, $i + 1);
    }
}
?>