<?php
/**
 * File containing the eZContentObjectStateGroup class.
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class respresenting a content object state group
 *
 * @version //autogentag//
 * @package kernel
 */
class eZContentObjectStateGroup extends eZPersistentObject
{
    const MAX_IDENTIFIER_LENGTH = 45;

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
                                                                "max_length" => self::MAX_IDENTIFIER_LENGTH ),
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
                      "name" => "ezcobj_state_group" );
        return $def;
    }

    /**
     * Fetches a content object state group by its numerical ID
     *
     * @param integer $id
     * @return eZContentObjectStateGroup|boolean
     */
    public static function fetchById( $id )
    {
        $stateGroups = self::fetchByConditions( array( "ezcobj_state_group.id=$id" ), 1, 0 );
        $stateGroup = count( $stateGroups ) > 0 ? $stateGroups[0] : false;
        return $stateGroup;
    }

    /**
     * Fetches a content object state group by its identifier
     *
     * @param string $identifier
     * @return eZContentObjectStateGroup|boolean
     */
    public static function fetchByIdentifier( $identifier )
    {
        $db = eZDB::instance();
        $identifier = $db->escapeString( $identifier );
        $stateGroups = self::fetchByConditions( array( "ezcobj_state_group.identifier='$identifier'" ), 1, 0 );
        $stateGroup = count( $stateGroups ) > 0 ? $stateGroups[0] : false;
        return $stateGroup;
    }

    /**
     * Fetches content object state groups by certain conditions
     *
     * @param array $conditions
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    private static function fetchByConditions( $conditions, $limit, $offset )
    {
        $db = eZDB::instance();

        $defaultConditions = array(
            'ezcobj_state_group_language.contentobject_state_group_id=ezcobj_state_group.id',
            eZContentLanguage::languagesSQLFilter( 'ezcobj_state_group' ),
            eZContentLanguage::sqlFilter( 'ezcobj_state_group_language', 'ezcobj_state_group' )
        );

        $conditions = array_merge( $conditions, $defaultConditions );

        $conditionsSQL = implode( ' AND ', $conditions );

        $sql = "SELECT * \r\n" .
               "FROM ezcobj_state_group, ezcobj_state_group_language \r\n".
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

    /**
     *
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function fetchByOffset( $limit, $offset )
    {
        return self::fetchByConditions( array(), $limit, $offset );
    }

    /**
     *
     *
     * @param eZContentObjectStateGroupLanguage $stateGroupLanguage
     */
    private function setLanguageObject( eZContentObjectStateGroupLanguage $stateGroupLanguage )
    {
        $this->LanguageObject = $stateGroupLanguage;
    }

    /**
     *
     *
     * @return eZContentObjectStateGroupLanguage
     */
    public function currentTranslation()
    {
        return $this->LanguageObject;
    }

    /**
     *
     *
     * @param string $locale
     * @return boolean
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
     *
     * @return array
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
     *
     * @return array
     */
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

    /**
     * Get the languages the state group exists in.
     *
     * @return array an array of eZContentLanguage instances
     */
    public function languages()
    {
        return isset( $this->LanguageMask ) ? eZContentLanguage::prioritizedLanguagesByMask( $this->LanguageMask ) : array();
    }

    /**
     * Get the languages the state group exists in.
     *
     * @return array an array of language code strings.
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
     * Stores the content object state group and its translations.
     *
     * Before storing a content object state group, you should use
     * {@link eZContentObjectStateGroup::isValid()} to check its validness.
     *
     * @param array $fieldFilters
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

        eZExpiryHandler::registerShutdownFunction();
        $handler->setTimestamp( 'state-limitations', time() );

        $db->commit();
    }

    /**
     *
     *
     * @return eZContentLanguage
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
     * @see eZContentObjectStateGroup::store()
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

    /**
     *
     *
     * @param boolean $refreshMemberVariable
     * @return array
     */
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

    /**
     * Fetches the HTTP persistent variables for this content object state group and its localizations.
     *
     * "ContentObjectStateGroup" is used as base name for the persistent variables.
     *
     * @see eZHTTPPersistence
     */
    public function fetchHTTPPersistentVariables()
    {
        $translations = $this->allTranslations();

        $http = eZHTTPTool::instance();
        eZHTTPPersistence::fetch( 'ContentObjectStateGroup' , eZContentObjectStateGroup::definition(), $this, $http, false );
        eZHTTPPersistence::fetch( 'ContentObjectStateGroup' , eZContentObjectStateGroupLanguage::definition(), $translations, $http, true );
    }

    /**
     *
     *
     * @param integer $id
     */
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

    /**
     *
     *
     * @param array $idList
     */
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
                $db->query( "UPDATE ezcobj_state_link
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

    /**
     *
     *
     * @param array $stateIDList
     * @return boolean
     */
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
                $db->query( "UPDATE ezcobj_state SET priority=$i WHERE id=$updateID" );
            }
        }
        $db->commit();
        return true;
    }

    /**
     * Creates a new content object state in this content object state group
     *
     * @param string $identifier identifier for the new state group
     * @return eZContentObjectState the new content object state
     */
    public function newState( $identifier = null )
    {
        return new eZContentObjectState( array( 'group_id' => $this->ID,
                                                'identifier' => $identifier ) );
    }

    /**
     * Returns an array of limitations useable by the policy system
     *
     * @return array
     */
    public static function limitations()
    {
        static $limitations;

        if ( $limitations === null )
        {
            $db = eZDB::instance();
            $dbName = md5( $db->DB );

            $cacheDir = eZSys::cacheDirectory();
            $phpCache = new eZPHPCreator( $cacheDir,
                                          'statelimitations_' . $dbName . '.php',
                                          '',
                                          array( 'clustering' => 'statelimitations' ) );

            $handler = eZExpiryHandler::instance();
            $storedTimeStamp = $handler->hasTimestamp( 'state-limitations' ) ? $handler->timestamp( 'state-limitations' ) : false;
            $expiryTime = $storedTimeStamp !== false ? $storedTimeStamp : 0;

            if ( $phpCache->canRestore( $expiryTime ) )
            {
                $var = $phpCache->restore( array( 'state_limitations' => 'state_limitations' ) );
                $limitations = $var['state_limitations'];
            }
            else
            {
                $limitations = array();

                $groups = self::fetchByOffset( false, false );

                foreach ( $groups as $group )
                {
                    $name = 'StateGroup_' . $group->attribute( 'identifier' );
                    $limitations[$name] = array(
                        'name'   => $name,
                        'values' => array(),
                        'path'   => 'private/classes/',
                        'file'   => 'ezcontentobjectstategroup.php',
                        'class' => __CLASS__,
                        'function' => 'limitationValues',
                        'parameter' => array( $group->attribute( 'id' ) )
                    );
                }

                $phpCache->addVariable( 'state_limitations', $limitations );
                $phpCache->store();
            }

            if ( $storedTimeStamp === false )
            {
                eZExpiryHandler::registerShutdownFunction();
                $handler->setTimestamp( 'state-limitations', time() );
            }
        }

        return $limitations;
    }

    /**
     * Returns an array of limitation values useable by the policy system
     *
     * @param integer $groupID
     * @return array
     */
    public static function limitationValues( $groupID )
    {
        $group = self::fetchById( $groupID );

        $states = $group->attribute( 'states' );

        $limitationValues = array();
        foreach ( $states as $state )
        {
            $limitationValues[] = array( 'name' => $state->attribute( 'current_translation' )->attribute( 'name' ),
                                         'id'   => $state->attribute( 'id' ) );
        }

        return $limitationValues;
    }

    private $LanguageObject;
    private $Translations;
    private $AllTranslations;
    private $States;
}
?>
