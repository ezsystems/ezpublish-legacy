<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

//include_once( 'kernel/classes/ezcontentlanguage.php' );

class eZSerializedObjectNameList
{
    const ALWAYS_AVAILABLE_STR = 'always-available';

    function eZSerializedObjectNameList( $serializedNamesString = false )
    {
        $this->DefaultLanguage = null;

        if ( $serializedNamesString )
            $this->initFromSerializedList( $serializedNamesString );
    }

    function initFromSerializedList( $serializedNamesString )
    {
        $this->HasDirtyData = false;
        $this->unserializeNames( $serializedNamesString );
    }

    function initFromString( $nameString, $languageLocale = false )
    {
        if ( !$languageLocale )
            $languageLocale = $this->defaultLanguageLocale();

        $serializedNameList = serialize( array( $languageLocale => $nameString,
                                                eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR => $languageLocale ) );
        $this->initFromSerializedList( $serializedNameList );
    }

    function initDefault()
    {
        $this->initFromString( '' );
    }

    function create( $serializedNamesString = false )
    {
        $object = new eZSerializedObjectNameList( $serializedNamesString );
        return $object;
    }

    function __clone()
    {
    }

    function copy( $serializedObjectNameListObject )
    {
        $serializedObjectNameListObject->setNameList( $this->nameList() );
        $serializedObjectNameListObject->setHasDirtyData( $this->hasDirtyData() );
        $serializedObjectNameListObject->setDefaultLanguage( $this->defaultLanguage() );
    }

    function mergeNameList( $inNameList )
    {
        foreach ( $inNameList as $languageLocale => $name )
            $this->setNameByLanguageLocale( $name, $languageLocale );

        $this->setHasDirtyData();
    }

    function serializeNames()
    {
        return serialize( $this->NameList );
    }

    function isEmpty()
    {
        return ( count( $this->NameList ) == 0 );
    }

    function unserializeNames( $serializedNamesString )
    {
        $this->NameList = array();
        if ( $serializedNamesString )
        {
            $this->NameList = @unserialize( $serializedNamesString );
            if ( $this->NameList === false || !is_array( $this->NameList ) )
                $this->NameList = array();
        }

        $this->setHasDirtyData( false );
    }

    function alwaysAvailableLanguageID()
    {
        $languageLocale = $this->alwaysAvailableLanguageLocale();
        $languageID = $languageLocale ? eZContentLanguage::idByLocale( $languageLocale ) : false;

        return $languageID;
    }

