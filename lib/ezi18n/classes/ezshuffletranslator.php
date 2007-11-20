<?php
//
// Definition of eZShuffleTranslator class
//
// Created on: <07-Jun-2002 12:40:42 amos>
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

/*! \file ez1337translator.php
*/

/*!
  \class eZShuffleTranslator ez1337translator.php
  \ingroup eZTranslation
  \brief Translates text by moving characters around

*/

//include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );

class eZShuffleTranslator extends eZTranslatorHandler
{
    /*!
     Construct the translator and loads the translation file $file if is set and exists.
    */
    function eZShuffleTranslator( $max_chars = 3 )
    {
        $this->eZTranslatorHandler( false );

        $this->MaxChars = $max_chars;
        $this->Messages = array();
    }

    /*!
     \reimp
    */
    function findMessage( $context, $source, $comment = null )
    {
        $man = eZTranslatorManager::instance();
        $translation = $this->shuffleText( $source );
        return $man->createMessage( $context, $source, $comment, $translation );
    }

    /*!
     Reorders some of the characters in $text and returns the new text string.
     The maximum number of reorders is taken from MaxChars.
    */
    function &shuffleText( $text )
    {
        $num = rand( 0, $this->MaxChars );
        for ( $i = 0; $i < $num; ++$i )
        {
            $len = strlen( $text );
            $offs = rand( 0, $len - 1 );
            if ( $offs == 0 )
            {
                $tmp = $text[$offs];
                $text[$offs] = $text[$len - 1];
                $text[$len] = $tmp;
            }
            else
            {
                $delta = -1;
                if ( $text[$offs+$delta] == " " and
                     $offs + 1 < $len )
                    $delta = 1;
                $tmp = $text[$offs];
                $text[$offs] = $text[$offs+$delta];
                $text[$offs+$delta] = $tmp;
            }
        }
        return $text;
    }

    /*!
     \reimp
    */
    function translate( $context, $source, $comment = null )
    {
        $msg = $this->findMessage( $context, $source, $comment );
        if ( $msg !== null )
        {
            return $msg["translation"];
        }

        return null;
    }

    /// \privatesection
    /// Contains the hash table with cached 1337 translations
    public $Messages;
}

?>
