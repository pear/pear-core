<?php
/**
 * PEAR_Command_Package (package, package-validate, cvsdiff, cvstag, run-tests, package-dependencies,
 * sign, makerpm, convert commands)
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 0.1
 */

/**
 * base class
 */
require_once 'PEAR/Command/Common.php';

/**
 * PEAR commands for login/logout
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Martin Jansen <mj@php.net>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since Release 0.1
 */

class PEAR_Command_Package extends PEAR_Command_Common
{
    // {{{ properties

    var $commands = array(
        'package' => array(
            'summary' => 'Build Package',
            'function' => 'doPackage',
            'shortcut' => 'p',
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
Creates a PEAR package from its description file (usually called
package.xml).  If a second packagefile is passed in, then
the packager will check to make sure that one is a package.xml
version 1.0, and the other is a package.xml version 2.0.  The
package.xml version 1.0 will be saved as "package.xml" in the archive,
and the other as "package2.xml" in the archive"
'
            ),
        'package-validate' => array(
            'summary' => 'Validate Package Consistency',
            'function' => 'doPackageValidate',
            'shortcut' => 'pv',
            'options' => array(),
            'doc' => '
',
            ),
        'cvsdiff' => array(
            'summary' => 'Run a "cvs diff" for all files in a package',
            'function' => 'doCvsDiff',
            'shortcut' => 'cd',
            'options' => array(
                'quiet' => array(
                    'shortopt' => 'q',
                    'doc' => 'Be quiet',
                    ),
                'reallyquiet' => array(
                    'shortopt' => 'Q',
                    'doc' => 'Be really quiet',
                    ),
                'date' => array(
                    'shortopt' => 'D',
                    'doc' => 'Diff against revision of DATE',
                    'arg' => 'DATE',
                    ),
                'release' => array(
                    'shortopt' => 'R',
                    'doc' => 'Diff against tag for package release REL',
                    'arg' => 'REL',
                    ),
                'revision' => array(
                    'shortopt' => 'r',
                    'doc' => 'Diff against revision REV',
                    'arg' => 'REV',
                    ),
                'context' => array(
                    'shortopt' => 'c',
                    'doc' => 'Generate context diff',
                    ),
                'unified' => array(
                    'shortopt' => 'u',
                    'doc' => 'Generate unified diff',
                    ),
                'ignore-case' => array(
                    'shortopt' => 'i',
                    'doc' => 'Ignore case, consider upper- and lower-case letters equivalent',
                    ),
                'ignore-whitespace' => array(
                    'shortopt' => 'b',
                    'doc' => 'Ignore changes in amount of white space',
                    ),
                'ignore-blank-lines' => array(
                    'shortopt' => 'B',
                    'doc' => 'Ignore changes that insert or delete blank lines',
                    ),
                'brief' => array(
                    'doc' => 'Report only whether the files differ, no details',
                    ),
                'dry-run' => array(
                    'shortopt' => 'n',
                    'doc' => 'Don\'t do anything, just pretend',
                    ),
                ),
            'doc' => '<package.xml>
Compares all the files in a package.  Without any options, this
command will compare the current code with the last checked-in code.
Using the -r or -R option you may compare the current code with that
of a specific release.
',
            ),
        'cvstag' => array(
            'summary' => 'Set CVS Release Tag',
            'function' => 'doCvsTag',
            'shortcut' => 'ct',
            'options' => array(
                'quiet' => array(
                    'shortopt' => 'q',
                    'doc' => 'Be quiet',
                    ),
                'reallyquiet' => array(
                    'shortopt' => 'Q',
                    'doc' => 'Be really quiet',
                    ),
                'slide' => array(
                    'shortopt' => 'F',
                    'doc' => 'Move (slide) tag if it exists',
                    ),
                'delete' => array(
                    'shortopt' => 'd',
                    'doc' => 'Remove tag',
                    ),
                'dry-run' => array(
                    'shortopt' => 'n',
                    'doc' => 'Don\'t do anything, just pretend',
                    ),
                ),
            'doc' => '<package.xml>
Sets a CVS tag on all files in a package.  Use this command after you have
packaged a distribution tarball with the "package" command to tag what
revisions of what files were in that release.  If need to fix something
after running cvstag once, but before the tarball is released to the public,
use the "slide" option to move the release tag.
',
            ),
        'run-tests' => array(
            'summary' => 'Run Regression Tests',
            'function' => 'doRunTests',
            'shortcut' => 'rt',
            'options' => array(
                'recur' => array(
                    'shortopt' => 'r',
                    'doc' => 'Run tests in child directories, recursively.  4 dirs deep maximum',
                ),
                'ini' => array(
                    'shortopt' => 'i',
                    'doc' => 'actual string of settings to pass to php in format " -d setting=blah"',
                    'arg' => 'SETTINGS'
                ),
                'realtimelog' => array(
                    'shortopt' => 'l',
                    'doc' => 'Log test runs/results as they are run',
                ),
                'quiet' => array(
                    'shortopt' => 'q',
                    'doc' => 'Only display detail for failed tests',
                ),
                'simple' => array(
                    'shortopt' => 's',
                    'doc' => 'Display simple output for all tests',
                ),
                'package' => array(
                    'shortopt' => 'p',
                    'doc' => 'Installed package from which to run tests',
                    'arg' => '[CHANNEL/]PACKAGE'
                ),
            ),
            'doc' => '[testfile|dir ...]
Run regression tests with PHP\'s regression testing script (run-tests.php).',
            ),
        'package-dependencies' => array(
            'summary' => 'Show package dependencies',
            'function' => 'doPackageDependencies',
            'shortcut' => 'pd',
            'options' => array(),
            'doc' => '
List all depencies the package has.'
            ),
        'sign' => array(
            'summary' => 'Sign a package distribution file',
            'function' => 'doSign',
            'shortcut' => 'si',
            'options' => array(),
            'doc' => '<package-file>
Signs a package distribution (.tar or .tgz) file with GnuPG.',
            ),
        'makerpm' => array(
            'summary' => 'Builds an RPM spec file from a PEAR package',
            'function' => 'doMakeRPM',
            'shortcut' => 'rpm',
            'options' => array(
                'spec-template' => array(
                    'shortopt' => 't',
                    'arg' => 'FILE',
                    'doc' => 'Use FILE as RPM spec file template'
                    ),
                'rpm-pkgname' => array(
                    'shortopt' => 'p',
                    'arg' => 'FORMAT',
                    'doc' => 'Use FORMAT as format string for RPM package name, %s is replaced
by the PEAR package name, defaults to "PEAR::%s".',
                    ),
                ),
            'doc' => '<package-file>

Creates an RPM .spec file for wrapping a PEAR package inside an RPM
package.  Intended to be used from the SPECS directory, with the PEAR
package tarball in the SOURCES directory:

$ pear makerpm ../SOURCES/Net_Socket-1.0.tgz
Wrote RPM spec file PEAR::Net_Geo-1.0.spec
$ rpm -bb PEAR::Net_Socket-1.0.spec
...
Wrote: /usr/src/redhat/RPMS/i386/PEAR::Net_Socket-1.0-1.i386.rpm
',
            ),
        'convert' => array(
            'summary' => 'Convert a package.xml 1.0 to package.xml 2.0 format',
            'function' => 'doConvert',
            'shortcut' => 'c2',
            'options' => array(
                'flat' => array(
                    'shortopt' => 'f',
                    'doc' => 'do not beautify the filelist.',
                    ),
                ),
            'doc' => '[descfile] [descfile2]
Converts a package.xml in 1.0 format into a package.xml
in 2.0 format.  The new file will be named package2.xml by default,
and package.xml will be used as the old file by default.
This is not the most intelligent conversion, and should only be
used for automated conversion or learning the format.
'
            ),
        );

