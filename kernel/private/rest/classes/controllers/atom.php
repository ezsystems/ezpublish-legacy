<?php
/**
 *  File containing the atom controller.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Controller class for producing atom feeds of content structure.
 *
 * This controller will provide several actions for retrieving content. There
 * will be basic collections, and more specialiced actions to retrieve delta
 * of new content based on updates since last-modified-date and/or feed entry
 * IDs.
 */
class ezpRestAtomController extends ezcMvcController
{
    public function doCollection()
    {
        // Document need to contain the minimum require data for each collection
        // Author, title, updated, id, link

        $crit = new ezpContentCriteria();
        $crit->accept[] = ezpContentCriteria::location()->subtree( ezpContentLocation::fetchByNodeId( $this->nodeId ) );
        $childNodes = ezpContentRepository::query( $crit );


        $result = new ezcMvcResult();

        $retData = array();
        $protIndex = strpos( $this->request->protocol, '-' );
        $baseUri = substr( $this->request->protocol, 0, $protIndex ) . "://{$this->request->host}";
        foreach( $childNodes as $node )
        {
            $childEntry = array(
                            'objectName' => $node->name,
                            'author' => $node->owner->Name,
                            'modified' => $node->dateModified,
                            'published' => $node->datePublished,
                            'classIdentifier' => $node->classIdentifier,
                            'nodeUrl' => $baseUri . $this->getRouter()->generateUrl( 1, array( 'nodeId' => $node->locations->node_id ) ) );
            $retData[] = $childEntry;
        }

        $result->variables['collection'] = $retData;
        return $result;
    }
}

?>
