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

include_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'extension/ezoe/classes/ezajaxcontent.php' );

$objectID      = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$tagName       = isset( $Params['TagName'] ) ? strtolower( trim( $Params['TagName'] )) : '';
$customTagName = isset( $Params['CustomTagName'] ) ? strtolower( trim( $Params['CustomTagName'] )) : '';



if ( $objectID === 0  || $objectVersion === 0 )
{
   echo "Missing Object ID or Object version";
   eZExecution::cleanExit();
}

$object = eZContentObject::fetch( $objectID );

if ( !$object )
{
   echo "Object fetch returned false! &nbsp; ObjectId: " . $objectID;
   eZExecution::cleanExit();
}


$templateName = '';


switch ( $tagName )
{
    case 'strong':
    case 'emphasize':
    case 'literal':
    case 'li':
    case 'ol':
    case 'ul':
    case 'tr':
    case 'paragraph':
        $templateName = 'tag_general.tpl';
        break;
    case 'header':
        $templateName = 'tag_header.tpl';
        break;
    case 'custom':
        $templateName = 'tag_custom.tpl';
        break;
    case 'link':
        $templateName = 'tag_link.tpl';
        break;
    case 'anchor':
        $templateName = 'tag_anchor.tpl';
        break;
    case 'table':
        $templateName = 'tag_table.tpl';
        break;
    case 'th':
    case 'td':
        $templateName = 'tag_table_cell.tpl';
        break;
}


if ( !$templateName )
{
   echo 'Tag name not supported: "' . $tagName . '"';
   eZExecution::cleanExit();
}



// class list with description
$classList  = array();
$customInlineList = array();
$contentIni = eZINI::instance( 'content.ini' );

if ( $tagName === 'custom' )
{
    if ( $contentIni->hasVariable( 'CustomTagSettings', 'CustomTagsDescription' ) )
        $customTagDescription = $contentIni->variable( 'CustomTagSettings', 'CustomTagsDescription' );
    else
        $customTagDescription = array();
        
    if ( $contentIni->hasVariable( 'CustomTagSettings', 'IsInline' ) )
        $customInlineList = $contentIni->variable( 'CustomTagSettings', 'IsInline' );

    foreach( $contentIni->variable( 'CustomTagSettings', 'AvailableCustomTags' ) as $tag )
    {
        if ( isset( $customTagDescription[$tag] ) )
            $classList[$tag] = $customTagDescription[$tag];
        else
            $classList[$tag] = $tag;
    }
}
else
{
    if ( $contentIni->hasVariable( $tagName, 'ClassDescription' ) )
        $classListDescription = $contentIni->variable( $tagName, 'ClassDescription' );
    else
        $classListDescription = array();

    $classList['0'] = 'None';
    if ( $contentIni->hasVariable( $tagName, 'AvailableClasses' ) )
    {
        foreach ( $contentIni->variable( $tagName, 'AvailableClasses' ) as $class )
        {
            if ( isset( $classListDescription[$class] ) )
                $classList[$class] = $classListDescription[$class];
            else
                $classList[$class] = $class;
        }
    }
}

$tpl = templateInit();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );

$tpl->setVariable( 'tag_name', $tagName );
$tpl->setVariable( 'custom_tag_name', $customTagName );

$tpl->setVariable( 'custom_inline_tags', $customInlineList );

$tpl->setVariable( 'class_list', $classList );

$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/' . $templateName );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );
return $Result;


//eZExecution::cleanExit();
//$GLOBALS['show_page_layout']

?>