    var $output;

    // }}}
    // {{{ constructor

    /**
     * PEAR_Command_Package constructor.
     *
     * @access public
     */
    function PEAR_Command_Package(&$ui, &$config)
    {
        parent::PEAR_Command_Common($ui, $config);
    }

    // }}}

    // {{{ _displayValidationResults()

    function _displayValidationResults($err, $warn, $strict = false)
    {
        foreach ($err as $e) {
            $this->output .= "Error: $e\n";
        }
        foreach ($warn as $w) {
            $this->output .= "Warning: $w\n";
        }
        $this->output .= sprintf('Validation: %d error(s), %d warning(s)'."\n",
                                       sizeof($err), sizeof($warn));
        if ($strict && sizeof($err) > 0) {
            $this->output .= "Fix these errors and try again.";
            return false;
        }
        return true;
    }

    // }}}
    function &getPackager()
    {
        if (!class_exists('PEAR_Packager')) {
            require_once 'PEAR/Packager.php';
        }
        $a = &new PEAR_Packager;
        return $a;
    }

    function getPackageFile($config, $debug = false, $tmpdir = null)
    {
        if (!class_exists('PEAR_Common')) {
            require_once 'PEAR/Common.php';
        }
        if (!class_exists('PEAR/PackageFile.php')) {
            require_once 'PEAR/PackageFile.php';
        }
        $a = &new PEAR_PackageFile($config, $debug, $tmpdir);
        $common = new PEAR_Common;
        $common->ui = $this->ui;
        $a->setLogger($common);
        return $a;
    }
    // {{{ doPackage()

