<?php
//
// Definition of EZStepInstaller class
//
// Created on: <08-Aug-2003 14:46:44 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezstep_class_definition.php
*/

/*!
  \class eZStepInstaller ezstep_class_definition.ph
  \brief The class EZStepInstaller provide a framework for eZStep installer classes

*/

class eZStepInstaller
{
    const DB_ERROR_EMPTY_PASSWORD = 1;
    const DB_ERROR_NONMATCH_PASSWORD = 2;
    const DB_ERROR_CONNECTION_FAILED = 3;
    const DB_ERROR_NOT_EMPTY = 4;
    const DB_ERROR_NO_DATABASES = 5;
    const DB_ERROR_NO_DIGEST_PROC = 6;
    const DB_ERROR_VERSION_INVALID = 7;
    const DB_ERROR_CHARSET_DIFFERS = 8;
    const DB_ERROR_ALREADY_CHOSEN = 10;

    const DB_DATA_APPEND = 1;
    const DB_DATA_REMOVE = 2;
    const DB_DATA_KEEP = 3;
    const DB_DATA_CHOOSE = 4;

    /*!
     Default constructor for eZ Publish installer classes

    \param template
    \param http object
    \param ini settings object
    \param persistencelist, all previous posted data
    */
    function eZStepInstaller( $tpl, $http, $ini, &$persistenceList,
                              $identifier, $name )
    {
        $this->Tpl = $tpl;
        $this->Http = $http;
        $this->Ini = $ini;
        $this->PersistenceList =& $persistenceList;
        $this->Identifier = $identifier;
        $this->Name = $name;
        $this->INI = eZINI::instance( 'kickstart.ini', '.' );
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
    function display()
    {
        $result = array();
        return $result;
    }

    function findAppropriateCharset( $primaryLanguage, $allLanguages, $canUseUnicode )
    {
        //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
        $commonCharsets = array();

        if ( is_array( $allLanguages ) and count( $allLanguages ) > 0 )
        {

            $language = $allLanguages[ 0 ];
            $charsets = $language->allowedCharsets();
            foreach ( $charsets as $charset )
            {
                $commonCharsets[] = eZCharsetInfo::realCharsetCode( $charset );
            }
            $commonCharsets = array_unique( $commonCharsets );

            for ( $i = 1; $i < count( $allLanguages ); ++$i )
            {
                $language = $allLanguages[$i];
                $charsets = $language->allowedCharsets();
                $realCharsets = array();
                foreach ( $charsets as $charset )
                {
                    $realCharsets[] = eZCharsetInfo::realCharsetCode( $charset );
                }
                $realCharsets = array_unique( $realCharsets );
                $commonCharsets = array_intersect( $commonCharsets, $realCharsets );
            }
        }
        $usableCharsets = array_values( $commonCharsets );
        $charset = false;
        if ( count( $usableCharsets ) > 0 )
        {
            if ( in_array( eZCharsetInfo::realCharsetCode( $primaryLanguage->charset() ), $usableCharsets ) )
                $charset = eZCharsetInfo::realCharsetCode( $primaryLanguage->charset() );
            else // Pick the first charset
                $charset = $usableCharsets[0];
        }
        else
        {
            if ( $canUseUnicode )
            {
                $charset = eZCharsetInfo::realCharsetCode( 'utf-8' );
            }
//          else
//          {
//              // Pick preferred primary language
//              $charset = $primaryLanguage->charset();
//          }
        }
        return $charset;
    }

    function findAppropriateCharsetsList( $primaryLanguage, $allLanguages, $canUseUnicode )
    {
        //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
        $commonCharsets = array();

        if ( is_array( $allLanguages ) and count( $allLanguages ) > 0 )
        {

            $language = $allLanguages[ 0 ];
            $charsets = $language->allowedCharsets();
            foreach ( $charsets as $charset )
            {
                $commonCharsets[] = eZCharsetInfo::realCharsetCode( $charset );
            }
            $commonCharsets = array_unique( $commonCharsets );

            for ( $i = 1; $i < count( $allLanguages ); ++$i )
            {
                $language = $allLanguages[$i];
                $charsets = $language->allowedCharsets();
                $realCharsets = array();
                foreach ( $charsets as $charset )
                {
                    $realCharsets[] = eZCharsetInfo::realCharsetCode( $charset );
                }
                $realCharsets = array_unique( $realCharsets );
                $commonCharsets = array_intersect( $commonCharsets, $realCharsets );
            }
        }
        $usableCharsets = array_values( $commonCharsets );

        if ( count( $usableCharsets ) > 0 )
        {
            if ( in_array( $primaryLanguage->charset(), $usableCharsets ) )
            {
                array_unshift( $usableCharsets, $primaryLanguage->charset() );
                $usableCharsets = array_unique( $usableCharsets );
            }
        }
        else
        {
            if ( $canUseUnicode )
            {
                $usableCharsets[] = eZCharsetInfo::realCharsetCode( 'utf-8' );
            }
        }

        return $usableCharsets;
    }

    function availableSitePackages()
    {
        //include_once( 'kernel/classes/ezpackage.php' );
        $packageList = eZPackage::fetchPackages( array(), array( 'type' => 'site' ) );

        return $packageList;
    }

    function extraDataList()
    {
        return array( 'title', 'url', 'database',
                      'access_type', 'access_type_value', 'admin_access_type_value',
                      'existing_database' );
    }

    function chosenSitePackage()
    {
        if ( isset( $this->PersistenceList['chosen_site_package']['0'] ) )
        {
            return $this->PersistenceList['chosen_site_package']['0'];
        }
        else
            return false;
    }

    function chosenSiteType()
    {
        if ( isset( $this->PersistenceList['chosen_site_package']['0'] ) )
        {
            $siteTypeIdentifier = $this->PersistenceList['chosen_site_package']['0'];
            $chosenSiteType['identifier'] = $siteTypeIdentifier;
            $extraList = $this->extraDataList();

            foreach ( $extraList as $extraItem )
            {
                if ( isset( $this->PersistenceList['site_extra_data_' . $extraItem][$siteTypeIdentifier] ) )
                {
                    $chosenSiteType[$extraItem] = $this->PersistenceList['site_extra_data_' . $extraItem][$siteTypeIdentifier];
                }
            }
        }
        return $chosenSiteType;
    }

    function selectSiteType( $sitePackageName )
    {
        //include_once( 'kernel/classes/ezpackage.php' );

        $package = eZPackage::fetch( $sitePackageName );
        if ( !$package )
            return false;

        $this->PersistenceList['chosen_site_package']['0'] = $sitePackageName;

        $this->PersistenceList['site_extra_data_title'][$sitePackageName] = $package->attribute('summary');
        return true;
    }

    function storeSiteType( $siteType )
    {
        $extraList = $this->extraDataList();
        $siteIdentifier = $siteType['identifier'];
        foreach ( $extraList as $extraItem )
        {
            if ( isset( $siteType[$extraItem] ) )
            {
                $this->PersistenceList['site_extra_data_' . $extraItem][$siteIdentifier] = $siteType[$extraItem];
            }
        }
        $this->PersistenceList['chosen_site_package']['0'] = $siteIdentifier;
        if ( $this->hasKickstartData() )
            $this->storePersistenceData();
    }

    function storePersistenceData()
    {
        //include_once( 'kernel/setup/ezsetupcommon.php' );
        foreach ( $this->PersistenceList as $key => $value )
        {
            eZSetupSetPersistencePostVariable( $key, $value );
        }
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
                         'site_charset' => false,
                         'status' => false );

        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];

