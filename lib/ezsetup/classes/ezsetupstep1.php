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
    
    //
    // Start testing
    //
    $resultArray = array();
    
	// Test PHP version
    $resultArray["general"]["phpversion"] = testPhpVersion( $testItems["general"]["phpversion"]["minversion"] ); 
    
	
    //
	// Test database modules
    $dbAvailable = false;
	foreach( array_keys( $testItems["database"] ) as $key )
	{
		$resultArray["database"][$key] = testModule( $testItems["database"][$key] );        
		if ( $resultArray["database"][$key]["pass"] == true )
			$dbAvailable = true;
	}
	// Another loop to set requirements properly if we have one available database type
	if ( $dbAvailable )
	{
		foreach( array_keys( $testItems["database"] ) as $key )
		{
			if ( $resultArray["database"][$key]["pass"] == false )
				$testItems["database"][$key]["req"] = false;
		}
	}
	

	//
    // Test for more modules
    foreach( array_keys( $testItems["modules"] ) as $key )
        $resultArray["modules"][$key] = testModule( $testItems["modules"][$key] );


	//
    // Test for file/directory permissions
	foreach( array_keys( $testItems["files"] ) as $key)
    	$resultArray["files"][$key] = testPermissions( $testItems["files"][$key]["file"] );



	//
	// Loop over main sections for output
	$outputArray = array();
	foreach( array_keys( $testItems ) as $mainKey )
	{
		// Loop over items
		foreach( array_keys( $testItems[$mainKey] ) as $key )
		{
			$result   = $resultArray[$mainKey][$key];
			$testItem = $testItems[$mainKey][$key];

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
	}
	$tpl->setVariable( "itemsResult", $outputArray );



    //
    // Now see if we have a problem
    //
    $continue = true;
    
	//
    // Error reporting for php version
    if ( $resultArray["general"]["phpversion"]["pass"] == false )
    {
        $tpl->setVariable( "errorDescription", $testItems["general"]["phpversion"]["error"]["message"] );
        $tpl->setVariable( "errorSuggestion", $testItems["general"]["phpversion"]["error"]["suggestion"] );
        $continue = false;    
    }
    

	//
    // Error reporting for database modules
    if ( $continue == true )
    {
        foreach( array_keys( $testItems["database"] ) as $key )
        {
			$item = $testItems["database"][$key];
			$result = $resultArray["database"][$key];
            switch( $result["pass"] )
            {
                case true:
                {
                    $availableDatabasesArray[] = array( "name" => $item["modulename"], "pass" => "true" );
                }break;
                case false:
                {
                    $availableDatabasesArray[] = array( "name" => $item["modulename"], "pass" => "false" ); 
                }break;                
            }
        }
        
        if ( ! $dbAvailable )
        {
            $continue = false;
            $tpl->setVariable( "errorDescription", "No usable database module found." );
            $tpl->setVariable( "errorSuggestion", "eZ publish needs at least one database that it supports. 
                                Please install the PHP module of one of the above listed databases.
                                You obviously also need a running database for the module." );
        }
        else
        {
            $tpl->setVariable( "databasesArray", $availableDatabasesArray );
        }
    }

    // Only continue with error testing, if the above test was successful
    if ( $continue == true )
    {
        // Error reporting for the rest of the modules
        foreach( array_keys( $testItems["modules"] ) as $key )
        {
			$item = $testItems["modules"][$key];
			$result = $testItems["modules"][$key];
            if ( $item["req"] == true && $result["pass"] == false )
            {
                $continue = false;
                $tpl->setVariable( "errorDescription", $item["error"]["general"]["message"] );
                $tpl->setVariable( "errorSuggestion", $item["error"]["general"]["suggestion"] );  
				break;
            }
        }
    }


	//
	// Error reporting on the file permissions
	if  ( $continue )
	{
		foreach( array_keys( $testItems["files"] ) as $key )
		{
			$item = $testItems["files"][$key];
			$result = $resultArray["files"][$key];
			if ( $item["req"] == true && $result["pass"] == false )
			{
				$continue = false;
                $tpl->setVariable( "errorDescription", $item["error"]["message"] );
                $tpl->setVariable( "errorSuggestion", $item["error"]["suggestion"] );  
				break;
			}
		}
	}

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
function testPhpVersion( $minVersion )
{ 
    /*
    // Get the operating systems name
    $operatingSystem = split( " ", php_uname() );
    $operatingSystem = strtolower( $operatingSystem[0] );
    
	// Find out if there is an os specific version needed
    if ( isset( $argArray["req"][$operatingSystem] ) )
        $neededVersion = $argArray["req"][$operatingSystem];
    else if ( isset( $argArray["req"]["general"] ) )
        $neededVersion = $argArray["req"]["general"];
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
function testPermissions( $file, $permissions = "w+" )
{
	// Make sure, we are working in the right directory.
	$file = eZSys::siteDir() . $file;

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

?>
