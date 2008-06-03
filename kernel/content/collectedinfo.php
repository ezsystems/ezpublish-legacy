<?php
//
// Created on: <25-Sep-2003 12:47:21 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

//include_once( 'kernel/classes/ezinformationcollection.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
require_once( 'kernel/common/template.php' );

$module = $Params['Module'];

$nodeID = $Params['NodeID'];
if ( !$nodeID )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$node = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$node )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

$object = $node->attribute( 'object' );
if ( !$object )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
if ( !$object->attribute( 'can_read' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$http = eZHTTPTool::instance();

$tpl = templateInit();

$icMap = array();
if ( $http->hasSessionVariable( 'InformationCollectionMap' ) )
    $icMap = $http->sessionVariable( 'InformationCollectionMap' );
$icID = false;
if ( isset( $icMap[$object->attribute( 'id' )] ) )
    $icID = $icMap[$object->attribute( 'id' )];

$informationCollectionTemplate = eZInformationCollection::templateForObject( $object );
$attributeHideList = eZInformationCollection::attributeHideList();

$tpl->setVariable( 'node_id', $nodeID );
$tpl->setVariable( 'collection_id', $icID );
$tpl->setVariable( 'node', $node );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'attribute_hide_list', $attributeHideList );
$tpl->setVariable( 'error', false );

$section = eZSection::fetch( $object->attribute( 'section_id' ) );
if ( $section )
    $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                      array( 'node', $node->attribute( 'node_id' ) ),
                      array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                      array( 'class', $object->attribute( 'contentclass_id' ) ),
                      array( 'navigation_part_identifier', $navigationPartIdentifier ),
                      array( 'depth', $node->attribute( 'depth' ) ),
                      array( 'url_alias', $node->attribute( 'url_alias' ) ),
                      array( 'class_identifier', $node->attribute( 'class_identifier' ) )
                      ) );


$Result['content'] = $tpl->fetch( 'design:content/collectedinfo/' . $informationCollectionTemplate . '.tpl' );

$title = $object->attribute( 'name' );
if ( $tpl->hasVariable( 'title' ) )
    $title = $tpl->variable( 'title' );

// create path
$parents = $node->attribute( 'path' );

$path = array();
$titlePath = array();
foreach ( $parents as $parent )
{
    $path[] = array( 'text' => $parent->attribute( 'name' ),
                     'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                     'url_alias' => $parent->attribute( 'url_alias' ),
                     'node_id' => $parent->attribute( 'node_id' )
                     );
}
$path[] = array( 'text' => $object->attribute( 'name' ),
                 'url' => '/content/view/full/' . $node->attribute( 'node_id' ),
                 'url_alias' => $node->attribute( 'url_alias' ),
                 'node_id' => $node->attribute( 'node_id' ) );

array_shift( $parents );
foreach ( $parents as $parent )
{
    $titlePath[] = array( 'text' => $parent->attribute( 'name' ),
                          'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                          'url_alias' => $parent->attribute( 'url_alias' ),
                          'node_id' => $parent->attribute( 'node_id' )
                          );
}
$titlePath[] = array( 'text' => $title,
                      'url' => '/content/view/full/' . $node->attribute( 'node_id' ),
                      'url_alias' => $node->attribute( 'url_alias' ),
                      'node_id' => $node->attribute( 'node_id' ) );

$Result['path'] = $path;
$Result['title_path'] = $titlePath;

?>
