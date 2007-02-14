--TEST--
PEAR_RunTest improve diff of failing EXPECTF test [SHOULD FAIL]
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
echo "hi123\n";
echo "oops123\n";
echo "oops\n";
echo "hi\n";
// bug9971.diff should contain:
// -002: hi%d
// +002: oops123
// -003: oops%d
// +003: oops
?>
--EXPECTF--
hi%d
hi%d
oops%d
hi