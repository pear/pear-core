<?php
/**
 * Parser for package.xml version 1.0
 */
class PEAR_PackageFile_Parser_PHP5_v1
{
    /**
     * @param string contents of package.xml file, version 1.0
     * @return PEAR_PackageFile_PHP5_v1
     */
    public function parse($data)
    {
        $dom = new DOMDocument;
        if (!@$dom->loadXml()) {
            throw new Exception('(this will be PEAR_Exception) invalid package.xml, not xml');
        }
        include_once 'PEAR/PackageFile/PHP5/v1.php';
        $p = new PEAR_PackageFile_PHP5_v1;
        $p->fromDom($dom);
        return $p;
    }
}
?>