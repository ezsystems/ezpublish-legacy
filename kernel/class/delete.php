<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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


//include_once( "kernel/classes/ezcontentclass.php" );
//include_once( "lib/ezutils/classes/ezhttppersistence.php" );
//include_once( "kernel/classes/ezcontentclassclassgroup.php" );

$Module = $Params['Module'];
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];

$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

$class = eZContentClass::fetch( $ClassID );
$ClassName = $class->attribute( 'name' );
$classObjects = eZContentObject::fetchSameClassList( $ClassID );
$ClassObjectsCount = count( $classObjects );
if ( $ClassObjectsCount == 0 )
    $ClassObjectsCount .= " object";
else
    $ClassObjectsCount .= " objects";
$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    $class->remove( true );
    eZContentClassClassGroup::removeClassMembers( $ClassID, 0 );
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
$Module->setTitle( "Deletion of class " .$ClassID );
require_once( "kernel/common/template.php" );
$tpl = templateInit();


$tpl->setVariable( "module", $Module );
$tpl->setVariable( "GroupID", $GroupID );
$tpl->setVariable( "ClassID", $ClassID );
$tpl->setVariable( "ClassName", $ClassName );
$tpl->setVariable( "ClassObjectsCount", $ClassObjectsCount );
$Result = array();
$Result['content'] = $tpl->fetch( "design:class/delete.tpl" );
$Result['path'] = array( array( 'url' => '/class/delete/',
                                'text' => ezi18n( 'kernel/class', 'Remove class' ) ) );
?>
