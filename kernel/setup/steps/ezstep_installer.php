<?php
//
// Definition of EZStepInstaller class
//
// Created on: <08-Aug-2003 14:46:44 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezstep_class_definition.php
*/

/*!
  \class EZStepInstaller ezstep_class_definition.ph
  \brief The class EZStepInstaller provide a framework for eZStep installer classes

*/

define( 'EZ_SETUP_DB_ERROR_EMPTY_PASSWORD', 1 );
define( 'EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD', 2 );
define( 'EZ_SETUP_DB_ERROR_CONNECTION_FAILED', 3 );
define( 'EZ_SETUP_DB_ERROR_NOT_EMPTY', 4 );
define( 'EZ_SETUP_DB_ERROR_NO_DATABASES', 5 );
define( 'EZ_SETUP_DB_ERROR_NO_DIGEST_PROC', 6 );
define( 'EZ_SETUP_DB_ERROR_VERSION_INVALID', 7 );

class eZStepInstaller
{
    /*!
     Default constructor for eZ publish installer classes

    \param template
    \param http object
    \param ini settings object
    \param persistencelist, all previous posted data
    */
    function eZStepInstaller( &$tpl, &$http, &$ini, &$persistenceList,
                              $identifier, $name )
    {
        $this->Tpl =& $tpl;
        $this->Http =& $http;
        $this->Ini =& $ini;
        $this->PersistenceList =& $persistenceList;
        $this->Identifier = $identifier;
        $this->Name = $name;
        $this->INI =& eZINI::instance( 'kickstart.ini', '.' );
        $this->KickstartData = false;

        $this->PersistenceList['use_kickstart'][$identifier] = true;

        // If we have read data for this step earlier we do not use kickstart
        if ( isset( $this->PersistenceList['kickstart'][$identifier] ) and
             $this->PersistenceList['kickstart'][$identifier] )
        {
            $this->PersistenceList['use_kickstart'][$identifier] = false;
        }

        if ( $this->INI->hasGroup( $this->Identifier ) )
        {
            $this->KickstartData = $this->INI->group( $this->Identifier );
            $this->PersistenceList['kickstart'][$identifier] = true;
        }
    }

    /*!
     \virtual

     Processespost data from this class.
     \return  true if post data accepted, or false if post data is rejected.
    */
    function processPostData()
    {
    }

    /*!
     \virtual

    Performs test needed by this class.

    This class may access class variables to store data needed for viewing if output failed
    \return true if all tests passed and continue with next default step,
            number of next step if all tests passed and next step is "hard coded",
           false if tests failed
    */
    function init()
    {
    }

    /*!
    \virtual

    Display information and forms needed to pass this step.
    \return result to use in template
    */
    function &display()
    {
        return null;
    }

    function findAppropriateCharset( &$primaryLanguage, &$allLanguages, $canUseUnicode )
    {
        include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
        $allCharsets = array();
        for ( $i = 0; $i < count( $allLanguages ); ++$i )
        {
            $language =& $allLanguages[$i];
            $charsets = $language->allowedCharsets();
            foreach ( $charsets as $charset )
            {
                $charset = eZCharsetInfo::realCharsetCode( $charset );
                $allCharsets[] = $charset;
            }
        }
        $allCharsets = array_unique( $allCharsets );
//         eZDebug::writeDebug( $allCharsets, 'allCharsets' );
        $commonCharsets = $allCharsets;
        for ( $i = 0; $i < count( $allLanguages ); ++$i )
        {
            $language =& $allLanguages[$i];
            $charsets = $language->allowedCharsets();
            $realCharsets = array();
            foreach ( $charsets as $charset )
            {
                $charset = eZCharsetInfo::realCharsetCode( $charset );
                $realCharsets[] = $charset;
            }
            $realCharsets = array_unique( $realCharsets );
            $commonCharsets = array_intersect( $commonCharsets, $realCharsets );
        }
        $usableCharsets = array_values( $commonCharsets );
//         eZDebug::writeDebug( $usableCharsets, 'usableCharsets' );
        $charset = false;
        if ( count( $usableCharsets ) > 0 )
        {
            if ( in_array( $primaryLanguage->charset(), $usableCharsets ) )
                $charset = $primaryLanguage->charset();
            else // Pick the first charset
                $charset = $usableCharsets[0];
        }
        else
        {
            if ( $canUseUnicode )
            {
                $charset = eZCharsetInfo::realCharsetCode( 'utf-8' );
            }
//             else
//             {
//                 // Pick preferred primary language
//                 $charset = $primaryLanguage->charset();
//             }
        }
//         eZDebug::writeDebug( $charset, 'charset' );
        return $charset;
    }

