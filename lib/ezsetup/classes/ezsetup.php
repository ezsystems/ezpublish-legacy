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


/*
 
Yes, this file still needs A LOT of work.

Here a short todo list (in no order):
    - create new user for database
    - use internationalization    
    - more tests: 
        + modules (libxml?) <- not needed
        + functions (mbstring, ttftextbox, ...)
        + programs (imagemagick)
        + critical combinations (winxp + php_isapi + ezsession = crash)
        + redhat: multipart/form-data
        + security tests (.htaccess, admin password) 
    - set nVH variables (siteDir, wwwDir, indexFile, includeDir)
    - catching of more errors
    - better dealing with errors: try to fix them!
    - more config options for first site.ini
    - create classes
    - installation of demo data (problem: convert .tgz package to phpzip? doesnt work.)
    - register email to ez systems
    - set charsets!
    - download of ez publish by php script
    - upgrade option

...feel free to add to this list, what I forgot.

--kai, 2002-11-08

*/


include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

// Initialize template
$tpl =& eZTemplate::instance();
$tpl->registerFunction( "section", new eZTemplateSectionFunction( "section" ) );
$tpl->registerFunction( "include", new eZTemplateIncludeFunction() );


// Initialize HTTP variables, etc.
$http =& eZHttpTool::instance();

//
// Test at what step we are
//
if ( $http->hasVariable( "nextStep" ) )
    $step = $http->postVariable( "nextStep" );
else
    $step = 1; 

$tpl->setVariable( "script", eZSys::serverVariable( 'PHP_SELF' ) );
$tpl->setVariable( "step", $step );
$tpl->setVariable( "nextStep", $step+1 );
if ( $step > 1 )
    $tpl->setVariable( "prevStep", $step-1 );

switch( $step )
{
    case "4":
    {
		include( "ezsetupstep4.php" );
        stepFour( $tpl, $http, $ini );
    }break;
    case "3":
    { 
		include( "ezsetupstep3.php" );
        stepThree( $tpl, $http );
    }break;
    case "2":
    { 
		include( "ezsetupstep2.php" );
        stepTwo( $tpl, $http );
    }break;    
    case "1":
    default:
    {
		include( "ezsetupstep1.php" );
        stepOne( $tpl, $http );
    }
}


/*!

    Define the test items. Also define error messages. These should later be internationalized.
    TODO: Maybe using an .ini file would be good too? 

*/
function configuration()
{
    $testItems = array();
    
    // What PHP version do we need. If a special version for an OS is needed, put it
    // in "req". See the example for "windows".    
    $testItems["phpversion"] = array( 
        "desc"             => "PHP Version",
        "req"              => array( "general" => "4.0.0", "windows" => "4.0.0" ),
        "errorDescription" => "Your version of PHP is too old.",
        "errorSuggestion"  => "<p>If you are using a webhoster, please ask them 
                               if they could update PHP to the newest version.</p>
                               <p>If you are setting up your own server, please update
                               to the newest version of <a href=\"http://www.php.net\">PHP</a>.</p>",
                                    );
    

	//
    // Databases that eZ publish supports and their error messages.
	$testItems["database"]["mysql"]["desc"]       = "PHP module: MySQL";
	$testItems["database"]["mysql"]["req"]        = "one db";
	$testItems["database"]["mysql"]["modulename"] = "mysql";
	$testItems["database"]["mysql"]["selectname"] = "MySQL";
	$testItems["database"]["mysql"]["error"]["1044"]["message"]    = "Access denied for this user and database combination!";
	$testItems["database"]["mysql"]["error"]["1044"]["suggestion"] = "You probably have access to only one database and are not allowed to create new ones. Please go back and enter the name of that database.";	
	$testItems["database"]["mysql"]["error"]["1045"]["message"]    = "Wrong username and/ or password!";
	$testItems["database"]["mysql"]["error"]["1045"]["suggestion"] = "Please go back and reenter the username and password.";
	$testItems["database"]["mysql"]["error"]["1049"]["message"]    = "Unknown database!";
	$testItems["database"]["mysql"]["error"]["1049"]["suggestion"] = "The provided database doesn't exist!";
	$testItems["database"]["mysql"]["error"]["2005"]["message"]    = "No running MySQL server found on provided hostname!";
	$testItems["database"]["mysql"]["error"]["2005"]["suggestion"] = "Please go back and enter the correct database hostname.";
	$testItems["database"]["mysql"]["error"]["1007"]["message"]    = "Database exists.";
	$testItems["database"]["mysql"]["error"]["1007"]["suggestion"] = "Please go back and choose a different database name.";					
	
	$testItems["database"]["postgresql"]["desc"]       = "PHP module: PostgreSQL";
	$testItems["database"]["postgresql"]["req"]        = "one db";
	$testItems["database"]["postgresql"]["modulename"] = "postgresql";
	$testItems["database"]["postgresql"]["selectname"] = "PostgreSQL";
	
	$testItems["database"]["oracle"]["desc"] = "PHP module: Oracle";
	$testItems["database"]["oracle"]["req"] = "one db";
	$testItems["database"]["oracle"]["modulename"] = "oracle";
	$testItems["database"]["oracle"]["selectname"] = "Oracle";

	
    // 
    // Modules that are required or useful for eZ publish
    $testItems["modules"] = array(
        array( "desc"             => "PHP module: libGD",
               "req"              => false,
               "modulename"       => "gd",
               "errorDescription" => "The required PHP module \"libGD\" is not installed.", 
               "errorSuggestion"  => "Please ask your webhoster to install this module or, if you
                                      have your own webserver, install it yourself." ),
                                  );
                                   
    // Test for filepermissions
    $testItems["filepermissions"] = array( 
        "desc"             => "Correct filepermissions",
        "req"              => true,
        "errorDescription" => "The webserver can't write to files, probably because file permissions are not set right.",
        "errorSuggestion"  => "Please set the permissions of directories to mode \"775\" and the 
                               permissions of files to \"664\". Normally you can do this with the command \"chmod\"
                               and/ or with your FTP software." 
                                           );    

    return $testItems;
}


// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

eZDisplayDebug();
exit;
?>