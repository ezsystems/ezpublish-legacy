<?php
//
// Definition of eZDBGarbageCollector class
//
// Created on: <09-Jun-2005 08:42:13 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZDBGrbageCollector ezdbgarbagecollector.php
  \brief Handles garbage collection in the database.

  Cleans up garbage (leaks) in the database which can be left behind by
  bugs or crashes in the system.

  Garbage collection is divided into several static functions which performs
  cleanup of one or more tables. What is common for all functions are the
  \a $maxTime and \a $sleepTime parameters which controls how long a GC
  operation can go on and how long it should sleep between batch operations.

  \code
  eZDBGarbageCollector::collectBaskets();
  // or
  // Perform GC for 5 minutes with 2 second sleep intervals
  eZDBGarbageCollector::collectBaskets( 5*60, 2 );
  \endcode

*/

class eZDBGarbageCollector
{
    /*!
     Controls the default value for how many items are cleaned in one batch operation.
    */
    const ITEM_LIMIT = 3000;

    /*!
     \static
     Removes all baskets which are missing a session entry.
     This usually means the basket was not cleaned up when the session was removed.

     \param $maxTime The maximum number of seconds the collector should run.
                     If set to \c false or \c true it will continue until all garbage is collected,
                     \c false means to try the fastest way possible (may use lots of resources).
     \param $sleepTime Controls how many seconds it will sleep between each batch of cleanup,
                       setting it to \c false means that it should never sleep.
     \param $limit Controls how many items are cleaned in one batch operation,
                   a value of \c false means to use default value.

     \note The function will most likely use a little more time than \a $maxTime so
           don't depend on it to be accurate.

     \note This will also remove the product collection the basket is using.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function collectBaskets( $maxTime = false, $sleepTime = false, $limit = false )
    {
        $db = eZDB::instance();

        if ( $maxTime === false and $db->hasRequiredServerVersion( '4.0', 'mysql' ) )
        {
            $sql = "DELETE FROM ezbasket, ezproductcollection, ezproductcollection_item, ezproductcollection_item_opt
USING ezsession
      RIGHT JOIN ezbasket
        ON ezsession.session_key = ezbasket.session_id
      LEFT JOIN ezproductcollection
        ON ezbasket.productcollection_id = ezproductcollection.id
      LEFT JOIN ezproductcollection_item
        ON ezproductcollection.id = ezproductcollection_item.productcollection_id
      LEFT JOIN ezproductcollection_item_opt
        ON ezproductcollection_item.id = ezproductcollection_item_opt.item_id
WHERE ezsession.session_key IS NULL";
            $db->query( $sql );
            return;
        }

        // Find all baskets which are lacking a session (db leaks)
        if ( $db->hasRequiredServerVersion( '8', 'oracle' ) )
        {
            $sql = "SELECT id, productcollection_id
FROM ezsession, ezbasket
WHERE ezbasket.session_id = ezsession.session_key (+) AND
      ezsession.session_key IS NULL";
        }
        else
        {
            $sql = "SELECT id, productcollection_id
FROM ezsession
     RIGHT JOIN ezbasket
       ON ezbasket.session_id = ezsession.session_key
WHERE ezsession.session_key IS NULL";
        }
        if ( $limit === false )
            $limit = eZDBGarbageCollector::ITEM_LIMIT;
        $end = false;
        if ( is_numeric( $maxTime ) )
            $end = time() + $maxTime;

        do
        {
            $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => $limit ) );
            if ( count( $rows ) == 0 )
                break;

            $productCollectionIDList = array();
            $idList = array();
            foreach ( $rows as $row )
            {
                $idList[] = (int)$row['id'];
                $productCollectionIDList[] = (int)$row['productcollection_id'];
            }
            eZProductCollection::cleanupList( $productCollectionIDList );

            $ids = implode( ', ', $idList );
            $db->query( "DELETE FROM ezbasket WHERE id IN ( $ids )" );

            // Stop when we used up our time
            if ( $end !== false and time() > $end )
                break;

            // Sleep a little if required, reduces load on server
            if ( $sleepTime !== false )
                sleep( $sleepTime );

        } while ( true );
    }

    /*!
     Removes entries from ezproductcollection which is missing relations to other tables.

     \param $maxTime The maximum number of seconds the collector should run.
                     If set to \c false or \c true it will continue until all garbage is collected,
                     \c false means to try the fastest way possible (may use lots of resources).
     \param $sleepTime Controls how many seconds it will sleep between each batch of cleanup,
                       setting it to \c false means that it should never sleep.
     \param $limit Controls how many items are cleaned in one batch operation,
                   a value of \c false means to use default value.

     \note The function will most likely use a little more time than \a $maxTime so
           don't depend on it to be accurate.

     \note The current code hardcodes the related tables.

     \warning This code may be very slow on large sites.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function collectProductCollections( $maxTime = false, $sleepTime = false, $limit = false )
    {
        $db = eZDB::instance();

        // Create a temporary table for filling in collection ids
        // that are in use
        if ( $db->databaseName() == 'mysql' )
            $db->query( "DROP TABLE IF EXISTS ezproductcollection_used" );
        $db->query( "CREATE TABLE ezproductcollection_used ( id int )" );

        // Fill in collections used by orders
        $db->query( "INSERT INTO ezproductcollection_used (id) SELECT productcollection_id FROM ezorder" );

        // Fill in collections used by wish lists
        $db->query( "INSERT INTO ezproductcollection_used (id) SELECT productcollection_id FROM ezwishlist" );

        // Fill in collections used by baskets
        $db->query( "INSERT INTO ezproductcollection_used (id) SELECT productcollection_id FROM ezbasket" );

        // Create the index for faster selects
        $db->query( "CREATE INDEX ezproductcollection_used_id on ezproductcollection_used( id )" );

        if ( $maxTime === false and $db->hasRequiredServerVersion( '4.0', 'mysql' ) )
        {
            // Delete entries which are not in ezproductcollection_used
            $db->query( "DELETE FROM ezproductcollection, ezproductcollection_item, ezproductcollection_item_opt
USING ezproductcollection_used
      RIGHT JOIN ezproductcollection
        ON ezproductcollection_used.id = ezproductcollection.id
      LEFT JOIN ezproductcollection_item
        ON ezproductcollection.id = ezproductcollection_item.productcollection_id
      LEFT JOIN ezproductcollection_item_opt
        ON ezproductcollection_item.id = ezproductcollection_item_opt.item_id
WHERE ezproductcollection_used.id IS NULL" );
            $db->query( $sql );

            // Remove the temporary table
            $db->query( "DROP TABLE ezproductcollection_used" );

            return;
        }

        // Find all product collections which are not in use
        if ( $db->hasRequiredServerVersion( '8', 'oracle' ) )
        {
            $sql = "SELECT ezproductcollection.id
FROM ezproductcollection_used, ezproductcollection
WHERE ezproductcollection_used.id = ezproductcollection.id (+) AND
      ezproductcollection_used.id IS NULL";
        }
        else
        {
            $sql = "SELECT ezproductcollection.id
FROM ezproductcollection_used
     RIGHT JOIN ezproductcollection
       ON ezproductcollection_used.id = ezproductcollection.id
WHERE ezproductcollection_used.id IS NULL";
        }
        if ( $limit === false )
            $limit = eZDBGarbageCollector::ITEM_LIMIT;
        $end = false;
        if ( is_numeric( $maxTime ) )
            $end = time() + $maxTime;

        do
        {
            $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => $limit ) );
            if ( count( $rows ) == 0 )
                break;

            $idList = array();
            foreach ( $rows as $row )
            {
                $idList[] = (int)$row['id'];
            }
            eZProductCollectionItem::cleanupList( $idList );

            $ids = implode( ', ', $idList );
            $db->query( "DELETE FROM ezproductcollection WHERE id IN ( $ids )" );
            $db->query( "DELETE FROM ezproductcollection_used WHERE id IN ( $ids )" );

            // Stop when we used up our time
            if ( $end !== false and time() > $end )
                break;

            // Sleep a little if required, reduces load on server
            if ( $sleepTime !== false )
                sleep( $sleepTime );

        } while ( true );

        // Remove the temporary table
        $db->query( "DROP TABLE ezproductcollection_used" );
    }

    /*!
     \static
     Removes all product collection items which are missing a product collection.

     \param $maxTime The maximum number of seconds the collector should run.
                     If set to \c false or \c true it will continue until all garbage is collected,
                     \c false means to try the fastest way possible (may use lots of resources).
     \param $sleepTime Controls how many seconds it will sleep between each batch of cleanup,
                       setting it to \c false means that it should never sleep.
     \param $limit Controls how many items are cleaned in one batch operation,
                   a value of \c false means to use default value.

     \note The function will most likely use a little more time than \a $maxTime so
           don't depend on it to be accurate.

     \note This will also remove the product collection item option the item is using.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function collectProductCollectionItems( $maxTime = false, $sleepTime = false, $limit = false )
    {
        $db = eZDB::instance();

        if ( $maxTime === false and $db->hasRequiredServerVersion( '4.0', 'mysql' ) )
        {
            $sql = "DELETE FROM ezproductcollection_item, ezproductcollection_item_opt
USING ezproductcollection
      LEFT JOIN ezproductcollection_item
        ON ezproductcollection.id = ezproductcollection_item.productcollection_id
      LEFT JOIN ezproductcollection_item_opt
        ON ezproductcollection_item.id = ezproductcollection_item_opt.item_id
WHERE ezproductcollection.id IS NULL";
            $db->query( $sql );

            return;
        }

        // Find all items which are lacking a collection (db leaks)
        if ( $db->hasRequiredServerVersion( '8', 'oracle' ) )
        {
            $sql = "SELECT ezproductcollection_item.productcollection_id
FROM ezproductcollection, ezproductcollection_item
WHERE ezproductcollection.id = ezproductcollection_item.productcollection_id (+) AND
      ezproductcollection.id IS NULL";
        }
        else
        {
            $sql = "SELECT ezproductcollection_item.productcollection_id
FROM ezproductcollection
     RIGHT JOIN ezproductcollection_item
       ON ezproductcollection.id = ezproductcollection_item.productcollection_id
WHERE ezproductcollection.id IS NULL";
        }
        if ( $limit === false )
            $limit = eZDBGarbageCollector::ITEM_LIMIT;
        $end = false;
        if ( is_numeric( $maxTime ) )
            $end = time() + $maxTime;

        do
        {
            $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => $limit ) );
            if ( count( $rows ) == 0 )
                break;

            $idList = array();
            foreach ( $rows as $row )
            {
                $idList[] = (int)$row['productcollection_id'];
            }
            eZProductCollectionItemOption::cleanupList( $idList );

            $ids = implode( ', ', $idList );
            $db->query( "DELETE FROM ezproductcollection_item WHERE productcollection_id IN ( $ids )" );

            // Stop when we used up our time
            if ( $end !== false and time() > $end )
                break;

            // Sleep a little if required, reduces load on server
            if ( $sleepTime !== false )
                sleep( $sleepTime );

        } while ( true );
    }

    /*!
     \static
     Removes all product collection item options which are missing a product collection item.

     \param $maxTime The maximum number of seconds the collector should run.
                     If set to \c false or \c true it will continue until all garbage is collected,
                     \c false means to try the fastest way possible (may use lots of resources).
     \param $sleepTime Controls how many seconds it will sleep between each batch of cleanup,
                       setting it to \c false means that it should never sleep.
     \param $limit Controls how many items are cleaned in one batch operation,
                   a value of \c false means to use default value.

     \note The function will most likely use a little more time than \a $maxTime so
           don't depend on it to be accurate.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function collectProductCollectionItemOptions( $maxTime = false, $sleepTime = false, $limit = false )
    {
        $db = eZDB::instance();

        if ( $maxTime === false and $db->hasRequiredServerVersion( '4.0', 'mysql' ) )
        {
            $sql = "DELETE FROM ezproductcollection_item_opt
USING ezproductcollection_item
      LEFT JOIN ezproductcollection_item_opt
        ON ezproductcollection_item.id = ezproductcollection_item_opt.item_id
WHERE ezproductcollection_item.id IS NULL";
            $db->query( $sql );

            return;
        }

        // Find all items which are lacking a collection (db leaks)
        if ( $db->hasRequiredServerVersion( '8', 'oracle' ) )
        {
            $sql = "SELECT ezproductcollection_item_opt.item_id
FROM ezproductcollection_item, ezproductcollection_item_opt
WHERE ezproductcollection_item.id = ezproductcollection_item_opt.item_id (+) AND
      ezproductcollection_item.id IS NULL";
        }
        else
        {
            $sql = "SELECT ezproductcollection_item_opt.item_id
FROM ezproductcollection_item
     RIGHT JOIN ezproductcollection_item_opt
       ON ezproductcollection_item.id = ezproductcollection_item_opt.item_id
WHERE ezproductcollection_item.id IS NULL";
        }
        if ( $limit === false )
            $limit = eZDBGarbageCollector::ITEM_LIMIT;
        $end = false;
        if ( is_numeric( $maxTime ) )
            $end = time() + $maxTime;

        do
        {
            $rows = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => $limit ) );
            if ( count( $rows ) == 0 )
                break;

            $idList = array();
            foreach ( $rows as $row )
            {
                $idList[] = (int)$row['item_id'];
            }

            $ids = implode( ', ', $idList );
            $db->query( "DELETE FROM ezproductcollection_item_opt WHERE item_id IN ( $ids )" );

            // Stop when we used up our time
            if ( $end !== false and time() > $end )
                break;

            // Sleep a little if required, reduces load on server
            if ( $sleepTime !== false )
                sleep( $sleepTime );

        } while ( true );
    }
}

?>
