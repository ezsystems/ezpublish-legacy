<?php
//
// Definition of eZStepLanguageOptions class
//
// Created on: <11-Aug-2003 17:27:57 kk>
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

/*! \file ezstep_language_options.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php' );
include_once( 'kernel/setup/ezsetupcommon.php' );
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepLanguageOptions ezstep_language_options.php
  \brief The class eZStepLanguageOptions does

*/

class eZStepLanguageOptions extends eZStepInstaller
{
    /*!
     Constructor
     \reimp
    */
    function eZStepLanguageOptions(&$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'language_options', 'Language options' );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        $regionalInfo = array();
        $regionalInfo['language_type'] = 1 ;
        $primaryLanguage = $this->Http->postVariable( 'eZSetupDefaultLanguage' );
        $languages = array();
        if ( $this->Http->hasPostVariable( 'eZSetupLanguages' ) )
            $languages = $this->Http->postVariable( 'eZSetupLanguages' );
        if ( !in_array( $primaryLanguage, $languages ) )
            $languages[] = $primaryLanguage;
        $regionalInfo['primary_language'] = $primaryLanguage;
        $regionalInfo['languages'] = $languages;
        $this->PersistenceList['regional_info'] = $regionalInfo;

        if ( !isset( $this->PersistenceList['database_info']['use_unicode'] ) ||
             $this->PersistenceList['database_info']['use_unicode'] == false )
        {
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

            $charset = $this->findAppropriateCharset( $primaryLanguage, $allLanguages, false );
            if ( !$charset )
            {
                $this->Error = 1;
                return false;
            }
        }

        return true;
    }

    /*!
      \reimp
     */
    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $regionalInfo = array();
            $regionalInfo['primary_language'] = $data['Primary'];
            if ( !in_array( $data['Primary'], $data['Languages'] ) )
                $data['Languages'][] = $data['Primary'];
            $regionalInfo['languages'] = $data['Languages'];
            $this->PersistenceList['regional_info'] = $regionalInfo;

            return true;
        }

        return false;
    }

    /*!
     \reimp
     */
    function display()
    {
        $locales =& eZLocale::localeList( true );
        $languages = array();
        foreach ( array_keys( $locales ) as $localeKey )
        {
            $locale =& $locales[$localeKey];
            if ( !$locale->attribute( 'country_variation' ) )
                $languages[] = $locale;
        }

        // bubble sort language based on language name. bubble bad, but only about 8-9 elements
        for ( $i =0; $i < count( $languages ); $i++ )
            for ( $n = 0; $n < count( $languages ) - 1; $n++ )
            {
                if ( strcmp( $languages[$n]['language_name'], $languages[$n+1]['language_name'] ) > 0 )
                {
                    $tmpElement = $languages[$n];
                    $languages[$n] = $languages[$n+1];
                    $languages[$n+1] = $tmpElement;
                }
            }

        $this->Tpl->setVariable( 'language_list', $languages );
        $showUnicodeError = false;
        if ( isset( $this->Error ) )
        {
            $showUnicodeError = !$this->PersistenceList['database_info']['use_unicode'];
            unset( $this->PersistenceList['database_info']['use_unicode'] );
        }
        $this->Tpl->setVariable( 'show_unicode_error', $showUnicodeError );

        $result = array();
        // Display template

        $result['content'] = $this->Tpl->fetch( "design:setup/init/language_options.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Language options' ),
                                        'url' => false ) );
        return $result;
    }


    var $Error;
}

?>
