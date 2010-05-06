<?php

/**
 * ezjscServerFunctionsNode class definition that provide node fetch functions
 * 
 */
class ezjscServerFunctionsNode extends ezjscServerFunctions
{
    /**
     * Returns a subtree node items for given parent node
     *
     * Following parameters are supported:
     * ezjscnode::subtree::parent_node_id::limit::offset::sort::order
     * 
     * @static
     * @param mixed $args
     * @return array
     */
    public static function subTree( $args )
    {
        $parentNodeID = isset( $args[0] ) ? $args[0] : null;
        $limit = isset( $args[1] ) ? $args[1] : 25;
        $offset = isset( $args[2] ) ? $args[2] : 0;
        $sort = isset( $args[3] ) ? self::sortMap( $args[3] ) : 'published';
        $order = isset( $args[4] ) ? $args[4] : false;

        if ( !$parentNodeID )
        {
            throw new ezcBaseFunctionalityNotSupportedException( 'Fetch node list', 'Parent node id is not valid' );
        }

        $node = eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( !$node instanceOf eZContentObjectTreeNode )
        {
            throw new ezcBaseFunctionalityNotSupportedException( 'Fetch node list', "Parent node '$parentNodeID' is not valid" );
        }

        $params = array( 'Depth' => 1,
                         'Limit' => $limit,
                         'Offset' => $offset,
                         'SortBy' => array( array( $sort, $order ) ),
                         'DepthOperator' => 'eq',
                         'AsObject' => true );

       // fetch nodes and total node count
        $count = $node->subTreeCount( $params );
        if ( $count )
        {
            $nodeArray = $node->subTree( $params );
        }
        else
        {
            $nodeArray = false;
        }

        // generate json response from node list
        if ( $nodeArray )
        {
            $list = ezjscAjaxContent::nodeEncode( $nodeArray, array( 'formatDate' => 'shortdatetime', 
                                                                     'fetchSection' => true,
                                                                     'fetchCreator' => true,
                                                                     'fetchClassIcon' => true ), 'raw' );
        }
        else
        {
            $list = array();
        }

        return array( 'parent_node_id' => $parentNodeID,
                      'count' => count( $nodeArray ),
                      'total_count' => (int)$count,
                      'list' => $list,
                      'limit' => $limit,
                      'offset' => $offset,
                      'sort' => $sort,
                      'order' => $order );
    }

    /**
     * A helper function which maps sort keys from encoded JSON node
     * to supported values
     *
     * @static
     * @param string $sort
     * @return string
     */
    public static function sortMap( $sort )
    {
        switch ( $sort )
        {
            case 'modified_date':
                $sortKey = 'modified';
                break;
            case 'published_date':
                $sortKey = 'published';
                break;
            default:
                $sortKey = $sort;
        }

        return $sortKey;
    }

    /**
     * Updating priority sorting for given node
     * 
     * @param mixed $args
     * @return array
     */
    public static function updatePriority( $args )
    {
        $http = eZHTTPTool::instance();

        if ( !$http->hasPostVariable('ContentNodeID') 
                || !$http->hasPostVariable('PriorityID')
                    || !$http->hasPostVariable('Priority') )
        {
            return array();
        }

        $contentNodeID = $http->postVariable('ContentNodeID');
        $priorityArray = $http->postVariable('Priority');
        $priorityIDArray = $http->postVariable('PriorityID');
        
        if ( eZOperationHandler::operationIsAvailable( 'content_updatepriority' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'updatepriority',
                                                             array( 'node_id' => $contentNodeID,
                                                                    'priority' => $priorityArray,
                                                                    'priority_id' => $priorityIDArray ), null, true );
        }
        else
        {
            eZContentOperationCollection::updatePriority( $contentNodeID, $priorityArray, $priorityIDArray );
        }

        if ( $http->hasPostVariable( 'ContentObjectID' ) )
        {
            $objectID = $http->postVariable( 'ContentObjectID' );
            eZContentCacheManager::clearContentCache( $objectID );
        }
    }
}

?>