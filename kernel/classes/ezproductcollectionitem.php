<?php
//
// Definition of eZProductCollectionItem class
//
// Created on: <04-Jul-2002 13:45:10 bf>
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
  \class eZProductCollectionItem ezproductcollection.php
  \brief eZProductCollectionItem handles one product item
  \ingroup eZKernel

*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobject.php" );

class eZProductCollectionItem extends eZPersistentObject
{
    function eZProductCollectionItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "productcollection_id" => "ProductCollectionID",
                                         "contentobject_id" => "ContentObjectID",
                                         "item_count" => "ItemCount",
                                         "price" => "Price"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "relations" => array( "contentobject_id" => array( "class" => "ezcontentobject",
                                                                         "field" => "id" ),
                                            "productcollection_id" => array( "class" => "ezproductcollection",
                                                                             "field" => "id" ) ),
                      "class_name" => "eZProductCollectionItem",
                      "name" => "ezproductcollection_item" );
    }


    function &create( $productCollectionID )
    {
        $row = array(
            "productcollection_id" => $productCollectionID
            );
        return new eZProductCollectionItem( $row );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCollectionItem::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            case "contentobject" :
            {
                return $this->contentObject(  );
            }break;

            default :
            {
                return eZPersistentObject::attribute( $attr );
            }break;
        }
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "contentobject" )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    /*!
     \return the discount percent for the current item
    */
    function discountPercent()
    {
        $discount = false;

        return $discount;
    }

    /*!
     \return Returns the content object defining the product.
    */
    function &contentObject()
    {
        if ( $this->ContentObject === null )
        {
            $this->ContentObject =& eZContentObject::fetch( $this->ContentObjectID );
        }

        return $this->ContentObject;
    }

    /// Stores the content object
    var $ContentObject = null;
}

?>
