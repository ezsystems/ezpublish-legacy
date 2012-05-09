<?php
/**
 * File containing the eZUserType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZUserType ezusertype.php
  \brief The class eZUserType handles user accounts and association with content objects
  \ingroup eZDatatype

*/

class eZUserType extends eZDataType
{
    const DATA_TYPE_STRING = "ezuser";

    function eZUserType( )
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "User account", 'Datatype name' ),
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

        if ( ( $version == null || $versionCount <= 1 )
                && eZUser::fetch( $userID ) !== null )
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
        if ( $http->hasPostVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) ) &&
             $http->hasPostVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) ) &&
             $http->hasPostVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) ) &&
             $http->hasPostVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $loginName = strip_tags( $http->postVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) ) );
            $email = $http->postVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) );
            $password = $http->postVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) );
            $passwordConfirm = $http->postVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) );
            if ( trim( $loginName ) == '' )
            {
                if ( $contentObjectAttribute->validateIsRequired() || trim( $email ) != '' )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
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
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                             'The username already exists, please choose another one.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
                // validate user email
                $isValidate = eZMail::validate( $email );
                if ( !$isValidate )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
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
                                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                                     'A user with this email already exists.' ) );
                                return eZInputValidator::STATE_INVALID;
                            }
                        }
                    }
                }
                // validate user name
                if ( !eZUser::validateLoginName( $loginName, $errorText ) )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         $errorText ) );
                    return eZInputValidator::STATE_INVALID;
                }
                // validate user password
                $ini = eZINI::instance();
                $generatePasswordIfEmpty = $ini->variable( "UserSettings", "GeneratePasswordIfEmpty" ) == 'true';
                if ( !$generatePasswordIfEmpty || ( $password != "" ) )
                {
                    if ( $password == "" )
                    {
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                             'The password cannot be empty.',
                                                                             'eZUserType' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    if ( $password != $passwordConfirm )
                    {
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                             'The passwords do not match.',
                                                                             'eZUserType' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    if ( !eZUser::validatePassword( $password ) )
                    {
                        $minPasswordLength = $ini->variable( 'UserSettings', 'MinPasswordLength' );
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                             'The password must be at least %1 characters long.', null, array( $minPasswordLength ) ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    if ( strtolower( $password ) == 'password' )
                    {
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                             'The password must not be "password".' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }

                // validate confirm email
                if ( $ini->variable( 'UserSettings', 'RequireConfirmEmail' ) == 'true' )
                {
                    $emailConfirm = $http->postVariable( $base . "_data_user_email_confirm_" . $contentObjectAttribute->attribute( "id" ) );
                    if ( $email != $emailConfirm )
                    {
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                             'The emails do not match.',
                                                                             'eZUserType' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
            }
        }
        else if ( $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
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
            $login = strip_tags( $http->postVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) ) );
            $email = $http->hasPostVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) ) ? $http->postVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) ) : '';
            $password = $http->hasPostVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) ) ? $http->postVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) ) : '';
            $passwordConfirm = $http->hasPostVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) ) ? $http->postVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) ) : '';

            $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );

            $user = $contentObjectAttribute->content();
            if ( $user === null )
            {
                $user = eZUser::create( $contentObjectID );
            }

            $ini = eZINI::instance();
            $generatePasswordIfEmpty = $ini->variable( "UserSettings", "GeneratePasswordIfEmpty" );
            if ( $password == "" )
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

    function isIndexable()
    {
        return true;
    }

    /*!
     We can only remove the user attribute if:
     - The current user, anonymous user and administrator user is not using this class
     - There are more classes with the ezuser datatype
    */
    function classAttributeRemovableInformation( $contentClassAttribute, $includeAll = true )
    {
        $result  = array( 'text' => ezpI18n::tr( 'kernel/classes/datatypes',
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
            $result['list'][] = array( 'text' => ezpI18n::tr( 'kernel/classes/datatypes',
                                                         "The account owner is currently logged in." ) );
            if ( !$includeAll )
                return $result;
        }

        $sql = "SELECT id FROM ezcontentobject WHERE id = $anonID AND contentclass_id = $classID";
        $rows = $db->arrayQuery( $sql );
        if ( count( $rows ) > 0 )
        {
            $result['list'][] = array( 'text' => ezpI18n::tr( 'kernel/classes/datatypes',
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
            $result['list'][] = array( 'text' => ezpI18n::tr( 'kernel/classes/datatypes',
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
            $result['list'][] = array( 'text' => ezpI18n::tr( 'kernel/classes/datatypes',
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

    /**
     * Returns the string representation of the attribute
     * passed in $contentObjectAttribute.
     *
     * The string definition will looks like this :
     * login|email|password_has|hash_identifier|is_enabled where :
     *
     * - login => user login cf : the login field in the ezuser table.
     *
     * - email => user email cf : the  email table field in the
     *   ezuser table.
     *
     * - password_hash => use password hash, cf password_hash field in the
     *   ezuser table.
     *
     * - hash_identifier => one of the hash name available in
     *   {@link eZUser::passwordHashTypeName()}
     *
     * - is_enabled => whether the user is enabled or not, cf the is_enabled
     *   field in the ezuser_setting table.
     *
     * Example:
     * <code>
     * foo|foo@ez.no|1234|md5_password|0
     * </code>
     *
     * @uses eZUser::isEnabled()
     * @param object $contentObjectAttribute A contentobject attribute of type user_account.
     * @return string The string definition.
     */
    function toString( $contentObjectAttribute )
    {
        $userID = $contentObjectAttribute->attribute( "contentobject_id" );
        if ( empty( $GLOBALS['eZUserObject_' . $userID] ) )
        {
            $GLOBALS['eZUserObject_' . $userID] = eZUser::fetch( $userID );
        }
        $user = $GLOBALS['eZUserObject_' . $userID];

        $userInfo = array(
            $user->attribute( 'login' ),
            $user->attribute( 'email' ),
            $user->attribute( 'password_hash' ),
            eZUser::passwordHashTypeName( $user->attribute( 'password_hash_type' ) ),
            (int)$user->isEnabled()
        );

        return implode( '|', $userInfo );
    }

    /**
     * Populates the user_account datatype with the correct values
     * based upon the string passed in $string.
     *
     * The string that must be passed looks like the following :
     * login|email|password_hash|hash_identifier|is_enabled
     *
     * Example:
     * <code>
     * foo|foo@ez.no|1234|md5_password|0
     * </code>
     *
     * @param object $contentObjectAttribute A contentobject attribute of type user_account.
     * @param string $string The string as described in the example.
     * @return object The newly created eZUser object
     */
    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;
        $userData = explode( '|', $string );
        if( count( $userData ) < 2 )
            return false;
        $login = $userData[0];
        $email = $userData[1];

        $userByUsername = eZUser::fetchByName( $login );
        if( $userByUsername && $userByUsername->attribute( 'contentobject_id' ) != $contentObjectAttribute->attribute( 'contentobject_id' ) )
            return false;

        if( eZUser::requireUniqueEmail() )
        {
            $userByEmail = eZUser::fetchByEmail( $email );
            if( $userByEmail && $userByEmail->attribute( 'contentobject_id' ) != $contentObjectAttribute->attribute( 'contentobject_id' ) )
                return false;
        }

        $user = eZUser::create( $contentObjectAttribute->attribute( 'contentobject_id' ) );

        $user->setAttribute( 'login', $login );
        $user->setAttribute( 'email', $email );
        if ( isset( $userData[2] ) )
            $user->setAttribute( 'password_hash', $userData[2] );

        if ( isset( $userData[3] ) )
            $user->setAttribute( 'password_hash_type', eZUser::passwordHashTypeID( $userData[3] ) );

        if( isset( $userData[4] ) )
        {
            $userSetting = eZUserSetting::fetch(
                $contentObjectAttribute->attribute( 'contentobject_id' )
            );
            $userSetting->setAttribute( "is_enabled", (int)(bool)$userData[4] );
            $userSetting->store();
        }

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
            $userNode->setAttribute( 'is_enabled', (int)$user->isEnabled() );
            $node->appendChild( $userNode );
        }

        return $node;
    }

    /*!
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

?>
