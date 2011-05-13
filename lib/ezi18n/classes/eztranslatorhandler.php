<?php
/**
 * File containing the eZTranslatorHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
    public $IsKeyBased;
}

?>
