#!/bin/sh

VAR_DIR="var"
VAR_SUBDIR=

CLEAR_CONTENT="1"
CLEAR_IMAGE="0"
CLEAR_INI="0"
CLEAR_TEMPLATE="0"
CLEAR_TEMPLATE_BLOCK="0"
CLEAR_TEMPLATE_OVERRIDE="0"
CLEAR_TRANSLATION="0"
CLEAR_EXPIRY="0"
CLEAR_URLALIAS="0"
CLEAR_SORTKEY="0"
CLEAR_CLASSIDENTIFIER="0"
CLEAR_RSS="0"
CLEAR_CODEPAGE="0"
CLEAR_TRANS="0"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --var-subdir=DIR           Use this subdirectory under var as root"
	    echo "         --clear-image              Remove image cache"
	    echo "         --clear-content            Remove content cache(default)"
	    echo "         --clear-ini                Remove ini file cache"
	    echo "         --clear-codepage           Remove codepages cache"
	    echo "         --clear-tpl                Remove template cache"
	    echo "         --clear-tpl-override       Remove template override cache"
	    echo "         --clear-tpl-block          Remove template-block cache"
	    echo "         --clear-ts                 Remove translation cache"
	    echo "         --clear-expiry             Remove expiry cache"
	    echo "         --clear-urlalias           Remove url alias cache"
	    echo "         --clear-sortkey            Remove sort key cache"
            echo "         --clear-classidentifiers   Remove class identifier cache"
	    echo "         --clear-rss                Remove RSS cache"
	    echo "         --clear-transformations    Remove character transformation cache"
            echo "         --clear-all                Remove all above caches"
            echo
            echo "Example:"
            echo "$0 --clear-image --clear-tpl"
	    exit 1
	    ;;
	--var-subdir*)
	    if echo $arg | grep -e "--var-subdir=" >/dev/null; then
		VAR_SUBDIR=`echo $arg | sed 's/--var-subdir=//'`
	    else
		echo "No subdir specified, ignoring"
	    fi
	    ;;
	--clear-content)
	    CLEAR_CONTENT="1"
	    ;;
	--clear-image)
	    CLEAR_IMAGE="1"
	    ;;
	--clear-ini)
	    CLEAR_INI="1"
	    ;;
	--clear-codepage)
	    CLEAR_CODEPAGE="1"
	    ;;
	--clear-tpl)
	    CLEAR_TEMPLATE="1"
	    ;;
	--clear-tpl-override)
	    CLEAR_TEMPLATE_OVERRIDE="1"
	    ;;
	--clear-tpl-block)
	    CLEAR_TEMPLATE_BLOCK="1"
	    ;;
	--clear-ts)
	    CLEAR_TRANSLATION="1"
	    ;;
	--clear-expiry)
	    CLEAR_EXPIRY="1"
	    ;;
	--clear-urlalias)
	    CLEAR_URLALIAS="1"
	    ;;
       --clear-sortkey)
            CLEAR_SORTKEY="1"
            ;;
       --clear-classidentifiers)
            CLEAR_CLASSIDENTIFIER="1"
            ;;
       --clear-rss)
	    CLEAR_RSS="1"
	    ;;
       --clear-transformations)
	    CLEAR_TRANS="1"
	    ;;

	--clear-all)
	    CLEAR_CONTENT="1"
	    CLEAR_IMAGE="1"
	    CLEAR_INI="1"
	    CLEAR_CODEPAGE="1"
	    CLEAR_TEMPLATE="1"
	    CLEAR_TEMPLATE_BLOCK="1"
	    CLEAR_TEMPLATE_OVERRIDE="1"
	    CLEAR_TRANSLATION="1"
	    CLEAR_EXPIRY="1"
	    CLEAR_URLALIAS="1"
            CLEAR_SORTKEY="1"
            CLEAR_CLASSIDENTIFIER="1"
	    CLEAR_RSS="1"
	    CLEAR_TRANS="1"
	    ;;
	*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

DIR="$VAR_DIR"
if [ ! -z "$VAR_SUBDIR" ]; then
    DIR="$DIR/$VAR_SUBDIR"
fi

SITEACCESS_DIR=""
SETTINGS_DIR="settings"

