<?php
//
// Created on: <23-Sen-2005 13:42:58 vd>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/common/template.php" );
include_once( 'kernel/classes/ezpreferences.php' );

$http =& eZHTTPTool::instance();

$Module =& $Params['Module'];
$NodeID =& $Params['NodeID'];

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
$requestedURI =& $GLOBALS['eZRequestedURI'];
if ( get_class( $requestedURI ) == 'ezuri' )
{
    $userRedirectURI = $requestedURI->uriString( true );
}
$http->setSessionVariable( 'userRedirectURIReverseObjects', $userRedirectURI );

$db =& eZDB::instance();

$deleteIDArray = array();

$contentObjectTreeNode = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$contentObjectTreeNode )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$path_strings = '( ';
$path_strings2 = '( ';
$except_path_strings = '';
$i = 0;

// Create WHERE section
$path_strings .= "tree.path_string like '$contentObjectTreeNode->PathString%'";
$path_strings2 .= "tree2.path_string like '$contentObjectTreeNode->PathString%'";

$path_strings_where = $path_strings2." ) ";
$path_strings .= " )";

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
// Total count of sub items
$countOfItems = $db->arrayQuery( "SELECT COUNT( DISTINCT( tree.node_id ) ) count

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

$childrenList = array(); // Contains children of Nodes from $deleteIDArray

// Fetch number of reverse related objects for each of the items being removed.
$reverselistCountChildrenArray = array();
foreach( $rows as $child )
{
    $contentObject = eZContentObject::fetchByNodeID( $child['node_id'] );
    $contentObject_ID = $contentObject->attribute('id');
    $reverseObjectCount = $contentObject->reverseRelatedObjectCount( false, false, false );
    $reverselistCountChildrenArray[$contentObject_ID] = $reverseObjectCount;
    $childrenList[] = eZContentObjectTreeNode::fetch( $child['node_id'] );
}

$contentObjectName = $contentObjectTreeNode->attribute('name');
$viewParameters = array( 'offset' => $Offset );

$tpl =& templateInit();

$tpl->setVariable( 'children_list', $childrenList );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'children_count', $rowsCount );
$tpl->setVariable( 'content_object_name', $contentObjectName );
$tpl->setVariable( 'reverse_list_count_children_array', $reverselistCountChildrenArray );
$tpl->setVariable( 'reverse_list_children_count', count( $reverselistCountChildrenArray ) );
$tpl->setVariable( 'node_id',  $NodeID );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/reverserelatedlist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/content', "\"$contentObjectName\": Sub items that are used by other objects" ) ) );

?>
