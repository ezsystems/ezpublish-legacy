<?php
//
// Created on: <02-Dec-2002 13:15:49 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
  \class eZInformationCollection ezinformationcollection.php
  \ingroup eZKernel
  \brief The class eZInformationCollection handles information collected by content objects

  Content objects can contain attributes which are able to collect information.
  The information collected is handled by the eZInformationCollection class.

*/

include_once( 'kernel/classes/ezinformationcollectionattribute.php' );
include_once( 'lib/ezutils/classes/ezsys.php' );
include_once( 'lib/ezlocale/classes/ezdatetime.php' );

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
                      'function_attributes' => array( 'attributes' => 'attributes',
                                                      'object' => 'object' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZInformationCollection',
                      'name' => 'ezinfocollection' );
    }

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
    function &fetchByUserIdentifier( $userIdentifier, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZInformationCollection::definition(),
                                                null,
                                                array( 'user_identifier' => $userIdentifier ),
                                                $asObject );
    }

    function fetchCount( $objectAttributeID, $value )
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
        return eZPersistentObject::fetchObjectList( eZInformationCollectionAttribute::definition(),
                                                    null,
                                                    array( 'informationcollection_id' => $this->ID ),
                                                    null, null,
                                                    $asObject );
    }

    function object()
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
        $timestamp = eZDateTime::currentTimeStamp();
        $row = array(
            'contentobject_id' => $contentObjectID,
            'user_identifier' => $userIdentifier,
            'created' => $timestamp,
            'modified' => $timestamp );
        return new eZInformationCollection( $row );
    }
}

?>
