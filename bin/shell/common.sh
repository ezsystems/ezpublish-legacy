#!/bin/bash

VERSION="3.5.0rc2"
VERSION_ONLY="3.5"
VERSION_STATE="rc2"
VERSION_PREVIOUS="3.5.0rc1"
VERSION_BRANCH="$VERSION_ONLY"
VERSION_NICK="$VERSION"
VERSION_STABLE="3.4.4"
DEVELOPMENT="true"

# URLs for the various repositories
REPOSITORY_BASE_URL="http://zev.ez.no/svn/nextgen"
TR_REPOSITORY_BASE_URL="http://zev.ez.no/svn/translation"
# This needs to be set correctly when a new branch is created
# e.g. stable/3.4 stable/3.5
REPOSITORY_BRANCH_PATH="trunk"
REPOSITORY_STABLE_BRANCH_PATH="stable"

CURRENT_URL="$REPOSITORY_BASE_URL/$REPOSITORY_BRANCH_PATH"
TRANSLATION_URL="$TR_REPOSITORY_BASE_URL/$REPOSITORY_BRANCH_PATH/translations"
LOCALE_URL="$TR_REPOSITORY_BASE_URL/$REPOSITORY_BRANCH_PATH/locale"


VERSIONS="2.9 3.0 3.1 3.2 3.3 3.4"
STABLE_VERSIONS="3.0 3.1 3.2 3.3 3.4"
ALL_VERSIONS="$VERSIONS $VERSION_ONLY"
ALL_STABLE_VERSIONS="$STABLE_VERSIONS $VERSION_ONLY"


RES_COL=60
# terminal sequence to move to that column. You could change this
# to something like "tput hpa ${RES_COL}" if your terminal supports it
MOVE_TO_COL="echo -en \\033[${RES_COL}G"
# terminal sequence to set color to a 'success' color (currently: green)
SETCOLOR_SUCCESS="echo -en \\033[1;32m"
# terminal sequence to set color to a 'failure' color (currently: red)
SETCOLOR_FAILURE="echo -en \\033[1;31m"
# terminal sequence to set color to a 'warning' color (currently: magenta)
SETCOLOR_WARNING="echo -en \\033[1;35m"

# terminal sequence to set color to a 'file' color (currently: default)
SETCOLOR_FILE="echo -en \\033[1;30m"
# terminal sequence to set color to a 'directory' color (currently: blue)
SETCOLOR_DIR="echo -en \\033[1;34m"
# terminal sequence to set color to a 'executable' color (currently: green)
SETCOLOR_EXE="echo -en \\033[1;32m"
# terminal sequence to set color to a 'url' color (currently: bold black)
SETCOLOR_URL="echo -en \\033[1;35m"
# terminal sequence to set color to a 'header1' color (currently: magenta background + yellow color)
SETCOLOR_HEADER1="echo -en \\033[1;43m\\033[1;33m"

# terminal sequence to set color to a 'comment' color (currently: gray)
SETCOLOR_COMMENT="echo -en \\033[1;30m"
# terminal sequence to set color to a 'emphasize' color (currently: bold black)
SETCOLOR_EMPHASIZE="echo -en \\033[1;38m"
# terminal sequence to set color to a 'new' color (currently: bold black)
SETCOLOR_NEW="echo -en \\033[1;38m"


# terminal sequence to reset to the default color.
SETCOLOR_NORMAL="echo -en \\033[0;39m"

# Position handling
POSITION_STORE="echo -en \\033[s"
POSITION_RESTORE="echo -en \\033[u"

# Moves the console cursor to a given column
# If the column is not specified it uses the default
# which is defined in $RES_COL
# Syntax:
# ez_move_to_col [col]
# Usage:
# ez_move_to_col
# ez_move_to_col 40
function ez_move_to_col
{
    local col
    if [ $# -gt 0 ]; then
	col=$1
    else
	col=$RES_COL
    fi
    echo -en "\\033[${col}G"
}

# Stores the current console position which can be restored
# with ez_restore_pos later on.
# Syntax:
# ez_store_pos
# Usage:
# ez_store_pos
function ez_store_pos
{
    echo -en "\\033[s"
}

# Restores a previously stored console position
# Syntax:
# ez_restore_pos
# Usage:
# ez_restore_pos
function ez_restore_pos
{
    echo -en "\\033[u"
}

# Colorizes the input string as an success
# Syntax:
# ez_color_ok <string>
# Usage:
# ez_color_ok 'Success'
function ez_color_ok
{
    echo -n "`$SETCOLOR_SUCCESS`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string as an failure
# Syntax:
# ez_color_fail <string>
# Usage:
# ez_color_fail 'Failed'
function ez_color_fail
{
    echo -n "`$SETCOLOR_FAILURE`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string as an warning
# Syntax:
# ez_color_warn <string>
# Usage:
# ez_color_warn 'Warning'
function ez_color_warn
{
    echo -n "`$SETCOLOR_WARNING`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string as a file
# Syntax:
# ez_color_file <string>
# Usage:
# ez_color_file myfile.txt
function ez_color_file
{
    echo -n "`$SETCOLOR_FILE`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string as a dir
# Syntax:
# ez_color_dir <string>
# Usage:
# ez_color_dir share/locale
function ez_color_dir
{
    echo -n "`$SETCOLOR_DIR`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string as a exe
# Syntax:
# ez_color_exe <string>
# Usage:
# ez_color_exe ls
function ez_color_exe
{
    echo -n "`$SETCOLOR_EXE`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string with emphasize
# Syntax:
# ez_color_em <string>
# Usage:
# ez_color_em 'some text'
function ez_color_em
{
    echo -n "`$SETCOLOR_EMPHASIZE`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string to make it look like a comment
# Syntax:
# ez_color_comment <string>
# Usage:
# ez_color_comment 'some text'
function ez_color_comment
{
    echo -n "`$SETCOLOR_COMMENT`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string to make it look new
# Syntax:
# ez_color_new <string>
# Usage:
# ez_color_new 'some text'
function ez_color_new
{
    echo -n "`$SETCOLOR_NEW`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string to make it look like a URL
# Syntax:
# ez_color_url <string>
# Usage:
# ez_color_url 'http://ez.no'
function ez_color_url
{
    echo -n "`$SETCOLOR_URL`$1`$SETCOLOR_NORMAL`"
}

# Colorizes the input string to make it look like a header (1)
# Syntax:
# ez_color_h1 <string>
# Usage:
# ez_color_h1 'A nice header'
function ez_color_h1
{
    echo -n "`$SETCOLOR_HEADER1` $1 `$SETCOLOR_NORMAL`"
}

# Prints Success or Failure at a given column and prints the message
# Syntax:
# ez_result_output <status> <failure-text>
# status - 0 for OK, otherwise failure
# Usage:
# ez_result_output $? "Some failure text"
function ez_result_output
{
    if [ $1 -ne 0 ]; then
	echo "`ez_move_to_col``ez_color_fail '[ Failure ]'`"
	echo "$2"
	return 1
    fi
    echo "`ez_move_to_col``ez_color_ok '[ Success ]'`"
    return 0
}

# Prints Success or Failure at a given column and prints the contents of the file
# Syntax:
# ez_result_file <status> <failure-file>
# status - 0 for OK, otherwise failure
# Usage:
# ez_result_file $? error.log
function ez_result_file
{
    if [ $1 -ne 0 ]; then
	echo "`ez_move_to_col``ez_color_fail '[ Failure ]'`"
	cat "$2"
	return 1
    fi
    echo "`ez_move_to_col``ez_color_ok '[ Success ]'`"
    return 0
}
