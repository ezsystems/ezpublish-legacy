<?php
//
// Definition of eZSimpleShopAccountHandler class
//
// Created on: <13-Feb-2003 09:54:35 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
