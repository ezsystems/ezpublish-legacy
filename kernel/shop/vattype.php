<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
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

include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezvattype.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];

$http =& eZHttpTool::instance();

$vatTypeArray =& eZVatType::fetchList();

if ( $http->hasPostVariable( "AddVatTypeButton" ) )
{
    $vatType =& eZVatType::create();
    $vatType->store();
    $module->redirectTo( $module->functionURI( "vattype" ) . "/" );
    return;
}

if ( $http->hasPostVariable( "SaveVatTypeButton" ) )
{
    foreach ( $vatTypeArray as $vatType )
    {
        $id = $vatType->attribute( 'id' );
        if ( $http->hasPostVariable( "vattype_name_" . $id ) )
        {
            $name = $http->postVariable( "vattype_name_" . $id );
        }
        if ( $http->hasPostVariable( "vattype_percentage_" . $id ) )
        {
            $percentage = $http->postVariable( "vattype_percentage_" . $id );
        }
        $vatType->setAttribute( 'name', $name );
        $vatType->setAttribute( 'percentage', $percentage );
        $vatType->store();
    }
    $module->redirectTo( $module->functionURI( "vattype" ) . "/" );
    return;
}

if ( $http->hasPostVariable( "RemoveVatTypeButton" ) )
{
    $vatTypeIDList = $http->postVariable( "vatTypeIDList" );

    foreach ( $vatTypeIDList as $vatTypeID )
    {
        eZVatType::remove( $vatTypeID );
    }
    $module->redirectTo( $module->functionURI( "vattype" ) . "/" );
    return;
}

$tpl =& templateInit();
$tpl->setVariable( "vattype_array", $vatTypeArray );
$tpl->setVariable( "module", $module );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'VAT types' ),
                 'url' => false );


$Result = array();
$Result['path'] =& $path;
$Result['content'] =& $tpl->fetch( "design:shop/vattype.tpl" );

?>
