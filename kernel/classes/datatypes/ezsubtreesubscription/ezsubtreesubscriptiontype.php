<?php
//
// Definition of eZSubtreeSubscriptionType class
//
// Created on: <20-May-2003 11:35:43 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file ezsubtreesubscriptiontype.php
*/

/*!
  \class eZSubtreeSubscriptionType ezsubtreesubscriptiontype.php
  \ingroup eZDatatype
  \brief The class eZSubtreeSubscriptionType does

*/
//include_once( "kernel/classes/ezdatatype.php" );

class eZSubtreeSubscriptionType extends eZDataType
{
    const DATA_TYPE_STRING = "ezsubtreesubscription";

    /*!
     Constructor
    */
    function eZSubtreeSubscriptionType()
    {
        $this->eZDataType(  self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Subtree subscription", 'Datatype name' ),
                            array( 'serialize_supported' => true,
                                   'object_serialize_map' => array( 'data_int' => 'value' ) ) );
    }


    /*!
     Store content
    */
    function onPublish( $attribute, $contentObject, $publishedNodes )
    {
        //include_once( 'kernel/classes/notification/handler/ezsubtree/ezsubtreenotificationrule.php' );
        $user = eZUser::currentUser();
        $address = $user->attribute( 'email' );
        $userID = $user->attribute( 'contentobject_id' );

        $nodeIDList = eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );

        if ( $attribute->attribute( 'data_int' ) == '1' )
        {
            $newSubscriptions = array();
            foreach ( $publishedNodes as $node )
            {
                if ( !in_array( $node->attribute( 'node_id' ), $nodeIDList ) )
                {
                    $newSubscriptions[] = $node->attribute( 'node_id' );
                }
            }

            foreach ( $newSubscriptions as $nodeID )
            {

                $rule = eZSubtreeNotificationRule::create( $nodeID, $userID );
                $rule->store();
            }
        }
        else
        {
            foreach ( $publishedNodes as $node )
            {
                if ( in_array( $node->attribute( 'node_id' ), $nodeIDList ) )
                {
                    eZSubtreeNotificationRule::removeByNodeAndUserID( $user->attribute( 'contentobject_id' ), $node->attribute( 'node_id' ) );
                }
            }
        }
        return true;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_subtreesubscription_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_data_subtreesubscription_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) )
                $data = 1;
        }
        else
        {
            $data = 0;
        }
        $contentObjectAttribute->setAttribute( "data_int", $data );
        return true;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }


    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;
        if ( ! is_numeric( $string ) )
            return false;

        $contentObjectAttribute->setAttribute( 'data_int', $string );
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $value = $objectAttribute->attribute( 'data_int' );
        $valueNode = $node->ownerDocument->createElement( 'value', $value );
        $node->appendChild( $valueNode );

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $valueNode = $attributeNode->getElementsByTagName( 'value' )->item( 0 );
        $value = $valueNode ? $valueNode->textContent : 0;
        $objectAttribute->setAttribute( 'data_int', $value );
    }

    /*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        return null;
    }
}

eZDataType::register( eZSubtreeSubscriptionType::DATA_TYPE_STRING, "eZSubtreeSubscriptionType" );

?>
