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


// We will do database tests, so get the needed files
// The file for the database type will be included later
include_once( "lib/ezdb/classes/ezdbinterface.php" );


/*!

    Step 3: Try to create the database etc.

*/
function stepThree( &$tpl, &$http )
{
    $testItems = configuration();

    // Get our variables
	$dbParams = array();
    $dbParams["type"]     = trim( $http->postVariable( "dbType" ) );
    $dbParams["server"]   = trim( $http->postVariable( "dbServer" ) );
    $dbParams["database"] = trim( $http->postVariable( "dbName" ) );
    $dbParams["user"]     = trim( $http->postVariable( "dbMainUser" ) );
    $dbParams["password"] = $http->postVariable( "dbMainPass" );
    $dbParams["charset"]  = $http->postVariable( "dbCharset" );
    $dbParams["builtin_encoding"]  = $http->postVariable( "dbEncoding" );
    /* $dbCreateUser  = $http->postVariable( "dbCreateUser" );
    $dbCreatePass  = $http->postVariable( "dbCreatePass" );
    $dbCreatePass2 = $http->postVariable( "dbCreatePass2" ); */
    

	// Complete testItems with the test results
	foreach( array_keys( $testItems ) as $key )
	{
		if ( $http->hasVariable( $key ) )
		{
			switch( $http->postVariable( $key ) )
			{
				case "true":
				{
					$testItems[$key]["pass"] = true;
				}break;

				case "false":
				{
					$testItems[$key]["pass"] = false;
				}break;
			}
		}
	}

	
    // Set template variables
    $tpl->setVariable( "dbType", $dbParams["type"] );
    $tpl->setVariable( "dbServer", $dbParams["server"] );
    $tpl->setVariable( "dbName", $dbParams["database"] );
    $tpl->setVariable( "dbMainUser", $dbParams["user"] );
    $tpl->setVariable( "charset", $dbParams["charset"] );
    $tpl->setVariable( "builtin_encoding", $dbParams["builtin_encoding"] );
    $tpl->setVariable( "dbMainPass", $dbParams["password"] );
    /*$tpl->setVariable( "dbCreateUser", $dbCreateUser );
    $tpl->setVariable( "dbCreatePass", $dbCreatePass );*/
    

    $tpl->setVariable( "createDb", false );
    $tpl->setVariable( "createSql", false );

    // Only continue, if we are successful
    $continue = false;
	
    // Include the right database file
	$dbModule = "ez" . $dbParams["type"] . "db";
	$dbModuleFile = "lib/ezdb/classes/" . $dbModule . ".php";
	if ( file_exists( $dbModuleFile ) )
		include_once( $dbModuleFile );
	
	
	// Try to get a connection to the database
	eZDebug::writeError( "Please ignore possible error message concerning connection errors of eZDB. We take care of this.", "eZSetup" ); 
	$dbObject = new $dbModule( $dbParams );
	
	if ( $dbObject->isConnected() == false && $dbObject->errorNumber() != "1049" )
	{
   		$tpl->setVariable( "dbConnect", "unsuccessful." );
		$error = errorHandling( $testItems, $dbParams, $dbObject );
	}
	else
	{
   		$tpl->setVariable( "dbConnect", "successful." );
		$continue = true;
	}	
	 
	//
	// If database doesn't exist yet, try to create the database
    if ( $continue && $dbObject->isConnected() == false )
    {
    	$tpl->setVariable( "createDb", true );
        $continue = false;

        // Try to create the database    
		$dbObject->createDatabase( $dbParams["database"] );
		if ( $dbObject->errorNumber() == "0" )
		{
			$tpl->setVariable( "dbCreate", "successful." );
            $continue = true;            
			// We have to reconnect otherwise query() will fail without error message!
			unset( $dbObject );
			$dbObject = new $dbModule( $dbParams );
		}
		else
		{
			$tpl->setVariable( "dbCreate", "unsuccessful." );
			$error = errorHandling( $testItems, $dbParams, $dbObject );
		}
    }
  
    
    //
    // Create database structures
    //
    if ( $continue )
    {
        $continue = false;
        $tpl->setVariable( "createSql", true );
	    $sqlFile = "kernel/sql/" . $dbParams["type"] . "/kernel_clean.sql";
		$sqlArray = prepareSqlQuery( $sqlFile );

		if ( $sqlArray && is_array( $sqlArray ) )
		{
	    	foreach( $sqlArray as $singleQuery )
			{
				if ( trim( $singleQuery ) != "" )
				{
					$dbObject->query( $singleQuery );
					if ( $dbObject->errorNumber() != 0 )
						break;
				} 
			}
		}
		
		if ( $sqlArray && is_array( $sqlArray ) && $dbObject->errorNumber() == 0 )
	    {
	        $tpl->setVariable( "dbCreateSql", "successful" );
	        $continue = true;
	    }
	    else
	    {
	        if ( $sqlArray && is_array( $sqlArray ) )
			{
				$tpl->setVariable( "dbCreateSql", "unsuccessful." );
				$error = errorHandling( $testItems, $dbParams, $dbObject );
			}
			else
			{
				$tpl->setVariable( "dbCreateSql", "unsuccessful." );
				$error["message"] = "Preparing the SQL statements was unsuccessful!";
				$error["suggestion"] = "Check that the file \"$sqlFile\" exists and can be read!";
			}
	    }  	
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
    
	// Set variables to handover to next step
	$handoverResult = array();
	foreach( array_keys( $testItems ) as $key )
	{
		$handoverResult[] = array( "name" => $key, "pass" => $testItems[$key]["pass"] ? "true" : "false" );
	}
	$tpl->setVariable( "handover", $handoverResult );


    // Display template
    $tpl->display( "design/standard/templates/setup/step3.tpl" );    
}




/*!
	Try to get some error explanations from config or show database error
*/
function errorHandling( $testItems, $dbParams, $dbObject )
{
	$error = array();
	if ( isset( $testItems[$dbParams["type"]]["error_msg_" . $dbObject->errorNumber()] ) )
	{
		$error["desc"] = $testItems[$dbParams["type"]]["error_msg_" . $dbObject->errorNumber()];
		$error["suggest"] = $testItems[$dbParams["type"]]["error_sol_" . $dbObject->errorNumber()];
	}
	else
	{
		$error["desc"] = "Unknown database error";
		$error["suggest"] = "Error message of " .  $dbParams["type"] .": " . $dbObject->errorMessage() . " (" . $dbObject->errorNumber() . ")";
	}
	return $error;
}



/*

	Prepare the sql file so we can create the database.
 
*/
function prepareSqlQuery( $sqlFile )
{
    $sqlQuery = fread( fopen( $sqlFile, 'r' ), filesize( $sqlFile ));
    if ( $sqlQuery )
    {
	    // Fix SQL file by deleting all comments and newlines
	    $sqlQuery = preg_replace( array( "/#.*" . "/", "/\n/", "/--.*" . "/" ), array( "", "", "" ), $sqlQuery );
	
	    // Split the query into an array (mysql_query doesn't like ";")
	    $sqlQueryArray = preg_split( "/;/", $sqlQuery );
		
		return $sqlQueryArray;
	}
	else
	{
		return false;
	}
}

?>
