<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'pickle' => array(
            'summary' => 'Build PECL Package',
            'function' => 'doPackage',
            'shortcut' => 'pi',
            'options' => array(
                'nocompress' => array(
                    'shortopt' => 'Z',
                    'doc' => 'Do not gzip the package file'
                    ),
                'showname' => array(
                    'shortopt' => 'n',
                    'doc' => 'Print the name of the packaged file.',
                    ),
                ),
            'doc' => '[descfile] [descfile2]
Creates a PECL package from its description file (usually called
package.xml).  If a second packagefile is passed in, then
the packager will check to make sure that one is a package.xml
version 1.0, and the other is a package.xml version 2.0.  The
package.xml version 1.0 will be saved as "package.xml" in the archive,
and the other as "package2.xml" in the archive"

If no second file is passed in, and [descfile] is a package.xml 2.0,
an automatic conversion will be made to a package.xml 1.0.  Note that
only simple package.xml 2.0 will be converted.  package.xml 2.0 with:

 - dependency types other than required/optional PECL package/ext/php/pearinstaller
 - more than one extsrcrelease
 - extbinrelease, phprelease, or bundle release type
 - dependency groups
 - ignore tags in release filelist
 - tasks other than replace
 - custom roles

will cause pickle to fail, and output an error message.  If your package2.xml
uses any of these features, you are best off using PEAR_PackageFileManager to
generate both package.xml.'
            ),
        );
?>