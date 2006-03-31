<?php
//
// Definition of eZContentLanguage class
//
// Created on: <08-Feb-2006 10:23:51 jk>
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

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'lib/ezlocale/classes/ezlocale.php' );

define( 'CONTENT_LANGUAGES_MAX_COUNT', 30 );

class eZContentLanguage extends eZPersistentObject
{
    function eZContentLanguage( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
	    return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'required' => true ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'required' => true ),
                                         'locale' => array( 'name' => 'Locale',
                                                            'datatype' => 'string',
                                                            'required' => true ),
                                         'disabled' => array( 'name' => 'Disabled',     /* disabled is reserved for the future */
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => false ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'translation' => 'translation',
                                                      'locale_object' => 'localeObject',
                                                      'object_count' => 'objectCount' ),
                      'sort' => array( 'name' => 'asc' ),
                      'class_name' => 'eZContentLanguage',
                      'name' => 'ezcontent_language' );
    }

    // static
    function addLanguage( $locale, $name = null )
    {
        $localeObject = eZLocale::instance( $locale );
        if ( !$localeObject )
        {
            eZDebug::writeError( "No such locale $locale!", 'eZContentLanguage::addLanguage' );
            return false;
        }

        if ( $name === null )
        {
            $name = $localeObject->attribute( 'intl_language_name' );
        }

        $db =& eZDB::instance();

        $languages = eZContentLanguage::fetchList( true );

        if ( ( $existingLanguage = eZContentLanguage::fetchByLocale( $locale ) ) )
        {
            eZDebug::writeWarning( "Language '$locale' already exists!", 'eZContentLanguage::addLanguage' );
            return $existingLanguage;
        }

        if ( count( $languages ) >= CONTENT_LANGUAGES_MAX_COUNT )
        {
            eZDebug::writeError( 'Too many languages, cannot add more!', 'eZContentLanguage::addLanguage' );
            return false;
        }

        $db->lock( 'ezcontent_language' );

        $idSum = 0;
        foreach( $languages as $language )
        {
            $idSum += $language->attribute( 'id' );
        }

        // ID 1 is reserved
        $candidateId = 2;
        while ( $idSum & $candidateId )
        {
            $candidateId *= 2;
        }

        $newLanguage = new eZContentLanguage( array(
                                                  'id' => $candidateId,
                                                  'locale' => $locale,
                                                  'name' => $name,
                                                  'disabled' => 0 ) );
        $newLanguage->store();

        $db->unlock();

        eZContentLanguage::fetchList( true );

        // clear the cache
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        return $newLanguage;
    }

    // static
    function removeLanguage( $id )
    {
        $language = eZContentLanguage::fetch( $id );
        if ( $language )
        {
            return $language->remove();
        }
        else
        {
            return false;
        }
    }

    function remove()
    {
        if ( $this->objectCount() > 0 )
        {
            return false;
        }

        eZPersistentObject::remove();

        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        eZContentLanguage::fetchList( true );

        return true;
    }

    // static
    // fetches list of all languages from the database table
    function fetchList( $forceReloading = false )
    {
        if ( !isset( $GLOBALS['eZContentLanguageList'] ) || $forceReloading )
        {
            $mask = 1; // we want have 0-th bit set too!
            $languages = eZPersistentObject::fetchObjectList( eZContentLanguage::definition() );

            unset( $GLOBALS['eZContentLanguageList'] );
            unset( $GLOBALS['eZContentLanguageMask'] );
            $GLOBALS['eZContentLanguageList'] = array();
            foreach ( $languages as $language )
            {
                $GLOBALS['eZContentLanguageList'][$language->attribute( 'id' )] = $language;
                $mask += $language->attribute( 'id' );
            }

            $GLOBALS['eZContentLanguageMask'] = $mask;
        }

        return $GLOBALS['eZContentLanguageList'];
    }

    // static
    // fetches list of all languages to be used by permission system
    // list is an array where each entry is an array with 'name' and 'id'
    function fetchLimitationList( $forceReloading = false )
    {
        $languages = array();
        foreach ( $this->fetchList( $forceReloading ) as $language )
        {
            $languages[] = array( 'name' => $language->attribute( 'name' ),
                                  'id' => $language->attribute( 'locale' ) );
        }
        return $languages;
    }

    function fetchLocaleList()
    {
        $languages = eZContentLanguage::fetchList();
        $localeList = array();

        foreach ( $languages as $language )
        {
            $localeList[] = $language->attribute( 'locale' );
        }

        return $localeList;
    }

    // static
    // fetches language by ID
    function fetch( $id )
    {
        $languages = eZContentLanguage::fetchList();

        return isset( $languages[$id] )? $languages[$id]: false;
    }

    function fetchByLocale( $locale )
    {
        $languages = eZContentLanguage::fetchList();

        foreach ( $languages as $language )
        {
            if ( $language->attribute( 'locale' ) == $locale )
            {
                return $language;
            }
        }

        return false;
    }

    // static
    function prioritizedLanguages( $additionalLanguage = false, $languageList = false )
    {
        // from INI settings
	    // cached in global variable
        // returns prioritizedlist

        //TODO: if INI setting does not exist, take the DefaultLang...

        if ( !isset( $GLOBALS['eZContentLanguagePrioritizedLanguages'] ) )
        {
            $GLOBALS['eZContentLanguagePrioritizedLanguages'] = array();

            $ini =& eZINI::instance();

            $languageListAsParameter = false;
            if ( $languageList )
            {
                $languageListAsParameter = true;
            }

            if ( !$languageList && $ini->hasVariable( 'RegionalSettings', 'SiteLanguageList' ) )
            {
                $languageList = $ini->variable( 'RegionalSettings', 'SiteLanguageList' );
            }

            if ( !$languageList )
            {
                $languageList = array( $ini->variable( 'RegionalSettings', 'ContentObjectLocale' ) );
            }

            if ( $additionalLanguage )
            {
                if ( ( $position = array_search( $additionalLanguage, $languageList ) ) !== false )
                {
                    unset( $languageList[$position] );
                }
                array_unshift( $languageList, $additionalLanguage );
            }

            foreach ( $languageList as $localeCode )
            {
                $language = eZContentLanguage::fetchByLocale( $localeCode );
                if ( $language )
                {
                    $GLOBALS['eZContentLanguagePrioritizedLanguages'][] = $language;
                }
                else
                {
                    eZDebug::writeWarning( "Language '$localeCode' does not exist or is not used!", 'eZContentLanguage::prioritizedLanguages' );
                }
            }

            if ( ( !$languageListAsParameter && $ini->variable( 'RegionalSettings', 'ShowUntranslatedObjects' ) == 'enabled' ) ||
                 ( isset( $GLOBALS['eZContentLanguageCronjobMode'] ) && $GLOBALS['eZContentLanguageCronjobMode'] ) )
            {
                $completeList = eZContentLanguage::fetchList();
                foreach ( $completeList as $language )
                {
                    if ( !in_array( $language->attribute( 'locale' ), $languageList ) )
                    {
                        $GLOBALS['eZContentLanguagePrioritizedLanguages'][] = $language;
                    }
                }
            }
        }

        return $GLOBALS['eZContentLanguagePrioritizedLanguages'];
    }

    function prioritizedLanguageCodes()
    {
        $languages = eZContentLanguage::prioritizedLanguages();
        $localeList = array();

        foreach ( $languages as $language )
        {
            $localeList[] = $language->attribute( 'locale' );
        }

        return $localeList;
    }

    function setPrioritizedLanguages( $languages )
    {
        unset( $GLOBALS['eZContentLanguagePrioritizedLanguages'] );
        eZContentLanguage::prioritizedLanguages( false, $languages );
    }

    function clearPrioritizedLanguages()
    {
        eZContentLanguage::setPrioritizedLanguages( false );
    }

    function topPriorityLanguage()
    {
        $prioritizedLanguages = eZContentLanguage::prioritizedLanguages();
        if ( $prioritizedLanguages )
        {
            return $prioritizedLanguages[0];
        }
        else
        {
            return false;
        }
    }

    // static
    function locale()
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );

        $topPriorityLanguage = eZContentLanguage::topPriorityLanguage();
        $localeCode = $topPriorityLanguage->attribute( 'locale' );
        $locale =& eZLocale::instance( $localeCode );
        return $locale;
    }

    function &localeObject()
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );

        $locale =& eZLocale::instance( $this->Locale );
        return $locale;
    }

    function languagesByMask( $mask )
    {
        // returns array of language objects which are allowed by $mask mask
        $result = array();

        $languages = eZContentLanguage::fetchList();
        foreach ( $languages as $key => $language )
        {
            if ( (int) $key & (int) $mask )
            {
                $result[$language->attribute( 'locale' )] = $language;
            }
        }

        return $result;
    }

    function prioritizedLanguagesByMask( $mask )
    {
        // the same as the previous one but only those which are selected for siteaccess and sorted by priority
        $result = array();

        $languages = eZContentLanguage::prioritizedLanguages();
        foreach ( $languages as $language )
        {
            if ( ( (int) $language->attribute( 'id' ) & (int) $mask ) > 0 )
            {
                $result[$language->attribute( 'locale' )] = $language;
            }
        }

        return $result;
    }

    function topPriorityLanguageByMask( $mask )
    {
        $languages = eZContentLanguage::prioritizedLanguages();
        foreach ( $languages as $language )
        {
			if ( ( (int) $language->attribute( 'id' ) & (int) $mask ) > 0 )
            {
                return $language;
            }
        }
		return false;
    }

    function maskByLocale( $locales, $setZerothBit = false )
    {
        if ( !$locales )
        {
            return 0;
        }

        if ( !is_array( $locales ) )
        {
            $locales = array( $locales );
        }

        $mask = 0;
        if ( $setZerothBit )
        {
            $mask = 1;
        }

        foreach( $locales as $locale )
        {
            $language = eZContentLanguage::fetchByLocale( $locale );
            if ( $language )
            {
                $mask += $language->attribute( 'id' );
            }
        }

        return (int) $mask;
    }

    function idByLocale( $locale )
    {
        $id = eZContentLanguage::maskByLocale( $locale );
        if ( $id == 0 )
        {
            $id = -1;
        }
        return $id;
    }

    //static
    function languagesSQLFilter( $languageListTable, $languageListAttributeName = 'language_mask' )
    {
        $prioritizedLanguages = eZContentLanguage::prioritizedLanguages();
        $mask = 1; // 1 - always available objects
        foreach( $prioritizedLanguages as $language )
        {
            $mask += $language->attribute( 'id' );
        }

        $db =& eZDB::instance();
        if ( $db->databaseName() == 'oracle' )
        {
            return " bitand( $languageListTable.$languageListAttributeName, $mask ) > 0 ";
        }
        else
        {
            return " $languageListTable.$languageListAttributeName & $mask > 0 ";
        }
    }

    // static
    function sqlFilter( $languageTable, $languageListTable = null, $languageAttributeName = 'language_id', $languageListAttributeName = 'language_mask' )
    {
        $db =& eZDB::instance();

        if ( $languageListTable === null )
        {
            $languageListTable = $languageTable;
        }

        $prioritizedLanguages = eZContentLanguage::prioritizedLanguages();
        if ( $db->databaseName() == 'oracle' )
        {
            $leftSide = "bitand( $languageListTable.$languageListAttributeName - bitand( $languageListTable.$languageListAttributeName, $languageTable.$languageAttributeName ), 1 )";
            $rightSide = "bitand( $languageTable.$languageAttributeName, 1 )";
        }
        else
        {
            $leftSide = "( ( $languageListTable.$languageListAttributeName - ( $languageListTable.$languageListAttributeName & $languageTable.$languageAttributeName ) ) & 1 )";
            $rightSide = "( $languageTable.$languageAttributeName & 1 )";
        }

        for ( $index = count( $prioritizedLanguages ) - 1, $multiplier = 2; $index >= 0; $index--, $multiplier *= 2 )
        {
            $id = $prioritizedLanguages[$index]->attribute( 'id' );

            if ( $db->databaseName() == 'oracle' )
            {
                $leftSide .= " + bitand( $languageListTable.$languageListAttributeName - bitand( $languageListTable.$languageListAttributeName, $languageTable.$languageAttributeName ), $id ) ";
                $rightSide .= " + bitand( $languageTable.$languageAttributeName, $id ) ";
            }
            else
            {
                $leftSide .= " + ( ( ( $languageListTable.$languageListAttributeName - ( $languageListTable.$languageListAttributeName & $languageTable.$languageAttributeName ) ) & $id ) ";
                $rightSide .= " + ( ( $languageTable.$languageAttributeName & $id ) ";
            }

            if ( $multiplier > $id )
            {
                $factor = " * " . ( $multiplier / $id );
                $leftSide .= $factor;
                $rightSide .= $factor;
            }
            else if ( $multiplier < $id )
            {
                $factor = " / ". ( $id / $multiplier );
                $leftSide .= $factor;
                $rightSide .= $factor;
            }
            if ( $db->databaseName() != 'oracle' )
            {
                $leftSide .= ' )';
                $rightSide .= ' )';
            }
        }

        if ( $db->databaseName() == 'oracle' )
        {
            $sql = "bitand( $languageTable.$languageAttributeName, $languageListTable.$languageListAttributeName ) > 0";
        }
        else
        {
            $sql = "$languageTable.$languageAttributeName & $languageListTable.$languageListAttributeName > 0";
        }

        return " ( $sql AND $leftSide < $rightSide ) ";
    }

    function &objectCount()
    {
        $db =& eZDB::instance();

        $languageID = $this->ID;
        if ( $db->databaseName() == 'oracle' )
        {
            $whereSQL = "bitand( language_mask, $languageID ) > 0";
        }
        else
        {
            $whereSQL = "language_mask & $languageID > 0";
        }

        $count = $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentobject WHERE $whereSQL" );
        $count = $count[0]['count'];

        return $count;
    }

    function objectInitialCount()
    {
        $db =& eZDB::instance();

        $languageID = $this->ID;
        $count = $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentobject WHERE initial_language_id = '$languageID'" );
        $count = $count[0]['count'];

        return $count;
    }

    function &translation()
    {
        return $this;
    }

    function updateObjectNames()
    {
        /* deprecated */
    }

    function setCronjobMode( $enable = true )
    {
        $GLOBALS['eZContentLanguageCronjobMode'] = true;
        unset( $GLOBALS['eZContentLanguagePrioritizedLanguages'] );
    }

    function clearCronjobMode()
    {
        eZContentLanguage::setCronjobMode( false );
    }

    function jsArrayByMask( $mask )
    {
        $jsArray = array();
        $languages = eZContentLanguage::prioritizedLanguagesByMask( $mask );
        foreach ( $languages as $key => $language )
        {
            $jsArray[] = "{ locale: '".$language->attribute( 'locale' ).
                "', name: '".$language->attribute( 'name' )."' }";
        }

        if ( $jsArray )
        {
            return '[ '.implode( ', ', $jsArray ).' ]';
        }
        else
        {
            return false;
        }
    }

    function maskForRealLanguages()
    {
        if ( !isset( $GLOBALS['eZContentLanguageMask'] ) )
        {
            eZContentLanguage::fetchList( true );
        }
        return $GLOBALS['eZContentLanguageMask'];
    }

    /*!
     \static
     Removes all memory cache forcing it to read from database again for next method calls.
    */
    function expireCache()
    {
        unset( $GLOBALS['eZContentLanguageList'],
               $GLOBALS['eZContentLanguagePrioritizedLanguages'],
               $GLOBALS['eZContentLanguageMask'],
               $GLOBALS['eZContentLanguageCronjobMode'] );
    }
}

?>
