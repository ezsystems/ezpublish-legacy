<?php
//
// Definition of eZOrderStatus class
//
// Created on: <07-Mar-2005 17:20:18 jhe>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*!
  \class eZOrderStatus ezorderstatus.php
  \brief Handles statuses which can be used on orders.

  This encapsulates the information about a status using
  the database table ezorder_status.

  This status can be selected on an order and is also stored
  in a history per order (eZOrderStatusHistory).

  The status consists of a name, a global ID and whether it is
  considered active or not.

  The following attributes are defined:
  - id - The auto increment ID for the status, this is only
         used to fetch a given status element from the database.
  - status_id - The global ID of the status, values below 1000 is considerd
                internal and cannot be removed by the user.
  - name - The name of the status.
  - is_active - Whether the status can be used by the end-user or not.

  Some special attributes:
  - is_internal - Returns true if the status is considerd an internal one (ID less than 1000).

  If the user creates a new status the function storeCustom() must be used, it will
  find the next available ID in the database and will use locking to avoid race conditions.

  To fetch a given status use fetch() when you have the DB ID or fetchByStatus() if you have
  a status ID.
  To fetch lists use fetchList() or fetchOrderedList() for a list sorted by name.
  If you intend to lookup many statuses using the ID the map from fetchMap() might be useful.
  To find the number of statuses in the system use orderStatusCount().

*/

class eZOrderStatus extends eZPersistentObject
{
    // Define for statuses that doesn't really exist (DB error)
    const UNDEFINED = 0;

    // Some predefined statuses, they will also appear in the database.
    const PENDING = 1;
    const PROCESSING = 2;
    const DELIVERED = 3;

    // All custom order statuses have this value or higher
    const CUSTOM = 1000;

