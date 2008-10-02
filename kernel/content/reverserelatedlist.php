<?php
//
// Created on: <23-Sen-2005 13:42:58 vd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

//include_once( "kernel/classes/ezcontentobject.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );
require_once( "kernel/common/template.php" );
//include_once( 'kernel/classes/ezpreferences.php' );

$http = eZHTTPTool::instance();

$Module = $Params['Module'];
$NodeID = $Params['NodeID'];

if ( $http->hasPostVariable( "BackButton" ) )
{
    $userRedirectURI = $http->sessionVariable( 'userRedirectURIReverseRelatedList' );
    $http->removeSessionVariable( 'userRedirectURIReverseRelatedList' );
    return $Module->redirectTo( $userRedirectURI );
}

if ( !isset( $Offset ) )
    $Offset = false;

$children_list_limit = eZPreferences::value( "reverse_children_list_limit" );

switch  ( $children_list_limit )
{
    case 0:
        $pageLimit = 10;
        break;
    case 1:
        $pageLimit = 10;
        break;
    case 2:
        $pageLimit = 25;
        break;
    case 3:
        $pageLimit = 50;
        break;
    default:
        $pageLimit = 10;
        break;
}

if ( $Offset < $pageLimit )
    $Offset = 0;

$requestedURI = '';
$userRedirectURI = '';
$requestedURI = $GLOBALS['eZRequestedURI'];
if ( $requestedURI instanceof eZURI )
{
    $userRedirectURI = $requestedURI->uriString( true );
}
$http->setSessionVariable( 'userRedirectURIReverseObjects', $userRedirectURI );

$db = eZDB::instance();

$deleteIDArray = array();

$contentObjectTreeNode = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$contentObjectTreeNode )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$path_strings = '( ';
$path_strings2 = '( ';
$except_path_strings = '';
$i = 0;

// Create WHERE section
$path_strings .= "tree.path_string like '$contentObjectTreeNode->PathString%'";
$path_strings2 .= "tree2.path_string like '$contentObjectTreeNode->PathString%'";

$path_strings_where = $path_strings2." ) ";
$path_strings .= " )";

// Total count of sub items
$countOfItems = $db->arrayQuery( "SELECT COUNT( DISTINCT( tree.node_id ) ) AS count

                                  FROM  ezcontentobject_tree tree,  ezcontentobject obj,
                                        ezcontentobject_link link LEFT JOIN ezcontentobject_tree tree2
                                        ON link.from_contentobject_id = tree2.contentobject_id
                                  WHERE $path_strings
                                        and link.to_contentobject_id = tree.contentobject_id
                                        and obj.id = link.from_contentobject_id
                                        and obj.current_version = link.from_contentobject_version
                                        and not ( $path_strings_where )
                            " );

$rowsCount = 0;
if ( isset( $countOfItems[0] ) )
    $rowsCount = $countOfItems[0]['count'];

if ( $rowsCount > 0 )
{
    // Select all elements having reverse relations. And ignore those items that don't relate to objects other than being removed.
    $rows = $db->arrayQuery( "SELECT   DISTINCT( tree.node_id )
                              FROM     ezcontentobject_tree tree,  ezcontentobject obj,
                                       ezcontentobject_link link LEFT JOIN ezcontentobject_tree tree2
                                       ON link.from_contentobject_id = tree2.contentobject_id
                              WHERE    $path_strings
                                       and link.to_contentobject_id = tree.contentobject_id
                                       and obj.id = link.from_contentobject_id
                                       and obj.current_version = link.from_contentobject_version
                                       and not ( $path_strings_where )

                                ", array( 'limit' => $pageLimit,
                                          'offset' => $Offset ) );
}
else
{
    $rows = array();
}

$childrenList = array(); // Contains children of Nodes from $deleteIDArray

// Fetch number of reverse related objects for each of the items being removed.
$reverselistCountChildrenArray = array();
foreach( $rows as $child )
{
    $contentObject = eZContentObject::fetchByNodeID( $child['node_id'] );
    $contentObject_ID = $contentObject->attribute('id');
    $reverseObjectCount = $contentObject->reverseRelatedObjectCount( false, false, 1 );
    $reverselistCountChildrenArray[$contentObject_ID] = $reverseObjectCount;
    $childrenList[] = eZContentObjectTreeNode::fetch( $child['node_id'] );
}

$contentObjectName = $contentObjectTreeNode->attribute('name');
$viewParameters = array( 'offset' => $Offset );

$tpl = templateInit();

$tpl->setVariable( 'children_list', $childrenList );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'children_count', $rowsCount );
$tpl->setVariable( 'content_object_name', $contentObjectName );
$tpl->setVariable( 'reverse_list_count_children_array', $reverselistCountChildrenArray );
$tpl->setVariable( 'reverse_list_children_count', count( $reverselistCountChildrenArray ) );
$tpl->setVariable( 'node_id',  $NodeID );

$Result = array();

$contentObject = $contentObjectTreeNode->attribute( 'object' );
if ( $contentObject )
{
    $section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
    if ( $section )
    {
        $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
        if ( $navigationPartIdentifier )
        {
            $Result['navigation_part'] = $navigationPartIdentifier;
        }
    }
}

$Result['content'] = $tpl->fetch( "design:content/reverserelatedlist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', "\"$contentObjectName\": Sub items that are used by other objects" ) ) );

?>
