<?php
//
// Definition of eZTextfileuser class
//
// Created on: <01-Aug-2003 14:06:48 wy>
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

/*! \file
*/

/*!
  \class eZTextFileUser eztextfileuser.php
  \ingroup eZDatatype
  \brief Handles logins for users defined a simple text file

  The handler will read the users from the text file defined in textfile.ini,
  the file contains multiple users on separate lines. Each line is again
  separated by a field-separator (default is tab).

  Once a login is requested by a user the handler will do one of two things:
  - Login the user with the existing user object found in the system
  - Creates a new user with the information found in the text file and login with that user.

*/

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
        $textFileIni = eZINI::instance( 'textfile.ini' );
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
                    $groupObject = $db->arrayQuery( $groupQuery );

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
                    $groupObject = $db->arrayQuery( $groupQuery );

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
            {
                // Increase number of failed login attempts.
                if ( isset( $userID ) )
                    eZUser::setFailedLoginAttempts( $userID );

                return false;
            }

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
                        $existUser = eZUser::fetchByName( $login );
                        if ( $existUser != null )
                        {
                            $createNewUser = false;
                        }
                        if ( $createNewUser )
                        {
                            $userClassID = $ini->variable( "UserSettings", "UserClassID" );
                            $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
                            $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );

                            $remoteID = "TextFile_" . $login;
                            // The content object may already exist if this process has failed once before, before the eZUser object was created.
                            // Therefore we try to fetch the eZContentObject before instantiating it.
                            $contentObject = eZContentObject::fetchByRemoteID( $remoteID );
                            if ( !is_object( $contentObject ) )
                            {
                                $class = eZContentClass::fetch( $userClassID );
                                $contentObject = $class->instantiate( $userCreatorID, $defaultSectionID );
                            }

                            $contentObject->setAttribute( 'remote_id', $remoteID );
                            $contentObject->store();

                            $contentObjectID = $contentObject->attribute( 'id' );
                            $userID = $contentObjectID;
                            $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                                               'contentobject_version' => 1,
                                                                               'parent_node' => $defaultUserPlacement,
                                                                               'is_main' => 1 ) );
                            $nodeAssignment->store();
                            $version = $contentObject->version( 1 );
                            $version->setAttribute( 'modified', time() );
                            $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
                            $version->store();

                            $contentObjectID = $contentObject->attribute( 'id' );
                            $contentObjectAttributes = $version->contentObjectAttributes();

                            $contentObjectAttributes[0]->setAttribute( 'data_text', $firstName );
                            $contentObjectAttributes[0]->store();

                            $contentObjectAttributes[1]->setAttribute( 'data_text', $lastName );
                            $contentObjectAttributes[1]->store();

                            $user = eZUser::create( $userID );
                            $user->setAttribute( 'login', $login );
                            $user->setAttribute( 'email', $email );
                            $user->setAttribute( 'password_hash', "" );
                            $user->setAttribute( 'password_hash_type', 0 );
                            $user->store();

                            eZUser::updateLastVisit( $userID );
                            eZUser::setCurrentlyLoggedInUser( $user, $userID );

                            // Reset number of failed login attempts
                            eZUser::setFailedLoginAttempts( $userID, 0 );

                            $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObjectID,
                                                                                                         'version' => 1 ) );
                            return $user;
                        }
                        else
                        {
                            // Update user information
                            $userID = $existUser->attribute( 'contentobject_id' );
                            $contentObject = eZContentObject::fetch( $userID );

                            $parentNodeID = $contentObject->attribute( 'main_parent_node_id' );
                            $currentVersion = $contentObject->attribute( 'current_version' );

                            $version = $contentObject->attribute( 'current' );
                            $contentObjectAttributes = $version->contentObjectAttributes();

                            $contentObjectAttributes[0]->setAttribute( 'data_text', $firstName );
                            $contentObjectAttributes[0]->store();

                            $contentObjectAttributes[1]->setAttribute( 'data_text', $lastName );
                            $contentObjectAttributes[1]->store();

                            $existUser = eZUser::fetch(  $userID );
                            $existUser->setAttribute('email', $email );
                            $existUser->setAttribute('password_hash', "" );
                            $existUser->setAttribute('password_hash_type', 0 );
                            $existUser->store();

                            if ( $defaultUserPlacement != $parentNodeID )
                            {
                                $newVersion = $contentObject->createNewVersion();
                                $newVersion->assignToNode( $defaultUserPlacement, 1 );
                                $newVersion->removeAssignment( $parentNodeID );
                                $newVersionNr = $newVersion->attribute( 'version' );
                                $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $userID,
                                                                                                             'version' => $newVersionNr ) );
                            }

                            eZUser::updateLastVisit( $userID );
                            eZUser::setCurrentlyLoggedInUser( $existUser, $userID );

                            // Reset number of failed login attempts
                            eZUser::setFailedLoginAttempts( $userID, 0 );

                            return $existUser;
                        }
                    }
                    else
                    {
                        // Increase number of failed login attempts.
                        if ( isset( $userID ) )
                            eZUser::setFailedLoginAttempts( $userID );

                        return false;
                    }
                }
            }
            fclose( $handle );
        }
        // Increase number of failed login attempts.
        if ( isset( $userID ) )
            eZUser::setFailedLoginAttempts( $userID );

        return false;
    }
}

?>
