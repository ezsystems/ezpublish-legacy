<?php
//
// Definition of eZTextInputParser class
//
// Created on: <17-Jul-2002 13:02:32 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*!
  \class eZTextInputParser eztextinputparser.php

*/

define( "EZ_INPUT_CHUNK_TEXT", 1 );
define( "EZ_INPUT_CHUNK_TAG", 2 );

class eZTextInputParser
{
    /*!

    */
    function eZTextInputParser()
    {

    }

    /*!
     Will parse the input text and create an array of the input.
     False will be returned if the parsing
    */
    function &parseText( &$text )
    {
        $returnArray = array();
        $pos = 0;

        while ( $pos < strlen( $text ) )
        {
            // find the next tag
            $tagStart = strpos( $text, "<", $pos );

            if ( $tagStart !== false )
            {
                if ( ( $tagStart - $pos ) >= 1 )
                {
                    $textChunk =& substr( $text, $pos, $tagStart - $pos );
                    $pos += $tagStart - $pos;


                    if ( strlen( trim( $textChunk ) ) != 0 )
                    {
                        $returnArray[] = array( "Type" => EZ_INPUT_CHUNK_TEXT,
                                                "Text" => $textChunk,
                                                "TagName" => "#text" );

                        eZDebug::writeNotice( $textChunk, "New text chunk in input" );
                    }
                }
                // get the tag
                $tagEnd = strpos( $text, ">", $pos );
                $tagChunk =& substr( $text, $pos, $tagEnd - $pos + 1 );
                $tagName =& preg_replace( "#^\<(.+)?(\s.*|\>)#m", "\\1", $tagChunk );

                // check for end tag
                if ( $tagName[0] == "/" )
                {
                    print( "endtag" );
                }

                $returnArray[] = array( "Type" => EZ_INPUT_CHUNK_TAG,
                                        "TagName" => $tagName,
                                        "Text" => $tagChunk,
                                        );

                $pos += $tagEnd - $pos;
                eZDebug::writeNotice( $tagChunk, "New tag chunk in input" );
            }
            else
            {

                // just plain text in the rest
                $textChunk =& substr( $text, $pos, strlen( $text ) );
                eZDebug::writeNotice( $textChunk, "New text chunk in input" );

                if ( strlen( trim( $textChunk ) ) != 0 )
                {
                    $returnArray[] = array( "Type" => EZ_INPUT_CHUNK_TEXT,
                                            "Text" => $textChunk,
                                            "TagName" => "#text"  );
                }

                $pos = strlen( $text );
            }


//            eZDebug::writeNotice( $pos, "Current pos" );

            $pos++;
        }
        return $returnArray;
    }

    /// Contains the tags found
    var $TagStack = array();

    /// The tags that don't break the text
    var $InlineTags = array( "emphasize", "strong" );

    /// The tags that break the paragraph
    var $BreakTags = array();
}

?>
