#!/bin/sh

ROOT=./

DOXY_BIN=$ROOT/bin/linux/doxygen
DOXY_CONF=$ROOT/doc/doxygen/Doxyfile

(cd $ROOT && $DOXY_BIN $DOXY_CONF)
