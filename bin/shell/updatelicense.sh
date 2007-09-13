#!/bin/sh

function show_help
{
    echo
    echo "The script will update license and headers."
    echo
    echo "Usage: $0 [options] [SQLFILE]..."
    echo
    echo "Options: -h"
    echo "         --help                     This message"
    echo "         --license-type=<type>      What license should be used: gpl(default), pul_v1"
    echo "         --licenses-dir=<dir>       Location of licenses files"
    echo "         --target-dir=<dir>         Location of phps to update"
    #echo "         --name=<name>              Name: eZ publish"
    echo "         --revision=<rev>           Revision number: 12345"
    echo "         --version=<version>        Version: 3.7.3"
    echo
}

function parse_cl_parameters
{
    [ -z "$*" ] && show_help
    for arg in $*; do
        case $arg in
        --help|-h)
            show_help
            exit 1
            ;;
        --license-type*)
            if echo $arg | grep -e "--license-type=" >/dev/null; then
                LICENSE_TYPE=`echo $arg | sed 's/--license-type=//'`
            fi
            ;;

        --licenses-dir*)
            if echo $arg | grep -e "--licenses-dir=" >/dev/null; then
                LICENSES_DIR=`echo $arg | sed 's/--licenses-dir=//'`
            fi
            ;;

        --target-dir*)
            if echo $arg | grep -e "--target-dir=" >/dev/null; then
                DEST_DIR=`echo $arg | sed 's/--target-dir=//'`
            fi
            ;;

        --revision*)
            if echo $arg | grep -e "--revision=" >/dev/null; then
                REV=`echo $arg | sed 's/--revision=//'`
            fi
            ;;

        --version*)
            if echo $arg | grep -e "--version=" >/dev/null; then
                VERSION=`echo $arg | sed 's/--version=//'`
            fi
            ;;
        #--name*)
        #    if echo $arg | grep -e "--name=" >/dev/null; then
        #        NAME=`echo $arg | sed 's/--name=//'`
        #    fi
        #    ;;

        --*)
            if [ $? -ne 0 ]; then
                echo "$arg: unknown long option specified"
                echo
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            fi
            ;;
        -*)
            if [ $? -ne 0 ]; then
                echo "$arg: unknown option specified"
                echo
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            fi
            ;;
        *)
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            ;;
        esac;
    done
}


# Updates ezinfo/copyright.php file,
# Changes copyright and license content
function update_ezinfo_copyright()
{
    local EZINFO=$1
    local COPYRIGHT_INFO=$2
    local BEGIN_COPYRIGHT_BLOCK="## BEGIN COPYRIGHT INFO ##"
    local END_COPYRIGHT_BLOCK="## END COPYRIGHT INFO ##"
    echo '"' > $COPYRIGHT_INFO.tmp
    sed -e 's/"/\\"/; ' "$COPYRIGHT_INFO" >> "$COPYRIGHT_INFO.tmp"
    echo '";' >> $COPYRIGHT_INFO.tmp
    sed -i -e '/'"$BEGIN_COPYRIGHT_BLOCK"'/,/'"$END_COPYRIGHT_BLOCK"'/{
    /'"$END_COPYRIGHT_BLOCK"'/r '$COPYRIGHT_INFO.tmp'
    d
    }' "$EZINFO";
    rm -f  "$COPYRIGHT_INFO.tmp"
}

## main ################################################

# "Declare" all the variables used in the script.
#NAME=""
VERSION=""
REV=""
LICENSE_TYPE='gpl'
LICENSE_FILE="LICENSE"
LICENSE_NOTICE_FILE="notice.yaml"
LICENSE_NOTICE_TMP_FILE="license-notice.tmp"
BEGIN_LICENSE_BLOCK="## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##"
END_LICENSE_BLOCK="## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##"
#BEGIN_LICENSE_BLOCK="Copyright (C) 1999-"
#END_LICENSE_BLOCK="you."


# Do the work.
parse_cl_parameters $*

LICENSE_DIR="$LICENSES_DIR/$LICENSE_TYPE"

LICENSE_FILE="$LICENSE_DIR/$LICENSE_FILE"
COPYRIGHT_INFO="$LICENSE_DIR/ezcopyright.txt"
LICENSE_NOTICE_FILE="$LICENSE_DIR/$LICENSE_NOTICE_FILE"
LICENSE_NOTICE_TMP_FILE="$LICENSE_DIR/$LICENSE_NOTICE_TMP_FILE"

EZINFO_FILE="$DEST_DIR/kernel/ezinfo/about.php"
EZINFO_COPYRIGHT_FILE="$DEST_DIR/kernel/ezinfo/copyright.php"
CONTRIBUTORS_TMP_FILE='contributors.tmp'
CONTRIBUTORS_DIR="$DEST_DIR/var/storage/contributors"
THIRDSOFT_FILE="$DEST_DIR/var/storage/third_party_software.php"

# Prepare contributor`s dir
[ -d "$CONTRIBUTORS_DIR" ] && rm -rf "$CONTRIBUTORS_DIR"
mkdir -p "$CONTRIBUTORS_DIR"

RELEASE="// SOFTWARE RELEASE: $VERSION"
BUILD_REV="// BUILD VERSION: $REV"
NOTICE_TMP_FILE="$LICENSE_DIR/notice.tmp"
MAXLENGTH="35"
if [ ! -e "$LICENSE_FILE" ]; then
    echo "License file '$LICENSE_FILE' doesn't exist."
    exit 1;
fi

if [ ! -e "$LICENSE_NOTICE_FILE" ]; then
    echo "License notice file '$LICENSE_NOTICE_FILE' doesn't exist."
    exit 1;
fi

echo "LICENSE_FILE: '$LICENSE_FILE'"
echo "LICENSE_NOTICE_FILE: '$LICENSE_NOTICE_FILE'"
echo "LICENSE_NOTICE_TMP_FILE: '$LICENSE_NOTICE_TMP_FILE'"
echo "DEST_DIR: '$DEST_DIR'"
echo "REV: '$REV'"
echo "VERSION: '$VERSION'"

sed -e '/# NOTE/!s/^/\/\/ /;s/# NOTE.*/\/\/\n/;s/\$Version\$/'$VERSION'/;s/\$Rev\$/'$REV'/' "$LICENSE_NOTICE_FILE" > "$LICENSE_NOTICE_TMP_FILE"
find "$DEST_DIR" \( -name "*.js" -o -name "*.php" \) -exec sed -i -e '/'"$BEGIN_LICENSE_BLOCK"'/,/'"$END_LICENSE_BLOCK"'/{
/'"$END_LICENSE_BLOCK"'/r '$LICENSE_NOTICE_TMP_FILE'
d
}' '{}' \;
update_ezinfo_copyright "$EZINFO_COPYRIGHT_FILE" "$COPYRIGHT_INFO"

rm -f "$LICENSE_NOTICE_TMP_FILE"
cp -f "$LICENSE_FILE" "$DEST_DIR"

