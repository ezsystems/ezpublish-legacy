<?php
//
// Created on: <30-Aug-2002 17:06:01 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$module =& $Params['Module'];
$errorType = $Params['Type'];
$errorNumber = $Params['Number'];
$extraErrorParameters = $Params['ExtraParameters'];

$tpl->setVariable( 'parameters', $extraErrorParameters );

$siteBasics = $GLOBALS['eZSiteBasics'];
$userObjectRequired = $siteBasics['user-object-required'];

$ini =& eZINI::instance();

if ( $userObjectRequired )
{
    // include user class
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

    $currentUser =& eZUser::currentUser();
    $tpl->setVariable( "current_user", $currentUser );
    $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );
}
else
{
    $tpl->setVariable( "current_user", false );
    $tpl->setVariable( "anonymous_user_id", false );
}

$embedContent = false;

// if ( $errorType == 'kernel' )
{
    $errorINI =& eZINI::instance( 'error.ini' );

    // Redirect if error.ini tells us to
    $errorHandlerList =& $errorINI->variable( 'ErrorSettings-' . $errorType, 'ErrorHandler' );
    $redirectURLList =& $errorINI->variable( 'ErrorSettings-' . $errorType, 'RedirectURL' );
    $rerunURLList =& $errorINI->variable( 'ErrorSettings-' . $errorType, 'RerunURL' );
    $embedURLList =& $errorINI->variable( 'ErrorSettings-' . $errorType, 'EmbedURL' );

    $errorHandlerType = $errorINI->variable( 'ErrorSettings', 'DefaultErrorHandler' );
    if ( isset( $errorHandlerList[$errorNumber] ) )
        $errorHandlerType = $errorHandlerList[$errorNumber];

    if ( $errorHandlerType != 'redirect' )
    {
        // Set apache error headers if error.ini tells us to
        if ( $errorINI->hasVariable( 'ErrorSettings-' . $errorType, 'HTTPError' ) )
        {
            $errorMap = $errorINI->variable( 'ErrorSettings-' . $errorType, 'HTTPError' );
            if ( isset( $errorMap[$errorNumber] ) )
            {
                $httpErrorCode = $errorMap[$errorNumber];
                if ( $errorINI->hasVariable( 'HTTPError-' . $httpErrorCode, 'HTTPName' ) )
                {
                    $httpErrorName = $errorINI->variable( 'HTTPError-' . $httpErrorCode, 'HTTPName' );
                    $httpErrorString = "$httpErrorCode $httpErrorName";
                    header( eZSys::serverVariable( 'SERVER_PROTOCOL' ) . " $httpErrorString" );
                    header( "Status: $httpErrorString" );
                    if ( $errorNumber == EZ_ERROR_KERNEL_MOVED )
                    {
                        $location = eZSys::indexDir() . "/" . $extraErrorParameters['new_location'];
                        header( "Location: " . $location );
                    }
                }
            }
        }
    }
    if ( $errorHandlerType == 'redirect' )
    {
        $errorRedirectURL = $errorINI->variable( 'ErrorSettings', 'DefaultRedirectURL' );
        if ( isset( $redirectURLList[$errorNumber] ) )
            $errorRedirectURL = $redirectURLList[$errorNumber];
        return $module->redirectTo( $errorRedirectURL );
    }
    else if ( $errorHandlerType == 'rerun' )
    {
        $errorRerunURL = $errorINI->variable( 'ErrorSettings', 'DefaultRerunURL' );
        if ( isset( $rerunURLList[$errorNumber] ) )
            $errorRerunURL = $rerunURLList[$errorNumber];
        $Result = array();
        $Result['content'] = false;
        $Result['rerun_uri'] = $errorRerunURL;
        $module->setExitStatus( EZ_MODULE_STATUS_RERUN );
        return $Result;
    }
    else if ( $errorHandlerType == 'embed' )
    {
        $errorEmbedURL = $errorINI->variable( 'ErrorSettings', 'DefaultEmbedURL' );
        if ( isset( $embedURLList[$errorNumber] ) )
            $errorEmbedURL = $embedURLList[$errorNumber];
        $uri = new eZURI( $errorEmbedURL );
        $moduleName = $uri->element();
        $embedModule = eZModule::exists( $moduleName );
        if ( get_class( $module ) == "ezmodule" )
        {
            $uri->increase();
            $viewName = false;
            if ( !$embedModule->singleFunction() )
            {
                $viewName = $uri->element();
                $uri->increase();
            }
            $embedParameters = $uri->elements( false );
            $embedResult =& $embedModule->run( $viewName, $embedParameters );
            $embedContent = $embedResult['content'];
        }

        // write reason to debug
//        $accessMessage = print_r($Params['ExtraParameters']['AccessList']['FunctionRequired'], true);
        // Function required
        if ( isset( $Params['ExtraParameters']['AccessList'] ) )
        {
            $accessMessage = "Function required:\n";
            foreach ( array_keys ( $Params['ExtraParameters']['AccessList']['FunctionRequired'] ) as $key )
                $accessMessage .= " $key : " . $Params['ExtraParameters']['AccessList']['FunctionRequired'][$key] . "\n" ;
            $accessMessage .= "Policies that didn't match:\n";
            foreach ( $Params['ExtraParameters']['AccessList']['PolicyList'] as $policy )
            {
                $accessMessage .= " PolicyID : " . $policy['PolicyID'] . "\n" ;
                $accessMessage .= "  Limitation : " . $policy['LimitationList']['Limitation'] . "\n" ;
                $accessMessage .= "  Required : ";
                foreach ( $policy['LimitationList']['Required'] as $required )
                    $accessMessage .= "$required, ";
                $accessMessage .= "\n";
            }

            eZDebug::writeWarning($accessMessage, "Insufficient permissions", "kernel/error/view.php");
        }

    }
}


$userRedirectURI = '';
$requestedURI =& $GLOBALS['eZRequestedURI'];
if ( get_class( $requestedURI ) == 'ezuri' )
{
    $userRedirectURI = $requestedURI->uriString( true );
}
$tpl->setVariable( 'redirect_uri', $userRedirectURI );
$tpl->setVariable( 'embed_content', $embedContent );

if ( (isset( $Params['ExtraParameters']['AccessList'] ) ) and  ( $ini->variable( 'RoleSettings', 'ShowAccessDeniedReason' ) === "enabled" ) )
{
    $tpl->setVariable( 'module_required', $Params['ExtraParameters']['AccessList']['FunctionRequired']['Module'] );
    $tpl->setVariable( 'function_required', $Params['ExtraParameters']['AccessList']['FunctionRequired']['Function'] );
}


$Result = array();
$Result['content'] =& $tpl->fetch( "design:error/$errorType/$errorNumber.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/error', 'Error' ),
                                'url' => false ),
                         array( 'text' => "$errorType ($errorNumber)",
                                'url' => false ) );

?>
