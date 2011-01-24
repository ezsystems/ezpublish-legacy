<?php
/**
 * File containing the ezpContentRestController class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * This controller is used for serving content
 */
class ezpRestContentController extends ezpRestMvcController
{
    /**
     * Expected Response groups for content viewing
     * @var string
     */
    const VIEWCONTENT_RESPONSEGROUP_METADATA = 'Metadata',
          VIEWCONTENT_RESPONSEGROUP_LOCATIONS = 'Locations',
          VIEWCONTENT_RESPONSEGROUP_FIELDS = 'Fields';
          
    /**
     * Expected Response groups for field viewing
     * @var string
     */
    const VIEWFIELDS_RESPONSEGROUP_FIELDVALUES = 'FieldValues',
          VIEWFIELDS_RESPONSEGORUP_METADATA = 'Metadata';
    
    /**
     * Handles content requests per node or object ID
     *
     * Requests:
     * - GET /api/content/node/XXX
     * - GET /api/content/object/XXX
     *
     * Optional HTTP parameters:
     * - translation=xxx-XX: an optionally forced locale to return
     *
     * @return ezpRestMvcResult
     */
    public function doViewContent()
    {
        $this->setDefaultResponseGroups( array( self::VIEWCONTENT_RESPONSEGROUP_METADATA ) );
        
        $isNodeRequested = false;
        if ( isset( $this->nodeId ) )
        {
            $content = ezpContent::fromNodeId( $this->nodeId );
            $isNodeRequested = true;
        }
        elseif ( isset( $this->objectId ) )
        {
            $content = ezpContent::fromObjectId( $this->objectId );
        }

        $result = new ezpRestMvcResult();
        
        // translation parameter
        if ( $this->hasContentVariable( 'Translation' ) )
            $content->setActiveLanguage( $this->getContentVariable( 'Translation' ) );

        // Handle metadata
        if( $this->hasResponseGroup( self::VIEWCONTENT_RESPONSEGROUP_METADATA ) )
        {
            $result->variables['metadata'] = ezpRestContentModel::getMetadataByContent( $content, $isNodeRequested );
            if( $isNodeRequested )
            {
                $location = ezpContentLocation::fetchByNodeId( $this->nodeId );
                $result->variables['metadata']['nodeId'] = $location->node_id;
                $result->variables['metadata']['nodeRemoteId'] = $location->remote_id;
            }
        }
        
        // Handle locations if requested
        if( $this->hasResponseGroup( self::VIEWCONTENT_RESPONSEGROUP_LOCATIONS ) )
        {
            $result->variables['locations'] = ezpRestContentModel::getLocationsByContent( $content );
        }
        
        // Handle fields content if requested
        if( $this->hasResponseGroup( self::VIEWCONTENT_RESPONSEGROUP_FIELDS ) )
        {
            $result->variables['fields'] = ezpRestContentModel::getFieldsByContent( $content );
        }
        
        // Add links to fields resources
        $result->variables['links'] = ezpRestContentModel::getFieldsLinksByContent( $content, $this->request );

        return $result;
    }

    /**
     * Handles a content request with fields per object or node id
     * Request: GET /api/content/object/XXX/fields
     * Request: GET /api/content/node/XXX/fields
     *
     * @return ezpRestMvcResult
     */
    public function doViewFields()
    {
        $this->setDefaultResponseGroups( array( self::VIEWFIELDS_RESPONSEGROUP_FIELDVALUES ) );
        
        $isNodeRequested = false;
        if ( isset( $this->nodeId ) )
        {
            $content = ezpContent::fromNodeId( $this->nodeId );
            $isNodeRequested = true;
        }
        elseif ( isset( $this->objectId ) )
        {
            $content = ezpContent::fromObjectId( $this->objectId );
        }

        $result = new ezpRestMvcResult();

        // translation parameter
        if ( $this->hasContentVariable( 'Translation' ) )
            $content->setActiveLanguage( $this->getContentVariable( 'Translation' ) );

        // Handle field values
        if( $this->hasResponseGroup( self::VIEWFIELDS_RESPONSEGROUP_FIELDVALUES ) )
        {
            $result->variables['fields'] = ezpRestContentModel::getFieldsByContent( $content );
        }
        
        // Handle object/node metadata
        if( $this->hasResponseGroup( self::VIEWFIELDS_RESPONSEGORUP_METADATA ) )
        {
            $result->variables['metadata'] = ezpRestContentModel::getMetadataByContent( $content );
            if( $isNodeRequested )
            {
                $location = ezpContentLocation::fetchByNodeId( $this->nodeId );
                $result->variables['metadata']['nodeId'] = $location->node_id;
                $result->variables['metadata']['nodeRemoteId'] = $location->remote_id;
            }
        }
        
        return $result;
    }

