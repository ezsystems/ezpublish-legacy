<?php
/**
 * File containing ezpRestContentModel class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * Model class containing backend method for REST content controller
 */
class ezpRestContentModel extends ezpRestModel
{
    /**
     * Returns metadata for given content as array
     * @param ezpContent $content
     * @return array
     */
    public static function getMetadataByContent( ezpContent $content )
    {
        $aMetadata = array(
            'objectName'            => $content->name,
            'classIdentifier'       => $content->classIdentifier,
            'datePublished'         => (int)$content->datePublished,
            'dateModified'          => (int)$content->dateModified,
            'objectRemoteId'        => $content->remote_id,
            'objectId'              => (int)$content->id
        );
        
        return $aMetadata;
    }
    
    /**
     * Returns metadata for given content location as array
     * @param ezpContentLocation $location
     * @return array
     */
    public static function getMetadataByLocation( ezpContentLocation $location )
    {
        $url = $location->url_alias;
        eZURI::transformURI( $url, false, 'full' ); // $url is passed as a reference
        
        $aMetadata = array(
            'nodeId'        => (int)$location->node_id,
            'nodeRemoteId'  => $location->remote_id,
            'fullUrl'       => $url
        );
        
        return $aMetadata;
    }
    
    /**
     * Returns all locations for provided content as array.
     * @param ezpContent $content
     * @return array Associative array with following keys :
     *                  - fullUrl => URL for content, including server
     *                  - nodeId => NodeID for location
     *                  - remoteId => RemoteID for location
     *                  - isMain => whether location is main for provided content
     */
    public static function getLocationsByContent( ezpContent $content )
    {
        $aReturnLocations = array();
        $assignedNodes = $content->assigned_nodes;
        foreach( $assignedNodes as $node )
        {
            $location = ezpContentLocation::fromNode( $node );
            $locationData = self::getMetadataByLocation( $location );
            $locationData['isMain'] = $location->is_main;
            $aReturnLocations[] = $locationData;
        }
        
        return $aReturnLocations;
    }
    
    /**
     * Returns all fields for provided content
     * @param ezpContent $content
     * @return array Associative array with following keys :
     *                  - type => Field type (datatype string)
     *                  - identifier => Attribute identifier
     *                  - value => String representation of field content
     *                  - id => Attribute numerical ID
     *                  - classattribute_id => Numerical class attribute ID
     */
    public static function getFieldsByContent( ezpContent $content )
    {
        $aReturnFields = array();
        foreach( $content->fields as $name => $field )
        {
            $aReturnFields[$name] = self::attributeOutputData( $field );
        }
        
        return $aReturnFields;
    }
    
    /**
     * Transforms an ezpContentField in an array representation
     * @todo Refactor, this doesn't really belong here. Either in ezpContentField, or in an extend class
     * @param ezpContentField $field
     * @return array Associative array with following keys :
     *                  - type => Field type (datatype string)
     *                  - identifier => Attribute identifier
     *                  - value => String representation of field content
     *                  - id => Attribute numerical ID
     *                  - classattribute_id => Numerical class attribute ID
     */
    public static function attributeOutputData( ezpContentField $field )
    {
        // @TODO move to datatype representation layer
        switch( $field->data_type_string )
        {
            case 'ezxmltext':
                $html = $field->content->attribute( 'output' )->attribute( 'output_text' );
                $attributeValue = array( strip_tags( $html ) );
                break;
            case 'ezimage':
                $strRepImage = $field->toString();
                $delimPos = strpos( $strRepImage, '|' );
                if ( $delimPos !== false )
                {
                    $strRepImage = substr( $strRepImage, 0, $delimPos );
                }
                $attributeValue = array( $strRepImage );
                break;
            default:
                $attributeValue = array( $field->toString() );
                break;
        }

        // cleanup values so that the result is consistent:
        // - no array if one item
        // - false if no values
        if ( count( $attributeValue ) == 0 )
        {
            $attributeValue = false;
        }
        elseif ( count( $attributeValue ) == 1 )
        {
            $attributeValue = current( $attributeValue );
        }

        return array(
            'type'                  => $field->data_type_string,
            'identifier'            => $field->contentclass_attribute_identifier,
            'value'                 => $attributeValue,
            'id'                    => (int)$field->id,
            'classattribute_id'     => (int)$field->contentclassattribute_id
        );
    }
    
    /**
     * Returns fields links for a given content, for a potential future request on a specific field.
     * Note that every link provided is based on the current URI.
     * So for a content REST request "/content/node/2?Translation=eng-GB", a field link will look like "content/node/2/field/field_identifier?Translation=eng-GB"
     * @param ezpContent $content
     * @param ezpRestRequest $currentRequest Current REST request object. Needed to build proper links
     * @return array Associative array, indexed by field identifier. An additional "*" index is added to request every fields
     */
    public static function getFieldsLinksByContent( ezpContent $content, ezpRestRequest $currentRequest )
    {
         $links = array();
         $baseUri = $currentRequest->getBaseURI();
         $contentQueryString = $currentRequest->getContentQueryString( true );
         
         foreach( $content->fields as $fieldName => $fieldValue )
         {
             $links[$fieldName] = $baseUri.'/field/'.$fieldName.$contentQueryString;
         }
         $links['*'] = $baseUri.'/fields'.$contentQueryString;

         return $links;
    }
    
    /**
     * Returns all children node data, based on the provided criteria object
     * @param ezpContentCriteria $c
     * @param ezpRestRequest $currentRequest
     * @param array $responseGroups Requested ResponseGroups
     * @return array
     */
    public static function getChildrenList( ezpContentCriteria $c, ezpRestRequest $currentRequest, array $responseGroups = array() )
    {
        $aRetData = array();
        $aChildren = ezpContentRepository::query( $c );

        foreach( $aChildren as $childNode )
        {
            $childEntry = self::getMetadataByContent( $childNode );
            $childEntry = array_merge( $childEntry, self::getMetadataByLocation( $childNode->locations ) );
            
            // Add fields with their values if requested
            if( in_array( ezpRestContentController::VIEWLIST_RESPONSEGROUP_FIELDS, $responseGroups ) )
            {
                $childEntry['fields'] = array();
                foreach( $childNode->fields as $fieldName => $field )
                {
                    $childEntry['fields'][$fieldName] = self::attributeOutputData( $field );
                }
            }
            
            $aRetData[] = $childEntry;
        }
        
        return $aRetData;
    }
    
    /**
     * Returns the children count, based on the provided criteria object
     * @param ezpContentCriteria $c
     * @return int
     */
    public static function getChildrenCount( ezpContentCriteria $c )
    {
        $count = ezpContentRepository::queryCount( $c );
        return $count;
    }
}

?>
