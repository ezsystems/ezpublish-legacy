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
include_once( "kernel/classes/ezcontentclassgroup.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$Module =& $Params["Module"];

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( "RemoveGroupButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
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
        $group =& eZContentClassGroup::fetch( $groupID );
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
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    $data = $tpldata["data"];
    $asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    $base = $tpldata["http_base"];
    unset( $list );
    $list =& eZContentClassGroup::fetchList( false, $asObject );
    $tpl->setVariable( $tplname, $list );
}

$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/grouplist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezi18n( 'kernel/class', 'Classes' ) ) );

?>
