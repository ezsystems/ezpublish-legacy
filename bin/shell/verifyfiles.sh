#!/bin/bash

echo "Checking file consistency"
md5sum --check var/storage/filelist.md5|grep FAILED
