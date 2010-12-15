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
class ezpRestContentController extends ezcMvcController implements ezpRestControllerInterface
{
    /**
     * Creates a view object associated with controller
     *
     * @param ezcMvcResult $result
     * @return ezcMvcView
     */
    public function loadView( ezcMvcResult $result )
    {
        return new ezpRestJsonView( $this->request, $result );
    }

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
     * @return ezcMvcResult
     */
    public function doViewContent()
    {
        // try {
            if ( isset( $this->nodeId ) )
                $content = ezpContent::fromNodeId( $this->nodeId );
            elseif ( isset( $this->objectId ) )
                $content = ezpContent::fromObjectId( $this->objectId );
        // } catch( Exception $e ) {
            // @todo handle error
            // die( $e->getMessage() );
        // }

        // translation parameter
        if ( isset( $this->request->variables['translation'] ) )
            $content->setActiveLanguage( $this->request->variables['translation'] );

        // object data
        $result = $this->viewContent( $content );

        // Add links to fields resources
        $result->variables['links'] = $this->fieldsLinks( $content );

        return $result;
    }

    /**
     * Generates links to fields request URIs
     * @param ezpContent $content
     * @return array
     */
    public function fieldsLinks( ezpContent $content )
    {
         $links = array();
         $baseUri = "{$this->request->protocol}://{$this->request->host}{$this->request->uri}";
         foreach( $content->fields as $fieldName => $fieldValue )
         {
             // @todo Handle translation GET parameter
             $links[$fieldName] = "$baseUri/field/$fieldName";
         }
         $links['*'] = "$baseUri/fields";

         return $links;
    }

    /**
     * Handles a content request with fields per object or node id
     * Request: GET /api/content/object/XXX/fields
     * Request: GET /api/content/node/XXX/fields
     *
     * @return ezcMvcResult
     */
    public function doViewFields()
    {
        try {
            if ( isset( $this->nodeId ) )
            {
                $content = ezpContent::fromNodeId( $this->nodeId );
            }
            elseif ( isset( $this->objectId ) )
            {
                $content = ezpContent::fromObjectId( $this->objectId );
            }
        } catch( Exception $e ) {
            // @todo handle error
            die( $e->getMessage() );
        }

        $result = new ezcMvcResult;

        // translation parameter
        if ( isset( $this->request->variables['translation'] ) )
            $content->setActiveLanguage( $this->request->variables['translation'] );

        // iterate over each field and extract its exposed properties
        $returnFields = array();
        foreach( $content->fields as $name => $field )
        {
            $returnFields[$name] = $this->attributeOutputData( $field );
        }
        $result->variables['fields'] = $returnFields;
        return $result;
    }

    /**
     * Handles a content unique field request through an object or node ID
     *
     * Requests:
     * - GET /api/content/node/:nodeId/field/:fieldIdentifier
     * - GET /api/content/object/:objectId/field/:fieldIdentifier
     *
     * @return ezcMvcResult
     */
    public function doViewField()
    {
        try {
            if ( isset( $this->nodeId ) )
                $content = ezpContent::fromNodeId( $this->nodeId );
            elseif ( isset( $this->objectId ) )
                $content = ezpContent::fromObjectId( $this->objectId );
        } catch( Exception $e ) {
            // @todo handle error
            die( $e->getMessage() );
        }

        if ( !isset( $content->fields->{$this->fieldIdentifier} ) )
        {
            // @todo Handle error
            return false;
        }

        // translation parameter
        if ( isset( $this->request->variables['translation'] ) )
            $content->setActiveLanguage( $this->request->variables['translation'] );

        // object metadata
        $result = self::viewContent( $content );

        // fieldd data
        $result->variables['fields'][$this->fieldIdentifier] = $this->attributeOutputData( $content->fields->{$this->fieldIdentifier} );

        return $result;
    }

    /**
     * Returns an ezcMvcResult that represents a piece of content
     * @param ezpContent $content
     * @param ezcMvcResult $result A result the variables will be added to. If not given, a fresh one is used.
     * @return ezcMvcResult
     */
    protected function viewContent( ezpContent $content, ezcMvcResult $result = null )
    {
        if ( $result === null )
            $result = new ezcMvcResult;

        // metadata
        $result->variables['classIdentifier'] = $content->classIdentifier;
        $result->variables['objectName'] = $content->name;
        $result->variables['datePublished'] = $content->datePublished;
        $result->variables['dateModified'] = $content->dateModified;

        // links to further resources about the object
        $resourceLinks = array();
        $result->variables['links'] = $resourceLinks;

        return $result;
    }

    /**
     * Transforms an ezpContentField in an array representation
     * @todo Refactor, this doesn't really belong here. Either in ezpContentField, or in an extend class
     */
    protected function attributeOutputData( ezpContentField $attribute )
    {
        // The following seems like an odd strategy.

        // $sXml = simplexml_import_dom( $attribute->serializedXML );
        // var_dump( $sXml->asXML() );
        // 
        // $attributeType = (string)$sXml['type'];
        // 
        // // get ezremote NS elements in order to get the attribute identifier
        // $ezremoteAttributes = $sXml->attributes( 'http://ez.no/ezobject' );
        // $attributeIdentifier = (string)$ezremoteAttributes['identifier'];
        // 
        // // attribute value
        // $children = $sXml->children();
        // $attributeValue = array();
        // foreach( $children as $child )
        // {
        //     // simple value
        //     if ( count( $child->children() ) == 0 )
        //     {
        //         // complex value, probably a native eZ Publish XML
        //         $attributeValue[$child->getName()] = (string)$child;
        //     }
        //     else
        //     {
        //         if ( $attributeType == 'ezxmltext' )
        //         {
        //             $html = $attribute->content->attribute( 'output' )->attribute( 'output_text' );
        //             $attributeValue = array( strip_tags( $html ) );
        //         }
        //     }
        // }
        
        // @TODO move to datatype representation layer
        switch( $attribute->data_type_string )
        {
            case 'ezxmltext':
                $html = $attribute->content->attribute( 'output' )->attribute( 'output_text' );
                $attributeValue = array( strip_tags( $html ) );
                break;
            case 'ezimage':
                $strRepImage = $attribute->tostring();
                $delimPos = strpos( $strRepImage, '|' );
                if ( $delimPos !== false )
                {
                    $strRepImage = substr( $strRepImage, 0, $delimPos );
                }
                $attributeValue = array( $strRepImage );
                break;
            default:
                $attributeValue = array( $attribute->tostring() );
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
            'type'       => $attribute->data_type_string,
            'identifier' => $attribute->contentclass_attribute_identifier,
            'value'      => $attributeValue,
        );
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
