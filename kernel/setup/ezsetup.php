<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
    - set nVH variables (siteDir, wwwDir, indexFile, includeDir)
    - download of ez publish by php script
    - upgrade option

*/
include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );


// Include common functions
include_once( "kernel/setup/ezsetupcommon.php" );
include_once( 'kernel/setup/steps/ezstep_data.php' );
include_once( 'kernel/setup/ezsetup_summary.php' );

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

$tpl->setAutoloadPathList( $ini->variable( 'TemplateSettings', 'AutoloadPathList' ) );
$tpl->autoload();

$tpl->registerResource( eZTemplateDesignResource::instance() );

// Initialize HTTP variables
$http =& eZHttpTool::instance();

$baseDir = 'kernel/setup/';

// Load step list data. See this file for install step references.
$stepDataFile = $baseDir . "steps/ezstep_data.php";
$stepData = null;
if ( file_exists( $stepDataFile ) )
{
    include_once( $stepDataFile );
    $stepData = new eZStepData();
}
if ( $stepData == null )
{
    print "<h1>Setup step data file not found. Setup is exiting...</h1>"; //TODO : i18n translate
    eZDisplayResult( $templateResult, eZDisplayDebug() );
    eZExecution::cleanExit();
}

// Test at what step we are
$step = $stepData->step(0); //step contains file and class
if ( $http->hasPostVariable( 'setup_next_step' ) )
{
    $step = &$stepData->step( $http->postVariable( 'setup_next_step' ) );
}

$persistenceList = eZSetupFetchPersistenceList();
$result = null;

// process previous step
$previousStepClass = null;
if ( $http->hasPostVariable( 'setup_previous_step' ) )
{
    $previousStep = &$stepData->step( $http->postVariable( 'setup_previous_step' ) );

    $includeFile = $baseDir .'steps/ezstep_'.$previousStep['file'].'.php';
    $result = array();

    if ( file_exists( $includeFile ) )
    {
        include_once( $includeFile );
        $className = 'eZStep'.$previousStep['class'];
        $previousStepClass = new $className( $tpl, $http, $ini, $persistenceList );

        if ( $previousStepClass->processPostData() == null ) // processing previous inout failed, step must be redone
        {
            $step = $previousStep;
        }
    }
}

$done = false;
$result = null;

while( !$done && $step != null )
{
// Some common variables for all steps
    $tpl->setVariable( "script", eZSys::serverVariable( 'PHP_SELF' ) );

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
                                         "release" => eZPublishSDK::release(),
                                         "alias" => eZPublishSDK::alias() ) );

    if ( $persistenceList === null )
        $persistenceList = eZSetupFetchPersistenceList();
    $tpl->setVariableRef( 'persistence_list', $persistenceList );

    // Try to include the relevant file
    $includeFile = $baseDir . 'steps/ezstep_'.$step['file'].'.php';
    $stepClass = false;
    if ( file_exists( $includeFile ) )
    {
        include_once( $includeFile );
        $className = 'eZStep'.$step['class'];

        if ( $step == $previousStep ) // if processing post data of previous step failed, use same class object.
        {
            $stepInstaller = $previousStepClass;
        }
        else
        {
            $stepInstaller = new $className( $tpl, $http, $ini, $persistenceList );
        }

        $result =& $stepInstaller->init();

        if( $result === true )
        {
            $step =& $stepData->nextStep( $step );
        }
        else if( is_int( $result ) || is_string( $result ) )
        {
            $step =& $stepData->step( $result );
        }
        else
        {
            $result = $stepInstaller->display();
            $result['help'] = $tpl->fetch( 'design:setup/init/'.$step['file'].'_help.tpl' );
            $done = true;
        }
    }
    else
    {
        print '<h1>Step '.$step['class'].' is not valid, no such file '.$includeFile.'. I\'m exiting...</h1>'; //TODO : i18n
        eZDisplayResult( $templateResult, eZDisplayDebug() );
        eZExecution::cleanExit();
    }
}

// generate summary
$summary = new eZSetupSummary( $tpl, $persistenceList );
$result['summary'] = $summary->summary();

// Compute install progress
$result['progress'] = $stepData->progress( $step );

// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

return $result;

//eZDisplayResult( $templateResult, eZDisplayDebug() );

//require_once( "lib/ezutils/classes/ezexecution.php" );
//eZExecution::cleanExit();
?>
