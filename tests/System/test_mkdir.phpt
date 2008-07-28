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
error_reporting(1803);
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



// Cleanup

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

print "end\n";
?>
--EXPECT--
Testing: MkDir
end
