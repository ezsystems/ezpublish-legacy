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
include( "kernel/setup/ezsetuptests.php" );

/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep( &$tpl, &$http, &$ini, &$persistenceList )
{
    $databaseMap = eZSetupDatabaseMap();
    $regionalInfo = array( 'language_type' => 1,
                           'languages' => array(),
                           'primary_language' => 'eng-GB' );
    if ( isset( $persistenceList['regional_info'] ) )
        $regionalInfo = $persistenceList['regional_info'];
    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    $http =& eZHTTPTool::instance();
    if ( $http->hasPostVariable( 'eZSetupLanguageType' ) )
        $regionalInfo['language_type'] = $http->postVariable( 'eZSetupLanguageType' );
    if ( $http->hasPostVariable( 'eZSetupLanguages' ) )
        $regionalInfo['languages'] = $http->postVariable( 'eZSetupLanguages' );
    if ( $http->hasPostVariable( 'eZSetupPrimaryLanguage' ) )
        $regionalInfo['primary_language'] = $http->postVariable( 'eZSetupPrimaryLanguage' );
    $persistenceList['regional_info']['language_type'] = $regionalInfo['language_type'];
    include_once( 'lib/ezlocale/classes/ezlocale.php' );
    $availableLanguages = eZLocale::localeList( true );
    $persistenceList['regional_info'] = $regionalInfo;

    $tpl->setVariable( 'regional_info', $regionalInfo );
    $tpl->setVariable( 'available_languages', $availableLanguages );
    
    $template = "design:setup/init/regional_options.tpl";
    if ( $http->hasPostVariable( 'eZSetupChooseVariations' ) )
    {
        $template = "design:setup/init/regional_options_variations.tpl";
        $chosenLanguages = array();
        $languageVariations = array();
        $languageList = array_merge( array( $regionalInfo['primary_language'] ), $regionalInfo['languages'] );
        $languageList = array_unique( $languageList );
        foreach ( $languageList as $language )
        {
            $chosenLanguages[] = eZLocale::instance( $language );
        }
        for ( $i = 0; $i < count( $availableLanguages ); ++$i )
        {
            $language =& $availableLanguages[$i];
            $locale = $language->localeCode();
            if ( $language->countryVariation() )
            {
                if ( !isset( $languageVariations[$locale] ) )
                    $languageVariations[$locale] = array();
                $languageVariations[$locale][] =& $language;
            }
            else if ( !isset( $languageVariations[$locale] ) )
                      $languageVariations[$locale] = array();
        }
        $tpl->setVariable( 'chosen_languages', $chosenLanguages );
        $tpl->setVariable( 'language_variatons', $languageVariations );
    }

    $result = array();
    // Display template
    $result['content'] = $tpl->fetch( $template );
    $result['path'] = array( array( 'text' => 'Regional options',
                                    'url' => false ) );
    return $result;
}


?>