<?php
//
// Definition of eZPackageFunctionCollection class
//
// Created on: <11-Aug-2003 18:30:26 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezpackagefunctioncollection.php
*/

/*!
  \class eZPackageFunctionCollection ezpackagefunctioncollection.php
  \brief The class eZPackageFunctionCollection does

*/

//include_once( 'kernel/error/errors.php' );

class eZPackageFunctionCollection
{
    /*!
     Constructor
    */
    function eZPackageFunctionCollection()
    {
    }

    function fetchList( $filterArray = false, $offset, $limit, $repositoryID )
    {
        $filterParams = array();
        $filterList = false;
        if ( isset( $filterArray ) and
             is_array( $filterArray ) and
             count( $filterArray ) > 0 )
        {
            $filterList = $filterArray;
            if ( count( $filterArray ) > 1 and
                 !is_array( $filterArray[0] ) )
            {
                $filterList = array( $filterArray );
            }
        }
        if ( $filterList !== false )
        {
            foreach ( $filterList as $filter )
            {
                if ( is_array( $filter ) and count( $filter ) > 0 )
                {
                    $filterName = $filter[0];
                    switch ( $filterName )
                    {
                        case 'type':
                        {
                            $typeValue = $filter[1];
                            $typeParam = array( 'type' => $typeValue );
                            $filterParams = array_merge( $filterParams, $typeParam );
                        } break;
                        case 'priority':
                        {
                            $priorityValue = $filter[1];
                            $priorityParam = array( 'priority' => $priorityValue );
                            $filterParams = array_merge( $filterParams, $priorityParam );
                        } break;
                        case 'vendor':
                        {
                            $vendorValue = $filter[1];
                            $vendorParam = array( 'vendor' => $vendorValue );
                            $filterParams = array_merge( $filterParams, $vendorParam );
                        } break;
                        case 'extension':
                        {
                            $extensionValue = $filter[1];
                            $extensionParam = array( 'extension' => $extensionValue );
                            $filterParams = array_merge( $filterParams, $extensionParam );
                        } break;
                        default:
                        {
                            eZDebug::writeWarning( 'Unknown package filter name: ' . $filterName );
                            continue;
                        };
                    }
                }
            }
        }
        $params = array( 'offset' => $offset,
                         'limit' => $limit );
        if ( $repositoryID )
            $params['repository_id'] = $repositoryID;

        //include_once( 'kernel/classes/ezpackage.php' );
        $packageList = eZPackage::fetchPackages( $params,
                                                 $filterParams );
        if ( $packageList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $packageList );
    }

    function fetchPackage( $packageName, $repositoryID )
    {
        //include_once( 'kernel/classes/ezpackage.php' );
        $package = eZPackage::fetch( $packageName, false, $repositoryID );
        if ( $package === false )
        {
            $retValue = array( 'error' => array( 'error_type' => 'kernel',
                                                 'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $retValue = array( 'result' => $package );
        }
        return $retValue;
    }

    function fetchDependentPackageList( $packageName, $filterArray = false, $repositoryID )
    {
        $filterParams = array();
        $filterList = false;
        if ( isset( $filterArray ) and
             is_array( $filterArray ) and
             count( $filterArray ) > 0 )
        {
            $filterList = $filterArray;
            if ( count( $filterArray ) > 1 and
                 !is_array( $filterArray[0] ) )
            {
                $filterList = array( $filterArray );
            }
        }
        if ( $filterList !== false )
        {
            foreach ( $filterList as $filter )
            {
                if ( is_array( $filter ) and count( $filter ) > 0 )
                {
                    $filterName = $filter[0];
                    switch ( $filterName )
                    {
                        case 'type':
                        {
                            $typeValue = $filter[1];
                            $typeParam = array( 'type' => $typeValue );
                            $filterParams = array_merge( $filterParams, $typeParam );
                        } break;
                        case 'name':
                        {
                            $nameValue = $filter[1];
                            $nameParam = array( 'name' => $nameValue );
                            $filterParams = array_merge( $filterParams, $nameParam );
                        } break;
                        case 'priority':
                        {
                            $priorityValue = $filter[1];
                            $priorityParam = array( 'priority' => $priorityValue );
                            $filterParams = array_merge( $filterParams, $priorityParam );
                        } break;
                        case 'vendor':
                        {
                            $vendorValue = $filter[1];
                            $vendorParam = array( 'vendor' => $vendorValue );
                            $filterParams = array_merge( $filterParams, $vendorParam );
                        } break;
                        case 'extension':
                        {
                            $extensionValue = $filter[1];
                            $extensionParam = array( 'extension' => $extensionValue );
                            $filterParams = array_merge( $filterParams, $extensionParam );
                        } break;
                        default:
                        {
                            eZDebug::writeWarning( 'Unknown package filter name: ' . $filterName );
                            continue;
                        };
                    }
                }
            }
        }
        //include_once( 'kernel/classes/ezpackage.php' );
        $package = eZPackage::fetch( $packageName, false, $repositoryID );
        $packageList = $package->fetchDependentPackages( $filterParams );
        if ( $packageList === false )
        {
            $retValue = array( 'error' => array( 'error_type' => 'kernel',
                                                 'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $retValue = array( 'result' => $packageList );
        }
        return $retValue;
    }

    function fetchMaintainerRoleList( $packageType, $checkRoles )
    {
        //include_once( 'kernel/classes/ezpackage.php' );
        $list = eZPackage::fetchMaintainerRoleList( $packageType, $checkRoles );
        if ( $list === false )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $list );
    }

    function fetchRepositoryList()
    {
        //include_once( 'kernel/classes/ezpackage.php' );
        $list = eZPackage::packageRepositories();
        if ( $list === false )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $list );
    }

    function canCreate()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'create' ) );
    }

    function canEdit()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'edit' ) );
    }

    function canImport()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'import' ) );
    }

    function canInstall()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'install' ) );
    }

    function canExport()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'export' ) );
    }

    function canRead()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'read' ) );
    }

    function canList()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'list' ) );
    }

    function canRemove()
    {
        return array( 'result' => eZPackage::canUsePolicyFunction( 'remove' ) );
    }
}

?>
