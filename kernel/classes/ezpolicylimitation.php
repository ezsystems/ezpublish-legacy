<?php
//
// Definition of eZPolicyLimitation class
//
// Created on: <19-Aug-2002 10:57:01 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezpolicylimitation.php
*/

/*!
  \class eZPolicyLimitation ezpolicylimitation.php
  \ingroup eZRole
  \brief Defines a limitation for a policy in the permission system

*/
include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpolicylimitationvalue.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZPolicyLimitation extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicyLimitation( $row )
    {
          $this->eZPersistentObject( $row );
          $this->NodeID = 0;
    }

    function definition()
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
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'policy' => 'policy',
                                                      'values' => 'valueList',
                                                      'values_as_array' => 'allValues',
                                                      'values_as_string' => 'allValuesAsString',
                                                      'values_as_array_with_names' => 'allValuesAsArrayWithNames',
                                                      'limit_value' => 'limitValue' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZPolicyLimitation",
                      "name" => "ezpolicy_limitation" );
    }

    function &limitValue()
    {
        return $this->LimitValue;
    }

    /*!
     Get policy object of this policy limitation
    */
    function &policy()
    {
        include_once( 'kernel/classes/ezpolicy.php' );
        $policy = eZPolicy::fetch( $this->attribute( 'policy_id' ) );
        return $policy;
    }

    /*!
     \reimp
    */
    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'limit_value':
            {
                $this->LimitValue = $val;
            } break;

            default:
            {
                eZPersistentObject::setAttribute( $attr, $val );
            } break;
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function createNew( $policyID, $identifier )
    {
        $policyParameter = new eZPolicyLimitation( array() );
        $policyParameter->setAttribute( 'policy_id', $policyID );
        $policyParameter->setAttribute( 'identifier', $identifier );
        $policyParameter->store();

        return $policyParameter;
    }

    /*!
     \static
     Create a new policy limitation for the policy \a $policyID with the identifier \a $identifier.
     \note The limitation is not stored.
    */
    function &create( $policyID, $identifier )
    {
        $row = array( 'id' => null,
                      'policy_id' => $policyID,
                      'identifier' => $identifier );
        $limitation = new eZPolicyLimitation( $row );
        return $limitation;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeSelected( $ID )
    {
        eZPersistentObject::removeObject( eZPolicyLimitation::definition(),
                                          array( "id" => $ID ) );
    }

    function fetchByIdentifier( $policyID, $identifier, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZPolicyLimitation::definition(),
                                                null,
                                                array( "policy_id" => $policyID,
                                                       "identifier" => $identifier ),
                                                $asObject );
    }

    function fetchByPolicyID( $policyID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZPolicyLimitation::definition(),
                                                    null,
                                                    array( "policy_id" => $policyID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function copy( $policyID )
    {
        $newParameter = eZPolicyLimitation::createNew( $policyID, $this->attribute( 'identifier' ) );
        foreach( $this->attribute( 'values' ) as $value )
        {
            $value->copy( $newParameter->attribute( 'id' ) );
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove( $id = false )
    {
        if ( is_numeric( $id ) )
        {
            $delID = $id;
//            $policyParameter = eZPolicyLimitation::fetch( $delID );
        }
        else
        {
//            $policyParameter =& $this;
            $delID = $this->ID;
        }

        $db =& eZDB::instance();
        $db->begin();

        $db->query( "DELETE FROM ezpolicy_limitation_value
                     WHERE ezpolicy_limitation_value.limitation_id = '$delID'" );

        $db->query( "DELETE FROM ezpolicy_limitation
                     WHERE ezpolicy_limitation.id = '$delID' " );
        $db->commit();
    }

    function &allValuesAsString()
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
        $policy =& $this->attribute( 'policy' );
        if ( !$policy )
        {
            $retValue = null;
            return $retValue;
        }

        $currentModule = $policy->attribute( 'module_name' );
        $mod = & eZModule::exists( $currentModule );
        $functions =& $mod->attribute( 'available_functions' );
        $functionNames = array_keys( $functions );

        $currentFunction = $policy->attribute( 'function_name' );
        $limitationValueArray = array();

        $limitation =& $functions[ $currentFunction ][$this->attribute( 'identifier' )];

//        eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitation, "limitation" );
        if ( $limitation &&
             count( $limitation[ 'values' ] == 0 ) &&
             array_key_exists( 'class', $limitation ) )
        {
            include_once( 'kernel/' . $limitation['path'] . $limitation['file']  );
            $obj = new $limitation['class']( array() );
            $limitationValueList = call_user_func_array ( array( &$obj , $limitation['function']) , $limitation['parameter'] );
//            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitationValueList, "limitationList" );
            foreach( $limitationValueList as $limitationValue )
            {
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $limitationValue[ 'name' ];
                $limitationValuePair['value'] = $limitationValue[ 'id' ];
                $limitationValueArray[] = $limitationValuePair;
            }
        }
        else if ( $limitation['name'] == "Node" )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            foreach ( $valueList as $value )
            {
                $node = eZContentObjectTreeNode::fetch( $value );
                if ( $node == null )
                    continue;
                $limitationValuePair = array();
                $limitationValuePair['Name'] = $node->attribute( 'name' );
                $limitationValuePair['value'] = $value;
                $limitationValueArray[] = $limitationValuePair;
            }
        }
        else if ( $limitation['name'] == "Subtree" )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            foreach ( $valueList as $value )
            {
                $subtreeObject = eZContentObjectTreeNode::fetchByPath( $value );
                if ( $subtreeObject != null )
                {
                    $subtreeID = $subtreeObject->attribute( 'node_id' );
                    $subtree = eZContentObjectTreeNode::fetch( $subtreeID );
                    $limitationValuePair = array();
                    $limitationValuePair['Name'] = $subtree->attribute( 'name' );
                    $limitationValuePair['value'] = $value;
                    $limitationValueArray[] = $limitationValuePair;
                }
            }
        }
        else
        {
            $limitationValueArray = $limitation[ 'values' ];
        }
        $limitationValuesWithNames = array();
        foreach ( array_keys( $valueList ) as $key )
        {
            $value = $valueList[$key];
//            eZDebugSetting::writeDebug( 'kernel-policy-limitation', $value, "value" );

            if ( isset( $limitationValueArray ) )
            {
                reset( $limitationValueArray );
                foreach ( array_keys( $limitationValueArray ) as $ckey )
                {
                    if ( $value == $limitationValueArray[$ckey]['value'] )
                    {
                        $limitationValuesWithNames[] =& $limitationValueArray[$ckey];
                    }
                }
            }
        }

        return $limitationValuesWithNames;
    }

    /*!
     Get limitation array

     \return access limitation array
    */
    function limitArray()
    {
        $limitValues =& $this->attribute( 'values' );

        $valueArray = array();

        foreach ( array_keys( $limitValues ) as $valueKey )
        {
            $valueArray[] = $limitValues[$valueKey]->attribute( 'value' );
        }

        return array( $this->attribute( 'identifier' ) => $valueArray );
    }

    function &allValues()
    {
        $values = array();
        foreach ( $this->attribute( 'values' ) as $value )
        {
                $values[] = $value->attribute( 'value' );
        }

        return $values;
    }

    function &valueList()
    {
        if ( !isset( $this->Values ) )
        {
            $values = eZPersistentObject::fetchObjectList( eZPolicyLimitationValue::definition(),
                                                            null, array( 'limitation_id' => $this->attribute( 'id') ), null, null,
                                                            true);

            if ( $this->LimitValue )
            {
                $values[] = new eZPolicyLimitationValue( array ( 'id' => -1,
                                                                 'value' => $this->LimitValue ) );
            }

            $this->Values =& $values;
        }

        return $this->Values;
    }

    function findByType( $type, $value, $asObject = true, $useLike = true )
    {
        $cond = '';
        $db = eZDB::instance();
        $value = $db->escapeString( $value );
        $type = $db->escapeString( $type );
        if ( $useLike === true )
        {
            $cond = "ezpolicy_limitation_value.value like '$value%' ";
        }
        else
        {
            $cond = "ezpolicy_limitation_value.value = '$value' ";
        }

        $query = "SELECT DISTINCT ezpolicy_limitation.*
                  FROM ezpolicy_limitation,
                       ezpolicy_limitation_value
                  WHERE
                       ezpolicy_limitation.identifier = '$type' AND
                       $cond AND
                       ezpolicy_limitation_value.limitation_id =  ezpolicy_limitation.id";

        $dbResult = $db->arrayQuery( $query );
        $resultArray = array();
        $resultCount = count( $dbResult );
        for( $i = 0; $i < $resultCount; $i++ )
        {
            if ( $asObject )
            {
                $resultArray[] = new eZPolicyLimitation( $dbResult[$i] );
            }
            else
            {
                $resultArray[] = $dbResult[$i]['id'];
            }
        }
        return $resultArray;
    }

    // Used for assign subtree matching
    var $LimitValue;

}

?>
