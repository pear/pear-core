#!/bin/sh
# package up PEAR
set -e

pear package package2.xml

[ -d go-pear-tarballs ] && rm -r go-pear-tarballs
mkdir go-pear-tarballs
cd go-pear-tarballs

cp ../PEAR-*.tgz .
gunzip PEAR-*.tgz
pear download -Z Archive_Tar Console_Getopt Structures_Graph XML_Util

mkdir src && cd src
for i in ../*.tar; do tar xvf $i; done
mv *\/* .
cd ../../

rm go-pear.phar install-pear-nozlib.phar
php make-gopear-phar.php
php make-installpear-nozlib-phar.php
