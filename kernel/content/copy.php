<?php
//
// Created on: <17-Jan-2003 12:47:11 amos>
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

$Module =& $Params['Module'];
$ObjectID =& $Params['ObjectID'];

include_once( 'kernel/classes/ezcontentobject.php' );

$object =& eZContentObject::fetch( $ObjectID );

if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_read' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $mainParentNodeID = $object->attribute( 'main_parent_node_id' );
    return $Module->redirectToView( 'view', array( 'full', $mainParentNodeID ) );
}

$contentINI =& eZINI::instance( 'content.ini' );

function &copyObject( &$object, $allVersions )
{
    return $object->copy( $allVersions );
}

$Result = array();

$versionHandling = $contentINI->variable( 'CopySettings', 'VersionHandling' );
if ( $versionHandling == 'user-defined' )
{
    if ( $Module->isCurrentAction( 'Copy' ) )
    {
        $allVersions = false;
        if ( $Module->actionParameter( 'VersionChoice' ) == 1 )
            $allVersions = true;
        $newObject =& copyObject( $object, $allVersions );
        return $Module->redirectToView( 'edit', array( $newObject->attribute( 'id' ), $newObject->attribute( 'current_version' ) ) );
    }
    else
    {
        include_once( 'kernel/common/template.php' );
        $tpl =& templateInit();
        $tpl->setVariable( 'object', $object );
        $Result['content'] = $tpl->fetch( 'design:content/copy.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'kernel/content', 'Content' ) ),
                                 array( 'url' => false,
                                        'text' => ezi18n( 'kernel/content', 'Copy' ) ) );
    }
}
else if ( $versionHandling == 'last-published' )
{
    $newObject =& copyObject( $object, false );
    return $Module->redirectToView( 'edit', array( $newObject->attribute( 'id' ), $newObject->attribute( 'current_version' ) ) );
}
else
{
    $newObject =& copyObject( $object, true );
    return $Module->redirectToView( 'edit', array( $newObject->attribute( 'id' ), $newObject->attribute( 'current_version' ) ) );
}

?>
