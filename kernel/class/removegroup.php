<?php
//
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
include_once( "kernel/classes/ezcontentclassclassgroup.php" );

$Module =& $Params["Module"];

$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID =& $Params["GroupID"];

$group =& eZContentClassGroup::fetch( $GroupID );
if( $group != null )
    $GroupName = $group->attribute( 'name' );

$classList =& eZContentClassClassGroup::fetchClassList( null, $GroupID );

$ClassCount = 0;
$deleteClassIDList = array();
foreach ( $classList as $class )
{
    $classID = $class->attribute( "contentclass_id" );
    $classGroups =& eZContentClassClassGroup::fetchGroupList( $classID, 0);
    if ( count( $classGroups ) == 1 )
    {
        $ClassCount++;
        $deleteClassIDList[] = $classID;
    }
}
if ( $ClassCount <= 1 )
    $ClassCount .= " class";
else
    $ClassCount .= " classes";
$http =& eZHTTPTool::instance();
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    eZContentClassGroup::removeSelected( $GroupID );
    eZContentClassClassGroup::removeGroupMembers( $GroupID );
    foreach ( $deleteClassIDList as $deleteClassID )
    {
        $deleteClass =& eZContentClass::fetch( $deleteClassID );
        $deleteClass->remove( true );
        $deleteClass =& eZContentClass::fetch( $deleteClassID, true, 1 );
        $deleteClass->remove( true );
    }
    $Module->redirectTo( '/class/grouplist/' );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/grouplist/' );
}
$Module->setTitle( "Remove group " .$GroupName );
include_once( "kernel/common/template.php" );
$tpl =& templateInit();


$tpl->setVariable( "module", $Module );
$tpl->setVariable( "GroupID", $GroupID );
$tpl->setVariable( "GroupName", $GroupName );
$tpl->setVariable( "ClassCount", $ClassCount );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/removegroup.tpl" );
$Result['path'] = array( array( 'url' => '/class/removegroup/',
                                'text' => 'Remove group' ) );
?>
