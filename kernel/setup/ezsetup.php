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
include_once( "kernel/setup/ezsetupcommon.php" );


// Initialize template
$tpl =& eZTemplate::instance();
//$tpl->registerFunction( "section", new eZTemplateSectionFunction( "section" ) );
//$tpl->registerFunction( "include", new eZTemplateIncludeFunction() );

include_once( 'kernel/common/eztemplatedesignresource.php' );
include_once( 'lib/ezutils/classes/ezini.php' );
$ini =& eZINI::instance();
if ( $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled' )
    eZTemplate::setIsDebugEnabled( true );

$Module =& $Params['Module'];
    
$tpl->setAutoloadPathList( $ini->variableArray( 'TemplateSettings', 'AutoloadPath' ) );
$tpl->autoload();

$tpl->registerResource( eZTemplateDesignResource::instance() );

// Initialize HTTP variables
$http =& eZHttpTool::instance();

$baseDir = 'kernel/setup/';
// Test at what part we are
$part = 1;

// Test at what step we are
$step = 1;
if ( $Module->hasActionParameter( "Step" ) )
{
    $nextStep = $Module->actionParameter( "Step" );
    eZDebug::writeDebug( "Switching to step $nextStep", 'ezsetup' );
    $step = $nextStep;
}

$partTable = array( 1 => array( 'name' => 'init',
                                'description' => 'Initialization' ) );
$partInfo = $partTable[$part];
$partName = $partInfo['name'];
$partDescription = $partInfo['description'];

$partFile = $baseDir . "parts/$partName/ezpart_init.php";
$partLoaded = false;
if ( file_exists( $partFile ) )
{
    include_once( $partFile );
    $partLoaded = true;
}
if ( !$partLoaded or
     !isset( $stepTable ) )
{
	print "<h1>Invalid part $part. Setup is exiting...</h1>";
    eZDisplayResult( $templateResult, eZDisplayDebug() );
    eZExecution::cleanExit();
}

$stepInfo = $stepTable[$step];
$stepName = $stepInfo['name'];
$stepDescription = $stepInfo['description'];

$type = 0;
for ( $i =0; $i < count( $mainStepTable ); ++$i )
{
    $mainStep =& $mainStepTable[$i];
    if ( in_array( $step, $mainStep['id_list'] ) )
        $type = 1;
    $mainStep['type'] = $type;
    if ( in_array( $step, $mainStep['id_list'] ) )
        $type = 2;
}

$partData = array( 'current' => $part,
                   'name' => $partName,
                   'description' => $partDescription );
$stepData = array( 'current' => $step,
                   'name' => $stepName,
                   'description' => $stepDescription );
$setup = array( 'part' => $partData,
                'step' => $stepData,
                'steps' => $stepTable,
                'main_steps' => $mainStepTable );

// Some common variables for all steps
$tpl->setVariable( "script", eZSys::serverVariable( 'PHP_SELF' ) );
$tpl->setVariable( "setup", $setup );

$siteBasics =& $GLOBALS['eZSiteBasics'];
$useIndex = $siteBasics['validity-check-required'];

if ( $useIndex )
    $script = eZSys::wwwDir() . eZSys::indexFileName();
else
    $script = eZSys::indexFile() . "/setup/$partName";
$tpl->setVariable( 'script', $script );

include_once( 'lib/version.php' );
$tpl->setVariable( "version", array( "text" => eZPublishSDK::version(),
                                     "major" => eZPublishSDK::majorVersion(),
                                     "minor" => eZPublishSDK::minorVersion(),
                                     "release" => eZPublishSDK::release() ) );

$persistenceList = eZSetupFetchPersistenceList();
$tpl->setVariableRef( 'persistence_list', $persistenceList );

// Try to include the relevant file
$includeFile = $baseDir . "parts/$partName/ezstep_$stepName.php";
$result = array();
if ( file_exists( $includeFile ) )
{
	include_once( $includeFile );
    $result =& eZSetupStep( $tpl, $http, $ini, $persistenceList );
}
else
{
	print "<h1>Step $step is not valid, no such file '$includeFile'. I'm exiting...</h1>";
    eZDisplayResult( $templateResult, eZDisplayDebug() );
    eZExecution::cleanExit();
}

$result['setup_info'] = $setup;

// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

return $result;

//eZDisplayResult( $templateResult, eZDisplayDebug() );

//require_once( "lib/ezutils/classes/ezexecution.php" );
//eZExecution::cleanExit();
?>
