#!/bin/bash

. ./bin/shell/common.sh
. ./bin/shell/packagescommon.sh

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
OUTPUT_REPOSITORY_EXPORT="$TMPDIR/export/addons"
EXPORT_PATH="packages/addons"

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
            echo "Example:"
            echo "$0 --export-path=/tmp/export"
	    exit 1
	    ;;
	-q)
	    QUIET="-q"
	    ;;
	--auto-commit)
	    AUTO_COMMIT="true"
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
	*)
	    echo "$arg: unkown option specified"
            $0 -h
	    exit 1
	    ;;
    esac;
done

[ -d $EXPORT_PATH ] || { echo "The export path $EXPORT_PATH does not exist"; exit 1; }

## Common initialization

rm -rf "$OUTPUT_REPOSITORY"
mkdir -p "$OUTPUT_REPOSITORY" || exit 1

rm -rf "$OUTPUT_REPOSITORY_EXPORT"
mkdir -p "$OUTPUT_REPOSITORY_EXPORT" || exit 1

rm -rf "$SITE_PACKAGES"
mkdir -p "$SITE_PACKAGES" || exit 1

rm -rf "$SITE_PACKAGES_EXPORT"
mkdir -p "$SITE_PACKAGES_EXPORT" || exit 1

# Contacts addon

if [[ -z $ADDON || $ADDON = 'contacts' ]]; then
    site='contacts'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Contacts" "$VERSION" -- \
	set $site description "Adds a simple contact database, it contains companies and persons." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'contacts/*' \
	|| exit 1
fi

# Contact us addon

if [[ -z $ADDON || $ADDON = 'contact_us' ]]; then
    site='contact_us'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Contact us" "$VERSION" -- \
	set $site description "Adds feedback form functionality." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'contact_us' \
	|| exit 1
fi

# Files addon

if [[ -z $ADDON || $ADDON = 'files' ]]; then
    site='files'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Files" "$VERSION" -- \
	set $site description "Adds a file database." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'files/*' \
	|| exit 1
fi

# Forum addon

if [[ -z $ADDON || $ADDON = 'forum' ]]; then
    site='files'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Forum" "$VERSION" -- \
	set $site description "Adds forum functionality. You can add multiple forums which can have multiple topics with replies." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'forums/*' \
	|| exit 1
fi

# Gallery addon

if [[ -z $ADDON || $ADDON = 'gallery' ]]; then
    site='gallery'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Image gallery" "$VERSION" -- \
	set $site description "Adds image gallery functionality. Allows for creation of multiple galleries which can show images either as thumbnails or slideshow." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'galleries/*' \
	|| exit 1
fi

# Links addon

if [[ -z $ADDON || $ADDON = 'links' ]]; then
    site='links'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Links" "$VERSION" -- \
	set $site description "Adds a link database." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'links/*' \
	|| exit 1
fi

# Media addon

if [[ -z $ADDON || $ADDON = 'media' ]]; then
    site='media'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Media types" "$VERSION" -- \
	set $site description "Adds media functionality. Allows for publishing of Flash, Quicktime, Real video and Windows media files." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'media/*' \
	|| exit 1
fi

## News addon

if [[ -z $ADDON || $ADDON = 'news' ]]; then
    site='news'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "News" "$VERSION" -- \
	set $site description "Adds article/news functionality to your site." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'news/*' \
	|| exit 1
fi

# Poll addon

if [[ -z $ADDON || $ADDON = 'poll' ]]; then
    site='poll'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Polls" "$VERSION" -- \
	set $site description "Adds functionality for handling simple user-surveys/polls." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'polls/*' \
	|| exit 1
fi

# Products addon

if [[ -z $ADDON || $ADDON = 'products' ]]; then
    site='products'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Products" "$VERSION" -- \
	set $site description "Adds products with shopping functionality." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'products/*' \
	|| exit 1
fi

# Weblog addon

if [[ -z $ADDON || $ADDON = 'weblog' ]]; then
    site='weblog'
    "$PMBIN" -r "$OUTPUT_REPOSITORY" $QUIET \
	-ladmin -ppublish \
	create $site "Weblog" "$VERSION" -- \
	set $site description "Adds blogging/weblog functionality." -- \
	set $site type 'contentobject' -- \
	add $site contentobject --siteaccess=base 'products/*' \
	|| exit 1
fi

#if [ -z "$ADDON" ]; then
#    if [ -n "$AUTO_COMMIT" ]; then
#	svn rm "$EXPORT_PATH/*" &>/dev/null || exit 1
#	svn ci "$EXPORT_PATH" &>/dev/null || exit 1
#    fi
#fi


for addon in $ADDON_PACKAGES; do
    [[ -z $ADDON || $ADDON = $addon ]] || continue

    if [ -n "$ADDON" ]; then
	if [ -n "$AUTO_COMMIT" ]; then
# 	    svn rm "$EXPORT_PATH/$addon" &>/dev/null || exit 1
# 	    svn ci "$EXPORT_PATH"
	    find "$EXPORT_PATH/$addon/" ! -path \*/.svn\* -exec rm -f {} \; &>/dev/null
	fi
    fi

    if [ -d "$OUTPUT_REPOSITORY/$addon" ]; then
	$PMBIN -r "$OUTPUT_REPOSITORY" $QUIET \
            -ladmin -ppublish \
	    export $addon -d "$OUTPUT_REPOSITORY_EXPORT" || exit 1
    fi

    if [ -n "$AUTO_COMMIT" ]; then
#	svn add "$EXPORT_PATH/$addon" &>/dev/null || exit 1
	cp -R "$OUTPUT_REPOSITORY_EXPORT/$addon"/* "$EXPORT_PATH/$addon/"
    fi
#    if [ -n "$ADDON" ]; then
#	if [ -n "$AUTO_COMMIT" ]; then
#	    svn ci "$EXPORT_PATH/$addon" &>/dev/null || exit 1
#	fi
#    fi

done

#if [ -z "$ADDON" ]; then
#    if [ -n "$AUTO_COMMIT" ]; then
#	svn ci "$EXPORT_PATH" &>/dev/null || exit 1
#    fi
#fi