for dir in $SETTINGS_DIR/siteaccess/*; do
    if [ -d "$dir" ]; then
	dirname=`basename $dir`
	if echo $dirname | grep -e "_user$" >/dev/null; then
	    dirname=`echo $dirname | sed 's/_user//'`
	elif echo $dirname | grep -e "_admin$" >/dev/null; then
	    dirname=`echo $dirname | sed 's/_admin//'`
	fi
	SITEACCESS_DIR="$SITEACCESS_DIR $dirname"
    fi
done

VAR_DIRS="$DIR"

if [ -z "$VAR_SUBDIR" ]; then
    for siteaccess in $SITEACCESS_DIR; do
	VAR_DIRS="$VAR_DIRS $VAR_DIR/$siteaccess"
    done
fi

for DIR in $VAR_DIRS; do

    if [ -d $DIR ]; then

	CONTENT_CACHEDIR="$DIR/cache/content"
	IMAGE_CACHEDIR="$DIR/cache/texttoimage"
	INI_CACHEDIR="$DIR/cache/ini"
	CODEPAGE_CACHEDIR="$DIR/cache/codepages"
	TEMPLATE_CACHEDIR="$DIR/cache/template"
	TEMPLATE_OVERRIDE_CACHEDIR="$DIR/cache/override"
	TEMPLATE_BLOCK_CACHEDIR="$DIR/cache/template-block"
	TRANSLATION_CACHEDIR="$DIR/cache/translation"
	EXPIRY_CACHEFILE="$DIR/cache/expiry.php"
	URLALIAS_CACHEDIR="$DIR/cache/wildcard"
 	SORTKEY_CACHEFILE="$DIR/cache/sortkey_"
        CLASSIDENTIFIER_CACHEFILE="$DIR/cache/classidentifiers_"
        CLASSATTRIBUTEIDENTIFIER_CACHEFILE="$DIR/cache/classattributeidentifiers_"
	RSS_CACHEDIR="$DIR/cache/rss"
	TRANS_CACHEDIR="$DIR/cache/trans"
   

	if [ "$CLEAR_CONTENT" -eq 1 ]; then
	    if [ -d "$CONTENT_CACHEDIR" ]; then
		echo "Removing cache files in $CONTENT_CACHEDIR"
		rm -rf "$CONTENT_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_IMAGE" -eq 1 ]; then
	    if [ -d "$IMAGE_CACHEDIR" ]; then
		echo "Removing image cache files in $IMAGE_CACHEDIR"
		rm -rf "$IMAGE_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_INI" -eq 1 ]; then
	    if [ -d "$INI_CACHEDIR" ]; then
		echo "Removing ini cache files in $INI_CACHEDIR"
		rm -rf "$INI_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_CODEPAGE" -eq 1 ]; then
	    if [ -d "$CODEPAGE_CACHEDIR" ]; then
		echo "Removing codepage cache files in $CODEPAGE_CACHEDIR"
		rm -rf "$CODEPAGE_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_TEMPLATE" -eq 1 ]; then
	    if [ -d "$TEMPLATE_CACHEDIR" ]; then
		echo "Removing template cache files in $TEMPLATE_CACHEDIR"
		rm -rf "$TEMPLATE_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_TEMPLATE_OVERRIDE" -eq 1 ]; then
	    if [ -d "$TEMPLATE_OVERRIDE_CACHEDIR" ]; then
		echo "Removing template override cache files in $TEMPLATE_OVERRIDE_CACHEDIR"
		rm -rf "$TEMPLATE_OVERRIDE_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_TEMPLATE_BLOCK" -eq 1 ]; then
	    if [ -d "$TEMPLATE_BLOCK_CACHEDIR" ]; then
		echo "Removing template-block cache files in $TEMPLATE_BLOCK_CACHEDIR"
		rm -rf "$TEMPLATE_BLOCK_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_TRANSLATION" -eq 1 ]; then
	    if [ -d "$TRANSLATION_CACHEDIR" ]; then
		echo "Removing translation cache files in $TRANSLATION_CACHEDIR"
		rm -rf "$TRANSLATION_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_EXPIRY" -eq 1 ]; then
	    if [ -d "$EXPIRY_CACHEFILE" ]; then
		echo "Removing expiry cache file $EXPIRY_CACHEFILE"
		rm -rf "$EXPIRY_CACHEFILE"
	    fi
	fi

	if [ "$CLEAR_URLALIAS" -eq 1 ]; then
	    if [ -d "$URLALIAS_CACHEDIR" ]; then
		echo "Removing translation cache files in $URLALIAS_CACHEDIR"
		rm -rf "$URLALIAS_CACHEDIR"
	    fi
	fi

        if [ "$CLEAR_SORTKEY" -eq 1 ]; then
            echo "Removing sortkey cache files in $SORTKEY_CACHEFILE"
            rm -f "$SORTKEY_CACHEFILE"*.php
        fi

	if [ "$CLEAR_RSS" -eq 1 ]; then
	    if [ -d "$RSS_CACHEDIR" ]; then
		echo "Removing RSS cache files in $RSS_CACHEDIR"
		rm -rf "$RSS_CACHEDIR"
	    fi
	fi

	if [ "$CLEAR_TRANS" -eq 1 ]; then
	    if [ -d "$TRANS_CACHEDIR" ]; then
		echo "Removing transformation cache files in $TRANS_CACHEDIR"
		rm -rf "$TRANS_CACHEDIR"
	    fi
	fi

        if [ "$CLEAR_CLASSIDENTIFIER" -eq 1 ]; then
            echo "Removing class identifier cache files in $CLASSIDENTIFIER_CACHEFILE"
            rm -f "$CLASSIDENTIFIER_CACHEFILE"*.php
            echo "Removing class attribute identifier cache files in $CLASSATTRIBUTEIDENTIFIER_CACHEFILE"
            rm -f "$CLASSATTRIBUTEIDENTIFIER_CACHEFILE"*.php
        fi
    fi
done
