<?php
//
// Definition of Ldapusermanage class
//
// Created on: <28-Jul-2003 15:12:08 wy>
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

/*! \file ldapusermanage.php
*/

include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/classes/datatypes/ezuser/ezusersetting.php' );
include_once( 'kernel/classes/ezcontentobject.php' );

eZModule::setGlobalPathList( array( "kernel" ) );
if ( !$isQuiet )
    $cli->output( "Checking LDAP users ..."  );

$db =& eZDB::instance();
$query = "SELECT contentobject_id, login
          FROM ezcontentobject, ezuser
          WHERE remote_id like 'LDAP%'
          AND ezcontentobject.id=contentobject_id";

$LDAPUsers =& $db->arrayQuery( $query );

$ini =& eZINI::instance();
$LDAPIni =& eZINI::instance( 'ldap.ini' );
$LDAPHost = $LDAPIni->variable( 'LDAPSettings', 'LDAPServer' );
$LDAPBaseDN = $LDAPIni->variable( 'LDAPSettings', 'LDAPBaseDn' );
$LDAPLogin = $LDAPIni->variable( 'LDAPSettings', 'LDAPLoginAttribute' );
$LDAPSearchScope = $LDAPIni->variable( 'LDAPSettings', 'LDAPSearchScope' );
$LDAPFirstNameAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPFirstNameAttribute' );
$LDAPLastNameAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPLastNameAttribute' );
$LDAPEmailAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPEmailAttribute' );
$LDAPUserGroupAttributeType = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupAttributeType' );
$LDAPUserGroupAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupAttribute' );
if ( $LDAPIni->hasVariable( 'LDAPSettings', 'LDAPSearchFilters' ) )
{
    $LDAPFilters = $LDAPIni->variable( 'LDAPSettings', 'LDAPSearchFilters' );
}
if ( $LDAPIni->hasVariable( 'LDAPSettings', 'LDAPUserGroupType' ) and  $LDAPIni->hasVariable( 'LDAPSettings', 'LDAPUserGroup' ) )
{
    $LDAPUserGroupType = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupType' );
    $LDAPUserGroup = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroup' );
}

if ( $LDAPIni->hasVariable( 'LDAPSettings', 'Utf8Encoding' ) )
{
    $isUtf8Encoding = $LDAPIni->variable( 'LDAPSettings', 'Utf8Encoding' );
}
else
{
    $isUtf8Encoding = false;
}

$LDAPEqualSign = trim($LDAPIni->variable( 'LDAPSettings', "LDAPEqualSign" ) );
$LDAPBaseDN = str_replace( $LDAPEqualSign, "=", $LDAPBaseDN );

if ( $LDAPUserGroupAttributeType != null )
{
    $attributeArray = array( $LDAPFirstNameAttribute,
                             $LDAPLastNameAttribute,
                             $LDAPEmailAttribute,
                             $LDAPUserGroupAttribute );
}
else
{
    $attributeArray = array( $LDAPFirstNameAttribute,
                             $LDAPLastNameAttribute,
                             $LDAPEmailAttribute );
}

$defaultUserPlacement = $ini->variable( "UserSettings", "DefaultUserPlacement" );
if ( $LDAPUserGroupType != null )
{
    if ( $LDAPUserGroupType == "name" )
    {
        $groupName = $LDAPUserGroup;
        $groupQuery = "SELECT ezcontentobject_tree.node_id
                       FROM ezcontentobject, ezcontentobject_tree
                       WHERE ezcontentobject.name='$groupName'
                       AND ezcontentobject.id=ezcontentobject_tree.contentobject_id";
        if ( count( $groupObject ) > 0 )
        {
            $defaultUserPlacement = $groupObject[0]['node_id'];
        }
    }
    else if ( $LDAPUserGroupType == "id" )
    {
        $groupID = $LDAPUserGroup;
        $groupQuery = "SELECT ezcontentobject_tree.node_id
                                           FROM ezcontentobject, ezcontentobject_tree
                                           WHERE ezcontentobject.id='$groupID'
                                           AND ezcontentobject.id=ezcontentobject_tree.contentobject_id";
        $groupObject =& $db->arrayQuery( $groupQuery );

        if ( count( $groupObject ) > 0 )
        {
            $defaultUserPlacement = $groupObject[0]['node_id'];
        }
    }
}

