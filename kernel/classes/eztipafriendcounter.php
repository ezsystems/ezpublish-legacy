<?php
/**
 * File containing the eZTipafriendCounter class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZTipafriendCounter
/*!

*/

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
                                                             'required' => true,
                                                             'foreign_class' => 'eZContentObjectTreeNode',
                                                             'foreign_attribute' => 'node_id',
                                                             'multiplicity' => '1..*' ),
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
