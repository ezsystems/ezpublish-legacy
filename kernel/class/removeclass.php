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
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID =& $Params["GroupID"];
$http =& eZHTTPTool::instance();
$deleteIDArray = $http->sessionVariable( "DeleteClassIDArray" );
$DeleteResult = array();
foreach ( $deleteIDArray as $deleteID )
{
    $ClassObjectsCount = 0;
    $class =& eZContentClass::fetch( $deleteID );
    if( $class != null )
    {
        $ClassID = $class->attribute( 'id' );
        $ClassName = $class->attribute( 'name' );
        $classObjects =&  eZContentObject::fetchSameClassList( $ClassID );
        $ClassObjectsCount = count( $classObjects );
        if ( $ClassObjectsCount == 1 )
            $ClassObjectsCount .= ezi18n( 'kernel/class', ' object' );
        else
            $ClassObjectsCount .= ezi18n( 'kernel/class', ' objects' );
        $item = array( "className" => $ClassName,
                       "objectCount" => $ClassObjectsCount );
        $DeleteResult[] = $item;
    }
}

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    foreach ( $deleteIDArray as $deleteID )
    {
        eZContentClassClassGroup::removeClassMembers( $ClassID, 0 );
        eZContentClassClassGroup::removeClassMembers( $ClassID, 1 );

        // Fetch real version and remove it
        $deleteClass =& eZContentClass::fetch( $deleteID );
        if ( $deleteClass != null )
            $deleteClass->remove( true );

        // Fetch temp version and remove it
        $tempDeleteClass =& eZContentClass::fetch( $deleteID, true, 1 );
        if ( $tempDeleteClass != null )
            $tempDeleteClass->remove( true, 1 );
    }
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
$Module->setTitle( ezi18n( 'kernel/class', 'Remove classes' ) . ' ' . $ClassID );
include_once( "kernel/common/template.php" );
$tpl =& templateInit();


$tpl->setVariable( "module", $Module );
$tpl->setVariable( "GroupID", $GroupID );
$tpl->setVariable( "DeleteResult", $DeleteResult );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/removeclass.tpl" );
$Result['path'] = array( array( 'url' => '/class/removeclass/',
                                'text' => ezi18n( 'kernel/class', 'Remove classes' ) ) );
?>
