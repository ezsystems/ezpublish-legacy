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

// This file holds the test functions that are used by step 1

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

	if ( ! file_exists( $file ) && file_exists( $file . ".php" ) )
        $file = $file . ".php";

	// Directories: Test, if we can create a file
	// Files: Test, if we can open a file in writing mode
	$pass = true;
	if ( ! file_exists( $file ) )
	{
	    $pass = false;
	}
	else if ( is_dir( $file ) )
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
		if ( ! is_writable( $file ) )
			$pass = false;
	}

    return array( "status" => $pass, "pass"   => $pass );
}



/*!
	Test if a program can be found in our path and is executable
*/
function testProgram( $parameters )
{
	$program = $parameters["program"];
	$searchPaths = $parameters["search_paths"];

	// In case we got it from ini file
	if ( !is_array( $searchPaths ) )
		$searchPaths = preg_split( "/;/", $searchPaths );

	$pass = false;
	$status = false;
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



/*!
	Test php ini settings
*/
function testPHPIni( $parameters )
{
	$setting = $parameters["setting"];
    $state = $parameters["state"];

    if ( (bool) ini_get( $setting ) == $state )
        $pass = true;
    else
        $pass = false;

    $status = $pass;
	return array( "status" => $status, "pass" => $pass );
}


/*!
	Test if mbstring is available
*/
function testMBString( $parameters )
{
    include_once( "lib/ezi18n/classes/ezmbstringmapper.php" );
    $pass = eZMBStringMapper::hasMBStringExtension();
    $status = $pass;
    return array( "status" => $status, "pass" => $pass );
}



?>
