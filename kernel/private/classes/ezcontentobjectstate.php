<?php
/**
 * File containing the eZContentObjectState class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a content object state
 *
 * @version //autogentag//
 * @package kernel
 */
class eZContentObjectState extends eZPersistentObject
{
    const MAX_IDENTIFIER_LENGTH = 45;

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
                                                                "max_length" => self::MAX_IDENTIFIER_LENGTH ),
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
                                                      "default_language" => "defaultLanguage",
                                                      "object_count" => "objectCount",
                                                      "group" => "group" ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectState",
                      "sort" => array( "group_id" => "asc", "priority" => "asc" ),
                      "name" => "ezcobj_state" );
        return $def;
    }

    /**
     * Fetches a content object state by its numerical ID.
     * @param integer $id the numerical ID of the content object state
     * @return eZContentObjectState|boolean an instance of eZContentObjectState, or false if the requested state does not exist
     */
    public static function fetchById( $id )
    {
        $states = self::fetchByConditions( array( "ezcobj_state.id=$id" ), 1, 0 );
        $state = count( $states ) > 0 ? $states[0] : false;
        return $state;
    }

    /**
     * Fetches a content object state by its identifier
     * and group ID
     *
     * @param string $identifier the identifier of the content object state, which is unique per content object state group
     * @param integer $groupID the numerical ID of the content object state group
     * @return eZContentObjectState|boolean an instance of eZContentObjectState, or false if the requested state does not exist
     */
    public static function fetchByIdentifier( $identifier, $groupID )
    {
        $db = eZDB::instance();
        $identifier = $db->escapeString( $identifier );
        $states = self::fetchByConditions( array( "ezcobj_state.identifier='$identifier'", "ezcobj_state_group.id=$groupID" ), 1, 0 );
        $state = count( $states ) > 0 ? $states[0] : false;
        return $state;
    }

    /**
     * Fetches content object states by conditions.
     *
     * The content object states are fetched in the right language, depending on the list of prioritized languages
     * of the site access.
     *
     * @param $conditions
     * @param $limit
     * @param $offset
     * @return array
     */
    private static function fetchByConditions( $conditions, $limit, $offset )
    {
        $db = eZDB::instance();

        $defaultConditions = array(
            'ezcobj_state.group_id=ezcobj_state_group.id',
            'ezcobj_state_language.contentobject_state_id=ezcobj_state.id',
            eZContentLanguage::languagesSQLFilter( 'ezcobj_state' ),
            eZContentLanguage::sqlFilter( 'ezcobj_state_language', 'ezcobj_state' )
        );

        $conditions = array_merge( $conditions, $defaultConditions );

        $conditionsSQL = implode( ' AND ', $conditions );

        $sql = "SELECT ezcobj_state.*, ezcobj_state_language.* \r\n" .
               "FROM ezcobj_state, ezcobj_state_group, ezcobj_state_language \r\n" .
               "WHERE $conditionsSQL \r\n" .
               "ORDER BY ezcobj_state.priority";

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

    /**
     * Fetches all content object states of a content object state group
     *
     * @param integer $groupID
     * @param integer $limit
     * @param integer $ofset
     *
     * @return array
     */
    public static function fetchByGroup( $groupID, $limit = false, $offset = false )
    {
        return self::fetchByConditions( array( "ezcobj_state_group.id=$groupID" ), $limit, $offset );
    }

    /**
     * @param eZContentObjectStateLanguage $stateLanguage
     */
    private function setLanguageObject( eZContentObjectStateLanguage $stateLanguage )
    {
        $this->LanguageObject = $stateLanguage;
    }

    /**
     * Return the current translation of the content object state
     *
     * @return eZContentObjectStateLanguage
     */
    public function currentTranslation()
    {
        return $this->LanguageObject;
    }

    /**
     * Sets the current language
     *
     * @param string $locale the locale code
     * @return boolean true if the language was found and set, false if the language was not found
     */
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

    /**
     *
     * @return array an array of eZContentObjectStateLanguage objects, representing all possible
     *         translations of this content object state
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
                    $allTranslations[$languageID] = new eZContentObjectStateLanguage( $row );
                }
            }
            ksort( $allTranslations );
            // array_values is needed here to reset keys, otherwise eZHTTPPersistence::fetch() won't work
            $this->AllTranslations = array_values( $allTranslations );
        }
        return $this->AllTranslations;
    }

    public function translationByLocale( $locale )
    {
        $languageID = eZContentLanguage::idByLocale( $locale );

        if ( $languageID )
        {
            $translations = $this->allTranslations();
            foreach ( $translations as $translation )
            {
                if ( $translation->realLanguageID() == $languageID )
                {
                    return $translation;
                }
            }
        }

        return false;
    }

    /**
     *
     * @return an array of eZContentObjectStateLanguage objects, representing all available
     *         translations of this content object state
     */
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

    /**
     * Retrieves the languages this content object state is translated into
     *
     * @return array an array of eZContentLanguage instances
     */
    public function languages()
    {
        return isset( $this->LanguageMask ) ? eZContentLanguage::prioritizedLanguagesByMask( $this->LanguageMask ) : array();
    }

    /**
     *
     * @return array the languages the state exists in, as an array with language code strings.
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

    /**
     * Stores the content object state and its translations.
     *
     * Before storing a content object state, you should use
     * {@link eZContentObjectState::isValid()} to check its validness.
     *
     * @param array $fieldFilters
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
                    if ( empty( $this->DefaultLanguageID ) )
                    {
                        $this->DefaultLanguageID = $languageID & ~1;
                    }
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

            $assignToObjects = false;
            if ( !isset( $this->ID ) )
            {
                $rows = $db->arrayQuery( "SELECT MAX(priority) AS max_priority FROM ezcobj_state WHERE group_id=" . $this->GroupID );

                if ( count( $rows ) > 0 && $rows[0]['max_priority'] !== null )
                {
                    $this->setAttribute( 'priority', $rows[0]['max_priority'] + 1 );
                }
                else
                {
                    // this is the first state created in the state group
                    // make all content objects use this state
                    $assignToObjects = true;
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

            if ( $assignToObjects )
            {
                $stateID = $this->ID;
                $db->query( "INSERT INTO ezcobj_state_link (contentobject_id, contentobject_state_id) SELECT id, $stateID FROM ezcontentobject" );
            }

            $db->commit();
        }
        else
        {
            eZPersistentObject::store( $fieldFilters );
        }
    }

    /**
     *
     * @return int the numerical ID of the default language
     */
    public function defaultLanguage()
    {
        return eZContentLanguage::fetch( $this->DefaultLanguageID );
    }

    /**
     * Checks if all data is valid and can be stored to the database.
     *
     * @param array &$messages
     * @return boolean true when valid, false when not valid
     * @see eZContentObjectState::store()
     */
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
            else if ( strlen( $this->Identifier ) > self::MAX_IDENTIFIER_LENGTH )
            {
                $messages[] = ezi18n( 'kernel/state/edit', 'Identifier: invalid, maximum %max characters allowed.',
                                      null, array( '%max' => self::MAX_IDENTIFIER_LENGTH ) );
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

        if ( $translationsWithData == 0 )
        {
            $isValid = false;
            $messages[] =  ezi18n( 'kernel/state/edit', 'Translations: you need to add at least one localization' );
        }
        else if ( empty( $this->DefaultLanguageID ) && $translationsWithData > 1 )
        {
            $isValid = false;
            $messages[] =  ezi18n( 'kernel/state/edit', 'Translations: there are multiple localizations but you did not specify which is the default one' );
        }

        return $isValid;
    }

    public function group()
    {
        return eZContentObjectStateGroup::fetchById( $this->GroupID );
    }

    /**
     * Get the list of content object states that is used to create the object state limitation list in the policy/edit view
     *
     * @return array
     */
    public static function limitationList()
    {
        $sql = "SELECT g.identifier group_identifier, s.identifier state_identifier, s.priority, s.id \r\n" .
               "FROM ezcobj_state s, ezcobj_state_group g \r\n" .
               "WHERE s.group_id=g.id \r\n" .
               "AND g.identifier NOT LIKE 'ez%' \r\n" .
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

    /**
     * The defaults are cached in a static class variable, so subsequent calls to this method do not require
     * queries to the database each time. To clear this cache use {@link eZContentObjectState::cleanDefaultsCache()}.
     *
     * @return array an array of all default content object states
     */
    public static function defaults()
    {
        if ( !is_array( self::$Defaults ) )
        {
            self::$Defaults = eZPersistentObject::fetchObjectList( self::definition(), null, array( 'priority' => 0 ) );
        }

        return self::$Defaults;
    }

    /**
     * Cleans the cache used by {@link eZContentObjectState::defaults()}.
     */
    public static function cleanDefaultsCache()
    {
        self::$Defaults = null;
    }

    /**
     * Fetches the HTTP persistent variables for this content object state and its localizations.
     *
     * "ContentObjectState" is used as base name for the persistent variables.
     *
     * @see eZHTTPPersistence
     */
    public function fetchHTTPPersistentVariables()
    {
        $translations = $this->allTranslations();

        $http = eZHTTPTool::instance();
        eZHTTPPersistence::fetch( 'ContentObjectState' , eZContentObjectState::definition(), $this, $http, false );
        eZHTTPPersistence::fetch( 'ContentObjectState' , eZContentObjectStateLanguage::definition(), $translations, $http, true );
    }

    /**
     * Removes a content object state by its numerical ID
     *
     * This method should not be used directly, instead use {@link eZContentObjectStateGroup::removeStatesByID()}.
     *
     * @param integer $id the numerical ID of the content object state
     */
    public static function removeByID( $id )
    {
        $db = eZDB::instance();
        $db->begin();
        $db->query( "DELETE FROM ezcobj_state_link WHERE contentobject_state_id=$id" );
        eZPersistentObject::removeObject( eZContentObjectStateLanguage::definition(), array( 'contentobject_state_id' => $id ) );
        eZPersistentObject::removeObject( eZContentObjectState::definition(), array( 'id' => $id ) );
        $db->commit();
    }

    /**
     * @return integer The count of objects that have this content object state
     */
    public function objectCount()
    {
        $db = eZDB::instance();
        $id = $this->ID;
        $result = $db->arrayQuery( "SELECT COUNT(contentobject_id) AS object_count FROM ezcobj_state_link WHERE contentobject_state_id=$id" );
        return $result[0]['object_count'];
    }

    private $LanguageObject;
    private $Translations;
    private $AllTranslations;
    private static $Defaults = null;
}
?>