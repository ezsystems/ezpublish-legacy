<?php
//
// Definition of eZStepCreateSites class
//
// Created on: <13-Aug-2003 19:54:38 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/
require_once( "kernel/common/i18n.php" );
/*!
  Error codes:

  EZSW-001: Failed to load database schema file
  EZSW-002: Failed to load database data file
  EZSW-003: Failed to initialize database schema
  EZSW-004: Failed to initialize database data
  EZSW-005: Failed to connect to database
  EZSW-006: Failed to initialize datatype related database data

  EZSW-020: Failed to fetch administrator user object
  EZSW-021: Failed to fetch administrator content object
  EZSW-022: Failed to create new version for administrator object
  EZSW-023: Failed to find first_name field of administrator object
  EZSW-024: Failed to find last_name field of administrator object
  EZSW-025: Failed to publish administrator object

  EZSW-040: Failed to initialize the site package <package>
  EZSW-041: Could not fetch site package <package>

  EZSW-050: Could not fetch addon package <package>
  EZSW-051: Could not install addon package <package>

  EZSW-060: Could not fetch style package <package>

  EZSW-070: Could not create ezpreference for <user_id>

*/


/*!
  \class eZStepCreateSites ezstep_create_sites.php
  \brief The class eZStepCreateSites does

*/

