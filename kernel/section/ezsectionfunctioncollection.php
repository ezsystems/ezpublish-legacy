<?php
//
// Definition of eZSectionFunctionCollection class
//
// Created on: <23-May-2003 16:46:17 amos>
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

/*! \file ezsectionfunctioncollection.php
*/

/*!
  \class eZSectionFunctionCollection ezsectionfunctioncollection.php
  \brief The class eZSectionFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZSectionFunctionCollection
{
    /*!
     Constructor
    */
    function eZSectionFunctionCollection()
    {
    }

    function &fetchSectionObject( $sectionID )
    {
        include_once( 'kernel/classes/ezsection.php' );
        $sectionObject =& eZSection::fetch( $sectionID );
        if ( $sectionObject === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $sectionObject );
    }

    function &fetchSectionList()
    {
        include_once( 'kernel/classes/ezsection.php' );
        $sectionObjects =& eZSection::fetchList( );
        return array( 'result' => $sectionObjects );
    }

    function &fetchObjectList( $sectionID, $offset = false, $limit = false, $sortOrder = false )
    {
        include_once( "kernel/classes/ezcontentobject.php" );

        if ( $sortOrder === false )
        {
            $sortOrder = array( 'id' => 'desc' );
        }
        $objects = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                        null,
                                                        array( 'section_id' => $sectionID ),
                                                        $sortOrder,
                                                        array( 'offset' => $offset, 'limit' => $limit ) );
        return array( 'result' => $objects );
    }

    function &fetchObjectListCount( $sectionID )
    {
        include_once( "kernel/classes/ezcontentobject.php" );

        $custom = array( array( 'operation' => 'count( id )',
                                'name' => 'count' ) );
        $rows = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                     array(),
                                                     array( 'section_id' => $sectionID ),
                                                     null, null, false, false, $custom );
        return array( 'result' => $rows[0]['count'] );
    }

    function &fetchRoles( $sectionID )
    {
        include_once( 'kernel/classes/ezpolicylimitation.php' );
        include_once( 'kernel/classes/ezrole.php' );

        $policies = $roleIDs = $usedRoleIDs = $roles = $roleLimitations = array();

        $limitations = eZPolicyLimitation::findByType( 'Section', $sectionID, true, false );
        foreach ( array_keys( $limitations ) as $key )
        {
            $policy =& $limitations[$key]->policy();
            $policies[] = $policy;

            $roleID= $policy->attribute( 'role_id' );
            $roleIDs[] = $roleID;
            if ( !isset( $roleLimitations[$roleID] ) )
            {
                $roleLimitations[$roleID] = array();
            }
            $roleLimitations[$roleID][] =& $policy;
        }

        foreach ( array_keys( $policies ) as $key )
        {
            $roleID = $policies[$key]->attribute( 'role_id' );
            if ( in_array( $roleID, $roleIDs ) && !in_array( $roleID, $usedRoleIDs ) )
            {
                $roles[] = $policies[$key]->attribute( 'role' );
                $usedRoleIDs[] = $roleID;
            }
        }

        return array( 'result' => array( 'roles' => $roles, 'limited_policies' => $roleLimitations ) );
    }

    function &fetchUserRoles( $sectionID )
    {
        include_once( 'kernel/classes/ezrole.php' );

        $userRoles = eZRole::fetchRolesByLimitation( 'section', $sectionID );
        return array( 'result' => $userRoles );
    }
}

?>
