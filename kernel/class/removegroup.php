<?php
//
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
include_once( "kernel/classes/ezcontentclassclassgroup.php" );

$Module =& $Params["Module"];
$http =& eZHTTPTool::instance();
$deleteIDArray = $http->sessionVariable( "DeleteGroupIDArray" );
$deleteResult = array();
$deleteClassIDList = array();
foreach ( $deleteIDArray as $deleteID )
{
    $deletedClassName = "";
    $group =& eZContentClassGroup::fetch( $deleteID );
    if( $group != null )
    {
        $GroupName = $group->attribute( 'name' );
        $classList =& eZContentClassClassGroup::fetchClassList( null, $deleteID );
        foreach ( $classList as $class )
        {
            $classID = $class->attribute( "id" );
            $classGroups =& eZContentClassClassGroup::fetchGroupList( $classID, 0);
            if ( count( $classGroups ) == 1 )
            {
                $classObject =& eZContentclass::fetch( $classID );
                $className = $classObject->attribute( "name" );
                $deletedClassName .= " '" . $className . "'" ;
                $deleteClassIDList[] = $classID;
            }
        }
        if ( $deletedClassName == "" )
            $deletedClassName = ezi18n( 'kernel/class', '(no classes)' );
        $item = array( "groupName" => $GroupName,
                       "deletedClassName" => $deletedClassName );
        $deleteResult[] = $item;
    }
}
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    foreach ( $deleteIDArray as $deleteID )
    {
        eZContentClassGroup::removeSelected( $deleteID );
        eZContentClassClassGroup::removeGroupMembers( $deleteID );
        foreach ( $deleteClassIDList as $deleteClassID )
        {
            $deleteClass =& eZContentClass::fetch( $deleteClassID );
            $deleteClass->remove( true );
            $deleteClass =& eZContentClass::fetch( $deleteClassID, true, 1 );
            $deleteClass->remove( true );
        }
    }
    $Module->redirectTo( '/class/grouplist/' );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/grouplist/' );
}
$Module->setTitle( ezi18n( 'kernel/class', 'Remove class groups' ) . ' ' . $GroupName );
include_once( "kernel/common/template.php" );
$tpl =& templateInit();


$tpl->setVariable( "module", $Module );
$tpl->setVariable( "DeleteResult", $deleteResult );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/removegroup.tpl" );
$Result['path'] = array( array( 'url' => '/class/removegroup/',
                                'text' => ezi18n( 'kernel/class', 'Remove class groups' ) ) );
?>
