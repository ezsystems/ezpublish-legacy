<?php
/**
 * File containing the eZOrderItem class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

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

class eZOrderItem extends eZPersistentObject
{
    public $VATValue;
    public $Price;
    static function definition()
    {
        return array( "fields" => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'order_id' => array( 'name' => 'OrderID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZOrder',
                                                              'foreign_attribute' => 'id',
                                                              'multiplicity' => '1..*' ),
                                         'description' => array( 'name' => 'Description',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'price' => array( 'name' => 'Price',
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'vat_value' => array( 'name' => 'VATValue',
                                                               'datatype' => 'float',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'is_vat_inc' => array( 'name' => 'IsVATIncluded',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'type' => array( 'name' => 'Type',
                                                          'datatype' => 'string',
                                                          'required' => false ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'vat_value' => 'vatValue',
                                                      'price_inc_vat' => 'priceIncVat',
                                                      'price_ex_vat' => 'priceExVAT' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZOrderItem',
                      'name' => 'ezorder_item' );
    }

    static function fetchList( $orderID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZOrderItem::definition(),
                                                    null,
                                                    array( "order_id" => $orderID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function fetchListByType( $orderID, $itemType, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZOrderItem::definition(),
                                                    null,
                                                    array( 'order_id' => $orderID, 'type' => $itemType ),
                                                    null,
                                                    null,
                                                    $asObject );

    }

    function vatValue()
    {
        return $this->VATValue;
    }

    function priceIncVAT()
    {
        if ( $this->attribute( 'is_vat_inc' ) == 1 )
        {
            return $this->Price;
        }
        else
        {
            $incVATPrice = $this->Price * ( $this->vatValue() + 100 ) / 100;
            return $incVATPrice;
        }

    }

    function priceExVAT()
    {
        if ( $this->attribute( 'is_vat_inc' ) == 1 )
        {
            return $this->Price / ( $this->vatValue() + 100 ) * 100;
        }

        return $this->Price;
    }

    /*!
     \static
     Removes all order items from the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezorder_item" );
    }
}

?>
