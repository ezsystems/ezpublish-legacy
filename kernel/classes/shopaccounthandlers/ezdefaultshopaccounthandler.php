<?php
//
// Definition of eZDefaultShopAccountHandler class
//
// Created on: <13-Feb-2003 08:58:14 bf>
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
        $user =& eZUser::currentUser();

        if ( !$user->isLoggedIn() )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /*!
     Redirectes to the user registration page.
    */
    function email( $order = false )
    {
        $user =& eZUser::currentUser();
        return $user->attribute( 'email' );
    }

    /*!
     \return the custom name for the given order
    */
    function accountName( $order )
    {
        $user =& eZUser::currentUser();
        $accountName = $user->attribute( 'name' );
        return $accountName;
    }

    function fetchAccountInformation( &$module )
    {
        eZHTTPTool::setSessionVariable( 'RedirectAfterLogin', '/shop/basket/' );
        eZHTTPTool::setSessionVariable( 'DoCheckoutAutomatically', true );
        $module->redirectTo( '/user/login/' );
    }

    function accountInformation( $order )
    {
        $user =& $order->user();
        $userObject =& $user->attribute( "contentobject" );
        $dataMap =& $object->dataMap();

        return array( 'first_name' => $dataMap['first_name']->content(),
                      'last_name' => $dataMap['last_name']->content(),
                      'email' => $user->attribute( "email" )
                      );
    }
}

?>
