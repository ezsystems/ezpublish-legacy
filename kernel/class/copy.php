<?php
//
// Created on: <08-Jan-2003 16:36:23 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$class = eZContentClass::fetch( $ClassID, true, 0 );
if ( !$class )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE );

$classCopy = clone $class;
$classCopy->initializeCopy( $class );
$classCopy->setAttribute( 'version', 1 );
$classCopy->store();

$mainGroupID = false;
$classGroups = eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
                                                          $class->attribute( 'version' ) );
for ( $i = 0; $i < count( $classGroups ); ++$i )
{
    $classGroup =& $classGroups[$i];
    $classGroup->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classGroup->setAttribute( 'contentclass_version', $classCopy->attribute( 'version' ) );
    $classGroup->store();
    if ( $mainGroupID === false )
        $mainGroupID = $classGroup->attribute( 'group_id' );
}

$classAttributeCopies = array();
$classAttributes = $class->fetchAttributes();
foreach ( array_keys( $classAttributes ) as $classAttributeKey )
{
    $classAttribute =& $classAttributes[$classAttributeKey];
    $classAttributeCopy = clone $classAttribute;

    if ( $datatype = $classAttributeCopy->dataType() ) //avoiding fatal error if datatype not exist (was removed).
    {
        $datatype->cloneClassAttribute( $classAttribute, $classAttributeCopy );
    }
    else
    {
        continue;
    }

    $classAttributeCopy->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classAttributeCopy->setAttribute( 'version', 1 );
    $classAttributeCopy->store();
    $classAttributeCopies[] =& $classAttributeCopy;
    unset( $classAttributeCopy );
}

$ini = eZINI::instance( 'content.ini' );
$classRedirect = strtolower( trim( $ini->variable( 'CopySettings', 'ClassRedirect' ) ) );

switch ( $classRedirect )
{
    case 'grouplist':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'grouplist', array() );
    } break;

    case 'classlist':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'classlist', array( $mainGroupID ) );
    } break;

    case 'classview':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'view', array( $classCopy->attribute( 'id' ) ) );
    } break;

    default:
    {
        eZDebug::writeWarning( "Invalid ClassRedirect value '$classRedirect', use one of: grouplist, classlist, classedit or classview" );
    }

    case 'classedit':
    {
        return $Module->redirectToView( 'edit', array( $classCopy->attribute( 'id' ) ) );
    } break;
}

?>
