<?php
//
// Definition of eZContentObjectPackageCreator class
//
// Created on: <09-Mar-2004 12:39:59 kk>
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

/*! \file ezcontentobjectpackagecreator.php
*/

/*!
  \ingroup package
  \class eZContentObjectPackageCreator ezcontentclasspackagecreator.php
  \brief A package creator for content objects
*/

include_once( 'kernel/classes/ezpackagecreationhandler.php' );

class eZContentObjectPackageCreator extends eZPackageCreationHandler
{
    /*!
     \reimp
    */
    function eZContentObjectPackageCreator( $id )
    {
        $steps = array();
        $steps[] = array( 'id' => 'object',
                          'name' => ezi18n( 'kernel/package', 'Content objects to include' ),
						  'methods' => array( 'initialize' => 'initializeObjectList',
                                              'load' => 'loadObjectList',
						                      'validate' => 'validateObjectList' ),
                          'template' => 'object_select.tpl' );
        $steps[] = array( 'id' => 'object_limits',
                          'name' => ezi18n( 'kernel/package', 'Content object limits' ),
						  'methods' => array( 'initialize' => 'initializeObjectLimits',
                                              'load' => 'loadObjectLimits',
						                      'validate' => 'validateObjectLimits' ),
                          'template' => 'object_limit.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         ezi18n( 'kernel/package', 'Content object export' ),
                                         $steps );
    }

    /*!
     \reimp
     Creates the package and adds the selected content classes.
    */
    function finalize( &$package, &$http, &$persistentData )
    {
		$this->createPackage( $package, $http, $persistentData, $cleanupFiles );

        $objectHandler = eZPackage::packageHandler( 'ezcontentobject' );
        $nodeList = $persistentData['node_list'];
        $options = $persistentData['object_options'];

        foreach( $nodeList as $nodeInfo )
        {
            $objectHandler->addNode( $nodeInfo['id'], $nodeInfo['type'] == 'subtree' );
        }
        $objectHandler->generatePackage( $package, $options );

        $package->setAttribute( 'is_active', true );
        $package->store();
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

    function initializeObjectList( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $persistentData['node_list'] = array();
    }

    function loadObjectList( &$package, &$http, $step, &$persistentData, &$tpl, &$module )
    {
        if ( $http->hasPostVariable( 'AddSubtree' ) )
        {
            include_once( 'kernel/classes/ezcontentbrowse.php' );
            eZContentBrowse::browse( array( 'action_name' => 'FindLimitationSubtree',
                                            'description_template' => 'design:package/creators/ezcontentobject/browse_subtree.tpl',
                                            'from_page' => '/package/create',
                                            'persistent_data' => array( 'PackageStep' => $http->postVariable( 'PackageStep' ),
                                                                        'CreatorItemID' => $http->postVariable( 'CreatorItemID' ),
                                                                        'CreatorStepID' => $http->postVariable( 'CreatorStepID' ),
                                                                        'Subtree' => 1 ) ),
                                     $module );
        }
        else if ( $http->hasPostVariable( 'AddNode' ) )
        {
            include_once( 'kernel/classes/ezcontentbrowse.php' );
            eZContentBrowse::browse( array( 'action_name' => 'FindLimitationNode',
                                            'description_template' => 'design:package/creators/ezcontentobject/browse_node.tpl',
                                            'from_page' => '/package/create',
                                            'persistent_data' => array( 'PackageStep' => $http->postVariable( 'PackageStep' ),
                                                                        'CreatorItemID' => $http->postVariable( 'CreatorItemID' ),
                                                                        'CreatorStepID' => $http->postVariable( 'CreatorStepID' ),
                                                                        'Node' => 1) ),
                                     $module );
        }
        else if( $http->hasPostVariable( 'RemoveSelected' ) )
        {
            foreach ( array_keys( $persistentData['node_list'] ) as $key )
            {
                if ( in_array( $persistentData['node_list'][$key]['id'], $http->postVariable( 'DeleteIDArray' ) ) )
                {
                    unset( $persistentData['node_list'][$key] );
                }
            }
        }
        else if( $http->hasPostVariable( 'SelectedNodeIDArray' ) )
        {
            if ( $http->hasPostVariable( 'Subtree' ) &&
                 $http->hasPostVariable( 'Subtree' ) == 1 )
            {
                foreach( $http->postVariable( 'SelectedNodeIDArray' ) as $nodeID )
                {
                    $persistentData['node_list'][] = array( 'id' => $nodeID,
                                                            'type' => 'subtree' );
                }
            }
            else if ( $http->hasPostVariable( 'Node' ) &&
                 $http->hasPostVariable( 'Node' ) == 1 )
            {
                foreach( $http->postVariable( 'SelectedNodeIDArray' ) as $nodeID )
                {
                    $persistentData['node_list'][] = array( 'id' => $nodeID,
                                                            'type' => 'node' );
                }
            }
        }

        $tpl->setVariable( 'node_list', $persistentData['node_list'] );
    }

    /*!
     Checks if at least one content class has been selected.
    */
    function validateObjectList( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        if ( count( $persistentData['node_list'] ) == 0 )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Selected nodes' ),
                                  'description' => ezi18n( 'kernel/package', 'You must select one or more node(s)/subtree(s) for export.' ) );
            return false;
        }

