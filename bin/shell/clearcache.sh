#!/bin/sh

CONTENT_CACHEDIR="var/cache/content"


if [ -d "$CONTENT_CACHEDIR" ]; then
    echo "Removing cache files in $CONTENT_CACHEDIR"
    rm -rf "$CONTENT_CACHEDIR"
fi
