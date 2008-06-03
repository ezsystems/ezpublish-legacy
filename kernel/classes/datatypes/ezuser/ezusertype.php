<?php
//
// Definition of eZUserType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
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

/*!
  \class eZUserType ezusertype.php
  \brief The class eZUserType handles user accounts and association with content objects
  \ingroup eZDatatype

*/

//include_once( "kernel/classes/ezdatatype.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
//include_once( "kernel/classes/datatypes/ezuser/ezusersetting.php" );
//include_once( "lib/ezutils/classes/ezmail.php" );

class eZUserType extends eZDataType
{
    const DATA_TYPE_STRING = "ezuser";

    function eZUserType( )
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "User account", 'Datatype name' ),
                           array( 'translation_allowed' => false,
                                  'serialize_supported' => true ) );
    }

    /*!
     Delete stored object attribute
    */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $db = eZDB::instance();
        $userID = $contentObjectAttribute->attribute( "contentobject_id" );

        $res = $db->arrayQuery( "SELECT COUNT(*) AS version_count FROM ezcontentobject_version WHERE contentobject_id = $userID" );
        $versionCount = $res[0]['version_count'];

        if ( $version == null || $versionCount <= 1 )
        {
            eZUser::removeUser( $userID );
            $db->query( "DELETE FROM ezuser_role WHERE contentobject_id = '$userID'" );
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $loginName = $http->postVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) );
            $email = $http->postVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) );
            $password = $http->postVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) );
            $passwordConfirm = $http->postVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) );
            if ( trim( $loginName ) == '' )
            {
                if ( $contentObjectAttribute->validateIsRequired() || trim( $email ) != '' )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The username must be specified.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
            else
            {
                $existUser = eZUser::fetchByName( $loginName );
                if ( $existUser != null )
                {
                    $userID = $existUser->attribute( 'contentobject_id' );
                    if ( $userID !=  $contentObjectAttribute->attribute( "contentobject_id" ) )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The username already exists, please choose another one.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
                $isValidate = eZMail::validate( $email );
                if ( !$isValidate )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'The email address is not valid.' ) );
                    return eZInputValidator::STATE_INVALID;
                }

                $authenticationMatch = eZUser::authenticationMatch();
                if ( $authenticationMatch & eZUser::AUTHENTICATE_EMAIL )
                {
                    if ( eZUser::requireUniqueEmail() )
                    {
                        $userByEmail = eZUser::fetchByEmail( $email );
                        if ( $userByEmail != null )
                        {
                            $userID = $userByEmail->attribute( 'contentobject_id' );
                            if ( $userID !=  $contentObjectAttribute->attribute( "contentobject_id" ) )
                            {
                                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                     'A user with this email already exists.' ) );
                                return eZInputValidator::STATE_INVALID;
                            }
                        }
                    }
                }
                $ini = eZINI::instance();
                $generatePasswordIfEmpty = $ini->variable( "UserSettings", "GeneratePasswordIfEmpty" ) == 'true';
                if ( !$generatePasswordIfEmpty || ( $password != "" ) )
                {
                    if ( ( $password != $passwordConfirm ) || ( $password == "" ) )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The passwords do not match.',
                                                                             'eZUserType' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    $minPasswordLength = $ini->hasVariable( 'UserSettings', 'MinPasswordLength' ) ? $ini->variable( 'UserSettings', 'MinPasswordLength' ) : 3;

                    if ( strlen( $password ) < (int) $minPasswordLength )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The password must be at least %1 characters long.',null, array( $minPasswordLength ) ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    if ( strtolower( $password ) == 'password' )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'The password must not be "password".' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $login = $http->postVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) );
            $email = $http->postVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) );
            $password = $http->postVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) );
            $passwordConfirm = $http->postVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) );

            $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );

            $user = $contentObjectAttribute->content();
            if ( $user === null )
            {
                $user = eZUser::create( $contentObjectID );
            }

            $ini = eZINI::instance();
            $generatePasswordIfEmpty = $ini->variable( "UserSettings", "GeneratePasswordIfEmpty" );
            if (  $password == "" )
            {
                if ( $generatePasswordIfEmpty == 'true' )
                {
                    $passwordLength = $ini->variable( "UserSettings", "GeneratePasswordLength" );
                    $password = $user->createPassword( $passwordLength );
                    $passwordConfirm = $password;
                    $http->setSessionVariable( "GeneratedPassword", $password );
                }
                else
                {
                    $password = null;
                }
            }

            eZDebugSetting::writeDebug( 'kernel-user', $password, "password" );
            eZDebugSetting::writeDebug( 'kernel-user', $passwordConfirm, "passwordConfirm" );
            eZDebugSetting::writeDebug( 'kernel-user', $login, "login" );
            eZDebugSetting::writeDebug( 'kernel-user', $email, "email" );
            eZDebugSetting::writeDebug( 'kernel-user', $contentObjectID, "contentObjectID" );
            if ( $password == "_ezpassword" )
            {
                $password = false;
                $passwordConfirm = false;
            }
            else
                $http->setSessionVariable( "GeneratedPassword", $password );

            eZDebugSetting::writeDebug( 'kernel-user', "setInformation run", "ezusertype" );
            $user->setInformation( $contentObjectID, $login, $email, $password, $passwordConfirm );
            $contentObjectAttribute->setContent( $user );
            return true;
        }
        return false;
    }

    function storeObjectAttribute( $contentObjectAttribute )
    {
        $user = $contentObjectAttribute->content();
        if ( !( $user instanceof eZUser ) )
        {
            // create a default user account
            $user = eZUser::create( $contentObjectAttribute->attribute( "contentobject_id" ) );
            $userID = $contentObjectAttribute->attribute( "contentobject_id" );
            $isEnabled = 1;
            $userSetting = eZUserSetting::create( $userID, $isEnabled );
            $userSetting->store();
        }
        $user->store();
        $contentObjectAttribute->setContent( $user );
    }

    /*!
     Returns the object title.
    */
    function title( $contentObjectAttribute, $name = "login" )
    {
        $user = $this->objectAttributeContent( $contentObjectAttribute );

        $value = $user->attribute( $name );

        return $value;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $user = $this->objectAttributeContent( $contentObjectAttribute );
        if ( is_object( $user ) and
             $user->isEnabled() )
            return true;
        return false;
    }

    /*!
     Returns the user object.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $userID = $contentObjectAttribute->attribute( "contentobject_id" );
        if ( empty( $GLOBALS['eZUserObject_' . $userID] ) )
        {
            $GLOBALS['eZUserObject_' . $userID] = eZUser::fetch( $userID );
        }
        $user = eZUser::fetch( $userID );
        eZDebugSetting::writeDebug( 'kernel-user', $user, 'user' );
        return $user;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
     We can only remove the user attribute if:
     - The current user, anonymous user and administrator user is not using this class
     - There are more classes with the ezuser datatype
    */
    function classAttributeRemovableInformation( $contentClassAttribute, $includeAll = true )
    {
        $result  = array( 'text' => ezi18n( 'kernel/classes/datatypes',
                                            "Cannot remove the account:" ),
                          'list' => array() );
        $currentUser = eZUser::currentUser();
        $userObject  = $currentUser->attribute( 'contentobject' );
        $ini         = eZINI::instance();
        $anonID      = (int)$ini->variable( 'UserSettings', 'AnonymousUserID' );
        $classID     = (int)$contentClassAttribute->attribute( 'contentclass_id' );
        $db          = eZDB::instance();

        if ( $classID == $userObject->attribute( 'contentclass_id' ) )
        {
            $result['list'][] = array( 'text' => ezi18n( 'kernel/classes/datatypes',
                                                         "The account owner is currently logged in." ) );
            if ( !$includeAll )
                return $result;
        }

        $sql = "SELECT id FROM ezcontentobject WHERE id = $anonID AND contentclass_id = $classID";
        $rows = $db->arrayQuery( $sql );
        if ( count( $rows ) > 0 )
        {
            $result['list'][] = array( 'text' => ezi18n( 'kernel/classes/datatypes',
                                                         "The account is currently used by the anonymous user." ) );
            if ( !$includeAll )
                return $result;
        }

        $sql = "SELECT ezco.id FROM ezcontentobject ezco, ezuser
 WHERE ezco.contentclass_id = $classID AND
       ezuser.login = 'admin' AND
       ezco.id = ezuser.contentobject_id ";
        $rows = $db->arrayQuery( $sql );
        if ( count( $rows ) > 0 )
        {
            $result['list'][] = array( 'text' => ezi18n( 'kernel/classes/datatypes',
                                                         "The account is currently used the administrator user." ) );
            if ( !$includeAll )
                return $result;
        }

        $sql = "SELECT count( ezcc.id ) AS count FROM ezcontentclass ezcc, ezcontentclass_attribute ezcca
 WHERE ezcc.id != $classID AND
       ezcca.data_type_string = 'ezuser' AND
       ezcc.id = ezcca.contentclass_id ";
        $rows = $db->arrayQuery( $sql );
        if ( $rows[0]['count'] == 0 )
        {
            $result['list'][] = array( 'text' => ezi18n( 'kernel/classes/datatypes',
                                                         "You cannot remove the last class holding user accounts." ) );
            if ( !$includeAll )
                return $result;
        }

        return $result;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaString = "";
        $user = $contentObjectAttribute->content();

        if ( $user instanceof eZUser )
        {
            // create a default user account
            $metaString .= $user->attribute( 'login' ) . " ";
            $metaString .= $user->attribute( 'email' ) . " ";
        }
        return $metaString;
    }

    function toString( $contentObjectAttribute )
    {
        $userID = $contentObjectAttribute->attribute( "contentobject_id" );
        if ( empty( $GLOBALS['eZUserObject_' . $userID] ) )
        {
            $GLOBALS['eZUserObject_' . $userID] = eZUser::fetch( $userID );
        }
        $user = $GLOBALS['eZUserObject_' . $userID];

        return implode( '|', array( $user->attribute( 'login' ),
                                    $user->attribute( 'email' ),
                                    $user->attribute( 'password_hash' ),
                                    eZUser::passwordHashTypeName( $user->attribute( 'password_hash_type' ) )  ) );
    }


    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;
        $userData = explode( '|', $string );
        if( count( $userData ) < 2 )
            return false;
        $login = $userData[0];
        $email = $userData[1];

        if ( eZUser::fetchByName( $login ) || eZUser::fetchByEmail( $email ) )
            return false;

        $user = eZUser::create( $contentObjectAttribute->attribute( 'contentobject_id' ) );

        $user->setAttribute( 'login', $login );
        $user->setAttribute( 'email', $email );
        if ( isset( $userData[2] ) )
            $user->setAttribute( 'password_hash', $userData[2] );

        if ( isset( $userData[3] ) )
            $user->setAttribute( 'password_hash_type', eZUser::passwordHashTypeID( $userData[3] ) );
        $user->store();
        return $user;
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $userID = $objectAttribute->attribute( "contentobject_id" );
        $user = eZUser::fetch( $userID );
        if ( is_object( $user ) )
        {
            $userNode = $node->ownerDocument->createElement( 'account' );
            $userNode->setAttribute( 'login', $user->attribute( 'login' ) );
            $userNode->setAttribute( 'email', $user->attribute( 'email' ) );
            $userNode->setAttribute( 'password_hash', $user->attribute( 'password_hash' ) );
            $userNode->setAttribute( 'password_hash_type', eZUser::passwordHashTypeName( $user->attribute( 'password_hash_type' ) ) );
            $node->appendChild( $userNode );
        }

        return $node;
    }

    /*!
     \reimp
     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $userNode = $attributeNode->getElementsByTagName( 'account' )->item( 0 );
        if ( is_object( $userNode ) )
        {
            $userID = $objectAttribute->attribute( 'contentobject_id' );
            $user = eZUser::fetch( $userID );
            if ( !is_object( $user ) )
            {
                $user = eZUser::create( $userID );
            }
            $user->setAttribute( 'login', $userNode->getAttribute( 'login' ) );
            $user->setAttribute( 'email', $userNode->getAttribute( 'email' ) );
            $user->setAttribute( 'password_hash', $userNode->getAttribute( 'password_hash' ) );
            $user->setAttribute( 'password_hash_type', eZUser::passwordHashTypeID( $userNode->getAttribute( 'password_hash_type' ) ) );
            $user->store();
        }
    }
}

eZDataType::register( eZUserType::DATA_TYPE_STRING, "eZUserType" );

?>
