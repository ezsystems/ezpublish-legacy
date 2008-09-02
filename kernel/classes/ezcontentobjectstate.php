<?php
// Persistent object class auto-generated

class eZContentObjectState extends eZPersistentObject
{
    function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        $def = array( "fields" => array( "id" => array( "name" => "ID",
                                                        "datatype" => "integer",
                                                        "required" => true ),
                                         "group_id" => array( "name" => "GroupID",
                                                              "datatype" => "integer",
                                                              "required" => true ),
                                         "identifier" => array( "name" => "Identifier",
                                                                "datatype" => "string",
                                                                "required" => true,
                                                                "max_length" => 45 ),
                                         "language_mask" => array( "name" => "LanguageMask",
                                                                   "datatype" => "integer",
                                                                   "default" => 0,
                                                                   "required" => true ),
                                         "default_language_id" => array( "name" => "DefaultLanguageID",
                                                                         "datatype" => "integer",
                                                                         "required" => true ),
                                         "priority" => array( "name" => "Order",
                                                              "datatype" => "integer",
                                                              "required" => true,
                                                              "default" => 0 ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( "current_translation" => "currentTranslation",
                                                      "all_translations" => "allTranslations",
                                                      "translations" => "translations",
                                                      "languages" => "languages",
                                                      "available_languages" => "availableLanguages",
                                                      "default_language" => "defaultLanguage" ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectState",
                      "sort" => array( "group_id" => "asc", "priority" => "asc" ),
                      "name" => "ezcontentobject_state" );
        return $def;
    }

    public static function fetchById( $id )
    {
        $states = self::fetchByConditions( array( "ezcontentobject_state.id=$id" ), 1, 0 );
        $state = count( $states ) > 0 ? $states[0] : false;
        return $state;
    }

    public static function fetchByIdentifier( $identifier, $groupID )
    {
        $db = eZDB::instance();
        $identifier = $db->escapeString( $identifier );
        $states = self::fetchByConditions( array( "ezcontentobject_state.identifier='$identifier'", "ezcontentobject_state_group.id=$groupID" ), 1, 0 );
        $state = count( $states ) > 0 ? $states[0] : false;
        return $state;
    }

    private static function fetchByConditions( $conditions, $limit, $offset )
    {
        $db = eZDB::instance();

        $defaultConditions = array(
            'ezcontentobject_state.group_id=ezcontentobject_state_group.id',
            'ezcontentobject_state_language.contentobject_state_id=ezcontentobject_state.id',
            eZContentLanguage::languagesSQLFilter( 'ezcontentobject_state' ),
            eZContentLanguage::sqlFilter( 'ezcontentobject_state_language', 'ezcontentobject_state' )
        );

        $conditions = array_merge( $conditions, $defaultConditions );

        $conditionsSQL = implode( ' AND ', $conditions );

        $sql = "SELECT ezcontentobject_state.*, ezcontentobject_state_language.* \r\n" .
               "FROM ezcontentobject_state, ezcontentobject_state_group, ezcontentobject_state_language \r\n" .
               "WHERE $conditionsSQL \r\n" .
               "ORDER BY ezcontentobject_state.priority";

        $rows = $db->arrayQuery( $sql, array( 'limit' => $limit, 'offset' => $offset ) );

        $states = array();
        foreach ( $rows as $row )
        {
            $state = new eZContentObjectState( $row );
            $stateLanguage = new eZContentObjectStateLanguage( $row );
            $state->setLanguageObject( $stateLanguage );
            $states[] = $state;
        }

        return $states;
    }

    public static function fetchByGroup( $groupID, $limit = false, $offset = false )
    {
        return self::fetchByConditions( array( "ezcontentobject_state_group.id=$groupID" ), $limit, $offset );
    }

    private function setLanguageObject( eZContentObjectStateLanguage $stateLanguage )
    {
        $this->LanguageObject = $stateLanguage;
    }

    public function currentTranslation()
    {
        return $this->LanguageObject;
    }

    /*!
     \return an array of eZContentObjectStateLanguage objects, representing all possible translations
    */
    public function allTranslations()
    {
        if ( !is_array( $this->AllTranslations ) )
        {
            $allTranslations = array();
            foreach ( $this->translations() as $translation )
            {
                $languageID = $translation->attribute( 'language_id' ) & ~1;
                $allTranslations[$languageID] = $translation;
            }

            $languages = eZContentLanguage::fetchList();
            foreach ( $languages as $language )
            {
                $languageID = $language->attribute( 'id' );
                if ( !array_key_exists( $languageID, $allTranslations ) )
                {
                    $row = array( 'language_id' => $languageID );
                    if ( isset( $this->ID ) )
                    {
                        $row['contentobject_state_id'] = $this->ID;
                    }
                    $allTranslations[] = new eZContentObjectStateLanguage( $row );
                }
            }
            ksort( $allTranslations );
            // array_values is needed here to reset keys, otherwise eZHTTPPersistence::fetch() won't work
            $this->AllTranslations = array_values( $allTranslations );
        }
        return $this->AllTranslations;
    }

    public function translations()
    {
        if ( !isset( $this->ID ) )
        {
            $this->Translations = array();
        }
        else if ( !is_array( $this->Translations ) )
        {
            $this->Translations = eZContentObjectStateLanguage::fetchByState( $this->ID );
        }
        return $this->Translations;
    }

    /*!
     \return the languages the state exists in.

     Returns an array of eZContentLanguage instances.
    */
    public function languages()
    {
        return isset( $this->LanguageMask ) ? eZContentLanguage::prioritizedLanguagesByMask( $this->LanguageMask ) : array();
    }

    /*!
     \return the languages the state exists in.

     Returns an array of language code strings.
    */
    public function availableLanguages()
    {
        $languages = array();
        $languageObjects = $this->languages();

        foreach ( $languageObjects as $languageObject )
        {
            $languages[] = $languageObject->attribute( 'locale' );
        }

        return $languages;
    }

    /*!
     \brief stores the state and all its translations
    */
    public function store( $fieldFilters = null )
    {
        if ( is_null( $fieldFilters ) )
        {
            $db = eZDB::instance();

            $db->begin();

            $languageMask = 1;
            // set language mask and always available bits
            foreach ( $this->AllTranslations() as $translation )
            {
                if ( $translation->hasData() )
                {
                    $languageID = $translation->attribute( 'language_id' );
                    // if default language, set always available flag
                    if ( $languageID & $this->DefaultLanguageID )
                    {
                        $translation->setAttribute( 'language_id', $languageID | 1 );
                    }
                    // otherwise, remove always available flag if it's set
                    else if ( $languageID & 1 )
                    {
                        $translation->setAttribute( 'language_id',  $languageID & ~1 );
                    }

                    $languageMask = $languageMask | $languageID;
                }
            }

            if ( !isset( $this->ID ) )
            {
                $rows = $db->arrayQuery( "SELECT MAX(priority) max_priority FROM ezcontentobject_state WHERE group_id=" . $this->GroupID );

                if ( count( $rows ) > 0 && $rows[0]['max_priority'] !== null )
                {
                    $this->setAttribute( 'priority', $rows[0]['max_priority'] + 1 );
                }
            }

            $this->setAttribute( 'language_mask', $languageMask );

            // store state
            eZPersistentObject::storeObject( $this, $fieldFilters );

            // store or remove translations
            foreach ( $this->AllTranslations as $translation )
            {
                if ( !$translation->hasData() )
                {
                    // the name and description are empty
                    // so the translation needs to be removed if it was stored before
                    if ( !is_null( $translation->attribute( 'contentobject_state_id' ) ) )
                    {
                        $translation->remove();
                    }
                }
                else
                {
                    if ( $translation->attribute( 'contentobject_state_id' ) != $this->ID )
                    {
                        $translation->setAttribute( 'contentobject_state_id', $this->ID );
                    }

                    $translation->store();
                }
            }

            $db->commit();
        }
        else
        {
            eZPersistentObject::store( $fieldFilters );
        }
    }

    public function defaultLanguage()
    {
        return eZContentLanguage::fetch( $this->DefaultLanguageID );
    }

    public function isValid( &$messages = array() )
    {
        $isValid = true;
        // missing identifier
        if ( !isset( $this->Identifier ) || $this->Identifier == '' )
        {
            $messages[] = ezi18n( 'kernel/state/edit', 'Identifier: input required' );
            $isValid = false;
        }
        else
        {
            // make sure the identifier contains only valid characters
            $trans = eZCharTransform::instance();
            $validIdentifier = $trans->transformByGroup( $this->Identifier, 'identifier' );
            if ( strcmp( $validIdentifier, $this->Identifier ) != 0 )
            {
                // invalid identifier
                $messages[] = ezi18n( 'kernel/state/edit', 'Identifier: invalid, it can only consist of characters in the range a-z, 0-9 and underscore.' );
                $isValid = false;
            }
            else if ( isset( $this->GroupID ) )
            {
                // check for existing identifier
                $existingState = self::fetchByIdentifier( $this->Identifier, $this->GroupID );
                if ( $existingState && ( !isset( $this->ID ) || $existingState->attribute( 'id' ) !== $this->ID ) )
                {
                    $messages[] = ezi18n( 'kernel/state/edit', 'Identifier: a content object state group with this identifier already exists, please give another identifier' );
                    $isValid = false;
                }
            }
        }

        $translationsWithData = 0;
        foreach ( $this->AllTranslations as $translation )
        {
            if ( $translation->hasData() )
            {
                $translationsWithData++;
                if ( !$translation->isValid( $messages ) )
                {
                    $isValid = false;
                }
            }
            else if ( ( $translation->attribute( 'language_id' ) & ~1 ) == $this->DefaultLanguageID )
            {
                // if name nor description are set but translation is specified as main language
                $isValid = false;
                $messages[] = ezi18n( 'kernel/state/edit', '%language_name: this language is the default but neither name or description were provided for this language', null, array( '%language_name' => $translation->attribute( 'language' )->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            }
        }

        return $isValid;
    }

    public static function limitationList()
    {
        $sql = "SELECT g.identifier group_identifier, s.identifier state_identifier, s.priority, s.id \r\n" .
               "FROM ezcontentobject_state s, ezcontentobject_state_group g \r\n" .
               "WHERE s.group_id=g.id \r\n" .
               "ORDER BY g.identifier, s.priority";
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $sql );
        $limitationList = array();
        foreach ( $rows as $row )
        {
            $limitationList[] = array( 'name' => $row['group_identifier'] . '/' . $row['state_identifier'], 'id' => $row['id'] );
        }

        return $limitationList;
    }

    public static function defaults()
    {
        if ( !is_array( self::$Defaults ) )
        {
            self::$Defaults = eZPersistentObject::fetchObjectList( self::definition(), null, array( 'priority' => 0 ) );
        }

        return self::$Defaults;
    }

    public function fetchHTTPPersistentVariables()
    {
        $translations = $this->allTranslations();

        $http = eZHTTPTool::instance();
        eZHTTPPersistence::fetch( 'ContentObjectState' , eZContentObjectState::definition(), $this, $http, false );
        eZHTTPPersistence::fetch( 'ContentObjectState' , eZContentObjectStateLanguage::definition(), $translations, $http, true );
    }

    public static function removeByID( $id )
    {
        $db = eZDB::instance();
        $db->begin();
        $db->query( "DELETE FROM ezcontentobject_state_link WHERE contentobject_state_id=$id" );
        eZPersistentObject::removeObject( eZContentObjectStateLanguage::definition(), array( 'contentobject_state_id' => $id ) );
        eZPersistentObject::removeObject( eZContentObjectState::definition(), array( 'id' => $id ) );
        $db->commit();
    }

    public static function removeByIDList( $idList, $groupID, $reOrder = true )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $idList as $id )
        {
            self::removeByID( $id );
        }

        // re-order remaining states in the same group
        $states = eZContentObjectState::fetchByGroup( $groupID );
        $i = 0;
        foreach ( $states as $state )
        {
            $state->setAttribute( 'priority', $i );
            $state->sync( array( 'priority' ) );
            $i++;
        }
        $db->commit();
    }

    private $LanguageObject;
    private $Translations;
    private $AllTranslations;
    private static $Defaults = null;
}
?>
