<?php
//
// Created on: <02-Dec-2002 13:15:49 bf>
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
  \class eZInformationCollection ezinformationcollection.php
  \ingroup eZKernel
  \brief The class eZInformationCollection handles information collected by content objects

  Content objects can contain attributes which are able to collect information.
  The information collected is handled by the eZInformationCollection class.

*/

//include_once( 'kernel/classes/ezinformationcollectionattribute.php' );
//include_once( 'lib/ezutils/classes/ezsys.php' );

class eZInformationCollection extends eZPersistentObject
{
    function eZInformationCollection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZInformationCollection class.
    */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'user_identifier' => array( 'name' => 'UserIdentifier',
                                                                     'datatype' => 'string',
                                                                     'default' => '',
                                                                     'required' => true ),
                                         'creator_id' => array( 'name' => 'CreatorID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZUser',
                                                                'foreign_attribute' => 'contentobject_id',
                                                                'multiplicity' => '1..*' ),
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
                                                      'data_map' => 'dataMap',
                                                      'object' => 'object',
                                                      'creator' => 'creator' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollection',
                      'name' => 'ezinfocollection' );
    }

    /*!
     \static
     \return an array with attribute identifiers that are not to be shown in
             information collection templates.
    */
    static function attributeHideList()
    {
        $attributes = array();
        $ini = eZINI::instance( 'collect.ini' );
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
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function removeContentObject( $delID )
    {
        if( !is_numeric( $delID ) )
        {
            return;
        }

        $db = eZDB::instance();
        $db->begin();

        $db->query( "DELETE FROM ezinfocollection
                     WHERE contentobject_id = '$delID'" );
        $db->query( "DELETE FROM ezinfocollection_attribute
                     WHERE contentobject_id = '$delID'" );
        $db->commit();
    }

    /*!
     \static

     Remove a specific collection

     \param contentobject id
    */
    static function removeCollection( $collectionID )
    {
        if( !is_numeric( $collectionID ) )
        {
            return;
        }

        $db = eZDB::instance();

        $db->query( "DELETE FROM ezinfocollection
                     WHERE id = '$collectionID'" );
        $db->query( "DELETE FROM ezinfocollection_attribute
                     WHERE informationcollection_id = '$collectionID'" );
    }

    /*!
     \static
     \return the name of the template to use for viewing a specific information collection.

     The template name is determined from the content class type and object attributes.
     See settings/collect.ini for more information.
    */
    static function templateForObject( $object )
    {
        return eZInformationCollection::typeForObject( $object );
    }

    /*!
     \static
     \return the name of the template to use for viewing a specific information collection.

     The template name is determined from the content class type and object attributes.
     See settings/collect.ini for more information.
    */
    static function typeForObject( $object )
    {
        if ( !$object )
            return false;
        $class = $object->contentClass();
        if ( !$class )
            return false;

        $ini = eZINI::instance( 'collect.ini' );
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
    static function allowAnonymous( $contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini = eZINI::instance( 'collect.ini' );
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
    static function userDataHandling( $contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini = eZINI::instance( 'collect.ini' );
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

    static function sendOutEmail( $contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini = eZINI::instance( 'collect.ini' );
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

    static function displayHandling( $contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini = eZINI::instance( 'collect.ini' );
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

    static function redirectURL( $contentObject )
    {
        if ( !$contentObject )
            return false;
        $type = eZInformationCollection::typeForObject( $contentObject );

        $ini = eZINI::instance( 'collect.ini' );
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
    static function fetch( $id, $asObject = true )
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
    static function fetchByUserIdentifier( $userIdentifier, $contentObjectID = false, $asObject = true )
    {
        $conditions = array( 'user_identifier' => $userIdentifier );
        if ( $contentObjectID )
            $conditions['contentobject_id'] = $contentObjectID;
        return eZPersistentObject::fetchObject( eZInformationCollection::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    static function fetchCountForAttribute( $objectAttributeID, $value )
    {
        $db = eZDB::instance();
        // Do a count on the value of collected integer info. Useful for e.g. polls
        $valueSQL = "";
        if ( $value !== false )
        {
            if ( is_integer( $value ) )
            {
                $valueSQL = " AND data_int='" . $db->escapeString( $value ) . "'";
            }
        }
        $objectAttributeID =(int) $objectAttributeID;
        $resArray = $db->arrayQuery( "SELECT count( ezinfocollection_attribute.id ) as count FROM ezinfocollection_attribute, ezinfocollection
                                       WHERE ezinfocollection_attribute.informationcollection_id = ezinfocollection.id
                                       AND ezinfocollection_attribute.contentobject_attribute_id = '" . $objectAttributeID . "' " .  $valueSQL );

        return $resArray[0]['count'];
    }

    static function fetchCollectionCountForObject( $objectID )
    {
        if( !is_numeric( $objectID ) )
        {
            return false;
        }

        $db = eZDB::instance();
        $resultArray = $db->arrayQuery( 'SELECT COUNT( * ) as count FROM ezinfocollection WHERE contentobject_id=' . $objectID );

        return $resultArray[0]['count'];
    }

    /*!
     \static
     \param $definition      - required, definition of fields
     \param $sortArray       - required, the input array

      This function converts sorting on the form array ( 'field', true ) to the array( 'field' => true )
      and checks if the field exists in the definition. The functions is used to make sorting the same
      way as done in fetch('content','list', ... )
    */
    static function getSortArrayFromParam( $definition, $sortArray )
    {
        if ( count( $sortArray ) < 2 )
        {
            return null;
        }

        $sortField = $sortArray[0];

        // Check if we have the specified sort_field in the definition
        if ( isset( $definition[ 'fields' ][ $sortField ] ) )
        {
            $sortDir = $sortArray[1] ? 'asc' : 'desc';
            $sorts = array( $sortField => $sortDir );
            return $sorts;
        }

        eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, 'eZInformationCollection ::fetchCollectionsList::getSortArrayFromParam' );
        return null;
    }

    /*!
     \static
     \param $creatorID       - optional, default false, limits the fetched set to a creator_id
     \param $contentObjectID - optional, default false, limits the fetched set of collection to
                               a specific content object
     \param $userIdentifier  - optional, default false, limits the fetched set to a user_identifier
     \param $limitArray      - optional, default false, limits the number of returned results
                               on the form:  array( 'limit' => $limit, 'offset' => $offset )
     \param $sortArray       - optional, default false, how to sort the result,
                               on the form: array( 'field', true/false ), true = asc
     \param $asObject        - optional, default true, specifies if results should be returned as objects.

      Fetches a list of information collections.
    */
    static function fetchCollectionsList( $contentObjectID = false, $creatorID = false , $userIdentifier = false, $limitArray  = false, $sortArray = false, $asObject = true )
    {
        $conditions = array();
        if ( $contentObjectID )
            $conditions = array( 'contentobject_id' => $contentObjectID  );
        if ( $creatorID )
            $conditions['creator_id'] = $creatorID;
        if ( $userIdentifier )
            $conditions['user_identifier'] = $userIdentifier;

        $limit = null;
        if ( isset( $limitArray['limit'] ) )
        {
            $limit = $limitArray;
            if ( ! ( $limit['offset'] ) )
            {
                $limit['offset'] = 0;
            }
        }

        $sorts = null;
        if ( $sortArray !== false )
        {
            if ( count( $sortArray ) >= 2 )
            {
                $sorts = array();
                $def = eZInformationCollection::definition();

                if ( ! ( is_array( $sortArray[0] ) ) )
                {
                    $sortArray = array( 0 => $sortArray );
                }

                foreach ( $sortArray as $sortElement )
                {
                    $result = eZInformationCollection::getSortArrayFromParam( $def, $sortElement );
                    $sorts = array_merge($sorts, $result );
                }
            }
            else
            {
                eZDebug::writeWarning( 'Too few parameters for setting sorting in fetch, ignoring', 'eZInformationCollection ::fetchCollectionsList' );
            }
        }

        return eZPersistentObject::fetchObjectList( eZInformationCollection::definition(),
                                                    null,
                                                    $conditions,
                                                    $sorts,
                                                    $limit,
                                                    $asObject );
    }

    /*!
      \static

      \param $creatorID       - optional, default false, the user to fetch collections for
      \param $contentObjectID - optional, default false, limits the fetched set of collection to
                                a specific content object

      Fetch the number of items limited by the parameters
    */
    static function fetchCollectionsCount( $contentObjectID = false, $creatorID = false, $userIdentifier = false )
    {
        $conditions = array();
        if ( is_numeric( $contentObjectID ) )
            $conditions = array( 'contentobject_id' => $contentObjectID  );
        if ( is_numeric( $creatorID ) )
            $conditions['creator_id'] = $creatorID ;
        if ( $userIdentifier )
            $conditions['user_identifier'] = $userIdentifier;

        $resultSet = eZPersistentObject::fetchObjectList( eZInformationCollection::definition(),
                                                          array(),
                                                          $conditions,
                                                          false,
                                                          null,
                                                          false,
                                                          false,
                                                          array( array( 'operation' => 'count( id )',
                                                                        'name' => 'count' ) ) );
        return $resultSet[0]['count'];
    }

    static function fetchCountList( $objectAttributeID )
    {
        $db = eZDB::instance();
        // Do a count on the value of collected integer info. Useful for e.g. polls
        $valueSQL = "";

        $objectAttributeID =(int) $objectAttributeID;
        $resArray = $db->arrayQuery( "SELECT data_int, count( ezinfocollection_attribute.id ) as count FROM ezinfocollection_attribute, ezinfocollection
                                       WHERE ezinfocollection_attribute.informationcollection_id = ezinfocollection.id
                                       AND ezinfocollection_attribute.contentobject_attribute_id = '" . $objectAttributeID . "' " .  $valueSQL . "
                                       GROUP BY data_int" );

        $result = array();
        foreach ( $resArray as $res )
        {
            $result[$res['data_int']] = $res['count'];
        }

        return $result;
    }

    function creator()
    {
       $creator = eZUser::fetch( $this->attribute( 'creator_id' ) );
       return $creator;
    }

    function informationCollectionAttributes( $asObject = true )
    {
        $db = eZDB::instance();

        $arrayRes = $db->arrayQuery( "SELECT ica.id, ica.informationcollection_id, ica.contentclass_attribute_id, ica.contentobject_attribute_id, ica.contentobject_id, ica.data_text, ica.data_int,
                                          ica.data_float
                                      FROM   ezinfocollection_attribute ica, ezcontentclass_attribute
                                      WHERE  ezcontentclass_attribute.id=ica.contentclass_attribute_id
                                             AND informationcollection_id='" . $this->ID . "'
                                             AND ezcontentclass_attribute.version=0
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

    /*!
      \return an array of attributes of the information collection by identifier.

      Fetches information collection attributes and indexes by the
      content class attribute identifier.
    */
    function dataMap()
    {
        // Retreive the indexed information collection attributes
        $informationCollectionAttributes = $this->informationCollectionAttributes();

        $retArray = array();

        // Loop through each attribute hashing the array with the
        // class attribute identifier associated with the information
        // collection attribute
        foreach ( $informationCollectionAttributes as $informationAttribute )
        {
            $contentClassAttribute = $informationAttribute->attribute( 'contentclass_attribute' );
            $id = $contentClassAttribute->attribute( 'identifier' );
            $retArray[$id] = $informationAttribute;
        }

        return $retArray;
    }

    function object()
    {
        return eZContentObject::fetch( $this->ContentObjectID );
    }

    /*!
     Same as generateUserIdentifier but returns the user identifier for the current user.
    */
    static function currentUserIdentifier()
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
    static function generateUserIdentifier( &$user )
    {
        if ( !$user )
        {
            $user = eZUser::currentUser();
        }
        $userIdentifierBase = false;
        if ( $user->attribute( 'is_logged_in' ) )
        {
            $userIdentifierBase = 'ezuser-' . $user->attribute( 'contentobject_id' );
            $userIdentifier = md5( $userIdentifierBase );
        }
        else
        {
            $userIdentifier = session_id();
            //$userIdentifierBase = 'ezuser-anonymous-' . eZSys::serverVariable( 'REMOTE_ADDR' );
        }
        return $userIdentifier;
    }

    /*!
     Creates a new eZInformationCollection instance.
    */
    static function create( $contentObjectID, $userIdentifier, $creatorID = false )
    {
        $timestamp = time();

        if ( $creatorID === false )
        {
            $user = eZUser::currentUser();
            $creatorID = $user->id();
        }
        $row = array( 'contentobject_id' => $contentObjectID,
                      'user_identifier' => $userIdentifier,
                      'creator_id' => $creatorID,
                      'created' => $timestamp,
                      'modified' => $timestamp );
        return new eZInformationCollection( $row );
    }

    /*!
     \static
     Removes all collected information.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->begin();
        eZInformationCollectionAttribute::cleanup();
        $db->query( "DELETE FROM ezinfocollection" );
        $db->commit();
    }
}

?>
