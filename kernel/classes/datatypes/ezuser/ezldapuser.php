<?php
//
// Definition of eZLDAPUser class
//
// Created on: <24-Jul-2003 15:48:06 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
//include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//include_once( 'lib/ezutils/classes/ezini.php' );

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
    static function loginUser( $login, $password, $authenticationMatch = false )
    {
        $http = eZHTTPTool::instance();
        $db = eZDB::instance();

        if ( $authenticationMatch === false )
            $authenticationMatch = eZUser::authenticationMatch();

        $loginEscaped = $db->escapeString( $login );
        $passwordEscaped = $db->escapeString( $password );

        $loginArray = array();
        if ( $authenticationMatch & eZUser::AUTHENTICATE_LOGIN )
            $loginArray[] = "login='$loginEscaped'";
        if ( $authenticationMatch & eZUser::AUTHENTICATE_EMAIL )
            $loginArray[] = "email='$loginEscaped'";
        if ( count( $loginArray ) == 0 )
            $loginArray[] = "login='$loginEscaped'";
        $loginText = implode( ' OR ', $loginArray );

        $contentObjectStatus = eZContentObject::STATUS_PUBLISHED;

        $ini = eZINI::instance();
        $LDAPIni = eZINI::instance( 'ldap.ini' );
        $databaseName = $db->databaseName();
        // if mysql
        if ( $databaseName === 'mysql' )
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
            foreach ( $users as $userRow )
            {
                $userID = $userRow['contentobject_id'];
                $hashType = $userRow['password_hash_type'];
                $hash = $userRow['password_hash'];
                $exists = eZUser::authenticateHash( $userRow['login'], $password, eZUser::site(),
                                                    $hashType,
                                                    $hash );

                // If hash type is MySql
                if ( $hashType == eZUser::PASSWORD_HASH_MYSQL and $databaseName === 'mysql' )
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
                 // If current user has been disabled after a few failed login attempts.
                $canLogin = eZUser::isEnabledAfterFailedLogin( $userID );

                if ( $exists )
                {
                    // We should store userID for warning message.
                    $GLOBALS['eZFailedLoginAttemptUserID'] = $userID;

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
        if ( $exists and $isEnabled and $canLogin )
        {
            eZDebugSetting::writeDebug( 'kernel-user', $userRow, 'user row' );
            $user = new eZUser( $userRow );
            eZDebugSetting::writeDebug( 'kernel-user', $user, 'user' );
            $userID = $user->attribute( 'contentobject_id' );

            eZUser::updateLastVisit( $userID );
            eZUser::setCurrentlyLoggedInUser( $user, $userID );

            // Reset number of failed login attempts
            eZUser::setFailedLoginAttempts( $userID, 0 );

            return $user;
        }
        else if ( $LDAPIni->variable( 'LDAPSettings', 'LDAPEnabled' ) == "true" )
        {
            // read LDAP ini settings
            // and then try to bind to the ldap server

            $LDAPVersion    = $LDAPIni->variable( 'LDAPSettings', 'LDAPVersion' );
            $LDAPServer     = $LDAPIni->variable( 'LDAPSettings', 'LDAPServer' );
            $LDAPPort       = $LDAPIni->variable( 'LDAPSettings', 'LDAPPort' );
            $LDAPBaseDN     = $LDAPIni->variable( 'LDAPSettings', 'LDAPBaseDn' );
            $LDAPBindUser   = $LDAPIni->variable( 'LDAPSettings', 'LDAPBindUser' );
            $LDAPBindPassword       = $LDAPIni->variable( 'LDAPSettings', 'LDAPBindPassword' );
            $LDAPSearchScope        = $LDAPIni->variable( 'LDAPSettings', 'LDAPSearchScope' );

            $LDAPLoginAttribute     = $LDAPIni->variable( 'LDAPSettings', 'LDAPLoginAttribute' );
            $LDAPFirstNameAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPFirstNameAttribute' );
            $LDAPLastNameAttribute  = $LDAPIni->variable( 'LDAPSettings', 'LDAPLastNameAttribute' );
            $LDAPEmailAttribute     = $LDAPIni->variable( 'LDAPSettings', 'LDAPEmailAttribute' );

            $defaultUserPlacement   = $ini->variable( "UserSettings", "DefaultUserPlacement" );

            $LDAPUserGroupAttributeType = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupAttributeType' );
            $LDAPUserGroupAttribute     = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupAttribute' );

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

            $ds = ldap_connect( $LDAPServer, $LDAPPort );

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
                    // Increase number of failed login attempts.
                    if ( isset( $userID ) )
                        eZUser::setFailedLoginAttempts( $userID );

                    $user = false;
                    return $user;
                }

                $LDAPFilter .= "($LDAPLoginAttribute=$login)";
                $LDAPFilter .= ")";

                ldap_set_option( $ds, LDAP_OPT_SIZELIMIT, 0 );
                ldap_set_option( $ds, LDAP_OPT_TIMELIMIT, 0 );

                $retrieveAttributes = array( $LDAPLoginAttribute,
                                             $LDAPFirstNameAttribute,
                                             $LDAPLastNameAttribute,
                                             $LDAPEmailAttribute );
                if ( $LDAPUserGroupAttributeType )
                    $retrieveAttributes[] = $LDAPUserGroupAttribute;

                if ( $LDAPSearchScope == "one" )
                    $sr = ldap_list( $ds, $LDAPBaseDN, $LDAPFilter, $retrieveAttributes );
                else if ( $LDAPSearchScope == "base" )
                    $sr = ldap_read( $ds, $LDAPBaseDN, $LDAPFilter, $retrieveAttributes );
                else
                    $sr = ldap_search( $ds, $LDAPBaseDN, $LDAPFilter, $retrieveAttributes );

                $info = ldap_get_entries( $ds, $sr ) ;
                if ( $info['count'] > 1 )
                {
                    // More than one user with same uid, not allow login.
                    $user = false;
                    return $user;
                }
                else if ( $info['count'] < 1 )
                {
                    // Increase number of failed login attempts.
                    if ( isset( $userID ) )
                        eZUser::setFailedLoginAttempts( $userID );

                    // user DN was not found
                    $user = false;
                    return $user;
                }

                if( !$password )
                {
                    $password = crypt( microtime() );
                }

                // is it real authenticated LDAP user?
                if  ( !@ldap_bind( $ds, $info[0]['dn'], $password ) )
                {
                    // Increase number of failed login attempts.
                    if ( isset( $userID ) )
                        eZUser::setFailedLoginAttempts( $userID );

                    $user = false;
                    return $user;
                }

                $extraNodeAssignments = array();
                $userGroupClassID = $ini->variable( "UserSettings", "UserGroupClassID" );

                // default user group assigning
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
                                                  AND ezcontentobject.contentclass_id=$userGroupClassID";
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
                                              AND ezcontentobject.contentclass_id=$userGroupClassID";
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
                                                  AND ezcontentobject.contentclass_id=$userGroupClassID";
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
                                              AND ezcontentobject.contentclass_id=$userGroupClassID";
                            $groupObject = $db->arrayQuery( $groupQuery );

                            if ( count( $groupObject ) > 0  )
                            {
                                $defaultUserPlacement = $groupObject[0]['node_id'];
                            }
                        }
                    }
                }

                $adminUser = eZUser::fetchByName( 'admin' );
                $adminUserContentObjectID = $adminUser->attribute( 'contentobject_id' );

                // read group mapping LDAP settings
                $LDAPGroupMappingType = $LDAPIni->variable( 'LDAPSettings', 'LDAPGroupMappingType' );
                $LDAPUserGroupMap     = $LDAPIni->variable( 'LDAPSettings', 'LDAPUserGroupMap' );

                if ( !is_array( $LDAPUserGroupMap ) )
                    $LDAPUserGroupMap = array();

                // group mapping constants
                $ByMemberAttribute             = 'SimpleMapping'; // by group's member attributes (with mapping)
                $ByMemberAttributeHierarhicaly = 'GetGroupsTree'; // by group's member attributes hierarhically
                $ByGroupAttribute              = 'UseGroupAttribute'; // by user's group attribute (old style)
                $groupMappingTypes = array( $ByMemberAttribute,
                                            $ByMemberAttributeHierarhicaly,
                                            $ByGroupAttribute);

                $userData =& $info[ 0 ];

                // default mapping using old style
                if ( !in_array( $LDAPGroupMappingType, $groupMappingTypes ) )
                {
                    $LDAPGroupMappingType = $ByGroupAttribute;
                }

                if ( $LDAPGroupMappingType == $ByMemberAttribute or
                     $LDAPGroupMappingType == $ByMemberAttributeHierarhicaly )
                {
                    $LDAPGroupBaseDN          = $LDAPIni->variable( 'LDAPSettings', 'LDAPGroupBaseDN' );
                    $LDAPGroupClass           = $LDAPIni->variable( 'LDAPSettings', 'LDAPGroupClass' );

                    $LDAPGroupNameAttribute   = $LDAPIni->variable( 'LDAPSettings', 'LDAPGroupNameAttribute' );
                    $LDAPGroupMemberAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPGroupMemberAttribute' );
                    $LDAPGroupDescriptionAttribute = $LDAPIni->variable( 'LDAPSettings', 'LDAPGroupDescriptionAttribute' );

                    $groupSearchingDepth = ( $LDAPGroupMappingType == '1' ) ? 1 : 1000;

                    // now, get all parents for currently ldap authenticated user
                    $requiredParams = array();
                    $requiredParams[ 'LDAPLoginAttribute' ]       = $LDAPLoginAttribute;
                    $requiredParams[ 'LDAPGroupBaseDN' ]          = $LDAPGroupBaseDN;
                    $requiredParams[ 'LDAPGroupClass' ]           = $LDAPGroupClass;
                    $requiredParams[ 'LDAPGroupNameAttribute' ]   = $LDAPGroupNameAttribute;
                    $requiredParams[ 'LDAPGroupMemberAttribute' ] = $LDAPGroupMemberAttribute;
                    $requiredParams[ 'LDAPGroupDescriptionAttribute' ] = $LDAPGroupDescriptionAttribute;
                    $requiredParams[ 'ds' ] =& $ds;
                    $requiredParams[ 'TopUserGroupNodeID' ] = 5;

                    $groupsTree = array();
                    $stack = array();
                    $newfilter = '(&(objectClass=' . $LDAPGroupClass . ')(' . $LDAPGroupMemberAttribute . '=' . $userData['dn'] . '))';

                    $groupsTree[ $userData['dn'] ] = array( 'data' => & $userData,
                                                                'parents' => array(),
                                                                'children' => array() );

                    eZLDAPUser::getUserGroupsTree( $requiredParams, $newfilter, $userData['dn'], $groupsTree, $stack, $groupSearchingDepth );
                    $userRecord =& $groupsTree[ $userData['dn'] ];

                    if ( $LDAPGroupMappingType == $ByMemberAttribute )
                    {
                        if ( count( $userRecord[ 'parents' ] ) > 0 )
                        {
                            $remappedGroupNames = array();
                            foreach ( array_keys( $userRecord[ 'parents' ] ) as $key )
                            {
                                $parentGroup =& $userRecord[ 'parents' ][ $key ];
                                if ( isset( $parentGroup[ 'data' ][ $LDAPGroupNameAttribute ] ) )
                                {
                                    $ldapGroupName = $parentGroup[ 'data' ][ $LDAPGroupNameAttribute ];
                                    if ( is_array( $ldapGroupName ) )
                                    {
                                        $ldapGroupName = ( $ldapGroupName[ 'count' ] > 0 ) ? $ldapGroupName[ 0 ] : '';
                                    }

                                    // remap group name and check that group exists
                                    if ( array_key_exists( $ldapGroupName, $LDAPUserGroupMap ) )
                                    {
                                        $remmapedGroupName = $LDAPUserGroupMap[ $ldapGroupName ];
                                        $groupQuery = "SELECT ezcontentobject_tree.node_id
                                                         FROM ezcontentobject, ezcontentobject_tree
                                                        WHERE ezcontentobject.name like '$remmapedGroupName'
                                                          AND ezcontentobject.id=ezcontentobject_tree.contentobject_id
                                                          AND ezcontentobject.contentclass_id=$userGroupClassID";
                                        $groupRow = $db->arrayQuery( $groupQuery );

                                        if ( count( $groupRow ) > 0 )
                                        {
                                            $userRecord['new_parents'][] = $groupRow[ 0 ][ 'node_id' ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else if ( $LDAPGroupMappingType == $ByMemberAttributeHierarhicaly )
                    {
                        eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUserContentObjectID );

                        $stack = array();
                        self::goAndPublishGroups( $requiredParams, $userData['dn'], $groupsTree, $stack, $groupSearchingDepth, true );
                    }
                    if ( isset( $userRecord['new_parents'] ) and
                         count( $userRecord['new_parents'] ) > 0 )
                    {
                        $defaultUserPlacement = $userRecord['new_parents'][0];
                        $extraNodeAssignments = array_merge( $extraNodeAssignments, $userRecord['new_parents'] );
                    }
                }
                else if ( $LDAPGroupMappingType == $ByGroupAttribute ) // old style mapping by group (employeetype) attribute
                {
                    if ( $LDAPUserGroupAttributeType )
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
                                                      AND ezcontentobject.contentclass_id=$userGroupClassID";
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
                                                      AND ezcontentobject.contentclass_id=$userGroupClassID";
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
                }

                $userAttributes = array( 'login'      => $login,
                                         'first_name' => isset( $userData[ $LDAPFirstNameAttribute ] ) ? $userData[ $LDAPFirstNameAttribute ][0] : false,
                                         'last_name'  => isset( $userData[ $LDAPLastNameAttribute ] ) ? $userData[ $LDAPLastNameAttribute ][0] : false,
                                         'email'      => isset( $userData[ $LDAPEmailAttribute ] ) ? $userData[ $LDAPEmailAttribute ][0] : false );

                $oldUser = clone eZUser::currentUser();
                eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUserContentObjectID );
                $existingUser = eZLDAPUser::publishUpdateUser( $extraNodeAssignments, $defaultUserPlacement, $userAttributes, $isUtf8Encoding );

                if ( is_object( $existingUser ) )
                {
                    eZUser::setCurrentlyLoggedInUser( $existingUser, $existingUser->attribute( 'contentobject_id' ) );
                }
                else
                {
                    eZUser::setCurrentlyLoggedInUser( $oldUser, $oldUser->attribute( 'contentobject_id' ) );
                }

                ldap_close( $ds );
                return $existingUser;
            }
            else
            {
                eZDebug::writeError( 'Cannot initialize connection for LDAP server', 'eZLDAPUser::loginUser()' );
                $user = false;
                return $user;
            }
        }
        else
        {
            // Increase number of failed login attempts.
            if ( isset( $userID ) )
                eZUser::setFailedLoginAttempts( $userID );

            $user = false;
            return $user;
        }
    }

    /*
        Static method, for internal usage only.
        Publishes new or update existing user
    */
    static function publishUpdateUser( $parentNodeIDs, $defaultUserPlacement, $userAttributes, $isUtf8Encoding = false )
    {
        $thisFunctionErrorLabel = 'eZLDAPUser.php, function publishUpdateUser()';

        if ( !is_array( $userAttributes ) or
             !isset( $userAttributes[ 'login' ] ) or empty( $userAttributes[ 'login' ] ) )
        {
            eZDebug::writeWarning( 'Empty user login passed.',
                                   $thisFunctionErrorLabel );
            return false;
        }

        if ( ( !is_array( $parentNodeIDs ) or count( $parentNodeIDs ) < 1 ) and
             !is_numeric( $defaultUserPlacement ) )
        {
            eZDebug::writeWarning( 'No one parent node IDs was passed for publishing new user (login = "' .
                                   $userAttributes[ 'login' ] . '")',
                                   $thisFunctionErrorLabel );
            return false;
        }
        $parentNodeIDs[] = $defaultUserPlacement;
        $parentNodeIDs = array_unique( $parentNodeIDs );


        $login      = $userAttributes[ 'login' ];
        $first_name = $userAttributes[ 'first_name' ];
        $last_name  = $userAttributes[ 'last_name' ];
        $email      = $userAttributes[ 'email' ];

        $user = eZUser::fetchByName( $login );
        $createNewUser = ( is_object( $user ) ) ? false : true;

        if ( $createNewUser )
        {
            if ( !isset( $first_name ) or empty( $first_name ) or
                 !isset( $last_name ) or empty( $last_name ) or
                 !isset( $email ) or empty( $email ) )
            {
                eZDebug::writeWarning( 'Cannot create user with empty first name (last name or email).',
                                       $thisFunctionErrorLabel );
                return false;
            }

            $ini = eZINI::instance();
            $userClassID = $ini->variable( "UserSettings", "UserClassID" );
            $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
            $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );

            $class = eZContentClass::fetch( $userClassID );
            $contentObject = $class->instantiate( $userCreatorID, $defaultSectionID );

            $remoteID = "LDAP_" . $login;
            $contentObject->setAttribute( 'remote_id', $remoteID );
            $contentObject->store();

            $userID = $contentObjectID = $contentObject->attribute( 'id' );

            $version = $contentObject->version( 1 );
            $version->setAttribute( 'modified', time() );
            $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
            $version->store();

            $user = eZLDAPUser::create( $userID );
            $user->setAttribute( 'login', $login );
        }
        else
        {
            $userID = $contentObjectID = $user->attribute( 'contentobject_id' );
            $contentObject = eZContentObject::fetch( $userID );
            $version = $contentObject->attribute( 'current' );
            //$currentVersion = $contentObject->attribute( 'current_version' );
        }

        //================= common part : start ========================
        $contentObjectAttributes = $version->contentObjectAttributes();

        // find ant set 'name' and 'description' attributes (as standard user group class)
        $firstNameIdentifier = 'first_name';
        $lastNameIdentifier = 'last_name';
        $firstNameAttribute = null;
        $lastNameAttribute = null;

        foreach( $contentObjectAttributes as $attribute )
        {
            if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == $firstNameIdentifier )
            {
                $firstNameAttribute = $attribute;
            }
            else if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == $lastNameIdentifier )
            {
                $lastNameAttribute = $attribute;
            }
        }
        if ( $firstNameAttribute )
        {
            if ( $isUtf8Encoding )
                $first_name = utf8_decode( $first_name );
            $firstNameAttribute->setAttribute( 'data_text', $first_name );
            $firstNameAttribute->store();
        }
        if ( $lastNameAttribute )
        {
            if ( $isUtf8Encoding )
                $last_name = utf8_decode( $last_name );
            $lastNameAttribute->setAttribute( 'data_text', $last_name );
            $lastNameAttribute->store();
        }

        $contentClass = $contentObject->attribute( 'content_class' );
        $name = $contentClass->contentObjectName( $contentObject );
        $contentObject->setName( $name );

        $user->setAttribute( 'email', $email );
        $user->setAttribute( 'password_hash', "" );
        $user->setAttribute( 'password_hash_type', 0 );
        $user->store();
        //================= common part : end ==========================

        if ( $createNewUser )
        {
            reset( $parentNodeIDs );
            //$defaultPlacement = current( $parentNodeIDs );
            // prepare node assignments for publishing new user
            foreach( $parentNodeIDs as $parentNodeID )
            {
                $newNodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                                      'contentobject_version' => 1,
                                                                      'parent_node' => $parentNodeID,
                                                                      'is_main' => ( $defaultUserPlacement == $parentNodeID ? 1 : 0 ) ) );
                $newNodeAssignment->setAttribute( 'parent_remote_id', "LDAP_" . $parentNodeID );
                $newNodeAssignment->store();
            }

            //$adminUser = eZUser::fetchByName( 'admin' );
            //eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUser->attribute( 'contentobject_id' ) );

            //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                         'version' => 1 ) );
        }
        else
        {
            $LDAPIni = eZINI::instance( 'ldap.ini' );
            $keepGroupAssignment = ( $LDAPIni->hasVariable( 'LDAPSettings', 'KeepGroupAssignment' ) ) ?
                ( $LDAPIni->variable( 'LDAPSettings', 'KeepGroupAssignment' ) == "enabled" ) : false;

            if ( $keepGroupAssignment == false )
            {
                $parentNodeID = $contentObject->attribute( 'main_parent_node_id' );
                if ( $defaultUserPlacement != $parentNodeID )
                {
                    //$adminUser = eZUser::fetchByName( 'admin' );
                    //eZUser::setCurrentlyLoggedInUser( $adminUser, $adminUser->attribute( 'contentobject_id' ) );

                    // Check: is there user has location (not main) in default placement
                    $nodeAssignmentList = $version->nodeAssignments();
                    $isAssignmentExist = false;
                    foreach ( $nodeAssignmentList as $nodeAssignment )
                    {
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
                            //include_once( 'kernel/classes/ezcontentobjecttreenodeoperations.php' );
                            if ( !eZContentObjectTreeNodeOperations::move( $mainNodeID, $defaultUserPlacement ) )
                            {
                                eZDebug::writeError( "Failed to move node $mainNodeID as child of parent node $defaultUserPlacement",
                                                     'kernel/classes/datatypes/ezuser/ezldapuser' );
                            }
                        }
                    }
                }
            }
        }

        eZUser::updateLastVisit( $userID );
        //eZUser::setCurrentlyLoggedInUser( $user, $userID );
        // Reset number of failed login attempts
        eZUser::setFailedLoginAttempts( $userID, 0 );
        return $user;
    }

    /*
        Static method, for internal usage only.
        Note: used user group class (see 'UserGroupClassID' ini setting, in 'UserSettings' section)
              must have name attribute with indentifier equal 'name'
    */
    static function publishNewUserGroup( $parentNodeIDs, $newGroupAttributes, $isUtf8Encoding = false )
    {
        $thisFunctionErrorLabel = 'eZLDAPUser.php, function publishNewUserGroup()';
        $newNodeIDs = array();

        if ( !is_array( $newGroupAttributes ) or
             !isset( $newGroupAttributes[ 'name' ] ) or
             empty( $newGroupAttributes[ 'name' ] ) )
        {
            eZDebug::writeWarning( 'Cannot create user group with empty name.',
                                   $thisFunctionErrorLabel );
            return $newNodeIDs;
        }
        if ( !is_array( $parentNodeIDs ) or count( $parentNodeIDs ) < 1 )
        {
            eZDebug::writeWarning( 'No one parent node IDs was passed for publishing new group (group name = "' .
                                   $newGroupAttributes[ 'name' ] . '")',
                                   $thisFunctionErrorLabel );
            return $newNodeIDs;
        }

        $ini = eZINI::instance();
        $userGroupClassID = $ini->variable( "UserSettings", "UserGroupClassID" );
        $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
        $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );

        $userGroupClass = eZContentClass::fetch( $userGroupClassID );
        $contentObject = $userGroupClass->instantiate( $userCreatorID, $defaultSectionID );

        $remoteID = "LDAP_" . $newGroupAttributes[ 'name' ];
        $contentObject->setAttribute( 'remote_id', $remoteID );
        $contentObject->store();

        $contentObjectID = $contentObject->attribute( 'id' );

        reset( $parentNodeIDs );
        $defaultPlacement = current( $parentNodeIDs );
        array_shift( $parentNodeIDs );

        $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                           'contentobject_version' => 1,
                                                           'parent_node' => $defaultPlacement,
                                                           'is_main' => 1 ) );
        $nodeAssignment->setAttribute( 'parent_remote_id', "LDAP_" . $defaultPlacement );
        $nodeAssignment->store();

        foreach( $parentNodeIDs as $parentNodeID )
        {
            $newNodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                                  'contentobject_version' => 1,
                                                                  'parent_node' => $parentNodeID,
                                                                  'is_main' => 0 ) );
            $newNodeAssignment->setAttribute( 'parent_remote_id', "LDAP_" . $parentNodeID );
            $newNodeAssignment->store();
        }

        $version = $contentObject->version( 1 );
        $version->setAttribute( 'modified', time() );
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
        $version->store();

        $contentObjectAttributes = $version->contentObjectAttributes();

        // find ant set 'name' and 'description' attributes (as standard user group class)
        $nameIdentifier = 'name';
        $descIdentifier = 'description';
        $nameContentAttribute = null;
        $descContentAttribute = null;
        foreach( $contentObjectAttributes as $attribute )
        {
            if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == $nameIdentifier )
            {
                $nameContentAttribute = $attribute;
            }
            else if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == $descIdentifier )
            {
                $descContentAttribute = $attribute;
            }
        }
        if ( $nameContentAttribute )
        {
            if ( $isUtf8Encoding )
                $newGroupAttributes[ 'name' ] = utf8_decode( $newGroupAttributes[ 'name' ] );
            $nameContentAttribute->setAttribute( 'data_text', $newGroupAttributes[ 'name' ] );
            $nameContentAttribute->store();
        }
        if ( $descContentAttribute and
             isset( $newGroupAttributes[ 'description' ] ) )
        {
            if ( $isUtf8Encoding )
                $newGroupAttributes[ 'description' ] = utf8_decode( $newGroupAttributes[ 'description' ] );
            $descContentAttribute->setAttribute( 'data_text', $newGroupAttributes[ 'description' ] );
            $descContentAttribute->store();
        }

        //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                     'version' => 1 ) );
        $newNodes = eZContentObjectTreeNode::fetchByContentObjectID( $contentObjectID, true, 1 );
        foreach ( $newNodes as $newNode )
        {
            $newNodeIDs[] = $newNode->attribute( 'node_id' );
        }

        return $newNodeIDs;
    }

    /*
        Static method, for internal usage only.
        Recursive, publishes groups by prepared tree of groups returned by getUserGroupsTree() method
    */
    static function goAndPublishGroups( &$requiredParams,
                                 $curDN,
                                 &$groupsTree,
                                 &$stack,
                                 $depth,
                                 $isUser = false )
    {
        $thisFunctionErrorLabel = 'eZLDAPUser.php, function goAndPublishGroups()';
        if ( !isset( $groupsTree[ $curDN ] ) )
        {
            eZDebug::writeError( 'Passed $curDN is not in result tree array.',
                                 $thisFunctionErrorLabel );
            return false;
        }

        array_push( $stack, $curDN );
        $current =& $groupsTree[ $curDN ];

        // check the name
        if ( $isUser )
        {
            $currentName = $current[ 'data' ][ $requiredParams[ 'LDAPLoginAttribute' ] ];
        }
        else
        {
            $currentName = $current[ 'data' ][ $requiredParams[ 'LDAPGroupNameAttribute' ] ];
        }

        if ( is_array( $currentName ) and //count( $currentName ) > 1 and
             isset( $currentName[ 'count' ] ) and $currentName[ 'count' ] > 0 )
        {
            $currentName = $currentName[ 0 ];
        }

        if ( empty( $currentName ) )
        {
            eZDebug::writeWarning( "Cannot create/use group with empty name (dn = $curDN)",
                                   $thisFunctionErrorLabel );
            return false;
        }

        // go through parents
        if ( is_array( $current['parents'] ) and count( $current['parents'] ) > 0 )
        {
            foreach( array_keys( $current['parents'] ) as $key )
            {
                $parent =& $groupsTree[ $key ];

                if ( in_array( $parent['data']['dn'], $stack ) )
                {
                    $groupsTree[ '_recursion_detected_' ] = true;
                    eZDebug::writeError( 'Recursion is detected in the user-groups tree while getting parent groups for ' . $curDN,
                                         $thisFunctionErrorLabel );
                    return false;
                }
                if ( isset( $parent[ 'nodes' ] ) and count( $parent[ 'nodes' ] ) > 0 )
                {
                    continue;
                }
                $ret = self::goAndPublishGroups( $requiredParams,
                                                 $parent['data']['dn'],
                                                 $groupsTree,
                                                 $stack,
                                                 $depth - 1 );
                if ( isset( $groupsTree[ '_recursion_detected_' ] ) and $groupsTree[ '_recursion_detected_' ] )
                {
                    return false;
                }
            }
        }
        else
        {
            // We've reached a top node
            if ( !isset( $groupsTree[ 'root' ] ) )
            {
                $groupsTree[ 'root' ] = array( 'data' => null,
                                               'parents' => null,
                                               'children' => array(),
                                               'nodes' => array( $requiredParams[ 'TopUserGroupNodeID' ] ) );
            }
            if ( !isset( $groupsTree[ 'root' ][ 'children' ][ $curDN ] ) )
                $groupsTree[ 'root' ][ 'children' ][ $curDN ] =& $current;
            if ( !isset( $current[ 'parents' ][ 'root' ] ) )
                $current[ 'parents' ][ 'root' ] =& $groupsTree[ 'root' ];
        }

        if ( !isset( $current[ 'nodes' ] ) )
            $current[ 'nodes' ] = array();

        $parentNodesForNew = array();
        foreach( array_keys( $current[ 'parents' ] ) as $key )
        {
            $parent =& $groupsTree[ $key ];
            if ( is_array( $parent[ 'nodes' ] ) and count( $parent[ 'nodes' ] ) > 0 )
            {
                foreach ( $parent[ 'nodes' ] as $parentNodeID )
                {
                    // fetch current parent node
                    $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
                    if ( is_object( $parentNode ) )
                    {
                        $params = array( 'Depth' => 1,
                                         'AttributeFilter' => array( array( 'name', '=', $currentName ) ) );
                        $nodes = eZContentObjectTreeNode::subTreeByNodeID( $params, $parentNodeID );

                        if ( is_array( $nodes ) and count( $nodes ) > 0 and !$isUser )
                        {
                            // if group with given name already exist under $parentNode then get fetch
                            // group node and remember its ID
                            $node =& $nodes[ 0 ];
                            $nodeID = $node->attribute( 'node_id' );
                            $current[ 'nodes' ][] = $nodeID;
                        }
                        else
                        {
                            // if not exist then remember $parentNodeID to publish a new one
                            $parentNodesForNew[] = $parentNodeID;
                        }
                    }
                    else
                    {
                        eZDebug::writeError( 'Cannot fetch parent node for creating new user group ' . $parentNodeID,
                                             $thisFunctionErrorLabel );
                    }
                }
            }
            else
            {
                eZDebug::writeError( "Cannot get any published parent group for group/user with name = '$currentName'" .
                                     " (dn = '" . $current[ 'data' ]['dn'] . "')",
                                     $thisFunctionErrorLabel );
            }
        }

        if ( count( $parentNodesForNew ) > 0 )
        {
            if ( $isUser )
            {
                $current[ 'new_parents' ] = $parentNodesForNew;
                $newNodeIDs = array();
            }
            else
            {
                $newNodeIDs = eZLDAPUser::publishNewUserGroup( $parentNodesForNew, array( 'name' => $currentName,
                                                                                          'description' => '' ) );
            }
            $current[ 'nodes' ] = array_merge( $current[ 'nodes' ], $newNodeIDs );
        }

        array_pop( $stack );
        return true;
    }

    /*
        Static method, for internal usage only
        Recursive method, which parses tree of groups from ldap server
    */
    static function getUserGroupsTree( &$requiredParams,
                                $filter,
                                $curDN,
                                &$groupsTree,
                                &$stack,            // stack for recursion checking
                                $depth = 0 )
    {
        if ( $depth == 0 )
        {
            return false;
        }
        $thisFunctionErrorLabel = 'eZLDAPUser.php, function getUserGroupsTree()';

        if ( !isset( $requiredParams[ 'LDAPGroupBaseDN' ] ) or empty( $requiredParams[ 'LDAPGroupBaseDN' ] ) or
             !isset( $requiredParams[ 'LDAPGroupClass' ] ) or empty( $requiredParams[ 'LDAPGroupClass' ] ) or
             !isset( $requiredParams[ 'LDAPGroupNameAttribute' ] ) or empty( $requiredParams[ 'LDAPGroupNameAttribute' ] ) or
             !isset( $requiredParams[ 'LDAPGroupMemberAttribute' ] ) or empty( $requiredParams[ 'LDAPGroupMemberAttribute' ] ) or
             !isset( $requiredParams[ 'ds' ] ) or !$requiredParams[ 'ds' ] )
        {
            eZDebug::writeError( 'Missing one of required parameters.',
                                 $thisFunctionErrorLabel );
            return false;
        }
        if ( !isset( $groupsTree[ $curDN ] ) )
        {
            eZDebug::writeError( 'Passed $curDN is not in result tree array. Algorithm\'s usage error.',
                                 $thisFunctionErrorLabel );
            return false;
        }
        array_push( $stack, $curDN );

        $LDAPGroupBaseDN          =& $requiredParams[ 'LDAPGroupBaseDN' ];
        $LDAPGroupClass           =& $requiredParams[ 'LDAPGroupClass' ];
        $LDAPGroupNameAttribute   =& $requiredParams[ 'LDAPGroupNameAttribute' ];
        $LDAPGroupMemberAttribute =& $requiredParams[ 'LDAPGroupMemberAttribute' ];
        $LDAPGroupDescriptionAttribute =& $requiredParams[ 'LDAPGroupDescriptionAttribute' ];
        $ds                       =& $requiredParams[ 'ds' ];

        $current =& $groupsTree[ $curDN ];

        $retrieveAttributes = array( $LDAPGroupNameAttribute,
                                     $LDAPGroupMemberAttribute );
        $sr = ldap_search( $ds, $LDAPGroupBaseDN, $filter, $retrieveAttributes );
        $entries = ldap_get_entries( $ds, $sr );

        if ( is_array( $entries ) and
             isset( $entries[ 'count' ] ) and $entries[ 'count' ] > 0 )
        {
            $newfilter = '(&(objectClass=' . $LDAPGroupClass . ')';

            for ( $i = 0; $i < $entries[ 'count' ]; $i++ )
            {
                $parent =& $entries[ $i ];
                if ( is_null( $parent ) )
                   continue;

                $parentDN =& $parent['dn'];
                if ( in_array( $parentDN, $stack ) )
                {
                    $requiredParams[ 'LDAPGroupNameAttribute' ];

                    eZDebug::writeError( 'Recursion is detected in the user-groups tree while getting parent groups for ' . $curDN,
                                         $thisFunctionErrorLabel );
                    $groupsTree[ '_recursion_detected_' ] = true;
                    return false;
                }

                if ( !isset( $groupsTree[ $parentDN ] ) )
                {
                    $groupsTree[ $parentDN ] = array( 'data' => $parent,
                                                      'parents' => array(),
                                                      'children' => array() );
                }
                $groupsTree[ $parentDN ][ 'children' ][ $curDN ] =& $current;
                $current[ 'parents' ][ $parentDN ] =& $groupsTree[ $parentDN ];
                $newfilter1 = $newfilter . '(' . $LDAPGroupMemberAttribute . '=' . $parentDN . '))';
                $ret = eZLDAPUser::getUserGroupsTree( $requiredParams,
                                                      $newfilter1,
                                                      $parentDN,
                                                      $groupsTree,
                                                      $stack,
                                                      $depth - 1 );
                if ( isset( $groupsTree[ '_recursion_detected_' ] ) and
                     $groupsTree[ '_recursion_detected_' ] )
                {
                    return false;
                }
            }
        }
        else
        {
            // We've reached a top node
            if ( !isset( $groupsTree[ 'root' ] ) )
            {
                $groupsTree[ 'root' ] = array( 'data' => null,
                                               'parents' => null,
                                               'children' => array(),
                                               'nodes' => array( $requiredParams[ 'TopUserGroupNodeID' ] ) );
            }
            if ( !isset( $groupsTree[ 'root' ][ 'children' ][ $curDN ] ) )
                $groupsTree[ 'root' ][ 'children' ][ $curDN ] =& $current;
            if ( !isset( $current[ 'parents' ][ 'root' ] ) )
                $current[ 'parents' ][ 'root' ] =& $groupsTree[ 'root' ];
        }

        array_pop( $stack );
        return true;
    }


}

?>
