<?php
/**
 * File containing the eZContentObjectStateGroupLanguage class.
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 *
 * @version //autogentag//
 * @package kernel
 */
class eZContentObjectStateGroupLanguage extends eZPersistentObject
{
    function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        $def = array( "fields" => array( "contentobject_state_group_id" => array( "name" => "ContentObjectStateGroupID",
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
                      "function_attributes" => array( "language" => "language", "is_valid" => "isValid" ),
                      "increment_key" => false,
                      "class_name" => "eZContentObjectStateGroupLanguage",
                      "sort" => array(),
                      "name" => "ezcontentobject_state_group_language" );
        return $def;
    }

    public static function fetchByGroup( $id )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectStateGroupLanguage::definition(), null, array( 'contentobject_state_group_id' => $id ) );
    }

    public function isValid( &$messages = array() )
    {
        $isValid = true;
        if ( isset( $this->Name ) && strlen( $this->Name ) > 45 )
        {
            $messages[] = ezi18n( 'kernel/state/edit', 'Name in %language_name is too long. Maximum 45 characters allowed.', null, array( '%language_name' => $this->language()->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            $isValid = false;
        }

        if ( ( !isset( $this->Name ) || $this->Name == '' ) && $this->Description != '' )
        {
            $messages[] = ezi18n( 'kernel/state/edit', 'Name in %language_name: input required', null, array( '%language_name' => $this->language()->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            $isValid = false;
        }

        return $isValid;
    }

    public function language()
    {
        return eZContentLanguage::fetch( $this->LanguageID & ~1 );
    }

    public function hasData()
    {
        return ( isset( $this->Name) && trim( $this->Name ) != '' ) || ( isset( $this->Description ) && trim( $this->Description ) != '' );
    }
}
?>
