<?php
//
// Definition of eZViewCounter class
//
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

//!! eZKernel
//! The class eZViewCounter
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassgroup.php" );

class eZViewCounter extends eZPersistentObject
{
    function eZViewCounter( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "count" => array( 'name' => "Count",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "node_id" ),
                      'relations' => array( 'node_id' => array( 'class' => 'ezcontentobjecttreenode',
                                                                'field' => 'node_id' ) ),
                      "class_name" => "eZViewCounter",
                      "sort" => array( "count" => "desc" ),
                      "name" => "ezview_counter" );
    }

    function &create( $node_id )
    {
        $row = array("node_id" => $node_id,
                     "count" => 0 );
        return new eZViewCounter( $row );
    }

    function &remove( $node_id )
    {
        eZPersistentObject::removeObject( eZViewCounter::definition(),
                                          array("node_id" => $node_id ) );
    }

    function &clear( $node_id )
    {
        $counter = eZViewCounter::fetch( $node_id );
        if ( $counter != null )
        {
            $counter->setAttribute( 'count', 0 );
            $counter->store();
        }
    }

    function increase()
    {
        $currentCount = $this->attribute( 'count' );
        $newCount = $currentCount + 1;
        $this->setAttribute( 'count', $newCount );
        $this->store();
    }

    function &fetch( $node_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZViewCounter::definition(),
                                                null,
                                                array("node_id" => $node_id ),
                                                $asObject );
    }

    function &fetchTopList( $classID = false, $sectionID = false, $offset = false, $limit = false )
    {
        if ( !$classID && !$sectionID )
        {

            return  eZPersistentObject::fetchObjectList( eZViewCounter::definition(),
                                                         null,
                                                         null,
                                                         null,
                                                         array( 'length' => $limit, 'offset' => $offset ),
                                                         false );
        }

        $queryPart = "";
        if ( $classID != false )
        {
            $queryPart .= "ezcontentobject.contentclass_id='$classID' AND ";
        }

        if ( $sectionID != false )
        {
            $queryPart .= "ezcontentobject.section_id='$sectionID' AND ";
        }

        $db =& eZDB::instance();
        $query = "SELECT ezview_counter.*
                  FROM
                         ezcontentobject_tree,
                         ezcontentobject,
                         ezview_counter
                  WHERE
                         ezview_counter.node_id=ezcontentobject_tree.node_id AND
                         $queryPart
                         ezcontentobject_tree.contentobject_id=ezcontentobject.id
                  ORDER BY ezview_counter.count DESC";

        if ( !$offset && !$limit )
        {
            $countListArray =& $db->arrayQuery( $query );
        }
        else
        {
            $countListArray =& $db->arrayQuery( $query, array( "offset" => $offset,
                                                               "limit" => $limit ) );
        }
        return $countListArray;
    }

    /// \privatesection
    var $NodeID;
    var $Count;
}

?>
