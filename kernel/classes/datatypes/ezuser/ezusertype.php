<?php
//
// Definition of eZUserType class
//
// Created on: <30-Apr-2002 13:06:21 bf>
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

/*!
  \class eZUserType ezusertype.php
  \brief The class eZUserType handles user accounts and association with content objects
  \ingroup eZKernel

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

define( "EZ_DATATYPESTRING_USER", "ezuser" );

class eZUserType extends eZDataType
{
    function eZUserType( )
    {
        $this->eZDataType( EZ_DATATYPESTRING_USER, "User account" );
    }


    function hasAttribute( $name )
    {
        return eZDataType::hasAttribute( $name );
    }

    function &attribute( $name )
    {
        return eZDataType::attribute( $name );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();
            $loginName = $http->postVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) );
            $email = $http->postVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) );
            $password = $http->postVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) );
            $passwodConfirm = $http->postVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) );
            if ( $classAttribute->attribute( "is_required" ) == true )
            {
                if ( trim( $loginName ) == "" )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'eZUserType',
                                                                         'An user account must be filled up' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
            if ( trim( $loginName ) != "" )
            {
                $isValidate =  eZMail::validate( $email );
                if ( ! $isValidate )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'eZUserType',
                                                                         'Email address is not valid.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                if ( ( $password != $passwodConfirm ) || ( $password == "" ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'eZUserType',
                                                                         'Please confirm your password.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                if ( strlen( $password ) < 3 )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                         'eZUserType',
                                                                         'The minimum length of password should be 3.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $login = $http->postVariable( $base . "_data_user_login_" . $contentObjectAttribute->attribute( "id" ) );
        $email = $http->postVariable( $base . "_data_user_email_" . $contentObjectAttribute->attribute( "id" ) );
        $password = $http->postVariable( $base . "_data_user_password_" . $contentObjectAttribute->attribute( "id" ) );
        if ( $password == '' )
            $password = null;

        $passwordConfirm = $http->postVariable( $base . "_data_user_password_confirm_" . $contentObjectAttribute->attribute( "id" ) );

        $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );

        $user =& $contentObjectAttribute->content();
        if ( $user === null )
            $user =& eZUser::create( $contentObjectID );

        eZDebug::writeNotice( "setInformation", "ezusertype" );
        if ( $password != "password" )
            $user->setInformation( $contentObjectID, $login, $email, $password );
        $contentObjectAttribute->setContent( $user );
    }

    function storeObjectAttribute( &$contentObjectAttribute )
    {
        $user =& $contentObjectAttribute->content();
        if ( get_class( $user ) != "ezuser" )
        {
            // create a default user account
            $user = eZUser::create( $contentObjectAttribute->attribute( "contentobject_id" ) );
        }
        $user->store();
    }

    /*!
     Returns the object title.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_user" );
    }

    /*!
     Returns the user object.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $userID = $contentObjectAttribute->attribute( "contentobject_id" );
        return eZUser::fetch( $userID );
    }


    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return "";
    }
}

eZDataType::register( EZ_DATATYPESTRING_USER, "ezusertype" );

?>