        return true;
    }

    function initializeObjectLimits( &$package, &$http, $step, &$persistentData, &$tpl )
    {
        $persistentData['object_options'] = array( 'include_classes' => 1,
                                                   'include_templates' => 1,
                                                   'site_access_array' => array(),
                                                   'versions' => 'current',
                                                   'language_array' => array(),
                                                   'node_assignment' => 'selected',
                                                   'related_objects' => 'selected',
                                                   'embed_objects' => 'selected' );

        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $persistentData['object_options']['site_access_array'] = array( $ini->variable( 'SiteSettings', 'DefaultAccess' ) );

        include_once( 'kernel/classes/ezcontentobject.php' );
        $availableLanguages = eZContentObject::translationList();
        foreach ( $availableLanguages as $language )
        {
            $persistentData['object_options']['language_array'][] = $language->attribute( 'locale_code' );
        }
    }

    function loadObjectLimits( &$package, &$http, $step, &$persistentData, &$tpl, &$module )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $availableSiteAccesses =& $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

        include_once( 'kernel/classes/ezcontentobject.php' );
        $availableLanguages = eZContentObject::translationList();
        $availableLanguageArray = array();
        foreach ( $availableLanguages as $language )
        {
            $availableLanguageArray[] = array( 'name' => $language->attribute( 'language_name' ),
                                               'locale' => $language->attribute( 'locale_code' ) );
        }

        $tpl->setVariable( 'available_site_accesses', $availableSiteAccesses );
        $tpl->setVariable( 'available_languages', $availableLanguageArray );
        $tpl->setVariable( 'options', $persistentData['object_options'] );
    }

    /*!
     Checks if at least one content class has been selected.
    */
    function validateObjectLimits( &$package, &$http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $options =& $persistentData['object_options'];

        $options['include_classes'] = $http->postVariable( 'IncludeClasses' );
        $options['include_templates'] = $http->postVariable( 'IncludeTemplates' );
        $options['site_access_array'] = $http->postVariable( 'SiteAccesses' );
        $options['versions'] = $http->postVariable( 'VersionExport' );
        $options['language_array'] = $http->postVariable( 'Languages' );
        $options['node_assignment'] = $http->postVariable( 'NodeAssignment' );
        $options['related_objects'] = $http->postVariable( 'RelatedObjects' );

        $result = true;
        if ( count( $persistentData['object_options']['language_array'] ) == 0 )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Selected nodes' ),
                                  'description' => ezi18n( 'kernel/package', 'You must choose one or more languages.' ) );
            $result = false;
        }

        if ( $persistentData['object_options']['include_templates'] &&
             count( $persistentData['object_options']['site_access_array'] ) == 0 )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Selected nodes' ),
                                  'description' => ezi18n( 'kernel/package', 'You must choose one or more site access.' ) );
            $result = false;
        }

        return $result;
    }

    /*!
     \reimp
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
	function generatePackageInformation( &$packageInformation, &$package, &$http, $step, &$persistentData )
	{
        $nodeList = $persistentData['node_list'];
        $options = $persistentData['object_options'];
        $nodeCount = 0;
        $description = 'This package contains the following nodes :' . "\n";
        $nodeNames = array();
        foreach( $nodeList as $nodeInfo )
        {
            $contentNode =& eZContentObjectTreeNode::fetch( $nodeInfo['id'] );
            $description .= $contentNode->attribute( 'name' ) . ' - ' . $nodeInfo['type'] . "\n";
            print( "'" . $contentNode->attribute( 'name' ) . "'<br/>" );
            $nodeNames[] = trim( $contentNode->attribute( 'name' ) );
            if ( $nodeInfo['type'] == 'node' )
            {
                ++$nodeCount;
            }
            else if ( $nodeInfo['type'] == 'subtree' )
            {
                $nodeCount += $contentNode->subTreeCount();
            }
        }

        $packageInformation['name'] = strtolower( implode( ',', $nodeNames ) );
        $packageInformation['summary'] = implode( ', ', $nodeNames );
        $packageInformation['description'] = $description;
    }

}
?>
