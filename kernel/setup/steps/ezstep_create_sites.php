<?php
//
// Definition of eZStepCreateSites class
//
// Created on: <13-Aug-2003 19:54:38 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
include_once( "kernel/setup/ezsetuptests.php" );
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
        $saveData = true; // set to true to save data

        $ini =& eZINI::create();

        $siteCount = $this->PersistenceList['site_templates']['count'];
        include_once( 'kernel/classes/ezpackage.php' );
        $accessMap = array( 'url' => array(),
                            'hostname' => array(),
                            'port' => array(),
                            'accesses' => array() );
        for ( $counter = 0; $counter < $siteCount; ++$counter )
        {
            $sitePackage = $this->PersistenceList['site_templates_'.$counter];
            $accessType = $sitePackage['access_type'];
            eZDebug::writeDebug( $sitePackage, "sitepackage_$counter" );
            $package =& eZPackage::fetch( $sitePackage['identifier'], 'kernel/setup/packages' );
            $this->initializePackage( $package, $sitePackage, $accessMap, $charset );
        }

//        $db->query ( 'show tables');

        $regionalInfo = $this->PersistenceList['regional_info'];
//         $demoData = $this->PersistenceList['demo_data'];
        $emailInfo = $this->PersistenceList['email_info'];
//         $siteInfo = $this->PersistenceList['site_info'];

        $imageINI = eZINI::create( 'image.ini' );
        $imageINI->setVariable( 'ConverterSettings', 'UseConvert', 'false' );
        $imageINI->setVariable( 'ConverterSettings', 'UseGD', 'false' );
        if ( $this->PersistenceList['imagemagick_program']['result'] )
        {
            $imageINI->setVariable( 'ConverterSettings', 'UseConvert', 'true' );
            $imageINI->setVariable( 'ShellSettings', 'ConvertPath', $this->PersistenceList['imagemagick_program']['path'] );
            $imageINI->setVariable( 'ShellSettings', 'ConvertExecutable', $this->PersistenceList['imagemagick_program']['program'] );
        }
        if ( $this->PersistenceList['imagegd_extension']['result'] )
        {
            $imageINI->setVariable( 'ConverterSettings', 'UseGD', 'true' );
        }
        $saveResult = false;
        if ( !$saveData )
            $saveResult = true;
        if ( $saveData )
            $saveResult = $imageINI->save( false, '.php', 'append', true );

//         if ( $saveResult )
//         {
//             $setupINI = eZINI::create( 'setup.ini' );
//             $setupINI->setVariable( "DatabaseSettings", "DefaultServer", $databaseInfo['server'] );
//             $setupINI->setVariable( "DatabaseSettings", "DefaultName", $databaseInfo['dbname'] );
//             $setupINI->setVariable( "DatabaseSettings", "DefaultUser", $databaseInfo['user'] );
//             $setupINI->setVariable( "DatabaseSettings", "DefaultPassword", $databaseInfo['password'] );
//             if ( $saveData )
//                 $saveResult = $setupINI->save( false, '.php', 'append', true );
//         }

        if ( $saveResult and
             $charset !== false )
        {
            $i18nINI = eZINI::create( 'i18n.ini' );
            $i18nINI->setVariable( 'CharacterSettings', 'Charset', $charset );
            if ( $saveData )
                $saveResult = $i18nINI->save( false, '.php', 'append', true );
        }

//             if ( $demoData['use'] )
//                 $ini->setVariable( 'SiteSettings', 'DefaultAccess', 'demo' );
//             else
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
        $ini->setVariable( 'SiteAccessSettings', 'SiteAccessList', $accessMap['accesses'] );
        $ini->setVariable( 'SiteAccessSettings', 'AvailableSiteAccessList', $accessMap['accesses'] );
        $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );
        $defaultAccess = 'admin';
        if ( isset( $accessMap['accesses'][0] ) )
            $defaultAccess = $accessMap['accesses'][0];
        $ini->setVariable( 'SiteSettings', 'DefaultAccess', $defaultAccess );

        if ( $saveData )
        {
            $saveResult = $ini->save( false, '.php', 'append', true );
        }

