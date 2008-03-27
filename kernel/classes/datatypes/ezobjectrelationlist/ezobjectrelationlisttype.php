<?php
//
// Definition of eZObjectRelationListType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

/*!
  \class eZObjectRelationListType ezobjectrelationlisttype.php
  \ingroup eZDatatype
  \brief A content datatype which handles object relations

Bugs/missing/deprecated features:
- No identifier support yet
- Validation and fixup for "Add new object" functionality
- Proper embed views for admin classes
- No translation page support yet (maybe?)
- is_modified is deprecated and is used for BC only.

*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
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
        $this->eZDataType( EZ_DATATYPESTRING_OBJECT_RELATION_LIST, ezi18n( 'kernel/classes/datatypes', "Object relations", 'Datatype name' ),
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

        $status = EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        $postVariableName = $base . "_data_object_relation_list_" . $contentObjectAttribute->attribute( "id" );
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $contentClassAttribute->content();
        // Check if selection type is not browse
        if ( $classContent['selection_type'] != 0 )
        {
            $selectedObjectIDArray = $http->hasPostVariable( $postVariableName ) ? $http->postVariable( $postVariableName ) : false;
            if ( $contentObjectAttribute->validateIsRequired() and $selectedObjectIDArray === false )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Missing objectrelation list input.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
            return $status;
        }

        $content =& $contentObjectAttribute->content();
        if ( $contentObjectAttribute->validateIsRequired() and count( $content['relation_list'] ) == 0 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Missing objectrelation list input.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

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
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
                $object =& $content['temp'][$subObjectID]['object'];
                $requireFixup = $content['temp'][$subObjectID]['require-fixup'];
                if ( $object and
                     $requireFixup )
                {
                    $attributes =& $content['temp'][$subObjectID]['attributes'];
                    $object->fixupInput( $attributes, $attributeBase );
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
        // new object creation
        $newObjectPostVariableName = "attribute_" . $contentObjectAttribute->attribute( "id" ) . "_new_object_name";
        if ( $http->hasPostVariable( $newObjectPostVariableName ) )
        {
            $name = $http->postVariable( $newObjectPostVariableName );
            if ( !empty( $name ) )
            {
                $content['new_object'] = $name;
            }
        }
        $singleSelectPostVariableName = "single_select_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $singleSelectPostVariableName ) )
            $content['singleselect'] = true;

        $postVariableName = $base . "_data_object_relation_list_" . $contentObjectAttribute->attribute( "id" );
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        $classContent = $contentClassAttribute->content();
        // Check if selection type is not browse
        if ( $classContent['selection_type'] != 0 )
        {
            $selectedObjectIDArray = $http->hasPostVariable( $postVariableName ) ? $http->postVariable( $postVariableName ) : false;
            $priority = 0;
            // We should clear content
            $content['relation_list'] = array();
            // If we got an empty object id list
            if ( $selectedObjectIDArray === false or ( isset( $selectedObjectIDArray[0] ) and $selectedObjectIDArray[0] == 'no_relation' ) )
            {
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
                return true;
            }

            foreach ( $selectedObjectIDArray as $objectID )
            {
                // Check if the given object ID has a numeric value, if not go to the next object.
                if ( !is_numeric( $objectID ) )
                {
                    eZDebug::writeError( "Related object ID (objectID): '$objectID', is not a numeric value.",
                                         "eZObjectRelationListType::fetchObjectAttributeHTTPInput" );

                    continue;
                }
                ++$priority;
                $content['relation_list'][] = $this->appendObject( $objectID, $priority, $contentObjectAttribute );
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
            return true;
        }

        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $priorityBase = $base . '_priority';
        $priorities = array();
        if ( $http->hasPostVariable( $priorityBase ) )
            $priorities = $http->postVariable( $priorityBase );
        $reorderedRelationList = array();
        // Contains existing priorities
        $existsPriorities = array();

        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $priorities[$contentObjectAttributeID][$i] = (int) $priorities[$contentObjectAttributeID][$i];
            $existsPriorities[$i] = $priorities[$contentObjectAttributeID][$i];

            // Change objects' priorities providing their uniqueness.
            for ( $j = 0; $j < count( $content['relation_list'] ); ++$j )
            {
                if ( $i == $j ) continue;
                if ( $priorities[$contentObjectAttributeID][$i] == $priorities[$contentObjectAttributeID][$j] )
                {
                    $index = $priorities[$contentObjectAttributeID][$i];
                    while ( in_array( $index, $existsPriorities ) )
                        ++$index;
                    $priorities[$contentObjectAttributeID][$j] = $index;
                }
            }
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
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
        $i = 0;
        while ( list( $key, $relationItem ) = each( $reorderedRelationList ) )
        {
            $content['relation_list'][] = $relationItem;
            $content['relation_list'][$i]['priority'] = $i + 1;
            ++$i;
        }
        $contentObjectAttribute->setContent( $content );
        return true;
    }

    function createNewObject( &$contentObjectAttribute, $name )
    {
        $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $classContent = $classAttribute->content();
        $classID = $classContent['object_class'];
        if ( !isset( $classID ) or !is_numeric( $classID ) )
            return false;

        $defaultPlacementNode = ( is_array( $classContent['default_placement'] ) and isset( $classContent['default_placement']['node_id'] ) ) ? $classContent['default_placement']['node_id'] : false;
        if ( !$defaultPlacementNode )
        {
            eZDebug::writeError( 'Default placement is missing', 'eZObjectRelationListType::createNewObject' );
            return false;
        }

        $node =& eZContentObjectTreeNode::fetch( $defaultPlacementNode );
        // Check if current user can create a new node as child of this node.
        if ( !$node or !$node->canCreate() )
        {
            eZDebug::writeError( 'Default placement is wrong or the current user can\'t create a new node as child of this node.', 'eZObjectRelationListType::createNewObject' );
            return false;
        }

        $classList =& $node->canCreateClassList( false );
        $canCreate = false;
        // Check if current user can create object of class (with $classID)
        foreach ( $classList as $class )
        {
            if ( $class['id'] == $classID )
            {
                $canCreate = true;
                break;
            }
        }
        if ( !$canCreate )
        {
            eZDebug::writeError( 'The current user is not allowed to create objects of class (ID=' . $classID . ')', 'eZObjectRelationListType::createNewObject' );
            return false;
        }

        $class =& eZContentClass::fetch( $classID );
        if ( !$class )
            return false;

        $currentObject =& $contentObjectAttribute->attribute( 'object' );
        $sectionID = $currentObject->attribute( 'section_id' );
        //instantiate object, same section, currentuser as owner (i.e. provide false as param)
        $newObjectInstance =& $class->instantiate( false, $sectionID );
        $nodeassignment = $newObjectInstance->createNodeAssignment( $defaultPlacementNode, true );
        $nodeassignment->store();
        $newObjectInstance->sync();
        include_once( "lib/ezutils/classes/ezoperationhandler.php" );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObjectInstance->attribute( 'id' ), 'version' => 1 ) );
        // so it updates the attributes
        $newObjectInstance->rename( $name );

        return $newObjectInstance->attribute( 'id' );
    }

    /*!
    */
    function storeObjectAttribute( &$attribute )
    {
        $content = $attribute->content();
        if ( isset( $content['new_object'] ) )
        {
            $newID = $this->createNewObject( $attribute, $content['new_object'] );
            // if this is a single element selection mode (radio or dropdown), then the newly created item is the only one selected
            if ( $newID )
            {
                if ( isset( $content['singleselect'] ) )
                    $content['relation_list'] = array();
                $content['relation_list'][] = $this->appendObject( $newID, 0, $attribute );
            }
            unset( $content['new_object'] );
            $attribute->setContent( $content );
            $attribute->store();
        }

        $contentClassAttributeID = $attribute->ContentClassAttributeID;
        $contentObjectID = $attribute->ContentObjectID;
        $contentObjectVersion = $attribute->Version;

        $obj = $attribute->object();
        //get eZContentObjectVersion
        $currVerobj = $obj->version( $contentObjectVersion );

        // create translation List
        // $translationList will contain for example eng-GB, ita-IT etc.
        $translationList =& $currVerobj->translations( false );

        // get current language_code
        $langCode = $attribute->attribute( 'language_code' );
        // get count of LanguageCode in translationList
        $countTsl = count( $translationList );
        // order by asc
        sort( $translationList );

        if ( ( $countTsl == 1 ) or ( $countTsl > 1 and $translationList[0] == $langCode ) )
        {
             eZContentObject::removeContentObjectRelation( false, $contentObjectVersion, $contentObjectID, $contentClassAttributeID, EZ_CONTENT_OBJECT_RELATION_ATTRIBUTE );
        }

        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem =& $content['relation_list'][$i];

            // Installing content object, postUnserialize is not called yet,
            // so object's ID is unknown.
            if ( !$relationItem['contentobject_id'] || !isset( $relationItem['contentobject_id'] ) )
                continue;

            $subObjectID = $relationItem['contentobject_id'];
            $subObjectVersion = $relationItem['contentobject_version'];

            eZContentObject::addContentObjectRelation( $subObjectID, $contentObjectVersion, $contentObjectID, $contentClassAttributeID, EZ_CONTENT_OBJECT_RELATION_ATTRIBUTE );

            if ( $relationItem['is_modified'] )
            {
                // handling sub-objects
                $object =& $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
                    $attributes =& $content['temp'][$subObjectID]['attributes'];
                    $attributeInputMap =& $content['temp'][$subObjectID]['attribute-input-map'];
                    $object->storeInput( $attributes,
                                         $attributeInputMap );
                    $version = eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    if ( $version )
                    {
                        $version->setAttribute( 'modified', time() );
                        $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
                        $version->store();
                    }

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

                    // Make the previous version archived
                    $currentVersion =& $object->currentVersion();
                    $currentVersion->setAttribute( 'status', EZ_VERSION_STATUS_ARCHIVED );
                    $currentVersion->setAttribute( 'modified', $time );
                    $currentVersion->store();

                    $version = eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
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
                    if ( !eZNodeAssignment::fetch( $object->attribute( 'id' ), $object->attribute( 'current_version' ), $relationItem['parent_node_id'], false ) )
                    {
                        $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                           'contentobject_version' => $object->attribute( 'current_version' ),
                                                                           'parent_node' => $relationItem['parent_node_id'],
                                                                           'sort_field' => 2,
                                                                           'sort_order' => 0,
                                                                           'is_main' => 1 ) );
                        $nodeAssignment->store();
                    }
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                                 'version' => $object->attribute( 'current_version' ) ) );
                    $objectNodeID = $object->attribute( 'main_node_id' );
                    $content['relation_list'][$i]['node_id'] = $objectNodeID;
                }
                else
                {
                    if ( !eZNodeAssignment::fetch( $object->attribute( 'id' ), $object->attribute( 'current_version' ), $contentObject->attribute( 'main_node_id' ), false ) )
                    {
                        $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                           'contentobject_version' => $object->attribute( 'current_version' ),
                                                                           'parent_node' => $contentObject->attribute( 'main_node_id' ),
                                                                           'sort_field' => 2,
                                                                           'sort_order' => 0,
                                                                           'is_main' => 1 ) );
                        $nodeAssignment->store();
                    }
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

        static $copiedRelatedAccordance;
        if ( !isset( $copiedRelatedAccordance ) )
            $copiedRelatedAccordance = array();

        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( 'data_text' );
            $contentObjectAttribute->setAttribute( 'data_text', $dataText );
            $contentObjectID = $contentObjectAttribute->attribute( 'contentobject_id' );
            $originalContentObjectID = $originalContentObjectAttribute->attribute( 'contentobject_id' );

            if ( $contentObjectID != $originalContentObjectID )
            {
                $classContent = eZObjectRelationListType::defaultClassAttributeContent();
                if ( !$classContent['default_placement'] )
                {
                    $content = $originalContentObjectAttribute->content();
                    $contentModified = false;

                    foreach ( array_keys( $content['relation_list'] ) as $key )
                    {
                        $relationItem =& $content['relation_list'][$key];

                        // create related object copies only if they are subobjects
                        $object =& eZContentObject::fetch( $relationItem['contentobject_id'] );
                        $mainNode = $object->attribute( 'main_node' );

                        if ( is_object( $mainNode ) )
                        {
                            $node = ( is_numeric( $relationItem['node_id'] ) and $relationItem['node_id'] ) ?
                                      eZContentObjectTreeNode::fetch( $relationItem['node_id'] ) : null;

                            if ( !$node or $node->attribute( 'contentobject_id' ) != $relationItem['contentobject_id'] )
                            {
                                $relationItem['node_id'] = $mainNode->attribute( 'node_id' );
                                $node = $mainNode;
                                $contentModified = true;
                            }

                            $parentNodeID = $node->attribute( 'parent_node_id' );
                            if ( $relationItem['parent_node_id'] != $parentNodeID )
                            {
                                $relationItem['parent_node_id'] = $parentNodeID;
                                $contentModified = true;
                            }
                        }
                        else
                        {
                            if ( !isset( $copiedRelatedAccordance[ $relationItem['contentobject_id'] ] ) )
                                $copiedRelatedAccordance[ $relationItem['contentobject_id'] ] = array();

                            if ( isset( $copiedRelatedAccordance[ $relationItem['contentobject_id'] ] ) and
                                 isset( $copiedRelatedAccordance[ $relationItem['contentobject_id'] ][ $contentObjectID ] ) )
                            {
                                $newObjectID = $copiedRelatedAccordance[ $relationItem['contentobject_id'] ][ $contentObjectID ][ 'to' ];
                            }
                            else
                            {
                                $newObject =& $object->copy( true );
                                $newObjectID = $newObject->attribute( 'id' );
                                $copiedRelatedAccordance[ $relationItem['contentobject_id'] ][ $contentObjectID ] = array( 'to' => $newObjectID,
                                                                                                                           'from' => $originalContentObjectID );
                            }
                            $relationItem['contentobject_id'] = $newObjectID;
                            $contentModified = true;
                        }
                    }

                    if ( $contentModified )
                    {
                        $contentObjectAttribute->setContent( $content );
                        $contentObjectAttribute->store();
                    }
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
        $selectionTypeVariable = 'ContentClass_ezobjectrelationlist_selection_type_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $selectionTypeVariable ) )
        {
            $selectionType = $http->postVariable( $selectionTypeVariable );
            $content['selection_type'] = $selectionType;
        }
        $objectClassVariable = 'ContentClass_ezobjectrelation_object_class_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $objectClassVariable ) )
        {
            $content['object_class'] = $http->postVariable( $objectClassVariable );
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
            $doc = eZObjectRelationListType::createClassDOMDocument( $content );
            eZObjectRelationListType::storeClassDOMDocument( $doc, $classAttribute );
            return true;
        }
        return false;
    }

    function storeObjectAttributeContent( &$objectAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $doc = eZObjectRelationListType::createObjectDOMDocument( $content );
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

    function createClassDOMDocument( $content )
    {
        $doc = new eZDOMDocument( 'ObjectRelationList' );
        $root = $doc->createElementNode( 'related-objects' );
        $constraints = $doc->createElementNode( 'constraints' );
        foreach ( $content['class_constraint_list'] as $constraintClassIdentifier )
        {
            unset( $constraintElement );
            $constraintElement = $doc->createElementNode( 'allowed-class',
                                                           array( 'contentclass-identifier' => $constraintClassIdentifier ) );
            $constraints->appendChild( $constraintElement );
        }
        $root->appendChild( $constraints );
        $constraintType = $doc->createElementNode( 'type', array( 'value' => $content['type'] ) );
        $root->appendChild( $constraintType );
        $selectionType = $doc->createElementNode( 'selection_type', array( 'value' => $content['selection_type'] ) );
        $root->appendChild( $selectionType );
        $objectClass = $doc->createElementNode( 'object_class', array( 'value' => $content['object_class'] ) );
        $root->appendChild( $objectClass );

        $placementAttributes = array();
        if ( $content['default_placement'] )
            $placementAttributes['node-id'] = $content['default_placement']['node_id'];
        $root->appendChild( $doc->createElementNode( 'contentobject-placement',
                                                     $placementAttributes ) );
        $doc->setRoot( $root );
        return $doc;
    }

    function createObjectDOMDocument( $content )
    {
        $doc = new eZDOMDocument( 'ObjectRelationList' );
        $root = $doc->createElementNode( 'related-objects' );
        $relationList = $doc->createElementNode( 'relation-list' );
        foreach ( $content['relation_list'] as $relationItem )
        {
            unset( $relationElement );
            $relationElement = $doc->createElementNode( 'relation-item' );
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
                      'in-trash' => 'in_trash',
                      'contentobject-id' => 'contentobject_id',
                      'contentobject-version' => 'contentobject_version',
                      'node-id' => 'node_id',
                      'parent-node-id' => 'parent_node_id',
                      'contentclass-id' => 'contentclass_id',
                      'contentclass-identifier' => 'contentclass_identifier',
                      'is-modified' => 'is_modified',
                      'contentobject-remote-id' => 'contentobject_remote_id' );
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
            $db =& eZDB::instance();
            $db->begin();
            foreach ( $content['relation_list'] as $deletionItem )
            {
                eZObjectRelationListType::removeRelationObject( $objectAttribute, $deletionItem );
            }
            $db->commit();
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
                    $class = eZContentClass::fetch( $classID );
                }
                else
                    return false;
            }
            else
                $class = eZContentClass::fetch( $classID );
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
                            $version = $object->createNewVersion();
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
            $browseType = 'AddRelatedObjectListToDataType';
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

            // Fetch the list of "allowed" classes .
            // A user can select objects of only those allowed classes when browsing.
            $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
            $classContent   =& $classAttribute->content();
            if ( isset( $classContent['class_constraint_list'] ) )
                $classConstraintList =& $classContent['class_constraint_list'];
            else
                $classConstraintList = array();

            include_once( 'kernel/classes/ezcontentbrowse.php' );
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
            if ( count($classConstraintList) > 0 )
                $browseParameters['class_array'] = $classConstraintList;

            eZContentBrowse::browse( $browseParameters,
                                     $module );
        }
        else if ( $action == 'set_object_relation_list' )
        {
            if ( !$http->hasPostVariable( 'BrowseCancelButton' ) )
            {
                $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
                $content = $contentObjectAttribute->content();
                $priority = 0;
                for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
                {
                    if ( $content['relation_list'][$i]['priority'] > $priority )
                        $priority = $content['relation_list'][$i]['priority'];
                }

                foreach ( $selectedObjectIDArray as $objectID )
                {
                    // Check if the given object ID has a numeric value, if not go to the next object.
                    if ( !is_numeric( $objectID ) )
                    {
                        eZDebug::writeError( "Related object ID (objectID): '$objectID', is not a numeric value.",
                            "eZObjectRelationListType::customObjectAttributeHTTPAction" );

                        continue;
                    }

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
                    $content['relation_list'][] = $this->appendObject( $objectID, $priority, $contentObjectAttribute );
                    $contentObjectAttribute->setContent( $content );
                    $contentObjectAttribute->store();
                }
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
            if ( eZContentObject::recursionProtect( $subObjectID ) )
            {
                if ( !$object )
                    $object =& eZContentObject::fetch( $subObjectID );
                if ( $object )
                    $object->handleAllCustomHTTPActions( $attributeBase,
                                                         $customActionAttributeArray,
                                                         $customActionParameters,
                                                         $subObjectVersion );
            }
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
                $relationAttribute = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
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
            $subObjectVersion = eZContentObjectVersion::fetchVersion( $deletionItem['contentobject_version'],
                                                                      $deletionItem['contentobject_id'] );
            if ( get_class( $subObjectVersion ) == 'ezcontentobjectversion' )
            {
                $subObjectVersion->remove();
            }
            else
            {
                eZDebug::writeError( 'Cleanup of subobject-version failed. Could not fetch object from relation list.\n' .
                                     'Requested subobject id: ' . $deletionItem['contentobject_id'] . '\n' .
                                     'Requested Subobject version: ' . $deletionItem['contentobject_version'],
                                     'eZObjectRelationListType::removeRelationObject' );
            }
        }
    }


    function createInstance( &$class, $priority, &$contentObjectAttribute, $nodePlacement = false )
    {
        $currentObject =& $contentObjectAttribute->attribute( 'object' );
        $sectionID = $currentObject->attribute( 'section_id' );
        $object = $class->instantiate( false, $sectionID );
        if ( !is_numeric( $nodePlacement ) or $nodePlacement <= 0 )
            $nodePlacement = false;
        $object->sync();
        $relationItem = array( 'identifier' => false,
                               'priority' => $priority,
                               'in_trash' => false,
                               'contentobject_id' => $object->attribute( 'id' ),
                               'contentobject_version' => $object->attribute( 'current_version' ),
                               'contentobject_remote_id' => $object->attribute( 'remote_id' ),
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
                               'in_trash' => false,
                               'contentobject_id' => $object->attribute( 'id' ),
                               'contentobject_version' => $object->attribute( 'current_version' ),
                               'contentobject_remote_id' => $object->attribute( 'remote_id' ),
                               'node_id' => $object->attribute( 'main_node_id' ),
                               'parent_node_id' => $object->attribute( 'main_parent_node_id' ),
                               'contentclass_id' => $class->attribute( 'id' ),
                               'contentclass_identifier' => $class->attribute( 'identifier' ),
                               'is_modified' => false );
        $relationItem['object'] =& $object;
        return $relationItem;
    }


    function fixRelatedObjectItem ( &$contentObjectAttribute, $objectID, $mode = false )
    {
        switch ( $mode )
        {
            case 'move':
                eZObjectRelationListType::fixRelationsMove( $objectID, $contentObjectAttribute );
                break;

            case 'trash':
                eZObjectRelationListType::fixRelationsTrash( $objectID, $contentObjectAttribute );
                break;

            case 'restore':
                eZObjectRelationListType::fixRelationsRestore( $objectID, $contentObjectAttribute );
                break;

            case 'remove':
                eZObjectRelationListType::fixRelationsRemove( $objectID, $contentObjectAttribute );
                break;

            case 'swap':
                eZObjectRelationListType::fixRelationsSwap( $objectID, $contentObjectAttribute );
                break;
        }
    }

    function fixRelationsMove ( $objectID, &$contentObjectAttribute )
    {
        $this->fixRelationsSwap( $objectID, $contentObjectAttribute );
    }

    function fixRelationsTrash ( $objectID, &$contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->attribute( 'content' );
        foreach ( array_keys( $content['relation_list'] ) as $key )
        {
            if ( $content['relation_list'][$key]['contentobject_id'] == $objectID )
            {
                $content['relation_list'][$key]['in_trash'] = true;
                $content['relation_list'][$key]['node_id'] = null;
                $content['relation_list'][$key]['parent_node_id']= null;
            }
        }
        eZObjectRelationListType::storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->storeData();
    }

    function fixRelationsRestore ( $objectID, &$contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->content();

        foreach ( array_keys( $content['relation_list'] ) as $key )
        {
            if ( $content['relation_list'][$key]['contentobject_id'] == $objectID )
            {
                $priority = $content['relation_list'][$key]['priority'];
                $content['relation_list'][$key] = $this->appendObject( $objectID, $priority, $contentObjectAttribute);
            }
        }
        eZObjectRelationListType::storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->storeData();
    }

    function fixRelationsRemove ( $objectID, $contentObjectAttribute )
    {
        $this->removeRelatedObjectItem( $contentObjectAttribute, $objectID );
        $contentObjectAttribute->storeData();
    }

    function fixRelationsSwap ( $objectID, &$contentObjectAttribute )
    {
        $content =& $contentObjectAttribute->content();

        foreach ( array_keys( $content['relation_list'] ) as $key )
        {
            $relatedObject =& $content['relation_list'][$key];
            if ( $relatedObject['contentobject_id'] == $objectID )
            {
                $priority = $content['relation_list'][$key]['priority'];
                $content['relation_list'][$key] = $this->appendObject($objectID, $priority, $contentObjectAttribute );
            }
        }

        eZObjectRelationListType::storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->storeData();
    }


    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $xmlText = $contentObjectAttribute->attribute( 'data_text' );
        if ( trim( $xmlText ) == '' )
        {
            $objectAttributeContent = eZObjectRelationListType::defaultObjectAttributeContent();
            return $objectAttributeContent;
        }
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
        {
            $classAttributeContent = eZObjectRelationListType::defaultClassAttributeContent();
            return $classAttributeContent;
        }
        $doc =& eZObjectRelationListType::parseXML( $xmlText );
        $content = eZObjectRelationListType::createClassContentStructure( $doc );
        return $content;
    }

    function &parseXML( $xmlText )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $xmlText );
        return $dom;
    }

    function defaultClassAttributeContent()
    {
        return array( 'object_class' => '',
                      'selection_type' => 0,
                      'type' => 0,
                      'class_constraint_list' => array(),
                      'default_placement' => false );
    }

    function defaultObjectAttributeContent()
    {
        return array( 'relation_list' => array() );
    }

    function createClassContentStructure( &$doc )
    {
        $content = eZObjectRelationListType::defaultClassAttributeContent();
        $root =& $doc->root();
        $objectPlacement =& $root->elementByName( 'contentobject-placement' );

        if ( $objectPlacement and $objectPlacement->hasAttributes() )
        {
            $nodeID = $objectPlacement->attributeValue( 'node-id' );
            $content['default_placement'] = array( 'node_id' => $nodeID );
        }
        $constraints =& $root->elementByName( 'constraints' );
        if ( $constraints )
        {
            $allowedClassList = $constraints->elementsByName( 'allowed-class' );
            foreach( $allowedClassList as $allowedClass )
            {
                $content['class_constraint_list'][] = $allowedClass->attributeValue( 'contentclass-identifier' );
            }
        }
        $type =& $root->elementByName( 'type' );
        if ( $type )
        {
            $content['type'] = $type->attributeValue( 'value' );
        }
        $selectionType =& $root->elementByName( 'selection_type' );
        if ( $selectionType )
        {
            $content['selection_type'] = $selectionType->attributeValue( 'value' );
        }
        $objectClass =& $root->elementByName( 'object_class' );
        if ( $objectClass )
        {
            $content['object_class'] = $objectClass->attributeValue( 'value' );
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
            $relationItems = $relationList->elementsByName( 'relation-item' );
            foreach( $relationItems as $relationItem )
            {
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
                if ( $nodeSelection and count( $nodeSelection ) > 0 )
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
            if ( !$subObjectID )
                continue;

            $attributes =& $content['temp'][$subObjectID]['attributes'];
            if ( !$attributes )
            {
                $subObjectVersion = $relationItem['contentobject_version'];
                $object =& eZContentObject::fetch( $subObjectID );
                if ( eZContentObject::recursionProtect( $subObjectID ) )
                {
                    if ( !$object )
                    {
                        continue;
                    }
                    $attributes =& $object->contentObjectAttributes( true, $subObjectVersion );
                }
            }

            $metaDataArray = array_merge( $metaDataArray, eZContentObjectAttribute::metaDataArray( $attributes ) );
        }

        return $metaDataArray;
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        $objectAttributeContent = $contentObjectAttribute->attribute( 'content' );
        $objectIDList = array();
        foreach( $objectAttributeContent['relation_list'] as $objectInfo )
        {
            $objectIDList[] = $objectInfo['contentobject_id'];
        }
        return implode( '-', $objectIDList );
    }

    function fromString( &$contentObjectAttribute, $string )
    {
        $objectIDList = explode( '-', $string );

        $content = eZObjectRelationListType::defaultObjectAttributeContent();
        $priority = 0;
        foreach( $objectIDList as $objectID )
        {
            $object = eZContentObject::fetch( $objectID );
            if ( $object )
            {
                ++$priority;
                $content['relation_list'][] = $this->appendObject( $objectID, $priority, $contentObjectAttribute );
            }
            else
            {
                eZDebug::writeWarning( $objectID, "Can not create relation because object is missing" );
            }
        }
        $contentObjectAttribute->setContent( $content );
        return true;
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
     Returns the content of the string for use as a title,
     for simplicity this is the name of the first object referenced or false.
    */
    function title( &$contentObjectAttribute )
    {
        $objectAttributeContent = $this->objectAttributeContent( $contentObjectAttribute );

        if ( count( $objectAttributeContent['relation_list'] ) > 0 )
        {
            $target = $objectAttributeContent['relation_list'][0];
            $targetObject =& eZContentObject::fetch( $target['contentobject_id'], false );
            return $targetObject['name'];
        }
        else
        {
            return false;
        }
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        if ( $content['default_placement'] )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-placement',
                                                                                     array( 'node-id' => $content['default_placement']['node_id'] ) ) );
        if ( is_numeric( $content['type'] ) )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'type', $content['type'] ) );
        else
            $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'type', '0' ) );
        $classConstraintsNode = eZDOMDocument::createElementNode( 'class-constraints' );
        $attributeParametersNode->appendChild( $classConstraintsNode );
        foreach ( $content['class_constraint_list'] as $classConstraint )
        {
            $classConstraintIdentifier = $classConstraint;
            $classConstraintsNode->appendChild( eZDOMDocument::createElementNode( 'class-constraint',
                                                                                  array( 'class-identifier' => $classConstraintIdentifier ) ) );
        }

        if ( isset( $content['selection_type'] ) && is_numeric( $content['selection_type'] ) )
        {
            $selectionTypeNode = eZDOMDocument::createElementTextNode( 'selection-type', $content['selection_type'] );
            $attributeParametersNode->appendChild( $selectionTypeNode );
        }

        if ( isset( $content['object_class'] ) && is_numeric( $content['object_class'] ) )
        {
            $objectClassNode = eZDOMDocument::createElementTextNode( 'object-class', $content['object_class'] );
            $attributeParametersNode->appendChild( $objectClassNode );
        }
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        $defaultPlacementNode = $attributeParametersNode->elementByName( 'default-placement' );
        $content['default_placement'] = false;
        if ( $defaultPlacementNode )
            $content['default_placement'] = array( 'node_id' => $defaultPlacementNode->attributeValue( 'node-id' ) );
        $content['type'] = $attributeParametersNode->elementTextContentByName( 'type' );
        $classConstraintsNode =& $attributeParametersNode->elementByName( 'class-constraints' );
        $classConstraintList = $classConstraintsNode->children();
        $content['class_constraint_list'] = array();
        foreach ( $classConstraintList as $classConstraintNode )
        {
            $classIdentifier = $classConstraintNode->attributeValue( 'class-identifier' );
            $content['class_constraint_list'][] = $classIdentifier;
        }
        $objectClassNode = $attributeParametersNode->elementByName( 'object-class' );
        if ( $objectClassNode )
        {
            $content['object_class'] = $objectClassNode->textContent();
        }

        $selectionTypeNode = $attributeParametersNode->elementByName( 'selection-type' );
        if ( $selectionTypeNode )
        {
            $content['selection_type'] = $selectionTypeNode->textContent();
        }

        $classAttribute->setContent( $content );
        $this->storeClassAttributeContent( $classAttribute, $content );
    }

    /*!
     For each relation export its priority and content object remote_id, like this:
      <related-objects>
        <relation-list>
          <relation-item priority="1"
                         contentobject-remote-id="faaeb9be3bd98ed09f606fc16d144eca" />
          <relation-item priority="2"
                         contentobject-remote-id="1bb4fe25487f05527efa8bfd394cecc7" />
        </relation-list>
     To do this we fetch content XML and strip all the relation attributes except of "priority" from there,
     and add "contentobject-remote-id" attribute.
     \reimp
    */
    function serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $xml = new eZXML();
        $domDocument = $xml->domTree( $objectAttribute->attribute( 'data_text' ) );
        $rootNode =& $domDocument->root();
        $relationList =& $rootNode->elementByName( 'relation-list' );
        if ( $relationList )
        {
            require_once( 'kernel/classes/ezcontentobject.php' );
            $relationItems = $relationList->elementsByName( 'relation-item' );
            foreach( $relationItems as $i => $relationItem )
            {
                // Add related object remote id as attribute to the relation item.
                $relatedObjectID = $relationItem->attributeValue( 'contentobject-id' );
                $relatedObject = eZContentObject::fetch( $relatedObjectID );
                $relatedObjectRemoteID = $relatedObject->attribute( 'remote_id' );
                require_once( 'kernel/classes/ezcontentobject.php' );
                $relationItems[$i]->setAttribute( 'contentobject-remote-id', $relatedObjectRemoteID );

                // Remove all other relation item attributes except of "priority".
                foreach ( $relationItem->attributes() as $attribute )
                {
                    $attrName = $attribute->name();
                    if ( $attrName != 'priority' && $attrName != 'contentobject-remote-id' )
                        $relationItems[$i]->removeAttribute( $attrName );
                }
            }
        }

        $node->appendChild( $rootNode );

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        $rootNode = $attributeNode->firstChild();

        if ( $rootNode->attributeValue( 'local_name' ) == 'data-text' )
            $xmlString = '';
        else
        {
            $xmlString = $rootNode->toString( 0 );
        }

        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }

    function postUnserializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $xmlString = $objectAttribute->attribute( 'data_text' );
        $doc =& $this->parseXML( $xmlString );
        $rootNode =& $doc->root();

        $relationList =& $rootNode->elementByName( 'relation-list' );
        if ( !$relationList )
            return false;

        require_once( 'kernel/classes/ezcontentobject.php' );
        $relationItems = $relationList->elementsByName( 'relation-item' );
        foreach( $relationItems as $i => $relationItem )
        {
            $relatedObjectRemoteID = $relationItem->attributeValue( 'contentobject-remote-id' );
            $object = eZContentObject::fetchByRemoteID( $relatedObjectRemoteID );

            if ( $object === null )
            {
                eZDebug::writeWarning( "Object with remote id '$relatedObjectRemoteID' not found: removing the link.",
                                       'eZObjectRelationListType::unserializeContentObjectAttribute()' );
                unset( $relationItems[$i] );
                continue;
            }

            $relationItems[$i]->setAttribute( 'contentobject-id',        $object->attribute( 'id' ) );
            $relationItems[$i]->setAttribute( 'contentobject-version',   $object->attribute( 'current_version' ) );
            $relationItems[$i]->setAttribute( 'node-id',                 $object->attribute( 'main_node_id' ) );
            $relationItems[$i]->setAttribute( 'parent-node-id',          $object->attribute( 'main_parent_node_id' ) );
            $relationItems[$i]->setAttribute( 'contentclass-id',         $object->attribute( 'contentclass_id' ) );
            $relationItems[$i]->setAttribute( 'contentclass-identifier', $object->attribute( 'class_identifier' ) );
        }

        $newXmlString = $rootNode->toString( 0 );

        $objectAttribute->setAttribute( 'data_text', $newXmlString );
        return true;
    }

    /*!
     Removes objects with given ID from the relations list
    */
    function removeRelatedObjectItem( &$contentObjectAttribute, $objectID )
    {
        $xmlText = $contentObjectAttribute->attribute( 'data_text' );
        if ( trim( $xmlText ) == '' ) return;

        $doc =& eZObjectRelationListType::parseXML( $xmlText );

        $return = false;
        $root =& $doc->root();
        $relationList =& $root->elementByName( 'relation-list' );
        if ( $relationList )
        {
            $relationItems = $relationList->elementsByName( 'relation-item' );
            if ( count( $relationItems ) )
            {
                foreach( array_keys( $relationItems ) as $key )
                {
                    $relationItem =& $relationItems[$key];
                    if ( $relationItem->attributeValue( 'contentobject-id' ) == $objectID )
                    {
                        $relationList->removeChild( $relationItem );
                        $return = true;
                    }
                }
            }
        }
        eZObjectRelationListType::storeObjectDOMDocument( $doc, $contentObjectAttribute );
        return $return;
    }

    /// \privatesection
}

eZDataType::register( EZ_DATATYPESTRING_OBJECT_RELATION_LIST, "ezobjectrelationlisttype" );

?>
