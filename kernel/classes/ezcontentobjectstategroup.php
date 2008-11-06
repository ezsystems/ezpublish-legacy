<?php

class eZContentObjectStateGroup extends eZPersistentObject
{
    function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    public static function definition()
    {
        $def = array( "fields" => array( "id" => array( "name" => "ID",
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
                                                                         "required" => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( "current_translation" => "currentTranslation",
                                                      "all_translations" => "allTranslations",
                                                      "translations" => "translations",
                                                      "languages" => "languages",
                                                      "available_languages" => "availableLanguages",
                                                      "default_language" => "defaultLanguage",
                                                      "states" => "states" ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectStateGroup",
                      "sort" => array( "identifier" => "asc" ),
                      "name" => "ezcontentobject_state_group" );
        return $def;
    }

    public static function fetchById( $id )
    {
        $stateGroups = self::fetchByConditions( array( "ezcontentobject_state_group.id=$id" ), 1, 0 );
        $stateGroup = count( $stateGroups ) > 0 ? $stateGroups[0] : false;
        return $stateGroup;
    }

    public static function fetchByIdentifier( $identifier )
    {
        $db = eZDB::instance();
        $identifier = $db->escapeString( $identifier );
        $stateGroups = self::fetchByConditions( array( "ezcontentobject_state_group.identifier='$identifier'" ), 1, 0 );
        $stateGroup = count( $stateGroups ) > 0 ? $stateGroups[0] : false;
        return $stateGroup;
    }

    private static function fetchByConditions( $conditions, $limit, $offset )
    {
        $db = eZDB::instance();

        $defaultConditions = array(
            'ezcontentobject_state_group_language.contentobject_state_group_id=ezcontentobject_state_group.id',
            eZContentLanguage::languagesSQLFilter( 'ezcontentobject_state_group' ),
            eZContentLanguage::sqlFilter( 'ezcontentobject_state_group_language', 'ezcontentobject_state_group' )
        );

        $conditions = array_merge( $conditions, $defaultConditions );

        $conditionsSQL = implode( ' AND ', $conditions );

        $sql = "SELECT * \r\n" .
               "FROM ezcontentobject_state_group, ezcontentobject_state_group_language \r\n".
               "WHERE $conditionsSQL";

        $rows = $db->arrayQuery( $sql, array( 'limit' => $limit, 'offset' => $offset ) );

        $stateGroups = array();
        foreach ( $rows as $row )
        {
            $stateGroup = new eZContentObjectStateGroup( $row );
            $stateGroupLanguage = new eZContentObjectStateGroupLanguage( $row );
            $stateGroup->setLanguageObject( $stateGroupLanguage );
            $stateGroups[] = $stateGroup;
        }

        return $stateGroups;
    }

    public static function fetchByOffset( $limit, $offset )
    {
        return self::fetchByConditions( array(), $limit, $offset );
    }

    private function setLanguageObject( eZContentObjectStateGroupLanguage $stateGroupLanguage )
    {
        $this->LanguageObject = $stateGroupLanguage;
    }

    public function currentTranslation()
    {
        return $this->LanguageObject;
    }

    public function setCurrentLanguage( $locale )
    {
        $lang = eZContentLanguage::fetchByLocale( $locale );
        $langID = $lang->attribute( 'id' );
        foreach ( $this->translations() as $translation )
        {
            if ( $translation->attribute( 'language_id' ) == $langID )
            {
                $this->setLanguageObject( $translation );
                return true;
            }
        }

        return false;
    }

    /*!
     \return an array of eZContentObjectStateGroupLanguage objects, representing all possible translations
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
                eZDebug::writeDebug( $languageID );

                if ( !array_key_exists( $languageID, $allTranslations ) )
                {
                    $row = array( 'language_id' => $languageID );
                    if ( isset( $this->ID ) )
                    {
                        $row['contentobject_state_group_id'] = $this->ID;
                    }
                    $allTranslations[] = new eZContentObjectStateGroupLanguage( $row );
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
            $this->Translations = eZContentObjectStateGroupLanguage::fetchByGroup( $this->ID );
        }
        return $this->Translations;
    }

    /*!
     \return the languages the state group exists in.

     Returns an array of eZContentLanguage instances.
    */
    public function languages()
    {
        return isset( $this->LanguageMask ) ? eZContentLanguage::prioritizedLanguagesByMask( $this->LanguageMask ) : array();
    }

    /*!
     \return the languages the state group exists in.

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
     \brief stores the state group and all its translations
    */
    public function store( $fieldFilters = null )
    {
        $db = eZDB::instance();

        $db->begin();

        $languageMask = 1;
        // set language mask and always available bits
        foreach ( $this->AllTranslations as $translation )
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
        $this->setAttribute( 'language_mask', $languageMask );

        // store state group
        eZPersistentObject::storeObject( $this, $fieldFilters );

        // store or remove translations
        foreach ( $this->AllTranslations as $translation )
        {
            if ( !$translation->hasData() )
            {
                // the name and description are empty
                // so the translation needs to be removed if it was stored before
                if ( !is_null( $translation->attribute( 'contentobject_state_group_id' ) ) )
                {
                    $translation->remove();
                }
            }
            else
            {
                if ( $translation->attribute( 'contentobject_state_group_id' ) != $this->ID )
                {
                    $translation->setAttribute( 'contentobject_state_group_id', $this->ID );
                }

                $translation->store();
            }
        }

        $db->commit();
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
            else
            {
                // check for existing identifier
                $existingGroup = self::fetchByIdentifier( $this->Identifier );
                if ( $existingGroup && ( !isset( $this->ID ) || $existingGroup->attribute( 'id' ) !== $this->ID ) )
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
                $messages[] =  ezi18n( 'kernel/state/edit', '%language_name: this language is the default but neither name or description were provided for this language', null, array( '%language_name' => $translation->attribute( 'language' )->attribute( 'locale_object' )->attribute( 'intl_language_name' ) ) );
            }
        }

        return $isValid;
    }

    public function states( $refreshMemberVariable = false )
    {
        if ( !isset( $this->ID ) )
        {
            return array();
        }
        else if ( !is_array( $this->States ) || $refreshMemberVariable )
        {
            $this->States = eZContentObjectState::fetchByGroup( $this->ID );
        }

        return $this->States;
    }

    public function fetchHTTPPersistentVariables()
    {
        $translations = $this->allTranslations();

        $http = eZHTTPTool::instance();
        eZHTTPPersistence::fetch( 'ContentObjectStateGroup' , eZContentObjectStateGroup::definition(), $this, $http, false );
        eZHTTPPersistence::fetch( 'ContentObjectStateGroup' , eZContentObjectStateGroupLanguage::definition(), $translations, $http, true );
    }

    public static function removeByID( $id )
    {
        $db = eZDB::instance();
        $db->begin();
        $states = eZContentObjectState::fetchByGroup( $id );
        foreach ( $states as $state )
        {
            eZContentObjectState::removeByID( $state->attribute( 'id' ) );
        }
        eZPersistentObject::removeObject( eZContentObjectStateGroupLanguage::definition(), array( 'contentobject_state_group_id' => $id ) );
        eZPersistentObject::removeObject( eZContentObjectStateGroup::definition(), array( 'id' => $id ) );
        $db->commit();
    }

    public function removeStatesByID( $idList )
    {
        $newDefaultStateID = null;
        $removeIDList = array();

        $db = eZDB::instance();
        $db->begin();

        $states = $this->states();

        foreach ( $states as $state )
        {
            $stateID = $state->attribute( 'id' );
            if ( in_array( $stateID, $idList ) )
            {
                $removeIDList[] = $stateID;
            }
            else if ( $newDefaultStateID === null )
            {
                $newDefaultStateID = $stateID;
            }
        }

        $removeIDListCount = count( $removeIDList );
        if ( $removeIDListCount > 0 )
        {
            if ( $newDefaultStateID )
            {
                $contentObjectStateIDCondition = $removeIDListCount > 1 ? $db->generateSQLInStatement( $removeIDList, 'contentobject_state_id' ) :
                                                                          "contentobject_state_id=$removeIDList[0]";
                $db->query( "UPDATE ezcontentobject_state_link
                             SET contentobject_state_id=$newDefaultStateID
                             WHERE $contentObjectStateIDCondition" );
                eZContentObjectState::cleanDefaultsCache();
            }

            foreach ( $removeIDList as $id )
            {
                eZContentObjectState::removeByID( $id );
            }

            // re-order remaining states in the same group
            $states = $this->states( true );
            $i = 0;
            foreach ( $states as $state )
            {
                $state->setAttribute( 'priority', $i );
                $state->sync( array( 'priority' ) );
                $i++;
            }
        }
        $db->commit();
    }

    public function reorderStates( $stateIDList )
    {
        $stateIDList = array_values( $stateIDList );

        $states = $this->states();

        $currentStateIDList = array();
        foreach ( $states as $state )
        {
            $stateID = $state->attribute( 'id' );
            if ( !in_array( $stateID, $stateIDList ) )
            {
                return false;
            }

            // need to convert to int here, otherwise comparing arrays with === won't work
            $currentStateIDList[] = (int)$stateID;
        }

        if ( $stateIDList === $currentStateIDList )
        {
            // order didn't change at all
            return true;
        }

        $db = eZDB::instance();
        $db->begin();
        foreach ( $stateIDList as $i => $updateID )
        {
            if ( $currentStateIDList[$i] != $updateID )
            {
                $db->query( "UPDATE ezcontentobject_state SET priority=$i WHERE id=$updateID" );
            }
        }
        $db->commit();
        return true;
    }

    private $LanguageObject;
    private $Translations;
    private $AllTranslations;
    private $States;
}
?>
