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
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
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
            return 'LanguageOptions';

        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();

//         $siteCount = $this->PersistenceList['site_templates']['count'];
//         $siteCount = 1;
        $siteTypes = $this->chosenSiteTypes();
//         for ( $counter = 0; $counter < $siteCount; ++$counter )
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
//             $sitePackage = $this->PersistenceList['site_templates_'.$counter];
            $accessType = $siteType['access_type'];
//             $package =& eZPackage::fetch( $siteType['identifier'], 'kernel/setup/packages' );
            $this->initializePackage( //$package,
                                      $siteType, $accessMap, $charset,
                                      $allLanguageCodes, $allLanguages, $primaryLanguage, $this->PersistenceList['admin'] );
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
            $saveResult = $ini->save( false, '.php', 'append', true, true, true );
        }

        return true; // Never show, generate sites
    }

    /*!
     \reimp
    */
    function &display()
    {
    }

    function initializePackage( // &$package,
                                $siteType,
                                &$accessMap, $charset,
                                &$allLanguageCodes, &$allLanguages, &$primaryLanguage,
                                &$admin)
    {
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

//         if ( $package )
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
                $setupINI =& eZINI::instance( 'setup.ini' );
                $sqlSchemaFile = $setupINI->variable( 'DatabaseSettings', 'SQLSchema' );
                $sqlFile = $setupINI->variable( 'DatabaseSettings', 'CleanSQLData' );
//                 print( "inserting SQL $sqlSchemaFile!<br/>\n" );
                $result = $db->insertFile( 'kernel/sql/', $sqlSchemaFile );
//                 print( "inserting SQL $sqlFile!<br/>\n" );
                $result = $result && $db->insertFile( 'kernel/sql/common', $sqlFile, false );
            }
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
            if ( $this->PersistenceList['admin']['email'] )
            {
                $siteINIChanges['InformationCollectionSettings'] = array( 'EmailReceiver' => false );
                $siteINIChanges['UserSettings'] = array( 'RegistrationEmail' => false );
                $siteINIChanges['MailSettings'] = array( 'AdminEmail' =>  $this->PersistenceList['admin']['email'],
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

//             print( "<pre>" ); var_dump( $siteINIChanges ); print( "</pre>" );

//             print( "user design: " . $userDesignName . "<br/>\n" );
//             print( "user access: " . $userSiteaccessName . "<br/>\n" );
//             print( "admin access: " . $adminSiteaccessName . "<br/>\n" );
//             exit;

            $siteINI =& eZINI::create( 'site.ini' );
            $siteINI->setVariables( $siteINIChanges );
            $siteINI->save( false, '.append.php', false, true, "settings/siteaccess/$adminSiteaccessName" );
            $siteINI->setVariable( 'DesignSettings', 'SiteDesign', $userDesignName );
            $siteINI->setVariable( 'DesignSettings', 'AdditionalSiteDesignList', array( 'base' ) );
            $siteINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName" );

            $extraSettings = eZSetupINISettings( $siteType );
            foreach ( $extraSettings as $extraSetting )
            {
                $iniName = $extraSetting['name'];
                $settings = $extraSetting['settings'];
                $tmpINI =& eZINI::create( $iniName );
                $tmpINI->setVariables( $settings );
                $tmpINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName" );
            }

            eZDir::mkdir( "design/" . $userDesignName );
            eZDir::mkdir( "design/" . $userDesignName . "/templates" );
            eZDir::mkdir( "design/" . $userDesignName . "/stylesheets" );
            eZDir::mkdir( "design/" . $userDesignName . "/images" );
            eZDir::mkdir( "design/" . $userDesignName . "/override" );
            eZDir::mkdir( "design/" . $userDesignName . "/override/templates" );

//             $package->install( $installParameters );

            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $userAccount =& eZUser::fetch( 14 );
            $userObject =& $userAccount->attribute( 'contentobject' );
            $userAccount->setInformation( 14, 'admin', $admin['email'], $admin['password'], $admin['password'] );
            $dataMap =& $userObject->attribute( 'data_map' );
            $dataMap['first_name']->setAttribute( 'data_text', $admin['first_name'] );
            $dataMap['first_name']->store();
            $dataMap['last_name']->setAttribute( 'data_text', $admin['last_name'] );
            $dataMap['last_name']->store();
            $userObject->store();
            $userAccount->store();

            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userObject->attribute( 'id' ),
                                                                                         'version' => $userObject->attribute( 'version' ) ) );
        }
//         else
//             eZDebug::writeError( "Failed fetching package " . $siteType['identifier'] );

        $saveResult = true;

        if ( $saveResult )
        {
//            eZSetupChangeEmailSetting( $this->PersistenceList['email_info'] );

            $primaryLanguageLocaleCode = $primaryLanguage->localeCode();

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
        }
    }

}

?>
