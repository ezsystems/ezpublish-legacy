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

Here a short todo list (more or less in order of importance):
    - more tests (these are not trivial):
        + critical combinations (winxp + php_isapi + ezsession = crash)
        + redhat: multipart/form-data
    - use internationalization
    - set nVH variables (siteDir, wwwDir, indexFile, includeDir)
    - create classes (?)
    - download of ez publish by php script
    - upgrade option

*/

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );


// Include common functions
include_once( "lib/ezsetup/classes/ezsetupcommon.php" );


// Initialize template
$tpl =& eZTemplate::instance();
$tpl->registerFunction( "section", new eZTemplateSectionFunction( "section" ) );
$tpl->registerFunction( "include", new eZTemplateIncludeFunction() );


// Initialize HTTP variables
$http =& eZHttpTool::instance();


// Test at what step we are
if ( $http->hasVariable( "nextStep" ) )
    $step = $http->postVariable( "nextStep" );
else
    $step = 1;


// Some common variables for all steps
$tpl->setVariable( "script", eZSys::serverVariable( 'PHP_SELF' ) );
$tpl->setVariable( "step", $step );
$tpl->setVariable( "nextStep", $step+1 );
if ( $step > 1 )
    $tpl->setVariable( "prevStep", $step-1 );


// Try to include the relevant file
$includeFile = "lib/ezsetup/classes/ezsetupstep" . $step . ".php";
if ( file_exists( $includeFile ) )
{
	include_once( $includeFile );
    eZSetupStep( $tpl, $http, $ini );

}
else
{
	print "<h1>Step $step is not valid. I'm exiting...</h1>";
	exit;
}


// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

print( eZDisplayDebug() );
require_once( "lib/ezutils/classes/ezexecution.php" );
eZExecution::cleanExit();
?>
