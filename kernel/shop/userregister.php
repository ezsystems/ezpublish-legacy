<?php
//
// Created on: <04-Mar-2003 10:22:42 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( 'kernel/common/template.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/ezbasket.php' );
include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'lib/ezutils/classes/ezmail.php' );

$tpl =& templateInit();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( '/shop/basket/' );
    return;
}

$user =& eZUser::currentUser();


if ( $user->isLoggedIn() )
{
    $userObject = $user->attribute( 'contentobject' );
    $userMap = $userObject->dataMap();
    $firstName = $userMap['first_name']->content();
    $lastName = $userMap['last_name']->content();
    $email = $user->attribute( 'email' );
}

// Initialize variables
$street1 = $street2 = $zip = $place = $state = $country = $comment = '';


// Check if user has an earlier order, copy order info from that one
$orderList = eZOrder::activeByUserID( $user->attribute( 'contentobject_id' ) );
if ( count( $orderList ) > 0 and  $user->isLoggedIn() )
{
    $accountInfo = $orderList[0]->accountInformation();
    $street1 = $accountInfo['street1'];
    $street2 = $accountInfo['street2'];
    $zip = $accountInfo['zip'];
    $place = $accountInfo['place'];
    $state = $accountInfo['state'];
    $country = $accountInfo['country'];
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

    $street1 = $http->postVariable( "Street1" );
    $street2 = $http->postVariable( "Street2" );
        if ( trim( $street2 ) == "" )
            $inputIsValid = false;

    $zip = $http->postVariable( "Zip" );
    if ( trim( $zip ) == "" )
        $inputIsValid = false;
    $place = $http->postVariable( "Place" );
    if ( trim( $place ) == "" )
        $inputIsValid = false;
    $state = $http->postVariable( "State" );
    $country = $http->postVariable( "Country" );
    if ( trim( $country ) == "" )
        $inputIsValid = false;

    $comment = $http->postVariable( "Comment" );

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

        $street1Node =& $doc->createElementNode( "street1" );
        $street1Node->appendChild( $doc->createTextNode( $street1 ) );
        $root->appendChild( $street1Node );

        $street2Node =& $doc->createElementNode( "street2" );
        $street2Node->appendChild( $doc->createTextNode( $street2 ) );
        $root->appendChild( $street2Node );

        $zipNode =& $doc->createElementNode( "zip" );
        $zipNode->appendChild( $doc->createTextNode( $zip ) );
        $root->appendChild( $zipNode );

        $placeNode =& $doc->createElementNode( "place" );
        $placeNode->appendChild( $doc->createTextNode( $place ) );
        $root->appendChild( $placeNode );

        $stateNode =& $doc->createElementNode( "state" );
        $stateNode->appendChild( $doc->createTextNode( $state ) );
        $root->appendChild( $stateNode );

        $countryNode =& $doc->createElementNode( "country" );
        $countryNode->appendChild( $doc->createTextNode( $country ) );
        $root->appendChild( $countryNode );

        $commentNode =& $doc->createElementNode( "comment" );
        $commentNode->appendChild( $doc->createTextNode( $comment ) );
        $root->appendChild( $commentNode );

        $order->setAttribute( 'data_text_1', $doc->toString() );
        $order->setAttribute( 'account_identifier', "ez" );

        $order->setAttribute( 'ignore_vat', 0 );

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

$tpl->setVariable( "first_name", $firstName );
$tpl->setVariable( "last_name", $lastName );
$tpl->setVariable( "email", $email );

$tpl->setVariable( "street1", $street1 );
$tpl->setVariable( "street2", $street2 );
$tpl->setVariable( "zip", $zip );
$tpl->setVariable( "place", $place );
$tpl->setVariable( "state", $state );
$tpl->setVariable( "country", $country );
$tpl->setVariable( "comment", $comment );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/userregister.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Enter account information' ) ) );
?>
