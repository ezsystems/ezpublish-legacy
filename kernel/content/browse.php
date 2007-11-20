<?php
//
// Created on: <18-Jul-2002 10:55:01 bf>
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

//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

//include_once( 'kernel/classes/ezcontentbrowse.php' );

//include_once( 'lib/ezutils/classes/ezhttptool.php' );

require_once( 'kernel/common/template.php' );

$tpl = templateInit();
$http = eZHTTPTool::instance();

$browse = new eZContentBrowse();

$Offset = $Params['Offset'];

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$parents = array();

// We get node list when browse is execiuted from search engine ( "search in browse" functionality )
if ( isset( $Params['NodeList'] ) )
{
     $nodeList = $Params['NodeList']['SearchResult'];
     $nodeListCount = $Params['NodeList']['SearchCount'];
     $requestedURI = $Params['NodeList']['RequestedURI'];
     $requestedURISuffix = $Params['NodeList']['RequestedURISuffix'];
}
else
{

    if ( isset( $Params['NodeID'] ) && is_numeric( $Params['NodeID'] ) )
    {
        $NodeID = $Params['NodeID'];
        $browse->setStartNode( $NodeID );
    }

    $NodeID = $browse->attribute( 'start_node' );

    $node = eZContentObjectTreeNode::fetch( $NodeID );
    if ( !$node )
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

    if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    $object = $node->attribute( 'object' );
    if ( !$object )
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

    if ( !$object->attribute( 'can_read' ) || !$node->attribute( 'can_read' ) )
    {
        if ( !$node->attribute( 'children_count' ) )
        {
            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        }
    }
    $parents = $node->attribute( 'path' );
}

$cancelAction = trim( $browse->attribute( 'cancel_page' ) );
if ( $cancelAction == trim( $browse->attribute( 'from_page' ) ) )
{
    $cancelAction = false;
}

$res = eZTemplateDesignResource::instance();

$keyArray = array();
if ( $browse->hasAttribute( 'keys' ) )
{
    $attributeKeys = $browse->attribute( 'keys' );
    if ( is_array( $attributeKeys ) )
    {
        foreach ( $attributeKeys as $attributeKey => $attributeValue )
        {
            $keyArray[] = array( $attributeKey, $attributeValue );
        }
    }
    $res->setKeys( $keyArray );
}


$tpl->setVariable( 'browse', $browse );
$tpl->setVariable( 'csm_menu_item_click_action', '/content/browse' );
$tpl->setVariable( 'cancel_action', $cancelAction );

if ( isset( $nodeList ) )
{
    $tpl->setVariable( 'node_list', $nodeList );
    $tpl->setVariable( 'node_list_count', $nodeListCount );
    $tpl->setVariable( 'requested_uri', $requestedURI );
    $tpl->setVariable( 'requested_uri_suffix', $requestedURISuffix );
}
else
{
    $tpl->setVariable( 'main_node', $node );
    $tpl->setVariable( 'node_id', $NodeID );
    $tpl->setVariable( 'parents', $parents );
}

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}
$viewParameters = array( 'offset' => $Offset, 'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'path', false );

if (isset( $GLOBALS['eZDesignKeys']['section'] ))
{
    $globalSectionID = $GLOBALS['eZDesignKeys']['section'];
    unset($GLOBALS['eZDesignKeys']['section']);
}


//setting keys for override
$res = eZTemplateDesignResource::instance();

$Result = array();

// Fetch the navigation part from the section information
$Result['navigation_part'] = 'ezcontentnavigationpart';
if ( !isset( $nodeList ) )
{
    //include_once( 'kernel/classes/ezsection.php' );
    $section = eZSection::fetch( $object->attribute( 'section_id' ) );
    if ( $section )
    {
        $Result['navigation_part'] = $section->attribute( 'navigation_part_identifier' );
    }
    $Result['node_id'] = $node->attribute( 'node_id' );

    $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                          array( 'node', $node->attribute( 'node_id' ) ), // Node ID
                          array( 'parent_node', $node->attribute( 'parent_node_id' ) ), // Parent Node ID
                          array( 'class', $object->attribute( 'contentclass_id' ) ), // Class ID
                          array( 'depth', $node->attribute( 'depth' ) ),
                          array( 'url_alias', $node->attribute( 'url_alias' ) ),
                          array( 'class_identifier', $node->attribute( 'class_identifier' ) ),
                          array( 'section', $object->attribute('section_id') )
                          ) );

}

$res->setKeys( array( array( 'view_offset', $Offset ),
                      array( 'navigation_part_identifier', $Result['navigation_part'] )
                      ) );

//$Result['path'] = $path;
$Result['content'] = $tpl->fetch( 'design:content/browse.tpl' );

if (isset( $globalSectionID ))
{
    $GLOBALS['eZDesignKeys']['section'] = $globalSectionID;
}

$templatePath = $tpl->variable( 'path' );
if ( $templatePath )
{
    $Result['path'] = $templatePath;
}
elseif ( isset( $nodeList ) )
{
    $Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Search' ),
                                    'url' => false ) );
}
else
{
    $path = array();
    foreach ( $parents as $parent )
    {
        $path[] = array( 'text' => $parent->attribute( 'name' ),
                         'url' => '/content/browse/' . $parent->attribute( 'node_id' ) . '/'
                         );
    }
    $path[] = array( 'text' => $object->attribute( 'name' ),
                     'url' => false );
    $Result['path'] = $path;
}


?>
