<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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


include_once( "kernel/classes/ezcontentclass.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );

$Module =& $Params["Module"];
$GroupID = false;
if ( isset( $Params["GroupID"] ) )
    $GroupID =& $Params["GroupID"];

$http =& eZHTTPTool::instance();
$http->setSessionVariable( 'FromGroupID', $GroupID );
if ( $http->hasPostVariable( "RemoveButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteClassIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removeclass' ) . '/'  . $GroupID . '/' );
        }
    }
}

if ( $http->hasPostVariable( "NewButton" ) )
{
    if ( $http->hasPostVariable( "CurrentGroupID" ) )
        $GroupID = $http->postVariable( "CurrentGroupID" );
    if ( $http->hasPostVariable( "CurrentGroupName" ) )
        $GroupName = $http->postVariable( "CurrentGroupName" );

    $params = array( null, $GroupID, $GroupName );
    $Module->run( "edit", $params );
    return;
}

if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groupclasses",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "groupclass_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezi18n( 'kernel/class', 'Class list of group' ) . ' ' . $GroupID );
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    //$data = $tpldata["data"];
    //$asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    //$sort = isset( $data["sort"] ) ? $data["sort"] : null;
    //$fields = isset( $data["fields"] ) ? $data["fields"] : null;
    //$base = $tpldata["http_base"];
    $list = & eZContentClassClassGroup::fetchClassList( 0, $GroupID, $asObject = true );
    $tpl->setVariable( $tplname, $list );
    $tpl->setVariable( "class_count", count( $list ) );
    $tpl->setVariable( "GroupID", $GroupID );
    $groupInfo = & eZContentClassGroup::fetch( $GroupID );
    $GroupName = '';
    if ( $groupInfo !== null )
        $GroupName = $groupInfo->attribute("name");
    $tpl->setVariable( "group_name", $GroupName );
}

$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/classlist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezi18n( 'kernel/class', 'Class group list' ) ),
                         array( 'url' => false,
                                'text' => $GroupName ) );
?>
