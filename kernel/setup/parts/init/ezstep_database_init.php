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

define( 'EZ_SETUP_DB_ERROR_EMPTY_PASSWORD', 1 );
define( 'EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD', 2 );

/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep( &$tpl, &$http, &$ini, &$persistenceList )
{
    $databaseMap = eZSetupDatabaseMap();
    $databaseInfo = $persistenceList['database_info'];
    $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
    $regionalInfo = $persistenceList['regional_info'];

    $tpl->setVariable( 'database_info', $databaseInfo );
    $tpl->setVariable( 'regional_info', $regionalInfo );

    $error = 0;

    if ( $http->hasPostVariable( 'eZSetupDatabasePassword' ) )
    {
        $password = $http->postVariable( 'eZSetupDatabasePassword' );
        $passwordConfirm = $http->postVariable( 'eZSetupDatabasePasswordConfirm' );
        if ( !$password )
        {
            $error = EZ_SETUP_DB_ERROR_EMPTY_PASSWORD;
        }
        else if ( $password != $passwordConfirm )
        {
            $error = EZ_SETUP_DB_ERROR_NONMATCH_PASSWORD;
        }
        else
        {
        }
    }

    $tpl->setVariable( 'error', $error );

    $result = array();
    // Display template
    $result['content'] = $tpl->fetch( "design:setup/init/database_init.tpl" );
    $result['path'] = array( array( 'text' => 'Database initalization',
                                    'url' => false ) );
    return $result;
}


?>