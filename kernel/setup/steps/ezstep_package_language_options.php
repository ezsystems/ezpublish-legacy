<?php
//
// Definition of eZStepPackageLanguageOptions class
//
// Created on: <21-Feb-2007 17:27:57 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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
require_once( 'kernel/common/i18n.php' );
//include_once( 'kernel/classes/ezpackage.php' );

/*!
  \class eZStepPackageLanguageOptions ezstep_package_language_options.php
  \brief The class eZStepPackageLanguageOptions does

*/

class eZStepPackageLanguageOptions extends eZStepInstaller
{
    /*!
     Constructor
     \reimp
    */
    function eZStepPackageLanguageOptions( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'package_language_options', 'Package language options' );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        $languageMap = array();
        if( $this->Http->hasPostVariable( 'eZSetupPackageLanguageMap' ) )
        {
            $languageMap = $this->Http->postVariable( 'eZSetupPackageLanguageMap' );
        }

        // Add site languages.
        $siteLanguageLocaleList = $this->PersistenceList['regional_info']['languages'];
        foreach( $siteLanguageLocaleList as $siteLanguage )
            $languageMap[$siteLanguage] = $siteLanguage;

        $this->PersistenceList['package_info']['language_map'] = $languageMap;

        return true;
    }

    /*!
      \reimp
     */
    function init()
    {
        /*
        if( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            return $this->kickstartContinueNextStep();
        }
        */

        //
        // Get all available languages
        //
        $languages = false;
        $defaultLanguage = false;
        $defaultExtraLanguages = false;

        eZSetupLanguageList( $languages, $defaultLanguage, $defaultExtraLanguages );


        //
        // Get info about package and site languages
        //
        $siteLanguageLocaleList = $this->PersistenceList['regional_info']['languages'];

        $packageNameList = array();
        $packageLanguageLocaleList = array();

        $sitePackageName = $this->chosenSitePackage();
        $sitePackage = eZPackage::fetch( $sitePackageName, false, false, false );
        if( is_object( $sitePackage ) )
        {
            $dependencies = $sitePackage->attribute( 'dependencies' );
            $requirements = $dependencies['requires'];

            foreach( $requirements as $req )
            {
                $packageNameList[] = $req['name'];
            }

            $packageLanguageLocaleList = eZPackage::languageInfoFromPackageList( $packageNameList, false );
        }

        // Explicitly add 'eng-GB' cause clean data is in 'eng-GB'.
        if( !in_array( 'eng-GB', $packageLanguageLocaleList ) )
            $packageLanguageLocaleList[] = 'eng-GB';
        //
        // Exclude languages which exist both in packges and site.
        //
        $packageLanguageLocaleList = array_diff( $packageLanguageLocaleList, $siteLanguageLocaleList );

        if( count( $packageLanguageLocaleList ) > 0 )
        {
            //
            // Get language names
            //
            $siteLanguageList = array();
            $packageLanguageList = array();
            foreach( $languages as $language )
            {
                $locale = $language->attribute( 'locale_code' );
                $name = $language->attribute( 'intl_language_name' );

                if( in_array( $locale, $siteLanguageLocaleList ) )
                {
                    $siteLanguageList[] = array( 'locale' => $locale,
                                                 'name' => $name );
                }

                if( in_array( $locale, $packageLanguageLocaleList ) )
                {
                    $packageLanguageList[] = array( 'locale' => $locale,
                                                    'name' => $name );
                }
            }

            $this->MissedPackageLanguageList = $packageLanguageList;
            $this->SiteLanguageList = $siteLanguageList;

            return false;
        }

        // There are no language conflicts => proceed with next step
        return true;
    }

    /*!
     \reimp
     */
    function display()
    {
        $packageLanguageList = $this->MissedPackageLanguageList;
        $siteLanguageList = $this->SiteLanguageList;

        $this->Tpl->setVariable( 'package_language_list', $packageLanguageList );
        $this->Tpl->setVariable( 'site_language_list', $siteLanguageList );

        $result = array();
        $result['content'] = $this->Tpl->fetch( "design:setup/init/package_language_options.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Package language options' ),
                                        'url' => false ) );
        return $result;
    }

    public $MissedPackageLanguageList;
    public $SiteLanguageList;
}
?>
