<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

// All test functions should be defined in ezsetuptests
include_once( "kernel/setup/ezsetuptests.php" );

/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep_finished( &$tpl, &$http, &$ini, &$persistenceList )
{
    $databaseMap = eZSetupDatabaseMap();
    $databaseInfo = $persistenceList['database_info'];
    $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
    $regionalInfo = $persistenceList['regional_info'];
    $demoData = $persistenceList['demo_data'];
    $emailInfo = $persistenceList['email_info'];
    $siteInfo = $persistenceList['site_info'];

    $imageINI =& eZINI::instance( 'image.ini' );
    $imageINI->setVariable( 'ConverterSettings', 'UseConvert', 'false' );
    $imageINI->setVariable( 'ConverterSettings', 'UseGD', 'false' );
    if ( $persistenceList['imagemagick_program']['result'] )
    {
        $imageINI->setVariable( 'ConverterSettings', 'UseConvert', 'true' );
        $imageINI->setVariable( 'ShellSettings', 'ConvertPath', $persistenceList['imagemagick_program']['path'] );
        $imageINI->setVariable( 'ShellSettings', 'ConvertExecutable', $persistenceList['imagemagick_program']['program'] );
    }
    if ( $persistenceList['imagegd_extension']['result'] )
    {
        $imageINI->setVariable( 'ConverterSettings', 'UseGD', 'true' );
    }
    $saveResult = $imageINI->save( false, '.php', false );

    $charset = false;
    if ( $saveResult )
    {
        eZSetupChangeEmailSetting( $persistenceList['email_info'] );

        $ini->setVariable( "SiteSettings", "SiteName", $siteInfo['title'] );
        $url = $siteInfo['url'];
        if ( preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url, $matches ) )
        {
            $url = $matches[1];
        }
        $ini->setVariable( "SiteSettings", "SiteURL", $url );

        $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", $databaseInfo['info']['driver'] );
        $ini->setVariable( "DatabaseSettings", "Server", $databaseInfo['server'] );
        $ini->setVariable( "DatabaseSettings", "Database", $databaseInfo['name'] );
        $ini->setVariable( "DatabaseSettings", "User", $databaseInfo['user'] );
        $ini->setVariable( "DatabaseSettings", "Password", $databaseInfo['password'] );

        include_once( 'lib/ezlocale/classes/ezlocale.php' );

        $primaryLanguage = null;
        $extraLanguages = array();
        $primaryLanguageCode = $persistenceList['regional_info']['primary_language'];
        $extraLanguageCodes = array();
        if ( isset( $persistenceList['regional_info']['languages'] ) )
            $extraLanguageCodes = $persistenceList['regional_info']['languages'];
        if ( isset( $persistenceList['regional_info']['variations'] ) )
        {
            $variations = $persistenceList['regional_info']['variations'];
            foreach ( $variations as $variation )
            {
                $locale = eZLocale::instance( $variation );
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
            $primaryLanguage =& eZLocale::instance( $persistenceList['regional_info']['primary_language'] );
        $ini->setVariable( 'RegionalSettings', 'Locale', $primaryLanguage->localeFullCode() );
        $ini->setVariable( 'RegionalSettings', 'ContentObjectLocale', $primaryLanguage->localeCode() );
        if ( $primaryLanguage->localeCode() == 'eng-GB' )
            $ini->setVariable( 'RegionalSettings', 'TextTranslation', 'disabled' );
        else
            $ini->setVariable( 'RegionalSettings', 'TextTranslation', 'enabled' );

        $charset = $primaryLanguage->charset();
        if ( $charset == '' )
            $charset = 'iso-8859-1';

        if ( $persistenceList['regional_info']['language_type'] == 3 )
            $charset = 'utf-8';

        $ini->setVariable( 'DatabaseSettings', 'Charset', $charset );

        $languages = array( $primaryLanguage->localeFullCode() );
        foreach ( $extraLanguages as $extraLanguage )
        {
            $languages[] = $extraLanguage->localeFullCode();
        }
        $ini->setVariable( 'ContentSettings', 'TranslationList', implode( ';', $languages ) );

        $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );

        $saveResult = $ini->save( false, '.php', false );
    }
    
    if ( $saveResult )
    {
        $setupINI =& eZINI::instance( 'setup.ini' );
        $setupINI->setVariable( "DatabaseSettings", "DefaultServer", $databaseInfo['server'] );
        $setupINI->setVariable( "DatabaseSettings", "DefaultName", $databaseInfo['name'] );
        $setupINI->setVariable( "DatabaseSettings", "DefaultUser", $databaseInfo['user'] );
        $setupINI->setVariable( "DatabaseSettings", "DefaultPassword", $databaseInfo['password'] );
        $saveResult = $setupINI->save( false, '.php', false );
    }

    if ( $saveResult and
         $charset !== false )
    {
        $i18nINI =& eZINI::instance( 'i18n.ini' );
        $i18nINI->setVariable( 'CharacterSettings', 'Charset', $charset );
        $saveResult = $i18nINI->save( false, '.php', false );
    }

    $tpl->setVariable( 'site_info', $siteInfo );
    $tpl->setVariable( 'email_info', $emailInfo );

    $result = array();
    // Display template
    $result['content'] = $tpl->fetch( "design:setup/init/finished.tpl" );
    $result['path'] = array( array( 'text' => 'Database initalization',
                                    'url' => false ) );

    return $result;
}


?>
