<?php
//
// Definition of eZ1337Translator class
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
  \class eZ1337Translator ez1337translator.php
  \ingroup eZTranslation
  \brief Translates text into the leet (1337) language

  It translates the following characters/strings
  - to - 2
  - for - 4
  - ate - 8
  - you - u
  - l - 1
  - e - 3
  - o - 0
  - a - 4
  - t - 7

*/

include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );

class eZ1337Translator extends eZTranslatorHandler
{
    /*!
     Construct the translator and loads the translation file $file if is set and exists.
    */
    function eZ1337Translator()
    {
        $this->eZTranslatorHandler( false );

        $this->Messages = array();
    }

    /*!
     \reimp
    */
    function findMessage( $context, $source, $comment = null )
    {
        $man = eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $comment );

        if ( !isset( $this->Messages[$key] ) )
        {
            $translation = $this->leetify( $source );
            $this->Messages[$key] = $man->createMessage( $context, $source, $comment, $translation );
        }

        return $this->Messages[$key];
    }

    /*!
     Translates the text into 1337 code.
    */
    function &leetify( $text )
    {
        $text = preg_replace( "/to/", "2", $text );
        $text = preg_replace( "/for/", "4", $text );
        $text = preg_replace( "/ate/", "8", $text );
        $text = preg_replace( "/you/", "u", $text );
        $text = preg_replace( array( "/l/",
                                     "/e/",
                                     "/o/",
                                     "/a/",
                                     "/t/" ),
                              array( "1",
                                     "3",
                                     "0",
                                     "4",
                                     "7" ), $text );
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

    /*!
     \static
     Initialize the bork translator if this is not allready done.
    */
    function &initialize()
    {
        $translator =& $GLOBALS["eZ1337Translator"];
        if ( isset( $translator ) and get_class( $translator ) == "ez1337translator" )
            return $translator;
        $translator = new eZ1337Translator();
        $man =& eZTranslatorManager::instance();
        $man->registerHandler( $translator );
        return $translator;
    }

    /// \privatesection
    /// Contains the hash table with cached 1337 translations
    var $Messages;
}

?>