    function availableSiteTypes()
    {
        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();
        return $siteTypes;
    }

    function extraDataList()
    {
        return array( 'title', 'url', 'database',
                      'access_type', 'access_type_value', 'admin_access_type_value',
                      'existing_database' );
    }

    function chosenSiteTypes()
    {
        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();
        $chosenSiteTypes = array();
        $extraList = $this->extraDataList();
        if ( isset( $this->PersistenceList['chosen_site_types'] ) )
        {
            foreach ( $this->PersistenceList['chosen_site_types'] as $siteTypeIdentifier )
            {
                if ( isset( $siteTypes[$siteTypeIdentifier] ) )
                {
                    $chosenSiteType = $siteTypes[$siteTypeIdentifier];
                    foreach ( $extraList as $extraItem )
                    {
                        if ( isset( $this->PersistenceList['site_extra_data_' . $extraItem][$siteTypeIdentifier] ) )
                        {
                            $chosenSiteType[$extraItem] = $this->PersistenceList['site_extra_data_' . $extraItem][$siteTypeIdentifier];
                        }
                    }
                    $chosenSiteTypes[] = $chosenSiteType;
                }
            }
        }
        return $chosenSiteTypes;
    }

    function selectSiteTypes( $chosenSiteTypes )
    {
        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();
        $siteIdentifiers = array();
        $chosenList = array();
        foreach ( $siteTypes as $siteTypeItem )
        {
            $siteIdentifier = $siteTypeItem['identifier'];
            if ( in_array( $siteIdentifier, $chosenSiteTypes ) )
                $chosenList[] = $siteIdentifier;
        }
        if ( count( $chosenList ) == 0 )
        {
            return false;
        }

        // Temporary hack which makes sure we only have one
        // chosen site.
        // This can be removed as soon as the setup wizard
        // support multiple sites again
        $chosenList = array_splice( $chosenList, 0, 1 );

        $this->PersistenceList['chosen_site_types'] = $chosenList;
        return true;
    }

    function storeSiteTypes( $siteTypes )
    {
        $extraList = $this->extraDataList();
        $siteList = array();
        foreach ( $siteTypes as $siteTypeItem )
        {
            $siteIdentifier = $siteTypeItem['identifier'];
            foreach ( $extraList as $extraItem )
            {
                if ( isset( $siteTypeItem[$extraItem] ) )
                {
                    $this->PersistenceList['site_extra_data_' . $extraItem][$siteIdentifier] = $siteTypeItem[$extraItem];
                }
            }
            $siteList[] = $siteIdentifier;
        }
        $this->PersistenceList['chosen_site_types'] = $siteList;
    }

    function storeExtraSiteData( $siteIdentifier, $dataIdentifier, $value )
    {
        if ( !isset( $this->PersistenceList['site_extra_data_' . $dataIdentifier] ) )
            $this->PersistenceList['site_extra_data_' . $dataIdentifier] = array();
        $this->PersistenceList['site_extra_data_' . $dataIdentifier][$siteIdentifier] = $value;
    }

    function extraData( $dataIdentifier )
    {
        if ( isset( $this->PersistenceList['site_extra_data_' . $dataIdentifier] ) )
            return $this->PersistenceList['site_extra_data_' . $dataIdentifier];
        return false;
    }

