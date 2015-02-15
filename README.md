PEAR - The PEAR Installer
=========================

What is the PEAR Installer? What is PEAR?
------------------------------------------

PEAR is the PHP Extension and Application Repository, found at
http://pear.php.net.  The PEAR Installer is this software, which
contains executable files and PHP code that is used to download
and install PEAR code from pear.php.net.

PEAR contains useful software libraries and applications such as
MDB2 (database abstraction), HTML_QuickForm (HTML forms management),
PhpDocumentor (auto-documentation generator), DB_DataObject
(Data Access Abstraction), and many hundreds more.  Browse all
available packages at http://pear.php.net, the list is constantly
growing and updating to reflect improvements in the PHP language.

**WARNING: do not run PEAR without installing it** - if you downloaded this
tarball manually, you MUST install it.  Read the instructions in INSTALL
prior to use.


Documentation
-------------

Documentation for PEAR can be found at http://pear.php.net/manual/.
Installation documentation can be found in the INSTALL file included
in this tarball.


Tests
-----

Run the tests without installation as follows:

```sh
$ ./scripts/pear.sh run-tests -r tests
```

You should have the Text_Diff package installed to get nicer error output.

Happy PHPing, we hope PEAR will be a great tool for your development work!
