--TEST--
list-all command
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(1803);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';
$reg = &$config->getRegistry();
$pf = new PEAR_PackageFile_v1;
$pf->setConfig($config);
$pf->setPackage('Archive_Zip');
$pf->setSummary('foo');
$pf->setDate(date('Y-m-d'));
$pf->setDescription('foo');
$pf->setVersion('1.0.0');
$pf->setState('stable');
$pf->setLicense('PHP License');
$pf->setNotes('foo');
$pf->addMaintainer('lead', 'cellog', 'Greg', 'cellog@php.net');
$pf->addFile('', 'foo.dat', array('role' => 'data'));
$pf->addPhpDep('4.0.0', 'ge');
$pf->validate();
$phpunit->assertNoErrors('setup');
$reg->addPackage2($pf);
$pearweb->addXmlrpcConfig("empty", "package.listAll",     array(true, true),
     array(
    ));
$pearweb->addXmlrpcConfig("smoog", "package.listAll",     array(true, true),
     array(
    'APC' =>
        array(
        'packageid' =>
            "220",
        'categoryid' =>
            "3",
        'category' =>
            "Caching",
        'license' =>
            "PHP",
        'summary' =>
            "Alternative PHP Cache",
        'description' =>
            "APC is the Alternative PHP Cache. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.",
        'lead' =>
            "rasmus",
        'stable' =>
            "2.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    ));
$pearweb->addXmlrpcConfig("pear.php.net", "package.listAll",     array(true, true, false),     array(
    'APC' =>
        array(
        'packageid' =>
            "220",
        'categoryid' =>
            "3",
        'category' =>
            "Caching",
        'license' =>
            "PHP",
        'summary' =>
            "Alternative PHP Cache",
        'description' =>
            "APC is the Alternative PHP Cache. It was conceived of to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.",
        'lead' =>
            "rasmus",
        'stable' =>
            "2.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'apd' =>
        array(
        'packageid' =>
            "118",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "PHP License",
        'summary' =>
            "A full-featured engine-level profiler/debugger",
        'description' =>
            "APD is a full-featured profiler/debugger that is loaded as a zend_extension.  It aims to be an analog of C\'s gprof or Perl\'s Devel::DProf.",
        'lead' =>
            "gschlossnagle",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Archive_Tar' =>
        array(
        'packageid' =>
            "24",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License",
        'summary' =>
            "Tar file management class",
        'description' =>
            "This class provides handling of tar files in PHP.
It supports creating, listing, extracting and adding to tar files.
Gzip support is available if PHP has the zlib extension built-in or
loaded. Bz2 compression is also supported with the bz2 extension loaded.",
        'lead' =>
            "vblavet",
        'stable' =>
            "1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Auth' =>
        array(
        'packageid' =>
            "2",
        'categoryid' =>
            "1",
        'category' =>
            "Authentication",
        'license' =>
            "PHP License",
        'summary' =>
            "Creating an authentication system.",
        'description' =>
            "The PEAR::Auth package provides methods for creating an authentication
system using PHP.

Currently it supports the following storage containers to read/write
the login data:

* All databases supported by the PEAR database layer
* All databases supported by the MDB database layer
* All databases supported by the MDB2 database layer
* Plaintext files
* LDAP servers
* POP3 servers
* IMAP servers
* vpopmail accounts
* RADIUS
* SAMBA password files
* SOAP",
        'lead' =>
            "yavo",
        'stable' =>
            "1.2.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Auth_HTTP' =>
        array(
        'packageid' =>
            "1",
        'categoryid' =>
            "1",
        'category' =>
            "Authentication",
        'license' =>
            "PHP License",
        'summary' =>
            "HTTP authentication",
        'description' =>
            "The PEAR::Auth_HTTP class provides methods for creating an HTTP
authentication system using PHP, that is similar to Apache\'s
realm-based .htaccess authentication.",
        'lead' =>
            "hirokawa",
        'stable' =>
            "2.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Auth_PrefManager' =>
        array(
        'packageid' =>
            "144",
        'categoryid' =>
            "1",
        'category' =>
            "Authentication",
        'license' =>
            "PHP License",
        'summary' =>
            "Preferences management class",
        'description' =>
            "Preference Manager is a class to handle user preferences in a web application, looking them up in a table
using a combination of their userid, and the preference name to get a value, and (optionally) returning
a default value for the preference if no value could be found for that user.

It is designed to be used alongside the PEAR Auth class, but can be used with anything that allows you
to obtain the user\'s id - including your own code.",
        'lead' =>
            "jellybob",
        'stable' =>
            "1.1.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Auth_RADIUS' =>
        array(
        'packageid' =>
            "170",
        'categoryid' =>
            "1",
        'category' =>
            "Authentication",
        'license' =>
            "BSD",
        'summary' =>
            "Wrapper Classes for the RADIUS PECL.",
        'description' =>
            "This package provides wrapper-classes for the RADIUS PECL.
There are different Classes for the different authentication methods.
If you are using CHAP-MD5 or MS-CHAP you need also the Crypt_CHAP package.
If you are using MS-CHAP you need also the mhash and mcrypt extension.",
        'lead' =>
            "mbretter",
        'stable' =>
            "1.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Auth_SASL' =>
        array(
        'packageid' =>
            "123",
        'categoryid' =>
            "1",
        'category' =>
            "Authentication",
        'license' =>
            "BSD",
        'summary' =>
            "Abstraction of various SASL mechanism responses",
        'description' =>
            "Provides code to generate responses to common SASL mechanisms, including:
o Digest-MD5
o CramMD5
o Plain
o Anonymous
o Login (Pseudo mechanism)",
        'lead' =>
            "damian",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Benchmark' =>
        array(
        'packageid' =>
            "53",
        'categoryid' =>
            "2",
        'category' =>
            "Benchmarking",
        'license' =>
            "PHP License",
        'summary' =>
            "Framework to benchmark PHP scripts or function calls.",
        'description' =>
            "Framework to benchmark PHP scripts or function calls.",
        'lead' =>
            "sebastian",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'bz2' =>
        array(
        'packageid' =>
            "209",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License",
        'summary' =>
            "A Bzip2 management extension",
        'description' =>
            "Bz2 is an extension to create and parse bzip2 compressed data.",
        'lead' =>
            "sterling",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Cache' =>
        array(
        'packageid' =>
            "40",
        'categoryid' =>
            "3",
        'category' =>
            "Caching",
        'license' =>
            "PHP License",
        'summary' =>
            "Framework for caching of arbitrary data.",
        'description' =>
            "With the PEAR Cache you can cache the result of certain function
calls, as well as the output of a whole script run or share data
between applications.",
        'lead' =>
            "dufuz",
        'stable' =>
            "1.5.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Cache_Lite' =>
        array(
        'packageid' =>
            "99",
        'categoryid' =>
            "3",
        'category' =>
            "Caching",
        'license' =>
            "lgpl",
        'summary' =>
            "Fast and Safe little cache system",
        'description' =>
            "This package is a little cache system optimized for file containers. It is fast and safe (because it uses file locking and/or anti-corruption tests).",
        'lead' =>
            "fab",
        'stable' =>
            "1.3.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Config' =>
        array(
        'packageid' =>
            "3",
        'categoryid' =>
            "4",
        'category' =>
            "Configuration",
        'license' =>
            "PHP License",
        'summary' =>
            "Your configurations swiss-army knife.",
        'description' =>
            "The Config package provides methods for configuration manipulation.
* Creates configurations from scratch
* Parses and outputs different formats (XML, PHP, INI, Apache...)
* Edits existing configurations
* Converts configurations to other formats
* Allows manipulation of sections, comments, directives...
* Parses configurations into a tree structure
* Provides XPath like access to directives",
        'lead' =>
            "mansion",
        'stable' =>
            "1.10.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Console_Getargs' =>
        array(
        'packageid' =>
            "333",
        'categoryid' =>
            "5",
        'category' =>
            "Console",
        'license' =>
            "PHP License",
        'summary' =>
            "A command-line arguments parser",
        'description' =>
            "The Console_Getargs package implements a Command Line arguments and
parameters parser for your CLI applications. It performs some basic
arguments validation and automatically creates a formatted help text,
based on the given configuration.",
        'lead' =>
            "scottmattocks",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Console_Getopt' =>
        array(
        'packageid' =>
            "67",
        'categoryid' =>
            "5",
        'category' =>
            "Console",
        'license' =>
            "PHP License",
        'summary' =>
            "Command-line option parser",
        'description' =>
            "This is a PHP implementation of \"getopt\" supporting both
short and long options.",
        'lead' =>
            "andrei",
        'stable' =>
            "1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Console_Table' =>
        array(
        'packageid' =>
            "109",
        'categoryid' =>
            "5",
        'category' =>
            "Console",
        'license' =>
            "BSD",
        'summary' =>
            "Class that makes it easy to build console style tables",
        'description' =>
            "Provides methods such as addRow(), insertRow(), addCol() etc to build Console
tables. Can be with or without headers, and has various configurable options.",
        'lead' =>
            "xnoguer",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Contact_Vcard_Build' =>
        array(
        'packageid' =>
            "191",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License",
        'summary' =>
            "Build (create) and fetch vCard 2.1 and 3.0 text blocks.",
        'description' =>
            "Allows you to programmatically create a vCard, version 2.1 or 3.0, and fetch the vCard text.",
        'lead' =>
            "pmjones",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Contact_Vcard_Parse' =>
        array(
        'packageid' =>
            "186",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License",
        'summary' =>
            "Parse vCard 2.1 and 3.0 files.",
        'description' =>
            "Allows you to parse vCard files and text blocks, and get back an array of the elements of each vCard in the file or text.",
        'lead' =>
            "pmjones",
        'stable' =>
            "1.30",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'crack' =>
        array(
        'packageid' =>
            "293",
        'categoryid' =>
            "29",
        'category' =>
            "Tools and Utilities",
        'license' =>
            "Artistic",
        'summary' =>
            "\"Good Password\" Checking Utility: Keep your users\' passwords reasonably safe from dictionary based attacks",
        'description' =>
            "This package provides an interface to the cracklib (libcrack) libraries that come standard on most unix-like distributions. This allows you to check passwords against dictionaries of words to ensure some minimal level of password security.

From the cracklib README
CrackLib makes literally hundreds of tests to determine whether you\'ve
chosen a bad password.

* It tries to generate words from your username and gecos entry to tries
to match them against what you\'ve chosen.

* It checks for simplistic patterns.

* It then tries to reverse-engineer your password into a dictionary
word, and searches for it in your dictionary.

- after all that, it\'s PROBABLY a safe(-ish) password. 8-)


The crack extension requires cracklib (libcrack) 2.7, some kind of word dictionary, and the proper header files (crack.h and packer.h) to build. For cracklib RPMs for Red Hat systems and a binary distribution for Windows systems, visit http://www.dragonstrider.com/cracklib.",
        'lead' =>
            "skettler",
        'stable' =>
            "0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Crypt_CBC' =>
        array(
        'packageid' =>
            "48",
        'categoryid' =>
            "6",
        'category' =>
            "Encryption",
        'license' =>
            "PHP 2.02",
        'summary' =>
            "A class to emulate Perl\'s Crypt::CBC module.",
        'description' =>
            "A class to emulate Perl\'s Crypt::CBC module.",
        'lead' =>
            "cmv",
        'stable' =>
            "0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Crypt_CHAP' =>
        array(
        'packageid' =>
            "169",
        'categoryid' =>
            "6",
        'category' =>
            "Encryption",
        'license' =>
            "BSD",
        'summary' =>
            "Generating CHAP packets.",
        'description' =>
            "This package provides Classes for generating CHAP packets.
Currently these types of CHAP are supported:
* CHAP-MD5
* MS-CHAPv1
* MS-CHAPv2
For MS-CHAP the mhash and mcrypt extensions must be loaded.",
        'lead' =>
            "mbretter",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Crypt_RC4' =>
        array(
        'packageid' =>
            "50",
        'categoryid' =>
            "6",
        'category' =>
            "Encryption",
        'license' =>
            "PHP",
        'summary' =>
            "Encryption class for RC4 encryption",
        'description' =>
            "RC4 encryption class",
        'lead' =>
            "zyprexia",
        'stable' =>
            "1.0.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Crypt_Xtea' =>
        array(
        'packageid' =>
            "110",
        'categoryid' =>
            "6",
        'category' =>
            "Encryption",
        'license' =>
            "PHP 2.02",
        'summary' =>
            "A class that implements the Tiny Encryption Algorithm (TEA) (New Variant).",
        'description' =>
            "A class that implements the Tiny Encryption Algorithm (TEA) (New Variant).
This class does not depend on mcrypt.
Since the latest fix handles properly dealing with unsigned integers,
which where solved by introducing new functions _rshift(), _add(), the
speed of the encryption and decryption has radically dropped.
Do not use for large amounts of data.
Original code from http://vader.brad.ac.uk/tea/source.shtml#new_ansi
Currently to be found at: http://www.simonshepherd.supanet.com/source.shtml#new_ansi",
        'lead' =>
            "jderks",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'cybermut' =>
        array(
        'packageid' =>
            "199",
        'categoryid' =>
            "18",
        'category' =>
            "Payment",
        'license' =>
            "PHP License",
        'summary' =>
            "CyberMut Paiement System",
        'description' =>
            "This extension gives you the possibility to use the CyberMut Paiement System of the Credit Mutuel (French Bank).",
        'lead' =>
            "nicos",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'cyrus' =>
        array(
        'packageid' =>
            "210",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "An extension which eases the manipulation of Cyrus IMAP servers.",
        'description' =>
            "An extension which eases the manipulation of Cyrus IMAP servers.",
        'lead' =>
            "sterling",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Date' =>
        array(
        'packageid' =>
            "57",
        'categoryid' =>
            "8",
        'category' =>
            "Date and Time",
        'license' =>
            "PHP License",
        'summary' =>
            "Date and Time Zone Classes",
        'description' =>
            "Generic classes for representation and manipulation of
dates, times and time zones without the need of timestamps,
which is a huge limitation for php programs.  Includes time zone data,
time zone conversions and many date/time conversions.
It does not rely on 32-bit system date stamps, so
you can display calendars and compare dates that date
pre 1970 and post 2038. This package also provides a class
to convert date strings between Gregorian and Human calendar formats.",
        'lead' =>
            "pajoye",
        'stable' =>
            "1.4.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB' =>
        array(
        'packageid' =>
            "46",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP License",
        'summary' =>
            "Database Abstraction Layer",
        'description' =>
            "DB is a database abstraction layer providing:
* an OO-style query API
* portability features that make programs written for one DBMS work with other DBMS\'s
* a DSN (data source name) format for specifying database servers
* prepare/execute (bind) emulation for databases that don\'t support it natively
* a result object for each query response
* portable error codes
* sequence emulation
* sequential and non-sequential row fetching as well as bulk fetching
* formats fetched rows as associative arrays, ordered arrays or objects
* row limit support
* transactions support
* table information interface
* DocBook and PHPDoc API documentation

DB layers itself on top of PHP\'s existing database
extensions.  The currently supported extensions are:
dbase, fbsql, interbase, informix, msql, mssql, mysql,
mysqli, oci8, odbc, pgsql, sqlite and sybase.

DB is compatible with both PHP 4 and PHP 5.",
        'lead' =>
            "danielc",
        'stable' =>
            "1.6.8",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DBA' =>
        array(
        'packageid' =>
            "85",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "LGPL",
        'summary' =>
            "Berkely-style database abstraction class",
        'description' =>
            "DBA is a wrapper for the php DBA functions. It includes a file-based emulator and provides a uniform, object-based interface for the Berkeley-style database systems.",
        'lead' =>
            "busterb",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_ado' =>
        array(
        'packageid' =>
            "68",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "LGPL",
        'summary' =>
            "DB driver which use MS ADODB library",
        'description' =>
            "DB_ado is a database independent query interface definition for Microsoft\'s ADODB library using PHP\'s COM extension.
This class allows you to connect to different data sources like MS Access, MS SQL Server, Oracle and other RDBMS on a Win32 operating system.
Moreover the possibility exists to use MS Excel spreadsheets, XML, text files and other not relational data as data source.",
        'lead' =>
            "alexios",
        'stable' =>
            "1.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_DataObject' =>
        array(
        'packageid' =>
            "80",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP License",
        'summary' =>
            "An SQL Builder, Object Interface to Database Tables",
        'description' =>
            "DataObject performs 2 tasks:
  1. Builds SQL statements based on the objects vars and the builder methods.
  2. acts as a datastore for a table row.
  The core class is designed to be extended for each of your tables so that you put the
  data logic inside the data classes.
  included is a Generator to make your configuration files and your base classes.
  nd",
        'lead' =>
            "alan_k",
        'stable' =>
            "1.7.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_ldap' =>
        array(
        'packageid' =>
            "101",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "LGPL",
        'summary' =>
            "DB interface to LDAP server",
        'description' =>
            "The PEAR::DB_ldap class provides a DB compliant interface to LDAP servers",
        'lead' =>
            "ludoo",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_NestedSet' =>
        array(
        'packageid' =>
            "187",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP License",
        'summary' =>
            "API to build and query nested sets",
        'description' =>
            "DB_NestedSet let\'s you create trees with infinite depth
inside a relational database.
The package provides a way to
o create/update/delete nodes
o query nodes, trees and subtrees
o copy (clone) nodes, trees and subtrees
o move nodes, trees and subtrees
o Works with PEAR::DB, PEAR::MDB, PEAR::MDB2
o output the tree with
  - PEAR::HTML_TreeMenu
  - TigraMenu (http://www.softcomplex.com/products/tigra_menu/)
  - CoolMenus (http://www.dhtmlcentral.com/projects/coolmenus/)
  - PEAR::Image_GraphViz (http://pear.php.net/package/Image_GraphViz)
  - PEAR::HTML_Menu",
        'lead' =>
            "datenpunk",
        'stable' =>
            "1.2.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_odbtp' =>
        array(
        'packageid' =>
            "397",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP",
        'summary' =>
            "DB interface for ODBTP",
        'description' =>
            "DB_odbtp is a PEAR DB driver that uses the ODBTP extension to connect to a database.
It can be used to remotely access any Win32-ODBC accessible database from any platform.",
        'lead' =>
            "rtwitty",
        'stable' =>
            "1.0.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_Pager' =>
        array(
        'packageid' =>
            "31",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "LGPL",
        'summary' =>
            "Retrieve and return information of database result sets",
        'description' =>
            "This class handles all the stuff needed for displaying
paginated results from a database query of Pear DB.
including fetching only the needed rows and giving extensive information
for helping build an HTML or GTK query result display.",
        'lead' =>
            "quipo",
        'stable' =>
            "0.7",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'DB_QueryTool' =>
        array(
        'packageid' =>
            "163",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP",
        'summary' =>
            "An OO-interface for easily retrieving and modifying data in a DB.",
        'description' =>
            "This package is an OO-abstraction to the SQL-Query language, it provides methods such
as setWhere, setOrder, setGroup, setJoin, etc. to easily build queries.
It also provides an easy to learn interface that interacts nicely with HTML-forms using
arrays that contain the column data, that shall be updated/added in a DB.
This package bases on an SQL-Builder which lets you easily build
SQL-Statements and execute them.",
        'lead' =>
            "quipo",
        'stable' =>
            "0.11.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'enchant' =>
        array(
        'packageid' =>
            "302",
        'categoryid' =>
            "36",
        'category' =>
            "Text",
        'license' =>
            "PHP",
        'summary' =>
            "libenchant binder, support near all spelling tools",
        'description' =>
            "Enchant is a binder for libenchant. Libenchant provides a common
API for many spell libraries:
- aspell/pspell (intended to replace ispell)
- hspell (hebrew)
- ispell 
- myspell (OpenOffice project, mozilla)
- uspell (primarily Yiddish, Hebrew, and Eastern European languages)
A plugin system allows to add custom spell support.
see www.abisource.com/enchant/",
        'lead' =>
            "iliaa",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File' =>
        array(
        'packageid' =>
            "43",
        'categoryid' =>
            "9",
        'category' =>
            "File System",
        'license' =>
            "PHP",
        'summary' =>
            "Common file and directory routines",
        'description' =>
            "Provides easy access to read/write to files along with
some common routines to deal with paths. Also provides
interface for handling CSV files.",
        'lead' =>
            "mike",
        'stable' =>
            "1.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File_Find' =>
        array(
        'packageid' =>
            "27",
        'categoryid' =>
            "9",
        'category' =>
            "File System",
        'license' =>
            "PHP",
        'summary' =>
            "A Class the facillitates the search of filesystems",
        'description' =>
            "File_Find, created as a replacement for its Perl counterpart, also named 
File_Find, is a directory searcher, which handles, globbing, recursive 
directory searching, as well as a slew of other cool features.?",
        'lead' =>
            "tuupola",
        'stable' =>
            "0.2.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File_Fstab' =>
        array(
        'packageid' =>
            "328",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License v3.0",
        'summary' =>
            "Read and write fstab files",
        'description' =>
            "File_Fstab is an easy-to-use package which can read & write UNIX fstab files. It presents a pleasant object-oriented interface to the fstab.
Features:
* Supports blockdev, label, and UUID specification of mount device.
* Extendable to parse non-standard fstab formats by defining a new Entry class for that format.
* Easily examine and set mount options for an entry.
* Stable, functional interface.
* Fully documented with PHPDoc.",
        'lead' =>
            "ieure",
        'stable' =>
            "2.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File_HtAccess' =>
        array(
        'packageid' =>
            "131",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP",
        'summary' =>
            "Manipulate .htaccess files",
        'description' =>
            "Provides methods to create and manipulate .htaccess files.",
        'lead' =>
            "tuupola",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File_Passwd' =>
        array(
        'packageid' =>
            "128",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP",
        'summary' =>
            "Manipulate many kinds of password files",
        'description' =>
            "Provides methods to manipulate and authenticate against standard Unix, 
SMB server, AuthUser (.htpasswd), AuthDigest (.htdigest), CVS pserver 
and custom formatted password files.",
        'lead' =>
            "mike",
        'stable' =>
            "1.1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File_SearchReplace' =>
        array(
        'packageid' =>
            "45",
        'categoryid' =>
            "9",
        'category' =>
            "File System",
        'license' =>
            "BSD",
        'summary' =>
            "Performs search and replace routines",
        'description' =>
            "Provides various functions to perform search/replace
on files. Preg/Ereg regex supported along with faster
but more basic str_replace routine.",
        'lead' =>
            "tal",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'File_SMBPasswd' =>
        array(
        'packageid' =>
            "198",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "BSD",
        'summary' =>
            "Class for managing SAMBA style password files.",
        'description' =>
            "With this package, you can maintain smbpasswd-files, usualy used by SAMBA.",
        'lead' =>
            "mbretter",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'fribidi' =>
        array(
        'packageid' =>
            "190",
        'categoryid' =>
            "28",
        'category' =>
            "Internationalization",
        'license' =>
            "PHP",
        'summary' =>
            "Implementation of the Unicode BiDi algorithm",
        'description' =>
            "A PHP frontend to the FriBidi library: an implementation of the unicode Bidi algorithm,
provides means for handling right-to-left text.",
        'lead' =>
            "tal",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'FSM' =>
        array(
        'packageid' =>
            "134",
        'categoryid' =>
            "31",
        'category' =>
            "Processing",
        'license' =>
            "PHP",
        'summary' =>
            "Finite State Machine",
        'description' =>
            "The FSM package provides a simple class that implements a Finite State Machine.",
        'lead' =>
            "jon",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_BBCodeParser' =>
        array(
        'packageid' =>
            "229",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "This is a parser to replace UBB style tags with their html equivalents.",
        'description' =>
            "This is a parser to replace UBB style tags with their html equivalents.
 It does not simply do some regex calls, but is complete stack based parse engine. This ensures that all tags are properly nested, if not, extra tags are added to maintain the nesting. This parser should only produce xhtml 1.0 compliant code. All tags are validated and so are all their attributes. It should be easy to extend this parser with your own tags.",
        'lead' =>
            "sjr",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Common' =>
        array(
        'packageid' =>
            "69",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "PEAR::HTML_Common is a base class for other HTML classes.",
        'description' =>
            "The PEAR::HTML_Common package provides methods for html code display and attributes handling.
* Methods to set, remove, update html attributes.
* Handles comments in HTML code.
* Handles layout, tabs, line endings for nicer HTML code.",
        'lead' =>
            "avb",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Crypt' =>
        array(
        'packageid' =>
            "112",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "Encrypts text which is later decoded using javascript on the client side",
        'description' =>
            "The PEAR::HTML_Crypt provides methods to encrypt text, which 
   can be later be decrypted using JavaScript on the client side
 
   This is very useful to prevent spam robots collecting email
   addresses from your site, included is a method to add mailto 
   links to the text being generated",
        'lead' =>
            "mikedransfield",
        'stable' =>
            "1.2.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_CSS' =>
        array(
        'packageid' =>
            "233",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License 3.0",
        'summary' =>
            "HTML_CSS is a class for generating CSS declarations.",
        'description' =>
            "HTML_CSS provides a simple interface for generating
a stylesheet declaration. It is completely standards compliant, and
has some great features:
* Simple OO interface to CSS definitions
* Can parse existing CSS (string or file)
* Output to
    - Inline stylesheet declarations
    - Document internal stylesheet declarations
    - Standalone stylesheet declarations
    - Array of definitions
    - File

In addition, it shares the following with HTML_Common based classes:
* Indent style support
* Line ending style",
        'lead' =>
            "farell",
        'stable' =>
            "0.2.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Form' =>
        array(
        'packageid' =>
            "157",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "Simple HTML form package",
        'description' =>
            "This is a simple HTML form generator.  It supports all the
HTML form element types including file uploads, may return
or print the form, just individual form elements or the full
form in \"table mode\" with a fixed layout.

This package has been superceded by HTML_QuickForm.",
        'lead' =>
            "danielc",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Javascript' =>
        array(
        'packageid' =>
            "93",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP 3.0",
        'summary' =>
            "Provides an interface for creating simple JS scripts.",
        'description' =>
            "Provides two classes:
HTML_Javascript for performing basic JS operations.
HTML_Javascript_Convert for converting variables
Allow output data to a file, to the standart output(print), or return",
        'lead' =>
            "alan_k",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Menu' =>
        array(
        'packageid' =>
            "243",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "Generates HTML menus from multidimensional hashes.",
        'description' =>
            "With the HTML_Menu class one can easily create and maintain a 
navigation structure for websites, configuring it via a multidimensional 
hash structure. Different modes for the HTML output are supported.",
        'lead' =>
            "uw",
        'stable' =>
            "2.1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Progress' =>
        array(
        'packageid' =>
            "235",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License 3.0",
        'summary' =>
            "How to include a loading bar in your XHTML documents quickly and easily.",
        'description' =>
            "This package provides a way to add a loading bar fully customizable in existing XHTML documents.
Your browser should accept DHTML feature.

Features:
- create horizontal, vertival bar and also circle, ellipse and polygons (square, rectangle)
- allows usage of existing external StyleSheet and/or JavaScript 
- all elements (progress, cells, string) are customizable by their html properties
- percent/string is floating all around the progress meter
- compliant with all CSS/XHMTL standards
- integration with all template engines is very easy
- implements Observer design pattern. It is possible to add Listeners
- adds a customizable UI monitor pattern to display a progress bar. 
  User-end can abort progress at any time.
- Look and feel can be sets by internal API or external config file
- Allows many progress meter on same page without uses of iframe solution
- Since release 1.2.0 you may display new shapes like: circle, ellipse, square and rectangle.",
        'lead' =>
            "farell",
        'stable' =>
            "1.2.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_QuickForm' =>
        array(
        'packageid' =>
            "58",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "The PEAR::HTML_QuickForm package provides methods for creating, validating, processing HTML forms.",
        'description' =>
            "The HTML_QuickForm package provides methods for dynamically create, validate and render HTML forms.

Features:
* More than 20 ready-to-use form elements.
* XHTML compliant generated code.
* Numerous mixable and extendable validation rules.
* Automatic server-side validation and filtering.
* On request javascript code generation for client-side validation.
* File uploads support.
* Total customization of form rendering.
* Support for external template engines (ITX, Sigma, Flexy, Smarty).
* Pluggable elements, rules and renderers extensions.",
        'lead' =>
            "avb",
        'stable' =>
            "3.2.4pl1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_QuickForm_Controller' =>
        array(
        'packageid' =>
            "245",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "The add-on to HTML_QuickForm package that allows building of multipage forms",
        'description' =>
            "The package is essentially an implementation of a PageController pattern.
Architecture:
* Controller class that examines HTTP requests and manages form values persistence across requests.
* Page class (subclass of QuickForm) representing a single page of the form.
* Business logic is contained in subclasses of Action class.
Cool features:
* Includes several default Actions that allow easy building of multipage forms.
* Includes usage examples for common usage cases (single-page form, wizard, tabbed form).",
        'lead' =>
            "avb",
        'stable' =>
            "1.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Select_Common' =>
        array(
        'packageid' =>
            "165",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "BSD",
        'summary' =>
            "Some small classes to handle common &lt;select&gt; lists",
        'description' =>
            "Provides &lt;select&gt; lists for:
o Country
o UK counties
o US States
o FR Departements",
        'lead' =>
            "derick",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Table' =>
        array(
        'packageid' =>
            "70",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and efficient.",
        'description' =>
            "The PEAR::HTML_Table package provides methods for easy and efficient design of HTML tables.
* Lots of customization options.
* Tables can be modified at any time.
* The logic is the same as standard HTML editors.
* Handles col and rowspans. 
* PHP code is shorter, easier to read and to maintain.
* Tables options can be reused.",
        'lead' =>
            "dufuz",
        'stable' =>
            "1.5",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Table_Matrix' =>
        array(
        'packageid' =>
            "327",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License v3.0",
        'summary' =>
            "Autofill a table with data",
        'description' =>
            "HTML_Table_Matrix is an extension to HTML_Table which allows you to easily fill up a table with data.
Features:
- It uses Filler classes to determine how the data gets filled in the table. With a custom Filler, you can fill data in up, down, forwards, backwards, diagonally, randomly or any other way you like.
- Comes with Fillers to fill left-to-right-top-to-bottom and right-to-left-top-to-bottom.
- Abstract Filler methods keep the code clean & easy to understand.
- Table height or width may be omitted, and it will figure out the correct table size based on the data you provide.
- It integrates handily with Pager to create pleasant pageable table layouts, such as for an image gallery. Just specify a height or width, Filler, and feed it the data returned from Pager.
- Table may be constrained to a specific height or width, and excess data will be ignored.
- Fill offset may be specified, to leave room for a table header, or other elements in the table.
- Fully documented with PHPDoc.
- Includes fully functional example code.",
        'lead' =>
            "ieure",
        'stable' =>
            "1.0.6",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Template_Flexy' =>
        array(
        'packageid' =>
            "111",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "An extremely powerful Tokenizer driven Template engine",
        'description' =>
            "HTML_Template_Flexy started it\'s life as a simplification of HTML_Template_Xipe, 
however in Version 0.2, It became one of the first template engine to use a real Lexer,
rather than regex\'es, making it possible to do things like ASP.net or Cold Fusion tags. 
However, it still has a very simple set of goals.
- Very Simple API, 
   o easy to learn...
   o prevents to much logic going in templates
- Easy to write document\'able code
   o By using object vars for a template rather than \'assign\', you 
     can use phpdoc comments to list what variable you use.
- Editable in WYSIWYG editors
   o you can create full featured templates, that doesnt get broken every time you edit with 
     Dreamweaver(tm) or Mozzila editor
   o Uses namespaced attributes to add looping/conditionals  
- Extremely Fast, 
   o runtime is at least 4 time smaller than most other template engines (eg. Smarty)
   o uses compiled templates, as a result it is many times faster on blocks and loops than 
     than Regex templates (eg. IT/phplib)
- Safer (for cross site scripting attacks)
   o All variables default to be output as HTML escaped (overridden with the :h modifier)
- Multilanguage support
   o Parses strings out of template, so you can build translation tools
   o Compiles language specific templates (so translation is only done once, not on every request)
- Full dynamic element support (like ASP.NET), so you can pick elements to replace at runtime

Features:
- {variable} to echo \$object->variable
- {method()} to echo \$object->method();
- {foreach:var,key,value} to PHP foreach loops
- tag attributes FLEXY:FOREACH, FLEXY:IF for looping and conditional HTML inclusion
- {if:variable} to PHP If statement
- {if:method()} to PHP If statement
- {else:} and {end:} to close or alternate If statements
- FORM to HTML_Template_Flexy_Element\'s
- replacement of INPUT, TEXTAREA and SELECT tags with HTML_Template_Flexy_Element code
  use FLEXY:IGNORE (inherited) and FLEXY:IGNOREONLY (single) to prevent replacements
- FLEXY:START/FLEXY:STARTCHILDREN tags to define where template starts/finishes
- support for urlencoded braces {} in HTML attributes.  
- documentation in the pear manual

- examples at http://cvs.php.net/cvs.php/pear/HTML_Template_Flexy/tests/

** The long term plan for Flexy is to be integrated as a backend for the 
Future Template Package (A BC wrapper will be made available - as I need 
to use it too!)",
        'lead' =>
            "alan_k",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Template_IT' =>
        array(
        'packageid' =>
            "108",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "Integrated Templates",
        'description' =>
            "HTML_Template_IT:
Simple template API.
The Isotemplate API is somewhat tricky for a beginner although it is the best
one you can build. template::parse() [phplib template = Isotemplate] requests
you to name a source and a target where the current block gets parsed into.
Source and target can be block names or even handler names. This API gives you
a maximum of fexibility but you always have to know what you do which is
quite unusual for php skripter like me.

I noticed that I do not any control on which block gets parsed into which one.
If all blocks are within one file, the script knows how they are nested and in
which way you have to parse them. IT knows that inner1 is a child of block2, there\'s
no need to tell him about this.
Features :
  * Nested blocks
  * Include external file
  * Custom tags format (default {mytag})

HTML_Template_ITX :
With this class you get the full power of the phplib template class.
You may have one file with blocks in it but you have as well one main file
and multiple files one for each block. This is quite usefull when you have
user configurable websites. Using blocks not in the main template allows
you to modify some parts of your layout easily.",
        'lead' =>
            "uw",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Template_PHPLIB' =>
        array(
        'packageid' =>
            "168",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "LGPL",
        'summary' =>
            "preg_* based template system.",
        'description' =>
            "The popular Template system from PHPLIB ported to PEAR. It has some
features that can\'t be found currently in the original version like
fallback paths. It has minor improvements and cleanup in the code as
well as some speed improvements.",
        'lead' =>
            "bjoern",
        'stable' =>
            "1.3.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Template_Sigma' =>
        array(
        'packageid' =>
            "189",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "An implementation of Integrated Templates API with template \'compilation\' added",
        'description' =>
            "HTML_Template_Sigma implements Integrated Templates API designed by Ulf Wendel.

Features:
* Nested blocks. Nesting is controlled by the engine.
* Ability to include files from within template: &lt;!-- INCLUDE --&gt;
* Automatic removal of empty blocks and unknown variables (methods to manually tweak/override this are also available)
* Methods for runtime addition and replacement of blocks in templates
* Ability to insert simple function calls into templates: func_uppercase(\'Hello world!\') and to define callback functions for these
* \'Compiled\' templates: the engine has to parse a template file using regular expressions to find all the blocks and variable placeholders. This is a very \"expensive\" operation and is an overkill to do on every page request: templates seldom change on production websites. Thus this feature: an internal representation of the template structure is saved into a file and this file gets loaded instead of the source one on subsequent requests (unless the source changes)
* PHPUnit-based tests to define correct behaviour
* Usage examples for most of the features are available, look in the docs/ directory",
        'lead' =>
            "avb",
        'stable' =>
            "1.1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_Template_Xipe' =>
        array(
        'packageid' =>
            "162",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "A simple, fast and powerful template engine.",
        'description' =>
            "The template engine is a compiling engine, all templates are compiled into PHP-files.
This will make the delivery of the files faster on the next request, since the template
doesn\'t need to be compiled again. If the template changes it will be recompiled.

There is no new template language to learn. Beside the default mode, there is a set of constructs
since version 1.6 which allow you to edit your templates with WYSIWYG editors.

By default the template engine uses indention for building blocks (you can turn that off).
This feature was inspired by Python and by the need I felt to force myself
to write proper HTML-code, using proper indentions, to make the code better readable.

Every template is customizable in multiple ways. You can configure each
template or an entire directory to use different delimiters, caching parameters, etc.
via either an XML-file or a XML-chunk which you simply write anywhere inside the tpl-code.

Using the Cache the final file can also be cached (i.e. a resulting HTML-file).
The caching options can be customized as needed. The cache can reduce the server
load by very much, since the entire php-file doesn\'t need to be processed again,
the resulting client-readable data are simply delivered right from the cache 
(the data are saved using php\'s output buffering).

The template engine is prepared to be used for multi-language applications too.
If you i.e. use the PEAR::I18N for translating the template,
the compiled templates need to be saved under a different name for each language.
The template engine is prepared for that too, it saves the compiled template including the
language code if required (i.e. a compiled index.tpl which is saved for english gets the filename index.tpl.en.php).",
        'lead' =>
            "dufuz",
        'stable' =>
            "1.7.6",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTML_TreeMenu' =>
        array(
        'packageid' =>
            "77",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "BSD",
        'summary' =>
            "Provides an api to create a HTML tree",
        'description' =>
            "PHP Based api creates a tree structure using a couple of
small PHP classes. This can then be converted to javascript
using the printMenu() method. The tree is  dynamic in
IE 4 or higher, NN6/Mozilla and Opera 7, and maintains state
(the collapsed/expanded status of the branches) by using cookies.
Other browsers display the tree fully expanded. Each node can
have an optional link and icon. New API in 1.1 with many changes
(see CVS for changelog) and new features, of which most came
from Chip Chapin (http://www.chipchapin.com).",
        'lead' =>
            "richard",
        'stable' =>
            "1.1.9",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTTP' =>
        array(
        'packageid' =>
            "5",
        'categoryid' =>
            "11",
        'category' =>
            "HTTP",
        'license' =>
            "PHP License",
        'summary' =>
            "Miscellaneous HTTP utilities",
        'description' =>
            "The HTTP class is a class with static methods for doing 
miscellaneous HTTP related stuff like date formatting,
language negotiation or HTTP redirection.",
        'lead' =>
            "mike",
        'stable' =>
            "1.3.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTTP_Client' =>
        array(
        'packageid' =>
            "213",
        'categoryid' =>
            "11",
        'category' =>
            "HTTP",
        'license' =>
            "PHP License",
        'summary' =>
            "Easy way to perform multiple HTTP requests and process their results",
        'description' =>
            "The HTTP_Client class wraps around HTTP_Request and provides a higher level interface 
for performing multiple HTTP requests.

Features:
* Manages cookies and referrers between requests
* Handles HTTP redirection
* Has methods to set default headers and request parameters
* Implements the Subject-Observer design pattern: the base class sends 
events to listeners that do the response processing.",
        'lead' =>
            "avb",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTTP_Header' =>
        array(
        'packageid' =>
            "164",
        'categoryid' =>
            "11",
        'category' =>
            "HTTP",
        'license' =>
            "PHP License",
        'summary' =>
            "OO interface to modify and handle HTTP headers and status codes.",
        'description' =>
            "This class provides methods to set/modify HTTP headers 
and status codes including an HTTP caching facility.
It also provides methods for checking Status types.",
        'lead' =>
            "mike",
        'stable' =>
            "1.1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTTP_Request' =>
        array(
        'packageid' =>
            "33",
        'categoryid' =>
            "11",
        'category' =>
            "HTTP",
        'license' =>
            "BSD",
        'summary' =>
            "Provides an easy way to perform HTTP requests",
        'description' =>
            "Supports GET/POST/HEAD/TRACE/PUT/DELETE, Basic authentication, Proxy,
Proxy Authentication, SSL, file uploads etc.",
        'lead' =>
            "avb",
        'stable' =>
            "1.2.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'HTTP_Upload' =>
        array(
        'packageid' =>
            "20",
        'categoryid' =>
            "11",
        'category' =>
            "HTTP",
        'license' =>
            "LGPL",
        'summary' =>
            "Easy and secure managment of files submitted via HTML Forms",
        'description' =>
            "This class provides an advanced file uploader system for file uploads made
from html forms. Features:
 * Can handle from one file to multiple files.
 * Safe file copying from tmp dir.
 * Easy detecting mechanism of valid upload, missing upload or error.
 * Gives extensive information about the uploaded file.
 * Rename uploaded files in different ways: as it is, safe or unique
 * Validate allowed file extensions
 * Multiple languages error messages support (es, en, de, fr, it, nl, pt_BR)",
        'lead' =>
            "antonio",
        'stable' =>
            "0.9.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'huffman' =>
        array(
        'packageid' =>
            "385",
        'categoryid' =>
            "29",
        'category' =>
            "Tools and Utilities",
        'license' =>
            "PHP",
        'summary' =>
            "Huffman compression is a lossless compression algorithm that is ideal for compressing textual data.",
        'description' =>
            "Huffman compression belongs into a family of algorithms with a variable codeword length. That means that individual symbols (characters in a text file for instance) are replaced by bit sequences that have a distinct length. So symbols that occur a lot in a file are given a short sequence while other that are used seldom get a longer bit sequence.",
        'lead' =>
            "mnx",
        'stable' =>
            "0.2.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Image_Barcode' =>
        array(
        'packageid' =>
            "142",
        'categoryid' =>
            "12",
        'category' =>
            "Images",
        'license' =>
            "PHP License",
        'summary' =>
            "Barcode generation",
        'description' =>
            "With PEAR::Image_Barcode class you can create a barcode representation of a
given string.
                                                                                                                             
This class uses GD function because this the generated graphic can be any of
GD supported supported image types.",
        'lead' =>
            "msmarcal",
        'stable' =>
            "0.5",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Image_Color' =>
        array(
        'packageid' =>
            "35",
        'categoryid' =>
            "12",
        'category' =>
            "Images",
        'license' =>
            "PHP License",
        'summary' =>
            "Manage and handles color data and conversions.",
        'description' =>
            "Manage and handles color data and conversions.",
        'lead' =>
            "jasonlotito",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Image_GIS' =>
        array(
        'packageid' =>
            "151",
        'categoryid' =>
            "12",
        'category' =>
            "Images",
        'license' =>
            "PHP License",
        'summary' =>
            "Visualization of GIS data.",
        'description' =>
            "Generating maps on demand can be a hard job as most often you don\'t
have the maps you need in digital form.
But you can generate your own maps based on raw, digital data files
which are available for free on the net.
This package provides a parser for the most common format for
geographical data, the Arcinfo/E00 format as well as renderers to
produce images using GD or Scalable Vector Graphics (SVG).",
        'lead' =>
            "ostborn",
        'stable' =>
            "1.1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Image_GraphViz' =>
        array(
        'packageid' =>
            "39",
        'categoryid' =>
            "12",
        'category' =>
            "Images",
        'license' =>
            "PHP License",
        'summary' =>
            "Interface to AT&T\'s GraphViz tools",
        'description' =>
            "The GraphViz class allows for the creation of and the work with 
 directed and undirected graphs and their visualization with 
 AT&T\'s GraphViz tools.",
        'lead' =>
            "sebastian",
        'stable' =>
            "1.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Image_IPTC' =>
        array(
        'packageid' =>
            "194",
        'categoryid' =>
            "12",
        'category' =>
            "Images",
        'license' =>
            "PHP License",
        'summary' =>
            "Extract, modify, and save IPTC data",
        'description' =>
            "This package provides a mechanism for modifying IPTC header information. The class abstracts the functionality of iptcembed() and iptcparse() in addition to providing methods that properly handle replacing IPTC header fields back into image files.",
        'lead' =>
            "polone",
        'stable' =>
            "1.0.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Log' =>
        array(
        'packageid' =>
            "8",
        'categoryid' =>
            "13",
        'category' =>
            "Logging",
        'license' =>
            "PHP License",
        'summary' =>
            "Logging utilities",
        'description' =>
            "The Log framework provides an abstracted logging system.  It supports logging to console, file, syslog, SQL, Sqlite, mail and mcal targets.  It also provides a subject - observer mechanism.",
        'lead' =>
            "jon",
        'stable' =>
            "1.8.7",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'lzf' =>
        array(
        'packageid' =>
            "262",
        'categoryid' =>
            "36",
        'category' =>
            "Text",
        'license' =>
            "PHP License",
        'summary' =>
            "LZF compression.",
        'description' =>
            "This package handles LZF de/compression.",
        'lead' =>
            "mg",
        'stable' =>
            "1.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Mail' =>
        array(
        'packageid' =>
            "72",
        'categoryid' =>
            "14",
        'category' =>
            "Mail",
        'license' =>
            "PHP/BSD",
        'summary' =>
            "Class that provides multiple interfaces for sending emails",
        'description' =>
            "PEAR\'s Mail:: package defines the interface for implementing mailers under the PEAR hierarchy, and provides supporting functions useful in multiple mailer backends. Currently supported are native PHP mail() function, sendmail and SMTP. This package also provides a RFC 822 Email address list validation utility class.",
        'lead' =>
            "jon",
        'stable' =>
            "1.1.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'mailparse' =>
        array(
        'packageid' =>
            "143",
        'categoryid' =>
            "14",
        'category' =>
            "Mail",
        'license' =>
            "PHP",
        'summary' =>
            "Email message manipulation",
        'description' =>
            "Mailparse is an extension for parsing and working with email messages.
It can deal with rfc822 and rfc2045 (MIME) compliant messages.",
        'lead' =>
            "wez",
        'stable' =>
            "2.0b",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Mail_Mime' =>
        array(
        'packageid' =>
            "21",
        'categoryid' =>
            "14",
        'category' =>
            "Mail",
        'license' =>
            "PHP",
        'summary' =>
            "Provides classes to create and decode mime messages.",
        'description' =>
            "  Provides classes to deal with creation and manipulation of mime messages:

* mime.php: Create mime email, with html, attachments, embedded images etc.

* mimePart.php: Advanced method of creating mime messages.

* mimeDecode.php: Decodes mime messages to a usable structure.

* xmail.dtd: An XML DTD to acompany the getXML() method of the decoding class.

* xmail.xsl: An XSLT stylesheet to transform the output of the getXML() method back to an email",
        'lead' =>
            "cipri",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Mail_Queue' =>
        array(
        'packageid' =>
            "113",
        'categoryid' =>
            "14",
        'category' =>
            "Mail",
        'license' =>
            "PHP",
        'summary' =>
            "Class for put mails in queue and send them later in background.",
        'description' =>
            "Class to handle mail queue managment.
Wrapper for PEAR::Mail and PEAR::DB (or PEAR::MDB/MDB2).
It can load, save and send saved mails in background
and also backup some mails.

The Mail_Queue class puts mails in a temporary container,
waiting to be fed to the MTA (Mail Transport Agent),
and sends them later (e.g. a certain amount of mails
every few minutes) by crontab or in other way.",
        'lead' =>
            "chief",
        'stable' =>
            "1.1.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_Basex' =>
        array(
        'packageid' =>
            "147",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP",
        'summary' =>
            "Simple class for converting base set of numbers with a customizable character base set.",
        'description' =>
            "Base X conversion class",
        'lead' =>
            "zyprexia",
        'stable' =>
            "0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_Fibonacci' =>
        array(
        'packageid' =>
            "153",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP",
        'summary' =>
            "Package to calculate and manipulate Fibonacci numbers",
        'description' =>
            "The Fibonacci series is constructed using the formula:
      F(n) = F(n - 1) + F (n - 2),
By convention F(0) = 0, and F(1) = 1.
An alternative formula that uses the Golden Ratio can also be used:
      F(n) = (PHI^n - phi^n)/sqrt(5) [Lucas\' formula],
where PHI = (1 + sqrt(5))/2 is the Golden Ratio, and
      phi = (1 - sqrt(5))/2 is its reciprocal
Requires Math_Integer, and can be used with big integers if the GMP or
the BCMATH libraries are present.",
        'lead' =>
            "jmcastagnetto",
        'stable' =>
            "0.8",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_Integer' =>
        array(
        'packageid' =>
            "152",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP",
        'summary' =>
            "Package to represent and manipulate integers",
        'description' =>
            "The class Math_Integer can represent integers bigger than the
signed longs that are the default of PHP, if either the GMP or
the BCMATH (bundled with PHP) are present. Otherwise it will fall
back to the internal integer representation.
The Math_IntegerOp class defines operations on Math_Integer objects.",
        'lead' =>
            "jmcastagnetto",
        'stable' =>
            "0.8",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_Matrix' =>
        array(
        'packageid' =>
            "202",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP",
        'summary' =>
            "Class to represent matrices and matrix operations",
        'description' =>
            "Matrices are represented as 2 dimensional arrays of numbers. 
This class defines methods for matrix objects, as well as static methods 
to read, write and manipulate matrices, including methods to solve systems 
of linear equations (with and without iterative error correction).
Requires the Math_Vector package.
For running the unit tests you will need PHPUnit version 0.6.2 or older.",
        'lead' =>
            "jmcastagnetto",
        'stable' =>
            "0.8.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_RPN' =>
        array(
        'packageid' =>
            "232",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP License",
        'summary' =>
            "Reverse Polish Notation.",
        'description' =>
            "Change Expression To RPN (Reverse Polish Notation) and evaluate it.",
        'lead' =>
            "mszczytowski",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_Stats' =>
        array(
        'packageid' =>
            "60",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP",
        'summary' =>
            "Classes to calculate statistical parameters",
        'description' =>
            "Package to calculate statistical parameters of numerical arrays
of data. The data can be in a simple numerical array, or in a 
cummulative numerical array. A cummulative array, has the value
as the index and the number of repeats as the value for the
array item, e.g. \$data = array(3=>4, 2.3=>5, 1.25=>6, 0.5=>3).

Nulls can be rejected, ignored or handled as zero values.

Note: You should be using the latest release (0.9.0beta3 currently), as it fixes problems with the calculations of several of the statistics that exist in the stable release.",
        'lead' =>
            "jmcastagnetto",
        'stable' =>
            "0.8.5",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Math_TrigOp' =>
        array(
        'packageid' =>
            "138",
        'categoryid' =>
            "15",
        'category' =>
            "Math",
        'license' =>
            "PHP",
        'summary' =>
            "Supplementary trigonometric functions",
        'description' =>
            "Static class with methods that implement supplementary trigonometric,
inverse trigonometric, hyperbolic, and inverse hyperbolic functions.",
        'lead' =>
            "jmcastagnetto",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'MDB' =>
        array(
        'packageid' =>
            "54",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "BSD style",
        'summary' =>
            "database abstraction layer",
        'description' =>
            "PEAR MDB is a merge of the PEAR DB and Metabase php database abstraction layers.
It provides a common API for all support RDBMS. The main difference to most
other DB abstraction packages is that MDB goes much further to ensure
portability. Among other things MDB features:
* An OO-style query API
* A DSN (data source name) or array format for specifying database servers
* Datatype abstraction and on demand datatype conversion
* Portable error codes
* Sequential and non sequential row fetching as well as bulk fetching
* Ordered array and associative array for the fetched rows
* Prepare/execute (bind) emulation
* Sequence emulation
* Replace emulation
* Limited Subselect emulation
* Row limit support
* Transactions support
* Large Object support
* Index/Unique support
* Module Framework to load advanced functionality on demand
* Table information interface
* RDBMS management methods (creating, dropping, altering)
* RDBMS independent xml based schema definition management
* Altering of a DB from a changed xml schema
* Reverse engineering of xml schemas from an existing DB (currently only MySQL)
* Full integration into the PEAR Framework
* Wrappers for the PEAR DB and Metabase APIs
* PHPDoc API documentation
Currently supported RDBMS:
MySQL
PostGreSQL
Oracle
Frontbase
Querysim
Interbase/Firebird
MSSQL",
        'lead' =>
            "lsmith",
        'stable' =>
            "1.3.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'MDB_QueryTool' =>
        array(
        'packageid' =>
            "167",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP",
        'summary' =>
            "An OO-interface for easily retrieving and modifying data in a DB.",
        'description' =>
            "This package is an OO-abstraction to the SQL-Query language, it provides methods such
as setWhere, setOrder, setGroup, setJoin, etc. to easily build queries.
It also provides an easy to learn interface that interacts nicely with HTML-forms using
arrays that contain the column data, that shall be updated/added in a DB.
This package bases on an SQL-Builder which lets you easily build
SQL-Statements and execute them.
NB: this is just a MDB porting from the original DB_QueryTool
written by Wolfram Kriesing and Paolo Panto (vision:produktion, wk@visionp.de).",
        'lead' =>
            "quipo",
        'stable' =>
            "0.11.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'memcache' =>
        array(
        'packageid' =>
            "294",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "PHP License",
        'summary' =>
            "memcached extension",
        'description' =>
            "Memcached is a caching daemon designed especially for 
dynamic web applications to decrease database load by 
storing objects in memory.
This extension allows you to work with memcached through
handy OO and procedural interfaces.",
        'lead' =>
            "tony2001",
        'stable' =>
            "1.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'MP3_ID' =>
        array(
        'packageid' =>
            "211",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "LGPL",
        'summary' =>
            "Read/Write MP3-Tags",
        'description' =>
            "The class offers methods for reading and
writing information tags (version 1) in MP3 files.",
        'lead' =>
            "alexmerz",
        'stable' =>
            "1.1.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_CheckIP' =>
        array(
        'packageid' =>
            "9",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Check the syntax of IPv4 addresses",
        'description' =>
            "This package validates IPv4 addresses.",
        'lead' =>
            "mj",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Curl' =>
        array(
        'packageid' =>
            "30",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP",
        'summary' =>
            "Net_Curl provides an OO interface to PHP\'s cURL extension",
        'description' =>
            "Provides an OO interface to PHP\'s curl extension",
        'lead' =>
            "gurugeek",
        'stable' =>
            "0.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Dict' =>
        array(
        'packageid' =>
            "114",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP",
        'summary' =>
            "Interface to the DICT Protocol",
        'description' =>
            "This class provides a simple API to the DICT Protocol handling all the network related issues 
and providing DICT responses in PHP datatypes 
to make it easy for a developer to use DICT 
servers in their programs.",
        'lead' =>
            "cnb",
        'stable' =>
            "1.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Dig' =>
        array(
        'packageid' =>
            "49",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP 2.02",
        'summary' =>
            "The PEAR::Net_Dig class should be a nice, friendly OO interface to the dig command",
        'description' =>
            "Net_Dig class is no longer being maintained.  Use of Net_DNS is recommended instead.  A brief tutorial on how to migrate to Net_DNS is listed below.",
        'lead' =>
            "cmv",
        'stable' =>
            "0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_DNS' =>
        array(
        'packageid' =>
            "59",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "LGPL 2.1",
        'summary' =>
            "Resolver library used to communicate with a DNS server",
        'description' =>
            "A resolver library used to communicate with a name server to perform DNS queries, zone transfers, dynamic DNS updates, etc.  Creates an object hierarchy from a DNS server\'s response, which allows you to view all of the information given by the DNS server.  It bypasses the system\'s resolver library and communicates directly with the server.",
        'lead' =>
            "ekilfoil",
        'stable' =>
            "0.03",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Finger' =>
        array(
        'packageid' =>
            "158",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "The PEAR::Net_Finger class provides a tool for querying Finger Servers",
        'description' =>
            "Wrapper class for finger calls.",
        'lead' =>
            "nohn",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_FTP' =>
        array(
        'packageid' =>
            "148",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Net_FTP provides an OO interface to the PHP FTP functions plus some additions",
        'description' =>
            "Net_FTP allows you to communicate with FTP servers in a more comfortable way
than the native FTP functions of PHP do. The class implements everything nativly
supported by PHP and additionally features like recursive up- and downloading,
dircreation and chmodding. It although implements an observer pattern to allow
for example the view of a progress bar.",
        'lead' =>
            "toby",
        'stable' =>
            "1.3.0RC1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Geo' =>
        array(
        'packageid' =>
            "55",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP",
        'summary' =>
            "Geographical locations based on Internet address",
        'description' =>
            "Obtains geographical information based on IP number, domain name, or AS number. Makes use of CAIDA Net_Geo lookup or locaizer extension.",
        'lead' =>
            "graeme",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Ident' =>
        array(
        'packageid' =>
            "122",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP",
        'summary' =>
            "Identification Protocol implementation",
        'description' =>
            "The PEAR::Net_Ident implements Identification Protocol according to RFC
1413.
The Identification Protocol (a.k.a., \"ident\", a.k.a., \"the Ident
Protocol\") provides a means to determine the identity of a user of a
particular TCP connection.  Given a TCP port number pair, it returns a
character string which identifies the owner of that connection on the
server\'s system.",
        'lead' =>
            "nepto",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_IMAP' =>
        array(
        'packageid' =>
            "181",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Provides an implementation of the IMAP protocol",
        'description' =>
            "Provides an implementation of the IMAP4Rev1 protocol using PEAR\'s Net_Socket and the optional Auth_SASL class.",
        'lead' =>
            "damian",
        'stable' =>
            "1.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_IPv4' =>
        array(
        'packageid' =>
            "106",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP 2.0",
        'summary' =>
            "IPv4 network calculations and validation",
        'description' =>
            "Class used for calculating IPv4 (AF_INET family) address information
such as network as network address, broadcast address, and IP address
validity.",
        'lead' =>
            "ekilfoil",
        'stable' =>
            "1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_IPv6' =>
        array(
        'packageid' =>
            "10",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Check and validate IPv6 addresses",
        'description' =>
            "The class allows you to:
* check if an addresse is an IPv6 addresse
* compress/uncompress IPv6 addresses
* check for an IPv4 compatible ending in an IPv6 adresse",
        'lead' =>
            "alexmerz",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_LMTP' =>
        array(
        'packageid' =>
            "197",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Provides an implementation of the RFC2033 LMTP protocol",
        'description' =>
            "Provides an implementation of the RFC2033 LMTP using PEAR\'s Net_Socket and Auth_SASL class.",
        'lead' =>
            "damian",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_NNTP' =>
        array(
        'packageid' =>
            "11",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "W3C",
        'summary' =>
            "Implementation of the NNTP protocol",
        'description' =>
            "Package for communicating with NNTP/USENET servers. Includes features like post, view, list, authentication, overview, etc.

------------------------------------------------------------------------------

ATTENTION!!!

Due to PHP bug #27822 (fixed in v4.3.6) and #28055 (fixed in v4.3.7) the Net_NNTP v0.10.x performs \'rather\' slow when being used on v4.3.1-6.</b> Due to the bugs the connection validation in isConnected() doesn\'t works as intended...

- PHP4.2 is not affected.

- PHP4.3.7-dev (12/05-04) is confirmed functional.

- PHP5.0 is still not supported.(unreleased CVS code should now support at PHP5.0 (rc3). Be warned though! It hasn\'t been heavily tested! For now one should use
Net/Socket.php v1.13 from CVS, since Net_Socket v1.0.2 hangs...)

------------------------------------------------------------------------------

The new protocol implementation (v0.3+) is to considered beta state. The error handling is a lot better than in v0.2, but is still a little weak, since the error codes/messages is not done yet (PEAR_Error objects IS returned, but often only the server\'s response messages is returned). All expected NNTP errors are are caught though...

------------------------------------------------------------------------------

MAINTAINED versions of Net_NNTP:

0.11.x (beta)

- Backward compatible drop in replacement for v0.2.x
- This is going to become the v1.0 release

------------------------------------------------------------------------------

DEPRECATED versions of Net_NNTP:

0.2.x (stable) - no further development - only critical bugs will be fixed!

- Rather outdated, but widely used.


0.1 (stable) - no further development - use v0.2.x instead

- NOT binary-safe !!!

------------------------------------------------------------------------------

UNMAINTAINED (development) versions of Net_NNTP:

0.10.x (alpha) - no further development - use v0.11.x instead

- (merges v0.3.3 and v0.9.4 into one package)
- Backward compatible drop in replacement for v0.2.x
- The Net_NNTP class from v0.9.x is now called Net_NNTP_Realtime. The backward compatibility with v0.2.x (and v0.3.x) is only included to allow existing projects to function. (please ;) use the Net_NNTP_Realtime class in new projects...


0.9.x (alpha) - no further development - use v0.10.x instead

- A few method names have changed, so it does not maintain full backward compatibility with v0.2.x
- A new protocol implementation has replaced the original one, and the behavior of a few methods has changed. Two new (experimental) classes, Header and Message, has been added to ease the development. (The clasic API is actually considered beta state, but due to the two new classes the package has to be considered alpha state)...
- If backward compatibility with v0.2 is a requirement, the new protocol implementation from v0.9 is also avalible in v0.3 (which is actually a modified v0.9, where some of the new features has been left out).


0.3.x (beta) - no further development - use v0.10.x instead

- v0.10.0 includes an excat copy of the classes in v0.3.3
- Backward compatible drop in replacement for v0.2.x
- An amputated backport of v0.9.x. It uses the new protocol implememtation from v0.9.x, but preserves backward compatibility with v0.2.x. (The new alpha state code has been left out, and the methods which has been renamed still function as usual even though they are now only aliases to their replacements).
- This version was created to allow people who require backward compatibility with v0.2.x to use/test the new protocol implementation.
- (Not all changes in v0.9.x relate to v0.3.x, so don\'t expect this backport to be updated each time a new v0.9.x release is rolled out)
- (Please don\'t use the old method names for new projects)

------------------------------------------------------------------------------

People who\'ve put work into Net_NNTP (in order of appearance):

Martin Kaltoft <martin@nitro.dk>, Lead
- Initial code.

Thomas V.V.Cox <cox@idecnet.com>, Developer
- A lot of new methods (including authentication)

Morgan Christiansson <mog@linux.nu>, Contributor
- A few new methods

Alexander Merz <alexmerz@php.net>, Contributor
- PEAR?ifing of original code
- Documentation (v0.1)

Heino H. Gehlsen <heino@php.net>, Lead
- Total rewrite based on new protocol class
- Documentation (v0.10)",
        'lead' =>
            "heino",
        'stable' =>
            "0.2.5",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Ping' =>
        array(
        'packageid' =>
            "12",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Execute ping",
        'description' =>
            "OS independet wrapper class for executing ping calls",
        'lead' =>
            "jan",
        'stable' =>
            "2.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_POP3' =>
        array(
        'packageid' =>
            "25",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "BSD",
        'summary' =>
            "Provides a POP3 class to access POP3 server.",
        'description' =>
            "Provides a POP3 class to access POP3 server. Support all POP3 commands
including UIDL listings, APOP authentication,DIGEST-MD5 and CRAM-MD5 using optional Auth_SASL package",
        'lead' =>
            "gschlossnagle",
        'stable' =>
            "1.3.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Portscan' =>
        array(
        'packageid' =>
            "23",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP 2.02",
        'summary' =>
            "Portscanner utilities.",
        'description' =>
            "The Net_Portscan package allows one to perform basic portscanning
functions with PHP. It supports checking an individual port or
checking a whole range of ports on a machine.",
        'lead' =>
            "mj",
        'stable' =>
            "1.0.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Sieve' =>
        array(
        'packageid' =>
            "71",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "BSD",
        'summary' =>
            "Handles talking to timsieved",
        'description' =>
            "Provides an API to talk to the timsieved server that comes
with Cyrus IMAPd. Can be used to install, remove, mark active etc
sieve scripts.",
        'lead' =>
            "damian",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_SmartIRC' =>
        array(
        'packageid' =>
            "146",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "LGPL",
        'summary' =>
            "Net_SmartIRC is a PHP class for communication with IRC networks",
        'description' =>
            "Net_SmartIRC is a PHP class for communication with IRC networks,
which conforms to the RFC 2812 (IRC protocol).
It\'s an API that handles all IRC protocol messages.
This class is designed for creating IRC bots, chats and show irc related info on webpages.

Full featurelist of Net_SmartIRC
-------------------------------------
- full object oriented programmed
- every received IRC message is parsed into an ircdata object
  (it contains following info: from, nick, ident, host, channel, message, type, rawmessage)
- actionhandler for the API
  on different types of messages (channel/notice/query/kick/join..) callbacks can be registered
- messagehandler for the API
  class based messagehandling, using IRC reply codes
- time events
  callbacks to methods in intervals
- send/receive floodprotection
- detects and changes nickname on nickname collisions
- autoreconnect, if connection is lost
- autoretry for connecting to IRC servers
- debugging/logging system with log levels (destination can be file, stdout, syslog or browserout)
- supports fsocks and PHP socket extension
- supports PHP 4.1.x to 4.3.2 (also PHP 5.0.0b1)
- sendbuffer with a queue that has 3 priority levels (high, medium, low) plus a bypass level (critical)
- channel syncing (tracking of users/modes/topic etc in objects)
- user syncing (tracking the user in channels, nick/ident/host/realname/server/hopcount in objects)
- when channel syncing is acticated the following functions are available:
  isJoined
  isOpped
  isVoiced
  isBanned
- on reconnect all joined channels will be rejoined, also when keys are used
- own CTCP version reply can be set
- IRC commands:
  pass
  op
  deop
  voice
  devoice
  ban
  unban
  join
  part
  action
  message
  notice
  query
  ctcp
  mode
  topic
  nick
  invite
  list
  names
  kick
  who
  whois
  whowas
  quit",
        'lead' =>
            "meebey",
        'stable' =>
            "0.5.5p1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_SMTP' =>
        array(
        'packageid' =>
            "90",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Provides an implementation of the SMTP protocol",
        'description' =>
            "Provides an implementation of the SMTP protocol using PEAR\'s Net_Socket class.",
        'lead' =>
            "jon",
        'stable' =>
            "1.2.6",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Socket' =>
        array(
        'packageid' =>
            "64",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "Network Socket Interface",
        'description' =>
            "Net_Socket is a class interface to TCP sockets.  It provides blocking
and non-blocking operation, with different reading and writing modes
(byte-wise, block-wise, line-wise and special formats like network
byte-order ip addresses).",
        'lead' =>
            "chagenbu",
        'stable' =>
            "1.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_URL' =>
        array(
        'packageid' =>
            "34",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "BSD",
        'summary' =>
            "Easy parsing of Urls",
        'description' =>
            "Provides easy parsing of URLs and their constituent parts.",
        'lead' =>
            "richard",
        'stable' =>
            "1.0.14",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_UserAgent_Detect' =>
        array(
        'packageid' =>
            "62",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP 2.01",
        'summary' =>
            "Net_UserAgent_Detect determines the Web browser, version, and platform from an HTTP user agent string",
        'description' =>
            "The Net_UserAgent object does a number of tests on an HTTP user
agent string.  The results of these tests are available via methods of
the object.

This module is based upon the JavaScript browser detection code
available at http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html.
This module had many influences from the lib/Browser.php code in
version 1.3 of Horde.",
        'lead' =>
            "jrust",
        'stable' =>
            "2.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Net_Whois' =>
        array(
        'packageid' =>
            "13",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP",
        'summary' =>
            "The PEAR::Net_Whois class provides a tool to query internet domain name and network number directory services",
        'description' =>
            "The PEAR::Net_Whois looks up records in the databases maintained by several Network Information Centers (NICs).",
        'lead' =>
            "svenasse",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Numbers_Roman' =>
        array(
        'packageid' =>
            "29",
        'categoryid' =>
            "17",
        'category' =>
            "Numbers",
        'license' =>
            "PHP",
        'summary' =>
            "Provides methods for converting to and from Roman Numerals.",
        'description' =>
            "Numbers_Roman provides static methods for converting to and from Roman 
numerals. It supports Roman numerals in both uppercase and lowercase 
styles and conversion for and to numbers up to 5 999 999",
        'lead' =>
            "gurugeek",
        'stable' =>
            "0.2.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'oci8' =>
        array(
        'packageid' =>
            "277",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP",
        'summary' =>
            "Oracle Call Interface(OCI) wrapper",
        'description' =>
            "This module allows you to access Oracle9/8/7 database. 
It wraps the Oracle Call Interface (OCI).",
        'lead' =>
            "tony2001",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'odbtp' =>
        array(
        'packageid' =>
            "323",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "LGPL",
        'summary' =>
            "ODBTP client functions",
        'description' =>
            "This extension provides a set of ODBTP, Open Database Transport
Protocol, client functions. ODBTP allows any platform to remotely
use the ODBC facilities installed on a Win32 host to connect to a database.  Linux and UNIX clients can use this
extension to access Win32 databases like MS SQL Server, MS Access
and Visual FoxPro.",
        'lead' =>
            "rtwitty",
        'stable' =>
            "1.1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Pager' =>
        array(
        'packageid' =>
            "32",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "Data paging class",
        'description' =>
            "It takes an array of data as input and page it according to various parameters. It also builds links within a specified range, and allows complete customization of the output (it even works with mod_rewrite).
Two operating modes available: \"Jumping\" and \"Sliding\" window style.",
        'lead' =>
            "quipo",
        'stable' =>
            "2.2.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Pager_Sliding' =>
        array(
        'packageid' =>
            "136",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP License",
        'summary' =>
            "Sliding Window Pager.",
        'description' =>
            "It takes an array of data as input and page it according to various parameters. It also builds links within a specified range, and allows complete customization of the output (it even works with mod_rewrite). It is compatible with PEAR::Pager\'s API.

[Deprecated]Use PEAR::Pager v2.x with \$mode = \'Sliding\' instead",
        'lead' =>
            "quipo",
        'stable' =>
            "1.6",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Paradox' =>
        array(
        'packageid' =>
            "268",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP License",
        'summary' =>
            "An extension to read Paradox files",
        'description' =>
            "Paradox is an extension to read and write Paradox .DB and .PX files.
It can handle almost all field types and binary large objects stored
in .MB files.",
        'lead' =>
            "steinm",
        'stable' =>
            "1.3.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'parsekit' =>
        array(
        'packageid' =>
            "331",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "PHP",
        'summary' =>
            "PHP Opcode Analyser",
        'description' =>
            "Provides a userspace interpretation of the opcodes generated by the Zend engine compiler built into PHP.
This extension is meant for development and debug purposes only and contains some code which is potentially non-threadsafe.",
        'lead' =>
            "pollita",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Payment_Clieop' =>
        array(
        'packageid' =>
            "51",
        'categoryid' =>
            "18",
        'category' =>
            "Payment",
        'license' =>
            "PHP",
        'summary' =>
            "These classes can create a clieop03 file for you which you can send to a Dutch Bank. Ofcourse you need also a Dutch bank account.",
        'description' =>
            "Clieop03 generation classes",
        'lead' =>
            "zyprexia",
        'stable' =>
            "0.1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Payment_DTA' =>
        array(
        'packageid' =>
            "244",
        'categoryid' =>
            "18",
        'category' =>
            "Payment",
        'license' =>
            "BSD style",
        'summary' =>
            "Creates DTA files containing money transaction data (Germany).",
        'description' =>
            "Payment_DTA provides functions to create DTA files used in Germany to exchange informations about money transactions with banks or online banking programs.",
        'lead' =>
            "hstainer",
        'stable' =>
            "1.00",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'pdflib' =>
        array(
        'packageid' =>
            "355",
        'categoryid' =>
            "36",
        'category' =>
            "Text",
        'license' =>
            "PHP",
        'summary' =>
            "Creating PDF on the fly with the PDFlib library",
        'description' =>
            "This extension wraps the PDFlib programming library
for processing PDF on the fly, created by Thomas Merz. 

PDFlib is available under the PDFlib Lite License
(http://www.pdflib.com/pdffiles/PDFlib-Lite-license.pdf)
and for commercial licensing.",
        'lead' =>
            "steinm",
        'stable' =>
            "2.0.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PEAR' =>
        array(
        'packageid' =>
            "14",
        'categoryid' =>
            "19",
        'category' =>
            "PEAR",
        'license' =>
            "PHP License",
        'summary' =>
            "PEAR Base System",
        'description' =>
            "The PEAR package contains:
 * the PEAR installer, for creating, distributing
   and installing packages
 * the alpha-quality PEAR_Exception php5-only exception class
 * the beta-quality PEAR_ErrorStack advanced error handling mechanism
 * the PEAR_Error error handling mechanism
 * the OS_Guess class for retrieving info about the OS
   where PHP is running on
 * the System class for quick handling common operations
   with files and directories
 * the PEAR base class",
        'lead' =>
            "cellog",
        'stable' =>
            "1.3.3.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PEAR_Info' =>
        array(
        'packageid' =>
            "195",
        'categoryid' =>
            "19",
        'category' =>
            "PEAR",
        'license' =>
            "PHP License",
        'summary' =>
            "Show Information about your PEAR install and its packages",
        'description' =>
            "This package generates a comprehensive information page for your current PEAR install.
* The format for the page is similar to that for phpinfo() except using PEAR colors.
* Has complete PEAR Credits (based on the packages you have installed).
* Will show if there is a newer version than the one presently installed (and what its state is)
* Each package has an anchor in the form pkg_PackageName - where PackageName is a case-sensitive PEAR package name",
        'lead' =>
            "davey",
        'stable' =>
            "1.5.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PEAR_PackageFileManager' =>
        array(
        'packageid' =>
            "227",
        'categoryid' =>
            "19",
        'category' =>
            "PEAR",
        'license' =>
            "PHP License",
        'summary' =>
            "PEAR_PackageFileManager takes an existing package.xml file and updates it with a new filelist and changelog",
        'description' =>
            "This package revolutionizes the maintenance of PEAR packages.  With a few parameters,
the entire package.xml is automatically updated with a listing of all files in a package.
Features include
 - reads in an existing package.xml file, and only changes the release/changelog
 - a plugin system for retrieving files in a directory.  Currently two plugins
   exist, one for standard recursive directory content listing, and one that
   reads the CVS/Entries files and generates a file listing based on the contents
   of a checked out CVS repository
 - incredibly flexible options for assigning install roles to files/directories
 - ability to ignore any file based on a * ? wildcard-enabled string(s)
 - ability to include only files that match a * ? wildcard-enabled string(s)
 - ability to manage dependencies
 - can output the package.xml in any directory, and read in the package.xml
   file from any directory.
 - can specify a different name for the package.xml file
 
As of version 1.2.0, PEAR_PackageFileManager is fully unit tested.",
        'lead' =>
            "cellog",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'perl' =>
        array(
        'packageid' =>
            "305",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "PHP",
        'summary' =>
            "Embedded Perl.",
        'description' =>
            "This extension embeds Perl Interpreter into PHP. It allows execute Perl files, evaluate Perl code, access Perl variables and instantiate Perl objects.",
        'lead' =>
            "dmitry",
        'stable' =>
            "0.6",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PhpDocumentor' =>
        array(
        'packageid' =>
            "137",
        'categoryid' =>
            "29",
        'category' =>
            "Tools and Utilities",
        'license' =>
            "PHP License",
        'summary' =>
            "The phpDocumentor package provides automatic documenting of php api directly from the source.",
        'description' =>
            "The phpDocumentor tool is a standalone auto-documentor similar to JavaDoc
written in PHP.  It differs from PHPDoc in that it is MUCH faster, parses a much
wider range of php files, and comes with many customizations including 11 HTML
templates, windows help file CHM output, PDF output, and XML DocBook peardoc2
output for use with documenting PEAR.  In addition, it can do PHPXref source
code highlighting and linking.

Features (short list):
-output in HTML, PDF (directly), CHM (with windows help compiler), XML DocBook
-very fast
-web and command-line interface
-fully customizable output with Smarty-based templates
-recognizes JavaDoc-style documentation with special tags customized for PHP 4
-automatic linking, class inheritance diagrams and intelligent override
-customizable source code highlighting, with phpxref-style cross-referencing
-parses standard README/CHANGELOG/INSTALL/FAQ files and includes them
 directly in documentation
-generates a todo list from @todo tags in source
-generates multiple documentation sets based on @access private, @internal and
 {@internal} tags
-example php files can be placed directly in documentation with highlighting
 and phpxref linking using the @example tag
-linking between external manual and API documentation is possible at the
 sub-section level in all output formats
-easily extended for specific documentation needs with Converter
-full documentation of every feature, manual can be generated directly from
 the source code with \"phpdoc -c makedocs\" in any format desired.
-current manual always available at http://www.phpdoc.org/manual.php
-user .ini files can be used to control output, multiple outputs can be
 generated at once
 
**WARNING**:
To use the web interface, you must set PEAR\'s data_dir to a subdirectory of
document root.

If browsing to http://localhost/index.php displays /path/to/htdocs/index.php,
set data_dir to a subdirectory of /path/to/htdocs:

$ pear config-set data_dir /path/to/htdocs/pear
$ pear install PhpDocumentor

http://localhost/pear/PhpDocumentor is the web interface",
        'lead' =>
            "cellog",
        'stable' =>
            "1.2.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PHPUnit' =>
        array(
        'packageid' =>
            "38",
        'categoryid' =>
            "43",
        'category' =>
            "Testing",
        'license' =>
            "PHP License",
        'summary' =>
            "Regression testing framework for unit tests.",
        'description' =>
            "PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP.",
        'lead' =>
            "sebastian",
        'stable' =>
            "1.1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PHPUnit2' =>
        array(
        'packageid' =>
            "308",
        'categoryid' =>
            "43",
        'category' =>
            "Testing",
        'license' =>
            "PHP License",
        'summary' =>
            "Regression testing framework for unit tests.",
        'description' =>
            "PHPUnit is a regression testing framework used by the developer who implements unit tests in PHP.",
        'lead' =>
            "sebastian",
        'stable' =>
            "2.1.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'PHP_Compat' =>
        array(
        'packageid' =>
            "338",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "PHP License",
        'summary' =>
            "Provides missing functionality for older versions of PHP",
        'description' =>
            "PHP_Compat provides missing functionality in the form of
Constants and Functions for older versions of PHP.",
        'lead' =>
            "aidan",
        'stable' =>
            "1.3.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'ps' =>
        array(
        'packageid' =>
            "299",
        'categoryid' =>
            "36",
        'category' =>
            "Text",
        'license' =>
            "PHP License",
        'summary' =>
            "An extension to create PostScript files",
        'description' =>
            "ps is an extension similar to the pdf extension but for creating PostScript files. Its api is modelled after the pdf extension.",
        'lead' =>
            "steinm",
        'stable' =>
            "1.3.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'radius' =>
        array(
        'packageid' =>
            "149",
        'categoryid' =>
            "1",
        'category' =>
            "Authentication",
        'license' =>
            "BSD",
        'summary' =>
            "Radius client library",
        'description' =>
            "This package is based on the libradius of FreeBSD, with some modifications and extensions. 
This PECL provides full support for RADIUS authentication (RFC 2865) and RADIUS accounting (RFC 2866), 
works on Unix and on Windows. Its an easy way to authenticate your users against the user-database of your 
OS (for example against Windows Active-Directory via IAS).",
        'lead' =>
            "mbretter",
        'stable' =>
            "1.2.4",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Science_Chemistry' =>
        array(
        'packageid' =>
            "15",
        'categoryid' =>
            "21",
        'category' =>
            "Science",
        'license' =>
            "PHP License",
        'summary' =>
            "Classes to manipulate chemical objects: atoms, molecules, etc.",
        'description' =>
            "General classes to represent Atoms, Molecules and Macromolecules.  Also
parsing code for PDB, CML and XYZ file formats.  Examples of parsing and
conversion to/from chemical structure formats. Includes a utility class with
information on the Elements in the Periodic Table.",
        'lead' =>
            "jmcastagnetto",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Services_Weather' =>
        array(
        'packageid' =>
            "260",
        'categoryid' =>
            "23",
        'category' =>
            "Web Services",
        'license' =>
            "PHP License",
        'summary' =>
            "This class acts as an interface to various online weather-services.",
        'description' =>
            "Services_Weather searches for given locations and retrieves current
weather data and, dependent on the used service, also forecasts. Up to
now, GlobalWeather from CapeScience, Weather XML from EJSE (US only),
a XOAP service from Weather.com and METAR/TAF from NOAA are supported.
Further services will get included, if they become available, have a
usable API and are properly documented.",
        'lead' =>
            "eru",
        'stable' =>
            "1.3.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'spplus' =>
        array(
        'packageid' =>
            "133",
        'categoryid' =>
            "18",
        'category' =>
            "Payment",
        'license' =>
            "LGPL",
        'summary' =>
            "SPPLUS Paiement System",
        'description' =>
            "This extension gives you the possibility to use the SPPLUS Paiement System of the Caisse d\'Epargne (French Bank).",
        'lead' =>
            "nicos",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'SQLite' =>
        array(
        'packageid' =>
            "193",
        'categoryid' =>
            "7",
        'category' =>
            "Database",
        'license' =>
            "PHP",
        'summary' =>
            "SQLite database bindings",
        'description' =>
            "SQLite is a C library that implements an embeddable SQL database engine.
Programs that link with the SQLite library can have SQL database access
without running a separate RDBMS process.
This extension allows you to access SQLite databases from within PHP.
Windows binary for PHP 4.3 is available from:
http://snaps.php.net/win32/PECL_4_3/php_sqlite.dll
**Note that this extension is built into PHP 5 by default**",
        'lead' =>
            "iliaa",
        'stable' =>
            "1.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Stream_SHM' =>
        array(
        'packageid' =>
            "161",
        'categoryid' =>
            "35",
        'category' =>
            "Streams",
        'license' =>
            "PHP",
        'summary' =>
            "Shared Memory Stream",
        'description' =>
            "The Stream_SHM package provides a class that can be registered with stream_register_wrapper() in order to have stream-based shared-memory access.",
        'lead' =>
            "sklar",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Stream_Var' =>
        array(
        'packageid' =>
            "241",
        'categoryid' =>
            "35",
        'category' =>
            "Streams",
        'license' =>
            "PHP License",
        'summary' =>
            "Allows stream based access to any variable.",
        'description' =>
            "Stream_Var can be registered as a stream with stream_register_wrapper() and allows stream based acces to variables in any scope. Arrays are treated as directories, so it\'s possible to replace temporary directories and files in your application with variables.",
        'lead' =>
            "schst",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Structures_Graph' =>
        array(
        'packageid' =>
            "296",
        'categoryid' =>
            "27",
        'category' =>
            "Structures",
        'license' =>
            "LGPL",
        'summary' =>
            "Graph datastructure manipulation library",
        'description' =>
            "Structures_Graph is a package for creating and manipulating graph datastructures. It allows building of directed
and undirected graphs, with data and metadata stored in nodes. The library provides functions for graph traversing
as well as for characteristic extraction from the graph topology.",
        'lead' =>
            "sergiosgc",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'System_Command' =>
        array(
        'packageid' =>
            "74",
        'categoryid' =>
            "5",
        'category' =>
            "Console",
        'license' =>
            "PHP License",
        'summary' =>
            "PEAR::System_Command is a commandline execution interface.",
        'description' =>
            "System_Command is a commandline execution interface.
Running functions from the commandline can be risky if the proper precautions are
not taken to escape the shell arguments and reaping the exit status properly.  This class
provides a formal interface to both, so that you can run a system command as comfortably as
you would run a php function, with full pear error handling as results on failure.
It is important to note that this class, unlike other implementations, distinguishes between
output to stderr and output to stdout.  It also reports the exit status of the command.  
So in every sense of the word, it gives php shell capabilities.",
        'lead' =>
            "dallen",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'System_Mount' =>
        array(
        'packageid' =>
            "346",
        'categoryid' =>
            "37",
        'category' =>
            "System",
        'license' =>
            "PHP License v3.0",
        'summary' =>
            "Mount and unmount devices in fstab",
        'description' =>
            "System_Mount provides a simple interface to deal with mounting and unmounting devices listed in the system\'s fstab.

Features:
* Very compact, easy-to-read code, based on File_Fstab.
* Examines mount options to determine if a device can be mounted or not.
* Extremely easy to use.
* Fully documented with PHPDoc.",
        'lead' =>
            "ieure",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'TCLink' =>
        array(
        'packageid' =>
            "160",
        'categoryid' =>
            "18",
        'category' =>
            "Payment",
        'license' =>
            "LGPL",
        'summary' =>
            "Enables credit card processing via the TrustCommerce payment gateway",
        'description' =>
            "This package provides a module for using TCLink directly from PHP scripts.
TCLink is a thin client library to allow your e-commerce servers to connect
to the TrustCommerce payment gateway.",
        'lead' =>
            "witten",
        'stable' =>
            "3.4.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'tcpwrap' =>
        array(
        'packageid' =>
            "263",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "tcpwrappers binding.",
        'description' =>
            "This package handles /etc/hosts.allow and /etc/hosts.deny files.",
        'lead' =>
            "mg",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Text_Password' =>
        array(
        'packageid' =>
            "207",
        'categoryid' =>
            "36",
        'category' =>
            "Text",
        'license' =>
            "PHP License",
        'summary' =>
            "Creating passwords with PHP.",
        'description' =>
            "Text_Password allows one to create pronounceable and unpronounceable
passwords. The full functional range is explained in the manual at
http://pear.php.net/manual/.",
        'lead' =>
            "olivier",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Text_Statistics' =>
        array(
        'packageid' =>
            "171",
        'categoryid' =>
            "27",
        'category' =>
            "Structures",
        'license' =>
            "PHP License",
        'summary' =>
            "Compute readability indexes for documents.",
        'description' =>
            "Text_Statistics allows for computation of readability indexes for
text documents.",
        'lead' =>
            "gschlossnagle",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'tidy' =>
        array(
        'packageid' =>
            "236",
        'categoryid' =>
            "10",
        'category' =>
            "HTML",
        'license' =>
            "PHP",
        'summary' =>
            "Tidy HTML Repairing and Parsing",
        'description' =>
            "Tidy is a binding for the Tidy HTML clean and repair utility which
allows you to parse, diagnose, repair, and otherwise manipulate HTML,
XHTML and XML documents quickly.",
        'lead' =>
            "iliaa",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Translation' =>
        array(
        'packageid' =>
            "124",
        'categoryid' =>
            "28",
        'category' =>
            "Internationalization",
        'license' =>
            "PHP License",
        'summary' =>
            "Class for creating multilingual websites.",
        'description' =>
            "Class allows storing and retrieving all the strings on multilingual site in a database. The class connects to any database using PEAR::DB extension. The object should be created for every page. While creation all the strings connected with specific page and the strings connected with all the pages on the site are loaded into variable, so access to them is quite fast and does not overload database server connection.",
        'lead' =>
            "quipo",
        'stable' =>
            "1.2.6pl1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'uuid' =>
        array(
        'packageid' =>
            "216",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP License",
        'summary' =>
            "UUID support functions",
        'description' =>
            "This extension provides functions to generate and analyse
universally unique identifiers (UUIDs). It depends on the
external libuuid. This library is available on most linux 
systems, its source is bundled with the ext2fs tools.",
        'lead' =>
            "hholzgra",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Var_Dump' =>
        array(
        'packageid' =>
            "103",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "PHP License",
        'summary' =>
            "Provides methods for dumping structured information about a variable.",
        'description' =>
            "The Var_Dump class is a wrapper for the var_dump function.

The var_dump function displays structured information about expressions that includes its type and value. Arrays are explored recursively with values indented to show structure.

The Var_Dump class captures the output of the var_dump function, by using output control functions, and then uses external renderer classes for displaying the result in various graphical ways :
* Simple text,
* HTML/XHTML text,
* HTML/XHTML table,
* XML,
* ...",
        'lead' =>
            "fredericpoeydomenge",
        'stable' =>
            "1.0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'xattr' =>
        array(
        'packageid' =>
            "380",
        'categoryid' =>
            "9",
        'category' =>
            "File System",
        'license' =>
            "PHP License",
        'summary' =>
            "Extended attributes.",
        'description' =>
            "This package allows to manipulate extended attributes on filesystems that support them. Requires libattr from Linux XFS project.",
        'lead' =>
            "mg",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'Xdebug' =>
        array(
        'packageid' =>
            "214",
        'categoryid' =>
            "25",
        'category' =>
            "PHP",
        'license' =>
            "BSD style",
        'summary' =>
            "Provides functions for function traces and profiling",
        'description' =>
            "The Xdebug extension helps you debugging your script by providing a lot of
valuable debug information. The debug information that Xdebug can provide
includes the following:

    * stack and function traces in error messages with:
          o full parameter display for user defined functions
          o function name, file name and line indications
          o support for member functions
    * memory allocation
    * protection for infinite recursions

Xdebug also provides:

    * profiling information for PHP scripts
    * script execution analysis
    * capabilities to debug your scripts interactively with a debug client",
        'lead' =>
            "derick",
        'stable' =>
            "1.3.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'xdiff' =>
        array(
        'packageid' =>
            "283",
        'categoryid' =>
            "36",
        'category' =>
            "Text",
        'license' =>
            "PHP License",
        'summary' =>
            "File differences/patches.",
        'description' =>
            "This extension creates and applies patches to both text and binary files.",
        'lead' =>
            "mg",
        'stable' =>
            "1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'xmlReader' =>
        array(
        'packageid' =>
            "330",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "Provides fast, non-cached, forward-only access to XML data.",
        'description' =>
            "This extension wraps the libxml xmlReader API. The reader acts as a cursor 
going forward on the document stream and stopping at each node in the way. 
xmlReader is similar to SAX though uses a much simpler API.",
        'lead' =>
            "rrichards",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_Beautifier' =>
        array(
        'packageid' =>
            "256",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "Class to format XML documents.",
        'description' =>
            "XML_Beautifier will add indentation and linebreaks to you XML files, replace all entities, format your comments and makes your document easier to read. You can influence the way your document is beautified with several options.",
        'lead' =>
            "schst",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_CSSML' =>
        array(
        'packageid' =>
            "61",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "The PEAR::XML_CSSML package provides methods for creating cascading style sheets (CSS) from an XML standard called CSSML.",
        'description' =>
            "The best way to describe this library is to classify it as a template system for generating cascading style sheets (CSS). It is ideal for storing all of the CSS in a single location and allowing it to be parsed as needed at runtime (or from cache) using both general and browser filters specified in the attribute for the style tags. It can be driven with either the libxslt pear extenstion (part of xmldom) or the xslt extension (part of the sablotron libraries).

You may see an example usage of this class at the follow url: 

http://mojave.mojavelinux.com/forum/viewtopic.php?p=22#22

Users may post questions or comments about the class at this location.
  
My hope is that such a system becomes the standard for the organization of stylesheet information in the future.",
        'lead' =>
            "dallen",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_fo2pdf' =>
        array(
        'packageid' =>
            "16",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "Converts a xsl-fo file to pdf/ps/pcl/text/etc with the help of apache-fop",
        'description' =>
            "Converts a xsl-fo file to pdf/ps/pcl/text/etc with the help of apache-fop",
        'lead' =>
            "chregu",
        'stable' =>
            "0.98",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_HTMLSax' =>
        array(
        'packageid' =>
            "203",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP",
        'summary' =>
            "A SAX parser for HTML and other badly formed XML documents",
        'description' =>
            "XML_HTMLSax is a SAX based XML parser for badly formed XML documents, such as HTML.
  The original code base was developed by Alexander Zhukov and published at http://sourceforge.net/projects/phpshelve/. Alexander kindly gave permission to modify the code and license for inclusion in PEAR.

  PEAR::XML_HTMLSax provides an API very similar to the native PHP XML extension (http://www.php.net/xml), allowing handlers using one to be easily adapted to the other. The key difference is HTMLSax will not break on badly formed XML, allowing it to be used for parsing HTML documents. Otherwise HTMLSax supports all the handlers available from Expat except namespace and external entity handlers. Provides methods for handling XML escapes as well as JSP/ASP opening and close tags.

  Version 1.x introduced an API similar to the native SAX extension but used a slow character by character approach to parsing.

  Version 2.x has had it\'s internals completely overhauled to use a Lexer, delivering performance *approaching* that of the native XML extension, as well as a radically improved, modular design that makes adding further functionality easy.

  Version 3.x is about fine tuning the API, behaviour and providing a mechanism to distinguish HTML \"quirks\" from badly formed HTML (later functionality not yet implemented)

  A big thanks to Jeff Moore (lead developer of WACT: http://wact.sourceforge.net) who\'s largely responsible for new design, as well input from other members at Sitepoint\'s Advanced PHP forums: http://www.sitepointforums.com/showthread.php?threadid=121246.

  Thanks also to Marcus Baker (lead developer of SimpleTest: http://www.lastcraft.com/simple_test.php) for sorting out the unit tests.",
        'lead' =>
            "hfuecks",
        'stable' =>
            "2.1.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_image2svg' =>
        array(
        'packageid' =>
            "66",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP 2.02",
        'summary' =>
            "Image to SVG conversion",
        'description' =>
            "The class converts images, such as of the format JPEG, PNG and GIF to a standalone SVG representation. The image is being encoded by the PHP native encode_base64() function. You can use it to get back a complete SVG file, which is based on a predefinded, easy adaptable template file, or you can take the encoded file as a return value, using the get() method. Due to the encoding by base64, the SVG files will increase approx. 30% in size compared to the conventional image.",
        'lead' =>
            "urs",
        'stable' =>
            "0.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_NITF' =>
        array(
        'packageid' =>
            "188",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "Parse NITF documents.",
        'description' =>
            "This package provides a NITF XML parser. The parser was designed with NITF version 3.1, but should be forward-compatible when new versions of the NITF DTD are produced. Various methods for accessing the major elements of the document, such as the hedline(s), byline, and lede are provided. This class was originally tested against the Associated Press\'s (AP) XML data feed.",
        'lead' =>
            "polone",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_Parser' =>
        array(
        'packageid' =>
            "56",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "XML parsing class based on PHP\'s bundled expat",
        'description' =>
            "This is an XML parser based on PHPs built-in xml extension.
It supports two basic modes of operation: \"func\" and \"event\".  In \"func\" mode, it will look for a function named after each element (xmltag_ELEMENT for start tags and xmltag_ELEMENT_ for end tags), and in \"event\" mode it uses a set of generic callbacks.

Since version 1.2.0 there\'s a new XML_Parser_Simple class that makes parsing of most XML documents easier, by automatically providing a stack for the elements.
Furthermore its now possible to split the parser from the handler object, so you do not have to extend XML_Parser anymore in order to parse a document with it.",
        'lead' =>
            "schst",
        'stable' =>
            "1.2.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_RPC' =>
        array(
        'packageid' =>
            "17",
        'categoryid' =>
            "23",
        'category' =>
            "Web Services",
        'license' =>
            "PHP License",
        'summary' =>
            "PHP implementation of the XML-RPC protocol",
        'description' =>
            "This is a PEAR-ified version of Useful inc\'s XML-RPC
for PHP.  It has support for HTTP transport, proxies and authentication.",
        'lead' =>
            "ssb",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_RSS' =>
        array(
        'packageid' =>
            "22",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "RSS parser",
        'description' =>
            "Parser for Resource Description Framework (RDF) Site Summary (RSS)
documents.",
        'lead' =>
            "mj",
        'stable' =>
            "0.9.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_SVG' =>
        array(
        'packageid' =>
            "221",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "LGPL",
        'summary' =>
            "XML_SVG API",
        'description' =>
            "This package provides an object-oriented API for building SVG documents.",
        'lead' =>
            "yunosh",
        'stable' =>
            "0.0.3",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_Transformer' =>
        array(
        'packageid' =>
            "37",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "XML Transformations in PHP",
        'description' =>
            "The XML Transformer allows the binding of PHP functionality to XML tags to transform an XML document without the need for and the limitations of XSLT.",
        'lead' =>
            "sebastian",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_Tree' =>
        array(
        'packageid' =>
            "19",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "Represent XML data in a tree structure",
        'description' =>
            "Allows for the building of XML data structures using a tree
representation, without the need for an extension like DOMXML.",
        'lead' =>
            "davey",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_Util' =>
        array(
        'packageid' =>
            "234",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "XML utility class.",
        'description' =>
            "Selection of methods that are often needed when working with XML documents. Functionality includes creating of attribute lists from arrays, creation of tags, validation of XML names and more.",
        'lead' =>
            "schst",
        'stable' =>
            "1.1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'XML_Wddx' =>
        array(
        'packageid' =>
            "309",
        'categoryid' =>
            "22",
        'category' =>
            "XML",
        'license' =>
            "PHP License",
        'summary' =>
            "Wddx pretty serializer and deserializer",
        'description' =>
            "XML_Wddx does 2 things:
a) a drop in replacement for the XML_Wddx extension (if it\'s not built in)
b) produce an editable wddx file (with indenting etc.) and uses CDATA, rather than char tags
This package contains 2 static method:
XML_Wddx:serialize(\$value)
XML_Wddx:deserialize(\$value)
should be 90% compatible with wddx_deserialize(), and the deserializer will use wddx_deserialize if it is built in..
No support for recordsets is available at present in the PHP version of the deserializer.",
        'lead' =>
            "alan_k",
        'stable' =>
            "1.0.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'yaz' =>
        array(
        'packageid' =>
            "322",
        'categoryid' =>
            "16",
        'category' =>
            "Networking",
        'license' =>
            "PHP",
        'summary' =>
            "a Z39.50 client for PHP",
        'description' =>
            "This extension implements a Z39.50 client for PHP using the YAZ toolkit.

Find more information at:
  http://www.indexdata.dk/phpyaz/
  http://www.indexdata.dk/yaz/",
        'lead' =>
            "dickmeiss",
        'stable' =>
            "1.0.2",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'zip' =>
        array(
        'packageid' =>
            "208",
        'categoryid' =>
            "33",
        'category' =>
            "File Formats",
        'license' =>
            "PHP License",
        'summary' =>
            "A zip management extension",
        'description' =>
            "Zip is an extension to read zip files.",
        'lead' =>
            "sterling",
        'stable' =>
            "1.0",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    'zlib_filter' =>
        array(
        'packageid' =>
            "315",
        'categoryid' =>
            "35",
        'category' =>
            "Streams",
        'license' =>
            "PHP",
        'summary' =>
            "zlib filter implementation backport for PHP 5.0",
        'description' =>
            "RFC 1951 inflate/deflate stream filter implementation.  Performs inline compression/decompression using the deflate method on any PHP I/O stream.  The data produced by this filter, while compatable with the payload portion of an RFC 1952 gzip file, does not include headers or tailers for full RFC 1952 gzip compatability.  To achieve this format, use the compress.zlib:// fopen wrapper built directly into PHP.",
        'lead' =>
            "pollita",
        'stable' =>
            "1.1",
        'unstable' =>
            false,
        'state' =>
            "stable",
        'deps' =>
            array(
            ),
        ),
    ));
$reg = &$config->getRegistry();
$ch = new PEAR_ChannelFile;
$ch->setName('smoog');
$ch->setSUmmary('smoog');
$ch->setDefaultPEARProtocols();
$reg->addChannel($ch);
$ch->setName('empty');
$reg->addChannel($ch);
$e = $command->run('list-all', array(), array());
$phpunit->assertNoErrors('pear.php.net');
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'All packages [Channel pear.php.net]:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Latest',
        2 => 'Local',
      ),
      'channel' => 'pear.php.net',
      'data' => 
      array (
        'Caching' => 
        array (
          0 => 
          array (
            0 => 'pear/APC',
            1 => '2.0.4',
            2 => NULL,
            3 => 'Alternative PHP Cache',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Cache',
            1 => '1.5.4',
            2 => NULL,
            3 => 'Framework for caching of arbitrary data.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Cache_Lite',
            1 => '1.3.1',
            2 => NULL,
            3 => 'Fast and Safe little cache system',
            4 => 
            array (
            ),
          ),
        ),
        'PHP' => 
        array (
          0 => 
          array (
            0 => 'pear/apd',
            1 => '1.0.1',
            2 => NULL,
            3 => 'A full-featured engine-level profiler/debugger',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/memcache',
            1 => '1.4',
            2 => NULL,
            3 => 'memcached extension',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/parsekit',
            1 => '1.0',
            2 => NULL,
            3 => 'PHP Opcode Analyser',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/perl',
            1 => '0.6',
            2 => NULL,
            3 => 'Embedded Perl.',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/PHP_Compat',
            1 => '1.3.1',
            2 => NULL,
            3 => 'Provides missing functionality for older versions of PHP',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/Var_Dump',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Provides methods for dumping structured information about a variable.',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/Xdebug',
            1 => '1.3.2',
            2 => NULL,
            3 => 'Provides functions for function traces and profiling',
            4 => 
            array (
            ),
          ),
        ),
        'File Formats' => 
        array (
          0 => 
          array (
            0 => 'pear/Archive_Tar',
            1 => '1.2',
            2 => NULL,
            3 => 'Tar file management class',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/bz2',
            1 => '1.0',
            2 => NULL,
            3 => 'A Bzip2 management extension',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Contact_Vcard_Build',
            1 => '1.1',
            2 => NULL,
            3 => 'Build (create) and fetch vCard 2.1 and 3.0 text blocks.',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Contact_Vcard_Parse',
            1 => '1.30',
            2 => NULL,
            3 => 'Parse vCard 2.1 and 3.0 files.',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/File_Fstab',
            1 => '2.0.1',
            2 => NULL,
            3 => 'Read and write fstab files',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/File_HtAccess',
            1 => '1.1.0',
            2 => NULL,
            3 => 'Manipulate .htaccess files',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/File_Passwd',
            1 => '1.1.1',
            2 => NULL,
            3 => 'Manipulate many kinds of password files',
            4 => 
            array (
            ),
          ),
          7 => 
          array (
            0 => 'pear/File_SMBPasswd',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Class for managing SAMBA style password files.',
            4 => 
            array (
            ),
          ),
          8 => 
          array (
            0 => 'pear/MP3_ID',
            1 => '1.1.3',
            2 => NULL,
            3 => 'Read/Write MP3-Tags',
            4 => 
            array (
            ),
          ),
          9 => 
          array (
            0 => 'pear/zip',
            1 => '1.0',
            2 => NULL,
            3 => 'A zip management extension',
            4 => 
            array (
            ),
          ),
        ),
        'Authentication' => 
        array (
          0 => 
          array (
            0 => 'pear/Auth',
            1 => '1.2.3',
            2 => NULL,
            3 => 'Creating an authentication system.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Auth_HTTP',
            1 => '2.0',
            2 => NULL,
            3 => 'HTTP authentication',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Auth_PrefManager',
            1 => '1.1.3',
            2 => NULL,
            3 => 'Preferences management class',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Auth_RADIUS',
            1 => '1.0.4',
            2 => NULL,
            3 => 'Wrapper Classes for the RADIUS PECL.',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/Auth_SASL',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Abstraction of various SASL mechanism responses',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/radius',
            1 => '1.2.4',
            2 => NULL,
            3 => 'Radius client library',
            4 => 
            array (
            ),
          ),
        ),
        'Benchmarking' => 
        array (
          0 => 
          array (
            0 => 'pear/Benchmark',
            1 => '1.2.1',
            2 => NULL,
            3 => 'Framework to benchmark PHP scripts or function calls.',
            4 => 
            array (
            ),
          ),
        ),
        'Configuration' => 
        array (
          0 => 
          array (
            0 => 'pear/Config',
            1 => '1.10.3',
            2 => NULL,
            3 => 'Your configurations swiss-army knife.',
            4 => 
            array (
            ),
          ),
        ),
        'Console' => 
        array (
          0 => 
          array (
            0 => 'pear/Console_Getargs',
            1 => '1.2.1',
            2 => NULL,
            3 => 'A command-line arguments parser',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Console_Getopt',
            1 => '1.2',
            2 => NULL,
            3 => 'Command-line option parser',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Console_Table',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Class that makes it easy to build console style tables',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/System_Command',
            1 => '1.0.1',
            2 => NULL,
            3 => 'PEAR::System_Command is a commandline execution interface.',
            4 => 
            array (
            ),
          ),
        ),
        'Tools and Utilities' => 
        array (
          0 => 
          array (
            0 => 'pear/crack',
            1 => '0.1',
            2 => NULL,
            3 => '"Good Password" Checking Utility: Keep your users\\\' passwords reasonably safe from dictionary based attacks',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/huffman',
            1 => '0.2.0',
            2 => NULL,
            3 => 'Huffman compression is a lossless compression algorithm that is ideal for compressing textual data.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/PhpDocumentor',
            1 => '1.2.3',
            2 => NULL,
            3 => 'The phpDocumentor package provides automatic documenting of php api directly from the source.',
            4 => 
            array (
            ),
          ),
        ),
        'Encryption' => 
        array (
          0 => 
          array (
            0 => 'pear/Crypt_CBC',
            1 => '0.4',
            2 => NULL,
            3 => 'A class to emulate Perl\\\'s Crypt::CBC module.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Crypt_CHAP',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Generating CHAP packets.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Crypt_RC4',
            1 => '1.0.2',
            2 => NULL,
            3 => 'Encryption class for RC4 encryption',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Crypt_Xtea',
            1 => '1.0',
            2 => NULL,
            3 => 'A class that implements the Tiny Encryption Algorithm (TEA) (New Variant).',
            4 => 
            array (
            ),
          ),
        ),
        'Payment' => 
        array (
          0 => 
          array (
            0 => 'pear/cybermut',
            1 => '1.1',
            2 => NULL,
            3 => 'CyberMut Paiement System',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Payment_Clieop',
            1 => '0.1.1',
            2 => NULL,
            3 => 'These classes can create a clieop03 file for you which you can send to a Dutch Bank. Ofcourse you need also a Dutch bank account.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Payment_DTA',
            1 => '1.00',
            2 => NULL,
            3 => 'Creates DTA files containing money transaction data (Germany).',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/spplus',
            1 => '1.0',
            2 => NULL,
            3 => 'SPPLUS Paiement System',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/TCLink',
            1 => '3.4.0',
            2 => NULL,
            3 => 'Enables credit card processing via the TrustCommerce payment gateway',
            4 => 
            array (
            ),
          ),
        ),
        'Networking' => 
        array (
          0 => 
          array (
            0 => 'pear/cyrus',
            1 => '1.0',
            2 => NULL,
            3 => 'An extension which eases the manipulation of Cyrus IMAP servers.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Net_CheckIP',
            1 => '1.1',
            2 => NULL,
            3 => 'Check the syntax of IPv4 addresses',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Net_Curl',
            1 => '0.2',
            2 => NULL,
            3 => 'Net_Curl provides an OO interface to PHP\\\'s cURL extension',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Net_Dict',
            1 => '1.0.3',
            2 => NULL,
            3 => 'Interface to the DICT Protocol',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/Net_Dig',
            1 => '0.1',
            2 => NULL,
            3 => 'The PEAR::Net_Dig class should be a nice, friendly OO interface to the dig command',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/Net_DNS',
            1 => '0.03',
            2 => NULL,
            3 => 'Resolver library used to communicate with a DNS server',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/Net_Finger',
            1 => '1.0.0',
            2 => NULL,
            3 => 'The PEAR::Net_Finger class provides a tool for querying Finger Servers',
            4 => 
            array (
            ),
          ),
          7 => 
          array (
            0 => 'pear/Net_FTP',
            1 => '1.3.0RC1',
            2 => NULL,
            3 => 'Net_FTP provides an OO interface to the PHP FTP functions plus some additions',
            4 => 
            array (
            ),
          ),
          8 => 
          array (
            0 => 'pear/Net_Geo',
            1 => '1.0',
            2 => NULL,
            3 => 'Geographical locations based on Internet address',
            4 => 
            array (
            ),
          ),
          9 => 
          array (
            0 => 'pear/Net_Ident',
            1 => '1.0',
            2 => NULL,
            3 => 'Identification Protocol implementation',
            4 => 
            array (
            ),
          ),
          10 => 
          array (
            0 => 'pear/Net_IMAP',
            1 => '1.0.3',
            2 => NULL,
            3 => 'Provides an implementation of the IMAP protocol',
            4 => 
            array (
            ),
          ),
          11 => 
          array (
            0 => 'pear/Net_IPv4',
            1 => '1.2',
            2 => NULL,
            3 => 'IPv4 network calculations and validation',
            4 => 
            array (
            ),
          ),
          12 => 
          array (
            0 => 'pear/Net_IPv6',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Check and validate IPv6 addresses',
            4 => 
            array (
            ),
          ),
          13 => 
          array (
            0 => 'pear/Net_LMTP',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Provides an implementation of the RFC2033 LMTP protocol',
            4 => 
            array (
            ),
          ),
          14 => 
          array (
            0 => 'pear/Net_NNTP',
            1 => '0.2.5',
            2 => NULL,
            3 => 'Implementation of the NNTP protocol',
            4 => 
            array (
            ),
          ),
          15 => 
          array (
            0 => 'pear/Net_Ping',
            1 => '2.4',
            2 => NULL,
            3 => 'Execute ping',
            4 => 
            array (
            ),
          ),
          16 => 
          array (
            0 => 'pear/Net_POP3',
            1 => '1.3.3',
            2 => NULL,
            3 => 'Provides a POP3 class to access POP3 server.',
            4 => 
            array (
            ),
          ),
          17 => 
          array (
            0 => 'pear/Net_Portscan',
            1 => '1.0.2',
            2 => NULL,
            3 => 'Portscanner utilities.',
            4 => 
            array (
            ),
          ),
          18 => 
          array (
            0 => 'pear/Net_Sieve',
            1 => '1.1.0',
            2 => NULL,
            3 => 'Handles talking to timsieved',
            4 => 
            array (
            ),
          ),
          19 => 
          array (
            0 => 'pear/Net_SmartIRC',
            1 => '0.5.5p1',
            2 => NULL,
            3 => 'Net_SmartIRC is a PHP class for communication with IRC networks',
            4 => 
            array (
            ),
          ),
          20 => 
          array (
            0 => 'pear/Net_SMTP',
            1 => '1.2.6',
            2 => NULL,
            3 => 'Provides an implementation of the SMTP protocol',
            4 => 
            array (
            ),
          ),
          21 => 
          array (
            0 => 'pear/Net_Socket',
            1 => '1.0.4',
            2 => NULL,
            3 => 'Network Socket Interface',
            4 => 
            array (
            ),
          ),
          22 => 
          array (
            0 => 'pear/Net_URL',
            1 => '1.0.14',
            2 => NULL,
            3 => 'Easy parsing of Urls',
            4 => 
            array (
            ),
          ),
          23 => 
          array (
            0 => 'pear/Net_UserAgent_Detect',
            1 => '2.0.1',
            2 => NULL,
            3 => 'Net_UserAgent_Detect determines the Web browser, version, and platform from an HTTP user agent string',
            4 => 
            array (
            ),
          ),
          24 => 
          array (
            0 => 'pear/Net_Whois',
            1 => '1.0',
            2 => NULL,
            3 => 'The PEAR::Net_Whois class provides a tool to query internet domain name and network number directory services',
            4 => 
            array (
            ),
          ),
          25 => 
          array (
            0 => 'pear/tcpwrap',
            1 => '1.0',
            2 => NULL,
            3 => 'tcpwrappers binding.',
            4 => 
            array (
            ),
          ),
          26 => 
          array (
            0 => 'pear/uuid',
            1 => '1.0',
            2 => NULL,
            3 => 'UUID support functions',
            4 => 
            array (
            ),
          ),
          27 => 
          array (
            0 => 'pear/yaz',
            1 => '1.0.2',
            2 => NULL,
            3 => 'a Z39.50 client for PHP',
            4 => 
            array (
            ),
          ),
        ),
        'Date and Time' => 
        array (
          0 => 
          array (
            0 => 'pear/Date',
            1 => '1.4.3',
            2 => NULL,
            3 => 'Date and Time Zone Classes',
            4 => 
            array (
            ),
          ),
        ),
        'Database' => 
        array (
          0 => 
          array (
            0 => 'pear/DB',
            1 => '1.6.8',
            2 => NULL,
            3 => 'Database Abstraction Layer',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/DBA',
            1 => '1.1',
            2 => NULL,
            3 => 'Berkely-style database abstraction class',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/DB_ado',
            1 => '1.3',
            2 => NULL,
            3 => 'DB driver which use MS ADODB library',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/DB_DataObject',
            1 => '1.7.2',
            2 => NULL,
            3 => 'An SQL Builder, Object Interface to Database Tables',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/DB_ldap',
            1 => '1.1.0',
            2 => NULL,
            3 => 'DB interface to LDAP server',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/DB_NestedSet',
            1 => '1.2.4',
            2 => NULL,
            3 => 'API to build and query nested sets',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/DB_odbtp',
            1 => '1.0.2',
            2 => NULL,
            3 => 'DB interface for ODBTP',
            4 => 
            array (
            ),
          ),
          7 => 
          array (
            0 => 'pear/DB_Pager',
            1 => '0.7',
            2 => NULL,
            3 => 'Retrieve and return information of database result sets',
            4 => 
            array (
            ),
          ),
          8 => 
          array (
            0 => 'pear/DB_QueryTool',
            1 => '0.11.1',
            2 => NULL,
            3 => 'An OO-interface for easily retrieving and modifying data in a DB.',
            4 => 
            array (
            ),
          ),
          9 => 
          array (
            0 => 'pear/MDB',
            1 => '1.3.0',
            2 => NULL,
            3 => 'database abstraction layer',
            4 => 
            array (
            ),
          ),
          10 => 
          array (
            0 => 'pear/MDB_QueryTool',
            1 => '0.11.1',
            2 => NULL,
            3 => 'An OO-interface for easily retrieving and modifying data in a DB.',
            4 => 
            array (
            ),
          ),
          11 => 
          array (
            0 => 'pear/oci8',
            1 => '1.0',
            2 => NULL,
            3 => 'Oracle Call Interface(OCI) wrapper',
            4 => 
            array (
            ),
          ),
          12 => 
          array (
            0 => 'pear/odbtp',
            1 => '1.1.2',
            2 => NULL,
            3 => 'ODBTP client functions',
            4 => 
            array (
            ),
          ),
          13 => 
          array (
            0 => 'pear/Paradox',
            1 => '1.3.0',
            2 => NULL,
            3 => 'An extension to read Paradox files',
            4 => 
            array (
            ),
          ),
          14 => 
          array (
            0 => 'pear/SQLite',
            1 => '1.0.3',
            2 => NULL,
            3 => 'SQLite database bindings',
            4 => 
            array (
            ),
          ),
        ),
        'Text' => 
        array (
          0 => 
          array (
            0 => 'pear/enchant',
            1 => '1.0',
            2 => NULL,
            3 => 'libenchant binder, support near all spelling tools',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/lzf',
            1 => '1.3',
            2 => NULL,
            3 => 'LZF compression.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/pdflib',
            1 => '2.0.4',
            2 => NULL,
            3 => 'Creating PDF on the fly with the PDFlib library',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/ps',
            1 => '1.3.0',
            2 => NULL,
            3 => 'An extension to create PostScript files',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/Text_Password',
            1 => '1.0',
            2 => NULL,
            3 => 'Creating passwords with PHP.',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/xdiff',
            1 => '1.2',
            2 => NULL,
            3 => 'File differences/patches.',
            4 => 
            array (
            ),
          ),
        ),
        'File System' => 
        array (
          0 => 
          array (
            0 => 'pear/File',
            1 => '1.0.3',
            2 => NULL,
            3 => 'Common file and directory routines',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/File_Find',
            1 => '0.2.0',
            2 => NULL,
            3 => 'A Class the facillitates the search of filesystems',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/File_SearchReplace',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Performs search and replace routines',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/xattr',
            1 => '1.0',
            2 => NULL,
            3 => 'Extended attributes.',
            4 => 
            array (
            ),
          ),
        ),
        'Internationalization' => 
        array (
          0 => 
          array (
            0 => 'pear/fribidi',
            1 => '1.0',
            2 => NULL,
            3 => 'Implementation of the Unicode BiDi algorithm',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Translation',
            1 => '1.2.6pl1',
            2 => NULL,
            3 => 'Class for creating multilingual websites.',
            4 => 
            array (
            ),
          ),
        ),
        'Processing' => 
        array (
          0 => 
          array (
            0 => 'pear/FSM',
            1 => '1.2.1',
            2 => NULL,
            3 => 'Finite State Machine',
            4 => 
            array (
            ),
          ),
        ),
        'HTML' => 
        array (
          0 => 
          array (
            0 => 'pear/HTML_BBCodeParser',
            1 => '1.1',
            2 => NULL,
            3 => 'This is a parser to replace UBB style tags with their html equivalents.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/HTML_Common',
            1 => '1.2.1',
            2 => NULL,
            3 => 'PEAR::HTML_Common is a base class for other HTML classes.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/HTML_Crypt',
            1 => '1.2.2',
            2 => NULL,
            3 => 'Encrypts text which is later decoded using javascript on the client side',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/HTML_CSS',
            1 => '0.2.0',
            2 => NULL,
            3 => 'HTML_CSS is a class for generating CSS declarations.',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/HTML_Form',
            1 => '1.1.0',
            2 => NULL,
            3 => 'Simple HTML form package',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/HTML_Javascript',
            1 => '1.1.0',
            2 => NULL,
            3 => 'Provides an interface for creating simple JS scripts.',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/HTML_Menu',
            1 => '2.1.1',
            2 => NULL,
            3 => 'Generates HTML menus from multidimensional hashes.',
            4 => 
            array (
            ),
          ),
          7 => 
          array (
            0 => 'pear/HTML_Progress',
            1 => '1.2.0',
            2 => NULL,
            3 => 'How to include a loading bar in your XHTML documents quickly and easily.',
            4 => 
            array (
            ),
          ),
          8 => 
          array (
            0 => 'pear/HTML_QuickForm',
            1 => '3.2.4pl1',
            2 => NULL,
            3 => 'The PEAR::HTML_QuickForm package provides methods for creating, validating, processing HTML forms.',
            4 => 
            array (
            ),
          ),
          9 => 
          array (
            0 => 'pear/HTML_QuickForm_Controller',
            1 => '1.0.3',
            2 => NULL,
            3 => 'The add-on to HTML_QuickForm package that allows building of multipage forms',
            4 => 
            array (
            ),
          ),
          10 => 
          array (
            0 => 'pear/HTML_Select_Common',
            1 => '1.1',
            2 => NULL,
            3 => 'Some small classes to handle common &lt;select&gt; lists',
            4 => 
            array (
            ),
          ),
          11 => 
          array (
            0 => 'pear/HTML_Table',
            1 => '1.5',
            2 => NULL,
            3 => 'PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and efficient.',
            4 => 
            array (
            ),
          ),
          12 => 
          array (
            0 => 'pear/HTML_Table_Matrix',
            1 => '1.0.6',
            2 => NULL,
            3 => 'Autofill a table with data',
            4 => 
            array (
            ),
          ),
          13 => 
          array (
            0 => 'pear/HTML_Template_Flexy',
            1 => '1.1.0',
            2 => NULL,
            3 => 'An extremely powerful Tokenizer driven Template engine',
            4 => 
            array (
            ),
          ),
          14 => 
          array (
            0 => 'pear/HTML_Template_IT',
            1 => '1.1',
            2 => NULL,
            3 => 'Integrated Templates',
            4 => 
            array (
            ),
          ),
          15 => 
          array (
            0 => 'pear/HTML_Template_PHPLIB',
            1 => '1.3.1',
            2 => NULL,
            3 => 'preg_* based template system.',
            4 => 
            array (
            ),
          ),
          16 => 
          array (
            0 => 'pear/HTML_Template_Sigma',
            1 => '1.1.2',
            2 => NULL,
            3 => 'An implementation of Integrated Templates API with template \\\'compilation\\\' added',
            4 => 
            array (
            ),
          ),
          17 => 
          array (
            0 => 'pear/HTML_Template_Xipe',
            1 => '1.7.6',
            2 => NULL,
            3 => 'A simple, fast and powerful template engine.',
            4 => 
            array (
            ),
          ),
          18 => 
          array (
            0 => 'pear/HTML_TreeMenu',
            1 => '1.1.9',
            2 => NULL,
            3 => 'Provides an api to create a HTML tree',
            4 => 
            array (
            ),
          ),
          19 => 
          array (
            0 => 'pear/Pager',
            1 => '2.2.4',
            2 => NULL,
            3 => 'Data paging class',
            4 => 
            array (
            ),
          ),
          20 => 
          array (
            0 => 'pear/Pager_Sliding',
            1 => '1.6',
            2 => NULL,
            3 => 'Sliding Window Pager.',
            4 => 
            array (
            ),
          ),
          21 => 
          array (
            0 => 'pear/tidy',
            1 => '1.1',
            2 => NULL,
            3 => 'Tidy HTML Repairing and Parsing',
            4 => 
            array (
            ),
          ),
        ),
        'HTTP' => 
        array (
          0 => 
          array (
            0 => 'pear/HTTP',
            1 => '1.3.3',
            2 => NULL,
            3 => 'Miscellaneous HTTP utilities',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/HTTP_Client',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Easy way to perform multiple HTTP requests and process their results',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/HTTP_Header',
            1 => '1.1.1',
            2 => NULL,
            3 => 'OO interface to modify and handle HTTP headers and status codes.',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/HTTP_Request',
            1 => '1.2.3',
            2 => NULL,
            3 => 'Provides an easy way to perform HTTP requests',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/HTTP_Upload',
            1 => '0.9.1',
            2 => NULL,
            3 => 'Easy and secure managment of files submitted via HTML Forms',
            4 => 
            array (
            ),
          ),
        ),
        'Images' => 
        array (
          0 => 
          array (
            0 => 'pear/Image_Barcode',
            1 => '0.5',
            2 => NULL,
            3 => 'Barcode generation',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Image_Color',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Manage and handles color data and conversions.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Image_GIS',
            1 => '1.1.1',
            2 => NULL,
            3 => 'Visualization of GIS data.',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Image_GraphViz',
            1 => '1.0.3',
            2 => NULL,
            3 => 'Interface to AT&T\\\'s GraphViz tools',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/Image_IPTC',
            1 => '1.0.2',
            2 => NULL,
            3 => 'Extract, modify, and save IPTC data',
            4 => 
            array (
            ),
          ),
        ),
        'Logging' => 
        array (
          0 => 
          array (
            0 => 'pear/Log',
            1 => '1.8.7',
            2 => NULL,
            3 => 'Logging utilities',
            4 => 
            array (
            ),
          ),
        ),
        'Mail' => 
        array (
          0 => 
          array (
            0 => 'pear/Mail',
            1 => '1.1.4',
            2 => NULL,
            3 => 'Class that provides multiple interfaces for sending emails',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/mailparse',
            1 => '2.0b',
            2 => NULL,
            3 => 'Email message manipulation',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Mail_Mime',
            1 => '1.2.1',
            2 => NULL,
            3 => 'Provides classes to create and decode mime messages.',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Mail_Queue',
            1 => '1.1.3',
            2 => NULL,
            3 => 'Class for put mails in queue and send them later in background.',
            4 => 
            array (
            ),
          ),
        ),
        'Math' => 
        array (
          0 => 
          array (
            0 => 'pear/Math_Basex',
            1 => '0.3',
            2 => NULL,
            3 => 'Simple class for converting base set of numbers with a customizable character base set.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Math_Fibonacci',
            1 => '0.8',
            2 => NULL,
            3 => 'Package to calculate and manipulate Fibonacci numbers',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/Math_Integer',
            1 => '0.8',
            2 => NULL,
            3 => 'Package to represent and manipulate integers',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/Math_Matrix',
            1 => '0.8.0',
            2 => NULL,
            3 => 'Class to represent matrices and matrix operations',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/Math_RPN',
            1 => '1.1',
            2 => NULL,
            3 => 'Reverse Polish Notation.',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/Math_Stats',
            1 => '0.8.5',
            2 => NULL,
            3 => 'Classes to calculate statistical parameters',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/Math_TrigOp',
            1 => '1.0',
            2 => NULL,
            3 => 'Supplementary trigonometric functions',
            4 => 
            array (
            ),
          ),
        ),
        'Numbers' => 
        array (
          0 => 
          array (
            0 => 'pear/Numbers_Roman',
            1 => '0.2.0',
            2 => NULL,
            3 => 'Provides methods for converting to and from Roman Numerals.',
            4 => 
            array (
            ),
          ),
        ),
        'PEAR' => 
        array (
          0 => 
          array (
            0 => 'pear/PEAR',
            1 => '1.3.3.1',
            2 => NULL,
            3 => 'PEAR Base System',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/PEAR_Info',
            1 => '1.5.2',
            2 => NULL,
            3 => 'Show Information about your PEAR install and its packages',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/PEAR_PackageFileManager',
            1 => '1.2.1',
            2 => NULL,
            3 => 'PEAR_PackageFileManager takes an existing package.xml file and updates it with a new filelist and changelog',
            4 => 
            array (
            ),
          ),
        ),
        'Testing' => 
        array (
          0 => 
          array (
            0 => 'pear/PHPUnit',
            1 => '1.1.1',
            2 => NULL,
            3 => 'Regression testing framework for unit tests.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/PHPUnit2',
            1 => '2.1.4',
            2 => NULL,
            3 => 'Regression testing framework for unit tests.',
            4 => 
            array (
            ),
          ),
        ),
        'Science' => 
        array (
          0 => 
          array (
            0 => 'pear/Science_Chemistry',
            1 => '1.1.0',
            2 => NULL,
            3 => 'Classes to manipulate chemical objects: atoms, molecules, etc.',
            4 => 
            array (
            ),
          ),
        ),
        'Web Services' => 
        array (
          0 => 
          array (
            0 => 'pear/Services_Weather',
            1 => '1.3.1',
            2 => NULL,
            3 => 'This class acts as an interface to various online weather-services.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/XML_RPC',
            1 => '1.1.0',
            2 => NULL,
            3 => 'PHP implementation of the XML-RPC protocol',
            4 => 
            array (
            ),
          ),
        ),
        'Streams' => 
        array (
          0 => 
          array (
            0 => 'pear/Stream_SHM',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Shared Memory Stream',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Stream_Var',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Allows stream based access to any variable.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/zlib_filter',
            1 => '1.1',
            2 => NULL,
            3 => 'zlib filter implementation backport for PHP 5.0',
            4 => 
            array (
            ),
          ),
        ),
        'Structures' => 
        array (
          0 => 
          array (
            0 => 'pear/Structures_Graph',
            1 => '1.0.1',
            2 => NULL,
            3 => 'Graph datastructure manipulation library',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/Text_Statistics',
            1 => '1.0',
            2 => NULL,
            3 => 'Compute readability indexes for documents.',
            4 => 
            array (
            ),
          ),
        ),
        'System' => 
        array (
          0 => 
          array (
            0 => 'pear/System_Mount',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Mount and unmount devices in fstab',
            4 => 
            array (
            ),
          ),
        ),
        'XML' => 
        array (
          0 => 
          array (
            0 => 'pear/xmlReader',
            1 => '1.0',
            2 => NULL,
            3 => 'Provides fast, non-cached, forward-only access to XML data.',
            4 => 
            array (
            ),
          ),
          1 => 
          array (
            0 => 'pear/XML_Beautifier',
            1 => '1.1',
            2 => NULL,
            3 => 'Class to format XML documents.',
            4 => 
            array (
            ),
          ),
          2 => 
          array (
            0 => 'pear/XML_CSSML',
            1 => '1.1',
            2 => NULL,
            3 => 'The PEAR::XML_CSSML package provides methods for creating cascading style sheets (CSS) from an XML standard called CSSML.',
            4 => 
            array (
            ),
          ),
          3 => 
          array (
            0 => 'pear/XML_fo2pdf',
            1 => '0.98',
            2 => NULL,
            3 => 'Converts a xsl-fo file to pdf/ps/pcl/text/etc with the help of apache-fop',
            4 => 
            array (
            ),
          ),
          4 => 
          array (
            0 => 'pear/XML_HTMLSax',
            1 => '2.1.2',
            2 => NULL,
            3 => 'A SAX parser for HTML and other badly formed XML documents',
            4 => 
            array (
            ),
          ),
          5 => 
          array (
            0 => 'pear/XML_image2svg',
            1 => '0.1',
            2 => NULL,
            3 => 'Image to SVG conversion',
            4 => 
            array (
            ),
          ),
          6 => 
          array (
            0 => 'pear/XML_NITF',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Parse NITF documents.',
            4 => 
            array (
            ),
          ),
          7 => 
          array (
            0 => 'pear/XML_Parser',
            1 => '1.2.1',
            2 => NULL,
            3 => 'XML parsing class based on PHP\\\'s bundled expat',
            4 => 
            array (
            ),
          ),
          8 => 
          array (
            0 => 'pear/XML_RSS',
            1 => '0.9.2',
            2 => NULL,
            3 => 'RSS parser',
            4 => 
            array (
            ),
          ),
          9 => 
          array (
            0 => 'pear/XML_SVG',
            1 => '0.0.3',
            2 => NULL,
            3 => 'XML_SVG API',
            4 => 
            array (
            ),
          ),
          10 => 
          array (
            0 => 'pear/XML_Transformer',
            1 => '1.1.0',
            2 => NULL,
            3 => 'XML Transformations in PHP',
            4 => 
            array (
            ),
          ),
          11 => 
          array (
            0 => 'pear/XML_Tree',
            1 => '1.1',
            2 => NULL,
            3 => 'Represent XML data in a tree structure',
            4 => 
            array (
            ),
          ),
          12 => 
          array (
            0 => 'pear/XML_Util',
            1 => '1.1.0',
            2 => NULL,
            3 => 'XML utility class.',
            4 => 
            array (
            ),
          ),
          13 => 
          array (
            0 => 'pear/XML_Wddx',
            1 => '1.0.0',
            2 => NULL,
            3 => 'Wddx pretty serializer and deserializer',
            4 => 
            array (
            ),
          ),
        ),
        'Local' => 
        array (
          0 => 
          array (
            0 => 'pear/Archive_Zip',
            1 => '',
            2 => '1.0.0',
            3 => 'foo',
            4 => 
            array (
              array (
                'type' => 'php',
                'rel' => 'ge',
                'version' => '4.0.0',
              ),
            ),
          ),
        ),
      ),
    ),
    'cmd' => 'list-all',
  ),
), $fakelog->getLog(), 'pear log');
$e = $command->run('list-all', array('channel' => 'smoog'), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'All packages [Channel smoog]:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Latest',
        2 => 'Local',
      ),
      'channel' => 'smoog',
      'data' => 
      array (
        'Caching' => 
        array (
          0 => 
          array (
            0 => 'smoog/APC',
            1 => '2.0.4',
            2 => NULL,
            3 => 'Alternative PHP Cache',
            4 => 
            array (
            ),
          ),
        ),
      ),
    ),
    'cmd' => 'list-all',
  ),
), $fakelog->getLog(), 'smoog log');
$phpunit->assertNoErrors('smoog');
$e = $command->run('list-all', array('channel' => 'empty'), array());
$phpunit->assertEquals(array (
  0 => 
  array (
    'info' => 
    array (
      'caption' => 'All packages [Channel empty]:',
      'border' => true,
      'headline' => 
      array (
        0 => 'Package',
        1 => 'Latest',
        2 => 'Local',
      ),
      'channel' => 'empty',
    ),
    'cmd' => 'list-all',
  ),
), $fakelog->getLog(), 'empty log');
$phpunit->assertNoErrors('empty');
echo 'tests done';
?>
--CLEAN--
<?php
require_once dirname(dirname(__FILE__)) . '/teardown.php.inc';
?>
--EXPECT--
tests done
