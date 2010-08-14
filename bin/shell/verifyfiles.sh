#!/bin/bash

echo "Checking file consistency"
md5sum --check share/filelist.md5|grep FAILED
