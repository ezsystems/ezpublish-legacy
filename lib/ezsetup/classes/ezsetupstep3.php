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
require_once( "lib/ezdb/classes/ezdbinterface.php" );


/*!

    Step 3: Try to create the database etc.

*/
function eZSetupStep( &$tpl, &$http )
{
	// Get our configuration
    $testItems = configuration();

	// Set variables to handover to next step
	$handoverResult = array();
    completeItems( $testItems, $http, $handoverResult );

    // Get our variables
	$dbParams = array();
    $dbParams["type"]             = trim( $http->postVariable( "dbType" ) );
    $dbParams["server"]           = trim( $http->postVariable( "dbServer" ) );
    $dbParams["database"]         = trim( $http->postVariable( "dbName" ) );
    $dbParams["main_user"]        = trim( $http->postVariable( "dbMainUser" ) );
    $dbParams["main_pass"]        = $http->postVariable( "dbMainPass" );
    $dbParams["charset"]          = trim( $http->postVariable( "dbCharset" ) );
    $dbParams["builtin_encoding"] = trim( $http->postVariable( "dbEncoding" ) );
    $dbParams["create_user"]      = trim( $http->postVariable( "dbCreateUser" ) );
    $dbParams["create_pass"]      = $http->postVariable( "dbCreatePass" );
    $dbParams["create_pass2"]     = $http->postVariable( "dbCreatePass2" );
	if ( $http->hasVariable( "dbDeleteTables" ) )
		$dbParams["delete_tables"]= $http->postVariable( "dbDeleteTables" );
	else
		$dbParams["delete_tables"]= false;

    // Our switch to see if tests were successful
    $continue = true;

    // Set template variables
    $handoverResult[] = array( "name" => "dbType", "value" => $dbParams["type"] );
    $handoverResult[] = array( "name" => "dbServer", "value" => $dbParams["server"] );
    $handoverResult[] = array( "name" => "dbName", "value" => $dbParams["database"] );
    $handoverResult[] = array( "name" => "dbMainUser", "value" => $dbParams["main_user"] );
    $handoverResult[] = array( "name" => "dbMainPass", "value" => $dbParams["main_pass"] );
    $handoverResult[] = array( "name" => "dbCreateUser", "value" => $dbParams["create_user"] );
    $handoverResult[] = array( "name" => "dbDeleteTables", "value" => $dbParams["delete_tables"] );
    $handoverResult[] = array( "name" => "dbCharset", "value" => $dbParams["charset"] );
    $handoverResult[] = array( "name" => "dbBuiltinEncoding", "value" => $dbParams["builtin_encoding"] );

    // Unfortunately we have to set them again to report it to the user
    $tpl->setVariable( "dbType", $dbParams["type"] );
    $tpl->setVariable( "dbServer", $dbParams["server"] );
    $tpl->setVariable( "dbName", $dbParams["database"] );
    $tpl->setVariable( "dbMainUser", $dbParams["main_user"] );
    $tpl->setVariable( "dbCreateUser", $dbParams["create_user"] );

    // The different sections
	$tpl->setVariable( "connectDb", false );
	$tpl->setVariable( "unpackDemo", false );
    $tpl->setVariable( "createDb", false );
    $tpl->setVariable( "createSql", false );
    $tpl->setVariable( "createUser", false );
    $tpl->setVariable( "deleteTables", false );

	// Go back to step 2, if create_pass != create_pass2
	if ( $dbParams["create_pass"] != $dbParams["create_pass2"] )
    {
		$error["desc"] = "The passwords for the user that you want to create are not the same. ";
		$error["suggest"] = "Please go back and make sure that you type in the same password.";
        $continue = false;
	}
	else
	{
	    $tpl->setVariable( "connectDb", true );
	}

	// Switch if should install demo data or not.
	if ( $http->hasVariable( "unpackDemo" ) && $http->postVariable( "unpackDemo" ) == "true" )
		$unpackDemo = true;
	else
		$unpackDemo = false;

	if ( $continue && $unpackDemo )
	{
		$tpl->setVariable( "unpackDemo", true );

		// unpack the demo data
		$file = eZSys::siteDir() . "var.tgz";

		//require_once( "lib/ezsetup/classes/PEAR.php" );
		require_once( "lib/ezsetup/classes/Tar.php" );
		$tarObject = new Archive_Tar ( $file, true );
		$tarObject->setErrorHandling(PEAR_ERROR_PRINT);

		if ( $tarObject->extract( eZSys::siteDir() ) )
		{
			$tpl->setVariable( "unpackDemo_msg", "successful." );
		}
		else
		{
			$tpl->setVariable( "unpackDemo_msg", "unsuccessful." );
			$error["desc"] = "Couldn't unpack demo data. ";
			$error["suggest"] = "Check the file permissions of the directory \"var\"";
			$continue = false;
		}
	}


	if ( $continue )
	{
		// Include the right database file
		$dbModule = "ez" . $dbParams["type"] . "db";
		$dbModuleFile = "lib/ezdb/classes/" . $dbModule . ".php";
		if ( file_exists( $dbModuleFile ) )
			include_once( $dbModuleFile );

		// Try to get a connection to the database
		eZDebug::writeError( "Please ignore possible error message concerning connection errors of eZDB. We take care of this.", "eZSetup" );

		// Set the right user to use. We first need the main_user.
		$dbParams["user"] = $dbParams["main_user"];
		$dbParams["password"] = $dbParams["main_pass"];

		// Create a database object.
		$dbObject = new $dbModule( $dbParams );

		// TODO: The error number thing is noooo goooood!
		if ( $dbObject->isConnected() == false && $dbObject->errorNumber() != "1049" )
		{
			$tpl->setVariable( "dbConnect", "unsuccessful." );
			$error = errorHandling( $testItems, $dbParams, $dbObject );
			$continue = false;
		}
		else
		{
			$tpl->setVariable( "dbConnect", "successful." );
			$continue = true;
		}
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
	else if ( $continue && $dbObject->isConnected() == true && $dbParams["delete_tables"] == "yes" )
	{
		$tpl->setVariable( "deleteTables", true );
		$continue = false;


		// Get all tables (TODO: Is this just mysql or for all dbs?)
		$sqlQuery = "SHOW TABLES LIKE 'ez%'";
		$dbObject->OutputSQL = false;
		$sqlResult = $dbObject->arrayQuery( $sqlQuery );
		$dbObject->OutputSQL = true;

		// Continue, if we were successful
		$dbError = false;
		if ( $dbObject->errorNumber() == 0 )
		{
			$i = 0;
			$dbObject->OutputSQL = false;
			while( $i < count( $sqlResult ) )
			{
				$sqlQuery = "DROP TABLE " . $sqlResult[$i][0];
				$dbObject->query( $sqlQuery );
				if ( $dbObject->errorNumber() != 0 )
				{
					$dbError = true;
					break;
				}
				$i++;
			}
			$dbObject->OutputSQL = true;
		}
		else
			$dbError = true;

		// See if there was an error
		if ( ! $dbError )
		{
			$tpl->setVariable( "deleteTablesOK", "successful." );
			$continue = true;
		}
		else
		{
			$tpl->setVariable( "deleteTablesOK", "unsuccessful." );
			$error = errorHandling( $testItems, $dbParams, $dbObject );
		}
	}


	//
	// If wanted, create database user for new database
	if ( $continue && $dbParams["create_user"] != "" )
	{
		$tpl->setVariable( "createUser", true );
		$continue = false;

		// From now on we want to use the new user!
		$dbParams["user"] = $dbParams["create_user"];
		$dbParams["password"] = $dbParams["create_pass"];

		// Try to create user TODO: Does this work on other databases? Is this safe enough?
		$sqlQuery = "grant all on ". $dbObject->escapeString( $dbParams["database"] ). ".* to " . $dbObject->escapeString( $dbParams["user"] ). "@localhost identified by '" . $dbObject->escapeString( $dbParams["password"] ). "'";
		$dbObject->OutputSQL = false;
		$dbObject->query( $sqlQuery );
		$dbObject->OutputSQL = true;

		// Reconnect to database with new user
		if ( $dbObject->errorNumber() == 0 )
			$dbObject = new $dbModule( $dbParams );

		if ( $dbObject->errorNumber() == 0 )
		{

			$tpl->setVariable( "dbCreateUserMsg", "successful." );
			$continue = true;
		}
		else
		{
			$tpl->setVariable( "dbCreateUserMsg", "unsuccessful." );
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

		if ( $unpackDemo && isset( $testItems[$dbParams["type"]]["sql_demo"] ) )
			$sqlFile = $testItems[$dbParams["type"]]["sql_demo"];
		else
			$sqlFile = $testItems[$dbParams["type"]]["sql_core"];

		$result = doQuery( $dbObject, $sqlFile );

		if ( $dbObject->errorNumber() == 0 )
	    {
	        $tpl->setVariable( "dbCreateSql", "successful" );
	        $continue = true;
	    }
	    else
	    {
			$tpl->setVariable( "dbCreateSql", "unsuccessful." );
			$error = errorHandling( $testItems, $dbParams, $dbObject );
	    }
    }

	//
	// Insert database content for demo
	//


    if ( $continue )
    {
        $handoverResult[] = array( "name" => "dbUser", "value" => $dbParams["user"] );
        $handoverResult[] = array( "name" => "dbPass", "value" => $dbParams["password"] );
        $tpl->setVariable( "continue", true );
    }
    else
    {
        $tpl->setVariable( "continue", false );
        $tpl->setVariable( "errorDescription", $error["desc"] );
        $tpl->setVariable( "errorSuggestion", $error["suggest"] );
    }


	$tpl->setVariable( "handover", $handoverResult );


    // Display template
    $tpl->display( "design/standard/templates/setup/step3.tpl" );
}




/***************************************************************/
/****** Helping functions that are only used by this file ******/
/***************************************************************/


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



/*!
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

function doQuery( &$dbObject, $sqlFile )
{
	$sqlArray = prepareSqlQuery( $sqlFile );

	// Turn unneccessary SQL debug output off
	$dbObject->OutputSQL = false;
	if ( $sqlArray && is_array( $sqlArray ) )
	{
		foreach( $sqlArray as $singleQuery )
		{
			if ( trim( $singleQuery ) != "" )
			{
				$dbObject->query( $singleQuery );
				if ( $dbObject->errorNumber() != 0 )
					return false;
			}
		}
		return true;
	}
}

?>
