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
include( "lib/ezsetup/classes/ezsetuptests.php" );



/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep( &$tpl, &$http, &$ini )
{
	// Get our configuration
	$testItems = configuration();

	// Some variables which we need for the testing of the databases
	$dbAvailable = false;
	$databaseArray = array();
	$handoverResult = array();
   
    // Start testing
    $resultArray = array();
    
	// Loop through all test items doing the tests
	$continue = true;
	foreach( array_keys( $testItems ) as $key )
	{
		// Execute the function that was defined in the configuration file
		$resultArray[$key] = $testItems[$key]["function"]( $testItems[$key] );
		
		// Fill array with databases, because we have to test if one is available
		if ( isset( $testItems[$key]["type"] ) && $testItems[$key]["type"] == "db" )
		{
			$databaseArray[$key] =& $testItems[$key];
			if ( $resultArray[$key]["pass"] == true )
				$dbAvailable = true;
		}
		
		// Set the error messages
		if ( $resultArray[$key]["pass"] == false && $testItems[$key]["req"] == true )
		{
			// Don't show error message for failed db module if we have a working db module
			if ( !isset( $testItems[$key]["type"] ) || $testItems[$key]["type"] != "db" || ! $dbAvailable )
			{
				// Set the error messages on the first error not the last
				if ( $continue )
				{
					$tpl->setVariable( "errorDescription", $testItems[$key]["error_msg"] );
					$tpl->setVariable( "errorSuggestion", $testItems[$key]["error_sol"] );
					$continue = false;
				}
			}
		}

		

		// Array for hidden form fields
		$handoverResult[] = array( "name" => $key, "value" => $resultArray[$key]["pass"] ? "true" : "false" );
	}
	$tpl->setVariable( "handover", $handoverResult );


	// Now set the requirement of failed databases to false, because we need only one!
	if ( $dbAvailable )
	{
		foreach( array_keys( $databaseArray ) as $key)
		{
			if ( $resultArray[$key]["pass"] == false )
				$databaseArray[$key]["req"] = false;
		}
	}
	

	// Loop over items for output
	// Notice: Separate loop to keep first loop simpler. 
	//         Maybe this should be put in first loop too.
	$outputArray = array();
	foreach( array_keys( $testItems ) as $key )
	{
		$result   =& $resultArray[$key];
		$testItem =& $testItems[$key];

		// Title for test item
		$desc = $testItem["desc"];
		
		// Format string for "requirement" nicer
		if ( isset( $testItem["req_desc"] ) && is_string( $testItem["req_desc"] ) )
			$req = $testItem["req_desc"];
		else if ( $testItem["req"] == true )
			$req = "yes";
		else
			$req = "no";

		// Format string for "status" nicer
		if ( is_string( $result["status"] ) )
			$status = $result["status"];
		else if ( $result["status"] )
			$status = "ok";
		else
			$status = "--";
		
		$warning = "";
		// Check if it was a pass
		if ( $result["pass"] == true )
		{
			$pass = "pass";
			$class = "ezsetup_ok";
		}
		else
		{
			if ( $testItem["req"] == false )
			{
				$pass = "uncritical";
				$class = "ezsetup_warning";
				if ( isset( $testItems[$key]["warning"] ) )
					$warning = $testItems[$key]["warning"];
			}
			else
			{
				$pass = "fail";
				$class = "ezsetup_fatal";
			}
		}

		// Create array for template
		$outputArray[] = array( "desc"   => $desc,
								"req"    => $req,
								"status" => $status,
								"pass"   => $pass,
								"class"  => $class,
								"warning"=> $warning );
	}
	$tpl->setVariable( "itemsResult", $outputArray );

    // Set continue
    $tpl->setVariable( "continue", $continue );
    
    // Display template
    $tpl->display( "design/standard/templates/setup/step1.tpl" );
}


?>
