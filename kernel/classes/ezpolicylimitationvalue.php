<?php
//
// Definition of eZPolicyLimitationValue class
//
// Created on: <19-Aug-2002 11:28:06 sp>
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

/*! \file ezpolicylimitationvalue.php
*/

/*!
  \class eZPolicyLimitationValue ezpolicylimitationvalue.php
  \ingroup eZRole
  \brief Defines a limitation value for a policy in the permission system

*/
include_once( "kernel/classes/ezpersistentobject.php" );

class eZPolicyLimitationValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicyLimitationValue( $row )
    {
          $this->eZPersistentObject( $row );
    }


    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'limitation_id' => array( 'name' => 'LimitationID',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         'value' => array( 'name' => 'Value',
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array(),
                      "increment_key" => "id",
                      "sort" => array( "value" => "asc" ),
                      "class_name" => "eZPolicyLimitationValue",
                      "name" => "ezpolicy_limitation_value" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function attribute( $attr )
    {
        return eZPersistentObject::attribute( $attr );
    }


    function createNew( $limitationID, $value )
    {
        $limitationValue = new eZPolicyLimitationValue( array() );
        $limitationValue->setAttribute( 'limitation_id', $limitationID );
        $limitationValue->setAttribute( 'value', $value );
        $limitationValue->store();

        return $limitationValue;
    }

    /*!
     \static
     Creates a new limitation value for the limitation \a $limitationID and returns it.
     \note The value is not stored.
    */
    function &create( $limitationID, $value )
    {
        $row = array( 'id' => false,
                      'limitation_id' => $limitationID,
                      'value' => $value );
        $limitationValue = new eZPolicyLimitationValue( $row );
        return $limitationValue;
    }

    function copy( $limitationID )
    {
        $newValue = eZPolicyLimitationValue::createNew( $limitationID, $this->attribute( 'value' ) );
    }

    function &fetchList( $limitationID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZPolicyLimitationValue::definition(),
                                                    null,
                                                    array( 'limitation_id' => $limitationID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function removeByValue( $value, $policyID = false )
    {
        if ( $policyID )
        {
            $limitationIDList = array();
            $limitations =& eZPolicyLimitation::fetchByPolicyID( $policyID, false );
            foreach ( $limitations as $limitationArray )
            {
                $limitationIDList[] = $limitationArray['id'];
            }
            if  ( count( $limitationIDList ) > 0 )
            {
                eZPersistentObject::removeObject( eZPolicyLimitationValue::definition(),
                                                  array( 'limitation_id' => array( $limitationIDList  ),
                                                         "value" => $value ) );
                return;
            }
        }
        eZPersistentObject::removeObject( eZPolicyLimitationValue::definition(),
                                          array( "value" => $value ) );
    }

    function remove( $id = false )
    {
        if ( is_numeric( $id ) )
        {
            $delID = $id;
            $limitationValue =& eZPolicyLimitationValue::fetch( $delID );
        }
        else
        {
            $limitationValue =& $this;
            $delID = $this->ID;
        }

        $db =& eZDB::instance();

        $db->query( "DELETE FROM ezpolicy_limitation_value
                     WHERE ezpolicy_limitation_value.id = '$delID'" );
    }

}

?>
