<?php
//
// Definition of eZContentObjectPackageInstaller class
//
// Created on: <01-Apr-2004 12:39:59 kk>
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

/*! \file ezcontentobjectpackageinstaller.php
*/

/*!
  \ingroup package
  \class eZContentObjectPackageInstaller ezcontentclasspackageinstaller.php
  \brief A package creator for content objects
*/

include_once( 'kernel/classes/ezpackageinstallationhandler.php' );

class eZContentObjectPackageInstaller extends eZPackageInstallationHandler
{

        /*!
     \reimp
    */
    function eZContentObjectPackageInstaller( &$package, $id, $installItem )
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
                                             $id,
                                             ezi18n( 'kernel/package', 'Content object import' ),
                                             $steps,
                                             $installItem );
    }

    /*!
     \reimp
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( &$package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'contentclass'.
    */
	function packageType( &$package, &$persistentData )
	{
	    return 'contentobject';
	}

    /*!
     \reimp
    */
    function initializeSiteAccess( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $availableSiteAccessArray =& $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

        if ( !isset( $persistentData['site_access_map'] ) )
        {
            $persistentData['site_access_map'] = array();
            $persistentData['site_access_available'] = $availableSiteAccessArray;
            $rootDOMNode = $this->rootDOMNode();
            $siteAccessListNode = $rootDOMNode->elementByName( 'site-access-list' );

            foreach( $siteAccessListNode->elementsByName( 'site-access' ) as $siteAccessNode )
            {
                $originalSiteAccessName = $siteAccessNode->textContent();
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
    function validateSiteAccess( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
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
    function initializeTopNodes( &$package, &$http, $step, &$persistentData, &$tpl, &$module )
    {
        if ( !isset( $persistentData['top_nodes_map'] ) )
        {
            $persistentData['top_nodes_map'] = array();
            $rootDOMNode = $this->rootDOMNode();
            $topNodeListNode = $rootDOMNode->elementByName( 'top-node-list' );

            $ini =& eZINI::instance( 'content.ini' );
            $defaultPlacementNodeID = $ini->variable( 'NodeSettings', 'RootNode' );
            $defaultPlacementNode = eZContentObjectTreeNode::fetch( $defaultPlacementNodeID );
            $defaultPlacementName = $defaultPlacementNode->attribute( 'name' );
            foreach ( $topNodeListNode->elementsByName( 'top-node' ) as $topNodeDOMNode )
            {
                $persistentData['top_nodes_map'][(string)$topNodeDOMNode->attributeValue( 'node-id' )] = array( 'old_node_id' => $topNodeDOMNode->attributeValue( 'node-id' ),
                                                                                                                'name' => $topNodeDOMNode->textContent(),
                                                                                                                'new_node_id' => $defaultPlacementNodeID,
                                                                                                                'new_parent_name' => $defaultPlacementName );
            }
        }

        foreach( array_keys( $persistentData['top_nodes_map'] ) as $topNodeArrayKey )
        {
            if ( $http->hasPostVariable( 'BrowseNode_' . $topNodeArrayKey ) )
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                                'description_template' => 'design:package/installers/ezcontentobject/browse_topnode.tpl',
                                                'from_page' => '/package/install',
                                                'persistent_data' => array( 'PackageStep' => $http->postVariable( 'PackageStep' ),
                                                                            'InstallItemID' => $http->postVariable( 'InstallItemID' ),
                                                                            'InstallStepID' => $http->postVariable( 'InstallStepID' ),
                                                                            'ReturnBrowse_' . $topNodeArrayKey => 1 ) ),
                                         $module );
            }
            else if ( $http->hasPostVariable( 'ReturnBrowse_' . $topNodeArrayKey ) )
            {
                $nodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
                $persistentData['top_nodes_map'][$topNodeArrayKey]['new_node_id'] = $nodeIDArray[0];
                $contentNode = eZContentObjectTreeNode::fetch( $nodeIDArray[0] );
                $persistentData['top_nodes_map'][$topNodeArrayKey]['new_parent_name'] = $contentNode->attribute( 'name' );
            }
        }

        $tpl->setVariable( 'top_nodes_map', $persistentData['top_nodes_map'] );
    }

    /*!
     \reimp
    */
    function validateTopNodes( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
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
    function finalize( &$package, &$http, &$persistentData )
    {
        $package->installItem( $this->InstallItem, $persistentData );
    }

}
?>