    function doPackage($command, $options, $params)
    {
        $this->output = '';
        $pkginfofile = isset($params[0]) ? $params[0] : 'package.xml';
        $pkg2 = isset($params[1]) ? $params[1] : null;
        if (!$pkg2 && !isset($params[0])) {
            if (@file_exists('package2.xml')) {
                $pkg2 = 'package2.xml';
            }
        }
        $packager = &$this->getPackager();
        $reg = &$this->config->getRegistry();
        $dir = dirname($pkginfofile);
        $compress = empty($options['nocompress']) ? true : false;
        $result = $packager->package($pkginfofile, $compress, $pkg2);
        if (PEAR::isError($result)) {
            return $this->raiseError($result);
        }
        // Don't want output, only the package file name just created
        if (isset($options['showname'])) {
            $this->output = $result;
        }
        if ($this->output) {
            $this->ui->outputData($this->output, $command);
        }
        return true;
    }

    // }}}
    // {{{ doPackageValidate()

    function doPackageValidate($command, $options, $params)
    {
        $this->output = '';
        if (sizeof($params) < 1) {
            $params[0] = "package.xml";
        }
        $obj = &$this->getPackageFile($this->config, $this->_debug);
        $obj->rawReturn();
        PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $info = $obj->fromTgzFile($params[0], PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($info)) {
            $info = $obj->fromPackageFile($params[0], PEAR_VALIDATE_NORMAL);
        } else {
            $archive = $info->getArchiveFile();
            $tar = &new Archive_Tar($archive);
            $tar->extract(dirname($info->getPackageFile()));
            $info->setPackageFile(dirname($info->getPackageFile()) . DIRECTORY_SEPARATOR .
                $info->getPackage() . '-' . $info->getVersion() . DIRECTORY_SEPARATOR .
                basename($info->getPackageFile()));
        }
        PEAR::staticPopErrorHandling();
        if (PEAR::isError($info)) {
            return $this->raiseError($info);
        }
        $valid = false;
        if ($info->getPackagexmlVersion() == '2.0') {
            if ($valid = $info->validate(PEAR_VALIDATE_NORMAL)) {
                $info->flattenFileList();
                $valid = $info->validate(PEAR_VALIDATE_PACKAGING);
            }
        } else {
            $valid = $info->validate(PEAR_VALIDATE_PACKAGING);
        }
        $err = array();
        $warn = array();
        if (!$valid) {
            foreach ($info->getValidationWarnings() as $error) {
                if ($error['level'] == 'warning') {
                    $warn[] = $error['message'];
                } else {
                    $err[] = $error['message'];
                }
            }
        }
        $this->_displayValidationResults($err, $warn);
        $this->ui->outputData($this->output, $command);
        return true;
    }

    // }}}
    // {{{ doCvsTag()

