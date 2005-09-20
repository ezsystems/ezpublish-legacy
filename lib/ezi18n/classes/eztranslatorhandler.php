<?php
//
// Definition of eZTranslatorHandler class
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

/*! \file eztranslatorhandler.php
*/

/*!
  \class eZTranslatorHandler eztranslatorhandler.php
  \ingroup eZTranslation
  \brief Base class for translation handling

*/

class eZTranslatorHandler
{
    /*!
     Constructor
    */
    function eZTranslatorHandler( $is_key_based )
    {
        $this->IsKeyBased = $is_key_based;
    }

    /*!
     \return true if the handler can lookup translations with translation keys.

    */
    function isKeyBased()
    {
        return $this->IsKeyBased;
    }

    /*!
     \pure
     \return the translation message for the key \a $key or null if the key does not exist.

     This function must overridden if isKeyBased() is true.
    */
    function findKey( $key )
    {
        return null;
    }

    /*!
     \pure
     \return the translation message for \a $source and \a $context or null if the key does not exist.

     If you know the translation key use findKey() instead.

     This function must overridden if isKeyBased() is true.
    */
    function findMessage( $context, $source, $comment = null )
    {
        return null;
    }

    /*!
     \pure
     \return the translation string for \a $source and \a $context or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function translate( $context, $source, $comment = null )
    {
        return null;
    }

    /*!
     \pure
     \return the translation string for \a $key or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function keyTranslate( $key )
    {
        return null;
    }

    /// \privatesection
    /// Tells whether the handler is key based or not
    var $IsKeyBased;
}

?>
