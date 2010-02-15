<?php
//
// Definition of EZStepWelcome class
//
// Created on: <08-Aug-2003 15:00:02 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/



/*!
  \class eZStepWelcome ezstep_welcome.php
  \brief The class eZStepWelcome does

*/
class eZStepWelcome extends eZStepInstaller
{

    /*!
     Constructor
    */
    function eZStepWelcome( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'welcome', 'Welcome' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_finetune_button' ) )
        {
            $this->PersistenceList['run_finetune'] = true;
        }

        if ( $this->Http->hasPostVariable( 'eZSetupWizardLanguage' ) )
        {
            $wizardLanguage = $this->Http->postVariable( 'eZSetupWizardLanguage' );
            $this->PersistenceList['setup_wizard'] = array( 'language' => $wizardLanguage );

            eZTranslatorManager::setActiveTranslation( $wizardLanguage );
        }

        return true;
    }

    function init()
    {
        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $optionalRunResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:system_check', $this->PersistenceList );
        $this->OptionalResults = $optionalRunResult['results'];
        $this->OptionalResult = $optionalRunResult['result'];

        $testsRun = array();
        if ( isset( $this->Results ) && is_array( $this->Results ) )
        {
            foreach ( $this->Results as $testResultItem )
            {
                $testsRun[$testResultItem[1]] = $testResultItem[0];
            }
        }

        $this->PersistenceList['tests_run'] = $testsRun;
        $this->PersistenceList['optional_tests_run'] = $testsRun;

        return false; // Always show welcome message
    }

    function display()
    {
        $result = array();

        $languages = false;
        $defaultLanguage = false;
        $defaultExtraLanguages = false;

        eZSetupLanguageList( $languages, $defaultLanguage, $defaultExtraLanguages );

        eZTranslatorManager::setActiveTranslation( $defaultLanguage, false );

        $this->Tpl->setVariable( 'language_list', $languages );
        $this->Tpl->setVariable( 'primary_language', $defaultLanguage );
        $this->Tpl->setVariable( 'optional_test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/welcome.tpl' );
        $result['path'] = array( array( 'text' => eZi18n::translate( 'design/standard/setup/init',
                                                          'Welcome to eZ Publish' ),
                                    'url' => false ) );

        return $result;
    }

    function showMessage()
    {
        return true;
    }

}

?>
