<?php

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'lib/ezutils/classes/ezuri.php' );
include_once( 'lib/ezutils/classes/ezsys.php' );
include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
include_once( 'kernel/classes/ezclusterfilehandler.php' );

define( 'MAX_AGE', 86400 );

function washJS( $string )
{
    return str_replace( array( "\\", "\"", "'"), array( "\\\\", "\\042", "\\047" ), $string );
}

function arrayToJSON( $array )
{
    if ( $array )
    {
        $result = array();
        $resultDict = array();
        $isDict = false;
        $index = 0;
        foreach( $array as $key => $value )
        {
            if ( $key != $index++ )
            {
                $isDict = true;
            }

            if ( is_array( $value ) )
            {
                $value = arrayToJSON( $value );
            }
            else if ( !is_numeric( $value ) )
            {
                $value = '"' . washJS( $value ) . '"';
            }

            $result[] = $value;
            $resultDict[] = '"' . washJS( $key ) . '":' . $value;
        }
        if ( $isDict )
        {
            return '{' . implode( $resultDict, ',' ) . '}';
        }
        else
        {
            return '[' . implode( $result, ',' ) . ']';
        }
    }
    else
    {
        return '[]';
    }
}

while ( @ob_end_clean() );

if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified' );

    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: max-age=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) . ' GMT' );
    header( 'Pragma: ' );

    eZExecution::cleanExit();
}

$nodeID = (int) $_GET['node_id'];

$siteINI =& eZINI::instance();
$contentstructuremenuINI =& eZINI::instance( 'contentstructuremenu.ini' );

if ( $contentstructuremenuINI->variable( 'TreeMenu', 'Dynamic' ) != 'enabled' )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden' );

    eZExecution::cleanExit();
    return;
}

$accessName = false;
if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
{
    $accessName = $GLOBALS['eZCurrentAccess']['name'];
}

$user =& eZUser::currentUser();
$limitedAssignmentValueList = implode( ',', $user->limitValueList() );
$roleList = implode( ',', $user->roleIDList() );

$showHidden = $siteINI->variable( 'SiteAccessSettings', 'ShowHiddenNodes' );

// TODO: should we use the timestamp from the $_GET['expiry']?

$keyString = eZSys::ezcrc32( 'content_structure_' . 
                             $nodeID . '_' .
                             ( ( $showHidden )? 'hidden_': '' ) .
                             $roleList . '_' .
                             $limitedAssignmentValueList . '_' .
                             $accessName );
$cacheFilename = $keyString . '.cache';
$cacheDir = eZSys::cacheDirectory() . '/template-block' ;
$nodeIDString = (string) $nodeID;
for ( $i = 0; $i < strlen( $nodeIDString ); $i++ )
{
    $cacheDir .= '/' . $nodeIDString[$i];
}
$cacheDir .= '/cache/' . $cacheFilename[0] . '/' . $cacheFilename[1] . '/' . $cacheFilename[2];
$cacheFilename = $cacheDir . '/' . $cacheFilename;

$cacheFile = eZClusterFileHandler::instance( $cacheFilename );
if ( $cacheFile->exists() )
{
    $handler =& eZExpiryHandler::instance();
    $globalExpiryTime = -1;
    if ( $handler->hasTimestamp( 'template-block-cache' ) )
    {
        $globalExpiryTime = $handler->timestamp( 'template-block-cache' );
    }

    if ( $globalExpiryTime == -1 || $cacheFile->mtime() > $globalExpiryTime )
    {
        $cacheFileContent = $cacheFile->fetchContents();
        $httpCharset = eZTextCodec::httpCharset();

        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
        header( 'Cache-Control: max-age=' . MAX_AGE );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $_GET['modified'] ) . ' GMT' );
        header( 'Pragma: ' );
        header( 'Content-Type: application/json; charset=' . $httpCharset );
        header( 'Content-Length: ' . strlen( $cacheFileContent ) );

        echo $cacheFileContent;

        eZExecution::cleanExit();
        return;
    }
}

$node = eZContentObjectTreeNode::fetch( $nodeID );

if ( !$node )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found' );
}
else if ( !$node->canRead() )
{
    $jsonText= arrayToJSON( array(
        'error_code' => -1,
        'error_message' => ezi18n( 'kernel/content', 'You do not have enough rights to access the requested node' ),
        'node_id' => $nodeID,
    ) );

    header( 'Content-Type: application/json' );
    header( 'Content-Length: '.strlen( $jsonText ) );

    echo $jsonText;
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

    foreach ( $children as $child )
    {
        $childObject = $child->object();
        $childResponse = array();
        $childResponse['node_id'] = $child->NodeID;
        $childResponse['object_id'] = $child->ContentObjectID;
        $object =& $child->object();
        $childResponse['class_id'] = $object->ClassID;
        $childResponse['has_children'] = ( $child->subTreeCount( $conditions ) )? 1: 0;
        $childResponse['name'] = $child->getName();
        $childResponse['url'] = $child->url();
        eZURI::transformURI( $childResponse['url'] );
        $childResponse['modified_subnode'] = $child->ModifiedSubNode;
        $childResponse['languages'] = $childObject->availableLanguages();
        $childResponse['is_hidden'] = $child->IsHidden;
        $childResponse['is_invisible'] = $child->IsInvisible;
        if ( $createHereMenu == 'full' )
        {
            $childResponse['class_list'] = array();
            foreach ( $child->canCreateClassList() as $class )
            {
                $childResponse['class_list'][] = $class['id'];
            }
        }
        $response['children'][] = $childResponse;

        unset( $object );
        eZContentObject::clearCache();
    }

    $httpCharset = eZTextCodec::httpCharset();

    $jsonText= arrayToJSON( $response );

    $codec = eZTextCodec::instance( $httpCharset, 'unicode' );
    if ( $codec )
    {
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
    
        $httpCharset = 'iso-8859-1';
    }

    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: cache, max-age=' . MAX_AGE . ', post-check=' . MAX_AGE . ', pre-check=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $node->ModifiedSubNode ) . ' GMT' );
    header( 'Pragma: cache' );
    header( 'Content-Type: application/json; charset=' . $httpCharset );
    header( 'Content-Length: '.strlen( $jsonText ) );

    echo $jsonText;

    if ( $cacheFile )
    {
        $cacheFile->storeContents( $jsonText, 'template-block' );
    }
}

eZExecution::cleanExit();

?>