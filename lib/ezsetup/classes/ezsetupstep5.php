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

    Step 5: Write site.ini

*/
function stepFive( &$tpl, &$http, &$ini )
{
    // Get our configuration
	$testItems = configuration();

 	// Complete testItems with the test results
	completeItems( $testItems, $http );


	//
    // Get our variables from the post form
    $dbType      = $http->postVariable( "dbType" );
    $dbServer    = $http->postVariable( "dbServer" );
    $dbName      = $http->postVariable( "dbName" );
    $dbUser      = $http->postVariable( "dbUser" );
    $dbPass      = $http->postVariable( "dbPass" );
    $siteName    = $http->postVariable( "siteName" );
    $siteURL     = $http->postVariable( "siteURL" );
    $siteCharset = $http->postVariable( "siteCharset" );
    
    // Set values in ini
    $ini->setVariable( "SiteSettings", "SiteName", $siteName );
    $ini->setVariable( "SiteSettings", "SiteURL", $siteURL );
    $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", "ez" . $dbType );
    $ini->setVariable( "DatabaseSettings", "Server", $dbServer );
    $ini->setVariable( "DatabaseSettings", "Database", $dbName );
    $ini->setVariable( "DatabaseSettings", "User", $dbUser );
    $ini->setVariable( "DatabaseSettings", "Password", $dbPass );
    $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );
                        
    
	// Write ini file
    $savingStatus = writeIni( $ini );
    if ( $savingStatus )
    {
        $tpl->setVariable( "configWrite", "successful" );
        $tpl->setVariable( "continue", true );
        $tpl->setVariable( "url", eZSys::wwwDir() . eZSys::indexFile() );
    }
    else
    {
        $tpl->setVariable( "configWrite", "unsuccessful" );
        $tpl->setVariable( "continue", false );
    }
    
    // Show template
    $tpl->display( "design/standard/templates/setup/step5.tpl" );    
}




/*!
	Complete the testItems array with the values that we got of the former post form
*/
function completeItems( &$testItems, &$http )
{
	foreach( array_keys( $testItems ) as $key )
	{
		if ( $http->hasVariable( $key ) )
		{
			// Transform "true" to true and "false" to false
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
}



/*!
	Write the ini file, delete the old cache file and make backups
*/
function writeIni( $iniLocal )
{
	// We are tinkering with the ini file, so delete the cache
	if ( file_exists( $iniLocal->CacheFile ) )
		unlink( $iniLocal->CacheFile );

	// Create the proper path
    $filePath = $iniLocal->rootDir() . "/" . $iniLocal->FileName;
	
	//Backup file
	$backup = backupFile( $filePath );
    if ( $backup )
    {
        // write back ini file
        $savingStatus = $iniLocal->save( $iniLocal->FileName . ".php" );
        if ( $savingStatus )
            return true;
        else
            return false;
    }
    else
        return false;
}



/*!
	Backup file with extension .bak, .b00, .b01, ...
*/
function backupFile( $filePath )
{
	// Find the right backup extension if there are already backup files
	$ext = ".bak.php";
    $i=0;
    while( file_exists( $filePath . $ext ) )
    {
        if ( $i < 10 )
            $ext = ".b0$i.php";
        else
            $ext = ".b$i.php";
        $i++;
    }
    
	// Backup the file with the right extension
	if ( file_exists( $filePath . ".php" ) )
        $backup = rename( $filePath . ".php", $filePath . $ext ); 
    else
        $backup = rename( $filePath, $filePath . $ext ); 
	
	return $backup;
}

?>
