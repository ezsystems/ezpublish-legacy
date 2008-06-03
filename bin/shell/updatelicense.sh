#!/bin/sh
function show_help
{
    echo
    echo "The script will update license and headers."
    echo
    echo "Usage: $0 [options] [SQLFILE]..."
    echo
    echo "Options: -h"
    echo "         --help                     This message"
    echo "         --license-type=<type>      What license should be used: gpl(default), pul_v1"
    echo "         --licenses-dir=<dir>       Location of licenses files"
    echo "         --target-dir=<dir>         Location of phps to update"
    #echo "         --name=<name>              Name: eZ Publish"
    echo "         --revision=<rev>           Revision number: 12345"
    echo "         --version=<version>        Version: 3.7.3"
    echo
}

function parse_cl_parameters
{
    [ -z "$*" ] && show_help
    for arg in $*; do
        case $arg in
        --help|-h)
            show_help
            exit 1
            ;;
        --license-type*)
            if echo $arg | grep -e "--license-type=" >/dev/null; then
                LICENSE_TYPE=`echo $arg | sed 's/--license-type=//'`
            fi
            ;;

        --licenses-dir*)
            if echo $arg | grep -e "--licenses-dir=" >/dev/null; then
                LICENSES_DIR=`echo $arg | sed 's/--licenses-dir=//'`
            fi
            ;;

        --target-dir*)
            if echo $arg | grep -e "--target-dir=" >/dev/null; then
                DEST_DIR=`echo $arg | sed 's/--target-dir=//'`
            fi
            ;;

        --revision*)
            if echo $arg | grep -e "--revision=" >/dev/null; then
                REV=`echo $arg | sed 's/--revision=//'`
            fi
            ;;

        --version*)
            if echo $arg | grep -e "--version=" >/dev/null; then
                VERSION=`echo $arg | sed 's/--version=//'`
            fi
            ;;
        #--name*)
        #    if echo $arg | grep -e "--name=" >/dev/null; then
        #        NAME=`echo $arg | sed 's/--name=//'`
        #    fi
        #    ;;

        --*)
            if [ $? -ne 0 ]; then
                echo "$arg: unknown long option specified"
                echo
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            fi
            ;;
        -*)
            if [ $? -ne 0 ]; then
                echo "$arg: unknown option specified"
                echo
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            fi
            ;;
        *)
                echo "Type '$0 --help\` for a list of options to use."
                exit 1
            ;;
        esac;
    done
}
# Fetches notice content from file
function fetch_notice()
{
    local LICENSE_NOTICE_FILE=$1
    local NOTICE_TMP_FILE=$2
    fetch_license_content $LICENSE_NOTICE_FILE $NOTICE_TMP_FILE 'NOTICE: >' 1
    NOTICE=$CONTENT
}

# Fetches software license content from file
function fetch_license()
{
    local LICENSE_NOTICE_FILE=$1
    local LICENSE_TMP_FILE=$2
    fetch_license_content $LICENSE_NOTICE_FILE $LICENSE_TMP_FILE 'SOFTWARE LICENSE:' 0
    LICENSE=$CONTENT
}

# Fetches content from file
function fetch_license_content()
{
    local LICENSE_NOTICE_FILE=$1
    local TMP_FILE=$2
    local STRING=$3
    local PRINT_NEXT=$4
    if [ "$PRINT_NEXT" == "1" ]; then
        sed -e 's/$/\\n\\/; ' "$LICENSE_NOTICE_FILE" >"$LICENSE_NOTICE_FILE.tmp"
        awk "/#/{next} \
             /$STRING/ {print( \"//\", \$0 );
                        print_next = 1;
                        next
                       } \
             {if ( print_next )
                  {
                    print( \"//\", \$0)
                  }
             } \
             END { print \"//\" }\
             " \
             "$LICENSE_NOTICE_FILE.tmp" > "$TMP_FILE"
        rm -f  "$LICENSE_NOTICE_FILE.tmp"
        CONTENT=`sed -e 's/\s*\\\n\\\/\\\n\\\/; ' "$TMP_FILE"`
        rm -f $TMP_FILE
    else
        CONTENT=`grep -i "$STRING" "$LICENSE_NOTICE_FILE"`
        CONTENT='// '$CONTENT
    fi
}

# Updates ezinfo/about.php file,
# Changes license content
function update_ezinfo_license()
{
    local EZINFO=$1
    local LICENSE_INFO=$2
    local BEGIN_LICENSE_BLOCK="## BEGIN LICENSE INFO ##"
    local END_LICENSE_BLOCK="## END LICENSE INFO ##"
    echo '"' > $LICENSE_INFO.tmp
    sed -e 's/"/\\"/; ' "$LICENSE_INFO" >> "$LICENSE_INFO.tmp"
    echo '";' >> $LICENSE_INFO.tmp
    sed -i -e '/'"$BEGIN_LICENSE_BLOCK"'/,/'"$END_LICENSE_BLOCK"'/{
    /'"$END_LICENSE_BLOCK"'/r '$LICENSE_INFO.tmp'
    d
    }' "$EZINFO";
    rm -f  "$LICENSE_INFO.tmp"
}

