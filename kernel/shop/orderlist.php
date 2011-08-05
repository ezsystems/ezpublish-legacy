<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];

$tpl = eZTemplate::factory();

$offset = $Params['Offset'];
$limit = 15;


if( eZPreferences::value( 'admin_orderlist_sortfield' ) )
{
    $sortField = eZPreferences::value( 'admin_orderlist_sortfield' );
}

if ( !isset( $sortField ) || ( ( $sortField != 'created' ) && ( $sortField!= 'user_name' ) ) )
{
    $sortField = 'created';
}

if( eZPreferences::value( 'admin_orderlist_sortorder' ) )
{
    $sortOrder = eZPreferences::value( 'admin_orderlist_sortorder' );
}

if ( !isset( $sortOrder ) || ( ( $sortOrder != 'asc' ) && ( $sortOrder!= 'desc' ) ) )
{
    $sortOrder = 'asc';
}

$http = eZHTTPTool::instance();

// The RemoveButton is not present in the orderlist, but is here for backwards
// compatibility. Simply replace the ArchiveButton for the RemoveButton will
// do the trick.
//
// Note that removing order can cause wrong order numbers (order_nr are
// reused).  See eZOrder::activate.
if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    if ( $http->hasPostVariable( 'OrderIDArray' ) )
    {
        $orderIDArray = $http->postVariable( 'OrderIDArray' );
        if ( $orderIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteOrderIDArray', $orderIDArray );
            $Module->redirectTo( $Module->functionURI( 'removeorder' ) . '/' );
        }
    }
}

// Archive options.
if ( $http->hasPostVariable( 'ArchiveButton' ) )
{
    if ( $http->hasPostVariable( 'OrderIDArray' ) )
    {
        $orderIDArray = $http->postVariable( 'OrderIDArray' );
        if ( $orderIDArray !== null )
        {
            $http->setSessionVariable( 'OrderIDArray', $orderIDArray );
            $Module->redirectTo( $Module->functionURI( 'archiveorder' ) . '/' );
        }
    }
}

if ( $http->hasPostVariable( 'SaveOrderStatusButton' ) )
{
    if ( $http->hasPostVariable( 'StatusList' ) )
    {
        foreach ( $http->postVariable( 'StatusList' ) as $orderID => $statusID )
        {
            $order = eZOrder::fetch( $orderID );
            $access = $order->canModifyStatus( $statusID );
            if ( $access and $order->attribute( 'status_id' ) != $statusID )
            {
                $order->modifyStatus( $statusID );
            }
        }
    }
}

$orderArray = eZOrder::active( true, $offset, $limit, $sortField, $sortOrder );
$orderCount = eZOrder::activeCount();

$tpl->setVariable( 'order_list', $orderArray );
$tpl->setVariable( 'order_list_count', $orderCount );
$tpl->setVariable( 'limit', $limit );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'sort_field', $sortField );
$tpl->setVariable( 'sort_order', $sortOrder );

$Result = array();
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( 'design:shop/orderlist.tpl' );
?>
