<?php
// quick-load, small memory footprint command configuration
$implements = array(
        'install' => array(
            'summary' => 'Install Package',
            'function' => 'doInstall',
            'shortcut' => 'i',
            'options' => array(
                'force' => array(
                    'shortopt' => 'f',
                    'doc' => 'will overwrite newer installed packages',
                    ),
                'nodeps' => array(
                    'shortopt' => 'n',
                    'doc' => 'ignore dependencies, install anyway',
                    ),
                'register-only' => array(
                    'shortopt' => 'r',
                    'doc' => 'do not install files, only register the package as installed',
                    ),
                'soft' => array(
                    'shortopt' => 's',
                    'doc' => 'soft install, fail silently, or upgrade if already installed',
                    ),
                'nobuild' => array(
                    'shortopt' => 'B',
                    'doc' => 'don\'t build C extensions',
                    ),
                'nocompress' => array(
                    'shortopt' => 'Z',
                    'doc' => 'request uncompressed files when downloading',
                    ),
                'installroot' => array(
                    'shortopt' => 'R',
                    'arg' => 'DIR',
                    'doc' => 'root directory used when installing files (ala PHP\'s INSTALL_ROOT)',
                    ),
                'ignore-errors' => array(
                    'doc' => 'force install even if there were errors',
                    ),
                'alldeps' => array(
                    'shortopt' => 'a',
                    'doc' => 'install all required and optional dependencies',
                    ),
                'onlyreqdeps' => array(
                    'shortopt' => 'o',
                    'doc' => 'install all required dependencies',
                    ),
                'remoteconfig' => array(
                    'shortopt' => 'F',
                    'arg' => 'URL',
                    'doc' => 'also install to ftp site using remote config file (ftp://host.com/pear.conf)'
                    ),
                'offline' => array(
                    'shortopt' => 'O',
                    'doc' => 'do not attempt to download any urls or contact channels',
                    ),
                'pretend' => array(
                    'shortopt' => 'p',
                    'doc' => 'Only list the packages that would be downloaded',
                    ),
                ),
            'doc' => '[channel/]<package> ...
Installs one or more PEAR packages.  You can specify a package to
install in four ways:

"Package-1.0.tgz" : installs from a local file

"http://example.com/Package-1.0.tgz" : installs from
anywhere on the net.

"package.xml" : installs the package described in
package.xml.  Useful for testing, or for wrapping a PEAR package in
another package manager such as RPM.

"Package[-version/state][.tar]" : queries your default channel\'s server
({config master_server}) and downloads the newest package with
the preferred quality/state ({config preferred_state}).

To retrieve Package version 1.1, use "Package-1.1," to retrieve
Package state beta, use "Package-beta."  To retrieve an uncompressed
file, append .tar (make sure there is no file by the same name first)

To download a package from another channel, prefix with the channel name like
"channel/Package"

More than one package may be specified at once.  It is ok to mix these
four ways of specifying packages.
'),
        'upgrade' => array(
            'summary' => 'Upgrade Package',
            'function' => 'doInstall',
            'shortcut' => 'up',
            'options' => array(
                'force' => array(
                    'shortopt' => 'f',
                    'doc' => 'overwrite newer installed packages',
                    ),
                'nodeps' => array(
                    'shortopt' => 'n',
                    'doc' => 'ignore dependencies, upgrade anyway',
                    ),
                'register-only' => array(
                    'shortopt' => 'r',
                    'doc' => 'do not install files, only register the package as upgraded',
                    ),
                'nobuild' => array(
                    'shortopt' => 'B',
                    'doc' => 'don\'t build C extensions',
                    ),
                'nocompress' => array(
                    'shortopt' => 'Z',
                    'doc' => 'request uncompressed files when downloading',
                    ),
                'installroot' => array(
                    'shortopt' => 'R',
                    'arg' => 'DIR',
                    'doc' => 'root directory used when installing files (ala PHP\'s INSTALL_ROOT)',
                    ),
                'ignore-errors' => array(
                    'doc' => 'force install even if there were errors',
                    ),
                'alldeps' => array(
                    'shortopt' => 'a',
                    'doc' => 'install all required and optional dependencies',
                    ),
                'onlyreqdeps' => array(
                    'shortopt' => 'o',
                    'doc' => 'install all required dependencies',
                    ),
                'remoteconfig' => array(
                    'shortopt' => 'F',
                    'arg' => 'URL',
                    'doc' => 'also upgrade on ftp site using remote config file (ftp://host.com/pear.conf)'
                    ),
                'offline' => array(
                    'shortopt' => 'O',
                    'doc' => 'do not attempt to download any urls or contact channels',
                    ),
                'pretend' => array(
                    'shortopt' => 'p',
                    'doc' => 'Only list the packages that would be downloaded',
                    ),
                ),
            'doc' => '<package> ...
Upgrades one or more PEAR packages.  See documentation for the
"install" command for ways to specify a package.

When upgrading, your package will be updated if the provided new
package has a higher version number (use the -f option if you need to
upgrade anyway).

More than one package may be specified at once.
'),
        'upgrade-all' => array(
            'summary' => 'Upgrade All Packages',
            'function' => 'doInstall',
            'shortcut' => 'ua',
            'options' => array(
                'nodeps' => array(
                    'shortopt' => 'n',
                    'doc' => 'ignore dependencies, upgrade anyway',
                    ),
                'register-only' => array(
                    'shortopt' => 'r',
                    'doc' => 'do not install files, only register the package as upgraded',
                    ),
                'nobuild' => array(
                    'shortopt' => 'B',
                    'doc' => 'don\'t build C extensions',
                    ),
                'nocompress' => array(
                    'shortopt' => 'Z',
                    'doc' => 'request uncompressed files when downloading',
                    ),
                'installroot' => array(
                    'shortopt' => 'R',
                    'arg' => 'DIR',
                    'doc' => 'root directory used when installing files (ala PHP\'s INSTALL_ROOT)',
                    ),
                'ignore-errors' => array(
                    'doc' => 'force install even if there were errors',
                    ),
                'remoteconfig' => array(
                    'shortopt' => 'F',
                    'arg' => 'URL',
                    'doc' => 'also upgrade on ftp site using remote config file (ftp://host.com/pear.conf)'
                    ),
                ),
            'doc' => '
Upgrades all packages that have a newer release available.  Upgrades are
done only if there is a release available of the state specified in
"preferred_state" (currently {config preferred_state}), or a state considered
more stable.
'),
        'uninstall' => array(
            'summary' => 'Un-install Package',
            'function' => 'doUninstall',
            'shortcut' => 'un',
            'options' => array(
                'nodeps' => array(
                    'shortopt' => 'n',
                    'doc' => 'ignore dependencies, uninstall anyway',
                    ),
                'register-only' => array(
                    'shortopt' => 'r',
                    'doc' => 'do not remove files, only register the packages as not installed',
                    ),
                'installroot' => array(
                    'shortopt' => 'R',
                    'arg' => 'DIR',
                    'doc' => 'root directory used when installing files (ala PHP\'s INSTALL_ROOT)',
                    ),
                'ignore-errors' => array(
                    'doc' => 'force install even if there were errors',
                    ),
                'remoteconfig' => array(
                    'shortopt' => 'F',
                    'arg' => 'URL',
                    'doc' => 'also uninstall on ftp site using remote config file (ftp://host.com/pear.conf)'
                    ),
                'offline' => array(
                    'shortopt' => 'O',
                    'doc' => 'do not attempt to uninstall remotely',
                    ),
                ),
            'doc' => '[channel/]<package> ...
Uninstalls one or more PEAR packages.  More than one package may be
specified at once.  Prefix with channel name to uninstall from a
channel not in your default channel ({config default_channel})
'),
        'bundle' => array(
            'summary' => 'Unpacks a Pecl Package',
            'function' => 'doBundle',
            'shortcut' => 'bun',
            'options' => array(
                'destination' => array(
                   'shortopt' => 'd',
                    'arg' => 'DIR',
                    'doc' => 'Optional destination directory for unpacking (defaults to current path or "ext" if exists)',
                    ),
                'force' => array(
                    'shortopt' => 'f',
                    'doc' => 'Force the unpacking even if there were errors in the package',
                ),
            ),
            'doc' => '<package>
Unpacks a Pecl Package into the selected location. It will download the
package if needed.
'),
        'run-scripts' => array(
            'summary' => 'Run Post-Install Scripts bundled with a package',
            'function' => 'doRunScripts',
            'shortcut' => 'rs',
            'options' => array(
            ),
            'doc' => '<package>
Run post-installation scripts in package <package>, if any exist.
'),
    );
?>