<?php

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


function testMBString( $parameters )
{
    include_once( "lib/ezi18n/classes/ezmbstringmapper.php" );
    $pass = eZMBStringMapper::hasMBStringExtension();
    $status = $pass;

    return array( "status" => $status, "pass" => $pass );
}



?>
