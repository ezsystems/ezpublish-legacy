<?php
//
// Definition of eZProductCollectionItem class
//
// Created on: <04-Jul-2002 13:45:10 bf>
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
  \class eZProductCollectionItem ezproductcollection.php
  \brief eZProductCollectionItem handles one product item
  \ingroup eZKernel

*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezproductcollectionitemoption.php" );


class eZProductCollectionItem extends eZPersistentObject
{
    function eZProductCollectionItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "productcollection_id" => array( 'name' => "ProductCollectionID",
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "item_count" => array( 'name' => "ItemCount",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "price" => array( 'name' => "Price",
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'is_vat_inc' => array( 'name' => "IsVATIncluded",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'vat_value' => array( 'name' => "VATValue",
                                                               'datatype' => 'float',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'discount' => array( 'name' => "DiscountValue",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'function_attributes' => array( 'contentobject' => 'ContentObject',
                                                      'option_list' => 'OptionList' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "relations" => array( "contentobject_id" => array( "class" => "ezcontentobject",
                                                                         "field" => "id" ),
                                            "productcollection_id" => array( "class" => "ezproductcollection",
                                                                             "field" => "id" ) ),
                      "class_name" => "eZProductCollectionItem",
                      "name" => "ezproductcollection_item" );
    }

    /*!
     Creates a new empty collection item which belongs to
     collection \a $collectionID and returns it.
    */
    function &create( $productCollectionID )
    {
        $row = array( "productcollection_id" => $productCollectionID );
        return new eZProductCollectionItem( $row );
    }

    /*!
     Clones the collection item object and returns it. The ID of the clone is erased.
    */
    function &clone()
    {
        $item = $this;
        $item->setAttribute( 'id', null );
        return $item;
    }

    /*!
     Copies the collection object item and the option,
     the new copy will point to the collection \a $collectionID.
     \return the new collection item object.
     \note The new collection item will already be present in the database.
    */
    function &copy( $collectionID )
    {
        $item =& $this->clone();
        $item->setAttribute( 'productcollection_id', $collectionID );
        $item->store();
        $oldItemOptionList =& $this->optionList();
        foreach ( array_keys( $oldItemOptionList ) as $oldItemOptionKey )
        {
            $oldItemOption =& $oldItemOptionList[$oldItemOptionKey];
            $itemOption =& $oldItemOption->copy( $item->attribute( 'id' ) );
        }
        return $item;
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZProductCollectionItem::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case "contentobject" :
            {
                return $this->contentObject(  );
            }break;
            case "option_list" :
            {
                return $this->optionList(  );
            }break;

            default :
            {
                return eZPersistentObject::attribute( $attr );
            }break;
        }
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "contentobject" || $attr == 'option_list' )
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

    function &optionList()
    {
        return eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
    }

    function remove()
    {
        $itemOptionList =& eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
        foreach( array_keys( $itemOptionList ) as $key )
        {
            $itemOption =& $itemOptionList[$key];
            $itemOption->remove();
        }
        eZPersistentObject::remove();
    }

    function calculatePriceWithOptions()
    {
         $optionList =& eZProductCollectionItemOption::fetchList( $this->attribute( 'id' ) );
         $contentObject =& $this->contentObject();
         $contentObjectVersion =& $contentObject->attribute( 'current_version' );
         $optionsPrice = 0.0;
         foreach( $optionList as $option )
         {
             $objectAttribute =& eZContentObjectAttribute::fetch( $option->attribute( 'object_attribute_id' ), $contentObjectVersion );
             if ( $objectAttribute == null )
             {
                 $optionsPrice += 0.0;
                 continue;
             }
             $optionContent = $objectAttribute->content();
             $optionContentItems =& $optionContent->attribute( 'option_list' );
             $optionFound = false;
             foreach( $optionContentItems as $optionContentItem )
             {
                 if ( $optionContentItem['id'] == $option->attribute( 'option_item_id' ) &&
                      $optionContent->name() == $option->attribute( 'name' ) &&
                      $optionContentItem['value'] == $option->attribute( 'value' ) )
                 {

                     $optionFound = true;
                     $optionsPrice += $optionContentItem['additional_price'];

/*                     if ( $optionContentItem[ 'additional_price' ] == $option->attribute( 'price' ) )
                     {
                         $optionFound = true;
                         $optionsPrice += $optionContentItem['additional_price'];
                     }
                     else
                     {
                         $optionFound = false;
                         return false;

                     }
*/
                 }

             }
         }
         return $optionsPrice;
    }

    function verify()
    {
        $contentObject =& $this->attribute( 'contentobject' );
        if ( $contentObject != null && $contentObject->attribute( 'main_node_id' ) > 0 )
        {
            $attributes = $contentObject->contentObjectAttributes();
            $optionsPrice = $this->calculatePriceWithOptions();
/*            if (  $optionsPrice === false )
            {
                eZDebug::writeDebug( $optionPrice , "Option price is not the same" );

                return false;
            } */
            foreach ( $attributes as $attribute )
            {
                $dataType =& $attribute->dataType();
                if ( $dataType->isA() == "ezprice" )
                {
                    $priceObj =& $attribute->content();

                    $price = $priceObj->attribute( 'price' );
                    $priceWithOptions = $price + $optionsPrice;
                    if ( $priceWithOptions != $this->attribute( 'price' ) )
                    {
                        return false;
                    }
                    if ( $priceObj->attribute( 'is_vat_included' ) != ( $this->attribute( 'is_vat_inc' ) > 0 ) )
                    {
                        return false;
                    }
                    if ( $priceObj->attribute( 'vat_percent' ) != $this->attribute( 'vat_value' ) )
                    {
                        return false;
                    }
                    if ( $priceObj->discount() != $this->attribute( 'discount' ) )
                    {
                        return false;
                    }
                    return true;
                }
            }

        }
        return false;
    }

    /*!
     \static
     Removes all product collection items which related to the product collections specified in the array \a $productCollectionIDList.
    */
    function cleanupList( $productCollectionIDList )
    {
        $db =& eZDB::instance();
        $idText = implode( ', ', $productCollectionIDList );
        $rows = $db->arrayQuery( "SELECT id FROM ezproductcollection_item WHERE productcollection_id IN ( $idText )" );
        if ( count( $rows ) > 0 )
        {
            $itemIDList = array();
            foreach ( $rows as $row )
            {
                $itemIDList[] = $row['id'];
            }
            include_once( 'kernel/classes/ezproductcollectionitemoption.php' );
            eZProductCollectionItemOption::cleanupList( $itemIDList );
        }
        $db->query( "DELETE FROM ezproductcollection_item WHERE productcollection_id IN ( $idText )" );
    }

    /// Stores the content object
    var $ContentObject = null;
}

?>
