<?php
//
// Definition of eZBasket class
//
// Created on: <04-Jul-2002 15:28:58 bf>
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

/*!
  \class eZBasket ezbasket.php
  \brief eZBasket handles shopping baskets
  \ingroup eZKernel

  \sa eZProductCollection
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );

class eZBasket extends eZPersistentObject
{
    /*!
    */
    function eZBasket( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "session_id" => "SessionID",
                                         "productcollection_id" => "ProductCollectionID"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZBasket",
                      "name" => "ezbasket" );
    }

    function attribute( $attr )
    {
        if ( $attr == "items" )
            return $this->items();
        else if ( $attr == "is_empty" )
            return $this->isEmpty();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "items" )
            return true;
        else if ( $attr == "is_empty" )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    function &items( $asObject=true )
    {
        $items =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null, array( "productcollection_id" => $this->ProductCollectionID
                                                                       ),
                                                          null, null,
                                                          $asObject );
        return $items;
    }

    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        $item->remove();
    }

    function isEmpty()
    {
        $items =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null, array( "productcollection_id" => $this->ProductCollectionID
                                                                       ),
                                                          null, null,
                                                          false );
        if ( count( $items ) > 0 )
            return false;
        else
            return true;
    }

    /*!
     Will return the basket for the current session. If a basket does not exist one will be created.
     \return current eZBasket object
    */
    function &currentBasket( $asObject=true )
    {
        $http =& eZHTTPTool::instance();
        $sessionID = $http->sessionID();

        $basketList =& eZPersistentObject::fetchObjectList( eZBasket::definition(),
                                                          null, array( "session_id" => $sessionID
                                                                       ),
                                                          null, null,
                                                          $asObject );

        $currentBasket = false;
        if ( count( $basketList ) == 0 )
        {
            $collection =& eZProductCollection::create();
            $collection->store();

            $currentBasket = new eZBasket( array( "session_id" => $sessionID,
                                              "productcollection_id" => $collection->attribute( "id" ) ) );
            $currentBasket->store();
        }
        else
        {
            $currentBasket =& $basketList[0];
        }
        return $currentBasket;
    }
}

?>
