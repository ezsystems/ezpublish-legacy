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
    $resultArray["phpversion"] = testPhpVersion( $testItems["phpversion"] );   
    
    // Test database modules
    foreach( $testItems["database"] as $item )
        $resultArray[$item["modulename"]] = testModule( $item );        
    
    // Test for more modules
    foreach( $testItems["modules"] as $item )
        $resultArray[$item["modulename"]] = testModule( $item );

    // Test for file permissions
    $resultArray["filepermissions"] = testFilePermissions( $testItems["filepermissions"] );
    
    // Create the array that shows our results to the user
    $outputArray = array();
    foreach( $resultArray as $resultItem )
    {
        // Make the result look a bit nicer
        if ( $resultItem["pass"] == true )
        {
		    $pass = "pass";
			$class = "ezsetup_ok";
		}
        else
		{
            $pass = "failed";
 
			if ( $resultItem["req"] == "one db" || $resultItem["req"] == false )
				$class = "ezsetup_warning";
			else
				$class = "ezsetup_fatal";
		}
        
        if ( $resultItem["exist"] == false )
            $exist = "no";
        else if ( is_string( $resultItem["exist"] ) )
            $exist = $resultItem["exist"];
        else
            $exist = "yes";
        
        if ( $resultItem["req"] == false )
            $req = "no";
        else if ( is_string( $resultItem["req"] ) )
            $req = $resultItem["req"];
        else
            $req = "yes";
            
        $outputArray[] = array( "desc" => $resultItem["desc"],
                                    "req"  => $req,
                                    "exist" => $exist,
                                    "pass" => $pass,
									"class" => $class );
    }
    $tpl->setVariable( "itemsResult", $outputArray );

    
    //
    // Now see if we have a problem
    //
    
    // Shall we continue??
    $continue = true;
    
    // Error reporting for php version
    if ( $resultArray["phpversion"]["pass"] == false )
    {
        $tpl->setVariable( "errorDescription", $testItems["phpversion"]["errorDescription"] );
        $tpl->setVariable( "errorSuggestion", $testItems["phpversion"]["errorSuggestion"] );
        $continue = false;    
    }
    
    // Only continue with error testing, if the above test was successful
    if ( $continue == true )
    {
        // Error reporting for database modules
        $availableDatabases = count( $testItems["database"] ); 
        $availableDatabasesArray = array();
        foreach( $testItems["database"] as $item )
        {
            switch( $resultArray[$item["modulename"]]["pass"] )
            {
                case true:
                {
                    $availableDatabasesArray[] = array( "name" => $item["modulename"], "pass" => "true" );
                }break;
                case false:
                {
                    $availableDatabases--;
                    $availableDatabasesArray[] = array( "name" => $item["modulename"], "pass" => "false" );                    
                }break;                
            }
        }
        
        if ( $availableDatabases < 1 )
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
        foreach( $testItems["modules"] as $item )
        {
            if ( $item["req"] == true && $resultArray[$item["modulename"]]["pass"] == false )
            {
                $continue = false;
                $tpl->setVariable( "errorDescription", $item["errorDescription"] );
                $tpl->setVariable( "errorSuggestion", $item["errorSuggestion"] );  
            }
        }
    }

    // Only continue with error testing, if the above test was successful
    if ( $continue == true && $resultArray["filepermissions"]["pass"] == false )
    {
        $continue = false;
        $tpl->setVariable( "errorDescription", $testItems["filepermissions"]["errorDescription"] );
        $tpl->setVariable( "errorSuggestion", $testItems["filepermissions"]["errorSuggestion"] );        
    }
    
    //
    // DB Settings
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

    // compare the versions
    $currentVersion = phpversion();
    $compCurrentVersion = str_replace( ".", "", $currentVersion );
    $compNeededVersion = str_replace( ".", "", $neededVersion );
    if ( $compCurrentVersion >= $compNeededVersion )
        $pass = true;
    else
        $pass = false;
    
    return array( "desc" => $argArray["desc"],
                  "req"  => $neededVersion,
                  "exist" => $currentVersion,
                  "pass" => $pass
                );     
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

    return array( "desc" => $argArray["desc"],
                  "req" => $argArray["req"],
                  "exist" => $pass,
                  "pass" => $pass 
                );         
}



/*!
    Test if we can create a file in this directory.
*/
function testFilePermissions( $argArray )
{
	$pass = true;

	$tmpfname = tempnam( eZSys::siteDir(), "ezsetup" );
	if ( ! $tmpfname )
		$pass = false;	
	
	if ( $pass )
		$fp = fopen( $tmpfname, "w" );
	if ( ! $fp )
		$pass = false;
		
	if ( $pass )
		$test = fwrite( $fp, "this file can be deleted. It gets created by the eZ setup module of eZ publish." );
	if ( ! $test )
		$pass = false;

	if ( $pass )
		$test = fclose( $fp );
	if ( ! $test )
		$pass = false;
	
	if ( $pass )
		$test = unlink( $tmpfname );
	if ( ! $test )
		$pass = false;
	
    $status = $pass;

    return array( "desc" => $argArray["desc"],
                  "req" => $argArray["req"],
                  "exist" => $status,
                  "pass" => $pass );    
}

?>