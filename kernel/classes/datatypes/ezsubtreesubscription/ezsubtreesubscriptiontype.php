<?php
//
// Definition of eZSubtreeSubscriptionType class
//
// Created on: <20-May-2003 11:35:43 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezsubtreesubscriptiontype.php
*/

/*!
  \class eZSubtreeSubscriptionType ezsubtreesubscriptiontype.php
  \brief The class eZSubtreeSubscriptionType does

*/
include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_SUBTREESUBSCRIPTION", "ezsubtreesubscription" );

class eZSubtreeSubscriptionType extends eZDataType
{
    /*!
     Constructor
    */
    function eZSubtreeSubscriptionType()
    {
        $this->eZDataType(  EZ_DATATYPESTRING_SUBTREESUBSCRIPTION, ezi18n( 'kernel/classes/datatypes', "Subtree subscription", 'Datatype name' ),
                            array( 'serialize_supported' => true ) );
    }


    /*!
     Store content
    */
    function onPublish( &$attribute, &$contentObject, &$publishedNodes )
    {
        include_once( 'kernel/classes/notification/handler/ezsubtree/ezsubtreenotificationrule.php' );
        $user =& eZUser::currentUser();
        $address = $user->attribute( 'email' );
        $userID = $user->attribute( 'contentobject_id' );

        $nodeIDList =& eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );

        if ( $attribute->attribute( 'data_int' ) == '1' )
        {
            $newSubscriptions = array();
            foreach ( array_keys( $publishedNodes ) as $key )
            {
                $node =& $publishedNodes[$key];
                if ( !in_array( $node->attribute( 'node_id' ), $nodeIDList ) )
                {
                    $newSubscriptions[] = $node->attribute( 'node_id' );
                }
            }
//             eZDebug::writeDebug( $newSubscriptions, "New subscriptions shell be created" );

            foreach ( $newSubscriptions as $nodeID )
            {

                $rule =& eZSubtreeNotificationRule::create( $nodeID, $userID );
                $rule->store();
            }
        }
        else
        {
            foreach ( array_keys( $publishedNodes ) as $key )
            {
                $node =& $publishedNodes[$key];
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
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
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


}

eZDataType::register( EZ_DATATYPESTRING_SUBTREESUBSCRIPTION, "ezsubtreesubscriptiontype" );

?>
