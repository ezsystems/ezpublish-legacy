<?php
//
// Definition of eZShopFunctionCollection class
//
// Created on: <06-æÅ×-2003 10:34:21 sp>
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

/*! \file ezshopfunctioncollection.php
*/

/*!
  \class eZShopFunctionCollection ezshopfunctioncollection.php
  \brief The class eZShopFunctionCollection does

*/

class eZShopFunctionCollection
{
    /*!
     Constructor
    */
    function eZShopFunctionCollection()
    {
    }

    function fetchBasket( )
    {
        $http = eZHTTPTool::instance();
        $sessionID = $http->sessionID();

        $basketList = eZPersistentObject::fetchObjectList( eZBasket::definition(),
                                                            null,
                                                            array( "session_id" => $sessionID ),
                                                            null,
                                                            null,
                                                            true );

        $currentBasket = false;
        if ( count( $basketList ) == 0 )
        {
            // If we don't have a stored basket we create a temporary
            // one which can be returned.
            $collection = eZProductCollection::create();

            $currentBasket = new eZBasket( array( "session_id" => $sessionID,
                                                  "productcollection_id" => 0 ) );
        }
        else
        {
            $currentBasket = $basketList[0];
        }

        if ( $currentBasket === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => $currentBasket );
        }

        return $result;
    }

    function fetchBestSellList( $topParentNodeID, $limit, $offset, $start_time, $end_time, $duration, $ascending, $extended )
    {
        $node = eZContentObjectTreeNode::fetch( $topParentNodeID , false, false);
        if ( !is_array( $node ) )
            return array( 'result' => null );

        $nodePath = $node['path_string'];
        $currentTime = time();
        $sqlCreatedCondition = '';

        if ( is_numeric( $start_time ) and is_numeric( $end_time ) )
        {
            $sqlCreatedCondition = "AND ezorder.created BETWEEN '$start_time' AND '$end_time'";
        }
        else if ( is_numeric( $start_time ) and is_numeric( $duration ) )
        {
            $end_time = $start_time + $duration;
            $sqlCreatedCondition = "AND ezorder.created BETWEEN '$start_time' AND '$end_time'";
        }
        else if ( is_numeric( $end_time ) and is_numeric( $duration ) )
        {
            $start_time = $end_time - $duration;
            $sqlCreatedCondition = "AND ezorder.created BETWEEN '$start_time' AND '$end_time'";
        }
        else if ( is_numeric( $start_time ) )
        {
            $sqlCreatedCondition = "AND ezorder.created > '$start_time'";
        }
        else if ( is_numeric( $end_time ) )
        {
            $sqlCreatedCondition = "AND ezorder.created < '$end_time'";
        }
        else if ( is_numeric( $duration ) )
        {
            // substract passed duration from current time timestamp to get start_time stamp
            // end_timestamp is equal to current time in this case
            $start_time = $currentTime - $duration;
            $sqlCreatedCondition = "AND ezorder.created > '$start_time'";
        }

        $sqlOrderString = ( $ascending ? 'ORDER BY count asc' : 'ORDER BY count desc' );
        $query="SELECT sum(ezproductcollection_item.item_count) as count,
                       ezproductcollection_item.contentobject_id
                  FROM ezcontentobject_tree,
                       ezproductcollection_item,
                       ezorder
                 WHERE ezcontentobject_tree.contentobject_id=ezproductcollection_item.contentobject_id AND
                       ezorder.productcollection_id=ezproductcollection_item.productcollection_id AND
                       ezcontentobject_tree.path_string like '$nodePath%'
                       $sqlCreatedCondition
                 GROUP BY ezproductcollection_item.contentobject_id
                 $sqlOrderString";


        $db = eZDB::instance();
        $topList = $db->arrayQuery( $query, array( 'limit' => $limit, 'offset' => $offset ) );

        if ( $extended )
        {
            foreach ( array_keys ( $topList ) as $key )
            {
                $contentObject = eZContentObject::fetch( $topList[ $key ][ 'contentobject_id' ] );
                if ( $contentObject === null )
                    return array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
                $topList[$key]['object'] = $contentObject;
            }
            return array( 'result' => $topList );
        }
        else
        {
            $contentObjectList = array();
            foreach ( array_keys ( $topList ) as $key )
            {
                $objectID = $topList[$key]['contentobject_id'];
                $contentObject = eZContentObject::fetch( $objectID );
                if ( $contentObject === null )
                    return array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
                $contentObjectList[] = $contentObject;
            }
            return array( 'result' => $contentObjectList );
        }
    }

    function fetchRelatedPurchaseList( $contentObjectID, $limit )
    {
        $contentObjectID = (int)$contentObjectID;
        $db = eZDB::instance();
        $tmpTableName = $db->generateUniqueTempTableName( 'ezproductcoll_tmp_%' );
        $db->createTempTable( "CREATE TEMPORARY TABLE $tmpTableName( productcollection_id int )" );
        $db->query( "INSERT INTO $tmpTableName SELECT ezorder.productcollection_id
                                                           FROM ezorder, ezproductcollection_item
                                                          WHERE ezorder.productcollection_id=ezproductcollection_item.productcollection_id
                                                            AND ezproductcollection_item.contentobject_id=$contentObjectID",
                    eZDBInterface::SERVER_SLAVE );

        $query="SELECT sum(ezproductcollection_item.item_count) as count, contentobject_id FROM ezproductcollection_item, $tmpTableName
                 WHERE ezproductcollection_item.productcollection_id=$tmpTableName.productcollection_id
                   AND ezproductcollection_item.contentobject_id<>$contentObjectID
              GROUP BY ezproductcollection_item.contentobject_id
              ORDER BY count desc";

        $objectList = $db->arrayQuery( $query, array( 'limit' => $limit ), eZDBInterface::SERVER_SLAVE );

        $db->dropTempTable( "DROP TABLE $tmpTableName" );
        $contentObjectList = array();
        foreach ( array_keys ( $objectList ) as $key )
        {
            $objectID = $objectList[$key]['contentobject_id'];
            $contentObject = eZContentObject::fetch( $objectID );
            if ( $contentObject === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                                'error_code' => eZError::KERNEL_NOT_FOUND ) );
            $contentObjectList[] = $contentObject;
        }
        return array( 'result' => $contentObjectList );
    }

    function fetchWishList( $production_id, $offset = false, $limit = false )
    {
        $wishList = new eZWishList();
        $wishListItems = $wishList->items( true, $production_id, $offset, $limit );
        return array ( 'result' => $wishListItems );
    }

    function fetchWishListCount( $production_id )
    {
        $wishList = new eZWishList();
        $count = $wishList->itemCount( $production_id );
        return array ( 'result' => $count );
    }

    /*!
     Finds the number of history element for the order \a $orderID.
    */
    function fetchOrderStatusHistoryCount( $orderID )
    {
        $count = eZOrderStatusHistory::fetchCount( $orderID );
        return array( 'result' => $count );
    }

    /*!
     Finds the history elements for the order \a $orderID.
    */
    function fetchOrderStatusHistory( $orderID )
    {
        $list = eZOrderStatusHistory::fetchListByOrder( $orderID );
        return array( 'result' => $list );
    }

    /*!
     Returns a list of available currencies.
    */
    function fetchCurrencyList( $status = false )
    {
        $conditions = null;
        $status = eZCurrencyData::statusStringToNumeric( $status );
        if ( $status !== false )
        {
            $conditions = array( 'status' => $status );
        }

        $currencyList = eZCurrencyData::fetchList( $conditions );

        $result = array( 'result' => $currencyList );

        return $result;
    }

    /*!
     Returns currency by code.
    */
    function fetchCurrency( $code )
    {
        $currency = eZCurrencyData::fetch( $code );
        if ( is_object( $currency ) )
            $result = array( 'result' => $currency );
        else
            $result = array( 'result' => false );

        return $result;
    }

    function fetchPreferredCurrencyCode()
    {
        $currency = eZShopFunctions::preferredCurrencyCode();
        $result = array( 'result' => $currency );

        return $result;
    }

    function fetchUserCountry()
    {
        // Get country saved in user preferences.
        $country = eZShopFunctions::getPreferredUserCountry();
        if ( !$country )
        {
            // If not found, get country from user object
            // and save it to the preference.
            $country = eZShopFunctions::getUserCountry();
            if ( $country )
                eZShopFunctions::setPreferredUserCountry( $country );
        }

        return array( 'result' => $country );
    }

    function fetchProductCategory( $categoryID )
    {
        return array( 'result' => eZProductCategory::fetch( $categoryID ) );
    }


    function fetchProductCategoryList()
    {
        return array( 'result' => eZProductCategory::fetchList() );
    }
}

?>
