<?php
//
// Definition of eZContentBrowseBookmark class
//
// Created on: <29-Apr-2003 15:32:53 sp>
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

/*! \file ezcontentbrowsebookmark.php
*/

/*!
  \class eZContentBrowseBookmark ezcontentbrowsebookmark.php
  \brief Handles bookmarking nodes for users

  Allows the creation and fetching of bookmark lists for users.
  The bookmark list is used in the browse page to allow quick navigation and selection.

  Creating a new bookmark item is done with
\code
$userID = eZUser::currentUserID();
$nodeID = 2;
$nodeName = 'Node';
eZContentBrowseBookmark::createNew( $userID, $nodeID, $nodeName )
\endcode

  Fetching the list is done with
\code
$userID = eZUser::currentUserID();
eZContentBrowseBookmark::fetchListForUser( $userID )
\endcode

*/

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

class eZContentBrowseBookmark extends eZPersistentObject
{
    /*!
     \reimp
    */
    function eZContentBrowseBookmark( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "node_id" => array( 'name' => "NodeID",
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
                      "class_name" => "eZContentBrowseBookmark",
                      "name" => "ezcontentbrowsebookmark" );

    }

    /*!
     \reimp
    */
    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    /*!
     \reimp
    */
    function hasAttribute( $attributeName )
    {
        if ( $attributeName == 'node' or
             $attributeName == 'contentobject_id' )
        {
            return true;
        }
        else if ( $attributeName == 'contentobject_id' )
        {
            return $this->contentObjectID();
        }
        else
            return eZPersistentObject::hasAttribute( $attributeName );
    }

    /*!
     \reimp
    */
    function &attribute( $attributeName )
    {
        if ( $attributeName == 'node' )
        {
            return $this->fetchNode();
        }
        else
            return eZPersistentObject::attribute( $attributeName );
    }


    /*!
     \static
     \return the bookmark item \a $bookmarkID.
    */
    function fetch( $bookmarkID )
    {
        return eZPersistentObject::fetchObject( eZContentBrowseBookmark::definition(),
                                                null, array( 'id' => $bookmarkID ), true );
    }

    /*!
     \static
     \return the bookmark list for user \a $userID.
    */
    function fetchListForUser( $userID )
    {
        return eZPersistentObject::fetchObjectList( eZContentBrowseBookmark::definition(),
                                                    null, array( 'user_id' => $userID ), null,null,
                                                    true );
    }

    /*!
     \static
     Creates a new bookmark item for user \a $userID with node id \a $nodeID and name \a $nodeName.
     The new item is returned.
    */
    function &createNew( $userID, $nodeID, $nodeName )
    {
        $bookmark = new eZContentBrowseBookmark( array( 'user_id' => $userID,
                                                        'node_id' => $nodeID,
                                                        'name' => $nodeName ) );
        $bookmark->store();
        return $bookmark;
    }

    /*!
     \return the tree node which this item refers to.
    */
    function &fetchNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
    }

    /*!
     \return the content object ID of the tree node which this item refers to.
    */
    function contentObjectID()
    {
        $node =& $this->fetchNode();
        if ( $node )
            return $node->attribute( 'contentobject_id' );
        return null;
    }

    /*!
     \static
     Removes all bookmark entries for all users.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcontentbrowsebookmark" );
    }

    /*!
     \static
     Removes all bookmark entries for node.
    */
    function removeByNodeID( $nodeID )
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcontentbrowsebookmark WHERE node_id=$nodeID" );
    }
}

?>