//connect to LDAP server
$ds = ldap_connect( $LDAPHost );

if ( $ds )
{
    $r = ldap_bind( $ds );
    if ( !$r )
    {
        return false;
    }
    ldap_set_option( $ds, LDAP_OPT_SIZELIMIT, 0 );
    ldap_set_option( $ds, LDAP_OPT_TIMELIMIT, 0 );
}
else
{
    return false;
}

foreach ( array_keys ( $LDAPUsers ) as $key )
{
    $LDAPUser =& $LDAPUsers[$key];
    $login = $LDAPUser['login'];
    $userID = $LDAPUser['contentobject_id'];

    $LDAPFilter = "( &";
    if ( count( $LDAPFilters ) > 0 )
    {
        foreach ( array_keys( $LDAPFilters ) as $key )
        {
            $LDAPFilter .= "(" . $LDAPFilters[$key] . ")";
        }
    }
    $LDAPFilter .= "($LDAPLogin=$login)";
    $LDAPFilter .= ")";
    $LDAPFilter = str_replace( $LDAPEqualSign, "=", $LDAPFilter );
    if ( $LDAPSearchScope == "one" )
        $sr = ldap_list( $ds, $LDAPBaseDN, $LDAPFilter, $attributeArray );
    else if ( $LDAPSearchScope == "base" )
        $sr = ldap_read( $ds, $LDAPBaseDN, $LDAPFilter, $attributeArray );
    else
        $sr = ldap_search( $ds, $LDAPBaseDN, $LDAPFilter, $attributeArray );
    $info = ldap_get_entries( $ds, $sr );
    if ( $info["count"] != 1 )
    {
        $cli->output( "Disable user " . $cli->stylize( 'emphasize', $login ) );
        // Disable the user
        $userSetting =& eZUserSetting::fetch( $userID );
        $userSetting->setAttribute( "is_enabled", false );
        $userSetting->store();
    }
    else
    {
        // Update user information
        $contentObject =& eZContentObject::fetch( $userID );

        $parentNodeID = $contentObject->attribute( 'main_parent_node_id' );
        $currentVersion = $contentObject->attribute( 'current_version' );

        $version =& $contentObject->attribute( 'current' );
        $contentObjectAttributes =& $version->contentObjectAttributes();

        if ( $isUtf8Encoding )
        {
            $firstName = utf8_decode( $info[0][$LDAPFirstNameAttribute][0] );
            $lastName = utf8_decode( $info[0][$LDAPLastNameAttribute][0] );
            $ldapEMail = utf8_decode( $info[0][$LDAPEmailAttribute][0] );
        }
        else
        {
            $firstName = $info[0][$LDAPFirstNameAttribute][0];
            $lastName = $info[0][$LDAPLastNameAttribute][0];
            $ldapEMail = $info[0][$LDAPEmailAttribute][0];
        }

        $contentObjectAttributes[0]->setAttribute( 'data_text', $firstName );
        $contentObjectAttributes[0]->store();

        $contentObjectAttributes[1]->setAttribute( 'data_text', $lastName );
        $contentObjectAttributes[1]->store();

        $contentClass =& $contentObject->attribute( 'content_class' );
        $name = $contentClass->contentObjectName( $contentObject );
        $contentObject->setName( $name );

        $existUser =& eZUser::fetch(  $userID );
        $existUser->setAttribute('email', $ldapEMail );
        $existUser->setAttribute('password_hash', "" );
        $existUser->setAttribute('password_hash_type', 0 );
        $existUser->store();

        // If user has changed to another group, update it.
        if ( $LDAPUserGroupAttributeType != null )
        {
            if ( $LDAPUserGroupAttributeType == "name" )
            {
                if ( $isUtf8Encoding )
                {
                     $LDAPGroupName = utf8_decode( $info[0][$LDAPUserGroupAttribute][0] );
                }
                else
                {
                    $LDAPGroupName = $info[0][$LDAPUserGroupAttribute][0];
                }
                if ( $LDAPGroupName != null )
                {
                    $LDAPGroupQuery = "SELECT ezcontentobject_tree.node_id
                                       FROM ezcontentobject, ezcontentobject_tree
                                       WHERE ezcontentobject.name='$LDAPGroupName'
                                       AND ezcontentobject.id=ezcontentobject_tree.contentobject_id";
                    $LDAPGroupObject =& $db->arrayQuery( $LDAPGroupQuery );

                    if ( count( $LDAPGroupObject ) > 0 )
                    {
                        $groupNodeID = $LDAPGroupObject[0]['node_id'];
                        if ( $groupNodeID != $parentNodeID )
                        {
                            $cli->output( $cli->stylize( 'emphasize', $existUser->attribute('login') ) . " has been moved to the group he belongs." );
                            $newVersion =& $contentObject->createNewVersion();
                            $newVersion->assignToNode( $groupNodeID, 1 );
                            $newVersion->removeAssignment( $parentNodeID );
                            $newVersionNr = $newVersion->attribute( 'version' );
                            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                                         'version' => $newVersionNr ) );
                        }
                    }
                    else
                    {
                        // move user to default group
                        if ( $defaultUserPlacement != $parentNodeID )
                        {
                            $cli->output( $cli->stylize( 'emphasize', $existUser->attribute('login') ) . " has been moved to default group" );
                            $newVersion =& $contentObject->createNewVersion();
                            $newVersion->assignToNode( $defaultUserPlacement, 1 );
                            $newVersion->removeAssignment( $parentNodeID );
                            $newVersionNr = $newVersion->attribute( 'version' );
                            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                                         'version' => $newVersionNr ) );
                        }
                    }
                }
            }
            else if ( $LDAPUserGroupAttributeType == "id" )
            {
                if ( $isUtf8Encoding )
                {
                    $LDAPGroupID = utf8_decode( $info[0][$LDAPUserGroupAttribute][0] );
                }
                else
                {
                    $LDAPGroupID = $info[0][$LDAPUserGroupAttribute][0];
                }
                if ( $LDAPGroupID != null )
                {
                    $LDAPGroupName = "LDAP " . $groupID;
                    $LDAPGroupQuery = "SELECT ezcontentobject_tree.node_id
                                       FROM ezcontentobject, ezcontentobject_tree
                                       WHERE ezcontentobject.name='$LDAPGroupName'
                                       AND ezcontentobject.id=ezcontentobject_tree.contentobject_id";
                    $LDAPGroupObject =& $db->arrayQuery( $LDAPGroupQuery );

                    if ( count( $LDAPGroupObject ) > 0 )
                    {
                        $groupNodeID = $LDAPGroupObject[0]['node_id'];
                        if ( $groupNodeID != $parentNodeID )
                        {
                            $cli->output( $cli->stylize( 'emphasize', $existUser->attribute('login') ) . " has been moved to the group he belongs." );
                            $newVersion =& $contentObject->createNewVersion();
                            $newVersion->assignToNode( $groupNodeID, 1 );
                            $newVersion->removeAssignment( $parentNodeID );
                            $newVersionNr = $newVersion->attribute( 'version' );
                            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                                         'version' => $newVersionNr ) );
                        }
                    }
                    else
                    {
                        // move user to default group
                        if ( $defaultUserPlacement != $parentNodeID )
                        {
                            $cli->output( $cli->stylize( 'emphasize', $existUser->attribute('login') ) . " has been moved to default group" );
                            $newVersion =& $contentObject->createNewVersion();
                            $newVersion->assignToNode( $defaultUserPlacement, 1 );
                            $newVersion->removeAssignment( $parentNodeID );
                            $newVersionNr = $newVersion->attribute( 'version' );
                            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                                         'version' => $newVersionNr ) );
                        }
                    }
                }
            }
        }
    }
}

if ( !$isQuiet )
    $cli->output( "All LDAP users have been updated!" );
?>