    function alwaysAvailableLanguageLocale()
    {
        $languageLocale = isset( $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] ) ? $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] : false;
        return $languageLocale;
    }

    function alwaysAvailableLanguage()
    {
        $language = false;
        if ( isset( $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] ) )
            $language = eZContentLanguage::fetchByLocale( $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] );

        return $language;
    }

    function languageMask()
    {
        $mask = 0;
        foreach ( $this->NameList as $languageLocale => $name )
        {
            if ( $languageLocale == eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR )
            {
                $mask += 1;
            }
            else
            {
                $languageID = eZContentLanguage::idByLocale( $languageLocale );
                $mask += $languageID;
            }
        }

        return $mask;
    }

    function name( $languageLocale = false )
    {
        return ( ( $languageLocale === false ) ? $this->nameByPrioritizedLanguages() : $this->nameByLanguageLocale( $languageLocale ) );
    }

    function nameByPrioritizedLanguages()
    {
        $name = $this->alwaysAvailableName();
        $languageList = eZContentLanguage::prioritizedLanguages();
        foreach ( $languageList as $language )
        {
            if ( $this->hasNameInLocale( $language->attribute( 'locale' ) ) )
            {
                $name = $this->nameByLanguageLocale( $language->attribute( 'locale' ) );
                break;
            }
        }

        return $name;
    }

    function nameByLanguageLocale( $languageLocale )
    {
        $name = '';
        if ( $this->hasNameInLocale( $languageLocale ) )
            $name = $this->NameList[$languageLocale];

        return $name;
    }

    function alwaysAvailableName()
    {
        $name = '';
        if ( isset( $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] ) )
        {
            $name = $this->nameByLanguageLocale( $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] );
        }

        return $name;
    }

    function setAlwaysAvailableLanguage( $languageLocale )
    {
        if ( $languageLocale )
        {
            $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] = $languageLocale;
        }
        else
        {
            unset( $this->NameList[eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR] );
        }

        $this->setHasDirtyData();
    }

    function updateAlwaysAvailable( $alwaysAvailableLocale = false )
    {
        if ( !$alwaysAvailableLocale )
            $alwaysAvailableLocale = $this->alwaysAvailableLanguageLocale();

        if ( !$this->hasNameInLocale( $alwaysAvailableLocale ) )
        {
            $languageLocaleList = array_keys( $this->nameList() );
            $alwaysAvailableLocale = $languageLocaleList[0];
        }

        $this->setAlwaysAvailableLanguage( $alwaysAvailableLocale );
    }

    function hasNameInLocale( $languageLocale )
    {
        $hasName = false;
        if ( is_array ( $this->NameList ) )
        {
            if ( $languageLocale && isset( $this->NameList[$languageLocale] ) )
            {
                $hasName = true;
            }
        }
        else
        {
            eZDebug::writeWarning( "Trying to get name for language '$languageLocale' without initialized name list.", 'eZSerializedObjectNameList::hasNameInLocale' );
        }

        return $hasName;
    }

    function setName( $name, $languageLocale = false )
    {
        if ( !$languageLocale )
            $languageLocale = $this->topPriorityLanguageLocale();

        $this->setNameByLanguageLocale( $name, $languageLocale );
    }

    function setNameByLanguageLocale( $name, $languageLocale )
    {
        if ( is_array( $this->NameList ) )
        {
            if ( $languageLocale )
            {
                $this->NameList[$languageLocale] = $name;
                $this->setHasDirtyData();
            }
            else
            {
                eZDebug::writeWarning( "Language locale is not specified while setting name '$name'", 'eZSerializedObjectNameList::setNameByLanguageLocale' );
            }
        }
        else
        {
            eZDebug::writeWarning( "Trying to set name '$name' for language '$languageLocale' without initialized name list.", 'eZSerializedObjectNameList::setNameByLanguageLocale' );
        }
    }

    /*!
     Appends \a $appendString string to each name in NameList.
    */
    function appendGroupName( $appendString )
    {
        foreach ( array_keys( $this->NameList ) as $languageLocale )
        {
            if ( $languageLocale != eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR )
                $this->NameList[$languageLocale] .= $appendString;
        }
    }

    /*!
     \static
    */
    static function nameFromSerializedString( $serializedNames, $languageLocale = false )
    {
        $nameList = new eZSerializedObjectNameList( $serializedNames );
        return $nameList->name( $languageLocale );
    }


    /*!
     \return true if the data is considered dirty(e.g. names were changed)
    */
    function hasDirtyData()
    {
        return $this->HasDirtyData;
    }

    /*!
     Sets whether the object has dirty data or not.
     \sa hasDirtyData, sync
    */
    function setHasDirtyData( $hasDirtyData = true )
    {
        $this->HasDirtyData = $hasDirtyData;
    }

    function nameList()
    {
        return $this->NameList;
    }

    function nameListCount()
    {
        return count( $this->nameList );
    }

    function setNameList( $nameListArray )
    {
        $this->NameList = $nameListArray;
    }

    function resetNameList()
    {
        $this->setNameList( array() );
    }

    function removeName( $languageLocale )
    {
        if ( isset( $this->NameList[$languageLocale] ) )
        {
            unset( $this->NameList[$languageLocale] );
            $this->setHasDirtyData();
        }
    }

    /*!
    */
    function defaultLanguageLocale()
    {
        $languageLocale = false;
        $language = $this->defaultLanguage();

        if ( is_object( $language ) )
            $languageLocale = $language->attribute( 'locale' );

        return $languageLocale;
    }

    /*!
    */
    function defaultLanguage()
    {
        if ( !is_object( $this->DefaultLanguage ) )
        {
            $this->DefaultLanguage = eZContentLanguage::topPriorityLanguage();
        }

        return $this->DefaultLanguage;
    }

    function setDefaultLanguage( $language )
    {
        $this->DefualtLanguage = $language;
    }

    function setDefaultLanguageByLocale( $languageLocale, $createIfNotExist = true )
    {
        $language = eZContentLanguage::fetchByLocale( $languageLocale, $createIfNotExist );

        if ( is_object( $language ) )
        {
            $this->setDefaultLanguage( $language );
        }
        else
        {
            eZDebug::writeWarning( "Can't set '$languageLocale' as default language. '$languageLocale' language doesn't exist in system", "eZSerializedObjectNameList::setDefaultLanguageByLocale" );
        }

        return $language;
    }

    /*!
     The same as 'topPriorityLanguage' but returns language locale.

     \return language locale.
    */
    function topPriorityLanguageLocale()
    {
        $languageLocale = false;

        $language = $this->topPriorityLanguage();
        if ( $language )
            $languageLocale = $language->attribute( 'locale' );

        return $languageLocale;
    }

    /*!
     Returns top prioriry language for which there is translation according to
     siteaccess's available language list.
     If there is no translations for languages listed in siteaccess's available language list
     it returns 'always available' language.

     \return language object.
    */
    function topPriorityLanguage()
    {
        $language = false;

        $languageLocaleList = $this->languageLocaleList();

        $language = eZContentLanguage::topPriorityLanguageByLocaleList( $languageLocaleList );

        if ( !$language )
            $language = $this->alwaysAvailableLanguage();

        return $language;
    }

    /*!
     Returns an array of languages in which contentclass has translations.
     However, if there is a name in language which is not
     listed as 'available' for siteaccess, that langugese will not be returned
     (except of 'always available' language).

     \return an array of language's locales.
    */
    function prioritizedLanguages()
    {
        $languageLocaleList = $this->languageLocaleList();
        $languages = eZContentLanguage::prioritizedLanguagesByLocaleList( $languageLocaleList );

        $alwaysAvailableLanguage = $this->alwaysAvailableLanguage();
        if ( $alwaysAvailableLanguage )
        {
            $alwaysAvailableLanguageLocale = $alwaysAvailableLanguage->attribute( 'locale' );
            if ( !isset( $languages[$alwaysAvailableLanguageLocale] ) )
            {
                $languages[$alwaysAvailableLanguageLocale] = $alwaysAvailableLanguage;
            }
        }

        return $languages;
    }

    function prioritizedLanguagesJsArray()
    {
        $languages = $this->prioritizedLanguages();

        $jsArray = array();

        foreach ( $languages as $key => $language )
        {
            $jsArray[] = "{ locale: '".$language->attribute( 'locale' ).
                "', name: '".$language->attribute( 'name' )."' }";
        }

        if ( count( $jsArray ) > 0 )
        {
            $jsArray = '[ '.implode( ', ', $jsArray ).' ]';
        }

        return $jsArray;
    }

    function languageLocaleList()
    {
        $languageLocaleList = array();

        if ( is_array( $this->NameList ) && count( $this->NameList ) > 0 )
        {
            foreach ( array_keys( $this->NameList ) as $languageLocale )
            {
                if ( $languageLocale != eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR )
                    $languageLocaleList[] = $languageLocale;
            }
        }

        return $languageLocaleList;
    }

    /*!
     The same as 'languageLocaleList' but returns a list of 'eZContentLanguage' objects.

     \return array of language objects.
    */
    function languages()
    {
        $languages = array();

        $languageLocaleList = $this->languageLocaleList();
        foreach( $languageLocaleList as $languageLocale )
            $languages[$languageLocale] = eZContentLanguage::fetchByLocale( $languageLocale );

        return $languages;
    }

    /*!
     Returns an array of languages for which translations don't exist.

     \return an array of languages. Each key in this array is 'language locale'.
    */
    function untranslatedLanguages()
    {
        $availableLanguages = $this->prioritizedLanguages();
        $availableLanguagesCodes = array_keys( $availableLanguages );

        $languages = array();
        foreach ( eZContentLanguage::prioritizedLanguages() as $language )
        {
            $languageCode = $language->attribute( 'locale' );
            if ( !in_array( $languageCode, $availableLanguagesCodes ) )
            {
                $languages[$languageCode] = $language;
            }
        }

        return $languages;
    }

    /*!
     \param $languageInfo. languageInfo = array( 'map_table' => array( [<lang> => <to_lang>],
                                                                       [<lang> => 'skip_language'],
                                                                       .... ) );

     Note: it's probably needed to call 'validate' after 'normalize'.
           'normialize' doesnt' check whether language exist or not, cause you can have names in languages which are not
           in 'map_table', so you need to call 'validate' anyway.
    */
    function normalize( $languageInfo )
    {
        if ( is_array( $languageInfo ) && isset( $languageInfo['map_table'] ) )
        {
            // do normalization on new 'nameList' to avoid dependence on the order
            // of <lang>s in 'map_table'. Normailzation on $this->nameList directly
            // can lead to unwanted behaviour, like
            //      'map_table' => array( 'ger-DE' => 'skip_language',
            //                            'eng-GB' => 'ger-DE' )
            // and
            //      'map_table' => array( 'eng-GB' => 'get-DE',
            //                            'ger-DE' => 'skip_language' )
            // will produce different results.
            $nameList = clone $this;
            $this->resetNameList();

            foreach ( $languageInfo['map_table'] as $fromLanguageLocale => $toLanguageLocale )
            {
                $name = $nameList->nameByLanguageLocale( $fromLanguageLocale );

                if ( $tolanguageLocale == 'skip_language' )
                {
                    // do nothing;
                }
                else
                {
                    $this->setNameByLanguageLocale( $name, $toLanguageLocale );
                }

                // exclude 'processed' name.
                $nameList->removeName( $fromLanguageLocale );
            }

            // copy names which were not transformed
            $this->mergeNameList( $nameList );

            // update always-available(probably original 'always-available' was skiped)
            $this->updateAlwaysAvailable();
        }
    }

    /*!
     Make sure that languages namelist corresponds to languages in the system.
     \param $param. TRUE - create languages if they don't exist in the system.
                    FALSE - remove names form namelist if corresponding language doesn't exist in the system.
                    array - language map. The name will be removed if its language is not in the map.
                            Ex: array( 'language_locale_1' => 'map_to_language_locale',
                                       'language_locale_2' => 'skip' );
                                will map name in 'language_locale_1' language to 'map_to_language_locale' and
                                remove name in 'language_locale_2'. 'map_to_language_locale' language will be
                                created If it doesn't exist in the system.
    */
    function validate( $param = true )
    {
        $languageMap = ( is_array( $param ) && (count( $param ) > 0) ) ? $param : false;
        $createLanguageIfNotExist = ( $param === true ) ? true : false;
        $nameList = $this->nameList();
        foreach ( $nameList as $nameLanguageLocale => $name )
        {
            if ( $nameLanguageLocale != eZSerializedObjectNameList::ALWAYS_AVAILABLE_STR )
            {
                $language = false;

                if( $createLanguageIfNotExist )
                {
                    $language = eZContentLanguage::fetchByLocale( $nameLanguageLocale, true );
                }
                else
                {
                    if ( is_array( $languageMap ) )
                    {
                        $languageLocale = isset( $languageMap[$nameLanguageLocale] ) ? $languageMap[$nameLanguageLocale] : false;

                        if( $languageLocale && $languageLocale != 'skip' )
                        {
                            $language = eZContentLanguage::fetchByLocale( $languageLocale, true );
                        }
                    }
                    else
                    {
                        // just check '$nameLanguageLocale' language if '$languageMap' is not specified.
                        $language = eZContentLanguage::fetchByLocale( $nameLanguageLocale, false );
                    }
                }

                $languageLocale = is_object( $language ) ? $language->attribute( 'locale' ) : false;

                if( $languageLocale != $nameLanguageLocale )
                {
                    if( $languageLocale )
                    {
                        // map name's language.
                        $this->removeName( $nameLanguageLocale );
                        $this->setName( $name, $languageLocale );
                    }
                    else
                    {
                        $this->removeName( $nameLanguageLocale );
                    }
                }
            }
        }
        // update always-available(probably original 'always-available' was skiped)
        $this->updateAlwaysAvailable();
    }

    public $NameList;
    public $HasDirtyData;
    public $DefaultLanguage;
}

?>
