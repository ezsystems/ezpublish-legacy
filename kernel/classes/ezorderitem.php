<?php
//
// Created on: <05-Dec-2002 09:12:43 bf>
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

/*!
  \class eZOrderItem ezorderitem.php
  \brief eZOrderItem handles custom order items
  \ingroup eZKernel

  Custom order items are used to automatically add new items to
  a specific order. You can use it to e.g. specify shipping and
  handling, special discount or wrapping costs.

  The order items is different from the product collection items
  in the way that there is no product for each order item.

  \sa eZProductCollection eZBasket eZOrder
*/

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezvattype.php" );

class eZOrderItem extends eZPersistentObject
{
    function eZOrderItem( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'order_id' => array( 'name' => 'OrderID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'description' => array( 'name' => 'Description',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'price' => array( 'name' => 'Price',
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'vat_value' => array( 'name' => 'VATValue',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZOrderItem',
                      'name' => 'ezorder_item' );
    }

    function &fetchList( $orderID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZOrderItem::definition(),
                                                    null,
                                                    array( "order_id" => $orderID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function attribute( $attr )
    {
        if ( $attr == "vat_value" )
            return $this->vatValue();
        else if ( $attr == "price_inc_vat" )
            return $this->priceIncVAT();
        else if ( $attr == "price_ex_vat" )
            return $this->priceExVAT();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        if ( $attr == "vat_value" )
            return true;
        else if ( $attr == "price_inc_vat" )
            return true;
        else if ( $attr == "price_ex_vat" )
            return true;
        else
            return eZPersistentObject::hasAttribute( $attr );
    }

    function &vatValue()
    {
        if ( $this->VATValue === false )
        {
            $vatType =& eZVATType::fetch( $this->VATTypeID );
            $this->VATValue = $vatType->attribute( 'percentage' );
        }

        return $this->VATValue;
    }

    function &priceIncVAT()
    {
        if ( $this->IsVATIncluded )
        {
            return $this->Price;
        }
        else
        {
            $incVATPrice = $this->Price * ( $this->vatValue() + 100 ) / 100;
            return $incVATPrice;
        }

    }

    function &priceExVAT()
    {
        if ( $this->IsVATIncluded )
        {
            $exVATPrice = $this->Price / ( $this->vatValue() + 100 ) * 100;
            return $exVATPrice;
        }
        else
            return $this->Price;

    }

    /*!
     \static
     Removes all order items from the database.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezorder_item" );
    }

    /// Cached value of the vat percentage
    var $VATValue = false;
    var $IsVATIncluded = false;
}

?>
