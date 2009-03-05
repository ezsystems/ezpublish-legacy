<?php
//
// Definition of eZTextInputParser class
//
// Created on: <17-Jul-2002 13:02:32 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*!
  \class eZTextInputParser eztextinputparser.php

*/

class eZTextInputParser
{
    const CHUNK_TEXT = 1;
    const CHUNK_TAG = 2;

    /*!

    */
    function eZTextInputParser()
    {

    }

    /*!
     Will parse the input text and create an array of the input.
     False will be returned if the parsing
    */
    function parseText( $text )
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
                    $textChunk = substr( $text, $pos, $tagStart - $pos );
                    $pos += $tagStart - $pos;


                    if ( strlen( trim( $textChunk ) ) != 0 )
                    {
                        $returnArray[] = array( "Type" => eZTextInputParser::CHUNK_TEXT,
                                                "Text" => $textChunk,
                                                "TagName" => "#text" );

                        eZDebug::writeNotice( $textChunk, "New text chunk in input" );
                    }
                }
                // get the tag
                $tagEnd = strpos( $text, ">", $pos );
                $tagChunk = substr( $text, $pos, $tagEnd - $pos + 1 );
                $tagName = preg_replace( "#^\<(.+)?(\s.*|\>)#m", "\\1", $tagChunk );

                // check for end tag
                if ( $tagName[0] == "/" )
                {
                    print( "endtag" );
                }

                $returnArray[] = array( "Type" => eZTextInputParser::CHUNK_TAG,
                                        "TagName" => $tagName,
                                        "Text" => $tagChunk,
                                        );

                $pos += $tagEnd - $pos;
                eZDebug::writeNotice( $tagChunk, "New tag chunk in input" );
            }
            else
            {

                // just plain text in the rest
                $textChunk = substr( $text, $pos, strlen( $text ) );
                eZDebug::writeNotice( $textChunk, "New text chunk in input" );

                if ( strlen( trim( $textChunk ) ) != 0 )
                {
                    $returnArray[] = array( "Type" => eZTextInputParser::CHUNK_TEXT,
                                            "Text" => $textChunk,
                                            "TagName" => "#text"  );
                }

                $pos = strlen( $text );
            }

            $pos++;
        }
        return $returnArray;
    }

    /// Contains the tags found
    public $TagStack = array();

    /// The tags that don't break the text
    public $InlineTags = array( "emphasize", "strong" );

    /// The tags that break the paragraph
    public $BreakTags = array();
}

?>
