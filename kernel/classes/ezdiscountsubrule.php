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

/*! \file ezdiscountrule.php
*/

/*!
  \class eZDiscountSubRule ezdiscountsubrule.php
  \brief The class eZDiscountSubRule does

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZDiscountSubRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountSubRule( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "discountrule_id" => array( 'name' => "DiscountRuleID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         "discount_percent" => array( 'name' => "DiscountPercent",
                                                                      'datatype' => 'float',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "limitation" => array( 'name' => "Limitation",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZDiscountSubRule",
                      "name" => "ezdiscountsubrule" );
    }

    /*!
     \reimp
    */
    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'discount_percent':
            {
                include_once( 'lib/ezlocale/classes/ezlocale.php' );
                $locale =& eZLocale::instance();

                eZPersistentObject::setAttribute( $attr, $locale->internalNumber( $val ) );
            } break;

            default:
            {
                eZPersistentObject::setAttribute( $attr, $val );
            } break;
        }
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZDiscountSubRule::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRule::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &fetchByRuleID( $discountRuleID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRule::definition(),
                                                    null,
                                                    array( "discountrule_id" => $discountRuleID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function &create( $discountRuleID )
    {
        $row = array(
            "id" => null,
            "name" => "New Discount Rule",
            "discountrule_id" => $discountRuleID,
            "discount_percent" => "",
            "limitation" => "*" );
        return new eZDiscountSubRule( $row );
    }

    function &remove ( $id )
    {
        eZPersistentObject::removeObject( eZDiscountSubRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
