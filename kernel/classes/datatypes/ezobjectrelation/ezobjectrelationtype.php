<?php
//
// Definition of eZObjectRelationType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZObjectRelationType ezobjectrelationtype.php
  \ingroup eZDatatype
  \brief A content datatype which handles object relations

*/

//include_once( "kernel/classes/ezdatatype.php" );
//include_once( "lib/ezutils/classes/ezintegervalidator.php" );
//include_once( "lib/ezi18n/classes/eztranslatormanager.php" );

class eZObjectRelationType extends eZDataType
{
    const DATA_TYPE_STRING = "ezobjectrelation";

    /*!
     Initializes with a string id and a description.
    */
    function eZObjectRelationType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Object relation", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Initializes the class attribute with some data.
     \reimp
     */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataText );
        }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $postVariableName = $base . "_data_object_relation_id_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $relatedObjectID = $http->postVariable( $postVariableName );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();

            if ( $contentObjectAttribute->validateIsRequired() and $relatedObjectID == 0 )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Missing objectrelation input.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $postVariableName = $base . "_data_object_relation_id_" . $contentObjectAttribute->attribute( "id" );
        $haveData = false;
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $relatedObjectID = $http->postVariable( $postVariableName );
            if ( $relatedObjectID == '' )
                $relatedObjectID = null;
            $contentObjectAttribute->setAttribute( 'data_int', $relatedObjectID );
            $haveData = true;
        }
        $fuzzyMatchVariableName = $base . "_data_object_relation_fuzzy_match_" . $contentObjectAttribute->attribute( "id" );
        if ( $http->hasPostVariable( $fuzzyMatchVariableName ) )
        {
            //include_once( 'lib/ezi18n/classes/ezchartransform.php' );
            $trans = eZCharTransform::instance();

            $fuzzyMatchText = trim( $http->postVariable( $fuzzyMatchVariableName ) );
            if ( $fuzzyMatchText != '' )
            {
                $fuzzyMatchText = $trans->transformByGroup( $fuzzyMatchText, 'lowercase' );
                $classAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
                if ( $classAttribute )
                {
                    $classContent = $classAttribute->content();
                    if ( $classContent['default_selection_node'] )
                    {
                        $nodeID = $classContent['default_selection_node'];
                        $nodeList = eZContentObjectTreeNode::subTreeByNodeID( array( 'Depth' => 1 ), $nodeID );
                        $lastDiff = false;
                        $matchObjectID = false;
                        foreach ( $nodeList as $node )
                        {
                            $name = $trans->transformByGroup( trim( $node->attribute( 'name' ) ), 'lowercase' );
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
     Stores relation to the ezcontentobject_link table
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $contentClassAttributeID = $contentObjectAttribute->ContentClassAttributeID;
        $contentObjectID = $contentObjectAttribute->ContentObjectID;
        $contentObjectVersion = $contentObjectAttribute->Version;

        $obj = $contentObjectAttribute->object();
        //get eZContentObjectVersion
        $currVerobj = $obj->version( $contentObjectVersion );
        // get array of language codes
        $transList = $currVerobj->translations( false );
        $countTsl = count( $transList );

        if ( ( $countTsl == 1 ) )
        {
             eZContentObject::fetch( $contentObjectID )->removeContentObjectRelation( false, $contentObjectVersion, $contentClassAttributeID, eZContentObject::RELATION_ATTRIBUTE );
        }

        $objectID = $contentObjectAttribute->attribute( "data_int" );

        if ( $objectID )
        {
            eZContentObject::fetch( $contentObjectID )->addContentObjectRelation( $objectID, $contentObjectVersion, $contentClassAttributeID, eZContentObject::RELATION_ATTRIBUTE );
        }
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $selectionTypeName = 'ContentClass_ezobjectrelation_selection_type_' . $classAttribute->attribute( 'id' );
        $state = eZInputValidator::STATE_ACCEPTED;
        if ( $http->hasPostVariable( $selectionTypeName ) )
        {
            $selectionType = $http->postVariable( $selectionTypeName );
            if ( $selectionType < 0 and
                 $selectionType > 2 )
            {
                $state = eZInputValidator::STATE_INVALID;
            }
        }
        return $state;
    }

    /*!
     \reimp
    */
    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
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

    function preStoreClassAttribute( $classAttribute, $version )
    {
        $content = $classAttribute->content();
        $classAttribute->setAttribute( 'data_int1', $content['selection_type'] );
        $classAttribute->setAttribute( 'data_int2', $content['default_selection_node'] );
        $classAttribute->setAttribute( 'data_int3', $content['fuzzy_match'] );
    }

    /*!
     \private
     Delete the old version from ezcontentobject_link if count of translations > 1
    */
    function removeContentObjectRelation( $contentObjectAttribute )
    {
        $obj = $contentObjectAttribute->object();
        $atrributeTrans = $contentObjectAttribute->fetchAttributeTranslations( );
        // Check if current relation exists in ezcontentobject_link
        foreach ( $atrributeTrans as $attrTarns )
        {
            if ( $attrTarns->attribute( 'id' ) != $contentObjectAttribute->attribute( 'id' ) )
                if ( $attrTarns->attribute( 'data_int' ) == $contentObjectAttribute->attribute( 'data_int' ) )
                     return;
        }

        //get eZContentObjectVersion
        $currVerobj = $obj->currentVersion();
        // get array of ezcontentobjecttranslations
        $transList = $currVerobj->translations( false );
        // get count of LanguageCode in transList
        $countTsl = count( $transList );
        // Delete the old version from ezcontentobject_link if count of translations > 1
        if ( $countTsl > 1 )
        {
            $objectID = $contentObjectAttribute->attribute( "data_int" );
            $contentClassAttributeID = $contentObjectAttribute->ContentClassAttributeID;
            $contentObjectID = $contentObjectAttribute->ContentObjectID;
            $contentObjectVersion = $contentObjectAttribute->Version;
            eZContentObject::fetch( $contentObjectID )->removeContentObjectRelation( $objectID, $contentObjectVersion, $contentClassAttributeID, eZContentObject::RELATION_ATTRIBUTE );
        }
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        switch ( $action )
        {
            case "set_object_relation" :
            {
                if ( $http->hasPostVariable( 'BrowseActionName' ) and
                          $http->postVariable( 'BrowseActionName' ) == ( 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) ) and
                          $http->hasPostVariable( "SelectedObjectIDArray" ) )
                {
                    if ( !$http->hasPostVariable( 'BrowseCancelButton' ) )
                    {
                        $selectedObjectArray = $http->hasPostVariable( "SelectedObjectIDArray" );
                        $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );

                        // Delete the old version from ezcontentobject_link if count of translations > 1
                        $this->removeContentObjectRelation( $contentObjectAttribute );

                        $objectID = $selectedObjectIDArray[0];
                        $contentObjectAttribute->setAttribute( 'data_int', $objectID );
                        $contentObjectAttribute->store();
                    }
                }
            } break;

            case "browse_object" :
            {
                $module = $parameters['module'];
                $redirectionURI = $parameters['current-redirection-uri'];
                $ini = eZINI::instance( 'content.ini' );

                //include_once( 'kernel/classes/ezcontentbrowse.php' );
                $browseType = 'AddRelatedObjectToDataType';
                $browseTypeINIVariable = $ini->variable( 'ObjectRelationDataTypeSettings', 'ClassAttributeStartNode' );
                foreach( $browseTypeINIVariable as $value )
                {
                    list( $classAttributeID, $type ) = explode( ';',$value );
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
            } break;

            case "remove_object" :
            {
                // Delete the old version from ezcontentobject_link if count of translations > 1
                $this->removeContentObjectRelation( $contentObjectAttribute );

                $contentObjectAttribute->setAttribute( 'data_int', 0 );
                $contentObjectAttribute->store();
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
    function objectAttributeContent( $contentObjectAttribute )
    {
        $objectID = $contentObjectAttribute->attribute( "data_int" );
        if ( $objectID != 0 )
            $object = eZContentObject::fetch( $objectID );
        else
            $object = null;
        return $object;
    }

    /*!
     \reimp
     Sets \c grouped_input to \c true when browse mode is active or
     a dropdown with a fuzzy match is used.
    */
    function objectDisplayInformation( $objectAttribute, $mergeInfo = false )
    {
        $classAttribute = $objectAttribute->contentClassAttribute();
        $content = eZObjectRelationType::classAttributeContent( $classAttribute );
        $editGrouped = ( $content['selection_type'] == 0 or
                         ( $content['selection_type'] == 1 and $content['fuzzy_match'] ) );

        $info = array( 'edit' => array( 'grouped_input' => $editGrouped ),
                       'collection' => array( 'grouped_input' => $editGrouped ) );
        return eZDataType::objectDisplayInformation( $objectAttribute, $info );
    }

    /*!
     \reimp
    */
    function sortKey( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'int';
    }

    function classAttributeContent( $classObjectAttribute )
    {
        $selectionType = $classObjectAttribute->attribute( "data_int1" );
        $defaultSelectionNode = $classObjectAttribute->attribute( "data_int2" );
        $fuzzyMatch = $classObjectAttribute->attribute( "data_int3" );
        return array( 'selection_type' => $selectionType,
                      'default_selection_node' => $defaultSelectionNode,
                      'fuzzy_match' => $fuzzyMatch );
    }

    function customClassAttributeHTTPAction( $http, $action, $classAttribute )
    {
        switch ( $action )
        {
            case 'browse_for_selection_node':
            {
                $module = $classAttribute->currentModule();
                //include_once( 'kernel/classes/ezcontentbrowse.php' );
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
                //include_once( 'kernel/classes/ezcontentbrowse.php' );
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
                $content = $classAttribute->content();
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
            if ( eZContentObject::recursionProtect( $object->attribute( 'id' ) ) )
            {
                // Does the related object exist in the same language as the current content attribute ?
                if ( in_array( $contentObjectAttribute->attribute( 'language_code' ), $object->attribute( 'current' )->translationList( false, false ) ) )
                {
                    $attributes = $object->attribute( 'current' )->contentObjectAttributes( $contentObjectAttribute->attribute( 'language_code' ) );
                }
                else
                {
                    $attributes = $object->contentObjectAttributes();
                }

                return eZContentObjectAttribute::metaDataArray( $attributes );
            }
            else
            {
                return array();
            }
        }
        return false;
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        if ( !is_numeric( $string ) || !eZContentObject::fetch( $string ) )
            return false;

        $contentObjectAttribute->setAttribute( 'data_int', $string );
        return true;
    }

    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( $contentObjectAttribute, $name = null )
    {
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( $object )
        {
            return $object->attribute( 'name' );
        }
        return false;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $object = $this->objectAttributeContent( $contentObjectAttribute );
        if ( $object )
            return true;
        return false;
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $content = $classAttribute->content();
        $dom = $attributeParametersNode->ownerDocument;
        $selectionTypeNode = $dom->createElement( 'selection-type' );
        $selectionTypeNode->setAttribute( 'id', $content['selection_type'] );
        $attributeParametersNode->appendChild( $selectionTypeNode );
        $fuzzyMatchNode = $dom->createElement( 'fuzzy-match' );
        $fuzzyMatchNode->setAttribute( 'id', $content['fuzzy_match'] );
        $attributeParametersNode->appendChild( $fuzzyMatchNode );
        if ( $content['default_selection_node'] )
        {
            $defaultSelectionNode = $dom->createElement( 'default-selection' );
            $defaultSelectionNode->setAttribute( 'node-id', $content['default_selection_node'] );
            $attributeParametersNode->appendChild( $defaultSelectionNode );
        }
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $content = $classAttribute->content();
        $selectionTypeNode = $attributeParametersNode->getElementsByTagName( 'selection-type' )->item( 0 );
        $content['selection_type'] = 0;
        if ( $selectionTypeNode )
            $content['selection_type'] = $selectionTypeNode->getAttribute( 'id' );

        $fuzzyMatchNode = $attributeParametersNode->getElementsByTagName( 'fuzzy-match' )->item( 0 );
        $content['fuzzy_match'] = false;
        if ( $fuzzyMatchNode )
            $content['fuzzy_match'] = $fuzzyMatchNode->getAttribute( 'id' );

        $defaultSelectionNode = $attributeParametersNode->getElementsByTagName( 'default-selection' )->item( 0 );
        $content['default_selection_node'] = false;
        if ( $defaultSelectionNode )
            $content['default_selection_node'] = $defaultSelectionNode->getAttribute( 'node-id' );

        $classAttribute->setContent( $content );
        $classAttribute->store();
    }

    /*!
     Export related object's remote_id.
     \reimp
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $relatedObjectID = $objectAttribute->attribute( 'data_int' );

        if ( !is_null( $relatedObjectID ) )
        {
            require_once( 'kernel/classes/ezcontentobject.php' );
            $relatedObject = eZContentObject::fetch( $relatedObjectID );
            if ( !$relatedObject )
            {
                eZDebug::writeNotice( 'Related object with ID: ' . $relatedObjectID . ' does not exist.' );
            }
            else
            {
                $relatedObjectRemoteID = $relatedObject->attribute( 'remote_id' );
                $dom = $node->ownerDocument;
                $relatedObjectRemoteIDNode = $dom->createElement( 'related-object-remote-id' );
                $relatedObjectRemoteIDNode->appendChild( $dom->createTextNode( $relatedObjectRemoteID ) );
                $node->appendChild( $relatedObjectRemoteIDNode );
            }
        }

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $relatedObjectRemoteIDNode = $attributeNode->getElementsByTagName( 'related-object-remote-id' )->item( 0 );
        $relatedObjectID = null;

        if ( $relatedObjectRemoteIDNode )
        {
            $relatedObjectRemoteID = $relatedObjectRemoteIDNode->textContent;
            $object = eZContentObject::fetchByRemoteID( $relatedObjectRemoteID );
            if ( $object )
            {
                $relatedObjectID = $object->attribute( 'id' );
            }
            else
            {
                // store remoteID so it can be used in postUnserialize
                $objectAttribute->setAttribute( 'data_text', $relatedObjectRemoteID );
            }
        }

        $objectAttribute->setAttribute( 'data_int', $relatedObjectID );
    }

    /*!
     \reimp
    */
    function postUnserializeContentObjectAttribute( $package, $objectAttribute )
    {
        $attributeChanged = false;
        $relatedObjectID = $objectAttribute->attribute( 'data_int' );

        if ( !$relatedObjectID )
        {
            // Restore cross-relations using preserved remoteID
            $relatedObjectRemoteID = $objectAttribute->attribute( 'data_text' );
            if ( $relatedObjectRemoteID)
            {
                $object = eZContentObject::fetchByRemoteID( $relatedObjectRemoteID );
                $relatedObjectID = ( $object !== null ) ? $object->attribute( 'id' ) : null;

                if ( $relatedObjectID )
                {
                    $objectAttribute->setAttribute( 'data_int', $relatedObjectID );
                    $attributeChanged = true;
                }
            }
        }

        return $attributeChanged;
    }

    /*!
     Removes objects with given ID from the relations list
    */
    function removeRelatedObjectItem( $contentObjectAttribute, $objectID )
    {
        $contentObjectAttribute->setAttribute( "data_int", null );
        return true;
    }

    /// \privatesection
}

eZDataType::register( eZObjectRelationType::DATA_TYPE_STRING, "eZObjectRelationType" );

?>