        $dbDriver = $databaseInfo['info']['driver'];

        if ( $dbCharset === false )
            $dbCharset = 'iso-8859-1';
        $dbParameters = array( 'server' => $databaseInfo['server'],
                               'port' => $databaseInfo['port'],
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

        $db = eZDB::instance( $dbDriver, $dbParameters, true );
        $result['db_instance'] = $db;
        $result['connected'] = $db->isConnected();
        if ( $db->isConnected() == false )
        {
            $result['error_code'] = self::DB_ERROR_CONNECTION_FAILED;
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
                $result['error_code'] = self::DB_ERROR_VERSION_INVALID;
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
                $result['error_code'] = self::DB_ERROR_NO_DIGEST_PROC;
                return $result;
            }
        }

        $result['use_unicode'] = false;
        if ( $db->isCharsetSupported( 'utf-8' ) )
        {
            $result['use_unicode'] = true;
        }

        // If we regional info we can start checking the charset
        if ( isset( $this->PersistenceList['regional_info'] ) )
        {
            if ( isset( $this->PersistenceList['regional_info']['site_charset'] ) and
                 strlen( $this->PersistenceList['regional_info']['site_charset'] ) > 0 )
            {
                $charsetsList = array( $this->PersistenceList['regional_info']['site_charset'] );
            }
            else
            {
                // Figure out charset automatically if it is not set yet
                //include_once( 'lib/ezlocale/classes/ezlocale.php' );
                $primaryLanguage     = null;
                $allLanguages        = array();
                $allLanguageCodes    = array();
                $variationsLanguages = array();
                $primaryLanguageCode = $this->PersistenceList['regional_info']['primary_language'];
                $extraLanguageCodes  = isset( $this->PersistenceList['regional_info']['languages'] ) ? $this->PersistenceList['regional_info']['languages'] : array();
                $extraLanguageCodes  = array_diff( $extraLanguageCodes, array( $primaryLanguageCode ) );

                /*
                if ( isset( $this->PersistenceList['regional_info']['variations'] ) )
                {
                    $variations = $this->PersistenceList['regional_info']['variations'];
                    foreach ( $variations as $variation )
                    {
                        $locale = eZLocale::create( $variation );
                        if ( $locale->localeCode() == $primaryLanguageCode )
                        {
                            $primaryLanguage = $locale;
                        }
                        else
                        {
                            $variationsLanguages[] = $locale;
                        }
                    }
                }
                */

                if ( $primaryLanguage === null )
                    $primaryLanguage = eZLocale::create( $primaryLanguageCode );

                $allLanguages[] = $primaryLanguage;

                foreach ( $extraLanguageCodes as $extraLanguageCode )
                {
                    $allLanguages[] = eZLocale::create( $extraLanguageCode );
                    $allLanguageCodes[] = $extraLanguageCode;
                }

                $charsetsList = $this->findAppropriateCharsetsList( $primaryLanguage, $allLanguages, $result['use_unicode'] );
            }

            $checkedCharset = $db->checkCharset( $charsetsList, $currentCharset );
            if ( $checkedCharset === false )
            {
                // If the current charset is utf-8 we use that instead
                // since it can represent any character possible in the chosen languages
                if ( $currentCharset == 'utf-8' )
                {
                    $charset = 'utf-8';
                    $result['site_charset'] = $charset;
                }
                else
                {
                    $result['connected'] = false;
                    $this->PersistenceList['database_info']['requested_charset'] = implode( ", ", $charsetsList );
                    $this->PersistenceList['database_info']['current_charset'] = $currentCharset;
                    $result['error_code'] = self::DB_ERROR_CHARSET_DIFFERS;
                    return $result;
                }
            }
            else if ( $checkedCharset === true )
            {
                $result['site_charset'] = $charsetsList[ 0 ];
            }
            else
            {
                $result['site_charset'] = $checkedCharset;
            }
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
            case self::DB_ERROR_CONNECTION_FAILED:
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
                                      'number' => self::DB_ERROR_CONNECTION_FAILED );
                }
                else
                {
                    $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                        'The database would not accept the connection, please review your settings and try again.' ),
                                  'url' => false,
                                      'number' => self::DB_ERROR_CONNECTION_FAILED );
                }

                break;
            }
            case self::DB_ERROR_NONMATCH_PASSWORD:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'Password entries did not match.' ),
                                  'url' => false,
                                  'number' => self::DB_ERROR_NONMATCH_PASSWORD );
                break;
            }
            case self::DB_ERROR_NOT_EMPTY:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'The selected database was not empty, please choose from the alternatives below.' ),
                                  'url' => false,
                                  'number' => self::DB_ERROR_NOT_EMPTY );
                $dbNotEmpty = 1;
                break;
            }
            case self::DB_ERROR_NO_DATABASES:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    'The selected user has not got access to any databases. Change user or create a database for the user.' ),
                                  'url' => false,
                                  'number' => self::DB_ERROR_NO_DATABASES );
                break;
            }

            case self::DB_ERROR_NO_DIGEST_PROC:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    "The 'digest' function is not available in your database, you cannot run eZ Publish without this. See the documentation for more information." ),
                                  'url' => array( 'href' => 'http://ez.no/doc/ez_publish/technical_manual/current/installation/normal_installation/requirements_for_doing_a_normal_installation#digest_function',
                                                  'text' => 'PostgreSQL digest FAQ' ),
                                  'number' => self::DB_ERROR_NO_DATABASES );
                break;
            }

            case self::DB_ERROR_VERSION_INVALID:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    "Your database version %version does not fit the minimum requirement which is %req_version.
