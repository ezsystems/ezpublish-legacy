<?php
//
// Created on: <30-Aug-2002 17:06:01 bf>
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

include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$Module =& $Params['Module'];
$Type = $Params['Type'];
$Number = $Params['Number'];
$ExtraParameters = $Params['ExtraParameters'];

$tpl->setVariable( 'parameters', $ExtraParameters );

$siteBasics = $GLOBALS['eZSiteBasics'];
$userObjectRequired = $siteBasics['user-object-required'];

if ( $userObjectRequired )
{
    // include user class
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

    $currentUser =& eZUser::currentUser();
    $ini =& eZINI::instance();
    $tpl->setVariable( "current_user", $currentUser );
    $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );
}
else
{
    $tpl->setVariable( "current_user", false );
    $tpl->setVariable( "anonymous_user_id", false );
}

if ( $Type == 'kernel' )
{
    $error_ini =& eZINI::instance( 'error.ini' );

    // Redirect if error.ini tells us to
    $errorhandlers =& $error_ini->variable( 'ErrorSettings', 'ErrorHandler' );
    $redirecturls =& $error_ini->variable( 'ErrorSettings', 'RedirectURL' );
    if ( $errorhandlers[$Number] == 'redirect' &&
         isSet( $redirecturls[$Number] ) )
    {
        return $Module->redirectTo( $redirecturls[$Number] );
    }

    // Set apache error headers if error.ini tells us to
    if ( in_array( $Number, $error_ini->variableArray( 'ErrorSettings', 'NotFoundErrors' ) ) )
    {
        header( eZSys::serverVariable( 'SERVER_PROTOCOL' ) . " 404 Not Found" );
        header( "Status: 404 Not Found" );
    }
}

$userRedirectURI = '';
$requestedURI =& $GLOBALS['eZRequestedURI'];
if ( get_class( $requestedURI ) == 'ezuri' )
{
//     $requestedModule = $requestedURI->element( 0, false );
//     $requestedView = $requestedURI->element( 1, false );
//     if ( $requestedModule != 'user' or
//          $requestedView != 'login' )
    $userRedirectURI = $requestedURI->uriString( true );
}
$tpl->setVariable( 'redirect_uri', $userRedirectURI );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:error/$Type/$Number.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/error', 'Error' ),
                                'url' => false ),
                         array( 'text' => "$Type ($Number)",
                                'url' => false ) );

?>