class eZStepCreateSites extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepCreateSites( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'create_sites', 'Create sites' );
    }

    function processPostData()
    {
        return true; // Always proceede
    }

    function init()
    {
        $this->Error = array( 'errors' => array()  );

        set_time_limit( 10*60 );

        $siteType = $this->chosenSiteType();

        // If we are installing a package without extension packages we
        // generate the autoload array for extensions.
        // For the time being we only do this for the plain_site package.
        if ( $siteType['identifier'] == 'plain_site' )
        {
            ezpAutoloader::updateExtensionAutoloadArray();
        }

        $saveData = true; // set to true to save data

        //$ini = eZINI::create();

        $accessMap = array( 'url' => array(),
                            'hostname' => array(),
                            'port' => array(),
                            'accesses' => array() );

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

        // If we have already figured out charset we used that
        if ( isset( $this->PersistenceList['regional_info']['site_charset'] ) and
             strlen( $this->PersistenceList['regional_info']['site_charset'] ) > 0 )
        {
            $charset = $this->PersistenceList['regional_info']['site_charset'];
        }
        else
        {
            // Figure out charset automatically if it is not set yet
            $canUseUnicode = $this->PersistenceList['database_info']['use_unicode'];
            $charset = $this->findAppropriateCharset( $primaryLanguage, $allLanguages, $canUseUnicode );
        }

        if ( !$charset )
        {
            // Make sure kickstart functionality stops
            $this->setAllowKickstart( false );
            return 'LanguageOptions';
        }
        else
        {
            $i18nINI = eZINI::create( 'i18n.ini' );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $i18nINI->setReadOnlySettingsCheck( false );

            $i18nINI->setVariable( 'CharacterSettings', 'Charset', $charset );
            $i18nINI->save( false, '.php', 'append', true );
        }

        $siteINISettings = array();
        $result = true;

            $accessType = $siteType['access_type'];
            $resultArray = array( 'errors' => array() );

            $result = $this->initializePackage( $siteType, $accessMap, $charset,
                                                $allLanguageCodes, $allLanguages, $primaryLanguage, $this->PersistenceList['admin'],
                                                $resultArray );
            if ( !$result )
            {
                $this->Error['errors'] = array_merge( $this->Error['errors'], $resultArray['errors'] );
                $this->Error['errors'][] = array( 'code' => 'EZSW-040',
                                                  'text' => "Failed to initialize site package '" . $siteType['identifier'] . "'" );
                //$result = false;
                return false;
            }

            if ( $resultArray['common_settings'] )
            {
                $extraCommonSettings = $resultArray['common_settings'];

                foreach ( $extraCommonSettings as $extraSetting )
                {
                    if ( $extraSetting === false )
                        continue;

                    $iniName = $extraSetting['name'];
                    $settings = $extraSetting['settings'];
                    $resetArray = false;
                    if ( isset( $extraSetting['reset_arrays'] ) )
                        $resetArray = $extraSetting['reset_arrays'];

                    if ( $iniName == 'site.ini' )
                    {
                        $siteINISettings[] = $settings;
                        continue;
                    }

                    if ( file_exists( 'settings/override/' . $iniName . '.append' ) ||
                         file_exists( 'settings/override/' . $iniName . '.append.php' ) )
                    {
                        $tmpINI = eZINI::instance( $iniName, 'settings/override', null, null, false, true );
                    }
                    else
                    {
                        $tmpINI = eZINI::create( $iniName );
                    }
                    // Set ReadOnlySettingsCheck to false: towards
                    // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
                    $tmpINI->setReadOnlySettingsCheck( false );
                    $tmpINI->setVariables( $settings );
                    $tmpINI->save( false, '.append.php', false, true, "settings/override", $resetArray );
                }
            }

        if ( !$result )
        {
            // Display errors
            return false;
        }

        $ini = eZINI::instance();
        // Set ReadOnlySettingsCheck to false: towards
        // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
        $ini->setReadOnlySettingsCheck( false );

        $regionalInfo = $this->PersistenceList['regional_info'];
        $emailInfo = $this->PersistenceList['email_info'];

        $imageINI = eZINI::create( 'image.ini' );
        // Set ReadOnlySettingsCheck to false: towards
        // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
        $imageINI->setReadOnlySettingsCheck( false );

        $imageINI->setVariable( 'ImageMagick', 'IsEnabled', 'false' );
        if ( $this->PersistenceList['imagemagick_program']['result'] )
        {
            $imageINI->setVariable( 'ImageMagick', 'ExecutablePath', $this->PersistenceList['imagemagick_program']['path'] );
            $imageINI->setVariable( 'ImageMagick', 'Executable', $this->PersistenceList['imagemagick_program']['program'] );

            $imageINI->setVariable( 'ImageMagick', 'IsEnabled', 'true' );
        }

        $saveResult = false;
        if ( !$saveData )
            $saveResult = true;
        if ( $saveData )
            $saveResult = $imageINI->save( false, '.php', 'append', true );

        if ( $saveResult and
             $charset !== false )
        {
            /*$i18nINI = eZINI::create( 'i18n.ini' );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $i18nINI->setReadOnlySettingsCheck( false );

            $i18nINI->setVariable( 'CharacterSettings', 'Charset', $charset );
            if ( $saveData )
                $saveResult = $i18nINI->save( false, '.php', 'append', true );
            */
        }

        switch ( $accessType )
        {
            case 'port':
            {
                $matchOrder = 'port';
            } break;
            case 'hostname':
            {
                $matchOrder = 'host';
            } break;
            case 'url':
            default:
            {
                $matchOrder = 'uri';
            } break;
        }
        $hostMatchMapItems = array();
        foreach ( $accessMap['hostname'] as $hostName => $siteAccessName )
        {
            $hostMatchMapItems[] = $hostName . ';' . $siteAccessName;
        }
        $portMatchMapItems = array();
        foreach ( $accessMap['port'] as $port => $siteAccessName )
        {
            $portMatchMapItems[$port] = $siteAccessName;
        }
        $ini->setVariable( 'SiteAccessSettings', 'MatchOrder', $matchOrder );
        $ini->setVariable( 'SiteAccessSettings', 'HostMatchMapItems', $hostMatchMapItems );
        foreach ( $portMatchMapItems as $port => $siteAccessName )
        {
            $ini->setVariable( 'PortAccessSettings', $port, $siteAccessName );
        }
        $ini->setVariable( 'SiteSettings', 'SiteList', $accessMap['sites'] );
        $ini->setVariable( 'SiteAccessSettings', 'AvailableSiteAccessList', $accessMap['accesses'] );
        $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );
        $ini->setVariable( 'Session', 'SessionNameHandler', 'custom' );
        $ini->setVariable( 'MailSettings', 'AdminEmail', $this->PersistenceList['admin']['email'] );
        $ini->setVariable( 'MailSettings', 'EmailSender', false );

        $defaultAccess = 'admin';
        if ( isset( $accessMap['accesses'][0] ) )
            $defaultAccess = $accessMap['accesses'][0];
        $ini->setVariable( 'SiteSettings', 'DefaultAccess', $defaultAccess );

        if ( $emailInfo['type'] == 1 )
        {
            $ini->setVariable( 'MailSettings', 'Transport', 'sendmail' );
        }
        else
        {
            $ini->setVariable( 'MailSettings', 'Transport', 'SMTP' );
            $ini->setVariable( 'MailSettings', 'TransportServer', $emailInfo['server'] );
            $ini->setVariable( 'MailSettings', 'TransportUser', $emailInfo['user'] );
            $ini->setVariable( 'MailSettings', 'TransportPassword', $emailInfo['password'] );
        }

        if ( $saveData )
        {
            foreach ( $siteINISettings as $siteINISetting )
            {
                $ini->setVariables( $siteINISetting );
            }
        }

        if ( $saveData )
        {
            $saveResult = $ini->save( false, '.php', 'append', true, true, true );
        }

        return true; // Never show, generate sites
    }

    function display()
    {
        $errors = array();
        if ( is_array( $this->Error ) )
        {
            $errors = $this->Error['errors'];
        }

        $this->Tpl->setVariable( 'error_list', $errors );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/create_sites.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Creating sites' ),
                                        'url' => false ) );
        return $result;
    }

    function initializePackage( // $package,
                                $siteType,
                                &$accessMap, $charset,
                                &$extraLanguageCodes, &$allLanguages, &$primaryLanguage,
                                &$admin,
                                &$resultArray )
    {
        // Time limit #3:
        // We set the time limit to 5 minutes to ensure we have enough time
        // to initialize the site. However we only set if the current limit
        // is too small
        $maxTime = ini_get( 'max_execution_time' );
        if ( $maxTime != 0 and
             $maxTime < 5*60 )
        {
            @set_time_limit( 5*60 );
        }

        switch ( $siteType['access_type'] )
        {
            case 'port':
            {
                $userSiteaccessName = $siteType['identifier'] . '_' . 'user';
                $adminSiteaccessName = $siteType['identifier'] . '_' . 'admin';
                $accessMap['port'][$siteType['access_type_value']] = $userSiteaccessName;
                $accessMap['port'][$siteType['admin_access_type_value']] = $adminSiteaccessName;
            } break;
            case 'hostname':
            {
                $userSiteaccessName = $siteType['identifier'] . '_' . 'user';
                $adminSiteaccessName = $siteType['identifier'] . '_' . 'admin';
                $accessMap['hostname'][$siteType['access_type_value']] = $userSiteaccessName;
                $accessMap['hostname'][$siteType['admin_access_type_value']] = $adminSiteaccessName;
            } break;
            case 'url':
            default:
            {
                $userSiteaccessName = $siteType['access_type_value'];
                $adminSiteaccessName = $siteType['admin_access_type_value'];
                $accessMap['url'][$siteType['access_type_value']] = $userSiteaccessName;
                $accessMap['url'][$siteType['admin_access_type_value']] = $adminSiteaccessName;
            } break;
        }
        $accessMap['accesses'][] = $userSiteaccessName;
        $accessMap['accesses'][] = $adminSiteaccessName;
        $accessMap['sites'][] = $userSiteaccessName;
        $userDesignName = $siteType['identifier'];

        $languageObjects = $allLanguages;

        $databaseMap = eZSetupDatabaseMap();

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];

        $dbServer = $databaseInfo['server'];
        $dbPort = $databaseInfo['port'];
