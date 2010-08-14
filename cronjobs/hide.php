<?php
/**
 * File containing the hide.php cronjob.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

$ini = eZINI::instance( 'content.ini' );
$rootNodeIDList = $ini->variable( 'HideSettings','RootNodeList' );
$hideAttributeArray = $ini->variable( 'HideSettings', 'HideDateAttributeList' );

$currrentDate = time();

$offset = 0;
$limit = 50;

eZINI::instance()->setVariable( 'SiteAccessSettings', 'ShowHiddenNodes', 'false' );

$hiddenNodesParams = array(
    'LoadDataMap' => false,
    'Limit' => $limit,
    'Offset' => $offset,
    'SortBy' => array( array( 'published', true ) ) );

$newMemoryUsage = $oldMemoryUsage = 0;
foreach( $rootNodeIDList as $nodeID )
{
    $rootNode = eZContentObjectTreeNode::fetch( $nodeID );
    if ( !$isQuiet )
    {
        $cli->output( 'Hiding content of node "' . $rootNode->attribute( 'name' ) . '" (' . $nodeID . ')' );
        $cli->output();
    }
    foreach ( $hideAttributeArray as $hideClass => $attributeIdentifier )
    {
        $offset = 0;
        $countParams = array( 'ClassFilterType' => 'include',
                              'ClassFilterArray' => array( $hideClass ),
                              'Limitation' => array(),
                              'AttributeFilter' => array( 'and',
                                  array( "{$hideClass}/{$attributeIdentifier}", '<=', $currrentDate ),
                                  array( "{$hideClass}/$attributeIdentifier", '>', 0 ) ) );

        $nodeArrayCount = $rootNode->subTreeCount( $countParams );
        if ( $nodeArrayCount > 0 )
        {
            if ( !$isQuiet )
            {
                $cli->output( "Hiding {$nodeArrayCount} node(s) of class {$hideClass}." );
            }

            do
            {
                $nodeArray = $rootNode->subTree( $hiddenNodesParams );

                foreach ( $nodeArray as $node )
                {
                    eZContentObjectTreeNode::hideSubTree( $node );
                    if ( !$isQuiet )
                    {
                        $cli->output( 'Hiding node: "' . $node->attribute( 'name' ) . '" (' . $node->attribute( 'node_id' ) . ')' );
                    }
                }
                // clear memory after every batch
                eZContentObject::clearCache();
            } while( is_array( $nodeArray ) and count( $nodeArray ) > 0 );

            if ( !$isQuiet )
            {
                $cli->output();
            }
        }
        else
        {
            if ( !$isQuiet )
            {
                $cli->output( "Nothing to hide." );
            }
        }
    }
    if ( !$isQuiet )
    {
        $cli->output();
    }
}

?>
