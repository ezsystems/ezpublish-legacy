<?php
//
// Definition of eZDiscountSubRule class
//
// Created on: <27-Nov-2002 13:05:59 wy>
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

/*! \file ezdiscountrule.php
*/

/*!
  \class eZDiscountSubRuleValue ezdiscountsubrule.php
  \brief The class eZDiscountSubRuleValue does

*/
include_once( "kernel/classes/ezpersistentobject.php" );
class eZDiscountSubRuleValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountSubRuleValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "discountsubrule_id" => array( 'name' => "DiscountSubRuleID",
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true ),
                                         "value" => array( 'name' => "Value",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "issection" => array( 'name' => "IsSection",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "discountsubrule_id", "value", "isSection" ),
                      "increment_key" => "discountsubrule_id",
                      "class_name" => "eZDiscountSubRuleValue",
                      "name" => "ezdiscountsubrule_value" );
    }

    function &fetchBySubRuleID( $discountSubRuleID, $isSection = 0, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRuleValue::definition(),
                                                    null,
                                                    array( "discountsubrule_id" => $discountSubRuleID,
                                                           "issection" => $isSection ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &remove( $discountSubRuleID, $value, $isSection )
    {
        eZPersistentObject::removeObject( eZDiscountSubRuleValue::definition(),
                                           array( "discountsubrule_id" =>$discountSubRuleID,
                                                  "value" => $value,
                                                  "issection" => $isSection ) );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRuleValue::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &create( $discountSubRuleID, $value, $isSection = false )
    {
        $row = array(
            "discountsubrule_id" => $discountSubRuleID,
            "value" => $value,
            "issection" => $isSection );
        return new eZDiscountSubRuleValue( $row );
    }

    function &removeBySubRuleID ( $discountSubRuleID )
    {
        eZPersistentObject::removeObject( eZDiscountSubRuleValue::definition(),
                                          array( "discountsubrule_id" => $discountSubRuleID ) );
    }
}
?>
