<?php
/**
 * Parser for package.xml version 1.0
 */
class PEAR_PackageFile_Parser_PHP5_v1
{
    private $_registry;
    public function setRegistry($r)
    {
        $this->_registry = $r;
    }

    /**
     * @param string contents of package.xml file, version 1.0
     * @return PEAR_PackageFile_PHP5_v1
     */
    public function parse($data, $state, $file)
    {
        $dom = new DOMDocument;
        if (!@$dom->loadXml($data)) {
            // can't use exceptions - same code calls php4 and php5 in installer :(
        }
        include_once 'PEAR/PackageFile/PHP5/v1.php';
        $p = new PEAR_PackageFile_PHP5_v1;
        $p->setRegistry($this->_registry);
        $p->fromDom($dom, $state, $file);
        return $p;
    }
}
/*
leaving in because I might move things around

set_include_path('C:/devel/pear_with_channels');
require_once 'PEAR/Validate.php';
//$data = file_get_contents('C:/php4/pear_pkgs/Auth/package.xml');
$data = file_get_contents($file = 'C:/devel/pear_with_channels/package-PEAR.xml');
$d = new PEAR_PackageFile_Parser_PHP5_v1;
$d->setRegistry(null);
$a = $d->parse($data, PEAR_VALIDATE_NORMAL, $file);
if (!$a->validate(PEAR_VALIDATE_INSTALLING)) {
    var_dump($a->getValidationWarnings());
} else {
    require_once 'PEAR/PackageFile/Generator/PHP5/v1.php';
    $b = new PEAR_PackageFile_Generator_PHP5_v1($a);
    echo $b->toXml();
}
*/
?>