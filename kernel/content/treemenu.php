<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'lib/ezutils/classes/ezuri.php' );
//include_once( 'lib/ezutils/classes/ezsys.php' );
//include_once( 'kernel/classes/ezclusterfilehandler.php' );
//include_once( 'lib/eztemplate/classes/eztemplatecacheblock.php' );
//include_once( 'kernel/classes/ezclusterfilefailure.php' );

eZExpiryHandler::registerShutdownFunction();

if ( !defined( 'MAX_AGE' ) )
{
    define( 'MAX_AGE', 86400 );
}

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

$siteINI = eZINI::instance();
$contentstructuremenuINI = eZINI::instance( 'contentstructuremenu.ini' );

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
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
        header( 'Cache-Control: max-age=' . MAX_AGE );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $_GET['modified'] ) . ' GMT' );
        header( 'Pragma: ' );
        header( 'Content-Type: application/json' );
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
        $object = $child->object();
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

    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + MAX_AGE ) . ' GMT' );
    header( 'Cache-Control: cache, max-age=' . MAX_AGE . ', post-check=' . MAX_AGE . ', pre-check=' . MAX_AGE );
    header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $node->ModifiedSubNode ) . ' GMT' );
    header( 'Pragma: cache' );
    header( 'Content-Type: application/json' );
    header( 'Content-Length: '.strlen( $jsonText ) );

    echo $jsonText;

    if ( $handler )
    {
        $handler->storeCache( array( 'scope' => 'template-block',
                                     'binarydata' => $jsonText ) );
    }
}

eZExecution::cleanExit();

?>
