<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

if ( !defined( 'MAX_AGE' ) )
{
    define( 'MAX_AGE', 86400 );
}

// Ensure to deactivate pagelayout and debug output in case we're going through index_tree_menu.php
$Result['pagelayout'] = false;
eZDebug::updateSettings(
    array(
         'debug-enabled' => false
    )
);

// We use aggressive browser caching by default, by manually set appropriate HTTP headers.
// This behavior can be deactivated by setting 'use-cache-headers' user parameter to false.
$useCacheHeaders = isset( $UserParameters['use-cache-headers'] ) ? (bool)$UserParameters['use-cache-headers'] : true;
if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) && $useCacheHeaders )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: max-age=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) . ' GMT' );
    header( 'Pragma: ' );

    $Result['content'] = '';
    return;
}

$nodeID = (int) $Params['NodeID'];

$siteINI = eZINI::instance();
$contentstructuremenuINI = eZINI::instance( 'contentstructuremenu.ini' );

if ( $contentstructuremenuINI->variable( 'TreeMenu', 'Dynamic' ) != 'enabled' )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden' );
    $Result['content'] = json_encode(
        array(
             'error'    => ezpI18n::tr( 'kernel/content/treemenu', 'Cannot display the treemenu because it is disabled.' ),
             'code'     => 403
        )
    );
    return;
}

$accessName = false;
if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
{
    $accessName = $GLOBALS['eZCurrentAccess']['name'];
}

$user = eZUser::currentUser();
$limitedAssignmentValueList = implode( ',', $user->limitValueList() );
$roleList = implode( ',', $user->roleIDList() );

$showHidden = $siteINI->variable( 'SiteAccessSettings', 'ShowHiddenNodes' ) == 'true';

$handler = false;
if ( $contentstructuremenuINI->variable( 'TreeMenu', 'UseCache' ) == 'enabled' and
     $siteINI->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled' )
{
    list( $handler, $cacheFileContent ) = eZTemplateCacheBlock::retrieve( array(
        'content_structure',
        $nodeID,
        $showHidden,
        $user->roleIDList(),
        $user->limitValueList(),
        $accessName ), $nodeID, -1 );

    if ( !( $cacheFileContent  instanceof eZClusterFileFailure ) )
    {
        if ( $useCacheHeaders )
        {
            header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
            header( 'Cache-Control: max-age=' . MAX_AGE );
            header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $Params['Modified'] ) . ' GMT' );
            header( 'Pragma: ' );
            header( 'Content-Type: application/json' );
            header( 'Content-Length: ' . strlen( $cacheFileContent ) );
        }

        $Result['content'] = $cacheFileContent;
        return;
    }
}

$node = eZContentObjectTreeNode::fetch( $nodeID );

if ( !$node )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found' );
    $Result['content'] = '';
}
else if ( !$node->canRead() )
{
    $jsonText= json_encode(
        array(
            'error_code' => -1,
            'error_message' => ezpI18n::tr( 'kernel/content', 'You do not have enough rights to access the requested node' ),
            'node_id' => $nodeID,
        )
    );

    header( $_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden' );
    header( 'Content-Type: application/json' );
    header( 'Content-Length: '.strlen( $jsonText ) );

    $Result['content'] = $jsonText;
}
else
{
    $conditions = array( 'Depth' => '1',
                         'SortBy' => $node->sortArray() );

    $showClasses = $contentstructuremenuINI->variable( 'TreeMenu', 'ShowClasses' );
    if ( $showClasses )
    {
        $conditions['ClassFilterType'] = 'include';
        $conditions['ClassFilterArray'] = $showClasses;
    }

    $limit = $contentstructuremenuINI->variable( 'TreeMenu', 'MaxNodes' );
    if ( $limit )
    {
        $conditions['Limit'] = $limit;
    }

    $sortBy = $contentstructuremenuINI->variable( 'TreeMenu', 'SortBy' );
    if ( $sortBy && $sortBy != 'false' )
    {
        if ( !is_array( $sortBy ) )
        {
            $sortBy = array( $sortBy );
        }

        $sortArray = array();
        foreach ( $sortBy as $sortCondition )
        {
            $conditionArray = explode( '/', $sortCondition, 2 );
            if ( isset( $conditionArray[1] ) && $conditionArray[1] == 'descending' )
            {
                $conditionArray[1] = false;
            }
            else
            {
                $conditionArray[1] = true;
            }
            $sortArray[] = $conditionArray;
        }

        $conditions['SortBy'] = $sortArray;
    }

    $children = $node->subTree( $conditions );

    $createHereMenu = $contentstructuremenuINI->variable( 'TreeMenu', 'CreateHereMenu' );

    $response = array();
    $response['error_code'] = 0;
    $response['node_id'] = $node->NodeID;
    $response['children_count'] = count( $children );
    $response['children'] = array();

    $httpCharset = eZTextCodec::httpCharset();

    foreach ( $children as $child )
    {
        $childObject = $child->object();
        $childResponse = array();
        $childResponse['node_id'] = (int)$child->NodeID;
        $childResponse['object_id'] = (int)$child->ContentObjectID;
        $object = $child->object();
        $childResponse['class_id'] = (int)$object->ClassID;
        $childResponse['has_children'] = $child->subTreeCount( $conditions ) > 0;
        $childResponse['name'] = htmlentities( $child->getName(), ENT_COMPAT, $httpCharset );
        $childResponse['url'] = $child->url();
        // force system url on empty urls (root node)
        if ( $childResponse['url'] === '' )
            $childResponse['url'] = 'content/view/full/' . $childResponse['node_id'];
        eZURI::transformURI( $childResponse['url'] );
        $childResponse['modified_subnode'] = (int)$child->ModifiedSubNode;
        $childResponse['languages'] = $childObject->availableLanguages();
        $childResponse['is_hidden'] = (bool)$child->IsHidden;
        $childResponse['is_invisible'] = (bool)$child->IsInvisible;
        if ( $createHereMenu == 'full' )
        {
            $childResponse['class_list'] = array();
            foreach ( $child->canCreateClassList() as $class )
            {
                $childResponse['class_list'][] = (int)$class['id'];
            }
        }
        $response['children'][] = $childResponse;

        unset( $object );
        eZContentObject::clearCache();
    }

    $jsonText= json_encode( $response );

    $codec = eZTextCodec::instance( $httpCharset, 'unicode' );
    $jsonTextArray = $codec->convertString( $jsonText );
    $jsonText = '';
    foreach ( $jsonTextArray as $character )
    {
        if ( $character < 128 )
        {
            $jsonText .= chr( $character );
        }
        else
        {
            $jsonText .= '\u' . str_pad( dechex( $character ), 4, '0000', STR_PAD_LEFT );
        }
    }

    if ( $useCacheHeaders )
    {
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
        header( 'Cache-Control: cache, max-age=' . MAX_AGE . ', post-check=' . MAX_AGE . ', pre-check=' . MAX_AGE );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $node->ModifiedSubNode ) . ' GMT' );
        header( 'Pragma: cache' );
        header( 'Content-Type: application/json; charset=' . $httpCharset );
        header( 'Content-Length: '. mb_strlen( $jsonText ) );
    }

    $Result['lastModified'] = new DateTime( "@$node->ModifiedSubNode" );
    $Result['content'] = $jsonText;

    if ( $handler )
    {
        $handler->storeCache( array( 'scope' => 'template-block',
                                     'binarydata' => $jsonText ) );
    }
}

?>
