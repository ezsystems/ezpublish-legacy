<?php
//
// Created on: <13-Feb-2003 10:10:35 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( "kernel/common/template.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( "kernel/classes/ezbasket.php" );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "lib/ezutils/classes/ezmail.php" );

$tpl =& templateInit();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( '/shop/basket/' );
    return;
}

$tpl->setVariable( "input_error", false );
if ( $module->isCurrentAction( 'Store' ) )
{
    $inputIsValid = true;
    $firstName = $http->postVariable( "FirstName" );
    if ( trim( $firstName ) == "" )
        $inputIsValid = false;
    $lastName = $http->postVariable( "LastName" );
    if ( trim( $lastName ) == "" )
        $inputIsValid = false;
    $email = $http->postVariable( "EMail" );
    if ( ! eZMail::validate( $email ) )
        $inputIsValid = false;
    $address = $http->postVariable( "Address" );
    if ( trim( $address ) == "" )
        $inputIsValid = false;
    $tpl->setVariable( "first_name", $firstName );
    $tpl->setVariable( "last_name", $lastName );
    $tpl->setVariable( "email", $email );
    $tpl->setVariable( "address", $address );

    if ( $inputIsValid == true )
    {
        // Check for validation
        $basket =& eZBasket::currentBasket();
        $order =& $basket->createOrder();

        $doc = new eZDOMDocument( 'account_information' );

        $root =& $doc->createElementNode( "shop_account" );
        $doc->setRoot( $root );

        $firstNameNode =& $doc->createElementNode( "first-name" );
        $firstNameNode->appendChild( $doc->createTextNode( $firstName ) );
        $root->appendChild( $firstNameNode );

        $lastNameNode =& $doc->createElementNode( "last-name" );
        $lastNameNode->appendChild( $doc->createTextNode( $lastName ) );
        $root->appendChild( $lastNameNode );

        $emailNode =& $doc->createElementNode( "email" );
        $emailNode->appendChild( $doc->createTextNode( $email ) );
        $root->appendChild( $emailNode );

        $addressNode =& $doc->createElementNode( "address" );
        $addressNode->appendChild( $doc->createTextNode( $address ) );
        $root->appendChild( $addressNode );

        $order->setAttribute( 'data_text_1', $doc->toString() );
        $order->setAttribute( 'account_identifier', "simple" );
        $order->store();

        eZHTTPTool::setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );

        $module->redirectTo( '/shop/confirmorder/' );
        return;
    }
    else
    {
        $tpl->setVariable( "input_error", true );
    }
}

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/register.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Enter account information' ) ) );

?>
