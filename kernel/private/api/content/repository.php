<?php
/**
 * File containing the ezpContentRepository class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
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
        $fetchParams = self::translateFetchParams( $criteria );
        $nodes = eZContentObjectTreeNode::subTreeByNodeID( $fetchParams->params, $fetchParams->rootNodeId );
        $return = array();
        foreach( $nodes as $node )
        {
            $return[] = ezpContent::fromNode( $node );
        }
        return $return;
    }

    /**
     * Returns node count using a given set of criteria
     * @param ezpContentCriteria $criteria
     * @return int
     */
    public static function queryCount( ezpContentCriteria $criteria )
    {
        $fetchParams = self::translateFetchParams( $criteria );
        $count = eZContentObjectTreeNode::subTreeCountByNodeID( $fetchParams->params, $fetchParams->rootNodeId );
        return $count;
    }

    /**
     * We have a set of content criteria in $criteria
     * These criteria provide us with:
     * - location conditions (part of subtree X, not part of subtree Y, etc)
     * - content based conditions (content class, attribute value, etc)
     *
     * Based on this, we need to end up calling eZContentObjectTreeNode and return the resulting objects
     * as an ezpContentList, a countable iterator that iterates over ezpContent objects
     *
     * This method will make the translation between ezpContentCriteria and acceptable fetch params
     * @return stdClass Object with 2 properties :
     *                      - rootNodeId => array of parent node Ids
     *                      - params => array of translated fetch params
     */
    protected static function translateFetchParams( ezpContentCriteria $criteria )
    {
        $ret = new stdClass();
        $ret->rootNodeId = array();
        $ret->params = array();

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

            switch( $translatedCriteria['type'] )
            {
                case 'location':
                    $ret->rootNodeId[] = $translatedCriteria['value'];
                    break;

                case 'param':
                    // This implementation is really ugly... Need to change it
                    foreach( $translatedCriteria['name'] as $idx => $criteriaName )
                    {
                        $ret->params[$criteriaName] = $translatedCriteria['value'][$idx];
                    }
                    break;
            }
        }

        // @TODO : Handle deny criterias
        // foreach( $criteria->deny as $denyCriteria ) {}
        return $ret;
    }

    /**
     * Fetches an ezpContent based on an identifier object.
     *
     * The content will be fetched depending on what aspects of the identifier object have been configured.
     *
     * Example 1:
     * <code>
     * $identifier = new ezpContentIdentifier;
     * $identifier->objectId = 123;
     * $content = ezpContentRepository::fetch( $identifier );
     * </code>
     *
     * Example 2:
     * <code>
     * $identifier = new ezpContentIdentifier;
     * $identifier->nodeId = 456;
     * $content = ezpContentRepository::fetch( $identifier );
     * </code>
     *
     * Further evolutions on ezpContentIdentifier will allow for unified fetching without adding new methods
     *
     * @param ezpContentIdentifier $identifier
     * @return ezpContent
     */
    public static function fetch( ezpContentIdentifier $identifier )
    {

    }

    private static $defaultRootNode = 1;
}
?>