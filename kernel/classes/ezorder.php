<?php
//
// Definition of eZOrder class
//
// Created on: <31-Jul-2002 14:00:03 bf>
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
  \class eZOrder ezorder.php
  \brief eZOrder handles orders
  \ingroup eZKernel

  \sa eZProductCollection eZBasket
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

class eZOrder extends eZPersistentObject
{
    /*!
    */
    function eZOrder( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "user_id" => "UserID",
                                         "productcollection_id" => "ProductCollectionID",
                                         "created" => "Created"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZOrder",
                      "name" => "ezorder" );
    }

    function &fetch( $id, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZOrder::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $as_object );
    }

    function &fetchList( $as_object = true )
    {
        return eZPersistentObject::fetchObjectList( eZOrder::definition(),
                                                    null, null, null, null,
                                                    $as_object );
    }

    function attribute( $attr )
    {
        if ( $attr == "items" )
            return $this->items();
        else if ( $attr == "user" )
            return $this->user();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "items" )
            return true;
        else if ( $attr == "user" )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    function &items( $as_object=true )
    {
        $items =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                       null, array( "productcollection_id" => $this->ProductCollectionID
                                                                       ),
                                                          null, null,
                                                          $as_object );
        return $items;
    }

    function removeItem( $itemID )
    {
        $item = eZProductCollectionItem::fetch( $itemID );
        $item->remove();
    }

    /*!
     \return the user who has created the order.
    */
    function &user()
    {
        return eZUser::fetch( $this->UserID );
    }
}

?>
