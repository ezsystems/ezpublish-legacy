#!/bin/bash

# column to start "[  OK  ]" label in
RES_COL=60
# terminal sequence to move to that column. You could change this
# to something like "tput hpa ${RES_COL}" if your terminal supports it
MOVE_TO_COL="echo -en \\033[${RES_COL}G"
# terminal sequence to set color to a 'success' color (currently: green)
SETCOLOR_SUCCESS="echo -en \\033[1;32m"
# terminal sequence to set color to a 'failure' color (currently: red)
SETCOLOR_FAILURE="echo -en \\033[1;31m"
# terminal sequence to set color to a 'warning' color (currently: yellow)
SETCOLOR_WARNING="echo -en \\033[1;33m"

# terminal sequence to set color to a 'file' color (currently: red)
SETCOLOR_FILE="echo -en \\033[1;31m"
# terminal sequence to set color to a 'directory' color (currently: blue)
SETCOLOR_DIR="echo -en \\033[1;34m"

# terminal sequence to reset to the default color.
SETCOLOR_NORMAL="echo -en \\033[0;39m"
