<?php
//
// Created on: <04-Jul-2002 13:06:30 bf>
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
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

if ( $http->hasPostVariable( 'NewButton' )  )
{
    if ( $http->hasPostVariable( 'ClassID' )  && $http->hasPostVariable( 'NodeID' ) )
    {
        $node = eZContentObjectTreeNode::fetch( $http->postVariable( 'NodeID' )  );
        $parentContentObject = $node->attribute( 'contentobject' );
        if ( $parentContentObject->checkAccess( 'create', $http->postVariable( 'ClassID' ) ) == '1' )
        {
            $contentObject =& eZContentObject::createNew( $http->postVariable( 'ClassID' ), $http->postVariable( 'NodeID' ) );

            $module->redirectTo( $module->functionURI( 'edit' ) . '/' . $contentObject->attribute( 'id' ) . '/' . $contentObject->attribute( 'current_version' ) );
            return;

        }else
        {
            $Module->redirectTo( '/error/403' );
            return;
        }

    }
}

if ( $http->hasPostVariable( 'EditButton' )  )
{
    if ( $http->hasPostVariable( 'ContentObjectID' ) )
    {
        $module->redirectTo( $module->functionURI( 'edit' ) . '/' . $http->postVariable( 'ContentObjectID' ) . '/' );
        return;
    }
}

if ( $http->hasPostVariable( "ContentObjectID" )  )
{
    $objectID = $http->postVariable( "ContentObjectID" );
    $action = $http->postVariable( "ContentObjectID" );

    // Check which action to perform
    if ( $http->hasPostVariable( "ActionAddToCart" ) )
    {
        $shopModule =& eZModule::exists( array( "kernel" ), "shop" );

        $result =& $shopModule->run( "cart", array() );
        $module->setExitStatus( $shopModule->exitStatus() );
        $module->setRedirectURI( $shopModule->redirectURI() );
    }
    else if ( $http->hasPostVariable( "ActionAddToWishList" ) )
    {
        $shopModule =& eZModule::exists( array( "kernel" ), "shop" );

        $result =& $shopModule->run( "wishlist", array() );
        $module->setExitStatus( $shopModule->exitStatus() );
        $module->setRedirectURI( $shopModule->redirectURI() );
    }
    else
    {
        eZDebug::writeError( "Unknown content object action" );
    }
}

// return module contents
$Result =& $result;

?>
