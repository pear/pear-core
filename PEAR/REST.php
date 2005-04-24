<?php
/**
 * PEAR_REST
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
 * @since      File available since Release 1.4.0a1
 */

/**
 * For downloading xml files
 */
require_once 'PEAR/Downloader.php';
require_once 'PEAR/XMLParser.php';

/**
 * Intelligently retrieve data, following hyperlinks if necessary, and re-directing
 * as well
 * @category   pear
 * @package    PEAR
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 1.4.0a1
 */
class PEAR_REST extends PEAR_Downloader
{
    function PEAR_REST(&$ui, &$config, $options = array())
    {
        parent::PEAR_Downloader($ui, $options, $config);
    }

    function retrieveData($url, $accept = false, $forcestring = false)
    {
        $cacheId = $this->getCacheId($url);
        if (isset($this->_options['downloadonly'])) {
            $dldir = System::mktemp(array('-d', 'rest'));
        } else {
            $dldir = $this->getDownloadDir();
        }
        $file = $this->downloadHttp($url, $this->ui, $dldir, null, $cacheId,
            $accept);
        if (PEAR::isError($file)) {
            return $file;
        }
        if (!$file) {
            return $this->getCache($url);
        }
        $headers = $file[2];
        $lastmodified = $file[1];
        $content = implode('', file($file[0]));
        if ($forcestring) {
            $this->saveCache($url, $content, $lastmodified);
            return $content;
        }
        if (isset($headers['content-type'])) {
            switch ($headers['content-type']) {
                case 'text/xml' :
                    $parser = new PEAR_XMLParser;
                    $parser->parse($content);
                    $content = $parser->getData();
                case 'text/html' :
                default :
                    // use it as a string
            }
        } else {
            // assume XML
            $parser = new PEAR_XMLParser;
            $parser->parse($file);
            $content = $parser->getData();
        }
        $this->saveCache($url, $content, $lastmodified);
        return $content;
    }

    function getCacheId($url)
    {
        $cacheidfile = $this->config->get('cache_dir') . DIRECTORY_SEPARATOR .
            md5($url) . 'rest.cacheid';
        if (@file_exists($cacheidfile)) {
            return unserialize(implode('', file($cacheidfile)));
        } else {
            return false;
        }
    }

    function getCache($url)
    {
        $cachefile = $this->config->get('cache_dir') . DIRECTORY_SEPARATOR .
            md5($url) . 'rest.cachefile';
        if (@file_exists($cachefile)) {
            return unserialize(implode('', file($cachefile)));
        }
    }

    function saveCache($url, $contents, $lastmodified)
    {
        $cacheidfile = $this->config->get('cache_dir') . DIRECTORY_SEPARATOR .
            md5($url) . 'rest.cacheid';
        $cachefile = $this->config->get('cache_dir') . DIRECTORY_SEPARATOR .
            md5($url) . 'rest.cachefile';
        $fp = @fopen($cacheidfile, 'wb');
        if (!$fp) {
            return false;
        }
        fwrite($fp, serialize($lastmodified));
        fclose($fp);
        $fp = @fopen($cachefile, 'wb');
        if (!$fp) {
            @unlink($cacheidfile);
            return false;
        }
        fwrite($fp, serialize($contents));
        fclose($fp);
        return true;
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
        $info = $this->retrieveData($base . 'r/' . strtolower($package) . '/allreleases.xml');
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
            if ($installed && version_compare($release['v'], $installed, '<')) {
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
        if ($found) {
            $releaseinfo = $this->retrieveData($base . 'r/' . strtolower($package) . '/' . 
                $release['v'] . '.xml');
            if (PEAR::isError($releaseinfo)) {
                return $releaseinfo;
            }
            $packagexml = $this->retrieveData($base . 'r/' . strtolower($package) . '/' .
                'deps.' . $release['v'] . '.txt', false, true);
            if (PEAR::isError($packagexml)) {
                return $packagexml;
            }
            return 
                array('version' => $releaseinfo['v'],
                      'info' => unserialize($packagexml),
                      'package' => $releaseinfo['p']['_content'],
                      'url' => $releaseinfo['g']);
        } else {
            $release = $info['r'][0];
            $releaseinfo = $this->retrieveData($base . 'r/' . strtolower($package) . '/' . 
                $release['v'] . '.xml');
            if (PEAR::isError($releaseinfo)) {
                return $releaseinfo;
            }
            $packagexml = unserialize($this->retrieveData($base . 'r/' . strtolower($package) . '/' .
                'deps.' . $release['v'] . '.txt', false, true));
            if (PEAR::isError($packagexml)) {
                return $packagexml;
            }
            return
                array('version' => $releaseinfo['v'],
                      'package' => $releaseinfo['p']['_content'],
                      'info' => unserialize($packagexml));
        }
    }
}
?>