<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( 'kernel/classes/ezcontentlanguage.php' );

class eZSerializedObjectNameList
{
    function eZSerializedObjectNameList( $serializedNamesString = false )
    {
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
            $languageLocale = eZSerializedObjectNameList::defaultLanguageLocale();

        $serializedNameList = serialize( array( $languageLocale => $nameString,
                                                'always-available' => $languageLocale ) );
        $this->initFromSerializedList( $serializedNameList );
    }

    function initDefault()
    {
        $this->initFromString( '' );
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
        $languageLocale = isset( $this->NameList['always-available'] ) ? $this->NameList['always-available'] : false;
        return $languageLocale;
    }

    function alwaysAvailableLanguage()
    {
        $language = false;
        if ( isset( $this->NameList['always-available'] ) )
            $language = eZContentLanguage::fetchByLocale( $this->NameList['always-available'] );

        return $language;
    }

    function languageMask()
    {
        $mask = 0;
        foreach ( $this->NameList as $languageLocale => $name )
        {
            if ( $languageLocale == 'always-available' )
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
        if ( isset( $this->NameList['always-available'] ) )
        {
            $name = $this->nameByLanguageLocale( $this->NameList['always-available'] );
        }

        return $name;
    }

    function setAlwaysAvailableLanguage( $languageLocale )
    {
        if ( $languageLocale )
        {
            $this->NameList['always-available'] = $languageLocale;
        }
        else
        {
            unset( $this->NameList['always-available'] );
        }

        $this->setHasDirtyData();
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
            if ( $languageLocale != 'always-available' )
                $this->NameList[$languageLocale] .= $appendString;
        }
    }

    /*!
     \static
    */
    function nameFromSerializedString( $serializedNames, $languageLocale = false )
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

    function removeName( $languageLocale )
    {
        if ( isset( $this->NameList[$languageLocale] ) )
        {
            unset( $this->NameList[$languageLocale] );
            $this->setHasDirtyData();
        }
    }

    /*!
     \static
    */
    function defaultLanguageLocale()
    {
        $languageLocale = false;
        $language = eZSerializedObjectNameList::defaultLanguage();

        if ( is_object( $language ) )
            $languageLocale = $language->attribute( 'locale' );

        return $languageLocale;
    }

    /*!
     \static
    */
    function defaultLanguage()
    {
        $language = eZContentLanguage::topPriorityLanguage();
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
                if ( $languageLocale != 'always-available' )
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

    var $NameList;
    var $HasDirtyData;
}

?>
