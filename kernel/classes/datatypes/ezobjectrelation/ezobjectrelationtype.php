<?php
//
// Definition of eZObjectRelationType class
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
  \class eZObjectRelationType ezobjectrelationtype.php
  \ingroup eZKernel
  \brief A content datatype which handles object relations

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );
include_once( "lib/ezi18n/classes/eztranslatormanager.php" );

define( "EZ_DATATYPESTRING_OBJECT_RELATION", "ezobjectrelation" );

class eZObjectRelationType extends eZDataType
{
    /*!
     Initializes with a string id and a description.
    */
    function eZObjectRelationType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_OBJECT_RELATION, ezi18n( 'kernel/classes/datatypes', "Object relation", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $postVariableName = $base . "_data_object_relation_id_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $relatedObjectID =& $http->postVariable( $postVariableName );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( $classAttribute->attribute( "is_required" ) and $relatedObjectID == 0 )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Missing objectrelation input.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $postVariableName = $base . "_data_object_relation_id_" . $contentObjectAttribute->attribute( "id" );
        $haveData = false;
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $relatedObjectID =& $http->postVariable( $postVariableName );
            $contentObjectAttribute->setAttribute( 'data_int', $relatedObjectID );
            $haveData = true;
        }
        $fuzzyMatchVariableName = $base . "_data_object_relation_fuzzy_match_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $fuzzyMatchVariableName ) )
        {
            $fuzzyMatchText = trim( $http->postVariable( $fuzzyMatchVariableName ) );
            if ( $fuzzyMatchText != '' )
            {
                $fuzzyMatchText = strtolower( $fuzzyMatchText );
                $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
                if ( $classAttribute )
                {
                    $classContent =& $classAttribute->content();
                    if ( $classContent['default_selection_node'] )
                    {
                        $nodeID = $classContent['default_selection_node'];
                        $nodeList =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1 ), $nodeID );
                        $lastDiff = false;
                        $matchObjectID = false;
                        foreach ( $nodeList as $node )
                        {
                            // This should be replaced with i18n enabled char transformation.
                            $name = strtolower( trim( $node->attribute( 'name' ) ) );
                            $diff = $this->fuzzyTextMatch( $name, $fuzzyMatchText );
                            if ( $diff === false )
                                continue;
                            if ( $diff == 0 )
                            {
                                $matchObjectID = $node->attribute( 'contentobject_id' );
                                break;
                            }
                            if ( $lastDiff === false or
                                 $diff < $lastDiff )
                            {
                                $lastDiff = $diff;
                                $matchObjectID = $node->attribute( 'contentobject_id' );
                            }
                        }
                        if ( $matchObjectID !== false )
                        {
                            $contentObjectAttribute->setAttribute( 'data_int', $matchObjectID );
                            $haveData = true;
                        }
                    }
                }
            }
        }
        return $haveData;
    }

    /*!
     \private
     \return a number of how near \a $match is to \a $text, the lower the better and 0 is a perfect match.
     \return \c false if it does not match
    */
    function fuzzyTextMatch( $text, $match )
    {
        $pos = strpos( $text, $match );
        if ( $pos !== false )
        {
            $diff = strlen( $text ) - ( strlen( $match ) + $pos );
            $diff += $pos;
            return $diff;
        }
        return false;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$attribute )
    {

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
            if ( $selectionType < 0 and
                 $selectionType > 2 )
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
        $helperName = 'ContentClass_ezobjectrelation_selection_fuzzy_match_helper_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $helperName ) )
        {
            $fuzzyMatchName = 'ContentClass_ezobjectrelation_selection_fuzzy_match_' . $classAttribute->attribute( 'id' );
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
        $classAttribute->setAttribute( 'data_int3', $content['fuzzy_match'] );
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
                    $browseTypeINIVariable = $ini->variable( 'ObjectRelationDataTypeSettings', 'ClassAttributeStartNode' );
                    foreach( $browseTypeINIVariable as $value )
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
//                     $contentObjectAttribute->setContent( $objectID );
                    $contentObjectAttribute->setAttribute( 'data_int', $objectID );
                    $contentObjectAttribute->store();
                    $http->removeSessionVariable( 'BrowseCustomAction' );
                }
            } break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZObjectRelationType" );
            } break;
        }
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $objectID = $contentObjectAttribute->attribute( "data_int" );
        if ( $objectID != 0 )
            return eZContentObject::fetch( $contentObjectAttribute->attribute( "data_int" ) );
        else
            return false;
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
        $fuzzyMatch = $classObjectAttribute->attribute( "data_int3" );
        $content = array( 'selection_type' => $selectionType,
                          'default_selection_node' => $defaultSelectionNode,
                          'fuzzy_match' => $fuzzyMatch );
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
                                                'description_template' => 'design:class/datatype/browse_objectrelation_placement.tpl',
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
            case 'disable_selection_node':
            {
                $content =& $classAttribute->content();
                $content['default_selection_node'] = false;
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
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( $object )
        {
            $attributes =& $object->contentObjectAttributes();
            return eZContentObjectAttribute::metaDataArray( $attributes );
        }
        return false;
    }

    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( $object )
        {
            return $object->attribute( 'name' );
        }
        return false;
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( $object )
            return true;
        return false;
    }

    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $content =& $classAttribute->content();
        $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'selection-type',
                                                                                 array( 'id' => $content['selection_type'] ) ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'fuzzy-match',
                                                                                 array( 'id' => $content['fuzzy_match'] ) ) );
        if ( $content['default_selection_node'] )
            $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-selection',
                                                                                     array( 'node-id' => $content['default_selection_node'] ) ) );
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

        $fuzzyMatchNode = $attributeParametersNode->elementByName( 'fuzzy-match' );
        $content['fuzzy_match'] = false;
        if ( $fuzzyMatchNode )
            $content['fuzzy_match'] = $fuzzyMatchNode->attributeValue( 'id' );

        $defaultSelectionNode = $attributeParametersNode->elementByName( 'default-selection' );
        $content['default_selection_node'] = false;
        if ( $defaultSelectionNode )
            $content['default_selection_node'] = $defaultSelectionNode->attributeValue( 'node-id' );

        $classAttribute->setContent( $content );
        $classAttribute->store();
    }

    /// \privatesection
}

eZDataType::register( EZ_DATATYPESTRING_OBJECT_RELATION, "ezobjectrelationtype" );

?>
