<?php
//
// Definition of eZStepCreateSites class
//
// Created on: <13-Aug-2003 19:54:38 kk>
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

/*! \file ezstep_create_sites.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );
include_once( 'lib/ezdb/classes/ezdb.php' );


/*!
  Error codes:

  EZSW-001: Failed to load database schema file
  EZSW-002: Failed to load database data file
  EZSW-003: Failed to initialize database schema
  EZSW-004: Failed to initialize database data

  EZSW-020: Failed to fetch administrator user object
  EZSW-021: Failed to fetch administrator content object
  EZSW-022: Failed to create new version for administrator object
  EZSW-023: Failed to find first_name field of administrator object
  EZSW-024: Failed to find last_name field of administrator object
  EZSW-025: Failed to publish administrator object

  EZSW-040: Failed to initialize the site package <package>

  EZSW-050: Could not fetch addon package <package>
  EZSW-051: Could not install addon package <package>

  EZSW-060: Could not fetch style package <package>

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
    function eZStepCreateSites( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'create_sites', 'Create sites' );
    }

	/*!
     \reimp
    */
    function processPostData()
    {
        return true; // Always proceede
    }

    /*!
     \reimp
     */
    function init()
    {
        $this->Error = array( 'errors' => array()  );

        set_time_limit( 10*60 );
        $saveData = true; // set to true to save data

        $ini =& eZINI::create();

        include_once( 'kernel/classes/ezpackage.php' );
        $accessMap = array( 'url' => array(),
                            'hostname' => array(),
                            'port' => array(),
                            'accesses' => array() );

        include_once( 'lib/ezlocale/classes/ezlocale.php' );

        $primaryLanguage = null;
        $allLanguages = array();
        $allLanguageCodes = array();
        $extraLanguages = array();
        $primaryLanguageCode = $this->PersistenceList['regional_info']['primary_language'];
        $extraLanguageCodes = array();
        if ( isset( $this->PersistenceList['regional_info']['languages'] ) )
            $extraLanguageCodes = $this->PersistenceList['regional_info']['languages'];
        $extraLanguageCodes = array_diff( $extraLanguageCodes, array( $primaryLanguageCode ) );
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
                    $extraLanguages[] = $locale;
                }
            }
        }
        $allLanguages[] =& $primaryLanguage;
        foreach ( $extraLanguageCodes as $extraLanguageCode )
        {
            $allLanguages[] =& eZLocale::create( $extraLanguageCode );
            $allLanguageCodes[] = $extraLanguageCode;
        }

        if ( $primaryLanguage === null )
            $primaryLanguage = eZLocale::create( $this->PersistenceList['regional_info']['primary_language'] );

        $canUseUnicode = $this->PersistenceList['database_info']['use_unicode'];

        $charset = $this->findAppropriateCharset( $primaryLanguage, $allLanguages, $canUseUnicode );
        if ( !$charset )
        {
            // Make sure kickstart functionality stops
            $this->setAllowKickstart( false );
            return 'LanguageOptions';
        }

        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();

//         $siteCount = $this->PersistenceList['site_templates']['count'];
//         $siteCount = 1;
        $siteTypes = $this->chosenSiteTypes();
//         for ( $counter = 0; $counter < $siteCount; ++$counter )

        $siteINISettings = array();

        $result = true;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
//             $sitePackage = $this->PersistenceList['site_templates_'.$counter];
            $accessType = $siteType['access_type'];