    function doCvsTag($command, $options, $params)
    {
        $this->output = '';
        $_cmd = $command;
        if (sizeof($params) < 1) {
            $help = $this->getHelp($command);
            return $this->raiseError("$command: missing parameter: $help[0]");
        }
        $obj = &$this->getPackageFile($this->config, $this->_debug);
        $info = $obj->fromAnyFile($params[0], PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($info)) {
            return $this->raiseError($info);
        }
        $err = $warn = array();
        if (!$info->validate()) {
            foreach ($info->getValidationWarnings() as $error) {
                if ($error['level'] == 'warning') {
                    $warn[] = $error['message'];
                } else {
                    $err[] = $error['message'];
                }
            }
        }
        if (!$this->_displayValidationResults($err, $warn, true)) {
            $this->ui->outputData($this->output, $command);
            return $this->raiseError('CVS tag failed');
        }
        $version = $info->getVersion();
        $cvsversion = preg_replace('/[^a-z0-9]/i', '_', $version);
        $cvstag = "RELEASE_$cvsversion";
        $files = array_keys($info->getFilelist());
        $command = "cvs";
        if (isset($options['quiet'])) {
            $command .= ' -q';
        }
        if (isset($options['reallyquiet'])) {
            $command .= ' -Q';
        }
        $command .= ' tag';
        if (isset($options['slide'])) {
            $command .= ' -F';
        }
        if (isset($options['delete'])) {
            $command .= ' -d';
        }
        $command .= ' ' . $cvstag . ' ' . escapeshellarg($params[0]);
        foreach ($files as $file) {
            $command .= ' ' . escapeshellarg($file);
        }
        if ($this->config->get('verbose') > 1) {
            $this->output .= "+ $command\n";
        }
        $this->output .= "+ $command\n";
        if (empty($options['dry-run'])) {
            $fp = popen($command, "r");
            while ($line = fgets($fp, 1024)) {
                $this->output .= rtrim($line)."\n";
            }
            pclose($fp);
        }
        $this->ui->outputData($this->output, $_cmd);
        return true;
    }

    // }}}
    // {{{ doCvsDiff()

    function doCvsDiff($command, $options, $params)
    {
        $this->output = '';
        if (sizeof($params) < 1) {
            $help = $this->getHelp($command);
            return $this->raiseError("$command: missing parameter: $help[0]");
        }
        $obj = &$this->getPackageFile($this->config, $this->_debug);
        $info = $obj->fromAnyFile($params[0], PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($info)) {
            return $this->raiseError($info);
        }
        $err = $warn = array();
        if (!$info->validate()) {
            foreach ($info->getValidationWarnings() as $error) {
                if ($error['level'] == 'warning') {
                    $warn[] = $error['message'];
                } else {
                    $err[] = $error['message'];
                }
            }
        }
        if (!$this->_displayValidationResults($err, $warn, true)) {
            $this->ui->outputData($this->output, $command);
            return $this->raiseError('CVS diff failed');
        }
        $info1 = $info->getFilelist();
        $files = $info1;
        $cmd = "cvs";
        if (isset($options['quiet'])) {
            $cmd .= ' -q';
            unset($options['quiet']);
        }
        if (isset($options['reallyquiet'])) {
            $cmd .= ' -Q';
            unset($options['reallyquiet']);
        }
        if (isset($options['release'])) {
            $cvsversion = preg_replace('/[^a-z0-9]/i', '_', $options['release']);
            $cvstag = "RELEASE_$cvsversion";
            $options['revision'] = $cvstag;
            unset($options['release']);
        }
        $execute = true;
        if (isset($options['dry-run'])) {
            $execute = false;
            unset($options['dry-run']);
        }
        $cmd .= ' diff';
        // the rest of the options are passed right on to "cvs diff"
        foreach ($options as $option => $optarg) {
            $arg = @$this->commands[$command]['options'][$option]['arg'];
            $short = @$this->commands[$command]['options'][$option]['shortopt'];
            $cmd .= $short ? " -$short" : " --$option";
            if ($arg && $optarg) {
                $cmd .= ($short ? '' : '=') . escapeshellarg($optarg);
            }
        }
        foreach ($files as $file) {
            $cmd .= ' ' . escapeshellarg($file);
        }
        if ($this->config->get('verbose') > 1) {
            $this->output .= "+ $cmd\n";
        }
        if ($execute) {
            $fp = popen($cmd, "r");
            while ($line = fgets($fp, 1024)) {
                $this->output .= rtrim($line)."\n";
            }
            pclose($fp);
        }
        $this->ui->outputData($this->output, $command);
        return true;
    }

    // }}}
    // {{{ doRunTests()

