<?php
//
// Definition of eZStepSystemFinetune class
//
// Created on: <08-Aug-2003 16:46:32 kk>
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

/*! \file ezstep_system_check.php
*/
include_once( "kernel/setup/ezsetuptests.php" );
include_once( 'kernel/setup/steps/ezstep_installer.php' );
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepSystemCheck ezstep_system_check.php
  \brief The class eZStepSystemCheck does

*/

class eZStepSystemFinetune extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSystemFinetune( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     */
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

    /*!
     */
    function init()
    {
        if ( !isset( $this->PersistenceList['run_finetune'] ) )
    	    $this->PersistenceList['run_finetune'] = false;
        if ( $this->PersistenceList['run_finetune'] )
        {
            $criticalTests = eZSetupCriticalTests();
            $optionalTests = eZSetupOptionalTests();
            $testTable = eZSetupTestTable();

            $arguments = array();
            $runResult = eZSetupRunTests( $criticalTests, $arguments, 'eZSetup:init:system_check', $this->PersistenceList );
            $optionalRunResult = eZSetupRunTests( $optionalTests, $arguments, 'eZSetup:init:system_check', $this->PersistenceList );
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

    /*!
    */
    function &display()
    {
        $this->Tpl->setVariable( 'test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $this->Tpl->setVariable( 'persistence_data', $this->PersistenceList );
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/system_finetune.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'System finetuning' ),
                                        'url' => false ) );
        return $result;
    }

    /*!
    */
    function showMessage()
    {
        return false;
    }

    // Variables for storing results from tests
    var $Result = null;
    var $Results = null;
}

?>
