<?php
//
// Definition of eZStepSystemFinetune class
//
// Created on: <08-Aug-2003 16:46:32 kk>
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
  \class eZStepSystemCheck ezstep_system_check.php
  \brief The class eZStepSystemCheck does

*/

class eZStepSystemFinetune extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSystemFinetune( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'system_finetune', 'System finetune' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_finetune_button' ) )
        {
            $this->PersistenceList['run_finetune'] = true;
            return false;
        }
        $this->PersistenceList['run_finetune'] = false;
        return true;
    }

    function init()
    {
        if ( !isset( $this->PersistenceList['run_finetune'] ) )
            $this->PersistenceList['run_finetune'] = false;
        if ( $this->PersistenceList['run_finetune'] )
        {
            $criticalTests = eZSetupCriticalTests();
            $optionalTests = eZSetupOptionalTests();
            $testTable = eZSetupTestTable();

            $runResult = eZSetupRunTests( $criticalTests, 'eZSetup:init:system_check', $this->PersistenceList );
            $optionalRunResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:system_check', $this->PersistenceList );
            $this->Results = $runResult['results'];
            $this->Result = $runResult['result'];
            $this->OptionalResults = $optionalRunResult['results'];
            $this->OptionalResult = $optionalRunResult['result'];
            $persistenceData = $runResult['persistence_list'];

            $testsRun = array();
            foreach ( $this->Results as $testResultItem )
            {
                $testsRun[$testResultItem[1]] = $testResultItem[0];
            }

            eZSetupMergePersistenceList( $this->PersistenceList, $persistenceData );

            $this->PersistenceList['tests_run'] = $testsRun;
            $this->PersistenceList['optional_tests_run'] = $testsRun;

            return ( $this->OptionalResult == EZ_SETUP_TEST_SUCCESS );
        }
        return true;
    }

    function display()
    {
        $this->Tpl->setVariable( 'test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $this->Tpl->setVariable( 'persistence_data', $this->PersistenceList );
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/system_finetune.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'System finetuning' ),
                                        'url' => false ) );
        return $result;
    }

    function showMessage()
    {
        return false;
    }

    // Variables for storing results from tests
    public $Result = null;
    public $Results = null;
}

?>
