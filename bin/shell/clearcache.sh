#!/bin/sh

CONTENT_CACHEDIR="var/cache/content"
IMAGE_CACHEDIR="var/cache/texttoimage"
INI_CACHEDIR="var/cache/ini"
TEMPLATE_CACHEDIR="var/cache/template"
TRANSLATION_CACHEDIR="var/cache/translation"
EXPIRY_CACHEFILE="var/cache/expiry.php"

CLEAR_CONTENT="1"
CLEAR_IMAGE="0"
CLEAR_INI="0"
CLEAR_TEMPLATE="0"
CLEAR_TRANSLATION="0"
CLEAR_EXPIRY="0"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --clear-image              Remove image cache"
	    echo "         --clear-content            Remove content cache(default)"
	    echo "         --clear-ini                Remove ini file cache"
	    echo "         --clear-tpl                Remove template cache"
	    echo "         --clear-ts                 Remove translation cache"
	    echo "         --clear-expiry             Remove expiry cache"
	    echo "         --clear-all                Remove all above caches"
            echo
            echo "Example:"
            echo "$0 --clear-image --clear-tpl"
	    exit 1
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
	--clear-tpl)
	    CLEAR_TEMPLATE="1"
	    ;;
	--clear-ts)
	    CLEAR_TRANSLATION="1"
	    ;;
	--clear-expiry)
	    CLEAR_EXPIRY="1"
	    ;;
	--clear-all)
	    CLEAR_CONTENT="1"
	    CLEAR_IMAGE="1"
	    CLEAR_INI="1"
	    CLEAR_TEMPLATE="1"
	    CLEAR_TRANSLATION="1"
	    CLEAR_EXPIRY="1"
	    ;;
	*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

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

if [ "$CLEAR_TEMPLATE" -eq 1 ]; then
    if [ -d "$TEMPLATE_CACHEDIR" ]; then
	echo "Removing template cache files in $TEMPLATE_CACHEDIR"
	rm -rf "$TEMPLATE_CACHEDIR"
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
