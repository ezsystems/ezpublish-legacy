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
	$testItems["general"]["phpversion"]["desc"] = "PHP Version";
	$testItems["general"]["phpversion"]["minversion"] = "4.0.0";
	$testItems["general"]["phpversion"]["req"] = true;
	$testItems["general"]["phpversion"]["req_desc"] = $testItems["general"]["phpversion"]["minversion"];
	$testItems["general"]["phpversion"]["error"]["message"] = "Your version of PHP is too old.";
	$testItems["general"]["phpversion"]["error"]["suggestion"] = "<p>If you are using a webhoster, please ask them if they could update PHP to the newest version.</p>
<p>If you are setting up your own server, please update to the newest version of <a href=\"http://www.php.net\">PHP</a>.</p>";

    

	//
    // Databases that eZ publish supports and their error messages.
	// Make them all required, ezsetupstep1 will then change some to not required if one is available.
	$testItems["database"]["mysql"]["desc"]       = "PHP module: MySQL";
	$testItems["database"]["mysql"]["req"]        = true;
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
	$testItems["database"]["postgresql"]["req"]        = true;
	$testItems["database"]["postgresql"]["modulename"] = "postgresql";
	$testItems["database"]["postgresql"]["selectname"] = "PostgreSQL";
	
	$testItems["database"]["oracle"]["desc"]       = "PHP module: Oracle";
	$testItems["database"]["oracle"]["req"]        = true;
	$testItems["database"]["oracle"]["modulename"] = "oracle";
	$testItems["database"]["oracle"]["selectname"] = "Oracle";

	
    // 
    // Modules that are required or useful for eZ publish
	$testItems["modules"]["libgd"]["desc"]       = "PHP module: libGD";
	$testItems["modules"]["libgd"]["req"]        = false;
	$testItems["modules"]["libgd"]["modulename"] = "gd";
	$testItems["modules"]["libgd"]["error"]["general"]["message"]    = "The required PHP module \"libGD\" is not installed.";
	$testItems["modules"]["libgd"]["error"]["general"]["suggestion"] = "Please ask your webhoster to install this module or, if you have your own webserver, install it yourself.";		
    
	
	//
	// Test for filepermissions
    $testItems["files"]["cachedirs"]["desc"] = "Cache directories";
    $testItems["files"]["cachedirs"]["file"] = "var/cache";
	$testItems["files"]["cachedirs"]["req"]  = true;
	$testItems["files"]["cachedirs"]["error"]["message"]    = "The webserver can't write in cache directories.";
	$testItems["files"]["cachedirs"]["error"]["suggestion"] = "Change the permissions of the directory \"" . eZSys::siteDir() . $testItems["files"]["cachedirs"]["file"] . "\" to 775 or 777.";

	$testItems["files"]["ini"]["desc"] = "Site configuration file";
	$testItems["files"]["ini"]["file"] = "settings/site.ini";
	$testItems["files"]["ini"]["req"] = true;
	$testItems["files"]["ini"]["error"]["message"] = "The webserver can't write the site configuration file \"site.ini\"!";
	$testItems["files"]["ini"]["error"]["suggestion"] = "Change the permissions of the file \"settings/site.ini\" to 664 or 666.";
	

    return $testItems;
}


// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

eZDisplayDebug();
exit;
?>
