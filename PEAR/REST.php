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
    function PEAR_REST(&$ui, &$config)
    {
        parent::PEAR_Downloader($ui, array(), $config);
    }

    function retrieveData($url, $accept = false)
    {
        $cacheId = $this->getCacheId($url);
        $file = $this->downloadHttp($url, $this->ui, $this->getDownloadDir(), null, $cacheId,
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
        if (isset($headers['content-type'])) {
            switch ($headers['content-type']) {
                case 'text/xml' :
                    $parser = new PEAR_XMLParser;
                    $parser->parse($file);
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
        $this->saveCache($url, $contents, $lastmodified);
        return $contents;
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
            return implode('', file($cachefile));
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
}
?>