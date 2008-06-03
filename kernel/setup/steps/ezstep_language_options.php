<?php
//
// Definition of eZStepLanguageOptions class
//
// Created on: <11-Aug-2003 17:27:57 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file ezstep_language_options.php
*/
//include_once( 'kernel/setup/steps/ezstep_installer.php' );
//include_once( 'kernel/setup/ezsetupcommon.php' );
require_once( "kernel/common/i18n.php" );

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
    function eZStepLanguageOptions( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'language_options', 'Language options' );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        $primaryLanguage = $this->Http->postVariable( 'eZSetupDefaultLanguage' );
        $languages       = $this->Http->hasPostVariable( 'eZSetupLanguages' ) ? $this->Http->postVariable( 'eZSetupLanguages' ): array();

        if ( !in_array( $primaryLanguage, $languages ) )
            $languages[] = $primaryLanguage;

        $regionalInfo = array();
        $regionalInfo['language_type'] = 1 ;
        $regionalInfo['primary_language'] = $primaryLanguage;
        $regionalInfo['languages'] = $languages;
        $regionalInfo['enable_unicode'] = true;
        $regionalInfo['site_charset'] = 'utf-8';

        $this->PersistenceList['regional_info'] = $regionalInfo;
        $charset = false;

//SP experimental code 26.04.2007 commented "if"
//        if ( !isset( $this->PersistenceList['database_info']['use_unicode'] ) ||
//             $this->PersistenceList['database_info']['use_unicode'] == false )
//        {
            // If we have already figured out charset and it is utf-8
            // we don't have to check the new languages
            if ( isset( $this->PersistenceList['regional_info']['site_charset'] ) and
                 $this->PersistenceList['regional_info']['site_charset'] == 'utf-8' )
            {
                $charset = 'utf-8';
            }
            else
            {
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

                $canUseUnicode = isset( $this->PersistenceList['database_info']['use_unicode'] ) ? $this->PersistenceList['database_info']['use_unicode'] : false;
                $charset = $this->findAppropriateCharset( $primaryLanguage, $allLanguages, $canUseUnicode );
                if ( !$charset )
                {
                    $this->Error = 1;
                    return false;
                }
            }
            // Store the charset for later handling
            $this->PersistenceList['regional_info']['site_charset'] = $charset;
//SP experimental code 26.04.2007 commented "if"
//      }


        if ( $this->PersistenceList['regional_info']['site_charset'] )
        {
            $i18nINI = eZINI::create( 'i18n.ini' );
            // Set ReadOnlySettingsCheck to false: towards
            // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
            $i18nINI->setReadOnlySettingsCheck( false );

            $i18nINI->setVariable( 'CharacterSettings', 'Charset', $this->PersistenceList['regional_info']['site_charset'] );
            $i18nINI->save( false, '.php', 'append', true );
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
            $regionalInfo['enable_unicode'] = true;

            $this->PersistenceList['regional_info'] = $regionalInfo;
            $this->storePersistenceData();

            return $this->kickstartContinueNextStep();
        }

        return false;
    }

    /*!
     \reimp
     */
    function display()
    {
        $languages = false;
        $defaultLanguage = false;
        $defaultExtraLanguages = false;

        eZSetupLanguageList( $languages, $defaultLanguage, $defaultExtraLanguages );

        $this->Tpl->setVariable( 'language_list', $languages );

        $showUnicodeError = false;
        if ( isset( $this->Error ) )
        {
            $showUnicodeError = !$this->PersistenceList['database_info']['use_unicode'];
            $this->PersistenceList['database_info']['use_unicode'] = false;
        }
        $this->Tpl->setVariable( 'show_unicode_error', $showUnicodeError );

        $regionalInfo = array( 'primary_language' => $defaultLanguage,
                               'languages' => $defaultExtraLanguages );
        if ( isset( $this->PersistenceList['regional_info'] ) )
            $regionalInfo = $this->PersistenceList['regional_info'];
        if ( !isset( $regionalInfo['enable_unicode'] ) )
            $regionalInfo['enable_unicode'] = true;

        $this->Tpl->setVariable( 'regional_info', $regionalInfo );

        // The default is to not use unicode if it has not been detected by
        // database driver to be OK.
        $databaseInfo = array( 'use_unicode' => false );
        if ( isset( $this->PersistenceList['database_info'] ) )
        {
            $databaseInfo = $this->PersistenceList['database_info'];
        }

        $this->Tpl->setVariable( 'database_info', $databaseInfo );

        $result = array();
        // Display template

        $result['content'] = $this->Tpl->fetch( "design:setup/init/language_options.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Language options' ),
                                        'url' => false ) );
        return $result;
    }


    public $Error;
}

?>
