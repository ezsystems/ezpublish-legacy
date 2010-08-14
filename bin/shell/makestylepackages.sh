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
OUTPUT_REPOSITORY="$TMPDIR/sites"
EXPORT_PATH="packages/styles"

# Check parameters
for arg in $*; do
    case $arg in
	--help|-h)
	    echo "Usage: $0 [options]"
	    echo
	    echo "Options: -h"
	    echo "         --help                     This message"
	    echo "         --export-path=DIR          Where to export the packages, default is 'packages/styles'"
	    echo
	    exit 1
	    ;;
	-q)
	    QUIET="-q"
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

[ -z $QUIET ] && echo "Placing packages in $SITE_PACKAGES"

## Common initialization

rm -rf "$OUTPUT_REPOSITORY"
mkdir -p "$OUTPUT_REPOSITORY" || exit 1

rm -rf "$SITE_PACKAGES"
mkdir -p "$SITE_PACKAGES" || exit 1

rm -rf "$SITE_PACKAGES_EXPORT"
mkdir -p "$SITE_PACKAGES_EXPORT" || exit 1


    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
	create t01 "Theme 01" "$VERSION" import  -- \
	set t01 type sitestyle -- \
	add t01 file -n sitecssfile design/base/stylesheets/t1/site-colors.css -- \
	add t01 file -n classescssfile design/base/stylesheets/t1/classes-colors.css -- \
	add t01 thumbnail -n thumbnail design/standard/images/setup/t1.png -- \
	add t01 file -n image design/base/images/t1/t1-tab-selected-right.gif -- \
	add t01 file -n image design/base/images/t1/t1-tab-selected-left.gif -- \
	add t01 file -n image design/base/images/t1/t1-arrow.gif -- \
	add t01 file -n image design/base/images/t1/t1-bin.gif -- \
	add t01 file -n image design/base/images/t1/t1-button.gif -- \
	add t01 file -n image design/base/images/t1/t1-tab-normal-left.gif -- \
	add t01 file -n image design/base/images/t1/t1-tab-normal-right.gif -- \
	export t01 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
	create t02 "Theme 02" "$VERSION" import -- \
	set t02 type sitestyle -- \
	add t02 file -n sitecssfile design/base/stylesheets/t2/site-colors.css -- \
	add t02 file -n classescssfile design/base/stylesheets/t2/classes-colors.css -- \
	add t02 thumbnail -n thumbnail design/standard/images/setup/t2.png -- \
	add t02 file -n image design/base/images/t2/ratingstar.png -- \
	add t02 file -n image design/base/images/t2/arrow.gif -- \
	add t02 file -n image design/base/images/t2/bin.gif -- \
	add t02 file -n image design/base/images/t2/button.gif -- \
	export t02 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t03 "Theme 03" "$VERSION" import -- \
	set t03 type sitestyle -- \
	add t03 file -n sitecssfile design/base/stylesheets/t3/site-colors.css -- \
	add t03 file -n classescssfile design/base/stylesheets/t3/classes-colors.css -- \
	add t03 thumbnail -n thumbnail design/standard/images/setup/t3.png -- \
	add t03 file -n image design/base/images/t3/ratingstar.png -- \
	add t03 file -n image design/base/images/t3/arrow.gif -- \
	add t03 file -n image design/base/images/t3/bin.gif -- \
	add t03 file -n image design/base/images/t3/button.gif -- \
	export t03 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t04 "Theme 04" "$VERSION" import -- \
	set t04 type sitestyle -- \
	add t04 file -n sitecssfile design/base/stylesheets/t4/site-colors.css -- \
	add t04 file -n classescssfile design/base/stylesheets/t4/classes-colors.css -- \
	add t04 thumbnail -n thumbnail design/standard/images/setup/t4.png -- \
	add t04 file -n image design/base/images/t4/ratingstar.png -- \
	add t04 file -n image design/base/images/t4/arrow.gif -- \
	add t04 file -n image design/base/images/t4/bin.gif -- \
	add t04 file -n image design/base/images/t4/button.gif -- \
	export t04 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t05 "Theme 05" "$VERSION" import -- \
	set t05 type sitestyle -- \
	add t05 file -n sitecssfile design/base/stylesheets/t5/site-colors.css -- \
	add t05 file -n classescssfile design/base/stylesheets/t5/classes-colors.css -- \
	add t05 thumbnail -n thumbnail design/standard/images/setup/t5.png -- \
	add t05 file -n image design/base/images/t5/ratingstar.png -- \
	add t05 file -n image design/base/images/t5/arrow.gif -- \
	add t05 file -n image design/base/images/t5/bin.gif -- \
	add t05 file -n image design/base/images/t5/button.gif -- \
	export t05 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t06 "Theme 06" "$VERSION" import -- \
	set t06 type sitestyle -- \
	add t06 file -n sitecssfile design/base/stylesheets/t6/site-colors.css -- \
	add t06 file -n classescssfile design/base/stylesheets/t6/classes-colors.css -- \
	add t06 thumbnail -n thumbnail design/standard/images/setup/t6.png -- \
	add t06 file -n image design/base/images/t6/arrow.gif -- \
	add t06 file -n image design/base/images/t6/button.gif -- \
	export t06 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t07 "Theme 07" "$VERSION" import -- \
	set t07 type sitestyle -- \
	add t07 file -n sitecssfile design/base/stylesheets/t7/site-colors.css -- \
	add t07 file -n classescssfile design/base/stylesheets/t7/classes-colors.css -- \
	add t07 thumbnail -n thumbnail design/standard/images/setup/t7.png -- \
	add t07 file -n image design/base/images/t7/arrow.gif -- \
	add t07 file -n image design/base/images/t7/button.gif -- \
	export t07 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t08 "Theme 08" "$VERSION" import -- \
	set t08 type sitestyle -- \
	add t08 file -n sitecssfile design/base/stylesheets/t8/site-colors.css -- \
	add t08 file -n classescssfile design/base/stylesheets/t8/classes-colors.css -- \
	add t08 thumbnail -n thumbnail design/standard/images/setup/t8.png -- \
	add t08 file -n image design/base/images/t8/arrow.gif -- \
	add t08 file -n image design/base/images/t8/button.gif -- \
        add t08 file -n image design/base/images/t8/bg.gif -- \
        add t08 file -n image design/base/images/t8/bin.gif -- \
	export t08 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t09 "Theme 09" "$VERSION" import -- \
	set t09 type sitestyle -- \
	add t09 file -n sitecssfile design/base/stylesheets/t9/site-colors.css -- \
	add t09 file -n classescssfile design/base/stylesheets/t9/classes-colors.css -- \
	add t09 thumbnail -n thumbnail design/standard/images/setup/t9.png -- \
	add t09 file -n image design/base/images/t9/arrow.gif -- \
	add t09 file -n image design/base/images/t9/button.gif -- \
	export t09 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t10 "Theme 10" "$VERSION" import -- \
	set t10 type sitestyle -- \
	add t10 file -n sitecssfile design/base/stylesheets/t10/site-colors.css -- \
	add t10 file -n classescssfile design/base/stylesheets/t10/classes-colors.css -- \
	add t10 thumbnail -n thumbnail design/standard/images/setup/t10.png -- \
	add t10 file -n image design/base/images/t10/arrow.gif -- \
	add t10 file -n image design/base/images/t10/button.gif -- \
	export t10 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t11 "Theme 11" "$VERSION" import -- \
	set t11 type sitestyle -- \
	add t11 file -n sitecssfile design/base/stylesheets/t11/site-colors.css -- \
	add t11 file -n classescssfile design/base/stylesheets/t11/classes-colors.css -- \
	add t11 thumbnail -n thumbnail design/standard/images/setup/t11.png -- \
	add t11 file -n image design/base/images/t11/arrow.gif -- \
	add t11 file -n image design/base/images/t11/button.gif -- \
	export t11 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t12 "Theme 12" "$VERSION" import -- \
	set t12 type sitestyle -- \
	add t12 file -n sitecssfile design/base/stylesheets/t12/site-colors.css -- \
	add t12 file -n classescssfile design/base/stylesheets/t12/classes-colors.css -- \
	add t12 thumbnail -n thumbnail design/standard/images/setup/t12.png -- \
	add t12 file -n image design/base/images/t12/arrow.gif -- \
	add t12 file -n image design/base/images/t12/button.gif -- \
	export t12 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t13 "Theme 13" "$VERSION" import -- \
	set t13 type sitestyle -- \
	add t13 file -n sitecssfile design/base/stylesheets/t13/site-colors.css -- \
	add t13 file -n classescssfile design/base/stylesheets/t13/classes-colors.css -- \
	add t13 thumbnail -n thumbnail design/standard/images/setup/t13.png -- \
	add t13 file -n image design/base/images/t13/arrow.gif -- \
	add t13 file -n image design/base/images/t13/button.gif -- \
	export t13 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t14 "Theme 14" "$VERSION" import -- \
	set t14 type sitestyle -- \
	add t14 file -n sitecssfile design/base/stylesheets/t14/site-colors.css -- \
	add t14 file -n classescssfile design/base/stylesheets/t14/classes-colors.css -- \
	add t14 thumbnail -n thumbnail design/standard/images/setup/t14.png -- \
	add t14 file -n image design/base/images/t14/arrow.gif -- \
	add t14 file -n image design/base/images/t14/button.gif -- \
	export t14 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t15 "Theme 15" "$VERSION" import -- \
	set t15 type sitestyle -- \
	add t15 file -n sitecssfile design/base/stylesheets/t15/site-colors.css -- \
	add t15 file -n classescssfile design/base/stylesheets/t15/classes-colors.css -- \
	add t15 thumbnail -n thumbnail design/standard/images/setup/t15.png -- \
	add t15 file -n image design/base/images/t15/arrow.gif -- \
	add t15 file -n image design/base/images/t15/carpet-logo.gif -- \
	add t15 file -n image design/base/images/t15/carpet_dark-background.gif -- \
	add t15 file -n image design/base/images/t15/carpet-background.gif -- \
	export t15 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t16 "Theme 16" "$VERSION" import -- \
	set t16 type sitestyle -- \
	add t16 file -n sitecssfile design/base/stylesheets/t16/site-colors.css -- \
	add t16 file -n classescssfile design/base/stylesheets/t16/classes-colors.css -- \
	add t16 thumbnail -n thumbnail design/standard/images/setup/t16.png -- \
	add t16 file -n image design/base/images/t16/arrow.gif -- \
	add t16 file -n image design/base/images/t16/grid-background.gif -- \
	add t16 file -n image design/base/images/t16/freezer-logo.gif -- \
	add t16 file -n image design/base/images/t16/freeze-background.jpg -- \
	export t16 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t17 "Theme 17" "$VERSION" import -- \
	set t17 type sitestyle -- \
	add t17 file -n sitecssfile design/base/stylesheets/t17/site-colors.css -- \
	add t17 file -n classescssfile design/base/stylesheets/t17/classes-colors.css -- \
	add t17 thumbnail -n thumbnail design/standard/images/setup/t17.png -- \
	add t17 file -n image design/base/images/t17/arrow.gif -- \
	add t17 file -n image design/base/images/t17/button.gif -- \
	export t17 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t18 "Theme 18" "$VERSION" import -- \
	set t18 type sitestyle -- \
	add t18 file -n sitecssfile design/base/stylesheets/t18/site-colors.css -- \
	add t18 file -n classescssfile design/base/stylesheets/t18/classes-colors.css -- \
	add t18 thumbnail -n thumbnail design/standard/images/setup/t18.png -- \
	add t18 file -n image design/base/images/t18/vline.gif -- \
	add t18 file -n image design/base/images/t18/background-top.png -- \
	add t18 file -n image design/base/images/t18/background.png -- \
	add t18 file -n image design/base/images/t18/background-menu.png -- \
	export t18 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t19 "Theme 19" "$VERSION" import -- \
	set t19 type sitestyle -- \
	add t19 file -n sitecssfile design/base/stylesheets/t19/site-colors.css -- \
	add t19 file -n classescssfile design/base/stylesheets/t19/classes-colors.css -- \
	add t19 thumbnail -n thumbnail design/standard/images/setup/t19.png -- \
	add t19 file -n image design/base/images/t19/arrow.gif -- \
	add t19 file -n image design/base/images/t19/button.gif -- \
	export t19 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t20 "Theme 20" "$VERSION" import -- \
	set t20 type sitestyle -- \
	add t20 file -n sitecssfile design/base/stylesheets/t20/site-colors.css -- \
	add t20 file -n classescssfile design/base/stylesheets/t20/classes-colors.css -- \
	add t20 thumbnail -n thumbnail design/standard/images/setup/t20.png -- \
	add t20 file -n image design/base/images/t20/arrow.gif -- \
	add t20 file -n image design/base/images/t20/button.gif -- \
	export t20 -d "$EXPORT_PATH" || exit 1