//        $dbName = $databaseInfo['dbname'];
        $dbSocket = $databaseInfo['socket'];
        $dbUser = $databaseInfo['user'];
        $dbPwd = $databaseInfo['password'];
        $dbCharset = $charset;
        $dbDriver = $databaseInfo['info']['driver'];

        $dbName = $siteType['database'];
        $dbParameters = array( 'server' => $dbServer,
                               'port' => $dbPort,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => $dbName,
                               'charset' => $dbCharset );
        $db = eZDB::instance( $dbDriver, $dbParameters, true );
        if ( !$db->isConnected() )
        {
            $resultArray['errors'][] = array( 'code' => 'EZSW-005',
                                              'text' => ( "Failed connecting to database $dbName\n" .
                                                          $db->errorMessage() ) );
            return false;
        }
        eZDB::setInstance( $db );

        $result = true;

        // Initialize the database by inserting schema and data

        if ( !isset( $siteType['existing_database'] ) )
        {
            $siteType['existing_database'] = false;
        }
        if ( $siteType['existing_database'] == eZStepInstaller::DB_DATA_REMOVE )
        {
            eZDBTool::cleanup( $db );
        }

        if ( $siteType['existing_database'] != eZStepInstaller::DB_DATA_KEEP )
        {
            $result = true;
            $schemaArray = eZDbSchema::read( 'share/db_schema.dba', true );
            if ( !$schemaArray )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-001',
                                                  'message' => "Failed loading database schema file share/db_schema.dba" );
                $result = false;
            }

            if ( $result )
            {
                $result = true;
                $dataArray = eZDbSchema::read( 'share/db_data.dba', true );
                if ( !$dataArray )
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-002',
                                                      'text' => "Failed loading database data file share/db_data.dba" );
                    $result = false;
                }

                if ( $result )
                {
                    $schemaArray = array_merge( $schemaArray, $dataArray );
                    $schemaArray['type'] = strtolower( $db->databaseName() );
                    $schemaArray['instance'] = $db;
                    $result = true;
                    $dbSchema = eZDbSchema::instance( $schemaArray );
                    if ( !$dbSchema )
                    {
                        $resultArray['errors'][] = array( 'code' => 'EZSW-003',
                                                          'text' => "Failed loading " . $db->databaseName() . " schema handler" );
                        $result = false;
                    }

                    if ( $result )
                    {
                        $result = true;
                        // This will insert the schema, then the data and
                        // run any sequence value correction SQL if required
                        $params = array( 'schema' => true,
                                         'data' => true );

                        if ( $db->databaseName() == 'mysql' )
                        {
                            // We try to use InnoDB table type if it is available, else we use the default type.
                            $innoDBAvail = $db->arrayQuery( "SHOW VARIABLES LIKE 'have_innodb';" );
                            if ( $innoDBAvail[0]['Value'] == 'YES' )
                                $params['table_type'] = 'innodb';
                        }

                        if ( !$dbSchema->insertSchema( $params ) )
                        {
                            $resultArray['errors'][] = array( 'code' => 'EZSW-004',
                                                              'text' => ( "Failed inserting data to " . $db->databaseName() . "\n" .
                                                                          $db->errorMessage() ) );
                            $result = false;
                        }
                    }
                }
            }

            if ( $result )
            {
                // Inserting data from the dba-data files of the datatypes
                eZDataType::loadAndRegisterAllTypes();
                $registeredDataTypes = eZDataType::registeredDataTypes();
                foreach ( $registeredDataTypes as $dataType )
                {
                    if ( !$dataType->importDBDataFromDBAFile() )
                    {
                        $resultArray['errors'][] = array( 'code' => 'EZSW-002',
                                                          'text' => "Failed importing datatype related data into database: \n" .
                                                                    'datatype - ' . $dataType->DataTypeString . ", \n" .
                                                                    'dba-data file - ' . $dataType->getDBAFilePath() );
                    }
                }
            }
        }
        if ( !$result )
        {
            return false;
        }

        // Database initialization done

        // Prepare languages
        $primaryLanguageLocaleCode = $primaryLanguage->localeCode();
        $primaryLanguageName = $primaryLanguage->languageName();
        $prioritizedLanguages = array_merge( array( $primaryLanguageLocaleCode ), $extraLanguageCodes );

        $installParameters = array( 'path' => '.' );
        $installParameters['ini'] = array();
        $siteINIChanges = array();
        $url = $siteType['url'];
        if ( preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url, $matches ) )
        {
            $url = $matches[1];
        }

        $siteINIChanges['SiteAccessSettings'] = array( 'RelatedSiteAccessList' => $accessMap['accesses'] );

        $siteINIChanges['ContentSettings'] = array( 'TranslationList' => implode( ';', $extraLanguageCodes ) );
        $siteINIChanges['SiteSettings'] = array( 'SiteName' => $siteType['title'],
                                                 'SiteURL' => $url );
        $siteINIChanges['DatabaseSettings'] = array( 'DatabaseImplementation' => $dbDriver,
                                                     'Server' => $dbServer,
                                                     'Port' => $dbPort,
                                                     'Database' => $dbName,
                                                     'User' => $dbUser,
                                                     'Password' => $dbPwd,
                                                     'Charset' => false );
        $siteINIChanges['FileSettings'] = array( 'VarDir' => 'var/' . $siteType['identifier'] );
        if ( trim( $dbSocket ) != '' )
            $siteINIChanges['DatabaseSettings']['Socket'] = $dbSocket;
        else
            $siteINIChanges['DatabaseSettings']['Socket'] = 'disabled';
        if ( $admin['email'] )
        {
            $siteINIChanges['InformationCollectionSettings'] = array( 'EmailReceiver' => false );
            $siteINIChanges['UserSettings'] = array( 'RegistrationEmail' => false );
            $siteINIChanges['MailSettings'] = array( 'AdminEmail' =>  $admin['email'],
                                                     'EmailSender' => false );
        }
        $siteINIChanges['RegionalSettings'] = array( 'Locale' => $primaryLanguage->localeFullCode(),
                                                     'ContentObjectLocale' => $primaryLanguage->localeCode(),
                                                     'SiteLanguageList' => $prioritizedLanguages );
        if ( $primaryLanguage->localeCode() == 'eng-GB' )
            $siteINIChanges['RegionalSettings']['TextTranslation'] = 'disabled';
        else
            $siteINIChanges['RegionalSettings']['TextTranslation'] = 'enabled';

        $installParameters['ini']['siteaccess'][$adminSiteaccessName]['site.ini.append'] = $siteINIChanges;
        $installParameters['ini']['siteaccess'][$userSiteaccessName]['site.ini.append'] = $siteINIChanges;
        $installParameters['ini']['siteaccess'][$userSiteaccessName]['site.ini']['DesignSettings'] = array( 'SiteDesign' => $userDesignName );
        $installParameters['variables']['user_siteaccess'] = $userSiteaccessName;
        $installParameters['variables']['admin_siteaccess'] = $adminSiteaccessName;
        $installParameters['variables']['design'] = $userDesignName;

        $tmpSiteINI = eZINI::create( 'site.ini' );
        // Set ReadOnlySettingsCheck to false: towards
        // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
        $tmpSiteINI->setReadOnlySettingsCheck( false );

        $tmpSiteINI->setVariable( 'FileSettings', 'VarDir', $siteINIChanges['FileSettings']['VarDir'] );

        // Change the current translation variables, before other parts start using them
        $tmpSiteINI->setVariable( 'RegionalSettings', 'Locale', $siteINIChanges['RegionalSettings']['Locale'] );
        $tmpSiteINI->setVariable( 'RegionalSettings', 'ContentObjectLocale', $siteINIChanges['RegionalSettings']['ContentObjectLocale'] );
        $tmpSiteINI->setVariable( 'RegionalSettings', 'TextTranslation', $siteINIChanges['RegionalSettings']['TextTranslation'] );

        $tmpSiteINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName" );

        /*
        $typeFunctionality = eZSetupFunctionality( $siteType['identifier'] );
        $extraFunctionality = array_merge( isset( $this->PersistenceList['additional_packages'] ) ?
                                           $this->PersistenceList['additional_packages'] :
                                           array(),
                                           $typeFunctionality['required'] );
        $extraFunctionality = array_unique( $extraFunctionality );
        */

        // Add a policy to permit editors using OE
        eZPolicy::createNew( 3, array( 'ModuleName' => 'ezoe', 'FunctionName' => '*' ) );

        // Install site package and it's required packages
        $sitePackageName = $this->chosenSitePackage();
        $sitePackage = eZPackage::fetch( $sitePackageName );
        if ( !is_object( $sitePackage ) )
        {
            $resultArray['errors'][] = array( 'code' => 'EZSW-041',
                                              'text' => " Could not fetch site package: '$sitePackageName'" );
            return false;
        }

        $dependecies = $sitePackage->attribute('dependencies');
        $requires = $dependecies['requires'];
        $requiredPackages = array();

        // Include setting files
        $settingsFiles = $sitePackage->attribute( 'settings-files' );
        foreach( $settingsFiles as $settingsFileName )
        {
            if ( file_exists( $sitePackage->path() . '/settings/' . $settingsFileName ) )
            {
                include_once( $sitePackage->path() . '/settings/' . $settingsFileName );
            }
        }

        // Call user function for additional setup tasks.
        if ( function_exists( 'eZSitePreInstall' ) )
            eZSitePreInstall();


        // Make sure objects use the selected main language instead of eng-GB
        if ( $primaryLanguageLocaleCode != 'eng-GB' )
        {
            $engLanguageObj = eZContentLanguage::fetchByLocale( 'eng-GB' );
            $engLanguageID = (int)$engLanguageObj->attribute( 'id' );
            $updateSql = "UPDATE ezcontent_language
SET
locale='$primaryLanguageLocaleCode',
name='$primaryLanguageName'
WHERE
id=$engLanguageID";
            $db->query( $updateSql );
            eZContentLanguage::expireCache();
            $primaryLanguageObj = eZContentLanguage::fetchByLocale( $primaryLanguageLocaleCode );
            /*
            // Add it if it is missing (most likely)
            if ( !$primaryLanguageObj )
            {
                $primaryLanguageObj = eZContentLanguage::addLanguage( $primaryLanguageLocaleCode, $primaryLanguageName );
            }
            */
            $primaryLanguageID = (int)$primaryLanguageObj->attribute( 'id' );

            // Find objects which are always available
            if ( $db->databaseName() == 'oracle' )
            {
                $sql = "SELECT id
FROM
ezcontentobject
WHERE
bitand( language_mask, 1 ) = 1";
            }
            else
            {
                $sql = "SELECT id
FROM
ezcontentobject
WHERE
language_mask & 1 = 1";
            }
            $objectList = array();
            $list = $db->arrayQuery( $sql );
            foreach ( $list as $row )
            {
                $objectList[] = (int)$row['id'];
            }
            $inSql = 'IN ( ' . implode( ', ', $objectList ) . ')';

            // Updates databases that have eng-GB data to the new locale.
            $updateSql = "UPDATE ezcontentobject_name
SET
content_translation='$primaryLanguageLocaleCode',
real_translation='$primaryLanguageLocaleCode',
language_id=$primaryLanguageID
WHERE
content_translation='eng-GB' OR
real_translation='eng-GB'";
            $db->query( $updateSql );
            // Fix always available
            $updateSql = "UPDATE ezcontentobject_name
SET
language_id=language_id+1
WHERE
contentobject_id $inSql";
            $db->query( $updateSql );

            // attributes
            $updateSql = "UPDATE ezcontentobject_attribute
SET
language_code='$primaryLanguageLocaleCode',
language_id=$primaryLanguageID
WHERE
language_code='eng-GB'";
            $db->query( $updateSql );
            // Fix always available
            $updateSql = "UPDATE ezcontentobject_attribute
SET
language_id=language_id+1
WHERE
contentobject_id $inSql";
            $db->query( $updateSql );

            // version
            $updateSql = "UPDATE ezcontentobject_version
SET
initial_language_id=$primaryLanguageID,
language_mask=$primaryLanguageID
WHERE
initial_language_id=$engLanguageID";
            $db->query( $updateSql );
            // Fix always available
            $updateSql = "UPDATE ezcontentobject_version
SET
language_mask=language_mask+1
WHERE
contentobject_id $inSql";
            $db->query( $updateSql );

            // object
            $updateSql = "UPDATE ezcontentobject
SET
initial_language_id=$primaryLanguageID,
language_mask=$primaryLanguageID
WHERE
initial_language_id=$engLanguageID";
            $db->query( $updateSql );
            // Fix always available
            $updateSql = "UPDATE ezcontentobject
SET
language_mask=language_mask+1
WHERE
id $inSql";
            $db->query( $updateSql );

            // content object state groups & states
            $mask = $primaryLanguageID | 1;

            $db->query( "UPDATE ezcobj_state_group
                         SET language_mask = $mask, default_language_id = $primaryLanguageID
                         WHERE default_language_id = $engLanguageID" );

            $db->query( "UPDATE ezcobj_state
                         SET language_mask = $mask, default_language_id = $primaryLanguageID
                         WHERE default_language_id = $engLanguageID" );

            $db->query( "UPDATE ezcobj_state_group_language
                         SET language_id = $primaryLanguageID
                         WHERE language_id = $engLanguageID" );

            $db->query( "UPDATE ezcobj_state_language
                         SET language_id = $primaryLanguageID
                         WHERE language_id = $engLanguageID" );

            // ezcontentclass_name
            $updateSql = "UPDATE ezcontentclass_name
SET
language_locale='$primaryLanguageLocaleCode'
WHERE
language_locale='eng-GB'";
            $db->query( $updateSql );

            // use high-level api, becuase it's impossible to update serialized names with direct sqls.
            // use direct access to 'NameList' to avoid unnecessary sql-requests and because
            // we do 'replacement' of existing languge(with some 'id') with another language code.
            $contentClassList = eZContentClass::fetchList();
            foreach( $contentClassList as $contentClass )
            {
                $classAttributes = $contentClass->fetchAttributes();
                foreach( $classAttributes as $classAttribute )
                {
                    $classAttribute->NameList->setName( $classAttribute->NameList->name( 'eng-GB' ), $primaryLanguageLocaleCode );
                    $classAttribute->NameList->setAlwaysAvailableLanguage( $primaryLanguageLocaleCode );
                    $classAttribute->NameList->removeName( 'eng-GB' );
                    $classAttribute->store();
                }

                $contentClass->NameList->setName( $contentClass->NameList->name( 'eng-GB' ), $primaryLanguageLocaleCode );
                $contentClass->NameList->setAlwaysAvailableLanguage( $primaryLanguageLocaleCode );
                $contentClass->NameList->removeName( 'eng-GB' );
                $contentClass->NameList->setHasDirtyData( false ); // to not update 'ezcontentclass_name', because we've already updated it.
                $contentClass->store();
            }

        }
        // Setup all languages
        foreach ( $allLanguages as $languageObject )
        {
            $primaryLanguageObj = eZContentLanguage::fetchByLocale( $languageObject->localeCode() );
            // Add it if it is missing (most likely)
            if ( !$primaryLanguageObj )
            {
                $primaryLanguageObj = eZContentLanguage::addLanguage( $languageObject->localeCode(), $languageObject->internationalLanguageName() );
            }
        }
        eZContentLanguage::expireCache();
        // Make sure priority list is changed to the new chosen languages
        eZContentLanguage::setPrioritizedLanguages( $prioritizedLanguages );

        if ( $siteType['existing_database'] != eZStepInstaller::DB_DATA_KEEP )
        {
            $user = eZUser::instance( 14 );  // Must be initialized to make node assignments work correctly
            if ( !is_object( $user ) )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-020',
                                                  'text' => "Could not fetch administrator user object" );
                return false;
            }
            // Make sure Admin is the currently logged in user
            // This makes sure all new/changed objects get this as creator
            $user->loginCurrent();

            // by default(if 'language_map' is not set) create all necessary languages
            $languageMap = ( isset( $this->PersistenceList['package_info'] ) && isset( $this->PersistenceList['package_info']['language_map'] ) )
                                ? $this->PersistenceList['package_info']['language_map']
                                : true;

            if ( is_array( $languageMap ) && count( $languageMap ) > 0 )
            {
                //
                // Create necessary languages and set them as "prioritized languages" to avoid
                // drawbacks in fetch functions, like eZContentObjectTreeNode::fetch().
                //
                $prioritizedLanguageObjects = eZContentLanguage::prioritizedLanguages(); // returned objects
                foreach ( $languageMap as $fromLanguage => $toLanguage )
                {
                    if ( $toLanguage != 'skip' )
                    {
                        $prioritizedLanguageObjects[] = eZContentLanguage::fetchByLocale( $toLanguage, true );
                    }
                }
                $prioritizedLanguageLocales = array();
                foreach ( $prioritizedLanguageObjects as $language )
                {
                    $locale = $language->attribute( 'locale' );
                    if ( !in_array( $locale, $prioritizedLanguageLocales ) )
                    {
                        $prioritizedLanguageLocales[] = $locale;
                    }
                }
                eZContentLanguage::setPrioritizedLanguages( $prioritizedLanguageLocales );
            }


            foreach ( $requires as $require )
            {
                if ( $require['type'] != 'ezpackage' )
                    continue;

                $packageName = $require['name'];
                $package = eZPackage::fetch( $packageName, false, false, false );

                if ( is_object( $package ) )
                {
                    $requiredPackages[] = $package;
                    if ( $package->attribute( 'install_type' ) == 'install' )
                    {
                        $installParameters = array( 'use_dates_from_package' => true,
                                                    'site_access_map' => array( '*' => $userSiteaccessName ),
                                                    'top_nodes_map' => array( '*' => 2 ),
                                                    'design_map' => array( '*' => $userDesignName ),
                                                    'language_map' => $languageMap,
                                                    'restore_dates' => true,
                                                    'user_id' => $user->attribute( 'contentobject_id' ),
                                                    'non-interactive' => true );

                        $status = $package->install( $installParameters );
                        if ( !$status )
                        {
                            $errorText = "Unable to install package '$packageName'";
                            if ( isset( $installParameters['error']['description'] ) )
                                $errorText .= ": " . $installParameters['error']['description'];

                            $resultArray['errors'][] = array( 'code' => 'EZSW-051',
                                                              'text' => $errorText );
                            return false;
                        }
                    }
                }
                else
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-050',
                                                      'text' => "Could not fetch required package: '$packageName'" );
                    return false;
                }
                unset( $package );
            }
        }

        $GLOBALS['eZContentObjectDefaultLanguage'] = $primaryLanguageLocaleCode;

        $nodeRemoteMap = array();
        $rows = $db->arrayQuery( "SELECT node_id, remote_id FROM ezcontentobject_tree" );
        foreach ( $rows as $row )
        {
            $remoteID = $row['remote_id'];
            if ( strlen( trim( $remoteID ) ) > 0 )
                $nodeRemoteMap[$remoteID] = $row['node_id'];
        }

        $objectRemoteMap = array();
        $rows = $db->arrayQuery( "SELECT id, remote_id FROM ezcontentobject" );
        foreach ( $rows as $row )
        {
            $remoteID = $row['remote_id'];
            if ( strlen( trim( $remoteID ) ) > 0 )
                $objectRemoteMap[$remoteID] = $row['id'];
        }

        $classRemoteMap = array();
        $rows = $db->arrayQuery( "SELECT id, identifier, remote_id FROM ezcontentclass" );
        foreach ( $rows as $row )
        {
            $remoteID = $row['remote_id'];
            if ( strlen( trim( $remoteID ) ) > 0 )
                $classRemoteMap[$remoteID] = array( 'id' => $row['id'],
                                                    'identifier' => $row['identifier'] );
        }

        $siteCSS = false;
        $classesCSS = false;

        foreach( $requiredPackages as $package )
        {
            if ( $package->attribute( 'type' ) == 'sitestyle' )
            {
                $fileList = $package->fileList( 'default' );
                foreach ( $fileList as $file )
                {
                    $fileIdentifier = $file["variable-name"];
                    if ( $fileIdentifier == 'sitecssfile' )
                    {
                        $siteCSS = $package->fileItemPath( $file, 'default' );
                    }
                    else if ( $fileIdentifier == 'classescssfile' )
                    {
                        $classesCSS = $package->fileItemPath( $file, 'default' );
                    }
                }
            }
        }

        $parameters = array( 'node_remote_map' => $nodeRemoteMap,
                             'object_remote_map' => $objectRemoteMap,
                             'class_remote_map' => $classRemoteMap,
                             //'extra_functionality' => $extraFunctionality,
                             'preview_design' => $userDesignName,
                             'design_list' => array( $userDesignName, 'admin' ),
                             'user_siteaccess' => $userSiteaccessName,
                             'admin_siteaccess' => $adminSiteaccessName,
                             'package_object' => $sitePackage,
                             'siteaccess_urls' => $this->siteaccessURLs(),
                             'access_map' => $accessMap,
                             'site_type' => $siteType,
                             'all_language_codes' => $prioritizedLanguages );


        $siteINIStored = false;
        $siteINIAdminStored = false;
        $designINIStored = false;

        if ( function_exists( 'eZSiteINISettings' ) )
            $extraSettings = eZSiteINISettings( $parameters );
        else
            $extraSettings = array();

        if ( function_exists( 'eZSiteAdminINISettings' ) )
            $extraAdminSettings = eZSiteAdminINISettings( $parameters );
        else
            $extraAdminSettings = array();

        if ( function_exists( 'eZSiteCommonINISettings' ) )
            $extraCommonSettings = eZSiteCommonINISettings( $parameters );
        else
            $extraCommonSettings = array();

        $isUntranslatedSettingAdded = false;
        foreach ( $extraAdminSettings as $key => $extraAdminSetting )
        {
            if ( $extraAdminSetting['name'] == 'site.ini' )
            {
                $extraAdminSettings[$key]['settings']['RegionalSettings']['ShowUntranslatedObjects'] = 'enabled';
                $isUntranslatedSettingAdded = true;
                break;
            }
        }
        if ( !$isUntranslatedSettingAdded )
        {
            $extraAdminSettings[] = array( 'name' => 'site.ini',
                                           'settings' => array( 'RegionalSettings' => array( 'ShowUntranslatedObjects' => 'enabled' ) ) );
        }

        // Enable OE and ODF extensions by default
        $extensionsToEnable = array();
        foreach ( array( 'ezoe', 'ezodf' ) as $extension )
        {
            if ( file_exists( "extension/$extension" ) )
            {
                $extensionsToEnable[] = $extension;
            }
        }

        $settingAdded = false;
        foreach ( $extraCommonSettings as $key => $extraCommonSetting )
        {
            if ( $extraCommonSetting['name'] == 'site.ini' &&
                 isset( $extraCommonSettings[$key]['settings']['ExtensionSettings']['ActiveExtensions'] ) )
            {
                $settingAdded = true;
                $extraCommonSettings[$key]['settings']['ExtensionSettings']['ActiveExtensions'] =
                    array_merge( $extraCommonSettings[$key]['settings']['ExtensionSettings']['ActiveExtensions'], $extensionsToEnable );
                break;
            }
        }

        if ( !$settingAdded )
        {
            $extraCommonSettings[] = array( 'name' => 'site.ini',
                                            'settings' => array( 'ExtensionSettings' => array( 'ActiveExtensions' => $extensionsToEnable ) ) );
        }

        // Enable dynamic tree menu for the admin interface by default
        $enableDynamicTreeMenuAdded = false;
        foreach ( $extraAdminSettings as $key => $extraSetting )
        {
            if ( $extraSetting['name'] == 'contentstructuremenu.ini' )
            {
                if ( isset( $extraSetting['settings']['TreeMenu'] ) )
                {
                    $extraAdminSettings[$key]['settings']['TreeMenu']['Dynamic'] = 'enabled';
                }
                else
                {
                    $extraAdminSettings[$key]['settings'] = array( 'TreeMenu' => array( 'Dynamic' => 'enabled' ) );
                }
                $enableDynamicTreeMenuAdded = true;
                break;
            }
        }
        if ( !$enableDynamicTreeMenuAdded )
        {
            $extraAdminSettings[] = array( 'name' => 'contentstructuremenu.ini',
                                           'settings' => array( 'TreeMenu' => array( 'Dynamic' => 'enabled' ) ) );
        }

        $resultArray['common_settings'] = $extraCommonSettings;

        foreach ( $extraSettings as $extraSetting )
        {
            if ( $extraSetting === false )
                continue;

            $iniName = $extraSetting['name'];
            $settings = $extraSetting['settings'];
            $resetArray = false;
            if ( isset( $extraSetting['reset_arrays'] ) )
                $resetArray = $extraSetting['reset_arrays'];

            $tmpINI = eZINI::create( $iniName );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $tmpINI->setReadOnlySettingsCheck( false );

            $tmpINI->setVariables( $settings );
            if ( $iniName == 'site.ini' )
            {
                $siteINIStored = true;
                $tmpINI->setVariables( $siteINIChanges );
                $tmpINI->setVariable( 'DesignSettings', 'SiteDesign', $userDesignName );
                $tmpINI->setVariable( 'DesignSettings', 'AdditionalSiteDesignList', array( 'base' ) );
            }
            else if ( $iniName == 'design.ini' )
            {
                if ( $siteCSS )
                    $tmpINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
                if ( $classesCSS )
                    $tmpINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
                $designINIStored = true;
            }
            $tmpINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName", $resetArray );

            if ( $siteType['existing_database'] != eZStepInstaller::DB_DATA_KEEP )
            {
                // setting up appropriate data in look&feel object
                $templateLookClass = eZContentClass::fetchByIdentifier( 'template_look', true );
                if ( $templateLookClass )
                {
                    $objectList = $templateLookClass->objectList();
                    if ( $objectList and count( $objectList ) > 0 )
                    {
                        $templateLookObject = current( $objectList );
                        $dataMap = $templateLookObject->fetchDataMap();
                        $dataMap[ 'title' ]->setAttribute( 'data_text', $siteINIChanges['SiteSettings']['SiteName'] );
                        $dataMap[ 'title' ]->store();
                        $dataMap[ 'siteurl' ]->setAttribute( 'data_text', $siteINIChanges['SiteSettings']['SiteURL'] );
                        $dataMap[ 'siteurl' ]->store();
                        $dataMap[ 'email' ]->setAttribute( 'data_text', $siteINIChanges['MailSettings']['AdminEmail'] );
                        $dataMap[ 'email' ]->store();
                        $objectName = $templateLookClass->contentObjectName( $templateLookObject );
                        $templateLookObject->setName( $objectName );
                        $templateLookObject->store();
                    }
                }
            }
        }

        foreach ( $extraAdminSettings as $extraSetting )
        {
            if ( $extraSetting === false )
                continue;

            $iniName = $extraSetting['name'];
            $settings = $extraSetting['settings'];
            $resetArray = false;
            if ( isset( $extraSetting['reset_arrays'] ) )
                $resetArray = $extraSetting['reset_arrays'];

            $tmpINI = eZINI::create( $iniName );

            $tmpINI->setVariables( $settings );
            if ( $iniName == 'site.ini' )
            {
                $siteINIAdminStored = true;
                $tmpINI->setVariables( $siteINIChanges );
                $tmpINI->setVariable( 'SiteAccessSettings', 'RequireUserLogin', 'true' );
                $tmpINI->setVariable( 'DesignSettings', 'SiteDesign', 'admin' );
                $tmpINI->setVariable( 'SiteSettings', 'LoginPage', 'custom' );
                $tmpINI->setVariable( 'SiteSettings', 'IndexPage', 'content/dashboard' );
            }
            $tmpINI->save( false, '.append.php', false, true, "settings/siteaccess/$adminSiteaccessName", $resetArray );
        }

        if ( !$siteINIAdminStored )
        {
            $siteINI = eZINI::create( 'site.ini' );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $siteINI->setReadOnlySettingsCheck( false );

            $siteINI->setVariables( $siteINIChanges );
            $siteINI->setVariable( 'SiteAccessSettings', 'RequireUserLogin', 'true' );
            $siteINI->setVariable( 'DesignSettings', 'SiteDesign', 'admin' );
            $siteINI->setVariable( 'SiteSettings', 'LoginPage', 'custom' );
            $siteINI->setVariable( 'SiteSettings', 'IndexPage', 'content/dashboard' );
            $siteINI->save( false, '.append.php', false, false, "settings/siteaccess/$adminSiteaccessName", true );
        }
        if ( !$siteINIStored )
        {
            $siteINI = eZINI::create( 'site.ini' );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $siteINI->setReadOnlySettingsCheck( false );

            $siteINI->setVariables( $siteINIChanges );
            $siteINI->setVariable( 'DesignSettings', 'SiteDesign', $userDesignName );
            $siteINI->setVariable( 'DesignSettings', 'AdditionalSiteDesignList', array( 'base' ) );
            $siteINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName", true );
        }
        if ( !$designINIStored )
        {
            $designINI = eZINI::create( 'design.ini' );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $designINI->setReadOnlySettingsCheck( false );

            if ( $siteCSS )
                $designINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
            if ( $classesCSS )
                $designINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
            $designINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName" );
        }

        eZDir::mkdir( "design/" . $userDesignName );
        eZDir::mkdir( "design/" . $userDesignName . "/templates" );
        eZDir::mkdir( "design/" . $userDesignName . "/stylesheets" );
        eZDir::mkdir( "design/" . $userDesignName . "/images" );
        eZDir::mkdir( "design/" . $userDesignName . "/override" );
        eZDir::mkdir( "design/" . $userDesignName . "/override/templates" );

        if ( $siteType['existing_database'] == eZStepInstaller::DB_DATA_KEEP )
        {
            return true;
        }

        // Try and remove user/login without limitation from the anonymous user
        $anonRole = eZRole::fetchByName( 'Anonymous' );
        if ( is_object( $anonRole ) )
        {
            $anonPolicies = $anonRole->policyList();
            foreach ( $anonPolicies as $anonPolicy )
            {
                if ( $anonPolicy->attribute( 'module_name' ) == 'user' and
                     $anonPolicy->attribute( 'function_name' ) == 'login' )
                {
                    $anonPolicy->removeThis();
                    break;
                }
            }
        }

        // Setup all roles according to site chosen and addons

        if ( function_exists( 'eZSiteRoles' ) )
        {
            $extraRoles = eZSiteRoles( $parameters );

            foreach ( $extraRoles as $extraRole )
            {
                if ( !$extraRole )
                    continue;
                $extraRoleName = $extraRole['name'];
                $role = eZRole::fetchByName( $extraRoleName );
                if ( !is_object( $role ) )
                {
                    $role = eZRole::create( $extraRoleName );
                    $role->store();
                }
                $roleID = $role->attribute( 'id' );
                if ( isset( $extraRole['policies'] ) )
                {
                    $extraPolicies = $extraRole['policies'];
                    foreach ( $extraPolicies as $extraPolicy )
                    {
                        if ( isset( $extraPolicy['limitation'] ) )
                        {
                            $role->appendPolicy( $extraPolicy['module'], $extraPolicy['function'], $extraPolicy['limitation'] );
                        }
                        else
                        {
                            $role->appendPolicy( $extraPolicy['module'], $extraPolicy['function'] );
                        }
                    }
                }

                if ( isset( $extraRole['assignments'] ) )
                {
                    $roleAssignments = $extraRole['assignments'];
                    foreach ( $roleAssignments as $roleAssignment )
                    {
                        $assignmentIdentifier = false;
                        $assignmentValue = false;
                        if ( isset( $roleAssignment['limitation'] ) )
                        {
                            $assignmentIdentifier = $roleAssignment['limitation']['identifier'];
                            $assignmentValue = $roleAssignment['limitation']['value'];
                        }
                        $role->assignToUser( $roleAssignment['user_id'], $assignmentIdentifier, $assignmentValue );
                    }
                }
            }
        }

        // Setup user preferences based on the site chosen and addons
        if ( function_exists( 'eZSitePreferences' ) )
        {
            $prefs = eZSitePreferences( $parameters );
            foreach ( $prefs as $prefEntry )
            {
                if ( !$prefEntry )
                    continue;
                $prefUserID = $prefEntry['user_id'];
                foreach ( $prefEntry['preferences'] as $pref )
                {
                    $prefName = $pref['name'];
                    $prefValue = $pref['value'];
                    if ( !eZPreferences::setValue( $prefName, $prefValue, $prefUserID ) )
                    {
                        $resultArray['errors'][] = array( 'code' => 'EZSW-070',
                                                          'text' => "Could not create ezpreference '$prefValue' for $prefUserID" );
                        return false;
                    }
                }
            }
        }

        $publishAdmin = false;
        $userAccount = eZUser::fetch( 14 );
        if ( !is_object( $userAccount ) )
        {
            $resultArray['errors'][] = array( 'code' => 'EZSW-020',
                                              'text' => "Could not fetch administrator user object" );
            return false;
        }

        $userObject = $userAccount->attribute( 'contentobject' );
        if ( !is_object( $userObject ) )
        {
            $resultArray['errors'][] = array( 'code' => 'EZSW-021',
                                              'text' => "Could not fetch administrator content object" );
            return false;
        }

        if ( trim( $admin['email'] ) )
        {
            $userAccount->setInformation( 14, 'admin', $admin['email'], $admin['password'], $admin['password'] );
        }

        if ( trim( $admin['first_name'] ) or trim( $admin['last_name'] ) )
        {
            $newUserObject = $userObject->createNewVersion( false, false );
            if ( !is_object( $newUserObject ) )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-022',
                                                  'text' => "Could not create new version of administrator content object" );
                return false;
            }
            $dataMap = $newUserObject->attribute( 'data_map' );
            $error = false;
            if ( !isset( $dataMap['first_name'] ) )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-023',
                                                  'text' => "Administrator content object does not have a 'first_name' field" );
                $error = true;
            }
            if ( !isset( $dataMap['last_name'] ) )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-024',
                                                  'text' => "Administrator content object does not have a 'last_name' field" );
                $error = true;
            }

            if ( $error )
                return false;

            $dataMap['first_name']->setAttribute( 'data_text', $admin['first_name'] );
            $dataMap['first_name']->store();
            $dataMap['last_name']->setAttribute( 'data_text', $admin['last_name'] );
            $dataMap['last_name']->store();
            $newUserObject->store();
            $publishAdmin = true;
        }
        $userAccount->store();

        if ( $publishAdmin )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newUserObject->attribute( 'contentobject_id' ),
                                                                                         'version' => $newUserObject->attribute( 'version' ) ) );
            if ( $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-025',
                                                  'text' => "Failed to properly publish the administrator object" );
                return false;
            }
        }

        // Call user function for additional setup tasks.
        if ( function_exists( 'eZSitePostInstall' ) )
            eZSitePostInstall( $parameters );


        // get all siteaccesses. do it via 'RelatedSiteAccessesList' settings.
        $adminSiteINI = eZINI::instance( 'site.ini' . '.append.php', "settings/siteaccess/$adminSiteaccessName" );
        $relatedSiteAccessList = $adminSiteINI->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

        // Adding override for 'tiny_image' view for 'multi-option2' datatype
        foreach ( $relatedSiteAccessList as $siteAccess )
        {
            $tmpOverrideINI = eZINI::instance( 'override.ini' . '.append.php', "settings/siteaccess/$siteAccess", null, null, null, true, true );

            $tmpOverrideINI->setVariable( 'tiny_image', 'Source'    , 'content/view/tiny.tpl' );
            $tmpOverrideINI->setVariable( 'tiny_image', 'MatchFile' , 'tiny_image.tpl' );
            $tmpOverrideINI->setVariable( 'tiny_image', 'Subdir'    , 'templates');
            $tmpOverrideINI->setVariable( 'tiny_image', 'Match'     , array( 'class_identifier' => 'image' ) );

            $tmpOverrideINI->save();
        }


        $accessMap = $parameters['access_map'];

        // Call user function for some text which will be displayed at 'Finish' screen
        if ( function_exists( 'eZSiteFinalText' ) )
        {
            $text = eZSiteFinalText( $parameters );
            if ( !isset( $this->PersistenceList['final_text'] ) )
                $this->PersistenceList['final_text'] = array();

            $this->PersistenceList['final_text'][] = $text;
        }

        // ensure that evaluated policy wildcards in the user info cache
        // will be up to date with the currently activated modules
        eZCache::clearByID( 'user_info_cache' );

        return true;
    }

}

?>
