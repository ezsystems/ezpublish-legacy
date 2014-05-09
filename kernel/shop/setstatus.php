<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$user = eZUser::currentUser();

$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
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
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

?>
