<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();
$module = $Params['Module'];


$tpl = eZTemplate::factory();
$tpl->setVariable( "module_name", 'shop' );

$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );

$order = eZOrder::fetch( $orderID );
if ( !is_object( $order ) )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $order instanceof eZOrder )
{
    if ( $http->hasPostVariable( "ConfirmOrderButton" ) )
    {
        $order->detachProductCollection();
        $ini = eZINI::instance();
        if ( $ini->variable( 'ShopSettings', 'ClearBasketOnCheckout' ) == 'enabled' )
        {
            $basket = eZBasket::currentBasket();
            $basket->remove();
        }
        $module->redirectTo( '/shop/checkout/' );
        return;
    }

    if ( $http->hasPostVariable( "CancelButton" ) )
    {
        $order->purge( /*$removeCollection = */ false );
        $module->redirectTo( '/shop/basket/' );
        return;
    }

    $tpl->setVariable( "order", $order );
}

$basket = eZBasket::currentBasket();
$basket->updatePrices();

$operationResult = eZOperationHandler::execute( 'shop', 'confirmorder', array( 'order_id' => $order->attribute( 'id' ) ) );

switch( $operationResult['status'] )
{
    case eZModuleOperationInfo::STATUS_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            $order = eZOrder::fetch( $order->attribute( 'id' ) );
            $tpl->setVariable( "order", $order );

            $Result = array();
            $Result['content'] = $tpl->fetch( "design:shop/confirmorder.tpl" );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezpI18n::tr( 'kernel/shop', 'Confirm order' ) ) );
        }
    }break;

    case eZModuleOperationInfo::STATUS_HALTED:
    case eZModuleOperationInfo::STATUS_REPEAT:
    {
        if (  isset( $operationResult['redirect_url'] ) )
        {
            $module->redirectTo( $operationResult['redirect_url'] );
            return;
        }
        else if ( isset( $operationResult['result'] ) )
        {
            $result = $operationResult['result'];
            $resultContent = false;
            if ( is_array( $result ) )
            {
                if ( isset( $result['content'] ) )
                {
                    $resultContent = $result['content'];
                }
                if ( isset( $result['path'] ) )
                {
                    $Result['path'] = $result['path'];
                }
            }
            else
            {
                $resultContent = $result;
            }
            $Result['content'] = $resultContent;
        }
    }break;
    case eZModuleOperationInfo::STATUS_CANCELLED:
    {
        $Result = array();
        if ( isset( $operationResult['result']['content'] ) )
            $Result['content'] = $operationResult['result']['content'];
        else
            $Result['content'] = ezpI18n::tr( 'kernel/shop', "The confirm order operation was canceled. Try to checkout again." );

        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezpI18n::tr( 'kernel/shop', 'Confirm order' ) ) );
    }

}

/*
$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/confirmorder.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/shop', 'Confirm order' ) ) );
*/
?>
