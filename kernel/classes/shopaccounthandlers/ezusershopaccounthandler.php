<?php
//
// Definition of eZUserShopAccountHandler class
//
// Created on: <04-Mar-2003 09:40:49 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
        $email = false;
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            $emailNode = $dom->getElementsByTagName( 'email' )->item( 0 );
            if ( $emailNode )
            {
                $email = $emailNode->textContent;
            }
        }

        return $email;
    }

    /*!
     \return the account information for the given order
    */
    function accountName( $order )
    {
        $accountName = '';
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            $firstNameNode = $dom->getElementsByTagName( 'first-name' )->item( 0 );
            $lastNameNode = $dom->getElementsByTagName( 'last-name' )->item( 0 );
            $accountName = $firstNameNode->textContent . ' ' . $lastNameNode->textContent;
        }

        return $accountName;
    }

    function accountInformation( $order )
    {
        $firstName = '';
        $lastName = '';
        $email = '';
        $street1 = '';
        $street2 = '';
        $zip = '';
        $place = '';
        $country = '';
        $comment = '';
        $state = '';

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );

            $firstNameNode = $dom->getElementsByTagName( 'first-name' )->item( 0 );
            if ( $firstNameNode )
            {
                $firstName = $firstNameNode->textContent;
            }

            $lastNameNode = $dom->getElementsByTagName( 'last-name' )->item( 0 );
            if ( $lastNameNode )
            {
                $lastName = $lastNameNode->textContent;
            }

            $emailNode = $dom->getElementsByTagName( 'email' )->item( 0 );
            if ( $emailNode )
            {
                $email = $emailNode->textContent;
            }

            $street1Node = $dom->getElementsByTagName( 'street1' )->item( 0 );
            if ( $street1Node )
            {
                $street1 = $street1Node->textContent;
            }

            $street2Node = $dom->getElementsByTagName( 'street2' )->item( 0 );
            if ( $street2Node )
            {
                $street2 = $street2Node->textContent;
            }

            $zipNode = $dom->getElementsByTagName( 'zip' )->item( 0 );
            if ( $zipNode )
            {
                $zip = $zipNode->textContent;
            }

            $placeNode = $dom->getElementsByTagName( 'place' )->item( 0 );
            if ( $placeNode )
            {
                $place = $placeNode->textContent;
            }

            $stateNode = $dom->getElementsByTagName( 'state' )->item( 0 );
            if ( $stateNode )
            {
                $state = $stateNode->textContent;
            }

            $countryNode = $dom->getElementsByTagName( 'country' )->item( 0 );
            if ( $countryNode )
            {
                $country = $countryNode->textContent;
            }

            $commentNode = $dom->getElementsByTagName( 'comment' )->item( 0 );
            if ( $commentNode )
            {
                $comment = $commentNode->textContent;
            }
        }

        return array( 'first_name' => $firstName,
                      'last_name' => $lastName,
                      'email' => $email,
                      'street1' => $street1,
                      'street2' => $street2,
                      'zip' => $zip,
                      'place' => $place,
                      'state' => $state,
                      'country' => $country,
                      'comment' => $comment,
                      );
    }
}

?>