//             $package =& eZPackage::fetch( $siteType['identifier'], 'kernel/setup/packages' );
            $resultArray = array( 'errors' => array() );
            $result = $this->initializePackage( $siteType, $accessMap, $charset,
                                                $allLanguageCodes, $allLanguages, $primaryLanguage, $this->PersistenceList['admin'],
                                                $resultArray );
            if ( !$result )
            {
                $this->Error['errors'] = array_merge( $this->Error['errors'], $resultArray['errors'] );
                $this->Error['errors'][] = array( 'code' => 'EZSW-040',
                                                  'text' => "Failed to initialize site package " . $siteType['identifier'] );
                $result = false;
                break;
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

                    $tmpINI =& eZINI::create( $iniName );
                    $tmpINI->setVariables( $settings );
                    $tmpINI->save( false, '.append.php', false, true, "settings/override", $resetArray );
                }

            }
        }

        if ( !$result )
        {
            // Display errors
            return false;
        }

        $regionalInfo = $this->PersistenceList['regional_info'];
        $emailInfo = $this->PersistenceList['email_info'];

        $imageINI = eZINI::create( 'image.ini' );
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
            $i18nINI = eZINI::create( 'i18n.ini' );
            $i18nINI->setVariable( 'CharacterSettings', 'Charset', $charset );
            if ( $saveData )
                $saveResult = $i18nINI->save( false, '.php', 'append', true );
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
//         eZDebug::writeDebug( 'Changing to sendmail' );
            $ini->setVariable( 'MailSettings', 'Transport', 'sendmail' );
        }
        else
        {
//         eZDebug::writeDebug( 'Changing to SMTP' );
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

    /*!
     \reimp
    */
    function &display()
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

    function initializePackage( // &$package,
                                $siteType,
                                &$accessMap, $charset,
                                &$allLanguageCodes, &$allLanguages, &$primaryLanguage,
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

        $languages = $allLanguageCodes;
        $languageObjects = $allLanguages;

        $databaseMap = eZSetupDatabaseMap();

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];

        $dbServer = $databaseInfo['server'];
//        $dbName = $databaseInfo['dbname'];
        $dbSocket = $databaseInfo['socket'];
        $dbUser = $databaseInfo['user'];
        $dbPwd = $databaseInfo['password'];
        $dbCharset = $charset;
        $dbDriver = $databaseInfo['info']['driver'];

        $dbName = $siteType['database'];
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => $dbName,
                               'charset' => $dbCharset );
        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        eZDB::setInstance( $db );

        $result = true;
