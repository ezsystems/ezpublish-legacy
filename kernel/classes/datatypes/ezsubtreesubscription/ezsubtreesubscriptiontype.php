<?php
/**
 * File containing the eZSubtreeSubscriptionType class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSubtreeSubscriptionType ezsubtreesubscriptiontype.php
  \ingroup eZDatatype
  \brief The class eZSubtreeSubscriptionType does

*/
class eZSubtreeSubscriptionType extends eZDataType
{
    const DATA_TYPE_STRING = "ezsubtreesubscription";

    /*!
     Constructor
    */
    function eZSubtreeSubscriptionType()
    {
        $this->eZDataType(  self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Subtree subscription", 'Datatype name' ),
                            array( 'serialize_supported' => true,
                                   'object_serialize_map' => array( 'data_int' => 'value' ) ) );
    }


    /*!
     Store content
    */
    function onPublish( $attribute, $contentObject, $publishedNodes )
    {
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

    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $dom = $node->ownerDocument;

        $value = $objectAttribute->attribute( 'data_int' );
        $valueNode = $dom->createElement( 'value' );
        $valueNode->appendChild( $dom->createTextNode( $value ) );
        $node->appendChild( $valueNode );

        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $valueNode = $attributeNode->getElementsByTagName( 'value' )->item( 0 );
        $value = $valueNode ? $valueNode->textContent : 0;
        $objectAttribute->setAttribute( 'data_int', $value );
    }

    function diff( $old, $new, $options = false )
    {
        return null;
    }
}

?>
