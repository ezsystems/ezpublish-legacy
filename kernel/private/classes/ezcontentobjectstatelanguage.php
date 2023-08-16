<?php
/**
 * File containing the eZContentObjectStateLanguage class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This class takes care of the localization of a content object state.
 *
 * Instances of this class contain the name and description of a content object state in a specific language.
 *
 * @version //autogentag//
 * @package kernel
 * @see eZContentObjectState
 */
class eZContentObjectStateLanguage extends eZPersistentObject
{
    public $Name;
    public $Description;
    public $LanguageID;
    static function definition()
    {
        static $definition = array( "fields" => array( "contentobject_state_id" => array( "name" => "ContentObjectStateID",
                                                                                          "datatype" => "integer",
                                                                                          "required" => true,
                                                                                          "foreign_class" => "eZContentObjectState",
                                                                                          "foreign_attribute" => "id",
                                                                                          "multiplicity" => "1..*" ),
                                         "language_id" => array( "name" => "LanguageID",
                                                                 "datatype" => "integer",
                                                                 "required" => true ),
                                         "name" => array( "name" => "Name",
                                                          "datatype" => "string",
                                                          "required" => true ),
                                         "description" => array( "name" => "Description",
                                                                 "datatype" => "text",
                                                                 "required" => false ) ),
                      "keys" => array( "contentobject_state_id",
                                       "language_id" ),
                      "function_attributes" => array( "language" => "language",
                                                      "is_valid" => "isValid",
                                                      "real_language_id" => "realLanguageID",
                                                    ),
                      "increment_key" => false,
                      "class_name" => "eZContentObjectStateLanguage",
                      "sort" => array(),
                      "name" => "ezcobj_state_language" );
        return $definition;
    }

    /**
     *
     *
     * @param integer $id
     * @return array
     */
    public static function fetchByState( $id )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectStateLanguage::definition(), null, array( 'contentobject_state_id' => $id ) );
    }

    /**
     *
     *
     * @param array $messages
     * @return boolean
     */
    public function isValid( &$messages = array() )
    {
        $isValid = true;
        if ( isset( $this->Name ) && strlen( $this->Name ) > 45 )
        {
            $messages[] = ezpI18n::tr( 'kernel/state/edit', 'Name in %language_name is too long. Maximum 45 characters allowed.', null, array( '%language_name' => $this->language()->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            $isValid = false;
        }

        if ( ( !isset( $this->Name ) || $this->Name == '' ) && $this->Description != '' )
        {
            $messages[] = ezpI18n::tr( 'kernel/state/edit', 'Name in %language_name: input required', null, array( '%language_name' => $this->language()->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            $isValid = false;
        }

        return $isValid;
    }

    /**
     *
     *
     * @return eZContentLanguage
     */
    public function language()
    {
        return eZContentLanguage::fetch( $this->LanguageID & ~1 );
    }

    /**
     *
     *
     * @return boolean
     */
    public function hasData()
    {
        return ( isset( $this->Name) && trim( $this->Name ) != '' ) || ( isset( $this->Description ) && trim( $this->Description ) != '' );
    }

    /**
     * returns the language id, without the always available bit
     *
     * @return int language id
     */
    public function realLanguageID()
    {
        return $this->LanguageID & ~1;
    }
}
?>