//         if ( $package )
        // Initialize the database by inserting schema and data
        {
            if ( $siteType['existing_database'] == 2 )
            {
                include_once( 'lib/ezdb/classes/ezdbtool.php' );
//                 print( "cleaning up DB!<br/>\n" );
                eZDBTool::cleanup( $db );
//                 $tableArray = $db->eZTableList();
//                 foreach ( array_keys( $tableArray ) as $table )
//                 {
//                     $db->removeRelation( $table, $tableArray[$table] );
//                 }
            }
            if ( $siteType['existing_database'] != 3 )
            {
                include_once( 'lib/ezdbschema/classes/ezdbschema.php' );
                $result = true;
                $schemaArray = eZDBSchema::read( 'share/db_schema.dba', true );
                if ( !$schemaArray )
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-001',
                                                      'message' => "Failed loading database schema file share/db_schema.dba" );
                    $result = false;
                }

                if ( $result )
                {
                    $result = true;
                    $dataArray = eZDBSchema::read( 'share/db_data.dba', true );
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
                        $schemaArray['instance'] =& $db;
                        $result = true;
                        $dbSchema = eZDBSchema::instance( $schemaArray );
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
                            if ( !$dbSchema->insertSchema( array( 'schema' => true,
                                                                  'data' => true ) ) )
                            {
                                $resultArray['errors'][] = array( 'code' => 'EZSW-004',
                                                                  'text' => "Failed inserting data to " . $db->databaseName() );
                                $result = false;
                            }
                        }
                    }
                }
            }
            if ( !$result )
            {
                return false;
            }
            // Database initialization done

            $installParameters = array( 'path' => '.' );
            $installParameters['ini'] = array();
            $siteINIChanges = array();
            $url = $siteType['url'];
            if ( preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url, $matches ) )
            {
                $url = $matches[1];
            }
            $siteINIChanges['ContentSettings'] = array( 'TranslationList' => implode( ';', $languages ) );
            $siteINIChanges['SiteSettings'] = array( 'SiteName' => $siteType['title'],
                                                     'SiteURL' => $url );
            $siteINIChanges['DatabaseSettings'] = array( 'DatabaseImplementation' => $dbDriver,
                                                         'Server' => $dbServer,
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
                                                         'ContentObjectLocale' => $primaryLanguage->localeCode() );
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

            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::instance( 14 );  // Must be initialized to make node assignments work correctly
            if ( !is_object( $user ) )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-020',
                                                  'text' => "Could not fetch administrator user object" );
                return false;
            }
            $ini =& eZINI::instance();
            $ini->setVariable( 'FileSettings', 'VarDir', $siteINIChanges['FileSettings']['VarDir'] );

            $typeFunctionality = eZSetupFunctionality( $siteType['identifier'] );
            $extraFunctionality = array_merge( isset( $this->PersistenceList['additional_packages'] ) ?
                                               $this->PersistenceList['additional_packages'] :
                                               array(),
                                               $typeFunctionality['required'] );
            $extraFunctionality = array_unique( $extraFunctionality );
            foreach ( $extraFunctionality as $packageName )
            {
                $package = eZPackage::fetch( $packageName, 'packages/addons' );
                if ( is_object( $package ) )
                {
                    $status = $package->install( array( 'site_access_map' => array( '*' => $userSiteaccessName ),
                                                        'top_nodes_map' => array( '*' => 2 ),
                                                        'design_map' => array( '*' => $userDesignName ),
                                                        'restore_dates' => true,
                                                        'user_id' => $user->attribute( 'contentobject_id' ) ) );
                    if ( !$status )
                    {
                        $resultArray['errors'][] = array( 'code' => 'EZSW-051',
                                                          'text' => "Could not install addon package named $packageName" );
                        return false;
                    }
                }
                else
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-050',
                                                      'text' => "Could not fetch addon package named $packageName" );
                    return false;
                }
                unset( $package );
            }

            $primaryLanguageLocaleCode = $primaryLanguage->localeCode();

            // Change the current translation variables, before other parts start using them
            $ini->setVariable( 'RegionalSettings', 'Locale', $primaryLanguage->localeFullCode() );
            $ini->setVariable( 'RegionalSettings', 'ContentObjectLocale', $primaryLanguage->localeCode() );
            $ini->setVariable( 'RegionalSettings', 'TextTranslation', $primaryLanguage->localeCode() == 'eng-GB' ? 'disabled' : 'enabled' );

            // Make sure objects use the selected main language instead of eng-GB
            if ( $primaryLanguageLocaleCode != 'eng-GB' )
            {
                // Updates databases that have eng-GB data to the new locale.
                $updateSql = "UPDATE ezcontentobject_name
SET
  content_translation='$primaryLanguageLocaleCode',
  real_translation='$primaryLanguageLocaleCode'
WHERE
  content_translation='eng-GB' OR
  real_translation='eng-GB'";
                $db->query( $updateSql );

                $updateSql = "UPDATE ezcontentobject_attribute
SET
  language_code='$primaryLanguageLocaleCode'
WHERE
  language_code='eng-GB'";
                $db->query( $updateSql );
//                 }
            }
            $GLOBALS['eZContentObjectDefaultLanguage'] = $primaryLanguageLocaleCode;

            // Make sure we have all the translation available
            // before we work with objects
            include_once( 'kernel/classes/ezcontenttranslation.php' );
            foreach ( array_keys( $languageObjects ) as $languageObjectKey )
            {
                $languageObject = $languageObjects[$languageObjectKey];
                $languageLocale = $languageObject->localeCode();

                if ( !eZContentTranslation::hasTranslation( $languageLocale ) )
                {
                    $translation = eZContentTranslation::createNew( $languageObject->languageName(), $languageLocale );
                    $translation->store();
                    if ( $languageLocale != $primaryLanguageLocaleCode )
                    {
                        $translation->updateObjectNames();
                    }
                }
            }

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

            $themePackage = false;
            if ( isset( $typeFunctionality['theme'] ) )
            {
                $themeName = $typeFunctionality['theme'];
                $themePackage =& eZPackage::fetch( $themeName, false, 'styles' );
                if ( is_object( $themePackage ) )
                {
                    $fileList = $themePackage->fileList( 'default' );
                    foreach ( array_keys( $fileList ) as $key )
                    {
                        $file =& $fileList[$key];
                        $fileIdentifier = $file["variable-name"];
                        if ( $fileIdentifier == 'sitecssfile' )
                        {
                            $siteCSS = $themePackage->fileItemPath( $file, 'default' );
                        }
                        else if ( $fileIdentifier == 'classescssfile' )
                        {
                            $classesCSS = $themePackage->fileItemPath( $file, 'default' );
                        }
                    }
                }
                else
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-060',
                                                      'text' => "Could not fetch style package named $themeName" );
                    return false;
                }
            }

            $parameters = array( 'node_remote_map' => $nodeRemoteMap,
                                 'object_remote_map' => $objectRemoteMap,
                                 'class_remote_map' => $classRemoteMap,
                                 'extra_functionality' => $extraFunctionality,
                                 'preview_design' => $userDesignName,
                                 'design_list' => array( $userDesignName, 'admin' ),
                                 'user_siteaccess' => $userSiteaccessName,
                                 'admin_siteaccess' => $adminSiteaccessName );

            $siteINIStored = false;
            $siteINIAdminStored = false;
            $designINIStored = false;

            $extraSettings = eZSetupINISettings( $siteType['identifier'], $parameters );
            $extraAdminSettings = eZSetupAdminINISettings( $siteType['identifier'], $parameters );
            $extraCommonSettings = eZSetupCommonINISettings( $siteType['identifier'], $parameters );
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
                $tmpINI =& eZINI::create( $iniName );
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
                $tmpINI =& eZINI::create( $iniName );
                $tmpINI->setVariables( $settings );
                if ( $iniName == 'site.ini' )
                {
                    $siteINIAdminStored = true;
                    $tmpINI->setVariables( $siteINIChanges );
                    $tmpINI->setVariable( 'SiteAccessSettings', 'RequireUserLogin', 'true' );
                    $tmpINI->setVariable( 'DesignSettings', 'SiteDesign', 'admin' );
                    $tmpINI->setVariable( 'SiteSettings', 'LoginPage', 'custom' );
                }
                $tmpINI->save( false, '.append.php', false, true, "settings/siteaccess/$adminSiteaccessName", $resetArray );
            }

            if ( !$siteINIAdminStored )
            {
                $siteINI =& eZINI::create( 'site.ini' );
                $siteINI->setVariables( $siteINIChanges );
                $siteINI->setVariable( 'SiteAccessSettings', 'RequireUserLogin', 'true' );
                $siteINI->setVariable( 'DesignSettings', 'SiteDesign', 'admin' );
                $siteINI->setVariable( 'SiteSettings', 'LoginPage', 'custom' );
                $siteINI->save( false, '.append.php', false, true, "settings/siteaccess/$adminSiteaccessName", true );
            }
            if ( !$siteINIStored )
            {
                $siteINI =& eZINI::create( 'site.ini' );
                $siteINI->setVariables( $siteINIChanges );
                $siteINI->setVariable( 'DesignSettings', 'SiteDesign', $userDesignName );
                $siteINI->setVariable( 'DesignSettings', 'AdditionalSiteDesignList', array( 'base' ) );
                $siteINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName", true );
            }
            if ( !$designINIStored )
            {
                $designINI =& eZINI::create( 'design.ini' );
                if ( $siteCSS )
                    $designINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
                if ( $classesCSS )
                    $designINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
                $designINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName" );
            }

            include_once( 'kernel/classes/ezrole.php' );

            // Try and remove user/login without limitation from the anonymous user
            $anonRole =& eZRole::fetchByName( 'Anonymous' );
            if ( is_object( $anonRole ) )
            {
                $anonPolicies =& $anonRole->policyList();
                foreach ( $anonPolicies as $anonPolicy )
                {
                    if ( $anonPolicy->attribute( 'module_name' ) == 'user' and
                         $anonPolicy->attribute( 'function_name' ) == 'login' )
                    {
                        $anonPolicy->remove();
                        break;
                    }
                }
            }

            $extraRoles = eZSetupRoles( $siteType['identifier'], $parameters );
            foreach ( $extraRoles as $extraRole )
            {
                if ( !$extraRole )
                    continue;
                $extraRoleName = $extraRole['name'];
                $role =& eZRole::fetchByName( $extraRoleName );
                if ( !is_object( $role ) )
                {
                    $role =& eZRole::create( $extraRoleName );
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

            eZDir::mkdir( "design/" . $userDesignName );
            eZDir::mkdir( "design/" . $userDesignName . "/templates" );
            eZDir::mkdir( "design/" . $userDesignName . "/stylesheets" );
            eZDir::mkdir( "design/" . $userDesignName . "/images" );
            eZDir::mkdir( "design/" . $userDesignName . "/override" );
            eZDir::mkdir( "design/" . $userDesignName . "/override/templates" );

            $publishAdmin = false;
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $userAccount =& eZUser::fetch( 14 );
            if ( !is_object( $userAccount ) )
            {
                $resultArray['errors'][] = array( 'code' => 'EZSW-020',
                                                  'text' => "Could not fetch administrator user object" );
                return false;
            }

            $userObject =& $userAccount->attribute( 'contentobject' );
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
                $newUserObject =& $userObject->createNewVersion( 1, false );
                if ( !is_object( $newUserObject ) )
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-022',
                                                      'text' => "Could not create new version of administrator content object" );
                    return false;
                }
                $dataMap =& $newUserObject->attribute( 'data_map' );
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
                include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newUserObject->attribute( 'contentobject_id' ),
                                                                                             'version' => $newUserObject->attribute( 'version' ) ) );
                if ( $operationResult['status'] != EZ_MODULE_OPERATION_CONTINUE )
                {
                    $resultArray['errors'][] = array( 'code' => 'EZSW-025',
                                                      'text' => "Failed to properly publish the administrator object" );
                    return false;
                }
            }
        }
//         else
//             eZDebug::writeError( "Failed fetching package " . $siteType['identifier'] );

        // This the end, my friend
        // Well, almost
        return true;
    }

}

?>
