--TEST--
System commands tests
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip ';
}
if (!($fp = @fsockopen('pear.php.net', 80))) {
    echo 'skip internet is down';
}
@fclose($fp);
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once 'System.php';

$sep = DIRECTORY_SEPARATOR;
$ereg_sep = $sep;
if (OS_WINDOWS) {
    $ereg_sep .= $sep;
}
/*******************
        mkDir
********************/
echo "Testing: MkDir\n";

// Single directory creation
System::mkDir('singledir');
if( !is_dir('singledir') ){
    print "System::mkDir('singledir'); failed\n";
}
System::rm('singledir');

// Multiple directory creation
System::mkDir('dir1 dir2 dir3');
if (!@is_dir('dir1') || !@is_dir('dir2') || !@is_dir('dir3')) {
    print "System::mkDir('dir1 dir2 dir3'); failed\n";
}

// Parent creation without "-p" fail
if (@System::mkDir("dir4{$sep}dir3")) {
    print "System::mkDir(\"dir4{$sep}dir3\") did not failed\n";
}

// Create a directory which is a file already fail
touch('file4');
$res = @System::mkDir('file4 dir5');
if ($res) {
    print "System::mkDir('file4 dir5') did not failed\n";
}
if (!@is_dir('dir5')) {
    print "System::mkDir('file4 dir5') failed\n";
}

// Parent directory creation
System::mkDir("-p dir2{$sep}dir21 dir6{$sep}dir61{$sep}dir611");
if (!@is_dir("dir2{$sep}dir21") || !@is_dir("dir6{$sep}dir61{$sep}dir611")) {
    print "System::mkDir(\"-p dir2{$sep}dir21 dir6{$sep}dir61{$sep}dir611\")); failed\n";
}

/*******************
        mkTemp
********************/
echo "Testing: mkTemp\n";

// Create a temporal file with "tst" as filename prefix
$tmpfile = System::mkTemp('tst');
$tmpenv = str_replace($sep, $ereg_sep, System::tmpDir());
if (!@is_file($tmpfile) || !ereg("^$tmpenv{$ereg_sep}tst", $tmpfile)) {
    print "System::mkTemp('tst') failed\n";
    var_dump(is_file($tmpfile), $tmpfile, "^$tmpenv{$ereg_sep}tst", !ereg("^$tmpenv{$ereg_sep}tst", $tmpfile));
}

// Create a temporal dir in "dir1" with default prefix "tmp"
$tmpdir  = System::mkTemp('-d -t dir1');
if (!@is_dir($tmpdir) || (!OS_WINDOWS && !ereg("^dir1{$ereg_sep}tmp", $tmpdir))) {
    print "System::mkTemp('-d -t dir1') failed\n";
}

/*******************
        rm
********************/
echo "Testing: rm\n";

if (OS_WINDOWS) {
    mkdir('dir1\\oops');
}

// Try to delete a dir without "-r" option
if (@System::rm('dir1')) {
    print "System::rm('dir1') did not fail\n";
}

// Multiple and recursive delete
$del = "dir1 dir2 dir3 file4 dir5 dir6";
if (!@System::rm("-r $del")) {
    print "System::rm(\"-r $del\") failed\n";
}

/*******************
        which
********************/
echo "Testing: which\n";

if (OS_UNIX) {
    $ls = trim(`which ls`);
    if (System::which('ls') != $ls) {
        print "System::which('ls') failed\n";
    }
    if (System::which($ls) != $ls) {
        print "System::which('$ls') failed\n";
    }
} elseif (OS_WINDOWS) {
    $sysroot = getenv('SystemRoot') . '\\system32\\';
    if (strcasecmp(System::which('cmd'), $sysroot . 'cmd.exe') != 0) {
        print "System::which('cmd') failed\n";
    }
    if (strcasecmp(System::which('cmd.exe'), $sysroot . 'cmd.exe') != 0) {
        print "System::which('cmd.exe') failed\n";
    }
    if (strcasecmp(System::which($sysroot . 'cmd.exe'),
            $sysroot . 'cmd.exe') != 0) {
        print 'System::which(' . $sysroot . "cmd.exe') failed\n";
    }
    if (strcasecmp(System::which($sysroot . 'cmd'),
            $sysroot . 'cmd.exe') != 0) {
        print 'System::which(' . $sysroot . "cmd') failed\n";
    }
}
if (System::which('i_am_not_a_command')) {
    print "System::which('i_am_not_a_command') did not failed\n";
}
// Missing tests for safe mode constraint...

 /*******************
         cat
 ********************/
