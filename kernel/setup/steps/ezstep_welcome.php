<?php
//
// Definition of EZStepWelcome class
//
// Created on: <08-Aug-2003 15:00:02 kk>
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

/*! \file ezstep_welcome.php
*/

include_once( "kernel/setup/ezsetuptests.php" );
include_once( 'kernel/setup/steps/ezstep_system_check.php' );
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );

/*!
  \class EZStepWelcome ezstep_welcome.php
  \brief The class EZStepWelcome does

*/
class eZStepWelcome extends eZStepInstaller
{

    /*!
     Constructor
     \reimp
    */
    function EZStepWelcome( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        return true;
    }

    /*!
     \reimp
     */
    function init()
    {
        return false; // Always show welcome message
    }

    /*!
     \reimp
    */
    function &display()
    {
        /* check and test system  settings */
        $systemCheck = new eZStepSystemCheck( $this->Tpl, $this->Http, $this->Ini, $this->PersistenceList );
        $systemCheckResult = $systemCheck->init();
        $this->Tpl->setVariable( 'system_check_result', $systemCheckResult );

        if( $systemCheckResult === true ) // system checks passed
        {
            $this->Tpl->setVariable( 'setup_next_step', 'DatabaseChoice' );
        }
        else
        {
            $this->Tpl->setVariable( 'setup_next_step', 'SystemCheck' );
        }

        $criticalTests = eZSetupCriticalTests();
//        $testTable = eZSetupTestTable();

        $arguments = array();
        $runResult = eZSetupRunTests( $criticalTests, $arguments, 'eZSetup:init:system_check' );
        $testResult = $runResult['result'];

        $this->Tpl->setVariable( 'system_test_result', $testResult );


        $result = array();
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/welcome.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Welcome to eZ publish' ),
                                    'url' => false ) );

        return $result;
    }

    /*!
     \reimp
    */
    function showMessage()
    {
        return true;
    }

}

?>
