<?php
//
// Definition of eZShuffleTranslator class
//
// Created on: <07-Jun-2002 12:40:42 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file ez1337translator.php
*/

/*!
  \class eZShuffleTranslator ez1337translator.php
  \ingroup eZTranslation
  \brief Translates text by moving characters around

*/

include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );

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
    var $Messages;
}

?>
