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

// For sending the register email
require_once( "lib/ezutils/classes/ezmail.php" );



/*!
    Step 5: Write site.ini
*/
function eZSetupStep( &$tpl, &$http, &$ini )
{
    // Get our configuration
	$testItems = configuration();

 	// Complete testItems with the test results
	$nothing = array();
	completeItems( $testItems, $http, $nothing );
	unset( $nothing );


    // Get our variables from the post form
    $dbType      = $http->postVariable( "dbType" );
    $dbServer    = $http->postVariable( "dbServer" );
    $dbName      = $http->postVariable( "dbName" );
    $dbUser      = $http->postVariable( "dbUser" );
    $dbPass      = $http->postVariable( "dbPass" );
    $siteName    = $http->postVariable( "siteName" );
    $siteURL     = $http->postVariable( "siteURL" );
    $siteCharset = $http->postVariable( "siteCharset" );
	if ( $http->hasVariable( "sendEmail" ) )
		$sendEmail = true;
	else
		$sendEmail = false;
    
    // Set values in ini
    $ini->setVariable( "SiteSettings", "SiteName", $siteName );
    $ini->setVariable( "SiteSettings", "SiteURL", $siteURL );
    $ini->setVariable( "DatabaseSettings", "DatabaseImplementation", "ez" . $dbType );
    $ini->setVariable( "DatabaseSettings", "Server", $dbServer );
    $ini->setVariable( "DatabaseSettings", "Database", $dbName );
    $ini->setVariable( "DatabaseSettings", "User", $dbUser );
    $ini->setVariable( "DatabaseSettings", "Password", $dbPass );
    // $ini->setVariable( "SiteAccessSettings", "CheckValidity", "false" );
                        
	// Set values in i18n ini
	$i18n = eZIni::instance( "i18n.ini", "" );
	$i18n->setVariable( "CharacterSettings", "Charset", $siteCharset );
	if ( $testItems["mbstring"]["pass"] == true )
		$mbstring = "enabled";
	else
		$mbstring = "disabled";
	$i18n->setVariable( "CharacterSettings", "MBStringExtension", $mbstring );
	$savingStatus = writeIni( $i18n );
    
	// Write ini file
	if ( $savingStatus )
	    $savingStatus = writeIni( $ini );
	else
		$errorMessage = "unsuccessful (i18n.ini writing)!";
	
    if ( $savingStatus )
    {
        $tpl->setVariable( "configWrite", "successful" );
        $tpl->setVariable( "continue", true );
        $tpl->setVariable( "url", eZSys::wwwDir() . eZSys::indexFile() );
    }
    else
    {
		if ( ! isset( $errorMessage ) )
			$errorMessage = "unsuccessful (site.ini writing)!";
        $tpl->setVariable( "configWrite", $errorMessage );
        $tpl->setVariable( "continue", false );
    }

	// If email sending is activated
	if ( $sendEmail )
	{
		$params = array();
		if ( $http->hasPostVariable( "emailServer" ) && $http->postVariable("emailServer") != "" )
		{
			$params["hostname"] = trim ( $http->postVariable( "emailServer" ) );
			if ( $http->hasPostVariable( "emailUser" ) )
				$params["user"] = trim( $http->postVariable( "emailUser" ) );
			if ( $http->hasPostVariable( "emailPassword" ) )
				$params["password"] = trim( $http->postVariable( "emailPassword" ) );
		}

		$email = new eZMail( "", $params );
		$email->setTo( "ez@duebbert.de" );
		$email->setFrom( $http->postVariable( "emailAddress" ) );
		$email->setSubject( "Registration - $siteName" );

		$body  = "SiteName: $siteName\n";
		$body .= "SiteURL: $siteURL\n";
		$body .= "Databasetype: $dbType\n";
		$body .= "Databasename: $dbName\n"; // see if we should use a new default name!
		$body .= "SiteCharset: $siteCharset\n";
		$body .= "nVH setup: true\n"; // TODO: once we can do rewrite setups change this
		$body .= "OS: " . php_uname();

		foreach( array_keys( $testItems ) as $key )
		{
			$body .= "$key: ";
			if ( $testItems[$key]["pass"] )
				$body .= "true";
			else
				$body .= "false";
			$body .= "\n";
		}
		
		$email->setBody( $body );
		$email->send();
	}
    
    // Show template
    $tpl->display( "design/standard/templates/setup/step5.tpl" );    
}



/***************************************************************/
/****** Helping functions that are only used by this file ******/
/***************************************************************/



/*!
	Write the ini file, delete the old cache file and make backups
*/
function writeIni( $iniLocal )
{
	// We are tinkering with the ini file, so delete the cache
	if ( file_exists( $iniLocal->CacheFile ) )
		unlink( $iniLocal->CacheFile );

	// Create the proper path
    $filePath = eZSys::siteDir() . $iniLocal->rootDir() . "/" . $iniLocal->FileName;
	
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
	{
        $backup = rename( $filePath . ".php", $filePath . $ext ); 
		return $backup;
	}
	else
		return true;
}
?>
