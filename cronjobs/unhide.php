<?php

/**
 * Extended to select only the invisible nodes, otherwise iterates over all nodes, not only invisible ones
 * NOTE: This will always rely on existence of unpublish_date in configured doctype, which will be added to the query
 *       unpublish_date > 0 && unpublish_date > currentDate
 *       Otherwise the job runs in kind fo endlessloop with hide job!
 */

// get values from content.ini
$ini = eZINI::instance( 'content.ini' );
// get starting node (go all trough the tree begining from this node)
$rootNodeIDList = $ini->variable( 'unHideSettings','RootNodeList' );
// get the attribute name (date and hour) to make comparisons
$unhideAttributeArray = $ini->variable( 'unHideSettings', 'unHideDateAttributeList' );

// get current date
$currrentDate = time();

$offset = 0;
$limit = 50;

// go trough all the tree
foreach( $rootNodeIDList as $nodeID )
{
    $rootNode = eZContentObjectTreeNode::fetch( $nodeID );

    if ( !$isQuiet )
    {
        $cli->output( 'Starting unhide cronjob at node "' . $rootNode->attribute( 'name' ) . '" (' . $nodeID . ')' );
        $cli->output();
    }

    foreach ( $unhideAttributeArray as $unhideClass => $attributeIdentifier )
    {
        $offset = 0;
        $counter = 0;

        // if actual date exists adn actual date is bigger or equal to the set date then show it (unhide)
        // take into consideration hidden nodes also (as they could be hidden and I need to un hide them)

        $params = array( 'ClassFilterType' => 'include',
            'ClassFilterArray' => array( $unhideClass ),
            'Limitation' => array(),
            'IgnoreVisibility' => true, //hiden nodes must be fetched
            'AttributeFilter' => array( 'and',
                array( $unhideClass . '/' . $attributeIdentifier, '<=', $currrentDate ),
                array( $unhideClass . '/' . $attributeIdentifier, '>', 0 ),
                array( $unhideClass . '/' . 'unpublish_date', '>', $currrentDate ),
                array( 'visibility', '=', 0 ),
                array( 'hidden', '=', 1 )
            )
        );

        // Calling the processInner for objects with: publish_date in past AND unpublish_date in future
        processInner($rootNode, $isQuiet, $cli, $limit, $offset, $counter, $params);

        // NOW processing objects: publish_date in past AND unpublish_date not set
        $params = array( 'ClassFilterType' => 'include',
            'ClassFilterArray' => array( $unhideClass ),
            'Limitation' => array(),
            'IgnoreVisibility' => true, //hiden nodes must be fetched
            'AttributeFilter' => array( 'and',
                array( $unhideClass . '/' . $attributeIdentifier, '<=', $currrentDate ),
                array( $unhideClass . '/' . $attributeIdentifier, '>', 0 ),
                array( $unhideClass . '/' . 'unpublish_date', '=', 0 ),
                array( 'visibility', '=', 0 ),
                array( 'hidden', '=', 1 )
            )
        );

        // Calling the processInner for objects with: publish_date in past AND unpublish_date in future
        processInner($rootNode, $isQuiet, $cli, $limit, $offset, $counter, $params);
    }
    if ( !$isQuiet )
    {
        $cli->output();
    }
}

/**
 * Handles the inner logic, as the fetch cannot handle correct and/or conditions like "date > currentDate or date == null"
 *
 * @param $limit
 * @param $offset
 * @param $params
 */
function processInner($rootNode, $isQuiet, $cli, $limit, $offset, $counter, $params) {
    $nodeArrayCount = $rootNode->subTreeCount( $params );

    if ( $nodeArrayCount > 0 )
    {
        do
        {
            $params['LoadDataMap'] = false;
            $params['Limit'] = $limit;
            $params['Offset'] = $offset;
            $params['SortBy'] = array( array( 'published', true ) );
            $nodeArray = $rootNode->subTree( $params );

            foreach ( $nodeArray as $node )
            {
                eZContentObjectTreeNode::unhideSubTree( $node );
                eZSearch::updateNodeVisibility($node->attribute( 'node_id' ), 'hide');

                if ( !$isQuiet )
                {
                    $cli->output( 'UNHIDING node: "' . $node->attribute( 'name' ) . '" (' . $node->attribute( 'node_id' ) . ')' );
                }
            }
            $counter += $limit;
        } while( $counter < $nodeArrayCount and is_array( $nodeArray ) and count( $nodeArray ) > 0 );

        if ( !$isQuiet )
        {
            $cli->output();
        }
    }
}
?>