#!/bin/bash

RESET_MARKERS=""

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --reset                    Reset all distribution markers"
            echo
	    exit 1
	    ;;
	--reset)
	    RESET_MARKERS="true"
	    ;;
	-*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
	*)
	    echo "$arg: unkown entry specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

SETDIST="./bin/shell/setdistribution.sh"

COMMON_BIN_FILES="bin bin/modfix.sh bin/shell bin/shell/makedoc.sh"
COMMON_DOC_FILES="doc doc/design doc/design/uml doc/design/uml/class_old.png doc/design/uml/class.png doc/design/uml/contentclass_db.png doc/design/uml/database.png doc/design/uml/ezsoap.png doc/design/uml/eztask_db.png doc/design/uml/eztask.png doc/design/uml/eztranslator.png doc/design/uml/ezxml.png database_update.sql runworkflows.php"
COMMON_SETTINGS_FILES="settings settings/site.ini settings/i18n.ini settings/layout.ini settings/template.ini settings/texttoimage.ini settings/units.ini settings/content.ini settings/siteaccess"
COMMON_DESIGN_FILES="design var.tgz"
CODEPAGES_FILES="share/codepages share/codepages/iso-8859-1 share/codepages/iso-8859-10 share/codepages/iso-8859-11 share/codepages/iso-8859-13 share/codepages/iso-8859-14 share/codepages/iso-8859-15 share/codepages/iso-8859-2 share/codepages/iso-8859-3 share/codepages/iso-8859-4 share/codepages/iso-8859-5 share/codepages/iso-8859-6 share/codepages/iso-8859-7 share/codepages/iso-8859-8 share/codepages/iso-8859-9 share/codepages/windows-1250 share/codepages/windows-1251 share/codepages/windows-1252 share/codepages/windows-1253 share/codepages/windows-1254 share/codepages/windows-1255 share/codepages/windows-1256 share/codepages/windows-1257 share/codepages/windows-1258"
COMMON_FILES="$COMMON_BIN_FILES $COMMON_DOC_FILES $COMMON_SETTINGS_FILES $COMMON_DESIGN_FILES /share $CODEPAGES_FILES index.php"

COMMON_DOC_DIRS="doc/changelogs doc/standards doc/doxygen doc/images"
COMMON_DESIGN_DIRS="design/standard design/user"
COMMON_SETTINGS_DIRS="settings/siteaccess/sdk"
COMMON_DIRS="$COMMON_DOC_DIRS $COMMON_DESIGN_DIRS $COMMON_SETTINGS_DIRS share/locale share/translations"

FULL_SETTINGS_DIRS="settings/siteaccess/admin settings/siteaccess/user"

SDK_FILES="kernel"
FULL_FILES=""

SDK_DIRS="lib kernel/sdk sdk"
FULL_DIRS="$FULL_SETTINGS_DIRS bin/openfts lib kernel sdk"

if [ -z $RESET_MARKERS ]; then
    echo "Preparing repository for distribution"

    echo "Adding files for SDK release"
    $SETDIST -q --release-sdk $COMMON_FILES $SDK_FILES
    echo "Adding files for full release"
    $SETDIST -q --release-full $COMMON_FILES $FULL_FILES

    echo "Adding dirs for SDK release"
    $SETDIST -q --release-sdk --release-all-children $COMMON_DIRS $SDK_DIRS
    echo "Adding dirs for full release"
    $SETDIST -q --release-full --release-all-children $COMMON_DIRS $FULL_DIRS
else
    $SETDIST -q --reset $COMMON_FILES $SDK_FILES $FULL_FILES $COMMON_DIRS $SDK_DIRS $FULL_DIRS
fi
