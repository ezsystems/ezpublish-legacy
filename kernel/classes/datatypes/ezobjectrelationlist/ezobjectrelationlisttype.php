<?php
//
// Definition of eZObjectRelationListType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*!
  \class eZObjectRelationListType ezobjectrelationlisttype.php
  \ingroup eZKernel
  \brief A content datatype which handles object relations

Bugs/missing features:
- Editing objects only uses current version not version specified in relation list
- No identifier support yet
- No node support yet
- No browse for nodes/objects support yet
- Removal should check if it is published by looking at main_node_id in object not relation item
- Validation and fixup for "Add new object" functionality
- Proper embed views for admin classes
- Custom action support, test with option for instance
- No translation page support yet (maybe?)
- Not all datatypes uses the new $attribute_base template variable, they must do so to work properly
- Multiple classes in one list is not supported, although the template indicate the opposite

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );
include_once( "lib/ezutils/classes/ezinputvalidator.php" );
include_once( "lib/ezi18n/classes/eztranslatormanager.php" );

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
//         $serializedContentName = $base . '_serialized_text';
//         if ( $http->hasPostVariable( $serializedContentName ) )
//         {
//             $serializedContentMap = $http->postVariable( $serializedContentName );
//             $content = unserialize( $serializedContentMap[$contentObjectAttribute->attribute( 'id' )] );
//         }
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
//                 $object =& eZContentObject::fetch( $subObjectID );
                $object =& $content['temp'][$subObjectID]['object'];
                $requireFixup = $content['temp'][$subObjectID]['require-fixup'];
                if ( $object and
                     $requireFixup )
                {
//                     $attributes =& $object->contentObjectAttributes();
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
//         print( "<pre>" );
//         var_dump( $content['relation_list'] );
//         print( "</pre>" );
        for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
        {
            $relationItem = $content['relation_list'][$i];
            if ( $relationItem['is_modified'] )
            {
                $subObjectID = $relationItem['contentobject_id'];
                $subObjectVersion = $relationItem['contentobject_version'];
                $attributeBase = $base . '_ezorl_edit_object_' . $subObjectID;
//                 $object =& eZContentObject::fetch( $subObjectID );
                $object =& $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
//                     $attributes =& $object->contentObjectAttributes();
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
//         print( "<pre>#2" );
//         var_dump( $content['relation_list'] );
//         print( "</pre>" );
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
//                 $object =& eZContentObject::fetch( $subObjectID );
                $object =& $content['temp'][$subObjectID]['object'];
                if ( $object )
                {
                    $attributes =& $content['temp'][$subObjectID]['attributes'];
//                     $attributes =& $object->contentObjectAttributes();
                    $attributeInputMap =& $content['temp'][$subObjectID]['attribute-input-map'];
                    $object->storeInput( $attributes,
                                         $attributeInputMap );
                    $version =& eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    include_once( 'lib/ezlocale/classes/ezdatetime.php' );
                    $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
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
                    $version =& eZContentObjectVersion::fetchVersion( $subObjectVersion, $subObjectID );
                    include_once( 'lib/ezlocale/classes/ezdatetime.php' );
                    $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
                    $version->setAttribute( 'status', EZ_VERSION_STATUS_PUBLISHED );
                    $version->store();
                    $object->setAttribute( 'status', EZ_CONTENT_OBJECT_STATUS_PUBLISHED );
                    if ( !$object->attribute( 'published' ) )
                        $object->setAttribute( 'published', eZDateTime::currentTimeStamp() );
                    $object->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
                    $object->setAttribute( 'current_version', $version->attribute( 'version' ) );
                    $object->setAttribute( 'is_published', true );
                    $objectName = $class->contentObjectName( $object, $version->attribute( 'version' ) );
                    $object->setName( $objectName, $version->attribute( 'version' ) );
                    $object->store();
                }
                if ( $relationItem['node_id'] > 0 )
                {
                    $nodeAssignment =& eZNodeAssignment::create( array( 'contentobject_id' => $object->attribute( 'id' ),
                                                                        'contentobject_version' => $object->attribute( 'current_version' ),
                                                                        'parent_node' => $relationItem['node_id'],
                                                                        'sort_field' => 2,
                                                                        'sort_order' => 0,
                                                                        'is_main' => 1 ) );
                    $nodeAssignment->store();
                    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ),
                                                                                                 'version' => $object->attribute( 'current_version' ) ) );
                }

                $content['relation_list'][$i]['is_modified'] = false;
            }
        }