    function doRunTests($command, $options, $params)
    {
        require_once 'PEAR/Common.php';
        require_once 'PEAR/RunTest.php';
        require_once "System.php";
        $log = new PEAR_Common;
        $log->ui = &$this->ui; // slightly hacky, but it will work
        $run = new PEAR_RunTest($log, $options);
        $tests = array();
        if (isset($options['recur'])) {
            $depth = 4;
        } else {
            $depth = 1;
        }
        if (!count($params)) {
            $params[] = '.';
        }
        if (isset($options['package'])) {
            $reg = &$this->config->getRegistry();
            $pname = $reg->parsePackageName($options['package'],
                $this->config->get('default_channel'));
            $package = &$reg->getPackage($pname['package'], $pname['channel']);
            $filelist = $package->getFilelist();
            foreach ($filelist as $name => $atts) {
                if ($atts['role'] != 'test') {
                    continue;
                }
                if (!preg_match('/\.phpt$/', $name)) {
                    continue;
                }
                $params[] = $atts['installed_as'];
            }
        }
        foreach ($params as $p) {
            if (is_dir($p)) {
                $dir = System::find(array($p, '-type', 'f',
                                            '-maxdepth', $depth,
                                            '-name', '*.phpt'));
                $tests = array_merge($tests, $dir);
            } else {
                if (!@file_exists($p)) {
                    if (!preg_match('/\.phpt$/', $p)) {
                        $p .= '.phpt';
                    }
                    $dir = System::find(array(dirname($p), '-type', 'f',
                                                '-maxdepth', $depth,
                                                '-name', $p));
                    $tests = array_merge($tests, $dir);
                } else {
                    $tests[] = $p;
                }
            }
        }
        $ini_settings = '';
        if (isset($options['ini'])) {
            $ini_settings .= $options['ini'];
        }
        if (isset($_ENV['TEST_PHP_INCLUDE_PATH'])) {
            $ini_settings .= " -d include_path={$_ENV['TEST_PHP_INCLUDE_PATH']}";
        }
        if ($ini_settings) {
            $this->ui->outputData('Using INI settings: "' . $ini_settings . '"');
        }
        $skipped = $passed = $failed = array();
        $this->ui->outputData('Running ' . count($tests) . ' tests', $command);
        $start = time();
        if (isset($options['realtimelog'])) {
            @unlink('run-tests.log');
        }
        foreach ($tests as $t) {
            if (isset($options['realtimelog'])) {
                $fp = @fopen('run-tests.log', 'a');
                if ($fp) {
                    fwrite($fp, "Running test $t...");
                    fclose($fp);
                }
            }
            PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
            $result = $run->run($t, $ini_settings);
            PEAR::staticPopErrorHandling();
            if (PEAR::isError($result)) {
                $this->log(0, $result->getMessage());
                continue;
            }
            if (OS_WINDOWS) {
                for($i=0;$i<2000;$i++) {
                    $i = $i; // delay - race conditions on windows
                }
            }
            if (isset($options['realtimelog'])) {
                $fp = @fopen('run-tests.log', 'a');
                if ($fp) {
                    fwrite($fp, "$result\n");
                    fclose($fp);
                }
            }
            if ($result == 'FAILED') {
            	$failed[] = $t;
            }
            if ($result == 'PASSED') {
            	$passed[] = $t;
            }
            if ($result == 'SKIPPED') {
            	$skipped[] = $t;
            }
        }
        $total = date('i:s', time() - $start);
        if (count($failed)) {
            $output = "TOTAL TIME: $total\n";
            $output .= count($passed) . " PASSED TESTS\n";
            $output .= count($skipped) . " SKIPPED TESTS\n";
    		$output .= count($failed) . " FAILED TESTS:\n";
        	foreach ($failed as $failure) {
        		$output .= $failure . "\n";
        	}
            if (isset($options['realtimelog'])) {
                $fp = @fopen('run-tests.log', 'a');
            } else {
                $fp = @fopen('run-tests.log', 'w');
            }
            if ($fp) {
                fwrite($fp, $output, strlen($output));
                fclose($fp);
                $this->ui->outputData('wrote log to "' . realpath('run-tests.log') . '"', $command);
            }
        } elseif (@file_exists('run-tests.log') && !@is_dir('run-tests.log')) {
            @unlink('run-tests.log');
        }
        $this->ui->outputData('TOTAL TIME: ' . $total);
        $this->ui->outputData(count($passed) . ' PASSED TESTS', $command);
        $this->ui->outputData(count($skipped) . ' SKIPPED TESTS', $command);
        if (count($failed)) {
    		$this->ui->outputData(count($failed) . ' FAILED TESTS:', $command);
        	foreach ($failed as $failure) {
        		$this->ui->outputData($failure, $command);
        	}
        }

        return true;
    }

    // }}}
    // {{{ doPackageDependencies()

