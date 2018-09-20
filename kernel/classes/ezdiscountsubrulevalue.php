<?php
/**
 * File containing the eZDiscountSubRule class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZDiscountSubRuleValue ezdiscountsubrule.php
  \brief The class eZDiscountSubRuleValue does

*/
class eZDiscountSubRuleValue extends eZPersistentObject
{
    static function definition()
    {
        return array( "fields" => array( "discountsubrule_id" => array( 'name' => "DiscountSubRuleID",
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true,
                                                                        'foreign_class' => 'eZDiscountSubRule',
                                                                        'foreign_attribute' => 'id',
                                                                        'multiplicity' => '1..*' ),
                                         "value" => array( 'name' => "Value",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "issection" => array( 'name' => "IsSection",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "discountsubrule_id", "value", "issection" ),
                      "increment_key" => "discountsubrule_id",
                      "class_name" => "eZDiscountSubRuleValue",
                      "name" => "ezdiscountsubrule_value" );
    }

    static function fetchBySubRuleID( $discountSubRuleID, $isSection = 0, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRuleValue::definition(),
                                                    null,
                                                    array( "discountsubrule_id" => $discountSubRuleID,
                                                           "issection" => $isSection ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRuleValue::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function create( $discountSubRuleID, $value, $isSection = false )
    {
        $row = array( "discountsubrule_id" => $discountSubRuleID,
                      "value" => $value,
                      "issection" => $isSection );
        return new eZDiscountSubRuleValue( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeBySubRuleID ( $discountSubRuleID )
    {
        eZPersistentObject::removeObject( eZDiscountSubRuleValue::definition(),
                                          array( "discountsubrule_id" => $discountSubRuleID ) );
    }
}
?>