echo "Testing: cat\n";

if (!function_exists('file_put_contents')) {
    function file_put_contents($file, $text) {
        $fd = fopen($file, 'w');
        fputs($fd, $text);
        fclose($fd);
    }
}
$catfile = System::mktemp('tst');

// Create temp files
$tmpfile = array();
$totalfiles = 3;
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    $tmpfile[] = System::mktemp('tst');
    file_put_contents($tmpfile[$i], 'FILE ' . $i);
}
// Concat in new file
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = '';
    $expected = '';
    for ($j = $i; $j > 0; --$j) {
        $cat .= $tmpfile[$j] . ' ';
        $expected .= 'FILE ' . $j;
    }
    $cat .= '> ' . $catfile;
    System::cat($cat);
    if (file_get_contents($catfile) != $expected) {
        print "System::cat('$cat') failed\n";
    }
}

// Concat append to file
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = '';
    for ($j = $i; $j > 0; --$j) {
        $cat .= $tmpfile[$j] . ' ';
        $expected .= 'FILE ' . $j;
    }
    $cat .= '>> ' . $catfile;
    System::cat($cat);
    if (file_get_contents($catfile) != $expected) {
        print "System::cat('$cat') failed\n";
    }
}

// Concat to string
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = '';
    $expected = '';
    for ($j = $i; $j > 0; --$j) {
        $cat .= $tmpfile[$j] . ' ';
        $expected .= 'FILE ' . $j;
    }
    if (System::cat($cat) != $expected) {
        print "System::cat('$cat') failed\n";
    }
}

// Concat by array to string
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = array();
    $expected = '';
    for ($j = $i; $j > 0; --$j) {
        $cat[] = $tmpfile[$j];
        $expected .= 'FILE ' . $j;
    }
    if (System::cat($cat) != $expected) {
        print "System::cat(Array) failed\n";
    }
}
// Concat by array in new file
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = array();
    $expected = '';
    for ($j = $i; $j > 0; --$j) {
        $cat[] = $tmpfile[$j];
        $expected .= 'FILE ' . $j;
    }
    $cat[] = '>';
    $cat[] = $catfile;
    System::cat($cat);
    if (file_get_contents($catfile) != $expected) {
        print "System::cat(Array > $catfile) failed\n";
    }
}

// Concat by array append to file
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = array();
    for ($j = $i; $j > 0; --$j) {
        $cat[] = $tmpfile[$j];
        $expected .= 'FILE ' . $j;
    }
    $cat[] = '>>';
    $cat[] = $catfile;
    System::cat($cat);
    if (file_get_contents($catfile) != $expected) {
        print "System::cat(Array >> $catfile) failed\n";
    }
}

// Concat from url wrapper
$cat = 'http://www.php.net/ http://pear.php.net/ > ' . $catfile;
if (!System::cat($cat)) {
    print "System::cat('$cat') failed\n";
}
/*
// Concat to files with space in names
$catfile = System::mktemp('tst') . ' space in filename';

// Create temp files
$tmpfile = array();
$totalfiles = 3;
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    $tmpfile[$i] = System::mktemp('tst') . ' space in filename';
    file_put_contents($tmpfile[$i], 'FILE ' . $i);
}

// Concat in new file
for ($i = $totalfiles; $i > 0; --$i) {
    $cat = '';
    $expected = '';
    for ($j = $i; $j > 0; --$j) {
        $cat .= '"' . $tmpfile[$j] . '" ';
        $expected .= 'FILE ' . $j;
    }
    $cat .= ' > "' . $catfile . '"';
    System::cat($cat);
    if (file_get_contents($catfile) != $expected) {
        print "System::cat('$cat') failed\n";
    }
}
*/
// Clean up
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    unlink($tmpfile[$i]);
}
unlink($catfile);

print "end\n";
?>
--EXPECT--
Testing: MkDir
Testing: mkTemp
Testing: rm
Testing: which
Testing: cat
end
