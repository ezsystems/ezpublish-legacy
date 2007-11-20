<?php
//
// Definition of eZContentObjectPackageInstaller class
//
// Created on: <01-Apr-2004 12:39:59 kk>
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

/*! \file ezcontentobjectpackageinstaller.php
*/

/*!
  \ingroup package
  \class eZContentObjectPackageInstaller ezcontentobjectpackageinstaller.php
  \brief A package creator for content objects
*/

//include_once( 'kernel/classes/ezpackageinstallationhandler.php' );

class eZContentObjectPackageInstaller extends eZPackageInstallationHandler
{

    /*!
     \reimp
    */
    function eZContentObjectPackageInstaller( $package, $type, $installItem )
    {
        $steps = array();
        $steps[] = array( 'id' => 'site_access',
                          'name' => ezi18n( 'kernel/package', 'Site access mapping' ),
                          'methods' => array( 'initialize' => 'initializeSiteAccess',
                                              'validate' => 'validateSiteAccess' ),
                          'template' => 'site_access.tpl' );
        $steps[] = array( 'id' => 'top_nodes',
                          'name' => ezi18n( 'kernel/package', 'Top node placements' ),
                          'methods' => array( 'initialize' => 'initializeTopNodes',
                                              'validate' => 'validateTopNodes' ),
                          'template' => 'top_nodes.tpl' );
        $this->eZPackageInstallationHandler( $package,
                                             $type,
                                             $installItem,
                                             ezi18n( 'kernel/package', 'Content object import' ),
                                             $steps );
    }

    /*!
     \reimp
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'contentobject'.
    */
    function packageType( $package, &$persistentData )
    {
        return 'contentobject';
    }

    /*!
     \reimp
    */
    function initializeSiteAccess( $package, $http, $step, &$persistentData, $tpl )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $availableSiteAccessArray = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

        if ( !isset( $persistentData['site_access_map'] ) )
        {
            $persistentData['site_access_map'] = array();
            $persistentData['site_access_available'] = $availableSiteAccessArray;
            $rootDOMNode = $this->rootDOMNode();
            $siteAccessListNode = $rootDOMNode->getElementsByTagName( 'site-access-list' )->item( 0 );

            foreach( $siteAccessListNode->getElementsByTagName( 'site-access' ) as $siteAccessNode )
            {
                $originalSiteAccessName = $siteAccessNode->textContent;
                if ( in_array( $originalSiteAccessName, $availableSiteAccessArray ) )
                {
                    $persistentData['site_access_map'][$originalSiteAccessName] = $originalSiteAccessName;
                }
                else
                {
                    $persistentData['site_access_map'][$originalSiteAccessName] = '';
                }
            }
        }

        $tpl->setVariable( 'site_access_map', $persistentData['site_access_map'] );
        $tpl->setVariable( 'available_site_access_array', $availableSiteAccessArray );
    }

    /*!
     \reimp
    */
    function validateSiteAccess( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $validate = true;
        foreach( $persistentData['site_access_map'] as $originalSiteAccess => $newSiteAccess )
        {
            $persistentData['site_access_map'][$originalSiteAccess] = $http->postVariable( 'SiteAccessMap_'.$originalSiteAccess );
            if ( !in_array( $persistentData['site_access_map'][$originalSiteAccess], $persistentData['site_access_available'] ) )
            {
                $validate = false;
            }
        }

        return $validate;
    }

    /*!
     \reimp
    */
    function initializeTopNodes( $package, $http, $step, &$persistentData, $tpl, &$module )
    {
        if ( !isset( $persistentData['top_nodes_map'] ) )
        {
            $persistentData['top_nodes_map'] = array();
            $rootDOMNode = $this->rootDOMNode();
            $topNodeListNode = $rootDOMNode->getElementsByTagName( 'top-node-list' )->item( 0 );

            $ini = eZINI::instance( 'content.ini' );
            $defaultPlacementNodeID = $ini->variable( 'NodeSettings', 'RootNode' );
            $defaultPlacementNode = eZContentObjectTreeNode::fetch( $defaultPlacementNodeID );
            $defaultPlacementName = $defaultPlacementNode->attribute( 'name' );
            foreach ( $topNodeListNode->getElementsByTagName( 'top-node' ) as $topNodeDOMNode )
            {
                $persistentData['top_nodes_map'][(string)$topNodeDOMNode->getAttribute( 'node-id' )] = array( 'old_node_id' => $topNodeDOMNode->getAttribute( 'node-id' ),
                                                                                                                'name' => $topNodeDOMNode->textContent,
                                                                                                                'new_node_id' => $defaultPlacementNodeID,
                                                                                                                'new_parent_name' => $defaultPlacementName );
            }
        }

        foreach( array_keys( $persistentData['top_nodes_map'] ) as $topNodeArrayKey )
        {
            if ( $http->hasPostVariable( 'BrowseNode_' . $topNodeArrayKey ) )
            {
                //include_once( 'kernel/classes/ezcontentbrowse.php' );
                eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                                'description_template' => 'design:package/installers/ezcontentobject/browse_topnode.tpl',
                                                'from_page' => '/package/install',
                                                'persistent_data' => array( 'PackageStep' => $http->postVariable( 'PackageStep' ),
                                                                            'InstallerType' => $http->postVariable( 'InstallerType' ),
                                                                            'InstallStepID' => $http->postVariable( 'InstallStepID' ),
                                                                            'ReturnBrowse_' . $topNodeArrayKey => 1 ) ),
                                         $module );
            }
            else if ( $http->hasPostVariable( 'ReturnBrowse_' . $topNodeArrayKey ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
            {
                $nodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
                if ( $nodeIDArray != null )
                {
                    $persistentData['top_nodes_map'][$topNodeArrayKey]['new_node_id'] = $nodeIDArray[0];
                    $contentNode = eZContentObjectTreeNode::fetch( $nodeIDArray[0] );
                    $persistentData['top_nodes_map'][$topNodeArrayKey]['new_parent_name'] = $contentNode->attribute( 'name' );
                }
            }
        }

        $tpl->setVariable( 'top_nodes_map', $persistentData['top_nodes_map'] );
    }

    /*!
     \reimp
    */
    function validateTopNodes( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $validate = true;
        foreach( array_keys( $persistentData['top_nodes_map'] ) as $topNodeArrayKey )
        {
            if ( $persistentData['top_nodes_map'][$topNodeArrayKey]['new_node_id'] === false )
            {
                $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Select parent nodes' ),
                                      'description' => ezi18n( 'kernel/package', 'You must assign all nodes to new parent nodes.' ) );
                $validate = false;
                break;
            }
        }

        return $validate;
    }

    /*!
     \reimp
    */
    function finalize( $package, $http, &$persistentData )
    {
        eZDebug::writeDebug( 'finalize is called', 'eZContentObjectPackageInstaller::finalize' );
        $package->installItem( $this->InstallItem, $persistentData );
    }

}
?>
