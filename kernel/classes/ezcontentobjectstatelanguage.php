<?php
// Persistent object class auto-generated

class eZContentObjectStateLanguage extends eZPersistentObject
{
    function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        $def = array( "fields" => array( "contentobject_state_id" => array( "name" => "ContentObjectStateID",
                                                                            "datatype" => "integer",
                                                                            "required" => true ),
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
                      "function_attributes" => array( "language" => "language" ),
                      "increment_key" => false,
                      "class_name" => "eZContentObjectStateLanguage",
                      "sort" => array(),
                      "name" => "ezcontentobject_state_language" );
        return $def;
    }

    public static function fetchByState( $id )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectStateLanguage::definition(), null, array( 'contentobject_state_id' => $id ) );
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
