--TEST--
System commands tests
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip ';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once 'System.php';

$sep = DIRECTORY_SEPARATOR;

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
        mktemp
********************/
echo "Testing: mktemp\n";

// Create a temporal file with "tst" as filename prefix
$tmpfile = System::mktemp('tst');
$tmpenv  = rtrim(realpath(System::tmpDir()), '\/');
if (!@is_file($tmpfile) || (0 !== strpos($tmpfile, "{$tmpenv}{$sep}tst"))) {
    print "System::mktemp('tst') failed\n";
    var_dump(is_file($tmpfile), $tmpfile, "{$tmpenv}{$sep}tst", (0 !== strpos($tmpfile, "{$tmpenv}{$sep}tst")));
}

// Create a temporal dir in "dir1" with default prefix "tmp"
$tmpdir = System::mktemp('-d -t dir1');
if (!@is_dir($tmpdir) || (false === strpos($tmpdir, "dir1{$sep}tmp"))) {
    print "System::mktemp('-d -t dir1') failed\n";
    var_dump(is_dir($tmpdir), $tmpdir, "dir1{$sep}tmp", (false === strpos($tmpdir, "dir1{$sep}tmp")));
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
        print "System::which('ls') 1 failed\n";
        var_dump($ls, System::which('ls'));
    }
    if (System::which($ls) != $ls) {
        print "System::which('$ls') 2 failed\n";
        var_dump($ls, System::which('ls'));
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
echo "Testing: cat offline\n";

if (!function_exists('file_put_contents')) {
    function file_put_contents($file, $text) {
        $fd = fopen($file, 'wb');
        fputs($fd, $text);
        fclose($fd);
    }
}
$catdir  = uniqid('foobar');
$catfile = $catdir.$sep.basename(System::mktemp("-t {$catdir} tst"));

// Create temp files
$tmpfile = array();
$totalfiles = 3;
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    $tmpfile[] = $catdir.$sep.basename(System::mktemp("-t {$catdir} tst"));
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
        print "System::cat(> '$cat') failed\n";
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
        print "System::cat(>> '$cat') failed\n";
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

// Clean up
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    unlink($tmpfile[$i]);
}
unlink($catfile);

// Concat to files with space in names
$catfile1 = $catdir.$sep.basename(System::mktemp("-t {$catdir} tst"));
$catfile  = $catfile1.' space in filename';

// Create temp files with space in names
$tmpfile  = array();
$tmpfile1 = array();
$totalfiles = 3;
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    $tmpfile1[$i] = $catdir.$sep.basename(System::mktemp("-t {$catdir} tst"));
    $tmpfile[$i]  = $tmpfile1[$i].' space in filename';
    file_put_contents($tmpfile[$i], 'FILE ' . $i);
}

// Concat by array in new file with space in names
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
        print "System::cat(Array > $catfile) with space in names failed\n";
    }
}

// Clean up
for ($i = 0; $i < $totalfiles + 1; ++$i) {
    unlink($tmpfile[$i]);
    unlink($tmpfile1[$i]);
}
unlink($catfile);
unlink($catfile1);

rmdir($catdir);
    
print "end\n";
?>
--EXPECT--
Testing: MkDir
Testing: mktemp
Testing: rm
Testing: which
Testing: cat offline
end
