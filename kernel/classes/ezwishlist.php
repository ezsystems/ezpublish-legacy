<?php
//
// Definition of eZWishList class
//
// Created on: <01-Aug-2002 10:22:02 bf>
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
  \class eZWishList ezwishlist.php
  \brief eZWishList handles shopping wish lists
  \ingroup eZKernel

  \sa eZProductCollection
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

class eZWishList extends eZPersistentObject
{
    /*!
    */
    function eZWishList( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "user_id" => "UserID",
                                         "productcollection_id" => "ProductCollectionID"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZWishList",
                      "name" => "ezwishlist" );
    }

    function attribute( $attr )
    {
        if ( $attr == "items" )
            return $this->items();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "items" )
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

    /*!
     Will return the wish list for the current user. If a wish list does not exist one will be created.
     \return current eZWishList object
    */
    function &currentWishList( $asObject=true )
    {
        $http =& eZHTTPTool::instance();

        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $WishListArray =& eZPersistentObject::fetchObjectList( eZWishList::definition(),
                                                          null, array( "user_id" => $userID
                                                                       ),
                                                          null, null,
                                                          $asObject );

        $currentWishList = false;
        if ( count( $WishListArray ) == 0 )
        {
            $collection =& eZProductCollection::create();
            $collection->store();

            $currentWishList = new eZWishList( array( "user_id" => $userID,
                                              "productcollection_id" => $collection->attribute( "id" ) ) );
            $currentWishList->store();
        }
        else
        {
            $currentWishList =& $WishListArray[0];
        }
        return $currentWishList;
    }
}

?>
