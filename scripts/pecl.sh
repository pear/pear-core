#!/bin/sh

# first find which PHP binary to use
if test "x$PHP_PEAR_PHP_BIN" != "x"; then
  PHP="$PHP_PEAR_PHP_BIN"
else
  if test "@php_bin@" = '@'php_bin'@'; then
    PHP=php
  else
    PHP="@php_bin@"
  fi
fi

# then look for the right pear include dir
if test "x$PHP_PEAR_INSTALL_DIR" != "x"; then
  INCDIR=$PHP_PEAR_INSTALL_DIR
  INCARG="-d include_path=$PHP_PEAR_INSTALL_DIR"
else
  if test "@php_dir@" = '@'php_dir'@'; then
    INCDIR=`dirname $0`
    INCARG=""
  else
    INCDIR="@php_dir@"
    INCARG="-d include_path=@php_dir@"
  fi
fi

# Find XML shared extension
if test "x$($PHP -r 'print_r(extension_loaded("xml"));')" != "x"; then
  if test "x$($PHP -n -r 'print_r(extension_loaded("xml"));')" = "x"; then
    XMLFLAG="-d extension_dir="`$PHP -i | grep ^extension_dir | sed 's/.*=> //')`" -d extension=xml.so"
  fi
fi

exec $PHP -C -n -q $INCARG -d date.timezone=UTC -d output_buffering=1 -d variables_order=EGPCS -d safe_mode=0 -d register_argc_argv="On" $XMLFLAG $INCDIR/peclcmd.php "$@"
