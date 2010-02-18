<?php
/**
 * File containing the eZContentObjectStateGroupLanguage class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This class takes care of the localization of a content object state group.
 *
 * Instances of this class contain the name and description of a content object state group in a specific language.
 *
 * @version //autogentag//
 * @package kernel
 * @see eZContentObjectStateGroup
 */
class eZContentObjectStateGroupLanguage extends eZPersistentObject
{
    function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        static $definition = array( "fields" => array( "contentobject_state_group_id" => array( "name" => "ContentObjectStateGroupID",
                                                                                  "datatype" => "integer",
                                                                                  "required" => false ),
                                         "name" => array( "name" => "Name",
                                                          "datatype" => "string",
                                                          "required" => false ),
                                         "description" => array( "name" => "Description",
                                                                 "datatype" => "text",
                                                                 "required" => false ),
                                         "language_id" => array( "name" => "LanguageID",
                                                                 "datatype" => "integer",
                                                                 "required" => false ) ),
                      "keys" => array( "contentobject_state_group_id",
                                       "language_id" ),
                      "function_attributes" => array( "language" => "language",
                                                      "is_valid" => "isValid",
                                                      "real_language_id" => "realLanguageID",
                                                    ),
                      "increment_key" => false,
                      "class_name" => "eZContentObjectStateGroupLanguage",
                      "sort" => array(),
                      "name" => "ezcobj_state_group_language" );
        return $definition;
    }

    /**
     * Retrieve the available languages of a content object state group by its numerical ID
     *
     * @param integer $id
     * @return array an array of eZContentObjectStateGroupLanguage objects
     */
    public static function fetchByGroup( $id )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectStateGroupLanguage::definition(), null, array( 'contentobject_state_group_id' => $id ) );
    }

    /**
     * Check if the data inside the content object state group language are valid and can be stored in the database
     *
     * @param array $messages
     * @return boolean
     */
    public function isValid( &$messages = array() )
    {
        $isValid = true;
        if ( isset( $this->Name ) && strlen( $this->Name ) > 45 )
        {
            $messages[] = ezpI18n::translate( 'kernel/state/edit', 'Name in %language_name is too long. Maximum 45 characters allowed.', null, array( '%language_name' => $this->language()->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            $isValid = false;
        }

        if ( ( !isset( $this->Name ) || $this->Name == '' ) && $this->Description != '' )
        {
            $messages[] = ezpI18n::translate( 'kernel/state/edit', 'Name in %language_name: input required', null, array( '%language_name' => $this->language()->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * return the eZ Publish content language associated with this content object state group language
     *
     * @return eZContentLanguage
     */
    public function language()
    {
        return eZContentLanguage::fetch( $this->LanguageID & ~1 );
    }

    /**
     * Return if this content object state group language has any data to store (= not empty)
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
