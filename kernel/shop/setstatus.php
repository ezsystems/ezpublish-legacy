<?php
//
// Created on: <08-Mar-2005 18:16:54 jhe>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezorder.php" );
include_once( "kernel/classes/ezorderstatus.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );

$module =& $Params["Module"];
$http =& eZHttpTool::instance();
$user =& eZUser::currentUser();

$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $http->hasPostVariable( "OrderID" ) && $http->hasPostVariable( "StatusID" ) && $http->hasPostVariable( "SetOrderStatusButton" ) )
{
    $access = $order->canModifyStatus( $StatusID );

    if ( $access )
    {
        if ( $order->attribute( 'status_id' ) != $StatusID )
        {
            $order->modifyStatus( $StatusID );
        }

        if ( $http->hasPostVariable( 'RedirectURI' ) )
        {
            $uri = $http->postVariable( 'RedirectURI' );
            $module->redirectTo( $uri );
            return;
        }
        else
        {
            $module->redirectTo( '/shop/orderview/' . $orderID );
            return;
        }
    }
    else
    {
        return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

?>
