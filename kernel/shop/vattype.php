<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezvattype.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
$http =& eZHttpTool::instance();
$tpl =& templateInit();
$errors = false;

function applyChanges( $module, $http, &$errors, $vatTypeArray = false )
{
    if ( $vatTypeArray === false )
        $vatTypeArray = eZVatType::fetchList( true, true );

    $db =& eZDB::instance();
    $db->begin();
    foreach ( $vatTypeArray as $vatType )
    {
        $id = $vatType->attribute( 'id' );

        if ( $id == -1 ) // avoid storing changes to the "fake" dynamic VAT type
            continue;

        if ( $http->hasPostVariable( "vattype_name_" . $id ) )
        {
            $name = $http->postVariable( "vattype_name_" . $id );
        }
        if ( $http->hasPostVariable( "vattype_percentage_" . $id ) )
        {
            $percentage = $http->postVariable( "vattype_percentage_" . $id );
        }

        if ( !$name || $percentage < 0 || $percentage > 100 )
        {
            if ( !$name )
                $errors[] = ezi18n( 'kernel/shop/vattype', 'Empty VAT type names are not allowed (corrected).' );
            else
                $errors[] = ezi18n( 'kernel/shop/vattype', 'Wrong VAT percentage (corrected).' );

            continue;
        }

        $vatType->setAttribute( 'name', $name );
        $vatType->setAttribute( 'percentage', $percentage );
        $vatType->store();
    }
    $db->commit();
}

/**
 * Generate a unique VAT type name.
 *
 * The generated name looks like "VAT type X"
 * where X is a unique number.
 */
function generateUniqueVatTypeName( $vatTypes )
{
    $commonPart = ezi18n( 'kernel/shop', 'VAT type' );
    $maxNumber = 0;
    foreach ( $vatTypes as $type )
    {
        $typeName = $type->attribute( 'name' );

        if ( !preg_match( "/^$commonPart (\d+)/", $typeName, $matches ) )
            continue;

        $curNumber = $matches[1];
        if ( $curNumber > $maxNumber )
            $maxNumber = $curNumber;
    }

    $maxNumber++;
    return "$commonPart $maxNumber";
}


if ( $http->hasPostVariable( "AddVatTypeButton" ) )
{
    $vatTypeArray = eZVatType::fetchList( true, true );
    applyChanges( $module, $http, $errors, $vatTypeArray );

    $vatType = eZVatType::create();
    $vatType->setAttribute( 'name', generateUniqueVatTypeName( $vatTypeArray ) );
    $vatType->store();
    $tpl->setVariable( 'last_added_id', $vatType->attribute( 'id' ) );
}
elseif ( $http->hasPostVariable( "SaveVatTypeButton" ) )
{
    applyChanges( $module, $http, $errors );
}
elseif ( $http->hasPostVariable( "RemoveVatTypeButton" ) )
{
    applyChanges( $module, $http, $errors );

    $vatTypeIDList = $http->postVariable( "vatTypeIDList" );

    $db =& eZDB::instance();
    $db->begin();
    foreach ( $vatTypeIDList as $vatTypeID )
    {
        eZVatType::remove( $vatTypeID );
    }
    $db->commit();
}

$vatTypeArray = eZVatType::fetchList( true, true );

if ( is_array( $errors ) )
    $errors = array_unique( $errors );

$tpl->setVariable( "vattype_array", $vatTypeArray );
$tpl->setVariable( "module", $module );
$tpl->setVariable( 'errors', $errors );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'VAT types' ),
                 'url' => false );


$Result = array();
$Result['path'] =& $path;
$Result['content'] =& $tpl->fetch( "design:shop/vattype.tpl" );

?>