    function doPackageDependencies($command, $options, $params)
    {
        // $params[0] -> the PEAR package to list its information
        if (sizeof($params) != 1) {
            return $this->raiseError("bad parameter(s), try \"help $command\"");
        }
        $obj = &$this->getPackageFile($this->config, $this->_debug);
        $info = $obj->fromAnyFile($params[0], PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($info)) {
            return $this->raiseError($info);
        }
        $deps = $info->getDeps();
        if (is_array($deps)) {
            if ($info->getPackagexmlVersion() == '1.0') {
                $data = array(
                    'caption' => 'Dependencies for pear/' . $info->getPackage(),
                    'border' => true,
                    'headline' => array("Required?", "Type", "Name", "Relation", "Version"),
                    );

                foreach ($deps as $d) {
                    if (isset($d['optional'])) {
                        if ($d['optional'] == 'yes') {
                            $req = 'No';
                        } else {
                            $req = 'Yes';
                        }
                    } else {
                        $req = 'Yes';
                    }
                    if (isset($this->_deps_rel_trans[$d['rel']])) {
                        $rel = $this->_deps_rel_trans[$d['rel']];
                    } else {
                        $rel = $d['rel'];
                    }

                    if (isset($this->_deps_type_trans[$d['type']])) {
                        $type = ucfirst($this->_deps_type_trans[$d['type']]);
                    } else {
                        $type = $d['type'];
                    }

                    if (isset($d['name'])) {
                        $name = $d['name'];
                    } else {
                        $name = '';
                    }

                    if (isset($d['version'])) {
                        $version = $d['version'];
                    } else {
                        $version = '';
                    }

                    $data['data'][] = array($req, $type, $name, $rel, $version);
                }
            } else { // package.xml 2.0 dependencies display
                require_once 'PEAR/Dependency2.php';
                $deps = $info->getDependencies();
                $reg = &$this->config->getRegistry();
                if (is_array($deps)) {
                    $d = new PEAR_Dependency2($this->config, array(), '');
                    $data = array(
                        'caption' => 'Dependencies for ' . $info->getPackage(),
                        'border' => true,
                        'headline' => array("Required?", "Type", "Name", 'Versioning', 'Group'),
                        );
                    foreach ($deps as $type => $subd) {
                        $req = ($type == 'required') ? 'Yes' : 'No';
                        if ($type == 'group') {
                            $group = $subd['attribs']['name'];
                        } else {
                            $group = '';
                        }
                        if (!isset($subd[0])) {
                            $subd = array($subd);
                        }
                        foreach ($subd as $groupa) {
                            foreach ($groupa as $deptype => $depinfo) {
                                if ($deptype == 'attribs') {
                                    continue;
                                }
                                if ($deptype == 'pearinstaller') {
                                    $deptype = 'pear Installer';
                                }
                                if (!isset($depinfo[0])) {
                                    $depinfo = array($depinfo);
                                }
                                foreach ($depinfo as $inf) {
                                    $name = '';
                                    if (isset($inf['channel'])) {
                                        $alias = $reg->channelAlias($inf['channel']);
                                        if (!$alias) {
                                            $alias = '(channel?) ' .$inf['channel'];
                                        }
                                        $name = $alias . '/';
                                    }
                                    if (isset($inf['name'])) {
                                        $name .= $inf['name'];
                                    } elseif (isset($inf['pattern'])) {
                                        $name .= $inf['pattern'];
                                    } else {
                                        $name .= '';
                                    }
                                    if (isset($inf['uri'])) {
                                        $name .= ' [' . $inf['uri'] .  ']';
                                    }
                                    if (isset($inf['conflicts'])) {
                                        $ver = 'conflicts';
                                    } else {
                                        $ver = $d->_getExtraString($inf);
                                    }
                                    $data['data'][] = array($req, ucfirst($deptype), $name,
                                        $ver, $group);
                                }
                            }
                        }
                    }
                }
            }

            $this->ui->outputData($data, $command);
            return true;
        }

        // Fallback
        $this->ui->outputData("This package does not have any dependencies.", $command);
    }

    // }}}
    // {{{ doSign()

