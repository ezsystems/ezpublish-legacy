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


/*!

    Step 2: Do some tests on the database and ask for db information

*/
function stepTwo( &$tpl, &$http )
{
    $testItems = configuration();

    // Get our variables
    if ( $http->hasVariable( "dbServer" ) )
        $dbServer      = $http->postVariable( "dbServer" );
    else
        $dbServer      = "localhost";
    if ( $http->hasVariable( "dbName" ) )
        $dbName        = $http->postVariable( "dbName" );
    else
        $dbName        = "ezpublish3";
    if ( $http->hasVariable( "dbMainUser" ) )
        $dbMainUser    = $http->postVariable( "dbMainUser" );
    else
        $dbMainUser    = "root";
    /* if ( $http->hasVariable( "dbCreateUser" ) )
        $dbCreateUser  = $http->postVariable( "dbCreateUser" );
    else
        $dbCreateUser  = ""; */

    
    // Assign some defaults
    $tpl->setVariable( "dbName", $dbName );
    $tpl->setVariable( "dbServer", $dbServer );
    $tpl->setVariable( "dbMainUser", $dbMainUser );        
    // $tpl->setVariable( "dbCreateUser", $dbCreateUser );
    

      // Databases
      $databasesArray = array();
      foreach( $testItems["database"] as $item )
      {
        // Only list the ones that are actually available on the server
           if ( $http->postVariable( $item["modulename"] ) == "true" )
           {
               $databasesArray[] = array( "name" => $item["modulename"], "desc" => $item["selectname"] );
            switch ( $item["modulename"] )
            {
                case "mysql":
                {
                    $databaseTest = @mysql_connect( "localhost" );
                    $tpl->setVariable( "dbServerExpl", "no server on \"localhost\" detected" );
                    $tpl->setVariable( "dbServer", "" );
                    switch( mysql_errno() )
                    {
                        case 1045: // Server runs
                        case 0:    // Successful login (without username...tststs)
                        {
                            $tpl->setVariable( "dbServer", "localhost" );
                            $tpl->setVariable( "dbServerExpl", "server detected" );
                        }break;
                    }
                }break;
                default:
                {
                    $tpl->setVariable( "dbserver", "localhost" );
                    $tpl->setVariable( "dbServerExpl", "no server detected" );
                }break;
            }
 
           }
      }
      $tpl->setVariable( "databasesArray", $databasesArray );
    
    if ( $http->hasVariable( "dbServer" ) )
        $tpl->setVariable( "dbServer", $http->postVariable( "dbServer" ) );
    if ( $http->hasVariable( "dbName" ) )
        $tpl->setVariable( "dbName", $http->postVariable( "dbName" ) );
    if ( $http->hasVariable( "dbMainUser" ) )
        $tpl->setVariable( "dbMainUser", $http->postVariable( "dbMainUser" ) );            
    if ( $http->hasVariable( "dbCreateUser" ) )
        $tpl->setVariable( "dbCreateUser", $http->postVariable( "dbCreateUser" ) );

    // Set available db types in case we have to go back to step two
    $availableDatabasesArray2 = array();
      foreach( $testItems["database"] as $item )
      {
        $availableDatabasesArray2[] = array( "name" => $item["modulename"], 
                                            "pass" => $http->postVariable( $item["modulename"] ) );
    }
    $tpl->setVariable( "databasesArray2", $availableDatabasesArray2 );
        

    $tpl->display( "design/standard/templates/setup/step2.tpl" );        
}

?>