    /**
     * Handles a content unique field request through an object or node ID
     *
     * Requests:
     * - GET /api/content/node/:nodeId/field/:fieldIdentifier
     * - GET /api/content/object/:objectId/field/:fieldIdentifier
     *
     * @return ezpRestMvcResult
     */
    public function doViewField()
    {
        $this->setDefaultResponseGroups( array( self::VIEWFIELDS_RESPONSEGROUP_FIELDVALUES ) );
        
        $isNodeRequested = false;
        if ( isset( $this->nodeId ) )
        {
            $isNodeRequested = true;
            $content = ezpContent::fromNodeId( $this->nodeId );
        }
        elseif ( isset( $this->objectId ) )
        {
            $content = ezpContent::fromObjectId( $this->objectId );
        }

        if ( !isset( $content->fields->{$this->fieldIdentifier} ) )
        {
            throw new ezpContentFieldNotFoundException( "'$this->fieldIdentifier' field is not available for this content." );
        }

        // Translation parameter
        if ( $this->hasContentVariable( 'Translation' ) )
            $content->setActiveLanguage( $this->getContentVariable( 'Translation' ) );

        $result = new ezpRestMvcResult();
            
        // Field data
        if( $this->hasResponseGroup( self::VIEWFIELDS_RESPONSEGROUP_FIELDVALUES ) )
        {
            $result->variables['fields'][$this->fieldIdentifier] = ezpRestContentModel::attributeOutputData( $content->fields->{$this->fieldIdentifier} );
        }
        
        // Handle object/node metadata
        if( $this->hasResponseGroup( self::VIEWFIELDS_RESPONSEGORUP_METADATA ) )
        {
            $result->variables['metadata'] = ezpRestContentModel::getMetadataByContent( $content );
            if( $isNodeRequested )
            {
                $location = ezpContentLocation::fetchByNodeId( $this->nodeId );
                $result->variables['metadata']['nodeId'] = $location->node_id;
                $result->variables['metadata']['nodeRemoteId'] = $location->remote_id;
            }
        }

        return $result;
    }

    public function doList()
    {
        $crit = new ezpContentCriteria();

        // Hmm, the following sequence is too long...
        $crit->accept[] = ezpContentCriteria::location()->subtree( ezpContentLocation::fetchByNodeId( $this->nodeId ) );

        $childNodes = ezpContentRepository::query( $crit );

        // Need paging here

        $result = new ezcMvcResult();

        $retData = array();
        // To be moved to URI convenience methods
        $protIndex = strpos( $this->request->protocol, '-' );
        $baseUri = substr( $this->request->protocol, 0, $protIndex ) . "://{$this->request->host}";
        foreach( $childNodes as $node )
        {
            $childEntry = array(
                            'objectName' => $node->name,
                            'classIdentifier' => $node->classIdentifier,
                            'nodeUrl' => $baseUri . $this->getRouter()->generateUrl( 1, array( 'nodeId' => $node->locations->node_id ) ) );
            $retData[] = $childEntry;
        }
        $result->variables['childNodes'] = $retData;
        return $result;
    }
}
?>
