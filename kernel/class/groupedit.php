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
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclassgroup.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$Module =& $Params["Module"];
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

if ( is_numeric( $GroupID ) )
{
    $classgroup =& eZContentClassGroup::fetch( $GroupID );
}
else
{
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $classgroup =& eZContentClassGroup::create( $user_id );
    $classgroup->setAttribute( "name", ezi18n( 'kernel/class/groupedit', "New Group" ) );
    $classgroup->store();
    $GroupID = $classgroup->attribute( "id" );
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $GroupID );
    return;
}

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $existingClassList =& eZContentClassClassGroup::fetchClassList( 0, $GroupID );
    if ( count( $existingClassList ) == 0 )
    {
        eZContentClassGroup::removeSelected( $GroupID );
    }
    $Module->redirectTo( $Module->functionURI( "grouplist" ) );
    return;
}

if ( $http->hasPostVariable( "StoreButton" ) )
{
    if ( $http->hasPostVariable( "Group_name" ) )
    {
        $name = $http->postVariable( "Group_name" );
    }
    $classgroup->setAttribute( "name", $name );
    // Set new modification date
    $date_time = time();
    $classgroup->setAttribute( "modified", $date_time );
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $classgroup->setAttribute( "modifier_id", $user_id );
    $classgroup->store();
    $Module->redirectToView( 'classlist', array( $classgroup->attribute( 'id' ) ) );
    return;
}

$Module->setTitle( "Edit class group " . $classgroup->attribute( "name" ) );

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "classgroup", $classgroup->attribute( "id" ) ) ) );

$tpl->setVariable( "http", $http );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "classgroup", $classgroup );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/groupedit.tpl" );

?>
