<?php
//
// Definition of eZObjectRelationListType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZObjectRelationListType ezobjectrelationlisttype.php
  \ingroup eZKernel
  \brief A content datatype which handles object relations

Bugs/missing features:
- No identifier support yet
- Validation and fixup for "Add new object" functionality
- Proper embed views for admin classes
- No translation page support yet (maybe?)

*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'lib/ezutils/classes/ezintegervalidator.php' );
include_once( 'lib/ezutils/classes/ezinputvalidator.php' );
include_once( 'lib/ezi18n/classes/eztranslatormanager.php' );
include_once( 'lib/ezxml/classes/ezxml.php' );

define( "EZ_DATATYPESTRING_OBJECT_RELATION_LIST", "ezobjectrelationlist" );

class eZObjectRelationListType extends eZDataType
{
    /*!
     Initializes with a string id and a description.
    */
    function eZObjectRelationListType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_OBJECT_RELATION_LIST, ezi18n( 'kernel/classes/datatypes', "Object relation list", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $inputParameters =& $contentObjectAttribute->inputParameters();
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        $parameters = $contentObjectAttribute->validationParameters();
        if ( isset( $parameters['prefix-name'] ) and
             $parameters['prefix-name'] )
            $parameters['prefix-name'][] = $contentClassAttribute->attribute( 'name' );
        else
            $parameters['prefix-name'] = array( $contentClassAttribute->attribute( 'name' ) );
        $content =& $contentObjectAttribute->content();
        $status = EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
                $object =& eZContentObject::fetch( $subObjectID );
                if ( $object )
                {
                    $attributes =& $object->contentObjectAttributes( true, $subObjectVersion );

                    $validationResult = $object->validateInput( $attributes, $attributeBase,
                                                                $inputParameters, $parameters );
                    $inputValidated = $validationResult['input-validated'];
                    $content['temp'][$subObjectID]['require-fixup'] = $validationResult['require-fixup'];
                    $statusMap = $validationResult['status-map'];
                    foreach ( $statusMap as $statusItem )
                    {
                        $statusValue = $statusItem['value'];
                        $statusAttribute =& $statusItem['attribute'];
                        if ( $statusValue == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE and
                             $status == EZ_INPUT_VALIDATOR_STATE_ACCEPTED )
                            $status = EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE;
                        else if ( $statusValue == EZ_INPUT_VALIDATOR_STATE_INVALID )
                        {
                            $contentObjectAttribute->setHasValidationError( false );
                            $status = EZ_INPUT_VALIDATOR_STATE_INVALID;
                        }
                    }

                    $content['temp'][$subObjectID]['attributes'] =& $attributes;
                    $content['temp'][$subObjectID]['object'] =& $object;
                }
            }
        }
        return $status;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function fixupObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->content();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
                $object =& $content['temp'][$subObjectID]['object'];
                $requireFixup = $content['temp'][$subObjectID]['require-fixup'];
                if ( $object and
                     $requireFixup )
                {
                    $attributes =& $content['temp'][$subObjectID]['attributes'];
                    $object->fixupInput( $contentObjectAttributes, $attributeBase );
                }
            }
        }
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->content();
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $priorityBase = $base . '_priority';
        $priorities = array();
        if ( $http->hasPostVariable( $priorityBase ) )
            $priorities = $http->postVariable( $priorityBase );
        $reorderedRelationList = array();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
                $object =& $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
                    $attributes =& $content['temp'][$subObjectID]['attributes'];

                    $customActionAttributeArray = array();
                    $fetchResult = $object->fetchInput( $attributes, $attributeBase,
                                                        $customActionAttributeArray,
                                                        $contentObjectAttribute->inputParameters() );
                    unset( $attributeInputMap );
                    $attributeInputMap =& $fetchResult['attribute-input-map'];
                    $content['temp'][$subObjectID]['attribute-input-map'] =& $attributeInputMap;
                    $content['temp'][$subObjectID]['attributes'] =& $attributes;
                    $content['temp'][$subObjectID]['object'] =& $object;
                }
            }
            if ( isset( $priorities[$contentObjectAttributeID][$i] ) )
                $relationItem['priority'] = $priorities[$contentObjectAttributeID][$i];
            $reorderedRelationList[$relationItem['priority']] = $relationItem;
        }
        ksort( $reorderedRelationList );
        unset( $content['relation_list'] );
        $content['relation_list'] = array();
        reset( $reorderedRelationList );
        while ( list( $key, $relationItem ) = each( $reorderedRelationList ) )
        {
            $content['relation_list'][] = $relationItem;
        }
        $contentObjectAttribute->setContent( $content );
        return true;
    }

    /*!
    */
    function storeObjectAttribute( &$attribute )
    {
        $content = $attribute->content();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem =& $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $object =& $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
                    $attributes =& $content['temp'][$subObjectID]['attributes'];
                    $attributeInputMap =& $content['temp'][$subObjectID]['attribute-input-map'];
                    $object->storeInput( $attributes,
                                         $attributeInputMap );
                    $version =& eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    $version->setAttribute( 'modified', time() );
                    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
                    $version->store();
                    $object->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_DRAFT );
                    $object->store();
                }
            }
        }
        return eZObjectRelationListType::storeObjectAttributeContent( $attribute, $content );
    }

    /*!
     \reimp
    */
    function onPublish( &$contentObjectAttribute, &$contentObject, &$publishedNodes )
    {
        $content = $contentObjectAttribute->content();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem =& $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $object =& eZContentObject::fetch( $subObjectID );
                if ( $object )
                {
                    $class =& $object->contentClass();
					$time = time();
                    $version =& eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    $version->setAttribute( 'modified', $time );
                    $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
                    $version->store();
                    $object->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_PUBLISHED );
                    if ( !$object->attribute( 'published' ) )
                        $object->setAttribute( 'published', $time );
                    $object->setAttribute( 'modified', $time );
                    $object->setAttribute( 'current_version', $version->attribute( 'version' ) );
                    $object->setAttribute( 'is_published', true );
                    $objectName = $class->contentObjectName( $object, $version->attribute( 'version' ) );
                    $object->setName( $objectName, $version->attribute( 'version' ) );
                    $object->store();
                }
                if ( $relationItem['parent_node_id'] > 0 )
                {
                    $nodeAssignment =& eZNodeAssignment::create( array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                        'contentobject_version' => $object->attribute( 'current_version' ),
                                                                        'parent_node' => $relationItem['parent_node_id'],
                                                                        'sort_field' => 2,
                                                                        'sort_order' => 0,
                                                                        'is_main' => 1 ) );
                    $nodeAssignment->store();
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                                 'version' => $object->attribute( 'current_version' ) ) );
                    $objectNodeID = $object->attribute( 'main_node_id' );
                    $content['relation_list'][$i]['node_id'] = $objectNodeID;
                }
                else
                {
                    $nodeAssignment =& eZNodeAssignment::create( array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                        'contentobject_version' => $object->attribute( 'current_version' ),
                                                                        'parent_node' => $contentObject->attribute( 'main_node_id' ),
                                                                        'sort_field' => 2,
                                                                        'sort_order' => 0,
                                                                        'is_main' => 1 ) );
                    $nodeAssignment->store();
                }
                $content['relation_list'][$i]['is_modified'] = false;
            }
        }
        eZObjectRelationListType::storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->store();
    }

    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            if ( $contentObjectAttribute->attribute( 'contentobject_id' ) != $originalContentObjectAttribute->attribute( 'contentobject_id' ) )
            {
                $classContent =& eZObjectRelationListType::defaultClassAttributeContent();
                if ( !$classContent['default_placement'] )
                {
                    $content = $originalContentObjectAttribute->content();
                    for ( $i = 0; $i < count( $content['relation_list'] ); $i++ )
                    {
                        $relationItem =& $content['relation_list'][$i];
                        $object =& eZContentObject::fetch( $relationItem['contentobject_id'] );
                        $newObject =& $object->copy( true );
                        $relationItem['contentobject_id'] = $newObject->attribute( 'id' );
                    }
                    $contentObjectAttribute->setContent( $content );
                    $contentObjectAttribute->store();
                }
            }
        }
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $content = $classAttribute->content();
        $postVariable = 'ContentClass_ezobjectrelationlist_class_list_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $postVariable ) )
        {
            $constrainedList = $http->postVariable( $postVariable );
            $constrainedClassList = array();
            foreach ( $constrainedList as $constraint )
            {
                if ( trim( $constraint ) != '' )
                    $constrainedClassList[] = $constraint;
            }
            $content['class_constraint_list'] = $constrainedClassList;
        }
        $typeVariable = 'ContentClass_ezobjectrelationlist_type_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $typeVariable ) )
        {
            $type = $http->postVariable( $typeVariable );
            $content['type'] = $type;
        }
        $classAttribute->setContent( $content );
        $classAttribute->store();
        return true;
    }

    /*!
     \reimp
    */
    function initializeClassAttribute( &$classAttribute )
    {
        $xmlText = $classAttribute->attribute( 'data_text5' );
        if ( trim( $xmlText ) == '' )
        {
            $content = eZObjectRelationListType::defaultClassAttributeContent();
            return eZObjectRelationListType::storeClassAttributeContent( $classAttribute, $content );
        }
    }

    /*!
     \reimp
    */
    function preStoreClassAttribute( &$classAttribute, $version )
    {
        $content = $classAttribute->content();
        return eZObjectRelationListType::storeClassAttributeContent( $classAttribute, $content );
    }

    function storeClassAttributeContent( &$classAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $doc =& eZObjectRelationListType::createClassDOMDocument( $content );
            eZObjectRelationListType::storeClassDOMDocument( $doc, $classAttribute );
            return true;
        }
        return false;
    }

    function storeObjectAttributeContent( &$objectAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $doc =& eZObjectRelationListType::createObjectDOMDocument( $content );
            eZObjectRelationListType::storeObjectDOMDocument( $doc, $objectAttribute );
            return true;
        }
        return false;
    }

    function storeClassDOMDocument( &$doc, &$classAttribute )
    {
        $docText = eZObjectRelationListType::domString( $doc );
        $classAttribute->setAttribute( 'data_text5', $docText );
    }

    function storeObjectDOMDocument( &$doc, &$objectAttribute )
    {
        $docText = eZObjectRelationListType::domString( $doc );
        $objectAttribute->setAttribute( 'data_text', $docText );
    }

    /*!
     \static
     \return the XML structure in \a $domDocument as text.
             It will take of care of the necessary charset conversions
             for content storage.
    */
    function domString( &$domDocument )
    {
        $ini =& eZINI::instance();
        $xmlCharset = $ini->variable( 'RegionalSettings', 'ContentXMLCharset' );
        if ( $xmlCharset == 'enabled' )
        {
            include_once( 'lib/ezi18n/classes/eztextcodec.php' );
            $charset = eZTextCodec::internalCharset();
        }
        else if ( $xmlCharset == 'disabled' )
            $charset = true;
        else
            $charset = $xmlCharset;
        if ( $charset !== true )
        {
            include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        }
        $domString = $domDocument->toString( $charset );
        return $domString;
    }

    function &createClassDOMDocument( $content )
    {
        $doc = new eZDOMDocument( 'ObjectRelationList' );
        $root =& $doc->createElementNode( 'related-objects' );
        $constraints =& $doc->createElementNode( 'constraints' );
        foreach ( $content['class_constraint_list'] as $constraintClassIdentifier )
        {
            $constraintElement =& $doc->createElementNode( 'allowed-class',
                                                           array( 'contentclass-identifier' => $constraintClassIdentifier ) );
            $constraints->appendChild( $constraintElement );
        }
        $root->appendChild( $constraints );
        $constraintType =& $doc->createElementNode( 'type', array( 'value' => $content['type'] ) );
        $root->appendChild( $constraintType );
        $placementAttributes = array();
        if ( $content['default_placement'] )
            $placementAttributes['node-id'] = $content['default_placement']['node_id'];
        $root->appendChild( $doc->createElementNode( 'contentobject-placement',
                                                     $placementAttributes ) );
        $doc->setRoot( $root );
        return $doc;
    }

    function &createObjectDOMDocument( $content )
    {
        $doc = new eZDOMDocument( 'ObjectRelationList' );
        $root =& $doc->createElementNode( 'related-objects' );
        $relationList =& $doc->createElementNode( 'relation-list' );
        foreach ( $content['relation_list'] as $relationItem )
        {
            $relationElement =& $doc->createElementNode( 'relation-item' );
            $relationElement->appendAttributes( $relationItem,
                                                eZObjectRelationListType::contentObjectArrayXMLMap() );
            $relationList->appendChild( $relationElement );
        }
        $root->appendChild( $relationList );
        $doc->setRoot( $root );
        return $doc;
    }

    function contentObjectArrayXMLMap()
    {
        return array( 'identifier' => 'identifier',
                      'priority' => 'priority',
                      'contentobject-id' => 'contentobject_id',
                      'contentobject-version' => 'contentobject_version',
                      'node-id' => 'node_id',
                      'parent-node-id' => 'parent_node_id',
                      'contentclass-id' => 'contentclass_id',
                      'contentclass-identifier' => 'contentclass_identifier',
                      'is-modified' => 'is_modified' );
    }

    /*!
     \reimp
    */
    function deleteStoredObjectAttribute( &$objectAttribute, $version = null )
    {
        $content =& $objectAttribute->content();
        if ( is_array( $content ) and
             is_array( $content['relation_list'] ) )
        {
            foreach ( $content['relation_list'] as $deletionItem )
            {
                eZObjectRelationListType::removeRelationObject( $objectAttribute, $deletionItem );
            }
        }
    }

    /*!
     \reimp
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute, $parameters )
    {
        $contentobjectID = false;
        if ( eZDataType::fetchActionValue( $action, 'new_class', $classID ) or
             $action == 'new_class' )
        {
            if ( $action == 'new_class' )
            {
                $base = $parameters['base_name'];
                $classVariableName = $base . '_new_class';
                if ( $http->hasPostVariable( $classVariableName ) )
                {
                    $classVariable = $http->postVariable( $classVariableName );
                    $classID = $classVariable[$contentObjectAttribute->attribute( 'id' )];
                    $class =& eZContentClass::fetch( $classID );
                }
                else
                    return false;
            }
            else
                $class =& eZContentClass::fetch( $classID );
            if ( $class )
            {
                $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
                $class_content = $classAttribute->content();
                $content = $contentObjectAttribute->content();
                $priority = 0;
                for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
                {
                    if ( $content['relation_list'][$i]['priority'] > $priority )
                        $priority = $content['relation_list'][$i]['priority'];
                }

                $base = $parameters['base_name'];
                $nodePlacement = false;
                $nodePlacementName = $base . '_object_initial_node_placement';
                if ( $http->hasPostVariable( $nodePlacementName ) )
                {
                    $nodePlacementMap = $http->postVariable( $nodePlacementName );
                    if ( isset( $nodePlacementMap[$contentObjectAttribute->attribute( 'id' )] ) )
                        $nodePlacement = $nodePlacementMap[$contentObjectAttribute->attribute( 'id' )];
                }
                $relationItem = eZObjectRelationListType::createInstance( $class,
                                                                          $priority + 1,
                                                                          $contentObjectAttribute,
                                                                          $nodePlacement );
                if ( $class_content['default_placement'] )
                {
                    $relationItem['parent_node_id'] = $class_content['default_placement']['node_id'];
                }

                $content['relation_list'][] = $relationItem;

                $hasAttributeInput = false;
                $attributeInputVariable = $base . '_has_attribute_input';
                if ( $http->hasPostVariable( $attributeInputVariable ) )
                {
                    $attributeInputMap = $http->postVariable( $attributeInputVariable );
                    if ( isset( $attributeInputMap[$contentObjectAttribute->attribute( 'id' )] ) )
                        $hasAttributeInput = $attributeInputMap[$contentObjectAttribute->attribute( 'id' )];
                }

                if ( $hasAttributeInput )
                {
                    $object =& $relationItem['object'];
                    $attributes =& $object->contentObjectAttributes();
                    foreach ( array_keys( $attributes ) as $key )
                    {
                        $attribute =& $attributes[$key];
                        $attributeBase = $base . '_ezorl_init_class_' . $object->attribute( 'contentclass_id' ) . '_attr_' . $attribute->attribute( 'contentclassattribute_id' );
                        $oldAttributeID = $attribute->attribute( 'id' );
                        $attribute->setAttribute( 'id', false );
                        if ( $attribute->fetchInput( $http, $attributeBase ) )
                        {
                            $attribute->setAttribute( 'id', $oldAttributeID );
                            $attribute->store();
                        }
                    }
                }

                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
            else
                eZDebug::writeError( "Unknown class ID $classID, cannot instantiate object",
                                     'eZObjectRelationListType::customObjectAttributeHTTPAction' );
        }
        else if ( eZDataType::fetchActionValue( $action, 'edit_objects', $contentobjectID ) or
                  $action == 'edit_objects' or
                  $action == 'remove_objects' )
        {
            $base = $parameters['base_name'];
            $selectionBase = $base . '_selection';
            $selections = array();
            $http =& eZHTTPTool::instance();
            if ( $http->hasPostVariable( $selectionBase ) )
            {
                $selectionMap = $http->postVariable( $selectionBase );
                $selections = $selectionMap[$contentObjectAttribute->attribute( 'id' )];
            }
            if ( $contentobjectID !== false )
                $selections[] = $contentobjectID;
            if ( $action == 'edit_objects' or
                 eZDataType::fetchActionValue( $action, 'edit_objects', $contentobjectID ) )
            {
                $content = $contentObjectAttribute->content();
                $relationList =& $content['relation_list'];
                for ( $i = 0; $i < count( $relationList ); ++$i )
                {
                    $relationItem =& $relationList[$i];
                    if ( !$relationItem['is_modified'] and
                         in_array( $relationItem['contentobject_id'], $selections ) )
                    {
                        $object =& eZContentObject::fetch( $relationItem['contentobject_id'] );
                        if ( $object->attribute( 'can_edit' ) )
                        {
                            $relationItem['is_modified'] = true;
                            $version =& $object->createNewVersion( $relationItem['contentobject_version'] );
                            $relationItem['contentobject_version'] = $version->attribute( 'version' );
                        }
                    }
                }
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
            else if ( $action == 'remove_objects' )
            {
                $content = $contentObjectAttribute->content();
                $relationList =& $content['relation_list'];
                $newRelationList = array();
                for ( $i = 0; $i < count( $relationList ); ++$i )
                {
                    $relationItem = $relationList[$i];
                    if ( in_array( $relationItem['contentobject_id'], $selections ) )
                    {
                        eZObjectRelationListType::removeRelationObject( $contentObjectAttribute, $relationItem );
                    }
                    else
                        $newRelationList[] = $relationItem;
                }
                $content['relation_list'] =& $newRelationList;
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
        }
        else if ( $action == 'browse_objects' )
        {
            $module =& $parameters['module'];
            $redirectionURI = $parameters['current-redirection-uri'];

            $ini = eZINI::instance( 'content.ini' );
            $browseType = 'AddRelatedObjectToDataType';
            $browseTypeINIVariable = $ini->variable( 'ObjectRelationDataTypeSettings', 'ClassAttributeStartNode' );
            foreach ( $browseTypeINIVariable as $value )
            {
                list( $classAttributeID, $type ) = explode( ';',$value );
                if ( is_numeric( $classAttributeID ) and
                     $classAttributeID == $contentObjectAttribute->attribute( 'contentclassattribute_id' ) and
                     strlen( $type ) > 0 )
                {
                    $browseType = $type;
                    break;
                }
            }
            $browseParameters = array( 'action_name' => 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ),
                                       'type' =>  $browseType,
                                       'browse_custom_action' => array( 'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) . '_set_object_relation_list]',
                                                                        'value' => $contentObjectAttribute->attribute( 'id' ) ),
                                       'persistent_data' => array( 'HasObjectInput' => 0 ),
                                       'from_page' => $redirectionURI );
            $base = $parameters['base_name'];
            $nodePlacementName = $base . '_browse_for_object_start_node';
            if ( $http->hasPostVariable( $nodePlacementName ) )
            {
                $nodePlacement = $http->postVariable( $nodePlacementName );
                if ( isset( $nodePlacement[$contentObjectAttribute->attribute( 'id' )] ) )
                    $browseParameters['start_node'] = eZContentBrowse::nodeAliasID( $nodePlacement[$contentObjectAttribute->attribute( 'id' )] );
            }
            eZContentBrowse::browse( $browseParameters,
                                     $module );
        }
        else if ( $action == 'set_object_relation_list' )
        {
            $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
            $content = $contentObjectAttribute->content();
            $priority = 0;
            for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
            {
                if ( $content['relation_list'][$i]['priority'] > $priority )
                    $priority = $content['relation_list'][$i]['priority'];
            }

            $objectID = $selectedObjectIDArray[0];
            foreach ( $selectedObjectIDArray as $objectID )
            {
                /* Here we check if current object is already in the related objects list.
                 * If so, we don't add it again.
                 * FIXME: Stupid linear search. Maybe there's some better way?
                 */
                $found = false;
                foreach ( $content['relation_list'] as $i )
                {
                    if ( $i['contentobject_id'] == $objectID )
                    {
                        $found = true;
                        break;
                    }
                }
                if ( $found )
                    continue;

                ++$priority;
                $content['relation_list'][] =& $this->appendObject( $objectID, $priority, $contentObjectAttribute );
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
        }
        else
        {
            eZDebug::writeError( "Unknown custom HTTP action: " . $action,
                                 'eZObjectRelationListType' );
        }
    }

    /*!
     \reimp
    */
    function handleCustomObjectHTTPActions( &$http, $attributeDataBaseName,
                                            $customActionAttributeArray, $customActionParameters )
    {
        $contentObjectAttribute =& $customActionParameters['contentobject_attribute'];
        $content =& $contentObjectAttribute->content();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            $subObjectID = $relationItem['contentobject_id'];
            $subObjectVersion = $relationItem['contentobject_version'];

            $attributeBase = $attributeDataBaseName . '_ezorl_edit_object_' . $subObjectID;
            $object =& $content['temp'][$subObjectID]['object'];
            if ( !$object )
                $object =& eZContentObject::fetch( $subObjectID );
            if ( $object )
                $object->handleAllCustomHTTPActions( $attributeBase,
                                                     $customActionAttributeArray,
                                                     $customActionParameters,
                                                     $subObjectVersion );

        }
    }

    /*!
     \static
     \return \c true if the relation item \a $relationItem exist in the content tree.
    */
    function isItemPublished( &$relationItem )
    {
        return is_numeric( $relationItem['node_id'] ) and $relationItem['node_id'] > 0;
    }

    /*!
     \private
     Removes the relation object \a $deletionItem if the item is owned solely by this
     version and is not published in the content tree.
    */
    function removeRelationObject( &$contentObjectAttribute, &$deletionItem )
    {
        if ( eZObjectRelationListType::isItemPublished( $deletionItem ) )
        {
            return;
        }

        $hostObject =& $contentObjectAttribute->attribute( 'object' );
        $hostObjectVersions =& $hostObject->versions();
        $isDeletionAllowed = true;

        // check if the relation item to be deleted is unique in the domain of all host-object versions
        foreach ( $hostObjectVersions as $version )
        {
            if ( $isDeletionAllowed and
                 $version->attribute( 'version' ) != $contentObjectAttribute->attribute( 'version' ) )
            {
                $relationAttribute =& eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                                           null,
                                                                           array( 'version' => $version->attribute( 'version' ),
                                                                                  'contentobject_id' => $hostObject->attribute( 'id' ),
                                                                                  'contentclassattribute_id' => $contentObjectAttribute->attribute( 'contentclassattribute_id' ) ) );

                if ( count( $relationAttribute ) > 0 )
                {
                    $relationContent =& $relationAttribute[0]->content();
                    if ( is_array( $relationContent ) and
                         is_array( $relationContent['relation_list'] ) )
                    {
                        $relationContentCount = count( $relationContent['relation_list'] );
                        for ( $i = 0; $i < $relationContentCount; ++$i )
                        {
                            if ( $deletionItem['contentobject_id'] == $relationContent['relation_list'][$i]['contentobject_id'] &&
                                 $deletionItem['contentobject_version'] == $relationContent['relation_list'][$i]['contentobject_version'] )
                            {
                                $isDeletionAllowed = false;
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        if ( $isDeletionAllowed )
        {
            $subObjectVersion =& eZContentObjectVersion::fetchVersion( $deletionItem['contentobject_version'],
                                                                       $deletionItem['contentobject_id'] );
            if ( get_class( $subObjectVersion ) == 'ezcontentobjectversion' )
            {
                $subObjectVersion->remove();
            }
            else
            {
                eZDebug::writeError( 'Cleanup of subobject-version failed. Could not fetch object from relation list.\n' .
                                     'Requested subobject id: ' . $relationItem['contentobject_id'] . '\n' .
                                     'Requested Subobject version: ' . $relationItem['contentobject_version'],
                                     'eZObjectRelationListType::removeRelationObject' );
            }
        }
    }


    function createInstance( &$class, $priority, &$contentObjectAttribute, $nodePlacement = false )
    {
        $currentObject =& $contentObjectAttribute->attribute( 'object' );
        $sectionID = $currentObject->attribute( 'section_id' );
        $object =& $class->instantiate( false, $sectionID );
        if ( !is_numeric( $nodePlacement ) or $nodePlacement <= 0 )
            $nodePlacement = false;
        $object->sync();
        $relationItem = array( 'identifier' => false,
                               'priority' => $priority,
                               'contentobject_id' => $object->attribute( 'id' ),
                               'contentobject_version' => $object->attribute( 'current_version' ),
                               'node_id' => false,
                               'parent_node_id' => $nodePlacement,
                               'contentclass_id' => $class->attribute( 'id' ),
                               'contentclass_identifier' => $class->attribute( 'identifier' ),
                               'is_modified' => true );
        $relationItem['object'] =& $object;
        return $relationItem;
    }

    function appendObject( $objectID, $priority, &$contentObjectAttribute )
    {
        $object =& eZContentObject::fetch( $objectID );
        $class =& $object->attribute( 'content_class' );
        $sectionID = $object->attribute( 'section_id' );
        $relationItem = array( 'identifier' => false,
                               'priority' => $priority,
                               'contentobject_id' => $object->attribute( 'id' ),
                               'contentobject_version' => $object->attribute( 'current_version' ),
                               'node_id' => $object->attribute( 'main_node_id' ),
                               'parent_node_id' => $object->attribute( 'main_parent_node_id' ),
                               'contentclass_id' => $class->attribute( 'id' ),
                               'contentclass_identifier' => $class->attribute( 'identifier' ),
                               'is_modified' => false );
        $relationItem['object'] =& $object;
        return $relationItem;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $xmlText = $contentObjectAttribute->attribute( 'data_text' );
        if ( trim( $xmlText ) == '' )
            return eZObjectRelationListType::defaultObjectAttributeContent();
        $doc =& eZObjectRelationListType::parseXML( $xmlText );
        $content = eZObjectRelationListType::createObjectContentStructure( $doc );
        return $content;
    }

    /*!
     \reimp
    */
    function &classAttributeContent( &$classAttribute )
    {
        $xmlText = $classAttribute->attribute( 'data_text5' );
        if ( trim( $xmlText ) == '' )
            return eZObjectRelationListType::defaultClassAttributeContent();
        $doc =& eZObjectRelationListType::parseXML( $xmlText );
        $content =& eZObjectRelationListType::createClassContentStructure( $doc );
        return $content;
    }

    function &parseXML( $xmlText )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $xmlText );
        return $dom;
    }

    function &defaultClassAttributeContent()
    {
        $default = array( 'type' => 0,
                          'class_constraint_list' => array(),
                          'default_placement' => false );
        return $default;
    }

    function defaultObjectAttributeContent()
    {
        return array( 'relation_list' => array() );
    }

    function &createClassContentStructure( &$doc )
    {
        $content =& eZObjectRelationListType::defaultClassAttributeContent();
        $root =& $doc->root();
        $objectPlacement =& $root->elementByName( 'contentobject-placement' );

        if ( $objectPlacement and $objectPlacement->hasAttributes() )
        {
            $nodeID =& $objectPlacement->attributeValue( 'node-id' );
            $content['default_placement'] = array( 'node_id' => &$nodeID );
        }
        $constraints =& $root->elementByName( 'constraints' );
        if ( $constraints )
        {
            $allowedClassList =& $constraints->elementsByName( 'allowed-class' );
            for ( $i = 0; $i < count( $allowedClassList ); ++$i )
            {
                $allowedClass =& $allowedClassList[$i];
                $content['class_constraint_list'][] =& $allowedClass->attributeValue( 'contentclass-identifier' );
            }
        }
        $type =& $root->elementByName( 'type' );
        if ( $type )
        {
            $content['type'] = $type->attributeValue( 'value' );
        }
        return $content;
    }

    function createObjectContentStructure( &$doc )
    {
        $content = eZObjectRelationListType::defaultObjectAttributeContent();
        $root =& $doc->root();
        $relationList =& $root->elementByName( 'relation-list' );
        if ( $relationList )
        {
            $relationItems =& $relationList->elementsByName( 'relation-item' );
            for ( $i = 0; $i < count( $relationItems ); ++$i )
            {
                $relationItem =& $relationItems[$i];
                $content['relation_list'][] = $relationItem->attributeValues( eZObjectRelationListType::contentObjectArrayXMLMap(),
                                                                              false );
            }
        }
        return $content;
    }

    /*!
     \reimp
    */
    function customClassAttributeHTTPAction( &$http, $action, &$classAttribute )
    {
        switch ( $action )
        {
            case 'browse_for_placement':
            {
                $module =& $classAttribute->currentModule();
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $customActionName = 'CustomActionButton[' . $classAttribute->attribute( 'id' ) . '_browsed_for_placement]';
                eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationListNode',
                                                'content' => array( 'contentclass_id' => $classAttribute->attribute( 'contentclass_id' ),
                                                                    'contentclass_attribute_id' => $classAttribute->attribute( 'id' ),
                                                                    'contentclass_version' => $classAttribute->attribute( 'version' ),
                                                                    'contentclass_attribute_identifier' => $classAttribute->attribute( 'identifier' ) ),
                                                'persistent_data' => array( $customActionName => '',
                                                                            'ContentClassHasInput' => false ),
                                                'description_template' => 'design:class/datatype/browse_objectrelationlist_placement.tpl',
                                                'from_page' => $module->currentRedirectionURI() ),
                                         $module );
            } break;
            case 'browsed_for_placement':
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeSelection = eZContentBrowse::result( 'SelectObjectRelationListNode' );
                if ( count( $nodeSelection ) > 0 )
                {
                    $nodeID = $nodeSelection[0];
                    $content = $classAttribute->content();
                    $content['default_placement'] = array( 'node_id' => $nodeID );
                    $classAttribute->setContent( $content );
                }
            } break;
            case 'disable_placement':
            {
                $content =& $classAttribute->content();
                $content['default_placement'] = false;
                $classAttribute->setContent( $content );
            } break;
            default:
            {
                eZDebug::writeError( "Unknown objectrelationlist action '$action'", 'eZContentObjectRelationListType::customClassAttributeHTTPAction' );
            } break;
        }
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaDataArray = array();
        $content =& $contentObjectAttribute->content();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            $subObjectID = $relationItem['contentobject_id'];
            $attributes =& $content['temp'][$subObjectID]['attributes'];
            if ( !$attributes )
            {
                $subObjectVersion = $relationItem['contentobject_version'];
                $object =& eZContentObject::fetch( $subObjectID );
                if ( !$object )
                {
                    continue;
                }
                $attributes =& $object->contentObjectAttributes( true, $subObjectVersion );
            }

            $metaDataArray = array_merge( $metaDataArray, eZContentObjectAttribute::metaDataArray( $attributes ) );
        }

        return $metaDataArray;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->content();
        return count( $content['relation_list'] ) > 0;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        return false;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        if ( $content['default_placement'] )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-placement',
                                                                                     array( 'node-id' => $content['default_placement']['node_id'] ) ) );
        $classConstraintsNode =& eZDOMDocument::createElementNode( 'class-constraints' );
        $attributeParametersNode->appendChild( $classConstraintsNode );
        foreach ( $content['class_constraint_list'] as $classConstraint )
        {
            $classConstraintIdentifier = $classConstraint;
            $classConstraintsNode->appendChild( eZDOMDocument::createElementNode( 'class-constraint',
                                                                                  array( 'class-identifier' => $classConstraintIdentifier ) ) );
        }
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        $defaultPlacementNode = $attributeParametersNode->elementByName( 'default-placement' );
        $content['default_placement'] = false;
        if ( $defaultPlacementNode )
            $content['default_placement'] = $defaultPlacementNode->attributeValue( 'node-id' );
        $classConstraintsNode =& $attributeParametersNode->elementByName( 'class-constraints' );
        $classConstraintList =& $classConstraintsNode->children();
        $content['class_constraint_list'] = array();
        foreach ( array_keys( $classConstraintList ) as $classConstraintKey )
        {
            $classConstraintNode =& $classConstraintList[$classConstraintKey];
            $classIdentifier = $classConstraintNode->attributeValue( 'class-identifier' );
            $content['class_constraint_list'][] = $classIdentifier;
        }
        $this->storeClassAttributeContent( $classAttribute, $content );
    }

    /// \privatesection
}

eZDataType::register( EZ_DATATYPESTRING_OBJECT_RELATION_LIST, "ezobjectrelationlisttype" );

?>
