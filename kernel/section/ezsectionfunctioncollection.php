<?php
/**
 * File containing the eZSectionFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSectionFunctionCollection ezsectionfunctioncollection.php
  \brief The class eZSectionFunctionCollection does

*/

class eZSectionFunctionCollection
{
    /*!
     Constructor
    */
    function eZSectionFunctionCollection()
    {
    }

    /**
     * Fetch section object given either section id or section identifier. There should be one and only one parameter.
     * @param integer $sectionID
     * @param string $sectionIdentifier
     * @return object
     */
    function fetchSectionObject( $sectionID = false, $sectionIdentifier = false )
    {
        if( $sectionID !== false )
        {
            if( $sectionIdentifier !== false )
            {
                $sectionObject = null;
            }
            else
            {
                $sectionObject = eZSection::fetch( $sectionID );
            }
        }
        else
        {
            if( $sectionIdentifier === false )
            {
                $sectionObject = null;
            }
            else
            {
                $sectionObject = eZSection::fetchByIdentifier( $sectionIdentifier );
            }
        }
        if ( $sectionObject === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $sectionObject );
    }

    function fetchSectionList()
    {
        $sectionObjects = eZSection::fetchList( );
        return array( 'result' => $sectionObjects );
    }

    function fetchObjectList( $sectionID, $offset = false, $limit = false, $sortOrder = false, $status = false )
    {
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
        $userRoles = eZRole::fetchRolesByLimitation( 'section', $sectionID );
        return array( 'result' => $userRoles );
    }
}

?>
