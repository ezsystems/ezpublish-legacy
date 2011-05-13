<?php
/**
 * File containing the eZDefaultShopAccountHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZDefaultShopAccountHandler
{
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
