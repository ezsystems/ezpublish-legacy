<?php
//
// Definition of eZSimpleShopAccountHandler class
//
// Created on: <13-Feb-2003 09:54:35 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( 'lib/ezxml/classes/ezxml.php' );

class eZSimpleShopAccountHandler
{
    /*!
    */
    function eZSimpleShopAccountHandler()
    {

    }

    /*!
     Will verify that the user has supplied the correct user information.
     Returns true if we have all the information needed about the user.
    */
    function verifyAccountInformation()
    {
        return false;
    }

    /*!
     Redirectes to the user registration page.
    */
    function fetchAccountInformation( &$module )
    {
        $module->redirectTo( '/shop/register/' );
    }

    /*!
     \return custom email for the given order
    */
    function email( $order )
    {
        $xml = new eZXML();
        $xmlDoc =& $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
            $dom =& $xml->domTree( $xmlDoc );
            $email =& $dom->elementsByName( "email" );
            return $email[0]->textContent();
        }
        else
            return false;
    }

    /*!
     \return custom name for the given order
    */
    function accountName( $order )
    {
        $accountName = "";
        $xml = new eZXML();
        $xmlDoc =& $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
            $dom =& $xml->domTree( $xmlDoc );
            $firstName = $dom->elementsByName( "first-name" );
            $lastName = $dom->elementsByName( "last-name" );
            $accountName = $firstName[0]->textContent() . " " . $lastName[0]->textContent();
        }
        return $accountName;
    }

    /*!
     \return the account information for the given order
    */
    function accountInformation( $order )
    {
        $xml = new eZXML();
        $xmlDoc =& $order->attribute( 'data_text_1' );
        $dom =& $xml->domTree( $xmlDoc );

        $firstName =& $dom->elementsByName( "first-name" );
        $lastName =& $dom->elementsByName( "last-name" );
        $email =& $dom->elementsByName( "email" );
        $address =& $dom->elementsByName( "address" );

        return array( 'first_name' => $firstName[0]->textContent(),
                      'last_name' => $lastName[0]->textContent(),
                      'email' => $email[0]->textContent(),
                      'address' => $address[0]->textContent()
                      );
    }
}

?>
