<?php
//
// Created on: <02-Dec-2002 13:15:49 bf>
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

/*!
  \class eZInformationCollection ezinformationcollection.php
  \ingroup eZKernel
  \brief The class eZInformationCollection handles information collected by content objects

  Content objects can contain attributes which are able to collect information.
  The information collected is handled by the eZInformationCollection class.

*/

include_once( 'kernel/classes/ezinformationcollectionattribute.php' );
include_once( 'lib/ezutils/classes/ezsys.php' );

class eZInformationCollection extends eZPersistentObject
{
    function eZInformationCollection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZInformationCollection class.
    */
    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'user_identifier' => array( 'name' => 'UserIdentifier',
                                                                     'datatype' => 'string',
                                                                     'default' => '',
                                                                     'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'attributes' => 'informationCollectionAttributes',
                                                      'object' => 'object' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollection',
                      'name' => 'ezinfocollection' );
    }

    /*!
     \reimp
    */
    function &attribute( $attr )
    {
        if ( $attr == 'attributes' )
        {
            return $this->informationCollectionAttributes();
        }
        else if ( $attr == 'object' )
        {
            return $this->object();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }

    /*!
     \static
     \return an array with attribute identifiers that are not to be shown in
             information collection templates.
    */
    function attributeHideList()
    {
        $attributes = array();
        $ini =& eZINI::instance( 'collect.ini' );
        $attributes[] = $ini->variable( 'InfoSettings', 'TypeAttribute' );
        $attributes[] = $ini->variable( 'EmailSettings', 'SendEmailAttribute' );
        $attributes[] = $ini->variable( 'DisplaySettings', 'DisplayAttribute' );
        $attributes[] = $ini->variable( 'DisplaySettings', 'RedirectURLAttribute' );
        $attributes[] = $ini->variable( 'CollectionSettings', 'CollectAnonymousDataAttribute' );
        $attributes[] = $ini->variable( 'CollectionSettings', 'CollectionUserDataAttribute' );
        return $attributes;
    }

    /*!
     \static

     Remove infomation collection from specified contentobject_id

     \param contentobject id
    */
    function removeContentObject( $delID )
    {
        if( !is_numeric( $delID ) )
        {
            return;
        }

        $db =& eZDB::instance();

        $db->query( "DELETE FROM ezinfocollection
                     WHERE contentobject_id = '$delID'" );
        $db->query( "DELETE FROM ezinfocollection_attribute
                     WHERE contentobject_id = '$delID'" );
    }

    /*!
     \static
     \return the name of the template to use for viewing a specific information collection.

     The template name is determined from the content class type and object attributes.
     See settings/collect.ini for more information.
    */
    function templateForObject( &$object )
    {
        return eZInformationCollection::typeForObject( $object );
    }

    /*!
     \static
     \return the name of the template to use for viewing a specific information collection.

     The template name is determined from the content class type and object attributes.
     See settings/collect.ini for more information.
    */
    function typeForObject( &$object )
    {
        if ( !$object )
            return false;
        $class =& $object->contentClass();
        if ( !$class )
            return false;

        $ini =& eZINI::instance( 'collect.ini' );
        $typeList = $ini->variable( 'InfoSettings', 'TypeList' );

        $classID = $class->attribute( 'id' );
        $classIdentifier = $class->attribute( 'identifier' );

        $type = false;

        if ( isset( $typeList[$classID] ) )
            $type = $typeList[$classID];
        else if ( isset( $typeList[$classIdentifier] ) )
            $type = $typeList[$classIdentifier];

        $typeAttribute = $ini->variable( 'InfoSettings', 'TypeAttribute' );
        if ( $typeAttribute )
        {
            $dataMap = $object->attribute( 'data_map' );
            if ( isset( $dataMap[$typeAttribute] ) )
            {
                $type = $dataMap[$typeAttribute]->content();
                if ( is_array( $type ) or
                     is_object( $type ) )
                    $type = false;
            }
        }

        if ( !$type )
            $type = $ini->variable( 'InfoSettings', 'Type' );

        return $type;
    }

    /*!
     \static
     \return \c true if anonymous users can submit data to the information collection \a $contentObject.
    */
    function allowAnonymous( &$contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini =& eZINI::instance( 'collect.ini' );
        $collectAnonymousList = $ini->variable( 'CollectionSettings', 'CollectAnonymousDataList' );

        $collectAnonymous = false;

        if ( isset( $collectAnonymousList[$type] ) )
            $collectAnonymous = $collectAnonymousList[$type];

        $collectAnonymousAttribute = $ini->variable( 'CollectionSettings', 'CollectAnonymousDataAttribute' );
        if ( $collectAnonymousAttribute )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            if ( isset( $dataMap[$collectAnonymousAttribute] ) )
            {
                $collectAnonymous = $dataMap[$collectAnonymousAttribute]->content();
                if ( is_array( $collectAnonymous ) or
                     is_object( $collectAnonymous ) )
                    $collectAnonymous = false;
            }
        }

        if ( !$collectAnonymous )
            $collectAnonymous = $ini->variable( 'CollectionSettings', 'CollectAnonymousData' );

        if ( $collectAnonymous == 'enabled' )
            $collectAnonymous = true;
        else
            $collectAnonymous = false;

        return $collectAnonymous;
    }

    /*!
     \static
     \return the type of handling that should be performed on user-data.

     Possible return types are:
     - multiple
     - unique
     - overwrite
    */
    function userDataHandling( &$contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini =& eZINI::instance( 'collect.ini' );
        $userDataList = $ini->variable( 'CollectionSettings', 'CollectionUserDataList' );

        $userData = false;

        if ( isset( $userDataList[$type] ) )
            $userData = $userDataList[$type];

        $userDataAttribute = $ini->variable( 'CollectionSettings', 'CollectionUserDataAttribute' );
        if ( $userDataAttribute )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            if ( isset( $dataMap[$userDataAttribute] ) )
            {
                $userData = $dataMap[$userDataAttribute]->content();
                if ( is_array( $userData ) or
                     is_object( $userData ) )
                    $userData = false;
            }
        }

        if ( !$userData )
            $userData = $ini->variable( 'CollectionSettings', 'CollectionUserData' );

        if ( !in_array( $userData, array( 'multiple', 'unique', 'overwrite' ) ) )
            $userData = 'unique';

        return $userData;
    }

    function sendOutEmail( &$contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini =& eZINI::instance( 'collect.ini' );
        $sendEmailList = $ini->variable( 'EmailSettings', 'SendEmailList' );

        $sendEmail = null;

        if ( isset( $sendEmailList[$type] ) )
            $sendEmail = $sendEmailList[$type] == 'enabled';

        $sendEmailAttribute = $ini->variable( 'EmailSettings', 'SendEmailAttribute' );
        if ( $sendEmailAttribute )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            if ( isset( $dataMap[$sendEmailAttribute] ) )
            {
                $sendEmail = $dataMap[$sendEmailAttribute]->content();
                if ( is_array( $sendEmail ) or
                     is_object( $sendEmail ) )
                    $sendEmail = null;
            }
        }

        if ( $sendEmail === null )
            $sendEmail = $ini->variable( 'EmailSettings', 'SendEmail' ) == 'enabled';

        return $sendEmail;
    }

    function displayHandling( &$contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini =& eZINI::instance( 'collect.ini' );
        $displayList = $ini->variable( 'DisplaySettings', 'DisplayList' );

        $display = false;

        if ( isset( $displayList[$type] ) )
            $display = $displayList[$type];

        $displayAttribute = $ini->variable( 'DisplaySettings', 'DisplayAttribute' );
        if ( $displayAttribute )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            if ( isset( $dataMap[$displayAttribute] ) )
            {
                $display = $dataMap[$displayAttribute]->content();
                if ( is_array( $display ) or
                     is_object( $display ) )
                    $display = false;
            }
        }

        if ( !$display )
            $display = $ini->variable( 'DisplaySettings', 'Display' );

        if ( !in_array( $display, array( 'result', 'redirect', 'node' ) ) )
            $display = 'result';

        return $display;
    }

    function redirectURL( &$contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini =& eZINI::instance( 'collect.ini' );
        $redirectURLList = $ini->variable( 'DisplaySettings', 'RedirectURLList' );

        $redirectURL = false;

        if ( isset( $redirectURLList[$type] ) )
            $redirectURL = $redirectURLList[$type];

        $redirectURLAttribute = $ini->variable( 'DisplaySettings', 'RedirectURLAttribute' );
        if ( $redirectURLAttribute )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            if ( isset( $dataMap[$redirectURLAttribute] ) )
            {
                $redirectURL = $dataMap[$redirectURLAttribute]->content();
                if ( is_array( $redirectURL ) or
                     is_object( $redirectURL ) )
                    $redirectURL = false;
            }
        }

        if ( !$redirectURL )
            $redirectURL = $ini->variable( 'DisplaySettings', 'RedirectURL' );

        return $redirectURL;
    }

    /*!
     \static
      Fetches the information collection by ID.
    */
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZInformationCollection::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    /*!
     \static
      Fetches the information collection by user identifier.
    */
    function &fetchByUserIdentifier( $userIdentifier, $contentObjectID = false, $asObject = true )
    {
        $conditions = array( 'user_identifier' => $userIdentifier );
        if ( $contentObjectID )
            $conditions['contentobject_id'] = $contentObjectID;
        return eZPersistentObject::fetchObject( eZInformationCollection::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    function fetchCountForAttribute( $objectAttributeID, $value )
    {
        $db =& eZDB::instance();
        // Do a count on the value of collected integer info. Useful for e.g. polls
        $valueSQL = "";
        if ( $value !== false )
        {
            if ( is_integer( $value ) )
            {
                $valueSQL = " AND data_int='" . $db->escapeString( $value ) . "'";
            }
        }

        $resArray =& $db->arrayQuery( "SELECT count( ezinfocollection_attribute.id ) as count FROM ezinfocollection_attribute, ezinfocollection
                                       WHERE ezinfocollection_attribute.informationcollection_id = ezinfocollection.id
                                       AND ezinfocollection_attribute.contentobject_attribute_id = '" . $db->escapeString( $objectAttributeID ) . "' " .  $valueSQL );

        return $resArray[0]['count'];
    }

    function fetchCountForObject( $objectID )
    {
        $db =& eZDB::instance();
        // Do a count on the value of collected integer info. Useful for e.g. polls

        $resArray =& $db->arrayQuery( "SELECT count( ezinfocollection.id ) as count FROM ezinfocollection
                                       WHERE ezinfocollection.contentobject_id = '" . $db->escapeString( $objectID ) . "' " );

        return $resArray[0]['count'];
    }

    function fetchCountList( $objectAttributeID )
    {
        $db =& eZDB::instance();
        // Do a count on the value of collected integer info. Useful for e.g. polls
        $valueSQL = "";
//         if ( $value !== false )
//         {
//             if ( is_integer( $value ) )
//             {
//                 $valueSQL = " AND data_int='" . $db->escapeString( $value ) . "'";
//             }
//         }

        $resArray =& $db->arrayQuery( "SELECT data_int, count( ezinfocollection_attribute.id ) as count FROM ezinfocollection_attribute, ezinfocollection
                                       WHERE ezinfocollection_attribute.informationcollection_id = ezinfocollection.id
                                       AND ezinfocollection_attribute.contentobject_attribute_id = '" . $db->escapeString( $objectAttributeID ) . "' " .  $valueSQL . "
                                       GROUP BY data_int" );

        $result = array();
        foreach ( $resArray as $res )
        {
            $result[$res['data_int']] = $res['count'];
        }

        return $result;
    }

    function &informationCollectionAttributes( $asObject = true )
    {
        $db =& eZDB::instance();

        $arrayRes = $db->arrayQuery( "SELECT ica.id, ica.informationcollection_id, ica.contentclass_attribute_id, ica.contentobject_attribute_id, ica.contentobject_id, ica.data_text, ica.data_int,
                                          ica.data_float
                                      FROM   ezinfocollection_attribute ica, ezcontentclass_attribute
                                      WHERE  ezcontentclass_attribute.id=ica.contentclass_attribute_id AND informationcollection_id='" . $this->ID . "'
                                      ORDER BY ezcontentclass_attribute.placement" );

        if ( $asObject )
        {
            $retArray = array();
            foreach ( $arrayRes as $row )
            {
                $retArray[] = new eZInformationCollectionAttribute( $row );
            }
        }
        else
        {
            $retArray = $arrayRes;
        }

        return $retArray;
    }

    function &object()
    {
        return eZContentObject::fetch( $this->ContentObjectID );
    }

    /*!
     Same as generateUserIdentifier but returns the user identifier for the current user.
    */
    function currentUserIdentifier()
    {
        $user = null;
        return eZInformationCollection::generateUserIdentifier( $user );
    }

    /*!
     Generates a user identifier for the user \a $user.
     If \a $user is \c null then the current user will be used.

     The user identifier is either calculated from the unique user ID
     or the IP address when the user is anonymous.
    */
    function generateUserIdentifier( &$user )
    {
        if ( !$user )
        {
            $user =& eZUser::currentUser();
        }
        $userIdentifierBase = false;
        if ( $user->attribute( 'is_logged_in' ) )
        {
            $userIdentifierBase = 'ezuser-' . $user->attribute( 'contentobject_id' );
        }
        else
        {
            $userIdentifierBase = 'ezuser-anonymous-' . eZSys::serverVariable( 'REMOTE_ADDR' );
        }
        $userIdentifier = md5( $userIdentifierBase );
        return $userIdentifier;
    }

    /*!
     Creates a new eZInformationCollection instance.
    */
    function &create( $contentObjectID, $userIdentifier )
    {
        $timestamp = time();
        $row = array(
            'contentobject_id' => $contentObjectID,
            'user_identifier' => $userIdentifier,
            'created' => $timestamp,
            'modified' => $timestamp );
        return new eZInformationCollection( $row );
    }

    /*!
     \static
     Removes all collected information.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        eZInformationCollectionAttribute::cleanup();
        $db->query( "DELETE FROM ezinfocollection" );
    }
}

?>
