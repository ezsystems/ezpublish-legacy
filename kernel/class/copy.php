<?php
//
// Created on: <08-Jan-2003 16:36:23 amos>
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
include_once( "kernel/classes/ezcontentclassclassgroup.php" );

$Module =& $Params["Module"];
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$class =& eZContentClass::fetch( $ClassID, true, 0 );
if ( !$class )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE );

$classCopy =& $class->clone();
$classCopy->initializeCopy( $class );
$classCopy->setAttribute( 'version', 1 );
$classCopy->store();

$mainGroupID = false;
$classGroups =& eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
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
$classAttributes =& $class->fetchAttributes();
foreach ( array_keys( $classAttributes ) as $classAttributeKey )
{
    $classAttribute =& $classAttributes[$classAttributeKey];
    $classAttributeCopy =& $classAttribute->clone();
    $classAttributeCopy->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classAttributeCopy->setAttribute( 'version', 1 );
    $classAttributeCopy->store();
    $datatype =& $classAttributeCopy->dataType();
    $datatype->cloneClassAttribute( $classAttribute, $classAttributeCopy );
    $classAttributeCopies[] =& $classAttributeCopy;
}

$ini =& eZINI::instance( 'content.ini' );
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
