<?php
//
// Created on: <02-May-2002 16:24:15 bf>
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

include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/common/template.php' );

//$Module->setExitStatus( EZ_MODULE_STATUS_SHOW_LOGIN_PAGE );

$Module =& $Params['Module'];

$ini =& eZINI::instance();
$http =& eZHTTPTool::instance();

$userLogin = '';
$userPassword = '';
$userRedirectURI = '';

if ( $Module->isCurrentAction( 'Login' ) and
     $Module->hasActionParameter( 'UserLogin' ) and
     $Module->hasActionParameter( 'UserPassword' )
     )
{
    $userLogin = $Module->actionParameter( 'UserLogin' );
    $userPassword = $Module->actionParameter( 'UserPassword' );
    $userRedirectURI = $Module->actionParameter( 'UserRedirectURI' );

    $user = eZUser::loginUser( $userLogin, $userPassword );

    $redirectionURI = $userRedirectURI;
    if ( $redirectionURI == '' )
        $redirectionURI = $ini->variable( 'SiteSettings', 'DefaultPage' );

    eZDebug::writeNotice( $user, 'user');
    $userID = 0;
    if ( get_class( $user ) == 'ezuser' )
        $userID = $user->id();
    if ( $userID > 0 )
    {
        $http->removeSessionVariable( 'eZUserLoggedInID' );
        $http->setSessionVariable( 'eZUserLoggedInID', $userID );
        eZDebug::writeNotice( $http->sessionVariable( 'eZUserLoggedInID' ), 'user' );
        return $Module->redirectTo( $redirectionURI );
    }
}
else
{
    $requestedURI =& $GLOBALS['eZRequestedURI'];
    if ( get_class( $requestedURI ) == 'ezuri' )
    {
        print( $requestedURI->uriString() . "<br/>" );
        $requestedModule = $requestedURI->element( 0, false );
        $requestedView = $requestedURI->element( 1, false );
        if ( $requestedModule != 'user' or
             $requestedView != 'login' )
            $userRedirectURI = $requestedURI->uriString( true );
    }
}

if( $http->hasPostVariable( "RegisterButton" ) )
{
    $Module->redirectTo( "/user/register" );
}
$tpl =& templateInit();

$tpl->setVariable( 'login', $userLogin, 'User' );
$tpl->setVariable( 'password', $userPassword, 'User' );
$tpl->setVariable( 'redirect_uri', $userRedirectURI, 'User' );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:user/login.tpl' );
$Result['path'] = array( array( 'text' => 'User',
                                'url' => false ),
                         array( 'text' => 'Login',
                                'url' => false ) );
if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';

?>
