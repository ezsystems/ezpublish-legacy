<?php
//
// Created on: <30-Jul-2007 00:00:00 ar>
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


$Module = array( "name" => 'eZtinymce Module and views for the poor and week!' );

$ViewList = array();
$ViewList["relations"] = array(
    "ui_context" => 'edit',
    "script" => 'relations.php',
    "params" => array( 'ObjectID', 'ObjectVersion', 'ContentType', 'EmbedID', 'EmbedInline', 'EmbedSize' )
    );

$ViewList["upload"] = array(
    "ui_context" => 'edit',
    "script" => 'upload.php',
    "params" => array( 'ObjectID', 'ObjectVersion', 'ContentType', 'ForcedUpload' )
    );

$ViewList["tags"] = array(
    "ui_context" => 'edit',
    "script" => 'tags.php',
    "params" => array( 'ObjectID', 'ObjectVersion', 'TagName', 'CustomTagName' )
    );
    
$ViewList["load"] = array(
    "script" => 'load.php',
    "params" => array( 'EmbedID','DataMap', 'ImagePreGenerateSizes' )
    );

$ViewList["embed_view"] = array(
    "script" => 'embed_view.php',
    "params" => array( 'EmbedID' )
    );
    
$ViewList["search"] = array(
    "script" => "search.php",
    'params' => array( 'SearchStr', 'SearchOffset', 'SearchLimit', 'VarName')
    );

$ViewList["expand"] = array(
    "script" => "expand.php",
    'params' => array('NodeID', 'Offset', 'Limit')
    );
    
$FunctionList = array();
$FunctionList['relations'] = array();
$FunctionList['search'] = array();
$FunctionList['expand'] = array();

?>