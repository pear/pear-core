<?php
require_once 'PEAR/PackageFile/Generator/PHP5/Common.php';
class PEAR_PackageFile_Generator_PHP5_v1 extends PEAR_PackageFile_Generator_PHP5_Common
{
    public function toXml()
    {
        // re-format the dom stuff
        parent::toXml();
        // do special formatting for short tags like name
        return $this->pf->dom->saveXml();
    }
}
?>