See the requirements page for more information.",
                                                    null,
                                                    array( '%version' => $errorInfo['database_info']['version'],
                                                           '%req_version' => $errorInfo['database_info']['required_version'] ) ),
                                  'url' => array( 'href' => 'http://ez.no/ez_publish/documentation/general_information/what_is_ez_publish/ez_publish_requirements',
                                                  'text' => 'eZ Publish requirements' ),
                                  'number' => self::DB_ERROR_NO_DATABASES );
                break;
            }

            case self::DB_ERROR_CHARSET_DIFFERS:
            {
                $dbError = array( 'text' => ezi18n( 'design/standard/setup/init',
                                                    "The database [%database_name] cannot be used, the setup wizard wants to create the site in [%req_charset] but the database has been created using character set [%charset]. You will have to choose a database having support for [%req_charset] or modify [%database_name] .",
                                                    null,
                                                    array( '%database_name' => $errorInfo['site_type']['database'],
                                                           '%charset' => $errorInfo['database_info']['current_charset'],
                                                           '%req_charset' => $errorInfo['database_info']['requested_charset'] ) ),
                                  'url' => false,
                                  'number' => self::DB_ERROR_CHARSET_DIFFERS );
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

    /*!
     \return Urls to access user and admin siteaccesses
    */
    function siteaccessURLs()
    {
        $siteType = $this->chosenSiteType();

        $url = $siteType['url'];
        if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
        {
            $url = 'http://' . $url;
        }
        $currentURL = $url;
        $adminURL = $url;

        if ( $siteType['access_type'] == 'url' )
        {
            $ini = eZINI::instance();
            if ( $ini->hasVariable( 'SiteSettings', 'DefaultAccess' ) )
            {
                $siteType['access_type_value'] = $ini->variable( 'SiteSettings', 'DefaultAccess' );
            }

            $url .= '/' . $siteType['access_type_value'];
            $adminURL .= '/' . $siteType['admin_access_type_value'];
        }
        else if ( $siteType['access_type'] == 'hostname' )
        {
            $url = $siteType['access_type_value'];
            $adminURL = $siteType['admin_access_type_value'];
            if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
            {
                $url = 'http://' . $url;
            }
            if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $adminURL ) )
            {
                $adminURL = 'http://' . $adminURL;
            }
            $url .= eZSys::indexDir( false );
            $adminURL .= eZSys::indexDir( false );
        }
        else if ( $siteType['access_type'] == 'port' )
        {
            $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $siteType['access_type_value'] ) );
            $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $siteType['admin_access_type_value'] ) );
        }

        $siteaccessURL = array( 'url' => $url,
                                'admin_url' => $adminURL );

        return $siteaccessURL;
    }

    public $Tpl;
    public $Http;
    public $Ini;
    public $PersistenceList;
    // The identifier of the current step
    public $Identifier;
    // The name of the current step
    public $Name;
    /// Kickstart INI file, if one is found
    public $INI;
    /// The kickstart data as an associative array or \c false if no data available
    public $KickstartData;
}

?>
