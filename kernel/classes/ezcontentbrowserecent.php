<?php
//
// Definition of eZContentBrowseRecent class
//
// Created on: <30-Apr-2003 13:04:11 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezcontentbrowserecent.php
*/

/*!
  \class eZContentBrowseRecent ezcontentbrowserecent.php
  \brief Handles recent nodes for users

  Allows the creation and fetching of recent lists for users.
  The recent list is used in the browse page to allow quick navigation and selection.

  Creating a new recent item is done with
\code
$userID = eZUser::currentUserID();
$nodeID = 2;
$nodeName = 'Node';
eZContentBrowseRecent::createNew( $userID, $nodeID, $nodeName )
\endcode

  Fetching the list is done with
\code
$userID = eZUser::currentUserID();
eZContentBrowseRecent::fetchListForUser( $userID )
\endcode

*/

//include_once( "lib/ezdb/classes/ezdb.php" );
require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( "kernel/classes/ezpersistentobject.php" );

class eZContentBrowseRecent extends eZPersistentObject
{
    /*!
     \reimp
    */
    function eZContentBrowseRecent( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZContentObjectTreeNode',
                                                             'foreign_attribute' => 'node_id',
                                                             'multiplicity' => '1..*' ),
                                         "created" => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'node' => 'fetchNode',
                                                      'contentobject_id' => 'contentObjectID' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZContentBrowseRecent",
                      "name" => "ezcontentbrowserecent" );

    }

    /*!
     \static
     \return the recent item \a $recentID
    */
    static function fetch( $recentID )
    {
        return eZPersistentObject::fetchObject( eZContentBrowseRecent::definition(),
                                                null, array( 'id' => $recentID ), true );
    }

    /*!
     \static
     \return the recent list for the user identifier by \a $userID.
    */
    static function fetchListForUser( $userID )
    {
        return eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    array( 'created' => 'desc' ), null, true );
    }

    /*!
     \static
     \return the maximum number of recent items for user \a $userID.
     The default value is read from MaximumRecentItems from group BrowseSettings in browse.ini.
     \note Currently all users get the same default maximum amount
    */
    static function maximumRecentItems( $userID )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance( 'browse.ini' );
        $maximum = $ini->variable( 'BrowseSettings', 'MaximumRecentItems' );
        return $maximum;
    }

    /*!
     \static
     Tries to create a new recent item and returns it.
     If the node ID \a $nodeID already exists as a recent item nothing is done and the old item is returned.

     It will also remove items when the maximum number of items for the user \a $userID is exceeded.
     \sa maximumRecentItems
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function createNew( $userID, $nodeID, $nodeName )
    {
        $recentCountList = eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                                array(),
                                                                array( 'user_id' => $userID ),
                                                                false,
                                                                null,
                                                                false,
                                                                false,
                                                                array( array( 'operation' => 'count( * )',
                                                                              'name' => 'count' ) ) );
        $matchingRecentList = eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                                   null,
                                                                   array( 'user_id' => $userID,
                                                                          'node_id' => $nodeID ),
                                                                   null,
                                                                   null,
                                                                   true );
        // If we already have the node in the list just return
        if ( count( $matchingRecentList ) > 0 )
        {
            $oldItem = $matchingRecentList[0];
            $oldItem->setAttribute( 'created', time() );
            $oldItem->store();
            return $oldItem;
        }
        $recentCount = 0;
        if ( isset( $recentCountList[0] ) and count( $recentCountList[0] ) > 0 )
            $recentCount = $recentCountList[0]['count'];
        $maximumCount = eZContentBrowseRecent::maximumRecentItems( $userID );
        // Remove oldest item

        $db = eZDB::instance();
        $db->begin();
        if ( $recentCount > $maximumCount )
        {
            $recentCountList = eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                                    null,
                                                                    array( 'user_id' => $userID ),
                                                                    array( 'created' => 'asc' ),
                                                                    array( 'length' => ( $recentCount - $maximumCount ), 'offset' => 0 ),
                                                                    true );
            foreach($recentCountList as $countList)
            {
                 $eldest = $countList;
                 $eldest->remove();
            }

        }

        $recent = new eZContentBrowseRecent( array( 'user_id' => $userID,
                                                    'node_id' => $nodeID,
                                                    'name' => $nodeName,
                                                    'created' => time() ) );
        $recent->store();
        $db->commit();
        return $recent;
    }

    /*!
     \return the tree node which this item refers to.
    */
    function fetchNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
    }

    /*!
     \return the content object ID of the tree node which this item refers to.
    */
    function contentObjectID()
    {
        $node = $this->fetchNode();
        if ( $node )
        {
            return $node->attribute( 'contentobject_id' );
        }

        return false;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeRecentByNodeID( $nodeID )
    {
        $db = eZDB::instance();
        $nodeID =(int) $nodeID;
        $db->query( "DELETE FROM ezcontentbrowserecent WHERE node_id=$nodeID" );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function updateNodeID( $oldNodeID, $newNodeID )
    {
        $db = eZDB::instance();
        $oldNodeID =(int) $oldNodeID;
        $newNodeID =(int) $newNodeID;
        $db->query( "UPDATE ezcontentbrowserecent SET node_id=$newNodeID WHERE node_id=$oldNodeID" );
    }

    /*!
     \static
     Removes all recent entries for all users.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezcontentbrowserecent" );
    }
}

?>
