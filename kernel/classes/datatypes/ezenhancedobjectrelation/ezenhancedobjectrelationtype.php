<?php
//
// Definition of eZEnhancedObjectRelationType class
//
// Created on: <24-Sept-2004 xavier dutoit>
// Modified by Gabriel Ambuehl to allow creation of new objects
// Modified by Gabriel Ambuehl so it's searchable now...
// Modified by Xavier Dutoit : added you can choose the selectable
// related objects by class (previously only by parent node)
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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

/*!
  \class eZEnhancedObjectRelationType ezenhancedobjectrelationtype.php
  \ingroup eZKernel
  \brief A content datatype which handles object relations
*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );
include_once( "lib/ezi18n/classes/eztranslatormanager.php" );

define( "EZ_DATATYPESTRING_ENHANCED_OBJECT_RELATION", "ezenhancedobjectrelation" );

class eZEnhancedObjectRelationType extends eZDataType
{
    /*!
     Initializes with a string id and a description.
    */
    function eZEnhancedObjectRelationType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_ENHANCED_OBJECT_RELATION, ezi18n( 'kernel/classes/datatypes', "Enhanced Object relation", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();

        $postVariableName = $base . "_data_object_relation_id_list_" . $contentObjectAttribute->attribute( "id" );
        if ( $contentObjectAttribute->validateIsRequired() and ( !$http->hasPostVariable( $postVariableName ) or count( $http->postVariable( $postVariableName ) ) == 0 ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Missing enhancedobjectrelation input.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
        // new object creation
        $newObjectPostVariableName = "attribute_" . $contentObjectAttribute->attribute( "id" ) . "_new_object_name";
        if ( $http->hasPostVariable( $newObjectPostVariableName ) )
        {
            $name = $http->postVariable( $newObjectPostVariableName );
            if ( !empty( $name ) )
            {
                $contentObjectAttribute->Content['new_object'] = $name;
            }
        }
        $singleSelectPostVariableName = "single_select_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $singleSelectPostVariableName ) )
            $contentObjectAttribute->Content['singleselect'] = true;

        // old starts here
        $contentObjectAttribute->Content['id_list'] = array();
        $idlist = array();
        $postVariableName = $base . "_data_object_relation_id_list_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $idlist = $http->postVariable( $postVariableName );
            foreach ( $idlist as $objectID )
                $contentObjectAttribute->Content['id_list'][] = $objectID;
        }

        return true;
    }

    /*!
     \reimp
    */
    function storeObjectAttribute( &$contentObjectAttribute )
    {
        if ( isset( $contentObjectAttribute->Content['new_object'] ) )
        {
            $newID = $this->createNewObject( $contentObjectAttribute, $contentObjectAttribute->Content['new_object'] );
            // if this is a single element selection mode (radio or dropdown), then the newly created item is the only one selected
            if ( $newID )
            {
                if ( isset( $contentObjectAttribute->Content['singleselect'] ) )
                    $contentObjectAttribute->Content['id_list'] = array();
                $contentObjectAttribute->Content['id_list'][] = $newID;
            }
        }
		
        $contentClassAttributeID = $contentObjectAttribute->ContentClassAttributeID;
        $contentObjectID = $contentObjectAttribute->ContentObjectID;
        $contentObjectVersion = $contentObjectAttribute->Version;

        $this->removeAllContentObjectRelation( $contentObjectID, $contentObjectVersion, $contentClassAttributeID );

        if ( empty ($contentObjectAttribute->Content['id_list'] ) )
            return true;

        foreach ( $contentObjectAttribute->Content['id_list'] as $objectID )
        {
            $this->addContentObjectRelation( $contentObjectID, $contentObjectVersion, $contentClassAttributeID, $objectID );
        }

        return true;
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $selectionTypeName = 'ContentClass_ezobjectrelation_selection_type_' . $classAttribute->attribute( 'id' );
        $state = EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        if ( $http->hasPostVariable( $selectionTypeName ) )
        {
            $selectionType = $http->postVariable( $selectionTypeName );
            if ( $selectionType < 0 or
                 $selectionType > 4 )
            {
                $state = EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        return $state;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function fixupObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $selectionTypeName = 'ContentClass_ezobjectrelation_selection_type_' . $classAttribute->attribute( 'id' );
        $content = $classAttribute->content();
        $hasData = false;
        if ( $http->hasPostVariable( $selectionTypeName ) )
        {
            $selectionType = $http->postVariable( $selectionTypeName );
            $content['selection_type'] = $selectionType;
            $hasData = true;
        }
        $filterClassName = 'ContentClass_ezobjectrelation_filter_class_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $filterClassName ) )
        {
            $filterClass = $http->postVariable( $filterClassName );
            $content['filter_class'] = $filterClass;
            $hasData = true;
        }

      	$placementNodeName = 'ContentClass_ezobjectrelationlist_placement_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( 'PlacementNodeName' ) )
        {
            $defaultPlacementNode = $http->postVariable( $placementNodeName );
            $content['default_placement_node'] = $defaultPlacementNode;
            $hasData = true;
        }
	
        $helperName = 'ContentClass_ezenhancedobjectrelation_selection_fuzzy_match_helper_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $helperName ) )
        {
            $fuzzyMatchName = 'ContentClass_ezenhancedobjectrelation_selection_fuzzy_match_' . $classAttribute->attribute( 'id' );
            $content['fuzzy_match'] = false;
            $hasData = true;
            if ( $http->hasPostVariable( $fuzzyMatchName ) )
            {
                $content['fuzzy_match'] = true;
            }
        }
        if ( $hasData )
        {
            $classAttribute->setContent( $content );
            return true;
        }

        return false;
    }

    function preStoreClassAttribute( &$classAttribute, $version )
    {
        $content = $classAttribute->content();
        $classAttribute->setAttribute( 'data_int1', $content['selection_type'] );
        $classAttribute->setAttribute( 'data_int2', $content['default_selection_node'] );
        $classAttribute->setAttribute( 'data_int3', $content['filter_class'] );
        $classAttribute->setAttribute( 'data_int4', $content['default_placement_node'] );
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute, $parameters )
    {
        switch ( $action )
        {
            case "set_object_relation" :
            {
                if ( $http->hasPostVariable( 'RemoveObjectButton_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $contentObjectAttribute->setAttribute( 'data_int', 0 );
                    $contentObjectAttribute->store();
                }

                if ( $http->hasPostVariable( 'BrowseObjectButton_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $module =& $parameters['module'];
                    $redirectionURI = $parameters['current-redirection-uri'];
                    $ini = eZINI::instance( 'content.ini' );
                    $browseType = 'AddRelatedObjectToDataType';
                    $browseTypeINIVariable = $ini->hasVariable( 'EnhancedObjectRelationDataTypeSettings', 'ClassAttributeStartNode' )
                                             ? $ini->variable( 'EnhancedObjectRelationDataTypeSettings', 'ClassAttributeStartNode' )
                                             : array();
                    foreach( $browseTypeINIVariable as $value )
                    {
                        list( $classAttributeID, $type ) = explode( ';', $value );
                        if ( $classAttributeID == $contentObjectAttribute->attribute( 'contentclassattribute_id' ) && strlen( $type ) > 0 )
                        {
                            $browseType = $type;
                            break;
                        }
                    }
                    eZContentBrowse::browse( array( 'action_name' => 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ),
                                                    'type' =>  $browseType,
                                                    'browse_custom_action' => array( 'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) . '_set_object_relation]',
                                                                                     'value' => $contentObjectAttribute->attribute( 'id' ) ),
                                                    'persistent_data' => array( 'HasObjectInput' => 0 ),
                                                    'from_page' => $redirectionURI ),
                                             $module );

                }
                else if ( $http->hasPostVariable( 'BrowseActionName' ) and
                          $http->postVariable( 'BrowseActionName' ) == ( 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) ) and
                          $http->hasPostVariable( "SelectedObjectIDArray" ) )
                {
                    $selectedObjectArray = $http->hasPostVariable( "SelectedObjectIDArray" );
                    $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );

                    $objectID = $selectedObjectIDArray[0];
                    $contentObjectAttribute->setAttribute( 'data_int', $objectID );
                    $contentObjectAttribute->store();
                    $http->removeSessionVariable( 'BrowseCustomAction' );
                }
            } break;
            case "new_object" :
            {
                $objectAttributeContent['addnew'] = true;
                if ( !array_key_exists( 'id_list', $objectAttributeContent ) )
                  $objectAttributeContent['id_list'] = array();
				
                $contentObjectAttribute->setContent( $objectAttributeContent );
            }
            default :
            {
                eZDebug::writeNotice( "Unknown custom HTTP action: " . $action, "eZEnhancedObjectRelationType" );
            } break;
        }
    }

    function createNewObject( &$contentObjectAttribute, $name )
    {
        $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $classContent = $classAttribute->content();
        $classID = $classContent['filter_class'];
        $defaultPlacementNode = $classContent['default_placement_node'];
        $node =& eZContentObjectTreeNode::fetch( $defaultPlacementNode );
        // Check if current user can create a new node as child of this node.
        if ( !$node or !$node->canCreate() )
            return false;

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
            return false;

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
      Adds a link to the given content object id.
      it's taken from ezcontentobject and should belong to that class.
      I don't want to make a link to a specific version of an object
    */
    function addContentObjectRelation( $fromObjectID, $fromObjectVersion, $contentClassAttributeID, $toObjectID )
    {
        $db =& eZDB::instance();
        //prevent sql injection
        $fromObjectID =(int) $fromObjectID;
        $contentClassAttributeID =(int) $contentClassAttributeID;
        $fromObjectVersion =(int) $fromObjectVersion;
        $toObjectID =(int) $toObjectID;
        $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version,  contentclassattribute_id, to_contentobject_id )		VALUES ( '$fromObjectID', '$fromObjectVersion', '$contentClassAttributeID', '$toObjectID' )" );
    }

    function removeAllContentObjectRelation( $fromObjectID, $contentObjectVersion, $contentClassAttributeID )
    {
        $db =& eZDB::instance();
        //to prevent sql injection
        $fromObjectID =(int) $fromObjectID;
        $contentClassAttributeID =(int) $contentClassAttributeID;		
        $contentObjectVersion =(int) $contentObjectVersion;
        $db->query( "DELETE FROM ezcontentobject_link WHERE from_contentobject_id='$fromObjectID' AND from_contentobject_version='$contentObjectVersion' AND  contentclassattribute_id='$contentClassAttributeID'" );
    }

    /*!
      \private
      Create the filter
    */
    function filterClassAttribute( $contentClassAttributeID )
    {
        if ( $contentClassAttributeID )
            return " AND contentclassattribute_id = '$contentClassAttributeID'";
        return '';
    }

    /*!
      Get the related objects' IDs
    */
    function relatedContentObjectIDArray( $objectID, $version, $contentClassAttributeID = null )
    {
        $db =& eZDB::instance();

        // protect from SQL injection
        $objectID = (int ) $objectID;
        $version =(int) $version;
        if ( $contentClassAttributeID != null )
        {
            $contentClassAttributeID=(int) $contentClassAttributeID;
        }

        $classAttributeFilter = $this->filterClassAttribute( $contentClassAttributeID );
        $relatedObjects = $db->arrayQuery( "SELECT to_contentobject_id as id
                                            FROM   ezcontentobject_link
                                            WHERE  ezcontentobject_link.from_contentobject_id='$objectID' AND
                                                   ezcontentobject_link.from_contentobject_version='$version' $classAttributeFilter" );
        if ( $relatedObjects === false )
        {
            $empty = array();
            return $empty;
        }

        return $relatedObjects;
    }

    /*!
      Initializes the object attribute with some data.
    */
    function initializeObjectAttribute( &$objectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
    	// skip if this is a new content object, THREE === IS VITAL DON'T CHANGE!!!
    	if ( $originalContentObjectAttribute !== null )
    	{	

            $contentObjectID =(int) $originalContentObjectAttribute->ContentObjectID;
            $originalVersion =(int) $originalContentObjectAttribute->Version;
            //$contentClassAttributeID = (int) $originalContentObjectAttribute->ContentClassAttributeID;

            $newVersion = (int) $objectAttribute->Version;

            // fetch original relations
            $db =& eZDB::instance();
            $oldRelations = $db->arrayQuery( "SELECT * FROM ezcontentobject_link WHERE from_contentobject_id='$contentObjectID' AND from_contentobject_version='$originalVersion'" );

            // remove relations
            $db->query( "DELETE FROM ezcontentobject_link WHERE from_contentobject_id='$contentObjectID' AND from_contentobject_version='$newVersion'" );
            //re-add, corrected.

            foreach ( $oldRelations as $relation )
            {
                $from_contentobject_id = $relation['from_contentobject_id'];
                $to_contentobject_id = $relation['to_contentobject_id'];
                $contentclassattribute_id = $relation['contentclassattribute_id'];
                $this->addContentObjectRelation( $from_contentobject_id, $newVersion, $contentclassattribute_id, $to_contentobject_id );
            }
    	}
    }
	
	
    /*!
     Returns the content.
     For some reasons, its called 2 times when I edit an object
     TODO: find someone able to optimise it.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $relatedObjects = $this->relatedContentObjectIDArray( $contentObjectAttribute->ContentObjectID, $contentObjectAttribute->Version,  $contentObjectAttribute->ContentClassAttributeID );
        $attributes = array();
        $attributes['id_list'] = array();

        foreach ( $relatedObjects as $relatedObject )
        {
            $attributes['id_list'][] = $relatedObject['id'];
        }

        return $attributes;
    }

    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function &sortKeyType()
    {
        return 'int';
    }

    function &classAttributeContent( &$classObjectAttribute )
    {
        $selectionType = $classObjectAttribute->attribute( "data_int1" );
        $defaultSelectionNode = $classObjectAttribute->attribute( "data_int2" );
        $classFilter = $classObjectAttribute->attribute( "data_int3" );
        $defaultPlacementNode = $classObjectAttribute->attribute( "data_int4" );
		
        $content = array( 'selection_type' => $selectionType,
                          'default_selection_node' => $defaultSelectionNode,
                          'filter_class' => $classFilter,
                          'default_placement_node' => $defaultPlacementNode );
        return $content;
    }

    function customClassAttributeHTTPAction( &$http, $action, &$classAttribute )
    {
        switch ( $action )
        {
            case 'browse_for_selection_node':
            {
                $module =& $classAttribute->currentModule();
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $customActionName = 'CustomActionButton[' . $classAttribute->attribute( 'id' ) . '_browsed_for_selection_node]';
                eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                                'content' => array( 'contentclass_id' => $classAttribute->attribute( 'contentclass_id' ),
                                                                    'contentclass_attribute_id' => $classAttribute->attribute( 'id' ),
                                                                    'contentclass_version' => $classAttribute->attribute( 'version' ),
                                                                    'contentclass_attribute_identifier' => $classAttribute->attribute( 'identifier' ) ),
                                                'persistent_data' => array( $customActionName => '',
                                                                            'ContentClassHasInput' => false ),
                                                'from_page' => $module->currentRedirectionURI() ),
                                         $module );
            } break;
            case 'browsed_for_selection_node':
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeSelection = eZContentBrowse::result( 'SelectObjectRelationNode' );
                if ( count( $nodeSelection ) > 0 )
                {
                    $nodeID = $nodeSelection[0];
                    $content = $classAttribute->content();
                    $content['default_selection_node'] = $nodeID;
                    $classAttribute->setContent( $content );
                }

            } break;
            case 'browse_for_placement_node':
            {

                $module =& $classAttribute->currentModule();
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $customActionName = 'CustomActionButton[' . $classAttribute->attribute( 'id' ) . '_browsed_for_placement_node]';
		
                eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                                'content' => array( 'contentclass_id' => $classAttribute->attribute( 'contentclass_id' ),
                                                                    'contentclass_attribute_id' => $classAttribute->attribute( 'id' ),
                                                                    'contentclass_version' => $classAttribute->attribute( 'version' ),
                                                                    'contentclass_attribute_identifier' => $classAttribute->attribute( 'identifier' ) ),
                                                'persistent_data' => array( $customActionName => '',
                                                                            'ContentClassHasInput' => false ),
                                                'from_page' => $module->currentRedirectionURI() ),
                                         $module );
            } break;
            case 'browsed_for_placement_node':
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeSelection = eZContentBrowse::result( 'SelectObjectRelationNode' );
                if ( count( $nodeSelection ) > 0 )
                {
                    $nodeID = $nodeSelection[0];
                    $content = $classAttribute->content();
                    $content['default_placement_node'] = $nodeID;
                    $classAttribute->setContent( $content );
                }	
            } break;
            case 'disable_selection_node':
            {
                $content =& $classAttribute->content();
                $content['default_selection_node'] = false;
                $classAttribute->setContent( $content );
            } break;
            case 'remove_placement_node':
            {
                $content =& $classAttribute->content();
                $content['default_placement_node'] = false;
                $classAttribute->setContent( $content );
            } break;
            default:
            {
                eZDebug::writeError( "Unknown enhancedobjectrelationlist action '$action'", 'eZContentEnhancedObjectRelationListType::customClassAttributeHTTPAction' );
            } break;
        }
    }

    /*!
    Returns the meta data used for storing search indices.
    Limitations:
       no matter what language the user has set, it searches for all of them. this is an ezpublish wide problem, though
   */
   function metaData( $contentObjectAttribute )
   {
       $metaDataArray = array();
       $relatedObjects = $this->fetchRelatedContentObjects( $contentObjectAttribute );

       foreach ( $relatedObjects as $relatedObject )
       {
           //multilingual indexing
           $translationList = $relatedObject->translationStringList();
           foreach ( $translationList as $translation )
           {
               $relatedObject->setCurrentLanguage( $translation );
               $attributes=$relatedObject->contentObjectAttributes();
               $metaData=eZContentObjectAttribute::metaDataArray( $attributes );
               $metaDataArray=array_merge( $metaDataArray, $metaData );
           }
       }

       return $metaDataArray;
   }

   /*!.
     see metaData for more.
    */
   function isIndexable()
   {
       return true;
   }

   /*
    Custom function, fetch related content objects
   */
   function fetchRelatedContentObjects( &$contentObjectAttribute )
   {
       $object = $this->objectAttributeContent( $contentObjectAttribute );
       // object now contains a hash with an array inside id_list
       if ( !$object )
           return false;

       $contentobjects = array();
       foreach( $object['id_list'] as $id )
       {
           $contentobject = eZContentObject::fetch( $id );
           if ( $contentobject )
               array_push( $contentobjects, $contentobject );
       }

       return $contentobjects;
   }

    /*!
     Returns the content of the string for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        $relations = $this->objectAttributeContent( $contentObjectAttribute );
        if ( is_array ( $relations ) && isset( $relations['id_list'] ) && is_array( $relations['id_list'] ) && ( count( $relations['id_list'] ) > 0 ) )
        {
            $object =& eZContentObject::fetch( $relations['id_list'][0] );
            return $object->attribute( 'name' );
        }

        return false;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( !$object )
            return false;

        return ( !empty( $object['id_list'] ) && ( $object['id_list'][0] != 0 ) );
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'selection-type',
                                                                                 array( 'id' => $content['selection_type'] ) ) );
        if ( $content['default_selection_node'] )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-selection',
                                                                                     array( 'node-id' => $content['default_selection_node'] ) ) );

        if ( $content['filter_class'] )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'filter_class',
                                                                                     array( 'class-id' => $content['filter_class'] ) ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        $selectionTypeNode = $attributeParametersNode->elementByName( 'selection-type' );
        $content['selection_type'] = 0;
        if ( $selectionTypeNode )
            $content['selection_type'] = $selectionTypeNode->attributeValue( 'id' );
        $filterClass = $attributeParametersNode->elementByName( 'filter_class' );
        if ( $filterClass )
            $content['filter_class'] = $filterClass->attributeValue( 'id' );

        $defaultSelectionNode = $attributeParametersNode->elementByName( 'default-selection' );
        $content['default_selection_node'] = false;
        if ( $defaultSelectionNode )
            $content['default_selection_node'] = $defaultSelectionNode->attributeValue( 'node-id' );

        $classAttribute->setContent( $content );
        $classAttribute->store();
    }

    /// \privatesection
}

eZDataType::register( EZ_DATATYPESTRING_ENHANCED_OBJECT_RELATION, "ezenhancedobjectrelationtype" );

?>
