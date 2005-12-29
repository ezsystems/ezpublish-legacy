<?php
//
// Definition of eZShopFunctionCollection class
//
// Created on: <06-æÅ×-2003 10:34:21 sp>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
        include_once( 'kernel/classes/ezbasket.php' );
        $http =& eZHTTPTool::instance();
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
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => $currentBasket );
        }

        return $result;
    }

    function fetchBestSellList( $topParentNodeID, $limit )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $node = eZContentObjectTreeNode::fetch( $topParentNodeID );
        if ( !isset( $node ) ) return array( 'result' => null );
        $nodePath = $node->attribute( 'path_string' );

        $query="SELECT sum(ezproductcollection_item.item_count) as count, ezproductcollection_item.contentobject_id
                  FROM ezcontentobject_tree,
                       ezproductcollection_item,
                       ezorder
                 WHERE ezcontentobject_tree.contentobject_id=ezproductcollection_item.contentobject_id AND
                       ezorder.productcollection_id=ezproductcollection_item.productcollection_id AND
                       ezcontentobject_tree.path_string like '$nodePath%'
                 GROUP BY ezproductcollection_item.contentobject_id
                 ORDER BY count desc";

        $db =& eZDB::instance();
        $topList = $db->arrayQuery( $query, array( 'limit' => $limit ) );

        $contentObjectList = array();
        foreach ( array_keys ( $topList ) as $key )
        {
            $objectID = $topList[$key]['contentobject_id'];
            $contentObject =& eZContentObject::fetch( $objectID );
            if ( $contentObject === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                                'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentObjectList[] = $contentObject;
        }
        return array( 'result' => $contentObjectList );
    }

    function fetchRelatedPurchaseList( $contentObjectID, $limit )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $db =& eZDB::instance();
        $tmpTableName = $db->generateUniqueTempTableName( 'ezproductcoll_tmp_%' );
        $db->createTempTable( "CREATE TEMPORARY TABLE $tmpTableName( productcollection_id int )" );
        $db->query( "INSERT INTO $tmpTableName SELECT ezorder.productcollection_id
                                                           FROM ezorder, ezproductcollection_item
                                                          WHERE ezorder.productcollection_id=ezproductcollection_item.productcollection_id
                                                            AND ezproductcollection_item.contentobject_id=$contentObjectID" );

        $query="SELECT sum(ezproductcollection_item.item_count) as count, contentobject_id FROM ezproductcollection_item, $tmpTableName
                 WHERE ezproductcollection_item.productcollection_id=$tmpTableName.productcollection_id
                   AND ezproductcollection_item.contentobject_id<>$contentObjectID
              GROUP BY ezproductcollection_item.contentobject_id
              ORDER BY count desc";

        $db =& eZDB::instance();
        $objectList = $db->arrayQuery( $query, array( 'limit' => $limit ) );

        $db->dropTempTable( "DROP TABLE $tmpTableName" );
        $contentObjectList = array();
        foreach ( array_keys ( $objectList ) as $key )
        {
            $objectID = $objectList[$key]['contentobject_id'];
            $contentObject =& eZContentObject::fetch( $objectID );
            if ( $contentObject === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                                'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentObjectList[] = $contentObject;
        }
        return array( 'result' => $contentObjectList );
    }

    function fetchWishList( $production_id, $offset = false, $limit = false )
    {
        include_once( 'kernel/classes/ezwishlist.php' );

        $wishList =& eZWishList::items( true, $production_id, $offset, $limit );
        return array ( 'result' => &$wishList );
    }

    function fetchWishListCount( $production_id )
    {
        include_once( 'kernel/classes/ezwishlist.php' );

        $count = eZWishList::itemCount( $production_id );
        return array ( 'result' => $count );
    }

    /*!
     Finds the number of history element for the order \a $orderID.
    */
    function fetchOrderStatusHistoryCount( $orderID )
    {
        include_once( 'kernel/classes/ezorderstatushistory.php' );

        $count = eZOrderStatusHistory::fetchCount( $orderID );
        return array( 'result' => $count );
    }

    /*!
     Finds the history elements for the order \a $orderID.
    */
    function fetchOrderStatusHistory( $orderID )
    {
        include_once( 'kernel/classes/ezorderstatushistory.php' );

        $list = eZOrderStatusHistory::fetchListByOrder( $orderID );
        return array( 'result' => $list );
    }

    /*!
     Returns a list of available currencies.
    */
    function fetchCurrencyList( $status = false )
    {
        include_once( 'kernel/shop/classes/ezcurrencydata.php' );

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

    function fetchPreferredCurrency()
    {
        include_once( 'kernel/shop/classes/ezshopfunctions.php' );
        $currency = eZShopFunctions::preferredCurrency();

        $result = array( 'result' => $currency );

        return $result;
    }
}

?>
