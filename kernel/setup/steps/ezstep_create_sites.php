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

        $siteCount = $this->PersistenceList['site_templates']['count'];
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

        for ( $counter = 0; $counter < $siteCount; ++$counter )
        {
            $sitePackage = $this->PersistenceList['site_templates_'.$counter];
            $accessType = $sitePackage['access_type'];
            $package =& eZPackage::fetch( $sitePackage['identifier'], 'kernel/setup/packages' );
            $this->initializePackage( $package, $sitePackage, $accessMap, $charset,
                                      $allLanguageCodes, $allLanguages, $primaryLanguage );
        }

        $regionalInfo = $this->PersistenceList['regional_info'];
        $emailInfo = $this->PersistenceList['email_info'];

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

            $imageINI->setVariable( 'Rules', 'DefaultRule', 'image/jpeg;gd' );
            $imageINI->setVariable( 'Rules', 'Rules', array( 'image/jpeg;image/jpeg;gd',
                                                             'image/png;image/png;gd',
                                                             'image/gif;image/png;gd',
                                                             'image/xpm;image/png;gd',
                                                             'image/tiff;image/png;gd' ) );
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
        $ini->setVariable( 'SiteAccessSettings', 'SiteAccessList', $accessMap['accesses'] );
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

    function initializePackage( &$package, $sitePackage,
                                &$accessMap, $charset,
                                &$allLanguageCodes, &$allLanguages, &$primaryLanguage )
    {
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
        $accessMap['sites'][] = $userSiteaccessName;
        $userDesignName = $sitePackage['identifier'];

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

        $dbName = $sitePackage['database'];
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => $dbName,
                               'charset' => $dbCharset );
        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
        eZDB::setInstance( $db );
        if ( $package )
        {
            if ( $sitePackage['existing_database'] == 2 )
            {
                $tableArray = $db->eZTableList();
                foreach ( $tableArray as $table )
                {
                    $db->removeRelation( $table, EZ_DB_RELATION_TABLE );
                }
            }
            $installParameters = array( 'path' => '.' );
            $installParameters['ini'] = array();
            $siteINIChanges = array();
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
            $siteINIChanges['FileSettings'] = array( 'VarDir' => 'var/' . $sitePackage['identifier'] );
            if ( trim( $dbSocket ) != '' )
                $siteINIChanges['DatabaseSettings']['Socket'] = $dbSocket;
            else
                $siteINIChanges['DatabaseSettings']['Socket'] = 'disabled';
            if ( $sitePackage['email'] )
            {
//                 $siteINIChanges['InformationCollectionSettings'] = array( 'EmailReceiver' => $sitePackage['email'] );
//                 $siteINIChanges['UserSettings'] = array( 'RegistrationEmail' => $sitePackage['email'] );
//                 $siteINIChanges['MailSettings'] = array( 'AdminEmail' => $sitePackage['email'],
//                                                          'EmailSender' => $sitePackage['email'] );
                $siteINIChanges['InformationCollectionSettings'] = array( 'EmailReceiver' => false );
                $siteINIChanges['UserSettings'] = array( 'RegistrationEmail' => false );
                $siteINIChanges['MailSettings'] = array( 'AdminEmail' => $sitePackage['email'],
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
            $package->install( $installParameters );
        }
        else
            eZDebug::writeError( "Failed fetching package " . $sitePackage['identifier'] );

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
