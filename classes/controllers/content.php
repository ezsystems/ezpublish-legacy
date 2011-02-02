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
     * Expected Response groups for content children listing
     * @var string
     */
    const VIEWLIST_RESPONSEGROUP_METADATA = 'Metadata',
          VIEWLIST_RESPONSEGROUP_FIELDS = 'Fields';
    
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
            $objectMetadata = ezpRestContentModel::getMetadataByContent( $content, $isNodeRequested );
            if( $isNodeRequested )
            {
                $nodeMetadata = ezpRestContentModel::getMetadataByLocation( ezpContentLocation::fetchByNodeId( $this->nodeId ) );
                $objectMetadata = array_merge( $objectMetadata, $nodeMetadata );
            }
            $result->variables['metadata'] = $objectMetadata;
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

        if ( isset($this->request->get['OutputFormat']) )
        {
            $renderer = ezpRestContentRenderer::getRendererForContent( $this->request->get['OutputFormat'], $content );
            $result->variables['renderedOutput'] = $renderer->render();
        }

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
            $objectMetadata = ezpRestContentModel::getMetadataByContent( $content, $isNodeRequested );
            if( $isNodeRequested )
            {
                $nodeMetadata = ezpRestContentModel::getMetadataByLocation( ezpContentLocation::fetchByNodeId( $this->nodeId ) );
                $objectMetadata = array_merge( $objectMetadata, $nodeMetadata );
            }
            $result->variables['metadata'] = $objectMetadata;
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
            $objectMetadata = ezpRestContentModel::getMetadataByContent( $content, $isNodeRequested );
            if( $isNodeRequested )
            {
                $nodeMetadata = ezpRestContentModel::getMetadataByLocation( ezpContentLocation::fetchByNodeId( $this->nodeId ) );
                $objectMetadata = array_merge( $objectMetadata, $nodeMetadata );
            }
            $result->variables['metadata'] = $objectMetadata;
        }

        return $result;
    }

    /**
     * Handles a content request to view a node children list
     * Requests :
     *   - GET /api/v1/content/node/<nodeId>/list(/offset/<offset>/limit/<limit>/sort/<sortKey>/<sortType>)
     *   - Every parameters in parenthesis are optional. However, to have offset/limit and sort, the order is mandatory
     *     (you can't provide sorting params before limit params). This is due to a limitation in the regexp route.
     *   - Following requests are valid :
     *     - /api/ezp/content/node/2/list/sort/name => will display 10 (default limit) children of node 2, sorted by ascending name
     *     - /api/ezp/content/node/2/list/limit/50/sort/published/desc => will display 50 children of node 2, sorted by descending publishing date
     *     - /api/ezp/content/node/2/list/offset/100/limit/50/sort/published/desc => will display 50 children of node 2 starting from offset 100, sorted by descending publishing date
     *
     * Default values :
     *   - offset : 0
     *   - limit : 10
     *   - sortType : asc
     */
    public function doList()
    {
        $this->setDefaultResponseGroups( array( self::VIEWLIST_RESPONSEGROUP_METADATA ) );
        $result = new ezpRestMvcResult();
        $crit = new ezpContentCriteria();

        // Location criteria
        // Hmm, the following sequence is too long...
        $crit->accept[] = ezpContentCriteria::location()->subtree( ezpContentLocation::fetchByNodeId( $this->nodeId ) );
        $crit->accept[] = ezpContentCriteria::depth( 1 ); // Fetch children only
        
        // Limit criteria
        $offset = isset( $this->offset ) ? $this->offset : 0;
        $limit = isset( $this->limit ) ? $this->limit : 10;
        $crit->accept[] = ezpContentCriteria::limit()->offset( $offset )->limit( $limit );
        
        // Sort criteria
        if( isset( $this->sortKey ) )
        {
            $sortOrder = isset( $this->sortType ) ? $this->sortType : 'asc';
            $crit->accept[] = ezpContentCriteria::sorting( $this->sortKey, $sortOrder );
        }

        $result->variables['childrenNodes'] = ezpRestContentModel::getChildrenList( $crit, $this->request, $this->getResponseGroups() );
        // REST links to children nodes
        // Little dirty since this should belong to the model layer, but I don't want to pass the router nor the full controller to the model
        $contentQueryString = $this->request->getContentQueryString( true );
        for( $i = 0, $iMax = count( $result->variables['childrenNodes'] ); $i < $iMax; ++$i )
        {
            $linkURI = $this->getRouter()->generateUrl( 'ezpNode', array( 'nodeId' => $result->variables['childrenNodes'][$i]['nodeId'] ) );
            $result->variables['childrenNodes'][$i]['link'] = $this->request->getHostURI().$linkURI.$contentQueryString;
        }
        
        // Handle Metadata
        if( $this->hasResponseGroup( self::VIEWLIST_RESPONSEGROUP_METADATA ) )
        {
            $childrenCount = ezpRestContentModel::getChildrenCount( $crit );
            $result->variables['metadata'] = array(
                'childrenCount' => $childrenCount,
                'parentNodeId'  => $this->nodeId
            );
            
        }
        
        return $result;
    }
    
    /**
     * Counts children of a given node
     * Request :
     *   - GET /api/ezp/content/node/childrenCount
     */
    public function doCountChildren()
    {
        $this->setDefaultResponseGroups( array( self::VIEWLIST_RESPONSEGROUP_METADATA ) );
        $result = new ezpRestMvcResult();
        
        if( $this->hasResponseGroup( self::VIEWLIST_RESPONSEGROUP_METADATA ) )
        {
            $crit = new ezpContentCriteria();
            $crit->accept[] = ezpContentCriteria::location()->subtree( ezpContentLocation::fetchByNodeId( $this->nodeId ) );
            $crit->accept[] = ezpContentCriteria::depth( 1 ); // Fetch children only
            $childrenCount = ezpRestContentModel::getChildrenCount( $crit );
            $result->variables['metadata'] = array(
                'childrenCount' => $childrenCount,
                'parentNodeId'  => $this->nodeId
            );
        }
        
        return $result;
    }
}
?>
