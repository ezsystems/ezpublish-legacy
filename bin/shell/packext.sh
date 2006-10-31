#!/bin/bash

function show_help
{
	    echo "Usage: $0 [options] <EXTENSION-IDENTIFIER>"
            echo " or"
            echo "Usage: $0 [options] --svn <EXTENSION-URL>"
	    echo
	    echo "Options:"
	    echo "    -h, --help               This message"
	    echo "    --svn                    Fetch the extension from the given SVN URL"
            echo "    --output-dir=<dir>       Output directory         [$OUTPUT_DIR]"
            echo "    --dist-type=<dist-type>  Distribution type to use [$DIST_TYPE]"
            echo "    --build-number=<num>     Snapshot build number"
            echo
            echo "Note:"
            echo "By default (i.e. without --svn option) extension is searched "
            echo "in extension/<EXTENSION-IDENTIFIER> directory."
            echo
            echo "Make sure that the SVN-property ez:distribution is set to 'full'"
            echo "for the files that are supposed to be included in the distribution,"
            echo "if you use --svn"
            echo
            echo "Examples:"
            echo " $0 ezforum"
            echo " $0 --svn http://svn.server.com/projects/ezforum/"
	    exit 1
}

# Parse command line parameters
function parse_cl_parameters
{
    [ -z "$*" ] && show_help
    for arg in $*; do
        case $arg in
    	--help|-h)
                show_help
    	    ;;
    	--svn)
    	    USE_SVN=1
            ;;
    	--build-number=*)
	    if echo $arg | grep -e "--build-number=" >/dev/null; then
                BUILD_SNAPSHOT=1
		CURRENT_BUILD_NUMBER=`echo $arg | sed 's/--build-number=//'`
	    fi
	    ;;
	--dist-type=*)
	    if echo $arg | grep -e "--dist-type=" >/dev/null; then
		DIST_TYPE=`echo $arg | sed 's/--dist-type=//'`
	    fi
	    ;;
	--output-dir=*)
	    if echo $arg | grep -e "--output-dir=" >/dev/null; then
		OUTPUT_DIR=`echo $arg | sed 's/--output-dir=//'`
	    fi
	    ;;
        -*)
    	    echo "$arg: unkown option specified"
                $0 -h
    	    exit 1
    	    ;;
    	*)
                [ -z "$extension" ] && extension=$arg
    	    ;;
        esac;
    done

    # Check if the extension to package is specified.
    if [ -z "$extension" ]; then
        echo "Please specify an extension to package."
        echo
        show_help
    fi
}


function create_destination_dirs
{
    # Create directory to copy extension to before packaging (temporaray directory).
    [ -d "$TMP_DIR" ] && rm -rf "$TMP_DIR"
    mkdir -p "$TMP_DIR"

    # Create directory to store packaged extension in (output directory).
    #[ -d "$OUTPUT_DIR" ] && rm -rf "$OUTPUT_DIR"
    if [ ! -f "$OUTPUT_DIR" ]; then
        mkdir -p "$OUTPUT_DIR"
    fi
}

