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


include_once( "kernel/classes/ezcontentclass.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

function &removeSelectedClasses( &$http, &$classes, $base )
{
    if ( $http->hasPostVariable( "DeleteButton" ) )
    {
        if ( eZHttpPersistence::splitSelected( $base,
                                               $classes, $http, "id",
                                               $keepers, $rejects ) )
        {
            $classes = $keepers;
            for ( $i = 0; $i < count( $rejects ); ++$i )
            {
                $reject =& $rejects[$i];
                $reject->remove( true );
            }
        }
    }
}

$Module =& $Params["Module"];

$http =& eZHttpTool::instance();

if ( $http->hasPostVariable( "NewButton" ) )
{
//     $Module->redirectTo( $Module->functionURI( "edit" ) );
    $params = array();
    $Module->run( "edit", $params );
    return;
}

if ( file_exists( "design/standard/prepare/class/list.php" ) )
{
    include( "design/standard/prepare/class/list.php" );
}
if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array(
                               "name" => "classes",
                               "http_base" => "ContentClass",
                               "data" => array( "command" => "mixed_list",
                                                "type" => "class" ) ) );
}

$Module->setTitle( ezi18n( 'kernel/class', 'Class list' ) );

include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$list_versions = array( "mixed_list" => false,
                        "defined_list" => 0,
                        "temporary_list" => 1 );

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
$user =& eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];
    $data = $tpldata["data"];
    $asObject = isset( $data["as_object"] ) ? $data["as_object"] : true;
    $sort = isset( $data["sort"] ) ? $data["sort"] : null;
    $fields = isset( $data["fields"] ) ? $data["fields"] : null;
    $base = $tpldata["http_base"];
    if ( $data["type"] == "class" )
    {
        switch( $data["command"] )
        {
            case "mixed_list":
            case "temporary_list":
            case "defined_list":
            {
                unset( $list );
//                 $list =& eZContentClass::fetchList( $list_versions[$data["command"]],
//                                                     $asObject, $user->attribute( "id" ),
//                                                     $sort, $fields );
                $list =& eZContentClass::fetchList( $list_versions[$data["command"]],
                                                    $asObject, false,
                                                    $sort, $fields );
                removeSelectedClasses( $http, $list, $base );
                $tpl->setVariable( $tplname, $list );
            } break;
            default:
                eZDebug::writeError( "Unknown data command: " . $data["command"],
                                     "Class::list" );
        }
    }
}

// $tpl->setVariable( "temp_classes", array() );
$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/list.tpl" );

?>
