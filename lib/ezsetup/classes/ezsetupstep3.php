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

    Step 3: Try to create the database etc.

*/
function stepThree( &$tpl, &$http )
{
    $testItems = configuration();

    // Get our variables
	$dbParams = array();
    $dbParams["type"]     = $http->postVariable( "dbType" );
    $dbParams["server"]   = $http->postVariable( "dbServer" );
    $dbParams["name"]     = $http->postVariable( "dbName" );
    $dbParams["mainuser"] = $http->postVariable( "dbMainUser" );
    $dbParams["mainpass"] = $http->postVariable( "dbMainPass" );
    /* $dbCreateUser  = $http->postVariable( "dbCreateUser" );
    $dbCreatePass  = $http->postVariable( "dbCreatePass" );
    $dbCreatePass2 = $http->postVariable( "dbCreatePass2" ); */
    
    // Set template variables
    $tpl->setVariable( "dbType", $dbParams["type"] );
    $tpl->setVariable( "dbServer", $dbParams["server"] );
    $tpl->setVariable( "dbName", $dbParams["name"] );
    $tpl->setVariable( "dbMainUser", $dbParams["mainuser"] );
    $tpl->setVariable( "dbMainPass", $dbParams["mainpass"] );
    /*$tpl->setVariable( "dbCreateUser", $dbCreateUser );
    $tpl->setVariable( "dbCreatePass", $dbCreatePass );*/
    
    // Set available db types in case we have to go back to step two
    $availableDatabasesArray = array();
    foreach( $testItems["database"] as $item )
    {
		$availableDatabasesArray[] = array( "name" => $item["modulename"], 
                                            "pass" => $http->postVariable( $item["modulename"] ) );
    }
    $tpl->setVariable( "databasesArray", $availableDatabasesArray );
    

    $tpl->setVariable( "createDb", false );
    $tpl->setVariable( "createSql", false );

    // Only continue, if we are successful
    $continue = false;
	$error = array();
	
    // Try to connect to the database
    switch ( $dbParams["type"] )
    {
        case "mysql":
        {
			$dbConnection = testMysqlConnection( $dbParams, $tpl, $continue, $error );
        }break;
        default:
        {
            $tpl->setVariable( "dbConnect", "don't know server type, sorry." );
        }
    }

    if ( $continue )
    {
        $continue = false;
        $tpl->setVariable( "createDb", true );

        // Try to create the database    
		createMysqlDb( $dbParams, $dbConnection, $tpl, $continue, $error );
    }
    
    //
    // Create database structures
    //
    if ( $continue )
    {
        $continue = false;
        $tpl->setVariable( "createSql", true );
		createMysqlStructures( $dbParams, $dbConnection, $tpl, $continue, $error );
    }

    if ( $continue )
    {
        $tpl->setVariable( "continue", true );
    }
    else
    {
        $tpl->setVariable( "continue", false );
        $tpl->setVariable( "errorDescription", $error["desc"] );
        $tpl->setVariable( "errorSuggestion", $error["suggest"] );
    }
    
    // Display template
    $tpl->display( "design/standard/templates/setup/step3.tpl" );    
}




/*!

	Tests for a connection to a mySQL database

*/
function testMysqlConnection( $dbParams, &$tpl, &$continue, &$error )
{
    $dbConnection = @mysql_connect( $dbParams["server"], $dbParams["mainuser"], $dbParams["mainpass"] );
    switch( mysql_errno() )
    {
	    case 0:    // Successful login
	    {
	        $tpl->setVariable( "dbConnect", "successful" );
			$continue = true;
			return $dbConnection;                    
	    }break;
	    case 1045:
	    {
	        $tpl->setVariable( "dbConnect", "unsuccessful." );
	        $error["desc"] = "Wrong username and/ or password!";
	        $error["suggest"] = "Please go back and reenter the username and password.";
			return $dbConnection;
	    }break;
	    case 2005:
	    {
	        $tpl->setVariable( "dbConnect", "unsuccessful." );
	        $error["desc"] = "\"" . $dbParams["server"] . "\" is no MySQL server!";
	        $error["suggest"] = "Please go back and enter the correct database hostname.";
			return $dbConnection;
	    }break;
	    
	    default:
	    {
	        $tpl->setVariable( "dbConnect", "unsuccessful." );
	        $error["desc"] = "Unknown error while connecting to database!";
	        $error["suggest"] = "The mySQL error is: " . mysql_error() . "(" . mysql_errno() . ")";
			return $dbConnection;
	    }break;
	}
}


/*!

	Create the database (mysql) 

*/
function createMysqlDb( $dbParams, &$dbConnection, &$tpl, &$continue, &$error )
{
    mysql_create_db( $dbParams["name"], $dbConnection );
    switch ( mysql_errno() )
    {
        case 0:
        {
            $tpl->setVariable( "dbCreate", "successful" );
            $continue = true;            
        }break;
        case 1007:
        {
            $tpl->setVariable( "dbCreate", "unsuccessful." );
            $error["desc"] = "Database \"" . $dbParams["name"] . "\" exists.";
            $error["suggest"] = "Please go back and choose a different name.";        
        }break;
        
        default:
        {
            $tpl->setVariable( "dbCreate", "unsuccessful." );
            $error["desc"] = "Unknown Error! MySQL error: " . mysql_error() . " (" . mysql_errno() . ")";
            $error["suggest"] = "Please try to fix the error and write to eZ systems about it.";
        }break;
    }

}


/*!

	Install the SQL structures

*/
function createMysqlStructures( $dbParams, &$dbConnection, &$tpl, &$continue, &$error )
{ 
    // Read in SQL file
    $sqlFile = "kernel/sql/mysql/kernel.sql";
    $sqlQuery = @fread( @fopen( $sqlFile, 'r' ), filesize( $sqlFile ));
    if ( $sqlQuery == false )
    {
        $error["desc"] = "Couldn't open file \"$sqlFile\"!";
        $error["suggest"] = "Please make sure the file is on the server and is readable.";
    }
    
    // Fix SQL file by deleting all comments and newlines
    $sqlQuery = preg_replace( array( "/#.*" . "/", "/\n/", "/--.*" . "/" ), array( "", "", "" ), $sqlQuery );

    // Split the query into an array (mysql_query doesn't like ";")
    $sqlQueryArray = preg_split( "/;/", $sqlQuery );
    
    // Execute the SQL queries
    mysql_select_db( $dbParams["name"] );
    if ( mysql_errno() != 0 )
    {
        $error["desc"] = "Couldn't select database \"" . $dbParams["name"] . "\".";
        $error["suggest"] = "This error shouldn't show as the database was just created, but cannot be 
                            selected now. Please check the permissions of the database user.";
    }
        
    foreach( $sqlQueryArray as $singleQuery )
    {
        if ( trim( $singleQuery ) != "" )
            mysql_query( $singleQuery );
        if ( mysql_errno() != 0 )
            break;
    }
    
    if ( mysql_errno() == 0 )
    {
        $tpl->setVariable( "dbCreateSql", "successful" );
        $continue = true;
    }
    else
    {
        $tpl->setVariable( "dbCreateSql", "unsuccessful." );
        $error["desc"] = "Couldn't create SQL structures.";
        $error["suggest"] = "Please report error to eZ systems or try to fix the SQL in the 
                            file \"$sqlFile\" yourself.<br />
                            mySQL error: " . mysql_error() . " (" . mysql_errno() . ")";
    }  
}
?>