#!/bin/bash

VERSION="3.4.0beta1"
VERSION_ONLY="3.4"
VERSION_STATE="beta1"
VERSION_PREVIOUS="3.4.0alpha4"
VERSION_BRANCH="$VERSION_ONLY"
VERSION_NICK="$VERSION"

# URLs for the various repositories
REPOSITORY_BASE_URL="http://zev.ez.no/svn/nextgen"
TR_REPOSITORY_BASE_URL="http://zev.ez.no/svn/translation"
# This needs to be set correctly when a new branch is created
# e.g. stable/3.4 stable/3.5
REPOSITORY_BRANCH_PATH="trunk"
REPOSITORY_STABLE_BRANCH_PATH="stable"

TRANSLATION_URL="$TR_REPOSITORY_BASE_URL/$REPOSITORY_BRANCH_PATH/translations"
LOCALE_URL="$TR_REPOSITORY_BASE_URL/$REPOSITORY_BRANCH_PATH/locale"


VERSIONS="2.9 3.0 3.1 3.2 3.3"
STABLE_VERSIONS="3.0 3.1 3.2 3.3"
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
