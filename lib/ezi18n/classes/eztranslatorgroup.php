<?php
//
// Definition of eZTranslatorGroup class
//
// Created on: <10-Jun-2002 11:05:00 amos>
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

/*! \file eztranslatorgroup.php
*/

/*!
  \class eZTranslatorGroup eztranslatorgroup.php
  \ingroup eZTranslation
  \brief Allows for picking translator handlers according to context

*/

include_once( "lib/ezi18n/classes/eztranslatorhandler.php" );

class eZTranslatorGroup extends eZTranslatorHandler
{
    /*!
     Constructor
    */
    function eZTranslatorGroup( $is_key_based )
    {
        $this->eZTranslatorHandler( $is_key_based );
        $this->Handlers = array();
    }

    /*!
     \pure
     \return the translation message for the key \a $key or null if the key does not exist.

     This function must overridden if isKeyBased() is true.
    */
    function findKey( $key )
    {
        $num = $this->keyPick( $key );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->findKey( $key );
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \pure
     \return the translation message for \a $source and \a $context or null if the key does not exist.

     If you know the translation key use findKey() instead.

     This function must overridden if isKeyBased() is true.
    */
    function findMessage( $context, $source, $comment = null )
    {
        $num = $this->pick( $context, $source, $comment );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->findMessage( $context, $source, $comment );
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \pure
     \return the translation string for \a $source and \a $context or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function translate( $context, $source, $comment = null )
    {
        $num = $this->pick( $context, $source, $comment );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->translate( $context, $source, $comment );
        }

        return null;
    }

    /*!
     \pure
     \return the translation string for \a $key or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function keyTranslate( $key )
    {
        $num = $this->keyPick( $key );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->keyTranslate( $key );
        }

        return null;
    }

    /*!
     \pure
     Reimplement this to pick one of the registered handlers based on \a $key.
     \return -1 for no handler or a number within the handler range (starting from 0).
     \sa pick
    */
    function keyPick( $key )
    {
    }

    /*!
     \pure
     Reimplement this to pick one of the registered handlers based on \a $context, \a $source and \a $comment.
     \return -1 for no handler or a number within the handler range (starting from 0).
     \sa keyPick
    */
    function pick( $context, $source, $comment )
    {
    }

    /*!
     \return the number of registered handlers.
    */
    function handlerCount()
    {
        return count( $this->Handlers );
    }

    /*!
     Registers the handler object \a $handler.
    */
    function registerHandler( &$handler )
    {
        if ( !$this->isKeyBased() and $handler->isKeyBased() )
        {
            eZDebug::writeError( "Cannot register keybased handler for non-keybased group", "eZTranslatorGroup" );
            return false;
        }
        $this->Handlers[] =& $handler;
        return true;
    }

    /// \privatesection
    /// The array of grouped handlers
    var $Handlers;
}

?>
