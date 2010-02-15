<?php
//
// Created on: <04-Mar-2003 10:22:42 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( '/shop/basket/' );
    return;
}

$user = eZUser::currentUser();

$firstName = '';
$lastName = '';
$email = '';
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
        $basket = eZBasket::currentBasket();

        $db = eZDB::instance();
        $db->begin();
        $order = $basket->createOrder();

        $doc = new DOMDocument( '1.0', 'utf-8' );

        $root = $doc->createElement( "shop_account" );
        $doc->appendChild( $root );

        $firstNameNode = $doc->createElement( "first-name", $firstName );
        $root->appendChild( $firstNameNode );

        $lastNameNode = $doc->createElement( "last-name", $lastName );
        $root->appendChild( $lastNameNode );

        $emailNode = $doc->createElement( "email", $email );
        $root->appendChild( $emailNode );

        $street1Node = $doc->createElement( "street1", $street1 );
        $root->appendChild( $street1Node );

        $street2Node = $doc->createElement( "street2", $street2 );
        $root->appendChild( $street2Node );

        $zipNode = $doc->createElement( "zip", $zip );
        $root->appendChild( $zipNode );

        $placeNode = $doc->createElement( "place", $place );
        $root->appendChild( $placeNode );

        $stateNode = $doc->createElement( "state", $state );
        $root->appendChild( $stateNode );

        $countryNode = $doc->createElement( "country", $country );
        $root->appendChild( $countryNode );

        $commentNode = $doc->createElement( "comment", $comment );
        $root->appendChild( $commentNode );

        $xmlString = $doc->saveXML();

        $order->setAttribute( 'data_text_1', $xmlString );
        $order->setAttribute( 'account_identifier', "ez" );

        $order->setAttribute( 'ignore_vat', 0 );

        $order->store();
        $db->commit();
        eZShopFunctions::setPreferredUserCountry( $country );
        $http->setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );

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
$Result['content'] = $tpl->fetch( "design:shop/userregister.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/shop', 'Enter account information' ) ) );
?>
