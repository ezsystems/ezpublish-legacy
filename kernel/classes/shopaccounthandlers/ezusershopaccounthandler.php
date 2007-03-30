<?php
//
// Definition of eZUserShopAccountHandler class
//
// Created on: <04-Mar-2003 09:40:49 bf>
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

class eZUserShopAccountHandler
{
    /*!
    */
    function eZUserShopAccountHandler()
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
        $module->redirectTo( '/shop/userregister/' );
    }

    /*!
     \return the account information for the given order
    */
    function email( $order )
    {
        $xml = new eZXML();
        $xmlDoc =& $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
            $dom = $xml->domTree( $xmlDoc );
            $email =& $dom->elementsByName( "email" );
            return $email[0]->textContent();
        }
        else
            return false;
    }

    /*!
     \return the account information for the given order
    */
    function accountName( $order )
    {
        $accountName = "";
        $xml = new eZXML();
        $xmlDoc =& $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
            $dom = $xml->domTree( $xmlDoc );
            $firstName = $dom->elementsByName( "first-name" );
            $lastName = $dom->elementsByName( "last-name" );
            $accountName = $firstName[0]->textContent() . " " . $lastName[0]->textContent();
        }

        return $accountName;
    }

    function accountInformation( $order )
    {
        $xml = new eZXML();
        $xmlDoc =& $order->attribute( 'data_text_1' );
        $dom = $xml->domTree( $xmlDoc );

        $firstName =& $dom->elementsByName( "first-name" );
        $lastName =& $dom->elementsByName( "last-name" );
        $email =& $dom->elementsByName( "email" );
        $street1 =& $dom->elementsByName( "street1" );
        $street2 =& $dom->elementsByName( "street2" );
        $zip =& $dom->elementsByName( "zip" );
        $place =& $dom->elementsByName( "place" );
        $state =& $dom->elementsByName( "state" );
        $country =& $dom->elementsByName( "country" );
        $comment =& $dom->elementsByName( "comment" );

        $firstNameText = "";
        if ( is_object( $firstName[0] ) )
            $firstNameText = $firstName[0]->textContent();

        $lastNameText = "";
        if ( is_object( $lastName[0] ) )
            $lastNameText = $lastName[0]->textContent();

        $emailText = "";
        if ( is_object( $email[0] ) )
            $emailText = $email[0]->textContent();

        $street1Text = "";
        if ( is_object( $street1[0] ) )
            $street1Text = $street1[0]->textContent();

        $street2Text = "";
        if ( is_object( $street2[0] ) )
            $street2Text = $street2[0]->textContent();

        $zipText = "";
        if ( is_object( $zip[0] ) )
            $zipText = $zip[0]->textContent();

        $placeText = "";
        if ( is_object( $place[0] ) )
            $placeText = $place[0]->textContent();

        $stateText = "";
        if ( is_object( $state[0] ) )
            $stateText = $state[0]->textContent();

        $countryText = "";
        if ( is_object( $country[0] ) )
            $countryText = $country[0]->textContent();

        $commentText = "";
        if ( is_object( $comment[0] ) )
            $commentText = $comment[0]->textContent();

        return array( 'first_name' => $firstNameText,
                      'last_name' => $lastNameText,
                      'email' => $emailText,
                      'street1' => $street1Text,
                      'street2' => $street2Text,
                      'zip' => $zipText,
                      'place' => $placeText,
                      'state' => $stateText,
                      'country' => $countryText,
                      'comment' => $commentText,
                      );
    }
}

?>
