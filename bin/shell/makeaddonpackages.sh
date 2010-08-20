#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/packagescommon.sh
. ./bin/shell/sqlcommon.sh

if ! which php &>/dev/null; then
    echo "No PHP executable found, please add it to the path"
    exit 1
fi

TMPDIR="/tmp/ez-$USER/packages"

[ -d $TMPDIR ] && rm -rf "$TMPDIR"
mkdir -p $TMPDIR || exit 1

PMBIN="./ezpm.php"

SITE_PACKAGES="$TMPDIR/extra.tmp"
SITE_PACKAGES_EXPORT="$TMPDIR/extra"
OUTPUT_REPOSITORY="$TMPDIR/addons"
EXPORT_PATH="packages/addons"

# Initialise several database related variables, see sqlcommon.sh
ezdist_db_init

DB_USER="root"
DB_PASSWD="none"
DB_SOCKET="none"
DB_HOST="none"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --auto-commit              Will remove the existing addons, commit it then add the new one and commit it"
	    echo "         --addon=ADDON              Create only package named ADDON"
	    echo "         --export-path=DIR          Where to export the packages, default is 'packages/addons'"
	    echo

	    # Show options for database
	    ezdist_db_show_options

            echo "Example:"
            echo "$0 --export-path=/tmp/export"
	    exit 1
	    ;;

	-q)
	    QUIET="-q"
	    ;;
	--addon=*)
	    if echo $arg | grep -e "--addon=" >/dev/null; then
		ADDON=`echo $arg | sed 's/--addon=//'`
	    fi
	    ;;
	--export-path*)
	    if echo $arg | grep -e "--export-path=" >/dev/null; then
		EXPORT_PATH=`echo $arg | sed 's/--export-path=//'`
	    fi
	    ;;

	--*)
	    # Check for DB arguments
	    ezdist_db_check_options "$arg"

	    if [ $? -ne 0 ]; then
		echo "$arg: unknown long option specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
	-*)
	    # Check for DB arguments
	    ezdist_db_check_short_options "$arg"

	    if [ $? -ne 0 ]; then
		echo "$arg: unknown option specified"
		echo
		echo "Type '$0 --help\` for a list of options to use."
		exit 1
	    fi
	    ;;
	*)
	    echo "$arg: unknown argument specified"
	    echo
            echo "Type '$0 --help\` for a list of options to use."
	    exit 1
	    ;;
    esac;
done

[ -d $EXPORT_PATH ] || { echo "The export path $EXPORT_PATH does not exist"; exit 1; }

## Common initialization

rm -rf "$OUTPUT_REPOSITORY"
mkdir -p "$OUTPUT_REPOSITORY" || exit 1

rm -rf "$SITE_PACKAGES"
mkdir -p "$SITE_PACKAGES" || exit 1

rm -rf "$SITE_PACKAGES_EXPORT"
mkdir -p "$SITE_PACKAGES_EXPORT" || exit 1

if ezdist_is_empty "$DB_TYPE"; then
    echo "No database type chosen"
    echo "Type '$0 --help\` for a list of options to use."
    exit 1
fi

[ -z "$QUIET" ] && echo "Connecting to DB using `ezdist_db_show_config`"
ezdist_db_prepare_params

# Contacts addon

if [[ -z $ADDON || $ADDON = 'contacts' ]]; then
    site='contacts'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Contacts" "$VERSION" -- \
	set $site description "Adds a simple contact database, it contains companies and persons." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'contacts/*' \
	|| exit 1
fi

# Contact us addon

if [[ -z $ADDON || $ADDON = 'contact_us' ]]; then
    site='contact_us'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Contact us" "$VERSION" -- \
	set $site description "Adds feedback form functionality." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'contact_us' \
	|| exit 1
fi

# Files addon

if [[ -z $ADDON || $ADDON = 'files' ]]; then
    site='files'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Files" "$VERSION" -- \
	set $site description "Adds a file database." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'files/*' \
	|| exit 1
fi

# Forum addon

if [[ -z $ADDON || $ADDON = 'forum' ]]; then
    site='forum'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Forum" "$VERSION" -- \
	set $site description "Adds forum functionality. You can add multiple forums which can have multiple topics with replies." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'forums/*' \
	|| exit 1
fi

# Gallery addon

if [[ -z $ADDON || $ADDON = 'gallery' ]]; then
    site='gallery'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Image gallery" "$VERSION" -- \
	set $site description "Adds image gallery functionality. Allows for creation of multiple galleries which can show images either as thumbnails or slideshow." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'galleries/*' \
	|| exit 1
fi

# Links addon

if [[ -z $ADDON || $ADDON = 'links' ]]; then
    site='links'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Links" "$VERSION" -- \
	set $site description "Adds a link database." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'links/*' \
	|| exit 1
fi

# Media addon

if [[ -z $ADDON || $ADDON = 'media' ]]; then
    site='media'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Media types" "$VERSION" -- \
	set $site description "Adds media functionality. Allows for publishing of Flash, Quicktime, Real video and Windows media files." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'media_files/*' \
	|| exit 1
fi

## News addon

if [[ -z $ADDON || $ADDON = 'news' ]]; then
    site='news'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "News" "$VERSION" -- \
	set $site description "Adds article/news functionality to your site." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'news/*' \
	|| exit 1
fi

# Poll addon

if [[ -z $ADDON || $ADDON = 'poll' ]]; then
    site='poll'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Polls" "$VERSION" -- \
	set $site description "Adds functionality for handling simple user-surveys/polls." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'polls/*' \
	|| exit 1
fi

# Products addon

if [[ -z $ADDON || $ADDON = 'products' ]]; then
    site='products'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Products" "$VERSION" -- \
	set $site description "Adds products with shopping functionality." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'products/*' \
	|| exit 1
fi

# Weblog addon

if [[ -z $ADDON || $ADDON = 'weblog' ]]; then
    site='weblog'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
	-ladmin -ppublish \
	create $site "Weblog" "$VERSION" -- \
	set $site description "Adds blogging/weblog functionality." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base,admin 'weblog/*' \
	|| exit 1
fi

for addon in $ADDON_PACKAGES; do
    [[ -z $ADDON || $ADDON = $addon ]] || continue

    if [ -d "$OUTPUT_REPOSITORY/$addon" ]; then
	$PMBIN -r "$OUTPUT_REPOSITORY" $PARAM_EZ_DB_ALL $QUIET \
            -ladmin -ppublish \
	    export $addon -d "$EXPORT_PATH" || exit 1
    fi

done