//         print( "<pre>" );
//         var_dump( $content );
//         print( "</pre>" );
        eZObjectRelationListType::storeObjectAttributeContent( $contentObjectAttribute, $content );
        $contentObjectAttribute->setContent( $content );
        $contentObjectAttribute->store();
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
            $content['attribute_type'] = $type;
        }
        $classAttribute->setContent( $content );
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
//         print( "<pre>" . htmlspecialchars( $docText ) . "</pre>" );
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
        $constraintType =& $doc->createElementNode( 'type', array( 'value' => $content['attribute_type'] ) );
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
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute, $parameters )
    {
//         print( "Custom action $action<br/>" );
        if ( eZDataType::fetchActionValue( $action, 'new_class', $classID ) )
        {
//             print( "Adding class $classID<br/>" );
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
                $relationItem = eZObjectRelationListType::createInstance( $class,
                                                                          $priority + 1,
                                                                          $contentObjectAttribute );
                if ( $class_content['default_placement'] )
                {
                    $relationItem['node_id'] = $class_content['default_placement']['node_id'];
                }

                $content['relation_list'][] = $relationItem;
                $object =& $relationItem['object'];
                $attributes =& $object->contentObjectAttributes();
                $base = $parameters['base_name'];
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
                $contentObjectAttribute->setContent( $content );
                $contentObjectAttribute->store();
            }
            else
                eZDebug::writeError( "Unknown class ID $classID, cannot instantiate object",
                                     'eZObjectRelationListType::customObjectAttributeHTTPAction' );
        }
        else if ( $action == 'edit_objects' or
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
            if ( $action == 'edit_objects' )
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
                        if ( !$relationItem['node_id'] )
                        {
                            $object =& eZContentObject::fetch( $relationItem['contentobject_id'] );
                            $object->purge();
                        }
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
                if ( $classAttributeID == $contentObjectAttribute->attribute( 'contentclassattribute_id' ) && strlen( $type ) > 0 )
                {
                    $browseType = $type;
                    eZDebug::writeDebug( $browseType, "browseType" );
                    break;
                }
            }
            eZContentBrowse::browse( array( 'action_name' => 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ),
                                            'type' =>  $browseType,
                                            'browse_custom_action' => array( 'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) . '_set_object_relation_list]',
                                                                             'value' => $contentObjectAttribute->attribute( 'id' ) ),
                                            'persistent_data' => array( 'HasObjectInput' => 0 ),
                                            'from_page' => $redirectionURI ),
                                     $module );
        }
        else if ( $action == 'set_object_relation_list' )
        {
            $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
            $objectID = $selectedObjectIDArray[0];
            $content = $contentObjectAttribute->content();
            $priority = 0;
            for ( $i = 0; $i < count( $content['relation_list'] ); ++$i )
            {
                if ( $content['relation_list'][$i]['priority'] > $priority )
                    $priority = $content['relation_list'][$i]['priority'];
            }
            $content['relation_list'][] =& $this->appendObject( $objectID, $priority + 1, $contentObjectAttribute );
            $contentObjectAttribute->setContent( $content );
            $contentObjectAttribute->store();
        }
        else
        {
            eZDebug::writeError( "Unknown custom HTTP action: " . $action,
                                 'eZObjectRelationListType' );
        }
    }

    function createInstance( &$class, $priority, &$contentObjectAttribute )
    {
        $currentObject =& $contentObjectAttribute->attribute( 'object' );
        $sectionID = $currentObject->attribute( 'section_id' );
        $object =& $class->instantiate( false, $sectionID );
        $object->sync();
        $relationItem = array( 'identifier' => false,
                               'priority' => $priority,
                               'contentobject_id' => $object->attribute( 'id' ),
                               'contentobject_version' => $object->attribute( 'current_version' ),
                               'node_id' => false,
                               'parent_node_id' => false,
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
//         $serializedText = serialize( $content );
//         $content['serialized_text'] = $serializedText;
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
        return false;
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
//         $objectID = $this->objectAttributeContent( $contentObjectAttribute );
//         if ( $objectID !== false )
//         {
//             $object =& eZContentObject::fetch( $objectID );
//             if ( $object )
//                 return $object->attribute( 'name' );
//         }
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
