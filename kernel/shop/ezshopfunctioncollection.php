<?php
//
// Definition of eZShopFunctionCollection class
//
// Created on: <06-æÅ×-2003 10:34:21 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

    function &fetchBasket( )
    {
        include_once( 'kernel/classes/ezbasket.php' );
        $basket =& eZBasket::currentBasket();
        if ( $basket === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $basket );
    }

    function fetchBestSellList( $topParentNodeID, $limit )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $node = eZContentObjectTreeNode::fetch( $topParentNodeID );
        $nodePath = $node->attribute( 'path_string' );

        $query="SELECT sum(ezproductcollection_item.item_count) as count, ezproductcollection_item.contentobject_id
                  FROM ezcontentobject_tree,
                       ezproductcollection_item,
                       ezorder
                 WHERE ezcontentobject_tree.contentobject_id=ezproductcollection_item.contentobject_id AND
                       ezorder.productcollection_id=ezproductcollection_item.productcollection_id AND
                       ezcontentobject_tree.path_string like '$nodePath%'
                 GROUP BY ezproductcollection_item.contentobject_id
                 ORDER BY count desc
                 LIMIT $limit";

        $db =& eZDB::instance();
        $topList=& $db->arrayQuery( $query );

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
        $db->createTempTable( "CREATE TEMPORARY TABLE ezproductcollection_tmp( productcollection_id int )" );
        $db->query( "INSERT INTO ezproductcollection_tmp SELECT ezorder.productcollection_id
                                                           FROM ezorder, ezproductcollection_item
                                                          WHERE ezorder.productcollection_id=ezproductcollection_item.productcollection_id
                                                            AND ezproductcollection_item.contentobject_id=$contentObjectID" );

        $query="SELECT sum(ezproductcollection_item.item_count) as count, contentobject_id FROM ezproductcollection_item, ezproductcollection_tmp
                 WHERE ezproductcollection_item.productcollection_id=ezproductcollection_tmp.productcollection_id
                   AND ezproductcollection_item.contentobject_id<>$contentObjectID
              GROUP BY ezproductcollection_item.contentobject_id
              ORDER BY count desc
                 LIMIT $limit";

        $db =& eZDB::instance();
        $objectList=& $db->arrayQuery( $query );

        $db->dropTempTable( "DROP TABLE ezproductcollection_tmp" );
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
}

?>
