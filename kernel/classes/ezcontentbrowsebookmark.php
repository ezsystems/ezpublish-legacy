<?php
//
// Definition of eZContentBrowseBookmark class
//
// Created on: <29-Apr-2003 15:32:53 sp>
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

/*! \file ezcontentbrowsebookmark.php
*/

/*!
  \class eZContentBrowseBookmark ezcontentbrowsebookmark.php
  \brief The class eZContentBrowseBookmark does

*/

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

class eZContentBrowseBookmark extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZContentBrowseBookmark( $row )
    {
        $this->eZPersistentObject( $row );
    }

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
                      "function_attributes" => array( 'node' => 'fetchNode' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZContentBrowseBookmark",
                      "name" => "ezcontentbrowsebookmark" );

    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function hasAttribute( $attr )
    {
        if ( $attr=='node' )
        {
            return true;
        }
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    function &attribute( $attr )
    {
        if ( $attr=='node' )
        {
            return $this->fetchNode();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }


    function fetch( $bookmarkID )
    {
        return eZPersistentObject::fetchObject( eZContentBrowseBookmark::definition(),
                                                null, array( 'id' => $bookmarkID ), true );
    }

    function fetchBookmarkListForUser( $userID )
    {
        return eZPersistentObject::fetchObjectList( eZContentBrowseBookmark::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    null,null, true );
    }

    function fetchRecentListForUser( $userID )
    {
        return eZPersistentObject::fetchObjectList( eZContentBrowseBookmark::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    null,null, true );
    }

    function fetchListForUser( $userID )
    {
        return eZPersistentObject::fetchObjectList( eZContentBrowseBookmark::definition(),
                                                    null, array( 'user_id' => $userID ), null,null,
                                                    true );
    }

    function &createNew( $userID, $nodeID, $nodeName )
    {
        $bookmark = new eZContentBrowseBookmark( array( 'user_id' => $userID,
                                                        'node_id' => $nodeID,
                                                        'name' => $nodeName ) );
        $bookmark->store();
        return $bookmark;
    }

    function &fetchNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
    }

}

?>
