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
        $databaseMap = eZSetupDatabaseMap();

        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];

        $dbServer = $databaseInfo['server'];
        $dbName = $databaseInfo['name'];
        $dbSocket = $databaseInfo['socket'];
        $dbUser = $databaseInfo['user'];
        $dbPwd = $databaseInfo['password'];
        $dbCharset = $charset;
        $dbDriver = $databaseInfo['info']['driver'];
        $dbParameters = array( 'server' => $dbServer,
                               'user' => $dbUser,
                               'password' => $dbPwd,
                               'socket' => $dbSocket,
                               'database' => $dbName,
                               'charset' => $dbCharset );
        $db =& eZDB::instance( $dbDriver, $dbParameters, true );
//        $db =& eZDB::instance( );

        $db->query ( 'show tables');

        $regionalInfo = $this->PersistenceList['regional_info'];
        $demoData = $this->PersistenceList['demo_data'];
        $emailInfo = $this->PersistenceList['email_info'];
        $siteInfo = $this->PersistenceList['site_info'];

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

        $charset = false;
        if ( $saveResult )
        {
            eZSetupChangeEmailSetting( $this->PersistenceList['email_info'] );

            $ini->setVariable( "SiteSettings", "SiteName", $siteInfo['title'] );
            $url = $siteInfo['url'];
            if ( preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url, $matches ) )
            {
                $url = $matches[1];
            }
            if ( $siteInfo['admin_email'] )
            {
                $ini->setVariable( 'InformationCollectionSettings', 'EmailReceiver', $siteInfo['admin_email'] );
                $ini->setVariable( 'UserSettings', 'RegistrationEmail', $siteInfo['admin_email'] );
                $ini->setVariable( 'MailSettings', 'AdminEmail', $siteInfo['admin_email'] );
                $ini->setVariable( 'MailSettings', 'EmailSender', $siteInfo['admin_email'] );
            }
            $ini->setVariable( "SiteSettings", "SiteURL", $url );

            $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", $databaseInfo['info']['driver'] );
            $ini->setVariable( "DatabaseSettings", "Server", $databaseInfo['server'] );
            $ini->setVariable( "DatabaseSettings", "Database", $databaseInfo['name'] );
            if ( trim( $databaseInfo['socket'] ) != '' )
                $ini->setVariable( "DatabaseSettings", "Socket", $databaseInfo['socket'] );
            else
                $ini->setVariable( "DatabaseSettings", "Socket", 'disabled' );
            $ini->setVariable( "DatabaseSettings", "User", $databaseInfo['user'] );
            $ini->setVariable( "DatabaseSettings", "Password", $databaseInfo['password'] );

            include_once( 'lib/ezlocale/classes/ezlocale.php' );

            $primaryLanguage = null;
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

            if ( $primaryLanguage === null )
                $primaryLanguage = eZLocale::create( $this->PersistenceList['regional_info']['primary_language'] );
            $ini->setVariable( 'RegionalSettings', 'Locale', $primaryLanguage->localeFullCode() );
            $ini->setVariable( 'RegionalSettings', 'ContentObjectLocale', $primaryLanguage->localeCode() );
            if ( $primaryLanguage->localeCode() == 'eng-GB' )
                $ini->setVariable( 'RegionalSettings', 'TextTranslation', 'disabled' );
            else
                $ini->setVariable( 'RegionalSettings', 'TextTranslation', 'enabled' );

            $charset = $primaryLanguage->charset();
            if ( $charset == '' )
                $charset = 'iso-8859-1';

            if ( $this->PersistenceList['regional_info']['language_type'] == 3 )
                $charset = 'utf-8';

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
            }

            $ini->setVariable( 'DatabaseSettings', 'Charset', $charset );

            $languages = array( $primaryLanguage->localeFullCode() );
            $languageObjects = array();
            $languageObjects[] = $primaryLanguage;
            foreach ( array_keys( $extraLanguages ) as $extraLanguageKey )
            {
                $extraLanguage = $extraLanguages[$extraLanguageKey];
                $languages[] = $extraLanguage->localeFullCode();
                $languageObjects[] = $extraLanguage;
            }
            $ini->setVariable( 'ContentSettings', 'TranslationList', implode( ';', $languages ) );
            include_once( 'kernel/classes/ezcontenttranslation.php' );
            foreach ( array_keys( $languageObjects ) as $languageObjectKey )
            {
                $languageObject = $languageObjects[$languageObjectKey];
                $languageLocale = $languageObject->localeCode();

//                $db->query( 'SELECT * from ezurl' );
//                $db->query( 'show tables');

                if ( !eZContentTranslation::hasTranslation( $languageLocale ) )
                {
                    $translation = eZContentTranslation::createNew( $languageObject->languageName(), $languageLocale );
                    $translation->store();
                    $translation->updateObjectNames();
                }
            }

            $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );

            if ( $demoData['use'] )
                $ini->setVariable( 'SiteSettings', 'DefaultAccess', 'demo' );
            else
                $ini->setVariable( 'SiteSettings', 'DefaultAccess', 'admin' );

            if ( $saveData ){
                print( "KAKE !");
                $saveResult = $ini->save( false, '.php', 'append', true );
            }
        }


        if ( $saveResult )
        {
            $setupINI = eZINI::create( 'setup.ini' );
            $setupINI->setVariable( "DatabaseSettings", "DefaultServer", $databaseInfo['server'] );
            $setupINI->setVariable( "DatabaseSettings", "DefaultName", $databaseInfo['name'] );
            $setupINI->setVariable( "DatabaseSettings", "DefaultUser", $databaseInfo['user'] );
            $setupINI->setVariable( "DatabaseSettings", "DefaultPassword", $databaseInfo['password'] );
            if ( $saveData )
                $saveResult = $setupINI->save( false, '.php', 'append', true );
        }

        if ( $saveResult and
             $charset !== false )
        {
            $i18nINI = eZINI::create( 'i18n.ini' );
            $i18nINI->setVariable( 'CharacterSettings', 'Charset', $charset );
            if ( $saveData )
                $saveResult = $i18nINI->save( false, '.php', 'append', true );
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

}

?>
