<?php
//
// Created on: <5-Jul-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.0
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


/* For embed preview */
include_once( 'kernel/common/template.php' );

$embedId         = 0;
$http            = eZHTTPTool::instance();
$tplSuffix       = '';
$idString        = '';
$tagName         = 'embed';
$embedObject     = false;

if ( isset( $Params['EmbedID'] )  && $Params['EmbedID'])
{
    $embedType = 'ezobject';
    if (  is_numeric( $Params['EmbedID'] ) )
        $embedId = $Params['EmbedID'];
    else
        list($embedType, $embedId) = explode('_', $Params['EmbedID']);

    if ( strcasecmp( $embedType  , 'eznode'  ) === 0 )
    {
        $embedNode   = eZContentObjectTreeNode::fetch( $embedType );
        $embedObject = $node->object();
        $tplSuffix   = '_node'; 
        $idString    = 'eZNode_' . $embedId;
    }
    else
    {
        $embedObject = eZContentObject::fetch( $embedId );
        $idString    = 'eZObject_' . $embedId;
    }
}

if ( $embedObject instanceof eZContentObject )
{
    $objectName      = $embedObject->attribute( 'name' );
    $classID         = $embedObject->attribute( 'contentclass_id' );
    $classIdentifier = $embedObject->attribute( 'class_identifier' );
    if ( !$embedObject->attribute( 'can_read' ) || !$embedObject->attribute( 'can_view_embed' ) )
    {
        $tplSuffix = '_denied';
    }
}
else
{
    $objectName      = 'Unknown';
    $classID         = 0;
    $classIdentifier = false;
}

$className = '';
$size  = 'medium';
$view  = 'embed';
$align = 'right';
$style = 'text-align: left;';

if ( $http->hasPostVariable('inline') &&
     $http->postVariable('inline') === 'true' )
{
    $tagName = 'embed-inline';
}

if ( $http->hasPostVariable('class') )
{
    $className = $http->postVariable('class');
}

if ( $http->hasPostVariable('size') )
{
    $size = $http->postVariable('size');
}

if ( $http->hasPostVariable('view') )
{
    $view = $http->postVariable('view');
}

if ( $http->hasPostVariable('align') )
{
    $align = $http->postVariable('align');
}


if ( $align === 'left' || $align === 'right' )
    $style .= ' float: ' . $align . ';'; 


$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array('classification', $className) ) );

$tpl = templateInit();
$tpl->setVariable( 'view', $view );
$tpl->setVariable( 'object', $embedObject );
$tpl->setVariable( 'link_parameters', array() );
$tpl->setVariable( 'classification', $className );
$tpl->setVariable( 'object_parameters', array( 'size' => $size, 'align' => $align, 'show_path' => true ) );
if ( isset( $embedNode ) ) $tpl->setVariable( 'node', $embedNode );

if ( $style !== '' )
    $style = ' style="' . $style . '"';

$templateOutput = $tpl->fetch( 'design:content/datatype/view/ezxmltags/' . $tagName . $tplSuffix . '.tpl' );
echo '<div id="' . $idString . '" title="' . $objectName . '"' . $style . '>' . $templateOutput . '</div>';





eZExecution::cleanExit();

?>