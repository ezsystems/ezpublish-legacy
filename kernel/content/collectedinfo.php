<?php
//
// Created on: <25-Sep-2003 12:47:21 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'kernel/common/template.php' );

$module =& $Params['Module'];

$nodeID = $Params['NodeID'];
if ( !$nodeID )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
$node =& eZContentObjectTreeNode::fetch( $nodeID );
if ( !$node )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
$object =& $node->attribute( 'object' );
if ( !$object )
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
if ( !$object->attribute( 'can_read' ) )
    return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

// $http =& eZHTTPTool::instance();

$tpl =& templateInit();

$icMap = array();
if ( eZHTTPTool::hasSessionVariable( 'InformationCollectionMap' ) )
    $icMap = eZHTTPTool::sessionVariable( 'InformationCollectionMap' );
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

$section =& eZSection::fetch( $object->attribute( 'section_id' ) );
if ( $section )
    $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                      array( 'node', $node->attribute( 'node_id' ) ),
                      array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                      array( 'class', $object->attribute( 'contentclass_id' ) ),
                      array( 'navigation_part_identifier', $navigationPartIdentifier ),
                      array( 'depth', $node->attribute( 'depth' ) ),
                      array( 'url_alias', $node->attribute( 'url_alias' ) )
                      ) );


$Result['content'] =& $tpl->fetch( 'design:content/collectedinfo/' . $informationCollectionTemplate . '.tpl' );

$title = $object->attribute( 'name' );
if ( $tpl->hasVariable( 'title' ) )
    $title = $tpl->variable( 'title' );

// create path
$parents =& $node->attribute( 'path' );

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

$Result['path'] =& $path;
$Result['title_path'] =& $titlePath;

?>
