<?php
//
// Definition of eZTranslatorManager class
//
// Created on: <10-Jun-2002 11:16:48 amos>
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

/*! \file eztranslatormanager.php
*/

/*! \defgroup eZTranslation Translation
    \ingroup eZI18N
*/


/*!
  \class eZTranslatorManager eztranslatormanager.php
  \ingroup eZTranslation
  \brief This provides internationalization support for text output

  Each message consists of:
   - context - the context of the translation
   - source - the source string
   - comment - a variation of the context/source
   - key - the uniquely generated key taken from context, source and eventually comment


*/

include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );

class eZTranslatorManager
{
    /*!
    */
    function eZTranslatorManager()
    {
        $this->Handlers = array();
    }

    /*!
     Tries to find the translation message that matches \a $key in all it's handlers
     and returns it. If no message could be found it either means that none of the
     handlers have a translation for the key or that some of the handlers are not key based,
     for instance realtime translators.
     In the latter case an extra call to findMessage() or translate() is required.

     Use keyTranslate if you only want to translate a message.

     \sa findMessage, keyTranslate
    */
    function findKey( $key )
    {
        $msg = null;
        for ( $i = 0; $i < count( $this->Handlers ) and $msg === null; ++$i )
        {
            $handler = $this->Handlers[$i];
            if ( $handler->isKeyBased() )
                $msg = $handler->findKey( $key );
        }
        return $msg;
    }

    /*!
     Tries to find the translation message that matches \a $context, \a $source and
     \a $comment. If that fails it tries \a $context and \a $source only.
     The message is then returned or null if no translation message could be found/generated for it.

     Use translate if you only want to translate a message.

     \sa findKey, translate
    */
    function findMessage( $context, $source, $comment = null )
    {
        if ( !is_string( $context ) or $context == "" )
            $context = "default";
        $msg = null;
        for ( $i = 0; $i < count( $this->Handlers ) and $msg === null; ++$i )
        {
            $handler = $this->Handlers[$i];
            $msg = $handler->findMessage( $context, $source, $comment );
        }
        return $msg;
    }

    /*!
     \return the translation string for \a $key.

     Note this returns the exact translation for the given key, use translate()
     instead if you want to have variable comment support.

     \sa findKey, translate
    */
    function keyTranslate( $key )
    {
        $trans = null;
        for ( $i = 0; $i < count( $this->Handlers ) and $trans === null; ++$i )
        {
            $handler = $this->Handlers[$i];
            if ( $handler->isKeyBased() )
                $trans = $handler->keyTranslate( $key );
        }
        return $trans;
    }

    /*!
     \return the translation string for \a $source and \a $context or null if the key does not exist.

     \sa findMessage, findKey
    */
    function translate( $context, $source, $comment = null )
    {
        if ( !is_string( $context ) or $context == "" )
            $context = "default";
        $trans = null;
        for ( $i = 0; $i < count( $this->Handlers ) and $trans === null; ++$i )
        {
            $handler = $this->Handlers[$i];
            $trans = $handler->translate( $context, $source, $comment );
        }
        return $trans;
    }

    /*!
     \static
     \return the unique instance of the translator system.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZTranslatorManagerInstance"];
        if ( get_class( $instance ) != "eztranslatormanager" )
        {
            $instance = new eZTranslatorManager();
        }
        return $instance;
    }

    /*!
     \static
     Registers the handler object \a $handler.
    */
    function registerHandler( &$handler )
    {
        if ( isset( $this ) and get_class( $this ) == "eztranslatormanager" )
            $instance =& $this;
        else
            $instance =& eZTranslatorManager::instance();
        $instance->Handlers[] =& $handler;
    }

    /*!
     \static
     Creates an md5 key based on the \a $context, \a $source and \a $comment and returns it.
    */
    function createKey( $context, $source, $comment = null )
    {
        if ( $comment === null )
            $comment = "";
        return md5( "$context\n$source\n$comment" );
    }

    /*!
     \static
     Creates a message structure out of \a $context, \a $source and \a $comment
     and returns it.
    */
    function createMessage( $context, $source, $comment = null, $translation = null )
    {
        $msg = array( "context" => $context,
                      "source" => $source,
                      "comment" => $comment,
                      "translation" => $translation );
        return $msg;
    }

    /// \privatesection
    /// The array of handler objects
    var $Handlers;
}

?>
