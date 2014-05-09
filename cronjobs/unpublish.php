<?php
/**
 * File containing the unpublish.php cronjob
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$ini = eZINI::instance( 'content.ini' );
$unpublishClasses = $ini->variable( 'UnpublishSettings','ClassList' );

$rootNodeIDList = $ini->variable( 'UnpublishSettings','RootNodeList' );

$currentDate = time();

foreach( $rootNodeIDList as $nodeID )
{
    $rootNode = eZContentObjectTreeNode::fetch( $nodeID );

    $articleNodeArray = $rootNode->subTree( 
        array( 
            'ClassFilterType' => 'include',
            'ClassFilterArray' => $unpublishClasses,
            'Limitation' => array()
        ) 
    );

    foreach ( $articleNodeArray as $articleNode )
    {
        $article = $articleNode->attribute( 'object' );
        $dataMap = $article->attribute( 'data_map' );

        $dateAttribute = $dataMap['unpublish_date'];

        if ( $dateAttribute === null )
            continue;

        $date = $dateAttribute->content();
        $articleRetractDate = $date->attribute( 'timestamp' );
        if ( $articleRetractDate > 0 && $articleRetractDate < $currentDate )
        {
            // Clean up content cache
            eZContentCacheManager::clearContentCacheIfNeeded( $article->attribute( 'id' ) );

            $article->removeThis( $articleNode->attribute( 'node_id' ) );
        }
    }
}


?>
