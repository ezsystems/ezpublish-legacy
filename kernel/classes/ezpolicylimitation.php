<?php
//
// Definition of eZPolicyLimitation class
//
// Created on: <19-Aug-2002 10:57:01 sp>
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

/*! \file ezpolicylimitation.php
*/

/*!
  \class eZPolicyLimitation ezpolicylimitation.php
  \brief The class eZPolicyLimitation does

*/
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpolicylimitationvalue.php" );

class eZPolicyLimitation extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicyLimitation( $row )
    {
          $this->eZPersistentObject( $row );
    }
    

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         'policy_id' => 'PolicyID',
                                         'identifier' => 'Identifier',
                                         'role_id' => 'RoleID',
                                         'function_name' => 'FunctionName',
                                         'module_name' => 'ModuleName',
                                         ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'values' => 'valueList',
                                                      'values_as_array' => 'allValues',
                                                      'values_as_string' => 'allValuesAsString'),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZPolicyLimitation",
                      "name" => "ezpolicy_limitation" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function attribute( $attr )
    {
        if ( $attr == "values" )
        {
            return $this->valueList();
        }elseif ( $attr == "values_as_string" )
        {
            return $this->allValuesAsString();
        }elseif ( $attr == "values_as_array" )
        {
            return $this->allValues();
        }
        return eZPersistentObject::attribute( $attr );
    }

    function createNew( $policyID, $identifier )
    {
        $policyParameter = new eZPolicyLimitation( array() );
        $policyParameter->setAttribute( 'policy_id', $policyID );
        $policyParameter->setAttribute( 'identifier', $identifier );
        $policyParameter->store();

        return $policyParameter;
    }

    function copy( $policyID )
    {
        $newParameter = eZPolicyLimitation::createNew( $policyID, $this->attribute( 'identifier' ) );
        foreach( $this->attribute( 'values' ) as $value )
        {
            $value->copy( $newParameter->attribute( 'id' ) );
        }
    }
    function remove( $id = false )
    {
        if ( is_numeric( $id ) )
        {
            $delID = $id;
//            $policyParameter =& eZPolicyLimitation::fetch( $delID );
        }
        else
        {
//            $policyParameter =& $this;
            $delID = $this->ID;
        }

        $db =& eZDB::instance();

        $db->query( "DELETE FROM ezpolicy_limitation_value 
                     WHERE ezpolicy_limitation_value.limitation_id = '$delID'" );

        $db->query( "DELETE FROM ezpolicy_limitation
                     WHERE ezpolicy_limitation.id = '$delID' " );
    }

    function allValuesAsString()
    {
        $str='';
        foreach ( $this->attribute( 'values' ) as $value )
        {
            if ( $str == '' )
            {
                $str .= $value->attribute( 'value' );
            }else
            {
                $str .= ',' . $value->attribute( 'value' );
            }
        }
        return $str;
             
    }
    function allValues()
    {
        $values = array();
        foreach ( $this->attribute( 'values' ) as $value )
        {
                $values[] =  $value->attribute( 'value' );

        }
        return $values;
             
    }

    function valueList()
    {
        if ( !isset( $this->Values ) )
        {
            $values =& eZPersistentObject::fetchObjectList( eZPolicyLimitationValue::definition(),
                                                              null, array( 'limitation_id' => $this->attribute( 'id') ), null, null,
                                                               true);
            $this->Values =& $values;
            
        }

        return $this->Values;

    }


}

?>
