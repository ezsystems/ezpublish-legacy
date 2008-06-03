<?php
//
// Definition of eZDiscountSubRule class
//
// Created on: <27-Nov-2002 13:05:59 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezdiscountrule.php
*/

/*!
  \class eZDiscountSubRuleValue ezdiscountsubrule.php
  \brief The class eZDiscountSubRuleValue does

*/
//include_once( "kernel/classes/ezpersistentobject.php" );
class eZDiscountSubRuleValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountSubRuleValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

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
