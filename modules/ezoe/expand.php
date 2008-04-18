<?php
//
// Created on: <15-Aug-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ systems AS
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
include_once( 'extension/ezoe/classes/ezajaxcontent.php' );


$nodeID      = (int) $Params['NodeID'];
$limit       = isset( $Params['Limit'] ) ? $Params['Limit'] : 20;
$offset      = (int) $Params['Offset'];
$http        = eZHTTPTool::instance();

if ( !$nodeID )
{
    header("HTTP/1.0 500 Internal Server Error");
    echo ezi18n( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ParentNodeID' ) );
    eZExecution::cleanExit();
}

$node = eZContentObjectTreeNode::fetch( $nodeID );

if ( !$node instanceOf eZContentObjectTreeNode )
{
    header("HTTP/1.0 500 Internal Server Error");
    echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ParentNodeID', '%value' => $nodeID ) );
    eZExecution::cleanExit();
}

$params = array( 'Depth' => 1,
        'Limit'            => $limit,
        'Offset'           => $offset,
        'SortBy'           => $node->attribute( 'sort_array' ),
        'DepthOperator'    => 'eq',
        'AsObject'         => true
);

if ( $http->hasPostVariable( 'ClassFilterArray' ) && $http->postVariable( 'ClassFilterArray' ) !== '' )
{
    $params['ClassFilterType']  = 'include';
    $params['ClassFilterArray'] = $http->postVariable( 'ClassFilterArray' );
}

if ( $http->hasPostVariable( 'SortBy' ) && $http->postVariable( 'SortBy' ) !== '' )
{
    $params['SortBy'] = $http->postVariable( 'SortBy' );
}


$nodeArray  = $node->subTree( $params );
$count      = $node->subTreeCount( $params );
$list       = '[]';

if ( $nodeArray )
{
	$list = eZAjaxContent::encode( $nodeArray, array( 'fetchChildrenCount' => true ) );
}


echo '{list:' . $list . 
     ",\r\ncount:" . count( $nodeArray ) .
     ",\r\ntotal_count:" . $count .
     ",\r\nnode:" . eZAjaxContent::encode( $node, array('fetchPath' => true ) ) .
     ",\r\noffset:" . $offset .
     ",\r\nlimit:" . $limit .
     "\r\n};";


eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>