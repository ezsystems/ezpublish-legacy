<?php
//
// Definition of eZDefaultShopAccountHandler class
//
// Created on: <13-Feb-2003 08:58:14 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

class eZDefaultShopAccountHandler
{
    /*!
    */
    function eZDefaultShopAccountHandler()
    {

    }

    /*!
     Will verify that the user has supplied the correct user information.
     Returns true if we have all the information needed about the user.
    */
    function verifyAccountInformation()
    {
        // Check login
        $user = eZUser::currentUser();
        if ( !$user->isLoggedIn() )
            return false;
        else
            return true;
    }

    /*!
     Redirectes to the user registration page.
    */
    function email( $order = false )
    {
        if ( $order === false )
            $user = eZUser::currentUser();
        else
            $user = $order->attribute( 'user' );

        if ( is_object( $user ) )
            return $user->attribute( 'email' );
        else
            return null;
    }

    /*!
     \return the custom name for the given order
    */
    function accountName( $order = false )
    {
        if ( $order === false )
            $user = eZUser::currentUser();
        else
            $user = $order->attribute( 'user' );

        if ( is_object( $user ) )
        {
            $userObject = $user->attribute( 'contentobject' );
            $accountName = $userObject->attribute( 'name' );
        }
        else
            $accountName = null;
        return $accountName;
    }

    function fetchAccountInformation( &$module )
    {
        $http = eZHTTPTool::instance();
        $http->setSessionVariable( 'RedirectAfterLogin', '/shop/basket/' );
        $http->setSessionVariable( 'DoCheckoutAutomatically', true );
        $module->redirectTo( '/user/login/' );
    }

    function accountInformation( $order )
    {
        $user = $order->user();
        $userObject = $user->attribute( "contentobject" );
        $dataMap = $userObject->dataMap();

        return array( 'first_name' => $dataMap['first_name']->content(),
                      'last_name' => $dataMap['last_name']->content(),
                      'email' => $user->attribute( "email" ) );
    }
}

?>
