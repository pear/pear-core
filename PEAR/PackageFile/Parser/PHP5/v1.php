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
    static public function parse($data)
    {
        $dom = new DOMDocument;
        if (!@$dom->loadXml($data)) {
            throw new Exception('(this will be PEAR_Exception) invalid package.xml, not xml');
        }
        include_once 'PEAR/PackageFile/PHP5/v1.php';
        $p = new PEAR_PackageFile_PHP5_v1;
        $p->fromDom($dom);
        return $p;
    }
}
/*
leaving in because I might move things around
set_include_path('C:/devel/pear_with_channels');
//$data = file_get_contents('C:/php4/pear_pkgs/Auth/package.xml');
$data = file_get_contents('C:/devel/pear_with_channels/package-PEAR.xml');
$a = PEAR_PackageFile_Parser_PHP5_v1::parse($data);
if (!$a->validate()) {
    var_dump($a->getValidationWarnings());
} else {
    require_once 'PEAR/PackageFile/Generator/PHP5/v1.php';
    $b = new PEAR_PackageFile_Generator_PHP5_v1($a);
    echo $b->toXml();
}
*/
?>