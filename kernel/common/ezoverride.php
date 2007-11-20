<?php
//
// Definition of eZOverride class
//
// Created on: <31-Oct-2002 09:18:07 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezoverride.php
*/

/*!
  \class eZOverride ezoverride.php
  \brief The class eZOverride does

*/

class eZOverride
{
    static function selectFile( $matches, $matchKeys, &$matchedKeys, $regexpMatch )
    {
        $match = null;
        foreach ( $matches as $templateMatch )
        {
            $templatePath = $templateMatch["file"];
            $templateType = $templateMatch["type"];
            if ( $templateType == "normal" )
            {
                if ( file_exists( $templatePath ) )
                {
                    $match = $templateMatch;
                    break;
                }
            }
            else if ( $templateType == "override" )
            {
                $foundOverrideFile = false;
                if ( file_exists( $templatePath ) )
                {
                    $match = $templateMatch;
                    $match["file"] = $templatePath;
                    $foundOverrideFile = true;
                }
                if ( !$foundOverrideFile and
                     count( $matchKeys ) == 0 )
                    continue;
                if ( !$foundOverrideFile and
                     preg_match( $regexpMatch, $templatePath, $regs ) )// Check for dir/filebase_keyname_keyid.tpl, eg. content/view_section_1.tpl
                {
                    foreach ( $matchKeys as $matchKeyName => $matchKeyValue )
                    {
                        $file = $regs[1] . "/" . $regs[2] . "_$matchKeyName" . "_$matchKeyValue" . $regs[3];
                        if ( file_exists( $file ) )
                        {
                            $match = $templateMatch;
                            $match["file"] = $file;
                            $foundOverrideFile = true;
                            $matchedKeys[$matchKeyName] = $matchKeyValue;
                            break;
                        }
                    }
                }
                if ( $match !== null )
                    break;
            }
        }
        return $match;
    }
}

?>
