<?php
//
// Definition of eZPolicy class
//
// Created on: <16-Aug-2002 16:34:41 sp>
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

/*! \file ezpolicy.php
*/

/*!
  \class eZPolicy ezpolicy.php
  \brief The class eZPolicy does

*/

include_once( 'kernel/classes/ezpolicylimitation.php' );

class eZPolicy extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicy( $row )
    {
          $this->eZPersistentObject( $row );
          $this->NodeID = 0;
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'role_id' => array( 'name' => 'RoleID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'module_name' => array( 'name' => 'ModuleName',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'function_name' => array( 'name' => 'FunctionName',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( 'limitations' => 'limitationList' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => 'eZPolicy',
                      'name' => 'ezpolicy' );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function & attribute( $attr )
    {
        switch( $attr )
        {
            case 'limit_identifier':
            {
                return $this->LimitIdentifier;
            } break;

            case 'limit_value':
            {
                return $this->LimitValue;
            } break;

            default:
            {
                return eZPersistentObject::attribute( $attr );
            } break;
        }
    }

    /*!
     \reimp
    */
    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'limit_identifier':
            {
                if ( !$this->LimitIdentifier )
                {
                    $this->LimitIdentifier = $val;
                }
            } break;

            case 'limit_value':
            {
                if ( !$this->LimitValue )
                {
                    $this->LimitValue = $val;
                }
            } break;

            default:
            {
                eZPersistentObject::setAttribute( $attr, $val );
            } break;
        }
    }

    function & createNew( $roleID , $params = array() )
    {
        $policy = new eZPolicy( array() );
        $policy->setAttribute( 'role_id', $roleID );
        if ( array_key_exists( 'ModuleName', $params ))
        {
            $policy->setAttribute( 'module_name', $params['ModuleName'] );
        }
        if ( array_key_exists( 'FunctionName', $params ))
        {
            $policy->setAttribute( 'function_name', $params['FunctionName'] );
        }
        $policy->store();

        return $policy;
    }

    /*!
     \static
     Creates a new policy assigned to the role identified by ID \a $roleID  and returns it.
     \note The policy is not stored.
     \param $module Which module to give access to or \c true to give access to all modules.
     \param $function Which function to give access to or \c true to give access to all functions.
     \param $limitations An associative array with limitations and their values, use an empty array for no limitations.
    */
    function &create( $roleID, $module, $function )
    {
        if ( $module === true )
            $module = '*';
        if ( $function === true )
            $function = '*';
        $row = array( 'id' => false,
                      'role_id' => $roleID,
                      'module_name' => $module,
                      'function_name' => $function );
        $policy =& new eZPolicy( $row );
        return $policy;
    }

    /*!
     Appends a new policy limitation to the current policy and returns it.
     \note The limitation and it's values will be stored to the database before returning.
     \param $identifier The identifier for the limitation, e.g. \c 'Class'
     \param $values Array of values to store for limitation.
    */
    function &appendLimitation( $identifier, $values )
    {
        include_once( 'kernel/classes/ezpolicylimitation.php' );
        include_once( 'kernel/classes/ezpolicylimitationvalue.php' );
        $limitation =& eZPolicyLimitation::create( $this->ID, $identifier );
        $limitation->store();
        $limitationID = $limitation->attribute( 'id' );
        $limitations = array();
        foreach ( $values as $value )
        {
            $limitationValue =& eZPolicyLimitationValue::create( $limitationID, $value );
            $limitationValue->store();
            if ( isset( $limitation->Values ) )
            {
                $limitation->Values[] =& $limitationValue;
            }
        }
        if ( isset( $this->Limitations ) )
        {
            $this->Limitations[] =& $limitation;
        }
        return $limitation;
    }

    function copy( $roleID )
    {
        $params = array();
        $params['ModuleName'] = $this->attribute( 'module_name' );
        $params['FunctionName'] = $this->attribute( 'function_name' );
        $newPolicy = eZPolicy::createNew( $roleID, $params  );
        foreach ( $this->attribute( 'limitations' ) as $limitation )
        {
            $limitation->copy( $newPolicy->attribute( 'id' ) );
        }
    }

    function remove( $id = false )
    {
        if ( is_numeric( $id ) )
        {
            $delID = $id;
            $policy =& eZPolicy::fetch( $delID );
        }
        else
        {
            $policy =& $this;
            $delID = $this->ID;
        }

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();
        foreach ( $policy->attribute( 'limitations' ) as $limitation )
        {
            $limitation->remove();
        }
        $db->query( "DELETE FROM ezpolicy
                     WHERE id='$delID'" );
    }

    /*!
     Generate access array from this policy.

     return access array
    */
    function &accessArray()
    {
        $limitations =& $this->attribute( 'limitations' );

        if ( !$limitations )
        {
            return array( $this->attribute( 'module_name' ) => array ( $this->attribute( 'function_name' ) => array( '*' => '*' ) ) );
        }

        $limitArray = array();

        foreach( array_keys( $limitations ) as $limitKey )
        {
            $limitArray = array_merge_recursive( $limitArray, $limitations[$limitKey]->limitArray() );
        }

        return array( $this->attribute( 'module_name' ) => array ( $this->attribute( 'function_name' ) => array( 'p_' . $this->attribute( 'id' ) => $limitArray ) ) );
    }

    /*!
     Fetch limitaion array()

     \param use limitation cache, true by default.
    */
    function &limitationList( $useCache = true )
    {
        if ( !isset( $this->Limitations ) || !$useCache )
        {

            $limitations =& eZPersistentObject::fetchObjectList( eZPolicyLimitation::definition(),
                                                                 null, array( 'policy_id' => $this->attribute( 'id') ), null, null,
                                                                 true );
            if ( $this->LimitIdentifier )
            {
                $policyLimitation = new eZPolicyLimitation( array ( 'id' => -1,
                                                                    'policy_id' => $this->attribute( 'id' ),
                                                                    'identifier' => $this->attribute( 'limit_identifier' ) ) );
                $policyLimitation->setAttribute( 'limit_value', $this->attribute( 'limit_value' ) );

                $limitations[] = $policyLimitation;
            }

            $this->Limitations =& $limitations;
        }

        return $this->Limitations;
    }

    function &fetch( $policyID )
    {
        return eZPersistentObject::fetchObject( eZPolicy::definition(),
                                                null, array('id' => $policyID ), true);

    }

    // Used for assign based limitations.
    var $LimitValue;
    var $LimitIdentifier;

}

?>
