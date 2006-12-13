<?php
//
// Definition of eZLDAPUser class
//
// Created on: <24-Jul-2003 15:48:06 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*! \file ezldapuser.php
*/

/*!
  \class eZLDAPUser ezldapuser.php
  \ingroup eZDatatype
  \brief The class eZLDAPUser does

*/
include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZLDAPUser extends eZUser
{
    /*!
     Constructor
    */
    function eZLDAPUser()
    {
    }

    /*!
    \static
     Logs in the user if applied username and password is
     valid. The userID is returned if succesful, false if not.
    */
    function &loginUser( $login, $password, $authenticationMatch = false )
    {
        $http =& eZHTTPTool::instance();
        $db =& eZDB::instance();

        if ( $authenticationMatch === false )
            $authenticationMatch = eZUser::authenticationMatch();

        $loginEscaped = $db->escapeString( $login );
        $passwordEscaped = $db->escapeString( $password );

        $loginArray = array();
        if ( $authenticationMatch & EZ_USER_AUTHENTICATE_LOGIN )
            $loginArray[] = "login='$loginEscaped'";
        if ( $authenticationMatch & EZ_USER_AUTHENTICATE_EMAIL )
            $loginArray[] = "email='$loginEscaped'";
        if ( count( $loginArray ) == 0 )
            $loginArray[] = "login='$loginEscaped'";
        $loginText = implode( ' OR ', $loginArray );

        $contentObjectStatus = EZ_CONTENT_OBJECT_STATUS_PUBLISHED;

        $ini =& eZINI::instance();
        $LDAPIni =& eZINI::instance( 'ldap.ini' );
        $databaseImplementation = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );
        // if mysql
        if ( $databaseImplementation == "ezmysql" )
        {
            $query = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                      FROM ezuser, ezcontentobject
                      WHERE ( $loginText ) AND
                        ezcontentobject.status='$contentObjectStatus' AND
                        ( ezcontentobject.id=contentobject_id OR ( password_hash_type=4 AND ( $loginText ) AND password_hash=PASSWORD('$passwordEscaped') ) )";
        }
        else
        {
            $query = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                      FROM ezuser, ezcontentobject
                      WHERE ( $loginText ) AND
                            ezcontentobject.status='$contentObjectStatus' AND
                            ezcontentobject.id=contentobject_id";
        }

        $users = $db->arrayQuery( $query );
        $exists = false;
        if ( count( $users ) >= 1 )
        {
            foreach ( array_keys( $users ) as $key )
            {
                $userRow =& $users[$key];
                $userID = $userRow['contentobject_id'];
                $hashType = $userRow['password_hash_type'];
                $hash = $userRow['password_hash'];
                $exists = eZUser::authenticateHash( $userRow['login'], $password, eZUser::site(),
                                                    $hashType,
                                                    $hash );

                // If hash type is MySql
                if ( $hashType == EZ_USER_PASSWORD_HASH_MYSQL and $databaseImplementation == "ezmysql" )
                {
                    $queryMysqlUser = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                              FROM ezuser, ezcontentobject
                              WHERE ezcontentobject.status='$contentObjectStatus' AND
                                    password_hash_type=4 AND ( $loginText ) AND password_hash=PASSWORD('$passwordEscaped') ";
                    $mysqlUsers = $db->arrayQuery( $queryMysqlUser );
                    if ( count( $mysqlUsers ) >= 1 )
                        $exists = true;
                }

                eZDebugSetting::writeDebug( 'kernel-user', eZUser::createHash( $userRow['login'], $password, eZUser::site(),
                                                                               $hashType ), "check hash" );
                eZDebugSetting::writeDebug( 'kernel-user', $hash, "stored hash" );
                if ( $exists )
                {
                    $userSetting = eZUserSetting::fetch( $userID );
                    $isEnabled = $userSetting->attribute( "is_enabled" );
                    if ( $hashType != eZUser::hashType() and
                         strtolower( $ini->variable( 'UserSettings', 'UpdateHash' ) ) == 'true' )
                    {
                        $hashType = eZUser::hashType();
                        $hash = eZUser::createHash( $login, $password, eZUser::site(),
                                                    $hashType );
                        $db->query( "UPDATE ezuser SET password_hash='$hash', password_hash_type='$hashType' WHERE contentobject_id='$userID'" );
                    }
                    break;
                }
            }
        }
        if ( $exists and $isEnabled )
        {
            eZDebugSetting::writeDebug( 'kernel-user', $userRow, 'user row' );
            $user = new eZUser( $userRow );
            eZDebugSetting::writeDebug( 'kernel-user', $user, 'user' );
            $userID = $user->attribute( 'contentobject_id' );

            eZUser::updateLastVisit( $userID );
            eZUser::setCurrentlyLoggedInUser( $user, $userID );

            return $user;
        }
        else if ( $LDAPIni->variable( 'LDAPSettings', 'LDAPEnabled' ) == "true" )
        {
            $createNewUser = true;
            $extraNodeAssignments = array();
            $existUser = $this->fetchByName( $login );
            if ( $existUser != null )
            {
                $createNewUser = false;
            }

            $LDAPVersion = $LDAPIni->variable( 'LDAPSettings', 'LDAPVersion' );
            $LDAPHost = $LDAPIni->variable( 'LDAPSettings', 'LDAPServer' );
            $LDAPPort = $LDAPIni->variable( 'LDAPSettings', 'LDAPPort' );
            $LDAPBaseDN = $LDAPIni->variable( 'LDAPSettings', 'LDAPBaseDn' );
            $LDAPBindUser = $LDAPIni->variable( 'LDAPSettings', 'LDAPBindUser' );
            $LDAPBindPassword = $LDAPIni->variable( 'LDAPSettings', 'LDAPBindPassword' );
            $LDAPLogin = $LDAPIni->variable( 'LDAPSettings', 'LDAPLoginAttribute' );
            $LDAPSearchScope = $LDAPIni->variable( 'LDAPSettings', 'LDAPSearchScope' );
            $LDAPFirstNameAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPFirstNameAttribute' );
            $LDAPLastNameAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPLastNameAttribute' );
            $LDAPEmailAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPEmailAttribute' );
            $defaultUserPlacement = $ini->variable( "UserSettings", "DefaultUserPlacement" );
            $LDAPUserGroupAttributeType = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupAttributeType' );
            $LDAPUserGroupAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupAttribute' );

            if ( $LDAPIni->hasVariable( 'LDAPSettings', 'Utf8Encoding' ) )
            {
                $Utf8Encoding = $LDAPIni->variable( 'LDAPSettings', 'Utf8Encoding' );
                if ( $Utf8Encoding == "true" )
                    $isUtf8Encoding = true;
                else
                    $isUtf8Encoding = false;
            }
            else
            {
                $isUtf8Encoding = false;
            }

            if ( $LDAPIni->hasVariable( 'LDAPSettings', 'LDAPSearchFilters' ) )
            {
                $LDAPFilters = $LDAPIni->variable( 'LDAPSettings', 'LDAPSearchFilters' );
            }
            if ( $LDAPIni->hasVariable( 'LDAPSettings', 'LDAPUserGroupType' ) and  $LDAPIni->hasVariable( 'LDAPSettings', 'LDAPUserGroup' ) )
            {
                $LDAPUserGroupType = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupType' );
                $LDAPUserGroup = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroup' );
            }

            $LDAPFilter = "( &";
            if ( count( $LDAPFilters ) > 0 )
            {
                foreach ( array_keys( $LDAPFilters ) as $key )
                {
                    $LDAPFilter .= "(" . $LDAPFilters[$key] . ")";
                }
            }
            $LDAPEqualSign = trim($LDAPIni->variable( 'LDAPSettings', "LDAPEqualSign" ) );
            $LDAPBaseDN = str_replace( $LDAPEqualSign, "=", $LDAPBaseDN );
            $LDAPFilter = str_replace( $LDAPEqualSign, "=", $LDAPFilter );

            $ds = ldap_connect( $LDAPHost, $LDAPPort );

            if ( $ds )
            {
                ldap_set_option( $ds, LDAP_OPT_PROTOCOL_VERSION, $LDAPVersion );
                if ( $LDAPBindUser == '' )
                {
                    $r = ldap_bind( $ds );
                }
                else
                {
                    $r = ldap_bind( $ds, $LDAPBindUser, $LDAPBindPassword );
                }
                if ( !$r )
                {
                    $user = false;
                    return $user;
                }

                $LDAPFilter .= "($LDAPLogin=$login)";
                $LDAPFilter .= ")";

                ldap_set_option( $ds, LDAP_OPT_SIZELIMIT, 0 );
                ldap_set_option( $ds, LDAP_OPT_TIMELIMIT, 0 );

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

                if ( $LDAPSearchScope == "one" )
                    $sr = ldap_list( $ds, $LDAPBaseDN, $LDAPFilter, $attributeArray );
                else if ( $LDAPSearchScope == "base" )
                    $sr = ldap_read( $ds, $LDAPBaseDN, $LDAPFilter, $attributeArray );
                else
                    $sr = ldap_search( $ds, $LDAPBaseDN, $LDAPFilter, $attributeArray );
                $info = ldap_get_entries( $ds, $sr ) ;
                if ( $info["count"] > 1 )
                {
                    // More than one user with same uid, not allow login.
                    $user = false;
                    return $user;
                }
                else if ( $info["count"] < 1 )
                {
                    // user DN was not found
                    $user = false;
                    return $user;
                }

                if( !$password )
                {
                    $password = crypt( microtime() );
                }

                // authenticated user
                if  ( !@ldap_bind( $ds, $info[0]['dn'], $password ) )
                {
                    $user = false;
                    return $user;
                }

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
                                $groupObject = $db->arrayQuery( $groupQuery );
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
                            $groupObject = $db->arrayQuery( $groupQuery );

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
                                $groupObject = $db->arrayQuery( $groupQuery );
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
                            $groupObject = $db->arrayQuery( $groupQuery );

                            if ( count( $groupObject ) > 0  )
                            {
                                $defaultUserPlacement = $groupObject[0]['node_id'];
                            }
                        }
                    }
                }

                if ( $LDAPUserGroupAttributeType != null )
                {
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
                            if ( $groupName != null )
                            {
                                $groupQuery = "SELECT ezcontentobject_tree.node_id
                                                 FROM ezcontentobject, ezcontentobject_tree
                                                WHERE ezcontentobject.name like '$groupName'
                                                  AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                                  AND ezcontentobject.contentclass_id=3";
                                $groupObject = $db->arrayQuery( $groupQuery );

                                if ( count( $groupObject ) > 0 and $i == 0 )
                                {
                                    $defaultUserPlacement = $groupObject[0]['node_id'];
                                }
                                else if ( count( $groupObject ) > 0 )
                                {
                                    $extraNodeAssignments[] = $groupObject[0]['node_id'];
                                }
                            }
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

                            if ( $groupID != null )
                            {
                                $groupName = "LDAP " . $groupID;
                                $groupQuery = "SELECT ezcontentobject_tree.node_id
                                                 FROM ezcontentobject, ezcontentobject_tree
                                                WHERE ezcontentobject.name like '$groupName'
                                                  AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                                  AND ezcontentobject.contentclass_id=3";
                                $groupObject = $db->arrayQuery( $groupQuery );

                                if ( count( $groupObject ) > 0 and $i == 0 )
                                {
                                    $defaultUserPlacement = $groupObject[0]['node_id'];
                                }
                                else if ( count( $groupObject ) > 0 )
                                {
                                    $extraNodeAssignments[] = $groupObject[0]['node_id'];
                                }
                            }
                        }
                    }
                }

                if ( $createNewUser )
                {
                    $userClassID = $ini->variable( "UserSettings", "UserClassID" );
                    $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
                    $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );

                    $class = eZContentClass::fetch( $userClassID );
                    $contentObject = $class->instantiate( $userCreatorID, $defaultSectionID );

                    $remoteID = "LDAP_" . $login;
                    $contentObject->setAttribute( 'remote_id', $remoteID );
                    $contentObject->store();

                    $contentObjectID = $contentObject->attribute( 'id' );
                    $userID = $contentObjectID;
                    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                                       'contentobject_version' => 1,
                                                                       'parent_node' => $defaultUserPlacement,
                                                                       'is_main' => 1 ) );
                    $nodeAssignment->setAttribute( 'parent_remote_id', "LDAP_" . $defaultUserPlacement );
                    $nodeAssignment->store();

                    if ( $extraNodeAssignments != null )
                    {
                        foreach( $extraNodeAssignments as $extraNodeAssignment )
                        {
                            $newNodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                                                  'contentobject_version' => 1,
                                                                                  'parent_node' => $extraNodeAssignment,
                                                                                  'is_main' => 0 ) );
                            $newNodeAssignment->setAttribute( 'parent_remote_id', "LDAP_" . $extraNodeAssignment );
                            $newNodeAssignment->store();
                        }
                    }

                    $version =& $contentObject->version( 1 );
                    $version->setAttribute( 'modified', time() );
                    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
                    $version->store();

                    $contentObjectID = $contentObject->attribute( 'id' );
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

                    $user = $this->create( $userID );
                    $user->setAttribute('login', $login );
                    $user->setAttribute('email', $ldapEMail );
                    $user->setAttribute('password_hash', "" );
                    $user->setAttribute('password_hash_type', 0 );
                    $user->store();

                    eZUser::updateLastVisit( $userID );
                    eZUser::setCurrentlyLoggedInUser( $user, $userID );

                    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                                 'version' => 1 ) );
                    return $user;
                }
                else
                {
                    // Update user information
                    $userID = $contentObjectID = $existUser->attribute( 'contentobject_id' );
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

                    $contentObjectAttributes[0]->setAttribute( 'data_text',  $firstName );
                    $contentObjectAttributes[0]->store();

                    $contentObjectAttributes[1]->setAttribute( 'data_text', $lastName );
                    $contentObjectAttributes[1]->store();

                    $contentClass =& $contentObject->attribute( 'content_class' );
                    $name = $contentClass->contentObjectName( $contentObject );
                    $contentObject->setName( $name );

                    $existUser = eZUser::fetch(  $userID );
                    $existUser->setAttribute('email', $ldapEMail );
                    $existUser->setAttribute('password_hash', "" );
                    $existUser->setAttribute('password_hash_type', 0 );
                    $existUser->store();

                    $keepGroupAssignment = ( $LDAPIni->hasVariable( 'LDAPSettings', 'KeepGroupAssignment' ) ) ?
                                                ( $LDAPIni->variable( 'LDAPSettings', 'KeepGroupAssignment' ) == "enabled" ) : false;

                    if ( $keepGroupAssignment == false )
                    {
                        if ( $defaultUserPlacement != $parentNodeID )
                        {
                            // Check: is there user has location (not main) in default placement
                            $nodeAssignmentList =& $version->nodeAssignments();
                            $isAssignmentExist = false;
                            foreach ( array_keys( $nodeAssignmentList ) as $nodeAssignmentKey )
                            {
                                $nodeAssignment =& $nodeAssignmentList[$nodeAssignmentKey];
                                if ( $defaultUserPlacement == $nodeAssignment->attribute( 'parent_node' ) )
                                {
                                    $isAssignmentExist = true;
                                    break;
                                }
                            }

                            if ( $isAssignmentExist )
                            {
                                // make existing node as main
                                $existingNode = eZContentObjectTreeNode::fetchNode( $contentObjectID, $defaultUserPlacement );
                                if ( !is_object( $existingNode ) )
                                {
                                    eZDebug::writeError( "Cannot find assigned node as $defaultUserPlacement's child.",
                                                         'kernel/classes/datatypes/ezuser/ezldapuser' );
                                }
                                else
                                {
                                    $existingNodeID = $existingNode->attribute( 'node_id' );
                                    $versionNum = $version->attribute( 'version' );
                                    eZContentObjectTreeNode::updateMainNodeID( $existingNodeID, $contentObjectID, $versionNum, $defaultUserPlacement );
                                }
                            }
                            else
                            {
                                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                                $adminUser = eZUser::fetchByName( 'admin' );
                                eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUser->attribute( 'contentobject_id' ) );

                                $mainNodeID = $contentObject->attribute( 'main_node_id' );
                                $mainNode = eZContentObjectTreeNode::fetch( $mainNodeID );

                                if ( !$mainNode->canMoveFrom() )
                                {
                                    eZDebug::writeError( "Cannot move node $mainNodeID.",
                                                         'kernel/classes/datatypes/ezuser/ezldapuser' );
                                }

                                $newParentNode = eZContentObjectTreeNode::fetch( $defaultUserPlacement );

                                // Check if we try to move the node as child of itself or one of its children
                                if ( in_array( $mainNodeID, $newParentNode->pathArray() ) )
                                {
                                    eZDebug::writeError( "Cannot move node $mainNodeID as child of itself or one of its own children (node $defaultUserPlacement).",
                                                         'kernel/classes/datatypes/ezuser/ezldapuser' );
                                }
                                else
                                {
                                    include_once( 'kernel/classes/ezcontentobjecttreenodeoperations.php' );
                                    if( !eZContentObjectTreeNodeOperations::move( $mainNodeID, $defaultUserPlacement ) )
                                    {
                                        eZDebug::writeError( "Failed to move node $mainNodeID as child of parent node $defaultUserPlacement",
                                                             'kernel/classes/datatypes/ezuser/ezldapuser' );
                                    }
                                }
                            }
                        }
                    }

                    eZUser::updateLastVisit( $userID );
                    eZUser::setCurrentlyLoggedInUser( $existUser, $userID );

                    return $existUser;
                }
                ldap_close( $ds );
            }
        }
        else
        {
            $user = false;
            return $user;
        }
    }
}

?>
