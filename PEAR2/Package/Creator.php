<?php
class PEAR2_Package_Creator
{
    private $_creators;
    /**
     * Begin package creation
     *
     * @param array|PEAR2_Package_ICreator $creators
     */
    function __construct($creators)
    {
        if ($creators instanceof PEAR2_Package_ICreator) {
            $this->_creators = array($creators);
        } elseif (is_array($creators)) {
            foreach ($creators as $creator) {
                if ($creator instanceof PEAR2_Package_ICreator) {
                    continue;
                }
                throw new PEAR2_Package_Creator_Exception('Invalid ' .
                    'PEAR2 package creator passed into PEAR2_Package_Creator');
            }
            $this->_creators = $creators;
        } else {
            throw new PEAR2_Package_Creator_Exception('Invalid ' .
                'PEAR2 package creator passed into PEAR2_Package_Creator');
        }
    }

    function render(PEAR2_Package $package)
    {
        $package->flattenFilelist();
        $contents = $package->getContents();
        $files = $contents['dir']['file'];
        if (!isset($files[0])) {
            $files = array($files);
        }
        foreach ($this->_creators as $creator) {
            $creator->init();
        }
        $packagexml = 'package-' . $package->getChannel() . '-' . $package->getName() .
            $package->getVersion() . '.xml';
        // TODO: add package.xml serialization based on package.xml from original
        // PEAR2_Package, and use that to save the package.xml here
        $creator->addFile($packagexml, '');
        foreach (new PEAR2_Package_Creator_FilelistIterator($files, $package) as $file => $packageat) {
            foreach ($this->_creators as $creator) {
                $creator->mkdir(dirname($packageat));
                $creator->addFile($packageat, $package->getFileContents($file));
            }
        }
        foreach ($this->_creators as $creator) {
            $creator->close();
        }
    }
}