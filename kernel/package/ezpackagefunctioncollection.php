<?php
/**
 * File containing the eZPackageFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPackageFunctionCollection ezpackagefunctioncollection.php
  \brief The class eZPackageFunctionCollection does

*/

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
                        }
                    }
                }
            }
        }
        $params = array( 'offset' => $offset,
                         'limit' => $limit );
        if ( $repositoryID )
            $params['repository_id'] = $repositoryID;

        $packageList = eZPackage::fetchPackages( $params,
                                                 $filterParams );
        if ( $packageList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $packageList );
    }

    function fetchPackage( $packageName, $repositoryID )
    {
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
                        }
                    }
                }
            }
        }
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
        $list = eZPackage::fetchMaintainerRoleList( $packageType, $checkRoles );
        if ( $list === false )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $list );
    }

    function fetchRepositoryList()
    {
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
