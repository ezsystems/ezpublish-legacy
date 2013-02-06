<?php
//
// Created on: <16-Jun-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2013 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// 
// 
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/* 
 * Brief: ezjsc module run
 * A light redirector to be able to run other modules indirectly w/o having to use empty layout/set/*.
 */

$uriParams = $Params['Parameters'];
$userParams = $Params['UserParameters'];

// Functions that earlier existed in index_ajax.php (now removed from ezjscore)
function exitWithInternalError( $errorText )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error' );
    //include_once( 'extension/ezjscore/classes/ezjscajaxcontent.php' );
    $contentType = ezjscAjaxContent::getHttpAccept();

    // set headers
    if ( $contentType === 'xml' )
        header('Content-Type: text/xml; charset=utf-8');
    else if ( $contentType === 'json' )
        header('Content-Type: text/javascript; charset=utf-8');

    echo ezjscAjaxContent::autoEncode( array( 'error_text' => $errorText, 'content' => '' ), $contentType );
    eZExecution::cleanExit();
}

function hasAccessToBySetting( $moduleName, $view = false, $policyAccessList = false )
{
    if ( $policyAccessList !== false )
    {
        if ( in_array( $moduleName, $policyAccessList) )
            return true;
        if ( $view && in_array( $moduleName . '/' . $view, $policyAccessList) )
            return true;
    }
    return false;
}



// look for module and view info in uri parameters
if ( !isset( $uriParams[1] ) )
{
    exitWithInternalError( "Did not find module info in url." );
    return;
}

// find module
$uri = eZURI::instance( eZSys::requestURI() );
$moduleName = $uri->element();
$module = eZModule::findModule( $moduleName );
if ( !$module instanceof eZModule )
{
    exitWithInternalError( "'$moduleName' module does not exist, or is not a valid module." );
    return;
}

// check existance of view
$viewName = $uri->element( 1 );
$moduleViews = $module->attribute('views');
if ( !isset( $moduleViews[ $viewName ] ) )
{
    exitWithInternalError( "'$viewName' view does not exist on the current module." );
    return;
}

// Check if module / view is disabled
$moduleCheck = eZModule::accessAllowed( $uri );
if ( !$moduleCheck['result'] )
{
    exitWithInternalError( '$moduleName/$viewName is disabled.' );
}


// check access to view
$ini         = eZINI::instance();
$currentUser = eZUser::currentUser();
if ( !hasAccessToBySetting( $moduleName, $viewName, $ini->variable( 'RoleSettings', 'PolicyOmitList' ) )
  && !$currentUser->hasAccessToView( $module, $viewName, $params ) )
{
    exitWithInternalError( "User does not have access to the $moduleName/$viewName policy." );
    return;
}

// run module view
$uri->increase();
$uri->increase();
$GLOBALS['eZRequestedModule'] = $module;
$moduleResult = $module->run( $viewName, $uri->elements( false ), false, $uri->userParameters() );

// ouput result and end exit cleanly
eZDB::checkTransactionCounter();
echo ezpEvent::getInstance()->filter( 'response/output', $moduleResult['content'] );
eZExecution::cleanExit();