# Updates ezinfo/copyright.php file,
# Changes copyright and license content
function update_ezinfo_copyright()
{
    local EZINFO=$1
    local COPYRIGHT_INFO=$2
    local BEGIN_COPYRIGHT_BLOCK="## BEGIN COPYRIGHT INFO ##"
    local END_COPYRIGHT_BLOCK="## END COPYRIGHT INFO ##"
    echo '"' > $COPYRIGHT_INFO.tmp
    sed -e 's/"/\\"/; ' "$COPYRIGHT_INFO" >> "$COPYRIGHT_INFO.tmp"
    echo '";' >> $COPYRIGHT_INFO.tmp
    sed -i -e '/'"$BEGIN_COPYRIGHT_BLOCK"'/,/'"$END_COPYRIGHT_BLOCK"'/{
    /'"$END_COPYRIGHT_BLOCK"'/r '$COPYRIGHT_INFO.tmp'
    d
    }' "$EZINFO";
    rm -f  "$COPYRIGHT_INFO.tmp"
}

## main ################################################

# "Declare" all the variables used in the script.
VERSION=""
REV=""
LICENSE_TYPE='gpl'
LICENSE_FILE="LICENSE"
LICENSE_NOTICE_FILE="notice.yaml"
LICENSE_NOTICE_TMP_FILE="license-notice.tmp"
BEGIN_LICENSE_BLOCK="## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##"
END_LICENSE_BLOCK="## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##"
SOFTWARE_NAME="SOFTWARE NAME: eZ Publish"

COPYRIGHT="// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS"

# Do the work.
parse_cl_parameters $*

EXCLUDE_DIR="extension"
LICENSE_DIR="$LICENSES_DIR/$LICENSE_TYPE"

LICENSE_FILE="$LICENSE_DIR/$LICENSE_FILE"
LICENSE_INFO="$LICENSE_DIR/ezinfo.txt"
COPYRIGHT_INFO="$LICENSE_DIR/ezcopyright.txt"
LICENSE_NOTICE_FILE="$LICENSE_DIR/$LICENSE_NOTICE_FILE"
LICENSE_NOTICE_TMP_FILE="$LICENSE_DIR/$LICENSE_NOTICE_TMP_FILE"

EZINFO_FILE="$DEST_DIR/kernel/ezinfo/about.php"
EZINFO_COPYRIGHT_FILE="$DEST_DIR/kernel/ezinfo/copyright.php"
CONTRIBUTORS_TMP_FILE='contributors.tmp'
CONTRIBUTORS_DIR="$DEST_DIR/var/storage/contributors"
THIRDSOFT_FILE="$DEST_DIR/var/storage/third_party_software.php"

# Prepare contributor`s dir
[ -d "$CONTRIBUTORS_DIR" ] && rm -rf "$CONTRIBUTORS_DIR"
mkdir -p "$CONTRIBUTORS_DIR"

RELEASE="// SOFTWARE RELEASE: $VERSION"
BUILD_REV="// BUILD VERSION: $REV"
NOTICE_TMP_FILE="$LICENSE_DIR/notice.tmp"
MAXLENGTH="35"
if [ ! -e "$LICENSE_FILE" ]; then
    echo "License file '$LICENSE_FILE' doesn't exist."
    exit 1;
fi

if [ ! -e "$LICENSE_NOTICE_FILE" ]; then
    echo "License notice file '$LICENSE_NOTICE_FILE' doesn't exist."
    exit 1;
fi

echo "LICENSE_FILE: '$LICENSE_FILE'"
echo "LICENSE_NOTICE_FILE: '$LICENSE_NOTICE_FILE'"
echo "LICENSE_NOTICE_TMP_FILE: '$LICENSE_NOTICE_TMP_FILE'"
echo "DEST_DIR: '$DEST_DIR'"
echo "REV: '$REV'"
echo "VERSION: '$VERSION'"

