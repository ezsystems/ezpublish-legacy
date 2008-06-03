<?php
//
// Definition of eZSectionFunctionCollection class
//
// Created on: <23-May-2003 16:46:17 amos>
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

/*! \file ezsectionfunctioncollection.php
*/

/*!
  \class eZSectionFunctionCollection ezsectionfunctioncollection.php
  \brief The class eZSectionFunctionCollection does

*/

//include_once( 'kernel/error/errors.php' );

class eZSectionFunctionCollection
{
    /*!
     Constructor
    */
    function eZSectionFunctionCollection()
    {
    }

    function fetchSectionObject( $sectionID )
    {
        //include_once( 'kernel/classes/ezsection.php' );
        $sectionObject = eZSection::fetch( $sectionID );
        if ( $sectionObject === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $sectionObject );
    }

    function fetchSectionList()
    {
        //include_once( 'kernel/classes/ezsection.php' );
        $sectionObjects = eZSection::fetchList( );
        return array( 'result' => $sectionObjects );
    }

    function fetchObjectList( $sectionID, $offset = false, $limit = false, $sortOrder = false, $status = false )
    {
        //include_once( "kernel/classes/ezcontentobject.php" );

        if ( $sortOrder === false )
        {
            $sortOrder = array( 'id' => 'desc' );
        }
        if ( $status == 'archived' )
            $status = eZContentObject::STATUS_ARCHIVED;
        else
            $status = eZContentObject::STATUS_PUBLISHED;
        $objects = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                        null,
                                                        array( 'section_id' => $sectionID,
                                                               'status' => $status ),
                                                        $sortOrder,
                                                        array( 'offset' => $offset, 'limit' => $limit ) );
        return array( 'result' => $objects );
    }

    function fetchObjectListCount( $sectionID, $status = false )
    {
        //include_once( "kernel/classes/ezcontentobject.php" );

        if ( $status == 'archived' )
            $status = eZContentObject::STATUS_ARCHIVED;
        else
            $status = eZContentObject::STATUS_PUBLISHED;
        $rows = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                     array(),
                                                     array( 'section_id' => $sectionID,
                                                            'status' => $status ),
                                                     false,
                                                     null,
                                                     false,
                                                     false,
                                                     array( array( 'operation' => 'count( id )',
                                                                   'name' => 'count' ) ) );
        return array( 'result' => $rows[0]['count'] );
    }

    function fetchRoles( $sectionID )
    {
        //include_once( 'kernel/classes/ezpolicylimitation.php' );
        //include_once( 'kernel/classes/ezrole.php' );

        $policies = $roleIDs = $usedRoleIDs = $roles = $roleLimitations = array();

        $limitations = eZPolicyLimitation::findByType( 'Section', $sectionID, true, false );
        foreach ( $limitations as $policyEntry )
        {
            $policy = $policyEntry->policy();
            $policies[] = $policy;

            $roleID = $policy->attribute( 'role_id' );
            $roleIDs[] = $roleID;
            if ( !isset( $roleLimitations[$roleID] ) )
            {
                $roleLimitations[$roleID] = array();
            }
            $roleLimitations[$roleID][] = $policy;
        }

        foreach ( $policies as $policy )
        {
            $roleID = $policy->attribute( 'role_id' );
            if ( in_array( $roleID, $roleIDs ) && !in_array( $roleID, $usedRoleIDs ) )
            {
                $roles[] = $policy->attribute( 'role' );
                $usedRoleIDs[] = $roleID;
            }
        }

        return array( 'result' => array( 'roles' => $roles, 'limited_policies' => $roleLimitations ) );
    }

    function fetchUserRoles( $sectionID )
    {
        //include_once( 'kernel/classes/ezrole.php' );

        $userRoles = eZRole::fetchRolesByLimitation( 'section', $sectionID );
        return array( 'result' => $userRoles );
    }
}

?>
