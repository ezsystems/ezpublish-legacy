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
$LDAPVersion = $LDAPIni->variable( 'LDAPSettings', 'LDAPVersion' );
$LDAPHost = $LDAPIni->variable( 'LDAPSettings', 'LDAPServer' );
$LDAPPort = $LDAPIni->variable( 'LDAPSettings', 'LDAPPort' );
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
    $Utf8EncodingSetting = $LDAPIni->variable( 'LDAPSettings', 'Utf8Encoding' );
    if ( $Utf8EncodingSetting == "true" )
        $isUtf8Encoding = true;
    else
        $isUtf8Encoding = false;
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
$extraNodeAssignments = array();
if ( $LDAPUserGroupType != null )
{
    if ( $LDAPUserGroupType == "name" )
    {
        if ( is_array( $LDAPUserGroup ) )
        {
            foreach ( array_keys( $LDAPUserGroup ) as $key )
            {
                $groupName = $LDAPUserGroup[$key];
                $groupQuery = "SELECT ezcontentobject_tree.node_id
                                 FROM ezcontentobject, ezcontentobject_tree
                                WHERE ezcontentobject.name like '$groupName'
                                  AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                  AND ezcontentobject.contentclass_id=3";
                $groupObject =& $db->arrayQuery( $groupQuery );
                if ( count( $groupObject ) > 0 and $key == 0 )
                {
                    $defaultUserPlacement = $groupObject[0]['node_id'];
                }
                else if ( count( $groupObject ) > 0 )
                {
                    $extraNodeAssignments[] = $groupObject[0]['node_id'];
                }
            }
        }
        else
        {
            $groupName = $LDAPUserGroup;
            $groupQuery = "SELECT ezcontentobject_tree.node_id
                             FROM ezcontentobject, ezcontentobject_tree
                            WHERE ezcontentobject.name like '$groupName'
                              AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                              AND ezcontentobject.contentclass_id=3";
            $groupObject =& $db->arrayQuery( $groupQuery );

            if ( count( $groupObject ) > 0  )
            {
                $defaultUserPlacement = $groupObject[0]['node_id'];
            }
        }
    }
    else if ( $LDAPUserGroupType == "id" )
    {
        if ( is_array( $LDAPUserGroup ) )
        {
            foreach ( array_keys( $LDAPUserGroup ) as $key )
            {
                $groupID = $LDAPUserGroup[$key];
                $groupQuery = "SELECT ezcontentobject_tree.node_id
                                 FROM ezcontentobject, ezcontentobject_tree
                                WHERE ezcontentobject.id='$groupID'
                                  AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                  AND ezcontentobject.contentclass_id=3";
                $groupObject =& $db->arrayQuery( $groupQuery );
                if ( count( $groupObject ) > 0 and $key == 0 )
                {
                    $defaultUserPlacement = $groupObject[0]['node_id'];
                }
                else if ( count( $groupObject ) > 0 )
                {
                    $extraNodeAssignments[] = $groupObject[0]['node_id'];
                }
            }
        }
        else
        {
            $groupID = $LDAPUserGroup;
            $groupQuery = "SELECT ezcontentobject_tree.node_id
                             FROM ezcontentobject, ezcontentobject_tree
                            WHERE ezcontentobject.id='$groupID'
                              AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                              AND ezcontentobject.contentclass_id=3";
            $groupObject =& $db->arrayQuery( $groupQuery );

            if ( count( $groupObject ) > 0  )
            {
                $defaultUserPlacement = $groupObject[0]['node_id'];
            }
        }
    }
}

