<?php
//
// Definition of eZTextfileuser class
//
// Created on: <01-Aug-2003 14:06:48 wy>
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

/*! \file eztextfileuser.php
*/

/*!
  \class eZTextFileUser eztextfileuser.php
  \brief The class eZTextFileUser does

*/

include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZTextFileUser extends eZUser
{
    /*!
     Constructor
    */
    function eZTextFileUser()
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
        $textFileIni =& eZINI::instance( 'textfile.ini' );
        $databaseImplementation = $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' );
        // if mysql
        if ( $databaseImplementation == "ezmysql" )
        {
            $query = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                      FROM ezuser, ezcontentobject
                      WHERE ( $loginText ) AND
                        ezcontentobject.status='$contentObjectStatus' AND
                        ( ezcontentobject.id=contentobject_id OR ( password_hash_type=4 AND ( $loginText ) AND password_hash=PASSWORD('$password') ) )";
        }
        else
        {
            $query = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                      FROM ezuser, ezcontentobject
                      WHERE ( $loginText ) AND
                            ezcontentobject.status='$contentObjectStatus' AND
                            ezcontentobject.id=contentobject_id";
        }

        $users =& $db->arrayQuery( $query );
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
                                             password_hash_type=4 AND ( $loginText ) AND password_hash=PASSWORD('$password') ";
                    $mysqlUsers =& $db->arrayQuery( $queryMysqlUser );
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
            $user =& new eZUser( $userRow );
            eZDebugSetting::writeDebug( 'kernel-user', $user, 'user' );
            $userID = $user->attribute( 'contentobject_id' );
            $GLOBALS["eZUserGlobalInstance_$userID"] =& $user;
            $http->setSessionVariable( 'eZUserLoggedInID', $userRow['contentobject_id'] );
            return $user;
        }
        else if ( $textFileIni->variable( 'TextFileSettings', 'TextFileEnabled' ) == "true" )
        {
            $fileName =  $textFileIni->variable( 'TextFileSettings', 'FileName' );
            $filePath =  $textFileIni->variable( 'TextFileSettings', 'FilePath' );
            $defaultUserPlacement = $ini->variable( "UserSettings", "DefaultUserPlacement" );
            $separator = $textFileIni->variable( "TextFileSettings", "FileFieldSeparator" );
            $loginColumnNr = $textFileIni->variable( "TextFileSettings", "LoginAttribute" );
            $passwordColumnNr = $textFileIni->variable( "TextFileSettings", "PasswordAttribute" );
            $emailColumnNr = $textFileIni->variable( "TextFileSettings", "EmailAttribute" );
            $lastNameColumnNr = $textFileIni->variable( "TextFileSettings", "LastNameAttribute" );
            $firstNameColumnNr = $textFileIni->variable( "TextFileSettings", "FirstNameAttribute" );
            if ( $textFileIni->hasVariable( 'TextFileSettings', 'DefaultUserGroupType' ) )
            {
                $UserGroupType =  $textFileIni->variable( 'TextFileSettings', 'DefaultUserGroupType' );
                $UserGroup = $textFileIni->variable( 'TextFileSettings', 'DefaultUserGroup' );
            }

            if ( $UserGroupType != null )
            {
                if ( $UserGroupType == "name" )
                {
                    $groupName = $UserGroup;
                    $groupQuery = "SELECT ezcontentobject_tree.node_id
                                       FROM ezcontentobject, ezcontentobject_tree
                                       WHERE ezcontentobject.name='$groupName'
                                       AND ezcontentobject.id=ezcontentobject_tree.contentobject_id";
                    $groupObject =& $db->arrayQuery( $groupQuery );

                    if ( count( $groupObject ) > 0  )
                    {
                        $defaultUserPlacement = $groupObject[0]['node_id'];
                    }
                }
                else if ( $UserGroupType == "id" )
                {
                    $groupID = $UserGroup;
                    $groupQuery = "SELECT ezcontentobject_tree.node_id
                                           FROM ezcontentobject, ezcontentobject_tree
                                           WHERE ezcontentobject.id='$groupID'
                                           AND ezcontentobject.id=ezcontentobject_tree.contentobject_id";
                    $groupObject =& $db->arrayQuery( $groupQuery );

                    if ( count( $groupObject ) > 0  )
                    {
                        $defaultUserPlacement = $groupObject[0]['node_id'];
                    }
                }
            }

            if ( $filePath != "root" and $filePath != null  )
                $fileName = $filePath . "/" . $fileName;

            if ( file_exists( $fileName ) )
                $handle = fopen ( $fileName, "r");
            else
                return false;
            while ( !feof( $handle ) )
            {
                $line = fgets( $handle, 4096 );

                if ( $separator == "tab" )
                    $userArray = explode( "\t", $line );
                else
                    $userArray = explode( $separator, $line );
                $uid = $userArray[$loginColumnNr-1];
                $email = $userArray[$emailColumnNr-1];
                $pass = $userArray[$passwordColumnNr-1];
                $firstName = $userArray[ $firstNameColumnNr-1];
                $lastName = $userArray[$lastNameColumnNr-1];
                if ( $login == $uid )
                {
                    if ( trim( $pass ) == $password )
                    {
                        $createNewUser = true;
                        $existUser =& $this->fetchByName( $login );
                        if ( $existUser != null )
                        {
                            $createNewUser = false;
                        }
                        if ( $createNewUser )
                        {
                            $userClassID = $ini->variable( "UserSettings", "UserClassID" );
                            $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
                            $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );

                            $class =& eZContentClass::fetch( $userClassID );
                            $contentObject =& $class->instantiate( $userCreatorID, $defaultSectionID );

                            $remoteID = "TextFile_" . $login;
                            $contentObject->setAttribute( 'remote_id', $remoteID );
                            $contentObject->store();

                            $contentObjectID = $contentObject->attribute( 'id' );
                            $userID = $contentObjectID;
                            $nodeAssignment =& eZNodeAssignment::create( array(
                                                                             'contentobject_id' => $contentObjectID,
                                                                             'contentobject_version' => 1,
                                                                             'parent_node' => $defaultUserPlacement,
                                                                             'is_main' => 1
                                                                             )
                                                                         );
                            $nodeAssignment->store();
                            $version =& $contentObject->version( 1 );
                            $version->setAttribute( 'modified', time() );
                            $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
                            $version->store();

                            $contentObjectID = $contentObject->attribute( 'id' );
                            $contentObjectAttributes =& $version->contentObjectAttributes();

                            $contentObjectAttributes[0]->setAttribute( 'data_text', $firstName );
                            $contentObjectAttributes[0]->store();

                            $contentObjectAttributes[1]->setAttribute( 'data_text', $lastName );
                            $contentObjectAttributes[1]->store();

                            $user = $this->create( $userID );
                            $user->setAttribute( 'login', $login );
                            $user->setAttribute( 'email', $email );
                            $user->setAttribute( 'password_hash', "" );
                            $user->setAttribute( 'password_hash_type', 0 );
                            $user->store();

                            $GLOBALS["eZUserGlobalInstance_$userID"] =& $user;
                            $http->setSessionVariable( 'eZUserLoggedInID', $userID );

                            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                                         'version' => 1 ) );
                            return $user;
                        }
                        else
                        {
                            // Update user information
                            $userID = $existUser->attribute( 'contentobject_id' );
                            $contentObject =& eZContentObject::fetch( $userID );

                            $parentNodeID = $contentObject->attribute( 'main_parent_node_id' );
                            $currentVersion = $contentObject->attribute( 'current_version' );

                            $version =& $contentObject->attribute( 'current' );
                            $contentObjectAttributes =& $version->contentObjectAttributes();

                            $contentObjectAttributes[0]->setAttribute( 'data_text', $firstName );
                            $contentObjectAttributes[0]->store();

                            $contentObjectAttributes[1]->setAttribute( 'data_text', $lastName );
                            $contentObjectAttributes[1]->store();

                            $existUser =& eZUser::fetch(  $userID );
                            $existUser->setAttribute('email', $email );
                            $existUser->setAttribute('password_hash', "" );
                            $existUser->setAttribute('password_hash_type', 0 );
                            $existUser->store();

                            if ( $defaultUserPlacement != $parentNodeID )
                            {
                                $newVersion =& $contentObject->createNewVersion();
                                $newVersion->assignToNode( $defaultUserPlacement, 1 );
                                $newVersion->removeAssignment( $parentNodeID );
                                $newVersionNr = $newVersion->attribute( 'version' );
                                include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
                                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                                             'version' => $newVersionNr ) );
                            }
                            $GLOBALS["eZUserGlobalInstance_$userID"] =& $existUser;
                            $http->setSessionVariable( 'eZUserLoggedInID', $userID );
                            return $existUser;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            fclose( $handle );
        }
        return false;
    }
}

?>
