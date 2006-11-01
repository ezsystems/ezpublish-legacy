<?php
//
// Definition of eZContentClassPackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezcontentclasspackagehandler.php
*/

/*!
  \class eZContentClassPackageHandler ezcontentclasspackagehandler.php
  \brief Handles content classes in the package system

*/

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezpackagehandler.php' );

define( "EZ_PACKAGE_CONTENTCLASS_ERROR_EXISTS", 1 );
define( "EZ_PACKAGE_CONTENTCLASS_ERROR_HAS_OBJECTS", 101 );

define( "EZ_PACKAGE_CONTENTCLASS_REPLACE", 1 );
define( "EZ_PACKAGE_CONTENTCLASS_SKIP", 2 );
define( "EZ_PACKAGE_CONTENTCLASS_NEW", 3 );
define( "EZ_PACKAGE_CONTENTCLASS_DELETE", 4 );

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
                      &$content, &$installParameters,
                      &$installData )
    {
        $classRemoteID = $content->elementTextContentByName( 'remote-id' );

        $class = eZContentClass::fetchByRemoteID( $classRemoteID );

        if ( $class == null )
        {
            eZDebug::writeNotice( "Class having remote id '$classRemoteID' not found.", 'eZContentClassPackageHandler::uninstall()' );
            return true;
        }

        if ( $class->isRemovable() )
        {
            $choosenAction = $this->errorChoosenAction( EZ_PACKAGE_CONTENTCLASS_ERROR_HAS_OBJECTS,
                                                        $installParameters );
            if ( $choosenAction == EZ_PACKAGE_CONTENTCLASS_SKIP )
            {
                return true;
            }
            if ( $choosenAction != EZ_PACKAGE_CONTENTCLASS_DELETE )
            {
                $objectsCount = eZContentObject::fetchSameClassListCount( $class->attribute( 'id' ) );
                $name = $class->attribute( 'name' );
                if ( $objectsCount )
                {
                    $installParameters['error'] = array( 'error_code' => EZ_PACKAGE_CONTENTCLASS_ERROR_HAS_OBJECTS,
                                                         'element_id' => $classRemoteID,
                                                         'description' => ezi18n( 'kernel/package',
                                                                                  "Removing class '%classname' will result in the removal of %objectscount object(s) of this class and all their sub-items. Are you sure you want to uninstall it?",
                                                                                  false,
                                                                                  array( '%classname' => $name,
                                                                                         '%objectscount' => $objectsCount ) ),
                                                         'actions' => array( EZ_PACKAGE_CONTENTCLASS_DELETE => "Uninstall class and object(s)",
                                                                             EZ_PACKAGE_CONTENTCLASS_SKIP => 'Skip' ) );
                    return false;
                }
            }

            eZDebug::writeNotice( sprintf( "Removing class '%s' (%d)", $class->attribute( 'name' ), $class->attribute( 'id' ) ) );

            include_once( 'kernel/classes/ezcontentclassoperations.php' );
            eZContentClassOperations::remove( $class->attribute( 'id' ) );
        }

        return true;
    }

    /*!
     \reimp
     Creates a new contentclass as defined in the xml structure.
    */
    function install( &$package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      &$content, &$installParameters,
                      &$installData )
    {
        $classNameList = new eZContentClassNameList();
        $classNameList->initFromSerializedList( $content->elementTextContentByName( 'serialized-name-list' ) );
        if ( $classNameList->isEmpty() )
            $classNameList->initFromString( $content->elementTextContentByName( 'name' ) );

        $classIdentifier = $content->elementTextContentByName( 'identifier' );
        $classRemoteID = $content->elementTextContentByName( 'remote-id' );
        $classObjectNamePattern = $content->elementTextContentByName( 'object-name-pattern' );
        $classIsContainer = $content->attributeValue( 'is-container' );
        if ( $classIsContainer !== false )
            $classIsContainer = $classIsContainer == 'true' ? 1 : 0;

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

        $class = eZContentClass::fetchByRemoteID( $classRemoteID );

        if ( $class )
        {
            $description = ezi18n( 'kernel/package', "Class '%classname' already exists.", false,
                                   array( '%classname' => $classNameList->name() ) );

            $choosenAction = $this->errorChoosenAction( EZ_PACKAGE_CONTENTCLASS_ERROR_EXISTS,
                                                        $installParameters, $description );
            switch( $choosenAction )
            {
            case EZ_PACKAGE_NON_INTERACTIVE:
            case EZ_PACKAGE_CONTENTCLASS_REPLACE:
                include_once( 'kernel/classes/ezcontentclassoperations.php' );
                if ( eZContentClassOperations::remove( $class->attribute( 'id' ) ) == false )
                    return true;
                break;

            case EZ_PACKAGE_CONTENTCLASS_SKIP:
                return true;

            case EZ_PACKAGE_CONTENTCLASS_NEW:
                $class->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)mktime() ) );
                $class->store();
                $classNameList->setName( $classNameList->name() . " (imported)" );
                break;

            default:
                $installParameters['error'] = array( 'error_code' => EZ_PACKAGE_CONTENTCLASS_ERROR_EXISTS,
                                                     'element_id' => $classRemoteID,
                                                     'description' => $description,
                                                     'actions' => array() );
                if ( $class->isRemovable() )
                {
                    $errorMsg = ezi18n( 'kernel/package', "Replace existing class" );
                    $objectsCount = eZContentObject::fetchSameClassListCount( $class->attribute( 'id' ) );
                    if ( $objectsCount )
                        $errorMsg .= ' ' . ezi18n( 'kernel/package', "(Warning! $objectsCount content object(s) and their sub-items will be removed)" );
                    $installParameters['error']['actions'][EZ_PACKAGE_CONTENTCLASS_REPLACE] = $errorMsg;
                }
                $installParameters['error']['actions'][EZ_PACKAGE_CONTENTCLASS_SKIP] = ezi18n( 'kernel/package', 'Skip installing this class' );
                $installParameters['error']['actions'][EZ_PACKAGE_CONTENTCLASS_NEW] = ezi18n( 'kernel/package', 'Keep existing and create a new one' );
                return false;
            }
        }

        unset( $class );

        // Try to create a unique class identifier
        $currentClassIdentifier = $classIdentifier;
        $unique = false;

        while( !$unique )
        {
            $classList = eZContentClass::fetchByIdentifier( $currentClassIdentifier );
            if ( $classList )
            {
                // "increment" class identifier
                if ( preg_match( '/^(.*)_(\d+)$/', $currentClassIdentifier, $matches ) )
                    $currentClassIdentifier = $matches[1] . '_' . ( $matches[2] + 1 );
                else
                    $currentClassIdentifier = $currentClassIdentifier . '_1';
            }
            else
                $unique = true;

            unset( $classList );
        }

        $classIdentifier = $currentClassIdentifier;

        $initialLanguageID = $classNameList->alwaysAvailableLanguageID();
        $languageMask = $classNameList->languageMask();
        // Get lang Locale if we want to create class in current primary language, if false site.ini[RegionalSettings].ContentObjectLocale will be used.
        $nameList = $classNameList->nameList();
        $langLocale = isset( $nameList['always-available'] ) ? $nameList['always-available'] : false ;

        // create class
        $class = eZContentClass::create( $userID,
                                         array( 'version' => 0,
                                                'serialized_name_list' => $classNameList->serializeNames(),
                                                'identifier' => $classIdentifier,
                                                'remote_id' => $classRemoteID,
                                                'contentobject_name' => $classObjectNamePattern,
                                                'is_container' => $classIsContainer,
                                                'created' => $classCreated,
                                                'modified' => $classModified,
                                                'initial_language_id' => $initialLanguageID,
                                                'language_mask' => $languageMask ),
                                          $langLocale );

        //$classNameList->setHasDataDirty();
        $class->setAlwaysAvailableLanguageID( $initialLanguageID );
        // setAlwaysAvailableLanguageID will do 'store'
        //$class->store();

        $classID = $class->attribute( 'id' );

        if ( !isset( $installData['classid_list'] ) )
            $installData['classid_list'] = array();
        if ( !isset( $installData['classid_map'] ) )
            $installData['classid_map'] = array();
        $installData['classid_list'][] = $class->attribute( 'id' );
        $installData['classid_map'][$classID] = $class->attribute( 'id' );

        // create class attributes
        $classAttributeList = $classAttributesNode->children();
        foreach ( $classAttributeList as $classAttributeNode )
        {
            $isNotSupported = strtolower( $classAttributeNode->attributeValue( 'unsupported' ) ) == 'true';
            if ( $isNotSupported )
                continue;

            $attributeDatatype = $classAttributeNode->attributeValue( 'datatype' );
            $attributeIsRequired = strtolower( $classAttributeNode->attributeValue( 'required' ) ) == 'true';
            $attributeIsSearchable = strtolower( $classAttributeNode->attributeValue( 'searchable' ) ) == 'true';
            $attributeIsInformationCollector = strtolower( $classAttributeNode->attributeValue( 'information-collector' ) ) == 'true';
            $attributeIsTranslatable = strtolower( $classAttributeNode->attributeValue( 'translatable' ) ) == 'true';
            $attributeSerializedNameList = new eZContentClassAttributeNameList();
            $attributeSerializedNameList->initFromSerializedList( $classAttributeNode->elementTextContentByName( 'serialized-name-list' ) );
            if ( $attributeSerializedNameList->isEmpty() )
                $attributeSerializedNameList->initFromString( $classAttributeNode->elementTextContentByName( 'name' ) );
            $attributeIdentifier = $classAttributeNode->elementTextContentByName( 'identifier' );
            $attributePlacement = $classAttributeNode->elementTextContentByName( 'placement' );
            $attributeDatatypeParameterNode = $classAttributeNode->elementByName( 'datatype-parameters' );

            $classAttribute =& $class->fetchAttributeByIdentifier( $attributeIdentifier );
            if ( !$classAttribute )
            {
                $classAttribute = eZContentClassAttribute::create( $class->attribute( 'id' ),
                                                                   $attributeDatatype,
                                                                   array( 'version' => 0,
                                                                          'identifier' => $attributeIdentifier,
                                                                          'serialized_name_list' => $attributeSerializedNameList->serializeNames(),
                                                                          'is_required' => $attributeIsRequired,
                                                                          'is_searchable' => $attributeIsSearchable,
                                                                          'is_information_collector' => $attributeIsInformationCollector,
                                                                          'can_translate' => $attributeIsTranslatable,
                                                                          'placement' => $attributePlacement ),
                                                                   $langLocale );
                $dataType = $classAttribute->dataType();
                $classAttribute->store();
                $dataType->unserializeContentClassAttribute( $classAttribute, $classAttributeNode, $attributeDatatypeParameterNode );
                $classAttribute->sync();
            }
        }

        // add class to a class group
        $classGroupsList = $classGroupsNode->children();
        foreach ( $classGroupsList as $classGroupNode )
        {
            $classGroupName = $classGroupNode->attributeValue( 'name' );
            $classGroup = eZContentClassGroup::fetchByName( $classGroupName );
            if ( !$classGroup )
            {
                $classGroup = eZContentClassGroup::create();
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
            $class = eZContentClass::fetch( $classID );
        if ( !$class )
            return;
        $classNode =& eZContentClassPackageHandler::classDOMTree( $class );
        if ( !$classNode )
            return;
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

    /*!
     \private
    */
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
                                $class = eZContentClass::fetch( $classID );
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
                                $class = eZContentClass::fetch( $realClassID );
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
        {
            $retValue = false;
            return $retValue;
        }

        $classNode = eZDOMDocument::createElementNode( 'content-class' );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'name',
                                                                       $class->attribute( 'name' ) ) );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'identifier',
                                                                       $class->attribute( 'identifier' ) ) );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'remote-id',
                                                                        $class->attribute( 'remote_id' ) ) );
        $classNode->appendChild( eZDOMDocument::createElementTextNode( 'object-name-pattern',
                                                                       $class->attribute( 'contentobject_name' ) ) );
        $classNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-container',
                                                                         $class->attribute( 'is_container' ) ? 'true' : 'false' ) );

        // Remote data start
        $remoteNode = eZDOMDocument::createElementNode( 'remote' );
        $classNode->appendChild( $remoteNode );

        $ini =& eZINI::instance();
        $siteName = $ini->variable( 'SiteSettings', 'SiteURL' );

        $classURL = 'http://' . $siteName . '/class/view/' . $class->attribute( 'id' );
        $siteURL = 'http://' . $siteName . '/';

        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'site-url',
                                                                        $siteURL ) );
        $remoteNode->appendChild( eZDOMDocument::createElementTextNode( 'url',
                                                                        $classURL ) );

        $classGroupsNode = eZDOMDocument::createElementNode( 'groups' );

        $classGroupList = eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
                                                                     $class->attribute( 'version' ) );
        foreach ( array_keys( $classGroupList ) as $classGroupKey )
        {
            $classGroupLink =& $classGroupList[$classGroupKey];
            $classGroup = eZContentClassGroup::fetch( $classGroupLink->attribute( 'group_id' ) );
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

        $creatorNode = eZDOMDocument::createElementNode( 'creator' );
        $remoteNode->appendChild( $creatorNode );
        $creatorNode->appendChild( eZDOMDocument::createElementTextNode( 'user-id',
                                                                         $class->attribute( 'creator_id' ) ) );
        $creator =& $class->attribute( 'creator' );
        if ( $creator )
            $creatorNode->appendChild( eZDOMDocument::createElementTextNode( 'user-login',
                                                                             $creator->attribute( 'login' ) ) );

        $modifierNode = eZDOMDocument::createElementNode( 'modifier' );
        $remoteNode->appendChild( $modifierNode );
        $modifierNode->appendChild( eZDOMDocument::createElementTextNode( 'user-id',
                                                                          $class->attribute( 'modifier_id' ) ) );
        $modifier =& $class->attribute( 'modifier' );
        if ( $modifier )
            $modifierNode->appendChild( eZDOMDocument::createElementTextNode( 'user-login',
                                                                              $modifier->attribute( 'login' ) ) );
        // Remote data end

        $attributesNode = eZDOMDocument::createElementNode( 'attributes' );
        $attributesNode->appendAttribute( eZDOMDocument::createAttributeNode( 'ezcontentclass-attribute',
                                                                              'http://ezpublish/contentclassattribute',
                                                                              'xmlns' ) );
        $classNode->appendChild( $attributesNode );

        $attributes =& $class->fetchAttributes();
        for ( $i = 0; $i < count( $attributes ); ++$i )
        {
            $attribute =& $attributes[$i];
            unset( $attributeNode );
            $attributeNode = eZDOMDocument::createElementNode( 'attribute',
                                                               array( 'datatype' => $attribute->attribute( 'data_type_string' ),
                                                                      'required' => $attribute->attribute( 'is_required' ) ? 'true' : 'false',
                                                                      'searchable' => $attribute->attribute( 'is_searchable' ) ? 'true' : 'false',
                                                                      'information-collector' => $attribute->attribute( 'is_information_collector' ) ? 'true' : 'false',
                                                                      'translatable' => $attribute->attribute( 'can_translate' ) ? 'true' : 'false' ) );
            unset( $attributeRemoteNode );
            $attributeRemoteNode = eZDOMDocument::createElementNode( 'remote' );
            $attributeNode->appendChild( $attributeRemoteNode );
            $attributeRemoteNode->appendChild( eZDOMDocument::createElementTextNode( 'id',
                                                                                     $attribute->attribute( 'id' ) ) );
            $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'name',
                                                                               $attribute->attribute( 'name' ) ) );
            $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'identifier',
                                                                               $attribute->attribute( 'identifier' ) ) );
            $attributeNode->appendChild( eZDOMDocument::createElementTextNode( 'placement',
                                                                               $attribute->attribute( 'placement' ) ) );
            unset( $attributeParametersNode );
            $attributeParametersNode = eZDOMDocument::createElementNode( 'datatype-parameters' );
            $attributeNode->appendChild( $attributeParametersNode );

            $dataType = $attribute->dataType();
            if ( is_object( $dataType ) )
            {
                $dataType->serializeContentClassAttribute( $attribute, $attributeNode, $attributeParametersNode );
            }

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
    /*function createInstallNode( &$package, $export, &$installNode, $installItem, $installType )
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
                eZFileHandler::copy( $originalPath, $installDirectory . '/' . $installItem['filename'] . '.xml' );
            }
        }
    }
    */
}

?>
