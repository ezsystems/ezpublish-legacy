<?php
//
// Definition of eZDiscountRule class
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
  \class eZDiscountRule ezdiscountrule.php
  \brief The class eZDiscountRule does

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZDiscountRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountRule( $row )
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
                                                         'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZDiscountRule",
                      "name" => "ezdiscountrule" );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZDiscountRule::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountRule::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &create()
    {
        $row = array(
            "id" => null,
            "name" => ezi18n( "kernel/shop/discountgroup", "New discount group" ) );
        return new eZDiscountRule( $row );
    }

    function &remove( $id )
    {
        eZPersistentObject::removeObject( eZDiscountRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