    function extraSiteData( $siteIdentifier, $dataIdentifier )
    {
        if ( isset( $this->PersistenceList['site_extra_data_' . $dataIdentifier][$siteIdentifier] ) )
            return $this->PersistenceList['site_extra_data_' . $dataIdentifier][$siteIdentifier];
        return false;
    }

    function checkDatabaseRequirements( $dbCharset = false, $overrideDBParameters = array() )
    {
        $result = array( 'error_code' => false,
                         'use_unicode' => false,
                         'db_version' => false,
                         'db_require_version' => false,
                         'status' => false );

        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];

        $dbDriver = $databaseInfo['info']['driver'];

        if ( $dbCharset === false )
            $dbCharset = 'iso-8859-1';
        $dbParameters = array( 'server' => $databaseInfo['server'],
                               'user' => $databaseInfo['user'],
                               'password' => $databaseInfo['password'],
                               'socket' => trim( $databaseInfo['socket'] ) == '' ? false : $databaseInfo['socket'],
                               'database' => $databaseInfo['database'],
                               'charset' => $dbCharset );
        $dbParameters = array_merge( $dbParameters, $overrideDBParameters );

        // PostgreSQL requires us to specify database name.
        // We use template1 here since it exists on all PostgreSQL installations.
        if( $dbParameters['database'] == '' and $this->PersistenceList['database_info']['type'] == 'pgsql' )
            $dbParameters['database'] = 'template1';

        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        $result['db_instance'] =& $db;
        $result['connected'] = $db->isConnected();
        if ( $db->isConnected() == false )
        {
            $result['error_code'] = EZ_SETUP_DB_ERROR_CONNECTION_FAILED;
            return $result;
        }

        // Check if the version of the database fits the minimum required
        $dbVersion = $db->databaseServerVersion();
        $result['db_version'] = $dbVersion['string'];
        $result['db_required_version'] = $databaseInfo['info']['required_version'];
        if ( $dbVersion != null )
        {
            if ( version_compare( $result['db_version'], $databaseInfo['info']['required_version'] ) == -1 )
            {
                $result['connected'] = false;
                $result['error_code'] = EZ_SETUP_DB_ERROR_VERSION_INVALID;
                return $result;
            }
        }

        // If we have PostgreSQL we need to make sure we have the 'digest' procedure available.
        if ( $db->databaseName() == 'postgresql' and $dbParameters['database'] != 'template1' )
        {
            $sql = "SELECT count(*) AS count FROM pg_proc WHERE proname='digest'";
            $rows = $db->arrayQuery( $sql );
            $count = $rows[0]['count'];
            // If it is 0 we don't have it
            if ( $count == 0 )
            {
                $result['error_code'] = EZ_SETUP_DB_ERROR_NO_DIGEST_PROC;
                return $result;
            }
        }

        $result['use_unicode'] = false;
        if ( $db->isCharsetSupported( 'utf-8' ) )
        {
            $result['use_unicode'] = true;
        }

