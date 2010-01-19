<?php
//
// Definition of eZObjectRelationListType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

class eZObjectRelationListType extends eZDataType
{
    const DATA_TYPE_STRING = "ezobjectrelationlist";

    /*!
     Initializes with a string id and a description.
    */
    function eZObjectRelationListType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Object relations", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $inputParameters = $contentObjectAttribute->inputParameters();
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $parameters = $contentObjectAttribute->validationParameters();
        if ( isset( $parameters['prefix-name'] ) and
             $parameters['prefix-name'] )
            $parameters['prefix-name'][] = $contentClassAttribute->attribute( 'name' );
        else
            $parameters['prefix-name'] = array( $contentClassAttribute->attribute( 'name' ) );

        $status = eZInputValidator::STATE_ACCEPTED;
        $postVariableName = $base . "_data_object_relation_list_" . $contentObjectAttribute->attribute( "id" );
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $classContent = $contentClassAttribute->content();
        // Check if selection type is not browse
        if ( $classContent['selection_type'] != 0 )
        {
            $selectedObjectIDArray = $http->hasPostVariable( $postVariableName ) ? $http->postVariable( $postVariableName ) : false;
            if ( $contentObjectAttribute->validateIsRequired() and $selectedObjectIDArray === false )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Missing objectrelation list input.' ) );
                return eZInputValidator::STATE_INVALID;
            }
            return $status;
        }

        $content = $contentObjectAttribute->content();
        if ( $contentObjectAttribute->validateIsRequired() and count( $content['relation_list'] ) == 0 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Missing objectrelation list input.' ) );
            return eZInputValidator::STATE_INVALID;
        }

        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
                $object = eZContentObject::fetch( $subObjectID );
                if ( $object )
                {
                    $attributes = $object->contentObjectAttributes( true, $subObjectVersion );

                    $validationResult = $object->validateInput( $attributes, $attributeBase,
                                                                $inputParameters, $parameters );
                    $inputValidated = $validationResult['input-validated'];
                    $content['temp'][$subObjectID]['require-fixup'] = $validationResult['require-fixup'];
                    $statusMap = $validationResult['status-map'];
                    foreach ( $statusMap as $statusItem )
                    {
                        $statusValue = $statusItem['value'];
                        if ( $statusValue == eZInputValidator::STATE_INTERMEDIATE and
                             $status == eZInputValidator::STATE_ACCEPTED )
                            $status = eZInputValidator::STATE_INTERMEDIATE;
                        else if ( $statusValue == eZInputValidator::STATE_INVALID )
                        {
                            $contentObjectAttribute->setHasValidationError( false );
                            $status = eZInputValidator::STATE_INVALID;
                        }
                    }

                    $content['temp'][$subObjectID]['attributes'] = $attributes;
                    $content['temp'][$subObjectID]['object'] = $object;
                }
            }
        }

        $contentObjectAttribute->setContent( $content );
        return $status;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function fixupObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
                $object = $content['temp'][$subObjectID]['object'];
                $requireFixup = $content['temp'][$subObjectID]['require-fixup'];
                if ( $object and
                     $requireFixup )
                {
                    $attributes = $content['temp'][$subObjectID]['attributes'];
                    $object->fixupInput( $attributes, $attributeBase );
                }
            }
        }
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
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
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $classContent = $contentClassAttribute->content();

        $selectedObjectIDArray = $http->hasPostVariable( $postVariableName ) ? $http->postVariable( $postVariableName ) : false;
        
        // If we got an empty object id list
        if ( ( $selectedObjectIDArray === false && $classContent['selection_type'] != 0 ) || ( isset( $selectedObjectIDArray[0] ) && $selectedObjectIDArray[0] === 'no_relation' ) )
        {
            $content['relation_list'] = array();
        	$contentObjectAttribute->setContent( $content );
            $contentObjectAttribute->store();
            return true;
        }

        // Check if selection type is not browse 
        if ( $classContent['selection_type'] != 0 )
        {
            $priority = 0;
            $content['relation_list'] = array();
        	foreach ( $selectedObjectIDArray as $objectID )
            {
                // Check if the given object ID has a numeric value, if not go to the next object.
                if ( !is_numeric( $objectID ) )
                {
                    eZDebug::writeError( "Related object ID (objectID): '$objectID', is not a numeric value.", __METHOD__ );

                    continue;
                }
                ++$priority;
                $content['relation_list'][] = $this->appendObject( $objectID, $priority, $contentObjectAttribute );
            }

            $contentObjectAttribute->setContent( $content );
            return true;
        }

        $priorities               = array();
        $priorityBase             = $base . '_priority';
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $priorityBase ) )
            $priorities = $http->postVariable( $priorityBase );

        // Add new relations
        if ( $selectedObjectIDArray )
        {
            foreach ( $selectedObjectIDArray as $x => $objectID )
            {
                // Check if the given object ID has a numeric value, if not go to the next object.
                if ( !is_numeric( $objectID ) )
                {
                    eZDebug::writeError( "Related object ID (objectID): '$objectID', is not a numeric value.", __METHOD__ );

                    continue;
                }
                for ( $y = 0, $c = count( $content['relation_list'] ); $y < $c; ++$y )
                {
                	if ( $objectID == $content['relation_list'][$y]['contentobject_id'] )
                	{
                		continue 2;
                	}
                }
                $content['relation_list'][] = $this->appendObject( $objectID, $priorities[$contentObjectAttributeID][$x], $contentObjectAttribute );
            }
        }

        $reorderedRelationList    = array();
        // Contains existing priorities
        $existsPriorities = array();

        for ( $i = 0, $c = count( $content['relation_list'] ); $i < $c; ++$i )
        {
            $priorities[$contentObjectAttributeID][$i] = (int) $priorities[$contentObjectAttributeID][$i];
            $existsPriorities[$i] = $priorities[$contentObjectAttributeID][$i];

            // Change objects' priorities providing their uniqueness.
            for ( $j = 0; $j < $c; ++$j )
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
                $object = $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
                    $attributes = $content['temp'][$subObjectID]['attributes'];

                    $customActionAttributeArray = array();
                    $fetchResult = $object->fetchInput( $attributes, $attributeBase,
                                                        $customActionAttributeArray,
                                                        $contentObjectAttribute->inputParameters() );
                    $content['temp'][$subObjectID]['attribute-input-map'] = $fetchResult['attribute-input-map'];
                    $content['temp'][$subObjectID]['attributes'] = $attributes;
                    $content['temp'][$subObjectID]['object'] = $object;
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

    function createNewObject( $contentObjectAttribute, $name )
    {
        $classAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $classContent = $classAttribute->content();
        $classID = $classContent['object_class'];
        if ( !isset( $classID ) or !is_numeric( $classID ) )
            return false;

        $defaultPlacementNode = ( is_array( $classContent['default_placement'] ) and isset( $classContent['default_placement']['node_id'] ) ) ? $classContent['default_placement']['node_id'] : false;
        if ( !$defaultPlacementNode )
        {
            eZDebug::writeError( 'Default placement is missing', __METHOD__ );
            return false;
        }

        $node = eZContentObjectTreeNode::fetch( $defaultPlacementNode );
        // Check if current user can create a new node as child of this node.
        if ( !$node or !$node->canCreate() )
        {
            eZDebug::writeError( 'Default placement is wrong or the current user can\'t create a new node as child of this node.', __METHOD__ );
            return false;
        }

        $classList = $node->canCreateClassList( false );
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
            eZDebug::writeError( 'The current user is not allowed to create objects of class (ID=' . $classID . ')', __METHOD__ );
            return false;
        }

        $class = eZContentClass::fetch( $classID );
        if ( !$class )
            return false;

        $currentObject = $contentObjectAttribute->attribute( 'object' );
        $sectionID = $currentObject->attribute( 'section_id' );
        //instantiate object, same section, currentuser as owner (i.e. provide false as param)
        $newObjectInstance = $class->instantiate( false, $sectionID );
        $nodeassignment = $newObjectInstance->createNodeAssignment( $defaultPlacementNode, true );
        $nodeassignment->store();
        $newObjectInstance->sync();
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObjectInstance->attribute( 'id' ), 'version' => 1 ) );
        // so it updates the attributes
        $newObjectInstance->rename( $name );

        return $newObjectInstance->attribute( 'id' );
    }

    function storeObjectAttribute( $attribute )
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
        }

        $contentClassAttributeID = $attribute->ContentClassAttributeID;
        $contentObjectID = $attribute->ContentObjectID;
        $contentObjectVersion = $attribute->Version;

        $obj = $attribute->object();
        //get eZContentObjectVersion
        $currVerobj = $obj->version( $contentObjectVersion );

        // create translation List
        // $translationList will contain for example eng-GB, ita-IT etc.
        $translationList = $currVerobj->translations( false );

        // get current language_code
        $langCode = $attribute->attribute( 'language_code' );
        // get count of LanguageCode in translationList
        $countTsl = count( $translationList );
        // order by asc
        sort( $translationList );

        if ( ( $countTsl == 1 ) or ( $countTsl > 1 and $translationList[0] == $langCode ) )
        {
             eZContentObject::fetch( $contentObjectID )->removeContentObjectRelation( false, $contentObjectVersion, $contentClassAttributeID, eZContentObject::RELATION_ATTRIBUTE );
        }

        foreach( $content['relation_list'] as $relationItem )
        {
            // Installing content object, postUnserialize is not called yet,
            // so object's ID is unknown.
            if ( !$relationItem['contentobject_id'] || !isset( $relationItem['contentobject_id'] ) )
                continue;

            $subObjectID = $relationItem['contentobject_id'];
            $subObjectVersion = $relationItem['contentobject_version'];

            eZContentObject::fetch( $contentObjectID )->addContentObjectRelation( $subObjectID, $contentObjectVersion, $contentClassAttributeID, eZContentObject::RELATION_ATTRIBUTE );

            if ( $relationItem['is_modified'] && isset( $content['temp'][$subObjectID]['object' ] ) )
            {
                // handling sub-objects
                $object = $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
                    $attributes = $content['temp'][$subObjectID]['attributes'];
                    $attributeInputMap = $content['temp'][$subObjectID]['attribute-input-map'];
                    $object->storeInput( $attributes,
                                         $attributeInputMap );
                    $version = eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    if ( $version )
                    {
                        $version->setAttribute( 'modified', time() );
                        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
                        $version->store();
                    }

                    $object->setAttribute( 'status', eZContentObject::STATUS_DRAFT );
                    $object->store();
                }
            }
        }
        return $this->storeObjectAttributeContent( $attribute, $content );
    }

    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $content = $contentObjectAttribute->content();
        foreach( $content['relation_list'] as $key => $relationItem )
        {
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $object = eZContentObject::fetch( $subObjectID );

                if ( $object )
                {
                    $class = $object->contentClass();
                    $time = time();

                    // Make the previous version archived
                    $currentVersion = $object->currentVersion();
                    $currentVersion->setAttribute( 'status', eZContentObjectVersion::STATUS_ARCHIVED );
                    $currentVersion->setAttribute( 'modified', $time );
                    $currentVersion->store();

                    $version = eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    $version->setAttribute( 'modified', $time );
                    $version->setAttribute( 'status', eZContentObjectVersion::STATUS_PUBLISHED );
                    $version->store();
                    $object->setAttribute( 'status', eZContentObject::STATUS_PUBLISHED );
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
                                                                           'sort_field' => eZContentObjectTreeNode::SORT_FIELD_PUBLISHED,
                                                                           'sort_order' => eZContentObjectTreeNode::SORT_ORDER_DESC,
                                                                           'is_main' => 1 ) );
                        $nodeAssignment->store();
                    }
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                                 'version' => $object->attribute( 'current_version' ) ) );
                    $objectNodeID = $object->attribute( 'main_node_id' );
                    $content['relation_list'][$key]['node_id'] = $objectNodeID;
                }
                else
                {
                    if ( !eZNodeAssignment::fetch( $object->attribute( 'id' ), $object->attribute( 'current_version' ), $contentObject->attribute( 'main_node_id' ), false ) )
                    {
                        $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                           'contentobject_version' => $object->attribute( 'current_version' ),
                                                                           'parent_node' => $contentObject->attribute( 'main_node_id' ),
                                                                           'sort_field' => eZContentObjectTreeNode::SORT_FIELD_PUBLISHED,
                                                                           'sort_order' => eZContentObjectTreeNode::SORT_ORDER_DESC,
                                                                           'is_main' => 1 ) );
                        $nodeAssignment->store();
                    }
                }
                $content['relation_list'][$key]['is_modified'] = false;
            }
        }
        $this->storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->store();
    }

    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
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
                $classContent = $this->defaultClassAttributeContent();
                if ( !$classContent['default_placement'] )
                {
                    $content = $originalContentObjectAttribute->content();
                    $contentModified = false;

                    foreach ( $content['relation_list'] as $key => $relationItem )
                    {
                        // create related object copies only if they are subobjects
                        $object = eZContentObject::fetch( $relationItem['contentobject_id'] );
                        if ( !$object instanceof eZContentObject )
                        {
                            unset( $content['relation_list'][$key] );
                            $contentModified = true;
                            continue;
                        }

                        $mainNode = $object->attribute( 'main_node' );
                        if ( $mainNode instanceof eZContentObjectTreeNode )
                        {
                            $node = ( is_numeric( $relationItem['node_id'] ) and $relationItem['node_id'] ) ?
                                      eZContentObjectTreeNode::fetch( $relationItem['node_id'] ) : null;

                            if ( !$node or $node->attribute( 'contentobject_id' ) != $relationItem['contentobject_id'] )
                            {
                                $content['relation_list'][$key]['node_id'] = $mainNode->attribute( 'node_id' );
                                $node = $mainNode;
                                $contentModified = true;
                            }

                            if ( $node instanceof eZContentObjectTreeNode )
                                $parentNodeID =  $node->attribute( 'parent_node_id' );
                            else
                                $parentNodeID = -1;

                            if ( $relationItem['parent_node_id'] != $parentNodeID )
                            {
                                $content['relation_list'][$key]['parent_node_id'] = $parentNodeID;
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
                                $newObject = $object->copy( true );
                                $newObjectID = $newObject->attribute( 'id' );
                                $copiedRelatedAccordance[ $relationItem['contentobject_id'] ][ $contentObjectID ] = array( 'to' => $newObjectID,
                                                                                                                           'from' => $originalContentObjectID );
                            }
                            $content['relation_list'][$key]['contentobject_id'] = $newObjectID;
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

    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
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

    function initializeClassAttribute( $classAttribute )
    {
        $xmlText = $classAttribute->attribute( 'data_text5' );
        if ( trim( $xmlText ) == '' )
        {
            $content = $this->defaultClassAttributeContent();
            return $this->storeClassAttributeContent( $classAttribute, $content );
        }
    }

    function preStoreClassAttribute( $classAttribute, $version )
    {
        $content = $classAttribute->content();
        return $this->storeClassAttributeContent( $classAttribute, $content );
    }

    function storeClassAttributeContent( $classAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $doc = $this->createClassDOMDocument( $content );
            $this->storeClassDOMDocument( $doc, $classAttribute );
            return true;
        }
        return false;
    }

    function storeObjectAttributeContent( $objectAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $doc = $this->createObjectDOMDocument( $content );
            $this->storeObjectDOMDocument( $doc, $objectAttribute );
            return true;
        }
        return false;
    }

    static function storeClassDOMDocument( $doc, $classAttribute )
    {
        $docText = self::domString( $doc );
        $classAttribute->setAttribute( 'data_text5', $docText );
    }

    static function storeObjectDOMDocument( $doc, $objectAttribute )
    {
        $docText = self::domString( $doc );
        $objectAttribute->setAttribute( 'data_text', $docText );
    }

    /*!
     \static
     \return the XML structure in \a $domDocument as text.
             It will take of care of the necessary charset conversions
             for content storage.
    */
    static function domString( $domDocument )
    {
        $ini = eZINI::instance();
        $xmlCharset = $ini->variable( 'RegionalSettings', 'ContentXMLCharset' );
        if ( $xmlCharset == 'enabled' )
        {
            $charset = eZTextCodec::internalCharset();
        }
        else if ( $xmlCharset == 'disabled' )
            $charset = true;
        else
            $charset = $xmlCharset;
        if ( $charset !== true )
        {
            $charset = eZCharsetInfo::realCharsetCode( $charset );
        }
        $domString = $domDocument->saveXML();
        return $domString;
    }

    static function createClassDOMDocument( $content )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( 'related-objects' );
        $constraints = $doc->createElement( 'constraints' );
        foreach ( $content['class_constraint_list'] as $constraintClassIdentifier )
        {
            unset( $constraintElement );
            $constraintElement = $doc->createElement( 'allowed-class' );
            $constraintElement->setAttribute( 'contentclass-identifier', $constraintClassIdentifier );
            $constraints->appendChild( $constraintElement );
        }
        $root->appendChild( $constraints );
        $constraintType = $doc->createElement( 'type' );
        $constraintType->setAttribute( 'value', $content['type'] );
        $root->appendChild( $constraintType );
        $selectionType = $doc->createElement( 'selection_type' );
        $selectionType->setAttribute( 'value', $content['selection_type'] );
        $root->appendChild( $selectionType );
        $objectClass = $doc->createElement( 'object_class' );
        $objectClass->setAttribute( 'value', $content['object_class'] );
        $root->appendChild( $objectClass );

        $placementNode = $doc->createElement( 'contentobject-placement' );
        if ( $content['default_placement'] )
        {
            $placementNode->setAttribute( 'node-id',  $content['default_placement']['node_id'] );
        }
        $root->appendChild( $placementNode );
        $doc->appendChild( $root );
        return $doc;
    }

    static function createObjectDOMDocument( $content )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( 'related-objects' );
        $relationList = $doc->createElement( 'relation-list' );
        $attributeDefinitions = self::contentObjectArrayXMLMap();

        foreach ( $content['relation_list'] as $relationItem )
        {
            unset( $relationElement );
            $relationElement = $doc->createElement( 'relation-item' );

            foreach ( $attributeDefinitions as $attributeXMLName => $attributeKey )
            {
                if ( isset( $relationItem[$attributeKey] ) && $relationItem[$attributeKey] !== false )
                {
                    $value = $relationItem[$attributeKey];
                    $relationElement->setAttribute( $attributeXMLName, $value );
                }
            }

            $relationList->appendChild( $relationElement );
        }
        $root->appendChild( $relationList );
        $doc->appendChild( $root );
        return $doc;
    }

    static function contentObjectArrayXMLMap()
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

    function deleteStoredObjectAttribute( $objectAttribute, $version = null )
    {
        $content = $objectAttribute->content();
        if ( is_array( $content ) and
             is_array( $content['relation_list'] ) )
        {
            $db = eZDB::instance();
            $db->begin();
            foreach ( $content['relation_list'] as $deletionItem )
            {
                $this->removeRelationObject( $objectAttribute, $deletionItem );
            }
            $db->commit();
        }
    }

    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
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
                $classAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
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
                $relationItem = $this->createInstance( $class,
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
                    $object = $relationItem['object'];
                    $attributes = $object->contentObjectAttributes();
                    foreach ( $attributes as $attribute )
                    {
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

                eZDebug::writeError( "Unknown class ID $classID, cannot instantiate object", __METHOD__ );
        }
        else if ( eZDataType::fetchActionValue( $action, 'edit_objects', $contentobjectID ) or
                  $action == 'edit_objects' or
                  $action == 'remove_objects' )
        {
            $base = $parameters['base_name'];
            $selectionBase = $base . '_selection';
            $selections = array();
            $http = eZHTTPTool::instance();
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
                foreach ( $content['relation_list'] as $key => $relationItem )
                {
                    if ( !$relationItem['is_modified'] and
                         in_array( $relationItem['contentobject_id'], $selections ) )
                    {
                        $object = eZContentObject::fetch( $relationItem['contentobject_id'] );
                        if ( $object->attribute( 'can_edit' ) )
                        {
                            $content['relation_list'][$key]['is_modified'] = true;
                            $version = $object->createNewVersion();
                            $content['relation_list'][$key]['contentobject_version'] = $version->attribute( 'version' );
                        }
                    }
                }
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
            else if ( $action == 'remove_objects' )
            {
                $content = $contentObjectAttribute->content();
                $relationList = $content['relation_list'];
                $newRelationList = array();
                foreach( $relationList as $relationItem )
                {
                    if ( in_array( $relationItem['contentobject_id'], $selections ) )
                    {
                        $this->removeRelationObject( $contentObjectAttribute, $relationItem );
                    }
                    else
                    {
                        $newRelationList[] = $relationItem;
                    }
                }
                $content['relation_list'] = $newRelationList;
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
        }
        else if ( $action == 'browse_objects' )
        {
            $module = $parameters['module'];
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
            $classAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
            $classContent   = $classAttribute->content();
            if ( isset( $classContent['class_constraint_list'] ) )
            {
                $classConstraintList = $classContent['class_constraint_list'];
            }
            else
            {
                $classConstraintList = array();
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
                        eZDebug::writeError( "Related object ID (objectID): '$objectID', is not a numeric value.", __METHOD__ );

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

    function handleCustomObjectHTTPActions( $http, $attributeDataBaseName,
                                            $customActionAttributeArray, $customActionParameters )
    {
        $contentObjectAttribute = $customActionParameters['contentobject_attribute'];
        $content = $contentObjectAttribute->content();
        foreach( $content['relation_list'] as $relationItem )
        {
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];

                $attributeBase = $attributeDataBaseName . '_ezorl_edit_object_' . $subObjectID;
                if ( eZContentObject::recursionProtect( $subObjectID ) )
                {
                    if ( isset ( $content['temp'] ) )
                        $object = $content['temp'][$subObjectID]['object'];
                    else
                        $object = eZContentObject::fetch( $subObjectID );
                    if ( $object )
                        $object->handleAllCustomHTTPActions( $attributeBase,
                                                             $customActionAttributeArray,
                                                             $customActionParameters,
                                                             $subObjectVersion );
                }
            }
        }
    }

    /*!
     \static
     \return \c true if the relation item \a $relationItem exist in the content tree.
    */
    static function isItemPublished( $relationItem )
    {
        return is_numeric( $relationItem['node_id'] ) and $relationItem['node_id'] > 0;
    }

    /*!
     \private
     Removes the relation object \a $deletionItem if the item is owned solely by this
     version and is not published in the content tree.
    */
    function removeRelationObject( $contentObjectAttribute, $deletionItem )
    {
        if ( $this->isItemPublished( $deletionItem ) )
        {
            return;
        }

        $hostObject = $contentObjectAttribute->attribute( 'object' );
        $hostObjectVersions = $hostObject->versions();
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
                    $relationContent = $relationAttribute[0]->content();
                    if ( is_array( $relationContent ) and
                         is_array( $relationContent['relation_list'] ) )
                    {
                        foreach( $relationContent['relation_list'] as $relationItem )
                        {
                            if ( $deletionItem['contentobject_id'] == $relationItem['contentobject_id'] &&
                                 $deletionItem['contentobject_version'] == $relationItem['contentobject_version'] )
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
            if ( $subObjectVersion instanceof eZContentObjectVersion )
            {
                $subObjectVersion->removeThis();
            }
            else
            {
                eZDebug::writeError( 'Cleanup of subobject-version failed. Could not fetch object from relation list.\n' .
                                     'Requested subobject id: ' . $deletionItem['contentobject_id'] . '\n' .
                                     'Requested Subobject version: ' . $deletionItem['contentobject_version'],
                                     __METHOD__ );
            }
        }
    }


    function createInstance( $class, $priority, $contentObjectAttribute, $nodePlacement = false )
    {
        $currentObject = $contentObjectAttribute->attribute( 'object' );
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
        $relationItem['object'] = $object;
        return $relationItem;
    }

    function appendObject( $objectID, $priority, $contentObjectAttribute )
    {
        $object = eZContentObject::fetch( $objectID );
        $class = $object->attribute( 'content_class' );
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
        $relationItem['object'] = $object;
        return $relationItem;
    }


    function fixRelatedObjectItem ( $contentObjectAttribute, $objectID, $mode )
    {
        switch ( $mode )
        {
            case 'move':
            {
                $this->fixRelationsMove( $objectID, $contentObjectAttribute );
            } break;

            case 'trash':
            {
                $this->fixRelationsTrash( $objectID, $contentObjectAttribute );
            } break;

            case 'restore':
            {
                $this->fixRelationsRestore( $objectID, $contentObjectAttribute );
            } break;

            case 'remove':
            {
                $this->fixRelationsRemove( $objectID, $contentObjectAttribute );
            } break;

            case 'swap':
            {
                $this->fixRelationsSwap( $objectID, $contentObjectAttribute );
            } break;

            default:
            {
                eZDebug::writeWarning( 'Unknown mode: ' . $mode, __METHOD__ );
            } break;
        }
    }

    function fixRelationsMove ( $objectID, $contentObjectAttribute )
    {
        $this->fixRelationsSwap( $objectID, $contentObjectAttribute );
    }

    function fixRelationsTrash ( $objectID, $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->attribute( 'content' );
        foreach ( array_keys( $content['relation_list'] ) as $key )
        {
            if ( $content['relation_list'][$key]['contentobject_id'] == $objectID )
            {
                $content['relation_list'][$key]['in_trash'] = true;
                $content['relation_list'][$key]['node_id'] = null;
                $content['relation_list'][$key]['parent_node_id']= null;
            }
        }
        $this->storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->storeData();
    }

    function fixRelationsRestore ( $objectID, $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();

        foreach ( array_keys( $content['relation_list'] ) as $key )
        {
            if ( $content['relation_list'][$key]['contentobject_id'] == $objectID )
            {
                $priority = $content['relation_list'][$key]['priority'];
                $content['relation_list'][$key] = $this->appendObject( $objectID, $priority, $contentObjectAttribute);
            }
        }
        $this->storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->storeData();
    }

    function fixRelationsRemove ( $objectID, $contentObjectAttribute )
    {
        $this->removeRelatedObjectItem( $contentObjectAttribute, $objectID );
        $contentObjectAttribute->storeData();
    }

    function fixRelationsSwap ( $objectID, $contentObjectAttribute )
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

        $this->storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->storeData();
    }


    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $xmlText = $contentObjectAttribute->attribute( 'data_text' );
        if ( trim( $xmlText ) == '' )
        {
            $objectAttributeContent = $this->defaultObjectAttributeContent();
            return $objectAttributeContent;
        }
        $doc = $this->parseXML( $xmlText );
        $content = $this->createObjectContentStructure( $doc );

        return $content;
    }

    function classAttributeContent( $classAttribute )
    {
        $xmlText = $classAttribute->attribute( 'data_text5' );
        if ( trim( $xmlText ) == '' )
        {
            return $this->defaultClassAttributeContent();
        }
        $doc = $this->parseXML( $xmlText );
        return $this->createClassContentStructure( $doc );
    }

    static function parseXML( $xmlText )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $dom->loadXML( $xmlText );
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

    function createClassContentStructure( $doc )
    {
        $content = $this->defaultClassAttributeContent();
        $root = $doc->documentElement;
        $objectPlacement = $root->getElementsByTagName( 'contentobject-placement' )->item( 0 );

        if ( $objectPlacement and $objectPlacement->hasAttributes() )
        {
            $nodeID = $objectPlacement->getAttribute( 'node-id' );
            $content['default_placement'] = array( 'node_id' => $nodeID );
        }
        $constraints = $root->getElementsByTagName( 'constraints' )->item( 0 );
        if ( $constraints )
        {
            $allowedClassList = $constraints->getElementsByTagName( 'allowed-class' );
            foreach( $allowedClassList as $allowedClass )
            {
                $content['class_constraint_list'][] = $allowedClass->getAttribute( 'contentclass-identifier' );
            }
        }
        $type = $root->getElementsByTagName( 'type' )->item( 0 );
        if ( $type )
        {
            $content['type'] = $type->getAttribute( 'value' );
        }
        $selectionType = $root->getElementsByTagName( 'selection_type' )->item( 0 );
        if ( $selectionType )
        {
            $content['selection_type'] = $selectionType->getAttribute( 'value' );
        }
        $objectClass = $root->getElementsByTagName( 'object_class' )->item( 0 );
        if ( $objectClass )
        {
            $content['object_class'] = $objectClass->getAttribute( 'value' );
        }

        return $content;
    }

    function createObjectContentStructure( $doc )
    {
        $content = $this->defaultObjectAttributeContent();
        $root = $doc->documentElement;
        $relationList = $root->getElementsByTagName( 'relation-list' )->item( 0 );
        if ( $relationList )
        {
            $contentObjectArrayXMLMap = $this->contentObjectArrayXMLMap();
            $relationItems = $relationList->getElementsByTagName( 'relation-item' );
            foreach ( $relationItems as $relationItem )
            {
                $hash = array();

                foreach ( $contentObjectArrayXMLMap as $attributeXMLName => $attributeKey )
                {
                    $attributeValue = $relationItem->hasAttribute( $attributeXMLName ) ? $relationItem->getAttribute( $attributeXMLName ) : false;
                    $hash[$attributeKey] = $attributeValue;
                }
                $content['relation_list'][] = $hash;
            }
        }
        return $content;
    }

    function customClassAttributeHTTPAction( $http, $action, $classAttribute )
    {
        switch ( $action )
        {
            case 'browse_for_placement':
            {
                $module = $classAttribute->currentModule();
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
                $content = $classAttribute->content();
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
     Returns the meta data used for storing search indexes.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaDataArray = $attributes = array();
        $content = $contentObjectAttribute->content();
        foreach( $content['relation_list'] as $relationItem )
        {
            $subObjectID = $relationItem['contentobject_id'];
            if ( !$subObjectID )
                continue;

            if ( isset( $content['temp'] ) )
                $attributes = $content['temp'][$subObjectID]['attributes'];
            else
            {
                $subObjectVersion = $relationItem['contentobject_version'];
                $object = eZContentObject::fetch( $subObjectID );
                if ( eZContentObject::recursionProtect( $subObjectID ) )
                {
                    if ( !$object )
                    {
                        continue;
                    }
                    $attributes = $object->contentObjectAttributes( true, $subObjectVersion );
                }
            }

            $attributeMetaDataArray = eZContentObjectAttribute::metaDataArray( $attributes );
            $metaDataArray = array_merge( $metaDataArray, $attributeMetaDataArray );
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

    function fromString( $contentObjectAttribute, $string )
    {
        $objectIDList = explode( '-', $string );

        $content = $this->defaultObjectAttributeContent();
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

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        return count( $content['relation_list'] ) > 0;
    }

    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the content of the string for use as a title,
     for simplicity this is the name of the first object referenced or false.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        $objectAttributeContent = $this->objectAttributeContent( $contentObjectAttribute );

        if ( count( $objectAttributeContent['relation_list'] ) > 0 )
        {
            $target = $objectAttributeContent['relation_list'][0];
            $targetObject = eZContentObject::fetch( $target['contentobject_id'], false );
            return $targetObject['name'];
        }
        else
        {
            return false;
        }
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $dom = $attributeParametersNode->ownerDocument;
        $content = $classAttribute->content();
        if ( $content['default_placement'] )
        {
            $defaultPlacementNode = $dom->createElement( 'default-placement' );
            $defaultPlacementNode->setAttribute( 'node-id', $content['default_placement']['node_id'] );
            $attributeParametersNode->appendChild( $defaultPlacementNode );
        }

        $type = is_numeric( $content['type'] ) ? $content['type'] : '0';
        $typeNode = $dom->createElement( 'type' );
        $typeNode->appendChild( $dom->createTextNode( $type ) );
        $attributeParametersNode->appendChild( $typeNode );

        $classConstraintsNode = $dom->createElement( 'class-constraints' );
        $attributeParametersNode->appendChild( $classConstraintsNode );
        foreach ( $content['class_constraint_list'] as $classConstraint )
        {
            $classConstraintIdentifier = $classConstraint;
            $classConstraintNode = $dom->createElement( 'class-constraint' );
            $classConstraintNode->setAttribute( 'class-identifier', $classConstraintIdentifier );
            $classConstraintsNode->appendChild( $classConstraintNode );
        }

        if ( isset( $content['selection_type'] ) && is_numeric( $content['selection_type'] ) )
        {
            $selectionTypeNode = $dom->createElement( 'selection-type' );
            $selectionTypeNode->appendChild( $dom->createTextNode( $content['selection_type'] ) );
            $attributeParametersNode->appendChild( $selectionTypeNode );
        }

        if ( isset( $content['object_class'] ) && is_numeric( $content['object_class'] ) )
        {
            $objectClassNode = $dom->createElement( 'object-class' );
            $objectClassNode->appendChild( $dom->createTextNode( $content['object_class'] ) );
            $attributeParametersNode->appendChild( $objectClassNode );
        }
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $content = $classAttribute->content();
        $defaultPlacementNode = $attributeParametersNode->getElementsByTagName( 'default-placement' )->item( 0 );
        $content['default_placement'] = false;

        if ( $defaultPlacementNode )
        {
            $content['default_placement'] = array( 'node_id' => $defaultPlacementNode->getAttribute( 'node-id' ) );
        }
        $content['type'] = $attributeParametersNode->getElementsByTagName( 'type' )->item( 0 )->textContent;
        $classConstraintsNode = $attributeParametersNode->getElementsByTagName( 'class-constraints' )->item( 0 );
        $classConstraintList = $classConstraintsNode->getElementsByTagName( 'class-constraint' );
        $content['class_constraint_list'] = array();
        foreach ( $classConstraintList as $classConstraintNode )
        {
            $classIdentifier = $classConstraintNode->getAttribute( 'class-identifier' );
            $content['class_constraint_list'][] = $classIdentifier;
        }

        $objectClassNode = $attributeParametersNode->getElementsByTagName( 'object-class' )->item( 0 );
        if ( $objectClassNode )
        {
            $content['object_class'] = $objectClassNode->textContent;
        }

        $selectionTypeNode = $attributeParametersNode->getElementsByTagName( 'selection-type' )->item( 0 );
        if ( $selectionTypeNode )
        {
            $content['selection_type'] = $selectionTypeNode->textContent;
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
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        eZDebug::writeDebug( $objectAttribute->attribute( 'data_text' ), 'xml string from data_text field' );
        if ( $objectAttribute->attribute( 'data_text' ) === null )
        {
            $content = array( 'relation_list' => array() );
            $dom = $this->createObjectDOMDocument( $content );
        }
        else
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $objectAttribute->attribute( 'data_text' ) );
        }
        $rootNode = $dom->documentElement;
        $relationList = $rootNode->getElementsByTagName( 'relation-list' )->item( 0 );
        if ( $relationList )
        {
            $relationItems = $relationList->getElementsByTagName( 'relation-item' );
            for ( $i = 0; $i < $relationItems->length; $i++ )
            {
                $relationItem = $relationItems->item( $i );
                // Add related object remote id as attribute to the relation item.
                $relatedObjectID = $relationItem->getAttribute( 'contentobject-id' );
                $relatedObject = eZContentObject::fetch( $relatedObjectID );
                $relatedObjectRemoteID = $relatedObject->attribute( 'remote_id' );
                $relationItem->setAttribute( 'contentobject-remote-id', $relatedObjectRemoteID );

                $attributes = $relationItem->attributes;
                // Remove all other relation item attributes except of "priority".
                // This loop intentionally starts with the last attribute, otherwise you will get unexpected results
                for ( $j = $attributes->length - 1; $j >= 0; $j-- )
                {
                    $attribute = $attributes->item( $j );
                    $attrName = $attribute->localName;

                    eZDebug::writeDebug( $attrName );
                    if ( $attrName != 'priority' && $attrName != 'contentobject-remote-id' )
                    {
                        $success = $relationItem->removeAttribute( $attribute->localName );
                        if ( !$success )
                        {
                            eZDebug::writeError( 'failed removing attribute ' . $attrName . ' from relation-item element' );
                        }
                    }
                }
            }
        }

        eZDebug::writeDebug( $dom->saveXML(), 'old xml doc' );

        $importedRootNode = $node->ownerDocument->importNode( $rootNode, true );
        $node->appendChild( $importedRootNode );

        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $rootNode = $attributeNode->getElementsByTagName( 'related-objects' )->item( 0 );
        $xmlString = $rootNode ? $rootNode->ownerDocument->saveXML( $rootNode ) : '';
        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }

    function postUnserializeContentObjectAttribute( $package, $objectAttribute )
    {
        $xmlString = $objectAttribute->attribute( 'data_text' );
        $doc = $this->parseXML( $xmlString );
        $rootNode = $doc->documentElement;

        $relationList = $rootNode->getElementsByTagName( 'relation-list' )->item( 0 );
        if ( !$relationList )
            return false;

        $relationItems = $relationList->getElementsByTagName( 'relation-item' );
        for ( $i = $relationItems->length - 1; $i >= 0; $i-- )
        {
            $relationItem = $relationItems->item( $i );
            $relatedObjectRemoteID = $relationItem->getAttribute( 'contentobject-remote-id' );
            $object = eZContentObject::fetchByRemoteID( $relatedObjectRemoteID );

            if ( $object === null )
            {
                eZDebug::writeWarning( "Object with remote id '$relatedObjectRemoteID' not found: removing the link.", __METHOD__ );
                $relationItem->parentNode->removeChild( $relationItem );
                continue;
            }

            $relationItem->setAttribute( 'contentobject-id',        $object->attribute( 'id' ) );
            $relationItem->setAttribute( 'contentobject-version',   $object->attribute( 'current_version' ) );
            $relationItem->setAttribute( 'node-id',                 $object->attribute( 'main_node_id' ) );
            $relationItem->setAttribute( 'parent-node-id',          $object->attribute( 'main_parent_node_id' ) );
            $relationItem->setAttribute( 'contentclass-id',         $object->attribute( 'contentclass_id' ) );
            $relationItem->setAttribute( 'contentclass-identifier', $object->attribute( 'class_identifier' ) );
        }

        $newXmlString = $doc->saveXML( $rootNode );

        $objectAttribute->setAttribute( 'data_text', $newXmlString );
        return true;
    }

    /*!
     Removes objects with given ID from the relations list
    */
    function removeRelatedObjectItem( $contentObjectAttribute, $objectID )
    {
        $xmlText = $contentObjectAttribute->attribute( 'data_text' );
        if ( trim( $xmlText ) == '' ) return;

        $doc = $this->parseXML( $xmlText );

        $return = false;
        $root = $doc->documentElement;
        $relationList = $root->getElementsByTagName( 'relation-list' )->item( 0 );
        if ( $relationList )
        {
            $relationItems = $relationList->getElementsByTagName( 'relation-item' );
            if ( !empty( $relationItems ) )
            {
                foreach( $relationItems as $relationItem )
                {
                    if ( $relationItem->getAttribute( 'contentobject-id' ) == $objectID )
                    {
                        $relationList->removeChild( $relationItem );
                        $return = true;
                    }
                }
            }
        }
        $this->storeObjectDOMDocument( $doc, $contentObjectAttribute );
        return $return;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    /// \privatesection
}

eZDataType::register( eZObjectRelationListType::DATA_TYPE_STRING, "eZObjectRelationListType" );

?>