function export_extension
{
    # Export extension.
    if [ "$USE_SVN" = 1 ]; then
        # Fetch extension from SVN.
        echo -n "Fetching extension from SVN"
        svn co "$extension" "$TMP_DIR" &>.svn.log
        ez_result_output $? "Failed to export $extension, see `ez_color_file .svn.log` for more info" || exit 1
    else
        # Copy extension from ./extension/<EXTENSION-IDENTIFIER>
        if [ ! -d "extension/$extension" ]; then
            echo "Directory `ez_color_file extension/$extension` does not exist."
            echo "Create it and make sure it contains an extension."
            exit 1
        fi
        cp -R extension/$extension/* "$TMP_DIR" &> .copy.log
        ez_result_output $? "Failed to export $extension, see `ez_color_file .copy.log` for more info" || exit 1
    fi
}

function parse_extension_dist_sh
{
    # Check if dist.sh exists.
    if [ ! -f "$TMP_DIR/dist.sh" ]; then
        echo "Distribution info file `ez_color_file dist.sh` is missing from extension"
        echo "This must be created before it can be packaged"
        exit 1
    fi

    # Check if dist.sh is executable.
    if [ ! -x "$TMP_DIR/dist.sh" ]; then
        echo "Distribution info file `ez_color_file dist.sh` is not executable"
        echo "This file must be executable for this script to read its contents"
        exit 1
    fi

    # Load variables from dist.sh.
    EXTENSION_NAME=""
    EXTENSION_IDENTIFIER=""
    EXTENSION_SUMMARY=""
    EXTENSION_LICENSE=""
    EXTENSION_VERSION=""
    EXTENSION_PUBLISH_VERSION=""
    EXTENSION_ARCHIVE_NAME=""
    EXTENSION_PHP_VERSION=""
    EXTENSION_FILTER_FILES=""
    . "$TMP_DIR/dist.sh"

    # TODO: Add option to skip removal of dist.sh.
    rm -f "$TMP_DIR/dist.sh"

    if [ -z "$EXTENSION_IDENTIFIER" ]; then
        echo "Identifier was not set for extension"
        exit 1
    fi
}

function copy_files_marked_for_distribution
{
    # Copy distribuion file to the destination directory.
    DEST="$OUTPUT_DIR/$EXTENSION_IDENTIFIER"
    mkdir -p "$DEST"
    echo "Copying distribution files"
    if [ "$USE_SVN" = 1 ]; then
        (cd "$TMP_DIR" && scan_dir . )
    else
        (cd "$TMP_DIR" && scan_dir . nosvn )
    fi
    echo
}

function find_svn_dirs
{
    # Find .svn directories.
    echo -n "Looking for `$SETCOLOR_DIR`.svn`$SETCOLOR_NORMAL` directories"
    (cd $DEST
        find . -name .svn -print &>.find.log
        if [ $? -ne 0 ]; then
            ez_result_output 1 "The following .svn directories was found"
            cat .find.log
            rm .find.log
            exit 1
        fi
        ez_result_output 0 ""
        rm .find.log ) || exit 1
}

function find_temporary_files
{
    # Find temporary files
    echo -n "Looking for `$SETCOLOR_COMMENT`temp`$SETCOLOR_NORMAL` files"
    (cd $DEST
        TEMPFILES=`find . -name '*[~#]' -print`
        echo $TEMPFILES | grep -e '[~#]' -q
        if [ $? -eq 0 ]; then
            ez_result_output 1 "Cannot create extension distribution, the following temporary files were found:"
            for tempfile in $TEMPFILES; do
                echo "`$SETCOLOR_FAILURE`$tempfile`$SETCOLOR_NORMAL`"
            done
            echo "The files must be removed before the extension distribution can be made"
            exit 1
        fi
    )
    ez_result_output $? "" || exit 1
}

function apply_filters
{
    function ez_skeleton_replace_variables
    {
        sed -e 's/\[ExtensionName\]/'"$EXTENSION_NAME"'/g'                      \
            -e 's/\[ExtensionIdentifier\]/'"$EXTENSION_IDENTIFIER"'/g'          \
            -e 's/\[ExtensionSummary\]/'"$EXTENSION_SUMMARY"'/g'                \
            -e 's/\[ExtensionLicense\]/'"$EXTENSION_LICENSE"'/g'                \
            -e 's/\[ExtensionPublishVersion\]/'"$EXTENSION_PUBLISH_VERSION"'/g' \
            -e 's/\[ExtensionArchiveName\]/'"$EXTENSION_ARCHIVE_NAME"'/g'       \
            -e 's/\[ExtensionVersion\]/'"$EXTENSION_VERSION"'/g'                \
            -e 's/\[PHPVersion\]/'"$EXTENSION_PHP_VERSION"'/g'                  \
            -e 's/\[Timestamp\]/'`date "+%s"`'/g'                               \
            -e 's/\[Host\]/'`uname -n`'/g'
    }

    echo -n "Applying filters"
    (cd $DEST
        for file in $EXTENSION_FILTER_FILES; do
            cat "$file" | ez_skeleton_replace_variables > "$file.1"
            if [ $? -ne 0 ]; then
                rm -f "$file.1"
                ez_result_output 1 "Failed to apply filter to `ez_color_file $file`"
                exit 1
            fi
            mv -f "$file.1" "$file"
        done
    )
    ez_result_output $? "" || exit 1
}

function create_extension_archive
{
    if [ "$BUILD_SNAPSHOT" == "1" ]; then
        EXTENSION_FILE_PREFIX="$EXTENSION_ARCHIVE_NAME""-extension-$EXTENSION_VERSION-build$CURRENT_BUILD_NUMBER"
    else
        EXTENSION_FILE_PREFIX="$EXTENSION_ARCHIVE_NAME""-extension-$EXTENSION_VERSION"
    fi
    EXTENSION_TGZFILE="$EXTENSION_FILE_PREFIX.tar.gz"
    EXTENSION_ZIPFILE="$EXTENSION_FILE_PREFIX.zip"
    EXTENSION_TBZFILE="$EXTENSION_FILE_PREFIX.tar.bz2"

    echo -n "Creating extension archive `ez_color_file $EXTENSION_TGZFILE`"
    (cd "$OUTPUT_DIR"
        rm -f "$EXTENSION_TGZFILE"
        tar cfz "$EXTENSION_TGZFILE" "$EXTENSION_IDENTIFIER")
    ez_result_output $? "Failed to package extension" || exit 1
    echo -n "Creating extension archive `ez_color_file $EXTENSION_TBZFILE`"
    (cd "$OUTPUT_DIR"
        rm -f "$EXTENSION_TBZFILE"
        tar cfj "$EXTENSION_TBZFILE" "$EXTENSION_IDENTIFIER")
    ez_result_output $? "Failed to package extension" || exit 1
    echo -n "Creating extension archive `ez_color_file $EXTENSION_ZIPFILE`"
    (cd "$OUTPUT_DIR"
        rm -f "$EXTENSION_ZIPFILE"
        zip -9 -r -q "$EXTENSION_ZIPFILE" "$EXTENSION_IDENTIFIER")
    ez_result_output $? "Failed to package extension" || exit 1
    rm -rf "$EXTENSION_IDENTIFIER"
}

function clean_dirs_up
{
    rm -rf "$TMP_DIR"
}


## main ################################################

. bin/shell/common.sh
. bin/shell/distcommon.sh

# "Declare" all the variables used in the script.
NAME="ezpublish"
DEST_ROOT="/tmp/ez-$USER"
TMP_DIR="$DEST_ROOT-extension"
OUTPUT_DIR="$DEST_ROOT/$NAME-$VERSION-extensions" # version is taken from common.sh
EXTENSION_TGZFILE=''
USE_SVN='' # Set to "1" to fetch extension from SVN instead of ./extension/<EXTENSION-IDENTIFIER> directory.
DIST_TYPE='full' # used by scan_dir function
extension=''
unset DEST_ROOT NAME

# Do the work.
parse_cl_parameters $*
echo "Working with extension `ez_color_url $extension`"
create_destination_dirs
export_extension
parse_extension_dist_sh
copy_files_marked_for_distribution
find_svn_dirs
find_temporary_files
apply_filters
create_extension_archive
clean_dirs_up
echo "Extension `ez_color_em $EXTENSION_IDENTIFIER` has been packaged to `ez_color_dir $OUTPUT_DIR/$EXTENSION_TGZFILE`"
echo "Extension `ez_color_em $EXTENSION_IDENTIFIER` has been packaged to `ez_color_dir $OUTPUT_DIR/$EXTENSION_TBZFILE`"
echo "Extension `ez_color_em $EXTENSION_IDENTIFIER` has been packaged to `ez_color_dir $OUTPUT_DIR/$EXTENSION_ZIPFILE`"
# Let makedist.sh know about name and identifier of the package we have created.
[ -f .ez.extension-name ] && echo -n $EXTENSION_TGZFILE > .ez.extension-name
[ -f .ez.extension-id ] && echo -n $EXTENSION_IDENTIFIER > .ez.extension-id
exit 0

