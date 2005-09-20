<?php
//
// Definition of eZBorkTranslator class
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

/*! \file ezborktranslator.php
*/

/*!
  \class eZBorkTranslator ezborktranslator.php
  \ingroup eZTranslation
  \brief Translates text into the Bork language (Mock Swedish)

  This translation is adapted from the Mock Swedish translation in Qt Quarterly 3/2002:
  http://doc.trolltech.com/qq/qq03-swedish-chef.html

  It translates the following characters/strings:
  (The "|" sign stands for a word boundary, and "-" stands for mid-word.)

  a-    -> e
  an    -> un
  au    -> oo
  en|   -> ee
  -ew   -> oo
  -f    -> ff
  -i    -> ee
  -ir   -> ur
  |o    -> oo
  ow    -> oo
  ph    -> f
  th|   -> t
  -tion -> shun
  -u    -> oo
  |U-   -> Oo
  y|    -> ai
  v     -> f
  w     -> v

  Words that are not changed by these rules will have "-a" appended to them.

*/

include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );

class eZBorkTranslator extends eZTranslatorHandler
{
    /*!
     Construct the translator.
    */
    function eZBorkTranslator()
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
            $translation = $this->borkify( $source );
            $this->Messages[$key] = $man->createMessage( $context, $source, $comment, $translation );
        }

        return $this->Messages[$key];
    }

    /*!
     Translates the text into bork code.
    */
    function borkify( $text )
    {
        $textBlocks = preg_split( "/(%[^ ]+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE );
        $newTextBlocks = array();
        foreach ( $textBlocks as $text )
        {
            if ( $text[0] == '%' )
            {
                $newTextBlocks[] = $text;
                continue;
            }
            $orgtext = $text;
            $text = preg_replace( "/a\B/", "e", $text );
            $text = preg_replace( "/an/", "un", $text );
            $text = preg_replace( "/au/", "oo", $text );
            $text = preg_replace( "/en\b/", "ee", $text );
            $text = preg_replace( "/\Bew/", "oo", $text );
            $text = preg_replace( "/\Bf/", "ff", $text );
            $text = preg_replace( "/\Bi/", "ee", $text );
            $text = preg_replace( "/\Bir/", "ur", $text );
            $text = preg_replace( "/\bo/", "oo", $text );
            $text = preg_replace( "/ow/", "oo", $text );
            $text = preg_replace( "/ph/", "f", $text );
            $text = preg_replace( "/th\b/", "t", $text );
            $text = preg_replace( "/\Btion/", "shun", $text );
            $text = preg_replace( "/\Bu/", "oo", $text );
            $text = preg_replace( "/\bU/", "Oo", $text );
            $text = preg_replace( "/y\b/", "ai", $text );
            $text = preg_replace( "/v/", "f", $text );
            $text = preg_replace( "/w/", "v", $text );
            $text = preg_replace( "/ooo/", "oo", $text );
            if ( $orgtext == $text )
                $text = $text . "-a";
            $newTextBlocks[] = $text;
        }
        $text = implode( '', $newTextBlocks );
        $text = preg_replace( "/([:.?!])(.*)/", "\\2\\1", $text );
        $text = "[" . $text . "]";
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
        $translator =& $GLOBALS["eZBorkTranslator"];
        if ( isset( $translator ) and get_class( $translator ) == "ezborktranslator" )
            return $translator;
        $translator = new eZBorkTranslator();
        $man =& eZTranslatorManager::instance();
        $man->registerHandler( $translator );
        return $translator;
    }

    /// \privatesection
    /// Contains the hash table with cached bork translations
    var $Messages;
}

?>
