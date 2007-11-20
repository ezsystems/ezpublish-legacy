<?php
//
// Definition of eZTipafriendCounter class
//
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

//!! eZKernel
//! The class eZTipafriendCounter
/*!

*/

//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezcontentclassgroup.php" );

class eZTipafriendCounter extends eZPersistentObject
{
    function eZTipafriendCounter( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'count' => array( 'name' => 'Count', // deprecated column, must not be used
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'requested' => array( 'name' => 'Requested',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      'keys' => array( 'node_id', 'requested' ),
                      'relations' => array( 'node_id' => array( 'class' => 'eZContentObjectTreeNode',
                                                                'field' => 'node_id' ) ),
                      'class_name' => 'eZTipafriendCounter',
                      'sort' => array( 'count' => 'desc' ),
                      'name' => 'eztipafriend_counter' );
    }

    static function create( $nodeID )
    {
        return new eZTipafriendCounter( array( 'node_id' => $nodeID,
                                               'count' => 0,
                                               'requested' => time() ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeForNode( $nodeID )
    {
        eZPersistentObject::removeObject( eZTipafriendCounter::definition(),
                                          array( 'node_id' => $nodeID ) );
    }

    /*!
     \deprecated
     Use removeForNode instead
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function clear( $nodeID )
    {
        eZTipafriendCounter::removeForNode( $nodeID );
    }

    /*!
     \deprecated, will be removed in future versions of eZP
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function increase()
    {
    }

    static function fetch( $nodeID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZTipafriendCounter::definition(),
                                                null,
                                                array( 'node_id' => $nodeID ),
                                                $asObject );
    }

    /*!
     \static
     Removes all counters for tipafriend functionality.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM eztipafriend_counter" );
    }

    /// \privatesection
    public $NodeID;
    public $Count;
}

?>
