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
	$config = eZINI::instance( "setup.ini" );
	$namedArray = $config->getNamedArray();

	// Convert the pseudo array (like "item1;item2;item3") to real arrays
	foreach( array_keys( $namedArray ) as $mainKey )
	{
		foreach( array_keys( $namedArray[$mainKey] ) as $key )
		{
			if ( preg_match( "/;/", $namedArray[$mainKey][$key] ) )
				$namedArray[$mainKey][$key] = preg_split( "/;/", $namedArray[$mainKey][$key] );
		}
	}
	return $namedArray;
}

// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

print( eZDisplayDebug() );
exit;
?>
