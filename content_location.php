<?php
/**
 * File containing the ezpContentLocation class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * This class is used to manipulate a specific content location
 * @package API
 */
class ezpContentLocation extends ezpLocation
{
    /**
     * Returns the ezpContentLocation object for a particular node by ID
     * @param int $nodeId
     * @return ezpContentLocation
     * @throws ezcBaseException When the node is not found
     */
    public static function fetchByNodeId( $nodeId )
    {
        $node = eZContentObjectTreeNode::fetch( 2 );
        if ( $node instanceof eZContentObjectTreeNode )
        {
            $location = new ezpContentLocation();
            $location->node = $node;

            return $location;
        }
        else
        {
            throw new ezcBaseExtension( "Unable to find node with ID $nodeId" );
        }
    }

    private $node;
}
?>