//     $htaccess = array( 'required' => false,
//                        'installed' => false );
//     if ( $this->PersistenceList['security']['install_htaccess'] )
//     {
//         $htaccess['required'] = true;
//         if ( file_exists( ".htaccess" ) )
//         {
//             if ( @copy( '.htaccess_root', '.htaccess.setupnew' ) )
//                 $htaccess['installed'] = '.htaccess.setupnew';
//         }
//         else
//         {
//             if ( @copy( '.htaccess_root', '.htaccess' ) )
//                 $htaccess['installed'] = true;
//         }
//     }

        return true; // Never show, generate sites
    }

    /*!
     \reimp
    */
    function &display()
    {
    }

    function findAppropriateCharset( &$primaryLanguage, &$extraLanguages, &$allLanguages, $canUseUnicode )
    {
        include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
//         $charset = $primaryLanguage->charset();
//         if ( $charset == '' )
//             $charset = 'iso-8859-1';

//         if ( $this->PersistenceList['regional_info']['language_type'] == 3 )
//             $charset = 'utf-8';
//         return $charset;
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
        eZDebug::writeDebug( $allCharsets, 'allCharsets' );
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
        eZDebug::writeDebug( $usableCharsets, 'usableCharsets' );
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
            else
            {
                // Pick preferred primary language
                $charset = $primaryLanguage->charset();
            }
        }
        eZDebug::writeDebug( $charset, 'charset' );
        return $charset;
    }

    function initializePackage( &$package, $sitePackage,
                                &$accessMap, &$charset )
    {
        eZDebug::writeDebug( $sitePackage, 'sitePackage' );
//         $sitePackage['admin_access_type_value'] = $sitePackage['access_type_value'] . '_admin';

        $canUseUnicode = $this->PersistenceList['database_use_unicode'];

        switch ( $sitePackage['access_type'] )
        {
            case 'port':
            {
                $userSiteaccessName = $sitePackage['identifier'] . '_' . 'user';
                $adminSiteaccessName = $sitePackage['identifier'] . '_' . 'admin';
                $accessMap['port'][$sitePackage['access_type_value']] = $userSiteaccessName;
                $accessMap['port'][$sitePackage['admin_access_type_value']] = $adminSiteaccessName;
            } break;
            case 'hostname':
            {
                $userSiteaccessName = $sitePackage['identifier'] . '_' . 'user';
                $adminSiteaccessName = $sitePackage['identifier'] . '_' . 'admin';
                $accessMap['hostname'][$sitePackage['access_type_value']] = $userSiteaccessName;
                $accessMap['hostname'][$sitePackage['admin_access_type_value']] = $adminSiteaccessName;
            } break;
            case 'url':
            default:
            {
                $userSiteaccessName = $sitePackage['access_type_value'];
                $adminSiteaccessName = $sitePackage['admin_access_type_value'];
                $accessMap['url'][$sitePackage['access_type_value']] = $userSiteaccessName;
                $accessMap['url'][$sitePackage['admin_access_type_value']] = $adminSiteaccessName;
            } break;
        }
        $accessMap['accesses'][] = $userSiteaccessName;
        $accessMap['accesses'][] = $adminSiteaccessName;
        $userDesignName = $sitePackage['identifier'];

        include_once( 'lib/ezlocale/classes/ezlocale.php' );

        $primaryLanguage = null;
        $allLanguages = array();
        $allLanguageCodes = array();
        $extraLanguages = array();
        $primaryLanguageCode = $this->PersistenceList['regional_info']['primary_language'];
        $extraLanguageCodes = array();
        if ( isset( $this->PersistenceList['regional_info']['languages'] ) )
            $extraLanguageCodes = $this->PersistenceList['regional_info']['languages'];
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

        $charset = $this->findAppropriateCharset( $primaryLanguage, $extraLanguages, $allLanguages, $canUseUnicode );

        $languages = $allLanguageCodes;
        $languageObjects = $allLanguages;
//         $languages = array( $primaryLanguage->localeFullCode() );
//         $languageObjects = array();
//         $languageObjects[] = $primaryLanguage;
//         foreach ( array_keys( $extraLanguages ) as $extraLanguageKey )
//         {
//             $extraLanguage = $extraLanguages[$extraLanguageKey];
//             $languages[] = $extraLanguage->localeFullCode();
//             $languageObjects[] = $extraLanguage;
//         }

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

        $dbName = $sitePackage['database'];
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => $dbName,
                               'charset' => $dbCharset );
        eZDebug::writeDebug( $dbDriver, 'dbDriver' );
        eZDebug::writeDebug( $dbParameters, 'dbParameters' );
        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        eZDB::setInstance( $db );
        if ( $package )
        {
//             eZDBTool::cleanup( $db );
            $installParameters = array( 'path' => '.' );
            $installParameters['ini'] = array();
            $siteINIChanges = array();
//             $siteInfo = $this->PersistenceList['site_info'];
            $url = $sitePackage['url'];
            if ( preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url, $matches ) )
            {
                $url = $matches[1];
            }
            $siteINIChanges['ContentSettings'] = array( 'TranslationList' => implode( ';', $languages ) );
            $siteINIChanges['SiteSettings'] = array( 'SiteName' => $sitePackage['title'],
                                                     'SiteURL' => $url );
            $siteINIChanges['DatabaseSettings'] = array( 'DatabaseImplementation' => $dbDriver,
                                                         'Server' => $dbServer,
                                                         'Database' => $dbName,
                                                         'User' => $dbUser,
                                                         'Password' => $dbPwd,
                                                         'Charset' => $charset );
            if ( trim( $dbSocket ) != '' )
                $siteINIChanges['DatabaseSettings']['Socket'] = $dbSocket;
            else
                $siteINIChanges['DatabaseSettings']['Socket'] = 'disabled';
            if ( $sitePackage['email'] )
            {
                $siteINIChanges['InformationCollectionSettings'] = array( 'EmailReceiver' => $sitePackage['email'] );
                $siteINIChanges['UserSettings'] = array( 'RegistrationEmail' => $sitePackage['email'] );
                $siteINIChanges['MailSettings'] = array( 'AdminEmail' => $sitePackage['email'],
                                                         'EmailSender' => $sitePackage['email'] );
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
            $package->install( $installParameters );
        }
        else
            eZDebug::writeError( "Failed fetching package " . $sitePackage['identifier'] );

        $saveResult = true;

        if ( $saveResult )
        {
            eZSetupChangeEmailSetting( $this->PersistenceList['email_info'] );

//             $ini->setVariable( "SiteSettings", "SiteName", $siteInfo['title'] );
//             if ( $siteInfo['admin_email'] )
//             {
//                 $ini->setVariable( 'InformationCollectionSettings', 'EmailReceiver', $siteInfo['admin_email'] );
//                 $ini->setVariable( 'UserSettings', 'RegistrationEmail', $siteInfo['admin_email'] );
//                 $ini->setVariable( 'MailSettings', 'AdminEmail', $siteInfo['admin_email'] );
//                 $ini->setVariable( 'MailSettings', 'EmailSender', $siteInfo['admin_email'] );
//             }
//             $ini->setVariable( "SiteSettings", "SiteURL", $url );

//             $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", $databaseInfo['info']['driver'] );
//             $ini->setVariable( "DatabaseSettings", "Server", $databaseInfo['server'] );
//             $ini->setVariable( "DatabaseSettings", "Database", $databaseInfo['dbname'] );
//             if ( trim( $databaseInfo['socket'] ) != '' )
//                 $ini->setVariable( "DatabaseSettings", "Socket", $databaseInfo['socket'] );
//             else
//                 $ini->setVariable( "DatabaseSettings", "Socket", 'disabled' );
//             $ini->setVariable( "DatabaseSettings", "User", $databaseInfo['user'] );
//             $ini->setVariable( "DatabaseSettings", "Password", $databaseInfo['password'] );

//             $ini->setVariable( 'RegionalSettings', 'Locale', $primaryLanguage->localeFullCode() );
//             $ini->setVariable( 'RegionalSettings', 'ContentObjectLocale', $primaryLanguage->localeCode() );
//             if ( $primaryLanguage->localeCode() == 'eng-GB' )
//                 $ini->setVariable( 'RegionalSettings', 'TextTranslation', 'disabled' );
//             else
//                 $ini->setVariable( 'RegionalSettings', 'TextTranslation', 'enabled' );

            $primaryLanguageLocaleCode = $primaryLanguage->localeCode();

            if ( $primaryLanguageLocaleCode != 'eng-GB' )
            {
//                 $siteCount = $this->PersistenceList['site_templates']['count'];
//                 for ( $counter = 0; $counter < $siteCount; ++$counter )
//                 {
//                     $dbName = $this->PersistenceList['site_templates_'.$counter]['database'];
//                     $dbParameters = array( 'server' => $dbServer,
//                                            'user' => $dbUser,
//                                            'password' => $dbPwd,
//                                            'socket' => $dbSocket,
//                                            'database' => $dbName,
//                                            'charset' => $dbCharset );
//                     $db =& eZDB::instance( $dbDriver, $dbParameters, true );


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
                    $translation->updateObjectNames();
                }
            }
        }
    }

}

?>
