<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
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

// All test functions should be defined in ezsetuptests
include( "kernel/setup/ezsetuptests.php" );



/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep_welcome( &$tpl, &$http, &$ini, &$persistenceList )
{
    $template = 'design:setup/init/welcome.tpl';

    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    $http =& eZHTTPTool::instance();
    if ( $http->hasPostVariable( 'DisableSetup' ) )
    {
        $template = 'design:setup/init/disable.tpl';

        $criticalTests = array( 'settings_permission' );
        $testTable = eZSetupTestTable();

        $arguments = array();
        $runResult = eZSetupRunTests( $criticalTests, $arguments, 'eZSetup:init:system_check' );
        $testResults = $runResult['results'];
        $testResult = $runResult['result'];
        $successCount = $runResult['success_count'];
        $persistenceData = $runResult['persistence_list'];

        eZSetupMergePersistenceList( $persistenceList, $persistenceData );

        $tpl->setVariable( 'test', array( 'result' => $testResult,
                                          'results' => $testResults ) );
        $tpl->setVariable( 'persistence_data', $persistenceList );

        $saveResult = true;
        if ( $testResult == 1 )
        {
            $ini =& eZINI::instance();
            $ini->setVariable( 'SiteAccessSettings', 'CheckValidity', 'false' );
            $saveResult = $ini->save( false, '.php', false );
        }
        $tpl->setVariable( 'save_result', $saveResult );
    }

    $criticalTests = eZSetupCriticalTests();
    $testTable = eZSetupTestTable();

    $arguments = array();
    $runResult = eZSetupRunTests( $criticalTests, $arguments, 'eZSetup:init:system_check' );
    $testResult = $runResult['result'];

    $tpl->setVariable( 'system_test_result', $testResult );

    $result = array();
    // Display template
    $result['content'] = $tpl->fetch( $template );
    $result['path'] = array( array( 'text' => 'Welcome to ezpublish',
                                    'url' => false ) );

    return $result;
}


?>
