<?php
//
// Definition of eZContentOperationCollection class
//
// Created on: <01-Nov-2002 13:51:17 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezcontentoperationcollection.php
*/

/*!
  \class eZContentOperationCollection ezcontentoperationcollection.php
  \brief The class eZContentOperationCollection does

*/

class eZContentOperationCollection
{
    /*!
     Constructor
    */
    function eZContentOperationCollection()
    {
    }

    function readNode( $nodeID )
    {
        print( "\$nodeID=$nodeID<br/>" );
    }

    function loopNodes( $nodeID )
    {
        print( "loopNodes:\$nodeID=$nodeID<br/>" );
        return array( 'parameters' => array( array( 'parent_node_id' => 3 ),
                                             array( 'parent_node_id' => 5 ),
                                             array( 'parent_node_id' => 12 ) ) );
    }

    function loopNodeAssignment( $objectID, $version )
    {
        print( "loopNodeAssignment:\$objectID=$objectID<br/>" );
        print( "loopNodeAssignment:\$version=$version<br/>" );
        return array( 'parameters' => array( array( 'parent_node_id' => 3 ),
                                             array( 'parent_node_id' => 5 ),
                                             array( 'parent_node_id' => 12 ) ) );
    }

    function setVersionStatus( $objectID, $version, $status )
    {
        switch ( $status )
        {
            case 1:
            {
                $statusName = 'pending';
            } break;
            case 2:
            {
                $statusName = 'archived';
            } break;
            case 3:
            {
                $statusName = 'published';
            } break;
            default:
                $statusName = 'none';
        }
        print( "setVersionStatus:\$objectID=$objectID<br/>" );
        print( "setVersionStatus:\$version=$version<br/>" );
        print( "setVersionStatus:\$status=$status($statusName)<br/>" );
    }

    function publishNode( $parentNodeID, $objectID, $version )
    {
        print( "publishNode:\$parentNodeID=$parentNodeID<br/>" );
        print( "publishNode:\$objectID=$objectID<br/>" );
        print( "publishNode:\$version=$version<br/>" );
    }

}

?>
