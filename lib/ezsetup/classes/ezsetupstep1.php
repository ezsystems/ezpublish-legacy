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

    Step 1: General tests and information for the databases

*/
function stepOne( &$tpl, &$http )
{
    $testItems = configuration();

	// Some variables which we need for the testing of the databases
	$dbAvailable = false;
	$databaseArray = array();
    

    //
    // Start testing
    //
    $resultArray = array();
    
	// Test all items in general.ini
	foreach( array_keys( $testItems ) as $key )
	{
		$resultArray[$key] = $testItems[$key]["function"]( $testItems[$key] );
		if ( isset( $testItems[$key]["type"] ) && $testItems[$key]["type"] == "db" )
		{
			// Tricky reference stuff
			$databaseArray[$key] =& $testItems[$key];
			if ( $resultArray[$key]["pass"] == true )
				$dbAvailable = true;
		}
	}
	
	// Now set the requirement of failed databases to false, because we need only one!
	if ( $dbAvailable )
	{
		foreach( array_keys( $databaseArray ) as $key)
		{
			if ( $resultArray[$key]["pass"] == false )
				$databaseArray[$key]["req"] = false;

		}
	}
	

	//
	// Loop over items for output
	$outputArray = array();
	foreach( array_keys( $testItems ) as $key )
	{
		$result   = $resultArray[$key];
		$testItem = $testItems[$key];

		// Convert strings "true" and "false" to proper true and false
		if ( is_string( $testItem["req"] ) )
		{
			switch( $testItem["req"] )
			{
				case "true":
				case "yes":
				{
					$testItem["req"] = true;
				}break;

				case "false":
				case "no":
				{
					$testItem["req"] = false;
				}break;

			}
		}

		// Title for test item
		$desc = $testItem["desc"];
		
		// Format string for "requirement" nicer
		if ( isset( $testItem["req_desc"] ) && is_string( $testItem["req_desc"] ) )
			$req = $testItem["req_desc"];
		else if ( $testItem["req"] )
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
			}
			else
			{
				$pass = "fail";
				$class = "ezsetup_fatal";
			}
		}

		// Put in array for template
		$outputArray[] = array( "desc"   => $desc,
								"req"    => $req,
								"status" => $status,
								"pass"   => $pass,
								"class"  => $class );
	}
	$tpl->setVariable( "itemsResult", $outputArray );



    //
    // Now see if we have a problem
    //
    $continue = true;
    

	// Error reporting
	foreach( array_keys( $testItems ) as $key)
	{
		if ( $resultArray[$key]["pass"] == false )
		{
			// Don't show error message for failed db module if we have a working db module
			if ( isset( $testItems[$key]["type"] ) && $testItems[$key]["type"] == "db" && $dbAvailable )
			{
				continue;
			}
			else
			{
				$tpl->setVariable( "errorDescription", $testItems[$key]["error_msg"] );
				$tpl->setVariable( "errorSuggestion", $testItems[$key]["error_sol"] );
				$continue = false;
				break;
			}
		}
	}


	// Set variables to handover to next step
	$handoverResult = array();
	foreach( array_keys( $testItems ) as $key )
	{
		$handoverResult[] = array( "name" => $key, "pass" => $resultArray[$key]["pass"] ? "true" : "false" );
	}
	$tpl->setVariable( "handover", $handoverResult );


    //
    // Set handover variables and continue
    //
    $tpl->setVariable( "continue", $continue );
    
    // Display template
    $tpl->display( "design/standard/templates/setup/step1.tpl" );
}




/*!
    
    Test if PHP version is equal or greater than required version
     
*/
function testPhpVersion( $argArray )
{ 
	$minVersion = $argArray["min_version"];

    /*
    // Get the operating systems name
    $operatingSystem = split( " ", php_uname() );
    $operatingSystem = strtolower( $operatingSystem[0] );
    
	// Find out if there is an os specific version needed
    if ( isset( $argArray["req"][$operatingSystem] ) )
        $neededVersion = $argArray["req"][$operatingSystem];
    else if ( isset( $argArray["req"] ) )
        $neededVersion = $argArray["req"];
    else
        $neededVersion = $argArray["req"]; 
	*/

	$neededVersion = $minVersion;

    // compare the versions
    $currentVersion = phpversion();
    $compCurrentVersion = str_replace( ".", "", $currentVersion );
    $compNeededVersion = str_replace( ".", "", $neededVersion );
    if ( $compCurrentVersion >= $compNeededVersion )
        $pass = true;
    else
        $pass = false;
    
    return array( "status" => $currentVersion, "pass"   => $pass );     
}


/*!
    Test if a module is loaded
*/
function testModule( $argArray )
{
    if ( (bool) extension_loaded( $argArray["modulename"] ) )
        $pass = true;
    else
        $pass = false;

    return array( "status" => $pass, "pass"   => $pass );         
}




/*!
	Test file permissions
*/
function testPermissions( $argArray )
{
	// Make sure, we are working in the right directory.
	$file = eZSys::siteDir() . $argArray["file"];

	if ( ! file_exists( $file ) )
		return "noexist";

	
	// Directories: Test, if we can create a file
	// Files: Test, if we can open a file in writing mode
	$pass = true;
	if ( is_dir( $file ) )
	{
		// TODO: Better temporary file name!
		$tmpfname = $file . "/ezsetup.tmp";
		$fp = fopen( $tmpfname, "w" );
		if ( ! $fp )
			$pass = false;
			
		if ( $pass )
			$test = fwrite( $fp, "this file can be deleted. It gets created by the eZ setup module of eZ publish." );
		if ( $pass && ! $test )
			$pass = false;

		if ( $pass )
			$test = fclose( $fp );
		
		if ( $pass )
			$test = unlink( $tmpfname );
		if ( $pass && ! $test )
			$pass = false;
	}
	else if ( is_file( $file ) )
	{
		$test = touch( $file );
		if ( ! $test )
			$pass = false;
	}

    return array( "status" => $pass, "pass"   => $pass );         
}



function testProgram( $parameters )
{
	$program = $parameters["program"];
	$searchPaths = $parameters["search_paths"];

	// In case we got it from ini file
	if ( !is_array( $searchPaths ) )
		$searchPaths = preg_split( "/;/", $searchPaths );

	$pass = false;
	$status = "not found";
	foreach( $searchPaths as $path )
	{
		$pathProgram = $path . "/" . $program;
		if ( file_exists( $pathProgram ) ) 
		{
			if ( function_exists( "is_executable" ) )
			{
				if ( is_executable( $pathProgram ) )
				{
					$pass = true;
					$status = "found";
					break;
				}
			}
			else
			{
				// Windows system
				$status = "found";
				$pass = true;
				break;
			}
		}
	}

	return array( "status" => $status, "pass" => $pass );
}
?>
