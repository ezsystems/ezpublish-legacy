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

/*! \file ezpolicy.php
*/

/*!
  \class eZPolicy ezpolicy.php
  \brief The class eZPolicy does

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpolicylimitation.php" );

class eZPolicy extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZPolicy( $row )
    {
          $this->eZPersistentObject( $row );
    }
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
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
                                                                   'required' => true ),
                                         'limitation' => array( 'name' => 'Limitation',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'limitations' => 'limitationList' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZPolicy",
                      "name" => "ezpolicy" );
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function &attribute( $attr )
    {
        if ( $attr == "limitations" )
            return $this->limitationList();

        return eZPersistentObject::attribute( $attr );
    }

    function &createNew( $roleID , $params = array() )
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
        if ( array_key_exists( 'Limitation', $params ))
        {
            $policy->setAttribute( 'limitation', $params['Limitation'] );
        }
        $policy->store();

        return $policy;
    }

    function copy( $roleID )
    {
        $params = array();
        $params['ModuleName'] = $this->attribute( 'module_name' );
        $params['FunctionName'] = $this->attribute( 'function_name' );
        $params['Limitation']  = $this->attribute( 'limitation' );
        $newPolicy = eZPolicy::createNew( $roleID, $params  );
        if ( $this->attribute( 'limitation' ) != '*' )
        {
            foreach ( $this->attribute( 'limitations' ) as $limitation )
            {
                $limitation->copy( $newPolicy->attribute( 'id' ) );
            }
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

        if ( $policy === null )
            return;

        $db =& eZDB::instance();
        if ( $policy->attribute( 'limitation' ) != '*' )
        {
            foreach ( $policy->attribute( 'limitations' ) as $limitation )
            {
                $limitation->remove();
            }
        }
        $db->query( "DELETE FROM ezpolicy
                     WHERE id='$delID'" );
    }

    function &limitationList( $useAvailCache = true )
    {
        if ( !isset( $this->Limitations ) )
        {

            $ini =& eZINI::instance();
            $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

            $http =& eZHTTPTool::instance();
            if ( $http->hasSessionVariable( 'DisableRoleCache' ) and
                 $http->sessionVariable( 'DisableRoleCache' ) == 1 )
            {
                $enableCaching = false;
            }

            $loadFromDb = true;
            $policyID = $this->attribute( 'id' );
            if ( $enableCaching == 'true' && $useAvailCache )
            {
                //  $http =& eZHTTPTool::instance();

                $hasLimitationsInCache = $http->hasSessionVariable( 'UserLimitations' );
                if ( $hasLimitationsInCache )
                {
                    $limitationsForAllUserPolicies =& $http->sessionVariable( 'UserLimitations' );
                    $limitationsForCurrentPolicy =& $limitationsForAllUserPolicies["$policyID"];
                    if ( count( $limitationsForCurrentPolicy ) > 0 )
                    {
                        $limitations = array();
                        foreach ( array_keys( $limitationsForCurrentPolicy ) as $key )
                        {
                            $limitationRow = $limitationsForCurrentPolicy[$key];
                            $limitations[] =& new eZPolicyLimitation( $limitationRow );
                        }
                        eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitations, "using cached  limitations for policy_id=$policyID" );
                        $this->Limitations =& $limitations;
                        $loadFromDb = false;
                    }
                }
            }
            if ( $loadFromDb )
            {
                $limitations =& eZPersistentObject::fetchObjectList( eZPolicyLimitation::definition(),
                                                                     null, array( 'policy_id' => $this->attribute( 'id') ), null, null,
                                                                     true );
                if ( $enableCaching )
                {
                    $limitationsForCurrentPolicy = array();
                    foreach ( array_keys( $limitations ) as $key )
                    {
                        $limitation =& $limitations[$key];
                        $limitationAttributes = array();
                        $limitationAttributes['id'] = $limitation->attribute( 'id' );
                        $limitationAttributes['policy_id'] = $limitation->attribute( 'policy_id' );
                        $limitationAttributes['identifier'] = $limitation->attribute( 'identifier' );
                        $limitationAttributes['role_id'] = $limitation->attribute( 'role_id' );
                        $limitationAttributes['module_name'] = $limitation->attribute( 'module_name' );
                        $limitationAttributes['function_name'] = $limitation->attribute( 'function_name' );
                        $limitationsForCurrentPolicy[] = $limitationAttributes;
                    }
                    $http =& eZHTTPTool::instance();
                    if ( !$http->hasSessionVariable( 'UserLimitations' ) )
                    {
                        $limitationArray =& $http->sessionVariable( 'UserLimitations' );
                    }
                    else
                    {
                        $limitationArray = array();
                    }

                    eZDebugSetting::writeDebug( 'kernel-policy-limitation', $limitationArray, "using limitations from db for policy_id=$policyID" );
                    $limitationArray["$policyID"] = $limitationsForCurrentPolicy;

                }

                $this->Limitations =& $limitations;
            }
        }

        return $this->Limitations;
    }

    function &fetch( $policyID )
    {
        return eZPersistentObject::fetchObject( eZPolicy::definition(),
                                                null, array('id' => $policyID ), true);

    }

}

?>
