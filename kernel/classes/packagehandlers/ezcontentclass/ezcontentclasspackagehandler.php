<?php
//
// Definition of eZContentClassPackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
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

/*! \file ezcontentclasspackagehandler.php
*/

/*!
  \class eZContentClassPackageHandler ezcontentclasspackagehandler.php
  \brief Handles content classes in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

class eZContentClassPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZContentClassPackageHandler()
    {
        $this->eZPackageHandler( 'ezcontentclass',
                                 array( 'extract-install-content' => true ) );
    }

    /*!
     \reimp
     Returns an explanation for the content class install item.
    */
    function explainInstallItem( &$package, $installItem )
    {
        if ( $installItem['filename'] )
        {
            $filename = $installItem['filename'];
            $subdirectory = $installItem['sub-directory'];
            if ( $subdirectory )
                $filepath = $subdirectory . '/' . $filename . '.xml';
            else
                $filepath = $filename . '.xml';

            $filepath = $package->path() . '/' . $filepath;

            $dom =& $package->fetchDOMFromFile( $filepath );
            if ( $dom )
            {
                $content =& $dom->root();
                $className = $content->elementTextContentByName( 'name' );
                $classIdentifier = $content->elementTextContentByName( 'identifier' );
                return array( 'description' => ezi18n( 'kernel/package', 'Content class %classname (%classidentifier)', false,
                                                       array( '%classname' => $className,
                                                              '%classidentifier' => $classIdentifier ) ) );
            }
        }
    }

    /*!
     \reimp
     Uninstalls all previously installed content classes.
    */
    function uninstall( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $installParameters,
                      &$installData )
    {
        if ( isset( $installData['classid_list'] ) )
        {
            $classIDList = $installData['classid_list'];
            foreach ( $classIDList as $classID )
            {
                eZContentClassClassGroup::removeClassMembers( $classID, 0 );
                eZContentClassClassGroup::removeClassMembers( $classID, 1 );

                $class =& eZContentClass::fetch( $classID );
                if ( $class )
                {
                    $class->remove( true, EZ_CLASS_VERSION_STATUS_DEFINED );
                }

                $tmpClass =& eZContentClass::fetch( $classID, true, EZ_CLASS_VERSION_STATUS_TEMPORARY );
                if ( $tmpClass )
                {
                    $tmpClass->remove( true, EZ_CLASS_VERSION_STATUS_TEMPORARY );
                }
            }
            unset( $installData['classid_list'] );
        }
    }

    /*!
     \reimp
     Creates a new contentclass as defined in the xml structure.
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, $installParameters,
                      &$installData )
    {
        $className = $content->elementTextContentByName( 'name' );
        $classIdentifier = $content->elementTextContentByName( 'identifier' );
        $classRemoteID = $content->elementTextContentByName( 'remote-id' );
        $classObjectNamePattern = $content->elementTextContentByName( 'object-name-pattern' );

        $classRemoteNode = $content->elementByName( 'remote' );
        $classID = $classRemoteNode->elementTextContentByName( 'id' );
        $classGroupsNode = $classRemoteNode->elementByName( 'groups' );
        $classCreated = $classRemoteNode->elementTextContentByName( 'created' );
        $classModified = $classRemoteNode->elementTextContentByName( 'modified' );
        $classCreatorNode = $classRemoteNode->elementByName( 'creator' );
        $classModifierNode = $classRemoteNode->elementByName( 'modifier' );

        $classAttributesNode = $content->elementByName( 'attributes' );

        $dateTime = time();
        $classCreated = $dateTime;
        $classModified = $dateTime;

        $userID = false;
        if ( isset( $installParameters['user_id'] ) )
            $userID = $installParameters['user_id'];

        if ( $classRemoteID != "" )
            $class =& eZContentClass::fetchByRemoteID( $classRemoteID );

        if ( !$class )
        {
            $class =& eZContentClass::create( $userID,
                                              array( 'version' => 0,
                                                     'name' => $className,
                                                     'identifier' => $classIdentifier,
                                                     'remote_id' => $classRemoteID,
                                                     'contentobject_name' => $classObjectNamePattern,
                                                     'created' => $classCreated,
                                                     'modified' => $classModified ) );
            $class->store();
            $classID = $class->attributes( 'id' );
        }

        if ( !isset( $installData['classid_list'] ) )
            $installData['classid_list'] = array();
        if ( !isset( $installData['classid_map'] ) )
            $installData['classid_map'] = array();
        $installData['classid_list'][] = $class->attribute( 'id' );
        $installData['classid_map'][$classID] = $class->attribute( 'id' );

        $classAttributeList =& $classAttributesNode->children();
        foreach ( array_keys( $classAttributeList ) as $classAttributeKey )
        {
            $classAttributeNode =& $classAttributeList[$classAttributeKey];
            $isNotSupported = strtolower( $classAttributeNode->attributeValue( 'unsupported' ) ) == 'true';
            if ( $isNotSupported )
                continue;

            $attributeDatatype = $classAttributeNode->attributeValue( 'datatype' );
            $attributeIsRequired = strtolower( $classAttributeNode->attributeValue( 'required' ) ) == 'true';
            $attributeIsSearchable = strtolower( $classAttributeNode->attributeValue( 'searchable' ) ) == 'true';
            $attributeIsInformationCollector = strtolower( $classAttributeNode->attributeValue( 'information-collector' ) ) == 'true';
            $attributeIsTranslatable = strtolower( $classAttributeNode->attributeValue( 'translatable' ) ) == 'true';
            $attributeName = $classAttributeNode->elementTextContentByName( 'name' );
            $attributeIdentifier = $classAttributeNode->elementTextContentByName( 'identifier' );
            $attributePlacement = $classAttributeNode->elementTextContentByName( 'placement' );
            $attributeDatatypeParameterNode = $classAttributeNode->elementByName( 'datatype-parameters' );

            $classAttribute =& $class->fetchAttributeByIdentifier( $attributeIdentifier );
            if ( !$classAttribute )
            {
                $classAttribute =& eZContentClassAttribute::create( $class->attribute( 'id' ),
                                                                    $attributeDatatype,
                                                                    array( 'version' => 0,
                                                                           'identifier' => $attributeIdentifier,
                                                                           'name' => $attributeName,
                                                                           'is_required' => $attributeIsRequired,
                                                                           'is_searchable' => $attributeIsSearchable,
                                                                           'is_information_collector' => $attributeIsInformationCollector,
                                                                           'can_translate' => $attributeIsTranslatable,
                                                                           'placement' => $attributePlacement ) );
                $dataType =& $classAttribute->dataType();
                $classAttribute->store();
                $dataType->unserializeContentClassAttribute( $classAttribute, $classAttributeNode, $attributeDatatypeParameterNode );
                $classAttribute->sync();
            }
        }

        $classGroupsList =& $classGroupsNode->children();
        foreach ( array_keys( $classGroupsList ) as $classGroupNodeKey )
        {
            $classGroupNode =& $classGroupsList[$classGroupNodeKey];
            $classGroupName = $classGroupNode->attributeValue( 'name' );
            $classGroup =& eZContentClassGroup::fetchByName( $classGroupName );
            if ( !$classGroup )
            {
                $classGroup =& eZContentClassGroup::create();
                $classGroup->setAttribute( 'name', $classGroupName );
                $classGroup->store();
            }
            $classGroup->appendClass( $class );
        }
        return true;
    }

    /*!
     \reimp
    */
    function add( $packageType, &$package, &$cli, $parameters )
    {
        foreach ( $parameters['class-list'] as $classItem )
        {
            $classID = $classItem['id'];
            $classIdentifier = $classItem['identifier'];
            $classValue = $classItem['value'];
            $cli->notice( "Adding class $classValue to package" );
            $this->addClass( $package, $classID, $classIdentifier );
        }
    }

    /*!
     \static
     Adds the content class with ID \a $classID to the package.
     If \a $classIdentifier is \c false then it will be fetched from the class.
    */
    function addClass( &$package, $classID, $classIdentifier = false )
    {
        $class = false;
        if ( is_numeric( $classID ) )
            $class =& eZContentClass::fetch( $classID );
        if ( !$class )
            continue;
        $classNode =& eZContentClassPackageHandler::classDOMTree( $class );
        if ( !$classNode )
            continue;
        if ( !$classIdentifier )
            $classIdentifier = $class->attribute( 'identifier' );
        $package->appendInstall( 'ezcontentclass', false, false, true,
                                 'class-' . $classIdentifier, 'ezcontentclass',
                                 array( 'content' => $classNode ) );
        $package->appendProvides( 'ezcontentclass', 'contentclass', $class->attribute( 'identifier' ) );
        $package->appendInstall( 'ezcontentclass', false, false, false,
                                 'class-' . $classIdentifier, 'ezcontentclass',
                                 array( 'content' => false ) );
    }

    /*!
     \reimp
    */
    function handleAddParameters( $packageType, &$package, &$cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    function handleParameters( $packageType, &$package, &$cli, $type, $arguments )
    {
        $classList = false;
        foreach ( $arguments as $argument )
        {
            if ( $argument[0] == '-' )
            {
                if ( strlen( $argument ) > 1 and
                     $argument[1] == '-' )
                {
                }
                else
                {
                }
            }
            else
            {
                if ( $classList === false )
                {
                    $classList = array();
                    $classArray = explode( ',', $argument );
                    $error = false;
                    foreach ( $classArray as $classID )
                    {
                        if ( in_array( $classID, $classList ) )
                        {
                            $cli->notice( "Content class $classID already in list" );
                            continue;
                        }
                        if ( is_numeric( $classID ) )
                        {
                            if ( !eZContentClass::exists( $classID, 0, false, false ) )
                            {
                                $cli->error( "Content class with ID $classID does not exist" );
                                $error = true;
                            }
                            else
                            {
                                unset( $class );
                                $class =& eZContentClass::fetch( $classID );
                                $classList[] = array( 'id' => $classID,
                                                      'identifier' => $class->attribute( 'identifier' ),
                                                      'value' => $classID );
                            }
                        }
                        else
                        {
                            $realClassID = eZContentClass::exists( $classID, 0, false, true );
                            if ( !$realClassID )
                            {
                                $cli->error( "Content class with identifier $classID does not exist" );
                                $error = true;
                            }
                            else
                            {
                                unset( $class );
                                $class =& eZContentClass::fetch( $realClassID );
                                $classList[] = array( 'id' => $realClassID,
                                                      'identifier' => $class->attribute( 'identifier' ),
                                                      'value' => $classID );
                            }
                        }
                    }
                    if ( $error )
                        return false;
                }
            }
        }
        if ( $classList === false )
        {
            $cli->error( "No class ids chosen" );
            return false;
        }
        return array( 'class-list' => $classList );
    }

    /*!
     \static
     Creates the DOM tree for the content class \a $class and returns the root node.
    */
    function &classDOMTree( &$class )
    {
        if ( !$class )
            return false;
        $classNode =& eZDOMDocument::createElementNode( 'content-class' );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'name',
                                                                       $class->attribute( 'name' ) ) );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'identifier',
                                                                       $class->attribute( 'identifier' ) ) );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'remote-id',
                                                                        $class->attribute( 'remote_id' ) ) );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'object-name-pattern',
                                                                       $class->attribute( 'contentobject_name' ) ) );

        // Remote data start
        $remoteNode =& eZDOMDocument::createElementNode( 'remote' );
        $classNode->appendChild( $remoteNode );

        $ini =& eZINI::instance();
        $siteName = $ini->variable( 'SiteSettings', 'SiteURL' );

        $classURL = 'http://' . $siteName . '/class/view/' . $class->attribute( 'id' );
        $siteURL = 'http://' . $siteName . '/';

        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'site-url',
                                                                        $siteURL ) );
        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'url',
                                                                        $classURL ) );

        $classGroupsNode =& eZDOMDocument::createElementNode( 'groups' );

        $classGroupList =& eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
                                                                     $class->attribute( 'version' ) );
        foreach ( array_keys( $classGroupList ) as $classGroupKey )
        {
            $classGroupLink =& $classGroupList[$classGroupKey];
            $classGroup =& eZContentClassGroup::fetch( $classGroupLink->attribute( 'group_id' ) );
            if ( $classGroup )
                $classGroupsNode->appendChild( eZDOMDocument::createElementNode( 'group',
                                                                                 array( 'id' => $classGroup->attribute( 'id' ),
                                                                                        'name' => $classGroup->attribute( 'name' ) ) ) );
        }
        $remoteNode->appendChild( $classGroupsNode );

        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'id',
                                                                        $class->attribute( 'id' ) ) );
        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'created',
                                                                        $class->attribute( 'created' ) ) );
        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'modified',
                                                                        $class->attribute( 'modified' ) ) );

        $creatorNode =& eZDOMDocument::createElementNode( 'creator' );
        $remoteNode->appendChild( $creatorNode );
        $creatorNode->appendChild( eZDOMDocument::createElementTextNode( 'user-id',
                                                                         $class->attribute( 'creator_id' ) ) );
        $creator =& $class->attribute( 'creator' );
        if ( $creator )
            $creatorNode->appendChild( eZDOMDocument::createElementTextNode( 'user-login',
                                                                             $creator->attribute( 'login' ) ) );

        $modifierNode =& eZDOMDocument::createElementNode( 'modifier' );
        $remoteNode->appendChild( $modifierNode );
        $modifierNode->appendChild( eZDOMDocument::createElementTextNode( 'user-id',
                                                                          $class->attribute( 'modifier_id' ) ) );
        $modifier =& $class->attribute( 'modifier' );
        if ( $modifier )
            $modifierNode->appendChild( eZDOMDocument::createElementTextNode( 'user-login',
                                                                              $modifier->attribute( 'login' ) ) );
        // Remote data end

        $attributesNode =& eZDOMDocument::createElementNode( 'attributes' );
        $attributesNode->appendAttribute( eZDOMDocument::createAttributeNode( 'ezcontentclass-attribute',
                                                                              'http://ezpublish/contentclassattribute',
                                                                              'xmlns' ) );
        $classNode->appendChild( $attributesNode );

        $attributes =& $class->fetchAttributes();
        for ( $i = 0; $i < count( $attributes ); ++$i )
        {
            $attribute =& $attributes[$i];
            $attributeNode =& eZDOMDocument::createElementNode( 'attribute',
                                                                array( 'datatype' => $attribute->attribute( 'data_type_string' ),
                                                                       'required' => $attribute->attribute( 'is_required' ) ? 'true' : 'false',
                                                                       'searchable' => $attribute->attribute( 'is_searchable' ) ? 'true' : 'false',
                                                                       'information-collector' => $attribute->attribute( 'is_information_collector' ) ? 'true' : 'false',
                                                                       'translatable' => $attribute->attribute( 'can_translate' ) ? 'true' : 'false' ) );
            $attributeRemoteNode =& eZDOMDocument::createElementNode( 'remote' );
            $attributeNode->appendChild( $attributeRemoteNode );
            $attributeRemoteNode->appendChild( eZDOMDocument::createElementTextNode( 'id',
                                                                                     $attribute->attribute( 'id' ) ) );
            $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'name',
                                                                               $attribute->attribute( 'name' ) ) );
            $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'identifier',
                                                                               $attribute->attribute( 'identifier' ) ) );
            $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'placement',
                                                                               $attribute->attribute( 'placement' ) ) );
            $attributeParametersNode =& eZDOMDocument::createElementNode( 'datatype-parameters' );
            $attributeNode->appendChild( $attributeParametersNode );

            $dataType =& $attribute->dataType();
            $dataType->serializeContentClassAttribute( $attribute, $attributeNode, $attributeParametersNode );

            $attributesNode->appendChild( $attributeNode );
        }
        return $classNode;
    }

    function contentclassDirectory()
    {
        return 'ezcontentclass';
    }

    /*!
     \reimp
    */
    function createInstallNode( &$package, $export, &$installNode, $installItem, $installType )
    {
        if ( $installNode->attributeValue( 'type' ) == 'ezcontentclass' )
        {
            if ( $export )
            {
                $classFile = $installItem['filename'] . '.xml';
                if ( $installItem['sub-directory'] )
                    $classFile = $installItem['sub-directory'] . '/' . $classFile;
                $originalPath = $package->path() . '/' . $classFile;
                $exportPath = $export['path'];
                $installDirectory = $exportPath . '/' . eZContentClassPackageHandler::contentclassDirectory();
                if ( !file_exists(  $installDirectory ) )
                    eZDir::mkdir( $installDirectory, eZDir::directoryPermission(), true );
                print( "eZFileHandler::copy( $originalPath, $installDirectory . '/' . " . $installItem['filename'] . " )" );
                eZFileHandler::copy( $originalPath, $installDirectory . '/' . $installItem['filename'] . '.xml' );
            }
        }
    }
}

?>