        $result['status'] = true;
        return $result;
    }

    function databaseErrorInfo( $errorInfo )
    {
        $code = $errorInfo['error_code'];
        $dbError = false;

        switch ( $code )
        {
            case EZ_SETUP_DB_ERROR_CONNECTION_FAILED:
            {
                if ( $errorInfo['database_info']['type'] == 'pgsql' )
                {
                    $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                        'Please make sure that the username and the password is correct. Verify that your PostgreSQL database is configured correctly.'
                                                        .'<br>See the PHP documentation for more information about this.'
                                                        .'<br>Remember to start postmaster with the -i option.'
                                                        .'<br>Note that PostgreSQL 7.2 is not supported.' ),
                                      'url' => array( 'href' => 'http://www.php.net/manual/en/ref.pgsql.php',
                                                      'text' => 'PHP documentation' ),
                                      'number' => EZ_SETUP_DB_ERROR_CONNECTION_FAILED );
                }
                else
                {
                    $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                        'The database would not accept the connection, please review your settings and try again.' ),
                                  'url' => false,
                                      'number' => EZ_SETUP_DB_ERROR_CONNECTION_FAILED );
                }

                break;
            }
            case EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'Password entries did not match.' ),
                                  'url' => false,
                                  'number' => EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD );
                break;
            }
            case EZ_SETUP_DB_ERROR_NOT_EMPTY:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'The selected database was not empty, please choose from the alternatives below.' ),
                                  'url' => false,
                                  'number' => EZ_SETUP_DB_ERROR_NOT_EMPTY );
                $dbNotEmpty = 1;
                break;
            }
            case EZ_SETUP_DB_ERROR_NO_DATABASES:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'The selected selected user has not got access to any databases. Change user or create a database for the user.' ),
                                  'url' => false,
                                  'number' => EZ_SETUP_DB_ERROR_NO_DATABASES );
                break;
            }

            case EZ_SETUP_DB_ERROR_NO_DIGEST_PROC:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    "The 'digest' procedure is not available in your database, you cannot run eZ publish without this. Visit the FAQ for more information." ),
                                  'url' => array( 'href' => 'http://ez.no/ez_publish/documentation/faq/database/what_is_the_reason_i_get_error_function_digest_character_varying_does_not_exist_on_postgresql',
                                                  'text' => 'PostgreSQL digest FAQ' ),
                                  'number' => EZ_SETUP_DB_ERROR_NO_DATABASES );
                break;
            }

            case EZ_SETUP_DB_ERROR_VERSION_INVALID:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    "Your database version %version does not fit the minimum requirement which is %req_version.
See the requirements page for more information.",
                                                    null,
                                                    array( '%version' => $errorInfo['database_info']['version'],
                                                           '%req_version' => $errorInfo['database_info']['required_version'] ) ),
                                  'url' => array( 'href' => 'http://ez.no/ez_publish/documentation/general_information/what_is_ez_publish/ez_publish_requirements',
                                                  'text' => 'eZ publish requirements' ),
                                  'number' => EZ_SETUP_DB_ERROR_NO_DATABASES );
                break;
            }
        }

        return $dbError;
    }

    /*!
     \return \c true if the step has kickstart data available.
    */
    function hasKickstartData()
    {
        if ( !$this->isKickstartAllowed() )
            return false;
        return $this->KickstartData !== false;
    }

    /*!
     \return All kickstart data as an associative array
    */
    function kickstartData()
    {
        return $this->KickstartData;
    }

    /*!
     \return \c true if kickstart functionality can be used.
    */
    function isKickstartAllowed()
    {
        $identifier = $this->Identifier;
        if ( isset( $this->PersistenceList['use_kickstart'][$identifier] ) and
             !$this->PersistenceList['use_kickstart'][$identifier] )
            return false;

        if ( isset( $GLOBALS['eZStepAllowKickstart'] ) )
            return $GLOBALS['eZStepAllowKickstart'];

        return true;
    }

    /*!
     \return \c true if the kickstart functionality should continue to the next step.
    */
    function kickstartContinueNextStep()
    {
        if ( isset( $this->KickstartData['Continue'] ) and
             $this->KickstartData['Continue'] == 'true' )
            return true;
        return false;
    }

    /*!
     Sets whether kickstart data can be checked or not.
    */
    function setAllowKickstart( $allow )
    {
        $GLOBALS['eZStepAllowKickstart'] = $allow;
    }

    var $Tpl;
    var $Http;
    var $Ini;
    var $PersistenceList;
    // The identifier of the current step
    var $Identifier;
    // The name of the current step
    var $Name;
    /// Kickstart INI file, if one is found
    var $INI;
    /// The kickstart data as an associative array or \c false if no data available
    var $KickstartData;
}

?>
