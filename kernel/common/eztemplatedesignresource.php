<?php
//
// Definition of eZTemplatedesignresource class
//
// Created on: <14-Sep-2002 15:37:17 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztemplatedesignresource.php
*/

/*!
  \class eZTemplatedesignresource eztemplatedesignresource.php
  \brief Handles template file loading with override support

*/

include_once( "lib/eztemplate/classes/eztemplatefileresource.php" );
include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateDesignResource extends eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "design".
    */
    function eZTemplateDesignResource( $name = "design" )
    {
        $this->eZTemplateFileResource( $name );
        $this->Keys = array();
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
    function handleResource( &$tpl, &$text, &$tstamp, &$path, $method, &$extraParameters )
    {
        $ini =& eZINI::instance();
        $std_base = $ini->variable( "DesignSettings", "StandardDesign" );
        $site_base = $ini->variable( "DesignSettings", "SiteDesign" );

        $matches = array();
        $matches[] = array( "file" => "design/$site_base/override/templates/$path",
                            "type" => "override" );
        $matches[] = array( "file" => "design/$std_base/override/templates/$path",
                            "type" => "override" );
        $matches[] = array( "file" => "design/$site_base/templates/$path",
                            "type" => "normal" );
        $matches[] = array( "file" => "design/$std_base/templates/$path",
                            "type" => "normal" );

        $match_keys = $this->Keys;
        $matchedKeys = array();

        if ( is_array( $extraParameters ) and
             isset( $extraParameters['ezdesign:keys'] ) )
        {
            $match_keys = array_merge( $match_keys, $extraParameters['ezdesign:keys'] );
        }

        $match = null;
        foreach ( $matches as $tpl_match )
        {
            $tpl_path = $tpl_match["file"];
            $tpl_type = $tpl_match["type"];
            if ( $tpl_type == "normal" )
            {
                if ( file_exists( $tpl_path ) )
                {
                    $match = $tpl_match;
                    break;
                }
            }
            else if ( $tpl_type == "override" )
            {
                if ( count( $match_keys ) == 0 )
                    continue;
                $tpl_dir = false;
                if ( preg_match( "#^(.+)/(.+)(\.tpl)$#", $tpl_path, $regs ) )
                {
                    $tpl_dir = $regs[1] . "/" . $regs[2];
                }
                $foundOverrideFile = false;
                if ( file_exists( $tpl_dir ) and
                     is_dir( $tpl_dir ) ) // Do advanced match with multiple keys
                {
                    $hd = opendir( $tpl_dir );
                    $key_regex = "([0-9]*)";
                    if ( count( $match_keys ) > 1 )
                        $key_regex .= str_repeat( ",([0-9]*)", count( $match_keys ) - 1 );

                    while( ( $file = readdir( $hd ) ) !== false )
                    {
                        if ( $file == "." or
                             $file == ".." or
                             $file[0] == "." )
                            continue;
                        if ( !preg_match( "#^$key_regex\.tpl$#", $file, $regs ) )
                            continue;
//                     eZDebug::writeNotice( "Matching file $file"  );
                        $key_index = 0;
                        $found = true;
                        for ( $i = 1; $i < count( $regs ) and $key_index < count( $match_keys ); ++$i )
                        {
                            $key = $match_keys[$key_index];
                            $key_val = $key[1];
//                         eZDebug::writeNotice( "Matching key $key_val" . '=' . $regs[$i] );
                            if ( is_numeric( $key_val ) and
                                 is_numeric( $regs[$i] ) )
                            {
                                if ( $regs[$i] != $key_val )
                                {
                                    $found = false;
                                    break;
                                }
                            }
                            else if ( $regs[$i] != "" )
                            {
                                $found = false;
                                break;
                            }
                            $matchedKeys[$key] = $key_val;
                            ++$key_index;
                        }
                        if ( !$found )
                            continue;
                        $match = $tpl_match;
                        $match["file"] = "$tpl_dir/$file";
                        $foundOverrideFile = true;
//                         eZDebug::writeNotice( "Multi match found, using override " . $match["file"]  );
                        break;
                    }
                    closedir( $hd );
                }
                if ( !$foundOverrideFile ) // Check for dir/filebase_keyname_keyid.tpl, eg. content/view_section_1.tpl
                {
                    preg_match( "#^(.+)/(.+)(\.tpl)$#", $tpl_path, $regs );
                    foreach ( $match_keys as $match_key )
                    {
                        $match_key_name = $match_key[0];
                        $match_key_val = $match_key[1];
                        $file = $regs[1] . "/" . $regs[2] . "_$match_key_name" . "_$match_key_val" . $regs[3];
                        if ( file_exists( $file ) )
                        {
                            $match = $tpl_match;
                            $match["file"] = $file;
                            $foundOverrideFile = true;
                            $matchedKeys[$match_key_name] = $match_key_val;
//                             eZDebug::writeNotice( "Match found, using override " . $match["file"]  );
                            break;
                        }
                    }
                }
                if ( $match !== null )
                    break;
            }
        }
        if ( $match === null )
            return false;

        $file = $match["file"];

        $usedKeys = array();
        foreach ( $match_keys as $match_key )
        {
            $usedKeys[$match_key[0]] = $match_key[1];
        }
        $extraParameters['ezdesign:used_keys'] = $usedKeys;
        $extraParameters['ezdesign:matched_keys'] = $matchedKeys;
        $tpl->setVariable( 'used', $usedKeys, 'DesignKeys' );
        $tpl->setVariable( 'matched', $usedKeys, 'DesignKeys' );
        return eZTemplateFileResource::handleResource( $tpl, $text, $tstamp, $file, $method, $extraParameters );
    }

    /*!
     Sets the override keys to \a $keys, if some of the keys already exists they are overriden
     by the new keys.
     \sa clearKeys
    */
    function setKeys( $keys )
    {
        $this->Keys = array_merge( $this->Keys, $keys );
    }

    /*!
     Removes all override keys.
     \sa setKeys
    */
    function clearKeys()
    {
        $this->Keys = array();
    }

    /*!
     \return the unique instance of the design resource.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZTemplateDesignResourceInstance"];
        if ( get_class( $instance ) != "eztemplatedesignresource" )
        {
            $instance = new eZTemplateDesignResource();
        }
        return $instance;
    }

    var $Keys;
}

?>