//connect to LDAP server
$ds = ldap_connect( $LDAPHost, $LDAPPort );
if ( $ds )
{
    ldap_set_option( $ds, LDAP_OPT_PROTOCOL_VERSION, $LDAPVersion );
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
            $republishRequired = false;
            $IsLDAPMain = true;
            $hasOtherNodeType = false;
            $hasLDAPNodeType = false;
            $otherNodeArray = array();
            $LDAPNodeArray = array();
            $newLDAPNodeArray = array();
            $parentNodes =& $contentObject->parentNodes( $currentVersion );;
            foreach(  array_keys( $parentNodes ) as $key )
            {
                $parentNode =& $parentNodes[$key];
                $parentNodeID = $parentNode->attribute( 'node_id' );
                $parentNodeName = $parentNode->attribute( 'name' );
                $nodeAssignment =& eZNodeAssignment::fetch( $contentObject->attribute( 'id' ), $currentVersion, $parentNodeID );
                $isMain = $nodeAssignment->attribute( 'is_main' );
                $remoteID = $nodeAssignment->attribute( 'parent_remote_id' );
                if ( preg_match( "/LDAP/i", $remoteID ) )
                {
                    $LDAPNodeArray[] = array( 'parent_node_name' => $parentNodeName, 'parent_node_id' => $parentNodeID, 'is_main' => $isMain );
                }
                else
                {
                    $otherNodeArray[] = array( 'parent_node_name' => $parentNodeName, 'parent_node_id' => $parentNodeID, 'is_main' => $isMain );
                    $hasOtherNodeType = true;
                    if ( $isMain )
                    {
                        $IsLDAPMain = false;
                    }
                }
            }
            $LDAPUserGroupCount = count( $LDAPNodeArray );
            $groupAttributeCount = $info[0][$LDAPUserGroupAttribute]['count'];

            if ( $LDAPUserGroupAttributeType == "name" )
            {
                for ( $i = 0; $i < $groupAttributeCount; $i++ )
                {
                    if ( $isUtf8Encoding )
                    {
                        $groupName = utf8_decode( $info[0][$LDAPUserGroupAttribute][$i] );
                    }
                    else
                    {
                        $groupName = $info[0][$LDAPUserGroupAttribute][$i];
                    }

                    $exist = false;
                    foreach( $LDAPNodeArray as $LDAPNode )
                    {
                        $existGroupName = $LDAPNode['parent_node_name'];
                        $existGroupID = $LDAPNode['parent_node_id'];
                        if ( strcasecmp( $existGroupName, $groupName )  == 0 )
                        {
                            $exist = true;
                            $hasLDAPNodeType = true;
                            if ( $IsLDAPMain and count( $newLDAPNodeArray ) == 0 )
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $existGroupName, 'parent_node_id' => $existGroupID, 'is_main' => 1 );
                            }
                            else
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $existGroupName, 'parent_node_id' => $existGroupID, 'is_main' => 0 );
                            }
                            $LDAPUserGroupCount--;
                        }
                    }

                    if ( $exist == false )
                    {
                        $groupQuery = "SELECT ezcontentobject_tree.node_id
                                         FROM ezcontentobject, ezcontentobject_tree
                                        WHERE ezcontentobject.name like '$groupName'
                                          AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                          AND ezcontentobject.contentclass_id=3";
                        $groupObject =& $db->arrayQuery( $groupQuery );

                        if ( count( $groupObject ) > 0 )
                        {
                            $hasLDAPNodeType = true;
                            if ( $IsLDAPMain and count( $newLDAPNodeArray ) == 0 )
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $groupName, 'parent_node_id' => $groupObject[0]['node_id'], 'is_main' => 1 );
                            }
                            else
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $groupName, 'parent_node_id' => $groupObject[0]['node_id'], 'is_main' => 0 );
                            }
                            $republishRequired = true;
                        }
                    }
                }

                if ( $LDAPUserGroupCount != 0 )
                {
                    $republishRequired = true;
                }
            }
            else if ( $LDAPUserGroupAttributeType == "id" )
            {
                for ( $i = 0; $i < $groupAttributeCount; $i++ )
                {
                    if ( $isUtf8Encoding )
                    {
                        $groupID = utf8_decode( $info[0][$LDAPUserGroupAttribute][$i] );
                    }
                    else
                    {
                        $groupID = $info[0][$LDAPUserGroupAttribute][$i];
                    }

                    $groupName = "LDAP " . $groupID;

                    $exist = false;
                    foreach( $LDAPNodeArray as $LDAPNode )
                    {
                        $existGroupName = $LDAPNode['parent_node_name'];
                        $existGroupID = $LDAPNode['parent_node_id'];
                        if ( strcasecmp( $existGroupName, $groupName )  == 0 )
                        {
                            $exist = true;
                            $hasLDAPNodeType = true;
                            if ( $IsLDAPMain and count( $newLDAPNodeArray ) == 0 )
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $existGroupName, 'parent_node_id' => $existGroupID, 'is_main' => 1 );
                            }
                            else
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $existGroupName, 'parent_node_id' => $existGroupID, 'is_main' => 0 );
                            }
                            $LDAPUserGroupCount--;
                        }
                    }

                    if ( $exist == false )
                    {
                        $groupQuery = "SELECT ezcontentobject_tree.node_id
                                         FROM ezcontentobject, ezcontentobject_tree
                                        WHERE ezcontentobject.name like '$groupName'
                                          AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                          AND ezcontentobject.contentclass_id=3";
                        $groupObject =& $db->arrayQuery( $groupQuery );

                        if ( count( $groupObject ) > 0 )
                        {
                            $hasLDAPNodeType = true;
                            if ( $IsLDAPMain and count( $newLDAPNodeArray ) == 0 )
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $groupName, 'parent_node_id' => $groupObject[0]['node_id'], 'is_main' => 1 );
                            }
                            else
                            {
                                $newLDAPNodeArray[] = array( 'parent_node_name' => $groupName, 'parent_node_id' => $groupObject[0]['node_id'], 'is_main' => 0 );
                            }
                            $republishRequired = true;
                        }
                    }
                }

                if ( $LDAPUserGroupCount != 0 )
                {
                    $republishRequired = true;
                }
            }
            if ( $republishRequired )
            {
                $newVersion =& $contentObject->createNewVersion();
                $newVersionNr = $newVersion->attribute( 'version' );
                $nodeAssignmentList =& $newVersion->attribute( 'node_assignments' );
                foreach ( array_keys( $nodeAssignmentList ) as $key  )
                {
                    $nodeAssignment =& $nodeAssignmentList[$key];
                    $nodeAssignment->remove();
                }

                if ( $hasOtherNodeType )
                {
                    foreach ( $otherNodeArray as $otherNode )
                    {
                        $newVersion->assignToNode( $otherNode['parent_node_id'], $otherNode['is_main'] );
                    }
                }

                if ( $hasLDAPNodeType )
                {
                    foreach ( $newLDAPNodeArray as $newLDAPNode )
                    {
                        $newVersion->assignToNode( $newLDAPNode['parent_node_id'], $newLDAPNode['is_main'] );
                        $assignment =& eZNodeAssignment::fetch( $contentObject->attribute( 'id' ), $newVersionNr, $newLDAPNode['parent_node_id'] );
                        $assignment->setAttribute( 'parent_remote_id', "LDAP_" . $newLDAPNode['parent_node_id'] );
                        $assignment->store();
                    }
                }

                if ( !$hasOtherNodeType and !$hasLDAPNodeType )
                {
                    $newVersion->assignToNode( $defaultUserPlacement, 1 );
                }
                include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                             'version' => $newVersionNr ) );
                $cli->output( $cli->stylize( 'emphasize', $existUser->attribute('login') ) . " has changed group, updated." );
            }
        }
    }
}

if ( !$isQuiet )
    $cli->output( "All LDAP users have been updated!" );
?>
