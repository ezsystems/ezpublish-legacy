<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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
        else
            $this->initDefault();
    }

    /*!
     \static
    */
    function initFromSerializedList( $serializedNamesString )
    {
        $this->HasDirtyData = false;
        $this->unserializeNames( $serializedNamesString );
    }

    function initFromString( $nameString )
    {
        $language = eZContentLanguage::topPriorityLanguage();
        $languageLocale = $language->attribute( 'locale' );
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
        $languageID = false;
        $languageLocale = isset( $this->NameList['always-available'] ) ? $this->NameList['always-available'] : false;
        if ( $languageLocale )
        {
            $languageID = eZContentLanguage::idByLocale( $languageLocale );
        }

        eZDebug::writeDebug( $languageID, 'lazy: alwaysAvailableLanguageID::$languageID' );
        return $languageID;
    }

    function languageMask()
    {
        $mask = 0;
        foreach ( $this->NameList as $languageLocale => $name )
        {
            eZDebug::writeDebug( "$languageLocale => $name", 'lazy: languageMask' );
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

    var $NameList;
    var $HasDirtyData;
}

?>