--TEST--
PEAR_Packager->package() failure
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
touch($temp_path . DIRECTORY_SEPARATOR . 'bloob.xml');
$ret = $packager->package($temp_path . DIRECTORY_SEPARATOR . 'bloob.xml');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile', 'message' => 'package.xml "' .
        $temp_path . DIRECTORY_SEPARATOR . 'bloob.xml" has no package.xml <package> version'),
    array('package' => 'PEAR_Error', 'message' => 'Cannot package, errors in package file'),
), 'ret');
$phpunit->assertIsa('PEAR_Error', $ret, 'bloob.xml');
if (version_compare(phpversion(), '5.0.0', '>=')) {
    $errmsg = 'XML error: XML_ERR_DOCUMENT_END at line 1';
} else {
    $errmsg = 'XML error: no element found at line 1';
}
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => $errmsg,
    1 => true,
  ),
), $fakelog->getLog(), 'log');
// v2 with invalid
$ret = $packager->package(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'fakebar.xml');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'Invalid tag order in <package>, found <time> expected one of "lead, developer, contributor, helper, date"'),
    array('package' => 'PEAR_Error', 'message' => 'Cannot package, errors in package file'),
), 'ret');
$phpunit->assertIsa('PEAR_Error', $ret, 'fakebar.xml');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'Error: Invalid tag order in <package>, found <time> expected one of "lead, developer, contributor, helper, date"',
    1 => true,
  ),
  1 =>
  array (
    0 => 'Parsing of package.xml from file "' . dirname(__FILE__)  .
    DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'fakebar.xml" failed',
    1 => true,
  ),
), $fakelog->getLog(), 'log');
// v1 with invalid
$ret = $packager->package(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'v1.xml');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1', 'message' => 'No summary found'),
    array('package' => 'PEAR_Error', 'message' => 'Cannot package, errors in package file'),
), 'ret');
$phpunit->assertIsa('PEAR_Error', $ret, 'fakebar.xml');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'Error: No summary found',
    1 => true,
  ),
  1 =>
  array (
    0 => 'Parsing of package.xml from file "' . dirname(__FILE__)  .
    DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'v1.xml" failed',
    1 => true,
  ),
), $fakelog->getLog(), 'log');
$savedir = getcwd();
chdir($temp_path);
// v1 with invalid, package-time validation
$ret = $packager->package(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'packageinvalidv1.xml');
$ds = DIRECTORY_SEPARATOR;
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1', 'message' => 'File "' . dirname(__FILE__) . $ds . 'packagefiles' .$ds . 'unknown.php" in package.xml does not exist'),
    array('package' => 'PEAR_Error', 'message' => 'Cannot package, errors in package'),
), 'ret');
$phpunit->assertIsa('PEAR_Error', $ret, 'packageinvalidv1.xml');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'Error: File "' . dirname(__FILE__) . $ds . 'packagefiles' .$ds . 'unknown.php" in package.xml does not exist',
    1 => true,
  ),
), $fakelog->getLog(), 'log');
// v2 with invalid, package-time validation
$ret = $packager->package(dirname(__FILE__)  . DIRECTORY_SEPARATOR . 'packagefiles' .
    DIRECTORY_SEPARATOR . 'packageinvalidv2.xml');
$ds = DIRECTORY_SEPARATOR;
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'File "' . dirname(__FILE__) . $ds . 'packagefiles' .$ds . 'unknown.php" in package.xml does not exist'),
    array('package' => 'PEAR_Error', 'message' => 'Cannot package, errors in package'),
), 'ret');
$phpunit->assertIsa('PEAR_Error', $ret, 'packageinvalidv2.xml');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'Error: File "' . dirname(__FILE__) . $ds . 'packagefiles' .$ds . 'unknown.php" in package.xml does not exist',
    1 => true,
  ),
), $fakelog->getLog(), 'log');
// test warnings, v1
$ret = $packager->package(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packagefiles' . DIRECTORY_SEPARATOR .
    'validwarnv1.xml');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v1', 'message' => 'in foo.php: class "gronk" not prefixed with package name "foo"'),
), 'warning v1');
$phpunit->assertEquals('foo-1.2.0a1.tgz', $ret, 'return warning v1');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'Analyzing foo.php',
    1 => true,
  ),
  1 =>
  array (
    0 => 'Warning: in foo.php: class "gronk" not prefixed with package name "foo"',
    1 => true,
  ),
  2 =>
  array (
    0 => 'Package foo-1.2.0a1.tgz done',
    1 => true,
  ),
), $fakelog->getLog(), 'log');
// test warnings, v2
$ret = $packager->package(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'packagefiles' . DIRECTORY_SEPARATOR .
    'validwarnfakebar.xml');
$phpunit->assertErrors(array(
    array('package' => 'PEAR_PackageFile_v2', 'message' => 'in foo.php: class "gronk" not prefixed with package name "fakebar"'),
), 'warning v1');
$phpunit->assertEquals('fakebar-1.9.0.tgz', $ret, 'return warning v2');
$phpunit->assertEquals(array (
  0 => 
  array (
    0 => 'Analyzing foo.php',
    1 => true,
  ),
  1 =>
  array (
    0 => 'Warning: in foo.php: class "gronk" not prefixed with package name "fakebar"',
    1 => true,
  ),
  2 =>
  array (
    0 => 'Package fakebar-1.9.0.tgz done',
    1 => true,
  ),
), $fakelog->getLog(), 'log');
chdir($savedir);
echo 'tests done';
?>
--EXPECT--
tests done
