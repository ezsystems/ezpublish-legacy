<?php
//
// Definition of eZProductCollection class
//
// Created on: <04-Jul-2002 13:40:41 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
  \class eZProductCollection ezproductcollection.php
  \brief eZProductCollection is a container class which handles groups of products
  \ingroup eZKernel

*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "lib/ezlocale/classes/ezdatetime.php" );
class eZProductCollection extends eZPersistentObject
{
    function eZProductCollection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZProductCollection",
                      "name" => "ezproductcollection" );
    }

    /*!
     Creates a new empty collection and returns it.
    */
    function &create( )
    {
        $row = array( "created" => eZDateTime::currentTimeStamp() );
        return new eZProductCollection( $row );
    }

    /*!
     Clones the collection object and returns it. The ID of the clone is erased.
    */
    function &clone()
    {
        $collection = $this;
        $collection->setAttribute( 'id', null );
        return $collection;
    }

    /*!
     Copies the collection object, the collection items and options.
     \return the new collection object.
     \note The new collection will already be present in the database.
    */
    function &copy()
    {
        $collection =& $this->clone();
        $collection->store();

        $oldItems =& $this->itemList();
        foreach ( array_keys( $oldItems ) as $oldItemKey )
        {
            $oldItem =& $oldItems[$oldItemKey];
            $item =& $oldItem->copy( $collection->attribute( 'id' ) );
        }
        return $collection;
    }

    /*!
     \return the product collection with ID \a $productCollectionID.
    */
    function &fetch( $productCollectionID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCollection::definition(),
                                                null,
                                                array( 'id' => $productCollectionID ),
                                                $asObject );
    }

    /*!
     \return all production collection items as an array.
    */
    function &itemList( $asObject = true )
    {
        $productItems =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                              null, array( "productcollection_id" => $this->ID ),
                                                              null,
                                                              null,
                                                              $asObject );
        return $productItems;
    }

    function &verify( $id )
    {
        $productItemList =& eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(),
                                                                 null, array( "productcollection_id" => $id ),
                                                                 null,
                                                                 null,
                                                                 true );
        $isValid = true;
        $invalidItemArray = array();
        foreach ( array_keys( $productItemList ) as $key )
        {
            $productItem =& $productItemList[$key];

            if ( !$productItem->verify() )
            {
                $invalidItemArray[] =& $productItem;
                //  eZDebug::writeDebug( $productItem , "invalid item" );
                $isValid = false;
            }
        }
        if ( !$isValid )
        {
            return $invalidItemArray;
        }
        return $isValid;
    }

}

?>
