<?php
/**
 * File containing the eZOverride class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Not currently used for anything
 * @deprecated 4.5
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