    function doSign($command, $options, $params)
    {
        require_once "System.php";
        require_once "Archive/Tar.php";
        // should move most of this code into PEAR_Packager
        // so it'll be easy to implement "pear package --sign"
        if (sizeof($params) != 1) {
            return $this->raiseError("bad parameter(s), try \"help $command\"");
        }
        if (!file_exists($params[0])) {
            return $this->raiseError("file does not exist: $params[0]");
        }
        $obj = $this->getPackageFile($this->config, $this->_debug);
        $info = $obj->fromTgzFile($params[0], PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($info)) {
            return $this->raiseError($info);
        }
        $tar = new Archive_Tar($params[0]);
        $tmpdir = System::mktemp('-d pearsign');
        if (!$tar->extractList('package2.xml package.sig', $tmpdir)) {
            if (!$tar->extractList('package.xml package.sig', $tmpdir)) {
                return $this->raiseError("failed to extract tar file");
            }
        }
        if (file_exists("$tmpdir/package.sig")) {
            return $this->raiseError("package already signed");
        }
        $packagexml = 'package.xml';
        if (file_exists("$tmpdir/package2.xml")) {
            $packagexml = 'package2.xml';
        }
        @unlink("$tmpdir/package.sig");
        $input = $this->ui->userDialog($command,
                                       array('GnuPG Passphrase'),
                                       array('password'));
        $gpg = popen("gpg --batch --passphrase-fd 0 --armor --detach-sign --output $tmpdir/package.sig $tmpdir/$packagexml 2>/dev/null", "w");
        if (!$gpg) {
            return $this->raiseError("gpg command failed");
        }
        fwrite($gpg, "$input[0]\n");
        if (pclose($gpg) || !file_exists("$tmpdir/package.sig")) {
            return $this->raiseError("gpg sign failed");
        }
        $tar->addModify("$tmpdir/package.sig", '', $tmpdir);
        return true;
    }

    // }}}
    // {{{ doMakeRPM()

    /*

    (cox)

    TODO:
        - Fill the rpm dependencies in the template file.
        - Make this work for package.xml 2.0

    IDEAS:
        - Instead of mapping the role to rpm vars, perhaps it's better
          to use directly the pear cmd to install the files by itself
          in %postrun so:
          pear -d php_dir=%{_libdir}/php/pear -d test_dir=.. <package>
    */

    function doMakeRPM($command, $options, $params)
    {
        require_once "System.php";
        require_once "Archive/Tar.php";
        if (sizeof($params) != 1) {
            return $this->raiseError("bad parameter(s), try \"help $command\"");
        }
        if (!file_exists($params[0])) {
            return $this->raiseError("file does not exist: $params[0]");
        }
        $reg = &$this->config->getRegistry();
        $pkg = &$this->getPackageFile($this->config, $this->_debug);
        $pf = &$pkg->fromAnyFile($params[0], PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($pf)) {
            $u = $pf->getUserinfo();
            if (is_array($u)) {
                foreach ($u as $err) {
                    if (is_array($err)) {
                        $err = $err['message'];
                    }
                    $this->ui->outputData($err);
                }
            }
            return $this->raiseError("$params[0] is not a valid package");
        }
        $tar = &new Archive_Tar($params[0]);
        $tmpdir = System::mktemp('-d pear2rpm');
        $instroot = System::mktemp('-d pear2rpm');
        $tmp = $this->config->get('verbose');
        $this->config->set('verbose', 0);
        $installer = $this->getInstaller($this->ui);
        $info = $installer->install($params[0],
                                    array('installroot' => $instroot,
                                          'nodeps' => true));
        $pkgdir = "$info[package]-$info[version]";
        $info['rpm_xml_dir'] = '/var/lib/pear';
        $this->config->set('verbose', $tmp);
        if (!$tar->extractList('package2.xml', $tmpdir, $pkgdir)) {
            if (!$tar->extractList('package.xml', $tmpdir, $pkgdir)) {
                return $this->raiseError("failed to extract $params[0]");
            }
        }
        if (!file_exists("$tmpdir/package2.xml") && !file_exists("$tmpdir/package.xml")) {
            return $this->raiseError("no package2.xml or package.xml found in $params[0]");
        }
        if (isset($options['spec-template'])) {
            $spec_template = $options['spec-template'];
        } else {
            $spec_template = '@DATA-DIR@/PEAR/template.spec';
        }
        if (isset($options['rpm-pkgname'])) {
            $rpm_pkgname_format = $options['rpm-pkgname'];
        } else {
            $rpm_pkgname_format = "PEAR::%s";
        }

        $info['extra_headers'] = '';
        $info['doc_files'] = '';
        $info['files'] = '';
        $info['rpm_package'] = sprintf($rpm_pkgname_format, $info['package']);
        $srcfiles = 0;
        foreach ($info['filelist'] as $name => $attr) {
            if (!isset($attr['role'])) {
                continue;
            }
            $name = preg_replace('![/:\\\\]!', '/', $name);
            if ($attr['role'] == 'doc') {
                $info['doc_files'] .= " $name";
            // Map role to the rpm vars
            } else {
                $c_prefix = '%{_libdir}/php/pear';
                switch ($attr['role']) {
                    case 'php':
                        $prefix = $c_prefix;
                    break;
                    case 'ext':
                        $prefix = '%{_libdir}/php';
                    break; // XXX good place?
                    case 'src':
                        $srcfiles++;
                        $prefix = '%{_includedir}/php';
                    break; // XXX good place?
                    case 'test':
                        $prefix = "$c_prefix/tests/" . $info['package'];
                    break;
                    case 'data':
                        $prefix = "$c_prefix/data/" . $info['package'];
                    break;
                    case 'script':
                        $prefix = '%{_bindir}';
                    break;
                    default: // non-standard roles will not be installed at all
                    continue;
                }
                $name = str_replace('\\', '/', $name);
                $info['files'] .= "$prefix/$name\n";
            }
        }
        if ($srcfiles > 0) {
            require_once 'OS/Guess.php';
            $os = new OS_Guess;
            $arch = $os->getCpu();
        } else {
            $arch = 'noarch';
        }
        $cfg = array('master_server', 'php_dir', 'ext_dir', 'doc_dir',
                     'bin_dir', 'data_dir', 'test_dir');
        foreach ($cfg as $k) {
            if ($k == 'master_server') {
                $chan = $reg->getChannel($pf->getChannel());
                $info[$k] = $chan->getServer();
                continue;
            }
            $info[$k] = $this->config->get($k);
        }
        $info['arch'] = $arch;
        $fp = @fopen($spec_template, "r");
        if (!$fp) {
            return $this->raiseError("could not open RPM spec file template $spec_template: $php_errormsg");
        }
        $spec_contents = preg_replace('/@([a-z0-9_-]+)@/e', '$info["\1"]', fread($fp, filesize($spec_template)));
        fclose($fp);
        $spec_file = "$info[rpm_package]-$info[version].spec";
        $wp = fopen($spec_file, "wb");
        if (!$wp) {
            return $this->raiseError("could not write RPM spec file $spec_file: $php_errormsg");
        }
        fwrite($wp, $spec_contents);
        fclose($wp);
        $this->ui->outputData("Wrote RPM spec file $spec_file", $command);

        return true;
    }

