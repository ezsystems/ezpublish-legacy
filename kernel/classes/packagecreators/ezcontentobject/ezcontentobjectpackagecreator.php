<?php
//
// Definition of eZContentObjectPackageCreator class
//
// Created on: <09-Mar-2004 12:39:59 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \ingroup package
  \class eZContentObjectPackageCreator ezcontentclasspackagecreator.php
  \brief A package creator for content objects
*/

class eZContentObjectPackageCreator extends eZPackageCreationHandler
{
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
     Creates the package and adds the selected content classes.
    */
    function finalize( &$package, $http, &$persistentData )
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
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'contentclass'.
    */
    function packageType( $package, &$persistentData )
    {
        return 'contentobject';
    }

    function initializeObjectList( $package, $http, $step, &$persistentData, $tpl )
    {
        $persistentData['node_list'] = array();
    }

    function loadObjectList( $package, $http, $step, &$persistentData, $tpl, &$module )
    {
        if ( $http->hasPostVariable( 'AddSubtree' ) )
        {
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
        else if( $http->hasPostVariable( 'SelectedNodeIDArray' ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
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
    function validateObjectList( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        if ( count( $persistentData['node_list'] ) == 0 )
        {
            $errorList[] = array( 'field' => ezi18n( 'kernel/package', 'Selected nodes' ),
                                  'description' => ezi18n( 'kernel/package', 'You must select one or more node(s)/subtree(s) for export.' ) );
            return false;
        }

        return true;
    }

    function initializeObjectLimits( $package, $http, $step, &$persistentData, $tpl )
    {
        $persistentData['object_options'] = array( 'include_classes' => 1,
                                                   'include_templates' => 1,
                                                   'site_access_array' => array(),
                                                   'versions' => 'current',
                                                   'language_array' => array(),
                                                   'node_assignment' => 'selected',
                                                   'related_objects' => 'selected',
                                                   'embed_objects' => 'selected' );

        $ini = eZINI::instance();
        $persistentData['object_options']['site_access_array'] = array( $ini->variable( 'SiteSettings', 'DefaultAccess' ) );

        $availableLanguages = eZContentObject::translationList();
        foreach ( $availableLanguages as $language )
        {
            $persistentData['object_options']['language_array'][] = $language->attribute( 'locale_code' );
        }
    }

    function loadObjectLimits( $package, $http, $step, &$persistentData, $tpl, &$module )
    {
        $ini = eZINI::instance();
        $availableSiteAccesses = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

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
    function validateObjectLimits( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $options =& $persistentData['object_options'];

        $options['include_classes'] = $http->hasPostVariable( 'IncludeClasses' ) ? $http->postVariable( 'IncludeClasses' ) : false;
        $options['include_templates'] = $http->hasPostVariable( 'IncludeTemplates' ) ? $http->postVariable( 'IncludeTemplates' ) : false;
        $options['site_access_array'] = $http->postVariable( 'SiteAccesses' );
        $options['versions'] = $http->postVariable( 'VersionExport' );
        $options['language_array'] = $http->postVariable( 'Languages' );
        $options['node_assignment'] = $http->postVariable( 'NodeAssignment' );
        $options['related_objects'] = $http->postVariable( 'RelatedObjects' );
        $options['minimal_template_set'] = $http->hasPostVariable( 'MinimalTemplateSet' ) ? $http->postVariable( 'MinimalTemplateSet' ) : false;

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
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
    function generatePackageInformation( &$packageInformation, $package, $http, $step, &$persistentData )
    {
        $nodeList = $persistentData['node_list'];
        $options = $persistentData['object_options'];
        $nodeCount = 0;
        $description = 'This package contains the following nodes :' . "\n";
        $nodeNames = array();
        foreach( $nodeList as $nodeInfo )
        {
            $contentNode = eZContentObjectTreeNode::fetch( $nodeInfo['id'] );
            $description .= $contentNode->attribute( 'name' ) . ' - ' . $nodeInfo['type'] . "\n";
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

        $packageInformation['name'] = implode( ',', $nodeNames );
        $packageInformation['summary'] = implode( ', ', $nodeNames );
        $packageInformation['description'] = $description;
    }

}
?>
