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
	    echo "         --build-root=DIR           Set build root, default is /tmp"
	    echo
	    echo "* Warning: Using these options will not make a valid distribution"
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
	create t1 "T1 CSS" "$VERSION" import  -- \
	set t1 type sitestyle -- \
	add t1 file -n sitecssfile design/base/stylesheets/t1/site-colors.css -- \
	add t1 file -n classescssfile design/base/stylesheets/t1/classes-colors.css -- \
	add t1 thumbnail -n thumbnail design/standard/images/setup/t1.png -- \
	add t1 file -n image design/base/images/t1/t1-tab-selected-right.gif -- \
	add t1 file -n image design/base/images/t1/t1-tab-selected-left.gif -- \
	add t1 file -n image design/base/images/t1/t1-arrow.gif -- \
	add t1 file -n image design/base/images/t1/t1-bin.gif -- \
	add t1 file -n image design/base/images/t1/t1-button.gif -- \
	add t1 file -n image design/base/images/t1/t1-tab-normal-left.gif -- \
	add t1 file -n image design/base/images/t1/t1-tab-normal-right.gif -- \
	export t1 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
	create t2 "T2 CSS" "$VERSION" import -- \
	set t2 type sitestyle -- \
	add t2 file -n sitecssfile design/base/stylesheets/t2/site-colors.css -- \
	add t2 file -n classescssfile design/base/stylesheets/t2/classes-colors.css -- \
	add t2 thumbnail -n thumbnail design/standard/images/setup/t2.png -- \
	add t2 file -n image design/base/images/t2/ratingstar.png -- \
	add t2 file -n image design/base/images/t2/arrow.gif -- \
	add t2 file -n image design/base/images/t2/bin.gif -- \
	add t2 file -n image design/base/images/t2/button.gif -- \
	export t2 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t3 "T3 CSS" "$VERSION" import -- \
	set t3 type sitestyle -- \
	add t3 file -n sitecssfile design/base/stylesheets/t3/site-colors.css -- \
	add t3 file -n classescssfile design/base/stylesheets/t3/classes-colors.css -- \
	add t3 thumbnail -n thumbnail design/standard/images/setup/t3.png -- \
	add t3 file -n image design/base/images/t3/ratingstar.png -- \
	add t3 file -n image design/base/images/t3/arrow.gif -- \
	add t3 file -n image design/base/images/t3/bin.gif -- \
	add t3 file -n image design/base/images/t3/button.gif -- \
	export t3 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t4 "T4 CSS" "$VERSION" import -- \
	set t4 type sitestyle -- \
	add t4 file -n sitecssfile design/base/stylesheets/t4/site-colors.css -- \
	add t4 file -n classescssfile design/base/stylesheets/t4/classes-colors.css -- \
	add t4 thumbnail -n thumbnail design/standard/images/setup/t4.png -- \
	add t4 file -n image design/base/images/t4/ratingstar.png -- \
	add t4 file -n image design/base/images/t4/arrow.gif -- \
	add t4 file -n image design/base/images/t4/bin.gif -- \
	add t4 file -n image design/base/images/t4/button.gif -- \
	export t4 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t5 "T5 CSS" "$VERSION" import -- \
	set t5 type sitestyle -- \
	add t5 file -n sitecssfile design/base/stylesheets/t5/site-colors.css -- \
	add t5 file -n classescssfile design/base/stylesheets/t5/classes-colors.css -- \
	add t5 thumbnail -n thumbnail design/standard/images/setup/t5.png -- \
	add t5 file -n image design/base/images/t5/ratingstar.png -- \
	add t5 file -n image design/base/images/t5/arrow.gif -- \
	add t5 file -n image design/base/images/t5/bin.gif -- \
	add t5 file -n image design/base/images/t5/button.gif -- \
	export t5 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t6 "T6 CSS" "$VERSION" import -- \
	set t6 type sitestyle -- \
	add t6 file -n sitecssfile design/base/stylesheets/t6/site-colors.css -- \
	add t6 file -n classescssfile design/base/stylesheets/t6/classes-colors.css -- \
	add t6 thumbnail -n thumbnail design/standard/images/setup/t6.png -- \
	add t6 file -n image design/base/images/t6/arrow.gif -- \
	add t6 file -n image design/base/images/t6/button.gif -- \
	export t6 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t7 "T7 CSS" "$VERSION" import -- \
	set t7 type sitestyle -- \
	add t7 file -n sitecssfile design/base/stylesheets/t7/site-colors.css -- \
	add t7 file -n classescssfile design/base/stylesheets/t7/classes-colors.css -- \
	add t7 thumbnail -n thumbnail design/standard/images/setup/t7.png -- \
	add t7 file -n image design/base/images/t7/arrow.gif -- \
	add t7 file -n image design/base/images/t7/button.gif -- \
	export t7 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t8 "T8 CSS" "$VERSION" import -- \
	set t8 type sitestyle -- \
	add t8 file -n sitecssfile design/base/stylesheets/t8/site-colors.css -- \
	add t8 file -n classescssfile design/base/stylesheets/t8/classes-colors.css -- \
	add t8 thumbnail -n thumbnail design/standard/images/setup/t8.png -- \
	add t8 file -n image design/base/images/t8/arrow.gif -- \
	add t8 file -n image design/base/images/t8/button.gif -- \
        add t8 file -n image design/base/images/t8/bg.gif -- \
        add t8 file -n image design/base/images/t8/bin.gif -- \
	export t8 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t9 "T9 CSS" "$VERSION" import -- \
	set t9 type sitestyle -- \
	add t9 file -n sitecssfile design/base/stylesheets/t9/site-colors.css -- \
	add t9 file -n classescssfile design/base/stylesheets/t9/classes-colors.css -- \
	add t9 thumbnail -n thumbnail design/standard/images/setup/t9.png -- \
	add t9 file -n image design/base/images/t9/arrow.gif -- \
	add t9 file -n image design/base/images/t9/button.gif -- \
	export t9 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t10 "T10 CSS" "$VERSION" import -- \
	set t10 type sitestyle -- \
	add t10 file -n sitecssfile design/base/stylesheets/t10/site-colors.css -- \
	add t10 file -n classescssfile design/base/stylesheets/t10/classes-colors.css -- \
	add t10 thumbnail -n thumbnail design/standard/images/setup/t10.png -- \
	add t10 file -n image design/base/images/t10/arrow.gif -- \
	add t10 file -n image design/base/images/t10/button.gif -- \
	export t10 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t11 "T11 CSS" "$VERSION" import -- \
	set t11 type sitestyle -- \
	add t11 file -n sitecssfile design/base/stylesheets/t11/site-colors.css -- \
	add t11 file -n classescssfile design/base/stylesheets/t11/classes-colors.css -- \
	add t11 thumbnail -n thumbnail design/standard/images/setup/t11.png -- \
	add t11 file -n image design/base/images/t11/arrow.gif -- \
	add t11 file -n image design/base/images/t11/button.gif -- \
	export t11 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t12 "T12 CSS" "$VERSION" import -- \
	set t12 type sitestyle -- \
	add t12 file -n sitecssfile design/base/stylesheets/t12/site-colors.css -- \
	add t12 file -n classescssfile design/base/stylesheets/t12/classes-colors.css -- \
	add t12 thumbnail -n thumbnail design/standard/images/setup/t12.png -- \
	add t12 file -n image design/base/images/t12/arrow.gif -- \
	add t12 file -n image design/base/images/t12/button.gif -- \
	export t12 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t13 "T13 CSS" "$VERSION" import -- \
	set t13 type sitestyle -- \
	add t13 file -n sitecssfile design/base/stylesheets/t13/site-colors.css -- \
	add t13 file -n classescssfile design/base/stylesheets/t13/classes-colors.css -- \
	add t13 thumbnail -n thumbnail design/standard/images/setup/t13.png -- \
	add t13 file -n image design/base/images/t13/arrow.gif -- \
	add t13 file -n image design/base/images/t13/button.gif -- \
	export t13 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t14 "T14 CSS" "$VERSION" import -- \
	set t14 type sitestyle -- \
	add t14 file -n sitecssfile design/base/stylesheets/t14/site-colors.css -- \
	add t14 file -n classescssfile design/base/stylesheets/t14/classes-colors.css -- \
	add t14 thumbnail -n thumbnail design/standard/images/setup/t14.png -- \
	add t14 file -n image design/base/images/t14/arrow.gif -- \
	add t14 file -n image design/base/images/t14/button.gif -- \
	export t14 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t15 "T15 CSS" "$VERSION" import -- \
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
        create t16 "T16 CSS" "$VERSION" import -- \
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
        create t17 "T17 CSS" "$VERSION" import -- \
	set t17 type sitestyle -- \
	add t17 file -n sitecssfile design/base/stylesheets/t17/site-colors.css -- \
	add t17 file -n classescssfile design/base/stylesheets/t17/classes-colors.css -- \
	add t17 thumbnail -n thumbnail design/standard/images/setup/t17.png -- \
	add t17 file -n image design/base/images/t17/arrow.gif -- \
	add t17 file -n image design/base/images/t17/button.gif -- \
	export t17 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t18 "T18 CSS" "$VERSION" import -- \
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
        create t19 "T19 CSS" "$VERSION" import -- \
	set t19 type sitestyle -- \
	add t19 file -n sitecssfile design/base/stylesheets/t19/site-colors.css -- \
	add t19 file -n classescssfile design/base/stylesheets/t19/classes-colors.css -- \
	add t19 thumbnail -n thumbnail design/standard/images/setup/t19.png -- \
	add t19 file -n image design/base/images/t19/arrow.gif -- \
	add t19 file -n image design/base/images/t19/button.gif -- \
	export t19 -d "$EXPORT_PATH" || exit 1
    ./ezpm.php -r "$SITE_PACKAGES" $QUIET \
        create t20 "T20 CSS" "$VERSION" import -- \
	set t20 type sitestyle -- \
	add t20 file -n sitecssfile design/base/stylesheets/t20/site-colors.css -- \
	add t20 file -n classescssfile design/base/stylesheets/t20/classes-colors.css -- \
	add t20 thumbnail -n thumbnail design/standard/images/setup/t20.png -- \
	add t20 file -n image design/base/images/t20/arrow.gif -- \
	add t20 file -n image design/base/images/t20/button.gif -- \
	export t20 -d "$EXPORT_PATH" || exit 1
