<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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


$Module = $Params['Module'];

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "RemoveGroupButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteGroupIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removegroup' ) . '/' );
        }
    }
}

if ( $http->hasPostVariable( "EditGroupButton" ) && $http->hasPostVariable( "EditGroupID" ) )
{
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $http->postVariable( "EditGroupID" ) );
    return;
}

if ( $http->hasPostVariable( "NewGroupButton" ) )
{
    $params = array();
    $Module->run( "groupedit", $params );
    return;
}

if ( $http->hasPostVariable( "NewClassButton" ) )
{
    if ( $http->hasPostVariable( "SelectedGroupID" ) )
    {
        $groupID = $http->postVariable( "SelectedGroupID" );
        $group = eZContentClassGroup::fetch( $groupID );
        $groupName = $group->attribute( 'name' );

        $params = array( null, $groupID, $groupName );
        return $Module->run( "edit", $params );
    }
}

if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groups",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "group_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezi18n( 'kernel/class', 'Class group list' ) );
require_once( "kernel/common/template.php" );
$tpl = templateInit();

$user = eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    $data = $tpldata["data"];
    $asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    $base = $tpldata["http_base"];
    unset( $list );
    $list = eZContentClassGroup::fetchList( false, $asObject );
    $tpl->setVariable( $tplname, $list );
}

$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:class/grouplist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezi18n( 'kernel/class', 'Classes' ) ) );

?>
