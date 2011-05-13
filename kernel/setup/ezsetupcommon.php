<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

// This file holds shared functions for the ezsetup files

/*!
 \return an array with tests that need to be run
         and succeed for the setup to continue.
*/
function eZSetupCriticalTests()
{
    $ini = eZINI::instance();
    return $ini->variableArray( 'SetupSettings', 'CriticalTests' );
}

/*!
 \return an array with tests that when run will give information on finetuning.
*/
function eZSetupOptionalTests()
{
    $ini = eZINI::instance();
    return $ini->variableArray( 'SetupSettings', 'OptionalTests' );
}

function eZSetupDatabaseMap()
{
    return array( 'mysqli' => array( 'type' => 'mysqli',
                                     'driver' => 'ezmysqli',
                                     'name' => 'MySQL Improved',
                                     'required_version' => '4.1.1',
                                     'has_demo_data' => true,
                                     'supports_unicode' => true ),
                  'mysql' => array( 'type' => 'mysql',
                                    'driver' => 'ezmysql',
                                    'name' => 'MySQL (Deprecated)',
                                    'required_version' => '4.1.1',
                                    'has_demo_data' => true,
                                    'supports_unicode' => false ),
                  'pgsql' => array( 'type' => 'pgsql',
                                    'driver' => 'ezpostgresql',
                                    'name' => 'PostgreSQL',
                                    'required_version' => '8.0',
                                    'has_demo_data' => false,
                                    'supports_unicode' => true ),
                   );
}

function eZSetupFetchPersistenceList()
{
    $persistenceList = array();
    $http = eZHTTPTool::instance();
    $postVariables = $http->attribute( 'post' );

    foreach ( $postVariables as $name => $value )
    {
        if ( preg_match( '/^P_([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)$/', $name, $matches ) )
        {
            $persistenceGroup = $matches[1];
            $persistenceName = $matches[2];
            $persistenceList[$persistenceGroup][$persistenceName] = $value;
        }
    }

    return $persistenceList;
}

function eZSetupSetPersistencePostVariable( $var, $value )
{
    $http = eZHTTPTool::instance();
    if ( is_array( $value ) )
    {
        foreach ( $value as $valueKey => $valueItem )
        {
            $http->setPostVariable( 'P_' . $var . '-' . $valueKey, $valueItem );
        }
    }
    else
    {
        $http->setPostVariable( 'P_' . $var . '-0', $value );
    }
}

function eZSetupMergePersistenceList( &$persistenceList, $persistenceDataList )
{
    foreach ( $persistenceDataList as $persistenceData )
    {
        $persistenceName = $persistenceData[0];
        $persistenceValues = $persistenceData[1];
        if ( !isset( $persistenceList[$persistenceName] ) )
        {
            $values =& $persistenceList[$persistenceName];
            foreach ( $persistenceValues as $persistenceValueName => $persistenceValueData )
            {
                $values[$persistenceValueName] = $persistenceValueData['value'];
            }
        }
        else
        {
            $oldValues =& $persistenceList[$persistenceName];
            foreach ( $persistenceValues as $persistenceValueName => $persistenceValueData )
            {
                if ( !isset( $oldValues[$persistenceValueName] ) )
                {
                    $oldValues[$persistenceValueName] = $persistenceValueData['value'];
                }
                else if ( is_array( $persistenceValueData['value'] ) and
                          isset( $persistenceValueData['merge'] ) and
                          $persistenceValueData['merge'] )
                {
                     $merged = array_merge( $oldValues[$persistenceValueName], $persistenceValueData['value'] );
                     if ( isset( $persistenceValueData['unique'] ) and
                          $persistenceValueData['unique'] )
                          $merged = array_unique( $merged );
                     $oldValues[$persistenceValueName] = $merged;
                }
                else
                {
                    $oldValues[$persistenceValueName] = $persistenceValueData['value'];
                }
            }
        }
    }
}

function eZSetupLanguageList( &$languageList, &$defaultLanguage, &$defaultExtraLanguages )
{
    $locales = eZLocale::localeList( true );
    $languageList = array();
    $httpMap   = array();
    $httpMapShort = array();
    // This alias array must be filled in with known names.
    // The key is the value from the locale INI file (HTTP group)
    // and the value is the HTTP alias.
    $httpAliases = array( 'no-bokmaal' => 'nb',
                          'no-nynorsk' => 'nn',
                          'ru-ru' => 'ru' );

    foreach ( array_keys( $locales ) as $localeKey )
    {
        $locale =& $locales[$localeKey];
        if ( !$locale->attribute( 'country_variation' ) )
        {
            $languageList[] = $locale;
            $httpLocale = strtolower( $locale->httpLocaleCode() );
            $httpMap[$httpLocale] = $locale;
            list( $httpLocaleShort ) = explode( '-', $httpLocale );
            $httpMapShort[$httpLocale] = $locale;
            if ( isset( $httpAliases[$httpLocale] ) )
            {
                $httpMapShort[$httpAliases[$httpLocale]] = $locale;
            }
        }
    }

    // bubble sort language based on language name. bubble bad, but only about 8-9 elements
    for ( $i =0; $i < count( $languageList ); $i++ )
        for ( $n = 0; $n < count( $languageList ) - 1; $n++ )
        {
            if ( strcmp( $languageList[$n]->attribute( 'language_name' ), $languageList[$n+1]->attribute( 'language_name' ) ) > 0 )
            {
                $tmpElement = $languageList[$n];
                $languageList[$n] = $languageList[$n+1];
                $languageList[$n+1] = $tmpElement;
            }
        }

    $defaultLanguage = false;
    $defaultExtraLanguages = array();
    if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) )
    {
        $acceptLanguages = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
        foreach ( $acceptLanguages as $acceptLanguage )
        {
            list( $acceptLanguageCode ) = explode( ';', $acceptLanguage );
            $acceptLanguageCode = strtolower( $acceptLanguageCode );
            $languageCode = false;
            if ( isset( $httpMap[$acceptLanguageCode] ) )
            {
                $languageCode = $httpMap[$acceptLanguageCode]->localeCode();
            }
            elseif ( isset( $httpMapShort[$acceptLanguageCode] ) )
            {
                $languageCode = $httpMapShort[$acceptLanguageCode]->localeCode();
            }
            if ( $languageCode )
            {
                if ( $defaultLanguage === false )
                {
                    $defaultLanguage = $languageCode;
                }
                /*
                else
                {
                    $defaultExtraLanguages[] = $languageCode;
                }
                */
            }
        }
    }
    if ( $defaultLanguage === false )
    {
        $defaultLanguage = 'eng-GB';
    }
    $defaultExtraLanguages = array_unique( array_diff( $defaultExtraLanguages, array( $defaultLanguage ) ) );
}

?>