    function doConvert($command, $options, $params)
    {
        $packagexml = isset($params[0]) ? $params[0] : 'package.xml';
        $newpackagexml = isset($params[1]) ? $params[1] : dirname($packagexml) .
            DIRECTORY_SEPARATOR . 'package2.xml';
        $pkg = &$this->getPackageFile($this->config, $this->_debug);
        PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
        $pf = $pkg->fromPackageFile($packagexml, PEAR_VALIDATE_NORMAL);
        PEAR::staticPopErrorHandling();
        if (!PEAR::isError($pf)) {
            if (is_a($pf, 'PEAR_PackageFile_v2')) {
                $this->ui->outputData($packagexml . ' is already a package.xml version 2.0');
                return true;
            }
            $gen = &$pf->getDefaultGenerator();
            $newpf = &$gen->toV2();
            $newpf->setPackagefile($newpackagexml);
            $gen = &$newpf->getDefaultGenerator();
            PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
            $state = (isset($options['flat']) ? PEAR_VALIDATE_PACKAGING : PEAR_VALIDATE_NORMAL);
            $saved = $gen->toPackageFile(dirname($newpackagexml), $state,
                basename($newpackagexml));
            PEAR::staticPopErrorHandling();
            if (PEAR::isError($saved)) {
                if (is_array($saved->getUserInfo())) {
                    foreach ($saved->getUserInfo() as $warning) {
                        $this->ui->outputData($warning['message']);
                    }
                }
                $this->ui->outputData($saved->getMessage());
                return true;
            }
            $this->ui->outputData('Wrote new version 2.0 package.xml to "' . $saved . '"');
            return true;
        } else {
            if (is_array($pf->getUserInfo())) {
                foreach ($pf->getUserInfo() as $warning) {
                    $this->ui->outputData($warning['message']);
                }
            }
            return $this->raiseError($pf);
        }
    }

    // }}}
}

?>