# Remove third-party software
rm -f "$THIRDSOFT_FILE"
# Update ezinfo license
update_ezinfo_license "$EZINFO_FILE" "$LICENSE_INFO"
update_ezinfo_copyright "$EZINFO_COPYRIGHT_FILE" "$COPYRIGHT_INFO"

OLDIFS=$IFS

# Init notice and license
fetch_notice $LICENSE_NOTICE_FILE $NOTICE_TMP_FILE
fetch_license $LICENSE_NOTICE_FILE $NOTICE_TMP_FILE

# Find files
FILES=`find "$DEST_DIR" \( -name "*.js" -o -name "*.php" \)`

# We shoud exclude some dirs (eg extension) from searching
#FILES=`find "$DEST_DIR" -type d \( -name "$EXCLUDE_DIR" \) -prune -o -type f \( -name "*.js" -o -name "*.php" \)`

for FILE in $FILES; do
#    echo "Processing file: $FILE"

# awk:
# if 'current_line' is greater then 28 - output line without modifications. We want to proccess just header and don't
#     want to process rest of the file.
# if 'current_line' doesn't start with '//' then it's useful code - output without modifications.
# if 'current_line' starts with '// ## BEGIN' - remove it
# if 'SOFTWARE NAME' is 'eZ Publish' then we're allowed to change release number
# if 'SOFTWARE NAME' is 'eZ Publish' or 'Online Editor' then we're allowed to change license notes.
# if 'current_line' is 'SOFTWARE RELEASE' and we're allowed to change it - change it
# if 'current_line' is 'SOFTWARE LICENSE' and we're allowed to change it - change it
# if 'current_line' is 'NOTICE: >' and we're allowed to change it - print new notice and start removing current notice,
#    assuming that '// ## END' immediataly follows notice block, so we'll remove everything between 'NOTICE: >' and
#    '// ## END')
# if we reach '// ## END' remove that line and stop removing notice.
# if non of conditions was triggered - just output current line.

    if [ -d "$FILE" ]; then
        continue
    fi

    awk "{if ( NR > $MAXLENGTH )
              {
                print;
                next
              }
         } \
         {if ( \$1 != \"//\" )
              {
                print;
                remove_notice = 0;
                next
              }
         } \
         /\/\/ ## BEGIN/ {next} \
         /SOFTWARE NAME: eZ Publish/ {can_change_license = 1;
                                      can_change_release = 1;
                                      can_change_revision = 1;
                                      can_change_copyright = 1;
                                      print;
                                      next
                                     } \
         /SOFTWARE NAME: eZ Online Editor/ {can_change_license = 1;
                                            can_change_copyright = 1;
                                            print;
                                            next
                                           } \
         /SOFTWARE NAME: eZ Paypal Payment Gateway/ {can_change_license = 1;
                                                     can_change_copyright = 1;
                                                     print;
                                                     next
                                                     } \
         /SOFTWARE RELEASE:/ {if ( can_change_release )
                                  {
                                    print \"$RELEASE\";
                                    next
                                   }
                             } \
         /BUILD VERSION:/ { next } \
         {if ( can_change_revision )
              {
                can_change_revision = 0;
                print \"$BUILD_REV\"
              }
         } \
         /SOFTWARE LICENSE:/ {if ( can_change_license )
                                  {
                                    print \"$LICENSE\";
                                    next
                                  }
                             } \
         /COPYRIGHT NOTICE:/ {if ( can_change_copyright )
                                  {
                                    print \"$COPYRIGHT\";
                                    next
                                  }
                             } \
         /NOTICE: >/ {if ( can_change_license )
                          {
                            remove_notice = 1;
                            print \"$NOTICE\"
                          }
                     } \
         /\/\/ ## END/ {remove_notice = 0;
                        next
                       } \
         {if ( remove_notice ) { next }} \
         {print}" \
        "$FILE" > "$FILE.tmp"

    cp -f "$FILE.tmp" "$FILE"
    rm -f "$FILE.tmp"

    # We should not parse contributors or third-party software if the $file is in $EXCLUDE_DIR
    if [ ${FILE//$EXCLUDE_DIR/''} != "$FILE" ]; then
       continue;
    fi

### BEGIN = Find contributors ======================================================

    awk "/CONTRIBUTORS:/ {i=split(\$0, Contributors, \"CONTRIBUTORS: \");
                          print (Contributors[2]);
                          next}" \
         "$FILE" > $CONTRIBUTORS_TMP_FILE

    # Parse contributors
    CONTRIBUTORS=`awk '{iopen = split( $0, NamesOpen, "{" );
                        OName = NamesOpen[iopen];
                        iclose = split( OName, NamesClose, "}" );
                        CName = NamesClose[1];
                        i = split( CName, Names, ", " );
                        {if ( i==1 )
                          {
                            result = CName;
                          }
                          else
                          {
                             for ( j=1; j<=i; j++ )
                             {
                                {if ( j==1 )
                                   {
                                     result = Names[j];
                                   }
                                }
                                {if ( ( j != 1 ) )
                                   {
                                     result = result"#"Names[j]
                                   }
                                }
                             }
                          }
                        }
                        }
                        END {print result}' $CONTRIBUTORS_TMP_FILE`

    rm -f "$CONTRIBUTORS_TMP_FILE"

    OLDIFS2=$IFS
    IFS="#"
    for CONTR in $CONTRIBUTORS; do
       # Strip spaces
       FILENAME=${CONTR// /''}".php"
       #FILENAME=${CONTR//[^a-zA-Z0-9]/'_'}".php"
       CONTR_NAME=${CONTR//\\/\\\\}
       CONTR_NAME=${CONTR_NAME//\'/\\\'}
       #CONTR_NAME=${CONTR_NAME//\$/\\\$}
       if [ ! -e "$CONTRIBUTORS_DIR/$FILENAME" ]; then
            echo "<?php
\$contributorSettings = array( 'name' => '$CONTR_NAME',
'files' => '" > "$CONTRIBUTORS_DIR/$FILENAME"
       fi

       echo ${FILE//$DEST_DIR\//''}"," >> "$CONTRIBUTORS_DIR/$FILENAME"
    done
    IFS=$OLDIFS2

### END = Find contributors =================================

### BEGIN = Find third party software ==========================

    SOFTWARE_NAME=`awk "/SOFTWARE NAME:/ {i = split( \\$0, Name, \"SOFTWARE NAME: \" );
                                          print ( Name[2] );
                          next}" \
         "$FILE"`

    if [ "$SOFTWARE_NAME" != "eZ publish" ] && [ "$SOFTWARE_NAME" != "eZ Publish" ] && [ "$SOFTWARE_NAME" != "" ]; then
        COPYRIGHT_NOTICE=`awk '{if ( get_new_line )
                                  {
                                    i = split( \$0, Name, "// " );
                                    print( Name[2] );
                                    ii = split( Name[2], Name2, " " );
                                    {if ( Name2[ii] == "\\\" )
                                       {
                                          get_new_line = 1;
                                       }
                                       else
                                       {
                                          get_new_line = 0
                                       }
                                    }
                                  }
                               } \
                              /COPYRIGHT NOTICE:/ {
                                                    i = split(\$0, Name, "COPYRIGHT NOTICE: " );
                                                    print( Name[2] );
                                                    ii = split( Name[2], Name2, " " );
                                                    {if ( Name2[ii] == "\\\" )
                                                        {
                                                            get_new_line = 1;
                                                        }
                                                    }
                                                  }' \
                            "$FILE"`

       SOFTWARE_LICENSE=`awk "/SOFTWARE LICENSE:/ {i=split(\\$0, Name, \"SOFTWARE LICENSE: \");
                              print (Name[2]);
                              }" "$FILE"`

       if [ ! -e "$THIRDSOFT_FILE" ]; then
            echo "<?php
\$thirdPartySoftware = array( "> "$THIRDSOFT_FILE"
       fi
       THIRD_SOFTWARE="$SOFTWARE_NAME : $SOFTWARE_LICENSE. $COPYRIGHT_NOTICE"
       THIRD_SOFTWARE=${THIRD_SOFTWARE//\\
       /''}
       THIRD_SOFTWARE=${THIRD_SOFTWARE//\\/\\\\}
       THIRD_SOFTWARE=${THIRD_SOFTWARE//\'/\\\'}
       echo "'$THIRD_SOFTWARE'," >> $THIRDSOFT_FILE

    fi
### END = Find third party software =======================

done
#OLD=$IFS
# Find all contrib files
IFS='
'
FILES=`find "$CONTRIBUTORS_DIR" -name "*"`
for FILE in $FILES; do
    if [ ! -d "$FILE" ]; then
    echo "' );
?>" >> $FILE
    fi
done

# Finish third-party sowaftware file
if [ -e "$THIRDSOFT_FILE" ]; then
    echo " );
?>" >> "$THIRDSOFT_FILE"

fi
IFS=$OLDIFS

# update lincense file
echo "Coping license file '$LICENSE_FILE'"
cp "$LICENSE_FILE" "$DEST_DIR/"
