<?php
//
// Definition of eZPolicyLimitation class
//
// Created on: <19-Aug-2002 10:57:01 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'policy_id' => array( 'name' => 'PolicyID',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'identifier' => array( 'name' => 'Identifier',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         'role_id' => array( 'name' => 'RoleID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'function_name' => array( 'name' => 'FunctionName',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'module_name' => array( 'name' => 'ModuleName',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'values' => 'valueList',
                                                      'values_as_array' => 'allValues',
                                                      'values_as_string' => 'allValuesAsString',
                                                      'values_as_array_with_names' => 'allValuesAsArrayWithNames'
                                                      ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZPolicyLimitation",
                      "name" => "ezpolicy_limitation" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function & attribute( $attr )
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
        }elseif ( $attr == "values_as_array_with_names" )
        {
            return $this->allValuesAsArrayWithNames();
        }
        return eZPersistentObject::attribute( $attr );
    }

    function createNew( $policyID, $identifier, $moduleName, $functionName )
    {
        $policyParameter = new eZPolicyLimitation( array() );
        $policyParameter->setAttribute( 'policy_id', $policyID );
        $policyParameter->setAttribute( 'identifier', $identifier );
        $policyParameter->setAttribute( 'function_name', $functionName );
        $policyParameter->setAttribute( 'module_name', $moduleName );
        $policyParameter->store();

        return $policyParameter;
    }

    function copy( $policyID )
    {
        $newParameter = eZPolicyLimitation::createNew( $policyID, $this->attribute( 'identifier' ),$this->attribute( 'module_name' ),$this->attribute( 'function_name' ) );
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
    function &allValuesAsArrayWithNames()
    {
        $valueList =& $this->attribute( 'values_as_array' );
        $names = array();
//        if ( $this->attribute( 'identifier' );
        $currentModule = $this->attribute( 'module_name' );
        $mod = & eZModule::exists( $currentModule );
        $functions =& $mod->attribute( 'aviable_functions' );
        $functionNames = array_keys( $functions );


        $currentFunction = $this->attribute( 'function_name' );
        $limitationValueArray =  array();

        $limitation =& $functions[ $currentFunction ][$this->attribute( 'identifier' )];
        eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitation, "limitation" );
        if( count( $limitation[ 'values' ] == 0 ) && array_key_exists( 'class', $limitation ) )
        {
            include_once( 'kernel/' . $limitation['path'] . $limitation['file']  );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array ( array( &$obj , $limitation['function']) , $limitation['parameter'] );
            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitationValueList, "limitationList" );
            foreach( $limitationValueList as $limitationValue )
            {
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $limitationValue[ 'name' ];
                $limitationValuePair['value'] = $limitationValue[ 'id' ];
                $limitationValueArray[] = $limitationValuePair;
            }
        }else
        {
            $limitationValueArray = $limitation[ 'values' ];
        }
        $limitationValuesWithNames = array();
        foreach ( array_keys( $valueList ) as $key )
        {
            $value = $valueList[$key];
            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $value, "value" );

            reset ( $limitationValueArray );
            foreach ( array_keys( $limitationValueArray ) as $ckey )
            {
                if ( $value == $limitationValueArray[$ckey]['value'] )
                {
                    $limitationValuesWithNames[] =& $limitationValueArray[$ckey];
                }
            }
        }
        return $limitationValuesWithNames;
    }
    function & allValues()
    {
        $values = array();
        foreach ( $this->attribute( 'values' ) as $value )
        {
                $values[] =  $value->attribute( 'value' );

        }
        return $values;
    }

    function & valueList()
    {
        eZDebugSetting::writeDebug( 'kernel-policy-limitation', "valueList call" );
        if ( !isset( $this->Values ) )
        {

            $ini =& eZINI::instance();
            $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );
//            $enableCaching = false;
            $loadFromDb = true;
            $limitationID = $this->attribute( 'id' );
            if ( $enableCaching == 'true' )
            {
                $http =& eZHTTPTool::instance();

                $hasLimitationValuesInCache = $http->hasSessionVariable( 'userLimitationValues' );
                if ( $hasLimitationValuesInCache )
                {
                    $limitationValuesForAllUserLimitations =& $http->sessionVariable( 'userLimitationValues' );
                    $limitationValuesForCurrentLimitation =& $limitationValuesForAllUserLimitations["$limitationID"];
                    if ( count( $limitationValuesForCurrentLimitation ) > 0 )
                    {
                        $limitationValues = array();
                        foreach ( array_keys( $limitationValuesForCurrentLimitation ) as $key )
                        {
                            $limitationValueRow = $limitationValuesForCurrentLimitation[$key];
                            $limitationValues[] =& new eZPolicyLimitationValue( $limitationValueRow );
                        }
                        eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitationValues, "using cached  limitationValues for limitation_id=$limitationID" );
                        $this->Values =& $limitationValues;
                        $loadFromDb = false;
                    }
                }

            }
            if ( $loadFromDb )
            {
                $values =& eZPersistentObject::fetchObjectList( eZPolicyLimitationValue::definition(),
                                                                null, array( 'limitation_id' => $this->attribute( 'id') ), null, null,
                                                                true);
                if ( $enableCaching )
                {
                    $limitationValues =& $values;
                    $limitationValuesForCurrentLimitation = array();
                    foreach ( array_keys( $limitationValues ) as $key )
                    {
                        $limitationValue =& $limitationValues[$key];
                        $limitationValueAttributes = array();
                        $limitationValueAttributes['id'] = $limitationValue->attribute( 'id' );
                        $limitationValueAttributes['limitation_id'] = $limitationValue->attribute( 'limitation_id' );
                        $limitationValueAttributes['value'] = $limitationValue->attribute( 'value' );
                        $limitationValuesForCurrentLimitation[] = $limitationValueAttributes;
                    }
                    $http =& eZHTTPTool::instance();
                    if ( !$http->hasSessionVariable( 'userLimitationValues' ) )
                    {
                        $limitationValueArray =& $http->sessionVariable( 'userLimitationValues' );
                    }
                    else
                    {
                        $limitationValueArray = array();
                    }

                    eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitationValueArray, "using limitationValues from db for limitation_id=$limitationID" );
                    $limitationValueArray["$limitationID"] = $limitationValuesForCurrentLimitation;

                }
                $this->Values =& $values;
            }
        }
        return $this->Values;
    }
}

?>
