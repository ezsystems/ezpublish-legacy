<?php
//
// Definition of Trash class
//
// Created on: <28-Jan-2003 13:19:47 sp>
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

/*! \file trash.php
*/

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezcontentobject.php' );

$Module =& $Params['Module'];
$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$http =& eZHTTPTool::instance();

$user =& eZUser::currentUser();
$userID = $user->id();

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $access = $user->hasAccessTo( 'content', 'cleantrash' );
        if ( $access['accessWord'] == 'yes' )
        {
            $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
            foreach ( $deleteIDArray as $deleteID )
            {
                eZDebug::writeNotice( $deleteID, "deleteID" );
                $object =& eZContentObject::fetch( $deleteID );
                $object->purge();
            }
        }
        else
        {

            return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
        }
    }
}

if ( $http->hasPostVariable( 'EmptyButton' )  )
{
    $access = $user->hasAccessTo( 'content', 'cleantrash' );
    if ( $access['accessWord'] == 'yes' )
    {
        $objectList =& eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                             null,
                                             array( 'status' => EZ_CONTENT_OBJECT_STATUS_ARCHIVED ),
                                             null,
                                             null,
                                             true );
        foreach ( array_keys( $objectList ) as $key )
        {
            $object =& $objectList[$key];
            $object->purge();
        }
    }
    else
    {
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

$tpl =& templateInit();
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/trash.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Trash' ),
                                'url' => false ) );


?>
