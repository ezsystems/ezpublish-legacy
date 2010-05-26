<?php
/**
 * File containing the ezpContentRepository class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package API
 */

/**
 * This class handles querying of content repository
 * @package API
 */
class ezpContentRepository
{
    /**
     * Runs a content repository query using a given set of criteria
     *
     * @param ezpContentCriteria $criteria
     * @return ezpContentList
     */
    public static function query( ezpContentCriteria $criteria )
    {
        $rootNodeId = array();

        /**
         * We have a set of content criteria in $criteria
         * These criteria provide us with:
         * - location conditions (part of subtree X, not part of subtree Y, etc)
         * - content based conditions (content class, attribute value, etc)
         *
         * Based on this, we need to end up calling eZContentObjectTreeNode and return the resulting objects
         * as an ezpContentList, a countable iterator that iterates over ezpContent objects
         */

        /**
         * eZContentObjectTreeNode requires one or more root node IDs to perform a content request.
         * Such a root node can be provided using an ezpContentLocationCriteria. If none is provided, a default root node
         * can be used. This is ezpContentRepository::$defaultRootNode
         */

        // each criteria will translate to a method parameter, either in the $params array,
        // or for the root location as $rootNodeId
        foreach( $criteria->accept as $acceptCriteria )
        {
            $translatedCriteria = $acceptCriteria->translate();
            $params = array();

            switch( $translatedCriteria['type'] )
            {
                case 'location':
                    $rootNodeId[] = $translatedCriteria['value'];
                    break;

                case 'param':
                    foreach( $translatedCriteria['name'] as $idx => $criteriaName )
                    {
                        $params[$criteriaName] = $translatedCriteria['value'][$idx];
                    }
                    break;
            }
        }

        // foreach( $criteria->deny as $denyCriteria ) {}

        $nodes = eZContentObjectTreeNode::subTreeByNodeID( $params, $rootNodeId );
        $return = array();
        foreach( $nodes as $node )
        {
            $return[] = ezpContent::fromNode( $node );
        }
        return $return;
    }

    private static $defaultRootNode = 1;
}
?>