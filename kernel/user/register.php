<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$Module =& $Params["Module"];
$message = null;
$userClassAttributes =& eZContentClassAttribute::fetchFilteredList( array( 'contentclass_id' => 4, 'version' => 0 ) );
$userAttributes = array();
foreach ( $userClassAttributes as $userClassAttribute )
{
    $classAttributeID = $userClassAttribute->attribute( 'id' );
    $classAttributeName = $userClassAttribute->attribute( 'name' );
    if( $classAttributeName != "User account" )
    {
        $item = array( "name" => $classAttributeName,
                       "classAttribute_id" => $classAttributeID );
        $userAttributes[] = $item;
    }
}

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( "StoreButton" ) )
{
    foreach ( $userAttributes as $userAttribute )
    {
        if ( $http->hasPostVariable( $userAttribute['name'] ) )
        {
            $name = $userAttribute['name'];
            $classAttributeID = $userAttribute['classAttribute_id'];
            $value = $http->postVariable( $userAttribute['name'] );
            $objectAttribute =& eZContentObjectAttribute::fetchObject( eZContentObjectAttribute::definition(),
                                                                       null,
                                                                       array( 'contentclassattribute_id' => $classAttributeID,
                                                                              'version' => $currentVersion ) );
            $objectAttribute->setAttribute( 'data_text', $value );
            $objectAttribute->store();
        }
    }
    if ( ( $http->hasPostVariable( "Login_id" ) ) and
         ( $http->hasPostVariable( "Email" ) ) and
         ( $http->hasPostVariable( "Password" ) ) and
         ( $http->hasPostVariable( "Password_confirm" ) ) )
    {
        $login = $http->postVariable( "Login_id" );
        $email = $http->postVariable( "Email" );
        $password = $http->postVariable( "Password" );
        $password_confirm = $http->postVariable( "Password_confirm" );
        if ( ( $password != $passwodConfirm ) || ( $password == "" ) )
            $message = "Password not match.";
    }
    return;
}

if ( $http->hasPostVariable( "CancelButton" ) )
{
    return;
}

$Module->setTitle( "Sign up" );
// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userAttributes", $userAttributes );
$tpl->setVariable( "message", $message );

$Result =& $tpl->fetch( "design:user/register.tpl" );

?>
