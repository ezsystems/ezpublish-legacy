<?php
//
// Definition of eZTipafriendCounter class
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
//! The class eZTipafriendCounter
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassgroup.php" );

class eZTipafriendCounter extends eZPersistentObject
{
    function eZTipafriendCounter( $row )
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
                      "class_name" => "eZTipafriendCounter",
                      "sort" => array( "count" => "desc" ),
                      "name" => "eztipafriend_counter" );
    }

    function &create( $node_id )
    {
        $row = array("node_id" => $node_id,
                     "count" => 0 );
        return new eZTipafriendCounter( $row );
    }

    function &remove( $node_id )
    {
        eZPersistentObject::removeObject( eZTipafriendCounter::definition(),
                                          array("node_id" => $node_id ) );
    }

    function &clear( $node_id )
    {
        $counter = eZTipafriendCounter::fetch( $node_id );
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
        return eZPersistentObject::fetchObject( eZTipafriendCounter::definition(),
                                                null,
                                                array("node_id" => $node_id ),
                                                $asObject );
    }

    /*!
     \static
     Removes all counters for tipafriend functionality.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM eztipafriend_counter" );
    }

    /// \privatesection
    var $NodeID;
    var $Count;
}

?>
