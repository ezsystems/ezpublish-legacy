<?php
//
// Definition of eZContentBrowseRecent class
//
// Created on: <30-Apr-2003 13:04:11 sp>
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

/*! \file ezcontentbrowserecent.php
*/

/*!
  \class eZContentBrowseRecent ezcontentbrowserecent.php
  \brief The class eZContentBrowseRecent does

*/

class eZContentBrowseRecent extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZContentBrowseRecent( $row )
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
                                         "created" => array( 'name' => 'Created',
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
                      "class_name" => "eZContentBrowseRecent",
                      "name" => "ezcontentbrowserecent" );

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


    function fetch( $recentID )
    {
        return eZPersistentObject::fetchObject( eZContentBrowseRecent::definition(),
                                                null, array( 'id' => $recentID ), true );
    }

    function fetchListForUser( $userID )
    {
        return eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                    null, array( 'user_id' => $userID ),
                                                    null,null, true );
    }

    function &createNew( $userID, $nodeID, $nodeName )
    {
        $recentCountList = eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                                array(), array( 'user_id' => $userID ),
                                                                null,null,false,null,array( array( 'operation' => 'count( * )',
                                                                                                   'name' => 'count' ) ) );
        $recentCount = $recentCountList[0]['count'];
        if ( $recentCount > 9 )
        {
            $recentCountList = eZPersistentObject::fetchObjectList( eZContentBrowseRecent::definition(),
                                                                    null,
                                                                    array( 'user_id' => $userID ),
                                                                    array( 'created' => 'desc' ),
                                                                    array( 'limit' => 1, 'offset' => 0 ),
                                                                    true );
            $eldest = $recentCountList[0];
            $eldest->remove();
        }

        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $recent =& new eZContentBrowseRecent( array( 'user_id' => $userID,
                                                     'node_id' => $nodeID,
                                                     'name' => $nodeName,
                                                     'created' => eZDateTime::currentTimeStamp() ) );
        $recent->store();
        return $recent;
    }

    function &fetchNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'node_id' ) );
    }


}

?>