    function eZOrderStatus( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZOrderStatus class.
    */
    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "status_id" => array( 'name' => 'StatusID',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "is_active" => array( 'name' => "IsActive",
                                                               'datatype' => 'bool',
                                                               'default' => true,
                                                               'required' => true ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array( 'is_internal' => 'isInternal' ),
                      "increment_key" => "id",
                      "class_name" => "eZOrderStatus",
                      "name" => "ezorder_status" );
    }

    /*!
     \return \c true if the status is considered an internal status.
    */
    function isInternal()
    {
        return $this->StatusID < eZOrderStatus::CUSTOM;
    }

    /*!
     \static
     Flushes all global caches for the statuses.
    */
    static function flush()
    {
        unset( $GLOBALS['eZOrderStatusList'],
               $GLOBALS['eZOrderStatusOList'],
               $GLOBALS['eZOrderStatusMap'],
               $GLOBALS['eZOrderStatusUndefined'] );
    }

    /*!
     \return the status object with the given DB ID.
    */
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZOrderStatus::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /*!
     \return the status object with the given status ID.
     \note It is safe to call this with ID 0, instead of fetching the DB
           data it calls createUndefined() and returns that data.
    */
    static function fetchByStatus( $statusID, $asObject = true )
    {
        if ( $statusID == 0 )
            return eZOrderStatus::createUndefined();
        return eZPersistentObject::fetchObject( eZOrderStatus::definition(),
                                                null,
                                                array( "status_id" => $statusID ),
                                                $asObject );
    }

    /*!
     \static
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders which maps from the status ID to the object.
    */
    static function fetchMap( $asObject = true, $showInactive = false )
    {
        if ( empty( $GLOBALS['eZOrderStatusMap'][$asObject][$showInactive] ) )
        {
            $conds = array();
            if ( !$showInactive )
                $conds['is_active'] = 1;
            $list = eZPersistentObject::fetchObjectList( eZOrderStatus::definition(),
                                                         null,
                                                         $conds,
                                                         null,
                                                         null,
                                                         $asObject );
            $map = array();
            if ( $asObject )
            {
                // Here we access member variables directly since it is of the same class
                foreach ( $list as $statusItem )
                {
                    $map[$statusItem->StatusID] = $statusItem;
                }
            }
            else
            {
                foreach ( $list as $statusItem )
                {
                    $map[$statusItem['status_id']] = $statusItem;
                }
            }
            $GLOBALS['eZOrderStatusMap'][$asObject][$showInactive] = $map;
        }

        return $GLOBALS['eZOrderStatusMap'][$asObject][$showInactive];
    }

    /*!
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders sorted by status ID.
    */
    static function fetchList( $asObject = true, $showInactive = false )
    {
        if ( empty( $GLOBALS['eZOrderStatusList'][$asObject][$showInactive] ) )
        {
            $conds = array();
            if ( !$showInactive )
                $conds['is_active'] = 1;
            $GLOBALS['eZOrderStatusList'][$asObject][$showInactive] = eZPersistentObject::fetchObjectList( eZOrderStatus::definition(),
                                                                                                           null,
                                                                                                           $conds,
                                                                                                           array( 'status_id' => false ),
                                                                                                           null,
                                                                                                           $asObject );
        }
        return $GLOBALS['eZOrderStatusList'][$asObject][$showInactive];
    }

    /*!
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders sorted by status ID.
    */
    static function fetchPolicyList( $showInactive = false )
    {
        $db = eZDB::instance();

        $conditionText = '';
        if ( !$showInactive )
            $conditionText = ' WHERE is_active = 1';

        $rows = $db->arrayQuery( "SELECT status_id AS id, name FROM ezorder_status$conditionText" );
        return $rows;
    }

    /*!
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders sorted by name.
    */
    static function fetchOrderedList( $asObject = true, $showInactive = false )
    {
        if ( empty( $GLOBALS['eZOrderStatusOList'][$asObject][$showInactive] ) )
        {
            $conds = array();
            if ( !$showInactive )
                $conds['is_active'] = 1;
            $GLOBALS['eZOrderStatusOList'][$asObject][$showInactive] = eZPersistentObject::fetchObjectList( eZOrderStatus::definition(),
                                                                                                            null,
                                                                                                            $conds,
                                                                                                            array( 'name' => false ),
                                                                                                            null,
                                                                                                            $asObject );
        }
        return $GLOBALS['eZOrderStatusOList'][$asObject][$showInactive];
    }

    /*!
     \return the number of active order statuses
    */
    static function orderStatusCount( $showInactive = false )
    {
        $db = eZDB::instance();

        $condText = '';
        if ( !$showInactive )
            $condText = " WHERE is_active = 1";
        $countArray = $db->arrayQuery(  "SELECT count( * ) AS count FROM ezorder_status$condText" );
        return $countArray[0]['count'];
    }


    /*!
     Will remove the current status from the database identifed by its DB ID.
     \note transaction safe
    */
    function removeThis()
    {
        $db = eZDB::instance();
        $db->begin();

        // Set all elements using this status to 0 (undefined).
        $statusID = (int)$this->StatusID;
        $db->query( "UPDATE ezorder SET status_id = 0 WHERE status_id = $statusID" );
        $db->query( "UPDATE ezorder_status_history SET status_id = 0 WHERE status_id = $statusID" );

        $id = $this->ID;
        eZPersistentObject::removeObject( eZOrderStatus::definition(), array( "id" => $id ) );

        $db->commit();

        eZOrderStatus::flush();
    }

    /*!
     \static
     Creates a new order status and returns it.
    */
    static function create()
    {
        $row = array(
            'id' => null,
            'is_active' => true,
            'name' => eZi18n::translate( 'kernel/shop', 'Order status' ) );
        return new eZOrderStatus( $row );
    }

    /*!
     \static
     Creates an order status which contains 'Undefined' as name and 0 as status ID.
     This can be used whenever code expects a status object to work with.
     \return The newly created status object.
    */
    static function createUndefined()
    {
        if ( empty( $GLOBALS['eZOrderStatusUndefined'] ) )
        {
            $row = array(
                'id' => null,
                'status_id' => eZOrderStatus::UNDEFINED,
                'is_active' => true,
                'name' => eZi18n::translate( 'kernel/shop', 'Undefined' ) );
            $GLOBALS['eZOrderStatusUndefined'] = new eZOrderStatus( $row );
        }
        return $GLOBALS['eZOrderStatusUndefined'];
    }

    /*!
     Stores a new custom order status.
     If there is no status ID yet it will acquire a new unique and store it
     with that.
     If it already has an ID it calls store() as normally.
    */
    function storeCustom()
    {
        if ( $this->StatusID )
        {
            eZOrderStatus::flush();
            $this->store();
        }
        else
        {
            // Lock the table while we find the highest number
            $db = eZDB::instance();
            $db->lock( 'ezorder_status' );

            $rows = $db->arrayQuery( "SELECT max( status_id ) as status_id FROM ezorder_status" );
            $statusID = $rows[0]['status_id'];

            // If the max ID is below the custom one we set as the first
            // custom ID, if not we increase it by one.
            if ( $statusID < eZOrderStatus::CUSTOM )
            {
                $statusID = eZOrderStatus::CUSTOM;
            }
            else
            {
                ++$statusID;
            }

            $this->StatusID = $statusID;
            $this->store();

            $db->unlock();

            eZOrderStatus::flush();
        }
    }
}

?>
