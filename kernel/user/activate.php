1<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

if ( isset( $Params["UserIDHash"] ) )
    $UserIDHash = $Params["UserIDHash"];
if ( isset( $Params["LoginID"] ) )
    $LoginID = $Params["LoginID"];
$message = null;

$user =& eZUser::fetchByName( $LoginID );
$userID = $user->attribute( 'contentobject_id' );

$idHash = md5( "$LoginID\n$userID" );

if ( $idHash !=  $UserIDHash )
{
    //Todo redirect to default page for page not exist
    return;
}

$hashType = $user->attribute( 'password_hash_type' );
$hash = $user->attribute( 'password_hash' );
$login = $user->attribute( 'login' );

if ( $http->hasPostVariable( "ActivateButton" ) )
{
    $userObject = & eZContentObject::fetch( $userID );
    if ( $userObject->attribute( "published" ) == 0 )
    {
        if ( $http->hasPostVariable( "Password" ) )
        {
            $password = $http->postVariable( "Password" );
            $valide = eZUser::authenticateHash( $login, $password, eZUser::site(),
                                                $hashType,
                                                $hash );
            if ( $valide )
            {
                eZDebug::writeError("fsfsfsf");
                $userSetting =& eZUserSetting::fetch( $userID );
                $userSetting->setAttribute( "is_enabled", true );
                $defaultMaxLogin = 10;
                $userSetting->setAttribute( "max_login", $defaultMaxLogin );
                $userSetting->store();
                $userObject->setAttribute( 'modified', mktime() );
                $userObject->setAttribute( 'published', mktime() );
                $userObject->store();

                // Assign user to guest group which has node_id 12
                $nodeAssignment =& eZNodeAssignment::create( array(
                                                                 'contentobject_id' => $userID,
                                                                 'contentobject_version' => $userObject->attribute( 'current_version' ),
                                                                 'parent_node' => 12,
                                                                 'is_main' => 1
                                                                 )
                                                             );
                $nodeAssignment->store();

                $version =&  $userObject->currentVersion();
                $nodeAssignmentList =& $version->attribute( 'node_assignments' );
                foreach ( array_keys( $nodeAssignmentList ) as $key )
                {
                    $existingNode =& eZContentObjectTreeNode::findNode( $nodeAssignment->attribute( 'parent_node' ) , $userObject->attribute( 'id' ), true );
                    $nodeID = $nodeAssignment->attribute( 'parent_node' );
                    $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                    if ( $existingNode  == null )
                    {
                         $parentNode =& eZContentObjectTreeNode::fetch( $nodeID );
                         $existingNode =&  $parentNode->addChild( $userID, 0, true );
                    }
                    $existingNode->setAttribute( 'contentobject_version', $version->attribute( 'version' ) );
                    $existingNode->setAttribute( 'contentobject_is_published', 1 );
                    if ( $version->attribute( 'main_parent_node_id' ) == $existingNode->attribute( 'parent_node_id' ) )
                    {
                        $userObject->setAttribute( 'main_node_id', $existingNode->attribute( 'node_id' ) );
                    }
                    $userObject->store();
                    $existingNode->store();
                }

                $message = "Your account has been activated!";
                $Module->redirectTo( '/user/login/' );
            }
            else
            {
                $message = "Login ID and password didn't match";
            }
        }
    }
    else
    {
        $message = "Your account has been activated or you are not allowed to activate it at all";
        $Module->redirectTo( '/user/login/' );
    }
}

$Module->setTitle( "Activate account" );
// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "LoginID", $LoginID );
$tpl->setVariable( "UserIDHash", $UserIDHash );
$tpl->setVariable( "message", $message );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:user/activate.tpl" );

?>
